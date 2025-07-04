<template>
  <div class="min-h-screen bg-gradient-to-br from-purple-900 via-blue-900 to-indigo-900">
    <Head title="Dashboard - Estudiante" />

    <!-- Header con logout -->
    <div class="bg-black/20 backdrop-blur border-b border-white/10">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex justify-between items-center">
          <div class="flex items-center space-x-4">
            <div class="w-10 h-10 bg-purple-600 rounded-full flex items-center justify-center">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
              </svg>
            </div>
            <div>
              <h1 class="text-xl font-bold text-white">Dashboard Estudiante</h1>
              <p class="text-purple-200 text-sm">Bienvenido, {{ estudiante.nombre }}</p>
            </div>
          </div>
          
          <!-- Botones de navegación y logout -->
          <div class="flex items-center space-x-4">
            <a href="/perfil" class="text-purple-200 hover:text-white transition-colors">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
              </svg>
            </a>
            
            <button 
              @click="logout"
              class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center space-x-2"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
              </svg>
              <span>Salir</span>
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Estadísticas del estudiante -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white/10 backdrop-blur rounded-xl p-6 border border-white/20">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
              </div>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-purple-200 truncate">Mis Clases</dt>
                <dd class="text-lg font-medium text-white">{{ estadisticas.total_clases || 0 }}</dd>
              </dl>
            </div>
          </div>
        </div>

        <div class="bg-white/10 backdrop-blur rounded-xl p-6 border border-white/20">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
              </div>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-purple-200 truncate">Clases Activas</dt>
                <dd class="text-lg font-medium text-white">{{ estadisticas.clases_activas || 0 }}</dd>
              </dl>
            </div>
          </div>
        </div>

        <div class="bg-white/10 backdrop-blur rounded-xl p-6 border border-white/20">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
              </div>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-purple-200 truncate">XP Total</dt>
                <dd class="text-lg font-medium text-white">{{ estadisticas.xp_total || 0 }}</dd>
              </dl>
            </div>
          </div>
        </div>

        <div class="bg-white/10 backdrop-blur rounded-xl p-6 border border-white/20">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-8 h-8 bg-yellow-500 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
              </div>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-purple-200 truncate">Actividades</dt>
                <dd class="text-lg font-medium text-white">{{ estadisticas.actividades_completadas || 0 }}</dd>
              </dl>
            </div>
          </div>
        </div>
      </div>

      <!-- Sección principal -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Lista de clases -->
        <div class="lg:col-span-2">
          <div class="bg-white/10 backdrop-blur rounded-xl border border-white/20 overflow-hidden">
            <div class="px-6 py-4 border-b border-white/10 flex justify-between items-center">
              <h3 class="text-lg font-medium text-white">Mis Clases</h3>
              <button 
                @click="mostrarFormularioUnirse = true"
                class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center space-x-2"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                <span>Unirse a Clase</span>
              </button>
            </div>

            <div class="divide-y divide-white/10">
              <div v-if="clases.length === 0" class="p-8 text-center">
                <svg class="mx-auto h-12 w-12 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-white">No estás inscrito en ninguna clase</h3>
                <p class="mt-1 text-sm text-purple-200">Únete a una clase usando el código proporcionado por tu profesor.</p>
              </div>

              <div v-for="clase in clases" :key="clase.id" class="p-6 hover:bg-white/5 transition-colors">
                <div class="flex items-center justify-between">
                  <div class="flex-1">
                    <div class="flex items-center space-x-3">
                      <h4 class="text-lg font-medium text-white">{{ clase.nombre }}</h4>
                      <span 
                        :class="clase.activa ? 'bg-green-500/20 text-green-300' : 'bg-gray-500/20 text-gray-300'"
                        class="px-2 py-1 rounded-full text-xs font-medium"
                      >
                        {{ clase.activa ? 'Activa' : 'Finalizada' }}
                      </span>
                    </div>
                    <p class="text-purple-200 text-sm mt-1">{{ clase.descripcion }}</p>
                    <div class="flex items-center space-x-4 mt-2 text-xs text-purple-300">
                      <span>Profesor: {{ clase.docente }}</span>
                      <span>Progreso: {{ clase.progreso }}%</span>
                      <span>{{ clase.fecha_inicio }} - {{ clase.fecha_fin }}</span>
                    </div>
                  </div>
                  
                  <div class="flex items-center space-x-2 ml-4">
                    <a 
                      :href="`/estudiantes/clases/${clase.id}`"
                      class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-2 rounded-lg font-medium transition-colors flex items-center space-x-1"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                      </svg>
                      <span>Entrar</span>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Panel lateral -->
        <div class="space-y-6">
          <!-- Formulario unirse a clase -->
          <div v-if="mostrarFormularioUnirse" class="bg-white/10 backdrop-blur rounded-xl border border-white/20 p-6">
            <div class="flex justify-between items-center mb-4">
              <h3 class="text-lg font-medium text-white">Unirse a Clase</h3>
              <button @click="mostrarFormularioUnirse = false" class="text-purple-300 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
              </button>
            </div>

            <form @submit.prevent="unirseAClase" class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-purple-200 mb-2">Código de la clase</label>
                <input 
                  v-model="codigoClase"
                  type="text" 
                  required
                  maxlength="6"
                  class="w-full px-3 py-2 bg-white/10 border border-white/20 rounded-lg text-white placeholder-purple-300 focus:outline-none focus:ring-2 focus:ring-purple-500 uppercase tracking-widest"
                  placeholder="ABC123"
                  @input="codigoClase = codigoClase.toUpperCase()"
                />
                <p class="text-xs text-purple-300 mt-1">Ingresa el código de 6 caracteres proporcionado por tu profesor</p>
              </div>

              <div class="flex space-x-3">
                <button 
                  type="submit"
                  :disabled="cargandoUnirse || codigoClase.length !== 6"
                  class="flex-1 bg-purple-600 hover:bg-purple-700 disabled:opacity-50 text-white py-2 px-4 rounded-lg font-medium transition-colors"
                >
                  {{ cargandoUnirse ? 'Uniéndose...' : 'Unirse' }}
                </button>
                <button 
                  type="button"
                  @click="mostrarFormularioUnirse = false"
                  class="bg-gray-600 hover:bg-gray-700 text-white py-2 px-4 rounded-lg font-medium transition-colors"
                >
                  Cancelar
                </button>
              </div>
            </form>
          </div>

          <!-- Acciones rápidas -->
          <div class="bg-white/10 backdrop-blur rounded-xl border border-white/20 p-6">
            <h3 class="text-lg font-medium text-white mb-4">Acciones Rápidas</h3>
            <div class="space-y-3">
              <a href="/perfil" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg font-medium transition-colors flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span>Ver Perfil</span>
              </a>
              
              <a href="/estudiantes/progreso" class="w-full bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg font-medium transition-colors flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <span>Mi Progreso</span>
              </a>
            </div>
          </div>

          <!-- Información del estudiante -->
          <div class="bg-white/10 backdrop-blur rounded-xl border border-white/20 p-6">
            <h3 class="text-lg font-medium text-white mb-4">Mi Información</h3>
            <div class="space-y-2 text-sm">
              <div class="flex justify-between">
                <span class="text-purple-200">Correo:</span>
                <span class="text-white">{{ estudiante.correo }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-purple-200">Código:</span>
                <span class="text-white">{{ estudiante.codigo }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal de notificaciones -->
    <div v-if="mostrarNotificacion" class="fixed top-4 right-4 z-50">
      <div 
        :class="{
          'bg-green-500': tipoNotificacion === 'success',
          'bg-red-500': tipoNotificacion === 'error',
          'bg-blue-500': tipoNotificacion === 'info'
        }"
        class="text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-2"
      >
        <svg v-if="tipoNotificacion === 'success'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <svg v-else-if="tipoNotificacion === 'error'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
        <span>{{ mensajeNotificacion }}</span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'

// Props
const props = defineProps({
  clases: {
    type: Array,
    default: () => []
  },
  estadisticas: {
    type: Object,
    default: () => ({
      total_clases: 0,
      clases_activas: 0,
      xp_total: 0,
      actividades_completadas: 0
    })
  },
  estudiante: {
    type: Object,
    default: () => ({
      nombre: '',
      correo: '',
      codigo: 'N/A'
    })
  }
})

// Estado reactivo
const mostrarFormularioUnirse = ref(false)
const cargandoUnirse = ref(false)
const codigoClase = ref('')
const mostrarNotificacion = ref(false)
const tipoNotificacion = ref('info')
const mensajeNotificacion = ref('')

// Métodos
const mostrarNotif = (tipo, mensaje) => {
  tipoNotificacion.value = tipo
  mensajeNotificacion.value = mensaje
  mostrarNotificacion.value = true
  setTimeout(() => {
    mostrarNotificacion.value = false
  }, 3000)
}

const logout = async () => {
  try {
    const response = await fetch('/logout', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'Content-Type': 'application/json'
      }
    })
    
    if (response.ok) {
      window.location.href = '/login'
    } else {
      throw new Error('Error en logout')
    }
  } catch (error) {
    console.error('Error al cerrar sesión:', error)
    mostrarNotif('error', 'Error al cerrar sesión')
  }
}

const unirseAClase = async () => {
  if (codigoClase.value.length !== 6) {
    mostrarNotif('error', 'El código debe tener 6 caracteres')
    return
  }

  cargandoUnirse.value = true

  try {
    const response = await fetch('/clases/unirse', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        codigo_clase: codigoClase.value
      })
    })

    const data = await response.json()

    if (response.ok && data.success) {
      mostrarNotif('success', 'Te has unido exitosamente a la clase')
      codigoClase.value = ''
      mostrarFormularioUnirse.value = false
      
      // Recargar página después de un momento
      setTimeout(() => {
        window.location.reload()
      }, 1500)
    } else {
      throw new Error(data.message || 'Código de clase inválido')
    }
  } catch (error) {
    console.error('Error uniéndose a clase:', error)
    mostrarNotif('error', error.message || 'Error al unirse a la clase')
  } finally {
    cargandoUnirse.value = false
  }
}
</script>