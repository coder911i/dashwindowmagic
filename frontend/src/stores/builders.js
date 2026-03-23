import { defineStore } from 'pinia'
import api from '@/services/axios'

export const useBuildersStore = defineStore('builders', {
  state: () => ({
    builders: [],
    commissions: [],
    loading: false
  }),

  actions: {
    async fetchBuilders() {
      this.loading = true
      try {
        const res = await api.get('/builders')
        this.builders = res.data.data
      } finally {
        this.loading = false
      }
    },

    async fetchCommissions() {
      const res = await api.get('/commissions')
      this.commissions = res.data.data
    },

    async logPayment(id, paymentData) {
      await api.post(`/commissions/${id}/payment`, paymentData)
      await this.fetchCommissions()
    }
  }
})
