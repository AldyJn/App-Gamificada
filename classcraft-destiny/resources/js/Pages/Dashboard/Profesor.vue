<template>
  <div class="min-h-screen bg-gradient-to-br from-indigo-900 via-purple-900 to-pink-900">
    <Head title="Dashboard - Profesor" />

    <!-- Header con logout -->
    <div class="bg-black/20 backdrop-blur border-b border-white/10">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex justify-between items-center">
          <div class="flex items-center space-x-4">
            <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
              </svg>
            </div>
            <div>
              <h1 class="text-xl font-bold text-white">Dashboard Profesor</h1>
              <p class="text-indigo-200 text-sm">Bienvenido, {{ profesor.nombre }}</p>
            </div>
          </div>
          
          <!-- Botones de navegación y logout -->
          <div class="flex items-center space-x-4">
            <a href="/perfil" class="text-indigo-200 hover:text-white transition-colors">
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
      <!-- Estadísticas del profesor -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white/10 backdrop-blur rounded-xl p-6 border border-white/20">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
              </div>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-indigo-200 truncate">Total Clases</dt>
                <dd class="text-lg font-medium text-white">{{ estadisticas.total_clases }}</dd>
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
                <dt class="text-sm font-medium text-indigo-200 truncate">Clases Activas</dt>
                <dd class="text-lg font-medium text-white">{{ estadisticas.clases_activas }}</dd>
              </dl>
            </div>
          </div>
        </div>

        <div class="bg-white/10 backdrop-blur rounded-xl p-6 border border-white/20">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                </svg>
              </div>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-indigo-200 truncate">Total Estudiantes</dt>
                <dd class="text-lg font-medium text-white">{{ estadisticas.total_estudiantes }}</dd>
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
                <dt class="text-sm font-medium text-indigo-200 truncate">Promedio por Clase</dt>
                <dd class="text-lg font-medium text-white">{{ estadisticas.promedio_estudiantes }}</dd>
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
                @click="mostrarFormularioClase = true"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center space-x-2"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                <span>Nueva Clase</span>
              </button>
            </div>

            <div class="divide-y divide-white/10">
              <div v-if="clases.length === 0" class="p-8 text-center">
                <svg class="mx-auto h-12 w-12 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-white">No tienes clases</h3>
                <p class="mt-1 text-sm text-indigo-200">Crea tu primera clase para comenzar.</p>
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
                        {{ clase.activa ? 'Activa' : 'Inactiva' }}
                      </span>
                    </div>
                    <p class="text-indigo-200 text-sm mt-1">{{ clase.descripcion }}</p>
                    <div class="flex items-center space-x-4 mt-2 text-xs text-indigo-300">
                      <span>Código: {{ clase.codigo }}</span>
                      <span>{{ clase.total_estudiantes }} estudiantes</span>
                      <span>{{ clase.fecha_inicio }} - {{ clase.fecha_fin }}</span>
                    </div>
                  </div>
                  
                  <div class="flex items-center space-x-2 ml-4">
                    <a 
                      :href="`/profesor/clases/${clase.id}`"
                      class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg font-medium transition-colors flex items-center space-x-1"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                      </svg>
                      <span>Ver</span>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Panel lateral -->
        <div class="space-y-6">
          <!-- Formulario nueva clase -->
          <div v-if="mostrarFormularioClase" class="bg-white/10 backdrop-blur rounded-xl border border-white/20 p-6">
            <div class="flex justify-between items-center mb-4">
              <h3 class="text-lg font-medium text-white">Nueva Clase</h3>
              <button @click="mostrarFormularioClase = false" class="text-indigo-300 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
              </button>
            </div>

            <form @submit.prevent="crearClase" class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-indigo-200 mb-2">Nombre de la clase</label>
                <input 
                  v-model="nuevaClase.nombre"
                  type="text" 
                  required
                  class="w-full px-3 py-2 bg-white/10 border border-white/20 rounded-lg text-white placeholder-indigo-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                  placeholder="Ej: Matemáticas 5to Grado"
                />
              </div>

              <div>
                <label class="block text-sm font-medium text-indigo-200 mb-2">Descripción</label>
                <textarea 
                  v-model="nuevaClase.descripcion"
                  rows="3"
                  class="w-full px-3 py-2 bg-white/10 border border-white/20 rounded-lg text-white placeholder-indigo-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                  placeholder="Descripción de la clase..."
                ></textarea>
              </div>

              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-indigo-200 mb-2">Fecha Inicio</label>
                  <input 
                    v-model="nuevaClase.fecha_inicio"
                    type="date" 
                    required
                    class="w-full px-3 py-2 bg-white/10 border border-white/20 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium text-indigo-200 mb-2">Fecha Fin</label>
                  <input 
                    v-model="nuevaClase.fecha_fin"
                    type="date" 
                    required
                    class="w-full px-3 py-2 bg-white/10 border border-white/20 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                  />
                </div>
              </div>

              <div class="flex space-x-3">
                <button 
                  type="submit"
                  :disabled="cargandoCreacion"
                  class="flex-1 bg-blue-600 hover:bg-blue-700 disabled:opacity-50 text-white py-2 px-4 rounded-lg font-medium transition-colors"
                >
                  {{ cargandoCreacion ? 'Creando...' : 'Crear Clase' }}
                </button>
                <button 
                  type="button"
                  @click="mostrarFormularioClase = false"
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
              <a href="/perfil" class="w-full bg-purple-600 hover:bg-purple-700 text-white py-2 px-4 rounded-lg font-medium transition-colors flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span>Ver Perfil</span>
              </a>
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
import { ref, reactive } from 'vue'

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
      total_estudiantes: 0,
      promedio_estudiantes: 0
    })
  },
  profesor: {
    type: Object,
    default: () => ({
      nombre: '',
      correo: ''
    })
  }
})

// Estado reactivo
const mostrarFormularioClase = ref(false)
const cargandoCreacion = ref(false)
const mostrarNotificacion = ref(false)
const tipoNotificacion = ref('info')
const mensajeNotificacion = ref('')

const nuevaClase = reactive({
  nombre: '',
  descripcion: '',
  fecha_inicio: new Date().toISOString().split('T')[0],
  fecha_fin: new Date(Date.now() + 30 * 24 * 60 * 60 * 1000).toISOString().split('T')[0]
})

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

const crearClase = async () => {
  if (!nuevaClase.nombre.trim()) {
    mostrarNotif('error', 'El nombre de la clase es requerido')
    return
  }

  cargandoCreacion.value = true

  try {
    const response = await fetch('/profesor/clases', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(nuevaClase)
    })

    const data = await response.json()

    if (response.ok && data.success) {
      mostrarNotif('success', 'Clase creada exitosamente')
      
      // Reset form
      Object.assign(nuevaClase, {
        nombre: '',
        descripcion: '',
        fecha_inicio: new Date().toISOString().split('T')[0],
        fecha_fin: new Date(Date.now() + 30 * 24 * 60 * 60 * 1000).toISOString().split('T')[0]
      })
      
      mostrarFormularioClase.value = false
      
      // Recargar página después de un momento
      setTimeout(() => {
        window.location.reload()
      }, 1500)
    } else {
      throw new Error(data.message || 'Error al crear la clase')
    }
  } catch (error) {
    console.error('Error creando clase:', error)
    mostrarNotif('error', error.message || 'Error al crear la clase')
  } finally {
    cargandoCreacion.value = false
  }
}
</script>