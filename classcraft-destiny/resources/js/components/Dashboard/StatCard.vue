<template>
  <div class="stat-card destiny-glow">
    <div class="stat-card-inner">
      <!-- Icon y Label -->
      <div class="flex items-start justify-between mb-4">
        <div class="stat-icon" :class="iconColor">
          {{ icon }}
        </div>
        <div class="stat-trend" v-if="showTrend">
          <svg 
            v-if="trendDirection === 'up'" 
            class="w-4 h-4 text-green-400" 
            fill="none" 
            stroke="currentColor" 
            viewBox="0 0 24 24"
          >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
          </svg>
          <svg 
            v-else-if="trendDirection === 'down'" 
            class="w-4 h-4 text-red-400" 
            fill="none" 
            stroke="currentColor" 
            viewBox="0 0 24 24"
          >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
          </svg>
          <svg 
            v-else 
            class="w-4 h-4 text-gray-400" 
            fill="none" 
            stroke="currentColor" 
            viewBox="0 0 24 24"
          >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
          </svg>
        </div>
      </div>

      <!-- Valor Principal -->
      <div class="mb-2">
        <div 
          class="stat-value" 
          :class="{ 'animate-counter': animated }"
          ref="counterRef"
        >
          {{ displayValue }}
        </div>
      </div>

      <!-- Label -->
      <div class="stat-label">
        {{ label }}
      </div>

      <!-- Información adicional -->
      <div v-if="showChange && prevValue !== undefined" class="stat-change mt-2">
        <span 
          class="text-xs font-medium"
          :class="{
            'text-green-400': change > 0,
            'text-red-400': change < 0,
            'text-gray-400': change === 0
          }"
        >
          {{ changeText }}
        </span>
      </div>

      <!-- Progress bar si se especifica un máximo -->
      <div v-if="maxValue" class="mt-3">
        <div class="w-full bg-gray-700 rounded-full h-2">
          <div 
            class="destiny-progress-bar h-2 rounded-full transition-all duration-1000"
            :style="{ width: `${progressPercentage}%` }"
          ></div>
        </div>
        <div class="text-xs text-gray-400 mt-1">
          {{ value }} / {{ maxValue }}
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue'

interface Props {
  value: number
  prevValue?: number
  label: string
  icon: string
  iconColor?: string
  animated?: boolean
  showTrend?: boolean
  showChange?: boolean
  maxValue?: number
  format?: 'number' | 'currency' | 'percentage'
  suffix?: string
}

const props = withDefaults(defineProps<Props>(), {
  iconColor: 'text-destiny-gold',
  animated: false,
  showTrend: true,
  showChange: true,
  format: 'number'
})

const counterRef = ref<HTMLElement>()
const currentDisplayValue = ref(0)

// Computed properties
const change = computed(() => {
  if (props.prevValue === undefined) return 0
  return props.value - props.prevValue
})

const trendDirection = computed(() => {
  if (change.value > 0) return 'up'
  if (change.value < 0) return 'down'
  return 'neutral'
})

const changeText = computed(() => {
  if (change.value === 0) return 'Sin cambios'
  const prefix = change.value > 0 ? '+' : ''
  const percentage = props.prevValue ? Math.abs((change.value / props.prevValue) * 100) : 0
  return `${prefix}${change.value} (${percentage.toFixed(1)}%)`
})

const displayValue = computed(() => {
  const value = props.animated ? currentDisplayValue.value : props.value
  
  switch (props.format) {
    case 'currency':
      return new Intl.NumberFormat('es-PE', {
        style: 'currency',
        currency: 'PEN'
      }).format(value)
    
    case 'percentage':
      return `${value.toFixed(1)}%`
    
    default:
      const formatted = new Intl.NumberFormat('es-PE').format(Math.floor(value))
      return props.suffix ? `${formatted} ${props.suffix}` : formatted
  }
})

const progressPercentage = computed(() => {
  if (!props.maxValue) return 0
  return Math.min((props.value / props.maxValue) * 100, 100)
})

// Animación del contador
const animateCounter = (start: number, end: number, duration: number = 1500) => {
  const startTime = Date.now()
  
  const updateCounter = () => {
    const elapsed = Date.now() - startTime
    const progress = Math.min(elapsed / duration, 1)
    
    // Easing function (ease out cubic)
    const easeOutCubic = 1 - Math.pow(1 - progress, 3)
    
    currentDisplayValue.value = start + (end - start) * easeOutCubic
    
    if (progress < 1) {
      requestAnimationFrame(updateCounter)
    } else {
      currentDisplayValue.value = end
    }
  }
  
  requestAnimationFrame(updateCounter)
}

// Watchers
watch(() => props.value, (newValue, oldValue) => {
  if (props.animated) {
    animateCounter(oldValue || 0, newValue)
  }
})

// Lifecycle
onMounted(() => {
  if (props.animated) {
    animateCounter(0, props.value)
  } else {
    currentDisplayValue.value = props.value
  }
})
</script>

<style scoped>
.stat-card {
  @apply bg-gradient-to-br from-gray-800/50 to-gray-900/50 backdrop-blur-lg;
  @apply border border-gray-700/50 rounded-xl p-6;
  @apply transition-all duration-300 hover:scale-105;
  position: relative;
  overflow: hidden;
}

.stat-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 1px;
  background: linear-gradient(90deg, transparent, rgba(199, 184, 138, 0.5), transparent);
}

.stat-card-inner {
  position: relative;
  z-index: 1;
}

.stat-icon {
  @apply text-3xl;
  filter: drop-shadow(0 0 8px currentColor);
}

.stat-value {
  @apply text-3xl font-bold text-white;
  font-family: 'Orbitron', monospace;
  text-shadow: 0 0 10px rgba(255, 255, 255, 0.3);
}

.stat-label {
  @apply text-sm font-medium text-gray-300 uppercase tracking-wider;
}

.stat-trend {
  @apply flex items-center justify-center w-8 h-8 rounded-full;
  @apply bg-gray-700/50 backdrop-blur-sm;
}

.stat-change {
  @apply flex items-center space-x-1;
}

.destiny-glow {
  box-shadow: 
    0 4px 20px rgba(0, 0, 0, 0.3),
    0 0 40px rgba(199, 184, 138, 0.1);
}

.destiny-glow:hover {
  box-shadow: 
    0 8px 25px rgba(0, 0, 0, 0.4),
    0 0 50px rgba(199, 184, 138, 0.2);
}

.destiny-progress-bar {
  background: linear-gradient(90deg, #C7B88A 0%, #6EC1E4 100%);
  box-shadow: 0 0 10px rgba(199, 184, 138, 0.5);
}

.animate-counter {
  transition: none;
}

/* Efectos adicionales para diferentes iconos */
.text-yellow-400 {
  animation: pulse-yellow 2s infinite;
}

.text-blue-400 {
  animation: pulse-blue 2s infinite;
}

.text-green-400 {
  animation: pulse-green 2s infinite;
}

.text-orange-400 {
  animation: pulse-orange 2s infinite;
}

@keyframes pulse-yellow {
  0%, 100% { 
    filter: drop-shadow(0 0 8px currentColor);
  }
  50% { 
    filter: drop-shadow(0 0 15px currentColor);
  }
}

@keyframes pulse-blue {
  0%, 100% { 
    filter: drop-shadow(0 0 8px currentColor);
  }
  50% { 
    filter: drop-shadow(0 0 15px currentColor);
  }
}

@keyframes pulse-green {
  0%, 100% { 
    filter: drop-shadow(0 0 8px currentColor);
  }
  50% { 
    filter: drop-shadow(0 0 15px currentColor);
  }
}

@keyframes pulse-orange {
  0%, 100% { 
    filter: drop-shadow(0 0 8px currentColor);
  }
  50% { 
    filter: drop-shadow(0 0 15px currentColor);
  }
}

/* Responsive */
@media (max-width: 640px) {
  .stat-card {
    @apply p-4;
  }
  
  .stat-value {
    @apply text-2xl;
  }
  
  .stat-icon {
    @apply text-2xl;
  }
}
</style>