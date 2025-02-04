<template>
    <div class="space-y-6">
        <h2 class="text-2xl font-bold text-center">Create a password</h2>

        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Password</label>
                <input
                    type="password"
                    v-model="formData.password"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    placeholder="Enter your password"
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
                <input
                    type="password"
                    v-model="formData.password_confirmation"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    placeholder="Confirm your password"
                >
            </div>
        </div>

        <div class="flex space-x-4">
            <button
                @click="$emit('back')"
                class="w-1/2 rounded-full border border-gray-300 px-4 py-2 hover:bg-gray-50"
            >
                Back
            </button>
            <button
                @click="submit"
                class="w-1/2 rounded-full bg-black px-4 py-2 text-white hover:bg-gray-800"
                :disabled="!isValid"
            >
                Create Account
            </button>
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
            }
        }
    },
    computed: {
        isValid() {
            return this.formData.password.length >= 8 &&
                this.formData.password === this.formData.password_confirmation
        }
    },
    methods: {
        submit() {
            if (this.isValid) {
                this.$emit('submit', this.formData)
            }
        }
    }
}
</script>
