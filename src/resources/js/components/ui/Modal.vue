<template>
    <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="fixed inset-0 bg-blue-300 bg-opacity-20 transition-opacity"></div>

        <div class="flex min-h-full items-center justify-center p-4 sm:p-6 md:p-8">
            <div class="relative transform rounded-lg bg-black items-center shadow-xl transition-all w-full max-w-md sm:max-w-lg pb-10 sm:pb-16">
                <div class="flex items-center justify-between px-3 pt-3 sm:px-4 sm:pt-4">
                    <button
                        @click="$emit('close')"
                        class="text-gray-400 hover:text-gray-500 w-8 h-8 flex items-center justify-center"
                    >
                        <span class="material-symbols-rounded text-lg sm:text-xl">close</span>
                    </button>

                    <div class="flex justify-center flex-grow">
                        <div class="w-6 h-6 sm:w-8 sm:h-8">
                            <logo />
                        </div>
                    </div>

                    <div class="w-8"></div>
                </div>

                <div class="p-4 sm:p-6">
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
