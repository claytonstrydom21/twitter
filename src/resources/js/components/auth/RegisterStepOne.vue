<template>
    <div class="flex flex-col items-center space-y-6 w-full max-w-md mx-auto">
        <h2 class="text-xl sm:text-2xl font-bold">Create your account</h2>

        <div class="w-full flex flex-col items-center space-y-6">
            <div class="relative w-full">
                <input
                    type="text"
                    v-model="formData.name"
                    :class="[
                        'block w-full px-4 pt-6 pb-2 text-white bg-transparent border rounded-md appearance-none focus:outline-none focus:ring-2 peer',
                        nameError
                            ? 'border-red-500 focus:ring-red-500'
                            : 'border-gray-300 focus:ring-blue-500'
                    ]"
                    placeholder=" "
                    @blur="validateName"
                >
                <label
                    :class="[
                        'absolute duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] left-4',
                        'peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0',
                        'peer-focus:scale-75 peer-focus:-translate-y-3',
                        nameError ? 'text-red-500' : 'text-gray-500'
                    ]"
                >
                    Name
                </label>
                <p v-if="nameError" class="mt-1 text-red-500 text-sm">{{ nameError }}</p>
            </div>

            <div class="relative w-full">
                <input
                    type="email"
                    v-model="formData.email"
                    :class="[
                        'block w-full px-4 pt-6 pb-2 text-white bg-transparent border rounded-md appearance-none focus:outline-none focus:ring-2 peer',
                        emailError
                            ? 'border-red-500 focus:ring-red-500'
                            : 'border-gray-300 focus:ring-blue-500'
                    ]"
                    placeholder=" "
                    @keyup.enter="nextStep"
                    @blur="validateEmailField"
                >
                <label
                    :class="[
                        'absolute duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] left-4',
                        'peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0',
                        'peer-focus:scale-75 peer-focus:-translate-y-3',
                        emailError ? 'text-red-500' : 'text-gray-500'
                    ]"
                >
                    Email
                </label>
                <p v-if="emailError" class="mt-1 text-red-500 text-sm">{{ emailError }}</p>
            </div>

            <button
                @click="nextStep"
                class="w-full rounded-full bg-white px-4 py-2 text-black font-medium hover:bg-gray-200"
                :disabled="!isValid"
            >
                Next
            </button>
        </div>
    </div>
</template>

<script>
export default {
    name: 'RegisterStepOne',
    data() {
        return {
            formData: {
                name: '',
                email: ''
            },
            nameError: '',
            emailError: ''
        }
    },
    computed: {
        isValid() {
            return this.formData.name.length > 0 &&
                this.formData.email.length > 0 &&
                this.isValidEmail(this.formData.email) &&
                !this.nameError &&
                !this.emailError
        }
    },
    methods: {
        isValidEmail() {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.formData.email)
        },
        validateName() {
            if (this.formData.name.length === 0) {
                this.nameError = 'Name is required'
            } else if (this.formData.name.length < 2) {
                this.nameError = 'Name must be at least 2 characters'
            } else {
                this.nameError = ''
            }
        },
        validateEmailField() {
            if (this.formData.email.length === 0) {
                this.emailError = 'Email address is required'
            } else if (!this.isValidEmail()) {
                this.emailError = 'Please enter a valid email address'
            } else {
                this.emailError = ''
            }
        },
        nextStep() {
            this.validateName()
            this.validateEmailField()

            if (this.isValid) {
                this.$emit('next', this.formData)
            }
        }
    },
    watch: {
        'formData.name'() {
            if (this.nameError) {
                this.nameError = ''
            }
        },
        'formData.email'() {
            if (this.emailError) {
                this.emailError = ''
            }
        }
    }
}
</script>
