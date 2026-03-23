<template>
  <div class="h-full bg-gray-50 flex flex-col overflow-y-auto">
    <div class="p-8 md:p-12 max-w-7xl mx-auto w-full">
      <div class="mb-16">
        <h1 class="text-4xl font-black text-gray-900 tracking-tighter uppercase mb-4">Subscription & Billing</h1>
        <p class="text-gray-400 text-xs font-bold uppercase tracking-[0.2em] italic">Scale your real estate empire</p>
      </div>

      <!-- Current Plan Status -->
      <div v-if="store.current" class="mb-12">
        <div class="bg-white rounded-[40px] p-10 border border-gray-100 shadow-sm flex flex-col md:flex-row gap-12 items-center">
          <div class="flex-1 space-y-4 text-left">
            <div class="flex items-center gap-3">
              <span class="px-4 py-1.5 bg-blue-600/10 text-blue-600 rounded-full text-[10px] font-black uppercase tracking-widest">
                Current Plan: {{ store.current.plan }}
              </span>
            </div>
            <h2 class="text-2xl font-black text-gray-900">Next billing: {{ store.current.currentPeriodEnd }}</h2>
            <p class="text-gray-400 text-xs font-bold uppercase tracking-widest">Growth Plan Active</p>
          </div>
          
          <div class="grid grid-cols-2 md:grid-cols-3 gap-8 w-full md:w-auto">
            <div class="space-y-2">
              <div class="h-2 w-32 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full bg-blue-600 w-3/5"></div>
              </div>
              <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Agents: 3/5</p>
            </div>
            <div class="space-y-2">
              <div class="h-2 w-32 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full bg-blue-600 w-1/2"></div>
              </div>
              <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">AI Msg: 2.4k/5k</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Plan Selection -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div v-for="(plan, key) in plans" :key="key" 
          class="bg-white p-10 rounded-[40px] border border-gray-100 shadow-sm hover:shadow-xl hover:border-blue-200 transition-all flex flex-col"
          :class="{ 'border-2 border-blue-600 relative': key === 'growth' }">
          
          <div v-if="key === 'growth'" class="absolute -top-4 left-1/2 -translate-x-1/2 bg-blue-600 text-white px-6 py-2 rounded-full text-[10px] font-black uppercase tracking-widest">
            MOST POPULAR
          </div>

          <div class="text-left mb-8">
            <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-2">{{ plan.name }}</h3>
            <div class="flex items-baseline gap-1">
              <span class="text-4xl font-black text-gray-900">₹{{ plan.price / 100 }}</span>
              <span class="text-gray-400 text-xs font-bold uppercase">/mo</span>
            </div>
          </div>

          <ul class="space-y-4 mb-10 flex-1 text-left">
            <li v-for="feature in plan.features" :key="feature" class="flex items-center gap-3 text-xs font-bold text-gray-600">
              <CheckCircleIcon class="w-5 h-5 text-emerald-500" />
              {{ feature }}
            </li>
          </ul>

          <button 
            @click="upgrade(key)"
            class="w-full py-5 rounded-[24px] text-xs font-black uppercase tracking-widest transition-all"
            :class="store.current?.plan === key ? 'bg-gray-100 text-gray-400 pointer-events-none' : 'bg-gray-900 text-white hover:bg-black shadow-lg hover:translate-y-[-2px]'"
          >
            {{ store.current?.plan === key ? 'Active Plan' : 'Select Plan' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useSubscriptionStore } from '@/stores/subscription'
import { useRazorpay } from '@/composables/useSubscription'
import { CheckCircleIcon } from '@heroicons/vue/24/solid'

const store = useSubscriptionStore()
const { openCheckout } = useRazorpay()

const plans = {
  starter: { name: 'Starter', price: 99900, features: ['1 Agent', '100 Leads', '50 AI Messages'] },
  growth: { name: 'Growth', price: 249900, features: ['5 Agents', 'Unlimited Leads', 'Unlimited AI', 'AI Chatbot Widget'] },
  agency: { name: 'Agency', price: 499900, features: ['20 Agents', 'Unlimited Everything', 'Commission Tracker'] }
}

onMounted(() => {
  store.fetchCurrent()
})

const upgrade = async (planKey) => {
  try {
    const order = await store.createOrder(planKey)
    
    openCheckout({
      key: order.key,
      amount: order.amount,
      currency: 'INR',
      name: 'Watering CRM',
      description: `Upgrade to ${planKey} plan`,
      order_id: order.orderId,
    }, async (response) => {
      await store.verifyPayment({
        planKey,
        orderId: order.orderId,
        paymentId: response.razorpay_payment_id,
        signature: response.razorpay_signature
      })
      alert('Subscription successful!')
    })
  } catch (err) {
    alert('Expansion failed. Please try again.')
  }
}
</script>
