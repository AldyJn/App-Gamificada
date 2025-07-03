// composables/useClasesIndex.ts
import { ref, computed } from 'vue'
import type { Ref } from 'vue'
import { debounce } from 'lodash'

// Interfaces
export interface ClasesIndexData {
  clases: Clase[]
  meta: PaginationMeta
  filtros_disponibles: FiltrosDisponibles
}

export interface Clase {
  id: number
  nombre: string
  descripcion: string
  codigo_acceso: string
  id_docente: number
  materia: string
  grado: string
  seccion: string
  ano_escolar: number
  activa: boolean
  configuracion: any
  imagen_banner?: string
  color_tema: string
  fecha_inicio: string
  fecha_fin: string
  tema_visual: string
  modo_competitivo: boolean
  created_at: string
  updated_at: string
  
  // Estadísticas calculadas
  estudiantes_count: number
  actividades_count: number
  personajes_count: number
  promedio_nivel: number
  estudiantes_activos: number
  alertas: AlertaClase[]
}

export interface AlertaClase {
  tipo: 'warning' | 'danger' | 'info' | 'success'
  mensaje: string
  icono: string
  count?: number
}

export interface FiltrosClase {
  materia: string[]
  grado: string[]
  seccion: string[]
  estado: 'todas' | 'activas' | 'inactivas' | 'archivadas' | 'competitivas'
  tema_visual: string[]
  modo_competitivo?: boolean
  fecha_inicio?: string
  fecha_fin?: string
  tiene_estudiantes?: boolean
}

export interface PaginationMeta {
  current_page: number
  per_page: number
  total: number
  last_page: number
  has_more: boolean
}

export interface FiltrosDisponibles {
  materias: string[]
  grados: string[]
  secciones: string[]
  temas_visuales: string[]
}

// Estado reactivo global
const clasesData = ref<ClasesIndexData | null>(null)
const loading = ref(false)
const error = ref<string | null>(null)

// Filtros y búsqueda
const filtros = ref<FiltrosClase>({
  materia: [],
  grado: [],
  seccion: [],
  estado: 'todas',
  tema_visual: []
})

const busqueda = ref('')
const clasesSeleccionadas = ref<number[]>([])
const paginaActual = ref(1)

export const useClasesIndex = () => {
  
  // Computed properties
  const hayClases = computed(() => {
    return clasesData.value?.clases && clasesData.value.clases.length > 0
  })

  const totalClases = computed(() => {
    return clasesData.value?.meta?.total || 0
  })

  const hayMasClases = computed(() => {
    return clasesData.value?.meta?.has_more || false
  })

  // Métodos principales
  const cargarClases = async (params: Record<string, any> = {}) => {
    loading.value = true
    error.value = null
    
    try {
      const queryParams = new URLSearchParams({
        page: paginaActual.value.toString(),
        limit: '20',
        ...params
      })

      // Agregar filtros de búsqueda
      if (busqueda.value) {
        queryParams.set('search', busqueda.value)
      }

      // Agregar filtros específicos
      if (filtros.value.materia.length > 0) {
        filtros.value.materia.forEach(materia => {
          queryParams.append('materia[]', materia)
        })
      }

      if (filtros.value.grado.length > 0) {
        filtros.value.grado.forEach(grado => {
          queryParams.append('grado[]', grado)
        })
      }

      if (filtros.value.seccion.length > 0) {
        filtros.value.seccion.forEach(seccion => {
          queryParams.append('seccion[]', seccion)
        })
      }

      if (filtros.value.estado !== 'todas') {
        queryParams.set('estado', filtros.value.estado)
      }

      if (filtros.value.tema_visual.length > 0) {
        filtros.value.tema_visual.forEach(tema => {
          queryParams.append('tema_visual[]', tema)
        })
      }

      if (filtros.value.modo_competitivo !== undefined) {
        queryParams.set('modo_competitivo', filtros.value.modo_competitivo.toString())
      }

      // Ordenamiento por defecto
      queryParams.set('sort', 'created_at')
      queryParams.set('order', 'desc')

      const response = await $fetch<{
        success: boolean
        data: ClasesIndexData
        message?: string
      }>(`/api/profesor/clases?${queryParams.toString()}`)

      if (response.success) {
        clasesData.value = response.data
      } else {
        throw new Error(response.message || 'Error al cargar clases')
      }
      
    } catch (err: any) {
      error.value = err.message || 'Error al cargar las clases'
      console.error('Error cargando clases:', err)
    } finally {
      loading.value = false
    }
  }

  // Filtrado con debounce
  const filtrarClases = debounce(async () => {
    paginaActual.value = 1 // Reset a primera página
    await cargarClases()
  }, 300)

  // Cambiar página
  const cambiarPagina = async (page: number) => {
    paginaActual.value = page
    await cargarClases()
  }

  // Acciones masivas
  const ejecutarBulkAction = async (action: string, data?: any) => {
    if (clasesSeleccionadas.value.length === 0) {
      throw new Error('No hay clases seleccionadas')
    }

    loading.value = true
    
    try {
      const response = await $fetch<{
        success: boolean
        message: string
        data?: any
      }>('/api/profesor/clases/bulk', {
        method: 'POST',
        body: {
          action,
          clase_ids: clasesSeleccionadas.value,
          data
        }
      })

      if (response.success) {
        // Limpiar selección
        clasesSeleccionadas.value = []
        
        // Recargar datos
        await cargarClases()
        
        return response
      } else {
        throw new Error(response.message || 'Error en acción masiva')
      }
      
    } catch (err: any) {
      error.value = err.message || 'Error ejecutando acción masiva'
      throw err
    } finally {
      loading.value = false
    }
  }

  // Crear nueva clase
  const crearClase = async (datosClase: Partial<Clase>) => {
    loading.value = true
    
    try {
      const response = await $fetch<{
        success: boolean
        data: Clase
        message: string
      }>('/api/profesor/clases', {
        method: 'POST',
        body: datosClase
      })

      if (response.success) {
        await cargarClases() // Recargar lista
        return response.data
      } else {
        throw new Error(response.message || 'Error al crear clase')
      }
      
    } catch (err: any) {
      error.value = err.message || 'Error al crear la clase'
      throw err
    } finally {
      loading.value = false
    }
  }

  // Actualizar clase
  const actualizarClase = async (id: number, datosClase: Partial<Clase>) => {
    loading.value = true
    
    try {
      const response = await $fetch<{
        success: boolean
        data: Clase
        message: string
      }>(`/api/profesor/clases/${id}`, {
        method: 'PUT',
        body: datosClase
      })

      if (response.success) {
        await cargarClases() // Recargar lista
        return response.data
      } else {
        throw new Error(response.message || 'Error al actualizar clase')
      }
      
    } catch (err: any) {
      error.value = err.message || 'Error al actualizar la clase'
      throw err
    } finally {
      loading.value = false
    }
  }

  // Eliminar clase
  const eliminarClase = async (id: number) => {
    loading.value = true
    
    try {
      const response = await $fetch<{
        success: boolean
        message: string
      }>(`/api/profesor/clases/${id}`, {
        method: 'DELETE'
      })

      if (response.success) {
        await cargarClases() // Recargar lista
        return response
      } else {
        throw new Error(response.message || 'Error al eliminar clase')
      }
      
    } catch (err: any) {
      error.value = err.message || 'Error al eliminar la clase'
      throw err
    } finally {
      loading.value = false
    }
  }

  // Duplicar clase
  const duplicarClase = async (id: number) => {
    loading.value = true
    
    try {
      const response = await $fetch<{
        success: boolean
        data: Clase
        message: string
      }>(`/api/profesor/clases/${id}/duplicate`, {
        method: 'POST'
      })

      if (response.success) {
        await cargarClases() // Recargar lista
        return response.data
      } else {
        throw new Error(response.message || 'Error al duplicar clase')
      }
      
    } catch (err: any) {
      error.value = err.message || 'Error al duplicar la clase'
      throw err
    } finally {
      loading.value = false
    }
  }

  // Alternar estado de clase
  const toggleClaseEstado = async (id: number, activa: boolean) => {
    try {
      const response = await $fetch<{
        success: boolean
        data: Clase
        message: string
      }>(`/api/profesor/clases/${id}`, {
        method: 'PUT',
        body: { activa }
      })

      if (response.success && clasesData.value) {
        // Actualizar estado local
        const clase = clasesData.value.clases.find(c => c.id === id)
        if (clase) {
          clase.activa = activa
        }
        return response.data
      } else {
        throw new Error(response.message || 'Error al cambiar estado')
      }
      
    } catch (err: any) {
      error.value = err.message || 'Error al cambiar estado de la clase'
      throw err
    }
  }

  // Exportar clases
  const exportarClases = async (formato: 'csv' | 'excel' | 'pdf' = 'csv', ids?: number[]) => {
    try {
      const params = new URLSearchParams({
        formato,
        ...(ids && ids.length > 0 ? { ids: ids.join(',') } : {})
      })

      const response = await $fetch(`/api/profesor/clases/export?${params}`, {
        method: 'GET'
      })

      // Crear y descargar archivo
      const blob = new Blob([response], { 
        type: formato === 'csv' ? 'text/csv' : 
              formato === 'excel' ? 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' :
              'application/pdf'
      })
      
      const url = window.URL.createObjectURL(blob)
      const link = document.createElement('a')
      link.href = url
      link.download = `clases_${new Date().toISOString().split('T')[0]}.${formato}`
      document.body.appendChild(link)
      link.click()
      document.body.removeChild(link)
      window.URL.revokeObjectURL(url)
      
    } catch (err: any) {
      error.value = err.message || 'Error al exportar clases'
      throw err
    }
  }

  // Refrescar datos
  const refresh = () => cargarClases()

  // Limpiar filtros
  const limpiarFiltros = () => {
    filtros.value = {
      materia: [],
      grado: [],
      seccion: [],
      estado: 'todas',
      tema_visual: []
    }
    busqueda.value = ''
    clasesSeleccionadas.value = []
    filtrarClases()
  }

  // Seleccionar/deseleccionar todas las clases visibles
  const toggleSeleccionarTodas = () => {
    if (!clasesData.value?.clases) return
    
    const todasSeleccionadas = clasesData.value.clases.every(clase => 
      clasesSeleccionadas.value.includes(clase.id)
    )
    
    if (todasSeleccionadas) {
      // Deseleccionar todas
      clasesSeleccionadas.value = []
    } else {
      // Seleccionar todas las visibles
      clasesSeleccionadas.value = clasesData.value.clases.map(clase => clase.id)
    }
  }

  return {
    // Estado
    clasesData,
    loading,
    error,
    filtros,
    busqueda,
    clasesSeleccionadas,
    paginaActual,
    
    // Computed
    hayClases,
    totalClases,
    hayMasClases,
    
    // Métodos
    cargarClases,
    filtrarClases,
    cambiarPagina,
    ejecutarBulkAction,
    crearClase,
    actualizarClase,
    eliminarClase,
    duplicarClase,
    toggleClaseEstado,
    exportarClases,
    refresh,
    limpiarFiltros,
    toggleSeleccionarTodas
  }
}