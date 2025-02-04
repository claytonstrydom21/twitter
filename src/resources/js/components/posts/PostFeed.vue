<template>
    <div class="space-y-4">
        <div v-for="post in posts" :key="post.id" class="border-b border-gray-800 p-4">
            <div class="flex space-x-4">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 rounded-full bg-gray-700"></div>
                </div>
                <div>
                    <div class="flex items-center space-x-2">
                        <span class="font-bold text-white">{{ post.user.name }}</span>
                        <span class="text-gray-500">· {{ formatDate(post.created_at) }}</span>
                    </div>
                    <p class="text-white mt-2">{{ post.content }}</p>
                </div>
            </div>
        </div>

        <div v-if="posts.length === 0" class="text-center py-8">
            <p class="text-gray-500">No posts yet</p>
        </div>
    </div>
</template>

<script>
export default {
    name: 'PostFeed',
    data() {
        return {
            posts: []
        }
    },
    methods: {
        formatDate(date) {
            return new Date(date).toLocaleDateString()
        },
        async fetchPosts() {
            try {
                const response = await fetch('/posts')
                if (response.ok) {
                    this.posts = await response.json()
                }
            } catch (error) {
                console.error('Error fetching posts:', error)
            }
        }
    },
    mounted() {
        this.fetchPosts()
    }
}
</script>
