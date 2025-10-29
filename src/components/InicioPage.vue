<script setup>
import { ref, onMounted } from 'vue'
import api from '@/api'

const siniestros = ref([])
const user = JSON.parse(localStorage.getItem('user') || '{}')
const showModal = ref(false)

// Cargar siniestros
const cargarPosts = async () => {
  try {
    const response = await api.get('siniestros_list.php')
    siniestros.value = response.data
  } catch (error) {
    console.error('Error al cargar los siniestros:', error)
  }
}

const openDropdown = ref(null)

const toggleDropdown = (id) => {
  openDropdown.value = openDropdown.value === id ? null : id
}

const openModal = () => (showModal.value = true)
const closeModal = () => (showModal.value = false)

// Unirse como voluntario
const unirseComoVoluntario = async (id) => {
  try {
    const formData = new FormData()
    formData.append('usuario_id', user.id)
    formData.append('siniestro_id', id)
    const response = await api.post('usuarios_update_voluntario.php', formData)
    if (response.data.status === 'success') {
      alert('Te has unido como voluntario al siniestro.')
      cargarPosts()
    } else {
      alert(response.data.message)
      console.log(user.id)
    }
  } catch {
    alert('Error al conectar con el servidor.')
  }
}

onMounted(() => {
  cargarPosts()
})
</script>

<template>
  <div id="inicio-page" class="inicio-page">
    <div v-if="siniestros.length" class="posts">
      <div v-for="s in siniestros" :key="s.id" class="post">
        <div class="post-header">
          <h3>🔥 Siniestro nivel {{ s.level }}</h3>
          <span class="level">Nivel: {{ s.level }}</span>
        </div>

        <div class="post-body">
          <div class="izquierda">
            <p><strong>Dirección:</strong> {{ s.location }}</p>
            <p><strong>Fecha y hora:</strong> {{ s.date_time }}</p>
            <div class="resources">
              <p><strong>Recursos necesarios:</strong></p>
              <ul>
                <li v-for="r in s.resources.split(',')" :key="r">{{ r.trim() }}</li>
              </ul>
            </div>
          </div>
          <div class="derecha">
            <img src="../assets/img/incendio.jpg" alt="Siniestro" />
          </div>
        </div>

        <div class="post-footer">
          <div class="join-btn">
            <button class="main-btn" @click="toggleDropdown(s.id)">
              Quiero unirme <i class="fa-solid fa-chevron-down"></i>
            </button>

            <div v-if="openDropdown === s.id" class="dropdown">
              <button @click="unirseComoVoluntario(s.id)">Como voluntario</button>
              <button @click="openModal(s.id)">Como donador</button>
              <button
                @click="
                  () => {
                    unirseComoVoluntario(s.id)
                    abrirModalDonador(s.id)
                  }
                "
              >
                Como voluntario y donador
              </button>
            </div>
          </div>
          <span class="counter">{{ s.total_voluntarios }} voluntarios</span>
        </div>
      </div>
    </div>

    <p v-else class="no-posts">No hay publicaciones por el momento.</p>
  </div>

  <div v-if="showModal" class="modal-overlay" @click.self="closeModal">
    <div class="modal-content">
      <h2>Nuevos recursos</h2>
      <form @submit.prevent="registrarCita">
        <label>Nombre del recurso:</label>
        <input type="text" v-model="name" required />

        <label>Descripción:</label>
        <textarea v-model="description" required />

        <label>Categoría</label>
        <select v-model="category" required>
          <option value="consumible">Consumible</option>
          <option value="material">Material</option>
        </select>

        <label>Cantidad:</label>
        <input type="number" v-model="quantity" required />

        <div class="modal-actions">
          <button type="button" @click="closeModal">Cancelar</button>
          <button type="submit">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</template>

<style scoped>
.post-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 12px;
}

.main-btn {
  background: #ff7043;
  color: white;
  padding: 8px 12px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 500;
  display: flex;
  align-items: center;
  gap: 6px;
}

.main-btn:hover {
  background: #e53935;
}

.dropdown {
  display: flex;
  flex-direction: column;
  margin-top: 8px;
  background: #fdeaea;
  border-radius: 8px;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
  overflow: hidden;
  animation: fadeIn 0.2s ease;
}

.dropdown button {
  background: none;
  border: none;
  padding: 8px 10px;
  text-align: left;
  cursor: pointer;
  color: #e53935;
}

.dropdown button:hover {
  background: #ffccbc;
}

.counter {
  color: #e53935;
  font-weight: 600;
  font-size: 0.95rem;
  margin-left: 12px;
  white-space: nowrap;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(-4px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>
