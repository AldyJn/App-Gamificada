<template>
  <Teleport to="body">
    <div
      v-if="show && clase"
      class="fixed inset-0 z-50 overflow-y-auto"
      @click="$emit('close')"
    >
      <!-- Overlay -->
      <div class="fixed inset-0 bg-black/75 backdrop-blur-sm transition-opacity"></div>
      
      <!-- Modal Container -->
      <div class="flex min-h-full items-center justify-center p-4">
        <div
          class="destiny-modal w-full max-w-4xl"
          @click.stop
        >
          <!-- Header con banner de clase -->
          <div class="relative">
            <!-- Banner de fondo -->
            <div
              class="h-40 w-full rounded-t-xl bg-gradient-to-br from-[#C7B88A] to-[#6EC1E4] relative overflow-hidden"
              :style="{
                backgroundImage: clase.imagen_banner ? `url(${clase.imagen_banner})` : 'none',
                '--class-theme-color': clase.color_tema
              }"
            >
              <!-- Overlay para texto legible -->
              <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
              
              <!-- Controles del modal -->
              <div class="absolute top-4 right-4 flex space-x-2">
                <button
                  @click="$emit('editar', clase)"
                  class="destiny-btn-icon bg-black/50 hover:bg-black/70 text-white"
                  title="Editar operación"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                  </svg>
                </button>
                <button
                  @click="$emit('close')"
                  class="destiny-btn-icon bg-black/50 hover:bg-black/70 text-white"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                  </svg>
                </button>
              </div>

              <!-- Información principal en el banner -->
              <div class="absolute bottom-4 left-6 right-6">
                <div class="flex items-end justify-between">
                  <div>
                    <h2 class="text-2xl font-bold text-white mb-2">{{ clase.nombre }}</h2>
                    <div class="flex items-center space-x-4 text-sm">
                      <span class="bg-white/20 backdrop-blur px-3 py-1 rounded-full text-white">
                        {{ clase.materia }}
                      </span>
                      <span class="bg-white/20 backdrop-blur px-3 py-1 rounded-full text-white">
                        {{ clase.grado }} {{ clase.seccion }}
                      </span>
                      <span :class="[
                        'px-3 py-1 rounded-full font-medium',
                        clase.activa 
                          ? 'bg-green-500/30 text-green-300 border border-green-500/50' 
                          : 'bg-orange-500/30 text-orange-300 border border-orange-500/50'
                      ]">
                        {{ clase.activa ? 'Operación Activa' : 'Operación Inactiva' }}
                      </span>
                    </div>
                  </div>
                  
                  <!-- Código de acceso -->
                  <div class="text-right">
                    <div class="text-xs text-white/70 mb-1">Código de Acceso</div>
                    <div class="flex items-center space-x-2">
                      <code class="bg-black/50 backdrop-blur px-3 py-2 rounded text-white font-mono text-lg">
                        {{ clase.codigo_acceso }}
                      </code>
                      <button
                        @click="copiarCodigo"
                        class="destiny-btn-icon-sm bg-black/50 hover:bg-black/70 text-white"
                        title="Copiar código"
                      >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Contenido del modal -->
          <div class="destiny-modal-content p-6 space-y-6">
            
            <!-- Descripción -->
            <div v-if="clase.descripcion" class="text-gray-300">
              <p>{{ clase.descripcion }}</p>
            </div>

            <!-- Estadísticas principales -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
              <div class="text-center p-4 bg-gradient-to-br from-blue-500/10 to-blue-600/10 border border-blue-500/20 rounded-lg">
                <div class="text-3xl font-bold text-blue-400 mb-1">{{ clase.estudiantes_count }}</div>
                <div class="text-sm text-gray-400">Guardianes Activos</div>
                <div class="text-xs text-blue-400 mt-1">{{ clase.estudiantes_activos }} conectados</div>
              </div>
              
              <div class="text-center p-4 bg-gradient-to-br from-green-500/10 to-green-600/10 border border-green-500/20 rounded-lg">
                <div class="text-3xl font-bold text-green-400 mb-1">{{ clase.actividades_count }}</div>
                <div class="text-sm text-gray-400">Misiones Disponibles</div>
                <div class="text-xs text-green-400 mt-1">Sistema activo</div>
              </div>
              
              <div class="text-center p-4 bg-gradient-to-br from-purple-500/10 to-purple-600/10 border border-purple-500/20 rounded-lg">
                <div class="text-3xl font-bold text-purple-400 mb-1">{{ Math.round(clase.promedio_nivel) }}</div>
                <div class="text-sm text-gray-400">Nivel Promedio</div>
                <div class="text-xs text-purple-400 mt-1">Luz creciente</div>
              </div>
              
              <div class="text-center p-4 bg-gradient-to-br from-orange-500/10 to-orange-600/10 border border-orange-500/20 rounded-lg">
                <div class="text-3xl font-bold text-orange-400 mb-1">{{ diasActiva }}</div>
                <div class="text-sm text-gray-400">Días Activa</div>
                <div class="text-xs text-orange-400 mt-1">Operación continua</div>
              </div>
            </div>

            <!-- Alertas si las hay -->
            <div v-if="clase.alertas && clase.alertas.length > 0" class="space-y-3">
              <h3 class="text-lg font-semibold text-white flex items-center">
                <svg class="w-5 h-5 mr-2 text-orange-400" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
                Alertas de Comando
              </h3>
              
              <div class="space-y-2">
                <div
                  v-for="alerta in clase.alertas"
                  :key="alerta.mensaje"
                  :class="[
                    'flex items-center justify-between p-3 rounded-lg border',
                    alerta.tipo === 'warning' ? 'bg-orange-500/10 border-orange-500/30 text-orange-300' :
                    alerta.tipo === 'danger' ? 'bg-red-500/10 border-red-500/30 text-red-300' :
                    alerta.tipo === 'info' ? 'bg-blue-500/10 border-blue-500/30 text-blue-300' :
                    'bg-green-500/10 border-green-500/30 text-green-300'
                  ]"
                >
                  <div class="flex items-center">
                    <span class="text-lg mr-3">{{ alerta.icono }}</span>
                    <span>{{ alerta.mensaje }}</span>
                  </div>
                  <span v-if="alerta.count" class="font-semibold">{{ alerta.count }}</span>
                </div>
              </div>
            </div>

            <!-- Configuración de la operación -->
            <div class="grid md:grid-cols-2 gap-6">
              <!-- Detalles temporales -->
              <div class="space-y-4">
                <h3 class="text-lg font-semibold text-white flex items-center">
                  <svg class="w-5 h-5 mr-2 text-[#C7B88A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                  </svg>
                  Cronología de Operación
                </h3>
                
                <div class="space-y-3 text-sm">
                  <div class="flex justify-between items-center p-3 bg-black/30 rounded-lg">
                    <span class="text-gray-400">Inicio de Operación:</span>
                    <span class="text-white font-medium">{{ formatearFecha(clase.fecha_inicio) }}</span>
                  </div>
                  
                  <div class="flex justify-between items-center p-3 bg-black/30 rounded-lg">
                    <span class="text-gray-400">Finalización:</span>
                    <span class="text-white font-medium">{{ formatearFecha(clase.fecha_fin) }}</span>
                  </div>
                  
                  <div class="flex justify-between items-center p-3 bg-black/30 rounded-lg">
                    <span class="text-gray-400">Año Escolar:</span>
                    <span class="text-white font-medium">{{ clase.ano_escolar }}</span>
                  </div>
                  
                  <div class="flex justify-between items-center p-3 bg-black/30 rounded-lg">
                    <span class="text-gray-400">Creada:</span>
                    <span class="text-white font-medium">{{ formatearFecha(clase.created_at) }}</span>
                  </div>
                </div>
              </div>

              <!-- Configuración visual y mecánicas -->
              <div class="space-y-4">
                <h3 class="text-lg font-semibold text-white flex items-center">
                  <svg class="w-5 h-5 mr-2 text-[#6EC1E4]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                  </svg>
                  Configuración de Sistema
                </h3>
                
                <div class="space-y-3 text-sm">
                  <div class="flex justify-between items-center p-3 bg-black/30 rounded-lg">
                    <span class="text-gray-400">Tema Visual:</span>
                    <span class="text-white font-medium capitalize">{{ formatearTemaVisual(clase.tema_visual) }}</span>
                  </div>
                  
                  <div class="flex justify-between items-center p-3 bg-black/30 rounded-lg">
                    <span class="text-gray-400">Modo Competitivo:</span>
                    <span :class="clase.modo_competitivo ? 'text-orange-400' : 'text-gray-400'">
                      {{ clase.modo_competitivo ? 'Activado' : 'Desactivado' }}
                    </span>
                  </div>
                  
                  <div v-if="clase.configuracion" class="p-3 bg-black/30 rounded-lg">
                    <div class="text-gray-400 mb-2">Multiplicador XP:</div>
                    <div class="flex items-center">
                      <div class="flex-1 bg-gray-700 rounded-full h-2">
                        <div 
                          class="bg-gradient-to-r from-[#C7B88A] to-[#6EC1E4] h-2 rounded-full transition-all duration-300"
                          :style="{ width: `${(clase.configuracion.multiplicador_xp || 1) * 50}%` }"
                        ></div>
                      </div>
                      <span class="ml-3 text-white font-medium">{{ clase.configuracion.multiplicador_xp || 1 }}x</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Código QR para que se unan estudiantes -->
            <div class="text-center p-6 bg-gradient-to-br from-[#1B1C29] to-[#2A3441] border border-[#C7B88A]/30 rounded-lg">
              <h3 class="text-lg font-semibold text-white mb-4">Invitación Guardián</h3>
              
              <!-- Aquí iría el QR code -->
              <div class="w-32 h-32 bg-white rounded-lg mx-auto mb-4 flex items-center justify-center">
                <div class="text-center">
                  <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M3 11h8V3H3v8zm2-6h4v4H5V5zm8-2v8h8V3h-8zm6 6h-4V5h4v4zM3 21h8v-8H3v8zm2-6h4v4H5v-4z"/>
                  </svg>
                  <div class="text-xs text-gray-500">Código QR</div>
                </div>
              </div>
              
              <p class="text-sm text-gray-400 mb-4">
                Los nuevos guardianes pueden escanear este código o usar el código de acceso para unirse a la operación.
              </p>
              
              <div class="flex justify-center space-x-4">
                <button
                  @click="copiarCodigo"
                  class="destiny-btn-secondary flex items-center space-x-2"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                  </svg>
                  <span>Copiar Código</span>
                </button>
                
                <button
                  @click="compartirClase"
                  class="destiny-btn-secondary flex items-center space-x-2"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"/>
                  </svg>
                  <span>Compartir</span>
                </button>
              </div>
            </div>
          </div>

          <!-- Footer con acciones -->
          <div class="destiny-modal-footer p-6 flex justify-between items-center border-t border-gray-700/50">
            <div class="flex space-x-3">
              <button
                @click="duplicarClase"
                class="destiny-btn-secondary flex items-center space-x-2"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
                <span>Duplicar</span>
              </button>
              
              <button
                @click="exportarClase"
                class="destiny-btn-secondary flex items-center space-x-2"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Exportar</span>
              </button>
            </div>
            
            <div class="flex space-x-3">
              <button
                @click="$emit('eliminar', clase)"
                class="destiny-btn-danger flex items-center space-x-2"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                <span>Eliminar</span>
              </button>
              
              <button
                @click="$emit('editar', clase)"
                class="destiny-btn destiny-btn-primary flex items-center space-x-2"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                <span>Editar Operación</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </Teleport>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import type { Clase } from '@/composables/useClasesIndex'

interface Props {
  show: boolean
  clase: Clase | null
}

const props = defineProps<Props>()

const emit = defineEmits<{
  close: []
  editar: [clase: Clase]
  eliminar: [clase: Clase]
}>()

// Computed properties
const diasActiva = computed(() => {
  if (!props.clase) return 0
  
  const inicio = new Date(props.clase.fecha_inicio)
  const ahora = new Date()
  const diferencia = ahora.getTime() - inicio.getTime()
  
  return Math.max(0, Math.floor(diferencia / (1000 * 60 * 60 * 24)))
})

// Métodos
const formatearFecha = (fecha: string) => {
  return new Date(fecha).toLocaleDateString('es-ES', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

const formatearTemaVisual = (tema: string) => {
  const temas: Record<string, string> = {
    'destiny_default': 'Destiny Clásico',
    'solar_system': 'Sistema Solar',
    'void_darkness': 'Vacío Oscuro',
    'arc_storm': 'Tormenta Arco',
    'forest_earth': 'Tierra Verde',
    'ice_crystal': 'Cristal Helado',
    'fire_phoenix': 'Fénix de Fuego',
    'cosmic_nebula': 'Nebulosa Cósmica'
  }
  
  return temas[tema] || tema.replace('_', ' ')
}

const copiarCodigo = async () => {
  if (!props.clase) return
  
  try {
    await navigator.clipboard.writeText(props.clase.codigo_acceso)
    // Aquí podrías mostrar una notificación de éxito
    console.log('Código copiado al portapapeles')
  } catch (error) {
    console.error('Error al copiar código:', error)
  }
}

const compartirClase = async () => {
  if (!props.clase) return
  
  const url = `${window.location.origin}/unirse/${props.clase.codigo_acceso}`
  const texto = `¡Únete a mi clase de ${props.clase.materia}! Código: ${props.clase.codigo_acceso}`
  
  if (navigator.share) {
    try {
      await navigator.share({
        title: `Clase: ${props.clase.nombre}`,
        text: texto,
        url: url
      })
    } catch (error) {
      console.error('Error al compartir:', error)
      await copiarEnlace(url)
    }
  } else {
    await copiarEnlace(url)
  }
}

const copiarEnlace = async (url: string) => {
  try {
    await navigator.clipboard.writeText(url)
    console.log('Enlace copiado al portapapeles')
  } catch (error) {
    console.error('Error al copiar enlace:', error)
  }
}

const duplicarClase = () => {
  if (props.clase) {
    // Emitir evento para duplicar en el componente padre
    console.log('Duplicando clase:', props.clase.id)
  }
}

const exportarClase = () => {
  if (props.clase) {
    // Emitir evento para exportar en el componente padre
    console.log('Exportando clase:', props.clase.id)
  }
}
</script>

<style scoped>
.destiny-modal {
  @apply bg-gradient-to-br from-[#1B1C29] to-[#2A3441] border border-[#C7B88A]/30 rounded-xl shadow-2xl backdrop-blur-xl;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.8);
}

.destiny-modal-content {
  @apply bg-gradient-to-br from-[#1B1C29]/50 to-[#2A3441]/50;
}

.destiny-modal-footer {
  @apply bg-gradient-to-r from-[#1B1C29]/80 to-[#2A3441]/80 backdrop-blur-sm;
}

.destiny-btn {
  @apply inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2;
}

.destiny-btn-primary {
  @apply bg-gradient-to-r from-[#C7B88A] to-[#6EC1E4] text-[#1B1C29] hover:from-[#B8A97A] hover:to-[#5DB1D4] focus:ring-[#C7B88A];
}

.destiny-btn-secondary {
  @apply bg-black/30 border border-gray-600 text-gray-300 hover:text-white hover:border-gray-500 hover:bg-black/50;
}

.destiny-btn-danger {
  @apply bg-gradient-to-r from-red-600 to-red-700 text-white hover:from-red-700 hover:to-red-800 focus:ring-red-500;
}

.destiny-btn-icon {
  @apply p-2 rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2;
}

.destiny-btn-icon-sm {
  @apply p-1 rounded transition-all duration-200 focus:outline-none;
}

/* Animaciones personalizadas */
@keyframes modal-appear {
  from {
    opacity: 0;
    transform: scale(0.95) translateY(-10px);
  }
  to {
    opacity: 1;
    transform: scale(1) translateY(0);
  }
}

.destiny-modal {
  animation: modal-appear 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Efectos de hover mejorados */
.destiny-btn:hover {
  transform: translateY(-1px);
}

.destiny-btn-icon:hover {
  transform: scale(1.05);
}

/* Responsive */
@media (max-width: 768px) {
  .destiny-modal {
    @apply mx-4 max-w-none;
  }
  
  .destiny-modal-footer {
    @apply flex-col space-y-3;
  }
  
  .destiny-modal-footer > div {
    @apply w-full justify-center;
  }
}

@media (max-width: 640px) {
  .grid.md\\:grid-cols-2 {
    @apply grid-cols-1;
  }
  
  .grid.grid-cols-2.md\\:grid-cols-4 {
    @apply grid-cols-2;
  }
}
</style>