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
          <Link href="/login" class="destiny-btn destiny-btn-cosmic destiny-btn-sm">
            Iniciar Sesión
          </Link>
        </div>
      </div>
    </nav>

    <!-- Contenedor Principal -->
    <div class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8 relative z-10">
      <div class="max-w-4xl w-full space-y-8">
        
        <!-- Header -->
        <div class="text-center cosmic-header">
          <div class="crown-icon mb-6">
            <svg class="w-16 h-16 text-purple-400" fill="currentColor" viewBox="0 0 24 24">
              <path d="M5 16L3 4l5.5 5L12 4l3.5 5L21 4l-2 12H5zm2.7-2h8.6l.9-5.4-2.1 1.7L12 8l-3.1 2.3-2.1-1.7L7.7 14z"/>
            </svg>
          </div>
          
          <h1 class="hero-title text-3xl md:text-4xl font-bold text-white mb-2 cosmic-glow-gold tracking-wider">
            ⚔️ FORJA DE GUARDIÁN
          </h1>
          <p class="hero-subtitle-main text-lg cosmic-text-muted">
            Únete a la Torre y forja tu leyenda
          </p>
          <p class="hero-description text-sm text-gray-400 mt-2 max-w-2xl mx-auto">
            Crea tu perfil de Guardián y comienza tu aventura en la Academia Zenthoria. 
            Donde el conocimiento se convierte en poder cósmico.
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
                    ? 'bg-green-500 border-green-500 text-white cosmic-glow-green'
                    : currentStep === index + 1
                    ? 'bg-cyan-500 border-cyan-500 text-white cosmic-glow-cyan'
                    : 'bg-black/30 border-gray-600 text-gray-400'
                ]"
              >
                <!-- Iconos para cada paso -->
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
              </div>

              <!-- Step Label -->
              <div class="ml-3 text-sm font-medium text-white hidden md:block">
                {{ step.title }}
              </div>

              <!-- Connector Line -->
              <div
                v-if="index < steps.length - 1"
                :class="[
                  'mx-4 h-0.5 w-16 transition-all duration-500',
                  currentStep > index + 1 ? 'bg-green-500 cosmic-glow-green' : 'bg-gray-600'
                ]"
              ></div>
            </div>
          </div>
          
          <!-- Progress indicator móvil -->
          <div class="md:hidden text-center mt-4">
            <p class="text-sm text-gray-400">
              Paso {{ currentStep }} de {{ steps.length }}: {{ steps[currentStep - 1].title }}
            </p>
          </div>
        </div>

        <!-- Main Form Card -->
        <div class="cosmic-card rounded-2xl p-8 space-y-6">
          
          <!-- Loading State -->
          <div v-if="isSubmitting" class="text-center py-8">
            <div class="cosmic-spinner mx-auto mb-4"></div>
            <p class="text-cyan-400 font-medium">Forjando tu Guardián...</p>
            <p class="text-gray-400 text-sm mt-2">Este proceso puede tomar unos momentos</p>
          </div>

          <!-- Form Steps Container -->
          <div v-else>
            <form @submit.prevent="handleSubmit" class="space-y-6">
              
              <!-- STEP 1: Datos Personales -->
              <div v-show="currentStep === 1" class="space-y-6">
                <div class="text-center mb-6">
                  <h2 class="text-2xl font-bold text-white cosmic-glow-cyan">
                    📋 Información Personal
                  </h2>
                  <p class="text-purple-400 text-sm mt-2">
                    Proporciona tus datos para crear tu perfil de Guardián
                  </p>
                </div>

                <!-- Errores globales -->
                <div v-if="errorMessage" class="cosmic-alert-error">
                  <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm">{{ errorMessage }}</span>
                  </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <!-- Nombre -->
                  <div class="space-y-2">
                    <label for="nombre" class="cosmic-label">
                      <span class="flex items-center space-x-2">
                        <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span>Nombre *</span>
                      </span>
                    </label>
                    <input
                      id="nombre"
                      v-model="form.nombre"
                      type="text"
                      autocomplete="given-name"
                      required
                      maxlength="100"
                      class="cosmic-input"
                      placeholder="Tu nombre de Guardián"
                    />
                  </div>

                  <!-- Apellido -->
                  <div class="space-y-2">
                    <label for="apellido" class="cosmic-label">
                      <span class="flex items-center space-x-2">
                        <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span>Apellido *</span>
                      </span>
                    </label>
                    <input
                      id="apellido"
                      v-model="form.apellido"
                      type="text"
                      autocomplete="family-name"
                      required
                      maxlength="100"
                      class="cosmic-input"
                      placeholder="Tu apellido"
                    />
                  </div>
                </div>

                <!-- Email -->
                <div class="space-y-2">
                  <label for="correo" class="cosmic-label">
                    <span class="flex items-center space-x-2">
                      <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                      </svg>
                      <span>Correo Electrónico *</span>
                    </span>
                  </label>
                  <input
                    id="correo"
                    v-model="form.correo"
                    type="email"
                    autocomplete="email"
                    required
                    class="cosmic-input"
                    placeholder="guardian@zenthoria.academy"
                  />
                </div>

                <!-- Passwords -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div class="space-y-2">
                    <label for="password" class="cosmic-label">
                      <span class="flex items-center space-x-2">
                        <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <span>Contraseña *</span>
                      </span>
                    </label>
                    <input
                      id="password"
                      v-model="form.password"
                      type="password"
                      autocomplete="new-password"
                      required
                      minlength="8"
                      class="cosmic-input"
                      placeholder="Mínimo 8 caracteres"
                    />
                  </div>

                  <div class="space-y-2">
                    <label for="password_confirmation" class="cosmic-label">
                      <span class="flex items-center space-x-2">
                        <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Confirmar Contraseña *</span>
                      </span>
                    </label>
                    <input
                      id="password_confirmation"
                      v-model="form.password_confirmation"
                      type="password"
                      autocomplete="new-password"
                      required
                      class="cosmic-input"
                      placeholder="Repite tu contraseña"
                    />
                  </div>
                </div>

                <!-- Tipo de Usuario -->
                <div class="space-y-3">
                  <label class="cosmic-label">
                    <span class="flex items-center space-x-2">
                      <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/>
                      </svg>
                      <span>Tipo de Usuario *</span>
                    </span>
                  </label>
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div
                      @click="form.id_tipo_usuario = 2"
                      class="cosmic-card-selector"
                      :class="form.id_tipo_usuario === 2 ? 'border-cyan-400 bg-cyan-500/10 cosmic-glow-cyan' : 'border-gray-600 bg-black/20 hover:border-gray-500'"
                    >
                      <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-cyan-500/20 flex items-center justify-center">
                          <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C20.832 18.477 19.246 18 17.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                          </svg>
                        </div>
                        <div>
                          <h3 class="text-white font-semibold">🎓 Estudiante</h3>
                          <p class="text-gray-400 text-sm">Unirse a clases y crear personajes</p>
                        </div>
                      </div>
                      <div v-if="form.id_tipo_usuario === 2" class="absolute top-2 right-2">
                        <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                      </div>
                    </div>

                    <div
                      @click="form.id_tipo_usuario = 1"
                      class="cosmic-card-selector"
                      :class="form.id_tipo_usuario === 1 ? 'border-cyan-400 bg-cyan-500/10 cosmic-glow-cyan' : 'border-gray-600 bg-black/20 hover:border-gray-500'"
                    >
                      <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-purple-500/20 flex items-center justify-center">
                          <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-6m-8 0H3m2 0h6M9 7h6m-6 4h6m-6 4h6"/>
                          </svg>
                        </div>
                        <div>
                          <h3 class="text-white font-semibold">👨‍🏫 Profesor</h3>
                          <p class="text-gray-400 text-sm">Crear y gestionar clases</p>
                        </div>
                      </div>
                      <div v-if="form.id_tipo_usuario === 1" class="absolute top-2 right-2">
                        <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- STEP 2: Selección de Avatar/Clase -->
              <div v-show="currentStep === 2" class="space-y-6">
                <div class="text-center mb-6">
                  <h2 class="text-2xl font-bold text-white cosmic-glow-cyan">
                    ⚔️ Elige tu Clase de Guardián
                  </h2>
                  <p class="text-purple-400 text-sm mt-2">
                    Selecciona tu clase y personaliza tu avatar para la batalla
                  </p>
                </div>

                <!-- Selección de Clase Principal -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                  
                  <!-- TITÁN -->
                  <div
                    @click="selectClass('titan')"
                    class="cosmic-class-card cursor-pointer"
                    :class="selectedClass === 'titan' ? 'border-orange-500 bg-orange-500/10 cosmic-glow-orange transform scale-105' : 'border-gray-600 bg-black/20 hover:border-orange-400 hover:bg-orange-500/5'"
                  >
                    <div class="w-full h-32 bg-gradient-to-br from-orange-600 to-red-600 rounded-lg mb-4 flex items-center justify-center">
                      <div class="text-4xl">🛡️</div>
                    </div>
                    
                    <div class="text-center">
                      <h3 class="text-lg font-bold text-white mb-2">🔥 TITÁN</h3>
                      <p class="text-gray-300 text-xs leading-relaxed mb-4">
                        Guardián de la vanguardia, especializado en combate y defensa con poderes solares.
                      </p>
                    </div>
                    
                    <div v-if="selectedClass === 'titan'" class="absolute top-2 right-2">
                      <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                      </svg>
                    </div>
                  </div>

                  <!-- CAZADOR -->
                  <div
                    @click="selectClass('hunter')"
                    class="cosmic-class-card cursor-pointer"
                    :class="selectedClass === 'hunter' ? 'border-blue-500 bg-blue-500/10 cosmic-glow-blue transform scale-105' : 'border-gray-600 bg-black/20 hover:border-blue-400 hover:bg-blue-500/5'"
                  >
                    <div class="w-full h-32 bg-gradient-to-br from-blue-600 to-cyan-600 rounded-lg mb-4 flex items-center justify-center">
                      <div class="text-4xl">🏹</div>
                    </div>
                    
                    <div class="text-center">
                      <h3 class="text-lg font-bold text-white mb-2">⚡ CAZADOR</h3>
                      <p class="text-gray-300 text-xs leading-relaxed mb-4">
                        Explorador ágil y sigiloso, maestro del combate a distancia con poderes de arco.
                      </p>
                    </div>
                    
                    <div v-if="selectedClass === 'hunter'" class="absolute top-2 right-2">
                      <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                      </svg>
                    </div>
                  </div>

                  <!-- HECHICERO -->
                  <div
                    @click="selectClass('warlock')"
                    class="cosmic-class-card cursor-pointer"
                    :class="selectedClass === 'warlock' ? 'border-purple-500 bg-purple-500/10 cosmic-glow-purple transform scale-105' : 'border-gray-600 bg-black/20 hover:border-purple-400 hover:bg-purple-500/5'"
                  >
                    <div class="w-full h-32 bg-gradient-to-br from-purple-600 to-pink-600 rounded-lg mb-4 flex items-center justify-center">
                      <div class="text-4xl">🔮</div>
                    </div>
                    
                    <div class="text-center">
                      <h3 class="text-lg font-bold text-white mb-2">🌟 HECHICERO</h3>
                      <p class="text-gray-300 text-xs leading-relaxed mb-4">
                        Maestro de las artes arcanas y los poderes del vacío, estratega invaluable.
                      </p>
                    </div>
                    
                    <div v-if="selectedClass === 'warlock'" class="absolute top-2 right-2">
                      <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                      </svg>
                    </div>
                  </div>
                </div>

                <!-- Personalización del Avatar -->
                <div v-if="selectedClass" class="space-y-4">
                  <h3 class="text-lg font-semibold text-white text-center">
                    🎨 Personalización del Avatar
                  </h3>
                  
                  <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Vista previa del avatar -->
                    <div class="text-center">
                      <div class="mx-auto w-32 h-32 rounded-full overflow-hidden border-4 border-cyan-500 cosmic-glow-cyan mb-4">
                        <div
                          class="w-full h-full bg-gradient-to-br flex items-center justify-center text-4xl"
                          :class="getClassGradient(selectedClass)"
                        >
                          {{ getClassEmoji(selectedClass) }}
                        </div>
                      </div>
                      
                      <p class="text-sm text-gray-400 mb-4">
                        Vista previa de tu {{ getClassName(selectedClass) }}
                      </p>
                    </div>
                    
                    <!-- Opciones de personalización -->
                    <div class="space-y-4">
                      <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">
                          Nombre del Personaje (Opcional)
                        </label>
                        <input
                          v-model="form.nombre_personaje"
                          type="text"
                          maxlength="50"
                          class="cosmic-input"
                          :placeholder="`Mi ${getClassName(selectedClass)}`"
                        />
                      </div>
                      
                      <div class="p-4 rounded-lg bg-black/20 border border-gray-600">
                        <h4 class="font-medium text-white mb-2">Habilidades Especiales:</h4>
                        <div class="text-sm text-gray-300 space-y-1">
                          <div v-for="ability in getClassAbilities(selectedClass)" :key="ability" class="flex items-center space-x-2">
                            <svg class="w-3 h-3 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>{{ ability }}</span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- STEP 3: Confirmación -->
              <div v-show="currentStep === 3" class="space-y-6">
                <div class="text-center mb-6">
                  <h2 class="text-2xl font-bold text-white cosmic-glow-cyan">
                    ✅ Confirmación Final
                  </h2>
                  <p class="text-purple-400 text-sm mt-2">
                    Revisa tu información antes de forjar tu Guardián
                  </p>
                </div>

                <!-- Resumen del Guardián -->
                <div class="cosmic-card bg-black/20 rounded-xl p-6 border border-cyan-500/30">
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- Información Personal -->
                    <div class="space-y-4">
                      <h3 class="text-white font-semibold border-b border-cyan-500/30 pb-2 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Información Personal
                      </h3>
                      <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                          <span class="text-gray-400">Nombre:</span>
                          <span class="text-white font-medium">{{ form.nombre }} {{ form.apellido }}</span>
                        </div>
                        <div class="flex justify-between">
                          <span class="text-gray-400">Email:</span>
                          <span class="text-white font-medium">{{ form.correo }}</span>
                        </div>
                        <div class="flex justify-between">
                          <span class="text-gray-400">Tipo:</span>
                          <span class="text-white font-medium flex items-center">
                            {{ form.id_tipo_usuario === 1 ? '👨‍🏫 Profesor' : '🎓 Estudiante' }}
                          </span>
                        </div>
                      </div>
                    </div>

                    <!-- Información de la Clase -->
                    <div class="space-y-4">
                      <h3 class="text-white font-semibold border-b border-cyan-500/30 pb-2 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Clase de Guardián
                      </h3>
                      <div class="flex items-center space-x-4">
                        <div 
                          class="w-16 h-16 rounded-full border-2 border-cyan-500 flex items-center justify-center text-2xl"
                          :class="getClassGradient(selectedClass)"
                        >
                          {{ getClassEmoji(selectedClass) }}
                        </div>
                        <div>
                          <p class="text-white font-medium text-lg">{{ getClassName(selectedClass) }}</p>
                          <p class="text-gray-400 text-sm">{{ getClassDescription(selectedClass) }}</p>
                          <p v-if="form.nombre_personaje" class="text-cyan-400 text-sm mt-1">
                            "{{ form.nombre_personaje }}"
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Términos y Condiciones -->
                <div class="space-y-4">
                  <div class="flex items-start space-x-3">
                   <input
                      id="terms"
                      v-model="form.terminos"
                      type="checkbox"
                      class="cosmic-checkbox mt-1"
                      required
                    />
                    <label for="terms" class="text-sm text-gray-300 leading-relaxed">
                      Acepto los términos y condiciones y la política de privacidad del sistema Zenthoria Guardian Academy
                    </label>
                  </div>
                  
                  <div class="flex items-start space-x-3">
                    <input
                      id="newsletter"
                      v-model="form.newsletter"
                      type="checkbox"
                      class="cosmic-checkbox mt-1"
                    />
                    <label for="newsletter" class="text-sm text-gray-300">
                      Quiero recibir noticias y actualizaciones sobre eventos especiales de la Academia
                    </label>
                  </div>
                </div>

                <!-- Mensaje Final Épico -->
                <div class="text-center space-y-4">
                  <div class="p-6 rounded-lg bg-gradient-to-r from-cyan-500/10 to-purple-500/10 border border-cyan-500/30">
                    <div class="flex justify-center mb-4">
                      <svg class="w-16 h-16 text-cyan-400 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                      </svg>
                    </div>
                    <h4 class="text-white font-bold text-xl mb-2">¡Tu leyenda está a punto de comenzar!</h4>
                    <p class="text-gray-300 mb-4">
                      Te unes a miles de Guardianes en la Torre de Zenthoria. 
                      Prepárate para transformar el conocimiento en poder cósmico.
                    </p>
                    <div class="flex justify-center space-x-6 text-sm">
                      <div class="text-center">
                        <div class="text-cyan-400 font-bold text-lg">{{ formatNumber(15750) }}</div>
                        <div class="text-gray-400">Guardianes Activos</div>
                      </div>
                      <div class="text-center">
                        <div class="text-purple-400 font-bold text-lg">{{ formatNumber(89400) }}</div>
                        <div class="text-gray-400">Misiones Completadas</div>
                      </div>
                      <div class="text-center">
                        <div class="text-gold-400 font-bold text-lg">{{ formatNumber(3650000) }}</div>
                        <div class="text-gray-400">Luz Recolectada</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Navigation Buttons -->
              <div class="flex justify-between items-center pt-8 border-t border-gray-700">
                <!-- Back Button -->
                <button
                  v-if="currentStep > 1"
                  @click="previousStep"
                  type="button"
                  class="cosmic-btn-secondary"
                  :disabled="isSubmitting"
                >
                  <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                  </svg>
                  ← Anterior
                </button>
                <div v-else></div>

                <!-- Next/Submit Button -->
                <button
                  v-if="currentStep < 3"
                  @click="nextStep"
                  type="button"
                  class="cosmic-btn cosmic-btn-primary"
                  :disabled="!canProceedToNextStep || isSubmitting"
                >
                  Siguiente →
                  <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                  </svg>
                </button>
                <button
                  v-else
                  type="submit"
                  class="cosmic-btn cosmic-btn-success"
                  :disabled="!form.terminos || isSubmitting || !selectedClass"
                >
                  <svg v-if="isSubmitting" class="animate-spin w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                  </svg>
                  <svg v-else class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                  </svg>
                  {{ isSubmitting ? 'Forjando Guardián...' : '⚔️ FORJAR GUARDIÁN' }}
                </button>
              </div>
            </form>
          </div>
        </div>

        <!-- Login Link -->
        <div class="text-center mt-8 space-y-4">
          <div class="cosmic-divider">
            <span class="text-xs text-gray-500">¿Ya tienes una cuenta?</span>
          </div>
          <Link
            href="/login"
            class="cosmic-btn cosmic-btn-ghost"
          >
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
            </svg>
            Iniciar Sesión en la Torre
          </Link>
        </div>

      </div>
    </div>

    <!-- Success Animation Modal -->
    <div
      v-if="showSuccessAnimation"
      class="fixed inset-0 z-50 flex items-center justify-center bg-black/90 backdrop-blur-sm"
    >
      <div class="cosmic-card rounded-2xl p-8 max-w-md w-full mx-4 text-center">
        <div class="mb-6">
          <div class="w-24 h-24 mx-auto rounded-full bg-green-500/20 flex items-center justify-center cosmic-glow-green animate-pulse mb-4">
            <svg class="w-12 h-12 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
          </div>
        </div>
        
        <h3 class="text-3xl font-bold text-white mb-4 cosmic-glow-green">
          ⚔️ ¡Guardián Forjado!
        </h3>
        <p class="text-gray-300 mb-6 leading-relaxed">
          Tu cuenta ha sido creada exitosamente. Bienvenido a la Torre de Zenthoria, 
          <span class="text-cyan-400 font-semibold">{{ form.nombre }}</span>.
        </p>
        
        <div class="space-y-4">
          <div class="flex items-center justify-center space-x-3 p-4 bg-black/20 rounded-lg border border-cyan-500/30">
            <div class="text-2xl">{{ getClassEmoji(selectedClass) }}</div>
            <div class="text-left">
              <p class="text-white font-medium">{{ getClassName(selectedClass) }}</p>
              <p class="text-gray-400 text-sm">Tu clase ha sido asignada</p>
            </div>
          </div>
          
          <button
            @click="redirectToDashboard"
            class="cosmic-btn cosmic-btn-success w-full"
          >
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
            🚀 Acceder a la Torre
          </button>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, computed, reactive, onMounted } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
// Props del componente
const props = defineProps({
  mustVerifyEmail: Boolean,
  status: String
})

// Estado reactivo
const currentStep = ref(1)
const isSubmitting = ref(false)
const selectedClass = ref('')
const termsAccepted = ref(false)
const showSuccessAnimation = ref(false)
const errorMessage = ref('')

// Formulario usando Inertia.js
const form = useForm({
  nombre: '',
  apellido: '',
  correo: '',
  password: '',
  password_confirmation: '',
  id_tipo_usuario: 2, // Default a estudiante
  nombre_personaje: '',
  newsletter: false,
   terminos: false
})

// Configuración de pasos del wizard
const steps = [
  { title: 'Datos Personales', icon: 'user' },
  { title: 'Clase de Guardián', icon: 'image' },
  { title: 'Confirmación', icon: 'check' }
]

// Definición de clases de Guardián
const guardianClasses = {
  titan: {
    name: 'Titán',
    emoji: '🛡️',
    description: 'Defensor implacable de la humanidad',
    abilities: ['Barrera Protectora', 'Puño Ardiente', 'Resistencia Férrea', 'Furia Solar'],
    gradient: 'from-orange-600 to-red-600'
  },
  hunter: {
    name: 'Cazador',
    emoji: '🏹',
    description: 'Explorador sigiloso y francotirador letal',
    abilities: ['Paso Sombra', 'Tiro Certero', 'Cuchilla Arcana', 'Velocidad Suprema'],
    gradient: 'from-blue-600 to-cyan-600'
  },
  warlock: {
    name: 'Hechicero',
    emoji: '🔮',
    description: 'Maestro de las artes arcanas',
    abilities: ['Nova Bomb', 'Curación Divina', 'Teletransporte', 'Sabiduría Ancestral'],
    gradient: 'from-purple-600 to-pink-600'
  }
}

// Computed properties
const canProceedToNextStep = computed(() => {
  switch (currentStep.value) {
    case 1:
      return form.nombre && 
             form.apellido && 
             form.correo && 
             form.password && 
             form.password_confirmation &&
             form.id_tipo_usuario &&
             form.password === form.password_confirmation
    case 2:
      return selectedClass.value !== ''
    case 3:
      return form.terminos
    default:
      return false
  }
})

// Métodos para clases de Guardián
const selectClass = (classId) => {
  selectedClass.value = classId
}

const getClassName = (classId) => {
  return guardianClasses[classId]?.name || 'Guardián'
}

const getClassEmoji = (classId) => {
  return guardianClasses[classId]?.emoji || '⚔️'
}

const getClassDescription = (classId) => {
  return guardianClasses[classId]?.description || 'Guerrero de la luz'
}

const getClassAbilities = (classId) => {
  return guardianClasses[classId]?.abilities || []
}
const submitRegistration = () => {
  // Validar que todos los campos estén llenos
  if (!form.nombre || !form.apellido || !form.correo || !form.password || !form.password_confirmation) {
    form.setError('general', 'Por favor complete todos los campos obligatorios')
    return
  }

  // Validar que las contraseñas coincidan
  if (form.password !== form.password_confirmation) {
    form.setError('password_confirmation', 'Las contraseñas no coinciden')
    return
  }

  // Validar que se haya seleccionado un tipo de usuario
  if (!form.id_tipo_usuario) {
    form.setError('id_tipo_usuario', 'Debe seleccionar un tipo de usuario')
    return
  }

  // Validar que se hayan aceptado los términos
  if (!form.terminos) {
    form.setError('terminos', 'Debe aceptar los términos y condiciones')
    return
  }

  // Enviar formulario
  form.post(route('register'), {
    preserveScroll: true,
    onSuccess: (page) => {
      console.log('Registro exitoso:', page)
      // El usuario será redirigido automáticamente al dashboard
    },
    onError: (errors) => {
      console.error('Errores en el registro:', errors)
      // Los errores se mostrarán automáticamente en el formulario
    },
    onFinish: () => {
      // Se ejecuta siempre al final
      console.log('Petición de registro terminada')
    }
  })
}
const getClassGradient = (classId) => {
  return `bg-gradient-to-br ${guardianClasses[classId]?.gradient || 'from-gray-600 to-gray-800'}`
}

// Métodos de navegación
const nextStep = () => {
  if (canProceedToNextStep.value && currentStep.value < 3) {
    currentStep.value++
  }
}

const previousStep = () => {
  if (currentStep.value > 1) {
    currentStep.value--
  }
}

// Método principal de envío del formulario
const handleSubmit = () => {
   if (!canProceedToNextStep.value) return
  
  form.post('/register', {
    onStart: () => {
      console.log('Iniciando registro...')
    },
    onSuccess: (page) => {
      console.log('Registro exitoso:', page)
      // Redireccionar al dashboard según el tipo de usuario
      const dashboardRoute = form.tipo_usuario === 1 
        ? '/profesor/dashboard' 
        : '/estudiante/dashboard'
      
      window.location.href = dashboardRoute
    },
    onError: (errors) => {
      console.error('Errores en el registro:', errors)
    },
    onFinish: () => {
      console.log('Petición de registro terminada')
    }
  })
}
// Métodos de utilidad
const redirectToDashboard = () => {
  const dashboardRoute = form.id_tipo_usuario === 1 
    ? '/profesor/dashboard' 
    : '/estudiante/dashboard'
  
  window.location.href = dashboardRoute
}

const formatNumber = (num) => {
  if (num >= 1000000) {
    return `${(num / 1000000).toFixed(1)}M`
  }
  if (num >= 1000) {
    return `${(num / 1000).toFixed(1)}K`
  }
  return num.toString()
}

// Lifecycle hooks
onMounted(() => {
  // Verificar si hay status de la URL
  if (props.status) {
    console.log('Status:', props.status)
  }
})
</script>

<style scoped>
/* Importar estilos del Index.vue */
@import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700&display=swap');

/* Variables CSS cósmicas de Zenthoria */
:root {
  --cosmic-purple: #7C3AED;
  --cosmic-purple-light: #A855F7;
  --cosmic-purple-dark: #5B21B6;
  --cosmic-cyan: #0EA5E9;
  --cosmic-cyan-light: #38BDF8;
  --cosmic-cyan-dark: #0284C7;
  --cosmic-gold: #F59E0B;
  --cosmic-gold-light: #FBBF24;
  --cosmic-blue: #3B82F6;
  --cosmic-green: #10B981;
  --cosmic-red: #EF4444;
  --cosmic-orange: #F97316;
  
  /* Fondos oscuros */
  --cosmic-dark: #0B0B1A;
  --cosmic-darker: #050511;
  --cosmic-panel: #1A1B2E;
  --cosmic-panel-light: #262B47;
  
  /* Textos */
  --cosmic-text: #F8FAFC;
  --cosmic-text-secondary: #E2E8F0;
  --cosmic-text-muted: #94A3B8;
  --cosmic-text-disabled: #64748B;
  
  /* Efectos */
  --cosmic-glow: #A855F7;
  --cosmic-shadow: rgba(0, 0, 0, 0.8);
  --cosmic-border: rgba(139, 92, 246, 0.2);
  --cosmic-border-bright: rgba(139, 92, 246, 0.5);
}

/* Contenedor principal */
.welcome-container {
  background: 
    radial-gradient(ellipse at top, rgba(124, 58, 237, 0.15) 0%, transparent 50%),
    radial-gradient(ellipse at bottom, rgba(14, 165, 233, 0.1) 0%, transparent 50%),
    linear-gradient(180deg, #0B0B1A 0%, #050511 100%);
  min-height: 100vh;
  overflow-x: hidden;
  font-family: 'Inter', 'Exo 2', system-ui, sans-serif;
  position: relative;
  color: #F8FAFC;
}

/* Fondo cósmico */
.destiny-cosmic-pattern {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-image: 
    radial-gradient(circle at 15% 25%, rgba(124, 58, 237, 0.15) 0%, transparent 25%),
    radial-gradient(circle at 85% 75%, rgba(14, 165, 233, 0.12) 0%, transparent 25%),
    radial-gradient(circle at 50% 50%, rgba(245, 158, 11, 0.06) 0%, transparent 30%);
  background-size: 800px 800px, 600px 600px, 400px 400px;
  animation: cosmic-drift 25s ease-in-out infinite;
  z-index: -2;
}

.destiny-particles {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%237C3AED' fill-opacity='0.04'%3E%3Ccircle cx='30' cy='30' r='1.5'/%3E%3C/g%3E%3Cg fill='%230EA5E9' fill-opacity='0.03'%3E%3Ccircle cx='15' cy='15' r='1'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
  animation: particles-drift 30s linear infinite;
  z-index: -1;
}

@keyframes cosmic-drift {
  0%, 100% { transform: translateY(0px) rotate(0deg); filter: hue-rotate(0deg); }
  33% { transform: translateY(-15px) rotate(1deg); filter: hue-rotate(10deg); }
  66% { transform: translateY(-25px) rotate(-1deg); filter: hue-rotate(-5deg); }
}

@keyframes particles-drift {
  0% { transform: translateY(0px) translateX(0px); }
  100% { transform: translateY(-100vh) translateX(50px); }
}

/* Navegación */
.welcome-nav {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  background: rgba(11, 11, 26, 0.95);
  backdrop-filter: blur(20px) saturate(180%);
  border-bottom: 1px solid var(--cosmic-border-bright);
  box-shadow: 0 4px 32px var(--cosmic-shadow);
  z-index: 1000;
  transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
}

.nav-container {
  max-width: 1400px;
  margin: 0 auto;
  padding: 1.25rem 2rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.nav-logo {
  display: flex;
  align-items: center;
  gap: 1rem;
  cursor: pointer;
  transition: all 0.3s ease;
}

.logo-text .destiny-title {
  color: var(--cosmic-text);
  font-family: 'Orbitron', monospace;
  font-weight: 800;
  font-size: 1.25rem;
  margin: 0;
  line-height: 1;
  text-shadow: 0 0 20px rgba(124, 58, 237, 0.8);
  letter-spacing: 0.05em;
}

.logo-text .destiny-subtitle {
  color: var(--cosmic-cyan);
  font-family: 'Orbitron', monospace;
  font-weight: 500;
  font-size: 0.75rem;
  line-height: 1;
  text-transform: uppercase;
  letter-spacing: 0.1em;
  text-shadow: 0 0 10px rgba(14, 165, 233, 0.6);
}

.nav-auth {
  display: flex;
  gap: 1rem;
  align-items: center;
}

.destiny-btn {
  padding: 0.875rem 1.75rem;
  border-radius: 12px;
  font-weight: 700;
  font-size: 0.875rem;
  cursor: pointer;
  transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  font-family: 'Inter', system-ui, sans-serif;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  border: 2px solid transparent;
  position: relative;
  overflow: hidden;
}

.destiny-btn-sm {
  padding: 0.625rem 1.25rem;
  font-size: 0.8rem;
}

.destiny-btn-cosmic {
  background: linear-gradient(135deg, var(--cosmic-purple), var(--cosmic-purple-light));
  color: var(--cosmic-text);
  border-color: var(--cosmic-purple-light);
  box-shadow: 0 4px 15px rgba(124, 58, 237, 0.3), inset 0 1px 0 rgba(255, 255, 255, 0.1);
}

.destiny-btn-cosmic:hover {
  transform: translateY(-3px) scale(1.02);
  box-shadow: 0 8px 25px rgba(124, 58, 237, 0.5), 0 0 20px rgba(124, 58, 237, 0.4), inset 0 1px 0 rgba(255, 255, 255, 0.2);
  text-decoration: none;
  color: var(--cosmic-text);
  background: linear-gradient(135deg, var(--cosmic-purple-light), var(--cosmic-purple));
}

/* Header cósmico */
.cosmic-header {
  position: relative;
  z-index: 2;
  padding-top: 6rem;
}

.crown-icon {
  animation: cosmic-float 4s ease-in-out infinite;
  filter: drop-shadow(0 0 30px rgba(124, 58, 237, 0.8));
}

@keyframes cosmic-float {
  0%, 100% { transform: translateY(0px) rotate(0deg); filter: drop-shadow(0 0 30px rgba(124, 58, 237, 0.8)); }
  50% { transform: translateY(-15px) rotate(2deg); filter: drop-shadow(0 0 40px rgba(14, 165, 233, 0.9)); }
}

.hero-title {
  font-size: clamp(2.5rem, 6vw, 4rem);
  font-weight: 900;
  color: #FFFFFF;
  margin-bottom: 1.5rem;
  text-shadow: 0 0 30px rgba(124, 58, 237, 0.8), 0 0 60px rgba(14, 165, 233, 0.4), 0 4px 8px rgba(0, 0, 0, 0.9);
  background: linear-gradient(135deg, #FFFFFF 0%, #A855F7 25%, #38BDF8 50%, #A855F7 75%, #FFFFFF 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  animation: cosmic-glow 5s ease-in-out infinite alternate;
  font-family: 'Orbitron', monospace;
  line-height: 1.1;
  text-align: center;
  letter-spacing: -0.02em;
}

.hero-subtitle-main {
  font-size: clamp(1rem, 2vw, 1.5rem);
  color: #38BDF8;
  margin-bottom: 1rem;
  font-weight: 600;
  text-align: center;
  text-shadow: 0 0 25px rgba(14, 165, 233, 0.8), 0 2px 4px rgba(0, 0, 0, 0.8);
  font-family: 'Inter', system-ui, sans-serif;
  letter-spacing: 0.02em;
}

.hero-description {
  font-size: clamp(0.9rem, 1.5vw, 1.1rem);
  color: #E2E8F0;
  margin-bottom: 2rem;
  line-height: 1.8;
  text-align: center;
  text-shadow: 0 2px 4px rgba(0, 0, 0, 0.8);
  font-weight: 400;
}

.cosmic-text-muted {
  color: var(--cosmic-text-muted);
}

@keyframes cosmic-glow {
  0% { filter: drop-shadow(0 0 30px rgba(124, 58, 237, 0.6)) brightness(1); }
  100% { filter: drop-shadow(0 0 50px rgba(14, 165, 233, 0.8)) brightness(1.1); }
}

/* Barra de progreso */
.cosmic-glow-green {
  box-shadow: 0 0 20px rgba(16, 185, 129, 0.5);
}

.cosmic-glow-cyan {
  box-shadow: 0 0 20px rgba(14, 165, 233, 0.5);
}

.cosmic-glow-orange {
  box-shadow: 0 0 20px rgba(249, 115, 22, 0.5);
}

.cosmic-glow-blue {
  box-shadow: 0 0 20px rgba(59, 130, 246, 0.5);
}

.cosmic-glow-purple {
  box-shadow: 0 0 20px rgba(168, 85, 247, 0.5);
}

/* Cards principales */
.cosmic-card {
  background: rgba(26, 27, 46, 0.9);
  backdrop-filter: blur(20px) saturate(180%);
  border: 1px solid var(--cosmic-border-bright);
  border-radius: 20px;
  box-shadow: 0 20px 60px var(--cosmic-shadow), inset 0 1px 0 rgba(255, 255, 255, 0.1);
  position: relative;
  overflow: hidden;
  transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
}

.cosmic-card:hover {
  border-color: var(--cosmic-cyan-light);
  box-shadow: 0 25px 80px var(--cosmic-shadow), 0 0 40px rgba(14, 165, 233, 0.3), inset 0 1px 0 rgba(255, 255, 255, 0.15);
  transform: translateY(-2px);
}

/* Cards de selección de clase */
.cosmic-class-card {
  background: rgba(26, 27, 46, 0.8);
  backdrop-filter: blur(15px) saturate(180%);
  padding: 1.5rem;
  border-radius: 16px;
  border: 2px solid;
  transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
  position: relative;
}

.cosmic-class-card:hover {
  background: rgba(26, 27, 46, 0.95);
  transform: translateY(-4px);
}

.cosmic-card-selector {
  position: relative;
  padding: 1rem;
  border-radius: 12px;
  border: 2px solid;
  cursor: pointer;
  transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
}

.cosmic-card-selector:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
}

/* Inputs cósmicos */
.cosmic-label {
  display: block;
  font-size: 0.875rem;
  font-weight: 600;
  color: var(--cosmic-purple-light);
  margin-bottom: 0.75rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  font-family: 'Inter', system-ui, sans-serif;
}

.cosmic-input {
  width: 100%;
  padding: 1rem 1.25rem;
  background: rgba(11, 11, 26, 0.8);
  border: 2px solid var(--cosmic-border);
  border-radius: 12px;
  color: var(--cosmic-text);
  font-size: 1rem;
  transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
  font-family: 'Inter', system-ui, sans-serif;
}

.cosmic-input:focus {
  outline: none;
  border-color: var(--cosmic-cyan-light);
  box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.2), 0 0 20px rgba(14, 165, 233, 0.1);
  background: rgba(11, 11, 26, 0.95);
  transform: translateY(-1px);
}

.cosmic-input::placeholder {
  color: var(--cosmic-text-disabled);
  font-style: italic;
}

.cosmic-input:hover:not(:focus) {
  border-color: var(--cosmic-border-bright);
  background: rgba(11, 11, 26, 0.9);
}

/* Checkbox */
.cosmic-checkbox {
  width: 1.25rem;
  height: 1.25rem;
  border: 2px solid var(--cosmic-border);
  border-radius: 6px;
  background: rgba(11, 11, 26, 0.8);
  accent-color: var(--cosmic-purple-light);
  transition: all 0.3s ease;
}

.cosmic-checkbox:checked {
  background: var(--cosmic-purple-light);
  border-color: var(--cosmic-purple-light);
}

/* Botones */
.cosmic-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 1rem 2rem;
  font-weight: 700;
  border-radius: 12px;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
  border: 2px solid transparent;
  cursor: pointer;
  position: relative;
  overflow: hidden;
  font-family: 'Inter', system-ui, sans-serif;
  text-decoration: none;
}

.cosmic-btn-primary {
  background: linear-gradient(135deg, var(--cosmic-purple), var(--cosmic-purple-light));
  color: var(--cosmic-text);
  border-color: var(--cosmic-purple-light);
  box-shadow: 0 8px 25px rgba(124, 58, 237, 0.4), inset 0 1px 0 rgba(255, 255, 255, 0.1);
}

.cosmic-btn-primary:hover:not(:disabled) {
  background: linear-gradient(135deg, var(--cosmic-purple-light), var(--cosmic-cyan));
  box-shadow: 0 12px 35px rgba(124, 58, 237, 0.6), 0 0 30px rgba(124, 58, 237, 0.5);
  transform: translateY(-2px) scale(1.02);
  text-decoration: none;
  color: var(--cosmic-text);
}

.cosmic-btn-primary:disabled {
  opacity: 0.6;
  cursor: not-allowed;
  transform: none;
  box-shadow: none;
}

.cosmic-btn-secondary {
  background: rgba(26, 27, 46, 0.8);
  color: var(--cosmic-text-muted);
  border-color: var(--cosmic-border);
  backdrop-filter: blur(10px);
  padding: 0.75rem 1.5rem;
  font-size: 0.875rem;
}

.cosmic-btn-secondary:hover {
  background: rgba(26, 27, 46, 0.95);
  color: var(--cosmic-text);
  border-color: var(--cosmic-border-bright);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
}

.cosmic-btn-success {
  background: linear-gradient(135deg, var(--cosmic-green), #059669);
  color: var(--cosmic-text);
  border-color: var(--cosmic-green);
  box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4), inset 0 1px 0 rgba(255, 255, 255, 0.1);
}

.cosmic-btn-success:hover:not(:disabled) {
  background: linear-gradient(135deg, #059669, #047857);
  box-shadow: 0 12px 35px rgba(16, 185, 129, 0.6), 0 0 30px rgba(16, 185, 129, 0.5);
  transform: translateY(-2px) scale(1.02);
  text-decoration: none;
  color: var(--cosmic-text);
}

.cosmic-btn-ghost {
  background: transparent;
  color: var(--cosmic-cyan-light);
  border-color: var(--cosmic-cyan-light);
  padding: 0.75rem 1.5rem;
  font-size: 0.875rem;
}

.cosmic-btn-ghost:hover {
  background: rgba(14, 165, 233, 0.1);
  box-shadow: 0 0 20px rgba(14, 165, 233, 0.3);
  text-decoration: none;
  color: var(--cosmic-cyan-light);
}

/* Spinners */
.cosmic-spinner {
  width: 2.5rem;
  height: 2.5rem;
  border: 3px solid rgba(124, 58, 237, 0.3);
  border-top: 3px solid var(--cosmic-purple-light);
  border-radius: 50%;
  animation: cosmic-spin 1s linear infinite;
}

@keyframes cosmic-spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

/* Alertas */
.cosmic-alert-error {
  background: rgba(239, 68, 68, 0.15);
  border: 1px solid rgba(239, 68, 68, 0.3);
  border-radius: 12px;
  padding: 1rem;
  color: #fca5a5;
  backdrop-filter: blur(10px);
}

/* Divisor */
.cosmic-divider {
  position: relative;
  text-align: center;
  margin: 1rem 0;
}

.cosmic-divider::before {
  content: '';
  position: absolute;
  top: 50%;
  left: 0;
  right: 0;
  height: 1px;
  background: linear-gradient(90deg, transparent, var(--cosmic-border), transparent);
}

.cosmic-divider span {
  background: var(--cosmic-panel);
  padding: 0 1rem;
  position: relative;
  z-index: 1;
}

/* Efectos de texto */
.text-gold-400 {
  color: var(--cosmic-gold-light);
}

.font-orbitron {
  font-family: 'Orbitron', monospace;
}

/* Responsivo */
@media (max-width: 768px) {
  .nav-container {
    padding: 1rem;
    flex-direction: column;
    gap: 1rem;
  }
  
  .cosmic-header {
    padding-top: 8rem;
  }
  
  .cosmic-card {
    padding: 1.5rem;
    margin: 1rem;
  }
  
  .cosmic-class-card {
    padding: 1rem;
  }
  
  .cosmic-btn {
    padding: 0.875rem 1.5rem;
    font-size: 0.875rem;
  }
  
  .hero-title {
    font-size: 2.5rem;
  }
}

@media (max-width: 480px) {
  .cosmic-card {
    padding: 1rem;
  }
  
  .hero-title {
    font-size: 2rem;
  }
  
  .cosmic-btn {
    padding: 0.75rem 1.25rem;
    font-size: 0.8rem;
  }
}
</style>