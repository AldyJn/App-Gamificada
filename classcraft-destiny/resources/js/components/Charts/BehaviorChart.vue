<template>
  <div class="chart-container">
    <div class="chart-header mb-4">
      <h4 class="text-sm font-semibold text-destiny-gold mb-1">Distribución de Comportamientos</h4>
      <p class="text-xs text-gray-400">Últimos 30 días</p>
    </div>

    <div class="chart-content relative">
      <canvas 
        ref="chartCanvas" 
        class="chart-canvas"
        :width="canvasWidth"
        :height="canvasHeight"
      ></canvas>

      <!-- Loading overlay -->
      <div v-if="loading" class="absolute inset-0 bg-black/50 flex items-center justify-center rounded-lg">
        <div class="loading-spinner w-8 h-8 border-2 border-destiny-gold border-t-transparent rounded-full animate-spin"></div>
      </div>

      <!-- Empty state -->
      <div v-if="!loading && isEmpty" class="absolute inset-0 flex flex-col items-center justify-center text-center">
        <div class="text-4xl mb-2">📊</div>
        <p class="text-sm text-gray-400">Sin datos disponibles</p>
      </div>
    </div>

    <!-- Legend -->
    <div class="chart-legend mt-4 grid grid-cols-3 gap-2">
      <div 
        v-for="(item, index) in legendData" 
        :key="item.label"
        class="legend-item flex items-center justify-center p-2 rounded-lg bg-gray-800/30 border border-gray-700/30 hover:bg-gray-700/30 transition-colors"
      >
        <div 
          class="legend-color w-3 h-3 rounded-full mr-2"
          :style="{ backgroundColor: item.color }"
        ></div>
        <div class="flex-1 min-w-0">
          <div class="text-xs font-medium text-white truncate">{{ item.label }}</div>
          <div class="text-xs text-gray-400">{{ item.value }}%</div>
        </div>
      </div>
    </div>

    <!-- Summary stats -->
    <div class="chart-summary mt-4 p-3 bg-black/20 rounded-lg border border-destiny-gold/20">
      <div class="grid grid-cols-2 gap-4 text-center">
        <div>
          <div class="text-lg font-bold text-green-400">{{ totalPositivos }}</div>
          <div class="text-xs text-gray-400">Positivos</div>
        </div>
        <div>
          <div class="text-lg font-bold text-red-400">{{ totalNegativos }}</div>
          <div class="text-xs text-gray-400">Negativos</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, nextTick, watch } from 'vue'

interface ChartData {
  labels: string[]
  datasets: Array<{
    data: number[]
    backgroundColor: string[]
    borderColor?: string[]
    borderWidth?: number
  }>
}

interface Props {
  data: ChartData
  width?: number
  height?: number
  loading?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  width: 200,
  height: 200,
  loading: false
})

const chartCanvas = ref<HTMLCanvasElement>()
const canvasWidth = computed(() => props.width)
const canvasHeight = computed(() => props.height)

const isEmpty = computed(() => {
  return !props.data?.datasets?.[0]?.data?.some(value => value > 0)
})

const legendData = computed(() => {
  if (!props.data?.labels || !props.data?.datasets?.[0]) return []
  
  const dataset = props.data.datasets[0]
  const total = dataset.data.reduce((sum, value) => sum + value, 0)
  
  return props.data.labels.map((label, index) => ({
    label,
    value: total > 0 ? Math.round((dataset.data[index] / total) * 100) : 0,
    color: Array.isArray(dataset.backgroundColor) 
      ? dataset.backgroundColor[index] 
      : dataset.backgroundColor || '#6b7280'
  }))
})

const totalPositivos = computed(() => {
  if (!props.data?.datasets?.[0]) return 0
  const dataset = props.data.datasets[0]
  // Asumiendo que el primer valor es positivos
  return dataset.data[0] || 0
})

const totalNegativos = computed(() => {
  if (!props.data?.datasets?.[0]) return 0
  const dataset = props.data.datasets[0]
  // Asumiendo que el último valor es negativos
  return dataset.data[dataset.data.length - 1] || 0
})

// Función para dibujar el gráfico de dona
const drawChart = () => {
  if (!chartCanvas.value || isEmpty.value) return

  const canvas = chartCanvas.value
  const ctx = canvas.getContext('2d')
  if (!ctx) return

  // Limpiar canvas
  ctx.clearRect(0, 0, canvas.width, canvas.height)

  const centerX = canvas.width / 2
  const centerY = canvas.height / 2
  const outerRadius = Math.min(centerX, centerY) - 20
  const innerRadius = outerRadius * 0.6

  const dataset = props.data.datasets[0]
  const total = dataset.data.reduce((sum, value) => sum + value, 0)
  
  if (total === 0) return

  let currentAngle = -Math.PI / 2 // Empezar desde arriba

  // Dibujar cada segmento
  dataset.data.forEach((value, index) => {
    if (value === 0) return

    const percentage = value / total
    const sliceAngle = percentage * 2 * Math.PI
    
    // Color del segmento
    const color = Array.isArray(dataset.backgroundColor) 
      ? dataset.backgroundColor[index] 
      : dataset.backgroundColor || '#6b7280'

    // Dibujar segmento principal
    ctx.beginPath()
    ctx.arc(centerX, centerY, outerRadius, currentAngle, currentAngle + sliceAngle)
    ctx.arc(centerX, centerY, innerRadius, currentAngle + sliceAngle, currentAngle, true)
    ctx.closePath()
    
    // Gradiente para cada segmento
    const gradient = ctx.createRadialGradient(centerX, centerY, innerRadius, centerX, centerY, outerRadius)
    gradient.addColorStop(0, color + '40') // 25% opacity
    gradient.addColorStop(1, color)
    
    ctx.fillStyle = gradient
    ctx.fill()
    
    // Borde brillante
    ctx.strokeStyle = color
    ctx.lineWidth = 2
    ctx.stroke()

    // Añadir glow effect
    ctx.shadowColor = color
    ctx.shadowBlur = 8
    ctx.stroke()
    ctx.shadowBlur = 0

    // Dibujar etiqueta de porcentaje si el segmento es lo suficientemente grande
    if (percentage > 0.05) { // Solo mostrar si es mayor al 5%
      const labelAngle = currentAngle + sliceAngle / 2
      const labelRadius = innerRadius + (outerRadius - innerRadius) / 2
      const labelX = centerX + Math.cos(labelAngle) * labelRadius
      const labelY = centerY + Math.sin(labelAngle) * labelRadius
      
      ctx.fillStyle = '#ffffff'
      ctx.font = 'bold 12px "Orbitron", monospace'
      ctx.textAlign = 'center'
      ctx.textBaseline = 'middle'
      ctx.fillText(`${Math.round(percentage * 100)}%`, labelX, labelY)
    }

    currentAngle += sliceAngle
  })

  // Dibujar centro con efecto de brillo
  const centerGradient = ctx.createRadialGradient(centerX, centerY, 0, centerX, centerY, innerRadius)
  centerGradient.addColorStop(0, 'rgba(199, 184, 138, 0.8)')
  centerGradient.addColorStop(0.7, 'rgba(199, 184, 138, 0.3)')
  centerGradient.addColorStop(1, 'transparent')

  ctx.beginPath()
  ctx.arc(centerX, centerY, innerRadius, 0, 2 * Math.PI)
  ctx.fillStyle = centerGradient
  ctx.fill()

  // Borde del centro
  ctx.beginPath()
  ctx.arc(centerX, centerY, innerRadius, 0, 2 * Math.PI)
  ctx.strokeStyle = 'rgba(199, 184, 138, 0.6)'
  ctx.lineWidth = 2
  ctx.stroke()

  // Texto central con total
  ctx.fillStyle = '#C7B88A'
  ctx.font = 'bold 18px "Orbitron", monospace'
  ctx.textAlign = 'center'
  ctx.textBaseline = 'middle'
  ctx.fillText(total.toString(), centerX, centerY - 6)
  
  ctx.fillStyle = '#6EC1E4'
  ctx.font = '10px "Orbitron", monospace'
  ctx.fillText('TOTAL', centerX, centerY + 8)
}

// Animación de entrada
const animateChart = () => {
  if (!chartCanvas.value || isEmpty.value) return

  let animationFrame = 0
  const totalFrames = 60

  const animate = () => {
    animationFrame++
    const progress = Math.min(animationFrame / totalFrames, 1)
    
    // Easing function (ease out cubic)
    const easeOutCubic = 1 - Math.pow(1 - progress, 3)
    
    // Crear datos animados
    const animatedData = {
      ...props.data,
      datasets: props.data.datasets.map(dataset => ({
        ...dataset,
        data: dataset.data.map(value => value * easeOutCubic)
      }))
    }

    // Dibujar con datos animados
    drawChartWithData(animatedData)

    if (progress < 1) {
      requestAnimationFrame(animate)
    }
  }

  animate()
}

const drawChartWithData = (data: ChartData) => {
  if (!chartCanvas.value) return

  const canvas = chartCanvas.value
  const ctx = canvas.getContext('2d')
  if (!ctx) return

  ctx.clearRect(0, 0, canvas.width, canvas.height)

  const centerX = canvas.width / 2
  const centerY = canvas.height / 2
  const outerRadius = Math.min(centerX, centerY) - 20
  const innerRadius = outerRadius * 0.6

  const dataset = data.datasets[0]
  const total = dataset.data.reduce((sum, value) => sum + value, 0)
  
  if (total === 0) return

  let currentAngle = -Math.PI / 2

  dataset.data.forEach((value, index) => {
    if (value === 0) return

    const percentage = value / total
    const sliceAngle = percentage * 2 * Math.PI
    
    const color = Array.isArray(dataset.backgroundColor) 
      ? dataset.backgroundColor[index] 
      : dataset.backgroundColor || '#6b7280'

    ctx.beginPath()
    ctx.arc(centerX, centerY, outerRadius, currentAngle, currentAngle + sliceAngle)
    ctx.arc(centerX, centerY, innerRadius, currentAngle + sliceAngle, currentAngle, true)
    ctx.closePath()
    
    const gradient = ctx.createRadialGradient(centerX, centerY, innerRadius, centerX, centerY, outerRadius)
    gradient.addColorStop(0, color + '40')
    gradient.addColorStop(1, color)
    
    ctx.fillStyle = gradient
    ctx.fill()
    
    ctx.strokeStyle = color
    ctx.lineWidth = 2
    ctx.stroke()

    currentAngle += sliceAngle
  })

  // Centro
  const centerGradient = ctx.createRadialGradient(centerX, centerY, 0, centerX, centerY, innerRadius)
  centerGradient.addColorStop(0, 'rgba(199, 184, 138, 0.8)')
  centerGradient.addColorStop(0.7, 'rgba(199, 184, 138, 0.3)')
  centerGradient.addColorStop(1, 'transparent')

  ctx.beginPath()
  ctx.arc(centerX, centerY, innerRadius, 0, 2 * Math.PI)
  ctx.fillStyle = centerGradient
  ctx.fill()

  ctx.beginPath()
  ctx.arc(centerX, centerY, innerRadius, 0, 2 * Math.PI)
  ctx.strokeStyle = 'rgba(199, 184, 138, 0.6)'
  ctx.lineWidth = 2
  ctx.stroke()

  // Texto central
  const displayTotal = Math.round(total)
  ctx.fillStyle = '#C7B88A'
  ctx.font = 'bold 18px "Orbitron", monospace'
  ctx.textAlign = 'center'
  ctx.textBaseline = 'middle'
  ctx.fillText(displayTotal.toString(), centerX, centerY - 6)
  
  ctx.fillStyle = '#6EC1E4'
  ctx.font = '10px "Orbitron", monospace'
  ctx.fillText('TOTAL', centerX, centerY + 8)
}

// Watchers y lifecycle
watch(() => props.data, () => {
  if (!props.loading) {
    nextTick(() => {
      animateChart()
    })
  }
}, { deep: true })

watch(() => props.loading, (loading) => {
  if (!loading) {
    nextTick(() => {
      animateChart()
    })
  }
})

onMounted(() => {
  nextTick(() => {
    if (!props.loading && !isEmpty.value) {
      animateChart()
    }
  })
})
</script>

<style scoped>
.chart-container {
  @apply w-full h-full;
}

.chart-content {
  @apply relative;
}

.chart-canvas {
  @apply mx-auto block;
}

.chart-header {
  @apply text-center;
}

.legend-item {
  @apply transition-all duration-300 cursor-pointer;
}

.legend-item:hover {
  @apply bg-gray-600/30 border-destiny-gold/30;
}

.legend-color {
  @apply shadow-lg flex-shrink-0;
  box-shadow: 0 0 8px currentColor;
}

.chart-summary {
  background: linear-gradient(135deg, rgba(0, 0, 0, 0.4), rgba(27, 28, 41, 0.6));
}

.loading-spinner {
  border-color: #C7B88A;
  border-top-color: transparent;
}

/* Animaciones específicas para diferentes valores */
.legend-item:nth-child(1) .legend-color {
  animation: pulse-success 2s ease-in-out infinite;
}

.legend-item:nth-child(2) .legend-color {
  animation: pulse-neutral 2s ease-in-out infinite;
}

.legend-item:nth-child(3) .legend-color {
  animation: pulse-danger 2s ease-in-out infinite;
}

@keyframes pulse-success {
  0%, 100% {
    box-shadow: 0 0 8px #22c55e;
  }
  50% {
    box-shadow: 0 0 15px #22c55e;
  }
}

@keyframes pulse-neutral {
  0%, 100% {
    box-shadow: 0 0 8px #6b7280;
  }
  50% {
    box-shadow: 0 0 15px #6b7280;
  }
}

@keyframes pulse-danger {
  0%, 100% {
    box-shadow: 0 0 8px #ef4444;
  }
  50% {
    box-shadow: 0 0 15px #ef4444;
  }
}

/* Responsive */
@media (max-width: 640px) {
  .chart-header h4 {
    @apply text-xs;
  }
  
  .chart-header p {
    @apply text-xs;
  }
  
  .legend-item {
    @apply p-1;
  }
  
  .legend-item div {
    @apply text-xs;
  }
}
</style>