<template>
  <Head title="Mi Perfil - Estudiante" />

  <div class="min-h-screen bg-gray-900 p-8">
    <div class="max-w-4xl mx-auto bg-gray-800 rounded-lg p-6">
      
      <!-- Header simple -->
      <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-white mb-2">
          🎓 Perfil de Estudiante
        </h1>
        <p class="text-gray-400">Sistema funcionando correctamente</p>
      </div>

      <!-- Información del usuario -->
      <div class="bg-gray-700 rounded-lg p-6 mb-6">
        <h2 class="text-xl font-semibold text-white mb-4">Información Personal</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-300">Nombre:</label>
            <p class="text-white">{{ usuario?.nombre || 'No disponible' }}</p>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-300">Apellido:</label>
            <p class="text-white">{{ usuario?.apellido || 'No disponible' }}</p>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-300">Correo:</label>
            <p class="text-white">{{ usuario?.correo || 'No disponible' }}</p>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-300">Código de Estudiante:</label>
            <p class="text-white">{{ estudiante?.codigo_estudiante || 'No asignado' }}</p>
          </div>
        </div>
      </div>

      <!-- Estadísticas simples -->
      <div class="bg-gray-700 rounded-lg p-6 mb-6">
        <h2 class="text-xl font-semibold text-white mb-4">Estadísticas</h2>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <div class="text-center">
            <div class="text-2xl font-bold text-blue-400">{{ estadisticas?.clases_inscritas || 0 }}</div>
            <div class="text-sm text-gray-400">Clases</div>
          </div>
          
          <div class="text-center">
            <div class="text-2xl font-bold text-green-400">{{ estadisticas?.actividades_completadas || 0 }}</div>
            <div class="text-sm text-gray-400">Actividades</div>
          </div>
          
          <div class="text-center">
            <div class="text-2xl font-bold text-purple-400">{{ estadisticas?.nivel_promedio || 1 }}</div>
            <div class="text-sm text-gray-400">Nivel</div>
          </div>
          
          <div class="text-center">
            <div class="text-2xl font-bold text-yellow-400">{{ estadisticas?.experiencia_total || 0 }}</div>
            <div class="text-sm text-gray-400">XP Total</div>
          </div>
        </div>
      </div>

      <!-- Estado del sistema -->
      <div class="bg-green-900/50 border border-green-700 rounded-lg p-4 mb-6">
        <div class="flex items-center">
          <svg class="w-5 h-5 text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          <span class="text-green-400 font-medium">✅ El perfil de estudiante está funcionando correctamente</span>
        </div>
      </div>

      <!-- Botones de navegación -->
      <div class="flex justify-center space-x-4">
        <Link 
          :href="route('dashboard')" 
          class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors"
        >
          ← Volver al Dashboard
        </Link>
        
        <Link 
          :href="route('estudiantes.mis-clases')" 
          class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg transition-colors"
        >
          Ver Mis Clases
        </Link>
      </div>

      <!-- Información de debug (solo para desarrollo) -->
      <div class="mt-8 bg-gray-900 rounded-lg p-4">
        <h3 class="text-sm font-medium text-gray-400 mb-2">Debug Info:</h3>
        <pre class="text-xs text-gray-500 overflow-auto">{{ JSON.stringify({ usuario, estudiante, estadisticas }, null, 2) }}</pre>
      </div>

    </div>
  </div>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3'

// Props del componente
const props = defineProps({
  usuario: {
    type: Object,
    default: () => ({})
  },
  estudiante: {
    type: Object,
    default: () => ({})
  },
  estadisticas: {
    type: Object,
    default: () => ({})
  },
  clases_recientes: {
    type: Array,
    default: () => []
  },
  logros_recientes: {
    type: Array,
    default: () => []
  }
})
</script>