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
        path: 'rera',
        name: 'admin-rera',
        component: () => import('@/views/admin/RERAChecker.vue'),
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
      },
      {
        path: 'properties',
        name: 'admin-properties',
        component: () => import('@/views/admin/Properties.vue')
      },
      {
        path: 'commissions',
        name: 'admin-commissions',
        component: () => import('@/views/admin/Commissions.vue')
      },
      {
        path: 'settings',
        name: 'admin-settings',
        component: () => import('@/views/admin/Settings.vue')
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
        component: () => import('@/views/agent/LeadDetail.vue'),
      },
      {
        path: 'follow-up',
        name: 'ai-follow-up',
        component: () => import('@/views/agent/AIFollowUp.vue'),
      },
      {
        path: 'enquiries',
        name: 'enquiries',
        component: () => import('@/views/agent/Enquiries.vue'),
      },
      {
        path: 'calculator',
        name: 'calculator',
        component: () => import('@/views/agent/EMICalculator.vue'),
      },
      {
        path: 'visits',
        name: 'visits',
        component: () => import('@/views/agent/Scheduler.vue'),
      },
    ]
  },
  {
    path: '/:pathMatch(.*)*',
    name: 'not-found',
    component: () => import('@/views/errors/NotFound.vue')
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore()
  
  if (authStore.isLoading) {
    await authStore.init()
  }
  
  const isAuthenticated = authStore.isAuthenticated
  const userRole = authStore.userRole

  if (to.meta.requiresAuth && !isAuthenticated) {
    next('/login')
  } else if (to.meta.role && userRole !== to.meta.role) {
    next(userRole === 'admin' ? '/admin/dashboard' : '/agent/pipeline')
  } else if (isAuthenticated && (to.name === 'login' || to.name === 'register')) {
    next(userRole === 'admin' ? '/admin/dashboard' : '/agent/pipeline')
  } else {
    next()
  }
})

export default router
