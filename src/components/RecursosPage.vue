<script setup>
import { ref, onMounted } from 'vue'
const recursos = ref([])
import api from '@/api'

onMounted(async () => {
  try {
    const response = await api.get('recursos_list.php')
    recursos.value = response.data
  } catch (error) {
    console.error('Error al cargar recursos:', error)
  }

})
</script>

<template>
  <div id="recursos">

    <table id="recursos-table" class="tabla" border="1">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>Descripcion</th>
          <th>Categoria</th>
          <th>Cantidad</th>
          <th>ID de usuario</th>
          <th>ID de siniestro</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="row in recursos" :key="row.id">
          <td>{{ row.id }}</td>
          <td>{{ row.name }}</td>
          <td>{{ row.description }}</td>
          <td>{{ row.category }}</td>
          <td>{{ row.quantity }}</td>
          <td>{{ row.usuario_id }}</td>
            <td>{{ row.siniestro_id }}</td>

        </tr>
      </tbody>
    </table>
  </div>
</template>

<style scoped>

</style>
