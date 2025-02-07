import axios from 'axios';

class PostService {
    constructor() {
        this.axios = axios.create();

        const token = document.querySelector('meta[name="csrf-token"]');
        if (token) {
            this.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
        } else {
            console.error('CSRF token not found');
        }
    }

    async createPost(content, image = null) {
        try {
            const formData = new FormData();
            formData.append('content', content);
            if(image) {
                formData.append('image', image);
            }
            const response = await this.axios.post('/posts', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    'Accept': 'application/json'
                }
            });
            return response.data;
        } catch (error) {
            throw this.handleError(error);
        }
    }

    async getPosts() {
        try {
            const response = await this.axios.get('/posts', {
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            });
            return response.data;
        } catch (error) {
            throw this.handleError(error);
        }
    }

    async getUnfollowedPosts(){
        try {
            const response = await this.axios.get('/posts/discover', {
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            });
            return response.data;
        } catch(error) {
            throw this.handleError(error);
        }
    }

    handleError(error) {
        if (error.response) {
            if (error.response.status === 422) {
                throw new Error(error.response.data.message);
            }
            return error.response.data;
        }
        return { message: 'An unexpected error occurred' };
    }
}

export default new PostService();
