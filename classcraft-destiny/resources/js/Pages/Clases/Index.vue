<template>
  <div class="min-h-screen bg-gradient-to-br from-[#0B0E16] via-[#1B1C29] to-[#0F1419]">
    <!-- Header con breadcrumbs y acciones principales -->
    <div class="sticky top-0 z-40 bg-gradient-to-r from-[#1B1C29]/95 via-[#2A3441]/95 to-[#1B1C29]/95 backdrop-blur-xl border-b border-[#C7B88A]/30">
      <div class="container mx-auto px-6 py-4">
        <div class="flex items-center justify-between">
          <!-- Breadcrumbs estilo Destiny -->
          <div class="flex items-center space-x-3">
            <div class="w-8 h-8 bg-gradient-to-br from-[#C7B88A] to-[#6EC1E4] rounded-lg flex items-center justify-center">
              <svg class="w-4 h-4 text-[#1B1C29]" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
              </svg>
            </div>
            <div>
              <h1 class="text-2xl font-bold text-white">Directorio de Operaciones</h1>
              <p class="text-sm text-[#C7B88A]">{{ clasesData?.meta?.total || 0 }} operaciones activas</p>
            </div>
          </div>

          <!-- Acciones principales -->
          <div class="flex items-center space-x-3">
            <!-- Modo de vista -->
            <div class="flex bg-black/30 rounded-lg p-1">
              <button
                @click="modoVista = 'grid'"
                :class="[
                  'px-3 py-2 rounded-md text-sm font-medium transition-all duration-200',
                  modoVista === 'grid' 
                    ? 'bg-[#C7B88A] text-[#1B1C29]' 
                    : 'text-gray-400 hover:text-white'
                ]"
              >
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M3 3h8v8H3V3zm0 10h8v8H3v-8zm10-10h8v8h-8V3zm0 10h8v8h-8v-8z"/>
                </svg>
              </button>
              <button
                @click="modoVista = 'list'"
                :class="[
                  'px-3 py-2 rounded-md text-sm font-medium transition-all duration-200',
                  modoVista === 'list' 
                    ? 'bg-[#C7B88A] text-[#1B1C29]' 
                    : 'text-gray-400 hover:text-white'
                ]"
              >
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
              </button>
            </div>

            <!-- Botón crear nueva clase -->
            <button
              @click="abrirModal('crear')"
              class="destiny-btn destiny-btn-primary flex items-center space-x-2"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
              </svg>
              <span>Nueva Operación</span>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Controles de filtrado y búsqueda -->
    <div class="container mx-auto px-6 py-6">
      <!-- Búsqueda principal -->
      <div class="search-container mb-6">
        <div class="relative">
          <svg class="search-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
          <input
            v-model="busqueda"
            @input="filtrarClases"
            type="text"
            placeholder="Buscar operaciones por nombre, materia, código..."
            class="search-input"
          >
          <div v-if="busqueda" class="absolute right-3 top-1/2 transform -translate-y-1/2">
            <button
              @click="busqueda = ''; filtrarClases()"
              class="text-gray-400 hover:text-white"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </button>
          </div>
        </div>
      </div>

      <!-- Filtros por categoría -->
      <div class="filter-tabs mb-6">
        <button
          v-for="filtro in filtrosDisponibles"
          :key="filtro.key"
          @click="toggleFiltro(filtro.key, filtro.value)"
          :class="[
            'filter-tab',
            esFiltroActivo(filtro.key, filtro.value) ? 'active' : ''
          ]"
        >
          <span class="mr-2">{{ filtro.icono }}</span>
          {{ filtro.label }}
          <span v-if="esFiltroActivo(filtro.key, filtro.value)" class="ml-2 text-xs">
            {{ contarClasesPorFiltro(filtro.key, filtro.value) }}
          </span>
        </button>
      </div>

      <!-- Filtros secundarios -->
      <div class="flex flex-wrap gap-4 mb-6">
        <!-- Filtro por grado -->
        <div class="flex items-center space-x-2">
          <label class="text-sm font-medium text-gray-300">Grado:</label>
          <select
            v-model="filtros.grado"
            @change="filtrarClases"
            class="destiny-select"
          >
            <option value="">Todos</option>
            <option v-for="grado in gradosDisponibles" :key="grado" :value="grado">
              {{ grado }}
            </option>
          </select>
        </div>

        <!-- Filtro por sección -->
        <div class="flex items-center space-x-2">
          <label class="text-sm font-medium text-gray-300">Sección:</label>
          <select
            v-model="filtros.seccion"
            @change="filtrarClases"
            class="destiny-select"
          >
            <option value="">Todas</option>
            <option v-for="seccion in seccionesDisponibles" :key="seccion" :value="seccion">
              Sección {{ seccion }}
            </option>
          </select>
        </div>

        <!-- Filtro por estado -->
        <div class="flex items-center space-x-2">
          <label class="text-sm font-medium text-gray-300">Estado:</label>
          <select
            v-model="filtros.estado"
            @change="filtrarClases"
            class="destiny-select"
          >
            <option value="todas">Todas</option>
            <option value="activas">Activas</option>
            <option value="inactivas">Inactivas</option>
            <option value="archivadas">Archivadas</option>
          </select>
        </div>
      </div>

      <!-- Acciones masivas -->
      <div v-if="clasesSeleccionadas.length > 0" class="bulk-actions mb-6">
        <div class="flex items-center space-x-4">
          <span class="text-sm font-medium text-white">
            {{ clasesSeleccionadas.length }} operación(es) seleccionada(s)
          </span>
          <div class="flex space-x-2">
            <button
              @click="ejecutarBulkAction('activate')"
              class="destiny-btn-secondary-sm"
            >
              <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
              </svg>
              Activar
            </button>
            <button
              @click="ejecutarBulkAction('deactivate')"
              class="destiny-btn-secondary-sm"
            >
              <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
              </svg>
              Desactivar
            </button>
            <button
              @click="ejecutarBulkAction('archive')"
              class="destiny-btn-secondary-sm"
            >
              <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8l6 6 6-6"/>
              </svg>
              Archivar
            </button>
            <button
              @click="ejecutarBulkAction('export')"
              class="destiny-btn-secondary-sm"
            >
              <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
              </svg>
              Exportar
            </button>
          </div>
          <button
            @click="clasesSeleccionadas = []"
            class="text-gray-400 hover:text-white"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Grid/Lista de clases -->
    <div class="container mx-auto px-6 pb-8">
      <!-- Loading state -->
      <div v-if="loading" class="text-center py-12">
        <div class="destiny-spinner-lg"></div>
        <p class="text-gray-400 mt-4">Cargando operaciones...</p>
      </div>

      <!-- Empty state -->
      <div v-else-if="!clasesData?.clases?.length" class="empty-state">
        <div class="empty-state-icon">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-16 h-16">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 11H5m14-7l2 2-2 2M5 13l2-2-2-2"/>
          </svg>
        </div>
        <h3 class="text-xl font-semibold text-white mb-2">No se encontraron operaciones</h3>
        <p class="text-gray-400 mb-6">
          {{ busqueda || hayFiltrosActivos ? 'Intenta ajustar tus filtros de búsqueda' : 'Comienza creando tu primera operación guardián' }}
        </p>
        <button
          v-if="!busqueda && !hayFiltrosActivos"
          @click="abrirModal('crear')"
          class="destiny-btn destiny-btn-primary"
        >
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
          </svg>
          Crear Primera Operación
        </button>
      </div>

      <!-- Grid de clases -->
      <div v-else-if="modoVista === 'grid'" class="activities-grid">
        <div
          v-for="clase in clasesData.clases"
          :key="clase.id"
          class="class-card"
          :style="{ '--class-theme-color': clase.color_tema, '--class-theme-secondary': '#6EC1E4' }"
          @click="abrirModal('preview', clase)"
        >
          <!-- Checkbox de selección -->
          <div class="absolute top-3 left-3 z-10">
            <input
              type="checkbox"
              :value="clase.id"
              v-model="clasesSeleccionadas"
              @click.stop
              class="destiny-checkbox"
            >
          </div>

          <!-- Banner de la clase -->
          <div
            class="class-banner"
            :style="{
              backgroundImage: clase.imagen_banner ? `url(${clase.imagen_banner})` : 'none'
            }"
          >
            <!-- Badge de estado -->
            <div :class="['class-status-badge', clase.activa ? 'activa' : 'inactiva']">
              {{ clase.activa ? 'Activa' : 'Inactiva' }}
            </div>

            <!-- Indicador de modo competitivo -->
            <div v-if="clase.modo_competitivo" class="absolute top-3 left-3">
              <div class="w-6 h-6 bg-orange-500/20 border border-orange-500 rounded-full flex items-center justify-center">
                <svg class="w-3 h-3 text-orange-500" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
              </div>
            </div>
          </div>

          <!-- Información de la clase -->
          <div class="space-y-3">
            <div>
              <h3 class="text-lg font-bold text-white mb-1">{{ clase.nombre }}</h3>
              <p class="text-sm text-gray-400">{{ clase.descripcion }}</p>
            </div>

            <!-- Metadatos -->
            <div class="flex flex-wrap gap-2 text-xs">
              <span class="bg-[#C7B88A]/20 text-[#C7B88A] px-2 py-1 rounded-full">
                {{ clase.materia }}
              </span>
              <span class="bg-[#6EC1E4]/20 text-[#6EC1E4] px-2 py-1 rounded-full">
                {{ clase.grado }} {{ clase.seccion }}
              </span>
              <span class="bg-gray-700/50 text-gray-300 px-2 py-1 rounded-full">
                {{ clase.codigo_acceso }}
              </span>
            </div>

            <!-- Estadísticas -->
            <div class="grid grid-cols-3 gap-3 pt-3 border-t border-gray-700/50">
              <div class="text-center">
                <div class="text-lg font-bold text-white">{{ clase.estudiantes_count }}</div>
                <div class="text-xs text-gray-400">Guardianes</div>
              </div>
              <div class="text-center">
                <div class="text-lg font-bold text-white">{{ clase.actividades_count }}</div>
                <div class="text-xs text-gray-400">Misiones</div>
              </div>
              <div class="text-center">
                <div class="text-lg font-bold text-white">{{ Math.round(clase.promedio_nivel) }}</div>
                <div class="text-xs text-gray-400">Nivel Prom.</div>
              </div>
            </div>

            <!-- Alertas -->
            <div v-if="clase.alertas.length > 0" class="space-y-1">
              <div
                v-for="alerta in clase.alertas"
                :key="alerta.mensaje"
                :class="[
                  'flex items-center space-x-2 text-xs px-2 py-1 rounded',
                  alerta.tipo === 'warning' ? 'bg-orange-500/20 text-orange-400' : '',
                  alerta.tipo === 'danger' ? 'bg-red-500/20 text-red-400' : '',
                  alerta.tipo === 'info' ? 'bg-blue-500/20 text-blue-400' : ''
                ]"
              >
                <span>{{ alerta.icono }}</span>
                <span>{{ alerta.mensaje }}</span>
                <span v-if="alerta.count" class="ml-auto font-semibold">({{ alerta.count }})</span>
              </div>
            </div>

            <!-- Acciones rápidas -->
            <div class="flex justify-between items-center pt-3 border-t border-gray-700/50">
              <div class="flex space-x-2">
                <button
                  @click.stop="abrirModal('editar', clase)"
                  class="destiny-btn-icon-sm"
                  title="Editar"
                >
                  <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                  </svg>
                </button>
                <button
                  @click.stop="duplicarClase(clase)"
                  class="destiny-btn-icon-sm"
                  title="Duplicar"
                >
                  <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                  </svg>
                </button>
              </div>
              <div class="text-xs text-gray-500">
                {{ formatearFecha(clase.created_at) }}
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Vista de lista -->
      <div v-else class="space-y-3">
        <div
          v-for="clase in clasesData.clases"
          :key="clase.id"
          class="bg-gradient-to-r from-[#1B1C29]/80 to-[#2A3441]/80 backdrop-blur-sm border border-[#C7B88A]/20 rounded-lg p-4 hover:border-[#C7B88A]/40 transition-all duration-300 cursor-pointer"
          @click="abrirModal('preview', clase)"
        >
          <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
              <!-- Checkbox -->
              <input
                type="checkbox"
                :value="clase.id"
                v-model="clasesSeleccionadas"
                @click.stop
                class="destiny-checkbox"
              >

              <!-- Info principal -->
              <div>
                <h3 class="text-lg font-semibold text-white">{{ clase.nombre }}</h3>
                <p class="text-sm text-gray-400">{{ clase.materia }} - {{ clase.grado }} {{ clase.seccion }}</p>
              </div>

              <!-- Badges -->
              <div class="flex space-x-2">
                <span :class="['px-2 py-1 rounded-full text-xs font-medium', clase.activa ? 'bg-green-500/20 text-green-400' : 'bg-orange-500/20 text-orange-400']">
                  {{ clase.activa ? 'Activa' : 'Inactiva' }}
                </span>
                <span v-if="clase.modo_competitivo" class="bg-orange-500/20 text-orange-400 px-2 py-1 rounded-full text-xs font-medium">
                  Competitivo
                </span>
              </div>
            </div>

            <!-- Stats y acciones -->
            <div class="flex items-center space-x-6">
              <!-- Stats -->
              <div class="flex space-x-4 text-sm">
                <div class="text-center">
                  <div class="font-semibold text-white">{{ clase.estudiantes_count }}</div>
                  <div class="text-xs text-gray-400">Guardianes</div>
                </div>
                <div class="text-center">
                  <div class="font-semibold text-white">{{ clase.actividades_count }}</div>
                  <div class="text-xs text-gray-400">Misiones</div>
                </div>
                <div class="text-center">
                  <div class="font-semibold text-white">{{ Math.round(clase.promedio_nivel) }}</div>
                  <div class="text-xs text-gray-400">Nivel Prom.</div>
                </div>
              </div>

              <!-- Acciones -->
              <div class="flex space-x-2">
                <button
                  @click.stop="abrirModal('editar', clase)"
                  class="destiny-btn-icon-sm"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                  </svg>
                </button>
                <button
                  @click.stop="toggleClaseEstado(clase)"
                  class="destiny-btn-icon-sm"
                >
                  <svg v-if="clase.activa" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                  <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h1m4 0h1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Paginación -->
      <div v-if="clasesData?.meta && clasesData.meta.last_page > 1" class="mt-8 flex justify-center">
        <nav class="flex space-x-2">
          <button
            v-for="page in pageNumbers"
            :key="page"
            @click="cambiarPagina(page)"
            :class="[
              'px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200',
              page === clasesData.meta.current_page
                ? 'bg-[#C7B88A] text-[#1B1C29]'
                : 'bg-black/30 text-gray-400 hover:text-white hover:bg-black/50'
            ]"
          >
            {{ page }}
          </button>
        </nav>
      </div>
    </div>

    <!-- Modals -->
    <NuevaClaseModal
      :show="modalActivo === 'crear'"
      @close="cerrarModal"
      @creada="onClaseCreada"
    />

    <EditarClaseModal
      :show="modalActivo === 'editar'"
      :clase="claseSeleccionada"
      @close="cerrarModal"
      @editada="onClaseEditada"
    />

    <PreviewClaseModal
      :show="modalActivo === 'preview'"
      :clase="claseSeleccionada"
      @close="cerrarModal"
      @editar="onEditarDesdePreview"
      @eliminar="onEliminarClase"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue'
import { debounce } from 'lodash'
import { useClasesIndex } from '@/composables/useClasesIndex'
import { useClaseModal } from '@/composables/useClaseModal'
import NuevaClaseModal from '@/components/Modals/NuevaClaseModal.vue'
import EditarClaseModal from '@/components/Modals/EditarClaseModal.vue'
import PreviewClaseModal from '@/components/Modals/PreviewClaseModal.vue'

// Interfaces
interface Clase {
  id: number
  nombre: string
  descripcion: string
  codigo_acceso: string
  materia: string
  grado: string
  seccion: string
  ano_escolar: number
  activa: boolean
  imagen_banner?: string
  color_tema: string
  fecha_inicio: string
  fecha_fin: string
  tema_visual: string
  modo_competitivo: boolean
  estudiantes_count: number
  actividades_count: number
  promedio_nivel: number
  alertas: AlertaClase[]
  created_at: string
}

interface AlertaClase {
  tipo: 'warning' | 'danger' | 'info'
  mensaje: string
  icono: string
  count?: number
}

// Composables
const {
  clasesData,
  loading,
  filtros,
  busqueda,
  clasesSeleccionadas,
  filtrarClases,
  ejecutarBulkAction,
  refresh
} = useClasesIndex()

const {
  modalActivo,
  claseSeleccionada,
  abrirModal,
  cerrarModal
} = useClaseModal()

// Estado local
const modoVista = ref<'grid' | 'list'>('grid')

// Datos estáticos
const gradosDisponibles = [
  '1° Primaria', '2° Primaria', '3° Primaria', '4° Primaria', '5° Primaria', '6° Primaria',
  '1° Secundaria', '2° Secundaria', '3° Secundaria', '4° Secundaria', '5° Secundaria'
]

const seccionesDisponibles = ['A', 'B', 'C', 'D', 'E', 'F']

// Filtros principales como tabs
const filtrosDisponibles = computed(() => [
  { key: 'materia', value: '', label: 'Todas las Materias', icono: '📚' },
  { key: 'materia', value: 'Matemáticas', label: 'Matemáticas', icono: '🔢' },
  { key: 'materia', value: 'Ciencias', label: 'Ciencias', icono: '🧪' },
  { key: 'materia', value: 'Historia', label: 'Historia', icono: '📜' },
  { key: 'materia', value: 'Literatura', label: 'Literatura', icono: '