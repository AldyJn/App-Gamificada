<template>
  <Head title="Perfil Estudiante" />
  
  <div class="min-h-screen bg-gradient-to-br from-gray-900 via-purple-900 to-blue-900">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="text-center">
        <h1 class="text-4xl font-bold text-white mb-8">Mi Perfil</h1>
        
        <div class="bg-gray-800/50 backdrop-blur-sm rounded-xl border border-gray-700 p-8">
          <div class="w-24 h-24 bg-purple-500/20 rounded-full mx-auto mb-6 flex items-center justify-center">
            <span class="text-3xl font-bold text-white">
              {{ estudiante.nombre.charAt(0).toUpperCase() }}
            </span>
          </div>
          
          <h2 class="text-2xl font-bold text-white mb-4">{{ estudiante.nombre }}</h2>
          <p class="text-gray-300 mb-6">{{ estudiante.correo }}</p>
          
          <div class="bg-purple-500/10 border border-purple-500/30 rounded-lg p-4 mb-6">
            <p class="text-purple-300">{{ mensaje }}</p>
          </div>
          
          <div class="flex justify-center space-x-4">
            <a 
              href="/dashboard" 
              class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200"
            >
              Volver al Dashboard
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Head } from '@inertiajs/vue3'

defineProps({
  estudiante: {
    type: Object,
    default: () => ({})
  },
  mensaje: {
    type: String,
    default: 'Perfil en construcción'
  }
})
</script>