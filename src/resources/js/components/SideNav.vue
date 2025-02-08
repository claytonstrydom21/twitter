<template>
    <!-- Base navigation container - mobile first -->
    <nav class="fixed z-50 bg-black text-white">
        <!-- Top bar - visible only on mobile -->
        <div class="flex items-center justify-between fixed top-0 left-0 right-0 px-4 py-3 border-b border-gray-800 bg-black md:hidden">
            <div class="flex-1">
                <!-- Empty div for spacing -->
            </div>
            <div class="flex-1 flex justify-center">
                <div class="w-6 h-6">
                    <logo />
                </div>
            </div>
            <!-- Mobile user dropdown -->
            <div class="flex-1 flex justify-end relative">
                <button @click="showMobileDropdown = !showMobileDropdown"
                        class="w-8 h-8 rounded-full bg-gray-700 flex items-center justify-center">
                </button>
                <div v-if="showMobileDropdown"
                     class="absolute right-0 top-full mt-2 w-56 bg-black border border-gray-800 rounded-lg shadow-lg">
                    <div class="py-1">
                        <a :href="`/profile/${user.username}`" class="block px-4 py-2 hover:bg-gray-800">Profile</a>
                        <a href="#" class="block px-4 py-2 hover:bg-gray-800">Add existing account</a>
                        <form @submit.prevent="handleLogout">
                            <button type="submit"
                                    class="w-full text-left px-4 py-2 hover:bg-gray-800"
                                    :disabled="isLoggingOut">
                                {{ isLoggingOut ? 'Logging out...' : `Log out @${user.username}` }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Desktop/Tablet Side Navigation -->
        <div class="hidden md:flex flex-col fixed left-0 top-0 bottom-0 w-20 lg:w-64 bg-black border-r border-gray-800">
            <!-- Logo section -->
            <div class="px-4 py-4">
                <div class="w-8 h-8">
                    <logo />
                </div>
            </div>

            <!-- Navigation links -->
            <ul class="flex flex-col space-y-2 px-2 py-4">
                <li>
                    <a href="/" class="flex items-center space-x-4 p-3 rounded-full hover:bg-gray-800">
                        <span class="material-symbols-rounded text-xl">home</span>
                        <span class="hidden lg:block">Home</span>
                    </a>
                </li>
                <li>
                    <a href="/explore" class="flex items-center space-x-4 p-3 rounded-full hover:bg-gray-800">
                        <span class="material-symbols-rounded text-xl">travel_explore</span>
                        <span class="hidden lg:block">Explore</span>
                    </a>
                </li>
            </ul>

            <!-- Desktop user profile -->
            <div class="mt-auto">
                <div class="relative">
                    <button @click="showDesktopDropdown = !showDesktopDropdown"
                            class="w-full p-4 flex items-center space-x-3 hover:bg-gray-800">
                        <div class="w-10 h-10 rounded-full bg-gray-700"></div>
                        <div class="hidden lg:block overflow-hidden">
                            <p class="font-medium truncate">{{ user.name }}</p>
                            <p class="text-sm text-gray-500 truncate">@{{ user.username }}</p>
                        </div>
                    </button>
                    <div v-if="showDesktopDropdown"
                         class="absolute bottom-full left-0 mb-2 w-56 bg-black border border-gray-800 rounded-lg shadow-lg">
                        <div class="py-1">
                            <a :href="`/profile/${user.username}`" class="block px-4 py-2 hover:bg-gray-800">Profile</a>
                            <a href="#" class="block px-4 py-2 hover:bg-gray-800">Add existing account</a>
                            <form @submit.prevent="handleLogout">
                                <button type="submit"
                                        class="w-full text-left px-4 py-2 hover:bg-gray-800"
                                        :disabled="isLoggingOut">
                                    {{ isLoggingOut ? 'Logging out...' : `Log out @${user.username}` }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom mobile navigation -->
        <div class="fixed bottom-0 left-0 right-0 md:hidden">
            <div class="flex items-center justify-around px-4 py-3 bg-black border-t border-gray-800">
                <a href="/" class="flex items-center justify-center p-2">
                    <span class="material-symbols-rounded text-xl">home</span>
                </a>
                <a href="/explore" class="flex items-center justify-center p-2">
                    <span class="material-symbols-rounded text-xl">travel_explore</span>
                </a>
                <a href="/notifications" class="flex items-center justify-center p-2 relative">
                    <span class="material-symbols-rounded text-xl">notifications</span>
                    <span class="absolute -top-1 -right-1 bg-red-500 text-xs rounded-full w-4 h-4 flex items-center justify-center">
            3
          </span>
                </a>
                <a href="/messages" class="flex items-center justify-center p-2">
                    <span class="material-symbols-rounded text-xl">mail</span>
                </a>
            </div>
        </div>
    </nav>
</template>

<script>
export default {
    name: 'SideNav',
    data() {
        return {
            showMobileDropdown: false,
            showDesktopDropdown: false,
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
                this.showMobileDropdown = false;
                this.showDesktopDropdown = false;
            }
        },
        closeDropdowns(e) {
            // Get references to the dropdown triggers
            const mobileButton = this.$refs.mobileDropdownTrigger;
            const desktopButton = this.$refs.desktopDropdownTrigger;

            if (this.showMobileDropdown &&
                mobileButton &&
                !mobileButton.contains(e.target)) {
                this.showMobileDropdown = false;
            }

            if (this.showDesktopDropdown &&
                desktopButton &&
                !desktopButton.contains(e.target)) {
                this.showDesktopDropdown = false;
            }
        }
    },
    mounted() {
        console.log('User:', this.user);
        document.addEventListener('click', this.closeDropdowns);
    },
    beforeUnmount() {
        document.removeEventListener('click', this.closeDropdowns);
    }
}
</script>
