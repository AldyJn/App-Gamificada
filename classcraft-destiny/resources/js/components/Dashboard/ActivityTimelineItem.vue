<template>
  <div class="timeline-item relative flex items-start space-x-4 pb-4">
    <!-- Timeline dot y línea -->
    <div class="timeline-marker flex-shrink-0 relative">
      <div 
        class="timeline-dot w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold"
        :class="dotClasses"
        :style="{ backgroundColor: actividad.color || defaultColor }"
      >
        {{ actividad.icono }}
      </div>
    </div>

    <!-- Contenido -->
    <div class="timeline-content flex-1 min-w-0">
      <div class="activity-card p-3 rounded-lg transition-all duration-300 hover:scale-[1.02]">
        <!-- Header con tiempo -->
        <div class="flex items-start justify-between mb-2">
          <div class="flex-1 min-w-0">
            <h4 class="activity-title font-semibold text-white text-sm leading-tight">
              {{ formatearTitulo() }}
            </h4>
            <p class="activity-description text-xs text-gray-400 mt-1 leading-relaxed">
              {{ actividad.descripcion }}
            </p>
          </div>
          <div class="flex-shrink-0 ml-2">
            <span class="activity-time text-xs text-gray-500">
              {{ tiempoTranscurrido }}
            </span>
          </div>
        </div>

        <!-- Información adicional según el tipo -->
        <div class="activity-details">
          <!-- Para comportamientos -->
          <div v-if="actividad.tipo === 'comportamiento' && actividad.puntos_aplicados" class="flex items-center space-x-2">
            <span 
              class="activity-points px-2 py-1 rounded-full text-xs font-bold"
              :class="{
                'bg-green-900/50 text-green-300 border border-green-500/30': actividad.puntos_aplicados > 0,
                'bg-red-900/50 text-red-300 border border-red-500/30': actividad.puntos_aplicados < 0,
                'bg-gray-900/50 text-gray-300 border border-gray-500/30': actividad.puntos_aplicados === 0
              }"
            >
              {{ actividad.puntos_aplicados > 0 ? '+' : '' }}{{ actividad.puntos_aplicados }} XP
            </span>
            
            <div class="activity-target text-xs text-gray-400">
              {{ actividad.estudiante_nombre }}
            </div>
          </div>

          <!-- Para nuevas inscripciones -->
          <div v-else-if="actividad.tipo === 'estudiante_inscrito'" class="flex items-center space-x-2">
            <div class="activity-badge bg-blue-900/50 text-blue-300 border border-blue-500/30 px-2 py-1 rounded-full text-xs">
              Nuevo Guardián
            </div>
            <div class="activity-target text-xs text-gray-400">
              {{ actividad.estudiante_nombre }}
            </div>
          </div>

          <!-- Para nuevas clases -->
          <div v-else-if="actividad.tipo === 'nueva_clase'" class="flex items-center space-x-2">
            <div class="activity-badge bg-purple-900/50 text-purple-300 border border-purple-500/30 px-2 py-1 rounded-full text-xs">
              Nueva Operación
            </div>
          </div>

          <!-- Para misiones completadas -->
          <div v-else-if="actividad.tipo === 'mision_completada'" class="flex items-center space-x-2">
            <div class="activity-badge bg-yellow-900/50 text-yellow-300 border border-yellow-500/30 px-2 py-1 rounded-full text-xs">
              Misión Completada
            </div>
            <div class="activity-target text-xs text-gray-400">
              {{ actividad.estudiante_nombre }}
            </div>
          </div>

          <!-- Para subidas de nivel -->
          <div v-else-if="actividad.tipo === 'nivel_subido'" class="flex items-center space-x-2">
            <div class="activity-badge bg-gradient-to-r from-destiny-gold to-yellow-500 text-black px-2 py-1 rounded-full text-xs font-bold">
              ¡Nivel Subido!
            </div>
            <div class="activity-target text-xs text-gray-400">
              {{ actividad.estudiante_nombre }}
            </div>
          </div>
        </div>

        <!-- Clase relacionada -->
        <div class="activity-class mt-2 flex items-center space-x-2">
          <svg class="w-3 h-3 text-gray-500" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
          </svg>
          <span class="text-xs text-gray-500">{{ actividad.clase_nombre }}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'

interface HistorialActividad {
  id: number
  tipo: 'comportamiento' | 'nueva_clase' | 'estudiante_inscrito' | 'mision_completada' | 'nivel_subido'
  descripcion: string
  estudiante_nombre?: string
  clase_nombre: string
  puntos_aplicados?: number
  fecha: string
  icono: string
  color?: string
}

interface Props {
  actividad: HistorialActividad
}

const props = defineProps<Props>()

// Computed properties
const tiempoTranscurrido = computed(() => {
  const ahora = new Date()
  const fechaActividad = new Date(props.actividad.fecha)
  const diferencia = ahora.getTime() - fechaActividad.getTime()

  const minutos = Math.floor(diferencia / (1000 * 60))
  const horas = Math.floor(diferencia / (1000 * 60 * 60))
  const dias = Math.floor(diferencia / (1000 * 60 * 60 * 24))

  if (minutos < 1) return 'Ahora'
  if (minutos < 60) return `${minutos}m`
  if (horas < 24) return `${horas}h`
  if (dias === 1) return 'Ayer'
  return `${dias}d`
})

const defaultColor = computed(() => {
  const colores = {
    comportamiento: '#22c55e',
    nueva_clase: '#3b82f6',
    estudiante_inscrito: '#8b5cf6',
    mision_completada: '#f59e0b',
    nivel_subido: '#eab308'
  }
  return colores[props.actividad.tipo] || '#6b7280'
})

const dotClasses = computed(() => {
  const baseClasses = 'text-white shadow-lg transition-all duration-300'
  const typeClasses = {
    comportamiento: 'ring-2 ring-green-400/50',
    nueva_clase: 'ring-2 ring-blue-400/50',
    estudiante_inscrito: 'ring-2 ring-purple-400/50',
    mision_completada: 'ring-2 ring-yellow-400/50',
    nivel_subido: 'ring-2 ring-yellow-300/50 animate-pulse'
  }
  
  return `${baseClasses} ${typeClasses[props.actividad.tipo] || 'ring-2 ring-gray-400/50'}`
})

// Methods
const formatearTitulo = (): string => {
  const { tipo, estudiante_nombre, puntos_aplicados } = props.actividad
  
  switch (tipo) {
    case 'comportamiento':
      if (puntos_aplicados && puntos_aplicados > 0) {
        return `✨ ${estudiante_nombre} recibió reconocimiento`
      } else if (puntos_aplicados && puntos_aplicados < 0) {
        return `⚠️ Comportamiento correctivo aplicado`
      } else {
        return `📝 Comportamiento registrado`
      }
    
    case 'nueva_clase':
      return '🚀 Nueva operación iniciada'
    
    case 'estudiante_inscrito':
      return `👤 ${estudiante_nombre} se unió como Guardián`
    
    case 'mision_completada':
      return `🏆 ${estudiante_nombre} completó una misión`
    
    case 'nivel_subido':
      return `📈 ${estudiante_nombre} subió de nivel`
    
    default:
      return props.actividad.descripcion
  }
}
</script>

<style scoped>
.timeline-item {
  position: relative;
}

.timeline-marker {
  position: relative;
  z-index: 2;
}

.timeline-dot {
  position: relative;
  z-index: 3;
  border: 2px solid rgba(255, 255, 255, 0.1);
}

.timeline-dot::before {
  content: '';
  position: absolute;
  inset: -4px;
  background: inherit;
  border-radius: 50%;
  opacity: 0.2;
  filter: blur(8px);
  z-index: -1;
}

.activity-card {
  background: linear-gradient(135deg, rgba(31, 41, 55, 0.6), rgba(17, 24, 39, 0.8));
  border: 1px solid rgba(75, 85, 99, 0.3);
  backdrop-filter: blur(8px);
}

.activity-card:hover {
  background: linear-gradient(135deg, rgba(31, 41, 55, 0.8), rgba(17, 24, 39, 0.9));
  border-color: rgba(199, 184, 138, 0.3);
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
}

.activity-title {
  background: linear-gradient(135deg, #ffffff, #e5e7eb);
  background-clip: text;
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

.activity-points {
  font-family: 'Orbitron', monospace;
  text-shadow: 0 0 8px currentColor;
}

.activity-badge {
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

/* Animaciones para diferentes tipos */
.timeline-dot[style*="rgb(34, 197, 94)"] {
  animation: pulse-green 2s infinite;
}

.timeline-dot[style*="rgb(59, 130, 246)"] {
  animation: pulse-blue 2s infinite;
}

.timeline-dot[style*="rgb(139, 92, 246)"] {
  animation: pulse-purple 2s infinite;
}

.timeline-dot[style*="rgb(245, 158, 11)"] {
  animation: pulse-yellow 2s infinite;
}

@keyframes pulse-green {
  0%, 100% {
    box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7);
  }
  50% {
    box-shadow: 0 0 0 8px rgba(34, 197, 94, 0);
  }
}

@keyframes pulse-blue {
  0%, 100% {
    box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.7);
  }
  50% {
    box-shadow: 0 0 0 8px rgba(59, 130, 246, 0);
  }
}

@keyframes pulse-purple {
  0%, 100% {
    box-shadow: 0 0 0 0 rgba(139, 92, 246, 0.7);
  }
  50% {
    box-shadow: 0 0 0 8px rgba(139, 92, 246, 0);
  }
}

@keyframes pulse-yellow {
  0%, 100% {
    box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.7);
  }
  50% {
    box-shadow: 0 0 0 8px rgba(245, 158, 11, 0);
  }
}

/* Responsive */
@media (max-width: 640px) {
  .timeline-dot {
    @apply w-6 h-6 text-xs;
  }
  
  .activity-card {
    @apply p-2;
  }
  
  .activity-title {
    @apply text-xs;
  }
  
  .activity-description {
    @apply text-xs;
  }
}
</style>