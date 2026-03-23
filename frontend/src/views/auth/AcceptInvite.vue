<template>
  <AuthLayout>
    <div class="mb-8 text-center lg:text-left">
      <h2 class="text-3xl font-extrabold text-gray-900 mb-2 tracking-tight uppercase">Join the Team</h2>
      <p class="text-gray-500 text-sm font-medium">Complete your profile to start managing leads with AI.</p>
    </div>

    <form @submit.prevent="handleAccept" class="space-y-6">
      <div>
        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Invitation Token</label>
        <input 
          v-model="token" type="text" readonly
          class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 text-gray-400 text-xs font-bold outline-none"
        />
      </div>

      <div>
        <label class="block text-[10px] font-black text-gray-700 uppercase tracking-widest mb-1">Full Name</label>
        <input 
          v-model="name" type="text" required placeholder="Arun Kumar"
          class="w-full px-4 py-3 rounded-xl border border-gray-200 outline-none focus:ring-2 focus:ring-blue-500/10 transition-all font-bold text-sm"
        />
      </div>

      <div>
        <label class="block text-[10px] font-black text-gray-700 uppercase tracking-widest mb-1">New Password</label>
        <input 
          v-model="password" type="password" required placeholder="••••••••"
          class="w-full px-4 py-3 rounded-xl border border-gray-200 outline-none focus:ring-2 focus:ring-blue-500/10 transition-all font-bold text-sm"
        />
      </div>

      <button 
        type="submit" :disabled="loading"
        class="w-full py-4 bg-blue-600 text-white font-black rounded-xl transition-all shadow-xl shadow-blue-500/20 uppercase tracking-widest text-xs hover:bg-blue-700 disabled:opacity-50"
      >
        <span v-if="loading">Finalizing...</span>
        <span v-else>Accept Invitation</span>
      </button>
    </form>

    <div v-if="error" class="mt-6 p-4 bg-red-50 text-red-600 rounded-xl text-xs font-bold border border-red-100 italic">
      {{ error }}
    </div>
  </AuthLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import AuthLayout from '@/components/layout/AuthLayout.vue'

const router = useRouter()
const route = useRoute()
const authStore = useAuthStore()

const token = ref('')
const name = ref('')
const password = ref('')
const loading = ref(false)
const error = ref('')

onMounted(() => {
  token.value = route.query.token || ''
})

const handleAccept = async () => {
  if (!token.value) {
    error.value = "Invite token is missing."
    return
  }
  
  loading.value = true
  error.value = ''
  
  try {
    const result = await authStore.acceptInvite({
      token: token.value,
      name: name.value,
      password: password.value
    })
    
    if (result.success) {
      router.push('/login')
    } else {
      error.value = result.message
    }
  } catch (err) {
    error.value = "An unexpected error occurred. Please try again."
  } finally {
    loading.value = false
  }
}
</script>
