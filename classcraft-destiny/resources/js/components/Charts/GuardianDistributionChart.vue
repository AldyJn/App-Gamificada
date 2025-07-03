<template>
  <div class="chart-container">
    <canvas 
      ref="chartCanvas" 
      class="chart-canvas"
      :width="canvasWidth"
      :height="canvasHeight"
    ></canvas>
    
    <!-- Legend -->
    <div class="chart-legend mt-4 space-y-2">
      <div 
        v-for="item in data" 
        :key="item.name"
        class="legend-item flex items-center justify-between p-2 rounded-lg bg-gray-800/50 border border-gray-700/50"
      >
        <div class="flex items-center space-x-3">
          <div 
            class="legend-color w-4 h-4 rounded-full"
            :style="{ backgroundColor: item.color }"
          ></div>
          <span class="legend-name text-sm font-medium text-white">{{ item.name }}</span>
        </div>
        <div class="legend-value">
          <span class="text-sm font-bold text-destiny-gold">{{ item.value }}</span>
          <span class="text-xs text-gray-400 ml-1">{{ getPercentage(item.value) }}%</span>
        </div>
      </div>
    </div>

    <!-- Summary -->
    <div class="chart-summary mt-4 p-3 bg-black/30 rounded-lg border border-destiny-gold/20">
      <div class="flex items-center justify-between">
        <span class="text-sm text-gray-400">Total de Guardianes:</span>
        <span class="text-lg font-bold text-destiny-gold">{{ totalGuardianes }}</span>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, nextTick, computed, watch } from 'vue'

interface ChartData {
  name: string
  value: number
  color: string
}

interface Props {
  data: ChartData[]
  width?: number
  height?: number
}

const props = withDefaults(defineProps<Props>(), {
  width: 250,
  height: 250
})

const chartCanvas = ref<HTMLCanvasElement>()
const canvasWidth = computed(() => props.width)
const canvasHeight = computed(() => props.height)

const totalGuardianes = computed(() => {
  return props.data.reduce((sum, item) => sum + item.value, 0)
})

const getPercentage = (value: number): string => {
  if (totalGuardianes.value === 0) return '0'
  return ((value / totalGuardianes.value) * 100).toFixed(1)
}

// Función para dibujar el gráfico de dona
const drawChart = () => {
  if (!chartCanvas.value || props.data.length === 0) return

  const canvas = chartCanvas.value
  const ctx = canvas.getContext('2d')
  if (!ctx) return

  // Limpiar canvas
  ctx.clearRect(0, 0, canvas.width, canvas.height)

  const centerX = canvas.width / 2
  const centerY = canvas.height / 2
  const outerRadius = Math.min(centerX, centerY) - 20
  const innerRadius = outerRadius * 0.6

  let currentAngle = -Math.PI / 2 // Empezar desde arriba

  // Dibujar cada segmento
  props.data.forEach((item, index) => {
    const percentage = item.value / totalGuardianes.value
    const sliceAngle = percentage * 2 * Math.PI

    // Dibujar segmento principal
    ctx.beginPath()
    ctx.arc(centerX, centerY, outerRadius, currentAngle, currentAngle + sliceAngle)
    ctx.arc(centerX, centerY, innerRadius, currentAngle + sliceAngle, currentAngle, true)
    ctx.closePath()
    
    // Gradiente para cada segmento
    const gradient = ctx.createRadialGradient(centerX, centerY, innerRadius, centerX, centerY, outerRadius)
    gradient.addColorStop(0, item.color + '40') // 25% opacity
    gradient.addColorStop(1, item.color)
    
    ctx.fillStyle = gradient
    ctx.fill()
    
    // Borde brillante
    ctx.strokeStyle = item.color
    ctx.lineWidth = 2
    ctx.stroke()

    // Añadir glow effect
    ctx.shadowColor = item.color
    ctx.shadowBlur = 10
    ctx.stroke()
    ctx.shadowBlur = 0

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
  ctx.font = 'bold 24px "Orbitron", monospace'
  ctx.textAlign = 'center'
  ctx.textBaseline = 'middle'
  ctx.fillText(totalGuardianes.value.toString(), centerX, centerY - 8)
  
  ctx.fillStyle = '#6EC1E4'
  ctx.font = '12px "Orbitron", monospace'
  ctx.fillText('GUARDIANES', centerX, centerY + 12)
}

// Animación de entrada
const animateChart = () => {
  if (!chartCanvas.value) return

  let animationFrame = 0
  const totalFrames = 60

  const animate = () => {
    animationFrame++
    const progress = Math.min(animationFrame / totalFrames, 1)
    
    // Easing function (ease out cubic)
    const easeOutCubic = 1 - Math.pow(1 - progress, 3)
    
    // Crear datos animados
    const animatedData = props.data.map(item => ({
      ...item,
      value: item.value * easeOutCubic
    }))

    // Dibujar con datos animados
    drawChartWithData(animatedData)

    if (progress < 1) {
      requestAnimationFrame(animate)
    }
  }

  animate()
}

const drawChartWithData = (data: ChartData[]) => {
  if (!chartCanvas.value || data.length === 0) return

  const canvas = chartCanvas.value
  const ctx = canvas.getContext('2d')
  if (!ctx) return

  ctx.clearRect(0, 0, canvas.width, canvas.height)

  const centerX = canvas.width / 2
  const centerY = canvas.height / 2
  const outerRadius = Math.min(centerX, centerY) - 20
  const innerRadius = outerRadius * 0.6

  const total = data.reduce((sum, item) => sum + item.value, 0)
  let currentAngle = -Math.PI / 2

  data.forEach((item) => {
    if (total === 0) return
    
    const percentage = item.value / total
    const sliceAngle = percentage * 2 * Math.PI

    ctx.beginPath()
    ctx.arc(centerX, centerY, outerRadius, currentAngle, currentAngle + sliceAngle)
    ctx.arc(centerX, centerY, innerRadius, currentAngle + sliceAngle, currentAngle, true)
    ctx.closePath()
    
    const gradient = ctx.createRadialGradient(centerX, centerY, innerRadius, centerX, centerY, outerRadius)
    gradient.addColorStop(0, item.color + '40')
    gradient.addColorStop(1, item.color)
    
    ctx.fillStyle = gradient
    ctx.fill()
    
    ctx.strokeStyle = item.color
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
  ctx.font = 'bold 24px "Orbitron", monospace'
  ctx.textAlign = 'center'
  ctx.textBaseline = 'middle'
  ctx.fillText(displayTotal.toString(), centerX, centerY - 8)
  
  ctx.fillStyle = '#6EC1E4'
  ctx.font = '12px "Orbitron", monospace'
  ctx.fillText('GUARDIANES', centerX, centerY + 12)
}

// Watchers y lifecycle
watch(() => props.data, () => {
  nextTick(() => {
    animateChart()
  })
}, { deep: true })

onMounted(() => {
  nextTick(() => {
    if (props.data.length > 0) {
      animateChart()
    }
  })
})
</script>

<style scoped>
.chart-container {
  @apply w-full h-full;
}

.chart-canvas {
  @apply mx-auto block;
}

.legend-item {
  @apply transition-all duration-300 hover:bg-gray-700/50 hover:border-destiny-gold/30;
}

.legend-color {
  @apply shadow-lg;
  box-shadow: 0 0 8px currentColor;
}

.legend-name {
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.legend-value {
  font-family: 'Orbitron', monospace;
}

.chart-summary {
  background: linear-gradient(135deg, rgba(0, 0, 0, 0.4), rgba(27, 28, 41, 0.6));
}

/* Responsive */
@media (max-width: 640px) {
  .legend-item {
    @apply p-2;
  }
  
  .legend-name,
  .legend-value {
    @apply text-xs;
  }
}</style>