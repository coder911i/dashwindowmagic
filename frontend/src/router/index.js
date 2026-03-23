import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import MainLayout from '@/layouts/MainLayout.vue'

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
    path: '/register',
    name: 'register',
    component: () => import('@/views/auth/RegisterView.vue')
  },
  {
    path: '/accept-invite',
    name: 'accept-invite',
    component: () => import('@/views/auth/AcceptInvite.vue')
  },
  {
    path: '/admin',
    component: MainLayout,
    meta: { requiresAuth: true, role: 'admin' },
    children: [
      {
        path: 'dashboard',
        name: 'admin-dashboard',
        component: () => import('@/views/admin/DashboardView.vue')
      },
      {
        path: 'leads',
        name: 'admin-leads',
        component: () => import('@/views/admin/LeadsView.vue')
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
    component: MainLayout,
    meta: { requiresAuth: true, role: 'agent' },
    children: [
      {
        path: 'dashboard',
        name: 'agent-dashboard',
        component: () => import('@/views/agent/DashboardView.vue')
      },
      {
        path: 'pipeline',
        name: 'agent-pipeline',
        component: () => import('@/views/agent/KanbanView.vue')
      },
      {
        path: 'leads',
        name: 'agent-leads',
        component: () => import('@/views/agent/LeadsView.vue')
      },
      {
        path: 'leads/:id',
        name: 'lead-detail',
        component: () => import('@/views/agent/LeadDetail.vue')
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
  
  // Wait for auth to init if it's still loading
  if (authStore.isLoading) {
    await authStore.init()
  }
  
  const isAuthenticated = authStore.isAuthenticated
  const userRole = authStore.userRole

  if (to.meta.requiresAuth && !isAuthenticated) {
    next('/login')
  } else if (to.meta.role && userRole !== to.meta.role) {
    // Redirect to correct dashboard if trying to access unauthorized area
    next(userRole === 'admin' ? '/admin/dashboard' : '/agent/pipeline')
  } else if (isAuthenticated && (to.name === 'login' || to.name === 'register')) {
    // Already logged in, go to respective dashboard
    next(userRole === 'admin' ? '/admin/dashboard' : '/agent/pipeline')
  } else {
    next()
  }
})

export default router
