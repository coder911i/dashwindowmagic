<template>
  <span :class="[baseClasses, typeClasses]">
    <slot />
  </span>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  type: {
    type: String,
    default: 'primary' // primary, secondary, danger, warning, success
  }
})

const baseClasses = 'px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider'

const typeClasses = computed(() => {
  switch (props.type) {
    case 'danger': return 'bg-red-50 text-red-600 border border-red-100'
    case 'warning': return 'bg-amber-50 text-amber-600 border border-amber-100'
    case 'success': return 'bg-emerald-50 text-emerald-600 border border-emerald-100'
    case 'secondary': return 'bg-gray-50 text-gray-500 border border-gray-100'
    default: return 'bg-blue-50 text-blue-600 border border-blue-100'
  }
})
</script>
