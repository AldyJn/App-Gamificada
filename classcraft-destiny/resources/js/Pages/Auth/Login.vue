<!-- HEAD SECTION - AGREGAR AL INICIO DEL TEMPLATE -->
<Head title="Iniciar Sesión - Zenthoria Guardian Academy" />

<template>
  <div class="welcome-container">
    <!-- Fondo cósmico animado -->
    <div class="destiny-cosmic-pattern"></div>
    <div class="destiny-particles"></div>
    
    <!-- Navegación Header -->
    <nav class="welcome-nav">
      <div class="nav-container">
        <div class="nav-logo">
          <div class="logo-emblem">
            <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-cyan-400 rounded-full flex items-center justify-center">
              <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2L2 7L12 12L22 7L12 2Z"/>
                <path d="M2 17L12 22L22 17"/>
                <path d="M2 12L12 17L22 12"/>
              </svg>
            </div>
          </div>
          <div class="logo-text">
            <h1 class="destiny-title text-xl">ZENTHORIA</h1>
            <span class="destiny-subtitle text-sm">GUARDIAN ACADEMY</span>
          </div>
        </div>

        <div class="nav-auth">
          <Link href="/" class="destiny-btn destiny-btn-sm">
            ← Volver al Inicio
          </Link>
          <Link href="/register" class="destiny-btn destiny-btn-cosmic destiny-btn-sm">
            Unirse a la Academia
          </Link>
        </div>
      </div>
    </nav>

    <!-- Contenedor Principal de Login -->
    <div class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8 relative z-10">
      <div class="max-w-md w-full space-y-8">
        
        <!-- Logo y título -->
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
        <div class="cosmic-card" v-motion-slide-visible-bottom>
          <div class="cosmic-card-content">
            
            <!-- Indicador de estado -->
            <div v-if="loginState.isLoading" class="text-center mb-6">
              <div class="cosmic-spinner mx-auto mb-2"></div>
              <p class="text-accent-cyan text-sm">Conectando con la Torre...</p>
            </div>

            <!-- Errores globales -->
            <div v-if="Object.keys(loginState.errors).length > 0 && !loginState.isLoading" 
                 class="cosmic-alert-error mb-6">
              <div class="flex items-center">
                <svg class="w-5 h-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <span class="text-sm">{{ getMainErrorMessage() }}</span>
              </div>
            </div>

            <form @submit.prevent="handleLogin" class="space-y-6">
              
              <!-- Campo Email -->
              <div>
                <label for="correo" class="cosmic-label">
                  <span class="flex items-center space-x-2">
                    <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                    </svg>
                    <span>Correo de Guardián</span>
                  </span>
                </label>
                <div class="relative">
                  <input
                    id="correo"
                    ref="emailInput"
                    v-model="correo"
                    type="email"
                    autocomplete="email"
                    required
                    class="cosmic-input w-full"
                    placeholder="guardian@zenthoria.academy"
                    @keydown.enter="focusPassword"
                    @input="errorMessage = ''"
                  />
                  <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                    <svg class="h-5 w-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                    </svg>
                  </div>
                </div>
                <p v-if="correoError" class="cosmic-error-text">{{ correoError }}</p>
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
                    v-model="password"
                    :type="showPassword ? 'text' : 'password'"
                    autocomplete="current-password"
                    required
                    class="cosmic-input w-full"
                    placeholder="••••••••"
                    @keydown.enter="handleLogin"
                    @input="errorMessage = ''"
                  />
                  <button
                    type="button"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center cosmic-btn-icon"
                    @click="loginState.showPassword = !loginState.showPassword"
                  >
                    <svg v-if="!loginState.showPassword" class="h-5 w-5 text-purple-400 hover:text-cyan-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <svg v-else class="h-5 w-5 text-purple-400 hover:text-cyan-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"/>
                    </svg>
                  </button>
                </div>
                <p v-if="passwordError" class="cosmic-error-text">{{ passwordError }}</p>
              </div>
<!-- Remember Me y Forgot Password -->
              <div class="flex items-center justify-between">
                <div class="flex items-center">
                  <input
                    id="remember_me"
                    v-model="loginState.rememberMe"
                    type="checkbox"
                    class="cosmic-checkbox"
                  />
                  <label for="remember_me" class="ml-2 block text-sm text-gray-300 hover:text-accent-cyan transition-colors cursor-pointer">
                    Mantener sesión activa
                  </label>
                </div>
                
                <div class="text-sm">
                  <a href="#" class="cosmic-link" @click.prevent="showForgotPassword = true">
                    ¿Olvidaste tu código?
                  </a>
                </div>
              </div>

              <!-- Botón de login -->
              <div>
                <button
                  type="submit"
                  :disabled="loginState.isLoading || !isFormValid"
                  class="cosmic-btn cosmic-btn-primary w-full"
                >
                  <template v-if="!loginState.isLoading">
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
                    Forjar Guardián
                  </Link>
                </p>
                
                <div class="cosmic-divider">
                  <span class="text-xs text-gray-500">O explora</span>
                </div>
                
                <Link href="/" class="cosmic-link text-xs">
                  ← Volver a la página principal
                </Link>
              </div>

            </form>

            <!-- Credenciales de prueba (solo desarrollo) -->
            <div v-if="isDevelopment" class="mt-6 pt-6 border-t border-gray-700 cosmic-dev-section">
              <p class="text-xs text-gray-500 mb-3 text-center">🧪 Credenciales de desarrollo:</p>
              <div class="grid grid-cols-2 gap-2">
                <button
                  @click="setTestCredentials('profesor')"
                  class="cosmic-btn-secondary text-xs py-2 px-3"
                >
                  👨‍🏫 Profesor
                </button>
                <button
                  @click="setTestCredentials('estudiante')"
                  class="cosmic-btn-secondary text-xs py-2 px-3"
                >
                  🎓 Estudiante
                </button>
              </div>
            </div>

          </div>
        </div>

        <!-- Footer -->
        <div class="text-center text-xs text-gray-500 cosmic-footer">
          <p>Zenthoria Guardian Academy © 2025</p>
          <p class="mt-1">Powered by Light Engine v3.0 + Cosmic Vue Framework</p>
        </div>

      </div>
    </div>
<!-- Modal de Forgot Password -->
    <Teleport to="body">
      <div v-if="showForgotPassword" class="cosmic-modal-overlay" @click="showForgotPassword = false">
        <div class="cosmic-modal" @click.stop>
          <div class="cosmic-modal-header">
            <h3 class="text-lg font-bold text-white mb-2">🔑 Recuperar Código de Acceso</h3>
            <p class="text-gray-300 text-sm mb-4">
              Ingresa tu correo electrónico y te enviaremos un enlace para restablecer tu código de acceso a la Torre.
            </p>
          </div>
          
          <div class="cosmic-modal-body">
            <div class="space-y-4">
              <div>
                <label for="forgot-email" class="cosmic-label text-sm">
                  Correo de Guardián
                </label>
                <input
                  id="forgot-email"
                  v-model="forgotEmail"
                  type="email"
                  placeholder="guardian@zenthoria.academy"
                  class="cosmic-input w-full"
                  :disabled="forgotLoading"
                />
              </div>
              
              <div v-if="forgotError" class="cosmic-alert-error">
                <div class="flex items-center">
                  <svg class="w-4 h-4 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                  </svg>
                  <span class="text-sm">{{ forgotError }}</span>
                </div>
              </div>
              
              <div v-if="forgotSuccess" class="cosmic-alert-success">
                <div class="flex items-center">
                  <svg class="w-4 h-4 text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                  </svg>
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
<!-- PARTE FALTANTE DEL LOGIN.VUE - AGREGAR DESPUÉS DEL SCRIPT SETUP -->

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

// Aliases para facilitar el uso
const correo = computed({
  get: () => form.correo,
  set: (value) => form.correo = value
})

const password = computed({
  get: () => form.password,
  set: (value) => form.password = value
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

  form.post(route('login'), {
    onStart: () => {
      isLoading.value = true
    },
    onSuccess: () => {
      isLoading.value = false
      // La redirección se maneja automáticamente por Inertia
    },
    onError: (errors) => {
      isLoading.value = false
      errorMessage.value = errors.correo || errors.password || 'Error de autenticación'
    },
    onFinish: () => {
      isLoading.value = false
      form.reset('password')
    }
  })
}

const handleForgotPassword = () => {
  if (!forgotEmail.value) {
    forgotError.value = 'Por favor, ingresa tu correo electrónico.'
    return
  }

  forgotError.value = ''
  forgotSuccess.value = ''

  // Simular envío de correo
  setTimeout(() => {
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
    // Mostrar notificación de status
    console.log('Status:', props.status)
  }
})
</script>

<!-- ESTILOS CSS COMPLETOS - AGREGAR DESPUÉS DEL STYLE SCOPED EXISTENTE -->

<style scoped>
/* Animaciones de entrada */
@keyframes fade-in-up {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.welcome-container {
  animation: fade-in-up 0.8s ease-out;
}

/* Mejoras de accesibilidad */
.cosmic-btn:focus,
.cosmic-input:focus,
.cosmic-link:focus {
  outline: 2px solid var(--cosmic-cyan-light);
  outline-offset: 2px;
}

/* Estados de hover mejorados */
.cosmic-input:hover:not(:focus) {
  border-color: var(--cosmic-border-bright);
}

.cosmic-card:hover {
  border-color: var(--cosmic-cyan);
}

/* Efectos de texto */
.text-accent-cyan {
  color: var(--cosmic-cyan-light);
  text-shadow: 0 0 10px rgba(56, 189, 248, 0.4);
}

.text-accent-gold {
  color: var(--cosmic-gold-light);
  text-shadow: 0 0 10px rgba(251, 191, 36, 0.4);
}

.font-orbitron {
  font-family: 'Orbitron', monospace;
}

/* Responsivo adicional */
@media (max-width: 768px) {
  .nav-container {
    padding: 1rem;
    flex-direction: column;
    gap: 1rem;
  }
  
  .cosmic-header {
    padding-top: 8rem;
  }
  
  .cosmic-modal {
    margin: 1rem;
  }
  
  .cosmic-modal-header,
  .cosmic-modal-body,
  .cosmic-modal-footer {
    padding: 1.5rem;
  }
}

@media (max-width: 480px) {
  .hero-title {
    font-size: 2rem;
  }
  
  .cosmic-btn {
    padding: 0.75rem 1.25rem;
    font-size: 0.8rem;
  }
}

/* Transiciones suaves globales */
* {
  transition-property: background-color, border-color, color, fill, stroke, opacity, box-shadow, transform;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 150ms;
}

/* Efectos especiales para el card */
.cosmic-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 2px;
  background: linear-gradient(90deg, transparent, var(--cosmic-purple-light), var(--cosmic-cyan-light), transparent);
  animation: cosmic-shine 3s ease-in-out infinite;
}

@keyframes cosmic-shine {
  0%, 100% { opacity: 0; }
  50% { opacity: 1; }
}

/* Estado de loading mejorado */
.cosmic-btn-primary:disabled {
  background: linear-gradient(135deg, rgba(124, 58, 237, 0.3), rgba(168, 85, 247, 0.3));
  color: rgba(248, 250, 252, 0.6);
  box-shadow: none;
  transform: none;
}

/* Mejoras para el modal */
.cosmic-modal-overlay {
  animation: modal-fade-in 0.3s ease;
}

.cosmic-modal {
  animation: modal-slide-up 0.3s ease;
}

@keyframes modal-fade-in {
  from { opacity: 0; }
  to { opacity: 1; }
}

@keyframes modal-slide-up {
  from { transform: translateY(30px) scale(0.95); opacity: 0; }
  to { transform: translateY(0) scale(1); opacity: 1; }
}

/* Efectos adicionales para inputs */
.cosmic-input:focus::placeholder {
  color: transparent;
}

.cosmic-input:focus + .absolute svg {
  color: var(--cosmic-cyan-light);
}

/* Hover effects para botones */
.cosmic-btn::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
  transition: left 0.5s;
}

.cosmic-btn:hover::before {
  left: 100%;
}

/* Efectos de glow para elementos seleccionados */
.cosmic-checkbox:checked {
  box-shadow: 0 0 10px rgba(124, 58, 237, 0.5);
}

/* Mejoras visuales para el footer */
.cosmic-footer {
  background: rgba(11, 11, 26, 0.3);
  border-radius: 8px;
  padding: 1rem;
  margin-top: 2rem;
}

/* Efectos de partículas mejorados */
.destiny-particles {
  opacity: 0.6;
}

.destiny-particles:hover {
  opacity: 1;
}

/* Efectos de iluminación para la navegación */
.nav-logo:hover .logo-emblem > div {
  box-shadow: 0 0 20px rgba(124, 58, 237, 0.6);
}

/* Mejoras para la barra de scroll */
::-webkit-scrollbar {
  width: 8px;
}

::-webkit-scrollbar-track {
  background: var(--cosmic-dark);
}

::-webkit-scrollbar-thumb {
  background: var(--cosmic-purple-light);
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background: var(--cosmic-cyan-light);
}
.cosmic-dev-section {
  background: rgba(245, 158, 11, 0.1);
  border: 1px solid rgba(245, 158, 11, 0.3);
  border-radius: 12px;
  padding: 1rem;
  margin-top: 1.5rem;
}

.cosmic-dev-section button {
  background: rgba(245, 158, 11, 0.1);
  border: 1px solid rgba(245, 158, 11, 0.3);
  color: #FBBF24;
  transition: all 0.3s ease;
}

.cosmic-dev-section button:hover {
  background: rgba(245, 158, 11, 0.2);
  border-color: rgba(245, 158, 11, 0.5);
  transform: translateY(-1px);
}
</style>

