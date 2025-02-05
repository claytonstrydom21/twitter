<template>
    <Modal :show="show" @close="$emit('close')">
        <LoginStepOne
            v-if="currentStep === 1"
            @next="handleStepOne"
            :loading="loading"
        />
        <LoginStepTwo
            v-else-if="currentStep === 2"
            :email="email"
            :loading="loading"
            :error="error"
            @back="handleBack"
            @submit="handleStepTwo"
        />
    </Modal>
</template>

<script>
import AuthService from '../../services/AuthService';
import Modal from '../ui/Modal.vue';
import LoginStepOne from './LoginStepOne.vue';
import LoginStepTwo from './LoginStepTwo.vue';

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
            email: '',
            error: '',
            loading: false
        }
    },
    methods: {
        handleStepOne(data) {
            this.email = data.email;
            this.currentStep = 2;
            this.error = '';
        },
        handleBack() {
            this.currentStep = 1;
            this.error = '';
        },
        async handleStepTwo(data) {
            this.loading = true;
            this.error = '';

            try {
                await AuthService.login({
                    email: this.email,
                    password: data.password
                });

                window.location.href = '/';
            } catch (error) {
                this.error = error.message || 'Invalid credentials';
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>
