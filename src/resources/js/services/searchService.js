import axios from 'axios';

class SearchService {
    constructor() {
        this.axios = axios.create();

        const token = document.querySelector('meta[name="csrf-token"]');
        if(token) {
            this.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
        }
    }

    async searchUsers(query)
    {
        try {
            const response = await this.axios.get('/search/users', {
                params: { query }
            });
            return response.data;
        } catch (error) {
            throw this.handleError(error);
        }
    }

    handleError(error) {
        if (error.response?.status === 422) {
            throw new Error(error.response.data.message);
        }
        return { message: 'An unexpected error occurred' };
    }
}

export default new SearchService();
