<template>
  <Head :title="`Clase: ${clase.nombre}`" />
  
  <div class="min-h-screen bg-gradient-to-br from-gray-900 via-blue-900 to-purple-900">
    <!-- Header -->
    <div class="bg-gray-800/50 backdrop-blur-sm border-b border-gray-700">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex items-center justify-between">
          <div>
            <nav class="flex items-center space-x-2 text-sm text-gray-400 mb-2">
              <a href="/profesor" class="hover:text-white transition-colors">Dashboard</a>
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
              </svg>
              <span class="text-white">{{ clase.nombre }}</span>
            </nav>
            <h1 class="text-3xl font-bold text-white">{{ clase.nombre }}</h1>
            <p class="text-gray-300 mt-1">Código de clase: {{ clase.codigo }}</p>
          </div>
          <div class="flex items-center space-x-4">
            <button 
              @click="mostrarModalAgregarEstudiante = true"
              class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center space-x-2"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
              </svg>
              <span>Agregar Estudiante</span>
            </button>
            <button 
              v-if="clase.total_estudiantes > 0"
              @click="seleccionarEstudianteAleatorio"
              class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center space-x-2"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m-9 0h10m-10 0a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V6a2 2 0 00-2-2"/>
              </svg>
              <span>Seleccionar Aleatorio</span>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Información de la clase -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Panel izquierdo - Información de la clase -->
        <div class="lg:col-span-1">
          <div class="bg-gray-800/50 backdrop-blur-sm rounded-xl border border-gray-700 p-6">
            <h2 class="text-xl font-bold text-white mb-4">Información de la Clase</h2>
            
            <div class="space-y-4">
              <div>
                <label class="text-sm font-medium text-gray-400">Descripción</label>
                <p class="text-white mt-1">{{ clase.descripcion || 'Sin descripción' }}</p>
              </div>
              
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="text-sm font-medium text-gray-400">Fecha Inicio</label>
                  <p class="text-white mt-1">{{ formatearFecha(clase.fecha_inicio) }}</p>
                </div>
                <div>
                  <label class="text-sm font-medium text-gray-400">Fecha Fin</label>
                  <p class="text-white mt-1">{{ formatearFecha(clase.fecha_fin) }}</p>
                </div>
              </div>
              
              <div>
                <label class="text-sm font-medium text-gray-400">Estado</label>
                <div class="mt-1 flex items-center">
                  <span 
                    :class="clase.activa ? 'bg-green-500' : 'bg-red-500'"
                    class="inline-block w-3 h-3 rounded-full mr-2"
                  ></span>
                  <span 
                    :class="clase.activa ? 'text-green-400' : 'text-red-400'"
                    class="font-medium"
                  >
                    {{ clase.activa ? 'Activa' : 'Inactiva' }}
                  </span>
                </div>
              </div>
              
              <div>
                <label class="text-sm font-medium text-gray-400">Progreso de Clase</label>
                <div class="mt-2">
                  <div class="flex items-center justify-between text-sm mb-1">
                    <span class="text-gray-300">{{ clase.progreso.completadas }}/{{ clase.progreso.total_actividades }} actividades</span>
                    <span class="text-white font-medium">{{ clase.progreso.porcentaje }}%</span>
                  </div>
                  <div class="w-full bg-gray-700 rounded-full h-2">
                    <div 
                      class="bg-blue-500 h-2 rounded-full transition-all duration-300"
                      :style="{ width: clase.progreso.porcentaje + '%' }"
                    ></div>
                  </div>
                </div>
              </div>
              
              <div class="pt-4 border-t border-gray-700">
                <div class="grid grid-cols-2 gap-4 text-center">
                  <div>
                    <p class="text-2xl font-bold text-blue-400">{{ clase.total_estudiantes }}</p>
                    <p class="text-sm text-gray-400">Estudiantes</p>
                  </div>
                  <div>
                    <p class="text-2xl font-bold text-purple-400">{{ clase.progreso.dias_restantes }}</p>
                    <p class="text-sm text-gray-400">Días restantes</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Panel derecho - Lista de estudiantes -->
        <div class="lg:col-span-2">
          <div class="bg-gray-800/50 backdrop-blur-sm rounded-xl border border-gray-700">
            <div class="px-6 py-4 border-b border-gray-700">
              <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-white">Estudiantes Inscritos</h2>
                <span class="bg-blue-500/20 text-blue-400 px-3 py-1 rounded-full text-sm font-medium">
                  {{ estudiantes.length }} estudiantes
                </span>
              </div>
            </div>

            <div class="p-6">
              <!-- Estado sin estudiantes -->
              <div v-if="estudiantes.length === 0" class="text-center py-12">
                <svg class="w-16 h-16 text-gray-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-300 mb-2">No hay estudiantes inscritos</h3>
                <p class="text-gray-500 mb-6">Agrega estudiantes para comenzar a gestionar tu clase</p>
                <button 
                  @click="mostrarModalAgregarEstudiante = true"
                  class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200 inline-flex items-center space-x-2"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                  </svg>
                  <span>Agregar Primer Estudiante</span>
                </button>
              </div>

              <!-- Lista de estudiantes -->
              <div v-else class="space-y-4">
                <div 
                  v-for="estudiante in estudiantes" 
                  :key="estudiante.id"
                  class="bg-gray-700/50 rounded-lg p-4 border border-gray-600 hover:border-blue-500 transition-all duration-200"
                >
                  <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                      <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                        <span class="text-white font-bold text-lg">
                          {{ estudiante.nombre.charAt(0).toUpperCase() }}
                        </span>
                      </div>
                      <div>
                        <h3 class="text-white font-medium">{{ estudiante.nombre }}</h3>
                        <p class="text-gray-400 text-sm">{{ estudiante.correo }}</p>
                        <p class="text-gray-500 text-xs">
                          Inscrito: {{ formatearFecha(estudiante.fecha_inscripcion) }}
                        </p>
                      </div>
                    </div>
                    
                    <div class="flex items-center space-x-2">
                      <button 
                        @click="seleccionarEstudianteEspecifico(estudiante)"
                        class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-2 rounded text-sm font-medium transition-colors duration-200"
                        title="Seleccionar este estudiante"
                      >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                      </button>
                      <button 
                        @click="removerEstudiante(estudiante)"
                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded text-sm font-medium transition-colors duration-200"
                        title="Remover estudiante"
                      >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Agregar Estudiante -->
    <div v-if="mostrarModalAgregarEstudiante" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
      <div class="bg-gray-800 rounded-xl p-6 w-full max-w-md border border-gray-700">
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-xl font-bold text-white">Agregar Estudiante</h3>
          <button 
            @click="cerrarModalAgregarEstudiante"
            class="text-gray-400 hover:text-white transition-colors duration-200"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
        </div>

        <form @submit.prevent="agregarEstudiante">
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-300 mb-2">
                Correo electrónico o código del estudiante *
              </label>
              <input 
                v-model="formularioEstudiante.codigo_estudiante"
                type="text" 
                required
                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="ejemplo@correo.com o EST001"
              >
              <p class="text-gray-400 text-xs mt-1">
                Introduce el correo electrónico o código único del estudiante
              </p>
            </div>
          </div>

          <div v-if="errorAgregar" class="mt-4 p-3 bg-red-500/20 border border-red-500 rounded-lg">
            <p class="text-red-400 text-sm">{{ errorAgregar }}</p>
          </div>

          <div class="flex items-center justify-end space-x-3 mt-6">
            <button 
              type="button"
              @click="cerrarModalAgregarEstudiante"
              class="px-4 py-2 text-gray-400 hover:text-white transition-colors duration-200"
            >
              Cancelar
            </button>
            <button 
              type="submit"
              :disabled="agregandoEstudiante"
              class="bg-green-600 hover:bg-green-700 disabled:bg-green-800 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center space-x-2"
            >
              <svg v-if="agregandoEstudiante" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <span>{{ agregandoEstudiante ? 'Agregando...' : 'Agregar Estudiante' }}</span>
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
import { ref, computed } from 'vue'
import { Head } from '@inertiajs/vue3'

// Props
const props = defineProps({
  clase: {
    type: Object,
    required: true
  },
  estudiantes: {
    type: Array,
    default: () => []
  }
})

// Estado reactivo
const estudiantes = ref(props.estudiantes)
const clase = ref(props.clase)
const mostrarModalAgregarEstudiante = ref(false)
const mostrarModalEstudiante = ref(false)
const agregandoEstudiante = ref(false)
const estudianteSeleccionado = ref(null)
const errorAgregar = ref('')

// Formulario para agregar estudiante
const formularioEstudiante = ref({
  codigo_estudiante: ''
})

// Métodos
const formatearFecha = (fecha) => {
  return new Date(fecha).toLocaleDateString('es-ES', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric'
  })
}

const agregarEstudiante = async () => {
  agregandoEstudiante.value = true
  errorAgregar.value = ''
  
  try {
    const response = await fetch(`/profesor/clases/${clase.value.id}/estudiantes`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify(formularioEstudiante.value)
    })

    const data = await response.json()

    if (data.success) {
      // Agregar el estudiante a la lista
      estudiantes.value.push(data.estudiante)
      
      // Actualizar el contador en la clase
      clase.value.total_estudiantes++
      
      // Cerrar modal y resetear formulario
      cerrarModalAgregarEstudiante()
      
      // Mostrar mensaje de éxito
      alert('¡Estudiante agregado exitosamente!')
    } else {
      errorAgregar.value = data.message || 'Error al agregar estudiante'
    }
  } catch (error) {
    console.error('Error:', error)
    errorAgregar.value = 'Error al agregar estudiante'
  } finally {
    agregandoEstudiante.value = false
  }
}

const seleccionarEstudianteAleatorio = async () => {
  try {
    const response = await fetch(`/profesor/clases/${clase.value.id}/seleccionar-aleatorio`, {
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
      alert(data.message || 'No hay estudiantes disponibles')
    }
  } catch (error) {
    console.error('Error:', error)
    alert('Error al seleccionar estudiante')
  }
}

const seleccionarEstudianteEspecifico = (estudiante) => {
  estudianteSeleccionado.value = {
    mensaje: `¡${estudiante.nombre}, es tu turno de participar!`
  }
  mostrarModalEstudiante.value = true
}

const removerEstudiante = async (estudiante) => {
  if (!confirm(`¿Estás seguro de que quieres remover a ${estudiante.nombre} de la clase?`)) {
    return
  }

  try {
    const response = await fetch(`/profesor/clases/${clase.value.id}/estudiantes/${estudiante.id}`, {
      method: 'DELETE',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    })

    const data = await response.json()

    if (data.success) {
      // Remover el estudiante de la lista
      const index = estudiantes.value.findIndex(e => e.id === estudiante.id)
      if (index > -1) {
        estudiantes.value.splice(index, 1)
      }
      
      // Actualizar el contador en la clase
      clase.value.total_estudiantes--
      
      alert('Estudiante removido exitosamente')
    } else {
      alert(data.message || 'Error al remover estudiante')
    }
  } catch (error) {
    console.error('Error:', error)
    alert('Error al remover estudiante')
  }
}

const cerrarModalAgregarEstudiante = () => {
  mostrarModalAgregarEstudiante.value = false
  formularioEstudiante.value.codigo_estudiante = ''
  errorAgregar.value = ''
}

const cerrarModalEstudiante = () => {
  mostrarModalEstudiante.value = false
  estudianteSeleccionado.value = null
}
</script>