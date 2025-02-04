<template>
    <Modal :show="show" @close="$emit('close')">
        <LoginStepOne
            v-if="currentStep === 1"
            @next="handleStepOne"
        />
        <LoginStepTwo
            v-else-if="currentStep === 2"
            :email="email"
            @back="currentStep = 1"
            @close="$emit('close')"
        />
    </Modal>
</template>

<script>
import Modal from '../ui/Modal.vue'
import LoginStepOne from './LoginStepOne.vue'
import LoginStepTwo from './LoginStepTwo.vue'

export default {
    name: 'LoginModal',
    components: {
        Modal,
        LoginStepOne,
        LoginStepTwo
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
            email: ''
        }
    },
    methods: {
        handleStepOne(data) {
            this.email = data.email
            this.currentStep = 2
        }
    }
}
</script>
