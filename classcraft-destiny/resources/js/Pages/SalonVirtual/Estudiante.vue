<template>
  <Head title="Salón Virtual" />

  <AuthenticatedLayout>
    <div class="salon-container">
      <!-- HUD Superior -->
      <div class="hud-top">
        <div class="clase-info">
          <h2 class="text-xl font-bold text-white">{{ clase.nombre }}</h2>
          <p class="text-sm text-gray-400">Prof. {{ clase.docente.usuario.nombre }}</p>
        </div>
        
        <div class="mi-info">
          <div class="personaje-badge">
            <div class="avatar-circle">
              <span class="text-2xl">{{ getAvatarEmoji(mi_personaje.avatar) }}</span>
            </div>
            <div class="info-text">
              <p class="font-bold text-white">{{ mi_personaje.nombre }}</p>
              <p class="text-xs text-purple-400">{{ mi_personaje.clase_rpg }} • Nivel {{ mi_personaje.nivel }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Área principal del salón -->
      <div class="salon-area">
        <!-- Mi personaje -->
        <div class="mi-personaje-container">
          <div class="personaje-sprite">
            <div class="sprite-body">
              <span class="text-5xl">{{ getAvatarEmoji(mi_personaje.avatar) }}</span>
            </div>
            <div class="nombre-tag">{{ mi_personaje.nombre }}</div>
            <div class="nivel-badge">Lvl {{ mi_personaje.nivel }}</div>
          </div>
        </div>

        <!-- Compañeros de clase -->
        <div class="companeros-grid">
          <div
            v-for="companero in companeros"
            :key="companero.id"
            class="companero-card"
          >
            <div class="companero-avatar">
              <span class="text-3xl">{{ companero.personaje ? getAvatarEmoji(companero.personaje.avatar) : '👤' }}</span>
            </div>
            <div class="companero-info">
              <p class="font-medium text-white text-sm">{{ companero.nombre }}</p>
              <p v-if="companero.personaje" class="text-xs text-gray-400">
                {{ companero.personaje.clase_rpg }} • Lvl {{ companero.personaje.nivel }}
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Panel de actividad -->
      <div class="activity-panel">
        <h3 class="panel-title">
          <i class="mdi mdi-message-text mr-2"></i>
          Actividad de Clase
        </h3>
        <div class="activity-feed">
          <p class="text-gray-500 text-center py-4">
            Esperando actividad del profesor...
          </p>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { Head } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
  clase: Object,
  mi_personaje: Object,
  companeros: Array
})

const getAvatarEmoji = (avatar) => {
  const avatars = {
    'default': '⚔️',
    'guerrero': '🛡️',
    'mago': '🔮',
    'arquero': '🏹',
    'sanador': '💚',
    'explorador': '🗺️'
  }
  return avatars[avatar] || avatars['default']
}
</script>

<style scoped>
.salon-container {
  @apply min-h-screen bg-gray-900 relative;
  background-image: 
    radial-gradient(ellipse at center, #1a1a2e 0%, #0a0a0a 70%);
}

.hud-top {
  @apply fixed top-0 left-0 right-0 z-20;
  @apply flex justify-between items-center;
  @apply px-8 py-4 bg-black/50 backdrop-blur-sm;
  border-bottom: 1px solid rgba(168, 85, 247, 0.3);
}

.personaje-badge {
  @apply flex items-center space-x-3;
  @apply bg-gray-800/80 rounded-full px-4 py-2;
  border: 1px solid rgba(168, 85, 247, 0.5);
}

.avatar-circle {
  @apply w-10 h-10 rounded-full bg-purple-600/30 flex items-center justify-center;
}

.salon-area {
  @apply pt-24 px-8 pb-32;
  @apply min-h-screen relative;
}

.mi-personaje-container {
  @apply flex justify-center mb-12;
}

.personaje-sprite {
  @apply relative;
}

.sprite-body {
  @apply w-24 h-24 rounded-full bg-purple-600/20 border-2 border-purple-500;
  @apply flex items-center justify-center;
  @apply shadow-lg;
  box-shadow: 0 0 30px rgba(168, 85, 247, 0.5);
}

.nombre-tag {
  @apply absolute -bottom-6 left-1/2 transform -translate-x-1/2;
  @apply bg-gray-800 px-3 py-1 rounded-full text-sm text-white;
  @apply whitespace-nowrap;
}

.nivel-badge {
  @apply absolute -top-2 -right-2;
  @apply bg-purple-600 text-white text-xs px-2 py-1 rounded-full;
  @apply font-bold;
}

.companeros-grid {
  @apply grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4;
  @apply max-w-6xl mx-auto;
}

.companero-card {
  @apply bg-gray-800/50 rounded-lg p-4;
  @apply border border-gray-700 hover:border-purple-500/50;
  @apply transition-all duration-200;
}

.companero-avatar {
  @apply w-16 h-16 rounded-full bg-gray-700/50 mx-auto mb-2;
  @apply flex items-center justify-center;
}

.companero-info {
  @apply text-center;
}

.activity-panel {
  @apply fixed bottom-0 right-0 w-80;
  @apply bg-gray-800/90 backdrop-blur-sm;
  @apply border-l border-t border-gray-700;
  @apply rounded-tl-lg;
}

.panel-title {
  @apply px-4 py-3 bg-gray-900/80 text-purple-400 font-medium;
  @apply flex items-center;
  border-bottom: 1px solid rgba(168, 85, 247, 0.3);
}

.activity-feed {
  @apply h-48 overflow-y-auto p-4;
}
</style>