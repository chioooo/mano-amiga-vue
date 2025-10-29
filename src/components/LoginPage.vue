<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '@/api'

const username = ref('')
const password = ref('')
const router = useRouter()
const error = ref('')

onMounted(() => {
  document.body.className = 'login-register'
})

const login = async () => {
  try {
    const response = await api.post('login.php', {
      username: username.value,
      password: password.value
    })

    if (response.data.status === 'success') {
      document.body.className = 'main-page'
      localStorage.setItem('user', JSON.stringify(response.data.user))
      console.log(response.data.user)
      router.push('/app')
    } else {
      error.value = response.data.message || 'Credenciales incorrectas'
    }
  } catch (error) {
    error.value = 'Error de conexión con el servidor'
  }
}
</script>

<template>
  <div class="auth-page">
    <h2>Iniciar Sesión</h2>

    <form @submit.prevent="login">
      <input v-model="username" type="text" placeholder="Nombre de usuario" required />
      <input v-model="password" type="password" placeholder="Contraseña" required />
      <button type="submit">Ingresar</button>
    </form>

    <p>¿No tienes cuenta?<router-link to="/register"><span>Regístrate</span></router-link></p>
    <p v-if="error" class="error">{{ error }}</p>
  </div>
</template>

<style scoped>

</style>
