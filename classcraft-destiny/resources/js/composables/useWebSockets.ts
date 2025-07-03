// composables/useWebSockets.ts
import { ref, onUnmounted } from 'vue'
import type { Ref } from 'vue'

// Type declarations for missing globals
declare global {
  interface Window {
    Pusher: any
  }
  
  interface ImportMetaEnv {
    VITE_PUSHER_APP_KEY?: string
    VITE_PUSHER_APP_CLUSTER?: string
  }
  
  interface ImportMeta {
    readonly env: ImportMetaEnv
  }
}

// Interfaces
export interface WebSocketConnection {
  echo: any // Laravel Echo instance
  channel: any // Channel instance
  connected: boolean
  reconnecting: boolean
  lastHeartbeat: Date | null
}

export interface WebSocketEventHandler {
  [eventName: string]: (data: any) => void
}

export interface WebSocketConfig {
  broadcaster: 'pusher' | 'socket.io' | 'null'
  key?: string
  cluster?: string
  forceTLS?: boolean
  encrypted?: boolean
  disableStats?: boolean
  enabledTransports?: string[]
  authEndpoint?: string
  auth?: {
    headers: Record<string, string>
  }
}

/**
 * Composable para manejar conexiones WebSocket con Laravel Echo
 * Específicamente diseñado para el sistema ClassCraft-Destiny
 */
export function useWebSockets() {
  // Estado reactivo - Using number for browser compatibility
  const connections: Ref<Map<string, WebSocketConnection>> = ref(new Map())
  const globalConnected = ref(false)
  const lastError = ref<string | null>(null)
  const heartbeatInterval = ref<number | null>(null)

  // Configuración por defecto
  const defaultConfig: WebSocketConfig = {
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY || 'app-key',
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER || 'mt1',
    forceTLS: true,
    encrypted: true,
    disableStats: true,
    enabledTransports: ['ws', 'wss'],
    authEndpoint: '/broadcasting/auth',
    auth: {
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
        'Accept': 'application/json'
      }
    }
  }

  /**
   * Inicializar Laravel Echo
   */
  const initializeEcho = async (config: Partial<WebSocketConfig> = {}): Promise<any> => {
    try {
      // Importar Laravel Echo dinámicamente
      const [{ default: Echo }, { default: Pusher }] = await Promise.all([
        import('laravel-echo'),
        import('pusher-js')
      ])

      // Configurar Pusher globalmente
      window.Pusher = Pusher

      // Combinar configuración
      const finalConfig = { ...defaultConfig, ...config }

      // Crear instancia de Echo
      const echo = new Echo({
        broadcaster: finalConfig.broadcaster,
        key: finalConfig.key,
        cluster: finalConfig.cluster,
        forceTLS: finalConfig.forceTLS,
        encrypted: finalConfig.encrypted,
        disableStats: finalConfig.disableStats,
        enabledTransports: finalConfig.enabledTransports,
        authEndpoint: finalConfig.authEndpoint,
        auth: finalConfig.auth
      })

      // Eventos de conexión globales - Type-safe access
      if (echo.connector && 'pusher' in echo.connector) {
        const pusherConnector = echo.connector as any
        
        pusherConnector.pusher.connection.bind('connected', () => {
          globalConnected.value = true
          lastError.value = null
          console.log('🔗 WebSocket conectado')
        })

        pusherConnector.pusher.connection.bind('disconnected', () => {
          globalConnected.value = false
          console.log('🔌 WebSocket desconectado')
        })

        pusherConnector.pusher.connection.bind('error', (error: any) => {
          lastError.value = error.message || 'Error de conexión WebSocket'
          console.error('❌ Error WebSocket:', error)
        })
      }

      return echo
    } catch (error: any) {
      lastError.value = error.message || 'Error al inicializar WebSocket'
      console.error('❌ Error inicializando Echo:', error)
      throw error
    }
  }

  /**
   * Conectar a un canal específico
   */
  const conectar = async (
    channelName: string, 
    eventHandlers: WebSocketEventHandler = {},
    config: Partial<WebSocketConfig> = {}
  ): Promise<string> => {
    try {
      // Verificar si ya existe una conexión para este canal
      if (connections.value.has(channelName)) {
        console.warn(`⚠️ Ya existe una conexión para el canal: ${channelName}`)
        return channelName
      }

      // Inicializar Echo si es necesario
      const echo = await initializeEcho(config)

      // Determinar el tipo de canal
      let channel
      if (channelName.startsWith('private-')) {
        channel = echo.private(channelName.replace('private-', ''))
      } else if (channelName.startsWith('presence-')) {
        channel = echo.join(channelName.replace('presence-', ''))
      } else {
        channel = echo.channel(channelName)
      }

      // Configurar event handlers
      Object.entries(eventHandlers).forEach(([eventName, handler]) => {
        channel.listen(eventName, handler)
      })

      // Eventos específicos para canales de presencia
      if (channelName.startsWith('presence-')) {
        channel
          .here((users: any[]) => {
            console.log('👥 Usuarios presentes:', users)
          })
          .joining((user: any) => {
            console.log('👤 Usuario conectado:', user)
          })
          .leaving((user: any) => {
            console.log('👋 Usuario desconectado:', user)
          })
      }

      // Guardar conexión
      const connection: WebSocketConnection = {
        echo,
        channel,
        connected: true,
        reconnecting: false,
        lastHeartbeat: new Date()
      }

      connections.value.set(channelName, connection)

      // Configurar heartbeat si es el primer canal
      if (connections.value.size === 1) {
        iniciarHeartbeat()
      }

      console.log(`✅ Conectado al canal: ${channelName}`)
      return channelName

    } catch (error: any) {
      lastError.value = error.message || `Error conectando al canal: ${channelName}`
      console.error(`❌ Error conectando al canal ${channelName}:`, error)
      throw error
    }
  }

  /**
   * Desconectar de un canal específico
   */
  const desconectar = (channelName: string): void => {
    const connection = connections.value.get(channelName)
    
    if (connection) {
      try {
        // Dejar el canal
        connection.echo.leaveChannel(channelName.replace(/^(private-|presence-)/, ''))
        
        // Eliminar de las conexiones
        connections.value.delete(channelName)
        
        console.log(`🔌 Desconectado del canal: ${channelName}`)
        
        // Detener heartbeat si no hay más conexiones
        if (connections.value.size === 0) {
          detenerHeartbeat()
        }
      } catch (error) {
        console.error(`❌ Error desconectando del canal ${channelName}:`, error)
      }
    }
  }

  /**
   * Desconectar de todos los canales
   */
  const desconectarTodos = (): void => {
    const channelNames = Array.from(connections.value.keys())
    
    channelNames.forEach(channelName => {
      desconectar(channelName)
    })
    
    connections.value.clear()
    globalConnected.value = false
    detenerHeartbeat()
    
    console.log('🔌 Todas las conexiones WebSocket cerradas')
  }

  /**
   * Reconectar a todos los canales
   */
  const reconectar = async (): Promise<void> => {
    const channelNames = Array.from(connections.value.keys())
    
    // Desconectar todos primero
    desconectarTodos()
    
    // Esperar un momento antes de reconectar
    await new Promise(resolve => setTimeout(resolve, 1000))
    
    // Reconectar a cada canal
    for (const channelName of channelNames) {
      try {
        await conectar(channelName)
      } catch (error) {
        console.error(`❌ Error reconectando al canal ${channelName}:`, error)
      }
    }
  }

  /**
   * Enviar evento a un canal
   */
  const enviarEvento = (channelName: string, eventName: string, data: any): void => {
    const connection = connections.value.get(channelName)
    
    if (connection && connection.connected) {
      try {
        connection.channel.whisper(eventName, data)
        console.log(`📤 Evento enviado: ${eventName} al canal ${channelName}`)
      } catch (error) {
        console.error(`❌ Error enviando evento ${eventName}:`, error)
      }
    } else {
      console.warn(`⚠️ No hay conexión activa para el canal: ${channelName}`)
    }
  }

  /**
   * Métodos específicos para ClassCraft-Destiny
   */
  const conectarProfesor = async (profesorId: number, eventHandlers: WebSocketEventHandler = {}) => {
    const channelName = `private-profesor.${profesorId}`
    
    const handlers = {
      'ComportamientoAplicado': (data: any) => {
        console.log('🌟 Comportamiento aplicado:', data)
        eventHandlers['ComportamientoAplicado']?.(data)
      },
      'EstudianteInscrito': (data: any) => {
        console.log('👤 Nuevo estudiante inscrito:', data)
        eventHandlers['EstudianteInscrito']?.(data)
      },
      'ClaseCreada': (data: any) => {
        console.log('🚀 Nueva clase creada:', data)
        eventHandlers['ClaseCreada']?.(data)
      },
      'AlertaCritica': (data: any) => {
        console.log('🚨 Alerta crítica:', data)
        eventHandlers['AlertaCritica']?.(data)
      },
      ...eventHandlers
    }
    
    return await conectar(channelName, handlers)
  }

  const conectarEstudiante = async (estudianteId: number, eventHandlers: WebSocketEventHandler = {}) => {
    const channelName = `private-estudiante.${estudianteId}`
    
    const handlers = {
      'ComportamientoRecibido': (data: any) => {
        console.log('⭐ Comportamiento recibido:', data)
        eventHandlers['ComportamientoRecibido']?.(data)
      },
      'NivelSubido': (data: any) => {
        console.log('📈 Nivel subido:', data)
        eventHandlers['NivelSubido']?.(data)
      },
      'MisionAsignada': (data: any) => {
        console.log('🎯 Nueva misión asignada:', data)
        eventHandlers['MisionAsignada']?.(data)
      },
      'RecompensaObtenida': (data: any) => {
        console.log('🏆 Recompensa obtenida:', data)
        eventHandlers['RecompensaObtenida']?.(data)
      },
      ...eventHandlers
    }
    
    return await conectar(channelName, handlers)
  }

  const conectarClase = async (claseId: number, eventHandlers: WebSocketEventHandler = {}) => {
    const channelName = `presence-clase.${claseId}`
    
    const handlers = {
      'ActividadRealizada': (data: any) => {
        console.log('📝 Actividad realizada en clase:', data)
        eventHandlers['ActividadRealizada']?.(data)
      },
      'RankingActualizado': (data: any) => {
        console.log('🏅 Ranking actualizado:', data)
        eventHandlers['RankingActualizado']?.(data)
      },
      'EstadisticasActualizadas': (data: any) => {
        console.log('📊 Estadísticas actualizadas:', data)
        eventHandlers['EstadisticasActualizadas']?.(data)
      },
      ...eventHandlers
    }
    
    return await conectar(channelName, handlers)
  }

  /**
   * Sistema de heartbeat para mantener conexiones vivas
   */
  const iniciarHeartbeat = (): void => {
    if (heartbeatInterval.value) return
    
    heartbeatInterval.value = window.setInterval(() => {
      connections.value.forEach((connection, channelName) => {
        if (connection.connected) {
          connection.lastHeartbeat = new Date()
          // Enviar ping silencioso
          try {
            connection.channel.whisper('heartbeat', { timestamp: Date.now() })
          } catch (error) {
            console.warn(`⚠️ Heartbeat falló para ${channelName}`)
          }
        }
      })
    }, 30000) // Cada 30 segundos
  }

  const detenerHeartbeat = (): void => {
    if (heartbeatInterval.value) {
      window.clearInterval(heartbeatInterval.value)
      heartbeatInterval.value = null
    }
  }

  /**
   * Obtener información de conexiones
   */
  const obtenerEstadoConexiones = () => {
    return Array.from(connections.value.entries()).map(([channelName, connection]) => ({
      channel: channelName,
      connected: connection.connected,
      reconnecting: connection.reconnecting,
      lastHeartbeat: connection.lastHeartbeat
    }))
  }

  /**
   * Cleanup al desmontar componente
   */
  onUnmounted(() => {
    desconectarTodos()
  })

  return {
    // Estado
    connections,
    globalConnected,
    lastError,

    // Métodos generales
    conectar,
    desconectar,
    desconectarTodos,
    reconectar,
    enviarEvento,

    // Métodos específicos ClassCraft-Destiny
    conectarProfesor,
    conectarEstudiante,
    conectarClase,

    // Utilidades
    obtenerEstadoConexiones,
    iniciarHeartbeat,
    detenerHeartbeat
  }
}