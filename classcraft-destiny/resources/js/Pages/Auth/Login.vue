<template>
  <Head title="Iniciar Sesión" />

  <div class="cosmic-container">
    <!-- Fondo animado -->
    <div class="cosmic-background"></div>

    <!-- Contenido principal -->
    <div class="welcome-container">
      
      <!-- Header -->
      <div class="text-center cosmic-header">
        <div class="crown-icon mb-6">
          <svg class="w-20 h-20 text-purple-400" fill="currentColor" viewBox="0 0 24 24">
            <path d="M5 16L3 4l5.5 5L12 4l3.5 5L21 4l-2 12H5zm2.7-2h8.6l.9-5.4-2.1 1.7L12 8l-3.1 2.3-2.1-1.7L7.7 14z"/>
          </svg>
        </div>
        
        <h2 class="hero-title text-4xl font-bold text-white font-orbitron tracking-wider">
          ⚔️ ACCESO A LA TORRE
        </h2>
        <p class="hero-subtitle-main text-lg text-accent-cyan font-light mt-4">
          Donde el conocimiento se convierte en poder
        </p>
        <p class="hero-description text-sm text-gray-400 mt-2">
          Ingresa tus credenciales para acceder a la Academia Zenthoria
        </p>
      </div>

      <!-- Formulario de login -->
      <div class="cosmic-card">
        <div class="cosmic-card-content">
          
          <!-- Indicador de estado -->
          <div v-if="isLoading" class="text-center mb-6">
            <div class="cosmic-spinner mx-auto mb-2"></div>
            <p class="text-accent-cyan text-sm">Conectando con la Torre...</p>
          </div>

          <!-- Errores globales -->
          <div v-if="errorMessage && !isLoading" class="cosmic-alert-error mb-6">
            <div class="flex items-center space-x-2">
              <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              <span>{{ errorMessage }}</span>
            </div>
          </div>

          <form @submit.prevent="handleLogin" class="login-form">
            
            <!-- Campo Email -->
            <div>
              <label for="email" class="cosmic-label">
                <span class="flex items-center space-x-2">
                  <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                  </svg>
                  <span>Correo Electrónico</span>
                </span>
              </label>
              <div class="relative">
                <input
                  id="email"
                  ref="emailInput"
                  v-model="form.correo"
                  type="email"
                  class="cosmic-input w-full pl-10 pr-10"
                  placeholder="guardian@zenthoria.edu"
                  required
                  autocomplete="email"
                  :class="{ 'border-red-500': form.errors.correo || form.errors.email }"
                  @keydown.enter="focusPassword"
                  @input="errorMessage = ''"
                />
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                  <svg class="h-5 w-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                  </svg>
                </div>
              </div>
              <p v-if="form.errors.correo || form.errors.email" class="cosmic-error-text">
                {{ form.errors.correo || form.errors.email }}
              </p>
            </div>

            <!-- Campo Password -->
            <div>
              <label for="password" class="cosmic-label">
                <span class="flex items-center space-x-2">
                  <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                  </svg>
                  <span>Código de Acceso</span>
                </span>
              </label>
              <div class="relative">
                <input
                  id="password"
                  ref="passwordInput"
                  v-model="form.password"
                  :type="showPassword ? 'text' : 'password'"
                  class="cosmic-input w-full pl-10 pr-10"
                  placeholder="••••••••••••"
                  required
                  autocomplete="current-password"
                  :class="{ 'border-red-500': form.errors.password }"
                  @input="errorMessage = ''"
                />
                <button
                  type="button"
                  @click="showPassword = !showPassword"
                  class="absolute inset-y-0 right-0 pr-3 flex items-center"
                >
                  <svg v-if="!showPassword" class="h-5 w-5 text-gray-400 hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                  </svg>
                  <svg v-else class="h-5 w-5 text-gray-400 hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"/>
                  </svg>
                </button>
              </div>
              <p v-if="form.errors.password" class="cosmic-error-text">{{ form.errors.password }}</p>
            </div>

            <!-- Remember y Forgot password -->
            <div class="flex items-center justify-between">
              <div class="flex items-center">
                <input
                  id="remember"
                  v-model="rememberMe"
                  type="checkbox"
                  class="cosmic-checkbox"
                />
                <label for="remember" class="ml-2 text-sm text-gray-300">
                  Recordar credenciales
                </label>
              </div>
              <div>
                <button
                  type="button"
                  @click="showForgotPassword = true"
                  class="cosmic-link text-sm"
                >
                  ¿Olvidaste tu código?
                </button>
              </div>
            </div>

            <!-- Botón de login -->
            <div>
              <button
                type="submit"
                :disabled="isLoading || !isFormValid"
                class="cosmic-btn cosmic-btn-primary w-full"
              >
                <template v-if="!isLoading">
                  <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                  </svg>
                  🚀 ACCEDER A LA TORRE
                </template>
                <template v-else>
                  <div class="cosmic-spinner-sm mr-2"></div>
                  CONECTANDO CON LA ACADEMIA...
                </template>
              </button>
            </div>

            <!-- Enlaces adicionales -->
            <div class="text-center space-y-3">
              <p class="text-sm text-gray-400">
                ¿Nuevo en la Academia?
                <Link href="/register" class="cosmic-link">
                  Crear cuenta de Guardián
                </Link>
              </p>
            </div>
          </form>
        </div>
      </div>

    </div>

    <!-- Modal de recuperación de contraseña -->
    <Teleport to="body">
      <div v-if="showForgotPassword" class="cosmic-modal-overlay" @click="showForgotPassword = false">
        <div class="cosmic-modal" @click.stop>
          <div class="cosmic-modal-header">
            <h3 class="text-xl font-bold text-white">Recuperar Acceso</h3>
            <button @click="showForgotPassword = false" class="cosmic-close-btn">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </button>
          </div>
          
          <div class="cosmic-modal-body">
            <p class="text-gray-300 mb-4">
              Ingresa tu correo electrónico y te enviaremos un enlace para restaurar tu acceso a la Torre.
            </p>
            
            <div class="space-y-4">
              <div>
                <label class="cosmic-label">Correo Electrónico</label>
                <input
                  v-model="forgotEmail"
                  type="email"
                  class="cosmic-input w-full"
                  placeholder="guardian@zenthoria.edu"
                  :class="{ 'border-red-500': forgotError }"
                />
                <div v-if="forgotError" class="cosmic-error-text">
                  <span class="text-sm">{{ forgotError }}</span>
                </div>
                <div v-if="forgotSuccess" class="cosmic-success-text">
                  <span class="text-sm">{{ forgotSuccess }}</span>
                </div>
              </div>
            </div>
          </div>
          
          <div class="cosmic-modal-footer">
            <div class="flex justify-end space-x-3">
              <button 
                @click="showForgotPassword = false" 
                class="cosmic-btn-secondary"
                :disabled="forgotLoading"
              >
                Cancelar
              </button>
              <button 
                @click="handleForgotPassword" 
                class="cosmic-btn cosmic-btn-primary"
                :disabled="forgotLoading || !forgotEmail"
              >
                <template v-if="!forgotLoading">
                  <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                  </svg>
                  Enviar Enlace
                </template>
                <template v-else>
                  <div class="cosmic-spinner-sm mr-2"></div>
                  Enviando...
                </template>
              </button>
            </div>
          </div>
        </div>
      </div>
    </Teleport>

  </div>
</template>

<script setup>
import { ref, computed, onMounted, nextTick } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'

// Props del componente
const props = defineProps({
  canResetPassword: Boolean,
  status: String
})

// Refs del DOM
const emailInput = ref()
const passwordInput = ref()

// Estado del formulario
const showForgotPassword = ref(false)
const forgotEmail = ref('')
const forgotError = ref('')
const forgotSuccess = ref('')
const forgotLoading = ref(false)
const showPassword = ref(false)
const rememberMe = ref(false)
const isLoading = ref(false)
const errorMessage = ref('')

// Formulario usando Inertia.js
const form = useForm({
  correo: '',
  password: '',
  remember: false
})

// Computed properties
const isFormValid = computed(() => {
  return form.correo && 
         form.password && 
         form.correo.length > 0 &&
         form.password.length > 0
})

// Métodos del componente
const handleLogin = () => {
  if (!isFormValid.value) return

  errorMessage.value = ''
  isLoading.value = true
  form.remember = rememberMe.value

  form.post('/login', {
    onStart: () => {
      isLoading.value = true
    },
    onSuccess: () => {
      isLoading.value = false
      // La redirección se maneja automáticamente por Inertia
    },
    onError: (errors) => {
      isLoading.value = false
      errorMessage.value = errors.correo || errors.email || errors.password || 'Error de autenticación'
    },
    onFinish: () => {
      isLoading.value = false
      form.reset('password')
    }
  })
}

const focusPassword = () => {
  passwordInput.value?.focus()
}

const handleForgotPassword = () => {
  if (!forgotEmail.value) {
    forgotError.value = 'Por favor, ingresa tu correo electrónico.'
    return
  }

  forgotError.value = ''
  forgotSuccess.value = ''
  forgotLoading.value = true

  // Simular envío de correo
  setTimeout(() => {
    forgotLoading.value = false
    forgotSuccess.value = 'Enlace de recuperación enviado. Revisa tu correo.'
    
    // Cerrar modal después de 3 segundos
    setTimeout(() => {
      showForgotPassword.value = false
      forgotEmail.value = ''
      forgotSuccess.value = ''
    }, 3000)
  }, 1000)
}

// Lifecycle hooks
onMounted(() => {
  // Auto-focus en el campo de email
  nextTick(() => {
    emailInput.value?.focus()
  })
  
  // Verificar si hay status de registro exitoso
  if (props.status) {
    console.log('Status:', props.status)
  }
})
</script>

<style scoped>
/* Estilos base */
.cosmic-container {
  min-height: 100vh;
  background: linear-gradient(135deg, #0a0b13 0%, #1e2139 50%, #0f1419 100%);
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 2rem;
  position: relative;
  font-family: 'Exo 2', sans-serif;
}

.cosmic-background {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: -1;
  background: 
    radial-gradient(ellipse at 20% 50%, rgba(120, 119, 198, 0.3), transparent),
    radial-gradient(ellipse at 80% 20%, rgba(255, 119, 198, 0.3), transparent),
    radial-gradient(ellipse at 40% 80%, rgba(119, 198, 255, 0.3), transparent);
}

.welcome-container {
  max-width: 28rem;
  width: 100%;
}

.cosmic-header {
  text-align: center;
  margin-bottom: 2rem;
}

.cosmic-card {
  background: rgba(15, 20, 25, 0.8);
  backdrop-filter: blur(20px);
  border: 1px solid rgba(56, 189, 248, 0.2);
  border-radius: 1rem;
  padding: 2rem;
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);
}

.cosmic-input {
  background: rgba(15, 20, 25, 0.6);
  border: 1px solid rgba(75, 85, 99, 0.5);
  border-radius: 0.5rem;
  color: white;
  padding: 0.75rem 1rem;
  transition: all 0.3s ease;
}

.cosmic-input:focus {
  outline: none;
  border-color: rgba(56, 189, 248, 0.5);
  box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.1);
}

.cosmic-label {
  display: block;
  font-size: 0.875rem;
  font-weight: 500;
  color: rgba(209, 213, 219, 1);
  margin-bottom: 0.5rem;
}

.cosmic-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 0.75rem 1.5rem;
  border-radius: 0.5rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  transition: all 0.3s ease;
  border: none;
  cursor: pointer;
}

.cosmic-btn-primary {
  background: linear-gradient(135deg, #3b82f6, #1d4ed8);
  color: white;
}

.cosmic-btn-primary:hover:not(:disabled) {
  background: linear-gradient(135deg, #2563eb, #1e40af);
  transform: translateY(-1px);
  box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.4);
}

.cosmic-btn-primary:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.cosmic-btn-secondary {
  background: rgba(75, 85, 99, 0.8);
  color: white;
  border: 1px solid rgba(156, 163, 175, 0.5);
}

.cosmic-link {
  color: rgba(56, 189, 248, 1);
  text-decoration: none;
  transition: color 0.3s ease;
}

.cosmic-link:hover {
  color: rgba(147, 197, 253, 1);
}

.cosmic-checkbox {
  background: rgba(15, 20, 25, 0.6);
  border: 1px solid rgba(75, 85, 99, 0.5);
  border-radius: 0.25rem;
  color: rgba(56, 189, 248, 1);
}

.cosmic-error-text {
  color: rgba(248, 113, 113, 1);
  font-size: 0.875rem;
  margin-top: 0.25rem;
}

.cosmic-success-text {
  color: rgba(34, 197, 94, 1);
  font-size: 0.875rem;
  margin-top: 0.25rem;
}

.cosmic-alert-error {
  background: rgba(239, 68, 68, 0.1);
  border: 1px solid rgba(239, 68, 68, 0.3);
  border-radius: 0.5rem;
  padding: 1rem;
  color: rgba(248, 113, 113, 1);
}

.cosmic-spinner {
  width: 2rem;
  height: 2rem;
  border: 2px solid rgba(75, 85, 99, 0.3);
  border-top: 2px solid rgba(56, 189, 248, 1);
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

.cosmic-spinner-sm {
  width: 1rem;
  height: 1rem;
  border: 2px solid rgba(75, 85, 99, 0.3);
  border-top: 2px solid white;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

.cosmic-modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.75);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 50;
}

.cosmic-modal {
  background: rgba(15, 20, 25, 0.95);
  backdrop-filter: blur(20px);
  border: 1px solid rgba(56, 189, 248, 0.2);
  border-radius: 1rem;
  max-width: 28rem;
  width: 90%;
  margin: 2rem;
}

.cosmic-modal-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 1.5rem;
  border-bottom: 1px solid rgba(75, 85, 99, 0.3);
}

.cosmic-modal-body {
  padding: 1.5rem;
}

.cosmic-modal-footer {
  padding: 1.5rem;
  border-top: 1px solid rgba(75, 85, 99, 0.3);
}

.cosmic-close-btn {
  background: none;
  border: none;
  color: rgba(156, 163, 175, 1);
  cursor: pointer;
  padding: 0.25rem;
  transition: color 0.3s ease;
}

.cosmic-close-btn:hover {
  color: white;
}

.text-accent-cyan {
  color: rgba(56, 189, 248, 1);
}

.font-orbitron {
  font-family: 'Orbitron', monospace;
}

.login-form {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.login-form > div {
  margin-bottom: 1rem;
}
@media (max-width: 640px) {
  .cosmic-container {
    padding: 1rem;
  }
  
  .cosmic-card {
    padding: 1.5rem;
  }
}
</style>