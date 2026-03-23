<template>
  <div class="h-full bg-gray-50 overflow-y-auto">
    <div class="p-8 md:p-12 max-w-7xl mx-auto w-full">
      <div class="flex justify-between items-center mb-16">
        <div class="text-left">
          <h1 class="text-4xl font-black text-gray-900 tracking-tighter uppercase mb-2">Inventory</h1>
          <p class="text-gray-400 text-xs font-bold uppercase tracking-[0.2em] italic">Manage your property portfolio</p>
        </div>
        <button 
          @click="showForm = true"
          class="bg-gray-900 text-white px-8 py-4 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-black shadow-xl transition-all"
        >
          + Add Property
        </button>
      </div>

      <!-- Properties Grid -->
      <div v-if="store.loading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <div v-for="i in 6" :key="i" class="h-[400px] bg-white rounded-[40px] animate-pulse border border-gray-100"></div>
      </div>

      <div v-else-if="store.properties.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <div v-for="property in store.properties" :key="property.id" 
          class="bg-white rounded-[40px] overflow-hidden border border-gray-100 shadow-sm hover:shadow-2xl transition-all group flex flex-col text-left">
          
          <div class="h-56 bg-gray-100 relative overflow-hidden">
            <img v-if="property.images?.[0]" :src="property.images[0]" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
            <div v-else class="w-full h-full flex items-center justify-center text-gray-300">
              <PhotoIcon class="w-12 h-12" />
            </div>
            <div class="absolute top-4 right-4">
              <span class="px-4 py-1.5 bg-white/90 backdrop-blur rounded-full text-[10px] font-black uppercase tracking-widest text-gray-900">
                {{ property.status || 'Available' }}
              </span>
            </div>
          </div>

          <div class="p-8 flex-1 flex flex-col">
            <div class="flex justify-between items-start mb-4">
              <h3 class="text-lg font-black text-gray-900 leading-tight uppercase tracking-tight">{{ property.title }}</h3>
              <span v-if="property.reraNumber" class="text-[9px] font-black text-blue-600 bg-blue-50 px-2 py-1 rounded">RERA</span>
            </div>
            
            <div class="flex items-center gap-2 mb-6">
              <MapPinIcon class="w-4 h-4 text-gray-400" />
              <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ property.locality }}, {{ property.city }}</span>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-8">
              <div class="p-4 bg-gray-50 rounded-2xl text-center">
                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Price</p>
                <p class="text-xs font-black text-gray-900">₹{{ property.price / 100000 }}L</p>
              </div>
              <div class="p-4 bg-gray-50 rounded-2xl text-center">
                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Configuration</p>
                <p class="text-xs font-black text-gray-900">{{ property.bhk }} BHK</p>
              </div>
            </div>

            <div class="mt-auto flex gap-3">
              <button class="flex-1 py-4 bg-gray-50 text-gray-900 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-gray-100 transition-all">Edit</button>
              <button @click="store.deleteProperty(property.id)" class="py-4 px-6 bg-red-50 text-red-500 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-red-100 transition-all">
                <TrashIcon class="w-4 h-4" />
              </button>
            </div>
          </div>
        </div>
      </div>

      <div v-else class="h-[60vh] flex flex-col items-center justify-center space-y-6">
        <div class="w-32 h-32 bg-gray-100 rounded-[40px] flex items-center justify-center text-gray-300">
          <HomeModernIcon class="w-16 h-16" />
        </div>
        <div class="text-center">
          <h3 class="text-lg font-black text-gray-900 uppercase tracking-widest">Inventory is empty</h3>
          <p class="text-gray-400 text-xs font-bold uppercase tracking-widest mt-2">Add your first property to get started</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { usePropertiesStore } from '@/stores/properties'
import { MapPinIcon, PhotoIcon, TrashIcon, HomeModernIcon } from '@heroicons/vue/24/outline'

const store = usePropertiesStore()
const showForm = ref(false)

onMounted(() => {
  store.fetchProperties()
})
</script>
