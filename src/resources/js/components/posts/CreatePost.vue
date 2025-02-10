<template>
    <div class="border-b border-gray-800 pb-4 mb-4">
        <div class="flex space-x-4">
            <div class="flex-shrink-0">
                <avatar
                    :src="user.avatar_url"
                    :name="user.name"
                    size="md"
                />
            </div>
            <div class="flex-grow">
                <form @submit.prevent="submitPost">
                    <textarea
                        v-model="content"
                        rows="3"
                        class="w-full bg-transparent text-white border-b border-gray-800 focus:border-gray-600 focus:ring-0 resize-none placeholder-gray-600"
                        placeholder="What's happening?"
                    ></textarea>
                    <div v-if="error" class="text-red-500 text-sm mt-1">
                        {{ error }}
                    </div>

                    <div v-if="imagePreview" class="mt-2">
                        <img :src="imagePreview" class="max-h-64 rounded-lg" />
                        <button
                            @click="removeImage"
                            type="button"
                            class="text-red-500 text-sm mt-1"
                        >
                            Remove image
                        </button>
                    </div>

                    <div class="flex items-center justify-between mt-3">
                        <div class="flex items-center space-x-4">
                            <label class="cursor-pointer">
                                <input
                                    type="file"
                                    class="hidden"
                                    accept="image/*"
                                    @change="handleImageUpload"
                                >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-400 hover:text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </label>
                            <span class="text-sm text-gray-500">
                                {{ 280 - content.length }} characters remaining
                            </span>
                        </div>
                        <button
                            type="submit"
                            :disabled="!isValid || isSubmitting"
                            class="px-4 py-2 bg-blue-500 text-white rounded-full font-semibold hover:bg-blue-600 disabled:opacity-50"
                        >
                            {{ isSubmitting ? 'Posting...' : 'Post' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import PostService from "../../services/PostService.js";
import Avatar from "../ui/Avatar.vue";

export default {
    name: 'CreatePost',
    components: {
        Avatar
    },
    data() {
        return {
            content: '',
            image: null,
            imagePreview: null,
            error: null,
            isSubmitting: false,
            user: window.user || {},
        }
    },
    computed: {
        isValid() {
            return this.content.length > 0 && this.content.length <= 280
        }
    },
    methods: {
        handleImageUpload(event) {
            const file = event.target.files[0];
            if (!file) return;

            if (file.size > 5 * 1024 * 1024) {
                this.error = 'Image size should not exceed 5MB';
                return;
            }

            this.image = file;
            this.imagePreview = URL.createObjectURL(file);
        },
        removeImage() {
            this.image = null;
            this.imagePreview = null;
        },
        async submitPost() {
            if (!this.isValid) return;

            this.error = null;
            this.isSubmitting = true;

            try {
                const newPost = await PostService.createPost(this.content, this.image);
                this.content = '';
                this.image = null;
                this.imagePreview = null;
                this.$emit('post-created', newPost);
            } catch (error) {
                this.error = error.message;
            } finally {
                this.isSubmitting = false;
            }
        }
    }
}
</script>
