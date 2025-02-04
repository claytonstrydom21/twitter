<template>
    <Modal :show="show" @close="$emit('close')">
        <div v-if="currentStep === 1">
            <RegisterStepOne
                @next="handleStepOne"
            />
        </div>
        <div v-else-if="currentStep === 2">
            <RegisterStepTwo
                @back="currentStep = 1"
                @submit="handleStepTwo"
            />
        </div>
    </Modal>
</template>

<script>
import Modal from '../ui/Modal.vue'
import RegisterStepOne from './RegisterStepOne.vue'
import RegisterStepTwo from './RegisterStepTwo.vue'

export default {
    name: 'RegisterModal',
    components: {
        Modal,
        RegisterStepOne,
        RegisterStepTwo
    },
    props: {
        show: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            currentStep: 1,
            registrationData: {}
        }
    },
    methods: {
        handleStepOne(data) {
            this.registrationData = { ...data }
            this.currentStep = 2
        },
        async handleStepTwo(data) {
            const formData = {
                ...this.registrationData,
                ...data,
                _token: document.querySelector('meta[name="csrf-token"]').content
            }

            try {
                const response = await fetch('/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(formData)
                })

                if (response.ok) {
                    window.location.href = '/set-username'
                } else {
                    const error = await response.json()
                    console.error('Registration failed:', error)
                }
            } catch (error) {
                console.error('Registration error:', error)
            }
        }
    }
}
</script>
