<template>
  <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm relative overflow-hidden group">
    <!-- Sparkline Graphic Placeholder -->
    <div class="absolute bottom-0 left-0 w-full h-1 opacity-20 bg-blue-600 group-hover:h-2 transition-all"></div>

    <div class="flex items-start justify-between mb-2">
      <div class="p-2 bg-blue-50 rounded-lg">
        <slot name="icon" />
      </div>
      <div v-if="trend" :class="[trend > 0 ? 'text-emerald-600' : 'text-red-600', 'text-xs font-bold flex items-center bg-gray-50 px-1.5 py-0.5 rounded']">
        {{ trend > 0 ? '↑' : '↓' }} {{ Math.abs(trend) }}%
      </div>
    </div>

    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">{{ label }}</p>
    <div class="flex items-baseline gap-2">
      <h3 class="text-3xl font-extrabold text-gray-900 tracking-tight">{{ value }}</h3>
      <span v-if="suffix" class="text-sm font-bold text-gray-400">{{ suffix }}</span>
    </div>
  </div>
</template>

<script setup>
defineProps({
  label: String,
  value: [String, Number],
  suffix: String,
  trend: Number
})
</script>
