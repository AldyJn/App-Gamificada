<template>
  <div class="destiny-login-container">
    <!-- Fondo hexagonal animado -->
    <div class="destiny-hex-pattern"></div>
    <div class="destiny-particles"></div>
    
    <!-- Contenedor principal -->
    <div class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8 relative z-10">
      <div class="max-w-md w-full space-y-8">
        
        <!-- Logo y título -->
        <div class="text-center" v-motion-fade-visible>
          <div class="destiny-logo-container mb-6">
            <!-- LOGO: Logo del sistema ClassCraft-Destiny -->
            <div class="w-24 h-24 mx-auto bg-gradient-to-br from-accent-gold to-accent-cyan rounded-full flex items-center justify-center destiny-glow-gold">
              <svg class="w-12 h-12 text-primary" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2L2 7L12 12L22 7L12 2Z"/>
                <path d="M2 17L12 22L22 17"/>
                <path d="M2 12L12 17L22 12"/>
              </svg>
            </div>
          </div>
          
          <h2 class="mt-6 text-center text-4xl font-bold text-white font-orbitron tracking-wider">
            CLASSCRAFT
          </h2>
          <p class="mt-2 text-center text-lg text-accent-cyan font-light">
            SISTEMA EDUCATIVO GUARDIÁN
          </p>
          <p class="mt-1 text-center text-sm text-gray-400">
            Accede a la Torre del Conocimiento
          </p>
        </div>

        <!-- Formulario de login -->
        <div class="destiny-card" v-motion-slide-visible-bottom>
          <div class="destiny-card-content">
            
            <!-- Indicador de estado -->
            <div v-if="loginState.isLoading" class="text-center mb-6">
              <div class="destiny-spinner mx-auto mb-2"></div>
              <p class="text-accent-cyan text-sm">Conectando con la Torre...</p>
            </div>

            <!-- Errores globales -->
            <div v-if="Object.keys(loginState.errors).length > 0 && !loginState.isLoading" 
                 class="destiny-alert-error mb-6">
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
                <label for="correo" class="destiny-label">
                  Correo Electrónico
                </label>
                <div class="relative">
                  <input
                    id="correo"
                    ref="emailInput"
                    v-model="correo"
                    :class="['destiny-input', { 'destiny-input-error': correoError }]"
                    type="email"
                    autocomplete="email"
                    placeholder="guardian@torre.destiny"
                    @keydown.enter="focusPassword"
                  />
                  <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                    <svg class="h-5 w-5 text-accent-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                    </svg>
                  </div>
                </div>
                <p v-if="correoError" class="destiny-error-text">{{ correoError }}</p>
              </div>

              <!-- Campo Password -->
              <div>
                <label for="password" class="destiny-label">
                  Contraseña
                </label>
                <div class="relative">
                  <input
                    id="password"
                    ref="passwordInput"
                    v-model="password"
                    :class="['destiny-input', { 'destiny-input-error': passwordError }]"
                    :type="loginState.showPassword ? 'text' : 'password'"
                    autocomplete="current-password"
                    placeholder="••••••••"
                    @keydown.enter="handleLogin"
                  />
                  <button
                    type="button"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center"
                    @click="loginState.showPassword = !loginState.showPassword"
                  >
                    <svg v-if="!loginState.showPassword" class="h-5 w-5 text-accent-gold hover:text-accent-cyan transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <svg v-else class="h-5 w-5 text-accent-gold hover:text-accent-cyan transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"/>
                    </svg>
                  </button>
                </div>
                <p v-if="passwordError" class="destiny-error-text">{{ passwordError }}</p>
              </div>

              <!-- Remember Me y Forgot Password -->
              <div class="flex items-center justify-between">
                <div class="flex items-center">
                  <input
                    id="remember_me"
                    v-model="loginState.rememberMe"
                    type="checkbox"
                    class="destiny-checkbox"
                  />
                  <label for="remember_me" class="ml-2 block text-sm text-gray-300 hover:text-accent-cyan transition-colors cursor-pointer">
                    Recordar sesión
                  </label>
                </div>
                
                <div class="text-sm">
                  <a href="#" class="destiny-link" @click.prevent="showForgotPassword = true">
                    ¿Olvidaste tu contraseña?
                  </a>
                </div>
              </div>

              <!-- Botón de login -->
              <div>
                <button
                  type="submit"
                  :disabled="loginState.isLoading || !isFormValid"
                  class="destiny-btn destiny-btn-primary w-full"
                >
                  <template v-if="!loginState.isLoading">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    INICIAR SESIÓN
                  </template>
                  <template v-else>
                    <div class="destiny-spinner-sm mr-2"></div>
                    CONECTANDO...
                  </template>
                </button>
              </div>

              <!-- Enlaces adicionales -->
              <div class="text-center">
                <p class="text-sm text-gray-400">
                  ¿Nuevo guardián?
                  <a href="/register" class="destiny-link">
                    Únete a la Torre
                  </a>
                </p>
              </div>

            </form>

            <!-- Credenciales de prueba (solo desarrollo) -->
            <div v-if="isDevelopment" class="mt-6 pt-6 border-t border-gray-700">
              <p class="text-xs text-gray-500 mb-3">Credenciales de desarrollo:</p>
              <div class="grid grid-cols-2 gap-2">
                <button
                  @click="setTestCredentials('profesor')"
                  class="destiny-btn-secondary text-xs py-1"
                >
                  Profesor
                </button>
                <button
                  @click="setTestCredentials('estudiante')"
                  class="destiny-btn-secondary text-xs py-1"
                >
                  Estudiante
                </button>
              </div>
            </div>

          </div>
        </div>

        <!-- Footer -->
        <div class="text-center text-xs text-gray-500" v-motion-fade-visible-once>
          <p>ClassCraft-Destiny © 2025 - Sistema Educativo Guardián</p>
          <p class="mt-1">Powered by Vue 3 + TypeScript</p>
        </div>

      </div>
    </div>

    <!-- Modal de Forgot Password -->
    <Teleport to="body">
      <div v-if="showForgotPassword" class="destiny-modal-overlay" @click="showForgotPassword = false">
        <div class="destiny-modal" @click.stop>
          <h3 class="text-lg font-bold text-white mb-4">Recuperar Contraseña</h3>
          <p class="text-gray-300 text-sm mb-4">
            Ingresa tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña.
          </p>
          <input
            v-model="forgotEmail"
            type="email"
            placeholder="tu-correo@ejemplo.com"
            class="destiny-input mb-4"
          />
          <div class="flex justify-end space-x-3">
            <button @click="showForgotPassword = false" class="destiny-btn-secondary">
              Cancelar
            </button>
            <button @click="handleForgotPassword" class="destiny-btn destiny-btn-primary">
              Enviar enlace
            </button>
          </div>
        </div>
      </div>
    </Teleport>

  </div>
</template>

<script setup lang="ts">
import { ref, reactive, computed, onMounted, nextTick } from 'vue'
import { useRouter } from 'vue-router'
import { useForm, useField } from 'vee-validate'
import * as yup from 'yup'

// Declaraciones de tipos para Node.js
declare const process: {
  env: {
    NODE_ENV: string
  }
}

// Interfaces
interface LoginForm {
  correo: string
  password: string
}

interface LoginState {
  isLoading: boolean
  loginForm: LoginForm
  errors: Record<string, string>
  showPassword: boolean
  rememberMe: boolean
}

interface ApiLoginResponse {
  success: boolean
  user?: {
    id: number
    nombre: string
    apellido: string
    correo: string
    avatar: string
    tipo_usuario: {
      id: number
      nombre: string
      descripcion: string
    }
    estado: {
      id: number
      nombre: string
    }
    ultimo_acceso: string
    configuracion_ui: {
      tema: string
      animaciones: boolean
      sonidos: boolean
    }
  }
  token?: string
  expires_at?: string
  message?: string
  errors?: Record<string, string[]>
}

// Stores y router
const router = useRouter()

// Simulación de notifications store (puedes reemplazar con tu implementación)
const showNotification = (notification: {
  type: 'success' | 'error' | 'warning'
  title: string
  message: string
  duration?: number
}) => {
  // Por ahora mostramos un alert simple, después se puede integrar con tu sistema de notificaciones
  console.log(`${notification.type.toUpperCase()}: ${notification.title} - ${notification.message}`)
  
  // Si tienes un sistema de toast/notifications, puedes reemplazar esta línea
  if (notification.type === 'error') {
    alert(`❌ ${notification.title}: ${notification.message}`)
  } else if (notification.type === 'success') {
    alert(`✅ ${notification.title}: ${notification.message}`)
  } else {
    alert(`⚠️ ${notification.title}: ${notification.message}`)
  }
}

// Refs
const emailInput = ref<HTMLInputElement>()
const passwordInput = ref<HTMLInputElement>()
const showForgotPassword = ref(false)
const forgotEmail = ref('')

// Estado del formulario (simplificado ya que usamos useField)
const loginState = reactive({
  isLoading: false,
  errors: {} as Record<string, string | string[]>,
  showPassword: false,
  rememberMe: false
})

// Validación con Yup
const validationSchema = yup.object({
  correo: yup
    .string()
    .required('El correo es obligatorio')
    .email('Debe ser un correo válido')
    .max(255, 'El correo es demasiado largo'),
  password: yup
    .string()
    .required('La contraseña es obligatoria')
    .min(6, 'La contraseña debe tener al menos 6 caracteres')
    .max(255, 'La contraseña es demasiado larga')
})

const { errors, validate } = useForm({
  validationSchema
})

const { value: correo, errorMessage: correoError } = useField<string>('correo')
const { value: password, errorMessage: passwordError } = useField<string>('password')

// Computed
const isFormValid = computed(() => {
  return correo.value && 
         password.value && 
         correo.value.toString().length > 0 &&
         password.value.toString().length > 0 &&
         !correoError.value && 
         !passwordError.value
})

const isDevelopment = computed(() => {
  return process.env.NODE_ENV === 'development'
})

// Métodos
const focusPassword = () => {
  nextTick(() => {
    passwordInput.value?.focus()
  })
}

const setTestCredentials = (tipo: 'profesor' | 'estudiante') => {
  if (tipo === 'profesor') {
    correo.value = 'profesor@test.com'
    password.value = 'password123'
  } else {
    correo.value = 'ana@estudiante.test.com'
    password.value = 'password123'
  }
}

const getMainErrorMessage = (): string => {
  const errorValues = Object.values(loginState.errors)
  if (errorValues.length > 0) {
    const firstError = errorValues[0]
    if (Array.isArray(firstError)) {
      return firstError[0] || 'Error de autenticación'
    }
    return firstError || 'Error de autenticación'
  }
  return 'Error de autenticación'
}

const handleLogin = async () => {
  if (loginState.isLoading || !isFormValid.value) return

  // Validar formulario completo
  const { valid } = await validate()
  if (!valid) return

  loginState.isLoading = true
  loginState.errors = {}

  try {
    // Obtener token CSRF
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
    
    const response = await fetch('/api/auth/login', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': csrfToken || '',
      },
      body: JSON.stringify({
        correo: correo.value,
        password: password.value,
        remember: loginState.rememberMe
      })
    })

    const data: ApiLoginResponse = await response.json()

    if (data.success && data.user && data.token) {
      // Guardar token en localStorage (temporal, después se puede usar store)
      localStorage.setItem('auth_token', data.token)
      localStorage.setItem('user_data', JSON.stringify(data.user))
      
      // Reproducir sonido de éxito si está habilitado
      if (data.user.configuracion_ui?.sonidos) {
        playSuccessSound()
      }

      // Notificación de éxito
      showNotification({
        type: 'success',
        title: '¡Bienvenido, Guardián!',
        message: `Hola ${data.user.nombre}, has accedido a la Torre.`,
        duration: 4000
      })

      // Redireccionar según el tipo de usuario
      const redirectPath = getRedirectPath(data.user.tipo_usuario.nombre)
      await router.push(redirectPath)

    } else {
      // Manejar errores
      if (data.errors) {
        // Convertir errores de array a string para mostrar el primero
        const processedErrors: Record<string, string> = {}
        Object.keys(data.errors).forEach(key => {
          const errorValue = data.errors![key]
          processedErrors[key] = Array.isArray(errorValue) ? errorValue[0] : errorValue
        })
        loginState.errors = processedErrors
      } else {
        loginState.errors = {
          general: data.message || 'Error de autenticación'
        }
      }

      // Reproducir sonido de error
      playErrorSound()
    }

  } catch (error) {
    console.error('Error en login:', error)
    loginState.errors = {
      general: 'Error de conexión. Por favor, intenta de nuevo.'
    }
    playErrorSound()
  } finally {
    loginState.isLoading = false
  }
}

const getRedirectPath = (tipoUsuario: string): string => {
  switch (tipoUsuario.toLowerCase()) {
    case 'profesor':
      return '/profesor/dashboard'
    case 'estudiante':
      return '/estudiante/dashboard'
    default:
      return '/dashboard'
  }
}

const handleForgotPassword = async () => {
  if (!forgotEmail.value) {
    showNotification({
      type: 'warning',
      title: 'Campo requerido',
      message: 'Por favor, ingresa tu correo electrónico.',
      duration: 3000
    })
    return
  }

  try {
    const response = await fetch('/api/auth/forgot-password', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: JSON.stringify({
        email: forgotEmail.value
      })
    })

    const data = await response.json()

    if (data.success) {
      showNotification({
        type: 'success',
        title: 'Enlace enviado',
        message: 'Revisa tu correo para restablecer tu contraseña.',
        duration: 5000
      })
      showForgotPassword.value = false
      forgotEmail.value = ''
    } else {
      showNotification({
        type: 'error',
        title: 'Error',
        message: data.message || 'No se pudo enviar el enlace.',
        duration: 4000
      })
    }
  } catch (error) {
    showNotification({
      type: 'error',
      title: 'Error de conexión',
      message: 'No se pudo procesar la solicitud.',
      duration: 4000
    })
  }
}

const playSuccessSound = () => {
  // Implementar sonido de éxito de Destiny
  const audio = new Audio('/sounds/destiny-success.mp3')
  audio.volume = 0.3
  audio.play().catch(() => {}) // Ignorar errores de audio
}

const playErrorSound = () => {
  // Implementar sonido de error de Destiny
  const audio = new Audio('/sounds/destiny-error.mp3')
  audio.volume = 0.3
  audio.play().catch(() => {}) // Ignorar errores de audio
}

// Lifecycle
onMounted(() => {
  // Auto-focus en el campo de email
  nextTick(() => {
    emailInput.value?.focus()
  })

  // Limpiar tokens previos si existen
  localStorage.removeItem('auth_token')
  localStorage.removeItem('user_data')
})
</script>

<style scoped>
/* Variables CSS Destiny */
:root {
  --destiny-primary: #1B1C29;
  --destiny-gold: #C7B88A;
  --destiny-cyan: #6EC1E4;
  --destiny-purple: #B6A1E4;
  --destiny-gray-dark: #2a3441;
  --destiny-gray-medium: #3a4551;
}

/* Fuentes */
@import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Open+Sans:wght@300;400;600;700&display=swap');

.font-orbitron {
  font-family: 'Orbitron', monospace;
}

/* Contenedor principal */
.destiny-login-container {
  min-height: 100vh;
  background: linear-gradient(135deg, #0a0f1a 0%, #1a2332 100%);
  position: relative;
  overflow: hidden;
}

/* Pattern hexagonal de fondo */
.destiny-hex-pattern {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-image: 
    radial-gradient(circle at 25px 25px, rgba(199,184,138,0.05) 2px, transparent 2px),
    radial-gradient(circle at 75px 75px, rgba(110,193,228,0.05) 2px, transparent 2px);
  background-size: 100px 100px;
  animation: hex-float 20s ease-in-out infinite;
}

@keyframes hex-float {
  0%, 100% { transform: translateY(0px) rotate(0deg); }
  50% { transform: translateY(-10px) rotate(1deg); }
}

/* Partículas de fondo */
.destiny-particles {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23C7B88A' fill-opacity='0.03'%3E%3Ccircle cx='30' cy='30' r='1'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
  animation: particles-drift 30s linear infinite;
}

@keyframes particles-drift {
  0% { transform: translateX(0px) translateY(0px); }
  100% { transform: translateX(-60px) translateY(-60px); }
}

/* Card principal */
.destiny-card {
  background: rgba(27, 28, 41, 0.9);
  backdrop-filter: blur(20px);
  border: 1px solid rgba(199, 184, 138, 0.2);
  border-radius: 16px;
  box-shadow: 
    0 8px 32px rgba(0, 0, 0, 0.4),
    inset 0 1px 0 rgba(255, 255, 255, 0.1);
  position: relative;
  overflow: hidden;
}

.destiny-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 1px;
  background: linear-gradient(90deg, transparent, var(--destiny-gold), transparent);
  animation: shine 3s ease-in-out infinite;
}

@keyframes shine {
  0%, 100% { opacity: 0; }
  50% { opacity: 1; }
}

.destiny-card-content {
  padding: 2rem;
}

/* Efectos glow */
.destiny-glow-gold {
  box-shadow: 0 0 20px rgba(199, 184, 138, 0.6);
}

.destiny-glow-cyan {
  box-shadow: 0 0 20px rgba(110, 193, 228, 0.6);
}

.destiny-glow-purple {
  box-shadow: 0 0 20px rgba(182, 161, 228, 0.6);
}

/* Inputs */
.destiny-label {
  display: block;
  font-size: 0.875rem;
  font-weight: 600;
  color: var(--destiny-gold);
  margin-bottom: 0.5rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.destiny-input {
  width: 100%;
  padding: 0.75rem 1rem;
  background: rgba(42, 52, 65, 0.8);
  border: 1px solid rgba(110, 193, 228, 0.3);
  border-radius: 8px;
  color: white;
  font-size: 1rem;
  transition: all 0.3s ease;
}

.destiny-input:focus {
  outline: none;
  border-color: var(--destiny-cyan);
  box-shadow: 0 0 0 3px rgba(110, 193, 228, 0.1);
  background: rgba(42, 52, 65, 1);
}

.destiny-input::placeholder {
  color: rgba(255, 255, 255, 0.4);
}

.destiny-input-error {
  border-color: #ef4444;
  box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
}

/* Checkbox */
.destiny-checkbox {
  width: 1rem;
  height: 1rem;
  border: 1px solid var(--destiny-cyan);
  border-radius: 3px;
  background: transparent;
  accent-color: var(--destiny-gold);
}

/* Botones */
.destiny-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 0.75rem 1.5rem;
  font-weight: 600;
  border-radius: 8px;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  transition: all 0.3s ease;
  border: none;
  cursor: pointer;
  position: relative;
  overflow: hidden;
}

.destiny-btn::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
  transition: left 0.5s;
}

.destiny-btn:hover::before {
  left: 100%;
}

.destiny-btn-primary {
  background: linear-gradient(135deg, var(--destiny-gold), #d4c79a);
  color: var(--destiny-primary);
  box-shadow: 0 4px 15px rgba(199, 184, 138, 0.3);
}

.destiny-btn-primary:hover:not(:disabled) {
  background: linear-gradient(135deg, #d4c79a, var(--destiny-gold));
  box-shadow: 0 6px 20px rgba(199, 184, 138, 0.4);
  transform: translateY(-1px);
}

.destiny-btn-primary:disabled {
  opacity: 0.5;
  cursor: not-allowed;
  transform: none;
}

.destiny-btn-secondary {
  background: rgba(110, 193, 228, 0.1);
  color: var(--destiny-cyan);
  border: 1px solid var(--destiny-cyan);
}

.destiny-btn-secondary:hover {
  background: rgba(110, 193, 228, 0.2);
  box-shadow: 0 0 15px rgba(110, 193, 228, 0.3);
}

/* Enlaces */
.destiny-link {
  color: var(--destiny-cyan);
  text-decoration: none;
  font-weight: 500;
  transition: all 0.3s ease;
}

.destiny-link:hover {
  color: var(--destiny-gold);
  text-shadow: 0 0 8px rgba(199, 184, 138, 0.6);
}

/* Spinners */
.destiny-spinner {
  width: 2rem;
  height: 2rem;
  border: 3px solid rgba(199, 184, 138, 0.3);
  border-top: 3px solid var(--destiny-gold);
  border-radius: 50%;
  animation: destiny-spin 1s linear infinite;
}

.destiny-spinner-sm {
  width: 1rem;
  height: 1rem;
  border: 2px solid rgba(199, 184, 138, 0.3);
  border-top: 2px solid var(--destiny-gold);
  border-radius: 50%;
  animation: destiny-spin 1s linear infinite;
}

@keyframes destiny-spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

/* Alertas */
.destiny-alert-error {
  background: rgba(239, 68, 68, 0.1);
  border: 1px solid rgba(239, 68, 68, 0.3);
  border-radius: 8px;
  padding: 0.75rem;
  color: #fca5a5;
}

/* Textos de error */
.destiny-error-text {
  color: #fca5a5;
  font-size: 0.875rem;
  margin-top: 0.25rem;
  font-weight: 500;
}

/* Modal */
.destiny-modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.8);
  backdrop-filter: blur(4px);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  padding: 1rem;
}

.destiny-modal {
  background: rgba(27, 28, 41, 0.95);
  backdrop-filter: blur(20px);
  border: 1px solid rgba(199, 184, 138, 0.2);
  border-radius: 16px;
  padding: 2rem;
  max-width: 400px;
  width: 100%;
  box-shadow: 
    0 20px 60px rgba(0, 0, 0, 0.5),
    inset 0 1px 0 rgba(255, 255, 255, 0.1);
}

/* Logo container */
.destiny-logo-container {
  position: relative;
}

.destiny-logo-container::before {
  content: '';
  position: absolute;
  top: -4px;
  left: -4px;
  right: -4px;
  bottom: -4px;
  background: linear-gradient(45deg, var(--destiny-gold), var(--destiny-cyan), var(--destiny-purple), var(--destiny-gold));
  border-radius: 50%;
  opacity: 0.3;
  animation: logo-pulse 3s ease-in-out infinite;
}

@keyframes logo-pulse {
  0%, 100% { 
    opacity: 0.3;
    transform: scale(1);
  }
  50% { 
    opacity: 0.6;
    transform: scale(1.05);
  }
}

/* Animaciones de entrada */
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

/* Responsivo */
@media (max-width: 640px) {
  .destiny-card-content {
    padding: 1.5rem;
  }
  
  .destiny-btn {
    padding: 0.875rem 1rem;
    font-size: 0.875rem;
  }
  
  h2 {
    font-size: 2rem;
  }
}

/* Estados de hover mejorados */
.destiny-input:hover {
  border-color: rgba(110, 193, 228, 0.5);
}

.destiny-card:hover {
  border-color: rgba(199, 184, 138, 0.3);
  box-shadow: 
    0 12px 40px rgba(0, 0, 0, 0.5),
    inset 0 1px 0 rgba(255, 255, 255, 0.15);
}

/* Efectos de partículas adicionales */
.destiny-particles::after {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: radial-gradient(2px 2px at 20px 30px, var(--destiny-gold), transparent),
              radial-gradient(2px 2px at 40px 70px, var(--destiny-cyan), transparent),
              radial-gradient(1px 1px at 90px 40px, var(--destiny-purple), transparent),
              radial-gradient(1px 1px at 130px 80px, var(--destiny-gold), transparent);
  background-size: 200px 200px;
  animation: particles-twinkle 4s ease-in-out infinite;
  opacity: 0.1;
}

@keyframes particles-twinkle {
  0%, 100% { opacity: 0.1; }
  50% { opacity: 0.3; }
}

/* Mejoras de accesibilidad */
.destiny-btn:focus,
.destiny-input:focus,
.destiny-link:focus {
  outline: 2px solid var(--destiny-cyan);
  outline-offset: 2px;
}

/* Transiciones suaves para estados */
* {
  transition-property: background-color, border-color, color, fill, stroke, opacity, box-shadow, transform;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 150ms;
}

/* Efectos adicionales para el formulario */
.destiny-card-content form {
  position: relative;
}

.destiny-card-content form::before {
  content: '';
  position: absolute;
  top: -10px;
  left: -10px;
  right: -10px;
  bottom: -10px;
  background: linear-gradient(45deg, 
    rgba(199, 184, 138, 0.1), 
    rgba(110, 193, 228, 0.1), 
    rgba(182, 161, 228, 0.1), 
    rgba(199, 184, 138, 0.1)
  );
  border-radius: 20px;
  opacity: 0;
  transition: opacity 0.3s ease;
  z-index: -1;
  animation: form-glow 6s ease-in-out infinite;
}

@keyframes form-glow {
  0%, 100% { opacity: 0; }
  50% { opacity: 0.1; }
}

/* Estado de loading mejorado */
.destiny-btn-primary:disabled {
  background: linear-gradient(135deg, rgba(199, 184, 138, 0.3), rgba(212, 199, 154, 0.3));
  color: rgba(27, 28, 41, 0.6);
  box-shadow: none;
}

/* Textos con efectos */
.text-accent-cyan {
  color: var(--destiny-cyan);
  text-shadow: 0 0 10px rgba(110, 193, 228, 0.3);
}

.text-accent-gold {
  color: var(--destiny-gold);
  text-shadow: 0 0 10px rgba(199, 184, 138, 0.3);
}

/* Mejoras para dispositivos móviles */
@media (max-width: 480px) {
  .destiny-login-container {
    padding: 1rem 0.5rem;
  }
  
  .destiny-modal {
    padding: 1.5rem;
    margin: 1rem;
  }
  
  .text-4xl {
    font-size: 1.875rem;
  }
  
  .text-lg {
    font-size: 1rem;
  }
}

/* Animación de carga de página */
.destiny-login-container {
  animation: pageLoad 1s ease-out;
}

@keyframes pageLoad {
  from {
    opacity: 0;
    transform: scale(1.02);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}
</style>