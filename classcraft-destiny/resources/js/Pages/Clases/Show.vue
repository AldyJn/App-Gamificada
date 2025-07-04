<template>
  <Head :title="clase.nombre" />

  <AuthenticatedLayout>
    <template #header>
      <div class="d-flex align-center justify-space-between">
        <div class="d-flex align-center">
          <v-btn
            icon="mdi-arrow-left"
            variant="text"
            :to="route('clases.index')"
            class="mr-3"
          ></v-btn>
          <div>
            <h2 class="text-h4 font-weight-bold">{{ clase.nombre }}</h2>
            <div class="text-subtitle-1 text-grey">
              Código: {{ clase.codigo }}
            </div>
          </div>
        </div>
        
        <div class="d-flex gap-2">
          <v-btn
            :to="route('clases.edit', clase.id)"
            variant="outlined"
            color="primary"
          >
            <v-icon class="mr-2">mdi-pencil</v-icon>
            Editar
          </v-btn>
          
          <v-btn
            @click="mostrarSelectorAleatorio = true"
            variant="elevated"
            color="success"
          >
            <v-icon class="mr-2">mdi-dice-6</v-icon>
            Selector Aleatorio
          </v-btn>
        </div>
      </div>
    </template>

    <v-container>
      <v-row>
        <!-- Información de la clase -->
        <v-col cols="12" md="4">
          <v-card>
            <v-card-title>
              <v-icon class="mr-2">mdi-information-outline</v-icon>
              Información
            </v-card-title>
            
            <v-card-text>
              <div class="mb-3">
                <div class="text-caption text-grey">Descripción</div>
                <div>{{ clase.descripcion || 'Sin descripción' }}</div>
              </div>
              
              <div class="mb-3">
                <div class="text-caption text-grey">Fecha de inicio</div>
                <div>{{ formatearFecha(clase.fecha_inicio) }}</div>
              </div>
              
              <div class="mb-3">
                <div class="text-caption text-grey">Fecha de fin</div>
                <div>{{ formatearFecha(clase.fecha_fin) }}</div>
              </div>
              
              <div class="mb-3">
                <div class="text-caption text-grey">Estado</div>
                <v-chip :color="getEstadoColor(clase)" size="small">
                  {{ getEstadoTexto(clase) }}
                </v-chip>
              </div>
              
              <div class="mb-3">
                <div class="text-caption text-grey">Estudiantes inscritos</div>
                <div class="text-h6">{{ estudiantes.length }}</div>
              </div>
            </v-card-text>
          </v-card>
        </v-col>

        <!-- Lista de estudiantes -->
        <v-col cols="12" md="8">
          <v-card>
            <v-card-title class="d-flex align-center justify-space-between">
              <div>
                <v-icon class="mr-2">mdi-account-multiple</v-icon>
                Estudiantes ({{ estudiantes.length }})
              </div>
              <v-btn
                @click="mostrarAgregarEstudiante = true"
                variant="outlined"
                color="primary"
                size="small"
              >
                <v-icon class="mr-2">mdi-plus</v-icon>
                Agregar
              </v-btn>
            </v-card-title>

            <v-card-text v-if="estudiantes.length === 0">
              <div class="text-center py-8">
                <v-icon size="60" color="grey-lighten-2">mdi-account-multiple</v-icon>
                <h4 class="text-h6 mt-4 mb-2">No hay estudiantes inscritos</h4>
                <p class="text-body-2 text-grey">
                  Agrega estudiantes o comparte el código de clase: <strong>{{ clase.codigo }}</strong>
                </p>
                <v-btn
                  @click="mostrarAgregarEstudiante = true"
                  variant="elevated"
                  color="primary"
                  class="mt-4"
                >
                  <v-icon class="mr-2">mdi-plus</v-icon>
                  Agregar Estudiante
                </v-btn>
              </div>
            </v-card-text>

            <v-list v-else>
              <v-list-item
                v-for="estudiante in estudiantes"
                :key="estudiante.id"
                class="border-b"
              >
                <template #prepend>
                  <v-avatar color="primary">
                    <span class="text-white">{{ getIniciales(estudiante.nombre) }}</span>
                  </v-avatar>
                </template>

                <v-list-item-title>
                  {{ estudiante.nombre }}
                </v-list-item-title>
                
                <v-list-item-subtitle>
                  {{ estudiante.correo }}
                </v-list-item-subtitle>

                <template #append>
                  <div class="d-flex align-center gap-2">
                    <v-chip
                      v-if="estudiante.pivot.fecha_inscripcion"
                      size="small"
                      color="success"
                    >
                      Inscrito {{ formatearFechaCorta(estudiante.pivot.fecha_inscripcion) }}
                    </v-chip>
                    
                    <v-menu>
                      <template #activator="{ props }">
                        <v-btn
                          icon="mdi-dots-vertical"
                          variant="text"
                          size="small"
                          v-bind="props"
                        ></v-btn>
                      </template>
                      
                      <v-list>
                        <v-list-item @click="verPerfilEstudiante(estudiante)">
                          <template #prepend>
                            <v-icon>mdi-account</v-icon>
                          </template>
                          <v-list-item-title>Ver perfil</v-list-item-title>
                        </v-list-item>
                        
                        <v-list-item @click="removerEstudiante(estudiante)">
                          <template #prepend>
                            <v-icon color="error">mdi-delete</v-icon>
                          </template>
                          <v-list-item-title>Remover</v-list-item-title>
                        </v-list-item>
                      </v-list>
                    </v-menu>
                  </div>
                </template>
              </v-list-item>
            </v-list>
          </v-card>
        </v-col>
      </v-row>
    </v-container>

    <!-- Modal selector aleatorio -->
    <v-dialog v-model="mostrarSelectorAleatorio" max-width="500">
      <v-card>
        <v-card-title>
          <v-icon class="mr-2">mdi-dice-6</v-icon>
          Selector Aleatorio de Estudiantes
        </v-card-title>
        
        <v-card-text>
          <div v-if="!estudianteSeleccionado" class="text-center py-8">
            <v-btn
              @click="seleccionarEstudianteAleatorio"
              :loading="seleccionando"
              variant="elevated"
              color="primary"
              size="large"
            >
              <v-icon class="mr-2">mdi-dice-6</v-icon>
              Seleccionar Estudiante
            </v-btn>
          </div>
          
          <div v-else class="text-center py-8">
            <v-avatar size="80" color="primary" class="mb-4">
              <span class="text-h4">{{ getIniciales(estudianteSeleccionado.nombre) }}</span>
            </v-avatar>
            
            <h3 class="text-h5 mb-2">{{ estudianteSeleccionado.nombre }}</h3>
            <p class="text-body-2 text-grey">{{ estudianteSeleccionado.correo }}</p>
            
            <v-btn
              @click="seleccionarEstudianteAleatorio"
              :loading="seleccionando"
              variant="outlined"
              color="primary"
              class="mt-4"
            >
              <v-icon class="mr-2">mdi-refresh</v-icon>
              Seleccionar Otro
            </v-btn>
          </div>
        </v-card-text>
        
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn @click="cerrarSelectorAleatorio" variant="text">
            Cerrar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!-- Modal agregar estudiante -->
    <v-dialog v-model="mostrarAgregarEstudiante" max-width="500">
      <v-card>
        <v-card-title>
          <v-icon class="mr-2">mdi-account-plus</v-icon>
          Agregar Estudiante
        </v-card-title>
        
        <v-card-text>
          <v-text-field
            v-model="busquedaEstudiante"
            label="Buscar estudiante por nombre o correo"
            prepend-icon="mdi-magnify"
            variant="outlined"
            @input="buscarEstudiantes"
          ></v-text-field>
          
          <v-list v-if="estudiantesBusqueda.length > 0">
            <v-list-item
              v-for="estudiante in estudiantesBusqueda"
              :key="estudiante.id"
              @click="agregarEstudiante(estudiante)"
              :disabled="estudianteYaInscrito(estudiante)"
            >
              <template #prepend>
                <v-avatar size="40" color="primary">
                  <span class="text-white">{{ getIniciales(estudiante.nombre) }}</span>
                </v-avatar>
              </template>
              
              <v-list-item-title>{{ estudiante.nombre }}</v-list-item-title>
              <v-list-item-subtitle>{{ estudiante.correo }}</v-list-item-subtitle>
              
              <template #append>
                <v-chip
                  v-if="estudianteYaInscrito(estudiante)"
                  size="small"
                  color="success"
                >
                  Ya inscrito
                </v-chip>
                <v-btn
                  v-else
                  icon="mdi-plus"
                  variant="text"
                  color="primary"
                  size="small"
                ></v-btn>
              </template>
            </v-list-item>
          </v-list>
          
          <div v-else-if="busquedaEstudiante.length > 2" class="text-center py-4">
            <v-icon size="60" color="grey-lighten-2">mdi-account-search</v-icon>
            <p class="text-body-2 text-grey mt-2">No se encontraron estudiantes</p>
          </div>
          
          <div v-else class="text-center py-4">
            <v-icon size="60" color="grey-lighten-2">mdi-account-search</v-icon>
            <p class="text-body-2 text-grey mt-2">Escribe al menos 3 caracteres para buscar</p>
          </div>
        </v-card-text>
        
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn @click="cerrarAgregarEstudiante" variant="text">
            Cerrar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

// Props
const props = defineProps({
  clase: {
    type: Object,
    required: true
  },
  estudiantes: {
    type: Array,
    required: true
  }
})

// Refs
const mostrarSelectorAleatorio = ref(false)
const mostrarAgregarEstudiante = ref(false)
const estudianteSeleccionado = ref(null)
const seleccionando = ref(false)
const busquedaEstudiante = ref('')
const estudiantesBusqueda = ref([])

// Métodos
const formatearFecha = (fecha) => {
  return new Date(fecha).toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

const formatearFechaCorta = (fecha) => {
  return new Date(fecha).toLocaleDateString('es-ES', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric'
  })
}

const getIniciales = (nombre) => {
  return nombre.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2)
}

const getEstadoColor = (clase) => {
  const now = new Date()
  const inicio = new Date(clase.fecha_inicio)
  const fin = new Date(clase.fecha_fin)
  
  if (now < inicio) return 'warning'
  if (now > fin) return 'error'
  return 'success'
}

const getEstadoTexto = (clase) => {
  const now = new Date()
  const inicio = new Date(clase.fecha_inicio)
  const fin = new Date(clase.fecha_fin)
  
  if (now < inicio) return 'Próxima'
  if (now > fin) return 'Finalizada'
  return 'Activa'
}

const seleccionarEstudianteAleatorio = async () => {
  if (props.estudiantes.length === 0) {
    alert('No hay estudiantes para seleccionar')
    return
  }
  
  seleccionando.value = true
  
  // Simular tiempo de selección para efecto dramático
  await new Promise(resolve => setTimeout(resolve, 1000))
  
  const indiceAleatorio = Math.floor(Math.random() * props.estudiantes.length)
  estudianteSeleccionado.value = props.estudiantes[indiceAleatorio]
  
  seleccionando.value = false
}

const cerrarSelectorAleatorio = () => {
  mostrarSelectorAleatorio.value = false
  estudianteSeleccionado.value = null
}

const buscarEstudiantes = async () => {
  if (busquedaEstudiante.value.length < 3) {
    estudiantesBusqueda.value = []
    return
  }
  
  try {
    const response = await fetch(`/api/estudiantes/buscar?q=${encodeURIComponent(busquedaEstudiante.value)}`)
    const data = await response.json()
    estudiantesBusqueda.value = data.estudiantes || []
  } catch (error) {
    console.error('Error al buscar estudiantes:', error)
    estudiantesBusqueda.value = []
  }
}

const estudianteYaInscrito = (estudiante) => {
  return props.estudiantes.some(e => e.id === estudiante.id)
}

const agregarEstudiante = (estudiante) => {
  if (estudianteYaInscrito(estudiante)) return
  
  router.post(route('clases.agregar-estudiante', props.clase.id), {
    estudiante_id: estudiante.id
  }, {
    onSuccess: () => {
      cerrarAgregarEstudiante()
    }
  })
}

const cerrarAgregarEstudiante = () => {
  mostrarAgregarEstudiante.value = false
  busquedaEstudiante.value = ''
  estudiantesBusqueda.value = []
}

const verPerfilEstudiante = (estudiante) => {
  router.visit(route('estudiantes.perfil', estudiante.id))
}

const removerEstudiante = (estudiante) => {
  if (confirm(`¿Estás seguro de remover a ${estudiante.nombre} de la clase?`)) {
    router.delete(route('clases.remover-estudiante', [props.clase.id, estudiante.id]))
  }
}
</script>