<template>
  <div class="h-full flex flex-col bg-gray-50 overflow-y-auto custom-scrollbar">
    <div class="p-8 md:p-12 max-w-7xl mx-auto w-full">
      <div class="mb-12 text-left">
        <h1 class="text-4xl font-black text-gray-900 tracking-tighter uppercase mb-2">Visit Scheduler</h1>
        <p class="text-gray-400 text-xs font-bold uppercase tracking-[0.2em] italic">Manage your property showcases</p>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <!-- Sidebar: Form -->
        <div class="lg:col-span-1">
          <div class="sticky top-8">
            <VisitForm :loading="loading" @submit="handleSchedule" />
            
            <div class="mt-8 p-6 bg-white rounded-3xl border border-gray-100 shadow-sm text-left">
              <h4 class="text-[10px] font-black text-gray-900 uppercase tracking-widest mb-4">Quick Stats</h4>
              <div class="space-y-4">
                <div class="flex justify-between items-center">
                  <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">This Week</span>
                  <span class="text-xs font-black text-blue-600">{{ weeklyCount }}</span>
                </div>
                <div class="flex justify-between items-center">
                  <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Pending</span>
                  <span class="text-xs font-black text-amber-600">{{ pendingCount }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Main: Calendar -->
        <div class="lg:col-span-2 text-left">
          <div v-if="loading && visits.length === 0" class="space-y-8">
            <div v-for="i in 3" :key="i" class="h-32 bg-gray-200 rounded-3xl animate-pulse"></div>
          </div>
          <VisitCalendar v-else :visits="visits" @action="handleAction" />
        </div>
      </div>
    </div>

    <!-- Modals -->
    <OutcomeModal :show="showOutcome" :visit="activeVisit" @close="showOutcome = false" @save="handleOutcomeSave" />
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useVisitsStore } from '@/stores/visits'
import { storeToRefs } from 'pinia'
import VisitCalendar from '@/components/visits/VisitCalendar.vue'
import VisitForm from '@/components/visits/VisitForm.vue'
import OutcomeModal from '@/components/visits/OutcomeModal.vue'

const visitsStore = useVisitsStore()
const { visits, loading } = storeToRefs(visitsStore)

const showOutcome = ref(false)
const activeVisit = ref(null)

onMounted(() => {
  visitsStore.fetchVisits()
})

const weeklyCount = computed(() => {
  return visits.value.filter(v => v.status === 'scheduled').length
})

const pendingCount = computed(() => {
  return visits.value.filter(v => v.status === 'scheduled').length
})

const handleSchedule = async (data) => {
  await visitsStore.scheduleVisit(data)
}

const handleAction = async ({ type, id }) => {
  if (type === 'complete') {
    activeVisit.value = visits.value.find(v => v.id === id)
    showOutcome.value = true
  } else if (type === 'cancel') {
    if (confirm('Cancel this visit?')) {
      await visitsStore.updateStatus(id, 'cancelled')
    }
  }
}

const handleOutcomeSave = async (outcomeData) => {
    if (activeVisit.value) {
        await visitsStore.updateStatus(activeVisit.value.id, 'completed', outcomeData)
        activeVisit.value = null
    }
}
</script>
