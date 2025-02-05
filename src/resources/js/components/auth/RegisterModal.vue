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
import AuthService from "../../services/AuthService.js";
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
            try {
                const response = await AuthService.register({
                    ...this.registrationData,
                    ...data
                });
                window.location.href = response.redirect;
            } catch (error) {
                console.error('Registration error:', error);
            }
        }
    }
}
</script>
