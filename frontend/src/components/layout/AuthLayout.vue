<template>
  <div class="min-h-screen grid grid-cols-1 lg:grid-cols-2">
    <!-- Left Side: Branding/Marketing -->
    <div class="hidden lg:flex flex-col justify-center items-center bg-[#0F1117] text-white p-12 relative overflow-hidden">
      <!-- Subtle Gradient Ornaments -->
      <div class="absolute top-0 left-0 w-full h-full opacity-20 bg-[radial-gradient(circle_at_top_right,_#2563EB,_transparent_50%)]"></div>
      
      <div class="z-10 max-w-lg text-center">
        <div class="mb-8 flex justify-center">
          <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-500/20">
            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
            </svg>
          </div>
        </div>
        <h1 class="text-4xl font-bold mb-4 tracking-tight uppercase">Watering CRM</h1>
        <p class="text-gray-400 text-lg mb-12 font-medium">
          "The fortune is in the follow-up."
        </p>
        
        <div class="space-y-6">
          <div class="bg-white/5 backdrop-blur-sm border border-white/10 p-6 rounded-2xl text-left">
            <p class="text-gray-300 italic mb-4">
              "This AI is like having a junior sales agent that never sleeps. Our lead-to-site-visit conversion jumped by 60% in just two months."
            </p>
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-full bg-blue-500/20 flex items-center justify-center font-bold text-blue-400">RS</div>
              <div>
                <p class="font-bold text-sm">Rahul Sharma</p>
                <p class="text-xs text-gray-500">MD, Sharma Real Estate Groups</p>
              </div>
            </div>
          </div>
          
          <div class="flex justify-center gap-4 text-xs text-gray-500">
            <span>Powered by Anthropic AI</span>
            <span>•</span>
            <span>Made for Indian Brokers</span>
            <span>•</span>
            <span>RERA Compliant</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Right Side: Auth Forms -->
    <div class="flex items-center justify-center p-8 bg-white">
      <div class="w-full max-w-md">
        <slot />
      </div>
    </div>
  </div>
</template>

<script setup>
</script>
