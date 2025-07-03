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
          class="destiny-modal inline-block align-bottom bg-gradient-to-br from-gray-900 to-black rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full"
          @click.stop
        >
          <!-- Header -->
          <div class="destiny-modal-header px-6 py-4 border-b border-destiny-gold/30">
            <div class="flex items-center justify-between">
              <h3 class="text-lg font-bold text-destiny-gold flex items-center">
                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/>
                </svg>
                Guardián Seleccionado
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
            <div v-if="estudiante" class="text-center">
              <!-- Avatar y efecto de selección -->
              <div class="relative mb-6">
                <div class="guardian-selection-effect">
                  <div class="guardian-avatar mx-auto mb-4">
                    <div 
                      class="w-24 h-24 rounded-full flex items-center justify-center text-3xl font-bold text-white mx-auto"
                      :class="getClassColor(estudiante.clase_rpg)"
                    >
                      {{ estudiante.nombre[0] }}{{ estudiante.apellido?.[0] || '' }}
                    </div>
                  </div>
                  
                  <!-- Anillos de selección animados -->
                  <div class="selection-ring ring-1"></div>
                  <div class="selection-ring ring-2"></div>
                  <div class="selection-ring ring-3"></div>
                </div>

                <!-- Clase RPG Badge -->
                <div 
                  class="absolute -top-2 -right-2 px-3 py-1 rounded-full text-xs font-bold text-white"
                  :class="getClassBadgeColor(estudiante.clase_rpg)"
                >
                  {{ getClassDisplayName(estudiante.clase_rpg) }}
                </div>
              </div>

              <!-- Información del estudiante -->
              <div class="mb-6">
                <h4 class="text-xl font-bold text-white mb-2">
                  {{ estudiante.nombre }} {{ estudiante.apellido }}
                </h4>
                <p class="text-destiny-cyan text-lg font-semibold">
                  Nivel {{ estudiante.nivel }} {{ getClassDisplayName(estudiante.clase_rpg) }}
                </p>
              </div>

              <!-- Stats del guardián -->
              <div class="grid grid-cols-2 gap-4 mb-6">
                <!-- Salud -->
                <div class="stat-container p-3 bg-gray-800/50 rounded-lg border border-gray-700/50">
                  <div class="text-xs text-gray-400 uppercase tracking-wider mb-1">Salud</div>
                  <div class="flex items-center space-x-2">
                    <div class="text-lg font-bold" :class="getSaludColor(estudiante.salud_actual)">
                      {{ estudiante.salud_actual }}%
                    </div>
                    <div class="flex-1">
                      <div class="w-full bg-gray-700 rounded-full h-2">
                        <div 
                          class="h-2 rounded-full transition-all duration-1000"
                          :class="getSaludBarColor(estudiante.salud_actual)"
                          :style="{ width: `${estudiante.salud_actual}%` }"
                        ></div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Experiencia -->
                <div class="stat-container p-3 bg-gray-800/50 rounded-lg border border-gray-700/50">
                  <div class="text-xs text-gray-400 uppercase tracking-wider mb-1">Experiencia</div>
                  <div class="text-lg font-bold text-destiny-gold">
                    {{ estudiante.experiencia_actual.toLocaleString() }} XP
                  </div>
                  <div class="text-xs text-gray-400">
                    {{ ((estudiante.experiencia_actual / estudiante.experiencia_siguiente) * 100).toFixed(1) }}% al siguiente nivel
                  </div>
                </div>
              </div>

              <!-- Barra de progreso al siguiente nivel -->
              <div class="mb-6 p-3 bg-blue-900/20 border border-blue-500/30 rounded-lg">
                <div class="flex items-center justify-between text-xs text-gray-400 mb-2">
                  <span>Progreso de Nivel</span>
                  <span>{{ estudiante.experiencia_actual }} / {{ estudiante.experiencia_siguiente }}</span>
                </div>
                <div class="w-full bg-gray-700 rounded-full h-3">
                  <div 
                    class="destiny-progress-bar h-3 rounded-full transition-all duration-1000"
                    :style="{ width: `${(estudiante.experiencia_actual / estudiante.experiencia_siguiente) * 100}%` }"
                  ></div>
                </div>
              </div>

              <!-- Estado del guardián -->
              <div class="mb-6">
                <div 
                  class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                  :class="getEstadoClasses(estudiante.estado)"
                >
                  <div class="w-2 h-2 rounded-full mr-2" :class="getEstadoDotColor(estudiante.estado)"></div>
                  {{ getEstadoTexto(estudiante.estado) }}
                </div>
              </div>

              <!-- Información de la clase -->
              <div v-if="clase" class="mb-6 p-3 bg-gray-800/30 rounded-lg border border-gray-700/30">
                <div class="text-xs text-gray-400 uppercase tracking-wider mb-1">Operación Actual</div>
                <div class="font-semibold text-white">{{ clase.nombre }}</div>
                <div class="text-sm text-gray-400">{{ clase.estudiantes_count }} Guardianes activos</div>
              </div>
            </div>

            <!-- Mensaje de carga -->
            <div v-else class="text-center py-8">
              <div class="guardian-spinner mx-auto mb-4"></div>
              <p class="text-gray-400">Seleccionando Guardián...</p>
            </div>
          </div>

          <!-- Footer -->
          <div v-if="estudiante" class="destiny-modal-footer px-6 py-4 border-t border-gray-700/50">
            <div class="flex justify-between items-center">
              <button
                @click="$emit('close')"
                class="px-4 py-2 text-gray-400 hover:text-white transition-colors"
              >
                Cerrar
              </button>
              <div class="flex space-x-2">
                <button
                  @click="seleccionarOtro"
                  class="destiny-button-secondary"
                >
                  <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                  </svg>
                  Otro Guardián
                </button>
                <button
                  @click="aplicarComportamiento"
                  class="destiny-button-primary"
                >
                  <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                  </svg>
                  Aplicar Comportamiento
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </Teleport>
</template>

<script setup lang="ts">
import { computed } from 'vue'

interface Estudiante {
  id: number
  nombre: string
  apellido: string
  nivel: number
  clase_rpg: string
  experiencia_actual: number
  experiencia_siguiente: number
  salud_actual: number
  salud_maxima: number
  estado: 'activo' | 'critico' | 'descanso'
}

interface Clase {
  id: number
  nombre: string
  estudiantes_count: number
}

interface Props {
  show: boolean
  estudiante?: Estudiante | null
  clase?: Clase | null
}

const props = defineProps<Props>()

const emit = defineEmits<{
  close: []
  'aplicar-comportamiento': [clase: Clase, estudiante: Estudiante]
}>()

// Methods
const getClassColor = (claseRpg: string): string => {
  const colores = {
    hunter: 'bg-gradient-to-br from-green-500 to-green-600',
    titan: 'bg-gradient-to-br from-blue-500 to-blue-600',
    warlock: 'bg-gradient-to-br from-purple-500 to-purple-600'
  }
  return colores[claseRpg] || 'bg-gradient-to-br from-gray-500 to-gray-600'
}

const getClassBadgeColor = (claseRpg: string): string => {
  const colores = {
    hunter: 'bg-green-500',
    titan: 'bg-blue-500',
    warlock: 'bg-purple-500'
  }
  return colores[claseRpg] || 'bg-gray-500'
}

const getClassDisplayName = (claseRpg: string): string => {
  const nombres = {
    hunter: 'Hunter',
    titan: 'Titan',
    warlock: 'Warlock'
  }
  return nombres[claseRpg] || 'Guardián'
}

const getSaludColor = (salud: number): string => {
  if (salud >= 70) return 'text-green-400'
  if (salud >= 30) return 'text-yellow-400'
  return 'text-red-400'
}

const getSaludBarColor = (salud: number): string => {
  if (salud >= 70) return 'bg-green-500'
  if (salud >= 30) return 'bg-yellow-500'
  return 'bg-red-500'
}

const getEstadoClasses = (estado: string): string => {
  const clases = {
    activo: 'bg-green-900/50 text-green-300 border border-green-500/30',
    critico: 'bg-red-900/50 text-red-300 border border-red-500/30',
    descanso: 'bg-yellow-900/50 text-yellow-300 border border-yellow-500/30'
  }
  return clases[estado] || 'bg-gray-900/50 text-gray-300 border border-gray-500/30'
}

const getEstadoDotColor = (estado: string): string => {
  const colores = {
    activo: 'bg-green-400',
    critico: 'bg-red-400',
    descanso: 'bg-yellow-400'
  }
  return colores[estado] || 'bg-gray-400'
}

const getEstadoTexto = (estado: string): string => {
  const textos = {
    activo: 'En Servicio Activo',
    critico: 'Estado Crítico',
    descanso: 'En Descanso'
  }
  return textos[estado] || 'Estado Desconocido'
}

const seleccionarOtro = () => {
  // Emitir evento para seleccionar otro estudiante aleatorio
  if (props.clase) {
    // Esto debería triggerar una nueva selección aleatoria en el componente padre
    emit('close')
    // El componente padre debería manejar la re-selección
  }
}

const aplicarComportamiento = () => {
  if (props.estudiante && props.clase) {
    emit('aplicar-comportamiento', props.clase, props.estudiante)
  }
}
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

.guardian-selection-effect {
  position: relative;
  display: inline-block;
}

.guardian-avatar {
  position: relative;
  z-index: 10;
  animation: guardian-reveal 1s ease-out;
}

.selection-ring {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  border: 2px solid rgba(199, 184, 138, 0.6);
  border-radius: 50%;
  animation: ring-expand 2s ease-out infinite;
}

.ring-1 {
  width: 120px;
  height: 120px;
  animation-delay: 0s;
}

.ring-2 {
  width: 140px;
  height: 140px;
  animation-delay: 0.3s;
}

.ring-3 {
  width: 160px;
  height: 160px;
  animation-delay: 0.6s;
}

@keyframes guardian-reveal {
  0% {
    opacity: 0;
    transform: scale(0.8) rotateY(180deg);
  }
  50% {
    opacity: 0.7;
    transform: scale(1.1) rotateY(90deg);
  }
  100% {
    opacity: 1;
    transform: scale(1) rotateY(0deg);
  }
}

@keyframes ring-expand {
  0% {
    opacity: 0.8;
    transform: translate(-50%, -50%) scale(0.8);
  }
  50% {
    opacity: 0.4;
    transform: translate(-50%, -50%) scale(1);
  }
  100% {
    opacity: 0;
    transform: translate(-50%, -50%) scale(1.2);
  }
}

.guardian-spinner {
  width: 60px;
  height: 60px;
  border: 4px solid rgba(199, 184, 138, 0.3);
  border-top: 4px solid #C7B88A;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.stat-container {
  transition: all 0.3s ease;
}

.stat-container:hover {
  background: rgba(75, 85, 99, 0.3);
  border-color: rgba(199, 184, 138, 0.3);
}

.destiny-progress-bar {
  background: linear-gradient(90deg, #C7B88A 0%, #6EC1E4 100%);
  box-shadow: 0 0 10px rgba(199, 184, 138, 0.3);
  animation: progress-glow 2s ease-in-out infinite alternate;
}

@keyframes progress-glow {
  from {
    box-shadow: 0 0 10px rgba(199, 184, 138, 0.3);
  }
  to {
    box-shadow: 0 0 20px rgba(199, 184, 138, 0.6);
  }
}

.destiny-button-primary {
  @apply bg-gradient-to-r from-destiny-gold to-yellow-500 text-black font-semibold px-4 py-2 rounded-lg;
  @apply hover:from-yellow-400 hover:to-destiny-gold transition-all duration-300;
  @apply shadow-lg hover:shadow-xl flex items-center;
}

.destiny-button-secondary {
  @apply bg-gradient-to-r from-gray-700 to-gray-800 text-white font-semibold px-4 py-2 rounded-lg;
  @apply hover:from-gray-600 hover:to-gray-700 transition-all duration-300;
  @apply border border-gray-600 hover:border-gray-500 flex items-center;
}

/* Responsive */
@media (max-width: 640px) {
  .guardian-avatar {
    @apply w-20 h-20 text-2xl;
  }
  
  .selection-ring.ring-1 {
    width: 100px;
    height: 100px;
  }
  
  .selection-ring.ring-2 {
    width: 120px;
    height: 120px;
  }
  
  .selection-ring.ring-3 {
    width: 140px;
    height: 140px;
  }
}
</style>