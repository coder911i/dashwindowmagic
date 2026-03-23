<template>
  <div class="p-8 bg-blue-600 rounded-3xl text-white shadow-2xl shadow-blue-500/40 relative overflow-hidden">
    <!-- Decorative background -->
    <div class="absolute -right-4 -top-4 w-32 h-32 bg-white/10 rounded-full blur-3xl"></div>
    
    <div class="relative z-10">
      <p class="text-[10px] font-black uppercase tracking-[0.2em] text-blue-100/60 mb-2">Estimated Monthly EMI</p>
      <h2 class="text-4xl font-black tracking-tighter mb-8">₹ {{ emi.toLocaleString('en-IN') }}<span class="text-lg font-medium text-blue-100/50">/mo</span></h2>
      
      <div class="grid grid-cols-2 gap-8 pt-6 border-t border-white/10">
        <div>
          <p class="text-[9px] font-black uppercase tracking-widest text-blue-100/40 mb-1">Loan Amount</p>
          <p class="text-sm font-black tracking-tight">₹ {{ loanAmount.toLocaleString('en-IN') }}</p>
        </div>
        <div>
          <p class="text-[9px] font-black uppercase tracking-widest text-blue-100/40 mb-1">Total Interest</p>
          <p class="text-sm font-black tracking-tight">₹ {{ totalInterest.toLocaleString('en-IN') }}</p>
        </div>
        <div>
          <p class="text-[9px] font-black uppercase tracking-widest text-blue-100/40 mb-1">Max Eligibility</p>
          <div class="flex items-center gap-2">
            <p class="text-sm font-black tracking-tight">₹ {{ maxEligible.toLocaleString('en-IN') }}</p>
            <CheckCircleIcon v-if="eligible" class="w-4 h-4 text-emerald-300" />
            <XCircleIcon v-else class="w-4 h-4 text-red-300" />
          </div>
        </div>
        <div>
          <p class="text-[9px] font-black uppercase tracking-widest text-blue-100/40 mb-1">Total Payment</p>
          <p class="text-sm font-black tracking-tight">₹ {{ totalPayment.toLocaleString('en-IN') }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { CheckCircleIcon, XCircleIcon } from '@heroicons/vue/24/solid'

defineProps({
  emi: Number,
  loanAmount: Number,
  totalInterest: Number,
  totalPayment: Number,
  maxEligible: Number,
  eligible: Boolean
})
</script>
