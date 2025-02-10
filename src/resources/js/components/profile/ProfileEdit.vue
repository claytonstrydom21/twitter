<template>
    <div class="bg-gray-900 rounded-lg p-6">
        <h2 class="text-xl font-bold text-white mb-6">Edit Profile</h2>
        <div class="mb-6 flex items-center">
            <div class="relative">
                <img
                    v-if="avatarPreview || user.avatar_url"
                    :src="avatarPreview || user.avatar_url"
                    class="w-24 h-24 rounded-full object-cover"
                />
                <div
                    v-else
                    class="w-24 h-24 rounded-full bg-gray-700 flex items-center justify-center"
                >
                    <span class="text-gray-400">No Image</span>
                </div>
                <input
                    type="file"
                    ref="fileInput"
                    @change="handleAvatarChange"
                    accept="image/*"
                    class="hidden"
                />
                <button
                    @click="$refs.fileInput.click()"
                    class="absolute bottom-0 right-0 bg-blue-500 rounded-full p-2 text-white"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </button>
            </div>
        </div>
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
                class="w-1/2 bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 disabled:opacity-50"
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
            avatarFile: null,
            avatarPreview: null,
            error: null,
            isLoading: false
        }
    },
    methods: {
        handleAvatarChange(event) {
            const file = event.target.files[0];
            if (!file) return;

            if (file.size > 5 * 1024 * 1024) {
                this.error = 'Image size should not exceed 5MB';
                return;
            }

            this.avatarFile = file;
            this.avatarPreview = URL.createObjectURL(file);
        },

        async updateProfile() {
            this.isLoading = true;
            this.error = null;

            const formData = new FormData();
            formData.append('name', this.form.name);
            formData.append('username', this.form.username);
            formData.append('email', this.form.email);

            if (this.avatarFile) {
                formData.append('avatar', this.avatarFile);
            }

            try {
                const response = await axios.post('/profile', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'X-HTTP-Method-Override': 'PATCH'
                    }
                });
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
