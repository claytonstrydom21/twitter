<template>
    <div>
        <div class="bg-gray-900 rounded-lg p-6 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <avatar
                        :src="user.avatar_url"
                        :name="user.name"
                        size="lg"
                    />
                    <div>
                        <h1 class="text-2xl font-bold text-white">{{ user.name }}</h1>
                        <p class="text-gray-500">@{{ user.username }}</p>
                    </div>
                </div>
                <div v-if="user.id !== currentUserId">
                    <follow-button
                        :user-id="user.id"
                        :current-user-id="currentUserId"
                        :initial-is-following="user.is_following"
                    />
                </div>
                <div v-else>
                    <button @click="editProfile" class="px-4 py-2 bg-blue-500 text-white rounded-full">
                        Edit Profile
                    </button>
                </div>
            </div>
            <div class="mt-6 flex space-x-8">
                <div class="flex items-center space-x-1">
                    <span class="text-white font-bold">{{ user.posts_count }}</span>
                    <span class="text-gray-500">Posts</span>
                </div>
                <div class="flex items-center space-x-1">
                    <span class="text-white font-bold">{{ user.followers_count }}</span>
                    <span class="text-gray-500">Followers</span>
                </div>
                <div class="flex items-center space-x-1">
                    <span class="text-white font-bold">{{ user.following_count }}</span>
                    <span class="text-gray-500">Following</span>
                </div>
            </div>
        </div>

        <base-feed :posts="posts" :current-user-id="currentUserId" />
    </div>
</template>

<script>
import BaseFeed from '../posts/BaseFeed.vue';
import FollowButton from '../FollowButton.vue';

export default {
    components: {
        BaseFeed,
        FollowButton
    },
    props: {
        user: {
            type: Object,
            required: true
        },
        posts: {
            type: Array,
            required: true
        },
        currentUserId: {
            type: Number,
            required: true
        }
    },
    methods: {
        editProfile() {
            window.location.href = '/profile/edit';
        }
    }
}
</script>
