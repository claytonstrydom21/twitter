<template>
    <div class="space-y-4">
        <div v-for="post in posts" :key="post.id" class="border-b border-gray-800 p-4">
            <div class="flex space-x-4">
                <avatar
                    :src="post.user.avatar_url"
                    :name="post.user.name"
                    size="md"
                />
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

                        <button
                            @click = "toggleComments(post.id)"
                            class="flex items-center space-x-1 text-gray-500 hover:text-blue-500"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            <span>{{ post.comments?.length || 0 }}</span>
                        </button>
                    </div>

                    <div v-if="expandedPosts.includes(post.id)" class="mt-4 space-y-4">
                        <comment-input
                            :post-id="Number(post.id)"
                            @comment-created="onCommentCreated(post.id, $event)"
                        />

                        <div v-for=" comment in post.comments" :key="comment.id" class="flex space-x-2">
                            <div class="w-8 h-8 rounded-full bg-gray-700"></div>
                            <div class="flex-grow ng-gray-800 rounded-lg p-2">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-white">@{{ comment.user.username }}</span>
                                    <button
                                        v-if="comment.user.id === currentUserId"
                                        @click="deleteComment(comment.id)"
                                        class="text-gray-500 hover:text-red-500"
                                    >
                                        <span class="text-xs">Delete</span>
                                    </button>
                                </div>
                                <p class="text-white">{{ comment.content }}</p>
                            </div>
                        </div>
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
import CommentInput from "../comments/CommentInput.vue";

export default {
    name: 'BaseFeed',
    data() {
        return {
            error: null,
            isLoading: false,
            currentUser: window.user,
            expandedPosts: []
        }
    },
    components: {
        CommentInput,
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
        toggleComments(postId) {
            const index = this.expandedPosts.indexOf(postId);
            if (index === -1) {
                this.expandedPosts.push(postId);
            } else {
                this.expandedPosts.splice(index, 1);
            }
        },
        onCommentCreated(postId, comment) {
            const post = this.posts.find(p => p.id === postId);
            if (post) {
                if (!post.comments) post.comments = [];
                post.comments.unshift(comment);
            }
        },
        async deleteComment(commentId) {
            try {
                const response = await fetch(`/comments/${commentId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (!response.ok) throw new Error('Failed to delete comment');

                this.posts.forEach(post => {
                    if (post.comments) {
                        post.comments = post.comments.filter(c => c.id !== commentId);
                    }
                });
            } catch (error) {
                console.error('Error deleting comment:', error);
            }
        }
    },
}
</script>
