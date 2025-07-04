<template>
  <Head title="Crear Clase" />

  <AuthenticatedLayout>
    <template #header>
      <div class="d-flex align-center">
        <v-btn
          icon="mdi-arrow-left"
          variant="text"
          :to="route('clases.index')"
          class="mr-3"
        ></v-btn>
        <h2 class="text-h4 font-weight-bold">Crear Nueva Clase</h2>
      </div>
    </template>

    <v-container>
      <v-row justify="center">
        <v-col cols="12" md="8" lg="6">
          <v-card>
            <v-card-title>
              <v-icon class="mr-2">mdi-google-classroom</v-icon>
              Información de la Clase
            </v-card-title>
            
            <v-card-text>
              <v-form @submit.prevent="submit">
                <v-row>
                  <!-- Nombre de la clase -->
                  <v-col cols="12">
                    <v-text-field
                      v-model="form.nombre"
                      label="Nombre de la clase *"
                      placeholder="Ej: Matemáticas Avanzadas"
                      prepend-icon="mdi-book"
                      :error-messages="form.errors.nombre"
                      variant="outlined"
                      required
                    ></v-text-field>
                  </v-col>

                  <!-- Descripción -->
                  <v-col cols="12">
                    <v-textarea
                      v-model="form.descripcion"
                      label="Descripción"
                      placeholder="Descripción de la clase y objetivos de aprendizaje"
                      prepend-icon="mdi-text"
                      :error-messages="form.errors.descripcion"
                      variant="outlined"
                      rows="3"
                    ></v-textarea>
                  </v-col>

                  <!-- Fecha de inicio -->
                  <v-col cols="12" md="6">
                    <v-text-field
                      v-model="form.fecha_inicio"
                      label="Fecha de inicio *"
                      type="date"
                      prepend-icon="mdi-calendar-start"
                      :error-messages="form.errors.fecha_inicio"
                      variant="outlined"
                      required
                    ></v-text-field>
                  </v-col>

                  <!-- Fecha de fin -->
                  <v-col cols="12" md="6">
                    <v-text-field
                      v-model="form.fecha_fin"
                      label="Fecha de fin *"
                      type="date"
                      prepend-icon="mdi-calendar-end"
                      :error-messages="form.errors.fecha_fin"
                      variant="outlined"
                      required
                    ></v-text-field>
                  </v-col>

                  <!-- Código de clase -->
                  <v-col cols="12" md="8">
                    <v-text-field
                      v-model="form.codigo"
                      label="Código de clase"
                      placeholder="Se generará automáticamente si se deja vacío"
                      prepend-icon="mdi-key"
                      :error-messages="form.errors.codigo"
                      variant="outlined"
                      hint="Los estudiantes usarán este código para unirse"
                      persistent-hint
                    ></v-text-field>
                  </v-col>

                  <!-- Botón generar código -->
                  <v-col cols="12" md="4">
                    <v-btn
                      @click="generarCodigo"
                      variant="outlined"
                      color="primary"
                      block
                      class="mt-2"
                    >
                      <v-icon class="mr-2">mdi-refresh</v-icon>
                      Generar Código
                    </v-btn>
                  </v-col>

                  <!-- Configuraciones adicionales -->
                  <v-col cols="12">
                    <v-divider class="my-4"></v-divider>
                    <h4 class="text-h6 mb-4">Configuraciones</h4>
                  </v-col>

                  <v-col cols="12" md="6">
                    <v-checkbox
                      v-model="form.permitir_inscripcion"
                      label="Permitir inscripción libre"
                      color="primary"
                      :error-messages="form.errors.permitir_inscripcion"
                    ></v-checkbox>
                  </v-col>

                  <v-col cols="12" md="6">
                    <v-checkbox
                      v-model="form.notificar_actividades"
                      label="Notificar nuevas actividades"
                      color="primary"
                      :error-messages="form.errors.notificar_actividades"
                    ></v-checkbox>
                  </v-col>
                </v-row>
              </v-form>
            </v-card-text>

            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn
                :to="route('clases.index')"
                variant="text"
                color="grey"
              >
                Cancelar
              </v-btn>
              <v-btn
                @click="submit"
                :loading="form.processing"
                variant="elevated"
                color="primary"
              >
                <v-icon class="mr-2">mdi-plus</v-icon>
                Crear Clase
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Head, useForm } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

// Formulario
const form = useForm({
  nombre: '',
  descripcion: '',
  fecha_inicio: '',
  fecha_fin: '',
  codigo: '',
  permitir_inscripcion: true,
  notificar_actividades: true
})

// Funciones
const generarCodigo = () => {
  // Generar código aleatorio de 6 caracteres
  const caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'
  let resultado = ''
  for (let i = 0; i < 6; i++) {
    resultado += caracteres.charAt(Math.floor(Math.random() * caracteres.length))
  }
  form.codigo = resultado
}

const submit = () => {
  form.post(route('clases.store'), {
    onSuccess: () => {
      // Redirigir a la lista de clases
    },
    onError: () => {
      // Mostrar errores
    }
  })
}

// Validar fechas
const fechaFinMinima = computed(() => {
  return form.fecha_inicio || new Date().toISOString().split('T')[0]
})
</script>