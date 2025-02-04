<template>
    <div class="space-y-6">
        <h2 class="text-2xl font-bold text-center">Create your account</h2>

        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Name</label>
                <input
                    type="text"
                    v-model="formData.name"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    placeholder="Enter your name"
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input
                    type="email"
                    v-model="formData.email"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    placeholder="Enter your email"
                >
            </div>
        </div>

        <button
            @click="nextStep"
            class="w-full rounded-full bg-black px-4 py-2 text-white hover:bg-gray-800"
            :disabled="!isValid"
        >
            Next
        </button>
    </div>
</template>

<script>
export default {
    name: 'RegisterStepOne',
    data() {
        return {
            formData: {
                name: '',
                email: ''
            }
        }
    },
    computed: {
        isValid() {
            return this.formData.name.length > 0 &&
                this.formData.email.length > 0 &&
                this.validateEmail(this.formData.email)
        }
    },
    methods: {
        validateEmail(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)
        },
        nextStep() {
            if (this.isValid) {
                this.$emit('next', this.formData)
            }
        }
    }
}
</script>
