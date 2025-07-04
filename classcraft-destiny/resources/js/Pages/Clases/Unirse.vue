<template>
  <Head title="Unirse a Clase" />

  <AuthenticatedLayout>
    <template #header>
      <div class="d-flex align-center">
        <v-btn
          icon="mdi-arrow-left"
          variant="text"
          :to="route('dashboard')"
          class="mr-3"
        ></v-btn>
        <h2 class="text-h4 font-weight-bold">Unirse a una Clase</h2>
      </div>
    </template>

    <v-container>
      <v-row justify="center">
        <v-col cols="12" md="6" lg="4">
          <v-card>
            <v-card-title class="text-center">
              <v-icon size="60" color="primary" class="mb-4">mdi-school</v-icon>
              <h3 class="text-h5">Ingresa el Código de Clase</h3>
            </v-card-title>
            
            <v-card-text>
              <v-form @submit.prevent="unirse">
                <v-text-field
                  v-model="form.codigo"
                  label="Código de clase"
                  placeholder="Ej: ABC123"
                  prepend-icon="mdi-key"
                  :error-messages="form.errors.codigo"
                  variant="outlined"
                  required
                  class="mb-4"
                  @input="form.codigo = $event.target.value.toUpperCase()"
                ></v-text-field>

                <v-alert
                  v-if="$page.props.flash.error"
                  type="error"
                  class="mb-4"
                >
                  {{ $page.props.flash.error }}
                </v-alert>

                <v-alert
                  v-if="$page.props.flash.success"
                  type="success"
                  class="mb-4"
                >
                  {{ $page.props.flash.success }}
                </v-alert>

                <div class="text-center">
                  <v-btn
                    type="submit"
                    :loading="form.processing"
                    variant="elevated"
                    color="primary"
                    size="large"
                    block
                  >
                    <v-icon class="mr-2">mdi-login</v-icon>
                    Unirse a la Clase
                  </v-btn>
                </div>
              </v-form>
            </v-card-text>
          </v-card>

          <!-- Información adicional -->
          <v-card class="mt-6">
            <v-card-title>
              <v-icon class="mr-2">mdi-information-outline</v-icon>
              ¿Cómo funciona?
            </v-card-title>
            
            <v-card-text>
              <v-list>
                <v-list-item>
                  <template #prepend>
                    <v-icon color="primary">mdi-numeric-1-circle</v-icon>
                  </template>
                  <v-list-item-title>Obtén el código</v-list-item-title>
                  <v-list-item-subtitle>
                    Tu profesor te proporcionará un código único de 6 caracteres
                  </v-list-item-subtitle>
                </v-list-item>

                <v-list-item>
                  <template #prepend>
                    <v-icon color="primary">mdi-numeric-2-circle</v-icon>
                  </template>
                  <v-list-item-title>Ingresa el código</v-list-item-title>
                  <v-list-item-subtitle>
                    Escribe el código en el campo de arriba
                  </v-list-item-subtitle>
                </v-list-item>

                <v-list-item>
                  <template #prepend>
                    <v-icon color="primary">mdi-numeric-3-circle</v-icon>
                  </template>
                  <v-list-item-title>¡Listo!</v-list-item-title>
                  <v-list-item-subtitle>
                    Te unirás automáticamente a la clase
                  </v-list-item-subtitle>
                </v-list-item>
              </v-list>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </AuthenticatedLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import { Head } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

// Formulario
const form = useForm({
  codigo: ''
})

// Método para unirse
const unirse = () => {
  form.post(route('clases.procesar-union'), {
    onSuccess: () => {
      // Se redirige automáticamente en el controlador
    }
  })
}
</script>