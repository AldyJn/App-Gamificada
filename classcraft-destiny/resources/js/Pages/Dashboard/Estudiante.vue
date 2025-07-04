<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-900 via-blue-900 to-purple-900">
    
    <!-- Header -->
    <header class="bg-black/20 backdrop-blur-lg border-b border-white/10">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
          <div class="flex items-center space-x-4">
            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-500 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
              </svg>
            </div>
            <div>
              <h1 class="text-xl font-bold text-white">Mi Dashboard</h1>
              <p class="text-sm text-gray-300">{{ estudiante.nombre }}</p>
            </div>
          </div>
          
          <div class="flex items-center space-x-4">
            <button @click="mostrarModalUnirse = true" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center space-x-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
              </svg>
              <span>Unirse a Clase</span>
            </button>
            
            <div class="relative">
              <button @click="mostrarMenuUsuario = !mostrarMenuUsuario" class="flex items-center space-x-2 text-white hover:text-gray-300 transition-colors duration-200">
                <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center">
                  <span class="text-sm font-semibold">{{ iniciales }}</span>
                </div>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
              </button>
              
              <!-- Menú desplegable -->
              <div v-if="mostrarMenuUsuario" class="absolute right-0 mt-2 w-48 bg-gray-800 rounded-lg shadow-lg border border-gray-700 z-50">
                <div class="py-1">
                  <a href="/perfil" class="block px-4 py-2 text-gray-300 hover:text-white hover:bg-gray-700 transition-colors duration-200">Mi Perfil</a>
                  <form @submit.prevent="cerrarSesion" class="block">
                    <button type="submit" class="w-full text-left px-4 py-2 text-gray-300 hover:text-white hover:bg-gray-700 transition-colors duration-200">Cerrar Sesión</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </header>

    <!-- Contenido Principal -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      
      <!-- Estadísticas Generales -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 border border-white/20">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-gray-300 text-sm">Mis Clases</p>
              <p class="text-3xl font-bold text-white">{{ estadisticas.total_clases }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-500/20 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 border border-white/20">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-gray-300 text-sm">Clases Activas</p>
              <p class="text-3xl font-bold text-white">{{ estadisticas.clases_activas }}</p>
            </div>
            <div class="w-12 h-12 bg-green-500/20 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 border border-white/20">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-gray-300 text-sm">Actividades</p>
              <p class="text-3xl font-bold text-white">{{ estadisticas.actividades_completadas }}</p>
            </div>
            <div class="w-12 h-12 bg-purple-500/20 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 border border-white/20">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-gray-300 text-sm">XP Total</p>
              <p class="text-3xl font-bold text-white">{{ estadisticas.xp_total.toLocaleString() }}</p>
            </div>
            <div class="w-12 h-12 bg-yellow-500/20 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
              </svg>
            </div>
          </div>
        </div>
      </div>

      <!-- Mensaje de bienvenida si no hay clases -->
      <div v-if="clases.length === 0" class="text-center py-12">
        <div class="max-w-md mx-auto">
          <div class="w-24 h-24 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
          </div>
          <h2 class="text-2xl font-bold text-white mb-4">¡Bienvenido!</h2>
          <p class="text-gray-300 mb-6">Aún no estás inscrito en ninguna clase. Solicita el código a tu profesor y únete a tu primera clase.</p>
          <button @click="mostrarModalUnirse = true" class="bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200">
            Unirse a una Clase
          </button>
        </div>
      </div>

      <!-- Lista de Clases -->
      <div v-else>
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-2xl font-bold text-white">Mis Clases</h2>
          <div class="flex items-center space-x-4">
            <select v-model="filtroClases" class="bg-gray-800 border border-gray-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-purple-500">
              <option value="todas">Todas las clases</option>
              <option value="activas">Solo activas</option>
              <option value="inactivas">Finalizadas</option>
            </select>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div 
            v-for="clase in clasesFiltradas" 
            :key="clase.id"
            class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 border border-white/20 hover:border-white/30 transition-all duration-200 cursor-pointer"
            @click="verClase(clase.id)"
          >
            <div class="flex items-start justify-between mb-4">
              <div class="flex-1">
                <h3 class="text-lg font-semibold text-white mb-2 line-clamp-2">{{ clase.nombre }}</h3>
                <p class="text-gray-300 text-sm mb-2">{{ clase.docente }}</p>
                <div class="flex items-center space-x-2">
                  <span 
                    :class="clase.activa ? 'bg-green-500/20 text-green-400' : 'bg-gray-500/20 text-gray-400'" 
                    class="px-2 py-1 rounded-full text-xs font-medium"
                  >
                    {{ clase.activa ? 'Activa' : 'Finalizada' }}
                  </span>
                  <span class="text-gray-400 text-xs">Código: {{ clase.codigo }}</span>
                </div>
              </div>
            </div>

            <div v-if="clase.descripcion" class="mb-4">
              <p class="text-gray-300 text-sm line-clamp-2">{{ clase.descripcion }}</p>
            </div>

            <!-- Progreso de la clase -->
            <div class="mb-4">
              <div class="flex items-center justify-between mb-2">
                <span class="text-gray-300 text-sm">Progreso del curso</span>
                <span class="text-white text-sm font-medium">{{ clase.progreso }}%</span>
              </div>
              <div class="w-full bg-gray-700 rounded-full h-2">
                <div 
                  class="bg-gradient-to-r from-purple-500 to-pink-500 h-2 rounded-full transition-all duration-300"
                  :style="{ width: clase.progreso + '%' }"
                ></div>
              </div>
            </div>

            <!-- Fechas -->
            <div class="flex items-center justify-between text-xs text-gray-400">
              <span>Inicio: {{ formatearFecha(clase.fecha_inicio) }}</span>
              <span>Fin: {{ formatearFecha(clase.fecha_fin) }}</span>
            </div>

            <!-- Fecha de inscripción -->
            <div class="mt-2 pt-2 border-t border-gray-600">
              <p class="text-xs text-gray-400">Te uniste: {{ formatearFecha(clase.fecha_inscripcion) }}</p>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- Modal Unirse a Clase -->
    <div v-if="mostrarModalUnirse" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 z-50" @click="cerrarModalUnirse">
      <div class="bg-gray-800 rounded-2xl p-6 w-full max-w-md border border-gray-700" @click.stop>
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-xl font-bold text-white">Unirse a Nueva Clase</h3>
          <button @click="cerrarModalUnirse" class="text-gray-400 hover:text-white transition-colors duration-200">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
        </div>

        <form @submit.prevent="unirseAClase" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-white mb-2">Código de Clase</label>
            <input 
              v-model="codigoNuevaClase"
              type="text" 
              maxlength="6"
              placeholder="Ej: ABC123"
              class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent uppercase tracking-wider text-center"
              required
            >
          </div>

          <div v-if="errorUnirse" class="p-3 bg-red-500/20 border border-red-500 rounded-lg">
            <p class="text-red-400 text-sm">{{ errorUnirse }}</p>
          </div>

          <div class="flex items-center justify-end space-x-3">
            <button 
              type="button"
              @click="cerrarModalUnirse"
              class="px-4 py-2 text-gray-400 hover:text-white transition-colors duration-200"
            >
              Cancelar
            </button>
            <button 
              type="submit"
              :disabled="uniendose"
              class="bg-purple-600 hover:bg-purple-700 disabled:bg-purple-800 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center space-x-2"
            >
              <svg v-if="uniendose" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <span>{{ uniendose ? 'Uniéndose...' : 'Unirse' }}</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>
<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { router } from '@inertiajs/vue3'

// Props
const props = defineProps({
  clases: Array,
  estadisticas: Object,
  estudiante: Object
})

// Estado reactivo
const mostrarMenuUsuario = ref(false)
const mostrarModalUnirse = ref(false)
const codigoNuevaClase = ref('')
const uniendose = ref(false)
const errorUnirse = ref('')
const filtroClases = ref('todas')

// Computed
const iniciales = computed(() => {
  const nombres = props.estudiante.nombre.split(' ')
  return nombres.map(n => n.charAt(0)).join('').substring(0, 2).toUpperCase()
})

const clasesFiltradas = computed(() => {
  if (filtroClases.value === 'activas') {
    return props.clases.filter(clase => clase.activa)
  } else if (filtroClases.value === 'inactivas') {
    return props.clases.filter(clase => !clase.activa)
  }
  return props.clases
})

// Métodos
const formatearFecha = (fecha) => {
  return new Date(fecha).toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

const verClase = (claseId) => {
  router.get(`/estudiante/clase/${claseId}`)
}

const unirseAClase = async () => {
  if (!codigoNuevaClase.value.trim()) return

  uniendose.value = true
  errorUnirse.value = ''

  try {
    await router.post('/estudiante/unirse', {
      codigo_clase: codigoNuevaClase.value.toUpperCase().trim()
    }, {
      onError: (errors) => {
        errorUnirse.value = errors.codigo_clase || 'Error al unirse a la clase'
      },
      onSuccess: () => {
        cerrarModalUnirse()
      }
    })
  } catch (error) {
    console.error('Error:', error)
    errorUnirse.value = 'Error al unirse a la clase'
  } finally {
    uniendose.value = false
  }
}

const cerrarModalUnirse = () => {
  mostrarModalUnirse.value = false
  codigoNuevaClase.value = ''
  errorUnirse.value = ''
}

const cerrarSesion = async () => {
  await router.post('/logout')
}

// Cerrar menú cuando se hace clic afuera
const cerrarMenus = (event) => {
  if (!event.target.closest('.relative')) {
    mostrarMenuUsuario.value = false
  }
}

onMounted(() => {
  document.addEventListener('click', cerrarMenus)
})

onUnmounted(() => {
  document.removeEventListener('click', cerrarMenus)
})
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>