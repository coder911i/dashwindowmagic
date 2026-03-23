<template>
  <Transition name="fade">
    <div v-if="show" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
      <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" @click="$emit('close')"></div>
      
      <Transition name="zoom">
        <div v-if="show" class="bg-white rounded-[40px] shadow-2xl w-full max-w-2xl relative overflow-hidden border border-white/20">
          <!-- Header -->
          <div class="px-8 py-6 border-b border-gray-50 flex justify-between items-center">
            <h3 class="text-xs font-black text-gray-900 uppercase tracking-widest">{{ title }}</h3>
            <button @click="$emit('close')" class="text-gray-400 hover:text-gray-900 transition-colors">
              <XMarkIcon class="w-6 h-6" />
            </button>
          </div>

          <!-- Body -->
          <div class="px-8 py-8 overflow-y-auto max-h-[70vh]">
            <slot />
          </div>

          <!-- Footer -->
          <div v-if="$slots.footer" class="px-8 py-6 bg-gray-50 border-t border-gray-100 flex justify-end gap-4">
            <slot name="footer" />
          </div>
        </div>
      </Transition>
    </div>
  </Transition>
</template>

<script setup>
import { XMarkIcon } from '@heroicons/vue/24/outline'

defineProps({
  show: Boolean,
  title: String
})

defineEmits(['close'])
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.3s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }

.zoom-enter-active, .zoom-leave-active { transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1); }
.zoom-enter-from { opacity: 0; transform: scale(0.9) translateY(20px); }
.zoom-leave-to { opacity: 0; transform: scale(0.95); }
</style>
