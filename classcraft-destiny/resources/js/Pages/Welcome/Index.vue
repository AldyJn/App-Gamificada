<!-- resources/js/Pages/Welcome/Index.vue -->
<template>
  <Head title="Zenthoria - Donde el conocimiento se convierte en poder" />

  <div class="welcome-container">
    <!-- Fondo cósmico animado -->
    <div class="destiny-cosmic-pattern"></div>
    <div class="destiny-particles"></div>

    <!-- Navigation Header -->
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

        <div class="nav-links">
          <a href="#features" class="nav-link">Características</a>
          <a href="#missions" class="nav-link">Misiones</a>
          <a href="#guardians" class="nav-link">Guardianes</a>
          <a href="#events" class="nav-link">Eventos</a>
        </div>

        <div class="nav-auth">
          <Link href="/login" class="destiny-btn destiny-btn-sm">
            Iniciar Sesión
          </Link>
          <Link href="/register" class="destiny-btn destiny-btn-cosmic destiny-btn-sm">
            Unirse a la Academia
          </Link>
        </div>
      </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section" id="hero">
      <div class="hero-background">
        <div class="hero-image cosmic-bg"></div>
        <div class="hero-overlay"></div>
      </div>

      <div class="hero-content">
        <div class="crown-icon">
          <svg class="w-16 h-16 text-purple-400" fill="currentColor" viewBox="0 0 24 24">
            <path d="M5 16L3 4l5.5 5L12 4l3.5 5L21 4l-2 12H5zm2.7-2h8.6l.9-5.4-2.1 1.7L12 8l-3.1 2.3-2.1-1.7L7.7 14z"/>
          </svg>
        </div>
        
        <h1 class="hero-title">
          👑 Bienvenido a Zenthoria
        </h1>
        <p class="hero-subtitle-main">
          Donde el conocimiento se convierte en poder.
        </p>
        <p class="hero-description">
          Embárcate en una aventura de aprendizaje como nunca antes. Lucha junto a tus compañeros, completa misiones, sube de nivel, y desbloquea habilidades únicas mientras dominas cada clase. Zenthoria es más que una plataforma educativa... Es tu campo de entrenamiento intergaláctico.
        </p>

        <!-- Hero Stats -->
        <div class="hero-stats" v-if="welcomeData?.hero?.estadisticas">
          <div class="stat-item cosmic-stat">
            <span class="stat-number">{{ formatNumber(welcomeData.hero.estadisticas.guardianes_activos) }}</span>
            <span class="stat-label">Guardianes Activos</span>
          </div>
          <div class="stat-item cosmic-stat">
            <span class="stat-number">{{ formatNumber(welcomeData.hero.estadisticas.luz_recolectada) }}</span>
            <span class="stat-label">Luz Recolectada</span>
          </div>
          <div class="stat-item cosmic-stat">
            <span class="stat-number">{{ formatNumber(welcomeData.hero.estadisticas.misiones_completadas) }}</span>
            <span class="stat-label">Misiones Completadas</span>
          </div>
        </div>

        <!-- CTA Buttons -->
        <div class="cta-buttons">
          <Link href="/register" class="btn-primary cosmic-btn">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
            Comenzar Misión
          </Link>
          <a href="#features" class="btn-secondary cosmic-btn-outline">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            Explorar Academia
          </a>
        </div>
      </div>

      <!-- Scroll Indicator -->
      <div class="scroll-indicator cosmic-pulse">
        <div class="scroll-arrow"></div>
      </div>
    </section>

    <!-- Featured Content Section -->
    <section class="featured-section cosmic-section" id="features">
      <div class="section-container">
        <div class="section-header cosmic-header">
          <h2 class="section-title cosmic-title">Características de la Academia</h2>
          <p class="section-subtitle">Descubre las habilidades que desbloquearás en tu journey</p>
        </div>

        <div class="features-epic-grid">
          
          <!-- Característica 1: Diviértete con tus amigos -->
          <div class="feature-block feature-purple">
            <div class="feature-image-container">
              <div class="feature-placeholder cosmic-purple">
                <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M16 4c0-1.11.89-2 2-2s2 .89 2 2-.89 2-2 2-2-.89-2-2zm4 18v-6h2.5l-2.54-7.63A1.5 1.5 0 0 0 18.5 7h-3c-.83 0-1.54.5-1.85 1.22l-1.92 5.78c-.1.3-.23.59-.41.83L8.5 12c.83 0 1.5-.67 1.5-1.5S9.33 9 8.5 9 7 9.67 7 10.5 7.67 12 8.5 12l2.82 2.83c.18.24.31.53.41.83l1.92 5.78c.31.72 1.02 1.22 1.85 1.22h3c.83 0 1.5-.67 1.5-1.5z"/>
                </svg>
                <span class="feature-emoji">👥</span>
              </div>
            </div>
            <div class="feature-content">
              <h3 class="feature-title">✔️ Diviértete con tus amigos</h3>
              <p class="feature-description">Forma equipos legendarios y conquisten misiones juntos. El trabajo en equipo es la clave del éxito en Zenthoria.</p>
            </div>
          </div>

          <!-- Característica 2: Convierte tus clases en aventuras épicas -->
          <div class="feature-block feature-cyan">
            <div class="feature-content">
              <h3 class="feature-title">✔️ Convierte tus clases en aventuras épicas</h3>
              <p class="feature-description">Cada lección es una misión. Cada tarea, un desafío épico. Transforma el aprendizaje en una experiencia inolvidable.</p>
            </div>
            <div class="feature-image-container">
              <div class="feature-placeholder cosmic-cyan">
                <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
                <span class="feature-emoji">⚔️</span>
              </div>
            </div>
          </div>

          <!-- Característica 3: Personaliza tu avatar -->
          <div class="feature-block feature-gold">
            <div class="feature-image-container">
              <div class="feature-placeholder cosmic-gold">
                <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
                <span class="feature-emoji">🎭</span>
              </div>
            </div>
            <div class="feature-content">
              <h3 class="feature-title">✔️ Personaliza tu avatar</h3>
              <p class="feature-description">Crea tu Guardian único. Elige tu clase, armas, armaduras y habilidades especiales para destacar en el cosmos.</p>
            </div>
          </div>

          <!-- Característica 4: Crea tu clase ideal -->
          <div class="feature-block feature-blue">
            <div class="feature-content">
              <h3 class="feature-title">✔️ Crea tu clase ideal</h3>
              <p class="feature-description">Diseña cursos únicos con mecánicas de juego. Implementa sistemas de recompensas y desafíos personalizados.</p>
            </div>
            <div class="feature-image-container">
              <div class="feature-placeholder cosmic-blue">
                <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                </svg>
                <span class="feature-emoji">🎓</span>
              </div>
            </div>
          </div>

          <!-- Característica 5: Participa en batallas educativas -->
          <div class="feature-block feature-red">
            <div class="feature-image-container">
              <div class="feature-placeholder cosmic-red">
                <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
                <span class="feature-emoji">⚔️</span>
              </div>
            </div>
            <div class="feature-content">
              <h3 class="feature-title">✔️ Participa en batallas educativas</h3>
              <p class="feature-description">Enfréntate a otros guardianes en duelos de conocimiento. Raids épicos donde el saber es tu mejor arma.</p>
            </div>
          </div>

          <!-- Característica 6: Gana recompensas y sube de nivel -->
          <div class="feature-block feature-green">
            <div class="feature-content">
              <h3 class="feature-title">✔️ Gana recompensas y sube de nivel</h3>
              <p class="feature-description">Desbloquea equipo legendario, habilidades únicas y títulos épicos mientras progresas en tu journey académico.</p>
            </div>
            <div class="feature-image-container">
              <div class="feature-placeholder cosmic-green">
                <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M7 14c-1.66 0-3 1.34-3 3 0 1.31-1.16 2-2 2 .92 1.22 2.49 2 4 2 2.21 0 4-1.79 4-4 0-1.66-1.34-3-3-3zm13.71-9.37l-1.34-1.34c-.39-.39-1.02-.39-1.41 0L9 12.25 11.75 15l8.96-8.96c.39-.39.39-1.02 0-1.41z"/>
                </svg>
                <span class="feature-emoji">🏆</span>
              </div>
            </div>
          </div>

          <!-- Característica 7: ¡Sé el héroe de tu historia académica! -->
          <div class="feature-block feature-cosmic">
            <div class="feature-image-container">
              <div class="feature-placeholder cosmic-rainbow">
                <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M5 16L3 4l5.5 5L12 4l3.5 5L21 4l-2 12H5zm2.7-2h8.6l.9-5.4-2.1 1.7L12 8l-3.1 2.3-2.1-1.7L7.7 14z"/>
                </svg>
                <span class="feature-emoji">👑</span>
              </div>
            </div>
            <div class="feature-content">
              <h3 class="feature-title">✔️ ¡Sé el héroe de tu historia académica!</h3>
              <p class="feature-description">Forja tu leyenda en Zenthoria. Cada decisión, cada logro, cada victoria te acerca más a convertirte en el Guardian definitivo.</p>
            </div>
          </div>

        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="welcome-footer cosmic-terminal">
      <div class="footer-container">
        <div class="footer-grid cosmic-terminal-grid">
          <div class="footer-section cosmic-terminal-section">
            <div class="footer-logo cosmic-tower-logo">
              <div class="logo-emblem">
                <div class="w-8 h-8 bg-gradient-to-br from-purple-400 to-cyan-400 rounded-full flex items-center justify-center cosmic-glow">
                  <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2L2 7L12 12L22 7L12 2Z"/>
                    <path d="M2 17L12 22L22 17"/>
                    <path d="M2 12L12 17L22 12"/>
                  </svg>
                </div>
              </div>
              <div>
                <h3 class="destiny-title text-lg cosmic-brand">ZENTHORIA</h3>
                <span class="destiny-subtitle text-sm cosmic-tagline">GUARDIAN ACADEMY</span>
              </div>
            </div>
            <p class="footer-description cosmic-mission-statement">
              Transformando la educación a través de la luz del conocimiento y la sabiduría cósmica
            </p>
          </div>
        </div>

        <div class="footer-bottom cosmic-terminal-bottom">
          <div class="copyright-info">
            <p>&copy; 2025 Zenthoria Guardian Academy. Todos los derechos reservados en esta dimensión.</p>
            <p>Powered by Light Engine v3.0 + Cosmic Vue Framework</p>
          </div>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { ref, onMounted, nextTick } from 'vue'
import { Head, Link } from '@inertiajs/vue3'

// Estado reactivo para Zenthoria
const welcomeData = ref(null)
const loading = ref(true)

// Datos de demo para Zenthoria
const initDemoData = () => {
  welcomeData.value = {
    hero: {
      titulo: '👑 Bienvenido a Zenthoria',
      subtitulo: 'Donde el conocimiento se convierte en poder.',
      descripcion: 'Embárcate en una aventura de aprendizaje como nunca antes. Lucha junto a tus compañeros, completa misiones, sube de nivel, y desbloquea habilidades únicas mientras dominas cada clase. Zenthoria es más que una plataforma educativa... Es tu campo de entrenamiento intergaláctico.',
      imagen_fallback: '/images/cosmic-hero-fallback.jpg',
      estadisticas: {
        guardianes_activos: 15750,
        luz_recolectada: 3650000,
        misiones_completadas: 89400
      }
    }
  }

  loading.value = false
}

// Métodos utilitarios para Zenthoria
const formatNumber = (num) => {
  if (num >= 1000000) {
    return `${(num / 1000000).toFixed(1)}M`
  }
  if (num >= 1000) {
    return `${(num / 1000).toFixed(1)}K`
  }
  return num.toString()
}

// Lifecycle
onMounted(() => {
  initDemoData()
})
</script>

<style scoped>
/* Variables CSS mejoradas */
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
  
  /* Fondos más dramáticos */
  --cosmic-dark: #0B0B1A;
  --cosmic-darker: #050511;
  --cosmic-panel: #1A1B2E;
  --cosmic-panel-light: #262B47;
  
  /* Textos más contrastantes */
  --cosmic-text: #F8FAFC;
  --cosmic-text-secondary: #E2E8F0;
  --cosmic-text-muted: #94A3B8;
  --cosmic-text-disabled: #64748B;
  
  /* Efectos y glows */
  --cosmic-glow: #A855F7;
  --cosmic-shadow: rgba(0, 0, 0, 0.8);
  --cosmic-shadow-heavy: rgba(0, 0, 0, 0.9);
  
  /* Bordes y separadores */
  --cosmic-border: rgba(139, 92, 246, 0.2);
  --cosmic-border-bright: rgba(139, 92, 246, 0.5);
}

/* Contenedor principal mejorado - FONDO OSCURO */
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

/* Fondo cósmico mejorado */
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
  background-size: 
    800px 800px,
    600px 600px,
    400px 400px;
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
  animation: particles-float 30s linear infinite;
  z-index: -1;
}

@keyframes cosmic-drift {
  0%, 100% { 
    transform: translateY(0px) rotate(0deg); 
    filter: hue-rotate(0deg);
  }
  33% { 
    transform: translateY(-15px) rotate(1deg); 
    filter: hue-rotate(10deg);
  }
  66% { 
    transform: translateY(-25px) rotate(-1deg); 
    filter: hue-rotate(-5deg);
  }
}

@keyframes particles-float {
  0% { transform: translateY(0px) translateX(0px); }
  100% { transform: translateY(-100vh) translateX(50px); }
}

/* Navegación mejorada */
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

.nav-links {
  display: flex;
  gap: 2.5rem;
  align-items: center;
}

.nav-link {
  color: var(--cosmic-text-muted);
  text-decoration: none;
  font-weight: 600;
  font-size: 0.95rem;
  transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
  position: relative;
  padding: 0.5rem 0;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.nav-link:hover {
  color: var(--cosmic-text);
  text-decoration: none;
  text-shadow: 0 0 15px rgba(124, 58, 237, 0.8);
  transform: translateY(-2px);
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
  box-shadow: 
    0 4px 15px rgba(124, 58, 237, 0.3),
    inset 0 1px 0 rgba(255, 255, 255, 0.1);
}

.destiny-btn-cosmic:hover {
  transform: translateY(-3px) scale(1.02);
  box-shadow: 
    0 8px 25px rgba(124, 58, 237, 0.5),
    0 0 20px rgba(124, 58, 237, 0.4),
    inset 0 1px 0 rgba(255, 255, 255, 0.2);
  text-decoration: none;
  color: var(--cosmic-text);
  background: linear-gradient(135deg, var(--cosmic-purple-light), var(--cosmic-purple));
}

/* Hero section épica mejorada */
.hero-section {
  height: 100vh;
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
}

.hero-background {
  position: absolute;
  inset: 0;
  z-index: -1;
}

.hero-video,
.hero-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.cosmic-bg {
  background: 
    radial-gradient(ellipse at 30% 20%, rgba(124, 58, 237, 0.2) 0%, transparent 40%),
    radial-gradient(ellipse at 70% 80%, rgba(14, 165, 233, 0.15) 0%, transparent 40%),
    radial-gradient(ellipse at center, rgba(245, 158, 11, 0.08) 0%, transparent 50%),
    linear-gradient(135deg, var(--cosmic-dark) 0%, var(--cosmic-darker) 100%);
}

.hero-content {
  text-align: center;
  max-width: 1000px;
  padding: 3rem 2rem;
  position: relative;
  z-index: 2;
}

.crown-icon {
  margin-bottom: 2.5rem;
  animation: cosmic-float 4s ease-in-out infinite;
  filter: drop-shadow(0 0 30px rgba(124, 58, 237, 0.8));
}

@keyframes cosmic-float {
  0%, 100% { 
    transform: translateY(0px) rotate(0deg); 
    filter: drop-shadow(0 0 30px rgba(124, 58, 237, 0.8));
  }
  50% { 
    transform: translateY(-15px) rotate(2deg); 
    filter: drop-shadow(0 0 40px rgba(14, 165, 233, 0.9));
  }
}

.hero-title {
  font-size: clamp(3.5rem, 8vw, 6rem);
  font-weight: 900;
  color: #FFFFFF;
  margin-bottom: 1.5rem;
  text-shadow: 
    0 0 30px rgba(124, 58, 237, 0.8),
    0 0 60px rgba(14, 165, 233, 0.4),
    0 4px 8px rgba(0, 0, 0, 0.9);
  background: linear-gradient(135deg, 
    #FFFFFF 0%, 
    #A855F7 25%, 
    #38BDF8 50%, 
    #A855F7 75%, 
    #FFFFFF 100%);
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
  font-size: clamp(1.25rem, 3vw, 2rem);
  color: #38BDF8;
  margin-bottom: 2rem;
  font-weight: 600;
  text-align: center;
  text-shadow: 
    0 0 25px rgba(14, 165, 233, 0.8),
    0 2px 4px rgba(0, 0, 0, 0.8);
  font-family: 'Inter', system-ui, sans-serif;
  letter-spacing: 0.02em;
}

.hero-description {
  font-size: clamp(1.1rem, 2vw, 1.3rem);
  color: #E2E8F0;
  margin-bottom: 3.5rem;
  line-height: 1.8;
  max-width: 900px;
  margin-left: auto;
  margin-right: auto;
  text-align: center;
  text-shadow: 0 2px 4px rgba(0, 0, 0, 0.8);
  font-weight: 400;
  letter-spacing: 0.01em;
}

@keyframes cosmic-glow {
  0% { 
    filter: drop-shadow(0 0 30px rgba(124, 58, 237, 0.6)) brightness(1);
    text-shadow: 
      0 0 30px rgba(124, 58, 237, 0.8),
      0 0 60px rgba(14, 165, 233, 0.4),
      0 4px 8px rgba(0, 0, 0, 0.9);
  }
  100% { 
    filter: drop-shadow(0 0 50px rgba(14, 165, 233, 0.8)) brightness(1.1);
    text-shadow: 
      0 0 40px rgba(14, 165, 233, 0.9),
      0 0 80px rgba(124, 58, 237, 0.5),
      0 4px 8px rgba(0, 0, 0, 0.9);
  }
}

/* Stats mejoradas */
.hero-stats {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 2rem;
  margin: 4rem 0;
  max-width: 700px;
  margin-left: auto;
  margin-right: auto;
}

.stat-item {
  text-align: center;
  padding: 2rem 1.5rem;
  border-radius: 16px;
  backdrop-filter: blur(20px) saturate(180%);
  position: relative;
  overflow: hidden;
}

.cosmic-stat {
  background: rgba(26, 27, 46, 0.9);
  border: 1px solid rgba(139, 92, 246, 0.5);
  transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
  position: relative;
}

.cosmic-stat:hover {
  border-color: #38BDF8;
  box-shadow: 
    0 0 30px rgba(14, 165, 233, 0.4),
    0 10px 40px rgba(0, 0, 0, 0.8);
  transform: translateY(-8px) scale(1.02);
}

.stat-number {
  font-size: clamp(2rem, 4vw, 3rem);
  font-weight: 800;
  color: #A855F7;
  display: block;
  margin-bottom: 0.75rem;
  font-family: 'Orbitron', monospace;
  text-shadow: 0 0 20px rgba(124, 58, 237, 0.6);
}

.stat-label {
  color: #94A3B8;
  font-size: 0.9rem;
  text-transform: uppercase;
  letter-spacing: 0.1em;
  font-weight: 600;
  line-height: 1.4;
}

/* Botones CTA mejorados */
.cta-buttons {
  display: flex;
  gap: 2rem;
  justify-content: center;
  flex-wrap: wrap;
  margin-top: 4rem;
}

.btn-primary,
.btn-secondary {
  padding: 1.25rem 2.5rem;
  border-radius: 16px;
  font-weight: 700;
  font-size: 1.125rem;
  cursor: pointer;
  transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 0.75rem;
  font-family: 'Inter', system-ui, sans-serif;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  border: 2px solid transparent;
  position: relative;
  overflow: hidden;
  min-width: 200px;
  justify-content: center;
}

.cosmic-btn {
  background: linear-gradient(135deg, var(--cosmic-purple), var(--cosmic-purple-light));
  color: var(--cosmic-text);
  border-color: var(--cosmic-purple-light);
  box-shadow: 
    0 8px 32px rgba(124, 58, 237, 0.4),
    inset 0 1px 0 rgba(255, 255, 255, 0.1),
    0 0 0 1px rgba(124, 58, 237, 0.2);
}

.cosmic-btn:hover {
  transform: translateY(-4px) scale(1.05);
  box-shadow: 
    0 12px 40px rgba(124, 58, 237, 0.6),
    0 0 30px rgba(124, 58, 237, 0.5),
    inset 0 1px 0 rgba(255, 255, 255, 0.2),
    0 0 0 1px rgba(124, 58, 237, 0.4);
  text-decoration: none;
  color: var(--cosmic-text);
  background: linear-gradient(135deg, var(--cosmic-purple-light), var(--cosmic-cyan));
}

.cosmic-btn-outline {
  background: rgba(26, 27, 46, 0.6);
  border: 2px solid var(--cosmic-cyan);
  backdrop-filter: blur(20px) saturate(180%);
  box-shadow: 
    0 8px 32px rgba(14, 165, 233, 0.3),
    inset 0 1px 0 rgba(255, 255, 255, 0.05);
  color: var(--cosmic-text);
}

.cosmic-btn-outline:hover {
  background: rgba(14, 165, 233, 0.15);
  border-color: var(--cosmic-cyan-light);
  box-shadow: 
    0 12px 40px rgba(14, 165, 233, 0.5),
    0 0 30px rgba(14, 165, 233, 0.4),
    inset 0 1px 0 rgba(255, 255, 255, 0.1);
  transform: translateY(-4px) scale(1.05);
  text-decoration: none;
  color: var(--cosmic-text);
}

.scroll-indicator {
  position: absolute;
  bottom: 3rem;
  left: 50%;
  transform: translateX(-50%);
  animation: bounce 2s infinite;
}

.cosmic-pulse {
  animation: cosmic-pulse 3s infinite;
}

.scroll-arrow {
  width: 3px;
  height: 40px;
  background: linear-gradient(180deg, var(--cosmic-cyan), transparent);
  position: relative;
  border-radius: 2px;
}

.scroll-arrow::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: -4px;
  width: 0;
  height: 0;
  border-left: 5px solid transparent;
  border-right: 5px solid transparent;
  border-top: 10px solid var(--cosmic-cyan);
  filter: drop-shadow(0 0 10px rgba(14, 165, 233, 0.8));
}

@keyframes bounce {
  0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
  40% { transform: translateY(-12px); }
  60% { transform: translateY(-6px); }
}

@keyframes cosmic-pulse {
  0%, 100% { 
    opacity: 1; 
    filter: drop-shadow(0 0 20px rgba(14, 165, 233, 0.8));
  }
  50% { 
    opacity: 0.6; 
    filter: drop-shadow(0 0 30px rgba(124, 58, 237, 0.9));
  }
}

/* Secciones mejoradas */
.section-container {
  max-width: 1400px;
  margin: 0 auto;
  padding: 8rem 2rem;
}

.section-header {
  text-align: center;
  margin-bottom: 5rem;
}

.section-title {
  font-size: clamp(2.5rem, 5vw, 3.5rem);
  font-weight: 800;
  color: #FFFFFF;
  margin-bottom: 1.5rem;
  font-family: 'Orbitron', monospace;
  text-transform: uppercase;
  letter-spacing: 0.02em;
}

.cosmic-title {
  background: linear-gradient(135deg, 
    #A855F7 0%, 
    #38BDF8 50%, 
    #A855F7 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  text-shadow: 0 0 40px rgba(124, 58, 237, 0.5);
  position: relative;
}

.section-subtitle {
  color: #94A3B8;
  font-size: clamp(1rem, 2vw, 1.25rem);
  max-width: 700px;
  margin: 0 auto;
  line-height: 1.7;
  font-weight: 400;
}

.cosmic-section {
  background: 
    radial-gradient(ellipse at top, rgba(26, 27, 46, 0.4) 0%, transparent 50%),
    linear-gradient(135deg, rgba(26, 27, 46, 0.2), rgba(11, 11, 26, 0.3));
  backdrop-filter: blur(10px) saturate(180%);
  border-top: 1px solid var(--cosmic-border);
  border-bottom: 1px solid var(--cosmic-border);
  position: relative;
}

/* Feature blocks épicos mejorados */
.features-epic-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 4rem;
  max-width: 1300px;
  margin: 0 auto;
}

.feature-block {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 3rem;
  align-items: center;
  background: 
    linear-gradient(135deg, rgba(26, 27, 46, 0.6) 0%, rgba(26, 27, 46, 0.4) 100%);
  border-radius: 24px;
  padding: 3rem;
  border: 1px solid var(--cosmic-border);
  backdrop-filter: blur(20px) saturate(180%);
  transition: all 0.5s cubic-bezier(0.25, 0.8, 0.25, 1);
  position: relative;
  overflow: hidden;
}

.feature-block:nth-child(even) {
  direction: rtl;
}

.feature-block:nth-child(even) .feature-content {
  direction: ltr;
}

.feature-block:hover {
  transform: translateY(-8px) scale(1.02);
  border-color: var(--cosmic-border-bright);
  box-shadow: 
    0 25px 50px var(--cosmic-shadow),
    0 0 50px rgba(124, 58, 237, 0.2);
}

/* Bordes coloridos para cada feature */
.feature-purple { 
  border-left: 4px solid var(--cosmic-purple-light);
  box-shadow: 0 10px 30px rgba(124, 58, 237, 0.1);
}

.feature-cyan { 
  border-left: 4px solid var(--cosmic-cyan-light);
  box-shadow: 0 10px 30px rgba(14, 165, 233, 0.1);
}

.feature-gold { 
  border-left: 4px solid var(--cosmic-gold);
  box-shadow: 0 10px 30px rgba(245, 158, 11, 0.1);
}

.feature-blue { 
  border-left: 4px solid var(--cosmic-blue);
  box-shadow: 0 10px 30px rgba(59, 130, 246, 0.1);
}

.feature-red { 
  border-left: 4px solid var(--cosmic-red);
  box-shadow: 0 10px 30px rgba(239, 68, 68, 0.1);
}

.feature-green { 
  border-left: 4px solid var(--cosmic-green);
  box-shadow: 0 10px 30px rgba(16, 185, 129, 0.1);
}

.feature-cosmic { 
  border-left: 4px solid var(--cosmic-glow);
  box-shadow: 0 10px 30px rgba(168, 85, 247, 0.1);
}

.feature-image-container {
  position: relative;
}

.feature-placeholder {
  width: 100%;
  height: 280px;
  border-radius: 20px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  color: var(--cosmic-text);
  font-weight: 700;
  font-size: 2rem;
  position: relative;
  overflow: hidden;
  transition: all 0.4s ease;
  box-shadow: 
    inset 0 2px 0 rgba(255, 255, 255, 0.1),
    0 10px 30px rgba(0, 0, 0, 0.3);
}

/* Gradientes mejorados para placeholders */
.cosmic-purple { 
  background: linear-gradient(135deg, var(--cosmic-purple) 0%, var(--cosmic-purple-light) 100%);
  box-shadow: 0 0 30px rgba(124, 58, 237, 0.4);
}

.cosmic-cyan { 
  background: linear-gradient(135deg, var(--cosmic-cyan) 0%, var(--cosmic-cyan-light) 100%);
  box-shadow: 0 0 30px rgba(14, 165, 233, 0.4);
}

.cosmic-gold { 
  background: linear-gradient(135deg, var(--cosmic-gold) 0%, var(--cosmic-gold-light) 100%);
  box-shadow: 0 0 30px rgba(245, 158, 11, 0.4);
}

.cosmic-blue { 
  background: linear-gradient(135deg, var(--cosmic-blue) 0%, #60A5FA 100%);
  box-shadow: 0 0 30px rgba(59, 130, 246, 0.4);
}

.cosmic-red { 
  background: linear-gradient(135deg, var(--cosmic-red) 0%, #F87171 100%);
  box-shadow: 0 0 30px rgba(239, 68, 68, 0.4);
}

.cosmic-green { 
  background: linear-gradient(135deg, var(--cosmic-green) 0%, #34D399 100%);
  box-shadow: 0 0 30px rgba(16, 185, 129, 0.4);
}

.cosmic-rainbow { 
  background: linear-gradient(135deg, 
    var(--cosmic-purple) 0%, 
    var(--cosmic-cyan) 33%, 
    var(--cosmic-gold) 66%, 
    var(--cosmic-purple) 100%);
  background-size: 200% 200%;
  animation: rainbow-flow 4s ease-in-out infinite;
  box-shadow: 0 0 40px rgba(168, 85, 247, 0.5);
}

@keyframes rainbow-flow {
  0%, 100% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
}

.feature-emoji {
  font-size: 4rem;
  margin-top: 1.5rem;
  filter: drop-shadow(0 0 20px rgba(0, 0, 0, 0.5));
  animation: emoji-glow 3s ease-in-out infinite alternate;
}

@keyframes emoji-glow {
  0% { transform: scale(1) rotate(0deg); }
  100% { transform: scale(1.1) rotate(5deg); }
}

.feature-content {
  padding: 1rem 0;
}

.feature-title {
  font-size: clamp(1.5rem, 3vw, 2rem);
  font-weight: 800;
  color: #FFFFFF;
  margin-bottom: 1.5rem;
  font-family: 'Orbitron', monospace;
  line-height: 1.3;
  text-shadow: 0 2px 4px rgba(0, 0, 0, 0.8);
}

.feature-description {
  color: #E2E8F0;
  line-height: 1.8;
  font-size: 1.1rem;
  font-weight: 400;
  margin: 0;
}

/* Footer épico mejorado */
.welcome-footer {
  background: 
    linear-gradient(135deg, rgba(11, 11, 26, 0.98) 0%, rgba(5, 5, 17, 0.99) 100%);
  border-top: 1px solid var(--cosmic-border-bright);
  backdrop-filter: blur(20px) saturate(180%);
  position: relative;
}

.cosmic-terminal {
  backdrop-filter: blur(20px) saturate(180%);
}

.footer-container {
  max-width: 1400px;
  margin: 0 auto;
  padding: 4rem 2rem 2rem;
}

.footer-grid {
  display: grid;
  grid-template-columns: 2fr 1fr 1fr 1fr;
  gap: 3rem;
  margin-bottom: 3rem;
}

.footer-section {
  display: flex;
  flex-direction: column;
}

.footer-logo {
  display: flex;
  align-items: center;
  gap: 1rem;
  margin-bottom: 1.5rem;
  transition: all 0.3s ease;
}

.cosmic-brand {
  color: var(--cosmic-purple-light);
  font-family: 'Orbitron', monospace;
  font-weight: 800;
  text-shadow: 0 0 15px rgba(124, 58, 237, 0.8);
  margin: 0;
  font-size: 1.25rem;
}

.cosmic-tagline {
  color: var(--cosmic-cyan-light);
  font-family: 'Orbitron', monospace;
  font-weight: 500;
  font-size: 0.75rem;
  text-transform: uppercase;
  letter-spacing: 0.1em;
}

.footer-description {
  color: var(--cosmic-text-muted);
  line-height: 1.7;
  max-width: 350px;
  font-size: 1rem;
}

.footer-bottom {
  text-align: center;
  padding-top: 2rem;
  border-top: 1px solid var(--cosmic-border);
  color: var(--cosmic-text-muted);
  font-size: 0.9rem;
}

.footer-bottom p {
  margin: 0.5rem 0;
  line-height: 1.6;
}

/* Responsive Design mejorado */
@media (max-width: 1200px) {
  .nav-links {
    display: none;
  }
  
  .footer-grid {
    grid-template-columns: 2fr 1fr 1fr;
  }
}

@media (max-width: 768px) {
  .nav-container {
    padding: 1rem;
    flex-direction: column;
    gap: 1rem;
  }
  
  .nav-auth {
    flex-direction: row;
    gap: 1rem;
    width: 100%;
    justify-content: center;
  }
  
  .hero-content {
    padding: 2rem 1rem;
  }
  
  .hero-stats {
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
    margin: 2.5rem 0;
  }
  
  .cta-buttons {
    flex-direction: column;
    align-items: center;
    gap: 1rem;
    margin-top: 2.5rem;
  }
  
  .btn-primary,
  .btn-secondary {
    width: 100%;
    max-width: 280px;
    padding: 1rem 2rem;
    font-size: 1rem;
  }
  
  .section-container {
    padding: 5rem 1rem;
  }
  
  .features-epic-grid {
    gap: 3rem;
  }
  
  .feature-block {
    grid-template-columns: 1fr;
    text-align: center;
    padding: 2rem 1.5rem;
    gap: 2rem;
  }
  
  .feature-block:nth-child(even) {
    direction: ltr;
  }
  
  .feature-placeholder {
    height: 220px;
    margin: 0 auto;
  }
  
  .footer-grid {
    grid-template-columns: 1fr;
    gap: 2rem;
    text-align: center;
  }
  
  .footer-logo {
    justify-content: center;
  }
  
  .footer-description {
    max-width: none;
    margin: 0 auto;
  }
}

@media (max-width: 480px) {
  .hero-stats {
    grid-template-columns: 1fr;
    gap: 1rem;
    margin: 2rem 0;
  }
  
  .feature-block {
    padding: 1.5rem 1rem;
    border-radius: 16px;
  }
  
  .feature-placeholder {
    height: 180px;
  }
  
  .footer-container {
    padding: 3rem 1rem 1.5rem;
  }
}
</style>