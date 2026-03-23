import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const routes = [
  {
    path: '/',
    redirect: '/login'
  },
  {
    path: '/login',
    name: 'login',
    component: () => import('@/views/auth/LoginView.vue')
  },
  {
    path: '/admin',
    component: () => import('@/views/admin/AdminLayout.vue'),
    meta: { requiresAuth: true, role: 'admin' },
    children: [
      {
        path: 'dashboard',
        name: 'admin-dashboard',
        component: () => import('@/views/admin/DashboardView.vue')
      },
      {
        path: 'agents',
        name: 'admin-agents',
        component: () => import('@/views/admin/AgentsView.vue')
      }
    ]
  },
  {
    path: '/agent',
    component: () => import('@/views/agent/AgentLayout.vue'),
    meta: { requiresAuth: true, role: 'agent' },
    children: [
      {
        path: 'pipeline',
        name: 'agent-pipeline',
        component: () => import('@/views/agent/KanbanView.vue')
      },
      {
        path: 'leads',
        name: 'agent-leads',
        component: () => import('@/views/agent/LeadsView.vue')
      }
    ]
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore()
  
  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    next('/login')
  } else if (to.meta.role && authStore.userRole !== to.meta.role) {
    // Redirect if role doesn't match
    next(authStore.userRole === 'admin' ? '/admin/dashboard' : '/agent/pipeline')
  } else {
    next()
  }
})

export default router
