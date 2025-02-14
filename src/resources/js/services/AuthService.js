import axios from 'axios';

class AuthService {
    constructor() {
        this.axios = axios.create();

        const token = document.querySelector('meta[name="csrf-token"]');
        if (token) {
            this.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
        } else {
            console.error('CSRF token not found');
        }

        this.axios.defaults.headers.common['Accept'] = 'application/json';
    }

    async register(data) {
        try {
            const response = await this.axios.post('/register', data, {
                headers: {
                    'Content-Type': data instanceof FormData ? 'multipart/form-data' : 'application/json'
                }
            });
            return response.data;
        } catch (error) {
            console.error('Full error:', error);
            if (error.response) {
                console.error('Registration Error Response:', error.response.data);
                if (error.response.status === 422) {
                    const errors = error.response.data.errors || {};
                    const errorMessage = Object.values(errors)[0]?.[0] || 'Validation failed';
                    throw new Error(errorMessage);
                }
                throw new Error(error.response.data.message || 'Registration failed');
            }
            throw error;
        }
    }

    async setUsername(username) {
        try {
            const response = await this.axios.post('/set-username', { username });
            return response.data;
        } catch (error) {
            if (error.response && error.response.status === 422) {
                throw new Error(error.response.data.message);
            }
            throw new Error('An error occurred while setting username');
        }
    }

    async login(credentials) {
        try {
            const response = await this.axios.post('/login', credentials);
            return response.data;
        } catch (error) {
            throw this.handleError(error);
        }
    }

    handleError(error) {
        if (error.response) {
            return error.response.data;
        }
        return { message: 'An unexpected error occurred' };
    }
}

export default new AuthService();
