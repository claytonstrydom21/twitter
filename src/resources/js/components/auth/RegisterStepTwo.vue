<template>
    <div class="flex flex-col items-center w-full space-y-6">
        <h2 class="text-2xl font-bold">Create a password</h2>

        <div class="w-4/5 flex flex-col items-center space-y-6">
            <div class="relative w-full">
                <input
                    type="password"
                    v-model="formData.password"
                    :class="[
                        'block w-full px-4 pt-6 pb-2 text-white bg-transparent border rounded-md appearance-none focus:outline-none focus:ring-2 peer',
                        passwordError
                            ? 'border-red-500 focus:ring-red-500'
                            : 'border-gray-300 focus:ring-blue-500'
                    ]"
                    placeholder=" "
                    @blur="validatePassword"
                >
                <label
                    :class="[
                        'absolute duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] left-4',
                        'peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0',
                        'peer-focus:scale-75 peer-focus:-translate-y-3',
                        passwordError ? 'text-red-500' : 'text-gray-500'
                    ]"
                >
                    Password
                </label>
                <p v-if="passwordError" class="mt-1 text-red-500 text-sm">{{ passwordError }}</p>
            </div>

            <div class="relative w-full">
                <input
                    type="password"
                    v-model="formData.password_confirmation"
                    :class="[
                        'block w-full px-4 pt-6 pb-2 text-white bg-transparent border rounded-md appearance-none focus:outline-none focus:ring-2 peer',
                        confirmError
                            ? 'border-red-500 focus:ring-red-500'
                            : 'border-gray-300 focus:ring-blue-500'
                    ]"
                    placeholder=" "
                    @blur="validateConfirmation"
                >
                <label
                    :class="[
                        'absolute duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] left-4',
                        'peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0',
                        'peer-focus:scale-75 peer-focus:-translate-y-3',
                        confirmError ? 'text-red-500' : 'text-gray-500'
                    ]"
                >
                    Confirm Password
                </label>
                <p v-if="confirmError" class="mt-1 text-red-500 text-sm">{{ confirmError }}</p>
            </div>

            <div class="flex space-x-4 w-full">
                <button
                    @click="$emit('back')"
                    class="w-1/2 rounded-full bg-white text-black px-4 py-2 hover:bg-gray-200"
                >
                    Back
                </button>
                <button
                    @click="submit"
                    class="w-1/2 rounded-full border border-gray-300 border-opacity-30 bg-black px-4 py-2 text-white hover:bg-gray-800"
                    :disabled="!isValid"
                >
                    Create Account
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'RegisterStepTwo',
    data() {
        return {
            formData: {
                password: '',
                password_confirmation: ''
            },
            passwordError: '',
            confirmError: ''
        }
    },
    computed: {
        isValid() {
            return this.formData.password.length >= 8 &&
                this.formData.password === this.formData.password_confirmation &&
                !this.passwordError &&
                !this.confirmError
        }
    },
    methods: {
        validatePassword() {
            if (this.formData.password.length === 0) {
                this.passwordError = 'Password is required'
            } else if (this.formData.password.length < 8) {
                this.passwordError = 'Password must be at least 8 characters'
            } else {
                this.passwordError = ''
            }
            this.validateConfirmation()
        },
        validateConfirmation() {
            if (this.formData.password_confirmation.length === 0) {
                this.confirmError = 'Please confirm your password'
            } else if (this.formData.password !== this.formData.password_confirmation) {
                this.confirmError = 'Passwords do not match'
            } else {
                this.confirmError = ''
            }
        },
        submit() {
            this.validatePassword()
            this.validateConfirmation()

            if (this.isValid) {
                this.$emit('submit', this.formData)
            }
        }
    },
    watch: {
        'formData.password'() {
            if (this.passwordError) {
                this.passwordError = ''
            }
        },
        'formData.password_confirmation'() {
            if (this.confirmError) {
                this.confirmError = ''
            }
        }
    }
}
</script>
