import { createRouter, createWebHistory } from 'vue-router'
import InicioPage from '@/components/InicioPage.vue'
import LoginPage from '@/components/LoginPage.vue'
import RegisterPage from '@/components/RegisterPage.vue'
import PerfilPage from '@/components/PerfilPage.vue'
import RecursosPage from '@/components/RecursosPage.vue'
import SiniestrosPage from '@/components/SiniestrosPage.vue'
import UsuariosPage from '@/components/UsuariosPage.vue'
import MainLayout from '@/components/MainLayout.vue'

const routes = [
  { path: '/', redirect: '/login' },
  { path: '/login', component: LoginPage },
  { path: '/register', component: RegisterPage },
  {
    path: '/app',
    component: MainLayout,
    children: [
      { path: '', component: InicioPage },
      { path: 'usuarios', component: UsuariosPage },
      { path: 'recursos', component: RecursosPage },
      { path: 'siniestros', component: SiniestrosPage },
      { path: 'perfil', component: PerfilPage }
    ]
  }
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes
})

router.beforeEach((to, from, next) => {
  const isLoggedIn = localStorage.getItem('user') !== null
  if (to.meta.requiresAuth && !isLoggedIn) {
    next('/login')
  } else {
    next()
  }
})

export default router
