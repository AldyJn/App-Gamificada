<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-900 via-blue-900 to-purple-900">
    
    <!-- Header -->
    <header class="bg-black/20 backdrop-blur-lg border-b border-white/10">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
          <div class="flex items-center space-x-4">
            <button @click="volverAlDashboard" class="text-gray-300 hover:text-white transition-colors duration-200">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
              </svg>
            </button>
            <div>
              <h1 class="text-xl font-bold text-white">{{ clase.nombre }}</h1>
              <p class="text-sm text-gray-300">Profesor: {{ clase.docente }}</p>
            </div>
          </div>
          
          <div class="flex items-center space-x-4">
            <div class="text-right">
              <p class="text-sm text-gray-300">Mi Nivel</p>
              <p class="text-lg font-bold text-white">{{ mi_progreso.nivel }}</p>
            </div>
            <div class="text-right">
              <p class="text-sm text-gray-300">XP</p>
              <p class="text-lg font-bold text-yellow-400">{{ mi_progreso.xp.toLocaleString() }}</p>
            </div>
          </div>
        </div>
      </div>
    </header>

    <!-- Contenido Principal -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      
      <!-- Información de la Clase -->
      <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 border border-white/20 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          
          <!-- Detalles de la clase -->
          <div class="md:col-span-2">
            <h2 class="text-2xl font-bold text-white mb-4">Información de la Clase</h2>
            <div v-if="clase.descripcion" class="mb-4">
              <p class="text-gray-300">{{ clase.descripcion }}</p>
            </div>
            
            <!-- Progreso del curso -->
            <div class="mb-4">
              <div class="flex items-center justify-between mb-2">
                <span class="text-gray-300">Progreso del curso</span>
                <span class="text-white font-medium">{{ clase.progreso }}%</span>
              </div>
              <div class="w-full bg-gray-700 rounded-full h-3">
                <div 
                  class="bg-gradient-to-r from-blue-500 to-purple-500 h-3 rounded-full transition-all duration-300"
                  :style="{ width: clase.progreso + '%' }"
                ></div>
              </div>
            </div>

            <!-- Estadísticas -->
            <div class="grid grid-cols-2 gap-4">
              <div class="bg-black/20 rounded-lg p-4">
                <p class="text-gray-300 text-sm">Total Estudiantes</p>
                <p class="text-2xl font-bold text-white">{{ clase.total_estudiantes }}</p>
              </div>
              <div class="bg-black/20 rounded-lg p-4">
                <p class="text-gray-300 text-sm">Mis Actividades</p>
                <p class="text-2xl font-bold text-green-400">{{ mi_progreso.actividades_completadas }}</p>
              </div>
            </div>
          </div>

          <!-- Mi progreso personal -->
          <div>
            <h3 class="text-lg font-semibold text-white mb-4">Mi Progreso</h3>
            <div class="space-y-4">
              
              <!-- Nivel actual -->
              <div class="bg-black/20 rounded-lg p-4 text-center">
                <div class="w-16 h-16 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-full flex items-center justify-center mx-auto mb-2">
                  <span class="text-xl font-bold text-white">{{ mi_progreso.nivel }}</span>
                </div>
                <p class="text-gray-300 text-sm">Nivel Actual</p>
              </div>

              <!-- XP Progress -->
              <div class="bg-black/20 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                  <span class="text-gray-300 text-sm">Experiencia</span>
                  <span class="text-yellow-400 text-sm font-medium">{{ mi_progreso.xp }}</span>
                </div>
                <div class="w-full bg-gray-700 rounded-full h-2">
                  <div class="bg-gradient-to-r from-yellow-500 to-orange-500 h-2 rounded-full" style="width: 65%"></div>
                </div>
                <p class="text-gray-400 text-xs mt-1">Faltan 350 XP para el siguiente nivel</p>
              </div>

              <!-- Actividades completadas -->
              <div class="bg-black/20 rounded-lg p-4">
                <div class="flex items-center justify-between">
                  <span class="text-gray-300 text-sm">Actividades</span>
                  <span class="text-green-400 font-semibold">{{ mi_progreso.actividades_completadas }}/10</span>
                </div>
                <div class="w-full bg-gray-700 rounded-full h-2 mt-2">
                  <div 
                    class="bg-gradient-to-r from-green-500 to-emerald-500 h-2 rounded-full"
                    :style="{ width: (mi_progreso.actividades_completadas / 10) * 100 + '%' }"
                  ></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Secciones principales -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Actividades y Misiones -->
        <div class="lg:col-span-2 space-y-6">
          
          <!-- Actividades Recientes -->
          <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 border border-white/20">
            <div class="flex items-center justify-between mb-6">
              <h3 class="text-xl font-bold text-white">Actividades Recientes</h3>
              <button class="text-blue-400 hover:text-blue-300 text-sm font-medium transition-colors duration-200">
                Ver todas
              </button>
            </div>

            <div class="space-y-4">
              <!-- Placeholder para actividades -->
              <div class="flex items-center space-x-4 p-4 bg-black/20 rounded-lg">
                <div class="w-10 h-10 bg-blue-500/20 rounded-lg flex items-center justify-center">
                  <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                  </svg>
                </div>
                <div class="flex-1">
                  <h4 class="text-white font-medium">Tarea de Matemáticas - Álgebra</h4>
                  <p class="text-gray-300 text-sm">Fecha límite: 15 de Julio</p>
                </div>
                <span class="bg-yellow-500/20 text-yellow-400 px-3 py-1 rounded-full text-sm font-medium">
                  Pendiente
                </span>
              </div>

              <div class="flex items-center space-x-4 p-4 bg-black/20 rounded-lg">
                <div class="w-10 h-10 bg-green-500/20 rounded-lg flex items-center justify-center">
                  <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                </div>
                <div class="flex-1">
                  <h4 class="text-white font-medium">Quiz - Funciones Lineales</h4>
                  <p class="text-gray-300 text-sm">Completado el 10 de Julio</p>
                </div>
                <span class="bg-green-500/20 text-green-400 px-3 py-1 rounded-full text-sm font-medium">
                  Completado
                </span>
              </div>

              <div class="flex items-center space-x-4 p-4 bg-black/20 rounded-lg">
                <div class="w-10 h-10 bg-purple-500/20 rounded-lg flex items-center justify-center">
                  <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                  </svg>
                </div>
                <div class="flex-1">
                  <h4 class="text-white font-medium">Proyecto Grupal - Estadística</h4>
                  <p class="text-gray-300 text-sm">Fecha límite: 20 de Julio</p>
                </div>
                <span class="bg-blue-500/20 text-blue-400 px-3 py-1 rounded-full text-sm font-medium">
                  En progreso
                </span>
              </div>
            </div>
          </div>

          <!-- Logros y Badges -->
          <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 border border-white/20">
            <h3 class="text-xl font-bold text-white mb-6">Mis Logros</h3>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
              <!-- Badges placeholder -->
              <div class="text-center p-4 bg-black/20 rounded-lg">
                <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-full flex items-center justify-center mx-auto mb-2">
                  <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                  </svg>
                </div>
                <p class="text-white text-xs font-medium">Primera Tarea</p>
              </div>

              <div class="text-center p-4 bg-black/20 rounded-lg opacity-50">
                <div class="w-12 h-12 bg-gray-600 rounded-full flex items-center justify-center mx-auto mb-2">
                  <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                  </svg>
                </div>
                <p class="text-gray-400 text-xs">Colaborador</p>
              </div>

              <div class="text-center p-4 bg-black/20 rounded-lg opacity-50">
                <div class="w-12 h-12 bg-gray-600 rounded-full flex items-center justify-center mx-auto mb-2">
                  <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                  </svg>
                </div>
                <p class="text-gray-400 text-xs">Velocista</p>
              </div>

              <div class="text-center p-4 bg-black/20 rounded-lg opacity-50">
                <div class="w-12 h-12 bg-gray-600 rounded-full flex items-center justify-center mx-auto mb-2">
                  <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                  </svg>
                </div>
                <p class="text-gray-400 text-xs">Innovador</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
          
          <!-- Compañeros de Clase -->
          <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 border border-white/20">
            <h3 class="text-lg font-bold text-white mb-4">Compañeros de Clase</h3>
            
            <div class="space-y-3">
              <div 
                v-for="companero in companeros.slice(0, 5)" 
                :key="companero.id"
                class="flex items-center space-x-3"
              >
                <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center">
                  <span class="text-xs font-semibold text-white">
                    {{ companero.nombre.split(' ').map(n => n.charAt(0)).join('').substring(0, 2) }}
                  </span>
                </div>
                <span class="text-gray-300 text-sm">{{ companero.nombre }}</span>
              </div>
              
              <div v-if="companeros.length > 5" class="text-center pt-2">
                <button class="text-blue-400 hover:text-blue-300 text-sm font-medium transition-colors duration-200">
                  Ver todos ({{ companeros.length - 5 }} más)
                </button>
              </div>
              
              <div v-if="companeros.length === 0" class="text-center py-4">
                <p class="text-gray-400 text-sm">Aún no hay otros estudiantes en esta clase</p>
              </div>
            </div>
          </div>

          <!-- Recursos Rápidos -->
          <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 border border-white/20">
            <h3 class="text-lg font-bold text-white mb-4">Recursos</h3>
            
            <div class="space-y-3">
              <button class="w-full flex items-center space-x-3 p-3 bg-black/20 rounded-lg hover:bg-black/30 transition-colors duration-200">
                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <span class="text-white text-sm">Material de Estudio</span>
              </button>

              <button class="w-full flex items-center space-x-3 p-3 bg-black/20 rounded-lg hover:bg-black/30 transition-colors duration-200">
                <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
                <span class="text-white text-sm">Mis Tareas</span>
              </button>

              <button class="w-full flex items-center space-x-3 p-3 bg-black/20 rounded-lg hover:bg-black/30 transition-colors duration-200">
                <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                </svg>
                <span class="text-white text-sm">Ranking</span>
              </button>

              <button class="w-full flex items-center space-x-3 p-3 bg-black/20 rounded-lg hover:bg-black/30 transition-colors duration-200">
                <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                <span class="text-white text-sm">Foro de Clase</span>
              </button>
            </div>
          </div>

          <!-- Próximas Fechas -->
          <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 border border-white/20">
            <h3 class="text-lg font-bold text-white mb-4">Próximas Fechas</h3>
            
            <div class="space-y-3">
              <div class="flex items-start space-x-3">
                <div class="w-2 h-2 bg-red-500 rounded-full mt-2"></div>
                <div>
                  <p class="text-white text-sm font-medium">Entrega Álgebra</p>
                  <p class="text-gray-400 text-xs">15 de Julio - 2 días</p>
                </div>
              </div>

              <div class="flex items-start space-x-3">
                <div class="w-2 h-2 bg-yellow-500 rounded-full mt-2"></div>
                <div>
                  <p class="text-white text-sm font-medium">Examen Parcial</p>
                  <p class="text-gray-400 text-xs">22 de Julio - 9 días</p>
                </div>
              </div>

              <div class="flex items-start space-x-3">
                <div class="w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                <div>
                  <p class="text-white text-sm font-medium">Proyecto Final</p>
                  <p class="text-gray-400 text-xs">30 de Julio - 17 días</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>
<script setup>
import { router } from '@inertiajs/vue3'

// Props
const props = defineProps({
  clase: Object,
  companeros: Array,
  mi_progreso: Object
})

// Métodos
const volverAlDashboard = () => {
  router.get('/estudiante/dashboard')
}
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>