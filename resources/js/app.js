import axios from 'axios';

window.axios = axios;

axios.defaults.headers.common['Accept'] = 'application/json';
axios.defaults.baseURL = import.meta.env.VITE_API_URL || '/api';
axios.interceptors.request.use(config => {
    const token = localStorage.getItem('token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
}, error => Promise.reject(error));

axios.interceptors.response.use(
    response => response,
    error => {
        if (error.response?.status === 401) {
            // token invalid / expired
            localStorage.removeItem('token');

            window.location.href = '/login';
        }
        return Promise.reject(error);
    }
);
