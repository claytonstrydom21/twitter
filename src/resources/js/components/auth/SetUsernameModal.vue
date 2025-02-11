<template>
    <div class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center">
        <div class="bg-black p-8 rounded-2xl w-full max-w-md border border-gray-800">
            <h2 class="text-xl font-bold text-white mb-6">Choose your username</h2>

            <form @submit.prevent="setUsername">
                <div class="space-y-4">
                    <div class="relative">
                        <input
                            v-model="username"
                            type="text"
                            class="w-full bg-transparent border transition-colors duration-200"
                            :class="[
                                'rounded-lg p-3 text-white',
                                {'border-gray-700': !username && !error,
                                 'border-red-500': error,
                                 'border-green-500': username && !error}
                            ]"
                            placeholder="Enter username"
                            maxlength="20"
                            @input="validateUsername"
                        >
                        <span class="absolute right-3 top-3 text-sm"
                              :class="{'text-gray-500': remainingChars > 5,
                                      'text-yellow-500': remainingChars <= 5 && remainingChars > 0,
                                      'text-red-500': remainingChars === 0}">
                            {{ remainingChars }}/20
                        </span>
                    </div>

                    <p v-if="error" class="text-red-500 text-sm mt-1">
                        {{ error }}
                    </p>

                    <div v-if="success"
                         class="bg-green-500 bg-opacity-10 border border-green-500 rounded-lg p-3 text-green-500 flex items-center space-x-2">
                        <span class="material-symbols-rounded text-sm">check_circle</span>
                        <span>Username set successfully! Redirecting...</span>
                    </div>

                    <div v-if="suggestions.length"
                         class="space-y-2 transition-opacity duration-300"
                         :class="{'opacity-50': loading}">
                        <p class="text-gray-500">Suggestions:</p>
                        <div class="grid grid-cols-2 gap-2">
                            <button
                                v-for="suggestion in suggestions"
                                :key="suggestion"
                                type="button"
                                @click="selectSuggestion(suggestion)"
                                class="text-blue-400 hover:bg-gray-800 rounded-lg p-2 text-left transition-colors duration-200"
                                :disabled="loading"
                            >
                                @{{ suggestion }}
                            </button>
                        </div>
                    </div>

                    <button
                        type="submit"
                        :disabled="!isValid || loading"
                        class="w-full bg-white text-black rounded-full py-2 font-bold disabled:opacity-50 relative overflow-hidden transition-all duration-200"
                    >
                        <span v-if="!loading"
                              class="inline-block transition-transform duration-200"
                              :class="{'opacity-0': loading}">
                            Next
                        </span>
                        <span v-if="loading"
                              class="absolute inset-0 flex items-center justify-center">
                            <svg class="animate-spin h-5 w-5 text-black" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import AuthService from "../../services/AuthService.js";

export default {
    name: 'SetUsernameModal',
    data() {
        return {
            username: '',
            suggestions: [],
            error: '',
            loading: false,
            success: false,
            validationError: '',
            validationTimeout: null
        }
    },
    computed: {
        isValid() {
            return this.username.length > 0 &&
                this.username.length <= 20 &&
                !this.validationError;
        },
        remainingChars() {
            return 20 - this.username.length;
        }
    },
    methods: {
        validateUsername() {
            clearTimeout(this.validationTimeout);

            this.validationError = '';

            this.validationTimeout = setTimeout(() => {
                if (!this.username) {
                    this.validationError = 'Username is required';
                    return;
                }

                if (this.username.length < 3) {
                    this.validationError = 'Username must be at least 3 characters';
                    return;
                }

                if (!/^[a-zA-Z0-9_-]+$/.test(this.username)) {
                    this.validationError = 'Username can only contain letters, numbers, underscores, and hyphens';
                }
            }, 300);
        },
        async fetchSuggestions() {
            try {
                const response = await fetch('/username-suggestions');
                this.suggestions = await response.json();
            } catch (error) {
                console.error('Error fetching suggestions:', error);
            }
        },
        selectSuggestion(suggestion) {
            this.username = suggestion;
            this.validateUsername();
        },
        async setUsername() {
            if (!this.isValid) return;

            this.loading = true;
            this.error = '';

            try {
                const response = await AuthService.setUsername(this.username);
                this.success = true;

                setTimeout(() => {
                    window.location.href = response.redirect;
                }, 1500);
            } catch (error) {
                this.error = error.message;
                this.success = false;

                this.username = '';
                this.$nextTick(() => {
                    this.$refs.usernameInput && this.$refs.usernameInput.focus();
                });
            } finally {
                this.loading = false;
            }
        }
    },
    mounted() {
        this.fetchSuggestions();
    }
}
</script>

<style scoped>
@keyframes spin {
    to { transform: rotate(360deg); }
}

.animate-spin {
    animation: spin 1s linear infinite;
}
</style>
