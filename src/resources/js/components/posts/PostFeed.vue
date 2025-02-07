<template>
    <base-feed :posts="posts" :current-user-id="currentUserId" />
</template>

<script>
import BaseFeed from './BaseFeed.vue';
import postService from '../../services/PostService.js';

export default {
    components: { BaseFeed },
    props: {
        currentUserId: {
            type: Number,
            required: true
        }
    },
    data() {
        return {
            posts: []
        }
    },
    methods: {
        async fetchPosts() {
            try {
                const posts = await postService.getPosts();
                this.posts = posts;
            } catch (error) {
                console.error('Error fetching posts:', error);
            }
        }
    },
    async mounted() {
        try {
            const posts = await postService.getPosts();
            this.posts = posts;
        } catch (error) {
            console.error('Error fetching posts:', error);
        }
    }
}
</script>
