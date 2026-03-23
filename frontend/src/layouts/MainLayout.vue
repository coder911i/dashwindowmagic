<template>
  <div class="flex h-screen bg-white">
    <!-- Sidebar -->
    <AdminSidebar v-if="isAdmin" />
    <AgentSidebar v-else />

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
      <!-- Top Bar -->
      <TopBar />

      <!-- Page Content -->
      <main class="flex-1 overflow-x-hidden overflow-y-auto bg-[#F9FAFB] p-6">
        <PageWrapper>
          <router-view v-slot="{ Component }">
            <transition name="fade" mode="out-in">
              <component :is="Component" />
            </transition>
          </router-view>
        </PageWrapper>
      </main>
    </div>

    <!-- Global Toasts -->
    <ToastContainer />
  </div>
</template>

<script setup>
import { useAuth } from '@/composables/useAuth'
import AdminSidebar from '@/components/layout/AdminSidebar.vue'
import AgentSidebar from '@/components/layout/AgentSidebar.vue'
import TopBar from '@/components/layout/TopBar.vue'
import PageWrapper from '@/components/layout/PageWrapper.vue'
import ToastContainer from '@/components/ui/ToastContainer.vue'

const { isAdmin } = useAuth()
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
