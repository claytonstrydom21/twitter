<template>
    <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="fixed inset-0 bg-blue-300 bg-opacity-20 transition-opacity"></div>

        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative transform rounded-lg bg-white text-left shadow-xl transition-all w-full max-w-lg">
                <button
                    @click="$emit('close')"
                    class="pull-left ml-2 mt-2 text-gray-400 hover:text-gray-500"
                >
                    <span class="material-symbols-rounded">close</span>
                </button>

                <div class="p-6">
                    <slot></slot>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'Modal',
    props: {
        show: {
            type: Boolean,
            default: false
        }
    }
}
</script>
