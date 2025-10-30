<script setup lang="ts">
import { ref, onMounted } from 'vue'
import api from '@/api'

const user = ref(null)


async function verPerfil() {
  try {
    const { data } = await api.get('usuarios_gets.php')
    if (data.error) {
      console.warn('No autenticado:', data.error)
      user.value = null
      return
    }
    user.value = data
  } catch (error) {
    console.error('Error al obtener el perfil:', error)
  }
}

onMounted(() => {
  verPerfil()
})
</script>

<template>

  <div class="profile" v-if="user">
    <div id="avatar">
      <img src="..\assets\img\perfil.jpg" alt="foto de perfil" />
      <div class="user-info">
        <h2>Nombre: {{ user.full_name }}</h2>
        <h2>Usuario: {{ user.username }}</h2>
        <h2>Tipo de usuario: {{ user.is_admin == 1 ? 'Administrador' : 'Voluntario' }}</h2>
        <p>Activa desde septiembre del 2024</p>
      </div>
    </div>
  </div>

</template>

<style scoped>

.perfil {
    display: flex;
    flex-direction: column;
    background: #fff3f3;
    border-left: 6px solid #e53935;
    padding: 20px 25px;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.12);
    width: 100%;
}
#avatar {
  display: flex;
  align-items: center;
}
.user-info {
  margin-left: 20px;
}
</style>
