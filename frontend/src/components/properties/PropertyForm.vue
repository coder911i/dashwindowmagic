<template>
  <div class="space-y-8">
    <div v-if="step === 1" class="space-y-8">
      <div class="grid grid-cols-2 gap-6">
        <div class="space-y-2">
          <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-2">Project Title</label>
          <input v-model="form.title" required type="text" placeholder="Cybercity Heights" 
            class="w-full px-6 py-4 bg-gray-50 rounded-2xl border-none text-xs font-bold text-gray-900 focus:ring-2 focus:ring-blue-600 outline-none transition-all" />
        </div>
        <div class="space-y-2">
          <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-2">Property Type</label>
          <select v-model="form.type" class="w-full px-6 py-4 bg-gray-50 rounded-2xl border-none text-xs font-bold text-gray-900 focus:ring-2 focus:ring-blue-600 outline-none transition-all">
            <option value="apartment">Apartment</option>
            <option value="villa">Villa</option>
            <option value="plot">Plot</option>
          </select>
        </div>
      </div>
      <div class="grid grid-cols-2 gap-6">
        <div class="space-y-2">
          <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-2">Price (₹)</label>
          <input v-model.number="form.price" required type="number" 
            class="w-full px-6 py-4 bg-gray-50 rounded-2xl border-none text-xs font-bold text-gray-900 focus:ring-2 focus:ring-blue-600 outline-none transition-all" />
        </div>
        <div class="space-y-2">
          <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-2">Area (Sqft)</label>
          <input v-model.number="form.area_sqft" required type="number" 
            class="w-full px-6 py-4 bg-gray-50 rounded-2xl border-none text-xs font-bold text-gray-900 focus:ring-2 focus:ring-blue-600 outline-none transition-all" />
        </div>
      </div>
    </div>

    <div v-if="step === 2" class="space-y-8">
      <div class="grid grid-cols-2 gap-6">
        <div class="space-y-2">
          <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-2">Locality</label>
          <input v-model="form.locality" required type="text" placeholder="Whitefield" 
            class="w-full px-6 py-4 bg-gray-50 rounded-2xl border-none text-xs font-bold text-gray-900 focus:ring-2 focus:ring-blue-600 outline-none transition-all" />
        </div>
        <div class="space-y-2">
          <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-2">City</label>
          <input v-model="form.city" required type="text" placeholder="Bengaluru" 
            class="w-full px-6 py-4 bg-gray-50 rounded-2xl border-none text-xs font-bold text-gray-900 focus:ring-2 focus:ring-blue-600 outline-none transition-all" />
        </div>
      </div>
      <div class="space-y-2">
        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-2">RERA Number</label>
        <input v-model="form.reraNumber" type="text" placeholder="PRM/KA/RERA/..." 
          class="w-full px-6 py-4 bg-gray-50 rounded-2xl border-none text-xs font-bold text-gray-900 focus:ring-2 focus:ring-blue-600 outline-none transition-all" />
      </div>
    </div>

    <!-- Navigation -->
    <div class="flex justify-between pt-8 border-t border-gray-50">
      <button v-if="step > 1" @click="step--" class="text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-gray-900">Back</button>
      <div v-else></div>
      <button v-if="step < 2" @click="step++" class="bg-blue-600 text-white px-10 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl">Next Step</button>
      <button v-else @click="submit" :disabled="loading" class="bg-gray-900 text-white px-10 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl disabled:opacity-50">
        {{ loading ? 'Publishing...' : 'Publish Property' }}
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { usePropertiesStore } from '@/stores/properties'

const props = defineProps({ property: Object })
const emit = defineEmits(['success'])
const store = usePropertiesStore()

const step = ref(1)
const loading = ref(false)
const form = ref(props.property ? { ...props.property } : {
  title: '', type: 'apartment', price: 0, area_sqft: 0,
  locality: '', city: '', reraNumber: '', bhk: '3', status: 'available'
})

const submit = async () => {
  loading.value = true
  try {
    if (props.property) {
      await store.updateProperty(props.property.id, form.value)
    } else {
      await store.addProperty(form.value)
    }
    emit('success')
  } catch (err) {
    alert('Failed to save property.')
  } finally {
    loading.value = false
  }
}
</script>
