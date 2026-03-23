import { computed } from 'vue'
import { useAuthStore } from '@/stores/auth'

export function useAuth() {
  const store = useAuthStore()

  return {
    user: computed(() => store.user),
    profile: computed(() => store.profile),
    isAuthenticated: computed(() => store.isAuthenticated),
    isAdmin: computed(() => store.userRole === 'admin'),
    isAgent: computed(() => store.userRole === 'agent'),
    isLoading: computed(() => store.isLoading),
    tenantId: computed(() => store.tenantId),
    
    login: store.login,
    logout: store.logout,
    register: store.register,
    acceptInvite: store.acceptInvite
  }
}
