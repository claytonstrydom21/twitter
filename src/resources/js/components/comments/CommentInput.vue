<template>
    <div class="mt-2">
        <form @submit.prevent="submitComment" class="flex items-start space-x-2">
            <input
                v-model="content"
                type="text"
                placeholder="Write a comment..."
                class="flex-grow bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500"
            >
            <button
                type="submit"
                :disabled="!content.trim() || isSubmitting"
                class="px-4 py-2 bg-blue-500 text-white rounded-lg disabled:opacity-50"
            >
                {{ isSubmitting ? 'Sending...' : 'Send' }}
            </button>
        </form>
    </div>
</template>

<script>
export default {
    props: {
        postId: {
            type: [Number, String],
            required: true
        }
    },
    data() {
        return {
            content: '',
            isSubmitting: false
        }
    },
    methods: {
        async submitComment() {
            if (!this.content.trim() || this.isSubmitting) return;

            this.isSubmitting = true;
            try {
                const response = await fetch(`/posts/${this.postId}/comments`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ content: this.content })
                });

                if (!response.ok) throw new Error('Failed to send comment');

                const comment = await response.json();
                this.$emit('comment-created', comment);
                this.content='';
            } catch (error) {
                console.error(error);
            } finally {
                this.isSubmitting = false;
            }
        }
    }
}
</script>
