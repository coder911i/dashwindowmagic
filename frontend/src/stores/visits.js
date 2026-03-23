import { defineStore } from 'pinia'
import api from '@/services/axios'

export const useVisitsStore = defineStore('visits', {
  state: () => ({
    visits: [],
    loading: false
  }),

  actions: {
    async fetchVisits(filters = {}) {
      this.loading = true
      try {
        const response = await api.get('/visits', { params: filters })
        this.visits = response.data.data
      } finally {
        this.loading = false
      }
    },

    async scheduleVisit(data) {
      await api.post('/visits', data)
      await this.fetchVisits()
    },

    async updateStatus(id, status) {
      await api.put(`/visits/${id}`, { status })
      await this.fetchVisits()
    }
  }
})
