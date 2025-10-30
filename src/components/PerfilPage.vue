<script setup>
import { ref, onMounted } from 'vue'
import api from '@/api'

const user = JSON.parse(localStorage.getItem('user') || '{}')
const usuario = ref(null)

const verPerfil = async () => {
  try {
    const response = await api.get(`usuarios_get.php?id=${user.id}`)
    usuario.value = response.data
    console.log(usuario.value)
  } catch (error) {
    console.error('Error al obtener el perfil:', error)
  }
}

onMounted(() => {
  verPerfil()
})
</script>

<template>

  <div class="profile" v-if="usuario">
    <div id="avatar">
      <img src="..\assets\img\perfil.jpg" alt="foto de perfil" />
      <div class="user-info">
        <h2>Nombre: {{ usuario.full_name }}</h2>
        <h2>Usuario: {{ usuario.username }}</h2>
        <h2>Tipo de usuario: {{ usuario.is_admin == 1 ? 'Administrador' : 'Voluntario' }}</h2>
        <p>Activa desde {{usuario.fecha_registro}}</p>
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
