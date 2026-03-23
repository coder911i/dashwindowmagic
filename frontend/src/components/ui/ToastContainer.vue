<template>
  <div class="fixed top-6 right-6 z-[9999] flex flex-col gap-4 max-w-sm w-full">
    <transition-group name="toast">
      <div 
        v-for="toast in toasts" :key="toast.id"
        class="bg-white rounded-[24px] p-5 shadow-2xl border border-gray-100 flex items-start gap-4 animate-slide-in"
      >
        <div 
          :class="toast.type === 'success' ? 'bg-emerald-100 text-emerald-600' : 'bg-red-100 text-red-600'"
          class="p-2 rounded-xl shrink-0"
        >
          <CheckCircleIcon v-if="toast.type === 'success'" class="w-5 h-5" />
          <ExclamationCircleIcon v-else class="w-5 h-5" />
        </div>
        <div class="flex-1">
          <p class="text-xs font-black text-gray-900 uppercase tracking-tighter">{{ toast.title }}</p>
          <p class="text-[10px] font-bold text-gray-400 mt-1">{{ toast.message }}</p>
        </div>
        <button @click="remove(toast.id)" class="text-gray-300 hover:text-gray-900 transition-colors">
          <XMarkIcon class="w-4 h-4" />
        </button>
      </div>
    </transition-group>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { CheckCircleIcon, ExclamationCircleIcon, XMarkIcon } from '@heroicons/vue/24/outline'

// In a real app, this would use a global store (Pinia)
const toasts = ref([
//   { id: 1, type: 'success', title: 'Action Successful', message: 'Lead added to the CRM.' }
])

const remove = (id) => {
  toasts.value = toasts.value.filter(t => t.id !== id)
}

// exposing for future use
defineExpose({
  add: (toast) => {
    const id = Date.now()
    toasts.value.push({ ...toast, id })
    setTimeout(() => remove(id), 5000)
  }
})
</script>

<style scoped>
.toast-enter-active, .toast-leave-active {
  transition: all 0.3s ease;
}
.toast-enter-from {
  opacity: 0;
  transform: translateX(30px);
}
.toast-leave-to {
  opacity: 0;
  transform: scale(0.9);
}
</style>
