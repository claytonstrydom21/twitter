<!-- resources/js/components/search/UserSearch.vue -->
<template>
    <div class="bg-gray-900 rounded-lg p-4">
        <div class="relative">
            <input
                type="text"
                v-model="searchQuery"
                @input="handleSearch"
                placeholder="Search users..."
                class="w-full bg-gray-800 text-white border border-gray-700 rounded-full px-4 py-2 focus:outline-none focus:border-blue-500"
            >

            <div v-if="isLoading" class="text-gray-500 text-sm mt-2">
                Searching...
            </div>

            <div v-else-if="searchResults.length > 0" class="mt-4 space-y-4">
                <div
                    v-for="user in searchResults"
                    :key="user.id"
                    class="flex items-center justify-between p-3 bg-gray-800 rounded-lg"
                >
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gray-700 rounded-full"></div>
                        <div class="ml-3">
                            <div class="text-white font-medium">{{ user.name }}</div>
                            <div class="text-gray-500 text-sm">@{{ user.username }}</div>
                        </div>
                    </div>
                    <follow-button
                        :user-id="user.id"
                        :current-user-id="currentUserId"
                        :initial-is-following="user.is_following"
                    />
                </div>
            </div>

            <div v-else-if="searchQuery && !isLoading" class="text-gray-500 text-sm mt-2">
                No users found
            </div>
        </div>
    </div>
</template>

<script>
import { debounce } from 'lodash';
import searchService from '../../services/searchService';
import FollowButton from '../FollowButton.vue';

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
            isLoading: false
        }
    },
    methods: {
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
    }
}
</script>
