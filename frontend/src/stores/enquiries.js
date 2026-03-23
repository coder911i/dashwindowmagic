import { defineStore } from 'pinia'
import api from '@/services/axios'

export const useEnquiriesStore = defineStore('enquiries', {
  state: () => ({
    enquiries: [],
    loading: false
  }),

  actions: {
    async fetchEnquiries(filters = {}) {
      this.loading = true
      try {
        const response = await api.get('/enquiries', { params: filters })
        this.enquiries = response.data.data
      } finally {
        this.loading = false
      }
    },

    async updateEnquiry(id, data) {
      await api.put(`/enquiries/${id}`, data)
      await this.fetchEnquiries()
    },

    async bulkAction(ids, data) {
      await api.post('/enquiries/bulk', { ids, data })
      await this.fetchEnquiries()
    }
  }
})
