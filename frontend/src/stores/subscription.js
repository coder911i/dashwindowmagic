import { defineStore } from 'pinia'
import api from '@/services/axios'

export const useSubscriptionStore = defineStore('subscription', {
  state: () => ({
    current: null,
    plans: [],
    loading: false
  }),

  actions: {
    async fetchCurrent() {
      this.loading = true
      try {
        const response = await api.get('/subscription/current')
        this.current = response.data.data
      } finally {
        this.loading = false
      }
    },

    async createOrder(planKey) {
      const response = await api.post('/subscription/create-order', { planKey })
      return response.data.data
    },

    async verifyPayment(paymentData) {
      await api.post('/subscription/verify-payment', paymentData)
      await this.fetchCurrent()
    }
  }
})
