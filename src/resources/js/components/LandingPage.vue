<template>
    <div class="w-full grid grid-cols-1 md:grid-cols-2 gap-8 px-6">
        <div class="flex justify-center items-center">
            <logo />
        </div>

        <div class="flex flex-col justify-center items-center md:items-start text-center m-5 w-full max-w-lg">
            <h1 class="text-6xl font-extrabold">Happening now</h1>
            <h1 class="mt-8 mb-5 text-2xl font-bold">Join today.</h1>

            <google-button />
            <apple-button />

            <div class="flex items-center w-3/5 max-w-md my-2">
                <hr class="flex-grow border-t border-gray-100 opacity-20">
                <p class="mx-4">or</p>
                <hr class="flex-grow border-t border-gray-100 opacity-20">
            </div>

            <button
                @click="showRegisterModal = true"
                class="w-3/5 max-w-md rounded-3xl px-12 py-2 bg-blue-500 text-white font-semibold hover:bg-blue-400"
            >
                Create account
            </button>

            <p class="w-3/5 text-xs text-gray-400">
                By signing up, you agree to the
                <a class="text-blue-500 hover:underline" href="">Terms of Service</a>
                and
                <a class="text-blue-500 hover:underline" href="">Privacy Policy</a>
                , including
                <a class="text-blue-500 hover:underline" href="">Cookie Use.</a>
            </p>
            <p class="font-semibold mt-10">Already have an account?</p>

            <button
                @click="showLoginModal = true"
                class="w-3/5 max-w-1/2 rounded-3xl border border-gray-100 border-opacity-30 px-12 py-2 bg-transparent text-blue-400 font-semibold mt-5 hover:bg-blue-500 hover:bg-opacity-20"
            >
                Sign In
            </button>
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
</template>

<script>
export default {
    name: 'LandingPage',
    data() {
        return {
            showRegisterModal: false,
            showLoginModal: false
        }
    }
}
</script>
