// composables/useProfesorDashboard.ts
import { ref, computed } from 'vue'
import type { Ref } from 'vue'

// Interfaces
export interface DashboardData {
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
  notificaciones: NotificationData[]
}

export interface ClaseCard {
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
  grado?: string
  seccion?: string
  configuracion_gamificacion?: any
}

export interface HistorialActividad {
  id: number
  tipo: 'comportamiento' | 'nueva_clase' | 'estudiante_inscrito' | 'mision_completada' | 'nivel_subido'
  descripcion: string
  estudiante_nombre?: string
  clase_nombre: string
  puntos_aplicados?: number
  fecha: string
  icono: string
  color?: string
}

export interface AlertaClase {
  tipo: 'warning' | 'error' | 'info' | 'success'
  mensaje: string
  icono: string
}

export interface NotificationData {
  id: number
  tipo: 'success' | 'error' | 'warning' | 'info'
  titulo: string
  mensaje: string
  fecha: string
  leida: boolean
}

export interface EstudianteAleatorio {
  id: number
  nombre: string
  apellido: string
  avatar?: string
  nivel: number
  clase_rpg: string
  salud_actual: number
  salud_maxima: number
  experiencia_actual: number
  experiencia_siguiente: number
  estado: 'activo' | 'critico' | 'descanso'
}

export interface ComportamientoData {
  id: number
  nombre: string
  descripcion: string
  puntos_experiencia: number
  puntos_salud: number
  tipo: 'positivo' | 'negativo' | 'neutral'
  icono: string
  color: string
  requiere_justificacion: boolean
}

export interface NuevaClaseData {
  nombre: string
  descripcion: string
  grado: string
  seccion: string
  tema_visual: string
  fecha_inicio: string
  fecha_fin: string
  configuracion_gamificacion: any
}

/**
 * Composable principal para el dashboard del profesor
 * Maneja toda la lógica de datos, API calls y estado del dashboard
 */
export function useProfesorDashboard() {
  // Estado reactivo
  const dashboardData: Ref<DashboardData | null> = ref(null)
  const loading = ref(false)
  const error = ref<string | null>(null)
  const lastRefresh = ref<Date | null>(null)

  // Estado de cache para optimizar requests
  const cache = new Map<string, { data: any; timestamp: number }>()
  const CACHE_DURATION = 5 * 60 * 1000 // 5 minutos

  /**
   * Función genérica para hacer requests con cache
   */
  const fetchWithCache = async <T>(url: string, options: any = {}): Promise<T> => {
    const cacheKey = `${url}_${JSON.stringify(options)}`
    const cached = cache.get(cacheKey)
    
    if (cached && Date.now() - cached.timestamp < CACHE_DURATION) {
      return cached.data
    }

    try {
      // Usar fetch API nativo en lugar de $fetch
      const response = await fetch(url, {
        ...options,
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
          ...options.headers,
        }
      })

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }

      const data = await response.json()

      cache.set(cacheKey, {
        data,
        timestamp: Date.now()
      })

      return data
    } catch (err: any) {
      console.error(`Error fetching ${url}:`, err)
      throw new Error(err.message || 'Error en la comunicación con el servidor')
    }
  }

  /**
   * Cargar datos completos del dashboard
   */
  const refresh = async (): Promise<void> => {
    if (loading.value) return
    
    loading.value = true
    error.value = null

    try {
      const response = await fetchWithCache<{success: boolean, data: DashboardData}>('/api/profesor/dashboard')
      
      if (response.success) {
        dashboardData.value = response.data
        lastRefresh.value = new Date()
      } else {
        throw new Error('Error al cargar datos del dashboard')
      }
    } catch (err: any) {
      error.value = err.message
      console.error('Error loading dashboard:', err)
    } finally {
      loading.value = false
    }
  }

  /**
   * Aplicar comportamiento a un estudiante
   */
  const aplicarComportamiento = async (
    claseId: number, 
    estudianteId: number, 
    comportamientoId: number,
    justificacion?: string
  ): Promise<any> => {
    try {
      const response = await fetch(`/api/profesor/clases/${claseId}/comportamientos`, {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          estudiante_id: estudianteId,
          comportamiento_id: comportamientoId,
          justificacion
        })
      })

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }

      const data = await response.json()

      // Invalidar cache relacionado
      cache.delete('/api/profesor/dashboard')
      cache.delete(`/api/profesor/clases/${claseId}/comportamientos/historial`)

      return data
    } catch (err: any) {
      throw new Error(err.message || 'Error al aplicar comportamiento')
    }
  }

  /**
   * Obtener estudiante aleatorio de una clase
   */
  const obtenerEstudianteAleatorio = async (claseId: number): Promise<EstudianteAleatorio> => {
    try {
      const response = await fetchWithCache<{success: boolean, data: EstudianteAleatorio}>(
        `/api/profesor/clases/${claseId}/estudiante-aleatorio`
      )
      
      if (response.success) {
        return response.data
      } else {
        throw new Error('No se pudo obtener estudiante aleatorio')
      }
    } catch (err: any) {
      throw new Error(err.message || 'Error al obtener estudiante aleatorio')
    }
  }

  /**
   * Crear nueva clase
   */
  const crearClase = async (datosClase: NuevaClaseData): Promise<ClaseCard> => {
    try {
      const response = await fetch('/api/profesor/clases', {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(datosClase)
      })

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }

      const data = await response.json()

      if (data.success) {
        // Invalidar cache
        cache.delete('/api/profesor/dashboard')
        cache.delete('/api/profesor/clases')
        
        return data.data
      } else {
        throw new Error('Error al crear la clase')
      }
    } catch (err: any) {
      throw new Error(err.message || 'Error al crear clase')
    }
  }

  /**
   * Obtener historial de actividades de una clase específica
   */
  const obtenerHistorialClase = async (claseId: number, limite: number = 20): Promise<HistorialActividad[]> => {
    try {
      const response = await fetchWithCache<{success: boolean, data: HistorialActividad[]}>(
        `/api/profesor/clases/${claseId}/comportamientos/historial?limite=${limite}`
      )
      
      if (response.success) {
        return response.data
      } else {
        throw new Error('Error al cargar historial')
      }
    } catch (err: any) {
      throw new Error(err.message || 'Error al cargar historial de clase')
    }
  }

  /**
   * Buscar estudiantes para agregar a clase
   */
  const buscarEstudiantes = async (termino: string): Promise<any[]> => {
    if (termino.length < 2) return []

    try {
      const response = await fetchWithCache<{success: boolean, data: any[]}>(
        `/api/profesor/estudiantes/buscar?q=${encodeURIComponent(termino)}`
      )
      
      if (response.success) {
        return response.data
      } else {
        return []
      }
    } catch (err: any) {
      console.error('Error searching students:', err)
      return []
    }
  }

  /**
   * Regenerar código QR de una clase
   */
  const regenerarCodigoQR = async (claseId: number): Promise<string> => {
    try {
      const response = await fetch(`/api/profesor/clases/${claseId}/regenerar-qr`, {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
        }
      })

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }

      const data = await response.json()

      if (data.success) {
        // Invalidar cache
        cache.delete('/api/profesor/dashboard')
        return data.data.qr_url
      } else {
        throw new Error('Error al regenerar código QR')
      }
    } catch (err: any) {
      throw new Error(err.message || 'Error al regenerar código QR')
    }
  }

  /**
   * Exportar datos de una clase
   */
  const exportarDatosClase = async (claseId: number, formato: 'excel' | 'pdf' = 'excel'): Promise<string> => {
    try {
      const response = await fetch(`/api/profesor/clases/${claseId}/exportar?formato=${formato}`)

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }

      const data = await response.json()

      if (data.success) {
        return data.data.url
      } else {
        throw new Error('Error al exportar datos')
      }
    } catch (err: any) {
      throw new Error(err.message || 'Error al exportar datos de clase')
    }
  }

  /**
   * Obtener comportamientos disponibles
   */
  const obtenerComportamientos = async (): Promise<ComportamientoData[]> => {
    try {
      const response = await fetchWithCache<{success: boolean, data: ComportamientoData[]}>(
        '/api/comportamientos'
      )
      
      if (response.success) {
        return response.data
      } else {
        throw new Error('Error al cargar comportamientos')
      }
    } catch (err: any) {
      throw new Error(err.message || 'Error al cargar comportamientos')
    }
  }

  /**
   * Obtener estadísticas detalladas para gráficos
   */
  const obtenerEstadisticasDetalladas = async (periodo: 'semana' | 'mes' | 'trimestre' = 'mes'): Promise<any> => {
    try {
      const response = await fetchWithCache<{success: boolean, data: any}>(
        `/api/profesor/estadisticas?periodo=${periodo}`
      )
      
      if (response.success) {
        return response.data
      } else {
        throw new Error('Error al cargar estadísticas')
      }
    } catch (err: any) {
      throw new Error(err.message || 'Error al cargar estadísticas detalladas')
    }
  }

  // Computed properties
  const estadisticas = computed(() => dashboardData.value?.estadisticas || {
    clases_activas: 0,
    clases_activas_prev: 0,
    total_estudiantes: 0,
    total_estudiantes_prev: 0,
    clases_hoy: 0,
    clases_hoy_prev: 0,
    tareas_pendientes: 0,
    tareas_pendientes_prev: 0,
    actividad_reciente: 0
  })

  const clases = computed(() => dashboardData.value?.clases || [])
  const actividadReciente = computed(() => dashboardData.value?.actividadReciente || [])
  const notificaciones = computed(() => dashboardData.value?.notificaciones || [])

  const clasesActivas = computed(() => clases.value.filter(clase => clase.activa))
  const clasesConAlertas = computed(() => clases.value.filter(clase => clase.alertas?.length > 0))
  const totalEstudiantesCriticos = computed(() => 
    clases.value.reduce((total, clase) => total + clase.personajes_criticos, 0)
  )

  const tieneClasesHoy = computed(() => estadisticas.value.clases_hoy > 0)
  const tieneTareasPendientes = computed(() => estadisticas.value.tareas_pendientes > 0)

  /**
   * Utilidades para formateo y presentación
   */
  const formatearTiempoTranscurrido = (fecha: string): string => {
    const ahora = new Date()
    const fechaActividad = new Date(fecha)
    const diferencia = ahora.getTime() - fechaActividad.getTime()

    const minutos = Math.floor(diferencia / (1000 * 60))
    const horas = Math.floor(diferencia / (1000 * 60 * 60))
    const dias = Math.floor(diferencia / (1000 * 60 * 60 * 24))

    if (minutos < 1) return 'Hace un momento'
    if (minutos < 60) return `Hace ${minutos}m`
    if (horas < 24) return `Hace ${horas}h`
    return `Hace ${dias}d`
  }

  const obtenerColorPorTipo = (tipo: string): string => {
    const colores: Record<string, string> = {
      comportamiento: '#22c55e',
      nueva_clase: '#3b82f6',
      estudiante_inscrito: '#8b5cf6',
      mision_completada: '#f59e0b',
      nivel_subido: '#eab308'
    }
    return colores[tipo] || '#6b7280'
  }

  const obtenerIconoPorTipo = (tipo: string): string => {
    const iconos: Record<string, string> = {
      comportamiento: '⭐',
      nueva_clase: '🚀',
      estudiante_inscrito: '👤',
      mision_completada: '🏆',
      nivel_subido: '📈'
    }
    return iconos[tipo] || '📝'
  }

  /**
   * Funciones de utilidad para el estado
   */
  const limpiarCache = (): void => {
    cache.clear()
  }

  const invalidarCache = (patron?: string): void => {
    if (!patron) {
      cache.clear()
      return
    }

    for (const key of cache.keys()) {
      if (key.includes(patron)) {
        cache.delete(key)
      }
    }
  }

  // Auto-refresh cada 5 minutos si la página está activa
  let autoRefreshInterval: number | null = null

  const iniciarAutoRefresh = (): void => {
    if (autoRefreshInterval) return

    autoRefreshInterval = window.setInterval(() => {
      if (!document.hidden && !loading.value) {
        refresh()
      }
    }, 5 * 60 * 1000) // 5 minutos
  }

  const detenerAutoRefresh = (): void => {
    if (autoRefreshInterval) {
      clearInterval(autoRefreshInterval)
      autoRefreshInterval = null
    }
  }

  return {
    // Estado
    dashboardData,
    loading,
    error,
    lastRefresh,

    // Computed
    estadisticas,
    clases,
    actividadReciente,
    notificaciones,
    clasesActivas,
    clasesConAlertas,
    totalEstudiantesCriticos,
    tieneClasesHoy,
    tieneTareasPendientes,

    // Métodos principales
    refresh,
    aplicarComportamiento,
    obtenerEstudianteAleatorio,
    crearClase,
    obtenerHistorialClase,
    buscarEstudiantes,
    regenerarCodigoQR,
    exportarDatosClase,
    obtenerComportamientos,
    obtenerEstadisticasDetalladas,

    // Utilidades
    formatearTiempoTranscurrido,
    obtenerColorPorTipo,
    obtenerIconoPorTipo,
    limpiarCache,
    invalidarCache,
    iniciarAutoRefresh,
    detenerAutoRefresh
  }
}