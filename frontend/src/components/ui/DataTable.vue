<template>
  <div class="bg-white rounded-[32px] border border-gray-100 overflow-hidden shadow-sm">
    <table class="w-full text-left">
      <thead>
        <tr class="bg-gray-50/50">
          <th v-for="col in columns" :key="col.key" 
            class="px-8 py-5 text-[10px] font-black text-gray-400 border-b border-gray-100 uppercase tracking-widest">
            {{ col.label }}
          </th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-50">
        <tr v-for="(row, idx) in data" :key="idx" @click="$emit('row-click', row)" 
          class="hover:bg-blue-50/30 transition-all cursor-pointer group">
          <td v-for="col in columns" :key="col.key" class="px-8 py-5">
            <slot :name="col.key" :row="row" :value="row[col.key]">
              <span class="text-xs font-bold text-gray-800">{{ row[col.key] || '-' }}</span>
            </slot>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
defineProps({
  columns: { type: Array, required: true },
  data: { type: Array, required: true }
})

defineEmits(['row-click'])
</script>
