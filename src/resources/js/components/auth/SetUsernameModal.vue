<template>
    <div class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center">
        <div class="bg-black p-8 rounded-2xl w-full max-w-md border border-gray-800">
            <h2 class="text-xl font-bold text-white mb-6">Choose your username</h2>

            <form @submit.prevent="setUsername">
                <div class="space-y-4">
                    <input
                        v-model="username"
                        type="text"
                        class="w-full bg-transparent border border-gray-700 rounded-lg p-3 text-white"
                        placeholder="Enter username"
                        maxlength="15"
                    >

                    <div v-if="suggestions.length" class="space-y-2">
                        <p class="text-gray-500">Suggestions:</p>
                        <div class="grid grid-cols-2 gap-2">
                            <button
                                v-for="suggestion in suggestions"
                                :key="suggestion"
                                type="button"
                                @click="username = suggestion"
                                class="text-blue-400 hover:bg-gray-800 rounded-lg p-2 text-left"
                            >
                                @{{ suggestion }}
                            </button>
                        </div>
                    </div>

                    <button
                        type="submit"
                        :disabled="!isValid"
                        class="w-full bg-white text-black rounded-full py-2 font-bold disabled:opacity-50"
                    >
                        Next
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
export default {
    name: 'SetUsernameModal',

    data() {
        return {
            username: '',
            suggestions: []
        }
    },
    computed: {
        isValid() {
            return this.username.length > 0 && this.username.length <= 20
        }
    },
    methods: {
        async fetchSuggestions() {
            const response = await fetch('/username-suggestions')
            this.suggestions = await response.json()
        },
        async setUsername() {
            if (!this.isValid) return

            try {
                const response = await fetch('/set-username', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ username: this.username })
                })

                if (response.ok) {
                    window.location.href = '/home'
                }
            } catch (error) {
                console.error('Error setting username:', error)
            }
        }
    },
    mounted() {
        this.fetchSuggestions()
    }
}
</script>
