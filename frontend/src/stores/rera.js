import { defineStore } from 'pinia'
import api from '@/services/axios'

export const useReraStore = defineStore('rera', {
  state: () => ({
    records: [],
    loading: false
  }),

  actions: {
    async fetchRecords() {
      this.loading = true
      try {
        const response = await api.get('/rera')
        this.records = response.data.data
      } finally {
        this.loading = false
      }
    },

    async validateFormat(state, reraNumber) {
      const response = await api.post('/rera/validate', { state, reraNumber })
      return response.data.data
    },

    async addRecord(data) {
      await api.post('/rera', data)
      await this.fetchRecords()
    }
  }
})
