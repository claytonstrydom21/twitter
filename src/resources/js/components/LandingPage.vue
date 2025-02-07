<template>
    <div class="min-h-screen flex flex-col">
        <div class="flex-grow flex justify-center items-center">
            <div class="w-full flex flex-col gap-4 px-4 sm:px-6 md:px-8 lg:grid lg:grid-cols-2 lg:gap-8 lg:px-12">
                <div class="flex justify-center items-center py-4 sm:justify-start lg:py-0">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 lg:w-auto lg:h-auto lg:mx-auto">
                        <logo />
                    </div>
                </div>
                <div class="flex flex-col items-center text-center w-full px-4 space-y-4 sm:px-6 lg:items-start lg:text-left lg:px-0 lg:mx-auto">
                    <h1 class="text-3xl font-extrabold sm:text-4xl lg:text-6xl">Happening now</h1>
                    <h2 class="text-xl font-bold sm:text-2xl lg:text-2xl">Join today.</h2>

                    <div class="w-full max-w-md sm:max-w-sm md:max-w-md space-y-3 lg:w-3/5 lg:min-w-[350px]">
                        <google-button />
                        <apple-button />
                    </div>

                    <or-divider class="w-full max-w-md sm:max-w-sm md:max-w-md lg:w-3/5 lg:min-w-[350px]" />

                    <button
                        @click="showRegisterModal = true"
                        class="w-full max-w-md sm:max-w-sm md:max-w-md rounded-3xl px-4 py-2 bg-blue-500 text-white font-semibold hover:bg-blue-400 lg:w-3/5 lg:min-w-[350px]"
                    >
                        Create account
                    </button>

                    <p class="w-full max-w-md sm:max-w-sm md:max-w-md text-xs text-gray-400 lg:w-3/5 lg:min-w-[350px]">
                        By signing up, you agree to the
                        <a class="text-blue-500 hover:underline" href="">Terms of Service</a>
                        and
                        <a class="text-blue-500 hover:underline" href="">Privacy Policy</a>
                        , including
                        <a class="text-blue-500 hover:underline" href="">Cookie Use.</a>
                    </p>

                    <div class="w-full max-w-md sm:max-w-sm md:max-w-md space-y-3 mt-6 lg:w-3/5 lg:min-w-[350px]">
                        <p class="font-semibold">Already have an account?</p>
                        <button
                            @click="showLoginModal = true"
                            class="w-full max-w-md sm:max-w-sm md:max-w-md rounded-3xl border border-gray-100 border-opacity-30 px-4 py-2 bg-transparent text-blue-400 font-semibold hover:bg-blue-500 hover:bg-opacity-20"
                        >
                            Sign In
                        </button>
                    </div>
                </div>

                <register-modal
                    :show="showRegisterModal"
                    @close="showRegisterModal = false"
                />
                <login-modal
                    :show="showLoginModal"
                    @close="showLoginModal = false"
                />
            </div>
        </div>

        <div class="w-full px-4 sm:px-6 md:px-8 lg:px-12">
            <Footer />
        </div>
    </div>
</template>

<script>
import Footer from './Footer.vue'
export default {
    name: 'LandingPage',
    components: {
        Footer
    },
    data() {
        return {
            showRegisterModal: false,
            showLoginModal: false
        }
    }
}
</script>
