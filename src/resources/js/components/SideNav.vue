<template>
    <nav class="h-screen w-250px bg-black text-white fixed flex flex-col pl-20">
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

        <div class="mt-auto border-t border-gray-800">
            <div class="p-4 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-gray-700"></div>
                    <div>
                        <p class="font-semibold">{{ user.name }}</p>
                        <p class="text-sm text-gray-500">@{{ user.username }}</p>
                    </div>
                </div>

                <div class="relative">
                    <button
                        @click="showDropdown = !showDropdown"
                        class="p-2 hover:bg-gray-800 rounded-full"
                    >
                        <span class="material-symbols-rounded">more_horiz</span>
                    </button>
                    <div
                        v-if="showDropdown"
                        class="absolute bottom-full right-0 mb-2 w-56 bg-black border border-gray-800 rounded-lg shadow-lg"
                    >
                        <div class="py-1">
                            <a href="#" class="block px-4 py-2 text-white hover:bg-gray-800">
                                Add existing account
                            </a>
                            <form @submit.prevent="handleLogout" class="block">
                                <button
                                    type="submit"
                                    class="w-full text-left px-4 py-2 text-white hover:bg-gray-800"
                                    :disabled="isLoggingOut"
                                >
                                    {{ isLoggingOut ? 'Logging out...' : `Log out @${user.username}` }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</template>

<script>
export default {
    name: 'SideNav',
    data() {
        return {
            showDropdown: false,
            user: window.user || {},
            title: 'X Clone',
            isLoggingOut: false,
            csrf: document.querySelector('meta[name="csrf-token"]')?.content
        }
    },
    methods: {
        async handleLogout() {
            if (this.isLoggingOut) return;
            this.isLoggingOut = true;

            try {
                const response = await fetch('/logout', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': this.csrf
                    },
                    credentials: 'same-origin'
                });

                if (response.redirected) {
                    localStorage.clear();
                    sessionStorage.clear();

                    window.location.href = response.url;
                } else {
                    console.error('Unexpected response from logout endpoint');
                    window.location.href = '/';
                }
            } catch (error) {
                console.error('Logout error:', error);
                alert('An error occurred while logging out. Please try again.');
            } finally {
                this.isLoggingOut = false;
            }
        }
    },
    mounted() {
        const handleClickOutside = (e) => {
            if (!this.$el.contains(e.target)) {
                this.showDropdown = false;
            }
        };

        document.addEventListener('click', handleClickOutside);

        this.clickHandler = handleClickOutside;
    },
    beforeUnmount() {
        if (this.clickHandler) {
            document.removeEventListener('click', this.clickHandler);
        }
    }
}
</script>
