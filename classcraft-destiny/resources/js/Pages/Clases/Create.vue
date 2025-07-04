<template>
  <Head title="Crear Clase" />

  <AuthenticatedLayout>
    <!-- Fondo animado estilo Destiny -->
    <div class="destiny-background"></div>
    
    <div class="min-h-screen relative">
      <!-- Header HUD -->
      <div class="fixed top-6 left-1/2 transform -translate-x-1/2 z-30">
        <div class="destiny-panel px-8 py-4">
          <h1 class="destiny-title text-2xl">
            FORJAR NUEVA MISIÓN
          </h1>
        </div>
      </div>

      <!-- Formulario principal -->
      <div class="pt-32 px-8 max-w-4xl mx-auto">
        <form @submit.prevent="submit" class="space-y-6">
          <!-- Panel de información básica -->
          <div class="destiny-card">
            <div class="card-header">
              <i class="mdi mdi-information text-cyan-400 mr-2"></i>
              INFORMACIÓN DE LA MISIÓN
            </div>
            
            <div class="card-content space-y-4">
              <!-- Nombre -->
              <div class="form-group">
                <label class="form-label">
                  <i class="mdi mdi-tag mr-1"></i>
                  Nombre de la Clase
                </label>
                <input
                  v-model="form.nombre"
                  type="text"
                  class="destiny-input"
                  placeholder="Ej: Matemáticas Avanzadas"
                  required
                />
                <div v-if="form.errors.nombre" class="error-text">
                  {{ form.errors.nombre }}
                </div>
              </div>

              <!-- Descripción -->
              <div class="form-group">
                <label class="form-label">
                  <i class="mdi mdi-text mr-1"></i>
                  Descripción
                </label>
                <textarea
                  v-model="form.descripcion"
                  class="destiny-input"
                  rows="3"
                  placeholder="Descripción de la clase y objetivos..."
                ></textarea>
              </div>

              <!-- Grado y Sección -->
              <div class="grid grid-cols-2 gap-4">
                <div class="form-group">
                  <label class="form-label">
                    <i class="mdi mdi-school mr-1"></i>
                    Grado
                  </label>
                  <input
                    v-model="form.grado"
                    type="text"
                    class="destiny-input"
                    placeholder="Ej: 10°"
                    required
                  />
                </div>
                
                <div class="form-group">
                  <label class="form-label">
                    <i class="mdi mdi-alpha-s-box mr-1"></i>
                    Sección
                  </label>
                  <input
                    v-model="form.seccion"
                    type="text"
                    class="destiny-input"
                    placeholder="Ej: A"
                    required
                  />
                </div>
              </div>

              <!-- Fechas -->
              <div class="grid grid-cols-2 gap-4">
                <div class="form-group">
                  <label class="form-label">
                    <i class="mdi mdi-calendar-start mr-1"></i>
                    Fecha de Inicio
                  </label>
                  <input
                    v-model="form.fecha_inicio"
                    type="date"
                    class="destiny-input"
                    :min="fechaHoy"
                    required
                  />
                </div>
                
                <div class="form-group">
                  <label class="form-label">
                    <i class="mdi mdi-calendar-end mr-1"></i>
                    Fecha de Fin
                  </label>
                  <input
                    v-model="form.fecha_fin"
                    type="date"
                    class="destiny-input"
                    :min="form.fecha_inicio || fechaHoy"
                    required
                  />
                </div>
              </div>
            </div>
          </div>

          <!-- Botones de acción -->
          <div class="flex justify-center space-x-4">
            <button
              type="button"
              @click="$inertia.visit(route('dashboard'))"
              class="destiny-btn destiny-btn-ghost"
            >
              <i class="mdi mdi-arrow-left mr-2"></i>
              CANCELAR
            </button>
            
            <button
              type="submit"
              :disabled="form.processing"
              class="destiny-btn destiny-btn-primary"
            >
              <i class="mdi mdi-plus-circle mr-2"></i>
              {{ form.processing ? 'CREANDO...' : 'CREAR MISIÓN' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Head, useForm } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const form = useForm({
  nombre: '',
  descripcion: '',
  grado: '',
  seccion: '',
  fecha_inicio: '',
  fecha_fin: ''
})

const fechaHoy = computed(() => {
  return new Date().toISOString().split('T')[0]
})

const submit = () => {
  form.post(route('clases.store'), {
    onSuccess: () => {
      // El controlador redirige al dashboard con mensaje de éxito
    }
  })
}
</script>

<style scoped>
/* Estilos Destiny */
.destiny-background {
  @apply fixed inset-0 bg-black;
  background-image: 
    radial-gradient(ellipse at top, #1a1a2e 0%, #0a0a0a 50%),
    radial-gradient(ellipse at bottom, #16213e 0%, #0a0a0a 50%);
}

.destiny-panel {
  @apply bg-gray-900/90 border border-cyan-500/30 rounded-lg;
  box-shadow: 0 0 20px rgba(6, 182, 212, 0.3);
}

.destiny-title {
  @apply text-cyan-400 font-bold tracking-wider;
  text-shadow: 0 0 10px rgba(6, 182, 212, 0.5);
}

.destiny-card {
  @apply bg-gray-800/80 border border-gray-700 rounded-lg overflow-hidden;
  backdrop-filter: blur(10px);
}

.card-header {
  @apply bg-gray-900/90 px-6 py-4 text-cyan-400 font-bold flex items-center;
  border-bottom: 1px solid rgba(6, 182, 212, 0.3);
}

.card-content {
  @apply p-6;
}

.form-group {
  @apply space-y-2;
}

.form-label {
  @apply text-gray-300 text-sm font-medium flex items-center;
}

.destiny-input {
  @apply w-full bg-gray-900/50 border border-gray-600 rounded-lg px-4 py-2 text-white;
  @apply focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 focus:outline-none;
  @apply transition-all duration-200;
}

.destiny-input:hover {
  @apply border-gray-500;
}

.error-text {
  @apply text-red-400 text-sm mt-1;
}

.destiny-btn {
  @apply px-6 py-3 rounded-lg font-medium transition-all duration-200;
  @apply flex items-center justify-center;
}

.destiny-btn-primary {
  @apply bg-cyan-600 text-white;
  @apply hover:bg-cyan-700 hover:shadow-lg;
  box-shadow: 0 0 20px rgba(6, 182, 212, 0.4);
}

.destiny-btn-ghost {
  @apply bg-transparent text-gray-400 border border-gray-600;
  @apply hover:bg-gray-800 hover:text-white hover:border-gray-500;
}

.destiny-btn:disabled {
  @apply opacity-50 cursor-not-allowed;
}
</style>