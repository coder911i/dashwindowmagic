<template>
  <div class="overflow-x-auto">
    <table class="w-full text-left border-collapse">
      <thead>
        <tr class="border-b border-gray-50">
          <th v-for="col in columns" :key="col" class="p-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">
            {{ col }}
          </th>
        </tr>
      </thead>
      <tbody>
        <tr 
          v-for="record in records" :key="record.id"
          class="group border-b border-gray-50 hover:bg-gray-50 transition-all"
        >
          <td class="p-6">
            <p class="text-[12px] font-black text-gray-900 tracking-tight">{{ record.projectName || 'Unnamed Project' }}</p>
            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">{{ record.state }}</p>
          </td>
          <td class="p-6 font-mono text-[11px] font-bold text-gray-500">{{ record.reraNumber }}</td>
          <td class="p-6">
            <p class="text-[11px] font-bold text-gray-700">{{ record.developerName || 'N/A' }}</p>
          </td>
          <td class="p-6">
            <div class="flex flex-col">
              <span class="text-[10px] font-black uppercase tracking-tighter" :class="getExpiryColor(record.expiryDate)">
                {{ record.expiryDate || 'N/A' }}
              </span>
              <span v-if="isExpiringSoon(record.expiryDate)" class="text-[8px] font-black text-orange-500 uppercase tracking-tighter">Expiring Soon ⚠️</span>
            </div>
          </td>
          <td class="p-6">
            <span class="px-2 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-[9px] font-black uppercase tracking-widest border border-emerald-100">
              Validated
            </span>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
defineProps({
  records: Array
})

const columns = ['Project / State', 'RERA Number', 'Developer', 'Expiry Date', 'Status']

const getExpiryColor = (date) => {
  if (!date) return 'text-gray-400'
  const expiry = new Date(date)
  const now = new Date()
  if (expiry < now) return 'text-red-600'
  return 'text-gray-900'
}

const isExpiringSoon = (date) => {
  if (!date) return false
  const expiry = new Date(date)
  const now = new Date()
  const diffTime = expiry - now
  const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24))
  return diffDays > 0 && diffDays < 60
}
</script>
