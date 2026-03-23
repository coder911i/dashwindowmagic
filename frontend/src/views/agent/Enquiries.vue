<template>
  <div class="h-full flex flex-col bg-white overflow-hidden rounded-3xl m-8 shadow-sm border border-gray-100">
    <EnquiryFilters @filter="handleFilter" />
    
    <div class="flex-1 overflow-y-auto">
      <div v-if="loading" class="p-12 space-y-4">
        <div v-for="i in 5" :key="i" class="h-16 bg-gray-50 rounded-2xl animate-pulse"></div>
      </div>
      
      <EnquiryTable 
        v-else
        :enquiries="enquiries"
        :selectedIds="selectedIds"
        :isAllSelected="isAllSelected"
        @toggleSelect="toggleSelect"
        @toggleSelectAll="toggleSelectAll"
      />
    </div>

    <BulkActionBar 
      :count="selectedIds.length" 
      @action="handleBulkAction"
    />
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useEnquiriesStore } from '@/stores/enquiries'
import { storeToRefs } from 'pinia'
import EnquiryTable from '@/components/enquiries/EnquiryTable.vue'
import EnquiryFilters from '@/components/enquiries/EnquiryFilters.vue'
import BulkActionBar from '@/components/enquiries/BulkActionBar.vue'

const enquiriesStore = useEnquiriesStore()
const { enquiries, loading } = storeToRefs(enquiriesStore)

const selectedIds = ref([])

onMounted(() => {
  enquiriesStore.fetchEnquiries()
})

const handleFilter = (filters) => {
  enquiriesStore.fetchEnquiries(filters)
}

const isAllSelected = computed(() => {
  return enquiries.value.length > 0 && selectedIds.value.length === enquiries.value.length
})

const toggleSelect = (id) => {
  if (selectedIds.value.includes(id)) {
    selectedIds.value = selectedIds.value.filter(i => i !== id)
  } else {
    selectedIds.value.push(id)
  }
}

const toggleSelectAll = () => {
  if (isAllSelected.value) {
    selectedIds.value = []
  } else {
    selectedIds.value = enquiries.value.map(e => e.id)
  }
}

const handleBulkAction = async ({ type, value }) => {
  if (type === 'delete') {
    if (confirm(`Delete ${selectedIds.value.length} enquiries?`)) {
      // await enquiriesStore.bulkDelete(selectedIds.value)
      selectedIds.value = []
    }
  } else if (type === 'export') {
    alert('Exporting to CSV...')
  } else if (type === 'status') {
    await enquiriesStore.bulkAction(selectedIds.value, { status: value })
    selectedIds.value = []
  }
}
</script>
