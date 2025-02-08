<template>
    <div class="bg-gray-900 rounded-lg p-6">
        <h2 class="text-xl font-bold text-white mb-6">Edit Profile</h2>
        <form @submit.prevent="updateProfile" class="space-y-4">
            <div>
                <label class="block text-gray-400 mb-2">Name</label>
                <input
                    v-model="form.name"
                    type="text"
                    class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500"
                >
            </div>

            <div>
                <label class="block text-gray-400 mb-2">Username</label>
                <input
                    v-model="form.username"
                    type="text"
                    class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500"
                >
            </div>

            <div>
                <label class="block text-gray-400 mb-2">Email</label>
                <input
                    v-model="form.email"
                    type="email"
                    class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500"
                >
            </div>

            <div v-if="error" class="text-red-500">{{ error }}</div>

            <button
                type="submit"
                :disabled="isLoading"
                class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 disabled:opacity-50"
            >
                {{ isLoading ? 'Saving...' : 'Save Changes' }}
            </button>
        </form>
    </div>
</template>

<script>
export default {
    props: {
        user: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            form: {
                name: this.user.name,
                username: this.user.username,
                email: this.user.email
            },
            error: null,
            isLoading: false
        }
    },
    methods: {
        async updateProfile() {
            this.isLoading = true;
            this.error = null;

            try {
                await axios.patch('/profile', this.form);
                window.location.href = `/profile/${this.form.username}`;
            } catch (error) {
                this.error = error.response?.data?.message || 'An error occurred';
            } finally {
                this.isLoading = false;
            }
        }
    }
}
</script>
