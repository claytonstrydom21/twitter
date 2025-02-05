import axios from 'axios';

class AuthService {
    constructor() {
        this.axios = axios.create({
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });
    }

    async register(data) {
        try {
            const response = await this.axios.post('/register', data);
            return response.data;
        } catch (error) {
            throw this.handleError(error);
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
