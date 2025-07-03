// composables/useNotifications.ts
import { ref, computed } from 'vue'
import type { Ref } from 'vue'

// Interfaces
export interface Notification {
  id: number | string
  tipo: 'success' | 'error' | 'warning' | 'info'
  titulo: string
  mensaje: string
  duracion?: number
  accion?: NotificationAction
  persistente?: boolean
  icono?: string
  timestamp?: Date
}

export interface NotificationAction {
  texto: string
  handler: () => void
  tipo?: 'primary' | 'secondary'
}

export interface NotificationOptions {
  posicion?: 'top-right' | 'top-left' | 'bottom-right' | 'bottom-left' | 'top-center' | 'bottom-center'
  maxNotifications?: number
  defaultDuration?: number
  sonidos?: boolean
}

// Interface para nuevas notificaciones (sin id y timestamp)
export interface NewNotification {
  tipo: 'success' | 'error' | 'warning' | 'info'
  titulo: string
  mensaje: string
  duracion?: number
  accion?: NotificationAction
  persistente?: boolean
  icono?: string
}

/**
 * Composable para manejo de notificaciones del sistema
 * Proporciona funcionalidad completa para mostrar, gestionar y configurar notificaciones
 */
export function useNotifications(options: NotificationOptions = {}) {
  // Configuración por defecto
  const config = {
    posicion: options.posicion || 'top-right',
    maxNotifications: options.maxNotifications || 5,
    defaultDuration: options.defaultDuration || 4000,
    sonidos: options.sonidos !== false
  }

  // Estado reactivo
  const notificaciones: Ref<Notification[]> = ref([])
  const pausadas = ref(false)

  // Map para tracking de timeouts (usando number en lugar de NodeJS.Timeout)
  const timeouts = new Map<string | number, number>()

  // Computed properties
  const notificacionesActivas = computed(() => 
    notificaciones.value.filter(n => !n.persistente || pausadas.value)
  )

  const notificacionesPorTipo = computed(() => {
    const grupos = {
      success: [] as Notification[],
      error: [] as Notification[],
      warning: [] as Notification[],
      info: [] as Notification[]
    }
    
    notificaciones.value.forEach(notif => {
      grupos[notif.tipo].push(notif)
    })
    
    return grupos
  })

  const tieneNotificacionesError = computed(() => 
    notificacionesPorTipo.value.error.length > 0
  )

  const conteoNotificaciones = computed(() => notificaciones.value.length)

  /**
   * Agregar una nueva notificación
   */
  const agregarNotificacion = (notificacion: NewNotification): string | number => {
    const id = Date.now() + Math.random()
    const nuevaNotificacion: Notification = {
      ...notificacion,
      id,
      timestamp: new Date(),
      duracion: notificacion.duracion || config.defaultDuration,
      icono: notificacion.icono || obtenerIconoPorDefecto(notificacion.tipo)
    }

    // Limitar número máximo de notificaciones
    if (notificaciones.value.length >= config.maxNotifications) {
      const notifMasAntigua = notificaciones.value[0]
      removerNotificacion(notifMasAntigua.id)
    }

    notificaciones.value.push(nuevaNotificacion)

    // Reproducir sonido si está habilitado
    if (config.sonidos) {
      reproducirSonido(notificacion.tipo)
    }

    // Configurar auto-remove si no es persistente
    if (!nuevaNotificacion.persistente && nuevaNotificacion.duracion && nuevaNotificacion.duracion > 0) {
      const timeout = window.setTimeout(() => {
        removerNotificacion(id)
      }, nuevaNotificacion.duracion)
      
      timeouts.set(id, timeout)
    }

    return id
  }

  /**
   * Remover notificación por ID
   */
  const removerNotificacion = (id: string | number): void => {
    const index = notificaciones.value.findIndex(n => n.id === id)
    if (index !== -1) {
      notificaciones.value.splice(index, 1)
    }

    // Limpiar timeout si existe
    const timeout = timeouts.get(id)
    if (timeout) {
      clearTimeout(timeout)
      timeouts.delete(id)
    }
  }

  /**
   * Limpiar todas las notificaciones
   */
  const limpiarNotificaciones = (): void => {
    // Limpiar todos los timeouts
    timeouts.forEach(timeout => clearTimeout(timeout))
    timeouts.clear()
    
    notificaciones.value = []
  }

  /**
   * Limpiar notificaciones por tipo
   */
  const limpiarPorTipo = (tipo: Notification['tipo']): void => {
    const notificacionesARemover = notificaciones.value
      .filter(n => n.tipo === tipo)
      .map(n => n.id)
    
    notificacionesARemover.forEach(id => removerNotificacion(id))
  }

  /**
   * Pausar/reanudar auto-remove de notificaciones
   */
  const pausarNotificaciones = (): void => {
    pausadas.value = true
    // Pausar todos los timeouts
    timeouts.forEach((timeout, id) => {
      clearTimeout(timeout)
      timeouts.delete(id)
    })
  }

  const reanudarNotificaciones = (): void => {
    pausadas.value = false
    // Reanudar timeouts para notificaciones no persistentes
    notificaciones.value.forEach(notif => {
      if (!notif.persistente && notif.duracion && notif.duracion > 0) {
        const timeout = window.setTimeout(() => {
          removerNotificacion(notif.id)
        }, notif.duracion)
        
        timeouts.set(notif.id, timeout)
      }
    })
  }

  /**
   * Métodos de conveniencia para tipos específicos
   */
  const notificarExito = (titulo: string, mensaje: string, duracion?: number) => {
    return agregarNotificacion({
      tipo: 'success',
      titulo,
      mensaje,
      duracion
    })
  }

  const notificarError = (titulo: string, mensaje: string, persistente = false) => {
    return agregarNotificacion({
      tipo: 'error',
      titulo,
      mensaje,
      persistente,
      duracion: persistente ? 0 : 6000
    })
  }

  const notificarAdvertencia = (titulo: string, mensaje: string, duracion?: number) => {
    return agregarNotificacion({
      tipo: 'warning',
      titulo,
      mensaje,
      duracion: duracion || 5000
    })
  }

  const notificarInfo = (titulo: string, mensaje: string, duracion?: number) => {
    return agregarNotificacion({
      tipo: 'info',
      titulo,
      mensaje,
      duracion
    })
  }

  /**
   * Notificaciones específicas del sistema ClassCraft-Destiny
   */
  const notificarComportamientoAplicado = (estudiante: string, puntos: number, comportamiento: string) => {
    const tipo = puntos > 0 ? 'success' : puntos < 0 ? 'warning' : 'info'
    const icono = puntos > 0 ? '⭐' : puntos < 0 ? '⚠️' : '📝'
    
    return agregarNotificacion({
      tipo,
      titulo: 'Comportamiento Aplicado',
      mensaje: `${icono} ${estudiante}: ${comportamiento} (${puntos > 0 ? '+' : ''}${puntos} XP)`,
      icono,
      duracion: 4000
    })
  }

  const notificarNuevoGuardian = (estudiante: string, clase: string) => {
    return agregarNotificacion({
      tipo: 'success',
      titulo: 'Nuevo Guardián',
      mensaje: `👤 ${estudiante} se unió a ${clase}`,
      icono: '👤',
      duracion: 5000
    })
  }

  const notificarNivelSubido = (estudiante: string, nuevoNivel: number) => {
    return agregarNotificacion({
      tipo: 'success',
      titulo: '¡Nivel Subido!',
      mensaje: `🎉 ${estudiante} alcanzó el nivel ${nuevoNivel}`,
      icono: '📈',
      duracion: 6000
    })
  }

  const notificarGuardianCritico = (estudiante: string, saludActual: number) => {
    return agregarNotificacion({
      tipo: 'error',
      titulo: 'Guardián en Estado Crítico',
      mensaje: `⚠️ ${estudiante} tiene ${saludActual}% de salud`,
      icono: '🚨',
      persistente: true
    })
  }

  const notificarMisionCompletada = (estudiante: string, mision: string) => {
    return agregarNotificacion({
      tipo: 'success',
      titulo: 'Misión Completada',
      mensaje: `🏆 ${estudiante} completó: ${mision}`,
      icono: '🏆',
      duracion: 5000
    })
  }

  /**
   * Utilidades
   */
  const obtenerIconoPorDefecto = (tipo: Notification['tipo']): string => {
    const iconos: Record<string, string> = {
      success: '✅',
      error: '❌',
      warning: '⚠️',
      info: 'ℹ️'
    }
    return iconos[tipo]
  }

  const reproducirSonido = (tipo: Notification['tipo']): void => {
    // Implementar sonidos específicos de Destiny
    try {
      const sonidos: Record<string, string> = {
        success: '/sounds/destiny_success.mp3',
        error: '/sounds/destiny_error.mp3',
        warning: '/sounds/destiny_warning.mp3',
        info: '/sounds/destiny_info.mp3'
      }

      const audio = new Audio(sonidos[tipo])
      audio.volume = 0.3
      audio.play().catch(() => {
        // Fallar silenciosamente si no se pueden reproducir sonidos
        console.log('No se pudo reproducir sonido de notificación')
      })
    } catch (error) {
      // Ignorar errores de audio
    }
  }

  const obtenerColorPorTipo = (tipo: Notification['tipo']): string => {
    const colores: Record<string, string> = {
      success: '#22c55e',
      error: '#ef4444',
      warning: '#f59e0b',
      info: '#3b82f6'
    }
    return colores[tipo]
  }

  const formatearTiempo = (timestamp: Date): string => {
    const ahora = new Date()
    const diferencia = ahora.getTime() - timestamp.getTime()
    const minutos = Math.floor(diferencia / (1000 * 60))

    if (minutos < 1) return 'Ahora'
    if (minutos < 60) return `${minutos}m`
    const horas = Math.floor(minutos / 60)
    if (horas < 24) return `${horas}h`
    const dias = Math.floor(horas / 24)
    return `${dias}d`
  }

  /**
   * Configuración dinámica
   */
  const actualizarConfiguracion = (nuevaConfig: Partial<NotificationOptions>): void => {
    Object.assign(config, nuevaConfig)
  }

  const exportarNotificaciones = (): Notification[] => {
    return [...notificaciones.value]
  }

  const importarNotificaciones = (notificacionesImportadas: Notification[]): void => {
    limpiarNotificaciones()
    notificacionesImportadas.forEach(notif => {
      // Convertir notificación a NewNotification antes de agregar
      const { id, timestamp, ...newNotif } = notif
      agregarNotificacion(newNotif)
    })
  }

  return {
    // Estado
    notificaciones,
    pausadas,
    config,

    // Computed
    notificacionesActivas,
    notificacionesPorTipo,
    tieneNotificacionesError,
    conteoNotificaciones,

    // Métodos principales
    agregarNotificacion,
    removerNotificacion,
    limpiarNotificaciones,
    limpiarPorTipo,
    pausarNotificaciones,
    reanudarNotificaciones,

    // Métodos de conveniencia
    notificarExito,
    notificarError,
    notificarAdvertencia,
    notificarInfo,

    // Métodos específicos del sistema
    notificarComportamientoAplicado,
    notificarNuevoGuardian,
    notificarNivelSubido,
    notificarGuardianCritico,
    notificarMisionCompletada,

    // Utilidades
    obtenerColorPorTipo,
    formatearTiempo,
    actualizarConfiguracion,
    exportarNotificaciones,
    importarNotificaciones
  }
}