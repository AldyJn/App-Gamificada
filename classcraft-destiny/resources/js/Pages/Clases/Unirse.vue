<template>
  <Head title="Unirse a Clase" />

  <AuthenticatedLayout>
    <div class="destiny-background"></div>
    
    <div class="min-h-screen relative">
      <!-- Header HUD -->
      <div class="fixed top-6 left-1/2 transform -translate-x-1/2 z-30">
        <div class="destiny-panel px-8 py-4">
          <h1 class="destiny-title text-2xl">
            UNIRSE A MISIÓN
          </h1>
        </div>
      </div>

      <div class="pt-32 px-8 max-w-4xl mx-auto">
        <!-- Paso 1: Ingresar código -->
        <div v-if="!claseEncontrada" class="destiny-card">
          <div class="card-header">
            <i class="mdi mdi-key-variant text-purple-400 mr-2"></i>
            CÓDIGO DE ACCESO
          </div>
          
          <div class="card-content">
            <div class="text-center mb-8">
              <p class="text-gray-300 text-lg">
                Ingresa el código de invitación proporcionado por tu profesor
              </p>
            </div>
            
            <div class="max-w-md mx-auto">
              <div class="form-group">
                <input
                  v-model="codigoTemp"
                  @input="codigoTemp = codigoTemp.toUpperCase()"
                  type="text"
                  class="destiny-input text-center text-2xl tracking-widest"
                  placeholder="ABC123"
                  maxlength="6"
                  autofocus
                />
                <div v-if="errorCodigo" class="error-text text-center mt-2">
                  {{ errorCodigo }}
                </div>
              </div>
              
              <button
                @click="verificarCodigo"
                :disabled="codigoTemp.length !== 6 || verificando"
                class="destiny-btn destiny-btn-primary w-full mt-6"
              >
                <i class="mdi mdi-magnify mr-2"></i>
                {{ verificando ? 'VERIFICANDO...' : 'VERIFICAR CÓDIGO' }}
              </button>
            </div>
          </div>
        </div>

        <!-- Paso 2: Crear personaje -->
        <form v-else @submit.prevent="submit" class="space-y-6">
          <!-- Información de la clase -->
          <div class="destiny-card">
            <div class="card-header">
              <i class="mdi mdi-information text-cyan-400 mr-2"></i>
              MISIÓN ENCONTRADA
            </div>
            <div class="card-content">
              <div class="bg-gray-900/50 rounded-lg p-4 border border-cyan-500/30">
                <h3 class="text-xl font-bold text-cyan-400 mb-2">{{ claseEncontrada.nombre }}</h3>
                <p class="text-gray-300">{{ claseEncontrada.descripcion }}</p>
                <div class="mt-3 flex items-center space-x-4 text-sm text-gray-400">
                  <span><i class="mdi mdi-school mr-1"></i>{{ claseEncontrada.grado }}</span>
                  <span><i class="mdi mdi-alpha-s-box mr-1"></i>{{ claseEncontrada.seccion }}</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Creación de personaje -->
          <div class="destiny-card">
            <div class="card-header">
              <i class="mdi mdi-account-plus text-purple-400 mr-2"></i>
              CREAR TU GUARDIÁN
            </div>
            
            <div class="card-content space-y-6">
              <!-- Nombre del personaje -->
              <div class="form-group">
                <label class="form-label">
                  <i class="mdi mdi-sword mr-1"></i>
                  Nombre de tu Guardián
                </label>
                <input
                  v-model="form.personaje.nombre"
                  type="text"
                  class="destiny-input"
                  placeholder="Ej: Valkyrie-7"
                  required
                />
                <div v-if="form.errors['personaje.nombre']" class="error-text">
                  {{ form.errors['personaje.nombre'] }}
                </div>
              </div>

              <!-- Selección de clase -->
              <div>
                <label class="form-label mb-4">
                  <i class="mdi mdi-shield-account mr-1"></i>
                  Elige tu Clase
                </label>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div
                    v-for="clase in clasesRpg"
                    :key="clase.id"
                    @click="form.personaje.id_clase_rpg = clase.id"
                    class="clase-card"
                    :class="{ 'selected': form.personaje.id_clase_rpg === clase.id }"
                  >
                    <div class="clase-icon">
                      {{ clase.icono }}
                    </div>
                    <h4 class="clase-nombre">{{ clase.nombre }}</h4>
                    <p class="clase-descripcion">{{ clase.descripcion }}</p>
                    
                    <div v-if="form.personaje.id_clase_rpg === clase.id" class="selected-badge">
                      <i class="mdi mdi-check-circle"></i>
                    </div>
                  </div>
                </div>
                
                <div v-if="form.errors['personaje.id_clase_rpg']" class="error-text mt-2">
                  {{ form.errors['personaje.id_clase_rpg'] }}
                </div>
              </div>
            </div>
          </div>

          <!-- Botones de acción -->
          <div class="flex justify-center space-x-4">
            <button
              type="button"
              @click="cancelar"
              class="destiny-btn destiny-btn-ghost"
            >
              <i class="mdi mdi-arrow-left mr-2"></i>
              CANCELAR
            </button>
            
            <button
              type="submit"
              :disabled="form.processing || !form.personaje.nombre || !form.personaje.id_clase_rpg"
              class="destiny-btn destiny-btn-primary"
            >
              <i class="mdi mdi-rocket-launch mr-2"></i>
              {{ form.processing ? 'UNIÉNDOSE...' : 'UNIRSE A LA MISIÓN' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Head, useForm, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
  clasesRpg: Array
})

const codigoTemp = ref('')
const claseEncontrada = ref(null)
const verificando = ref(false)
const errorCodigo = ref('')

const form = useForm({
  codigo_invitacion: '',
  personaje: {
    nombre: '',
    id_clase_rpg: null
  }
})

const verificarCodigo = async () => {
  errorCodigo.value = ''
  verificando.value = true
  
  try {
    const response = await fetch(route('clases.verificar.codigo'), {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify({ codigo: codigoTemp.value })
    })
    
    const data = await response.json()
    
    if (data.valido) {
      claseEncontrada.value = data.clase
      form.codigo_invitacion = codigoTemp.value
    } else {
      errorCodigo.value = 'Código inválido o clase no disponible'
    }
  } catch (error) {
    errorCodigo.value = 'Error al verificar el código'
  } finally {
    verificando.value = false
  }
}

const submit = () => {
  form.post(route('clases.unirse.post'))
}

const cancelar = () => {
  if (claseEncontrada.value) {
    claseEncontrada.value = null
    codigoTemp.value = ''
    form.reset()
  } else {
    router.visit(route('dashboard'))
  }
}
</script>

<style scoped>
/* Reutilizar estilos de Create.vue */
.destiny-background {
  @apply fixed inset-0 bg-black;
  background-image: 
    radial-gradient(ellipse at top, #1a1a2e 0%, #0a0a0a 50%),
    radial-gradient(ellipse at bottom, #16213e 0%, #0a0a0a 50%);
}

/* ... otros estilos base ... */

.clase-card {
  @apply relative bg-gray-900/50 border-2 border-gray-600 rounded-lg p-6;
  @apply cursor-pointer transition-all duration-200;
  @apply hover:border-purple-500 hover:bg-gray-900/70;
}

.clase-card.selected {
  @apply border-purple-400 bg-purple-900/30;
  box-shadow: 0 0 20px rgba(168, 85, 247, 0.3);
}

.clase-icon {
  @apply text-4xl mb-3;
}

.clase-nombre {
  @apply text-lg font-bold text-white mb-2;
}

.clase-descripcion {
  @apply text-sm text-gray-400;
}

.selected-badge {
  @apply absolute top-3 right-3 text-purple-400 text-xl;
}
</style>