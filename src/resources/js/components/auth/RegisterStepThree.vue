<!-- resources/js/components/auth/RegisterStepThree.vue -->
<template>
    <div class="flex flex-col items-center w-full space-y-6">
        <h2 class="text-2xl font-bold">Add a profile picture</h2>

        <div class="w-full flex flex-col items-center space-y-6">
            <div class="relative w-32 h-32 group">
                <div v-if="!imagePreview"
                     class="w-full h-full rounded-full bg-gray-700 flex items-center justify-center cursor-pointer hover:bg-gray-600 transition-colors"
                     @click="$refs.fileInput.click()"
                >
                    <span class="material-symbols-rounded text-gray-400">add_a_photo</span>
                </div>
                <img v-else
                     :src="imagePreview"
                     class="w-full h-full rounded-full object-cover cursor-pointer"
                     @click="$refs.fileInput.click()"
                >
                <input
                    type="file"
                    ref="fileInput"
                    class="hidden"
                    accept="image/*"
                    @change="handleImageChange"
                >
                <div v-if="imagePreview"
                     class="absolute inset-0 rounded-full bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity cursor-pointer"
                     @click="$refs.fileInput.click()"
                >
                    <span class="text-white text-sm">Change photo</span>
                </div>
            </div>

            <p v-if="error" class="text-red-500 text-sm">{{ error }}</p>

            <div class="flex flex-col sm:flex-row space-y-4 sm:space-x-4 sm:space-y-0 w-full">
                <button
                    @click="$emit('back')"
                    class="w-full sm:w-1/2 rounded-full bg-white text-black px-4 py-2 hover:bg-gray-200"
                >
                    Back
                </button>
                <button
                    @click="submit"
                    class="w-full sm:w-1/2 rounded-full border border-gray-300 border-opacity-30 bg-black px-4 py-2 text-white hover:bg-gray-800"
                >
                    {{ imagePreview ? 'Continue' : 'Skip for now' }}
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'RegisterStepThree',
    data() {
        return {
            imageFile: null,
            imagePreview: null,
            error: null
        }
    },
    methods: {
        handleImageChange(event) {
            const file = event.target.files[0];
            if (!file) return;

            const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

            if (!validTypes.includes(file.type)) {
                this.error = 'Invalid file type. Please choose a JPEG, PNG, GIF, or WebP image.';
                return;
            }

            if (file.size > 5 * 1024 * 1024) {
                this.error = 'Image size should not exceed 5MB';
                return;
            }

            this.imageFile = file;
            this.imagePreview = URL.createObjectURL(file);
            this.error = null;
        },
        submit() {
            if (this.imageFile) {
                this.$emit('submit', { avatar: this.imageFile });
            } else {
                this.$emit('submit', { avatar: null });
            }
        }
    }
}
</script>
