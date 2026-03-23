<template>
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
    <div v-for="(img, idx) in images" :key="idx" class="aspect-square rounded-3xl overflow-hidden relative group">
      <img :src="img" class="w-full h-full object-cover" />
      <button @click="$emit('remove', idx)" class="absolute top-2 right-2 w-8 h-8 bg-black/50 backdrop-blur text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
        <XMarkIcon class="w-4 h-4" />
      </button>
    </div>
    
    <label class="aspect-square rounded-[32px] border-2 border-dashed border-gray-100 bg-gray-50 flex flex-col items-center justify-center cursor-pointer hover:border-blue-600 hover:bg-blue-50/50 transition-all">
      <div class="w-10 h-10 bg-white rounded-xl shadow-sm flex items-center justify-center mb-2">
        <PhotoIcon class="w-5 h-5 text-blue-600" />
      </div>
      <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Add Image</p>
      <input type="file" multiple class="hidden" accept="image/*" @change="handleUpload" />
    </label>
  </div>
</template>

<script setup>
import { PhotoIcon, XMarkIcon } from '@heroicons/vue/24/outline'

const props = defineProps({ images: Array })
const emit = defineEmits(['upload', 'remove'])

const handleUpload = (e) => {
  const files = Array.from(e.target.files)
  emit('upload', files)
}
</script>
