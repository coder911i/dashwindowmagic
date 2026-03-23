import axios from 'axios'
import { useAuthStore } from '@/stores/auth'

const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL || 'http://localhost:8000/api',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  }
})

// Request interceptor for API tokens
api.interceptors.request.use(async (config) => {
  const authStore = useAuthStore()
  const token = await authStore.getToken()
  
  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }
  
  return config
}, (error) => {
  return Promise.reject(error)
})

// Response interceptor for global error handling
api.interceptors.response.use((response) => {
  return response
}, (error) => {
  const authStore = useAuthStore()
  
  if (error.response?.status === 401) {
    // Session expired or invalid
    authStore.logout()
    window.location.href = '/login'
  } else if (error.response?.status === 403) {
    console.error("Access Denied: You don't have permission for this action.")
  }
  
  return Promise.reject(error)
})

export default api
