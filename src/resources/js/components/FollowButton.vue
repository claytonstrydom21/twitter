<template>
    <button
        v-if="userId !== currentUserId"
        @click="toggleFollow"
        :class="[
            'px-4 py-1 rounded-full font-semibold text-sm',
            isFollowing
                ? 'bg-white text-black hover:bg-red-100 hover:text-red-600'
                : 'bg-blue-500 text-white hover:bg-blue-600'
        ]"
        :disabled="isLoading"
    >
        <span v-if="isLoading">Loading...</span>
        <span v-else>{{ isFollowing ? 'Unfollow' : 'Follow' }}</span>
    </button>
</template>

<script>
import followerService from "../services/followerService.js";

export default {
    name: 'FollowButton',
    props: {
        userId: {
            type: Number,
            required: true
        },
        currentUserId: {
            type: Number,
            required: true
        },
        initialIsFollowing: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            isFollowing: this.initialIsFollowing,
            isLoading: false,
            error: null
        }
    },
    methods: {
        async toggleFollow() {
            this.isLoading = true;
            this.error = null;
            try {
                if (this.isFollowing) {
                    await followerService.unfollowUser(this.userId);
                } else {
                    await followerService.followUser(this.userId);
                }
                this.isFollowing = !this.isFollowing;
            } catch (error) {
                console.error('Error details:', {
                    message: error.message,
                    response: error.response?.data,
                    status: error.response?.status
                });
            } finally {
                this.isLoading = false;
            }
        }
    }
}
</script>
