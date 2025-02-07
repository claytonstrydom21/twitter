<template>
    <div class="flex flex-col items-center space-y-6 w-full max-w-md mx-auto">
        <h2 class="text-xl sm:text-2xl font-bold text-center">Sign in to X</h2>

        <div class="w-full flex flex-col items-center space-y-6">
            <google-button class="w-full max-w-md" />
            <apple-button class="w-full max-w-md" />

            <or-divider class="md:w-full lg:w-full"/>

            <div class="relative w-full max-w-md">
                <input
                    type="email"
                    v-model="email"
                    :class="[
                        'block w-full px-4 pt-6 pb-2 text-white bg-transparent border rounded-md appearance-none focus:outline-none focus:ring-2 peer',
                        error
                            ? 'border-red-500 focus:ring-red-500'
                            : 'border-gray-300 focus:ring-blue-500'
                    ]"
                    placeholder=" "
                    @keyup.enter="nextStep"
                    @blur="validateEmail"
                >
                <label
                    :class="[
                        'absolute duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] left-4',
                        'peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0',
                        'peer-focus:scale-75 peer-focus:-translate-y-3',
                        error ? 'text-red-500' : 'text-gray-500'
                    ]"
                >
                    Email
                </label>
                <p v-if="error" class="mt-1 text-red-500 text-sm">{{ error }}</p>
            </div>

            <button
                @click="nextStep"
                class="w-full max-w-md rounded-full bg-white px-4 py-2 text-black font-medium hover:bg-gray-200 disabled:opacity-50"
                :disabled="!isValidEmail || email.length === 0 || loading"
            >
                <span v-if="!loading">Next</span>
                <span v-else class="flex items-center justify-center">
                    <svg class="animate-spin h-5 w-5 text-black" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
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
    name: 'LoginStepOne',
    props: {
        loading: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            email: '',
            error: ''
        }
    },
    computed: {
        isValidEmail() {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.email)
        }
    },
    methods: {
        validateEmail() {
            if (this.email.length === 0) {
                this.error = 'Email address is required'
            } else if (!this.isValidEmail) {
                this.error = 'Please enter a valid email address'
            } else {
                this.error = ''
            }
        },
        nextStep() {
            this.validateEmail()

            if (this.isValidEmail && this.email.length > 0) {
                this.$emit('next', { email: this.email })
            }
        }
    },
    watch: {
        email() {
            if (this.error) {
                this.error = ''
            }
        }
    }
}
</script>
