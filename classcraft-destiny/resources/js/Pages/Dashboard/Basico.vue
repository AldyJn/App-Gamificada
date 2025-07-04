<template>
  <Head title="Dashboard" />
  
  <div class="min-h-screen bg-gradient-to-br from-gray-900 via-blue-900 to-purple-900">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="text-center">
        <div class="mb-8">
          <div class="w-24 h-24 bg-blue-500/20 rounded-full mx-auto mb-6 flex items-center justify-center">
            <svg class="w-12 h-12 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
          </div>
          <h1 class="text-4xl font-bold text-white mb-4">Bienvenido al Sistema</h1>
          <p class="text-xl text-gray-300 mb-8">{{ mensaje }}</p>
        </div>

        <div class="bg-gray-800/50 backdrop-blur-sm rounded-xl border border-gray-700 p-8 mb-8">
          <h2 class="text-2xl font-bold text-white mb-4">Información del Usuario</h2>
          <div class="space-y-4">
            <div class="flex justify-between items-center">
              <span class="text-gray-400">Nombre:</span>
              <span class="text-white font-medium">{{ usuario.nombre }}</span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-gray-400">Correo:</span>
              <span class="text-white font-medium">{{ usuario.correo }}</span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-gray-400">Tipo de usuario:</span>
              <span class="text-yellow-400 font-medium capitalize">{{ usuario.tipo }}</span>
            </div>
          </div>
        </div>

        <div class="space-y-4">
          <p class="text-gray-400">Tu perfil está siendo configurado. Mientras tanto, puedes:</p>
          <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a 
              href="/perfil" 
              class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200 inline-flex items-center justify-center space-x-2"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
              <span>Configurar Perfil</span>
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
  mensaje: {
    type: String,
    default: 'Tu cuenta está en proceso de configuración'
  },
  usuario: {
    type: Object,
    default: () => ({})
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
  }
}
</script>