<template>
    <div>
        <create-post @post-created="onPostCreated"></create-post>
        <post-feed
            ref="postFeed"
            :current-user-id="currentUserId"
        ></post-feed>
    </div>
</template>

<script>
import CreatePost from './CreatePost.vue'
import PostFeed from "./PostFeed.vue";

export default {
    name: 'FeedContainer',
    components: {
        CreatePost,
        PostFeed
    },
    props: {
        currentUserId: {
            type: Number,
            required: true
        }
    },
    methods: {
        onPostCreated() {
            this.$refs.postFeed.fetchPosts()
        }
    },
}
</script>
