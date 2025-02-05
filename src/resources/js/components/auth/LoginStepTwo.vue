<template>
    <div class="space-y-6">
        <h2 class="text-2xl font-bold text-center">Enter your password</h2>

        <div class="space-y-4">
            <div>
                <p class="text-gray-600 mb-4">Enter the password for {{ email }}</p>
                <div class="relative">
                    <input
                        type="password"
                        v-model="password"
                        :class="[
                            'block w-full px-4 pt-6 pb-2 text-gray-900 bg-white border rounded-md appearance-none focus:outline-none focus:ring-2 peer',
                            error ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500'
                        ]"
                        placeholder=" "
                        @keyup.enter="submit"
                        :disabled="loading"
                    >
                    <label
                        :class="[
                            'absolute duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] left-4',
                            'peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0',
                            'peer-focus:scale-75 peer-focus:-translate-y-3',
                            error ? 'text-red-500' : 'text-gray-500'
                        ]"
                    >
                        Password
                    </label>
                    <p v-if="error" class="mt-1 text-red-500 text-sm">{{ error }}</p>
                </div>
            </div>
        </div>

        <div class="flex space-x-4">
            <button
                @click="$emit('back')"
                class="w-1/2 rounded-full border border-gray-300 px-4 py-2 hover:bg-gray-50"
                :disabled="loading"
            >
                Back
            </button>
            <button
                @click="submit"
                class="w-1/2 rounded-full bg-black px-4 py-2 text-white hover:bg-gray-800 disabled:opacity-50 disabled:cursor-not-allowed relative"
                :disabled="!isValid || loading"
            >
                <span v-if="!loading">Log in</span>
                <span v-else class="flex items-center justify-center">
                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </span>
            </button>
        </div>
    </div>
</template>

<script>
export default {
    name: 'LoginStepTwo',
    props: {
        email: {
            type: String,
            required: true
        },
        loading: {
            type: Boolean,
            default: false
        },
        error: {
            type: String,
            default: ''
        }
    },
    data() {
        return {
            password: ''
        }
    },
    computed: {
        isValid() {
            return this.password.length > 0
        }
    },
    methods: {
        submit() {
            if (!this.isValid || this.loading) return;
            this.$emit('submit', { password: this.password });
        }
    },
    watch: {
        password() {
            if (this.error) {
                this.$emit('update:error', '');
            }
        }
    }
}
</script>
