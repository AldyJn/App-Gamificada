<template>
  <div class="min-h-screen character-screen-bg relative overflow-hidden">
    <!-- Background Elements -->
    <div class="absolute inset-0 destiny-grid-pattern opacity-5"></div>
    <div class="absolute top-0 right-0 w-96 h-96 destiny-glow-cyan opacity-20 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 destiny-glow-purple opacity-20 rounded-full blur-3xl"></div>

    <!-- Header -->
    <header class="relative z-20 px-6 py-4 border-b border-cyan-500/20">
      <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
          <div class="flex items-center space-x-2">
            <div class="w-8 h-8 rounded-lg destiny-glow-gold flex items-center justify-center">
              <svg class="w-5 h-5 text-gray-900" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
              </svg>
            </div>
            <div>
              <h1 class="destiny-heading text-xl font-bold">Consola del Guardián</h1>
              <p class="destiny-text-muted text-sm">Sistema ClassCraft-Destiny</p>
            </div>
          </div>
        </div>
        
        <div class="flex items-center space-x-4">
          <!-- Currency Display -->
          <div class="flex items-center space-x-3">
            <div class="flex items-center space-x-1">
              <div class="w-6 h-6 rounded-full bg-yellow-500 flex items-center justify-center">
                <span class="text-xs font-bold text-yellow-900">G</span>
              </div>
              <span class="destiny-text text-sm font-medium">{{ dashboardData?.personaje?.monedas || 0 }}</span>
            </div>
          </div>
          
          <!-- Notifications -->
          <button class="relative p-2 rounded-lg bg-black/20 hover:bg-black/40 transition-colors">
            <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5-5-5 5h5zm0 0v5"/>
            </svg>
            <div v-if="notificacionesPendientes > 0" class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full flex items-center justify-center text-xs font-bold text-white">
              {{ notificacionesPendientes }}
            </div>
          </button>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <div class="relative z-10 px-6 py-8">
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left Panel - Guardian Avatar & Stats -->
        <div class="lg:col-span-4 space-y-6">
          <!-- Guardian Avatar Section -->
          <div class="holo-panel rounded-xl p-6 relative">
            <h2 class="destiny-heading text-lg font-bold mb-6 text-center">Mi Guardián</h2>
            
            <div class="relative mx-auto w-64 h-64 mb-6">
              <!-- Guardian Avatar Ring -->
              <div 
                class="guardian-avatar-ring absolute inset-0 rounded-full p-2"
                :style="{ '--xp-progress': `${progresoXP}%` }"
              >
                <div class="w-full h-full rounded-full overflow-hidden bg-gradient-to-br from-gray-800 to-gray-900 border-2 border-cyan-500/50">
                  <img 
                    v-if="dashboardData?.personaje?.imagen_personalizada"
                    :src="dashboardData.personaje.imagen_personalizada" 
                    :alt="dashboardData.personaje.nombre_personaje"
                    class="w-full h-full object-cover"
                  >
                  <div v-else class="w-full h-full flex items-center justify-center">
                    <svg class="w-24 h-24 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                  </div>
                </div>
              </div>
              
              <!-- Level Display -->
              <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 text-center">
                <div class="destiny-heading text-2xl font-bold text-cyan-400">
                  {{ dashboardData?.personaje?.nivel || 1 }}
                </div>
                <div class="destiny-text-muted text-xs">NIVEL</div>
              </div>
              
              <!-- Power Level -->
              <div class="absolute -bottom-8 left-1/2 transform -translate-x-1/2 text-center">
                <div class="power-level-display">
                  {{ dashboardData?.personaje?.power_level || 750 }}
                </div>
                <div class="destiny-text-muted text-xs">POWER LEVEL</div>
              </div>
            </div>

            <!-- Guardian Info -->
            <div class="text-center space-y-2 mt-8">
              <h3 class="destiny-heading text-xl font-bold">
                {{ dashboardData?.personaje?.nombre_personaje || 'Guardián Anónimo' }}
              </h3>
              <div class="flex items-center justify-center space-x-2">
                <div class="w-6 h-6 rounded-full bg-gradient-to-r from-purple-500 to-blue-500 flex items-center justify-center">
                  <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/>
                  </svg>
                </div>
                <span class="destiny-text text-sm font-medium">
                  {{ dashboardData?.personaje?.clase_rpg?.nombre || 'Titán' }}
                </span>
              </div>
            </div>

            <!-- Resource Bars -->
            <div class="space-y-3 mt-6">
              <!-- Health -->
              <div class="flex items-center space-x-3">
                <span class="destiny-text text-sm w-16">Salud</span>
                <div class="flex-1 relative">
                  <div class="resource-bar health relative">
                    <div 
                      class="h-full bg-gradient-to-r from-red-500 to-red-400 transition-all duration-500"
                      :style="{ width: `${porcentajeSalud}%` }"
                    ></div>
                  </div>
                  <span class="absolute right-0 top-0 text-xs destiny-text-muted">
                    {{ dashboardData?.personaje?.salud_actual || 0 }}/{{ dashboardData?.personaje?.salud_maxima || 100 }}
                  </span>
                </div>
              </div>
              
              <!-- Energy -->
              <div class="flex items-center space-x-3">
                <span class="destiny-text text-sm w-16">Energía</span>
                <div class="flex-1 relative">
                  <div class="resource-bar energy">
                    <div 
                      class="h-full bg-gradient-to-r from-blue-500 to-blue-400 transition-all duration-500"
                      :style="{ width: `${porcentajeEnergia}%` }"
                    ></div>
                  </div>
                  <span class="absolute right-0 top-0 text-xs destiny-text-muted">
                    {{ dashboardData?.personaje?.energia_actual || 0 }}/{{ dashboardData?.personaje?.energia_maxima || 10 }}
                  </span>
                </div>
              </div>
              
              <!-- Light -->
              <div class="flex items-center space-x-3">
                <span class="destiny-text text-sm w-16">Luz</span>
                <div class="flex-1 relative">
                  <div class="resource-bar light">
                    <div 
                      class="h-full bg-gradient-to-r from-yellow-500 to-yellow-400 transition-all duration-500"
                      :style="{ width: `${porcentajeLuz}%` }"
                    ></div>
                  </div>
                  <span class="absolute right-0 top-0 text-xs destiny-text-muted">
                    {{ dashboardData?.personaje?.luz_actual || 0 }}/{{ dashboardData?.personaje?.luz_maxima || 10 }}
                  </span>
                </div>
              </div>
            </div>

            <!-- Stats Hexagon -->
            <div class="mt-6 flex justify-center">
              <div class="grid grid-cols-3 gap-4 text-center">
                <div class="stat-hex">
                  <div class="stat-value">{{ dashboardData?.personaje?.stats_actuales?.fuerza || 0 }}</div>
                  <div class="stat-label">FUE</div>
                </div>
                <div class="stat-hex">
                  <div class="stat-value">{{ dashboardData?.personaje?.stats_actuales?.inteligencia || 0 }}</div>
                  <div class="stat-label">INT</div>
                </div>
                <div class="stat-hex">
                  <div class="stat-value">{{ dashboardData?.personaje?.stats_actuales?.agilidad || 0 }}</div>
                  <div class="stat-label">AGI</div>
                </div>
              </div>
            </div>
          </div>

          <!-- XP Progress Bar -->
          <div class="holo-panel rounded-xl p-6">
            <h3 class="destiny-heading text-lg font-bold mb-4">Progreso hacia el siguiente nivel</h3>
            <div class="relative">
              <div class="w-full h-4 bg-black/40 rounded-full overflow-hidden border border-cyan-500/30">
                <div 
                  class="h-full bg-gradient-to-r from-cyan-400 to-cyan-300 transition-all duration-1000 ease-out xp-bar-glow"
                  :style="{ width: `${progresoXP}%` }"
                ></div>
              </div>
              <div class="flex justify-between items-center mt-2">
                <span class="destiny-text text-sm">
                  {{ dashboardData?.personaje?.experiencia || 0 }} XP
                </span>
                <span class="destiny-text-muted text-sm">
                  {{ dashboardData?.personaje?.experiencia_siguiente || 1000 }} XP
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- Center Panel - Missions & Activities -->
        <div class="lg:col-span-5 space-y-6">
          <!-- Active Missions -->
          <div class="holo-panel rounded-xl p-6">
            <div class="flex items-center justify-between mb-6">
              <h2 class="destiny-heading text-lg font-bold">Misiones Activas</h2>
              <span class="destiny-text-muted text-sm">{{ misionesActivas.length }}/{{ maxMisiones }}</span>
            </div>
            
            <div v-if="misionesActivas.length > 0" class="space-y-4">
              <div 
                v-for="mision in misionesActivas" 
                :key="mision.id"
                class="mission-card rounded-lg p-4 cursor-pointer group"
                :class="[
                  `mission-${mision.tipo_mision}`,
                  { 'mission-completed': mision.progreso.completada }
                ]"
                @click="abrirDetallesMision(mision)"
              >
                <div class="flex items-start justify-between">
                  <div class="flex-1">
                    <div class="flex items-center space-x-2 mb-2">
                      <div class="flex items-center space-x-1">
                        <div class="w-3 h-3 rounded-full" :class="getDificultadColor(mision.dificultad)"></div>
                        <span class="destiny-text text-xs font-medium uppercase tracking-wide">
                          {{ mision.tipo_mision }}
                        </span>
                      </div>
                      <span v-if="mision.poder_requerido" class="destiny-text-muted text-xs">
                        ⚡ {{ mision.poder_requerido }}+
                      </span>
                    </div>
                    
                    <h3 class="destiny-heading text-base font-bold mb-1 group-hover:text-cyan-400 transition-colors">
                      {{ mision.titulo }}
                    </h3>
                    <p class="destiny-text-muted text-sm mb-3 line-clamp-2">
                      {{ mision.descripcion }}
                    </p>
                    
                    <!-- Progress Bar -->
                    <div class="relative mb-2">
                      <div class="w-full h-2 bg-black/40 rounded-full overflow-hidden">
                        <div 
                          class="h-full transition-all duration-500"
                          :class="getMissionProgressColor(mision.tipo_mision)"
                          :style="{ width: `${mision.progreso.porcentaje_progreso}%` }"
                        ></div>
                      </div>
                      <span class="destiny-text text-xs mt-1">
                        {{ mision.progreso.actividades_completadas }}/{{ mision.progreso.total_actividades_requeridas }} actividades
                      </span>
                    </div>
                  </div>
                  
                  <!-- Rewards -->
                  <div class="ml-4 text-right">
                    <div class="space-y-1">
                      <div v-if="mision.experiencia_bonus" class="flex items-center justify-end space-x-1">
                        <span class="destiny-text text-xs text-cyan-400">+{{ mision.experiencia_bonus }}</span>
                        <span class="destiny-text text-xs">XP</span>
                      </div>
                      <div v-if="mision.glimmer_bonus" class="flex items-center justify-end space-x-1">
                        <span class="destiny-text text-xs text-yellow-400">+{{ mision.glimmer_bonus }}</span>
                        <span class="destiny-text text-xs">G</span>
                      </div>
                    </div>
                    
                    <!-- Time remaining -->
                    <div v-if="mision.fecha_fin" class="mt-2">
                      <span class="destiny-text-muted text-xs">
                        {{ formatearTiempoRestante(mision.fecha_fin) }}
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <div v-else class="text-center py-8">
              <svg class="w-16 h-16 mx-auto text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
              </svg>
              <p class="destiny-text-muted">No hay misiones activas</p>
              <p class="destiny-text-muted text-sm">¡Únete a una clase para comenzar tu aventura!</p>
            </div>
          </div>

          <!-- Recent Achievements -->
          <div class="holo-panel rounded-xl p-6">
            <div class="flex items-center justify-between mb-6">
              <h2 class="destiny-heading text-lg font-bold">Logros Recientes</h2>
              <button 
                @click="verTodosLogros"
                class="destiny-text text-sm hover:text-cyan-400 transition-colors"
              >
                Ver todos
              </button>
            </div>
            
            <div v-if="logrosRecientes.length > 0" class="grid grid-cols-2 gap-4">
              <div 
                v-for="logro in logrosRecientes.slice(0, 4)" 
                :key="logro.id"
                class="triumph-badge rounded-lg p-4 text-center cursor-pointer group"
                :class="[`triumph-${logro.rareza}`]"
                @click="mostrarDetallesLogro(logro)"
              >
                <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-black/40 flex items-center justify-center">
                  <img 
                    v-if="logro.imagen_url" 
                    :src="logro.imagen_url" 
                    :alt="logro.nombre"
                    class="w-8 h-8 rounded-full"
                  >
                  <svg v-else class="w-6 h-6 text-current" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                  </svg>
                </div>
                <h4 class="destiny-text text-sm font-medium group-hover:text-current transition-colors line-clamp-2">
                  {{ logro.nombre }}
                </h4>
                <div v-if="logro.nuevo" class="mt-2">
                  <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full">NUEVO</span>
                </div>
              </div>
            </div>
            
            <div v-else class="text-center py-8">
              <svg class="w-16 h-16 mx-auto text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
              </svg>
              <p class="destiny-text-muted">No hay logros recientes</p>
              <p class="destiny-text-muted text-sm">¡Completa actividades para ganar badges!</p>
            </div>
          </div>
        </div>

        <!-- Right Panel - Stats & Quick Actions -->
        <div class="lg:col-span-3 space-y-6">
          <!-- Quick Stats -->
          <div class="holo-panel rounded-xl p-6">
            <h2 class="destiny-heading text-lg font-bold mb-6">Estadísticas</h2>
            <div class="space-y-4">
              <div class="stat-item">
                <div class="flex items-center justify-between">
                  <span class="destiny-text text-sm">Fireteams Activos</span>
                  <span class="destiny-heading text-lg font-bold text-cyan-400">
                    {{ estadisticas?.clases_activas || 0 }}
                  </span>
                </div>
              </div>
              
              <div class="stat-item">
                <div class="flex items-center justify-between">
                  <span class="destiny-text text-sm">Posición Global</span>
                  <span class="destiny-heading text-lg font-bold text-purple-400">
                    #{{ estadisticas?.ranking_global || '---' }}
                  </span>
                </div>
              </div>
              
              <div class="stat-item">
                <div class="flex items-center justify-between">
                  <span class="destiny-text text-sm">Logros Obtenidos</span>
                  <span class="destiny-heading text-lg font-bold text-yellow-400">
                    {{ estadisticas?.logros_obtenidos || 0 }}
                  </span>
                </div>
              </div>
              
              <div class="stat-item">
                <div class="flex items-center justify-between">
                  <span class="destiny-text text-sm">XP Total</span>
                  <span class="destiny-heading text-lg font-bold text-green-400">
                    {{ formatearNumero(estadisticas?.experiencia_total || 0) }}
                  </span>
                </div>
              </div>
            </div>
          </div>

          <!-- Quick Actions -->
          <div class="holo-panel rounded-xl p-6">
            <h2 class="destiny-heading text-lg font-bold mb-6">Acciones Rápidas</h2>
            <div class="space-y-3">
              <button 
                class="w-full p-3 rounded-lg bg-gradient-to-r from-cyan-600 to-cyan-500 hover:from-cyan-500 hover:to-cyan-400 text-white font-medium transition-all duration-200 transform hover:scale-105"
                @click="abrirUnirseClase"
              >
                Unirse a Fireteam
              </button>
              
              <button 
                class="w-full p-3 rounded-lg bg-gradient-to-r from-purple-600 to-purple-500 hover:from-purple-500 hover:to-purple-400 text-white font-medium transition-all duration-200 transform hover:scale-105"
                @click="abrirPersonalizacion"
              >
                Personalizar Guardián
              </button>
              
              <button 
                class="w-full p-3 rounded-lg bg-gradient-to-r from-yellow-600 to-yellow-500 hover:from-yellow-500 hover:to-yellow-400 text-white font-medium transition-all duration-200 transform hover:scale-105"
                @click="verInventario"
              >
                Ver Inventario
              </button>
            </div>
          </div>

          <!-- Recent Activity Feed -->
          <div class="holo-panel rounded-xl p-6">
            <h2 class="destiny-heading text-lg font-bold mb-6">Actividad Reciente</h2>
            <div v-if="actividadReciente.length > 0" class="space-y-3">
              <div 
                v-for="actividad in actividadReciente.slice(0, 5)" 
                :key="actividad.id"
                class="flex items-center space-x-3 p-3 rounded-lg bg-black/20"
              >
                <div class="w-8 h-8 rounded-full flex items-center justify-center" :class="getActivityColor(actividad.tipo)">
                  <svg class="w-4 h-4" :class="getActivityIconColor(actividad.tipo)" fill="currentColor" viewBox="0 0 24 24">
                    <path :d="getActivityIcon(actividad.tipo)"/>
                  </svg>
                </div>
                <div class="flex-1">
                  <div class="destiny-text text-sm font-medium">{{ actividad.titulo }}</div>
                  <div class="destiny-text-muted text-xs">{{ formatearTiempo(actividad.fecha) }}</div>
                </div>
              </div>
            </div>
            
            <div v-else class="text-center py-4">
              <p class="destiny-text-muted text-sm">No hay actividad reciente</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Loading Overlay -->
    <div v-if="loading" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50">
      <div class="destiny-spinner">
        <div class="animate-spin rounded-full h-16 w-16 border-t-2 border-b-2 border-cyan-400"></div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue'

// Declaraciones de tipos globales
declare global {
  interface Window {
    Echo?: {
      channel: (channel: string) => {
        listen: (event: string, callback: (e: any) => void) => any
        stopListening: (event: string) => void
      }
    }
  }
}

// Interfaces
interface PersonajeData {
  id: number
  id_estudiante?: number
  nombre_personaje: string
  nivel: number
  experiencia: number
  experiencia_siguiente: number
  power_level: number
  salud_actual: number
  salud_maxima: number
  energia_actual: number
  energia_maxima: number
  luz_actual: number
  luz_maxima: number
  clase_rpg: {
    id: number
    nombre: string
    icono: string
    descripcion: string
  }
  imagen_personalizada?: string
  gear_equipado: {
    shader?: string
    emblem?: string
  }
  stats_actuales: {
    fuerza: number
    inteligencia: number
    agilidad: number
  }
  monedas: number
}

interface Mision {
  id: number
  titulo: string
  descripcion: string
  tipo_mision: 'principal' | 'secundaria' | 'diaria' | 'semanal'
  dificultad: 'facil' | 'normal' | 'heroico' | 'legendario'
  poder_requerido: number
  experiencia_bonus: number
  glimmer_bonus: number
  fecha_fin: string
  progreso: {
    actividades_completadas: number
    total_actividades_requeridas: number
    porcentaje_progreso: number
    completada: boolean
  }
}

interface Badge {
  id: number
  nombre: string
  descripcion: string
  imagen_url: string
  rareza: 'comun' | 'raro' | 'epico' | 'legendario' | 'exotico'
  fecha_obtencion: string
  nuevo: boolean
}

interface EstudianteStats {
  nivel: number
  power_level: number
  clases_activas: number
  logros_obtenidos: number
  ranking_global: number
  experiencia_total: number
}

interface DashboardData {
  personaje: PersonajeData
  estadisticas: EstudianteStats
  misiones_activas: Mision[]
  logros_recientes: Badge[]
}

// Props
interface Props {
  dashboardData?: DashboardData | null
}

const props = withDefaults(defineProps<Props>(), {
  dashboardData: null
})

// Reactive data
const loading = ref(false)
const notificacionesPendientes = ref(3)
const maxMisiones = ref(5)

// Mock data for development
const misionesActivas = ref<Mision[]>([
  {
    id: 1,
    titulo: "Dominar Álgebra Lineal",
    descripcion: "Completa 5 ejercicios de ecuaciones lineales para dominar los fundamentos algebraicos",
    tipo_mision: "principal",
    dificultad: "normal",
    poder_requerido: 800,
    experiencia_bonus: 500,
    glimmer_bonus: 150,
    fecha_fin: "2025-07-10T23:59:59.000000Z",
    progreso: {
      actividades_completadas: 3,
      total_actividades_requeridas: 5,
      porcentaje_progreso: 60.0,
      completada: false
    }
  },
  {
    id: 2,
    titulo: "Participación Diaria",
    descripcion: "Participa activamente en las clases del día",
    tipo_mision: "diaria",
    dificultad: "facil",
    poder_requerido: 0,
    experiencia_bonus: 100,
    glimmer_bonus: 50,
    fecha_fin: "2025-07-03T23:59:59.000000Z",
    progreso: {
      actividades_completadas: 2,
      total_actividades_requeridas: 3,
      porcentaje_progreso: 66.7,
      completada: false
    }
  }
])

const logrosRecientes = ref<Badge[]>([
  {
    id: 1,
    nombre: "Primera Victoria",
    descripcion: "Completa tu primera actividad académica",
    imagen_url: "/images/badges/first_victory.png",
    rareza: "raro",
    fecha_obtencion: "2025-07-03T10:30:00.000000Z",
    nuevo: true
  },
  {
    id: 2,
    nombre: "Estudiante Constante",
    descripcion: "Asiste 7 días consecutivos",
    imagen_url: "/images/badges/consistent_student.png",
    rareza: "epico",
    fecha_obtencion: "2025-07-02T15:20:00.000000Z",
    nuevo: false
  }
])

const estadisticas = ref<EstudianteStats>({
  nivel: 25,
  power_level: 847,
  clases_activas: 2,
  logros_obtenidos: 12,
  ranking_global: 5,
  experiencia_total: 15750
})

const actividadReciente = ref([
  {
    id: 1,
    titulo: "Subida de Nivel",
    tipo: "nivel",
    fecha: "2025-07-03T10:30:00.000000Z"
  },
  {
    id: 2,
    titulo: "Misión Completada",
    tipo: "mision",
    fecha: "2025-07-03T09:15:00.000000Z"
  },
  {
    id: 3,
    titulo: "Logro Desbloqueado",
    tipo: "logro",
    fecha: "2025-07-02T16:45:00.000000Z"
  }
])

// Computed properties
const progresoXP = computed(() => {
  if (!props.dashboardData?.personaje) return 65
  const { experiencia, experiencia_siguiente } = props.dashboardData.personaje
  const nivelActual = Math.floor(experiencia / 100) * 100
  const xpEnNivel = experiencia - nivelActual
  const xpRequeridoNivel = experiencia_siguiente - nivelActual
  return (xpEnNivel / xpRequeridoNivel) * 100
})

const porcentajeSalud = computed(() => {
  if (!props.dashboardData?.personaje) return 85
  const { salud_actual, salud_maxima } = props.dashboardData.personaje
  return (salud_actual / salud_maxima) * 100
})

const porcentajeEnergia = computed(() => {
  if (!props.dashboardData?.personaje) return 80
  const { energia_actual, energia_maxima } = props.dashboardData.personaje
  return (energia_actual / energia_maxima) * 100
})

const porcentajeLuz = computed(() => {
  if (!props.dashboardData?.personaje) return 90
  const { luz_actual, luz_maxima } = props.dashboardData.personaje
  return (luz_actual / luz_maxima) * 100
})

// Methods
const getDificultadColor = (dificultad: string): string => {
  const colors: Record<string, string> = {
    facil: 'bg-green-500',
    normal: 'bg-blue-500',
    heroico: 'bg-purple-500',
    legendario: 'bg-yellow-500'
  }
  return colors[dificultad] || 'bg-gray-500'
}

const getMissionProgressColor = (tipo: string): string => {
  const colors: Record<string, string> = {
    principal: 'bg-gradient-to-r from-yellow-500 to-yellow-400',
    secundaria: 'bg-gradient-to-r from-cyan-500 to-cyan-400',
    diaria: 'bg-gradient-to-r from-purple-500 to-purple-400',
    semanal: 'bg-gradient-to-r from-green-500 to-green-400'
  }
  return colors[tipo] || 'bg-gradient-to-r from-gray-500 to-gray-400'
}

const getActivityColor = (tipo: string): string => {
  const colors: Record<string, string> = {
    nivel: 'bg-yellow-500',
    mision: 'bg-green-500',
    logro: 'bg-purple-500',
    muerte: 'bg-red-500'
  }
  return colors[tipo] || 'bg-gray-500'
}

const getActivityIconColor = (tipo: string): string => {
  const colors: Record<string, string> = {
    nivel: 'text-yellow-900',
    mision: 'text-green-900',
    logro: 'text-purple-900',
    muerte: 'text-red-900'
  }
  return colors[tipo] || 'text-gray-900'
}

const getActivityIcon = (tipo: string): string => {
  const icons: Record<string, string> = {
    nivel: "M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z",
    mision: "M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z",
    logro: "M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z",
    muerte: "M6 18L18 6M6 6l12 12"
  }
  return icons[tipo] || "M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"
}

const formatearTiempoRestante = (fecha: string): string => {
  const ahora = new Date()
  const fin = new Date(fecha)
  const diferencia = fin.getTime() - ahora.getTime()
  
  if (diferencia <= 0) return "Expirado"
  
  const dias = Math.floor(diferencia / (1000 * 60 * 60 * 24))
  const horas = Math.floor((diferencia % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))
  
  if (dias > 0) return `${dias}d ${horas}h`
  return `${horas}h`
}

const formatearTiempo = (fecha: string): string => {
  const ahora = new Date()
  const pasado = new Date(fecha)
  const diferencia = ahora.getTime() - pasado.getTime()
  
  const minutos = Math.floor(diferencia / (1000 * 60))
  const horas = Math.floor(diferencia / (1000 * 60 * 60))
  const dias = Math.floor(diferencia / (1000 * 60 * 60 * 24))
  
  if (dias > 0) return `Hace ${dias}d`
  if (horas > 0) return `Hace ${horas}h`
  return `Hace ${minutos}m`
}

const formatearNumero = (num: number): string => {
  if (num >= 1000000) return (num / 1000000).toFixed(1) + 'M'
  if (num >= 1000) return (num / 1000).toFixed(1) + 'K'
  return num.toString()
}

// Event handlers
const abrirDetallesMision = (mision: Mision): void => {
  console.log('Abrir detalles de misión:', mision)
  // Implementar navegación a detalles de misión
}

const mostrarDetallesLogro = (logro: Badge): void => {
  console.log('Mostrar detalles de logro:', logro)
  // Implementar modal o navegación a detalles de logro
}

const verTodosLogros = (): void => {
  // Navegar a página de logros
  window.location.href = '/estudiante/logros'
}

const abrirUnirseClase = (): void => {
  // Navegar a página de unirse a clase
  window.location.href = '/estudiante/unirse-clase'
}

const abrirPersonalizacion = (): void => {
  if (props.dashboardData?.personaje) {
    window.location.href = `/estudiante/personaje/${props.dashboardData.personaje.id}/personalizar`
  }
}

const verInventario = (): void => {
  console.log('Ver inventario')
  // Implementar navegación a inventario
  window.location.href = '/estudiante/inventario'
}

// WebSocket para actualizaciones en tiempo real
let echoChannel: any = null

onMounted(() => {
  // Configurar WebSocket si está disponible
  if (window.Echo && props.dashboardData?.personaje?.id_estudiante) {
    echoChannel = window.Echo.channel(`estudiante.${props.dashboardData.personaje.id_estudiante}`)
      .listen('ExperienciaGanada', (e: any) => {
        console.log('XP ganada:', e.cantidad)
        // Actualizar XP y animar
      })
      .listen('LogroDesbloqueado', (e: any) => {
        console.log('Logro desbloqueado:', e.badge)
        // Mostrar notificación y actualizar lista
      })
      .listen('MisionCompletada', (e: any) => {
        console.log('Misión completada:', e.mision)
        // Celebrar completado y actualizar estado
      })
  }
})

onUnmounted(() => {
  if (echoChannel) {
    echoChannel.stopListening('ExperienciaGanada')
    echoChannel.stopListening('LogroDesbloqueado')
    echoChannel.stopListening('MisionCompletada')
  }
})
</script>