<template>
  <button 
    class="quick-action-btn group"
    @click="$emit('click')"
    :disabled="disabled"
    :class="{ 'opacity-50 cursor-not-allowed': disabled }"
    :data-variant="variant"
  >
    <!-- Background glow effect -->
    <div class="action-glow absolute inset-0 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
    
    <!-- Content -->
    <div class="relative z-10 flex flex-col items-center justify-center p-4">
      <!-- Icon -->
      <div class="action-icon mb-3 text-3xl group-hover:scale-110 transition-transform duration-300">
        {{ icon }}
      </div>
      
      <!-- Label -->
      <div class="action-label text-sm font-semibold text-white mb-1 group-hover:text-destiny-gold transition-colors">
        {{ label }}
      </div>
      
      <!-- Description -->
      <div class="action-description text-xs text-gray-400 text-center leading-tight">
        {{ description }}
      </div>
      
      <!-- Badge (opcional) -->
      <div 
        v-if="badge"
        class="action-badge absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-6 h-6 flex items-center justify-center font-bold"
      >
        {{ badge }}
      </div>

      <!-- Loading state -->
      <div 
        v-if="loading" 
        class="absolute inset-0 bg-black/50 rounded-xl flex items-center justify-center backdrop-blur-sm"
      >
        <div class="loading-spinner w-6 h-6 border-2 border-destiny-gold border-t-transparent rounded-full animate-spin"></div>
      </div>
    </div>

    <!-- Animated border -->
    <div class="action-border absolute inset-0 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
  </button>
</template>

<script setup lang="ts">
interface Props {
  icon: string
  label: string
  description: string
  disabled?: boolean
  loading?: boolean
  badge?: string | number
  variant?: 'default' | 'primary' | 'danger' | 'success'
}

const props = withDefaults(defineProps<Props>(), {
  disabled: false,
  loading: false,
  variant: 'default'
})

const emit = defineEmits<{
  click: []
}>()
</script>

<style scoped>
.quick-action-btn {
  @apply relative bg-gradient-to-br from-gray-800/60 to-gray-900/80;
  @apply backdrop-blur-lg border border-gray-700/50 rounded-xl;
  @apply transition-all duration-300 active:scale-95;
  @apply hover:scale-105 hover:shadow-2xl;
  @apply min-h-[120px] w-full;
  position: relative;
  overflow: hidden;
}

.quick-action-btn:not(:disabled):hover {
  @apply border-destiny-gold/50 shadow-2xl;
  box-shadow: 
    0 10px 40px rgba(0, 0, 0, 0.3),
    0 0 30px rgba(199, 184, 138, 0.2);
}

.action-glow {
  background: linear-gradient(135deg, rgba(199, 184, 138, 0.1), rgba(110, 193, 228, 0.1));
}

.action-icon {
  filter: drop-shadow(0 0 8px currentColor);
}

.action-border {
  background: linear-gradient(135deg, rgba(199, 184, 138, 0.4), rgba(110, 193, 228, 0.4));
  mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
  mask-composite: xor;
  -webkit-mask-composite: xor;
  padding: 1px;
}

.action-badge {
  animation: pulse-badge 2s infinite;
  box-shadow: 0 0 10px rgba(239, 68, 68, 0.5);
}

@keyframes pulse-badge {
  0%, 100% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.1);
  }
}

/* Variantes de color */
.quick-action-btn[data-variant="primary"] .action-glow {
  background: linear-gradient(135deg, rgba(59, 130, 246, 0.2), rgba(147, 197, 253, 0.2));
}

.quick-action-btn[data-variant="danger"] .action-glow {
  background: linear-gradient(135deg, rgba(239, 68, 68, 0.2), rgba(252, 165, 165, 0.2));
}

.quick-action-btn[data-variant="success"] .action-glow {
  background: linear-gradient(135deg, rgba(34, 197, 94, 0.2), rgba(134, 239, 172, 0.2));
}

/* Efectos especiales para iconos específicos */
.action-icon:has([data-icon="🚀"]) {
  animation: rocket-float 3s ease-in-out infinite;
}

.action-icon:has([data-icon="🎲"]) {
  animation: dice-spin 4s ease-in-out infinite;
}

.action-icon:has([data-icon="⭐"]) {
  animation: star-twinkle 2s ease-in-out infinite;
}

.action-icon:has([data-icon="📊"]) {
  animation: chart-pulse 2.5s ease-in-out infinite;
}

@keyframes rocket-float {
  0%, 100% {
    transform: translateY(0px) rotate(0deg);
  }
  50% {
    transform: translateY(-10px) rotate(5deg);
  }
}

@keyframes dice-spin {
  0%, 100% {
    transform: rotate(0deg);
  }
  25% {
    transform: rotate(90deg);
  }
  50% {
    transform: rotate(180deg);
  }
  75% {
    transform: rotate(270deg);
  }
}

@keyframes star-twinkle {
  0%, 100% {
    filter: drop-shadow(0 0 8px currentColor) brightness(1);
    transform: scale(1);
  }
  50% {
    filter: drop-shadow(0 0 15px currentColor) brightness(1.3);
    transform: scale(1.1);
  }
}

@keyframes chart-pulse {
  0%, 100% {
    transform: scale(1);
    filter: drop-shadow(0 0 8px currentColor);
  }
  50% {
    transform: scale(1.05);
    filter: drop-shadow(0 0 12px currentColor);
  }
}

/* Estados interactivos mejorados */
.quick-action-btn:active:not(:disabled) {
  transform: scale(0.95);
}

.quick-action-btn:focus:not(:disabled) {
  outline: none;
  ring: 2px;
  ring-color: rgba(199, 184, 138, 0.5);
  ring-offset: 2px;
  ring-offset-color: transparent;
}

/* Responsive */
@media (max-width: 640px) {
  .quick-action-btn {
    @apply min-h-[100px];
  }
  
  .action-icon {
    @apply text-2xl mb-2;
  }
  
  .action-label {
    @apply text-xs;
  }
  
  .action-description {
    @apply text-xs;
  }
}

/* Loading spinner */
.loading-spinner {
  border-color: #C7B88A;
  border-top-color: transparent;
}

/* Accessibility improvements */
@media (prefers-reduced-motion: reduce) {
  .quick-action-btn,
  .action-icon,
  .action-glow,
  .action-border {
    animation: none !important;
    transition: none !important;
  }
}

/* Dark mode enhancements */
@media (prefers-color-scheme: dark) {
  .quick-action-btn {
    @apply bg-gradient-to-br from-gray-900/80 to-black/90;
  }
}
</style>