<template>
  <Head title="Dashboard Estudiante" />
  
  <div class="min-h-screen bg-gradient-to-br from-gray-900 via-purple-900 to-blue-900">
    <!-- Header del Dashboard -->
    <div class="bg-gray-800/50 backdrop-blur-sm border-b border-gray-700">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-3xl font-bold text-white">Dashboard Estudiante</h1>
            <p class="text-gray-300 mt-1">Bienvenido, {{ estudiante.nombre }}</p>
          </div>
          <div class="flex items-center space-x-4">
            <button 
              @click="cargarDatos"
              class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-3 rounded-lg font-medium transition-colors duration-200"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
              </svg>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Contenido principal -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      
      <!-- Mensaje de construcción -->
      <div class="text-center py-16">
        <div class="mb-8">
          <div class="w-32 h-32 bg-purple-500/20 rounded-full mx-auto mb-6 flex items-center justify-center">
            <svg class="w-16 h-16 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 7.172V5L8 4z"/>
            </svg>
          </div>
          <h2 class="text-4xl font-bold text-white mb-4">¡Dashboard en Construcción!</h2>
          <p class="text-xl text-gray-300 mb-8">{{ mensaje }}</p>
        </div>

        <!-- Información del estudiante -->
        <div class="bg-gray-800/50 backdrop-blur-sm rounded-xl border border-gray-700 p-8 max-w-md mx-auto mb-8">
          <h3 class="text-xl font-bold text-white mb-4">Tu Información</h3>
          <div class="space-y-3">
            <div class="flex justify-between items-center">
              <span class="text-gray-400">Nombre:</span>
              <span class="text-white font-medium">{{ estudiante.nombre }}</span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-gray-400">Correo:</span>
              <span class="text-white font-medium">{{ estudiante.correo }}</span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-gray-400">Rol:</span>
              <span class="text-purple-400 font-medium">Estudiante</span>
            </div>
          </div>
        </div>

        <!-- Próximas funcionalidades -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto mb-12">
          <div class="bg-gray-800/30 rounded-lg p-6 border border-gray-700">
            <div class="text-3xl mb-3">📚</div>
            <h3 class="text-lg font-bold text-white mb-2">Mis Clases</h3>
            <p class="text-gray-400 text-sm">Ve todas las clases en las que estás inscrito</p>
          </div>
          <div class="bg-gray-800/30 rounded-lg p-6 border border-gray-700">
            <div class="text-3xl mb-3">🎮</div>
            <h3 class="text-lg font-bold text-white mb-2">Mi Personaje</h3>
            <p class="text-gray-400 text-sm">Personaliza tu avatar y estadísticas</p>
          </div>
          <div class="bg-gray-800/30 rounded-lg p-6 border border-gray-700">
            <div class="text-3xl mb-3">🏆</div>
            <h3 class="text-lg font-bold text-white mb-2">Logros</h3>
            <p class="text-gray-400 text-sm">Consulta tus logros y progreso</p>
          </div>
        </div>

        <!-- Acciones disponibles -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <a 
            href="/estudiantes/perfil" 
            class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200 inline-flex items-center justify-center space-x-2"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <span>Ver Mi Perfil</span>
          </a>
          <button 
            @click="logout"
            class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200 inline-flex items-center justify-center space-x-2"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
            </svg>
            <span>Cerrar Sesión</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Head } from '@inertiajs/vue3'

// Props
const props = defineProps({
  mensaje: {
    type: String,
    default: 'Estamos preparando tu experiencia de aprendizaje'
  },
  estudiante: {
    type: Object,
    default: () => ({})
  }
})

// Métodos
const cargarDatos = () => {
  window.location.reload()
}

const logout = async () => {
  try {
    await fetch('/logout', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    })
    window.location.href = '/login'
  } catch (error) {
    console.error('Error al cerrar sesión:', error)
  }
}
</script>