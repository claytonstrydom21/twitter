<template>
    <nav class="h-screen w-64 bg-black text-white fixed flex flex-col pl-10">
        <div class="mb-8">
            <h1 class="text-2xl font-bold">X Clone</h1>
        </div>

        <ul class="space-y-6">
            <li>
                <a
                    href="/"
                    class="flex items-center space-x-3 text-lg rounded-lg hover:bg-gray-800 transition"
                >
                    <span class="material-symbols-rounded text-2xl">home</span>
                    <span>Home</span>
                </a>
            </li>

            <li>
                <a
                    href="/explore"
                    class="flex items-center space-x-3 text-lg rounded-lg hover:bg-gray-800 transition"
                >
                    <span class="material-symbols-rounded text-2xl">travel_explore</span>
                    <span>Explore</span>
                </a>
            </li>

            <li>
                <a
                    href="/profile"
                    class="flex items-center space-x-3 text-lg rounded-lg hover:bg-gray-800 transition"
                >
                    <span class="material-symbols-rounded text-2xl">person</span>
                    <span>Profile</span>
                </a>
            </li>
        </ul>
    </nav>
</template>

<script>
export default {
    name: 'SideNav',
    data() {
        return {
            title: 'X Clone'
        }
    },
    mounted() {
        console.log('SideNav component mounted')
    }
}
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:wght@400;700&display=swap');

</style>
