<template>
    <div class="space-y-4">
        <div v-for="post in posts" :key="post.id" class="border-b border-gray-800 p-4">
            <div class="flex space-x-4">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 rounded-full bg-gray-700"></div>
                </div>
                <div class="flex-grow">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <span class="font-bold text-white">@{{ post.user.username }}</span>
                            <span class="text-gray-500">· {{ formatDate(post.created_at) }}</span>
                        </div>
                        <follow-button
                            :user-id="Number(post.user.id)"
                            :current-user-id="Number(currentUserId)"
                            :initial-is-following="post.user.is_following"
                        ></follow-button>
                    </div>
                    <p class="text-white mt-2">{{ post.content }}</p>
                    <img
                        v-if="post.image_url"
                        :src="post.image_url"
                        class="mt-3 rounded-lg max-h-96 w-full object-contain bg-gray-900"
                        @click="openImage(post.image_url)"
                    >
                    <div class="mt-3 flex items-center space-x-4">
                        <like-button
                            :post-id="post.id"
                            :initial-is-liked="post.is_liked"
                            :initial-likes-count="post.likes_count"
                        />
                    </div>
                </div>
            </div>
        </div>

        <div v-if="posts.length === 0" class="text-center py-8">
            <p class="text-gray-500">No posts yet.</p>
        </div>
    </div>
</template>

<script>
import FollowButton from "../FollowButton.vue";
import LikeButton from "../ui/LikeButton.vue";

export default {
    name: 'BaseFeed',
    data() {
        return {
            error: null,
            isLoading: false,
            currentUser: window.user,
        }
    },
    components: {
        FollowButton,
        LikeButton
    },
    props: {
        currentUserId: {
            type: Number,
            required: true
        },
        posts: {
            type: Array,
            default: () => []
        }
    },
    methods: {
        formatDate(date) {
            return new Date(date).toLocaleDateString()
        },
    },
}
</script>
