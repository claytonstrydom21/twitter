import axios from 'axios';

class FollowerService {
    constructor() {
        this.axios = axios.create();

        const token = document.querySelector('meta[name="csrf-token"]');
        if(token) {
            this.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
        }
    }

    async followUser(userId){
        try {
            const response = await this.axios.post(`/users/${userId}/follow`);
            return response.data;
        } catch (error) {
            throw this.handleError(error);
        }
    }

    async unfollowUser(userId){
        try {
            const response = await this.axios.delete(`/users/${userId}/follow`);
            return response.data;
        } catch (error) {
            throw this.handleError(error);
        }
    }

    handleError(error) {
        console.log('Full error:', error);
        console.log('Response data:', error.response?.data);
        console.log('Status:', error.response?.status);
        if (error.response?.status === 422) {
            throw new Error(error.response.data.message || 'An unexpected error occurred');
        }
        throw new Error('Network error occurred');
    }
}

export default new FollowerService();
