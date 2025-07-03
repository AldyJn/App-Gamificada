<template>
  <Teleport to="body">
    <div
      v-if="show && clase"
      class="fixed inset-0 z-50 overflow-y-auto"
      @click="$emit('close')"
    >
      <!-- Overlay -->
      <div class="fixed inset-0 bg-black/75 backdrop-blur-sm transition-opacity"></div>
      
      <!-- Modal Container -->
      <div class="flex min-h-full items-center justify-center p-4">
        <div
          class="destiny-modal w-full max-w-2xl max-h-[90vh] overflow-y-auto"
          @click.stop
        >
          <!-- Header -->
          <div class="destiny-modal-header p-6 border-b border-gray-700/50">
            <div class="flex items-center justify-between">
              <div>
                <h2 class="text-2xl font-bold text-white">Modificar Operación</h2>
                <p class="text-sm text-gray-400 mt-1">Actualiza la configuración de tu operación guardián</p>
              </div>
              <button
                @click="$emit('close')"
                class="destiny-btn-icon text-gray-400 hover:text-white"
              >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
              </button>
            </div>
          </div>

          <!-- Contenido del formulario -->
          <div class="destiny-modal-content p-6">
            <form @submit.prevent="guardarCambios" class="space-y-6">
              
              <!-- Información básica -->
              <div class="space-y-4">
                <h3 class="text-lg font-semibold text-white flex items-center">
                  <svg class="w-5 h-5 mr-2 text-[#C7B88A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                  Información Básica
                </h3>
                
                <div class="grid md:grid-cols-2 gap-4">
                  <!-- Nombre de la clase -->
                  <div class="md:col-span-2">
                    <label for="nombre" class="block text-sm font-medium text-gray-300 mb-2">
                      Nombre de la Operación *
                    </label>
                    <input
                      id="nombre"
                      v-model="formulario.nombre"
                      type="text"
                      required
                      class="destiny-input w-full"
                      placeholder="Ej: Matemáticas Avanzadas 5° A"
                    >
                  </div>

                  <!-- Descripción -->
                  <div class="md:col-span-2">
                    <label for="descripcion" class="block text-sm font-medium text-gray-300 mb-2">
                      Descripción
                    </label>
                    <textarea
                      id="descripcion"
                      v-model="formulario.descripcion"
                      rows="3"
                      class="destiny-input w-full resize-none"
                      placeholder="Describe los objetivos y contenido de tu operación..."
                    ></textarea>
                  </div>

                  <!-- Grado -->
                  <div>
                    <label for="grado" class="block text-sm font-medium text-gray-300 mb-2">
                      Grado *
                    </label>
                    <select
                      id="grado"
                      v-model="formulario.grado"
                      required
                      class="destiny-select w-full"
                    >
                      <option value="">Seleccionar grado...</option>
                      <option v-for="grado in gradosDisponibles" :key="grado" :value="grado">
                        {{ grado }}
                      </option>
                    </select>
                  </div>

                  <!-- Sección -->
                  <div>
                    <label for="seccion" class="block text-sm font-medium text-gray-300 mb-2">
                      Sección *
                    </label>
                    <select
                      id="seccion"
                      v-model="formulario.seccion"
                      required
                      class="destiny-select w-full"
                    >
                      <option value="">Seleccionar sección...</option>
                      <option v-for="seccion in seccionesDisponibles" :key="seccion" :value="seccion">
                        Sección {{ seccion }}
                      </option>
                    </select>
                  </div>
                </div>
              </div>

              <!-- Configuración temporal -->
              <div class="space-y-4">
                <h3 class="text-lg font-semibold text-white flex items-center">
                  <svg class="w-5 h-5 mr-2 text-[#6EC1E4]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                  </svg>
                  Cronología de Operación
                </h3>
                
                <div class="grid md:grid-cols-2 gap-4">
                  <!-- Fecha de inicio -->
                  <div>
                    <label for="fecha_inicio" class="block text-sm font-medium text-gray-300 mb-2">
                      Fecha de Inicio *
                    </label>
                    <input
                      id="fecha_inicio"
                      v-model="formulario.fecha_inicio"
                      type="date"
                      required
                      class="destiny-input w-full"
                    >
                  </div>

                  <!-- Fecha de fin -->
                  <div>
                    <label for="fecha_fin" class="block text-sm font-medium text-gray-300 mb-2">
                      Fecha de Finalización *
                    </label>
                    <input
                      id="fecha_fin"
                      v-model="formulario.fecha_fin"
                      type="date"
                      required
                      class="destiny-input w-full"
                    >
                  </div>
                </div>
              </div>

              <!-- Configuración visual y mecánicas -->
              <div class="space-y-4">
                <h3 class="text-lg font-semibold text-white flex items-center">
                  <svg class="w-5 h-5 mr-2 text-[#C7B88A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z"/>
                  </svg>
                  Personalización Visual
                </h3>
                
                <div class="space-y-4">
                  <!-- Tema visual -->
                  <div>
                    <label class="block text-sm font-medium text-gray-300 mb-3">
                      Tema Visual
                    </label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                      <div
                        v-for="tema in temasVisuales"
                        :key="tema.id"
                        @click="formulario.tema_visual = tema.id"
                        :class="[
                          'cursor-pointer p-3 rounded-lg border-2 transition-all duration-200',
                          formulario.tema_visual === tema.id
                            ? 'border-[#C7B88A] bg-[#C7B88A]/10'
                            : 'border-gray-600 hover:border-gray-500 bg-black/20'
                        ]"
                      >
                        <div class="text-center">
                          <div class="text-2xl mb-1">{{ tema.icono }}</div>
                          <div class="text-sm font-medium text-white">{{ tema.nombre }}</div>
                          <div class="text-xs text-gray-400 mt-1">{{ tema.descripcion }}</div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Color personalizado -->
                  <div>
                    <label for="color_tema" class="block text-sm font-medium text-gray-300 mb-2">
                      Color Principal
                    </label>
                    <div class="flex items-center space-x-3">
                      <input
                        id="color_tema"
                        v-model="formulario.color_tema"
                        type="color"
                        class="w-12 h-10 rounded border border-gray-600 bg-black cursor-pointer"
                      >
                      <input
                        v-model="formulario.color_tema"
                        type="text"
                        class="destiny-input flex-1"
                        placeholder="#C7B88A"
                      >
                    </div>
                  </div>
                </div>
              </div>

              <!-- Configuración de gamificación -->
              <div class="space-y-4">
                <h3 class="text-lg font-semibold text-white flex items-center">
                  <svg class="w-5 h-5 mr-2 text-orange-400" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                  </svg>
                  Mecánicas de Juego
                </h3>
                
                <div class="space-y-4">
                  <!-- Modo competitivo -->
                  <div class="flex items-center justify-between p-4 bg-black/30 rounded-lg border border-gray-700/50">
                    <div>
                      <div class="font-medium text-white">Modo Competitivo</div>
                      <div class="text-sm text-gray-400">Habilita rankings y competencias entre guardianes</div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                      <input
                        v-model="formulario.configuracion_gamificacion.modo_competitivo"
                        type="checkbox"
                        class="sr-only peer"
                      >
                      <div class="w-11 h-6 bg-gray-600 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#C7B88A]/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#C7B88A]"></div>
                    </label>
                  </div>

                  <!-- Multiplicador de XP -->
                  <div>
                    <label class="block text-sm font-medium text-gray-300 mb-3">
                      Multiplicador de Experiencia: {{ formulario.configuracion_gamificacion.multiplicador_xp }}x
                    </label>
                    <div class="px-3">
                      <input
                        v-model="formulario.configuracion_gamificacion.multiplicador_xp"
                        type="range"
                        min="0.5"
                        max="3"
                        step="0.1"
                        class="w-full h-2 bg-gray-700 rounded-lg appearance-none cursor-pointer slider"
                      >
                      <div class="flex justify-between text-xs text-gray-400 mt-1">
                        <span>0.5x</span>
                        <span>1x</span>
                        <span>2x</span>
                        <span>3x</span>
                      </div>
                    </div>
                  </div>

                  <!-- Nivel de dificultad -->
                  <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                      Nivel de Dificultad
                    </label>
                    <div class="grid grid-cols-3 gap-2">
                      <button
                        v-for="dificultad in ['facil', 'normal', 'dificil']"
                        :key="dificultad"
                        type="button"
                        @click="formulario.configuracion_gamificacion.dificultad = dificultad"
                        :class="[
                          'p-3 rounded-lg border-2 text-sm font-medium transition-all duration-200',
                          formulario.configuracion_gamificacion.dificultad === dificultad
                            ? 'border-[#C7B88A] bg-[#C7B88A]/10 text-[#C7B88A]'
                            : 'border-gray-600 text-gray-400 hover:border-gray-500 hover:text-white'
                        ]"
                      >
                        {{ dificultad === 'facil' ? 'Fácil' : dificultad === 'normal' ? 'Normal' : 'Difícil' }}
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Estado de la clase -->
              <div class="space-y-4">
                <h3 class="text-lg font-semibold text-white flex items-center">
                  <svg class="w-5 h-5 mr-2 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                  Estado de Operación
                </h3>
                
                <div class="flex items-center justify-between p-4 bg-black/30 rounded-lg border border-gray-700/50">
                  <div>
                    <div class="font-medium text-white">Operación Activa</div>
                    <div class="text-sm text-gray-400">Los guardianes pueden unirse y participar en misiones</div>
                  </div>
                  <label class="relative inline-flex items-center cursor-pointer">
                    <input
                      v-model="formulario.activa"
                      type="checkbox"
                      class="sr-only peer"
                    >
                    <div class="w-11 h-6 bg-gray-600 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-400/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                  </label>
                </div>
              </div>

            </form>
          </div>

          <!-- Footer -->
          <div class="destiny-modal-footer p-6 border-t border-gray-700/50">
            <div class="flex justify-end space-x-3">
              <button
                @click="$emit('close')"
                type="button"
                class="destiny-btn-secondary"
              >
                Cancelar
              </button>
              <button
                @click="guardarCambios"
                :disabled="guardando || !esFormularioValido"
                class="destiny-btn destiny-btn-primary"
              >
                <div v-if="guardando" class="destiny-spinner-sm mr-2"></div>
                {{ guardando ? 'Guardando...' : 'Guardar Cambios' }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </Teleport>
</template>

<script setup lang="ts">
import { ref, computed, watch, onMounted } from 'vue'
import type { Clase } from '@/composables/useClasesIndex'

interface EditarClaseData {
  nombre: string
  descripcion: string
  grado: string
  seccion: string
  fecha_inicio: string
  fecha_fin: string
  tema_visual: string
  color_tema: string
  activa: boolean
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
  clase: Clase | null
}

const props = defineProps<Props>()

const emit = defineEmits<{
  close: []
  editada: [clase: Clase]
}>()

// Estado reactivo
const guardando = ref(false)

const formulario = ref<EditarClaseData>({
  nombre: '',
  descripcion: '',
  grado: '',
  seccion: '',
  fecha_inicio: '',
  fecha_fin: '',
  tema_visual: 'destiny_default',
  color_tema: '#C7B88A',
  activa: true,
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
    descripcion: 'Tema oficial con colores dorado y azul'
  },
  {
    id: 'solar_system',
    nombre: 'Sistema Solar',
    icono: '☀️',
    descripcion: 'Tema cálido con tonos naranjas'
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
    descripcion: 'Tema eléctrico azul y blanco'
  },
  {
    id: 'forest_earth',
    nombre: 'Tierra Verde',
    icono: '🌿',
    descripcion: 'Tema natural verde y marrón'
  },
  {
    id: 'ice_crystal',
    nombre: 'Cristal Helado',
    icono: '❄️',
    descripcion: 'Tema frío azul cristalino'
  }
]

// Computed properties
const esFormularioValido = computed(() => {
  return formulario.value.nombre.trim() && 
         formulario.value.grado && 
         formulario.value.seccion &&
         formulario.value.fecha_inicio &&
         formulario.value.fecha_fin &&
         new Date(formulario.value.fecha_fin) > new Date(formulario.value.fecha_inicio)
})

// Métodos
const cargarDatosClase = () => {
  if (props.clase) {
    formulario.value = {
      nombre: props.clase.nombre,
      descripcion: props.clase.descripcion || '',
      grado: props.clase.grado,
      seccion: props.clase.seccion,
      fecha_inicio: props.clase.fecha_inicio.split('T')[0], // Solo la fecha
      fecha_fin: props.clase.fecha_fin.split('T')[0],
      tema_visual: props.clase.tema_visual,
      color_tema: props.clase.color_tema,
      activa: props.clase.activa,
      configuracion_gamificacion: {
        multiplicador_xp: props.clase.configuracion?.multiplicador_xp || 1,
        dificultad: props.clase.configuracion?.dificultad || 'normal',
        modo_competitivo: props.clase.modo_competitivo
      }
    }
  }
}

const guardarCambios = async () => {
  if (!props.clase || !esFormularioValido.value) return
  
  guardando.value = true
  
  try {
    const response = await $fetch<{
      success: boolean
      data: Clase
      message: string
    }>(`/api/profesor/clases/${props.clase.id}`, {
      method: 'PUT',
      body: {
        ...formulario.value,
        configuracion: formulario.value.configuracion_gamificacion
      }
    })

    if (response.success) {
      emit('editada', response.data)
    } else {
      throw new Error(response.message || 'Error al actualizar clase')
    }
    
  } catch (error: any) {
    console.error('Error al guardar cambios:', error)
    // Aquí podrías mostrar una notificación de error
  } finally {
    guardando.value = false
  }
}

// Watchers
watch(() => props.show, (nuevoValor) => {
  if (nuevoValor) {
    cargarDatosClase()
  }
})

watch(() => props.clase, () => {
  if (props.show && props.clase) {
    cargarDatosClase()
  }
})

// Lifecycle
onMounted(() => {
  if (props.show && props.clase) {
    cargarDatosClase()
  }
})
</script>

<style scoped>
.destiny-modal {
  @apply bg-gradient-to-br from-[#1B1C29] to-[#2A3441] border border-[#C7B88A]/30 rounded-xl shadow-2xl backdrop-blur-xl;
}

.destiny-modal-header {
  @apply bg-gradient-to-r from-[#C7B88A]/10 to-[#6EC1E4]/10;
}

.destiny-modal-content {
  @apply bg-gradient-to-br from-[#1B1C29]/50 to-[#2A3441]/50;
}

.destiny-modal-footer {
  @apply bg-gradient-to-r from-[#1B1C29]/80 to-[#2A3441]/80;
}

.destiny-input {
  @apply bg-gradient-to-br from-[#1B1C29] to-[#2A3441] border border-[#C7B88A]/30 text-white rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-[#C7B88A] focus:border-transparent placeholder-gray-500;
}

.destiny-input:focus {
  @apply shadow-lg;
  box-shadow: 0 0 0 4px rgba(199, 184, 138, 0.1);
}

.destiny-select {
  @apply bg-gradient-to-br from-[#1B1C29] to-[#2A3441] border border-[#C7B88A]/30 text-white rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-[#C7B88A] focus:border-transparent;
}

.destiny-btn {
  @apply inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2;
}

.destiny-btn-primary {
  @apply bg-gradient-to-r from-[#C7B88A] to-[#6EC1E4] text-[#1B1C29] hover:from-[#B8A97A] hover:to-[#5DB1D4] focus:ring-[#C7B88A];
}

.destiny-btn-secondary {
  @apply bg-black/30 border border-gray-600 text-gray-300 hover:text-white hover:border-gray-500 hover:bg-black/50;
}

.destiny-btn-icon {
  @apply p-2 rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2;
}

.destiny-spinner-sm {
  @apply w-4 h-4 border-2 border-[#1B1C29]/20 border-t-[#1B1C29] rounded-full animate-spin;
}

/* Slider personalizado */
.slider::-webkit-slider-thumb {
  @apply appearance-none w-5 h-5 bg-gradient-to-r from-[#C7B88A] to-[#6EC1E4] rounded-full cursor-pointer;
  box-shadow: 0 0 10px rgba(199, 184, 138, 0.5);
}

.slider::-moz-range-thumb {
  @apply w-5 h-5 bg-gradient-to-r from-[#C7B88A] to-[#6EC1E4] rounded-full cursor-pointer border-0;
  box-shadow: 0 0 10px rgba(199, 184, 138, 0.5);
}

.slider::-webkit-slider-track {
  @apply bg-gray-700 rounded-lg h-2;
}

.slider::-moz-range-track {
  @apply bg-gray-700 rounded-lg h-2 border-0;
}

/* Animaciones */
@keyframes modal-appear {
  from {
    opacity: 0;
    transform: scale(0.95) translateY(-10px);
  }
  to {
    opacity: 1;
    transform: scale(1) translateY(0);
  }
}

.destiny-modal {
  animation: modal-appear 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Efectos hover */
.destiny-btn:hover {
  transform: translateY(-1px);
}

.destiny-btn:disabled {
  @apply opacity-50 cursor-not-allowed;
  transform: none;
}

/* Responsive */
@media (max-width: 768px) {
  .destiny-modal {
    @apply mx-4 max-w-none max-h-[95vh];
  }
  
  .grid.md\\:grid-cols-2 {
    @apply grid-cols-1;
  }
  
  .grid.md\\:grid-cols-3 {
    @apply grid-cols-2;
  }
}

@media (max-width: 640px) {
  .destiny-modal-content {
    @apply p-4;
  }
  
  .destiny-modal-header {
    @apply p-4;
  }
  
  .destiny-modal-footer {
    @apply p-4;
  }
  
  .grid.grid-cols-3 {
    @apply grid-cols-1;
  }
}