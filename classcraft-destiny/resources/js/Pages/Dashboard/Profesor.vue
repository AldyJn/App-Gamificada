<template>
  <Head title="Dashboard Profesor" />
  
  <div class="min-h-screen bg-gradient-to-br from-gray-900 via-blue-900 to-purple-900">
    <!-- Header del Dashboard -->
    <div class="bg-gray-800/50 backdrop-blur-sm border-b border-gray-700">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-3xl font-bold text-white">Dashboard Profesor</h1>
            <p class="text-gray-300 mt-1">Bienvenido, {{ profesor.nombre }}</p>
          </div>
          <div class="flex items-center space-x-4">
            <button 
              @click="mostrarModalCrearClase = true"
              class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center space-x-2"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
              </svg>
              <span>Crear Clase</span>
            </button>
            <button 
              @click="cargarDatos"
              class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-3 rounded-lg font-medium transition-colors duration-200"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
              </svg>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Estadísticas -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-gray-800/50 backdrop-blur-sm rounded-xl p-6 border border-gray-700">
          <div class="flex items-center">
            <div class="p-3 bg-blue-500/20 rounded-lg">
              <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-400">Total Clases</p>
              <p class="text-2xl font-bold text-white">{{ estadisticas.total_clases }}</p>
            </div>
          </div>
        </div>
        
        <div class="bg-gray-800/50 backdrop-blur-sm rounded-xl p-6 border border-gray-700">
          <div class="flex items-center">
            <div class="p-3 bg-green-500/20 rounded-lg">
              <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-400">Clases Activas</p>
              <p class="text-2xl font-bold text-white">{{ estadisticas.clases_activas }}</p>
            </div>
          </div>
        </div>

        <div class="bg-gray-800/50 backdrop-blur-sm rounded-xl p-6 border border-gray-700">
          <div class="flex items-center">
            <div class="p-3 bg-purple-500/20 rounded-lg">
              <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-400">Total Estudiantes</p>
              <p class="text-2xl font-bold text-white">{{ estadisticas.total_estudiantes }}</p>
            </div>
          </div>
        </div>

        <div class="bg-gray-800/50 backdrop-blur-sm rounded-xl p-6 border border-gray-700">
          <div class="flex items-center">
            <div class="p-3 bg-yellow-500/20 rounded-lg">
              <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-400">Promedio por Clase</p>
              <p class="text-2xl font-bold text-white">{{ estadisticas.promedio_estudiantes }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Lista de Clases -->
      <div class="bg-gray-800/50 backdrop-blur-sm rounded-xl border border-gray-700">
        <div class="px-6 py-4 border-b border-gray-700">
          <h2 class="text-xl font-bold text-white">Mis Clases</h2>
          <p class="text-gray-400 mt-1">Administra tus clases y estudiantes</p>
        </div>

        <div class="p-6">
          <!-- Estado sin clases -->
          <div v-if="clases.length === 0" class="text-center py-12">
            <svg class="w-16 h-16 text-gray-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            <h3 class="text-lg font-medium text-gray-300 mb-2">No tienes clases creadas</h3>
            <p class="text-gray-500 mb-6">Comienza creando tu primera clase para gestionar estudiantes</p>
            <button 
              @click="mostrarModalCrearClase = true"
              class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200 inline-flex items-center space-x-2"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
              </svg>
              <span>Crear Primera Clase</span>
            </button>
          </div>

          <!-- Grid de clases -->
          <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div 
              v-for="clase in clases" 
              :key="clase.id"
              class="bg-gray-700/50 rounded-lg p-6 border border-gray-600 hover:border-blue-500 transition-all duration-200 hover:shadow-lg"
            >
              <!-- Header de la clase -->
              <div class="flex items-start justify-between mb-4">
                <div>
                  <h3 class="text-lg font-bold text-white mb-1">{{ clase.nombre }}</h3>
                  <p class="text-sm text-gray-400">Código: {{ clase.codigo }}</p>
                </div>
                <div class="flex items-center space-x-2">
                  <span 
                    :class="clase.activa ? 'bg-green-500' : 'bg-red-500'"
                    class="inline-block w-3 h-3 rounded-full"
                  ></span>
                  <span 
                    :class="clase.activa ? 'text-green-400' : 'text-red-400'"
                    class="text-xs font-medium"
                  >
                    {{ clase.activa ? 'Activa' : 'Inactiva' }}
                  </span>
                </div>
              </div>

              <!-- Descripción -->
              <p v-if="clase.descripcion" class="text-gray-300 text-sm mb-4 line-clamp-2">
                {{ clase.descripcion }}
              </p>

              <!-- Información de fechas -->
              <div class="space-y-2 mb-4">
                <div class="flex items-center text-sm text-gray-400">
                  <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                  </svg>
                  <span>Inicio: {{ formatearFecha(clase.fecha_inicio) }}</span>
                </div>
                <div class="flex items-center text-sm text-gray-400">
                  <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                  </svg>
                  <span>Fin: {{ formatearFecha(clase.fecha_fin) }}</span>
                </div>
              </div>

              <!-- Estadísticas de la clase -->
              <div class="flex items-center justify-between mb-4">
                <div class="flex items-center text-sm text-gray-300">
                  <svg class="w-4 h-4 mr-2 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                  </svg>
                  <span>{{ clase.total_estudiantes }} estudiantes</span>
                </div>
                <div class="text-xs text-gray-400">
                  Progreso: {{ clase.progreso.porcentaje }}%
                </div>
              </div>

              <!-- Acciones -->
              <div class="flex items-center space-x-2">
                <button 
                  @click="verClase(clase.id)"
                  class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded text-sm font-medium transition-colors duration-200"
                >
                  Ver Clase
                </button>
                <button 
                  v-if="clase.puede_seleccionar"
                  @click="seleccionarEstudianteAleatorio(clase)"
                  class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded text-sm font-medium transition-colors duration-200 flex items-center"
                  title="Seleccionar estudiante al azar"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m-9 0h10m-10 0a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V6a2 2 0 00-2-2"/>
                  </svg>
                </button>
                <button 
                  v-else
                  disabled
                  class="bg-gray-600 text-gray-400 px-3 py-2 rounded text-sm font-medium cursor-not-allowed"
                  title="No hay estudiantes en esta clase"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"/>
                  </svg>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Crear Clase -->
    <div v-if="mostrarModalCrearClase" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
      <div class="bg-gray-800 rounded-xl p-6 w-full max-w-md border border-gray-700">
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-xl font-bold text-white">Crear Nueva Clase</h3>
          <button 
            @click="cerrarModalCrearClase"
            class="text-gray-400 hover:text-white transition-colors duration-200"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
        </div>

        <form @submit.prevent="crearClase">
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-300 mb-2">Nombre de la Clase *</label>
              <input 
                v-model="formularioClase.nombre"
                type="text" 
                required
                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Ej: Matemáticas 3ro A"
              >
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-300 mb-2">Descripción</label>
              <textarea 
                v-model="formularioClase.descripcion"
                rows="3"
                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Descripción opcional de la clase"
              ></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Grado</label>
                <input 
                  v-model="formularioClase.grado"
                  type="text" 
                  class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  placeholder="Ej: 3ro"
                >
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Sección</label>
                <input 
                  v-model="formularioClase.seccion"
                  type="text" 
                  class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  placeholder="Ej: A"
                >
              </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Fecha Inicio *</label>
                <input 
                  v-model="formularioClase.fecha_inicio"
                  type="date" 
                  required
                  :min="fechaHoy"
                  class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Fecha Fin *</label>
                <input 
                  v-model="formularioClase.fecha_fin"
                  type="date" 
                  required
                  :min="formularioClase.fecha_inicio || fechaHoy"
                  class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
              </div>
            </div>
          </div>

          <div class="flex items-center justify-end space-x-3 mt-6">
            <button 
              type="button"
              @click="cerrarModalCrearClase"
              class="px-4 py-2 text-gray-400 hover:text-white transition-colors duration-200"
            >
              Cancelar
            </button>
            <button 
              type="submit"
              :disabled="creandoClase"
              class="bg-blue-600 hover:bg-blue-700 disabled:bg-blue-800 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center space-x-2"
            >
              <svg v-if="creandoClase" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <span>{{ creandoClase ? 'Creando...' : 'Crear Clase' }}</span>
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Modal Estudiante Seleccionado -->
    <div v-if="mostrarModalEstudiante" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
      <div class="bg-gray-800 rounded-xl p-6 w-full max-w-md border border-gray-700 text-center">
        <div class="mb-6">
          <div class="w-16 h-16 bg-green-500/20 rounded-full mx-auto mb-4 flex items-center justify-center">
            <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
          </div>
          <h3 class="text-xl font-bold text-white mb-2">¡Estudiante Seleccionado!</h3>
          <p class="text-gray-300">{{ estudianteSeleccionado?.mensaje }}</p>
        </div>

        <button 
          @click="cerrarModalEstudiante"
          class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200"
        >
          Entendido
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { Head } from '@inertiajs/vue3'

// Props
const props = defineProps({
  clases: {
    type: Array,
    default: () => []
  },
  estadisticas: {
    type: Object,
    default: () => ({})
  },
  profesor: {
    type: Object,
    default: () => ({})
  }
})

// Estado reactivo
const clases = ref(props.clases)
const estadisticas = ref(props.estadisticas)
const profesor = ref(props.profesor)
const mostrarModalCrearClase = ref(false)
const mostrarModalEstudiante = ref(false)
const creandoClase = ref(false)
const estudianteSeleccionado = ref(null)

// Formulario para crear clase
const formularioClase = ref({
  nombre: '',
  descripcion: '',
  grado: '',
  seccion: '',
  fecha_inicio: '',
  fecha_fin: ''
})

// Computed
const fechaHoy = computed(() => {
  return new Date().toISOString().split('T')[0]
})

// Métodos
const formatearFecha = (fecha) => {
  return new Date(fecha).toLocaleDateString('es-ES', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric'
  })
}

const crearClase = async () => {
  creandoClase.value = true
  
  try {
    const response = await fetch('/profesor/clases', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify(formularioClase.value)
    })

    const data = await response.json()

    if (data.success) {
      // Agregar la nueva clase a la lista
      clases.value.push(data.clase)
      
      // Actualizar estadísticas
      estadisticas.value.total_clases++
      estadisticas.value.clases_activas++
      
      // Cerrar modal y resetear formulario
      cerrarModalCrearClase()
      
      // Mostrar mensaje de éxito
      alert(`¡Clase creada exitosamente! Código: ${data.clase.codigo}`)
    } else {
      alert('Error al crear la clase: ' + data.message)
    }
  } catch (error) {
    console.error('Error:', error)
    alert('Error al crear la clase')
  } finally {
    creandoClase.value = false
  }
}

const seleccionarEstudianteAleatorio = async (clase) => {
  try {
    const response = await fetch(`/profesor/clases/${clase.id}/seleccionar-aleatorio`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    })

    const data = await response.json()

    if (data.success) {
      estudianteSeleccionado.value = data.estudiante
      mostrarModalEstudiante.value = true
    } else {
      alert(data.message || 'No hay estudiantes disponibles en esta clase')
    }
  } catch (error) {
    console.error('Error:', error)
    alert('Error al seleccionar estudiante')
  }
}

const verClase = (claseId) => {
  window.location.href = `/profesor/clases/${claseId}`
}

const cargarDatos = () => {
  window.location.reload()
}

const cerrarModalCrearClase = () => {
  mostrarModalCrearClase.value = false
  formularioClase.value = {
    nombre: '',
    descripcion: '',
    grado: '',
    seccion: '',
    fecha_inicio: '',
    fecha_fin: ''
  }
}

const cerrarModalEstudiante = () => {
  mostrarModalEstudiante.value = false
  estudianteSeleccionado.value = null
}

// Inicialización
onMounted(() => {
  // Configurar fecha mínima por defecto
  if (!formularioClase.value.fecha_inicio) {
    formularioClase.value.fecha_inicio = fechaHoy.value
  }
})
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  line-clamp: 2; 
  overflow: hidden;
}
</style>