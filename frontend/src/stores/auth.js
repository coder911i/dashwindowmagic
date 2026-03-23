import { defineStore } from 'pinia'
import { auth } from '@/services/firebase'
import api from '@/services/axios'
import { 
  signInWithEmailAndPassword, 
  signOut, 
  onAuthStateChanged,
  getIdTokenResult
} from 'firebase/auth'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    profile: null,
    isAuthenticated: false,
    userRole: null,
    tenantId: null,
    isLoading: true
  }),

  actions: {
    async login(email, password) {
      try {
        const userCredential = await signInWithEmailAndPassword(auth, email, password)
        await this.syncProfile(userCredential.user)
        return { success: true }
      } catch (error) {
        return { success: false, message: error.message }
      }
    },

    async register(formData) {
      try {
        // 1. Call Laravel API (it handles Firebase creation and claim setting)
        const response = await api.post('/auth/register', formData)
        
        // 2. Sign in with Firebase now that account exists
        const userCredential = await signInWithEmailAndPassword(auth, formData.email, formData.password)
        await this.syncProfile(userCredential.user)
        
        return { success: true }
      } catch (error) {
        return { success: false, message: error.response?.data?.message || 'Registration failed.' }
      }
    },

    async acceptInvite(data) {
      try {
        const response = await api.post('/auth/accept-invite', data)
        const userCredential = await signInWithEmailAndPassword(auth, response.data.email, data.password)
        await this.syncProfile(userCredential.user)
        return { success: true }
      } catch (error) {
        return { success: false, message: error.response?.data?.message || 'Invitation acceptance failed.' }
      }
    },

    async logout() {
      await signOut(auth)
      this.$reset()
    },

    async syncProfile(user) {
      const tokenResult = await getIdTokenResult(user)
      this.user = user
      this.isAuthenticated = true
      this.userRole = tokenResult.claims.role
      this.tenantId = tokenResult.claims.tenantId
      
      // Fetch local profile data from Laravel
      const response = await api.get('/auth/me')
      this.profile = response.data
    },

    init() {
      return new Promise((resolve) => {
        onAuthStateChanged(auth, async (user) => {
          if (user) {
            try {
              await this.syncProfile(user)
            } catch (e) {
              console.error('Failed to sync profile', e)
              await this.logout()
            }
          } else {
            this.isAuthenticated = false
          }
          this.isLoading = false
          resolve(user)
        })
      })
    }
  }
})
