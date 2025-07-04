<template>
  <Head title="Mis Clases" />

  <AuthenticatedLayout>
    <template #header>
      <div class="d-flex align-center justify-space-between">
        <h2 class="text-h4 font-weight-bold">Mis Clases</h2>
        <v-btn
          :to="route('clases.create')"
          variant="elevated"
          color="primary"
          size="large"
        >
          <v-icon class="mr-2">mdi-plus</v-icon>
          Crear Clase
        </v-btn>
      </div>
    </template>

    <v-container>
      <!-- Estadísticas rápidas -->
      <v-row class="mb-6">
        <v-col cols="12" md="3">
          <v-card>
            <v-card-text>
              <div class="d-flex align-center">
                <v-icon size="40" color="primary" class="mr-3">mdi-google-classroom</v-icon>
                <div>
                  <div class="text-h4 font-weight-bold">{{ clases.length }}</div>
                  <div class="text-caption">Total de Clases</div>
                </div>
              </div>
            </v-card-text>
          </v-card>
        </v-col>

        <v-col cols="12" md="3">
          <v-card>
            <v-card-text>
              <div class="d-flex align-center">
                <v-icon size="40" color="success" class="mr-3">mdi-account-multiple</v-icon>
                <div>
                  <div class="text-h4 font-weight-bold">{{ totalEstudiantes }}</div>
                  <div class="text-caption">Estudiantes</div>
                </div>
              </div>
            </v-card-text>
          </v-card>
        </v-col>

        <v-col cols="12" md="3">
          <v-card>
            <v-card-text>
              <div class="d-flex align-center">
                <v-icon size="40" color="warning" class="mr-3">mdi-clock-outline</v-icon>
                <div>
                  <div class="text-h4 font-weight-bold">{{ clasesActivas }}</div>
                  <div class="text-caption">Clases Activas</div>
                </div>
              </div>
            </v-card-text>
          </v-card>
        </v-col>

        <v-col cols="12" md="3">
          <v-card>
            <v-card-text>
              <div class="d-flex align-center">
                <v-icon size="40" color="info" class="mr-3">mdi-clipboard-text</v-icon>
                <div>
                  <div class="text-h4 font-weight-bold">{{ totalActividades }}</div>
                  <div class="text-caption">Actividades</div>
                </div>
              </div>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>

      <!-- Lista de clases -->
      <v-row>
        <v-col cols="12">
          <v-card>
            <v-card-title>
              <v-icon class="mr-2">mdi-format-list-bulleted</v-icon>
              Lista de Clases
            </v-card-title>
            
            <v-card-text v-if="clases.length === 0">
              <div class="text-center py-8">
                <v-icon size="80" color="grey-lighten-2">mdi-google-classroom</v-icon>
                <h3 class="text-h6 mt-4 mb-2">No tienes clases creadas</h3>
                <p class="text-body-2 text-grey">Crea tu primera clase para comenzar</p>
                <v-btn
                  :to="route('clases.create')"
                  variant="elevated"
                  color="primary"
                  class="mt-4"
                >
                  <v-icon class="mr-2">mdi-plus</v-icon>
                  Crear Primera Clase
                </v-btn>
              </div>
            </v-card-text>

            <v-list v-else>
              <v-list-item
                v-for="clase in clases"
                :key="clase.id"
                @click="$inertia.visit(route('clases.show', clase.id))"
                class="border-b"
              >
                <template #prepend>
                  <v-avatar :color="getClaseColor(clase)" class="mr-3">
                    <v-icon>mdi-google-classroom</v-icon>
                  </v-avatar>
                </template>

                <v-list-item-title class="font-weight-bold">
                  {{ clase.nombre }}
                </v-list-item-title>
                
                <v-list-item-subtitle>
                  {{ clase.descripcion }}
                </v-list-item-subtitle>

                <template #append>
                  <div class="text-right">
                    <v-chip
                      :color="getEstadoColor(clase)"
                      size="small"
                      class="mb-1"
                    >
                      {{ getEstadoTexto(clase) }}
                    </v-chip>
                    <div class="text-caption">
                      {{ clase.estudiantes_count }} estudiantes
                    </div>
                    <div class="text-caption text-grey">
                      Código: {{ clase.codigo }}
                    </div>
                  </div>
                </template>
              </v-list-item>
            </v-list>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </AuthenticatedLayout>
</template>

<script setup>
import { computed } from 'vue'
import { Head } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

// Props
const props = defineProps({
  clases: {
    type: Array,
    required: true
  }
})

// Computed
const totalEstudiantes = computed(() => {
  return props.clases.reduce((total, clase) => total + (clase.estudiantes_count || 0), 0)
})

const clasesActivas = computed(() => {
  const now = new Date()
  return props.clases.filter(clase => {
    const inicio = new Date(clase.fecha_inicio)
    const fin = new Date(clase.fecha_fin)
    return inicio <= now && now <= fin
  }).length
})

const totalActividades = computed(() => {
  return props.clases.reduce((total, clase) => total + (clase.actividades_count || 0), 0)
})

// Métodos
const getClaseColor = (clase) => {
  const colors = ['primary', 'secondary', 'success', 'info', 'warning', 'error']
  return colors[clase.id % colors.length]
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
</script>