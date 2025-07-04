<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-900 via-purple-900 to-violet-900 flex items-center justify-center p-4">
    <div class="w-full max-w-md">
      
      <!-- Header -->
      <div class="text-center mb-8">
        <div class="mx-auto w-20 h-20 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center mb-4">
          <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
          </svg>
        </div>
        <h1 class="text-3xl font-bold text-white mb-2">Únete a tu Clase</h1>
        <p class="text-gray-300">Ingresa el código que te proporcionó tu profesor</p>
      </div>

      <!-- Formulario de Inscripción -->
      <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-8 shadow-xl border border-white/20">
        <form @submit.prevent="enviarFormulario" class="space-y-6">
          
          <!-- Código de Clase -->
          <div>
            <label class="block text-sm font-medium text-white mb-2">
              Código de Clase *
            </label>
            <div class="relative">
              <input 
                v-model="formulario.codigo_clase"
                @input="verificarCodigo"
                type="text" 
                maxlength="6"
                placeholder="Ej: ABC123"
                class="w-full px-4 py-3 bg-gray-800/50 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent uppercase tracking-wider text-center text-lg font-mono"
                :class="{
                  'border-green-500': codigoValido,
                  'border-red-500': codigoInvalido,
                  'border-gray-600': !codigoVerificado
                }"
                required
              >
              <div v-if="verificandoCodigo" class="absolute right-3 top-3">
                <svg class="animate-spin w-6 h-6 text-purple-500" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
              </div>
            </div>
            
            <!-- Información de la clase -->
            <div v-if="codigoValido && infoClase" class="mt-3 p-3 bg-green-500/20 border border-green-500 rounded-lg">
              <p class="text-green-400 font-medium">✓ Clase encontrada:</p>
              <p class="text-white font-semibold">{{ infoClase.nombre }}</p>
              <p class="text-gray-300 text-sm">Profesor: {{ infoClase.docente }}</p>
              <p class="text-gray-300 text-sm">Estudiantes: {{ infoClase.total_estudiantes }}</p>
            </div>
            
            <!-- Error de código -->
            <div v-if="codigoInvalido" class="mt-3 p-3 bg-red-500/20 border border-red-500 rounded-lg">
              <p class="text-red-400">{{ mensajeError }}</p>
            </div>
          </div>

          <!-- Datos Personales -->
          <div v-if="codigoValido" class="space-y-4 animate-fade-in">
            <hr class="border-gray-600">
            <h3 class="text-lg font-semibold text-white">Tus Datos</h3>
            
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-white mb-2">Nombre *</label>
                <input 
                  v-model="formulario.nombre"
                  type="text" 
                  required
                  class="w-full px-3 py-2 bg-gray-800/50 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500"
                  placeholder="Tu nombre"
                >
              </div>
              
              <div>
                <label class="block text-sm font-medium text-white mb-2">Apellido *</label>
                <input 
                  v-model="formulario.apellido"
                  type="text" 
                  required
                  class="w-full px-3 py-2 bg-gray-800/50 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500"
                  placeholder="Tu apellido"
                >
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium text-white mb-2">Correo Electrónico *</label>
              <input 
                v-model="formulario.correo"
                type="email" 
                required
                class="w-full px-3 py-2 bg-gray-800/50 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500"
                placeholder="tu.correo@ejemplo.com"
              >
            </div>

            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-white mb-2">Contraseña *</label>
                <input 
                  v-model="formulario.contraseña"
                  type="password" 
                  required
                  class="w-full px-3 py-2 bg-gray-800/50 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500"
                  placeholder="Mínimo 6 caracteres"
                >
              </div>
              
              <div>
                <label class="block text-sm font-medium text-white mb-2">Confirmar *</label>
                <input 
                  v-model="formulario.contraseña_confirmation"
                  type="password" 
                  required
                  class="w-full px-3 py-2 bg-gray-800/50 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500"
                  placeholder="Repetir contraseña"
                >
              </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-white mb-2">Grado</label>
                <input 
                  v-model="formulario.grado"
                  type="text" 
                  class="w-full px-3 py-2 bg-gray-800/50 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500"
                  :placeholder="infoClase?.grado || 'Ej: 5to'"
                >
              </div>
              
              <div>
                <label class="block text-sm font-medium text-white mb-2">Sección</label>
                <input 
                  v-model="formulario.seccion"
                  type="text" 
                  class="w-full px-3 py-2 bg-gray-800/50 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500"
                  :placeholder="infoClase?.seccion || 'Ej: A'"
                >
              </div>
            </div>
          </div>

          <!-- Errores generales -->
          <div v-if="errores.length > 0" class="p-3 bg-red-500/20 border border-red-500 rounded-lg">
            <ul class="text-red-400 text-sm space-y-1">
              <li v-for="error in errores" :key="error">• {{ error }}</li>
            </ul>
          </div>

          <!-- Botones -->
          <div class="flex flex-col space-y-3">
            <button 
              type="submit"
              :disabled="!codigoValido || procesando"
              class="w-full bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 disabled:from-gray-600 disabled:to-gray-700 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200 flex items-center justify-center space-x-2"
            >
              <svg v-if="procesando" class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <span>{{ procesando ? 'Inscribiendo...' : 'Unirse a la Clase' }}</span>
            </button>
            
            <a 
              href="/login" 
              class="text-center text-gray-300 hover:text-white transition-colors duration-200"
            >
              ¿Ya tienes cuenta? Inicia sesión
            </a>
          </div>
        </form>
      </div>

      <!-- Footer -->
      <div class="text-center mt-6">
        <p class="text-gray-400 text-sm">
          ¿No tienes el código? Pregúntale a tu profesor
        </p>
      </div>
    </div>
  </div>
</template>
<script setup>
import { ref, reactive, computed } from 'vue'
import { router } from '@inertiajs/vue3'

// Props
const props = defineProps({
  titulo: String,
  descripcion: String,
  errors: Object
})

// Estado reactivo
const formulario = reactive({
  codigo_clase: '',
  nombre: '',
  apellido: '',
  correo: '',
  contraseña: '',
  contraseña_confirmation: '',
  grado: '',
  seccion: ''
})

const verificandoCodigo = ref(false)
const codigoVerificado = ref(false)
const codigoValido = ref(false)
const codigoInvalido = ref(false)
const infoClase = ref(null)
const mensajeError = ref('')
const procesando = ref(false)
const errores = ref([])

// Computed
const puedeEnviar = computed(() => {
  return codigoValido.value && 
         formulario.nombre && 
         formulario.apellido && 
         formulario.correo && 
         formulario.contraseña && 
         formulario.contraseña_confirmation &&
         formulario.contraseña === formulario.contraseña_confirmation
})

// Métodos
let timerVerificacion = null

const verificarCodigo = async () => {
  const codigo = formulario.codigo_clase.toUpperCase().trim()
  formulario.codigo_clase = codigo
  
  if (codigo.length !== 6) {
    resetearEstadoCodigo()
    return
  }

  // Debounce la verificación
  clearTimeout(timerVerificacion)
  timerVerificacion = setTimeout(async () => {
    verificandoCodigo.value = true
    
    try {
      const response = await fetch('/api/verificar-codigo-publico', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        },
        body: JSON.stringify({ codigo })
      })

      const data = await response.json()

      if (data.valido) {
        codigoValido.value = true
        codigoInvalido.value = false
        infoClase.value = data.clase
        
        // Auto-rellenar grado y sección si están disponibles
        if (data.clase.grado && !formulario.grado) {
          formulario.grado = data.clase.grado
        }
        if (data.clase.seccion && !formulario.seccion) {
          formulario.seccion = data.clase.seccion
        }
      } else {
        codigoValido.value = false
        codigoInvalido.value = true
        mensajeError.value = data.mensaje || 'Código inválido'
        infoClase.value = null
      }
      
      codigoVerificado.value = true
    } catch (error) {
      console.error('Error verificando código:', error)
      codigoInvalido.value = true
      mensajeError.value = 'Error al verificar el código'
    } finally {
      verificandoCodigo.value = false
    }
  }, 500)
}

const resetearEstadoCodigo = () => {
  codigoVerificado.value = false
  codigoValido.value = false
  codigoInvalido.value = false
  infoClase.value = null
  mensajeError.value = ''
}

const enviarFormulario = async () => {
  if (!puedeEnviar.value) return

  procesando.value = true
  errores.value = []

  try {
    await router.post('/estudiante/inscripcion', formulario, {
      onError: (errors) => {
        errores.value = Object.values(errors).flat()
      },
      onSuccess: () => {
        // La redirección se maneja en el controlador
      }
    })
  } catch (error) {
    console.error('Error en inscripción:', error)
    errores.value = ['Error al procesar la inscripción. Inténtalo nuevamente.']
  } finally {
    procesando.value = false
  }
}
</script>

<style scoped>
.animate-fade-in {
  animation: fadeIn 0.5s ease-in-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

input:focus {
  box-shadow: 0 0 0 3px rgba(147, 51, 234, 0.1);
}
</style>