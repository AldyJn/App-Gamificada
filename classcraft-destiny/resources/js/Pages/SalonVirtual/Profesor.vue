<template>
  <Head title="Salón Virtual - Profesor" />

  <AuthenticatedLayout>
    <div class="salon-container">
      <!-- HUD Superior -->
      <div class="hud-top">
        <div class="clase-info">
          <h2 class="text-2xl font-bold text-white">{{ clase.nombre }}</h2>
          <p class="text-sm text-gray-400">{{ estudiantes.length }} estudiantes conectados</p>
        </div>
        
        <div class="controles">
          <button
            @click="seleccionarEstudianteAleatorio"
            :disabled="estudiantes.length === 0 || seleccionando"
            class="destiny-btn destiny-btn-primary"
          >
            <i class="mdi mdi-dice-6 mr-2"></i>
            {{ seleccionando ? 'Seleccionando...' : 'Selección Aleatoria' }}
          </button>
        </div>
      </div>

      <!-- Área principal -->
      <div class="salon-area">
        <!-- Grid de estudiantes -->
        <div class="estudiantes-grid">
          <div
            v-for="estudiante in estudiantes"
            :key="estudiante.id"
            class="estudiante-card"
            :class="{ 'selected': estudianteSeleccionado?.id === estudiante.id }"
          >
            <div class="estudiante-avatar">
              <span class="text-4xl">
                {{ estudiante.personaje ? getAvatarEmoji(estudiante.personaje.avatar) : '👤' }}
              </span>
              <div v-if="estudiante.personaje" class="nivel-badge">
                {{ estudiante.personaje.nivel }}
              </div>
            </div>
            
            <div class="estudiante-info">
              <p class="font-bold text-white">{{ estudiante.nombre }}</p>
              <p v-if="estudiante.personaje" class="text-sm text-purple-400">
                {{ estudiante.personaje.nombre }}
              </p>
              <p v-if="estudiante.personaje" class="text-xs text-gray-500">
                {{ estudiante.personaje.clase_rpg }}
              </p>
            </div>
            
            <div class="estudiante-actions">
              <button
                @click="seleccionarEstudiante(estudiante)"
                class="action-btn"
                title="Seleccionar estudiante"
              >
                <i class="mdi mdi-target"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- Modal de estudiante seleccionado -->
        <Transition name="modal">
          <div v-if="estudianteSeleccionado" class="modal-overlay" @click="cerrarSeleccion">
            <div class="modal-content" @click.stop>
              <div class="selected-header">
                <h3 class="text-2xl font-bold text-cyan-400">
                  ¡GUARDIÁN SELECCIONADO!
                </h3>
              </div>
              
              <div class="selected-body">
                <div class="selected-avatar">
                  <span class="text-6xl">
                    {{ estudianteSeleccionado.personaje ? 
                       getAvatarEmoji(estudianteSeleccionado.personaje.avatar) : '👤' }}
                  </span>
                </div>
                
                <h4 class="text-xl font-bold text-white mt-4">
                  {{ estudianteSeleccionado.nombre }}
                </h4>
                
                <div v-if="estudianteSeleccionado.personaje" class="personaje-details">
                  <p class="text-purple-400">{{ estudianteSeleccionado.personaje.nombre }}</p>
                  <p class="text-gray-400">
                    {{ estudianteSeleccionado.personaje.clase_rpg }} • 
                    Nivel {{ estudianteSeleccionado.personaje.nivel }}
                  </p>
                </div>
              </div>
              
              <div class="selected-footer">
                <button @click="cerrarSeleccion" class="destiny-btn destiny-btn-ghost">
                  Cerrar
                </button>
              </div>
            </div>
          </div>
        </Transition>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Head } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
  clase: Object,
  estudiantes: Array,
  configuracion: Object
})

const estudianteSeleccionado = ref(null)
const seleccionando = ref(false)

const getAvatarEmoji = (avatar) => {
  const avatars = {
    'default': '⚔️',
    'guerrero': '🛡️',
    'mago': '🔮',
    'arquero': '🏹',
    'sanador': '💚',
    'explorador': '🗺️'
  }
  return avatars[avatar] || avatars['default']
}

const seleccionarEstudianteAleatorio = async () => {
  if (props.estudiantes.length === 0) return
  
  seleccionando.value = true
  
  // Efecto de selección aleatoria
  let iterations = 0
  const maxIterations = 15
  const interval = setInterval(() => {
    const randomIndex = Math.floor(Math.random() * props.estudiantes.length)
    estudianteSeleccionado.value = props.estudiantes[randomIndex]
    
    iterations++
    if (iterations >= maxIterations) {
      clearInterval(interval)
      seleccionando.value = false
    }
  }, 100)
}

const seleccionarEstudiante = (estudiante) => {
  estudianteSeleccionado.value = estudiante
}

const cerrarSeleccion = () => {
  estudianteSeleccionado.value = null
}
</script>

<style scoped>
.salon-container {
  @apply min-h-screen bg-gray-900 relative;
  background-image: 
    radial-gradient(ellipse at center, #0f172a 0%, #000000 70%);
}

.estudiantes-grid {
  @apply grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6;
  @apply max-w-7xl mx-auto;
}

.estudiante-card {
  @apply relative bg-gray-800/50 rounded-lg p-6;
  @apply border-2 border-gray-700 hover:border-cyan-500/50;
  @apply transition-all duration-200;
}

.estudiante-card.selected {
  @apply border-cyan-400 bg-cyan-900/20;
  box-shadow: 0 0 30px rgba(6, 182, 212, 0.5);
}

.estudiante-avatar {
  @apply relative w-20 h-20 rounded-full bg-gray-700/50 mx-auto mb-3;
  @apply flex items-center justify-center;
}

.nivel-badge {
  @apply absolute -top-1 -right-1;
  @apply bg-cyan-600 text-white text-sm w-6 h-6;
  @apply rounded-full flex items-center justify-center font-bold;
}

.action-btn {
  @apply w-8 h-8 rounded-full bg-gray-700 hover:bg-cyan-600;
  @apply flex items-center justify-center text-gray-400 hover:text-white;
  @apply transition-all duration-200;
}

/* Modal styles */
.modal-overlay {
  @apply fixed inset-0 bg-black/80 backdrop-blur-sm;
  @apply flex items-center justify-center z-50;
}

.modal-content {
  @apply bg-gray-800 rounded-lg overflow-hidden;
  @apply border-2 border-cyan-500/50 max-w-md w-full;
  box-shadow: 0 0 50px rgba(6, 182, 212, 0.5);
}

.selected-header {
  @apply bg-gray-900 px-6 py-4 text-center;
  border-bottom: 2px solid rgba(6, 182, 212, 0.5);
}

.selected-body {
  @apply p-8 text-center;
}

.selected-avatar {
  @apply w-32 h-32 rounded-full bg-cyan-600/20 border-2 border-cyan-500;
  @apply flex items-center justify-center mx-auto;
  animation: pulse 2s infinite;
}

@keyframes pulse {
  0%, 100% {
    transform: scale(1);
    opacity: 1;
  }
  50% {
    transform: scale(1.05);
    opacity: 0.8;
  }
}

.modal-enter-active, .modal-leave-active {
  transition: opacity 0.3s ease;
}

.modal-enter-from, .modal-leave-to {
  opacity: 0;
}
</style>