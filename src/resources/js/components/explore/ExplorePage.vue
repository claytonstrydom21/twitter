<template>
    <div class="max-w-4xl mx-auto px-4">
        <div class="mb-8">
            <input
                type="text"
                v-model="searchQuery"
                @input="handleSearch"
                placeholder="Search users..."
                class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-3 focus:outline-none focus:border-blue-500"
            >

            <div v-if="isLoading" class="mt-4 text-gray-500 text-center">
                Searching...
            </div>

            <div v-else-if="searchResults.length > 0" class="mt-4 space-y-4">
                <div
                    v-for="user in searchResults"
                    :key="user.id"
                    class="flex items-center justify-between p-4 bg-gray-900 rounded-lg"
                >
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-gray-700 rounded-full"></div>
                        <div>
                            <div class="text-white font-medium">{{ user.name }}</div>
                            <div class="text-gray-500 text-sm">{{ user.email }}</div>
                        </div>
                    </div>
                    <follow-button
                        :user-id="user.id"
                        :current-user-id="currentUserId"
                        :initial-is-following="user.is_following"
                    />
                </div>
            </div>

            <div v-else-if="searchQuery && !isLoading" class="text-gray-500 text-center py-4">
                No users found
            </div>
        </div>

        <div v-if="!searchQuery">
            <h2 class="text-xl font-bold text-white mb-4">Discover New Posts</h2>
            <div v-if="posts.length === 0" class="text-center py-8 text-gray-500">
                No discoverable posts
            </div>
            <base-feed v-else :posts="posts" :current-user-id="currentUserId" />
        </div>
    </div>
</template>

<script>
import { debounce } from 'lodash';
import searchService from "../../services/searchService.js";
import FollowButton from "../FollowButton.vue";
import postService from "../../services/PostService.js";

export default {
    components: {
        FollowButton
    },
    props: {
        currentUserId: {
            type: Number,
            required: true
        }
    },
    data() {
        return {
            searchQuery: '',
            searchResults: [],
            isLoading: false,
            posts: []
        }
    },
    methods: {
        async fetchUnfollowedPosts() {
            try {
                this.posts = await postService.getUnfollowedPosts();
            } catch (error) {
                console.error('Error fetching posts:', error);
            }
        },
        handleSearch: debounce(async function() {
            if (this.searchQuery.length < 2) {
                this.searchResults = [];
                return;
            }

            this.isLoading = true;
            try {
                this.searchResults = await searchService.searchUsers(this.searchQuery);
            } catch (error) {
                console.error('Error searching users:', error);
            } finally {
                this.isLoading = false;
            }
        }, 300)
    },

    mounted() {
        this.fetchUnfollowedPosts();
    }
}
</script>
