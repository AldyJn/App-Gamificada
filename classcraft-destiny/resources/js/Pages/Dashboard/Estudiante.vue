<template>
  <div class="min-h-screen zenthoria-container">
    <!-- Fondo cósmico animado -->
    <div class="destiny-cosmic-pattern"></div>
    <div class="destiny-particles"></div>

    <!-- Navigation Header -->
    <nav class="guardian-nav">
      <div class="nav-container">
        <div class="nav-logo">
          <div class="logo-emblem">
            <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-cyan-400 rounded-full flex items-center justify-center cosmic-glow">
              <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
              </svg>
            </div>
          </div>
          <div class="logo-text">
            <h1 class="destiny-title text-xl">CENTRO DEL GUARDIÁN</h1>
            <span class="destiny-subtitle text-sm">ZENTHORIA ACADEMY</span>
          </div>
        </div>

        <div class="nav-actions">
          <!-- Currency Display -->
          <div class="cosmic-currency-display">
            <div class="currency-item">
              <div class="w-8 h-8 rounded-full bg-gradient-to-r from-yellow-400 to-yellow-600 flex items-center justify-center cosmic-glow">
                <span class="text-sm font-bold text-yellow-900">G</span>
              </div>
              <span class="currency-value">{{ dashboardData?.personaje?.monedas || 0 }}</span>
            </div>
            <div class="currency-item">
              <div class="w-8 h-8 rounded-full bg-gradient-to-r from-cyan-400 to-cyan-600 flex items-center justify-center cosmic-glow">
                <span class="text-sm font-bold text-cyan-900">L</span>
              </div>
              <span class="currency-value">{{ dashboardData?.personaje?.luz_actual || 0 }}</span>
            </div>
          </div>
          
          <!-- Notifications -->
          <button class="cosmic-notification-btn">
            <svg class="w-6 h-6 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM10 3v4a1 1 0 01-1 1H5"/>
            </svg>
            <div v-if="notificacionesPendientes > 0" class="notification-badge">
              {{ notificacionesPendientes }}
            </div>
          </button>

          <!-- Guardian Power Level -->
          <div class="power-level-indicator">
            <div class="power-level-label">POWER</div>
            <div class="power-level-value">{{ dashboardData?.personaje?.power_level || 750 }}</div>
          </div>
        </div>
      </div>
    </nav>

    <!-- Hero Guardian Section -->
    <section class="guardian-hero-section">
      <div class="hero-background cosmic-bg"></div>
      
      <div class="guardian-hero-content">
        <!-- Guardian Welcome -->
        <div class="guardian-welcome-card holo-panel">
          <div class="welcome-header">
            <div class="crown-icon cosmic-float">
              <svg class="w-12 h-12 text-purple-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M5 16L3 4l5.5 5L12 4l3.5 5L21 4l-2 12H5zm2.7-2h8.6l.9-5.4-2.1 1.7L12 8l-3.1 2.3-2.1-1.7L7.7 14z"/>
              </svg>
            </div>
            <h1 class="guardian-welcome-title">
              👑 Bienvenido, Guardián
            </h1>
            <h2 class="guardian-name">
              {{ dashboardData?.personaje?.nombre_personaje || 'Guardián Anónimo' }}
            </h2>
            <p class="guardian-subtitle">
              Continúa tu leyenda en Zenthoria. Cada decisión te acerca más a la grandeza cósmica.
            </p>
          </div>

          <!-- Guardian Quick Stats -->
          <div class="guardian-quick-stats">
            <div class="quick-stat cosmic-stat-purple">
              <div class="stat-icon">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
              </div>
              <div class="stat-content">
                <div class="stat-value">{{ dashboardData?.personaje?.nivel || 1 }}</div>
                <div class="stat-label">Nivel</div>
              </div>
            </div>
            
            <div class="quick-stat cosmic-stat-cyan">
              <div class="stat-icon">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/>
                </svg>
              </div>
              <div class="stat-content">
                <div class="stat-value">{{ estadisticas?.clases_activas || 0 }}</div>
                <div class="stat-label">Fireteams</div>
              </div>
            </div>
            
            <div class="quick-stat cosmic-stat-gold">
              <div class="stat-icon">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                </svg>
              </div>
              <div class="stat-content">
                <div class="stat-value">{{ estadisticas?.logros_obtenidos || 0 }}</div>
                <div class="stat-label">Logros</div>
              </div>
            </div>
          </div>

          <!-- CTA Buttons -->
          <div class="guardian-cta-buttons">
            <button @click="abrirUnirseClase" class="cosmic-btn cosmic-btn-primary">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
              </svg>
              Unirse a Fireteam
            </button>
            <button @click="abrirPersonalizacion" class="cosmic-btn cosmic-btn-outline">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
              </svg>
              Personalizar
            </button>
          </div>
        </div>
      </div>
    </section>

    <!-- Loading Overlay -->
    <div v-if="loading" class="cosmic-loading-overlay">
      <div class="destiny-spinner cosmic-spinner">
        <div class="animate-spin rounded-full h-16 w-16 border-t-2 border-b-2 border-cyan-400"></div>
        <div class="loading-text">Conectando con La Torre...</div>
      </div>
    </div>
  </div>
</template>
<template>
  <div class="min-h-screen zenthoria-container">
    <!-- Fondo cósmico animado -->
    <div class="destiny-cosmic-pattern"></div>
    <div class="destiny-particles"></div>

    <!-- Navigation Header -->
    <nav class="guardian-nav">
      <div class="nav-container">
        <div class="nav-logo">
          <div class="logo-emblem">
            <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-cyan-400 rounded-full flex items-center justify-center cosmic-glow">
              <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
              </svg>
            </div>
          </div>
          <div class="logo-text">
            <h1 class="destiny-title text-xl">CENTRO DEL GUARDIÁN</h1>
            <span class="destiny-subtitle text-sm">ZENTHORIA ACADEMY</span>
          </div>
        </div>

        <div class="nav-actions">
          <!-- Currency Display -->
          <div class="cosmic-currency-display">
            <div class="currency-item">
              <div class="w-8 h-8 rounded-full bg-gradient-to-r from-yellow-400 to-yellow-600 flex items-center justify-center cosmic-glow">
                <span class="text-sm font-bold text-yellow-900">G</span>
              </div>
              <span class="currency-value">{{ dashboardData?.personaje?.monedas || 0 }}</span>
            </div>
            <div class="currency-item">
              <div class="w-8 h-8 rounded-full bg-gradient-to-r from-cyan-400 to-cyan-600 flex items-center justify-center cosmic-glow">
                <span class="text-sm font-bold text-cyan-900">L</span>
              </div>
              <span class="currency-value">{{ dashboardData?.personaje?.luz_actual || 0 }}</span>
            </div>
          </div>
          
          <!-- Notifications -->
          <button class="cosmic-notification-btn">
            <svg class="w-6 h-6 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM10 3v4a1 1 0 01-1 1H5"/>
            </svg>
            <div v-if="notificacionesPendientes > 0" class="notification-badge">
              {{ notificacionesPendientes }}
            </div>
          </button>

          <!-- Guardian Power Level -->
          <div class="power-level-indicator">
            <div class="power-level-label">POWER</div>
            <div class="power-level-value">{{ dashboardData?.personaje?.power_level || 750 }}</div>
          </div>
        </div>
      </div>
    </nav>

    <!-- Hero Guardian Section -->
    <section class="guardian-hero-section">
      <div class="hero-background cosmic-bg"></div>
      
      <div class="guardian-hero-content">
        <!-- Guardian Welcome -->
        <div class="guardian-welcome-card holo-panel">
          <div class="welcome-header">
            <div class="crown-icon cosmic-float">
              <svg class="w-12 h-12 text-purple-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M5 16L3 4l5.5 5L12 4l3.5 5L21 4l-2 12H5zm2.7-2h8.6l.9-5.4-2.1 1.7L12 8l-3.1 2.3-2.1-1.7L7.7 14z"/>
              </svg>
            </div>
            <h1 class="guardian-welcome-title">
              👑 Bienvenido, Guardián
            </h1>
            <h2 class="guardian-name">
              {{ dashboardData?.personaje?.nombre_personaje || 'Guardián Anónimo' }}
            </h2>
            <p class="guardian-subtitle">
              Continúa tu leyenda en Zenthoria. Cada decisión te acerca más a la grandeza cósmica.
            </p>
          </div>

          <!-- Guardian Quick Stats -->
          <div class="guardian-quick-stats">
            <div class="quick-stat cosmic-stat-purple">
              <div class="stat-icon">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
              </div>
              <div class="stat-content">
                <div class="stat-value">{{ dashboardData?.personaje?.nivel || 1 }}</div>
                <div class="stat-label">Nivel</div>
              </div>
            </div>
            
            <div class="quick-stat cosmic-stat-cyan">
              <div class="stat-icon">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/>
                </svg>
              </div>
              <div class="stat-content">
                <div class="stat-value">{{ estadisticas?.clases_activas || 0 }}</div>
                <div class="stat-label">Fireteams</div>
              </div>
            </div>
            
            <div class="quick-stat cosmic-stat-gold">
              <div class="stat-icon">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                </svg>
              </div>
              <div class="stat-content">
                <div class="stat-value">{{ estadisticas?.logros_obtenidos || 0 }}</div>
                <div class="stat-label">Logros</div>
              </div>
            </div>
          </div>

          <!-- CTA Buttons -->
          <div class="guardian-cta-buttons">
            <button @click="abrirUnirseClase" class="cosmic-btn cosmic-btn-primary">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
              </svg>
              Unirse a Fireteam
            </button>
            <button @click="abrirPersonalizacion" class="cosmic-btn cosmic-btn-outline">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
              </svg>
              Personalizar
            </button>
          </div>
        </div>
      </div>
    </section>

    <!-- Main Guardian Dashboard Content -->
    <div class="guardian-dashboard-content">
      <div class="cosmic-grid">
        
        <!-- Left Panel - Guardian Avatar & Character Sheet -->
        <div class="guardian-left-panel">
          <!-- Guardian Avatar Section -->
          <div class="guardian-avatar-section holo-panel cosmic-panel">
            <div class="panel-header cosmic-header">
              <h2 class="panel-title">Mi Guardián</h2>
              <div class="panel-subtitle">Sistema de Combate Activo</div>
            </div>
            
            <div class="guardian-avatar-container">
              <!-- Guardian Avatar Ring con progreso XP -->
              <div class="guardian-avatar-ring" :style="{ '--xp-progress': `${progresoXP}%` }">
                <div class="avatar-inner-ring">
                  <div class="avatar-image-container">
                    <img 
                      v-if="dashboardData?.personaje?.imagen_personalizada"
                      :src="dashboardData.personaje.imagen_personalizada" 
                      :alt="dashboardData.personaje.nombre_personaje"
                      class="guardian-avatar-image"
                    >
                    <div v-else class="avatar-placeholder cosmic-avatar-placeholder">
                      <svg class="w-20 h-20 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                      </svg>
                    </div>
                  </div>
                </div>
                
                <!-- Level Display -->
                <div class="level-display cosmic-level">
                  <div class="level-number">{{ dashboardData?.personaje?.nivel || 1 }}</div>
                  <div class="level-label">NIVEL</div>
                </div>
                
                <!-- Power Level Badge -->
                <div class="power-level-badge cosmic-power">
                  <div class="power-value">{{ dashboardData?.personaje?.power_level || 750 }}</div>
                  <div class="power-label">POWER</div>
                </div>
              </div>

              <!-- Guardian Class & Info -->
              <div class="guardian-info cosmic-info">
                <h3 class="guardian-character-name">
                  {{ dashboardData?.personaje?.nombre_personaje || 'Guardián Anónimo' }}
                </h3>
                <div class="guardian-class-badge">
                  <div class="class-icon cosmic-class-icon" :class="getClassColor(dashboardData?.personaje?.clase_rpg?.nombre)">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/>
                    </svg>
                  </div>
                  <span class="class-name">
                    {{ dashboardData?.personaje?.clase_rpg?.nombre || 'Titán' }}
                  </span>
                </div>
              </div>
            </div>

            <!-- XP Progress Bar -->
            <div class="xp-progress-section cosmic-xp">
              <div class="xp-header">
                <span class="xp-label">Progreso hacia siguiente nivel</span>
                <span class="xp-values">
                  {{ dashboardData?.personaje?.experiencia || 0 }} / {{ dashboardData?.personaje?.experiencia_siguiente || 1000 }} XP
                </span>
              </div>
              <div class="xp-bar-container">
                <div class="xp-bar cosmic-xp-bar">
                  <div 
                    class="xp-fill cosmic-xp-fill"
                    :style="{ width: `${progresoXP}%` }"
                  ></div>
                  <div class="xp-glow cosmic-xp-glow" :style="{ width: `${progresoXP}%` }"></div>
                </div>
              </div>
            </div>
          </div>

          <!-- Resource Bars Section -->
          <div class="guardian-resources holo-panel cosmic-panel">
            <div class="panel-header cosmic-header">
              <h3 class="panel-title">Estado del Guardián</h3>
              <div class="panel-subtitle">Recursos Vitales</div>
            </div>
            
            <div class="resources-grid">
              <!-- Health Bar -->
              <div class="resource-item cosmic-resource-health">
                <div class="resource-header">
                  <div class="resource-icon">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                    </svg>
                  </div>
                  <span class="resource-label">Salud</span>
                  <span class="resource-value">
                    {{ dashboardData?.personaje?.salud_actual || 0 }}/{{ dashboardData?.personaje?.salud_maxima || 100 }}
                  </span>
                </div>
                <div class="resource-bar cosmic-bar-health">
                  <div 
                    class="resource-fill"
                    :style="{ width: `${porcentajeSalud}%` }"
                  ></div>
                </div>
              </div>
              
              <!-- Energy Bar -->
              <div class="resource-item cosmic-resource-energy">
                <div class="resource-header">
                  <div class="resource-icon">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                  </div>
                  <span class="resource-label">Energía</span>
                  <span class="resource-value">
                    {{ dashboardData?.personaje?.energia_actual || 0 }}/{{ dashboardData?.personaje?.energia_maxima || 10 }}
                  </span>
                </div>
                <div class="resource-bar cosmic-bar-energy">
                  <div 
                    class="resource-fill"
                    :style="{ width: `${porcentajeEnergia}%` }"
                  ></div>
                </div>
              </div>
              
              <!-- Light Bar -->
              <div class="resource-item cosmic-resource-light">
                <div class="resource-header">
                  <div class="resource-icon">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                  </div>
                  <span class="resource-label">Luz</span>
                  <span class="resource-value">
                    {{ dashboardData?.personaje?.luz_actual || 0 }}/{{ dashboardData?.personaje?.luz_maxima || 10 }}
                  </span>
                </div>
                <div class="resource-bar cosmic-bar-light">
                  <div 
                    class="resource-fill"
                    :style="{ width: `${porcentajeLuz}%` }"
                  ></div>
                </div>
              </div>
            </div>
          </div>

          <!-- Guardian Stats Hexagon -->
          <div class="guardian-stats holo-panel cosmic-panel">
            <div class="panel-header cosmic-header">
              <h3 class="panel-title">Atributos</h3>
              <div class="panel-subtitle">Estadísticas Base</div>
            </div>
            
            <div class="stats-hexagon-container">
              <div class="stats-hexagon">
                <div class="stat-hex cosmic-stat-str">
                  <div class="stat-hex-inner">
                    <div class="stat-value">{{ dashboardData?.personaje?.stats_actuales?.fuerza || 0 }}</div>
                    <div class="stat-label">FUE</div>
                  </div>
                </div>
                <div class="stat-hex cosmic-stat-int">
                  <div class="stat-hex-inner">
                    <div class="stat-value">{{ dashboardData?.personaje?.stats_actuales?.inteligencia || 0 }}</div>
                    <div class="stat-label">INT</div>
                  </div>
                </div>
                <div class="stat-hex cosmic-stat-agi">
                  <div class="stat-hex-inner">
                    <div class="stat-value">{{ dashboardData?.personaje?.stats_actuales?.agilidad || 0 }}</div>
                    <div class="stat-label">AGI</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

    <!-- Loading Overlay -->
    <div v-if="loading" class="cosmic-loading-overlay">
      <div class="destiny-spinner cosmic-spinner">
        <div class="animate-spin rounded-full h-16 w-16 border-t-2 border-b-2 border-cyan-400"></div>
        <div class="loading-text">Conectando con La Torre...</div>
      </div>
    </div>
  </div>
</template>
<template>
  <div class="min-h-screen zenthoria-container">
    <!-- Fondo cósmico animado -->
    <div class="destiny-cosmic-pattern"></div>
    <div class="destiny-particles"></div>

    <!-- Navigation Header -->
    <nav class="guardian-nav">
      <div class="nav-container">
        <div class="nav-logo">
          <div class="logo-emblem">
            <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-cyan-400 rounded-full flex items-center justify-center cosmic-glow">
              <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
              </svg>
            </div>
          </div>
          <div class="logo-text">
            <h1 class="destiny-title text-xl">CENTRO DEL GUARDIÁN</h1>
            <span class="destiny-subtitle text-sm">ZENTHORIA ACADEMY</span>
          </div>
        </div>

        <div class="nav-actions">
          <!-- Currency Display -->
          <div class="cosmic-currency-display">
            <div class="currency-item">
              <div class="w-8 h-8 rounded-full bg-gradient-to-r from-yellow-400 to-yellow-600 flex items-center justify-center cosmic-glow">
                <span class="text-sm font-bold text-yellow-900">G</span>
              </div>
              <span class="currency-value">{{ dashboardData?.personaje?.monedas || 0 }}</span>
            </div>
            <div class="currency-item">
              <div class="w-8 h-8 rounded-full bg-gradient-to-r from-cyan-400 to-cyan-600 flex items-center justify-center cosmic-glow">
                <span class="text-sm font-bold text-cyan-900">L</span>
              </div>
              <span class="currency-value">{{ dashboardData?.personaje?.luz_actual || 0 }}</span>
            </div>
          </div>
          
          <!-- Notifications -->
          <button class="cosmic-notification-btn">
            <svg class="w-6 h-6 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM10 3v4a1 1 0 01-1 1H5"/>
            </svg>
            <div v-if="notificacionesPendientes > 0" class="notification-badge">
              {{ notificacionesPendientes }}
            </div>
          </button>

          <!-- Guardian Power Level -->
          <div class="power-level-indicator">
            <div class="power-level-label">POWER</div>
            <div class="power-level-value">{{ dashboardData?.personaje?.power_level || 750 }}</div>
          </div>
        </div>
      </div>
    </nav>

    <!-- Hero Guardian Section -->
    <section class="guardian-hero-section">
      <div class="hero-background cosmic-bg"></div>
      
      <div class="guardian-hero-content">
        <!-- Guardian Welcome -->
        <div class="guardian-welcome-card holo-panel">
          <div class="welcome-header">
            <div class="crown-icon cosmic-float">
              <svg class="w-12 h-12 text-purple-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M5 16L3 4l5.5 5L12 4l3.5 5L21 4l-2 12H5zm2.7-2h8.6l.9-5.4-2.1 1.7L12 8l-3.1 2.3-2.1-1.7L7.7 14z"/>
              </svg>
            </div>
            <h1 class="guardian-welcome-title">
              👑 Bienvenido, Guardián
            </h1>
            <h2 class="guardian-name">
              {{ dashboardData?.personaje?.nombre_personaje || 'Guardián Anónimo' }}
            </h2>
            <p class="guardian-subtitle">
              Continúa tu leyenda en Zenthoria. Cada decisión te acerca más a la grandeza cósmica.
            </p>
          </div>

          <!-- Guardian Quick Stats -->
          <div class="guardian-quick-stats">
            <div class="quick-stat cosmic-stat-purple">
              <div class="stat-icon">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
              </div>
              <div class="stat-content">
                <div class="stat-value">{{ dashboardData?.personaje?.nivel || 1 }}</div>
                <div class="stat-label">Nivel</div>
              </div>
            </div>
            
            <div class="quick-stat cosmic-stat-cyan">
              <div class="stat-icon">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/>
                </svg>
              </div>
              <div class="stat-content">
                <div class="stat-value">{{ estadisticas?.clases_activas || 0 }}</div>
                <div class="stat-label">Fireteams</div>
              </div>
            </div>
            
            <div class="quick-stat cosmic-stat-gold">
              <div class="stat-icon">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                </svg>
              </div>
              <div class="stat-content">
                <div class="stat-value">{{ estadisticas?.logros_obtenidos || 0 }}</div>
                <div class="stat-label">Logros</div>
              </div>
            </div>
          </div>

          <!-- CTA Buttons -->
          <div class="guardian-cta-buttons">
            <button @click="abrirUnirseClase" class="cosmic-btn cosmic-btn-primary">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
              </svg>
              Unirse a Fireteam
            </button>
            <button @click="abrirPersonalizacion" class="cosmic-btn cosmic-btn-outline">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
              </svg>
              Personalizar
            </button>
          </div>
        </div>
      </div>
    </section>

    <!-- Main Guardian Dashboard Content -->
    <div class="guardian-dashboard-content">
      <div class="cosmic-grid">
        
        <!-- Left Panel - Guardian Avatar & Character Sheet -->
        <div class="guardian-left-panel">
          <!-- Guardian Avatar Section -->
          <div class="guardian-avatar-section holo-panel cosmic-panel">
            <div class="panel-header cosmic-header">
              <h2 class="panel-title">Mi Guardián</h2>
              <div class="panel-subtitle">Sistema de Combate Activo</div>
            </div>
            
            <div class="guardian-avatar-container">
              <!-- Guardian Avatar Ring con progreso XP -->
              <div class="guardian-avatar-ring" :style="{ '--xp-progress': `${progresoXP}%` }">
                <div class="avatar-inner-ring">
                  <div class="avatar-image-container">
                    <img 
                      v-if="dashboardData?.personaje?.imagen_personalizada"
                      :src="dashboardData.personaje.imagen_personalizada" 
                      :alt="dashboardData.personaje.nombre_personaje"
                      class="guardian-avatar-image"
                    >
                    <div v-else class="avatar-placeholder cosmic-avatar-placeholder">
                      <svg class="w-20 h-20 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                      </svg>
                    </div>
                  </div>
                </div>
                
                <!-- Level Display -->
                <div class="level-display cosmic-level">
                  <div class="level-number">{{ dashboardData?.personaje?.nivel || 1 }}</div>
                  <div class="level-label">NIVEL</div>
                </div>
                
                <!-- Power Level Badge -->
                <div class="power-level-badge cosmic-power">
                  <div class="power-value">{{ dashboardData?.personaje?.power_level || 750 }}</div>
                  <div class="power-label">POWER</div>
                </div>
              </div>

              <!-- Guardian Class & Info -->
              <div class="guardian-info cosmic-info">
                <h3 class="guardian-character-name">
                  {{ dashboardData?.personaje?.nombre_personaje || 'Guardián Anónimo' }}
                </h3>
                <div class="guardian-class-badge">
                  <div class="class-icon cosmic-class-icon" :class="getClassColor(dashboardData?.personaje?.clase_rpg?.nombre)">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/>
                    </svg>
                  </div>
                  <span class="class-name">
                    {{ dashboardData?.personaje?.clase_rpg?.nombre || 'Titán' }}
                  </span>
                </div>
              </div>
            </div>

            <!-- XP Progress Bar -->
            <div class="xp-progress-section cosmic-xp">
              <div class="xp-header">
                <span class="xp-label">Progreso hacia siguiente nivel</span>
                <span class="xp-values">
                  {{ dashboardData?.personaje?.experiencia || 0 }} / {{ dashboardData?.personaje?.experiencia_siguiente || 1000 }} XP
                </span>
              </div>
              <div class="xp-bar-container">
                <div class="xp-bar cosmic-xp-bar">
                  <div 
                    class="xp-fill cosmic-xp-fill"
                    :style="{ width: `${progresoXP}%` }"
                  ></div>
                  <div class="xp-glow cosmic-xp-glow" :style="{ width: `${progresoXP}%` }"></div>
                </div>
              </div>
            </div>
          </div>

          <!-- Resource Bars Section -->
          <div class="guardian-resources holo-panel cosmic-panel">
            <div class="panel-header cosmic-header">
              <h3 class="panel-title">Estado del Guardián</h3>
              <div class="panel-subtitle">Recursos Vitales</div>
            </div>
            
            <div class="resources-grid">
              <!-- Health Bar -->
              <div class="resource-item cosmic-resource-health">
                <div class="resource-header">
                  <div class="resource-icon">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                    </svg>
                  </div>
                  <span class="resource-label">Salud</span>
                  <span class="resource-value">
                    {{ dashboardData?.personaje?.salud_actual || 0 }}/{{ dashboardData?.personaje?.salud_maxima || 100 }}
                  </span>
                </div>
                <div class="resource-bar cosmic-bar-health">
                  <div 
                    class="resource-fill"
                    :style="{ width: `${porcentajeSalud}%` }"
                  ></div>
                </div>
              </div>
              
              <!-- Energy Bar -->
              <div class="resource-item cosmic-resource-energy">
                <div class="resource-header">
                  <div class="resource-icon">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                  </div>
                  <span class="resource-label">Energía</span>
                  <span class="resource-value">
                    {{ dashboardData?.personaje?.energia_actual || 0 }}/{{ dashboardData?.personaje?.energia_maxima || 10 }}
                  </span>
                </div>
                <div class="resource-bar cosmic-bar-energy">
                  <div 
                    class="resource-fill"
                    :style="{ width: `${porcentajeEnergia}%` }"
                  ></div>
                </div>
              </div>
              
              <!-- Light Bar -->
              <div class="resource-item cosmic-resource-light">
                <div class="resource-header">
                  <div class="resource-icon">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                  </div>
                  <span class="resource-label">Luz</span>
                  <span class="resource-value">
                    {{ dashboardData?.personaje?.luz_actual || 0 }}/{{ dashboardData?.personaje?.luz_maxima || 10 }}
                  </span>
                </div>
                <div class="resource-bar cosmic-bar-light">
                  <div 
                    class="resource-fill"
                    :style="{ width: `${porcentajeLuz}%` }"
                  ></div>
                </div>
              </div>
            </div>
          </div>

          <!-- Guardian Stats Hexagon -->
          <div class="guardian-stats holo-panel cosmic-panel">
            <div class="panel-header cosmic-header">
              <h3 class="panel-title">Atributos</h3>
              <div class="panel-subtitle">Estadísticas Base</div>
            </div>
            
            <div class="stats-hexagon-container">
              <div class="stats-hexagon">
                <div class="stat-hex cosmic-stat-str">
                  <div class="stat-hex-inner">
                    <div class="stat-value">{{ dashboardData?.personaje?.stats_actuales?.fuerza || 0 }}</div>
                    <div class="stat-label">FUE</div>
                  </div>
                </div>
                <div class="stat-hex cosmic-stat-int">
                  <div class="stat-hex-inner">
                    <div class="stat-value">{{ dashboardData?.personaje?.stats_actuales?.inteligencia || 0 }}</div>
                    <div class="stat-label">INT</div>
                  </div>
                </div>
                <div class="stat-hex cosmic-stat-agi">
                  <div class="stat-hex-inner">
                    <div class="stat-value">{{ dashboardData?.personaje?.stats_actuales?.agilidad || 0 }}</div>
                    <div class="stat-label">AGI</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Center Panel - Missions & Operations Center -->
        <div class="guardian-center-panel">
          <!-- Active Missions Section -->
          <div class="active-missions holo-panel cosmic-panel">
            <div class="panel-header cosmic-header">
              <div class="header-main">
                <h2 class="panel-title">🚀 Centro de Operaciones</h2>
                <div class="panel-subtitle">Misiones en Progreso</div>
              </div>
              <div class="missions-counter cosmic-counter">
                <span class="counter-value">{{ misionesActivas.length }}</span>
                <span class="counter-separator">/</span>
                <span class="counter-max">{{ maxMisiones }}</span>
              </div>
            </div>
            
            <div v-if="misionesActivas.length > 0" class="missions-grid">
              <div 
                v-for="mision in misionesActivas" 
                :key="mision.id"
                class="mission-card cosmic-mission-card"
                :class="[
                  `mission-${mision.tipo_mision}`,
                  `difficulty-${mision.dificultad}`,
                  { 'mission-completed': mision.progreso.completada }
                ]"
                @click="abrirDetallesMision(mision)"
              >
                <!-- Mission Header -->
                <div class="mission-header">
                  <div class="mission-type-badge" :class="getMissionTypeClass(mision.tipo_mision)">
                    <div class="badge-icon">
                      <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path :d="getMissionIcon(mision.tipo_mision)"/>
                      </svg>
                    </div>
                    <span class="badge-text">{{ getMissionTypeLabel(mision.tipo_mision) }}</span>
                  </div>
                  
                  <div class="mission-difficulty" :class="getDifficultyClass(mision.dificultad)">
                    <div class="difficulty-dots">
                      <div v-for="i in getDifficultyLevel(mision.dificultad)" :key="i" class="difficulty-dot active"></div>
                      <div v-for="i in (4 - getDifficultyLevel(mision.dificultad))" :key="i + 10" class="difficulty-dot"></div>
                    </div>
                    <span class="difficulty-label">{{ mision.dificultad.toUpperCase() }}</span>
                  </div>
                  
                  <div v-if="mision.poder_requerido" class="power-requirement cosmic-power-req">
                    ⚡ {{ mision.poder_requerido }}+
                  </div>
                </div>
                
                <!-- Mission Content -->
                <div class="mission-content">
                  <h3 class="mission-title cosmic-mission-title">
                    {{ mision.titulo }}
                  </h3>
                  <p class="mission-description cosmic-text-muted">
                    {{ mision.descripcion }}
                  </p>
                  
                  <!-- Mission Progress -->
                  <div class="mission-progress cosmic-progress">
                    <div class="progress-header">
                      <span class="progress-label">Progreso</span>
                      <span class="progress-percentage">{{ Math.round(mision.progreso.porcentaje_progreso) }}%</span>
                    </div>
                    <div class="progress-bar-container">
                      <div class="progress-bar cosmic-progress-bar" :class="getMissionProgressClass(mision.tipo_mision)">
                        <div 
                          class="progress-fill cosmic-progress-fill"
                          :style="{ width: `${mision.progreso.porcentaje_progreso}%` }"
                        ></div>
                        <div class="progress-glow" :style="{ width: `${mision.progreso.porcentaje_progreso}%` }"></div>
                      </div>
                    </div>
                    <div class="progress-activities">
                      {{ mision.progreso.actividades_completadas }}/{{ mision.progreso.total_actividades_requeridas }} actividades completadas
                    </div>
                  </div>
                </div>
                
                <!-- Mission Footer -->
                <div class="mission-footer">
                  <div class="mission-rewards cosmic-rewards">
                    <div v-if="mision.experiencia_bonus" class="reward-item cosmic-reward-xp">
                      <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                      </svg>
                      <span>+{{ mision.experiencia_bonus }} XP</span>
                    </div>
                    <div v-if="mision.glimmer_bonus" class="reward-item cosmic-reward-glimmer">
                      <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/>
                      </svg>
                      <span>+{{ mision.glimmer_bonus }} G</span>
                    </div>
                  </div>
                  
                  <!-- Time Remaining -->
                  <div v-if="mision.fecha_fin" class="mission-timer cosmic-timer">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                    <span>{{ formatearTiempoRestante(mision.fecha_fin) }}</span>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Empty State -->
            <div v-else class="missions-empty-state cosmic-empty-state">
              <div class="empty-icon cosmic-empty-icon">
                <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
              </div>
              <h3 class="empty-title">No hay misiones activas</h3>
              <p class="empty-description">¡Únete a un Fireteam para comenzar tu aventura épica!</p>
              <button @click="abrirUnirseClase" class="cosmic-btn cosmic-btn-primary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                Buscar Fireteam
              </button>
            </div>
          </div>

          <!-- Recent Achievements Section -->
          <div class="recent-achievements holo-panel cosmic-panel">
            <div class="panel-header cosmic-header">
              <div class="header-main">
                <h2 class="panel-title">🏆 Logros Épicos</h2>
                <div class="panel-subtitle">Triunfos Recientes</div>
              </div>
              <button 
                @click="verTodosLogros"
                class="cosmic-btn cosmic-btn-ghost"
              >
                Ver Todos
              </button>
            </div>
            
            <div v-if="logrosRecientes.length > 0" class="achievements-grid">
              <div 
                v-for="logro in logrosRecientes.slice(0, 4)" 
                :key="logro.id"
                class="achievement-card cosmic-achievement-card"
                :class="[`achievement-${logro.rareza}`]"
                @click="mostrarDetallesLogro(logro)"
              >
                <div class="achievement-icon-container">
                  <div class="achievement-icon cosmic-achievement-icon" :class="getRarityClass(logro.rareza)">
                    <img 
                      v-if="logro.imagen_url" 
                      :src="logro.imagen_url" 
                      :alt="logro.nombre"
                      class="achievement-image"
                    >
                    <svg v-else class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                  </div>
                  
                  <!-- Rarity Indicator -->
                  <div class="rarity-indicator" :class="getRarityIndicatorClass(logro.rareza)">
                    <div class="rarity-dots">
                      <div v-for="i in getRarityLevel(logro.rareza)" :key="i" class="rarity-dot"></div>
                    </div>
                  </div>
                </div>
                
                <div class="achievement-content">
                  <h4 class="achievement-title cosmic-achievement-title">
                    {{ logro.nombre }}
                  </h4>
                  <p class="achievement-description cosmic-text-muted">
                    {{ logro.descripcion }}
                  </p>
                  
                  <!-- New Badge -->
                  <div v-if="logro.nuevo" class="new-badge cosmic-new-badge">
                    <span class="new-text">¡NUEVO!</span>
                    <div class="new-glow"></div>
                  </div>
                  
                  <!-- Achievement Date -->
                  <div class="achievement-date cosmic-date">
                    {{ formatearFecha(logro.fecha_obtencion) }}
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Empty State -->
            <div v-else class="achievements-empty-state cosmic-empty-state">
              <div class="empty-icon cosmic-empty-icon">
                <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                </svg>
              </div>
              <h3 class="empty-title">No hay logros recientes</h3>
              <p class="empty-description">¡Completa actividades para desbloquear badges épicos!</p>
            </div>
          </div>
        </div>

    <!-- Loading Overlay -->
    <div v-if="loading" class="cosmic-loading-overlay">
      <div class="destiny-spinner cosmic-spinner">
        <div class="animate-spin rounded-full h-16 w-16 border-t-2 border-b-2 border-cyan-400"></div>
        <div class="loading-text">Conectando con La Torre...</div>
      </div>
    </div>
  </div>
</template>
<template>
  <div class="min-h-screen zenthoria-container">
    <!-- Fondo cósmico animado -->
    <div class="destiny-cosmic-pattern"></div>
    <div class="destiny-particles"></div>

    <!-- Navigation Header -->
    <nav class="guardian-nav">
      <div class="nav-container">
        <div class="nav-logo">
          <div class="logo-emblem">
            <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-cyan-400 rounded-full flex items-center justify-center cosmic-glow">
              <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
              </svg>
            </div>
          </div>
          <div class="logo-text">
            <h1 class="destiny-title text-xl">CENTRO DEL GUARDIÁN</h1>
            <span class="destiny-subtitle text-sm">ZENTHORIA ACADEMY</span>
          </div>
        </div>

        <div class="nav-actions">
          <!-- Currency Display -->
          <div class="cosmic-currency-display">
            <div class="currency-item">
              <div class="w-8 h-8 rounded-full bg-gradient-to-r from-yellow-400 to-yellow-600 flex items-center justify-center cosmic-glow">
                <span class="text-sm font-bold text-yellow-900">G</span>
              </div>
              <span class="currency-value">{{ dashboardData?.personaje?.monedas || 0 }}</span>
            </div>
            <div class="currency-item">
              <div class="w-8 h-8 rounded-full bg-gradient-to-r from-cyan-400 to-cyan-600 flex items-center justify-center cosmic-glow">
                <span class="text-sm font-bold text-cyan-900">L</span>
              </div>
              <span class="currency-value">{{ dashboardData?.personaje?.luz_actual || 0 }}</span>
            </div>
          </div>
          
          <!-- Notifications -->
          <button class="cosmic-notification-btn">
            <svg class="w-6 h-6 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM10 3v4a1 1 0 01-1 1H5"/>
            </svg>
            <div v-if="notificacionesPendientes > 0" class="notification-badge">
              {{ notificacionesPendientes }}
            </div>
          </button>

          <!-- Guardian Power Level -->
          <div class="power-level-indicator">
            <div class="power-level-label">POWER</div>
            <div class="power-level-value">{{ dashboardData?.personaje?.power_level || 750 }}</div>
          </div>
        </div>
      </div>
    </nav>

    <!-- Hero Guardian Section -->
    <section class="guardian-hero-section">
      <div class="hero-background cosmic-bg"></div>
      
      <div class="guardian-hero-content">
        <!-- Guardian Welcome -->
        <div class="guardian-welcome-card holo-panel">
          <div class="welcome-header">
            <div class="crown-icon cosmic-float">
              <svg class="w-12 h-12 text-purple-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M5 16L3 4l5.5 5L12 4l3.5 5L21 4l-2 12H5zm2.7-2h8.6l.9-5.4-2.1 1.7L12 8l-3.1 2.3-2.1-1.7L7.7 14z"/>
              </svg>
            </div>
            <h1 class="guardian-welcome-title">
              👑 Bienvenido, Guardián
            </h1>
            <h2 class="guardian-name">
              {{ dashboardData?.personaje?.nombre_personaje || 'Guardián Anónimo' }}
            </h2>
            <p class="guardian-subtitle">
              Continúa tu leyenda en Zenthoria. Cada decisión te acerca más a la grandeza cósmica.
            </p>
          </div>

          <!-- Guardian Quick Stats -->
          <div class="guardian-quick-stats">
            <div class="quick-stat cosmic-stat-purple">
              <div class="stat-icon">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
              </div>
              <div class="stat-content">
                <div class="stat-value">{{ dashboardData?.personaje?.nivel || 1 }}</div>
                <div class="stat-label">Nivel</div>
              </div>
            </div>
            
            <div class="quick-stat cosmic-stat-cyan">
              <div class="stat-icon">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/>
                </svg>
              </div>
              <div class="stat-content">
                <div class="stat-value">{{ estadisticas?.clases_activas || 0 }}</div>
                <div class="stat-label">Fireteams</div>
              </div>
            </div>
            
            <div class="quick-stat cosmic-stat-gold">
              <div class="stat-icon">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                </svg>
              </div>
              <div class="stat-content">
                <div class="stat-value">{{ estadisticas?.logros_obtenidos || 0 }}</div>
                <div class="stat-label">Logros</div>
              </div>
            </div>
          </div>

          <!-- CTA Buttons -->
          <div class="guardian-cta-buttons">
            <button @click="abrirUnirseClase" class="cosmic-btn cosmic-btn-primary">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
              </svg>
              Unirse a Fireteam
            </button>
            <button @click="abrirPersonalizacion" class="cosmic-btn cosmic-btn-outline">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
              </svg>
              Personalizar
            </button>
          </div>
        </div>
      </div>
    </section>

    <!-- Main Guardian Dashboard Content -->
    <div class="guardian-dashboard-content">
      <div class="cosmic-grid">
        
        <!-- Left Panel - Guardian Avatar & Character Sheet -->
        <div class="guardian-left-panel">
          <!-- Guardian Avatar Section -->
          <div class="guardian-avatar-section holo-panel cosmic-panel">
            <div class="panel-header cosmic-header">
              <h2 class="panel-title">Mi Guardián</h2>
              <div class="panel-subtitle">Sistema de Combate Activo</div>
            </div>
            
            <div class="guardian-avatar-container">
              <!-- Guardian Avatar Ring con progreso XP -->
              <div class="guardian-avatar-ring" :style="{ '--xp-progress': `${progresoXP}%` }">
                <div class="avatar-inner-ring">
                  <div class="avatar-image-container">
                    <img 
                      v-if="dashboardData?.personaje?.imagen_personalizada"
                      :src="dashboardData.personaje.imagen_personalizada" 
                      :alt="dashboardData.personaje.nombre_personaje"
                      class="guardian-avatar-image"
                    >
                    <div v-else class="avatar-placeholder cosmic-avatar-placeholder">
                      <svg class="w-20 h-20 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                      </svg>
                    </div>
                  </div>
                </div>
                
                <!-- Level Display -->
                <div class="level-display cosmic-level">
                  <div class="level-number">{{ dashboardData?.personaje?.nivel || 1 }}</div>
                  <div class="level-label">NIVEL</div>
                </div>
                
                <!-- Power Level Badge -->
                <div class="power-level-badge cosmic-power">
                  <div class="power-value">{{ dashboardData?.personaje?.power_level || 750 }}</div>
                  <div class="power-label">POWER</div>
                </div>
              </div>

              <!-- Guardian Class & Info -->
              <div class="guardian-info cosmic-info">
                <h3 class="guardian-character-name">
                  {{ dashboardData?.personaje?.nombre_personaje || 'Guardián Anónimo' }}
                </h3>
                <div class="guardian-class-badge">
                  <div class="class-icon cosmic-class-icon" :class="getClassColor(dashboardData?.personaje?.clase_rpg?.nombre)">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/>
                    </svg>
                  </div>
                  <span class="class-name">
                    {{ dashboardData?.personaje?.clase_rpg?.nombre || 'Titán' }}
                  </span>
                </div>
              </div>
            </div>

            <!-- XP Progress Bar -->
            <div class="xp-progress-section cosmic-xp">
              <div class="xp-header">
                <span class="xp-label">Progreso hacia siguiente nivel</span>
                <span class="xp-values">
                  {{ dashboardData?.personaje?.experiencia || 0 }} / {{ dashboardData?.personaje?.experiencia_siguiente || 1000 }} XP
                </span>
              </div>
              <div class="xp-bar-container">
                <div class="xp-bar cosmic-xp-bar">
                  <div 
                    class="xp-fill cosmic-xp-fill"
                    :style="{ width: `${progresoXP}%` }"
                  ></div>
                  <div class="xp-glow cosmic-xp-glow" :style="{ width: `${progresoXP}%` }"></div>
                </div>
              </div>
            </div>
          </div>

          <!-- Resource Bars Section -->
          <div class="guardian-resources holo-panel cosmic-panel">
            <div class="panel-header cosmic-header">
              <h3 class="panel-title">Estado del Guardián</h3>
              <div class="panel-subtitle">Recursos Vitales</div>
            </div>
            
            <div class="resources-grid">
              <!-- Health Bar -->
              <div class="resource-item cosmic-resource-health">
                <div class="resource-header">
                  <div class="resource-icon">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                    </svg>
                  </div>
                  <span class="resource-label">Salud</span>
                  <span class="resource-value">
                    {{ dashboardData?.personaje?.salud_actual || 0 }}/{{ dashboardData?.personaje?.salud_maxima || 100 }}
                  </span>
                </div>
                <div class="resource-bar cosmic-bar-health">
                  <div 
                    class="resource-fill"
                    :style="{ width: `${porcentajeSalud}%` }"
                  ></div>
                </div>
              </div>
              
              <!-- Energy Bar -->
              <div class="resource-item cosmic-resource-energy">
                <div class="resource-header">
                  <div class="resource-icon">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                  </div>
                  <span class="resource-label">Energía</span>
                  <span class="resource-value">
                    {{ dashboardData?.personaje?.energia_actual || 0 }}/{{ dashboardData?.personaje?.energia_maxima || 10 }}
                  </span>
                </div>
                <div class="resource-bar cosmic-bar-energy">
                  <div 
                    class="resource-fill"
                    :style="{ width: `${porcentajeEnergia}%` }"
                  ></div>
                </div>
              </div>
              
              <!-- Light Bar -->
              <div class="resource-item cosmic-resource-light">
                <div class="resource-header">
                  <div class="resource-icon">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                  </div>
                  <span class="resource-label">Luz</span>
                  <span class="resource-value">
                    {{ dashboardData?.personaje?.luz_actual || 0 }}/{{ dashboardData?.personaje?.luz_maxima || 10 }}
                  </span>
                </div>
                <div class="resource-bar cosmic-bar-light">
                  <div 
                    class="resource-fill"
                    :style="{ width: `${porcentajeLuz}%` }"
                  ></div>
                </div>
              </div>
            </div>
          </div>

          <!-- Guardian Stats Hexagon -->
          <div class="guardian-stats holo-panel cosmic-panel">
            <div class="panel-header cosmic-header">
              <h3 class="panel-title">Atributos</h3>
              <div class="panel-subtitle">Estadísticas Base</div>
            </div>
            
            <div class="stats-hexagon-container">
              <div class="stats-hexagon">
                <div class="stat-hex cosmic-stat-str">
                  <div class="stat-hex-inner">
                    <div class="stat-value">{{ dashboardData?.personaje?.stats_actuales?.fuerza || 0 }}</div>
                    <div class="stat-label">FUE</div>
                  </div>
                </div>
                <div class="stat-hex cosmic-stat-int">
                  <div class="stat-hex-inner">
                    <div class="stat-value">{{ dashboardData?.personaje?.stats_actuales?.inteligencia || 0 }}</div>
                    <div class="stat-label">INT</div>
                  </div>
                </div>
                <div class="stat-hex cosmic-stat-agi">
                  <div class="stat-hex-inner">
                    <div class="stat-value">{{ dashboardData?.personaje?.stats_actuales?.agilidad || 0 }}</div>
                    <div class="stat-label">AGI</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Center Panel - Missions & Operations Center -->
        <div class="guardian-center-panel">
          <!-- Active Missions Section -->
          <div class="active-missions holo-panel cosmic-panel">
            <div class="panel-header cosmic-header">
              <div class="header-main">
                <h2 class="panel-title">🚀 Centro de Operaciones</h2>
                <div class="panel-subtitle">Misiones en Progreso</div>
              </div>
              <div class="missions-counter cosmic-counter">
                <span class="counter-value">{{ misionesActivas.length }}</span>
                <span class="counter-separator">/</span>
                <span class="counter-max">{{ maxMisiones }}</span>
              </div>
            </div>
            
            <div v-if="misionesActivas.length > 0" class="missions-grid">
              <div 
                v-for="mision in misionesActivas" 
                :key="mision.id"
                class="mission-card cosmic-mission-card"
                :class="[
                  `mission-${mision.tipo_mision}`,
                  `difficulty-${mision.dificultad}`,
                  { 'mission-completed': mision.progreso.completada }
                ]"
                @click="abrirDetallesMision(mision)"
              >
                <!-- Mission Header -->
                <div class="mission-header">
                  <div class="mission-type-badge" :class="getMissionTypeClass(mision.tipo_mision)">
                    <div class="badge-icon">
                      <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path :d="getMissionIcon(mision.tipo_mision)"/>
                      </svg>
                    </div>
                    <span class="badge-text">{{ getMissionTypeLabel(mision.tipo_mision) }}</span>
                  </div>
                  
                  <div class="mission-difficulty" :class="getDifficultyClass(mision.dificultad)">
                    <div class="difficulty-dots">
                      <div v-for="i in getDifficultyLevel(mision.dificultad)" :key="i" class="difficulty-dot active"></div>
                      <div v-for="i in (4 - getDifficultyLevel(mision.dificultad))" :key="i + 10" class="difficulty-dot"></div>
                    </div>
                    <span class="difficulty-label">{{ mision.dificultad.toUpperCase() }}</span>
                  </div>
                  
                  <div v-if="mision.poder_requerido" class="power-requirement cosmic-power-req">
                    ⚡ {{ mision.poder_requerido }}+
                  </div>
                </div>
                
                <!-- Mission Content -->
                <div class="mission-content">
                  <h3 class="mission-title cosmic-mission-title">
                    {{ mision.titulo }}
                  </h3>
                  <p class="mission-description cosmic-text-muted">
                    {{ mision.descripcion }}
                  </p>
                  
                  <!-- Mission Progress -->
                  <div class="mission-progress cosmic-progress">
                    <div class="progress-header">
                      <span class="progress-label">Progreso</span>
                      <span class="progress-percentage">{{ Math.round(mision.progreso.porcentaje_progreso) }}%</span>
                    </div>
                    <div class="progress-bar-container">
                      <div class="progress-bar cosmic-progress-bar" :class="getMissionProgressClass(mision.tipo_mision)">
                        <div 
                          class="progress-fill cosmic-progress-fill"
                          :style="{ width: `${mision.progreso.porcentaje_progreso}%` }"
                        ></div>
                        <div class="progress-glow" :style="{ width: `${mision.progreso.porcentaje_progreso}%` }"></div>
                      </div>
                    </div>
                    <div class="progress-activities">
                      {{ mision.progreso.actividades_completadas }}/{{ mision.progreso.total_actividades_requeridas }} actividades completadas
                    </div>
                  </div>
                </div>
                
                <!-- Mission Footer -->
                <div class="mission-footer">
                  <div class="mission-rewards cosmic-rewards">
                    <div v-if="mision.experiencia_bonus" class="reward-item cosmic-reward-xp">
                      <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                      </svg>
                      <span>+{{ mision.experiencia_bonus }} XP</span>
                    </div>
                    <div v-if="mision.glimmer_bonus" class="reward-item cosmic-reward-glimmer">
                      <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/>
                      </svg>
                      <span>+{{ mision.glimmer_bonus }} G</span>
                    </div>
                  </div>
                  
                  <!-- Time Remaining -->
                  <div v-if="mision.fecha_fin" class="mission-timer cosmic-timer">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                    <span>{{ formatearTiempoRestante(mision.fecha_fin) }}</span>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Empty State -->
            <div v-else class="missions-empty-state cosmic-empty-state">
              <div class="empty-icon cosmic-empty-icon">
                <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
              </div>
              <h3 class="empty-title">No hay misiones activas</h3>
              <p class="empty-description">¡Únete a un Fireteam para comenzar tu aventura épica!</p>
              <button @click="abrirUnirseClase" class="cosmic-btn cosmic-btn-primary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                Buscar Fireteam
              </button>
            </div>
          </div>

          <!-- Recent Achievements Section -->
          <div class="recent-achievements holo-panel cosmic-panel">
            <div class="panel-header cosmic-header">
              <div class="header-main">
                <h2 class="panel-title">🏆 Logros Épicos</h2>
                <div class="panel-subtitle">Triunfos Recientes</div>
              </div>
              <button 
                @click="verTodosLogros"
                class="cosmic-btn cosmic-btn-ghost"
              >
                Ver Todos
              </button>
            </div>
            
            <div v-if="logrosRecientes.length > 0" class="achievements-grid">
              <div 
                v-for="logro in logrosRecientes.slice(0, 4)" 
                :key="logro.id"
                class="achievement-card cosmic-achievement-card"
                :class="[`achievement-${logro.rareza}`]"
                @click="mostrarDetallesLogro(logro)"
              >
                <div class="achievement-icon-container">
                  <div class="achievement-icon cosmic-achievement-icon" :class="getRarityClass(logro.rareza)">
                    <img 
                      v-if="logro.imagen_url" 
                      :src="logro.imagen_url" 
                      :alt="logro.nombre"
                      class="achievement-image"
                    >
                    <svg v-else class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                  </div>
                  
                  <!-- Rarity Indicator -->
                  <div class="rarity-indicator" :class="getRarityIndicatorClass(logro.rareza)">
                    <div class="rarity-dots">
                      <div v-for="i in getRarityLevel(logro.rareza)" :key="i" class="rarity-dot"></div>
                    </div>
                  </div>
                </div>
                
                <div class="achievement-content">
                  <h4 class="achievement-title cosmic-achievement-title">
                    {{ logro.nombre }}
                  </h4>
                  <p class="achievement-description cosmic-text-muted">
                    {{ logro.descripcion }}
                  </p>
                  
                  <!-- New Badge -->
                  <div v-if="logro.nuevo" class="new-badge cosmic-new-badge">
                    <span class="new-text">¡NUEVO!</span>
                    <div class="new-glow"></div>
                  </div>
                  
                  <!-- Achievement Date -->
                  <div class="achievement-date cosmic-date">
                    {{ formatearFecha(logro.fecha_obtencion) }}
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Empty State -->
            <div v-else class="achievements-empty-state cosmic-empty-state">
              <div class="empty-icon cosmic-empty-icon">
                <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                </svg>
              </div>
              <h3 class="empty-title">No hay logros recientes</h3>
              <p class="empty-description">¡Completa actividades para desbloquear badges épicos!</p>
            </div>
          </div>
        </div>

        <!-- Right Panel - Stats & Activity Center -->
        <div class="guardian-right-panel">
          <!-- Quick Stats Section -->
          <div class="quick-stats holo-panel cosmic-panel">
            <div class="panel-header cosmic-header">
              <h2 class="panel-title">📊 Centro de Estadísticas</h2>
              <div class="panel-subtitle">Métricas del Guardián</div>
            </div>
            
            <div class="stats-grid cosmic-stats-grid">
              <div class="stat-card cosmic-stat-card stat-fireteams">
                <div class="stat-icon-container">
                  <div class="stat-icon cosmic-stat-icon-cyan">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M16 4c0-1.11.89-2 2-2s2 .89 2 2-.89 2-2 2-2-.89-2-2zm4 18v-6h2.5l-2.54-7.63A1.5 1.5 0 0 0 18.5 7h-3c-.83 0-1.54.5-1.85 1.22l-1.92 5.78c-.1.3-.23.59-.41.83L8.5 12c.83 0 1.5-.67 1.5-1.5S9.33 9 8.5 9 7 9.67 7 10.5 7.67 12 8.5 12l2.82 2.83c.18.24.31.53.41.83l1.92 5.78c.31.72 1.02 1.22 1.85 1.22h3c.83 0 1.5-.67 1.5-1.5z"/>
                    </svg>
                  </div>
                </div>
                <div class="stat-content">
                  <div class="stat-value cosmic-stat-value">{{ estadisticas?.clases_activas || 0 }}</div>
                  <div class="stat-label">Fireteams Activos</div>
                </div>
                <div class="stat-trend cosmic-trend-up">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                  </svg>
                </div>
              </div>
              
              <div class="stat-card cosmic-stat-card stat-ranking">
                <div class="stat-icon-container">
                  <div class="stat-icon cosmic-stat-icon-purple">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M5 16L3 4l5.5 5L12 4l3.5 5L21 4l-2 12H5zm2.7-2h8.6l.9-5.4-2.1 1.7L12 8l-3.1 2.3-2.1-1.7L7.7 14z"/>
                    </svg>
                  </div>
                </div>
                <div class="stat-content">
                  <div class="stat-value cosmic-stat-value">#{{ estadisticas?.ranking_global || '---' }}</div>
                  <div class="stat-label">Posición Global</div>
                </div>
                <div class="stat-trend cosmic-trend-neutral">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                  </svg>
                </div>
              </div>
              
              <div class="stat-card cosmic-stat-card stat-achievements">
                <div class="stat-icon-container">
                  <div class="stat-icon cosmic-stat-icon-gold">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                  </div>
                </div>
                <div class="stat-content">
                  <div class="stat-value cosmic-stat-value">{{ estadisticas?.logros_obtenidos || 0 }}</div>
                  <div class="stat-label">Logros Obtenidos</div>
                </div>
                <div class="stat-trend cosmic-trend-up">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                  </svg>
                </div>
              </div>
              
              <div class="stat-card cosmic-stat-card stat-total-xp">
                <div class="stat-icon-container">
                  <div class="stat-icon cosmic-stat-icon-green">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                  </div>
                </div>
                <div class="stat-content">
                  <div class="stat-value cosmic-stat-value">{{ formatearNumero(estadisticas?.experiencia_total || 0) }}</div>
                  <div class="stat-label">XP Total</div>
                </div>
                <div class="stat-trend cosmic-trend-up">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                  </svg>
                </div>
              </div>
            </div>
          </div>

          <!-- Quick Actions Section -->
          <div class="quick-actions holo-panel cosmic-panel">
            <div class="panel-header cosmic-header">
              <h2 class="panel-title">⚡ Acciones Rápidas</h2>
              <div class="panel-subtitle">Centro de Comando</div>
            </div>
            
            <div class="actions-grid cosmic-actions-grid">
              <button 
                @click="abrirUnirseClase"
                class="action-button cosmic-action-button action-join-fireteam"
              >
                <div class="action-icon cosmic-action-icon-cyan">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                  </svg>
                </div>
                <div class="action-content">
                  <div class="action-title">Unirse a Fireteam</div>
                  <div class="action-subtitle">Buscar equipo</div>
                </div>
                <div class="action-arrow">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                  </svg>
                </div>
              </button>
              
              <button 
                @click="abrirPersonalizacion"
                class="action-button cosmic-action-button action-customize"
              >
                <div class="action-icon cosmic-action-icon-purple">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                  </svg>
                </div>
                <div class="action-content">
                  <div class="action-title">Personalizar</div>
                  <div class="action-subtitle">Editar guardián</div>
                </div>
                <div class="action-arrow">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                  </svg>
                </div>
              </button>
              
              <button 
                @click="verInventario"
                class="action-button cosmic-action-button action-inventory"
              >
                <div class="action-icon cosmic-action-icon-gold">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                  </svg>
                </div>
                <div class="action-content">
                  <div class="action-title">Ver Inventario</div>
                  <div class="action-subtitle">Equipo y objetos</div>
                </div>
                <div class="action-arrow">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                  </svg>
                </div>
              </button>
            </div>
          </div>

          <!-- Recent Activity Feed -->
          <div class="activity-feed holo-panel cosmic-panel">
            <div class="panel-header cosmic-header">
              <h2 class="panel-title">📡 Actividad Reciente</h2>
              <div class="panel-subtitle">Registro de La Torre</div>
            </div>
            
            <div v-if="actividadReciente.length > 0" class="activity-timeline cosmic-timeline">
              <div 
                v-for="actividad in actividadReciente.slice(0, 5)" 
                :key="actividad.id"
                class="activity-item cosmic-activity-item"
                :class="getActivityTypeClass(actividad.tipo)"
              >
                <div class="activity-icon-container">
                  <div class="activity-icon cosmic-activity-icon" :class="getActivityIconClass(actividad.tipo)">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                      <path :d="getActivityIcon(actividad.tipo)"/>
                    </svg>
                  </div>
                  <div class="activity-connector cosmic-connector"></div>
                </div>
                
                <div class="activity-content">
                  <div class="activity-title cosmic-activity-title">{{ actividad.titulo }}</div>
                  <div class="activity-meta cosmic-activity-meta">
                    <span class="activity-time">{{ formatearTiempo(actividad.fecha) }}</span>
                    <div class="activity-type-badge cosmic-type-badge" :class="getActivityTypeClass(actividad.tipo)">
                      {{ getActivityTypeLabel(actividad.tipo) }}
                    </div>
                  </div>
                </div>
                
                <div class="activity-glow cosmic-activity-glow" :class="getActivityGlowClass(actividad.tipo)"></div>
              </div>
            </div>
            
            <!-- Empty State -->
            <div v-else class="activity-empty-state cosmic-empty-state">
              <div class="empty-icon cosmic-empty-icon">
                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
              </div>
              <h3 class="empty-title">No hay actividad reciente</h3>
              <p class="empty-description">Inicia tu journey para ver tu progreso aquí</p>
            </div>
          </div>

          <!-- Guardian Power Analysis -->
          <div class="power-analysis holo-panel cosmic-panel">
            <div class="panel-header cosmic-header">
              <h2 class="panel-title">⚡ Análisis de Poder</h2>
              <div class="panel-subtitle">Distribución de Atributos</div>
            </div>
            
            <div class="power-chart cosmic-power-chart">
              <!-- Power Distribution Radar -->
              <div class="power-radar">
                <div class="radar-center cosmic-radar-center">
                  <div class="power-total">{{ dashboardData?.personaje?.power_level || 750 }}</div>
                  <div class="power-label">TOTAL</div>
                </div>
                
                <!-- Radar Arms -->
                <div class="radar-arm radar-strength" :style="{ '--strength': `${getStatPercentage('fuerza')}%` }">
                  <div class="radar-point cosmic-point-str"></div>
                  <div class="radar-label">FUE</div>
                </div>
                <div class="radar-arm radar-intelligence" :style="{ '--intelligence': `${getStatPercentage('inteligencia')}%` }">
                  <div class="radar-point cosmic-point-int"></div>
                  <div class="radar-label">INT</div>
                </div>
                <div class="radar-arm radar-agility" :style="{ '--agility': `${getStatPercentage('agilidad')}%` }">
                  <div class="radar-point cosmic-point-agi"></div>
                  <div class="radar-label">AGI</div>
                </div>
              </div>
              
              <!-- Power Breakdown -->
              <div class="power-breakdown cosmic-breakdown">
                <div class="breakdown-item cosmic-breakdown-str">
                  <div class="breakdown-label">Fuerza</div>
                  <div class="breakdown-bar">
                    <div class="breakdown-fill" :style="{ width: `${getStatPercentage('fuerza')}%` }"></div>
                  </div>
                  <div class="breakdown-value">{{ dashboardData?.personaje?.stats_actuales?.fuerza || 0 }}</div>
                </div>
                <div class="breakdown-item cosmic-breakdown-int">
                  <div class="breakdown-label">Inteligencia</div>
                  <div class="breakdown-bar">
                    <div class="breakdown-fill" :style="{ width: `${getStatPercentage('inteligencia')}%` }"></div>
                  </div>
                  <div class="breakdown-value">{{ dashboardData?.personaje?.stats_actuales?.inteligencia || 0 }}</div>
                </div>
                <div class="breakdown-item cosmic-breakdown-agi">
                  <div class="breakdown-label">Agilidad</div>
                  <div class="breakdown-bar">
                    <div class="breakdown-fill" :style="{ width: `${getStatPercentage('agilidad')}%` }"></div>
                  </div>
                  <div class="breakdown-value">{{ dashboardData?.personaje?.stats_actuales?.agilidad || 0 }}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Loading Overlay -->
    <div v-if="loading" class="cosmic-loading-overlay">
      <div class="destiny-spinner cosmic-spinner">
        <div class="animate-spin rounded-full h-16 w-16 border-t-2 border-b-2 border-cyan-400"></div>
        <div class="loading-text">Conectando con La Torre...</div>
      </div>
    </div>
  </div>
</template>
