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
          class="destiny-modal inline-block align-bottom bg-gradient-to-br from-gray-900 to-black rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full"
          @click.stop
        >
          <!-- Header -->
          <div class="destiny-modal-header px-6 py-4 border-b border-destiny-gold/30">
            <div class="flex items-center justify-between">
              <h3 class="text-xl font-bold text-destiny-gold flex items-center">
                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                </svg>
                Nueva Operación
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
          <form @submit.prevent="crearClase" class="destiny-modal-content">
            <div class="p-6 space-y-6">
              <!-- Información Básica -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                  <label class="block text-sm font-medium text-gray-300 mb-2">
                    Nombre de la Operación <span class="text-red-400">*</span>
                  </label>
                  <input
                    v-model="formulario.nombre"
                    type="text"
                    required
                    class="w-full bg-gray-800 border border-gray-600 rounded-lg px-3 py-2 text-white focus:ring-2 focus:ring-destiny-gold focus:border-transparent"
                    placeholder="Ej: Matemáticas 5° A - Álgebra Básica"
                  />
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-300 mb-2">
                    Grado <span class="text-red-400">*</span>
                  </label>
                  <select
                    v-model="formulario.grado"
                    required
                    class="w-full bg-gray-800 border border-gray-600 rounded-lg px-3 py-2 text-white focus:ring-2 focus:ring-destiny-gold focus:border-transparent"
                  >
                    <option value="">Selecciona un grado</option>
                    <option v-for="grado in gradosDisponibles" :key="grado" :value="grado">
                      {{ grado }}
                    </option>
                  </select>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-300 mb-2">
                    Sección <span class="text-red-400">*</span>
                  </label>
                  <select
                    v-model="formulario.seccion"
                    required
                    class="w-full bg-gray-800 border border-gray-600 rounded-lg px-3 py-2 text-white focus:ring-2 focus:ring-destiny-gold focus:border-transparent"
                  >
                    <option value="">Selecciona una sección</option>
                    <option v-for="seccion in seccionesDisponibles" :key="seccion" :value="seccion">
                      {{ seccion }}
                    </option>
                  </select>
                </div>
              </div>

              <!-- Descripción -->
              <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">
                  Descripción de la Misión
                </label>
                <textarea
                  v-model="formulario.descripcion"
                  rows="3"
                  class="w-full bg-gray-800 border border-gray-600 rounded-lg px-3 py-2 text-white focus:ring-2 focus:ring-destiny-gold focus:border-transparent resize-none"
                  placeholder="Describe brevemente los objetivos y contenido de esta operación..."
                ></textarea>
              </div>

              <!-- Fechas -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-medium text-gray-300 mb-2">
                    Fecha de Inicio <span class="text-red-400">*</span>
                  </label>
                  <input
                    v-model="formulario.fecha_inicio"
                    type="date"
                    required
                    :min="fechaMinima"
                    class="w-full bg-gray-800 border border-gray-600 rounded-lg px-3 py-2 text-white focus:ring-2 focus:ring-destiny-gold focus:border-transparent"
                  />
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-300 mb-2">
                    Fecha de Finalización <span class="text-red-400">*</span>
                  </label>
                  <input
                    v-model="formulario.fecha_fin"
                    type="date"
                    required
                    :min="formulario.fecha_inicio || fechaMinima"
                    class="w-full bg-gray-800 border border-gray-600 rounded-lg px-3 py-2 text-white focus:ring-2 focus:ring-destiny-gold focus:border-transparent"
                  />
                </div>
              </div>

              <!-- Tema Visual -->
              <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">
                  Tema Visual de la Operación
                </label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                  <button
                    v-for="tema in temasVisuales"
                    :key="tema.id"
                    type="button"
                    @click="formulario.tema_visual = tema.id"
                    class="tema-card p-3 rounded-lg border-2 transition-all duration-300 text-center"
                    :class="{
                      'border-destiny-gold bg-destiny-gold/10': formulario.tema_visual === tema.id,
                      'border-gray-600 bg-gray-800/50 hover:border-gray-500': formulario.tema_visual !== tema.id
                    }"
                  >
                    <div class="text-2xl mb-2">{{ tema.icono }}</div>
                    <div class="text-xs font-medium text-white">{{ tema.nombre }}</div>
                  </button>
                </div>
              </div>

              <!-- Configuración de Gamificación -->
              <div class="p-4 bg-blue-900/20 border border-blue-500/30 rounded-lg">
                <h4 class="text-sm font-semibold text-blue-300 mb-3 flex items-center">
                  <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                  </svg>
                  Configuración de Gamificación
                </h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1">
                      Multiplicador de XP
                    </label>
                    <select
                      v-model="formulario.configuracion_gamificacion.multiplicador_xp"
                      class="w-full bg-gray-800 border border-gray-600 rounded px-2 py-1 text-sm text-white focus:ring-1 focus:ring-destiny-gold"
                    >
                      <option value="1">Normal (x1)</option>
                      <option value="1.25">Aumentado (x1.25)</option>
                      <option value="1.5">Alto (x1.5)</option>
                      <option value="2">Doble (x2)</option>
                    </select>
                  </div>

                  <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1">
                      Dificultad Base
                    </label>
                    <select
                      v-model="formulario.configuracion_gamificacion.dificultad"
                      class="w-full bg-gray-800 border border-gray-600 rounded px-2 py-1 text-sm text-white focus:ring-1 focus:ring-destiny-gold"
                    >
                      <option value="facil">Fácil</option>
                      <option value="normal">Normal</option>
                      <option value="dificil">Difícil</option>
                      <option value="experto">Experto</option>
                    </select>
                  </div>

                  <div class="md:col-span-2">
                    <label class="flex items-center space-x-2">
                      <input
                        v-model="formulario.configuracion_gamificacion.modo_competitivo"
                        type="checkbox"
                        class="rounded bg-gray-800 border-gray-600 text-destiny-gold focus:ring-destiny-gold focus:ring-offset-0"
                      />
                      <span class="text-sm text-gray-300">Activar modo competitivo entre Guardianes</span>
                    </label>
                  </div>
                </div>
              </div>

              <!-- Preview del código de invitación -->
              <div class="p-4 bg-green-900/20 border border-green-500/30 rounded-lg">
                <h4 class="text-sm font-semibold text-green-300 mb-2 flex items-center">
                  <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                  Código de Acceso Generado
                </h4>
                <div class="flex items-center justify-between p-3 bg-black/30 rounded-lg">
                  <div>
                    <div class="text-destiny-gold font-mono font-bold text-lg">
                      {{ codigoGenerado }}
                    </div>
                    <div class="text-xs text-gray-400">
                      Los estudiantes usarán este código para unirse
                    </div>
                  </div>
                  <button
                    type="button"
                    @click="regenerarCodigo"
                    class="text-gray-400 hover:text-destiny-cyan transition-colors p-2 rounded"
                    title="Regenerar código"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                  </button>
                </div>
              </div>

              <!-- Resumen -->
              <div class="p-4 bg-gray-800/50 rounded-lg border border-gray-700/50">
                <h4 class="text-sm font-semibold text-gray-300 mb-3">Resumen de la Operación</h4>
                <div class="space-y-2 text-sm">
                  <div class="flex justify-between">
                    <span class="text-gray-400">Nombre:</span>
                    <span class="text-white">{{ formulario.nombre || 'Sin definir' }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-gray-400">Grado y Sección:</span>
                    <span class="text-white">{{ formulario.grado }} {{ formulario.seccion }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-gray-400">Duración:</span>
                    <span class="text-white">{{ duracionCalculada }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-gray-400">Tema:</span>
                    <span class="text-white">{{ temaSeleccionado?.nombre || 'Destiny Clásico' }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-gray-400">Multiplicador XP:</span>
                    <span class="text-destiny-gold font-semibold">x{{ formulario.configuracion_gamificacion.multiplicador_xp }}</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Footer -->
            <div class="destiny-modal-footer px-6 py-4 border-t border-gray-700/50 flex justify-end space-x-3">
              <button
                type="button"
                @click="$emit('close')"
                class="px-4 py-2 text-gray-400 hover:text-white transition-colors"
              >
                Cancelar
              </button>
              <button
                type="submit"
                :disabled="!formularioValido || creando"
                class="destiny-button-primary disabled:opacity-50 disabled:cursor-not-allowed flex items-center"
              >
                <svg v-if="creando" class="animate-spin -ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                {{ creando ? 'Creando Operación...' : 'Crear Operación' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </Teleport>
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue'

interface NuevaClaseData {
  nombre: string
  descripcion: string
  grado: string
  seccion: string
  fecha_inicio: string
  fecha_fin: string
  tema_visual: string
  configuracion_gamificacion: {
    multiplicador_xp: number
    dificultad: string
    modo_competitivo: boolean
  }
}

interface TemaVisual {
  id: string
  nombre: string
  icono: string
  descripcion: string
}

interface Props {
  show: boolean
}

const props = defineProps<Props>()

const emit = defineEmits<{
  close: []
  creada: [clase: any]
}>()

// Estado reactivo
const creando = ref(false)
const codigoGenerado = ref('')

const formulario = ref<NuevaClaseData>({
  nombre: '',
  descripcion: '',
  grado: '',
  seccion: '',
  fecha_inicio: '',
  fecha_fin: '',
  tema_visual: 'destiny_default',
  configuracion_gamificacion: {
    multiplicador_xp: 1,
    dificultad: 'normal',
    modo_competitivo: false
  }
})

// Datos estáticos
const gradosDisponibles = [
  '1° Primaria', '2° Primaria', '3° Primaria', '4° Primaria', '5° Primaria', '6° Primaria',
  '1° Secundaria', '2° Secundaria', '3° Secundaria', '4° Secundaria', '5° Secundaria'
]

const seccionesDisponibles = ['A', 'B', 'C', 'D', 'E', 'F']

const temasVisuales: TemaVisual[] = [
  {
    id: 'destiny_default',
    nombre: 'Destiny Clásico',
    icono: '🌌',
    descripcion: 'Tema oficial de Destiny con colores dorado y azul'
  },
  {
    id: 'solar_system',
    nombre: 'Sistema Solar',
    icono: '☀️',
    descripcion: 'Tema cálido con tonos naranjas y rojos'
  },
  {
    id: 'void_darkness',
    nombre: 'Vacío Oscuro',
    icono: '🌑',
    descripcion: 'Tema púrpura y negro del Vacío'
  },
  {
    id: 'arc_storm',
    nombre: 'Tormenta Arco',
    icono: '⚡',
    descripcion: 'Tema eléctrico con azules y blancos'
  },
  {
    id: 'forest_earth',
    nombre: 'Tierra Verde',
    icono: '🌿',
    descripcion: 'Tema natural con verdes y marrones'
  },
  {
    id: 'ice_crystal',
    nombre: 'Cristal Helado',
    icono: '❄️',
    descripcion: 'Tema frío con azules y blancos cristalinos'
  },
  {
    id: 'fire_phoenix',
    nombre: 'Fénix de Fuego',
    icono: '🔥',
    descripcion: 'Tema ardiente con rojos y naranjas'
  },
  {
    id: 'cosmic_nebula',
    nombre: 'Nebulosa Cósmica',
    icono: '🌠',
    descripcion: 'Tema espacial con púrpuras y azules'
  }
]

// Computed properties
const fechaMinima = computed(() => {
  const hoy = new Date()
  return hoy.toISOString().split('T')[0]
})

const formularioValido = computed(() => {
  return formulario.value.nombre.trim() &&
         formulario.value.grado &&
         formulario.value.seccion &&
         formulario.value.fecha_inicio &&
         formulario.value.fecha_fin &&
         new Date(formulario.value.fecha_fin) > new Date(formulario.value.fecha_inicio)
})

const temaSeleccionado = computed(() => {
  return temasVisuales.find(t => t.id === formulario.value.tema_visual)
})

const duracionCalculada = computed(() => {
  if (!formulario.value.fecha_inicio || !formulario.value.fecha_fin) {
    return 'Sin definir'
  }
  
  const inicio = new Date(formulario.value.fecha_inicio)
  const fin = new Date(formulario.value.fecha_fin)
  const diferencia = fin.getTime() - inicio.getTime()
  const dias = Math.ceil(diferencia / (1000 * 60 * 60 * 24))
  
  if (dias < 30) {
    return `${dias} días`
  } else if (dias < 365) {
    const meses = Math.ceil(dias / 30)
    return `${meses} mes${meses > 1 ? 'es' : ''}`
  } else {
    const años = Math.ceil(dias / 365)
    return `${años} año${años > 1 ? 's' : ''}`
  }
})

// Methods
const generarCodigoInvitacion = (): string => {
  const prefijo = formulario.value.grado ? formulario.value.grado.substring(0, 1) : 'X'
  const sufijo = formulario.value.seccion || 'A'
  const random = Math.random().toString(36).substring(2, 5).toUpperCase()
  return `${prefijo}${sufijo}${random}`
}

const regenerarCodigo = () => {
  codigoGenerado.value = generarCodigoInvitacion()
}

const resetFormulario = () => {
  formulario.value = {
    nombre: '',
    descripcion: '',
    grado: '',
    seccion: '',
    fecha_inicio: '',
    fecha_fin: '',
    tema_visual: 'destiny_default',
    configuracion_gamificacion: {
      multiplicador_xp: 1,
      dificultad: 'normal',
      modo_competitivo: false
    }
  }
  codigoGenerado.value = ''
}

const crearClase = async () => {
  if (!formularioValido.value) return

  creando.value = true

  try {
    const datosCompletos = {
      ...formulario.value,
      codigo_invitacion: codigoGenerado.value,
      año_academico: new Date().getFullYear(),
      activa: true
    }

    // Usar fetch nativo en lugar de $fetch para compatibilidad
    const response = await fetch('/api/profesor/clases', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(datosCompletos)
    })

    if (!response.ok) {
      throw new Error(`Error HTTP: ${response.status}`)
    }

    const data = await response.json()
    emit('creada', data.data || data)
    resetFormulario()
    
  } catch (error: any) {
    console.error('Error al crear clase:', error)
    alert(error.message || 'Error al crear la operación')
  } finally {
    creando.value = false
  }
}

// Watchers
watch(() => props.show, (show) => {
  if (show) {
    resetFormulario()
    regenerarCodigo()
    
    // Establecer fechas por defecto
    const hoy = new Date()
    const finAño = new Date(hoy.getFullYear(), 11, 31) // 31 de diciembre
    
    formulario.value.fecha_inicio = hoy.toISOString().split('T')[0]
    formulario.value.fecha_fin = finAño.toISOString().split('T')[0]
  }
})

watch([() => formulario.value.grado, () => formulario.value.seccion], () => {
  if (formulario.value.grado && formulario.value.seccion) {
    regenerarCodigo()
  }
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

.tema-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
}

.destiny-button-primary {
  @apply bg-gradient-to-r from-destiny-gold to-yellow-500 text-black font-semibold px-4 py-2 rounded-lg;
  @apply hover:from-yellow-400 hover:to-destiny-gold transition-all duration-300;
  @apply shadow-lg hover:shadow-xl;
}

/* Custom checkbox styling */
input[type="checkbox"] {
  @apply w-4 h-4;
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