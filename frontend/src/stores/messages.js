import { defineStore } from 'pinia'
import api from '@/services/axios'

export const useMessagesStore = defineStore('messages', {
  state: () => ({
    messages: [],
    isGenerating: false,
    isLoadingHistory: false
  }),

  actions: {
    async generateMessage(payload) {
      this.isGenerating = true
      try {
        const response = await api.post('/ai/generate-message', payload)
        return response.data.data
      } finally {
        this.isGenerating = false
      }
    },

    async fetchHistory(leadId) {
      this.isLoadingHistory = true
      try {
        const response = await api.get('/ai/messages', { params: { leadId } })
        this.messages = response.data.data
      } finally {
        this.isLoadingHistory = false
      }
    }
  }
})
