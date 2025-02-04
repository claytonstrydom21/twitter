<template>
    <div class="border-b border-gray-800 pb-4 mb-4">
        <div class="flex space-x-4">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 rounded-full bg-gray-700"></div>
            </div>
            <div class="flex-grow">
                <form @submit.prevent="submitPost">
          <textarea
              v-model="content"
              rows="3"
              class="w-full bg-transparent text-white border-b border-gray-800 focus:border-gray-600 focus:ring-0 resize-none placeholder-gray-600"
              placeholder="What's happening?"
          ></textarea>

                    <div class="flex items-center justify-between mt-3">
            <span class="text-sm text-gray-500">
              {{ 280 - content.length }} characters remaining
            </span>
                        <button
                            type="submit"
                            :disabled="!isValid"
                            class="px-4 py-2 bg-blue-500 text-white rounded-full font-semibold hover:bg-blue-600 disabled:opacity-50"
                        >
                            Post
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'CreatePost',
    data() {
        return {
            content: ''
        }
    },
    computed: {
        isValid() {
            return this.content.length > 0 && this.content.length <= 280
        }
    },
    methods: {
        async submitPost() {
            if (!this.isValid) return

            try {
                const response = await fetch('/posts', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ content: this.content })
                })

                if (response.ok) {
                    this.content = ''
                    this.$emit('post-created')
                }
            } catch (error) {
                console.error('Error creating post:', error)
            }
        }
    }
}
</script>
