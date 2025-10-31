<script setup>
import { ref, onMounted } from 'vue'
import api from '@/api'

const usuarios = ref([])
const showModal = ref(false)
const showDeleteModal = ref(false)
const usuarioIdToDelete = ref(null)
const selectedUsuario = ref(null)

const isEditing = ref(false)
const editingId = ref(null)

const full_name = ref(null)
const username = ref(null)
const is_admin = ref(0)
const siniestro_id = ref(null)


const cargarUsuarios = async () => {
  try {
    const response = await api.get('usuarios_list.php')
    usuarios.value = response.data
  } catch (error) {
    console.error('Error al cargar usuarios', error)
  }
}

onMounted(() => {
  cargarUsuarios()
})


const eliminarUsuario = async () => {
  try {
    const formData = new FormData()
    formData.append('id', selectedUsuario.value)
    const respuesta = await api.post('usuarios_delete.php', formData)
    if (respuesta.data.status === 'success') {
      await cargarUsuarios()
      closeDeleteModal()
    } else {
      alert(respuesta.data.message || 'No se pudo eliminar el usuario.')
    }
  } catch (err) {
    console.error('Error al eliminar el usuario:', err)
  }
}



const openModal = (usuario = null) => {
  if (usuario) {
    isEditing.value = true
    editingId.value = usuario.id
    full_name.value = usuario.full_name
    username.value = usuario.username
    is_admin.value = usuario.is_admin
    siniestro_id.value = usuario.siniestro_id
  } else {
    isEditing.value = false
    editingId.value = null
    limpiarFormulario()
  }
  showModal.value = true
}


const closeModal = () => {
  limpiarFormulario()
  isEditing.value = false
  editingId.value = null
  showModal.value = false
}


const limpiarFormulario = () => {
  full_name.value = ''
  username.value = ''
  is_admin.value = 0
  siniestro_id.value = null
}

const guardarusuario = async () => {
  if (isEditing.value) {
    await editarUsuario()
  } else {
    await registrarUsuario()
  }
}


const openDeleteModal = (usuario = null) => {
  usuarioIdToDelete.value = usuario.id
  showDeleteModal.value = true
}




const closeDeleteModal = () => {
  usuarioIdToDelete.value = null
  showDeleteModal.value = false
}

</script>

<template>

  <div id="usuarios">
    <button id="btn-new-siniestro" @click="openModal()" style="margin-top: 20px">
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
            <button id="btn-edit" class="edit" @click="openModal(row)"><i
                class="fa-solid fa-pen-to-square"></i></button>
            <button id="btn-delete" class="delete" @click="openDeleteModal(row)"><i
                class="fa-solid fa-trash"></i></button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>





  <div v-if="showModal" class="modal-overlay" @click.self="closeModal">
    <div class="modal-content">
      <h2>{{ isEditing ? 'Editar usuaruo' : 'Nuevo usuario' }}</h2>
      <form @submit.prevent="guardarusuario">
        <label >Nombre Completo:</label>
        <input type="text" v-model="full_name" required/>
        <label for="username">Usuario:</label>
        <input type="text" v-model="username" required/>
        <label for="is_admin">Administrador:</label>
        <select v-model="is_admin" required>
          <option value="0">Voluntario</option>
          <option value="1">Administrador</option>
        </select>
        <label >ID de Siniestro:</label>
        <input type="number" v-model="siniestro_id" required/>

        <div class="modal-actions">
          <button type="button" @click="closeModal">Cancelar</button>
          <button type="submit">{{ isEditing ? 'Actualizar' : 'Guardar' }}</button>
        </div>


      </form>


    </div>
  </div>




    <div v-if="showDeleteModal" class="modal-overlay" @click.self="closeDeleteModal">
      <div class="modal-content">
        <h2>Eliminar usuario</h2>
        <p>¿Esta seguro que desea eliminar el usuario {{ usuarioIdToDelete }}?</p>
        <div class="modal-actions">
          <button type="button" @click="closeDeleteModal">Cancelar</button>
          <button type="button" @click="eliminarUsuario">Confirmar</button>
        </div>
      </div>
    </div>
  
</template>

<style scoped></style>
