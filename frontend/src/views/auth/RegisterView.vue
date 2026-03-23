<template>
  <AuthLayout>
    <div class="mb-8 text-center lg:text-left">
      <h2 class="text-3xl font-extrabold text-gray-900 mb-2 tracking-tight">Start Growth</h2>
      <p class="text-gray-500 text-sm">Create your agency account to power your sales with AI.</p>
    </div>

    <form @submit.prevent="handleRegister" class="space-y-4">
      <div class="grid grid-cols-2 gap-4">
        <div class="col-span-2 sm:col-span-1">
          <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Owner Name</label>
          <input 
            v-model="form.name" type="text" required 
            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none transition-all"
          />
        </div>
        <div class="col-span-2 sm:col-span-1">
          <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Agency Name</label>
          <input 
            v-model="form.agency_name" type="text" required 
            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none transition-all"
          />
        </div>
      </div>

      <div>
        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Email Address</label>
        <input 
          v-model="form.email" type="email" required 
          class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none transition-all"
        />
      </div>

      <div>
        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Set Password</label>
        <input 
          v-model="form.password" type="password" required 
          class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none transition-all"
        />
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block text-xs font-bold text-gray-700 uppercase mb-1">City</label>
          <input 
            v-model="form.city" type="text" required 
            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none transition-all"
          />
        </div>
        <div>
          <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Phone (+91)</label>
          <input 
            v-model="form.phone" type="tel" required 
            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none transition-all"
          />
        </div>
      </div>

      <button 
        type="submit" 
        :disabled="loading"
        class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition-colors shadow-lg mt-4 disabled:opacity-50"
      >
        <span v-if="loading">Processing...</span>
        <span v-else>Register Agency</span>
      </button>

      <p class="text-center text-sm text-gray-500 mt-6 font-medium">
        Already have an account? 
        <router-link to="/login" class="text-blue-600 font-bold hover:underline">Sign in</router-link>
      </p>
    </form>

    <div v-if="error" class="mt-4 p-3 bg-red-50 text-red-600 rounded-lg text-sm border border-red-100 font-medium">
      {{ error }}
    </div>
  </AuthLayout>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import AuthLayout from '@/components/layout/AuthLayout.vue'

const router = useRouter()
const authStore = useAuthStore()

const form = reactive({
  name: '',
  agency_name: '',
  email: '',
  password: '',
  city: '',
  phone: ''
})

const loading = ref(false)
const error = ref('')

const handleRegister = async () => {
  loading.value = true
  error.value = ''
  
  const result = await authStore.register(form)
  
  if (result.success) {
    router.push('/admin/dashboard')
  } else {
    error.value = result.message
  }
  
  loading.value = false
}
</script>
