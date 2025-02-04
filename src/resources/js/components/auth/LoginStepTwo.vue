<template>
    <div class="space-y-6">
        <h2 class="text-2xl font-bold text-center">Enter your password</h2>

        <div class="space-y-4">
            <div>
                <p class="text-gray-600 mb-4">Enter the password for {{ email }}</p>
                <input
                    type="password"
                    v-model="password"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    placeholder="Enter your password"
                    @keyup.enter="submit"
                >
                <p v-if="error" class="mt-1 text-red-500 text-sm">{{ error }}</p>
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
                Log in
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
        }
    },
    data() {
        return {
            password: '',
            error: ''
        }
    },
    computed: {
        isValid() {
            return this.password.length > 0
        }
    },
    methods: {
        async submit() {
            if (!this.isValid) return

            try {
                const response = await fetch('/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        email: this.email,
                        password: this.password
                    })
                })

                if (response.ok) {
                    window.location.href = '/'
                } else {
                    const data = await response.json()
                    this.error = data.message || 'Invalid credentials'
                }
            } catch (error) {
                this.error = 'An error occurred. Please try again.'
            }
        }
    }
}
</script>
