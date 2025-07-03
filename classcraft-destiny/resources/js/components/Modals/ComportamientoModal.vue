<template>
  <Teleport to="body">
    <div
      v-if="show"
      class="fixed inset-0 z-50 overflow-y-auto"
      aria-labelledby="modal-title"
      role="dialog"
      aria-modal="true"
    >
      <!-- Background overlay -->
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div
          class="fixed inset-0 bg-black bg-opacity-75 transition-opacity"
          @click="$emit('close')"
        ></div>

        <!-- Center modal -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div
          class="destiny-modal inline-block align-bottom bg-gradient-to-br from-gray-900 to-black rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
          @click.stop
        >
          <!-- Header -->
          <div class="destiny-modal-header px-6 py-4 border-b border-destiny-gold/30">
            <div class="flex items-center justify-between">
              <h3 class="text-lg font-bold text-destiny-gold flex items-center">
                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
                Aplicar Comportamiento
              </h3>
              <button
                @click="$emit('close')"
                class="text-gray-400 hover:text-white transition-colors p-1 rounded"
              >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
              </button>
            </div>
          </div>

          <!-- Content -->
          <div class="destiny-modal-content p-6">
            <!-- Información del contexto -->
            <div v-if="clase" class="mb-6 p-4 bg-gray-800/50 rounded-lg border border-gray-700/50">
              <div class="flex items-center space-x-3 mb-3">
                <div class="w-10 h-10 bg-gradient-to-br from-destiny-gold to-destiny-cyan rounded-lg flex items-center justify-center">
                  <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                  </svg>
                </div>
                <div>
                  <h4 class="font-semibold text-white">{{ clase.nombre }}</h4>
                  <p class="text-sm text-gray-400">{{ clase.estudiantes_count }} Guardianes activos</p>
                </div>
              </div>
              
              <div v-if="estudiante" class="flex items-center space-x-3 p-3 bg-black/30 rounded-lg">
                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-sm font-bold text-white">
                  {{ estudiante.nombre[0] }}{{ estudiante.apellido?.[0] || '' }}
                </div>
                <div>
                  <p class="font-medium text-white">{{ estudiante.nombre }} {{ estudiante.apellido }}</p>
                  <p class="text-xs text-gray-400">Nivel {{ estudiante.nivel }} - {{ estudiante.clase_rpg }}</p>
                </div>
              </div>
            </div>

            <!-- Selector de estudiante si no hay uno específico -->
            <div v-if="!estudiante && estudiantesDisponibles.length > 0" class="mb-6">
              <label class="block text-sm font-medium text-gray-300 mb-2">
                Seleccionar Guardián
              </label>
              <select
                v-model="estudianteSeleccionado"
                class="w-full bg-gray-800 border border-gray-600 rounded-lg px-3 py-2 text-white focus:ring-2 focus:ring-destiny-gold focus:border-transparent"
              >
                <option value="">Selecciona un guardián...</option>
                <option
                  v-for="est in estudiantesDisponibles"
                  :key="est.id"
                  :value="est.id"
                >
                  {{ est.nombre }} {{ est.apellido }} - Nivel {{ est.nivel }}
                </option>
              </select>
            </div>

            <!-- Selector de comportamiento -->
            <div class="mb-6">
              <label class="block text-sm font-medium text-gray-300 mb-2">
                Tipo de Comportamiento
              </label>
              <div class="grid grid-cols-1 gap-3">
                <button
                  v-for="comportamiento in comportamientosDisponibles"
                  :key="comportamiento.id"
                  @click="seleccionarComportamiento(comportamiento)"
                  class="comportamiento-card text-left p-4 rounded-lg border-2 transition-all duration-300"
                  :class="{
                    'border-destiny-gold bg-destiny-gold/10': comportamientoSeleccionado?.id === comportamiento.id,
                    'border-gray-600 bg-gray-800/50 hover:border-gray-500': comportamientoSeleccionado?.id !== comportamiento.id
                  }"
                >
                  <div class="flex items-start justify-between">
                    <div class="flex items-center space-x-3">
                      <div 
                        class="text-2xl"
                        :style="{ color: comportamiento.color }"
                      >
                        {{ comportamiento.icono }}
                      </div>
                      <div>
                        <h4 class="font-semibold text-white">{{ comportamiento.nombre }}</h4>
                        <p class="text-sm text-gray-400">{{ comportamiento.descripcion }}</p>
                      </div>
                    </div>
                    <div class="text-right">
                      <div 
                        class="text-lg font-bold"
                        :class="{
                          'text-green-400': comportamiento.puntos_experiencia > 0,
                          'text-red-400': comportamiento.puntos_experiencia < 0,
                          'text-gray-400': comportamiento.puntos_experiencia === 0
                        }"
                      >
                        {{ comportamiento.puntos_experiencia > 0 ? '+' : '' }}{{ comportamiento.puntos_experiencia }} XP
                      </div>
                      <div 
                        v-if="comportamiento.puntos_salud !== 0"
                        class="text-sm"
                        :class="{
                          'text-green-400': comportamiento.puntos_salud > 0,
                          'text-red-400': comportamiento.puntos_salud < 0
                        }"
                      >
                        {{ comportamiento.puntos_salud > 0 ? '+' : '' }}{{ comportamiento.puntos_salud }} HP
                      </div>
                    </div>
                  </div>
                </button>
              </div>
            </div>

            <!-- Justificación (si es requerida) -->
            <div v-if="comportamientoSeleccionado?.requiere_justificacion" class="mb-6">
              <label class="block text-sm font-medium text-gray-300 mb-2">
                Justificación <span class="text-red-400">*</span>
              </label>
              <textarea
                v-model="justificacion"
                rows="3"
                class="w-full bg-gray-800 border border-gray-600 rounded-lg px-3 py-2 text-white focus:ring-2 focus:ring-destiny-gold focus:border-transparent resize-none"
                placeholder="Describe brevemente la razón de este comportamiento..."
              ></textarea>
            </div>

            <!-- Preview del efecto -->
            <div v-if="comportamientoSeleccionado && targetEstudiante" class="mb-6 p-4 bg-blue-900/20 border border-blue-500/30 rounded-lg">
              <h4 class="text-sm font-semibold text-blue-300 mb-2">Vista Previa del Efecto</h4>
              <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                  <span class="text-gray-400">Guardián:</span>
                  <span class="text-white">{{ targetEstudiante.nombre }} {{ targetEstudiante.apellido }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-400">Experiencia actual:</span>
                  <span class="text-white">{{ targetEstudiante.experiencia_actual || 0 }} XP</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-400">Nueva experiencia:</span>
                  <span 
                    class="font-semibold"
                    :class="{
                      'text-green-400': comportamientoSeleccionado.puntos_experiencia > 0,
                      'text-red-400': comportamientoSeleccionado.puntos_experiencia < 0,
                      'text-white': comportamientoSeleccionado.puntos_experiencia === 0
                    }"
                  >
                    {{ (targetEstudiante.experiencia_actual || 0) + comportamientoSeleccionado.puntos_experiencia }} XP
                  </span>
                </div>
                <div v-if="comportamientoSeleccionado.puntos_salud !== 0" class="flex justify-between">
                  <span class="text-gray-400">Salud:</span>
                  <span 
                    class="font-semibold"
                    :class="{
                      'text-green-400': comportamientoSeleccionado.puntos_salud > 0,
                      'text-red-400': comportamientoSeleccionado.puntos_salud < 0
                    }"
                  >
                    {{ Math.max(0, Math.min(100, (targetEstudiante.salud_actual || 100) + comportamientoSeleccionado.puntos_salud)) }}%
                  </span>
                </div>
              </div>
            </div>
          </div>

          <!-- Footer -->
          <div class="destiny-modal-footer px-6 py-4 border-t border-gray-700/50 flex justify-end space-x-3">
            <button
              @click="$emit('close')"
              class="px-4 py-2 text-gray-400 hover:text-white transition-colors"
            >
              Cancelar
            </button>
            <button
              @click="aplicarComportamiento"
              :disabled="!puedeAplicar || aplicando"
              class="destiny-button-primary disabled:opacity-50 disabled:cursor-not-allowed flex items-center"
            >
              <svg v-if="aplicando" class="animate-spin -ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              {{ aplicando ? 'Aplicando...' : 'Aplicar Comportamiento' }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </Teleport>
</template>

<script setup lang="ts">
import { ref, computed, watch, onMounted } from 'vue'

interface Estudiante {
  id: number
  nombre: string
  apellido: string
  nivel: number
  clase_rpg: string
  experiencia_actual: number
  salud_actual: number
}

interface Comportamiento {
  id: number
  nombre: string
  descripcion: string
  puntos_experiencia: number
  puntos_salud: number
  tipo: 'positivo' | 'negativo' | 'neutral'
  icono: string
  color: string
  requiere_justificacion: boolean
}

interface Clase {
  id: number
  nombre: string
  estudiantes_count: number
}

interface Props {
  show: boolean
  clase?: Clase | null
  estudiante?: Estudiante | null
}

const props = defineProps<Props>()

const emit = defineEmits<{
  close: []
  aplicado: [data: any]
}>()

// Estado reactivo
const estudianteSeleccionado = ref<number | string>('')
const comportamientoSeleccionado = ref<Comportamiento | null>(null)
const justificacion = ref('')
const aplicando = ref(false)
const estudiantesDisponibles = ref<Estudiante[]>([])
const comportamientosDisponibles = ref<Comportamiento[]>([])

// Computed properties
const targetEstudiante = computed(() => {
  if (props.estudiante) return props.estudiante
  if (estudianteSeleccionado.value) {
    return estudiantesDisponibles.value.find(e => e.id === Number(estudianteSeleccionado.value))
  }
  return null
})

const puedeAplicar = computed(() => {
  const tieneEstudiante = props.estudiante || estudianteSeleccionado.value
  const tieneComportamiento = comportamientoSeleccionado.value
  const tieneJustificacion = !comportamientoSeleccionado.value?.requiere_justificacion || justificacion.value.trim()
  
  return tieneEstudiante && tieneComportamiento && tieneJustificacion && !aplicando.value
})

// Función helper para obtener el token CSRF
const getCsrfToken = (): string => {
  const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
  return token || ''
}

// Methods
const seleccionarComportamiento = (comportamiento: Comportamiento) => {
  comportamientoSeleccionado.value = comportamiento
  if (!comportamiento.requiere_justificacion) {
    justificacion.value = ''
  }
}

const aplicarComportamiento = async () => {
  if (!puedeAplicar.value || !props.clase || !comportamientoSeleccionado.value) return

  aplicando.value = true

  try {
    const estudianteId = props.estudiante?.id || Number(estudianteSeleccionado.value)
    
    const response = await fetch(`/api/profesor/clases/${props.clase.id}/comportamientos`, {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': getCsrfToken()
      },
      body: JSON.stringify({
        estudiante_id: estudianteId,
        comportamiento_id: comportamientoSeleccionado.value.id,
        justificacion: justificacion.value.trim() || null
      })
    })

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`)
    }

    const data = await response.json()

    emit('aplicado', {
      estudiante: targetEstudiante.value,
      comportamiento: comportamientoSeleccionado.value,
      puntos: comportamientoSeleccionado.value.puntos_experiencia,
      response: data
    })

    // Limpiar formulario
    resetFormulario()
    
  } catch (error: any) {
    console.error('Error al aplicar comportamiento:', error)
    alert(error.message || 'Error al aplicar comportamiento')
  } finally {
    aplicando.value = false
  }
}

const resetFormulario = () => {
  estudianteSeleccionado.value = ''
  comportamientoSeleccionado.value = null
  justificacion.value = ''
}

const cargarComportamientos = async () => {
  try {
    const response = await fetch('/api/comportamientos', {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': getCsrfToken()
      }
    })

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`)
    }

    const data = await response.json()
    
    if (data.success) {
      comportamientosDisponibles.value = data.data
    }
  } catch (error) {
    console.error('Error al cargar comportamientos:', error)
  }
}

const cargarEstudiantes = async () => {
  if (!props.clase) return

  try {
    const response = await fetch(`/api/profesor/clases/${props.clase.id}/estudiantes`, {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': getCsrfToken()
      }
    })

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`)
    }

    const data = await response.json()
    
    if (data.success) {
      estudiantesDisponibles.value = data.data
    }
  } catch (error) {
    console.error('Error al cargar estudiantes:', error)
  }
}

// Watchers
watch(() => props.show, (show) => {
  if (show) {
    resetFormulario()
    cargarComportamientos()
    if (props.clase) {
      cargarEstudiantes()
    }
  }
})

watch(() => props.clase, (clase) => {
  if (clase && props.show) {
    cargarEstudiantes()
  }
})

// Lifecycle
onMounted(() => {
  cargarComportamientos()
})
</script>

<style scoped>
.destiny-modal {
  border: 1px solid rgba(199, 184, 138, 0.3);
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.8);
}

.destiny-modal-header {
  background: linear-gradient(135deg, rgba(199, 184, 138, 0.1), rgba(110, 193, 228, 0.1));
}

.destiny-modal-content {
  background: linear-gradient(135deg, rgba(31, 41, 55, 0.3), rgba(17, 24, 39, 0.5));
}

.destiny-modal-footer {
  background: linear-gradient(135deg, rgba(17, 24, 39, 0.5), rgba(0, 0, 0, 0.7));
}

.comportamiento-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
}

.destiny-button-primary {
  @apply bg-gradient-to-r from-destiny-gold to-yellow-500 text-black font-semibold px-4 py-2 rounded-lg;
  @apply hover:from-yellow-400 hover:to-destiny-gold transition-all duration-300;
  @apply shadow-lg hover:shadow-xl;
}

/* Scrollbar personalizado */
::-webkit-scrollbar {
  width: 8px;
}

::-webkit-scrollbar-track {
  background: rgba(27, 28, 41, 0.5);
  border-radius: 4px;
}

::-webkit-scrollbar-thumb {
  background: linear-gradient(45deg, #C7B88A, #6EC1E4);
  border-radius: 4px;
}
</style>