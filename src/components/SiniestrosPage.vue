<script setup lang="ts">
import { ref, onMounted, useId } from 'vue'
import { useRouter } from 'vue-router'
const showModal = ref(false)

const openModal = () => showModal.value = true
const closeModal = () => showModal.value = false


const location = ref(null)
const level = ref(null)
const date_time = ref(null)
const resources = ref(null)
const active = ref(null)
const router = useRouter()
const message = ref('')

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

const registrarSiniestro = async () => {
  try {

    const respuesta = await api.post('siniestros_add.php', {
      location: location.value,
      level: level.value,
      date_time: date_time.value,
      resources: resources.value,
      active: active.value
    })
    message.value = respuesta.data.message

    if (respuesta.data.status === 'success') {
      alert('Cuenta creada correctamente.')
      router.push('/login')
    } else {
      alert(respuesta.data.message || 'No se pudo crear la cuenta.')
    }
  } catch (err) {
    message.value = 'Error de conexión con el servidor'
    console.error(err)
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
            <button id="btn-edit" class="edit"><i class="fa-solid fa-pen-to-square"></i></button>
            <button id="btn-delete" class="delete"><i class="fa-solid fa-trash"></i></button>


          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <div v-if="showModal" class="modal-overlay" @click.self="closeModal">
    <div class="modal-content">
      <h2>Nuevo Siniestro</h2>
      <form @submit.prevent="registrarSiniestro">

        <label>Ubicación:</label>
        <input type="text" v-model="location" required />
        <label>Nivel:</label>
        <select v-model="level" required>
          <option value="" disabled>Seleccione nivel</option>
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
        </select>
        <label>Fecha:</label>
        <input type="date" v-model="date_time" required />
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
          <button type="submit">Guardar</button>

        </div>
      </form>
    </div>

  </div>

</template>

<style scoped>

.modal-overlay {
  position: fixed;
  top: 0; left: 0;
  width: 100%; height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal-content {
  background: #fff3f3;
  padding: 30px 25px;
  border-radius: 12px;
  width: 450px;
  max-width: 90%;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
  animation: modalFade 0.3s ease;
}

.modal-content h2 {
  margin-bottom: 20px;
  font-size: 1.6rem;
  color: #000000;
  text-align: center;
}

.modal-content label {
  display: block;
  margin-top: 12px;
  margin-bottom: 5px;
  font-weight: 500;
  color: #333;
}

.modal-content input,
.modal-content select {
  width: 400px;
  padding: 10px 12px;
  border: 1.5px solid #ccc;
  border-radius: 8px;
  font-size: 1rem;
  transition: 0.2s;
}

.modal-content input:focus,
.modal-content select:focus {
  border-color: #e53935;
  outline: none;
  box-shadow: 0 0 5px rgba(4, 181, 75, 0.4);
}

.modal-actions {
  display: flex;
  justify-content: flex-end;
  margin-top: 20px;
}

.modal-actions button {
  margin-left: 10px;
  padding: 8px 16px;
  border: none;
  border-radius: 8px;
  font-weight: 500;
  cursor: pointer;
  transition: 0.2s;
}

.modal-actions button:first-child {
  background-color: #ccc;
  color: #333;
}

.modal-actions button:first-child:hover {
  background-color: #b3b3b3;
}

.modal-actions button:last-child {
  background-color: #e53935;
  color: white;
}

.modal-actions button:last-child:hover {
  background-color: #e53935;
}



</style>
