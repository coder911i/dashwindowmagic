<template>
  <AuthLayout>
    <div class="mb-10 text-center lg:text-left">
      <h2 class="text-3xl font-extrabold text-gray-900 mb-2 font-inter tracking-tight">Welcome Back</h2>
      <p class="text-gray-500 font-inter">Enter your credentials to access your dashboard</p>
    </div>

    <form @submit.prevent="handleLogin" class="space-y-6">
      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2 font-inter">Email Address</label>
        <input 
          v-model="email" 
          type="email" 
          required 
          placeholder="rahul@estate.com"
          class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all placeholder:text-gray-400 font-inter"
        />
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2 font-inter">Password</label>
        <input 
          v-model="password" 
          type="password" 
          required 
          placeholder="••••••••"
          class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all placeholder:text-gray-400 font-inter"
        />
      </div>

      <div class="flex items-center justify-between">
        <label class="flex items-center gap-2 cursor-pointer">
          <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500" />
          <span class="text-sm text-gray-600 font-inter font-medium">Remember me</span>
        </label>
        <a href="#" class="text-sm font-bold text-blue-600 hover:text-blue-700 font-inter">Forgot password?</a>
      </div>

      <button 
        type="submit" 
        :disabled="loading"
        class="w-full py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition-colors shadow-lg shadow-blue-500/20 disabled:opacity-50 font-inter h-[44px]"
      >
        <span v-if="loading">Logging in...</span>
        <span v-else>Login to CRM</span>
      </button>

      <div class="relative py-4">
        <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-200"></div></div>
        <div class="relative flex justify-center text-sm"><span class="px-2 bg-white text-gray-500 font-inter">Or register your agency</span></div>
      </div>

      <router-link 
        to="/register" 
        class="w-full flex justify-center py-3.5 border-2 border-gray-200 hover:border-gray-300 text-gray-700 font-bold rounded-lg transition-all font-inter"
      >
        Create Admin Account
      </router-link>
    </form>

    <div v-if="error" class="mt-4 p-3 bg-red-50 text-red-600 rounded-lg text-sm font-medium border border-red-100 italic transition-all font-inter">
      {{ error }}
    </div>
  </AuthLayout>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import AuthLayout from '@/components/layout/AuthLayout.vue'

const email = ref('')
const password = ref('')
const loading = ref(false)
const error = ref('')
const router = useRouter()
const authStore = useAuthStore()

const handleLogin = async () => {
  loading.value = true
  error.value = ''
  
  const result = await authStore.login(email.value, password.value)
  
  if (result.success) {
    if (authStore.userRole === 'admin') {
      router.push('/admin/dashboard')
    } else {
      router.push('/agent/pipeline')
    }
  } else {
    error.value = result.message
  }
  
  loading.value = false
}
</script>
