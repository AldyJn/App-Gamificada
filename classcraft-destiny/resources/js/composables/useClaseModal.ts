// composables/useClaseModal.ts
import { ref, computed } from 'vue'
import type { Ref } from 'vue'
import type { Clase } from './useClasesIndex'

// Tipos de modal disponibles
export type TipoModal = 'crear' | 'editar' | 'preview' | 'eliminar' | 'duplicar' | null

// Estado reactivo del modal
const modalActivo = ref<TipoModal>(null)
const claseSeleccionada = ref<Clase | null>(null)
const configuracionModal = ref<Record<string, any>>({})

export const useClaseModal = () => {
  
  // Computed properties
  const modalAbierto = computed(() => modalActivo.value !== null)
  
  const esModalCrear = computed(() => modalActivo.value === 'crear')
  const esModalEditar = computed(() => modalActivo.value === 'editar')
  const esModalPreview = computed(() => modalActivo.value === 'preview')
  const esModalEliminar = computed(() => modalActivo.value === 'eliminar')
  const esModalDuplicar = computed(() => modalActivo.value === 'duplicar')

  // Métodos principales
  const abrirModal = (tipo: TipoModal, clase?: Clase | null, config?: Record<string, any>) => {
    modalActivo.value = tipo
    claseSeleccionada.value = clase || null
    configuracionModal.value = config || {}
    
    // Agregar clase al body para prevenir scroll
    if (tipo && typeof document !== 'undefined') {
      document.body.classList.add('modal-open')
    }
  }

  const cerrarModal = () => {
    modalActivo.value = null
    claseSeleccionada.value = null
    configuracionModal.value = {}
    
    // Remover clase del body
    if (typeof document !== 'undefined') {
      document.body.classList.remove('modal-open')
    }
  }

  // Métodos específicos para cada tipo de modal
  const abrirCrear = (config?: Record<string, any>) => {
    abrirModal('crear', null, config)
  }

  const abrirEditar = (clase: Clase, config?: Record<string, any>) => {
    abrirModal('editar', clase, config)
  }

  const abrirPreview = (clase: Clase, config?: Record<string, any>) => {
    abrirModal('preview', clase, config)
  }

  const abrirEliminar = (clase: Clase, config?: Record<string, any>) => {
    abrirModal('eliminar', clase, {
      titulo: `¿Eliminar "${clase.nombre}"?`,
      mensaje: 'Esta acción no se puede deshacer. Todos los datos asociados se perderán.',
      ...config
    })
  }

  const abrirDuplicar = (clase: Clase, config?: Record<string, any>) => {
    abrirModal('duplicar', clase, {
      titulo: `Duplicar "${clase.nombre}"`,
      ...config
    })
  }

  // Navegación entre modals
  const cambiarModal = (nuevoTipo: TipoModal, nuevaClase?: Clase, config?: Record<string, any>) => {
    modalActivo.value = nuevoTipo
    if (nuevaClase !== undefined) {
      claseSeleccionada.value = nuevaClase
    }
    if (config) {
      configuracionModal.value = { ...configuracionModal.value, ...config }
    }
  }

  // Transición de preview a editar (patrón común)
  const previewAEditar = () => {
    if (claseSeleccionada.value) {
      cambiarModal('editar', claseSeleccionada.value)
    }
  }

  // Transición de editar a preview
  const editarAPreview = () => {
    if (claseSeleccionada.value) {
      cambiarModal('preview', claseSeleccionada.value)
    }
  }

  // Manejo de eventos de teclado
  const manejarTecla = (event: KeyboardEvent) => {
    if (!modalAbierto.value) return

    switch (event.key) {
      case 'Escape':
        cerrarModal()
        break
      case 'Enter':
        // Solo en modal de confirmación
        if (modalActivo.value === 'eliminar') {
          // Emitir evento de confirmación
        }
        break
    }
  }

  // Auto-setup de event listeners
  if (typeof window !== 'undefined') {
    window.addEventListener('keydown', manejarTecla)
    
    // Cleanup en unmount (si se usa en componente)
    const cleanup = () => {
      window.removeEventListener('keydown', manejarTecla)
      if (modalAbierto.value) {
        cerrarModal()
      }
    }
    
    // Exponer cleanup para componentes que lo necesiten
    const onCleanup = (callback: () => void) => {
      if (typeof window !== 'undefined') {
        window.addEventListener('beforeunload', callback)
      }
    }
  }

  return {
    // Estado
    modalActivo,
    claseSeleccionada,
    configuracionModal,
    
    // Computed
    modalAbierto,
    esModalCrear,
    esModalEditar,
    esModalPreview,
    esModalEliminar,
    esModalDuplicar,
    
    // Métodos generales
    abrirModal,
    cerrarModal,
    cambiarModal,
    
    // Métodos específicos
    abrirCrear,
    abrirEditar,
    abrirPreview,
    abrirEliminar,
    abrirDuplicar,
    
    // Navegación
    previewAEditar,
    editarAPreview,
    
    // Utilidades
    manejarTecla
  }
}