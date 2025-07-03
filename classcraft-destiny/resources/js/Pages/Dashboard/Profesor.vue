<template>
  <div class="min-h-screen bg-destiny-dark text-white relative overflow-hidden">
    <!-- Hexagonal Pattern Background -->
    <div class="hexagon-pattern absolute inset-0 opacity-10"></div>
    
    <!-- Loading Overlay -->
    <div v-if="loading" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
      <div class="destiny-spinner"></div>
    </div>

    <!-- Main Layout -->
    <div class="relative z-10">
      <!-- Header -->
      <header class="holo-panel border-b border-destiny-gold/30 p-6 mb-8">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-gradient-to-br from-destiny-gold to-destiny-cyan rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
              </svg>
            </div>
            <div>
              <h1 class="text-2xl font-bold text-destiny-gold">Consola de Comando</h1>
              <p class="text-destiny-cyan">Torre del Conocimiento - Instructor {{ profesorNombre }}</p>
            </div>
          </div>
          
          <div class="flex items-center space-x-4">
            <button @click="refreshDashboard" class="destiny-button-ghost">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
              </svg>
              Actualizar
            </button>
            <div class="text-sm text-gray-400">
              Última actualización: {{ ultimaActualizacion }}
            </div>
          </div>
        </div>
      </header>

      <div class="px-6 space-y-8">
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          <StatCard
            :value="estadisticas.clases_activas"
            :prevValue="estadisticas.clases_activas_prev"
            label="Operaciones Activas"
            icon="⚡"
            iconColor="text-yellow-400"
            :animated="true"
          />
          <StatCard
            :value="estadisticas.total_estudiantes"
            :prevValue="estadisticas.total_estudiantes_prev"
            label="Guardianes Totales"
            icon="👥"
            iconColor="text-blue-400"
            :animated="true"
          />
          <StatCard
            :value="estadisticas.clases_hoy"
            :prevValue="estadisticas.clases_hoy_prev"
            label="Sesiones Hoy"
            icon="📅"
            iconColor="text-green-400"
            :animated="true"
          />
          <StatCard
            :value="estadisticas.tareas_pendientes"
            :prevValue="estadisticas.tareas_pendientes_prev"
            label="Pendientes"
            icon="⚠️"
            iconColor="text-orange-400"
            :animated="true"
          />
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <!-- Left Column: Active Classes -->
          <div class="lg:col-span-2 space-y-6">
            <!-- Active Classes -->
            <section class="holo-panel p-6">
              <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-destiny-gold flex items-center">
                  <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                  </svg>
                  Operaciones en Curso
                </h2>
                <button @click="mostrarModalNuevaClase" class="destiny-button-primary">
                  <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                  </svg>
                  Nueva Operación
                </button>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <ClassCard
                  v-for="clase in clases"
                  :key="clase.id"
                  :clase="clase"
                  @aplicar-comportamiento="abrirModalComportamiento"
                  @estudiante-aleatorio="seleccionarEstudianteAleatorio"
                  @ver-detalles="verDetallesClase"
                />
              </div>

              <div v-if="clases.length === 0" class="text-center py-12">
                <div class="text-6xl mb-4">🚀</div>
                <h3 class="text-xl font-semibold text-gray-300 mb-2">No hay operaciones activas</h3>
                <p class="text-gray-400 mb-4">Crea tu primera clase para comenzar</p>
                <button @click="mostrarModalNuevaClase" class="destiny-button-primary">
                  Crear Primera Operación
                </button>
              </div>
            </section>

            <!-- Quick Actions -->
            <section class="holo-panel p-6">
              <h3 class="text-lg font-semibold text-destiny-gold mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                Acciones Rápidas
              </h3>
              <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                <QuickActionButton
                  @click="mostrarModalNuevaClase"
                  icon="🚀"
                  label="Nueva Operación"
                  description="Crear clase"
                />
                <QuickActionButton
                  @click="estudianteAleatorioGlobal"
                  icon="🎲"
                  label="Guardián Aleatorio"
                  description="Selector general"
                />
                <QuickActionButton
                  @click="mostrarModalComportamiento"
                  icon="⭐"
                  label="Aplicar Logro"
                  description="Comportamiento"
                />
                <QuickActionButton
                  @click="generarReporte"
                  icon="📊"
                  label="Generar Reporte"
                  description="Estadísticas"
                />
              </div>
            </section>
          </div>

          <!-- Right Column: Activity & Notifications -->
          <div class="space-y-6">
            <!-- Recent Activity Timeline -->
            <section class="holo-panel p-6">
              <h3 class="text-lg font-semibold text-destiny-gold mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                </svg>
                Registro de Actividad
              </h3>
              
              <div class="timeline-destiny space-y-4 max-h-96 overflow-y-auto">
                <ActivityTimelineItem
                  v-for="actividad in actividadReciente"
                  :key="actividad.id"
                  :actividad="actividad"
                />
              </div>

              <div v-if="actividadReciente.length === 0" class="text-center py-8">
                <div class="text-4xl mb-2">📝</div>
                <p class="text-gray-400">No hay actividad reciente</p>
              </div>
            </section>

            <!-- Statistics Chart -->
            <section class="holo-panel p-6">
              <h3 class="text-lg font-semibold text-destiny-gold mb-4">Distribución de Guardianes</h3>
              <div class="h-64">
                <GuardianDistributionChart :data="chartData" />
              </div>
            </section>
          </div>
        </div>

        <!-- Bottom Section: Detailed Statistics -->
        <section class="holo-panel p-6">
          <h3 class="text-xl font-bold text-destiny-gold mb-6">Centro de Comando - Estadísticas Avanzadas</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <BehaviorChart :data="comportamientosData" />
            <ClassProgressChart :data="progresoClasesData" />
            <GuardianHealthChart :data="saludGuardianesData" />
          </div>
        </section>
      </div>
    </div>

    <!-- Modals -->
    <ComportamientoModal
      :show="modalComportamiento.show"
      :clase="modalComportamiento.clase"
      :estudiante="modalComportamiento.estudiante"
      @close="cerrarModalComportamiento"
      @aplicado="onComportamientoAplicado"
    />

    <NuevaClaseModal
      :show="modalNuevaClase.show"
      @close="cerrarModalNuevaClase"
      @creada="onClaseCreada"
    />

    <EstudianteAleatorioModal
      :show="modalEstudianteAleatorio.show"
      :estudiante="estudianteSeleccionado"
      :clase="claseParaAleatorio"
      @close="cerrarModalEstudianteAleatorio"
      @aplicar-comportamiento="abrirModalComportamiento"
    />

    <!-- Floating Notifications -->
    <NotificationContainer :notifications="notificaciones" />
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { useProfesorDashboard } from '@/composables/useProfesorDashboard'
import { useNotifications } from '@/composables/useNotifications'
import { useWebSockets } from '@/composables/useWebSockets'

// Components
import StatCard from '@/Components/Dashboard/StatCard.vue'
import ClassCard from '@/Components/Dashboard/ClassCard.vue'
import QuickActionButton from '@/Components/Dashboard/QuickActionButton.vue'
import ActivityTimelineItem from '@/Components/Dashboard/ActivityTimelineItem.vue'
import GuardianDistributionChart from '@/Components/Charts/GuardianDistributionChart.vue'
import BehaviorChart from '@/Components/Charts/BehaviorChart.vue'
import ClassProgressChart from '@/Components/Charts/ClassProgressChart.vue'
import GuardianHealthChart from '@/Components/Charts/GuardianHealthChart.vue'
import ComportamientoModal from '@/Components/Modals/ComportamientoModal.vue'
import NuevaClaseModal from '@/Components/Modals/NuevaClaseModal.vue'
import EstudianteAleatorioModal from '@/Components/Modals/EstudianteAleatorioModal.vue'
import NotificationContainer from '@/Components/Notifications/NotificationContainer.vue'

// Interfaces
interface DashboardData {
  estadisticas: {
    clases_activas: number
    clases_activas_prev: number
    total_estudiantes: number
    total_estudiantes_prev: number
    clases_hoy: number
    clases_hoy_prev: number
    tareas_pendientes: number
    tareas_pendientes_prev: number
    actividad_reciente: number
  }
  clases: ClaseCard[]
  actividadReciente: HistorialActividad[]
  notificaciones: Notification[]
}

interface ClaseCard {
  id: number
  nombre: string
  descripcion: string
  codigo_invitacion: string
  estudiantes_count: number
  activa: boolean
  progreso: number
  fecha_inicio: string
  fecha_fin: string
  tema_visual: string
  alertas: AlertaClase[]
  personajes_criticos: number
}

interface HistorialActividad {
  id: number
  tipo: 'comportamiento' | 'nueva_clase' | 'estudiante_inscrito'
  descripcion: string
  estudiante_nombre?: string
  clase_nombre: string
  puntos_aplicados?: number
  fecha: string
  icono: string
}

interface AlertaClase {
  tipo: 'warning' | 'error' | 'info'
  mensaje: string
  icono: string
}

// Composables
const { 
  dashboardData, 
  loading, 
  error, 
  refresh, 
  aplicarComportamiento, 
  obtenerEstudianteAleatorio,
  crearClase
} = useProfesorDashboard()

const { notificaciones, agregarNotificacion } = useNotifications()
const { conectar, desconectar } = useWebSockets()

// Reactive State
const modalComportamiento = ref({
  show: false,
  clase: null,
  estudiante: null
})

const modalNuevaClase = ref({
  show: false
})

const modalEstudianteAleatorio = ref({
  show: false
})

const estudianteSeleccionado = ref(null)
const claseParaAleatorio = ref(null)

// Computed Properties
const estadisticas = computed(() => dashboardData.value?.estadisticas || {})
const clases = computed(() => dashboardData.value?.clases || [])
const actividadReciente = computed(() => dashboardData.value?.actividadReciente || [])

const profesorNombre = computed(() => {
  // Obtener nombre del profesor del contexto global o props
  return 'Instructor Vanguardia'
})

const ultimaActualizacion = computed(() => {
  return new Date().toLocaleTimeString('es-PE', {
    hour: '2-digit',
    minute: '2-digit'
  })
})

const chartData = computed(() => {
  if (!clases.value.length) return []
  
  const clasesRpg = ['hunter', 'titan', 'warlock']
  return clasesRpg.map(clase => ({
    name: clase.charAt(0).toUpperCase() + clase.slice(1),
    value: Math.floor(Math.random() * 20) + 5,
    color: getClassColor(clase)
  }))
})

const comportamientosData = computed(() => {
  // Datos simulados para el gráfico de comportamientos
  return {
    labels: ['Positivos', 'Neutrales', 'Negativos'],
    datasets: [{
      data: [65, 25, 10],
      backgroundColor: ['#22c55e', '#6b7280', '#ef4444']
    }]
  }
})

const progresoClasesData = computed(() => {
  return clases.value.map(clase => ({
    name: clase.nombre,
    progreso: clase.progreso
  }))
})

const saludGuardianesData = computed(() => {
  return {
    criticos: 5,
    advertencia: 12,
    saludables: 45
  }
})

// Methods
const refreshDashboard = async () => {
  await refresh()
  agregarNotificacion({
    id: Date.now(),
    tipo: 'success',
    titulo: 'Dashboard Actualizado',
    mensaje: 'Datos sincronizados con La Torre',
    duracion: 3000
  })
}

const mostrarModalNuevaClase = () => {
  modalNuevaClase.value.show = true
}

const cerrarModalNuevaClase = () => {
  modalNuevaClase.value.show = false
}

const mostrarModalComportamiento = () => {
  modalComportamiento.value.show = true
}

const abrirModalComportamiento = (clase: ClaseCard, estudiante = null) => {
  modalComportamiento.value = {
    show: true,
    clase,
    estudiante
  }
}

const cerrarModalComportamiento = () => {
  modalComportamiento.value = {
    show: false,
    clase: null,
    estudiante: null
  }
}

const seleccionarEstudianteAleatorio = async (clase: ClaseCard) => {
  try {
    const estudiante = await obtenerEstudianteAleatorio(clase.id)
    estudianteSeleccionado.value = estudiante
    claseParaAleatorio.value = clase
    modalEstudianteAleatorio.value.show = true
  } catch (error) {
    agregarNotificacion({
      id: Date.now(),
      tipo: 'error',
      titulo: 'Error',
      mensaje: 'No se pudo seleccionar un estudiante aleatorio',
      duracion: 5000
    })
  }
}

const estudianteAleatorioGlobal = async () => {
  if (clases.value.length === 0) {
    agregarNotificacion({
      id: Date.now(),
      tipo: 'warning',
      titulo: 'Sin Operaciones',
      mensaje: 'Necesitas al menos una clase activa',
      duracion: 4000
    })
    return
  }
  
  const claseAleatoria = clases.value[Math.floor(Math.random() * clases.value.length)]
  await seleccionarEstudianteAleatorio(claseAleatoria)
}

const cerrarModalEstudianteAleatorio = () => {
  modalEstudianteAleatorio.value.show = false
  estudianteSeleccionado.value = null
  claseParaAleatorio.value = null
}

const verDetallesClase = (clase: ClaseCard) => {
  // Navegar a la vista de detalles de la clase
  // router.push(`/profesor/clases/${clase.id}`)
  console.log('Ver detalles de clase:', clase)
}

const generarReporte = () => {
  agregarNotificacion({
    id: Date.now(),
    tipo: 'info',
    titulo: 'Generando Reporte',
    mensaje: 'El informe estará listo en breve...',
    duracion: 3000
  })
  
  // Simular generación de reporte
  setTimeout(() => {
    agregarNotificacion({
      id: Date.now(),
      tipo: 'success',
      titulo: 'Reporte Completado',
      mensaje: 'El informe ha sido generado exitosamente',
      duracion: 5000
    })
  }, 3000)
}

const onComportamientoAplicado = (data: any) => {
  cerrarModalComportamiento()
  refreshDashboard()
  agregarNotificacion({
    id: Date.now(),
    tipo: 'success',
    titulo: 'Comportamiento Aplicado',
    mensaje: `${data.puntos > 0 ? '+' : ''}${data.puntos} puntos aplicados`,
    duracion: 4000
  })
}

const onClaseCreada = (clase: ClaseCard) => {
  cerrarModalNuevaClase()
  refreshDashboard()
  agregarNotificacion({
    id: Date.now(),
    tipo: 'success',
    titulo: 'Nueva Operación Creada',
    mensaje: `${clase.nombre} está lista para Guardianes`,
    duracion: 5000
  })
}

const getClassColor = (className: string): string => {
  const colors = {
    hunter: '#4ade80',
    titan: '#3b82f6', 
    warlock: '#a855f7'
  }
  return colors[className] || '#6b7280'
}

// Lifecycle
onMounted(async () => {
  await refresh()
  
  // Conectar WebSockets para actualizaciones en tiempo real
  conectar('profesor', {
    'ComportamientoAplicado': (data) => {
      refreshDashboard()
      agregarNotificacion({
        id: Date.now(),
        tipo: 'info',
        titulo: 'Actividad Detectada',
        mensaje: `Nuevo comportamiento aplicado en ${data.clase_nombre}`,
        duracion: 3000
      })
    },
    'EstudianteInscrito': (data) => {
      refreshDashboard()
      agregarNotificacion({
        id: Date.now(),
        tipo: 'success',
        titulo: 'Nuevo Guardián',
        mensaje: `${data.estudiante_nombre} se unió a ${data.clase_nombre}`,
        duracion: 4000
      })
    }
  })
})

// Cleanup
const { $router } = useNuxtApp()
</script>

<style scoped>
.bg-destiny-dark {
  background: linear-gradient(135deg, #0f0f23 0%, #1a1a2e 50%, #16213e 100%);
}

.holo-panel {
  background: linear-gradient(135deg, rgba(27, 28, 41, 0.9), rgba(58, 69, 81, 0.9));
  border: 1px solid rgba(199, 184, 138, 0.3);
  backdrop-filter: blur(10px);
  border-radius: 12px;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
}

.hexagon-pattern {
  background-image: 
    radial-gradient(circle at 25px 25px, rgba(199, 184, 138, 0.1) 2px, transparent 2px),
    radial-gradient(circle at 75px 75px, rgba(110, 193, 228, 0.1) 2px, transparent 2px);
  background-size: 100px 100px;
}

.destiny-spinner {
  width: 40px;
  height: 40px;
  border: 3px solid rgba(199, 184, 138, 0.3);
  border-top: 3px solid #C7B88A;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.destiny-button-primary {
  @apply bg-gradient-to-r from-destiny-gold to-yellow-500 text-black font-semibold px-4 py-2 rounded-lg;
  @apply hover:from-yellow-400 hover:to-destiny-gold transition-all duration-300;
  @apply shadow-lg hover:shadow-xl hover:scale-105;
}

.destiny-button-ghost {
  @apply bg-transparent border border-destiny-cyan text-destiny-cyan px-4 py-2 rounded-lg;
  @apply hover:bg-destiny-cyan hover:text-black transition-all duration-300;
  @apply flex items-center;
}

.timeline-destiny {
  position: relative;
}

.timeline-destiny::before {
  content: '';
  position: absolute;
  left: 20px;
  top: 0;
  bottom: 0;
  width: 2px;
  background: linear-gradient(to bottom, #C7B88A, #6EC1E4);
}

.text-destiny-gold {
  color: #C7B88A;
}

.text-destiny-cyan {
  color: #6EC1E4;
}

.border-destiny-gold {
  border-color: #C7B88A;
}

.border-destiny-cyan {
  border-color: #6EC1E4;
}

/* Scrollbar personalizado estilo Destiny */
::-webkit-scrollbar {
  width: 8px;
}

::-webkit-scrollbar-track {
  background: rgba(27, 28, 41, 0.5);
  border-radius: 4px;
}

::-webkit-scrollbar-thumb {
  background: linear-gradient(45deg, #C7B88A, #6EC1E4);
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(45deg, #6EC1E4, #C7B88A);
}
</style>