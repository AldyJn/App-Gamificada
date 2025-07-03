<template>
  <div class="milestone-card group cursor-pointer" @click="$emit('ver-detalles', clase)">
    <!-- Header con estado y alertas -->
    <div class="flex items-start justify-between mb-4">
      <div class="flex items-center space-x-2">
        <div 
          class="w-3 h-3 rounded-full"
          :class="{
            'bg-green-400 shadow-lg shadow-green-400/50': clase.activa,
            'bg-gray-500': !clase.activa
          }"
        ></div>
        <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">
          {{ clase.activa ? 'Operación Activa' : 'Operación Inactiva' }}
        </span>
      </div>
      
      <div v-if="clase.alertas?.length > 0" class="flex items-center space-x-1">
        <div 
          v-for="alerta in clase.alertas.slice(0, 2)" 
          :key="alerta.mensaje"
          class="text-lg"
          :title="alerta.mensaje"
        >
          {{ alerta.icono }}
        </div>
        <span v-if="clase.alertas.length > 2" class="text-xs text-gray-400">
          +{{ clase.alertas.length - 2 }}
        </span>
      </div>
    </div>

    <!-- Información principal -->
    <div class="mb-4">
      <h3 class="text-lg font-bold text-white mb-1 group-hover:text-destiny-gold transition-colors">
        {{ clase.nombre }}
      </h3>
      <p class="text-sm text-gray-400 line-clamp-2">
        {{ clase.descripcion || 'Sin descripción disponible' }}
      </p>
    </div>

    <!-- Código de invitación -->
    <div class="mb-4 p-3 bg-black/30 rounded-lg border border-destiny-gold/20">
      <div class="flex items-center justify-between">
        <div>
          <div class="text-xs text-gray-400 uppercase tracking-wider mb-1">Código de Acceso</div>
          <div class="text-destiny-gold font-mono font-bold text-lg">
            {{ clase.codigo_invitacion }}
          </div>
        </div>
        <button 
          @click.stop="copiarCodigo"
          class="text-gray-400 hover:text-destiny-cyan transition-colors p-2 rounded"
          title="Copiar código"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
          </svg>
        </button>
      </div>
    </div>

    <!-- Estadísticas -->
    <div class="grid grid-cols-2 gap-4 mb-4">
      <div class="text-center">
        <div class="text-2xl font-bold text-destiny-cyan">{{ clase.estudiantes_count }}</div>
        <div class="text-xs text-gray-400 uppercase">Guardianes</div>
      </div>
      <div class="text-center">
        <div class="text-2xl font-bold" :class="getProgresoColor(clase.progreso)">
          {{ Math.round(clase.progreso) }}%
        </div>
        <div class="text-xs text-gray-400 uppercase">Progreso</div>
      </div>
    </div>

    <!-- Barra de progreso -->
    <div class="mb-4">
      <div class="flex items-center justify-between text-xs text-gray-400 mb-2">
        <span>Avance del Período</span>
        <span>{{ Math.round(clase.progreso) }}%</span>
      </div>
      <div class="w-full bg-gray-700 rounded-full h-2">
        <div 
          class="destiny-progress-bar h-2 rounded-full transition-all duration-1000"
          :style="{ width: `${clase.progreso}%` }"
        ></div>
      </div>
    </div>

    <!-- Alertas críticas -->
    <div v-if="clase.personajes_criticos > 0" class="mb-4 p-2 bg-red-900/20 border border-red-500/30 rounded-lg">
      <div class="flex items-center space-x-2">
        <div class="text-red-400">⚠️</div>
        <span class="text-sm text-red-300">
          {{ clase.personajes_criticos }} Guardián{{ clase.personajes_criticos > 1 ? 'es' : '' }} en estado crítico
        </span>
      </div>
    </div>

    <!-- Información de fechas -->
    <div class="mb-4 text-xs text-gray-500 space-y-1">
      <div class="flex justify-between">
        <span>Inicio:</span>
        <span>{{ formatearFecha(clase.fecha_inicio) }}</span>
      </div>
      <div class="flex justify-between">
        <span>Finalización:</span>
        <span>{{ formatearFecha(clase.fecha_fin) }}</span>
      </div>
    </div>

    <!-- Acciones rápidas -->
    <div class="grid grid-cols-3 gap-2">
      <button
        @click.stop="$emit('aplicar-comportamiento', clase)"
        class="destiny-action-button group/btn"
        title="Aplicar comportamiento"
      >
        <svg class="w-4 h-4 mb-1 group-hover/btn:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24">
          <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
        </svg>
        <span class="text-xs">Comportamiento</span>
      </button>

      <button
        @click.stop="$emit('estudiante-aleatorio', clase)"
        class="destiny-action-button group/btn"
        title="Seleccionar estudiante aleatorio"
      >
        <svg class="w-4 h-4 mb-1 group-hover/btn:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24">
          <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/>
        </svg>
        <span class="text-xs">Aleatorio</span>
      </button>

      <button
        @click.stop="$emit('ver-detalles', clase)"
        class="destiny-action-button group/btn"
        title="Ver detalles de la clase"
      >
        <svg class="w-4 h-4 mb-1 group-hover/btn:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span class="text-xs">Detalles</span>
      </button>
    </div>

    <!-- Efecto de brillo en hover -->
    <div class="absolute inset-0 bg-gradient-to-r from-destiny-gold/0 via-destiny-gold/5 to-destiny-gold/0 opacity-0 group-hover:opacity-100 transition-opacity duration-500 rounded-xl pointer-events-none"></div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'

interface ClaseCard {
  id: number
  nombre: string
  descripcion: string
  codigo_invitacion: string
  estudiantes_count: number
  activa: boolean
  progreso: number
  fecha_inicio: string
  fecha_fin: string
  tema_visual: string
  alertas: AlertaClase[]
  personajes_criticos: number
}

interface AlertaClase {
  tipo: 'warning' | 'error' | 'info' | 'success'
  mensaje: string
  icono: string
}

interface Props {
  clase: ClaseCard
}

const props = defineProps<Props>()

const emit = defineEmits<{
  'aplicar-comportamiento': [clase: ClaseCard]
  'estudiante-aleatorio': [clase: ClaseCard]
  'ver-detalles': [clase: ClaseCard]
}>()

// Computed properties
const getProgresoColor = (progreso: number): string => {
  if (progreso >= 80) return 'text-green-400'
  if (progreso >= 60) return 'text-yellow-400'
  if (progreso >= 40) return 'text-orange-400'
  return 'text-red-400'
}

const formatearFecha = (fecha: string): string => {
  if (!fecha) return 'Sin definir'
  
  try {
    const date = new Date(fecha)
    return date.toLocaleDateString('es-PE', {
      day: '2-digit',
      month: '2-digit',
      year: '2-digit'
    })
  } catch {
    return 'Fecha inválida'
  }
}

// Methods
const copiarCodigo = async (event?: Event): Promise<void> => {
  try {
    await navigator.clipboard.writeText(props.clase.codigo_invitacion)
    
    // Mostrar feedback visual temporal
    const button = event?.target as HTMLElement
    if (button) {
      const originalContent = button.innerHTML
      button.innerHTML = `
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
          <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
        </svg>
      `
      button.classList.add('text-green-400')
      
      setTimeout(() => {
        button.innerHTML = originalContent
        button.classList.remove('text-green-400')
      }, 1500)
    }
    
    // Aquí podrías emitir un evento para mostrar una notificación
    console.log('Código copiado al portapapeles')
  } catch (error) {
    console.error('Error al copiar código:', error)
  }
}
</script>

<style scoped>
.milestone-card {
  @apply relative bg-gradient-to-br from-gray-800/60 to-gray-900/80;
  @apply backdrop-blur-lg border-2 border-transparent bg-clip-padding-box;
  @apply rounded-xl p-6 transition-all duration-300;
  @apply hover:scale-[1.02] hover:shadow-2xl;
  position: relative;
  overflow: hidden;
}

.milestone-card::before {
  content: '';
  position: absolute;
  inset: 0;
  padding: 2px;
  background: linear-gradient(135deg, rgba(199, 184, 138, 0.3), rgba(110, 193, 228, 0.3));
  border-radius: inherit;
  mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
  mask-composite: xor;
  -webkit-mask-composite: xor;
}

.milestone-card:hover::before {
  background: linear-gradient(135deg, rgba(199, 184, 138, 0.6), rgba(110, 193, 228, 0.6));
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

.destiny-action-button {
  @apply flex flex-col items-center justify-center p-3 rounded-lg;
  @apply bg-gradient-to-b from-gray-700/50 to-gray-800/50;
  @apply border border-gray-600/50 text-gray-300;
  @apply transition-all duration-300;
  @apply hover:border-destiny-gold/50 hover:text-destiny-gold;
  @apply hover:bg-gradient-to-b hover:from-destiny-gold/10 hover:to-destiny-gold/5;
  @apply active:scale-95;
}

.destiny-action-button:hover {
  box-shadow: 0 0 15px rgba(199, 184, 138, 0.2);
}

.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

/* Efectos de estado para diferentes tipos de clases */
.milestone-card[data-activa="true"] {
  box-shadow: 0 0 30px rgba(34, 197, 94, 0.1);
}

.milestone-card[data-criticos="true"] {
  box-shadow: 0 0 30px rgba(239, 68, 68, 0.1);
}

/* Responsive adjustments */
@media (max-width: 640px) {
  .milestone-card {
    @apply p-4;
  }
  
  .destiny-action-button {
    @apply p-2;
  }
  
  .destiny-action-button span {
    @apply text-xs;
  }
}
</style>