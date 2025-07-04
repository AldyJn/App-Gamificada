<template>
  <Head title="Mi Perfil - Estudiante" />

  <div class="cosmic-container">
    <!-- Fondo animado -->
    <div class="cosmic-background"></div>

    <!-- Contenido principal -->
    <div class="perfil-container">
      
      <!-- Header del perfil -->
      <div class="perfil-header">
        <div class="avatar-section">
          <div class="avatar-container">
            <img 
              v-if="usuario.avatar_url" 
              :src="usuario.avatar_url" 
              :alt="usuario.nombre"
              class="avatar-img"
            />
            <div v-else class="avatar-placeholder">
              {{ iniciales }}
            </div>
          </div>
          <button class="change-avatar-btn">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
          </button>
        </div>

        <div class="user-info">
          <h1 class="user-name">{{ usuario.nombre }} {{ usuario.apellido }}</h1>
          <p class="user-email">{{ usuario.correo }}</p>
          <div class="user-badges">
            <span class="badge badge-student">
              <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
              </svg>
              Estudiante
            </span>
            <span class="badge badge-code">
              ID: {{ estudiante.codigo_estudiante }}
            </span>
          </div>
        </div>
      </div>

      <!-- Navegación de tabs -->
      <div class="tabs-navigation">
        <button 
          v-for="tab in tabs" 
          :key="tab.id"
          @click="activeTab = tab.id"
          :class="['tab-btn', { 'active': activeTab === tab.id }]"
        >
          <component :is="tab.icon" class="w-5 h-5 mr-2" />
          {{ tab.label }}
        </button>
      </div>

      <!-- Contenido de tabs -->
      <div class="tab-content">
        
        <!-- Tab: Información General -->
        <div v-if="activeTab === 'general'" class="tab-panel">
          <div class="info-grid">
            <div class="info-card">
              <h3 class="info-title">Información Personal</h3>
              <div class="info-items">
                <div class="info-item">
                  <span class="info-label">Nombre Completo:</span>
                  <span class="info-value">{{ usuario.nombre }} {{ usuario.apellido }}</span>
                </div>
                <div class="info-item">
                  <span class="info-label">Correo Electrónico:</span>
                  <span class="info-value">{{ usuario.correo }}</span>
                </div>
                <div class="info-item">
                  <span class="info-label">Código de Estudiante:</span>
                  <span class="info-value">{{ estudiante.codigo_estudiante }}</span>
                </div>
                <div class="info-item">
                  <span class="info-label">Grado:</span>
                  <span class="info-value">{{ estudiante.grado }}</span>
                </div>
                <div class="info-item">
                  <span class="info-label">Sección:</span>
                  <span class="info-value">{{ estudiante.seccion }}</span>
                </div>
              </div>
            </div>

            <div class="info-card">
              <h3 class="info-title">Estadísticas</h3>
              <div class="stats-grid">
                <div class="stat-item">
                  <div class="stat-number">{{ estadisticas.clases_inscritas }}</div>
                  <div class="stat-label">Clases</div>
                </div>
                <div class="stat-item">
                  <div class="stat-number">{{ estadisticas.actividades_completadas }}</div>
                  <div class="stat-label">Actividades</div>
                </div>
                <div class="stat-item">
                  <div class="stat-number">{{ estadisticas.nivel_promedio }}</div>
                  <div class="stat-label">Nivel</div>
                </div>
                <div class="stat-item">
                  <div class="stat-number">{{ estadisticas.experiencia_total }}</div>
                  <div class="stat-label">XP Total</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Tab: Actividad -->
        <div v-if="activeTab === 'actividad'" class="tab-panel">
          <div class="activity-section">
            <h3 class="section-title">Actividad Reciente</h3>
            <div class="activity-placeholder">
              <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
              </svg>
              <p class="text-gray-400">No hay actividad reciente que mostrar</p>
              <p class="text-sm text-gray-500 mt-2">Las actividades aparecerán aquí cuando completes tareas</p>
            </div>
          </div>
        </div>

        <!-- Tab: Logros -->
        <div v-if="activeTab === 'logros'" class="tab-panel">
          <div class="achievements-section">
            <h3 class="section-title">Logros y Medallas</h3>
            <div class="achievements-placeholder">
              <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
              </svg>
              <p class="text-gray-400">Aún no tienes logros</p>
              <p class="text-sm text-gray-500 mt-2">Completa actividades para ganar medallas y logros</p>
            </div>
          </div>
        </div>

        <!-- Tab: Configuración -->
        <div v-if="activeTab === 'configuracion'" class="tab-panel">
          <div class="config-section">
            <h3 class="section-title">Configuración de Perfil</h3>
            
            <div class="config-card">
              <h4 class="config-subtitle">Preferencias de Notificaciones</h4>
              <div class="config-options">
                <label class="config-option">
                  <input type="checkbox" checked />
                  <span>Recibir notificaciones de nuevas actividades</span>
                </label>
                <label class="config-option">
                  <input type="checkbox" checked />
                  <span>Notificar cuando se publiquen calificaciones</span>
                </label>
                <label class="config-option">
                  <input type="checkbox" />
                  <span>Recordatorios de fechas límite</span>
                </label>
              </div>
            </div>

            <div class="config-card">
              <h4 class="config-subtitle">Privacidad</h4>
              <div class="config-options">
                <label class="config-option">
                  <input type="checkbox" checked />
                  <span>Perfil visible para compañeros de clase</span>
                </label>
                <label class="config-option">
                  <input type="checkbox" />
                  <span>Mostrar estadísticas en ranking</span>
                </label>
              </div>
            </div>
          </div>
        </div>

      </div>

      <!-- Acciones del perfil -->
      <div class="profile-actions">
        <Link :href="route('dashboard')" class="action-btn action-btn-secondary">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
          </svg>
          Volver al Dashboard
        </Link>
        
        <button @click="guardarCambios" class="action-btn action-btn-primary">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
          </svg>
          Guardar Cambios
        </button>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Head, Link } from '@inertiajs/vue3'

// Props
const props = defineProps({
  usuario: Object,
  estudiante: Object,
  estadisticas: Object,
  clases_recientes: Array,
  logros_recientes: Array
})

// Estado del componente
const activeTab = ref('general')

// Tabs disponibles
const tabs = [
  {
    id: 'general',
    label: 'General',
    icon: 'svg' // Placeholder, usaremos SVG inline
  },
  {
    id: 'actividad',
    label: 'Actividad',
    icon: 'svg'
  },
  {
    id: 'logros',
    label: 'Logros',
    icon: 'svg'
  },
  {
    id: 'configuracion',
    label: 'Configuración',
    icon: 'svg'
  }
]

// Computed
const iniciales = computed(() => {
  const nombre = props.usuario.nombre || ''
  const apellido = props.usuario.apellido || ''
  return (nombre.charAt(0) + apellido.charAt(0)).toUpperCase()
})

// Métodos
const guardarCambios = () => {
  // Placeholder - implementar guardado
  alert('Función de guardado en desarrollo')
}
</script>

<style scoped>
.cosmic-container {
  min-height: 100vh;
  background: linear-gradient(135deg, #0a0b13 0%, #1e2139 50%, #0f1419 100%);
  padding: 2rem;
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

.perfil-container {
  max-width: 1200px;
  margin: 0 auto;
  background: rgba(15, 20, 25, 0.8);
  backdrop-filter: blur(20px);
  border: 1px solid rgba(56, 189, 248, 0.2);
  border-radius: 1rem;
  overflow: hidden;
}

.perfil-header {
  display: flex;
  align-items: center;
  padding: 2rem;
  background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(147, 51, 234, 0.1));
  border-bottom: 1px solid rgba(75, 85, 99, 0.3);
}

.avatar-section {
  position: relative;
  margin-right: 2rem;
}

.avatar-container {
  width: 120px;
  height: 120px;
  border-radius: 50%;
  overflow: hidden;
  border: 4px solid rgba(56, 189, 248, 0.5);
}

.avatar-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.avatar-placeholder {
  width: 100%;
  height: 100%;
  background: linear-gradient(135deg, #3b82f6, #8b5cf6);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 2rem;
  font-weight: bold;
  color: white;
}

.change-avatar-btn {
  position: absolute;
  bottom: 0;
  right: 0;
  background: rgba(59, 130, 246, 0.9);
  border: none;
  border-radius: 50%;
  width: 40px;
  height: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  cursor: pointer;
  transition: background 0.3s ease;
}

.change-avatar-btn:hover {
  background: rgba(59, 130, 246, 1);
}

.user-info {
  flex: 1;
}

.user-name {
  font-size: 2rem;
  font-weight: bold;
  color: white;
  margin-bottom: 0.5rem;
}

.user-email {
  color: rgba(156, 163, 175, 1);
  margin-bottom: 1rem;
}

.user-badges {
  display: flex;
  gap: 0.5rem;
}

.badge {
  display: inline-flex;
  align-items: center;
  padding: 0.25rem 0.75rem;
  border-radius: 9999px;
  font-size: 0.875rem;
  font-weight: 500;
}

.badge-student {
  background: rgba(34, 197, 94, 0.2);
  color: rgba(34, 197, 94, 1);
  border: 1px solid rgba(34, 197, 94, 0.3);
}

.badge-code {
  background: rgba(156, 163, 175, 0.2);
  color: rgba(156, 163, 175, 1);
  border: 1px solid rgba(156, 163, 175, 0.3);
}

.tabs-navigation {
  display: flex;
  background: rgba(31, 41, 55, 0.5);
  border-bottom: 1px solid rgba(75, 85, 99, 0.3);
}

.tab-btn {
  display: flex;
  align-items: center;
  padding: 1rem 1.5rem;
  background: none;
  border: none;
  color: rgba(156, 163, 175, 1);
  cursor: pointer;
  transition: all 0.3s ease;
  border-bottom: 3px solid transparent;
}

.tab-btn:hover {
  background: rgba(75, 85, 99, 0.3);
  color: white;
}

.tab-btn.active {
  color: rgba(56, 189, 248, 1);
  background: rgba(56, 189, 248, 0.1);
  border-bottom-color: rgba(56, 189, 248, 1);
}

.tab-content {
  padding: 2rem;
}

.tab-panel {
  animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

.info-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 2rem;
}

.info-card {
  background: rgba(31, 41, 55, 0.5);
  border: 1px solid rgba(75, 85, 99, 0.3);
  border-radius: 0.5rem;
  padding: 1.5rem;
}

.info-title {
  font-size: 1.25rem;
  font-weight: 600;
  color: white;
  margin-bottom: 1rem;
}

.info-items {
  space-y: 1rem;
}

.info-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.75rem 0;
  border-bottom: 1px solid rgba(75, 85, 99, 0.3);
}

.info-item:last-child {
  border-bottom: none;
}

.info-label {
  color: rgba(156, 163, 175, 1);
  font-weight: 500;
}

.info-value {
  color: white;
  font-weight: 600;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 1rem;
}

.stat-item {
  text-align: center;
  padding: 1rem;
  background: rgba(59, 130, 246, 0.1);
  border-radius: 0.5rem;
  border: 1px solid rgba(59, 130, 246, 0.3);
}

.stat-number {
  font-size: 2rem;
  font-weight: bold;
  color: rgba(56, 189, 248, 1);
}

.stat-label {
  color: rgba(156, 163, 175, 1);
  font-size: 0.875rem;
  margin-top: 0.25rem;
}

.activity-placeholder,
.achievements-placeholder {
  text-align: center;
  padding: 3rem;
  background: rgba(31, 41, 55, 0.3);
  border-radius: 0.5rem;
  border: 2px dashed rgba(75, 85, 99, 0.5);
}

.section-title {
  font-size: 1.5rem;
  font-weight: 600;
  color: white;
  margin-bottom: 1.5rem;
}

.config-section {
  max-width: 600px;
}

.config-card {
  background: rgba(31, 41, 55, 0.5);
  border: 1px solid rgba(75, 85, 99, 0.3);
  border-radius: 0.5rem;
  padding: 1.5rem;
  margin-bottom: 1.5rem;
}

.config-subtitle {
  font-size: 1.125rem;
  font-weight: 600;
  color: white;
  margin-bottom: 1rem;
}

.config-options {
  space-y: 0.75rem;
}

.config-option {
  display: flex;
  align-items: center;
  color: rgba(209, 213, 219, 1);
  cursor: pointer;
}

.config-option input {
  margin-right: 0.75rem;
  accent-color: rgba(56, 189, 248, 1);
}

.profile-actions {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1.5rem 2rem;
  background: rgba(31, 41, 55, 0.5);
  border-top: 1px solid rgba(75, 85, 99, 0.3);
}

.action-btn {
  display: inline-flex;
  align-items: center;
  padding: 0.75rem 1.5rem;
  border-radius: 0.5rem;
  font-weight: 600;
  text-decoration: none;
  transition: all 0.3s ease;
  border: none;
  cursor: pointer;
}

.action-btn-primary {
  background: linear-gradient(135deg, #3b82f6, #1d4ed8);
  color: white;
}

.action-btn-primary:hover {
  background: linear-gradient(135deg, #2563eb, #1e40af);
  transform: translateY(-1px);
  box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.4);
}

.action-btn-secondary {
  background: rgba(75, 85, 99, 0.8);
  color: white;
  border: 1px solid rgba(156, 163, 175, 0.5);
}

.action-btn-secondary:hover {
  background: rgba(107, 114, 128, 0.8);
  transform: translateY(-1px);
}

@media (max-width: 768px) {
  .perfil-header {
    flex-direction: column;
    text-align: center;
  }
  
  .avatar-section {
    margin-right: 0;
    margin-bottom: 1rem;
  }
  
  .info-grid {
    grid-template-columns: 1fr;
  }
  
  .tabs-navigation {
    flex-wrap: wrap;
  }
  
  .tab-btn {
    flex: 1;
    min-width: 120px;
  }
  
  .profile-actions {
    flex-direction: column;
    gap: 1rem;
  }
  
  .action-btn {
    width: 100%;
    justify-content: center;
  }
}
</style>
