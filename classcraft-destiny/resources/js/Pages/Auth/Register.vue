<template>
  <div class="min-h-screen destiny-bg-primary relative overflow-hidden">
    <!-- Animated Background -->
    <div class="fixed inset-0 z-0">
      <div class="nebula-background">
        <div class="floating-particles"></div>
        <div class="energy-waves"></div>
      </div>
    </div>

    <div class="relative z-10 flex items-center justify-center min-h-screen p-4">
      <div class="w-full max-w-4xl">
        <!-- Header -->
        <div class="text-center mb-8">
          <h1 class="text-4xl md:text-5xl font-bold text-white mb-2 destiny-glow-gold tracking-wider">
            FORJA DE GUARDIÁN
          </h1>
          <p class="destiny-text-muted text-lg">
            Únete a la Torre y forja tu leyenda
          </p>
        </div>

        <!-- Multi-step Progress Bar -->
        <div class="mb-8">
          <div class="flex items-center justify-center space-x-4">
            <div
              v-for="(step, index) in steps"
              :key="index"
              class="flex items-center"
            >
              <!-- Step Icon -->
              <div
                :class="[
                  'relative w-12 h-12 rounded-full border-2 flex items-center justify-center transition-all duration-500',
                  currentStep > index + 1
                    ? 'bg-green-500 border-green-500 text-white destiny-glow-green'
                    : currentStep === index + 1
                    ? 'bg-cyan-500 border-cyan-500 text-white destiny-glow-cyan'
                    : 'bg-black/30 border-gray-600 text-gray-400'
                ]"
              >
                <!-- STEP_ICONS: Iconos para cada paso del wizard -->
                <svg v-if="currentStep > index + 1" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <svg v-else-if="index === 0" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <svg v-else-if="index === 1" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                
                <!-- Hexagonal Progress Indicator -->
                <div
                  v-if="currentStep === index + 1"
                  class="absolute inset-0 rounded-full border-2 border-cyan-400 animate-pulse"
                ></div>
              </div>

              <!-- Step Label -->
              <div class="ml-3 text-sm font-medium text-white">
                {{ step.title }}
              </div>

              <!-- Connector Line -->
              <div
                v-if="index < steps.length - 1"
                :class="[
                  'mx-4 h-0.5 w-16 transition-all duration-500',
                  currentStep > index + 1 ? 'bg-green-500 destiny-glow-green' : 'bg-gray-600'
                ]"
              ></div>
            </div>
          </div>
        </div>

        <!-- Main Form Card -->
        <div class="holo-panel rounded-2xl p-8 space-y-6">
          <form @submit.prevent="handleSubmit" class="space-y-6">
            
            <!-- STEP 1: Datos Personales -->
            <div v-show="currentStep === 1" class="space-y-6">
              <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-white destiny-glow-cyan">
                  Información Personal
                </h2>
                <p class="text-destiny-gold text-sm mt-2">
                  Proporciona tus datos para crear tu perfil de Guardián
                </p>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nombre -->
                <div class="space-y-2">
                  <label for="nombre" class="block destiny-text text-sm font-medium">
                    <span class="flex items-center space-x-2">
                      <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                      </svg>
                      <span>Nombre *</span>
                    </span>
                  </label>
                  <input
                    id="nombre"
                    v-model="registerForm.nombre"
                    type="text"
                    autocomplete="given-name"
                    required
                    maxlength="100"
                    class="w-full px-4 py-3 rounded-lg bg-black/20 border border-cyan-500/30 text-white placeholder-gray-400 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/20 focus:outline-none transition-all duration-300"
                    placeholder="Tu nombre"
                    :class="{ 'border-red-500': stepErrors[1]?.includes('nombre') }"
                  />
                  <div v-if="stepErrors[1]?.includes('nombre')" class="text-red-400 text-sm">
                    El nombre es obligatorio
                  </div>
                </div>

                <!-- Apellido -->
                <div class="space-y-2">
                  <label for="apellido" class="block destiny-text text-sm font-medium">
                    <span class="flex items-center space-x-2">
                      <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                      </svg>
                      <span>Apellido *</span>
                    </span>
                  </label>
                  <input
                    id="apellido"
                    v-model="registerForm.apellido"
                    type="text"
                    autocomplete="family-name"
                    required
                    maxlength="100"
                    class="w-full px-4 py-3 rounded-lg bg-black/20 border border-cyan-500/30 text-white placeholder-gray-400 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/20 focus:outline-none transition-all duration-300"
                    placeholder="Tu apellido"
                    :class="{ 'border-red-500': stepErrors[1]?.includes('apellido') }"
                  />
                  <div v-if="stepErrors[1]?.includes('apellido')" class="text-red-400 text-sm">
                    El apellido es obligatorio
                  </div>
                </div>
              </div>

              <!-- Email -->
              <div class="space-y-2">
                <label for="correo" class="block destiny-text text-sm font-medium">
                  <span class="flex items-center space-x-2">
                    <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                    </svg>
                    <span>Correo Electrónico *</span>
                  </span>
                </label>
                <input
                  id="correo"
                  v-model="registerForm.correo"
                  type="email"
                  autocomplete="email"
                  required
                  class="w-full px-4 py-3 rounded-lg bg-black/20 border border-cyan-500/30 text-white placeholder-gray-400 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/20 focus:outline-none transition-all duration-300"
                  placeholder="tu@correo.com"
                  :class="{ 'border-red-500': stepErrors[1]?.includes('correo') }"
                  @blur="validateEmail"
                />
                <div v-if="stepErrors[1]?.includes('correo')" class="text-red-400 text-sm">
                  Email inválido o ya registrado
                </div>
                <div v-if="emailValidating" class="text-cyan-400 text-sm flex items-center">
                  <svg class="animate-spin w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                  </svg>
                  Verificando disponibilidad...
                </div>
              </div>

              <!-- Passwords -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                  <label for="password" class="block destiny-text text-sm font-medium">
                    <span class="flex items-center space-x-2">
                      <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                      </svg>
                      <span>Contraseña *</span>
                    </span>
                  </label>
                  <input
                    id="password"
                    v-model="registerForm.password"
                    type="password"
                    autocomplete="new-password"
                    required
                    minlength="8"
                    class="w-full px-4 py-3 rounded-lg bg-black/20 border border-cyan-500/30 text-white placeholder-gray-400 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/20 focus:outline-none transition-all duration-300"
                    placeholder="Mínimo 8 caracteres"
                    :class="{ 'border-red-500': stepErrors[1]?.includes('password') }"
                  />
                  <div v-if="stepErrors[1]?.includes('password')" class="text-red-400 text-sm">
                    La contraseña debe tener al menos 8 caracteres
                  </div>
                </div>

                <div class="space-y-2">
                  <label for="password_confirmation" class="block destiny-text text-sm font-medium">
                    <span class="flex items-center space-x-2">
                      <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                      </svg>
                      <span>Confirmar Contraseña *</span>
                    </span>
                  </label>
                  <input
                    id="password_confirmation"
                    v-model="registerForm.password_confirmation"
                    type="password"
                    autocomplete="new-password"
                    required
                    class="w-full px-4 py-3 rounded-lg bg-black/20 border border-cyan-500/30 text-white placeholder-gray-400 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/20 focus:outline-none transition-all duration-300"
                    placeholder="Repite tu contraseña"
                    :class="{ 'border-red-500': stepErrors[1]?.includes('password_confirmation') }"
                  />
                  <div v-if="stepErrors[1]?.includes('password_confirmation')" class="text-red-400 text-sm">
                    Las contraseñas no coinciden
                  </div>
                </div>
              </div>

              <!-- Rol Selection -->
              <div class="space-y-3">
                <label class="block destiny-text text-sm font-medium">
                  <span class="flex items-center space-x-2">
                    <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/>
                    </svg>
                    <span>Tipo de Usuario *</span>
                  </span>
                </label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div
                    @click="registerForm.id_tipo_usuario = 2"
                    class="relative p-4 rounded-lg border-2 cursor-pointer transition-all duration-300"
                    :class="registerForm.id_tipo_usuario === 2 
                      ? 'border-cyan-400 bg-cyan-500/10 destiny-glow-cyan' 
                      : 'border-gray-600 bg-black/20 hover:border-gray-500'"
                  >
                    <div class="flex items-center space-x-3">
                      <div class="w-10 h-10 rounded-full bg-cyan-500/20 flex items-center justify-center">
                        <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C20.832 18.477 19.246 18 17.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                      </div>
                      <div>
                        <h3 class="text-white font-semibold">Estudiante</h3>
                        <p class="text-gray-400 text-sm">Unirse a clases y crear personajes</p>
                      </div>
                    </div>
                    <div v-if="registerForm.id_tipo_usuario === 2" class="absolute top-2 right-2">
                      <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                      </svg>
                    </div>
                  </div>

                  <div
                    @click="registerForm.id_tipo_usuario = 1"
                    class="relative p-4 rounded-lg border-2 cursor-pointer transition-all duration-300"
                    :class="registerForm.id_tipo_usuario === 1 
                      ? 'border-cyan-400 bg-cyan-500/10 destiny-glow-cyan' 
                      : 'border-gray-600 bg-black/20 hover:border-gray-500'"
                  >
                    <div class="flex items-center space-x-3">
                      <div class="w-10 h-10 rounded-full bg-purple-500/20 flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-6m-8 0H3m2 0h6M9 7h6m-6 4h6m-6 4h6"/>
                        </svg>
                      </div>
                      <div>
                        <h3 class="text-white font-semibold">Profesor</h3>
                        <p class="text-gray-400 text-sm">Crear y gestionar clases</p>
                      </div>
                    </div>
                    <div v-if="registerForm.id_tipo_usuario === 1" class="absolute top-2 right-2">
                      <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                      </svg>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- STEP 2: Avatar -->
            <div v-show="currentStep === 2" class="space-y-6">
              <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-white destiny-glow-cyan">
                  Avatar del Guardián
                </h2>
                <p class="text-destiny-gold text-sm mt-2">
                  Selecciona o sube tu imagen de perfil
                </p>
              </div>

              <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Avatar Upload -->
                <div class="space-y-4">
                  <div class="text-center">
                    <!-- Current Avatar Preview -->
                    <div class="mx-auto w-32 h-32 rounded-full overflow-hidden border-4 border-cyan-500 destiny-glow-cyan mb-4">
                      <!-- AVATAR_PLACEHOLDER: Imagen por defecto para avatar -->
                      <img
                        v-if="avatarPreview"
                        :src="avatarPreview"
                        alt="Avatar preview"
                        class="w-full h-full object-cover"
                      />
                      <div
                        v-else
                        class="w-full h-full bg-gradient-to-br from-cyan-600 to-purple-600 flex items-center justify-center"
                      >
                        <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                      </div>
                    </div>

                    <!-- Upload Controls -->
                    <div class="space-y-3">
                      <label
                        for="avatar-upload"
                        class="destiny-btn destiny-btn-primary cursor-pointer inline-flex items-center px-6 py-3 rounded-lg text-sm font-medium transition-all duration-300"
                      >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        Subir Avatar
                      </label>
                      <input
                        id="avatar-upload"
                        type="file"
                        accept="image/*"
                        class="hidden"
                        @change="handleAvatarUpload"
                      />

                      <div v-if="uploadProgress > 0 && uploadProgress < 100" class="w-full">
                        <div class="w-full bg-gray-700 rounded-full h-2">
                          <div
                            class="bg-cyan-500 h-2 rounded-full transition-all duration-300"
                            :style="{ width: uploadProgress + '%' }"
                          ></div>
                        </div>
                        <p class="text-cyan-400 text-sm mt-1">Subiendo... {{ uploadProgress }}%</p>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Avatar Presets -->
                <div class="space-y-4">
                  <h3 class="text-white font-semibold text-center">Avatares Predeterminados</h3>
                  <div class="grid grid-cols-3 gap-4">
                    <div
                      v-for="(preset, index) in avatarPresets"
                      :key="index"
                      @click="selectPresetAvatar(preset)"
                      class="w-16 h-16 rounded-full overflow-hidden border-2 cursor-pointer transition-all duration-300 hover:border-cyan-400 hover:scale-110"
                      :class="registerForm.avatar === preset ? 'border-cyan-400 destiny-glow-cyan' : 'border-gray-600'"
                    >
                      <img
                        :src="preset"
                        :alt="`Avatar preset ${index + 1}`"
                        class="w-full h-full object-cover"
                      />
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- STEP 3: Confirmación -->
            <div v-show="currentStep === 3" class="space-y-6">
              <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-white destiny-glow-cyan">
                  Confirmación
                </h2>
                <p class="text-destiny-gold text-sm mt-2">
                  Revisa tu información antes de crear tu cuenta
                </p>
              </div>

              <div class="holo-panel bg-black/20 rounded-xl p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <!-- Profile Summary -->
                  <div class="space-y-4">
                    <h3 class="text-white font-semibold border-b border-cyan-500/30 pb-2">
                      Información Personal
                    </h3>
                    <div class="space-y-2 text-sm">
                      <div class="flex justify-between">
                        <span class="text-gray-400">Nombre:</span>
                        <span class="text-white">{{ registerForm.nombre }} {{ registerForm.apellido }}</span>
                      </div>
                      <div class="flex justify-between">
                        <span class="text-gray-400">Email:</span>
                        <span class="text-white">{{ registerForm.correo }}</span>
                      </div>
                      <div class="flex justify-between">
                        <span class="text-gray-400">Tipo:</span>
                        <span class="text-white">
                          {{ registerForm.id_tipo_usuario === 1 ? 'Profesor' : 'Estudiante' }}
                        </span>
                      </div>
                    </div>
                  </div>

                  <!-- Avatar Summary -->
                  <div class="space-y-4">
                    <h3 class="text-white font-semibold border-b border-cyan-500/30 pb-2">
                      Avatar
                    </h3>
                    <div class="flex items-center space-x-4">
                      <div class="w-16 h-16 rounded-full overflow-hidden border-2 border-cyan-500">
                        <img
                          v-if="avatarPreview || registerForm.avatar"
                          :src="avatarPreview || registerForm.avatar"
                          alt="Avatar final"
                          class="w-full h-full object-cover"
                        />
                        <div
                          v-else
                          class="w-full h-full bg-gradient-to-br from-cyan-600 to-purple-600 flex items-center justify-center"
                        >
                          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                          </svg>
                        </div>
                      </div>
                      <div class="text-sm">
                        <p class="text-white font-medium">Avatar configurado</p>
                        <p class="text-gray-400">Listo para la Torre</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Terms and Conditions -->
              <div class="space-y-4">
                <div class="flex items-start space-x-3">
                  <input
                    id="terms"
                    v-model="termsAccepted"
                    type="checkbox"
                    class="mt-1 w-4 h-4 text-cyan-500 bg-black/30 border-gray-600 rounded focus:ring-cyan-500 focus:ring-2"
                    required
                  />
                  <label for="terms" class="text-sm text-gray-300">
                    Acepto los 
                    <a href="/terms" target="_blank" class="text-cyan-400 hover:text-cyan-300 underline">
                      términos y condiciones
                    </a>
                    y la 
                    <a href="/privacy" target="_blank" class="text-cyan-400 hover:text-cyan-300 underline">
                      política de privacidad
                    </a>
                    del sistema Zenthoria
                  </label>
                </div>
                <div v-if="!termsAccepted && showTermsError" class="text-red-400 text-sm">
                  Debes aceptar los términos y condiciones para continuar
                </div>
              </div>

              <!-- Final CTA -->
              <div class="text-center space-y-4">
                <div class="p-4 rounded-lg bg-cyan-500/10 border border-cyan-500/30">
                  <svg class="w-8 h-8 text-cyan-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                  </svg>
                  <p class="text-white font-medium">¡Tu leyenda está a punto de comenzar!</p>
                  <p class="text-gray-400 text-sm mt-1">
                    Únete a miles de Guardianes en la Torre
                  </p>
                </div>
              </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="flex justify-between items-center pt-6 border-t border-gray-700">
              <!-- Back Button -->
              <button
                v-if="currentStep > 1"
                @click="previousStep"
                type="button"
                class="destiny-btn destiny-btn-secondary inline-flex items-center px-6 py-3 rounded-lg text-sm font-medium transition-all duration-300"
                :disabled="isSubmitting"
              >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Anterior
              </button>
              <div v-else></div>

              <!-- Next/Submit Button -->
              <button
                v-if="currentStep < 3"
                @click="nextStep"
                type="button"
                class="destiny-btn destiny-btn-primary inline-flex items-center px-6 py-3 rounded-lg text-sm font-medium transition-all duration-300"
                :disabled="!canProceedToNextStep"
              >
                Siguiente
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
              </button>
              <button
                v-else
                type="submit"
                class="destiny-btn destiny-btn-success inline-flex items-center px-8 py-3 rounded-lg text-sm font-medium transition-all duration-300 transform hover:scale-105"
                :disabled="!termsAccepted || isSubmitting"
              >
                <svg v-if="isSubmitting" class="animate-spin w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                <svg v-else class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                {{ isSubmitting ? 'Forjando Guardián...' : 'Forjar Guardián' }}
              </button>
            </div>
          </form>
        </div>

        <!-- Login Link -->
        <div class="text-center mt-8">
          <p class="destiny-text-muted text-sm">
            ¿Ya tienes una cuenta en la Torre?
          </p>
          <router-link
            to="/login"
            class="destiny-btn destiny-btn-ghost inline-flex items-center px-6 py-2 rounded-lg text-sm font-medium transition-all duration-300 transform hover:scale-105 mt-2"
          >
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
            </svg>
            Iniciar Sesión
          </router-link>
        </div>
      </div>
    </div>

    <!-- Success Animation Modal -->
    <div
      v-if="showSuccessAnimation"
      class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm"
    >
      <div class="holo-panel rounded-2xl p-8 max-w-md w-full mx-4 text-center">
        <!-- SUCCESS_ANIMATION: Asset para animación de éxito -->
        <div class="mb-6">
          <div class="w-20 h-20 mx-auto rounded-full bg-green-500/20 flex items-center justify-center destiny-glow-green animate-pulse">
            <svg class="w-10 h-10 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
          </div>
        </div>
        
        <h3 class="text-2xl font-bold text-white mb-4 destiny-glow-green">
          ¡Guardián Forjado!
        </h3>
        <p class="text-gray-300 mb-6">
          Tu cuenta ha sido creada exitosamente. Bienvenido a la Torre, Guardián {{ registerForm.nombre }}.
        </p>
        
        <button
          @click="redirectToDashboard"
          class="destiny-btn destiny-btn-success w-full py-3 rounded-lg font-medium transition-all duration-300"
        >
          Acceder a la Torre
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, reactive, watch } from 'vue'
import { useRouter } from 'vue-router'
import * as yup from 'yup'

// Router
const router = useRouter()

// Reactive state
const currentStep = ref(1)
const isSubmitting = ref(false)
const uploadProgress = ref(0)
const avatarPreview = ref('')
const termsAccepted = ref(false)
const showTermsError = ref(false)
const showSuccessAnimation = ref(false)
const emailValidating = ref(false)

// Form data matching database structure
const registerForm = reactive({
  nombre: '',
  apellido: '',
  correo: '',
  password: '',
  password_confirmation: '',
  id_tipo_usuario: 2, // Default to estudiante
  avatar: '/images/avatars/student_default.jpg'
})

// Step errors tracking
const stepErrors = reactive<Record<number, string[]>>({
  1: [],
  2: [],
  3: []
})

// Wizard steps configuration
const steps = [
  { title: 'Datos Personales', icon: 'user' },
  { title: 'Avatar', icon: 'image' },
  { title: 'Confirmación', icon: 'check' }
]

// Avatar presets based on your project structure
const avatarPresets = [
  '/images/avatars/student_default.jpg',
  '/images/avatars/teacher_default.jpg',
  '/images/avatars/ana.jpg',
  '/images/avatars/luis.jpg',
  '/images/avatars/maria.jpg',
  '/images/avatars/diego.jpg',
  '/images/avatars/sofia.jpg',
  '/images/avatars/gabriel.jpg'
]

// Validation schemas using Yup
const stepSchemas = {
  1: yup.object({
    nombre: yup.string().required('El nombre es obligatorio').max(100, 'Máximo 100 caracteres'),
    apellido: yup.string().required('El apellido es obligatorio').max(100, 'Máximo 100 caracteres'),
    correo: yup.string().email('Email inválido').required('El correo es obligatorio'),
    password: yup.string().min(8, 'Mínimo 8 caracteres').required('La contraseña es obligatoria'),
    password_confirmation: yup.string()
      .oneOf([yup.ref('password')], 'Las contraseñas no coinciden')
      .required('Confirma tu contraseña'),
    id_tipo_usuario: yup.number().oneOf([1, 2], 'Selecciona un tipo de usuario').required()
  }),
  2: yup.object({
    avatar: yup.string().required('Selecciona un avatar')
  }),
  3: yup.object({})
}

// Computed properties
const canProceedToNextStep = computed(() => {
  try {
    stepSchemas[currentStep.value as keyof typeof stepSchemas].validateSync(registerForm)
    return stepErrors[currentStep.value].length === 0
  } catch {
    return false
  }
})

// Watch for real-time validation
watch(registerForm, () => {
  validateCurrentStep()
}, { deep: true })

// Methods
const validateCurrentStep = async () => {
  const schema = stepSchemas[currentStep.value as keyof typeof stepSchemas]
  stepErrors[currentStep.value] = []
  
  try {
    await schema.validate(registerForm, { abortEarly: false })
  } catch (error) {
    if (error instanceof yup.ValidationError) {
      stepErrors[currentStep.value] = error.inner.map(err => err.path || '')
    }
  }
}

const validateEmail = async () => {
  if (!registerForm.correo || !registerForm.correo.includes('@')) return
  
  emailValidating.value = true
  
  try {
    const response = await fetch('/api/verificar-email', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
      },
      body: JSON.stringify({ correo: registerForm.correo })
    })
    
    const data = await response.json()
    
    if (!data.disponible) {
      stepErrors[1] = [...stepErrors[1].filter(e => e !== 'correo'), 'correo']
    } else {
      stepErrors[1] = stepErrors[1].filter(e => e !== 'correo')
    }
  } catch (error) {
    console.error('Error validating email:', error)
  } finally {
    emailValidating.value = false
  }
}

const nextStep = async () => {
  await validateCurrentStep()
  
  if (canProceedToNextStep.value) {
    if (currentStep.value < 3) {
      currentStep.value++
    }
  }
}

const previousStep = () => {
  if (currentStep.value > 1) {
    currentStep.value--
  }
}

const handleAvatarUpload = async (event: Event) => {
  const file = (event.target as HTMLInputElement).files?.[0]
  if (!file) return
  
  // Validate file type and size
  const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp']
  if (!allowedTypes.includes(file.type)) {
    alert('Por favor selecciona una imagen válida (JPEG, PNG, GIF, WebP)')
    return
  }
  
  if (file.size > 5 * 1024 * 1024) { // 5MB limit
    alert('La imagen debe ser menor a 5MB')
    return
  }
  
  // Create preview
  const reader = new FileReader()
  reader.onload = (e) => {
    avatarPreview.value = e.target?.result as string
  }
  reader.readAsDataURL(file)
  
  // Simulate upload progress
  uploadProgress.value = 0
  const progressInterval = setInterval(() => {
    uploadProgress.value += 10
    if (uploadProgress.value >= 100) {
      clearInterval(progressInterval)
      
      // Upload to server
      uploadAvatarToServer(file)
    }
  }, 100)
}

const uploadAvatarToServer = async (file: File) => {
  try {
    const formData = new FormData()
    formData.append('avatar', file)
    
    const response = await fetch('/api/upload/avatar', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
      },
      body: formData
    })
    
    const data = await response.json()
    
    if (data.success) {
      registerForm.avatar = data.avatar_url
    } else {
      throw new Error(data.message || 'Error uploading avatar')
    }
  } catch (error) {
    console.error('Upload error:', error)
    alert('Error al subir la imagen. Por favor intenta de nuevo.')
    uploadProgress.value = 0
    avatarPreview.value = ''
  }
}

const selectPresetAvatar = (presetUrl: string) => {
  registerForm.avatar = presetUrl
  avatarPreview.value = presetUrl
  uploadProgress.value = 0
}

const handleSubmit = async () => {
  if (!termsAccepted.value) {
    showTermsError.value = true
    return
  }
  
  isSubmitting.value = true
  showTermsError.value = false
  
  try {
    // Validate all steps
    for (let step = 1; step <= 3; step++) {
      const schema = stepSchemas[step as keyof typeof stepSchemas]
      await schema.validate(registerForm, { abortEarly: false })
    }
    
    // Submit to registration endpoint
    const response = await fetch('/api/auth/register', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
      },
      body: JSON.stringify({
        nombre: registerForm.nombre,
        apellido: registerForm.apellido,
        correo: registerForm.correo,
        password: registerForm.password,
        password_confirmation: registerForm.password_confirmation,
        id_tipo_usuario: registerForm.id_tipo_usuario,
        avatar: registerForm.avatar
      })
    })
    
    const data = await response.json()
    
    if (response.ok) {
      // Show success animation
      showSuccessAnimation.value = true
      
      // Auto-redirect after animation
      setTimeout(() => {
        redirectToDashboard()
      }, 3000)
    } else {
      // Handle validation errors
      if (data.errors) {
        Object.keys(data.errors).forEach(field => {
          const stepForField = getStepForField(field)
          if (stepForField) {
            stepErrors[stepForField] = [...stepErrors[stepForField], field]
          }
        })
        
        // Go to first step with errors
        const firstErrorStep = Object.keys(stepErrors).find(step => 
          stepErrors[parseInt(step)].length > 0
        )
        if (firstErrorStep) {
          currentStep.value = parseInt(firstErrorStep)
        }
      } else {
        alert(data.message || 'Error al crear la cuenta. Por favor intenta de nuevo.')
      }
    }
  } catch (error) {
    console.error('Registration error:', error)
    alert('Error al crear la cuenta. Por favor intenta de nuevo.')
  } finally {
    isSubmitting.value = false
  }
}

const getStepForField = (field: string): number | null => {
  const fieldStepMap: Record<string, number> = {
    'nombre': 1,
    'apellido': 1,
    'correo': 1,
    'password': 1,
    'password_confirmation': 1,
    'id_tipo_usuario': 1,
    'avatar': 2
  }
  
  return fieldStepMap[field] || null
}

const redirectToDashboard = () => {
  const dashboardRoute = registerForm.id_tipo_usuario === 1 
    ? '/profesor/dashboard' 
    : '/estudiante/dashboard'
  
  window.location.href = dashboardRoute
}

// Initialize default avatar preview
avatarPreview.value = registerForm.avatar
</script>

<style scoped>
/* Destiny 2 inspired styles */
.destiny-bg-primary {
  background: linear-gradient(135deg, #0a0a0f 0%, #1a1a2e 50%, #16213e 100%);
}

.holo-panel {
  background: linear-gradient(145deg, rgba(13, 25, 43, 0.9), rgba(16, 35, 70, 0.6));
  border: 1px solid rgba(59, 130, 246, 0.3);
  backdrop-filter: blur(10px);
  box-shadow: 
    0 8px 32px rgba(0, 0, 0, 0.3),
    inset 0 1px 0 rgba(255, 255, 255, 0.1);
}

.destiny-glow-cyan {
  text-shadow: 0 0 10px rgba(6, 182, 212, 0.5);
  box-shadow: 0 0 20px rgba(6, 182, 212, 0.3);
}

.destiny-glow-green {
  text-shadow: 0 0 10px rgba(34, 197, 94, 0.5);
  box-shadow: 0 0 20px rgba(34, 197, 94, 0.3);
}

.destiny-glow-gold {
  text-shadow: 0 0 10px rgba(251, 191, 36, 0.5);
}

.destiny-text {
  color: #e2e8f0;
}

.destiny-text-muted {
  color: #94a3b8;
}

.destiny-btn {
  position: relative;
  overflow: hidden;
  transition: all 0.3s ease;
  border: 1px solid transparent;
}

.destiny-btn-primary {
  background: linear-gradient(135deg, #0891b2, #0e7490);
  color: white;
  border-color: #0891b2;
}

.destiny-btn-primary:hover {
  background: linear-gradient(135deg, #0e7490, #155e75);
  box-shadow: 0 0 20px rgba(8, 145, 178, 0.4);
}

.destiny-btn-secondary {
  background: linear-gradient(135deg, #374151, #4b5563);
  color: white;
  border-color: #6b7280;
}

.destiny-btn-secondary:hover {
  background: linear-gradient(135deg, #4b5563, #6b7280);
}

.destiny-btn-success {
  background: linear-gradient(135deg, #059669, #047857);
  color: white;
  border-color: #10b981;
}

.destiny-btn-success:hover {
  background: linear-gradient(135deg, #047857, #065f46);
  box-shadow: 0 0 20px rgba(16, 185, 129, 0.4);
}

.destiny-btn-ghost {
  background: transparent;
  color: #06b6d4;
  border-color: #06b6d4;
}

.destiny-btn-ghost:hover {
  background: rgba(6, 182, 212, 0.1);
  box-shadow: 0 0 15px rgba(6, 182, 212, 0.3);
}

.destiny-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

/* Background animations */
.nebula-background {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: 
    radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.1) 0%, transparent 50%),
    radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.1) 0%, transparent 50%),
    radial-gradient(circle at 40% 40%, rgba(120, 219, 255, 0.1) 0%, transparent 50%);
}

.floating-particles {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-image: 
    radial-gradient(2px 2px at 20px 30px, rgba(255,255,255,0.2), transparent),
    radial-gradient(2px 2px at 40px 70px, rgba(255,255,255,0.1), transparent),
    radial-gradient(1px 1px at 90px 40px, rgba(255,255,255,0.1), transparent),
    radial-gradient(1px 1px at 130px 80px, rgba(255,255,255,0.1), transparent);
  background-repeat: repeat;
  background-size: 200px 150px;
  animation: float 20s infinite linear;
}

.energy-waves {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(
    90deg,
    transparent,
    rgba(6, 182, 212, 0.03),
    transparent
  );
  animation: wave 8s infinite linear;
}

@keyframes float {
  0% { transform: translate(0, 0) rotate(0deg); }
  33% { transform: translate(30px, -30px) rotate(120deg); }
  66% { transform: translate(-20px, 20px) rotate(240deg); }
  100% { transform: translate(0, 0) rotate(360deg); }
}

@keyframes wave {
  0% { transform: translateX(-100%); }
  100% { transform: translateX(100%); }
}

/* Responsive design */
@media (max-width: 768px) {
  .holo-panel {
    margin: 1rem;
    padding: 1.5rem;
  }
  
  .destiny-btn {
    padding: 0.75rem 1.5rem;
    font-size: 0.875rem;
  }
}
</style>