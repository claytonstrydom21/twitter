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
        <div v-else-if="currentStep === 3">
            <RegisterStepThree
                @back="currentStep = 2"
                @submit="handleStepThree"
            />
        </div>
    </Modal>
</template>

<script>
import AuthService from "../../services/AuthService.js";
import Modal from '../ui/Modal.vue'
import RegisterStepOne from './RegisterStepOne.vue'
import RegisterStepTwo from './RegisterStepTwo.vue'
import RegisterStepThree from './RegisterStepThree.vue'

export default {
    name: 'RegisterModal',
    components: {
        Modal,
        RegisterStepOne,
        RegisterStepTwo,
        RegisterStepThree
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
        handleStepTwo(data) {
            this.registrationData = {
                ...this.registrationData,
                ...data
            }
            this.currentStep = 3
        },
        async handleStepThree(data) {
            try {
                const formData = new FormData();

                console.log('Full Registration Data:', {
                    name: this.registrationData.name,
                    email: this.registrationData.email,
                    avatar: data.avatar
                });

                formData.append('name', this.registrationData.name);
                formData.append('email', this.registrationData.email);
                formData.append('password', this.registrationData.password);
                formData.append('password_confirmation', this.registrationData.password_confirmation);

                if (data.avatar) {
                    console.log('Avatar File Details:', {
                        name: data.avatar.name,
                        size: data.avatar.size,
                        type: data.avatar.type
                    });
                    formData.append('avatar', data.avatar);
                }

                const response = await AuthService.register(formData);
                console.log('reg res:' , response);
                window.location.href = response.redirect;
            } catch (error) {
                console.error('Registration error:', error);
            }
        }
    }
}
</script>
