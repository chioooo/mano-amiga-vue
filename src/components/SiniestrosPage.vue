<script setup lang="ts">
import { ref, onMounted } from 'vue'
import api from '@/api'

const siniestros = ref([])

onMounted(async () => {
  try {
    const response = await api.get('siniestros_list.php')
    siniestros.value = response.data
  } catch (error) {
    console.error('Error al cargar siniestros:', error)
  }
})
</script>

<template>
  <div id="siniestros">
    <button id="btn-new-siniestro" style="margin-top: 20px">
      <i class="fa-solid fa-plus"></i>
      Crear
    </button>
    <table id="siniestros-table" class="tabla" border="1">
      <thead>
      <tr>
        <th>ID</th>
        <th>Ubicación</th>
        <th>Nivel</th>
        <th>Fecha/Hora</th>
        <th>Recursos</th>
        <th>Activo</th>
        <th>Acciones</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="row in siniestros" :key="row.id">
        <td>{{ row.id }}</td>
        <td>{{ row.location }}</td>
        <td>{{ row.level }}</td>
        <td>{{ row.date_time }}</td>
        <td>{{ row.resources }}</td>
        <td>{{ row.active }}</td>
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
