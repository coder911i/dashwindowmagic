<template>
  <div class="min-h-[calc(100vh-8rem)] py-12 px-6 flex items-center justify-center">
    <div class="w-full max-w-2xl bg-white rounded-[40px] shadow-2xl shadow-gray-200/50 border border-gray-100 p-8 md:p-12 overflow-hidden relative">
      <!-- Decoration -->
      <div class="absolute -top-12 -left-12 w-48 h-48 bg-blue-50 rounded-full blur-3xl opacity-50"></div>

      <div class="relative z-10">
        <div class="mb-10 text-center">
          <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-[10px] font-black uppercase tracking-widest mb-4 inline-block">Finance Tool</span>
          <h1 class="text-3xl font-black text-gray-900 tracking-tighter uppercase">EMI Calculator</h1>
          <p class="text-gray-400 text-xs font-bold mt-2 uppercase tracking-widest italic">Check eligibility for {{ selectedBank }} Home Loan</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
          <!-- Inputs -->
          <div class="space-y-8">
            <div class="space-y-3">
              <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest flex justify-between">
                Property Price <span>₹ {{ (price / 100000).toFixed(1) }}L</span>
              </label>
              <input 
                v-model.number="price" 
                type="range" min="1000000" max="50000000" step="500000"
                class="w-full h-1.5 bg-gray-100 rounded-lg appearance-none cursor-pointer accent-blue-600"
              />
            </div>

            <div class="space-y-3">
              <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest flex justify-between">
                Down Payment <span>{{ downPaymentPercent }}%</span>
              </label>
              <input 
                v-model.number="downPaymentPercent" 
                type="range" min="5" max="95" step="5"
                class="w-full h-1.5 bg-gray-100 rounded-lg appearance-none cursor-pointer accent-blue-600"
              />
            </div>

            <div class="space-y-3">
              <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Loan Tenure</label>
              <select v-model.number="tenureYears" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-xs font-bold outline-none appearance-none">
                <option v-for="y in [5,10,15,20,25,30]" :key="y" :value="y">{{ y }} Years</option>
              </select>
            </div>

            <div class="space-y-3">
              <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Preferred Bank</label>
              <div class="grid grid-cols-2 gap-2">
                <button 
                  v-for="rate, bank in banks" :key="bank"
                  @click="interestRate = rate; selectedBank = bank"
                  :class="['py-3 px-2 rounded-xl text-[10px] font-black border transition-all', interestRate === rate ? 'bg-blue-600 border-blue-600 text-white shadow-lg shadow-blue-500/20' : 'bg-white border-gray-100 text-gray-400 hover:border-blue-200']"
                >
                  {{ bank }} ({{ rate }}%)
                </button>
              </div>
            </div>

            <div class="space-y-3">
              <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Monthly Income</label>
              <div class="relative">
                <span class="absolute left-4 top-3.5 text-xs text-gray-400 font-bold">₹</span>
                <input v-model.number="monthlyIncome" type="number" class="w-full pl-8 pr-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-xs font-bold outline-none" />
              </div>
            </div>
          </div>

          <!-- Results -->
          <div class="flex flex-col">
            <EMIResult 
              :emi="monthlyEMI"
              :loan-amount="loanAmount"
              :total-interest="totalInterest"
              :total-payment="totalPayment"
              :max-eligible="maxEligibleLoan"
              :eligible="isEligible"
            />
            
            <ShareCard :message="shareMessage" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useEMI } from '@/composables/useEMI'
import EMIResult from '@/components/calculator/EMIResult.vue'
import ShareCard from '@/components/calculator/ShareCard.vue'

const {
  price, downPaymentPercent, tenureYears, monthlyIncome, interestRate,
  loanAmount, monthlyEMI, totalInterest, totalPayment, maxEligibleLoan, isEligible
} = useEMI()

const banks = {
  'SBI': 8.5,
  'HDFC': 8.75,
  'ICICI': 8.8,
  'Axis': 8.75
}

const selectedBank = ref('SBI')

const shareMessage = computed(() => {
  const eligibility = isEligible.value ? 'Yes ✅' : 'Need co-applicant ⚠️'
  return `Namaste! Based on your preference:\n\n🏠 Property: ₹${(price.value/100000).toFixed(1)}L\n💰 EMI: ₹${monthlyEMI.value.toLocaleString('en-IN')}/mo\n🏦 Loan: ₹${(loanAmount.value/100000).toFixed(1)}L (${selectedBank.value} @ ${interestRate.value}%)\n✅ Income Eligible: ${eligibility}\n\nShall we book a site visit to discuss further? 🏠`
})
</script>
