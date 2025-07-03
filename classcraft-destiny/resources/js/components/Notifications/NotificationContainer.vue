<template>
  <Teleport to="body">
    <div
      class="notification-container fixed top-4 right-4 z-50 space-y-3 max-w-sm w-full"
      :class="containerClasses"
    >
      <TransitionGroup
        name="notification"
        tag="div"
        class="space-y-3"
      >
        <div
          v-for="notification in notifications"
          :key="notification.id"
          class="notification-item"
          :class="getNotificationClasses(notification.tipo)"
          @mouseenter="pauseTimer(notification.id)"
          @mouseleave="resumeTimer(notification.id)"
        >
          <!-- Notification Content -->
          <div class="flex items-start space-x-3 p-4">
            <!-- Icon -->
            <div 
              class="notification-icon flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold"
              :class="getIconClasses(notification.tipo)"
            >
              <span v-if="notification.icono">{{ notification.icono }}</span>
              <svg v-else class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path v-if="notification.tipo === 'success'" d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                <path v-else-if="notification.tipo === 'error'" d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                <path v-else-if="notification.tipo === 'warning'" d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/>
                <path v-else d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
              </svg>
            </div>

            <!-- Content -->
            <div class="flex-1 min-w-0">
              <div class="flex items-start justify-between">
                <div class="flex-1 min-w-0">
                  <h4 class="notification-title text-sm font-semibold text-white leading-tight">
                    {{ notification.titulo }}
                  </h4>
                  <p class="notification-message text-sm text-gray-300 mt-1 leading-relaxed">
                    {{ notification.mensaje }}
                  </p>
                </div>

                <!-- Close button -->
                <button
                  @click="removeNotification(notification.id)"
                  class="notification-close ml-2 flex-shrink-0 text-gray-400 hover:text-white transition-colors p-1 rounded"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                  </svg>
                </button>
              </div>

              <!-- Action button (si existe) -->
              <div v-if="notification.accion" class="mt-3">
                <button
                  @click="executeAction(notification)"
                  class="notification-action text-xs font-medium px-3 py-1 rounded-lg transition-all duration-300"
                  :class="getActionClasses(notification.tipo)"
                >
                  {{ notification.accion.texto }}
                </button>
              </div>

              <!-- Timestamp -->
              <div v-if="notification.timestamp" class="mt-2">
                <span class="text-xs text-gray-500">
                  {{ formatTime(notification.timestamp) }}
                </span>
              </div>
            </div>
          </div>

          <!-- Progress bar para auto-remove -->
          <div 
            v-if="!notification.persistente && notification.duracion && notification.duracion > 0"
            class="notification-progress absolute bottom-0 left-0 h-1 bg-current opacity-30 transition-all duration-300"
            :style="{ width: `${getProgressWidth(notification.id)}%` }"
          ></div>

          <!-- Glow effect -->
          <div 
            class="notification-glow absolute inset-0 rounded-lg opacity-0 transition-opacity duration-300"
            :class="getGlowClasses(notification.tipo)"
          ></div>
        </div>
      </TransitionGroup>
    </div>
  </Teleport>
</template>

<script setup lang="ts">
import { ref, computed, watch, onMounted, onUnmounted } from 'vue'

interface Notification {
  id: number | string
  tipo: 'success' | 'error' | 'warning' | 'info'
  titulo: string
  mensaje: string
  duracion?: number
  accion?: {
    texto: string
    handler: () => void
    tipo?: 'primary' | 'secondary'
  }
  persistente?: boolean
  icono?: string
  timestamp?: Date
}

interface Props {
  notifications: Notification[]
  position?: 'top-right' | 'top-left' | 'bottom-right' | 'bottom-left' | 'top-center' | 'bottom-center'
  maxVisible?: number
}

const props = withDefaults(defineProps<Props>(), {
  position: 'top-right',
  maxVisible: 5
})

const emit = defineEmits<{
  remove: [id: string | number]
}>()

// Estado reactivo
const pausedTimers = ref(new Set<string | number>())
const progressTimers = ref(new Map<string | number, { start: number, duration: number }>())

// Computed properties
const containerClasses = computed(() => {
  const positions = {
    'top-right': 'top-4 right-4',
    'top-left': 'top-4 left-4',
    'bottom-right': 'bottom-4 right-4',
    'bottom-left': 'bottom-4 left-4',
    'top-center': 'top-4 left-1/2 transform -translate-x-1/2',
    'bottom-center': 'bottom-4 left-1/2 transform -translate-x-1/2'
  }
  return positions[props.position] || positions['top-right']
})

const visibleNotifications = computed(() => {
  return props.notifications.slice(0, props.maxVisible)
})

// Methods
const getNotificationClasses = (tipo: string): string => {
  const baseClasses = 'notification-card relative overflow-hidden rounded-lg shadow-lg border backdrop-blur-sm transition-all duration-300 hover:scale-105'
  
  const typeClasses = {
    success: 'bg-green-900/80 border-green-500/50 hover:shadow-green-500/20',
    error: 'bg-red-900/80 border-red-500/50 hover:shadow-red-500/20',
    warning: 'bg-yellow-900/80 border-yellow-500/50 hover:shadow-yellow-500/20',
    info: 'bg-blue-900/80 border-blue-500/50 hover:shadow-blue-500/20'
  }
  
  return `${baseClasses} ${typeClasses[tipo] || typeClasses.info}`
}

const getIconClasses = (tipo: string): string => {
  const classes = {
    success: 'bg-green-500 text-white',
    error: 'bg-red-500 text-white',
    warning: 'bg-yellow-500 text-black',
    info: 'bg-blue-500 text-white'
  }
  return classes[tipo] || classes.info
}

const getActionClasses = (tipo: string): string => {
  const classes = {
    success: 'bg-green-600 hover:bg-green-700 text-white',
    error: 'bg-red-600 hover:bg-red-700 text-white',
    warning: 'bg-yellow-600 hover:bg-yellow-700 text-black',
    info: 'bg-blue-600 hover:bg-blue-700 text-white'
  }
  return classes[tipo] || classes.info
}

const getGlowClasses = (tipo: string): string => {
  const classes = {
    success: 'bg-green-500/20',
    error: 'bg-red-500/20',
    warning: 'bg-yellow-500/20',
    info: 'bg-blue-500/20'
  }
  return classes[tipo] || classes.info
}

const removeNotification = (id: string | number): void => {
  emit('remove', id)
  pausedTimers.value.delete(id)
  progressTimers.value.delete(id)
}

const pauseTimer = (id: string | number): void => {
  pausedTimers.value.add(id)
}

const resumeTimer = (id: string | number): void => {
  pausedTimers.value.delete(id)
}

const executeAction = (notification: Notification): void => {
  if (notification.accion?.handler) {
    notification.accion.handler()
    removeNotification(notification.id)
  }
}

const formatTime = (timestamp: Date): string => {
  const now = new Date()
  const diff = now.getTime() - timestamp.getTime()
  const minutes = Math.floor(diff / (1000 * 60))
  
  if (minutes < 1) return 'Ahora'
  if (minutes < 60) return `${minutes}m`
  
  const hours = Math.floor(minutes / 60)
  if (hours < 24) return `${hours}h`
  
  const days = Math.floor(hours / 24)
  return `${days}d`
}

const getProgressWidth = (id: string | number): number => {
  const timer = progressTimers.value.get(id)
  if (!timer || pausedTimers.value.has(id)) return 100
  
  const elapsed = Date.now() - timer.start
  const progress = (elapsed / timer.duration) * 100
  return Math.max(0, 100 - progress)
}

// Watchers
watch(() => props.notifications, (newNotifications, oldNotifications) => {
  // Configurar timers para nuevas notificaciones
  newNotifications.forEach(notification => {
    if (!notification.persistente && 
        notification.duracion && 
        notification.duracion > 0 && 
        !progressTimers.value.has(notification.id)) {
      
      progressTimers.value.set(notification.id, {
        start: Date.now(),
        duration: notification.duracion
      })
      
      // Auto-remove después de la duración
      setTimeout(() => {
        if (!pausedTimers.value.has(notification.id)) {
          removeNotification(notification.id)
        }
      }, notification.duracion)
    }
  })
  
  // Limpiar timers de notificaciones removidas
  const currentIds = new Set(newNotifications.map(n => n.id))
  Array.from(progressTimers.value.keys()).forEach(id => {
    if (!currentIds.has(id)) {
      progressTimers.value.delete(id)
      pausedTimers.value.delete(id)
    }
  })
}, { deep: true })

// Update progress bars
let progressInterval: number | null = null

onMounted(() => {
  progressInterval = window.setInterval(() => {
    // Forzar re-render para actualizar barras de progreso
    progressTimers.value = new Map(progressTimers.value)
  }, 100)
})

onUnmounted(() => {
  if (progressInterval) {
    window.clearInterval(progressInterval)
  }
})
</script>

<style scoped>
/* Transition animations */
.notification-enter-active,
.notification-leave-active {
  transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
}

.notification-enter-from {
  opacity: 0;
  transform: translateX(100%) scale(0.9);
}

.notification-leave-to {
  opacity: 0;
  transform: translateX(100%) scale(0.9);
  height: 0;
  margin: 0;
  padding: 0;
}

.notification-move {
  transition: transform 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
}

/* Notification card styling */
.notification-card {
  backdrop-filter: blur(12px);
  box-shadow: 
    0 10px 30px rgba(0, 0, 0, 0.3),
    0 0 0 1px rgba(255, 255, 255, 0.1) inset;
}

.notification-card:hover .notification-glow {
  opacity: 1;
}

.notification-icon {
  box-shadow: 0 0 15px currentColor;
  animation: icon-pulse 2s ease-in-out infinite;
}

@keyframes icon-pulse {
  0%, 100% {
    box-shadow: 0 0 15px currentColor;
  }
  50% {
    box-shadow: 0 0 25px currentColor;
  }
}

.notification-title {
  background: linear-gradient(135deg, #ffffff, #e5e7eb);
  background-clip: text;
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

.notification-close:hover {
  background: rgba(255, 255, 255, 0.1);
}

.notification-action {
  transform: translateY(0);
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

.notification-action:hover {
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
}

.notification-progress {
  background: linear-gradient(90deg, currentColor, transparent);
  animation: progress-shimmer 2s ease-in-out infinite;
}

@keyframes progress-shimmer {
  0%, 100% {
    opacity: 0.3;
  }
  50% {
    opacity: 0.6;
  }
}

/* Destiny-themed enhancements */
.notification-container {
  font-family: 'Inter', system-ui, sans-serif;
}

/* Success notification specific styling */
.notification-card:has(.bg-green-500) {
  border-image: linear-gradient(135deg, #10b981, #065f46) 1;
}

/* Error notification specific styling */
.notification-card:has(.bg-red-500) {
  border-image: linear-gradient(135deg, #ef4444, #7f1d1d) 1;
}

/* Warning notification specific styling */
.notification-card:has(.bg-yellow-500) {
  border-image: linear-gradient(135deg, #f59e0b, #78350f) 1;
}

/* Info notification specific styling */
.notification-card:has(.bg-blue-500) {
  border-image: linear-gradient(135deg, #3b82f6, #1e3a8a) 1;
}

/* Responsive adjustments */
@media (max-width: 640px) {
  .notification-container {
    @apply left-4 right-4 max-w-none;
  }
  
  .notification-item {
    @apply text-sm;
  }
  
  .notification-icon {
    @apply w-6 h-6;
  }
}

/* Accessibility improvements */
@media (prefers-reduced-motion: reduce) {
  .notification-enter-active,
  .notification-leave-active,
  .notification-move,
  .notification-card,
  .notification-action,
  .notification-icon {
    transition: none !important;
    animation: none !important;
  }
}

/* Dark mode enhancements */
@media (prefers-color-scheme: dark) {
  .notification-card {
    backdrop-filter: blur(16px);
  }
}
</style>