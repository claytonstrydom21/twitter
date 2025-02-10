<template>
    <div
        :class="[
            'rounded-full bg-gray-700 flex items-center justify-center overflow-hidden',
            sizeClasses
        ]"
    >
        <img
            v-if="src"
            :src="src"
            :alt="alt"
            class="w-full h-full object-cover"
        />
        <span v-else class="text-gray-400 text-sm">{{ initials }}</span>
    </div>
</template>

<script>
export default {
    props: {
        src: {
            type: String,
            default: null
        },
        alt: {
            type: String,
            default: ''
        },
        size: {
            type: String,
            default: 'md',
            validator: value => ['sm', 'md', 'lg'].includes(value)
        },
        name: {
            type: String,
            default: ''
        }
    },
    computed: {
        sizeClasses() {
            return {
                'sm': 'w-8 h-8',
                'md': 'w-12 h-12',
                'lg': 'w-24 h-24'
            }[this.size]
        },
        initials() {
            return this.name
                .split(' ')
                .map(word => word[0])
                .join('')
                .toUpperCase()
                .slice(0, 2)
        }
    }
}
</script>
