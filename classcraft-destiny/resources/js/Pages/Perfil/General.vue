<template>
  <Head title="Mi Perfil" />
  
  <div class="min-h-screen bg-gradient-to-br from-gray-900 via-blue-900 to-purple-900">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="text-center">
        <div class="mb-8">
          <div class="w-24 h-24 bg-blue-500/20 rounded-full mx-auto mb-6 flex items-center justify-center">
            <span class="text-3xl font-bold text-white">
              {{ usuario.nombre.charAt(0).toUpperCase() }}
            </span>
          </div>
          <h1 class="text-4xl font-bold text-white mb-4">Mi Perfil</h1>
          <p class="text-xl text-gray-300 mb-8">Gestiona tu información personal</p>
        </div>

        <div class="bg-gray-800/50 backdrop-blur-sm rounded-xl border border-gray-700 p-8 mb-8">
          <h2 class="text-2xl font-bold text-white mb-6">Información Personal</h2>
          
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
              <label class="block text-sm font-medium text-gray-400 mb-2">Tipo de Usuario</label>
              <div class="bg-gray-700/50 rounded-lg p-3">
                <div class="flex items-center space-x-3">
                  <span 
                    :class="{
                      'bg-blue-500': usuario.tipo === 'docente',
                      'bg-purple-500': usuario.tipo === 'estudiante',
                      'bg-gray-500': usuario.tipo === 'indefinido'
                    }"
                    class="inline-block w-3 h-3 rounded-full"
                  ></span>
                  <span class="text-white font-medium capitalize">
                    {{ usuario.tipo === 'indefinido' ? 'No definido' : usuario.tipo }}
                  </span>
                </div>
              </div>
            </div>
            
            <div v-if="usuario.tipo === 'indefinido'" class="bg-yellow-500/10 border border-yellow-500/30 rounded-lg p-4">
              <div class="flex items-start space-x-3">
                <svg class="w-6 h-6 text-yellow-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
                <div>
                  <h3 class="text-yellow-300 font-medium mb-1">Perfil Incompleto</h3>
                  <p class="text-yellow-200 text-sm">
                    Tu tipo de usuario no está definido. Contacta al administrador para completar tu perfil.
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
              class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200 inline-flex items-center justify-center space-x-2"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
              </svg>
              <span>Volver al Dashboard</span>
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
          
          <div v-if="usuario.tipo !== 'indefinido'" class="text-center">
            <p class="text-gray-400 text-sm">
              Para cambios en tu perfil, contacta al administrador del sistema.
            </p>
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
      tipo: 'indefinido'
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