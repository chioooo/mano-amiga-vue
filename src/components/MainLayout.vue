<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const user = ref(null)
const isAdmin = ref(false)

onMounted(() => {
  document.body.className = 'main-page'
  const storedUser = JSON.parse(localStorage.getItem('user'))
  if (storedUser) {
    user.value = storedUser
    isAdmin.value = storedUser.is_admin === 1
  } else {
    router.push('/login')
  }
})

const logout = () => {
  document.body.className = 'login-register'
  localStorage.removeItem('user')
  router.push('/login')
}
</script>

<template>
  <div id="main-page">
    <aside class="sidebar">
      <h2>Gestión</h2>

      <router-link to="/app" exact-active-class="active">
        <i class="fas fa-home"></i>Inicio
      </router-link>
      <router-link v-if="isAdmin" to="/app/usuarios" exact-active-class="active">
        <i class="fas fa-users"></i>Usuarios
      </router-link>
      <router-link to="/app/perfil" exact-active-class="active">
        <i class="fas fa-user"></i>Perfil
      </router-link>
      <router-link v-if="isAdmin" to="/app/recursos" exact-active-class="active">
        <i class="fas fa-boxes"></i>Recursos
      </router-link>
      <router-link v-if="isAdmin" to="/app/siniestros" exact-active-class="active">
        <i class="fa-solid fa-skull-crossbones"></i>Siniestros
      </router-link>
    </aside>

    <main class="main-content">
      <header class="topbar">
        <div class="kill-btn" @click="logout">
          <i class="fa-solid fa-right-from-bracket"></i>
        </div>
      </header>

      <!-- Aquí se mostrará la página seleccionada -->
      <router-view />
    </main>
  </div>
</template>

<style scoped>

</style>
