import { defineStore } from 'pinia'
import api from '@/services/axios'

export const usePropertiesStore = defineStore('properties', {
  state: () => ({
    properties: [],
    loading: false
  }),

  actions: {
    async fetchProperties() {
      this.loading = true
      try {
        const response = await api.get('/properties')
        this.properties = response.data.data
      } finally {
        this.loading = false
      }
    },

    async createProperty(data) {
      const response = await api.post('/properties', data)
      this.properties.unshift(response.data.data)
      return response.data.data
    },

    async deleteProperty(id) {
      await api.delete(`/properties/${id}`)
      this.properties = this.properties.filter(p => p.id !== id)
    }
  }
})
