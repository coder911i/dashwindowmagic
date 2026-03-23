<template>
  <div class="space-y-8">
    <div class="flex justify-between items-center">
      <div>
        <h1 class="text-2xl font-extrabold text-gray-900 uppercase tracking-tighter">Team Management</h1>
        <p class="text-gray-400 text-[10px] font-black tracking-widest uppercase">Monitor agent performance and invite new members</p>
      </div>
      <button @click="showInviteModal = true" class="bg-[#0F1117] text-white px-6 py-3 rounded-2xl text-xs font-black uppercase tracking-widest shadow-xl hover:translate-y-[-2px] transition-all flex items-center gap-2">
        <UserPlusIcon class="w-4 h-4" /> Invite Agent
      </button>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div v-for="stat in agentStats" :key="stat.label" class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">{{ stat.label }}</p>
        <p class="text-2xl font-black text-gray-900">{{ stat.value }}</p>
      </div>
    </div>

    <!-- Agents Table -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
      <table class="w-full text-left">
        <thead>
          <tr class="bg-gray-50/50 border-b border-gray-50">
            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Agent</th>
            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Performance</th>
            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Role</th>
            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          <tr v-for="agent in agents" :key="agent.id" class="hover:bg-gray-50/30 transition-all cursor-pointer group">
            <td class="px-8 py-5">
              <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center font-bold text-blue-600 group-hover:bg-[#0F1117] group-hover:text-white transition-all">
                  {{ agent.name.charAt(0) }}
                </div>
                <div>
                  <p class="text-sm font-bold text-gray-900">{{ agent.name }}</p>
                  <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">{{ agent.email }}</p>
                </div>
              </div>
            </td>
            <td class="px-8 py-5">
              <div class="flex items-center gap-3">
                <div class="flex-1 max-w-[100px] h-1.5 bg-gray-100 rounded-full overflow-hidden">
                  <div class="h-full bg-blue-600 rounded-full" :style="{ width: agent.progress + '%' }"></div>
                </div>
                <span class="text-[10px] font-black text-blue-600">{{ agent.deals }} Deals</span>
              </div>
            </td>
            <td class="px-8 py-5">
              <Badge :type="agent.status === 'Active' ? 'success' : 'secondary'">{{ agent.status }}</Badge>
            </td>
            <td class="px-8 py-5">
              <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">{{ agent.role }}</p>
            </td>
            <td class="px-8 py-5">
              <button class="text-gray-400 hover:text-blue-600 transition-colors">
                <EllipsisHorizontalIcon class="w-5 h-5" />
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Invite Modal (Simplified) -->
    <div v-if="showInviteModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-[#0F1117]/60 backdrop-blur-sm">
      <div class="bg-white w-full max-w-md rounded-3xl p-8 shadow-2xl animate-scale-in">
        <h2 class="text-xl font-black text-gray-900 uppercase tracking-tighter mb-2">Invite New Agent</h2>
        <p class="text-xs text-gray-500 font-medium mb-6">They will receive an email to join your agency.</p>
        
        <div class="space-y-4">
          <div>
            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Email Address</label>
            <input v-model="inviteEmail" type="email" class="w-full px-4 py-3 rounded-xl border border-gray-200 outline-none focus:ring-2 focus:ring-blue-500/10 transition-all font-bold text-sm" placeholder="agent@agency.com" />
          </div>
          <div class="flex gap-4 mt-8">
            <button @click="showInviteModal = false" class="flex-1 py-3 rounded-xl text-xs font-black uppercase tracking-widest text-gray-400 border border-gray-100 hover:bg-gray-50 transition-all">Cancel</button>
            <button @click="sendInvite" class="flex-1 py-3 rounded-xl text-xs font-black uppercase tracking-widest bg-blue-600 text-white shadow-lg shadow-blue-500/20 hover:bg-blue-700 transition-all">Send Invite</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { UserPlusIcon, EllipsisHorizontalIcon } from '@heroicons/vue/24/outline'
import Badge from '@/components/ui/Badge.vue'

const showInviteModal = ref(false)
const inviteEmail = ref('')

const agentStats = [
  { label: 'Total Team', value: '14 Agents' },
  { label: 'Top Performer', value: 'Amit Singh' },
  { label: 'Pending Invites', value: '2' },
]

const agents = [
  { id: 1, name: 'Amit Singh', email: 'amit@watering.com', progress: 92, deals: 14, status: 'Active', role: 'Senior Agent' },
  { id: 2, name: 'Priya Verma', email: 'priya@watering.com', progress: 85, deals: 11, status: 'Active', role: 'Sales Agent' },
  { id: 3, name: 'Sanjay Jain', email: 'sanjay@watering.com', progress: 75, deals: 9, status: 'Active', role: 'Sales Agent' },
  { id: 4, name: 'Neha Gupta', email: 'neha@watering.com', progress: 0, deals: 0, status: 'Invited', role: 'Junior Agent' },
]

const sendInvite = () => {
  // Logic handled by InviteController
  alert('Invite sent to ' + inviteEmail.value)
  showInviteModal.value = false
  inviteEmail.value = ''
}
</script>

<style scoped>
@keyframes scale-in {
  from { opacity: 0; transform: scale(0.95); }
  to { opacity: 1; transform: scale(1); }
}
.animate-scale-in {
  animation: scale-in 0.2s ease-out;
}
</style>
