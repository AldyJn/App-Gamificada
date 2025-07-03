<template>
  <div class="chart-container">
    <div class="chart-header mb-4">
      <h4 class="text-sm font-semibold text-destiny-gold mb-1">Estado de Guardianes</h4>
      <p class="text-xs text-gray-400">Distribución de salud general</p>
    </div>

    <div class="chart-content">
      <div v-if="loading" class="loading-container flex items-center justify-center h-48">
        <div class="loading-spinner w-8 h-8 border-2 border-destiny-gold border-t-transparent rounded-full animate-spin"></div>
      </div>

      <div v-else class="health-gauges grid grid-cols-1 gap-4">
        <!-- Gauge de Estados Críticos -->
        <div class="gauge-container">
          <div class="gauge-header flex items-center justify-between mb-2">
            <span class="text-sm font-medium text-red-300">Estado Crítico</span>
            <span class="text-lg font-bold text-red-400">{{ data.criticos }}</span>
          </div>
          <div class="gauge-wrapper relative">
            <canvas 
              ref="criticosCanvas" 
              class="gauge-canvas"
              width="120" 
              height="60"
            ></canvas>
            <div class="gauge-label absolute inset-0 flex items-end justify-center pb-2">
              <span class="text-xs text-gray-400">< 30% HP</span>
            </div>
          </div>
        </div>

        <!-- Gauge de Estados de Advertencia -->
        <div class="gauge-container">
          <div class="gauge-header flex items-center justify-between mb-2">
            <span class="text-sm font-medium text-yellow-300">En Advertencia</span>
            <span class="text-lg font-bold text-yellow-400">{{ data.advertencia }}</span>
          </div>
          <div class="gauge-wrapper relative">
            <canvas 
              ref="advertenciaCanvas" 
              class="gauge-canvas"
              width="120" 
              height="60"
            ></canvas>
            <div class="gauge-label absolute inset-0 flex items-end justify-center pb-2">
              <span class="text-xs text-gray-400">30-70% HP</span>
            </div>
          </div>
        </div>

        <!-- Gauge de Estados Saludables -->
        <div class="gauge-container">
          <div class="gauge-header flex items-center justify-between mb-2">
            <span class="text-sm font-medium text-green-300">Saludables</span>
            <span class="text-lg font-bold text-green-400">{{ data.saludables }}</span>
          </div>
          <div class="gauge-wrapper relative">
            <canvas 
              ref="saludablesCanvas" 
              class="gauge-canvas"
              width="120" 
              height="60"
            ></canvas>
            <div class="gauge-label absolute inset-0 flex items-end justify-center pb-2">
              <span class="text-xs text-gray-400">> 70% HP</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Summary -->
    <div class="chart-summary mt-4">
      <!-- Overall health indicator -->
      <div class="overall-health p-3 bg-black/20 rounded-lg border border-destiny-gold/20 mb-3">
        <div class="flex items-center justify-between mb-2">
          <span class="text-sm font-medium text-gray-300">Salud General</span>
          <span 
            class="text-lg font-bold"
            :class="overallHealthColor"
          >
            {{ overallHealthPercentage }}%
          </span>
        </div>
        <div class="w-full bg-gray-700 rounded-full h-3">
          <div 
            class="h-3 rounded-full transition-all duration-1000"
            :class="overallHealthBarColor"
            :style="{ width: `${animatedOverallHealth}%` }"
          ></div>
        </div>
        <div class="mt-2 text-xs text-center" :class="overallHealthTextColor">
          {{ overallHealthStatus }}
        </div>
      </div>

      <!-- Quick stats -->
      <div class="grid grid-cols-3 gap-2 text-center">
        <div class="stat-item p-2 bg-red-900/20 border border-red-500/30 rounded">
          <div class="text-lg font-bold text-red-400">{{ criticalPercentage }}%</div>
          <div class="text-xs text-gray-400">Críticos</div>
        </div>
        <div class="stat-item p-2 bg-yellow-900/20 border border-yellow-500/30 rounded">
          <div class="text-lg font-bold text-yellow-400">{{ warningPercentage }}%</div>
          <div class="text-xs text-gray-400">Advertencia</div>
        </div>
        <div class="stat-item p-2 bg-green-900/20 border border-green-500/30 rounded">
          <div class="text-lg font-bold text-green-400">{{ healthyPercentage }}%</div>
          <div class="text-xs text-gray-400">Saludables</div>
        </div>
      </div>

      <!-- Alerts -->
      <div v-if="data.criticos > 0" class="alert mt-3 p-3 bg-red-900/30 border border-red-500/50 rounded-lg">
        <div class="flex items-center space-x-2">
          <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 24 24">
            <path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/>
          </svg>
          <span class="text-sm text-red-300">
            {{ data.criticos }} Guardián{{ data.criticos > 1 ? 'es' : '' }} 
            necesita{{ data.criticos > 1 ? 'n' : '' }} atención inmediata
          </span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, nextTick, watch } from 'vue'

interface HealthData {
  criticos: number
  advertencia: number
  saludables: number
}

interface Props {
  data: HealthData
  loading?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  loading: false
})

// Refs para los canvas
const criticosCanvas = ref<HTMLCanvasElement>()
const advertenciaCanvas = ref<HTMLCanvasElement>()
const saludablesCanvas = ref<HTMLCanvasElement>()

// Estado reactivo
const animatedOverallHealth = ref(0)

// Computed properties
const totalGuardians = computed(() => 
  props.data.criticos + props.data.advertencia + props.data.saludables
)

const criticalPercentage = computed(() => 
  totalGuardians.value > 0 ? Math.round((props.data.criticos / totalGuardians.value) * 100) : 0
)

const warningPercentage = computed(() => 
  totalGuardians.value > 0 ? Math.round((props.data.advertencia / totalGuardians.value) * 100) : 0
)

const healthyPercentage = computed(() => 
  totalGuardians.value > 0 ? Math.round((props.data.saludables / totalGuardians.value) * 100) : 0
)

const overallHealthPercentage = computed(() => {
  if (totalGuardians.value === 0) return 0
  
  // Weighted average: críticos = 10%, advertencia = 50%, saludables = 90%
  const weightedSum = (props.data.criticos * 10) + (props.data.advertencia * 50) + (props.data.saludables * 90)
  return Math.round(weightedSum / totalGuardians.value)
})

const overallHealthColor = computed(() => {
  const health = overallHealthPercentage.value
  if (health >= 70) return 'text-green-400'
  if (health >= 40) return 'text-yellow-400'
  return 'text-red-400'
})

const overallHealthBarColor = computed(() => {
  const health = overallHealthPercentage.value
  if (health >= 70) return 'bg-green-500'
  if (health >= 40) return 'bg-yellow-500'
  return 'bg-red-500'
})

const overallHealthTextColor = computed(() => {
  const health = overallHealthPercentage.value
  if (health >= 70) return 'text-green-300'
  if (health >= 40) return 'text-yellow-300'
  return 'text-red-300'
})

const overallHealthStatus = computed(() => {
  const health = overallHealthPercentage.value
  if (health >= 80) return 'Excelente Estado'
  if (health >= 60) return 'Buen Estado'
  if (health >= 40) return 'Estado Regular'
  if (health >= 20) return 'Estado Preocupante'
  return 'Estado Crítico'
})

// Methods
const drawGauge = (canvas: HTMLCanvasElement, value: number, maxValue: number, color: string) => {
  const ctx = canvas.getContext('2d')
  if (!ctx) return

  const centerX = canvas.width / 2
  const centerY = canvas.height - 10
  const radius = 40
  const startAngle = Math.PI // 180 grados
  const endAngle = 2 * Math.PI // 360 grados
  const percentage = maxValue > 0 ? value / maxValue : 0
  const currentAngle = startAngle + (endAngle - startAngle) * percentage

  // Limpiar canvas
  ctx.clearRect(0, 0, canvas.width, canvas.height)

  // Dibujar track del gauge
  ctx.beginPath()
  ctx.arc(centerX, centerY, radius, startAngle, endAngle)
  ctx.strokeStyle = 'rgba(75, 85, 99, 0.3)'
  ctx.lineWidth = 8
  ctx.stroke()

  // Dibujar progreso del gauge
  if (percentage > 0) {
    ctx.beginPath()
    ctx.arc(centerX, centerY, radius, startAngle, currentAngle)
    
    // Gradiente para el gauge
    const gradient = ctx.createLinearGradient(centerX - radius, centerY, centerX + radius, centerY)
    gradient.addColorStop(0, color + '80') // 50% opacity
    gradient.addColorStop(1, color)
    
    ctx.strokeStyle = gradient
    ctx.lineWidth = 8
    ctx.lineCap = 'round'
    ctx.stroke()

    // Glow effect
    ctx.shadowColor = color
    ctx.shadowBlur = 15
    ctx.stroke()
    ctx.shadowBlur = 0
  }

  // Dibujar punto final
  if (percentage > 0) {
    const endX = centerX + Math.cos(currentAngle) * radius
    const endY = centerY + Math.sin(currentAngle) * radius
    
    ctx.beginPath()
    ctx.arc(endX, endY, 4, 0, 2 * Math.PI)
    ctx.fillStyle = color
    ctx.fill()
    ctx.strokeStyle = '#ffffff'
    ctx.lineWidth = 2
    ctx.stroke()
  }

  // Dibujar valor en el centro
  ctx.fillStyle = color
  ctx.font = 'bold 16px "Orbitron", monospace'
  ctx.textAlign = 'center'
  ctx.textBaseline = 'middle'
  ctx.fillText(value.toString(), centerX, centerY - 5)
}

const animateGauges = () => {
  if (!criticosCanvas.value || !advertenciaCanvas.value || !saludablesCanvas.value) return

  let animationFrame = 0
  const totalFrames = 60

  const animate = () => {
    animationFrame++
    const progress = Math.min(animationFrame / totalFrames, 1)
    
    // Easing function
    const easeOutCubic = 1 - Math.pow(1 - progress, 3)
    
    // Animate gauges
    const maxValue = Math.max(props.data.criticos, props.data.advertencia, props.data.saludables, 1)
    
    drawGauge(
      criticosCanvas.value!,
      props.data.criticos * easeOutCubic,
      maxValue,
      '#ef4444'
    )
    
    drawGauge(
      advertenciaCanvas.value!,
      props.data.advertencia * easeOutCubic,
      maxValue,
      '#f59e0b'
    )
    
    drawGauge(
      saludablesCanvas.value!,
      props.data.saludables * easeOutCubic,
      maxValue,
      '#22c55e'
    )

    // Animate overall health bar
    animatedOverallHealth.value = overallHealthPercentage.value * easeOutCubic

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
      animateGauges()
    })
  }
}, { deep: true })

watch(() => props.loading, (loading) => {
  if (!loading) {
    nextTick(() => {
      animateGauges()
    })
  }
})

onMounted(() => {
  nextTick(() => {
    if (!props.loading) {
      animateGauges()
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

.gauge-container {
  @apply relative p-3 bg-gray-800/30 rounded-lg border border-gray-700/30;
  @apply hover:bg-gray-700/30 hover:border-gray-600/30 transition-colors;
}

.gauge-wrapper {
  @apply flex justify-center;
}

.gauge-canvas {
  @apply block;
}

.gauge-label {
  pointer-events: none;
}

.stat-item {
  @apply transition-all duration-300 hover:scale-105;
}

.stat-item:hover {
  @apply shadow-lg;
}

.overall-health {
  background: linear-gradient(135deg, rgba(0, 0, 0, 0.4), rgba(27, 28, 41, 0.6));
}

.alert {
  animation: pulse-alert 2s ease-in-out infinite;
}

@keyframes pulse-alert {
  0%, 100% {
    background-color: rgba(127, 29, 29, 0.3);
  }
  50% {
    background-color: rgba(127, 29, 29, 0.5);
  }
}

.loading-spinner {
  border-color: #C7B88A;
  border-top-color: transparent;
}

/* Health status indicators */
.gauge-container:nth-child(1) {
  border-left: 4px solid #ef4444;
}

.gauge-container:nth-child(2) {
  border-left: 4px solid #f59e0b;
}

.gauge-container:nth-child(3) {
  border-left: 4px solid #22c55e;
}

/* Responsive */
@media (max-width: 640px) {
  .gauge-canvas {
    width: 100px;
    height: 50px;
  }
  
  .gauge-container {
    @apply p-2;
  }
  
  .stat-item {
    @apply p-1;
  }
  
  .stat-item .text-lg {
    @apply text-base;
  }
}</style>