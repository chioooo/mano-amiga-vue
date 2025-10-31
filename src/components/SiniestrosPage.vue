<script setup>
import { ref, onMounted } from 'vue'
import api from '@/api'

const showModal = ref(false)

const isEditing = ref(false)
const editingId = ref(null)

const location = ref(null)
const level = ref(null)
const date_time = ref(null)
const resources = ref(null)
const active = ref(null)
const message = ref('')

const siniestros = ref([])
const openModal = (siniestro = null) => {
  if (siniestro) {
    isEditing.value = true
    editingId.value = siniestro.id
    location.value = siniestro.location
    level.value = siniestro.level
    date_time.value = siniestro.date_time
    resources.value = siniestro.resources
    active.value = siniestro.active
  } else {
    isEditing.value = false
    editingId.value = null
    limpiarFormulario()
  }
  showModal.value = true
}
const closeModal = () => (showModal.value = false)

const cargarSiniestros = async () => {
  try {
    const response = await api.get('siniestros_list.php')
    siniestros.value = response.data
  } catch (error) {
    console.error('Error al cargar siniestros:', error)
  }
}

onMounted(() => {
  cargarSiniestros()
})

const limpiarFormulario = () => {
  location.value = ''
  level.value = ''
  date_time.value = ''
  resources.value = ''
  active.value = ''
}

const guardarSiniestro = async () => {
  if (isEditing.value) {
    await editarSiniestro()
  } else {
    await registrarSiniestro()
  }
}

const registrarSiniestro = async () => {
  try {
    const formData = new FormData()
    formData.append('location', location.value)
    formData.append('level', level.value)
    formData.append('date_time', date_time.value)
    formData.append('resources', resources.value)
    formData.append('active', active.value)

    const respuesta = await api.post('siniestros_add.php', formData)
    message.value = respuesta.data.message

    if (respuesta.data.status === 'success') {
      alert('Siniestro registrado correctamente.')
      closeModal()
      await cargarSiniestros()
    } else {
      alert(respuesta.data.message || 'No se pudo registrar el siniestro.')
    }
  } catch (err) {
    message.value = 'Error de conexión con el servidor'
    console.error(err)
  }
}

const editarSiniestro = async () => {
  try {
    const formData = new FormData()
    formData.append('id', editingId.value)
    formData.append('location', location.value)
    formData.append('level', level.value)
    formData.append('date_time', date_time.value)
    formData.append('resources', resources.value)
    formData.append('active', active.value)

    const respuesta = await api.post('siniestros_update.php', formData)
    if (respuesta.data.status === 'success') {
      alert('Siniestro actualizado correctamente.')
      closeModal()
      await cargarSiniestros()
    } else {
      alert(respuesta.data.message || 'No se pudo actualizar el siniestro.')
    }
  } catch (err) {
    console.error('Error al editar siniestro:', err)
  }
}
</script>

<template>
  <div id="siniestros">
    <button id="btn-new-siniestro" @click="openModal" style="margin-top: 20px">
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
            <button id="btn-edit" class="edit" @click="openModal(row)">
              <i class="fa-solid fa-pen-to-square"></i>
            </button>
            <button id="btn-delete" class="delete"><i class="fa-solid fa-trash"></i></button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <div v-if="showModal" class="modal-overlay" @click.self="closeModal">
    <div class="modal-content">
      <h2>{{ isEditing ? 'Editar siniestro' : 'Nuevo siniestro' }}</h2>
      <form @submit.prevent="guardarSiniestro">
        <label>Ubicación:</label>
        <input type="text" v-model="location" required />

        <label>Nivel:</label>
        <select v-model="level" required>
          <option value="" disabled>Seleccione nivel</option>
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
        </select>

        <label>Fecha y hora:</label>
        <input type="datetime-local" v-model="date_time" required />

        <label>Recursos:</label>
        <input type="text" v-model="resources" required />

        <label>Activo:</label>
        <select v-model="active" required>
          <option value="" disabled>Seleccione estado</option>
          <option value="1">Sí</option>
          <option value="0">No</option>
        </select>

        <div class="modal-actions">
          <button type="button" @click="closeModal">Cancelar</button>
          <button type="submit">{{ isEditing ? 'Actualizar' : 'Guardar' }}</button>
        </div>
      </form>
    </div>
  </div>
</template>

<style scoped></style>
