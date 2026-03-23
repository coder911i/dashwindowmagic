<template>
  <header class="h-16 bg-white border-b border-gray-100 flex items-center justify-between px-8 shrink-0">
    <div class="flex items-center gap-4">
      <h2 class="text-lg font-bold text-gray-800">{{ pageTitle }}</h2>
    </div>

    <div class="flex items-center gap-6">
      <!-- Search Bar -->
      <div class="hidden sm:flex items-center bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-100 focus-within:ring-2 focus-within:ring-blue-500/10 transition-all">
        <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
        <input type="text" placeholder="Search leads, properties..." class="bg-transparent border-none outline-none text-sm w-64 placeholder:text-gray-400" />
      </div>

      <div class="flex items-center gap-4 border-l border-gray-100 pl-6">
        <button class="relative p-2 text-gray-400 hover:text-gray-600 transition-colors">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
          <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 border-2 border-white rounded-full"></span>
        </button>

        <div class="flex items-center gap-3 pl-2 cursor-pointer group">
          <div class="text-right hidden sm:block">
            <p class="text-xs font-bold text-gray-900 group-hover:text-blue-600 transition-colors">{{ profile?.name || 'User' }}</p>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">{{ profile?.role || 'Guest' }}</p>
          </div>
          <div class="w-9 h-9 rounded-full bg-blue-50 flex items-center justify-center font-bold text-blue-600 border border-blue-100">
            {{ profile?.name?.charAt(0) || 'U' }}
          </div>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import { useAuth } from '@/composables/useAuth'

const route = useRoute()
const { profile } = useAuth()

const pageTitle = computed(() => {
  const name = route.name?.toString() || ''
  return name.split('-').map(w => w.charAt(0).toUpperCase() + w.slice(1)).join(' ') || 'Watering CRM'
})
</script>
