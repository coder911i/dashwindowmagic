import { defineStore } from 'pinia'
import { db } from '@/services/firebase'
import api from '@/services/axios'
import { 
  collection, 
  query, 
  onSnapshot, 
  doc, 
  updateDoc,
  where
} from 'firebase/firestore'
import { useAuthStore } from './auth'

export const useLeadsStore = defineStore('leads', {
  state: () => ({
    leads: [],
    currentLead: null,
    isLoading: false,
    unsubscribe: null
  }),

  getters: {
    leadsByStatus: (state) => (status) => {
      return state.leads.filter(l => l.status === status)
    }
  },

  actions: {
    // Real-time synchronization
    initRealtime() {
      // Don't duplicate listeners
      if (this.unsubscribe) return

      const authStore = useAuthStore()
      if (!authStore.tenantId) return

      const leadsRef = collection(db, 'tenants', authStore.tenantId, 'leads')
      
      let q = query(leadsRef)
      if (authStore.userRole !== 'admin') {
        const uid = authStore.user?.uid || authStore.profile?.firebase_uid
        if (uid) {
          q = query(leadsRef, where('agentId', '==', uid))
        }
      }

      this.unsubscribe = onSnapshot(q, (snapshot) => {
        this.leads = snapshot.docs.map(doc => ({
          id: doc.id,
          ...doc.data()
        }))
      }, (error) => {
        console.error("Firestore Listen Error:", error)
      })
    },

    stopRealtime() {
      if (this.unsubscribe) {
        this.unsubscribe()
        this.unsubscribe = null
      }
    },

    /** Alias for initializing realtime if needed by generic components */
    async fetchLeads() {
      this.initRealtime()
    },

    async fetchLeadById(id) {
      if (!id) return null
      this.isLoading = true
      try {
        const response = await api.get(`/leads/${id}`)
        this.currentLead = response.data.data
        return this.currentLead
      } catch (err) {
        console.error("Fetch lead error:", err)
        return null
      } finally {
        this.isLoading = false
      }
    },

    async createLead(formData) {
      return await api.post('/leads', formData)
    },

    async updateLead(id, data) {
      return await api.put(`/leads/${id}`, data)
    },

    async updateLeadStatus(id, status) {
      const authStore = useAuthStore()
      const leadRef = doc(db, 'tenants', authStore.tenantId, 'leads', id)
      await updateDoc(leadRef, { 
        status, 
        updatedAt: new Date() 
      })
    },

    async scoreLead(id) {
      return await api.post(`/leads/${id}/score`)
    }
  }
})
