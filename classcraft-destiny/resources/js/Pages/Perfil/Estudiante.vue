<template>
  <Head title="Perfil Estudiante" />
  
  <div class="min-h-screen bg-gradient-to-br from-gray-900 via-purple-900 to-blue-900">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="text-center">
        <div class="mb-8">
          <div class="w-24 h-24 bg-purple-500/20 rounded-full mx-auto mb-6 flex items-center justify-center">
            <svg class="w-12 h-12 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
          </div>
          <h1 class="text-4xl font-bold text-white mb-4">Perfil de Estudiante</h1>
          <p class="text-xl text-gray-300 mb-8">Gestiona tu información como estudiante</p>
        </div>

        <div class="bg-gray-800/50 backdrop-blur-sm rounded-xl border border-gray-700 p-8 mb-8">
          <h2 class="text-2xl font-bold text-white mb-6">Información del Estudiante</h2>
          
          <div class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="text-left">
                <label class="block text-sm font-medium text-gray-400 mb-2">Nombre</label>
                <div class="bg-gray-700/50 rounded-lg p-3">
                  <p class="text-white font-medium">{{ usuario.nombre }}</p>
                </div>
              </div>
              
              <div class="text-left">
                <label class="block text-sm font-medium text-gray-400 mb-2">Correo Electrónico</label>
                <div class="bg-gray-700/50 rounded-lg p-3">
                  <p class="text-white font-medium">{{ usuario.correo }}</p>
                </div>
              </div>
            </div>
            
            <div class="text-left">
              <label class="block text-sm font-medium text-gray-400 mb-2">Rol</label>
              <div class="bg-gray-700/50 rounded-lg p-3">
                <div class="flex items-center space-x-3">
                  <span class="bg-purple-500 inline-block w-3 h-3 rounded-full"></span>
                  <span class="text-white font-medium">Estudiante</span>
                </div>
              </div>
            </div>
            
            <div class="bg-purple-500/10 border border-purple-500/30 rounded-lg p-4">
              <div class="flex items-start space-x-3">
                <svg class="w-6 h-6 text-purple-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                  <h3 class="text-purple-300 font-medium mb-1">Acceso de Estudiante</h3>
                  <p class="text-purple-200 text-sm">
                    Como estudiante, puedes unirte a clases, ver tu progreso y participar en actividades.
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Acciones disponibles -->
        <div class="space-y-4">
          <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a 
              href="/dashboard" 
              class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200 inline-flex items-center justify-center space-x-2"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
              </svg>
              <span>Volver al Dashboard</span>
            </a>
            
            <a 
              href="/estudiantes/mis-clases" 
              class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200 inline-flex items-center justify-center space-x-2"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
              </svg>
              <span>Mis Clases</span>
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
  </div>
</template>

<script setup>
import { Head } from '@inertiajs/vue3'

// Props
const props = defineProps({
  usuario: {
    type: Object,
    default: () => ({
      nombre: '',
      correo: '',
      tipo: 'estudiante'
    })
  }
})

// Métodos
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
    alert('Error al cerrar sesión')
  }
}
</script>