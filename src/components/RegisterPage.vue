<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '@/api'

const router = useRouter()
const fullname = ref('')
const username = ref('')
const password = ref('')
const message = ref('')

onMounted(() => {
  document.body.className = 'login-register'
})

const register = async () => {
  try {
    const response = await api.post('register.php', {
      fullname: fullname.value,
      username: username.value,
      password: password.value,
    })
    message.value = response.data.message


    if (response.data.status === 'success') {
      alert('Cuenta creada correctamente.')
      router.push('/login')
    } else {
      alert(response.data.message || 'No se pudo crear la cuenta.')
    }
  } catch (err) {
    message.value = 'Error de conexión con el servidor'
    console.error(err)
  }
}
</script>

<template>
  <div class="auth-page">
    <h2>Registro</h2>

    <form @submit.prevent="register">
      <input v-model="fullname" type="text" placeholder="Nombre completo" required />
      <input v-model="username" type="text" placeholder="Nombre de usuario" required />
      <input v-model="password" type="password" placeholder="Contraseña" required />
      <button type="submit">Registrarse</button>
    </form>

    <p>¿Ya tienes cuenta?<router-link to="/login"><span>Inicia sesión</span></router-link></p>
    <p v-if="message" class="msg">{{ message }}</p>
  </div>
</template>

<style scoped>

</style>
