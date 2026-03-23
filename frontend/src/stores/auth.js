import { defineStore } from 'pinia'
import { auth } from '@/services/firebase'
import { 
  signInWithEmailAndPassword, 
  signOut, 
  onAuthStateChanged,
  getIdTokenResult
} from 'firebase/auth'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    isAuthenticated: false,
    userRole: null,
    tenantId: null,
    isLoading: true
  }),

  actions: {
    async login(email, password) {
      try {
        const userCredential = await signInWithEmailAndPassword(auth, email, password)
        await this.fetchUserClaims(userCredential.user)
        return { success: true }
      } catch (error) {
        return { success: false, message: error.message }
      }
    },

    async logout() {
      await signOut(auth)
      this.user = null
      this.isAuthenticated = false
      this.userRole = null
      this.tenantId = null
    },

    async fetchUserClaims(user) {
      const tokenResult = await getIdTokenResult(user)
      this.user = user
      this.isAuthenticated = true
      this.userRole = tokenResult.claims.role || 'agent'
      this.tenantId = tokenResult.claims.tenantId
    },

    init() {
      return new Promise((resolve) => {
        onAuthStateChanged(auth, async (user) => {
          if (user) {
            await this.fetchUserClaims(user)
          } else {
            this.user = null
            this.isAuthenticated = false
          }
          this.isLoading = false
          resolve(user)
        })
      })
    }
  }
})
