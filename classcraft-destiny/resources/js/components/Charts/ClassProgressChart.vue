<template>
  <div class="chart-container">
    <div class="chart-header mb-4">
      <h4 class="text-sm font-semibold text-destiny-gold mb-1">Progreso de Operaciones</h4>
      <p class="text-xs text-gray-400">Avance del período académico</p>
    </div>

    <div class="chart-content">
      <div v-if="loading" class="loading-container flex items-center justify-center h-48">
        <div class="loading-spinner w-8 h-8 border-2 border-destiny-gold border-t-transparent rounded-full animate-spin"></div>
      </div>

      <div v-else-if="isEmpty" class="empty-state flex flex-col items-center justify-center h-48 text-center">
        <div class="text-4xl mb-2">📈</div>
        <p class="text-sm text-gray-400">Sin operaciones disponibles</p>
      </div>

      <div v-else class="progress-bars space-y-4">
        <div 
          v-for="(item, index) in data" 
          :key="item.name"
          class="progress-item"
        >
          <!-- Header de la clase -->
          <div class="flex items-center justify-between mb-2">
            <div class="flex items-center space-x-2">
              <div 
                class="w-3 h-3 rounded-full"
                :style="{ backgroundColor: getClassColor(index) }"
              ></div>
              <span class="text-sm font-medium text-white truncate">{{ item.name }}</span>
            </div>
            <div class="flex items-center space-x-2">
              <span 
                class="text-sm font-bold"
                :class="getProgressTextColor(item.progreso)"
              >
                {{ Math.round(item.progreso) }}%
              </span>
              <div class="status-icon">
                <svg v-if="item.progreso >= 90" class="w-4 h-4 text-green-400" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                </svg>
                <svg v-else-if="item.progreso >= 60" class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
                <svg v-else class="w-4 h-4 text-red-400" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                </svg>
              </div>
            </div>
          </div>

          <!-- Barra de progreso -->
          <div class="progress-bar-container relative">
            <div class="progress-bar-track w-full bg-gray-700 rounded-full h-4 overflow-hidden">
              <!-- Background pattern -->
              <div class="absolute inset-0 opacity-10">
                <div class="hexagon-pattern h-full"></div>
              </div>
              
              <!-- Progress fill -->
              <div 
                class="progress-bar-fill h-full rounded-full transition-all duration-1000 ease-out relative overflow-hidden"
                :style="{ 
                  width: `${animatedProgress[index] || 0}%`,
                  background: getProgressGradient(item.progreso)
                }"
              >
                <!-- Shine effect -->
                <div class="progress-shine absolute inset-0"></div>
                
                <!-- Progress text overlay -->
                <div 
                  v-if="item.progreso > 15"
                  class="absolute inset-0 flex items-center justify-center text-xs font-bold text-white"
                  style="text-shadow: 0 1px 2px rgba(0,0,0,0.5)"
                >
                  {{ Math.round(item.progreso) }}%
                </div>
              </div>

              <!-- Milestone markers -->
              <div class="absolute inset-0 flex items-center">
                <div 
                  v-for="milestone in milestones" 
                  :key="milestone"
                  class="absolute w-0.5 h-full bg-white/30"
                  :style="{ left: `${milestone}%` }"
                ></div>
              </div>
            </div>

            <!-- Progress status badge -->
            <div 
              class="absolute -right-2 top-1/2 transform -translate-y-1/2 px-2 py-1 rounded-full text-xs font-bold"
              :class="getStatusBadgeClasses(item.progreso)"
            >
              {{ getStatusText(item.progreso) }}
            </div>
          </div>

          <!-- Additional info -->
          <div class="flex items-center justify-between mt-2 text-xs text-gray-400">
            <span>{{ getTimeRemaining(item.progreso) }}</span>
            <span>{{ getCompletionPrediction(item.progreso) }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Summary stats -->
    <div class="chart-summary mt-4 p-3 bg-black/20 rounded-lg border border-destiny-gold/20">
      <div class="grid grid-cols-3 gap-4 text-center">
        <div>
          <div class="text-lg font-bold text-green-400">{{ completedCount }}</div>
          <div class="text-xs text-gray-400">Completadas</div>
        </div>
        <div>
          <div class="text-lg font-bold text-yellow-400">{{ inProgressCount }}</div>
          <div class="text-xs text-gray-400">En Progreso</div>
        </div>
        <div>
          <div class="text-lg font-bold text-red-400">{{ behindCount }}</div>
          <div class="text-xs text-gray-400">Atrasadas</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, nextTick, watch } from 'vue'

interface ProgressData {
  name: string
  progreso: number
}

interface Props {
  data: ProgressData[]
  loading?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  loading: false
})

// Estado reactivo
const animatedProgress = ref<number[]>([])
const milestones = [25, 50, 75] // Marcadores de progreso

// Computed properties
const isEmpty = computed(() => !props.data || props.data.length === 0)

const completedCount = computed(() => 
  props.data.filter(item => item.progreso >= 90).length
)

const inProgressCount = computed(() => 
  props.data.filter(item => item.progreso >= 30 && item.progreso < 90).length
)

const behindCount = computed(() => 
  props.data.filter(item => item.progreso < 30).length
)

// Methods
const getClassColor = (index: number): string => {
  const colors = [
    '#22c55e', // green
    '#3b82f6', // blue
    '#f59e0b', // yellow
    '#ef4444', // red
    '#8b5cf6', // purple
    '#06b6d4', // cyan
    '#f97316', // orange
    '#84cc16'  // lime
  ]
  return colors[index % colors.length]
}

const getProgressTextColor = (progreso: number): string => {
  if (progreso >= 80) return 'text-green-400'
  if (progreso >= 60) return 'text-yellow-400'
  if (progreso >= 40) return 'text-orange-400'
  return 'text-red-400'
}

const getProgressGradient = (progreso: number): string => {
  if (progreso >= 80) {
    return 'linear-gradient(90deg, #22c55e 0%, #16a34a 100%)'
  } else if (progreso >= 60) {
    return 'linear-gradient(90deg, #f59e0b 0%, #d97706 100%)'
  } else if (progreso >= 40) {
    return 'linear-gradient(90deg, #f97316 0%, #ea580c 100%)'
  } else {
    return 'linear-gradient(90deg, #ef4444 0%, #dc2626 100%)'
  }
}

const getStatusBadgeClasses = (progreso: number): string => {
  if (progreso >= 90) {
    return 'bg-green-500 text-white'
  } else if (progreso >= 60) {
    return 'bg-yellow-500 text-black'
  } else {
    return 'bg-red-500 text-white'
  }
}

const getStatusText = (progreso: number): string => {
  if (progreso >= 90) return 'OK'
  if (progreso >= 60) return '⚠'
  return '⚠'
}

const getTimeRemaining = (progreso: number): string => {
  // Simulación del tiempo restante basado en el progreso
  const today = new Date()
  const endOfYear = new Date(today.getFullYear(), 11, 31)
  const daysRemaining = Math.ceil((endOfYear.getTime() - today.getTime()) / (1000 * 60 * 60 * 24))
  
  if (progreso >= 90) {
    return '✅ Completada'
  } else {
    const expectedDays = Math.round(daysRemaining * (100 - progreso) / 100)
    return `~${expectedDays} días restantes`
  }
}

const getCompletionPrediction = (progreso: number): string => {
  if (progreso >= 90) return 'Finalizada'
  if (progreso >= 75) return 'A tiempo'
  if (progreso >= 50) return 'En riesgo'
  return 'Atrasada'
}

// Animation
const animateProgress = () => {
  if (isEmpty.value) return

  const duration = 1500 // ms
  const startTime = Date.now()
  
  const animate = () => {
    const elapsed = Date.now() - startTime
    const progress = Math.min(elapsed / duration, 1)
    
    // Easing function (ease out cubic)
    const easeOutCubic = 1 - Math.pow(1 - progress, 3)
    
    animatedProgress.value = props.data.map(item => item.progreso * easeOutCubic)
    
    if (progress < 1) {
      requestAnimationFrame(animate)
    }
  }
  
  animate()
}

// Watchers y lifecycle
watch(() => props.data, () => {
  if (!props.loading) {
    nextTick(() => {
      animateProgress()
    })
  }
}, { deep: true })

watch(() => props.loading, (loading) => {
  if (!loading) {
    nextTick(() => {
      animateProgress()
    })
  }
})

onMounted(() => {
  nextTick(() => {
    if (!props.loading && !isEmpty.value) {
      animateProgress()
    }
  })
})
</script>

<style scoped>
.chart-container {
  @apply w-full h-full;
}

.chart-header {
  @apply text-center;
}

.progress-item {
  @apply relative;
}

.progress-bar-container {
  @apply relative;
}

.progress-bar-track {
  position: relative;
  box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.3);
}

.progress-bar-fill {
  position: relative;
  box-shadow: 
    0 2px 8px rgba(0, 0, 0, 0.3),
    inset 0 1px 0 rgba(255, 255, 255, 0.2);
}

.progress-shine {
  background: linear-gradient(
    90deg,
    transparent 0%,
    rgba(255, 255, 255, 0.3) 50%,
    transparent 100%
  );
  animation: shine 2s ease-in-out infinite;
}

@keyframes shine {
  0% {
    transform: translateX(-100%);
  }
  50%, 100% {
    transform: translateX(100%);
  }
}

.hexagon-pattern {
  background-image: 
    radial-gradient(circle at 25px 25px, rgba(255, 255, 255, 0.1) 2px, transparent 2px),
    radial-gradient(circle at 75px 75px, rgba(255, 255, 255, 0.1) 2px, transparent 2px);
  background-size: 50px 50px;
}

.chart-summary {
  background: linear-gradient(135deg, rgba(0, 0, 0, 0.4), rgba(27, 28, 41, 0.6));
}

.loading-spinner {
  border-color: #C7B88A;
  border-top-color: transparent;
}

.status-icon {
  animation: pulse-icon 2s ease-in-out infinite;
}

@keyframes pulse-icon {
  0%, 100% {
    transform: scale(1);
    opacity: 1;
  }
  50% {
    transform: scale(1.1);
    opacity: 0.8;
  }
}

/* Hover effects */
.progress-item:hover .progress-bar-fill {
  box-shadow: 
    0 4px 12px rgba(0, 0, 0, 0.4),
    inset 0 1px 0 rgba(255, 255, 255, 0.3),
    0 0 20px currentColor;
}

.progress-item:hover .progress-shine {
  animation-duration: 1s;
}

/* Responsive */
@media (max-width: 640px) {
  .progress-bar-track {
    @apply h-3;
  }
  
  .chart-summary {
    @apply grid-cols-1 gap-2;
  }
  
  .progress-item .flex.items-center.justify-between .text-xs {
    @apply hidden;
  }
}
</style>