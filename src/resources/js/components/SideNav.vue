<template>
    <nav class="fixed z-50 bg-black text-white">
        <div class="flex items-center justify-between fixed top-0 left-0 right-0 px-4 py-3 border-b border-gray-800 bg-black md:hidden">
            <div class="flex-1">
            </div>
            <div class="flex-1 flex justify-center">
                <div class="w-6 h-6">
                    <logo />
                </div>
            </div>
            <div class="flex-1 flex justify-end relative">
                <button @click="showMobileDropdown = !showMobileDropdown"
                        class="w-8 h-8 rounded-full bg-gray-700 flex items-center justify-center">
                    <avatar
                        :src="user.avatar_url"
                        :name="user.name"
                        size="sm"
                    />
                </button>
                <div v-if="showMobileDropdown"
                     class="absolute right-0 top-full mt-2 w-56 bg-black border border-gray-800 rounded-lg shadow-lg">
                    <div class="py-1">
                        <a :href="`/profile/${user.username}`" class="block px-4 py-2 hover:bg-gray-800">Profile</a>
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

        <div class="hidden md:flex flex-col fixed left-0 top-0 bottom-0 w-20 lg:w-64 bg-black border-r border-gray-800">
            <div class="px-4 py-4">
                <div class="w-8 h-8">
                    <logo />
                </div>
            </div>

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
                <li>
                    <a href="/notifications" class="flex items-center space-x-4 p-3 rounded-full hover:bg-gray-800">
                        <span class="material-symbols-rounded text-xl">notifications</span>
                        <span class="hidden lg:block">Notifications</span>
                        <span
                            v-if="unreadNotificationsCount > 0"
                            class="absolute right-10 bg-red-500 text-xs rounded-full w-6 h-6 flex items-center justify-center">
                            {{ unreadNotificationsCount}}
                        </span>
                    </a>
                </li>
            </ul>

            <div class="mt-auto">
                <div class="relative">
                    <button @click="showDesktopDropdown = !showDesktopDropdown"
                            class="w-full p-4 flex items-center space-x-3 hover:bg-gray-800">
                        <avatar
                            :src="user.avatar_url"
                            :name="user.name"
                            size="md"
                        />
                        <div class="hidden lg:block overflow-hidden">
                            <p class="font-medium truncate">{{ user.name }}</p>
                            <p class="text-sm text-gray-500 truncate">@{{ user.username }}</p>
                        </div>
                    </button>
                    <div v-if="showDesktopDropdown"
                         class="absolute bottom-full left-0 mb-2 w-56 bg-black border border-gray-800 rounded-lg shadow-lg">
                        <div class="py-1">
                            <a :href="`/profile/${user.username}`" class="block px-4 py-2 hover:bg-gray-800">Profile</a>
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
                    <span
                        v-if="unreadNotificationsCount > 0"
                        class="absolute right-10 bg-red-500 text-xs rounded-full w-6 h-6 flex items-center justify-center">
                            {{ unreadNotificationsCount}}
                        </span>
                </a>
            </div>
        </div>
    </nav>
</template>

<script>
import Avatar from "./ui/Avatar.vue";
export default {
    name: 'SideNav',
    components: {
        Avatar
    },
    data() {
        return {
            showMobileDropdown: false,
            showDesktopDropdown: false,
            user: window.user || {},
            title: 'X Clone',
            isLoggingOut: false,
            csrf: document.querySelector('meta[name="csrf-token"]')?.content,
            unreadNotificationsCount: 0,
            notificationFetchInterval: null
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
                        'X-CSRF-TOKEN': this.csrf,
                        'Accept': 'application/json'
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
        },
        async fetchUnreadNotificationsCount() {
            try {
                const response = await axios.get('/notifications/count', {
                    withCredentials: true
                });
                this.unreadNotificationsCount = response.data.unread_count;
            } catch (error) {
                console.error('Detailed Notification Count Error:', {
                    response: error.response,
                    request: error.request,
                    message: error.message
                });
            }
        }
    },
    mounted() {
        document.addEventListener('click', this.closeDropdowns);

        this.fetchUnreadNotificationsCount();

        this.notificationFetchInterval = setInterval(() => {
            this.fetchUnreadNotificationsCount();
        }, 60000);
    },
    beforeUnmount() {
        document.removeEventListener('click', this.closeDropdowns);

        if(this.notificationFetchInterval) {
            clearInterval(this.notificationFetchInterval);
        }
    }
}
</script>
