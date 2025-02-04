<template>
    <div class="space-y-6">
        <h2 class="text-2xl font-bold text-center">Enter your email</h2>

        <div class="space-y-4">
            <div>
                <input
                    type="email"
                    v-model="email"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    placeholder="Enter your email"
                    @keyup.enter="nextStep"
                >
                <p v-if="error" class="mt-1 text-red-500 text-sm">{{ error }}</p>
            </div>
        </div>

        <button
            @click="nextStep"
            class="w-full rounded-full bg-black px-4 py-2 text-white hover:bg-gray-800"
            :disabled="!isValidEmail"
        >
            Next
        </button>
    </div>
</template>

<script>
export default {
    name: 'LoginStepOne',
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
        nextStep() {
            if (this.isValidEmail) {
                this.$emit('next', { email: this.email })
            }
        }
    }
}
</script>
