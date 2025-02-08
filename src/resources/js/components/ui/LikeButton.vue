<template>
    <button
        @click="toggleLike"
        class="flex items-center space-x-1 text-gray-500 hover:text-blue-500"
        :class="{ 'text-blue-500': isLiked }"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" :class="{ 'fill-current': isLiked }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
        </svg>
        <span>{{ likesCount }}</span>
    </button>
</template>

<script>
import axios from 'axios';

export default {
    props: {
        postId: {
            type: Number,
            required: true
        },
        initialIsLiked: {
            type: Boolean,
            default: false
        },
        initialLikesCount: {
            type: Number,
            default: 0
        }
    },
    data() {
        return {
            isLiked: this.initialIsLiked,
            likesCount: this.initialLikesCount,
            isLoading: false
        }
    },
    methods: {
        async toggleLike(){
            if (this.isLoading) return;

            this.isLoading = true;
            try {
                const response = await axios({
                    method: this.isLiked ? 'delete' : 'post',
                    url: `/posts/${this.postId}/like`,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                this.isLiked = !this.isLiked;
                this.likesCount = response.data.likes_count;
            } catch (error) {
                console.error('Error toggling like:', error);
            } finally {
                this.isLoading = false;
            }
        }
    }
}
</script>
