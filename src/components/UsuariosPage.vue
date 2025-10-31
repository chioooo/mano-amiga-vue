<script setup>
import { ref, onMounted } from 'vue'
import api from '@/api'

const usuarios = ref([])
const showModal = ref(false)

const openModal = () => (showModal.value = true)
const closeModal = () => (showModal.value = false)

const cargarUsuarios = async () => {
  try{
    const response = await api.get('usuarios_list.php')
    usuarios.value = response.data
  } catch (error) {
    console.error('Error al cargar usuarios', error)
  }
}

onMounted(() => {
  cargarUsuarios()
})

</script>

<template>
  <div id="usuarios">
    <button id="btn-new-siniestro" @click="openModal" style="margin-top: 20px">
      <i class="fa-solid fa-plus"></i>
      Crear
    </button>
    <table class="tabla" border="1">
      <thead>
      <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Usuario</th>
        <th>Administrador</th>
        <th>Siniestro</th>
        <th>Acciones</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="row in usuarios" :key="row.id">
        <td>{{ row.id }}</td>
        <td>{{ row.full_name }}</td>
        <td>{{ row.username }}</td>
        <td>{{ row.is_admin === 1 ? 'Administrador' : 'Voluntario' }}</td>
        <td>{{ row.siniestro_id }}</td>
        <td>
          <button id="btn-edit" class="edit"><i class="fa-solid fa-pen-to-square"></i></button>
          <button id="btn-delete" class="delete"><i class="fa-solid fa-trash"></i></button>
        </td>
      </tr>
      </tbody>
    </table>
  </div>
</template>

<style scoped>

</style>
