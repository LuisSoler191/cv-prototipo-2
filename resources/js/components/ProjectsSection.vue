<template>
  <section id="projects" class="projects">
    <div class="container">
      <div class="section-header">
        <p class="section-label">Lo último</p>
        <h2 class="section-title">Mis Proyectos</h2>
        <p class="section-subtitle">Soluciones que transforman ideas en realidad</p>
      </div>

      <div class="slider-container">
        <button class="slider-nav left" aria-label="Anterior" @click="slide(-1)">&#8249;</button>

        <div class="projects-slider" ref="sliderRef">
          <div
            v-for="project in projects"
            :key="project.title"
            class="project-card glass"
            role="button"
            tabindex="0"
            @click="openModal(project)"
            @keydown.enter.prevent="openModal(project)"
            @keydown.space.prevent="openModal(project)"
          >
            <div class="project-image">
              <img v-if="project.image" :src="project.image" :alt="project.title" />
              <div v-else class="project-placeholder">
                <span>{{ project.emoji }}</span>
              </div>
            </div>
            <div class="project-info">
              <div class="badges">
                <span v-if="project.featured" class="featured-badge">Destacado</span>
                <span v-if="project.wip" class="wip-badge">En desarrollo</span>
              </div>
              <h3 class="project-title">{{ project.title }}</h3>
              <p class="project-subtitle">{{ project.subtitle }}</p>
              <span class="view-details">Ver detalles →</span>
            </div>
          </div>
        </div>

        <button class="slider-nav right" aria-label="Siguiente" @click="slide(1)">&#8250;</button>
      </div>
    </div>

    <!-- Modal -->
    <Transition name="modal-fade">
      <div v-if="selectedProject" class="project-modal" @click.self="closeModal">
        <div class="modal-content glass">
          <button class="modal-close" @click="closeModal">✕</button>
          <div class="modal-body">
            <div class="modal-image">
              <img v-if="selectedProject.image" :src="selectedProject.image" :alt="selectedProject.title" />
              <div v-else class="modal-placeholder">
                <span class="big-icon">{{ selectedProject.emoji }}</span>
              </div>
            </div>
            <div class="modal-info">
              <div class="badges">
                <span v-if="selectedProject.featured" class="featured-badge">Destacado</span>
                <span v-if="selectedProject.wip" class="wip-badge">En desarrollo</span>
              </div>
              <h2 class="modal-title">{{ selectedProject.title }}</h2>
              <p class="modal-subtitle">{{ selectedProject.subtitle }}</p>
              <p class="modal-description">{{ selectedProject.description }}</p>
              <div class="modal-technologies">
                <h4>Tecnologías</h4>
                <div class="tech-tags">
                  <span class="tech-tag" v-for="tag in selectedProject.tags" :key="tag">{{ tag }}</span>
                </div>
              </div>
              <div class="modal-actions">
                <button v-if="selectedProject.title.includes('Chatbot')" @click="openChatDemo" class="modal-btn primary">
                  Ver demo →
                </button>
                <a v-else-if="selectedProject.demoUrl" :href="selectedProject.demoUrl" target="_blank" rel="noopener noreferrer" class="modal-btn primary">
                  Ver demo →
                </a>
                <a v-if="selectedProject.repoUrl" :href="selectedProject.repoUrl" target="_blank" rel="noopener noreferrer" class="modal-btn secondary">
                  GitHub
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </Transition>
  </section>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

const sliderRef = ref(null)
const selectedProject = ref(null)

function slide(dir) {
  if (sliderRef.value) sliderRef.value.scrollBy({ left: dir * 360, behavior: 'smooth' })
}
function openModal(p) { selectedProject.value = p }
function closeModal() { selectedProject.value = null }
function openChatDemo() {
  window.dispatchEvent(new CustomEvent('open-chat'))
  closeModal()
}

function onKeydown(e) {
  if (e.key === 'Escape' && selectedProject.value) {
    closeModal()
  }
}

onMounted(() => {
  window.addEventListener('keydown', onKeydown)
})

onUnmounted(() => {
  window.removeEventListener('keydown', onKeydown)
})

const projects = [
  {
    title: 'Portfolio Web + Chatbot IA',
    subtitle: 'CV web multidioma · En desarrollo · 2026',
    description: 'CV web SPA multidioma desplegado en servidor local propio con dark theme y glassmorphism. Integra un chatbot asesor basado en RAG, diseñado para resolver dudas sobre mi currículum. Impulsado por mi propio stack de IA local, con opción de elegir entre diferentes modelos.',
    image: '/images/robot-asistente.jpg',
    emoji: '🤖',
    featured: true,
    wip: true,
    tags: ['Laravel', 'Vue 3', 'Docker', 'Tailscale', 'SSH', 'llama.cpp', 'Qwen', 'OpenAPI'],
    demoUrl: 'https://luissoler.dev',
    repoUrl: 'https://github.com/LuisSoler191',
  },
  {
    title: 'GastroFlow',
    subtitle: 'KDS para restauración · TFG 2025–2026',
    description: 'Sistema de gestión de cocina flexible desde la llegada del comensal hasta el cobro conjunto o individual con QR, moldeable para adaptarse a cualquier tipo de negocio de restauración. Arquitectura en tiempo real con WebSockets y API REST desacoplada.',
    image: '/images/gastroflow3.png',
    emoji: '🍽️',
    featured: true,
    wip: false,
    tags: ['Laravel', 'React', 'API REST', 'WebSockets', 'MySQL', 'Docker'],
    demoUrl: null,
    repoUrl: 'https://github.com/LuisSoler191',
  },
  {
    title: 'Sierra del Segura Turismo',
    subtitle: 'Proyecto en producción · im3dia · 2026',
    description: 'SPA turística multiidioma con panel de administración completo. Módulos de municipios, rutas, alojamientos y eventos. API documentada con Swagger/OpenAPI 3.0. Co-esarrollado durante las prácticas en im3dia comunicación.',
    image: '/images/sierrasegura.jpg',
    emoji: '🏔️',
    featured: false,
    wip: false,
    tags: ['Laravel', 'Vue 3', 'Swagger', 'MySQL', 'Multiidioma'],
    demoUrl: 'https://sierradelsegura.com',
    repoUrl: null,
  },
  {
    title: 'Forestales.net',
    subtitle: 'Proyecto en producción · im3dia · 2026',
    description: 'Plataforma web sectorial con panel de administración, gestión de contenidos y soporte multiidioma. Módulos de noticias, directorio de empresas y documentación técnica. Co-esarrollado durante las prácticas en im3dia comunicación.',
    image: '/images/forestales.png',
    emoji: '🌲',
    featured: false,
    wip: false,
    tags: ['Laravel', 'Vue 2', 'PHP', 'MySQL', 'API REST'],
    demoUrl: 'https://forestales.net',
    repoUrl: null,
  },
]
</script>

<style scoped>
.projects { padding: 100px 0; background: var(--bg-primary); }
.section-header { text-align: center; margin-bottom: 64px; }
.section-label {
  font-size: 14px; font-weight: 600; color: var(--accent);
  text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 12px;
}
.section-title {
  font-size: 48px; font-weight: 700;
  letter-spacing: -0.03em; margin-bottom: 16px;
  background: linear-gradient(135deg, #fff, #a1a1a1);
  -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
}
.section-subtitle { font-size: 18px; color: var(--text-secondary); }

.slider-container { position: relative; max-width: 1400px; margin: 0 auto; padding: 0 60px; }
.slider-nav {
  position: absolute; top: 50%; transform: translateY(-50%);
  width: 44px; height: 44px; border-radius: 50%;
  background: rgba(255,255,255,0.1); backdrop-filter: blur(10px);
  border: 1px solid rgba(255,255,255,0.1);
  color: var(--text-primary); font-size: 28px; cursor: pointer; z-index: 10;
  display: flex; align-items: center; justify-content: center;
  transition: all 0.3s ease; line-height: 1;
}
.slider-nav:hover { background: rgba(255,255,255,0.2); transform: translateY(-50%) scale(1.1); }
.slider-nav.left { left: 0; }
.slider-nav.right { right: 0; }

.projects-slider {
  display: flex; gap: 24px;
  overflow-x: auto; scroll-behavior: smooth;
  padding: 20px 0; scrollbar-width: none;
}
.projects-slider::-webkit-scrollbar { display: none; }

.project-card {
  min-width: 340px; max-width: 340px;
  border-radius: 20px; overflow: hidden;
  cursor: pointer; transition: all 0.3s ease;
}
.project-card:hover {
   transform: translateY(-8px);
   border-color: rgba(255,255,255,0.15);
   box-shadow: 0 20px 40px rgba(0,0,0,0.3);
}
.project-card:focus-visible {
   outline: 2px solid var(--accent);
   outline-offset: 4px;
}
.project-image {
  width: 100%; height: 240px;
  background: rgba(255,255,255,0.05);
  display: flex; align-items: center; justify-content: center; overflow: hidden;
}
.project-image img { width: 100%; height: 100%; object-fit: cover; }
.project-placeholder {
  display: flex; align-items: center; justify-content: center;
  width: 100%; height: 100%;
  background: linear-gradient(135deg, rgba(10,132,255,0.12), rgba(10,132,255,0.04));
  font-size: 72px;
}
.project-info { padding: 24px; }
.badges { display: flex; gap: 8px; margin-bottom: 12px; flex-wrap: wrap; }
.featured-badge {
  display: inline-block; padding: 4px 12px;
  background: rgba(10,132,255,0.15); color: var(--accent);
  border-radius: 12px; font-size: 12px; font-weight: 600;
}
.wip-badge {
  display: inline-block; padding: 4px 12px;
  background: rgba(255,159,10,0.15); color: #ff9f0a;
  border: 1px solid rgba(255,159,10,0.3);
  border-radius: 12px; font-size: 12px; font-weight: 600;
}
.project-title { font-size: 20px; font-weight: 700; color: var(--text-primary); margin-bottom: 8px; letter-spacing: -0.02em; }
.project-subtitle { font-size: 14px; color: var(--text-secondary); margin-bottom: 16px; line-height: 1.5; }
.view-details { font-size: 14px; color: var(--accent); font-weight: 500; }

/* Modal */
.project-modal {
  position: fixed; inset: 0;
  background: rgba(0,0,0,0.85); backdrop-filter: blur(10px);
  z-index: 2000; display: flex; align-items: center; justify-content: center; padding: 20px;
}
.modal-content {
  max-width: 900px; width: 100%; max-height: 90vh;
  overflow-y: auto; border-radius: 24px; position: relative;
}
.modal-close {
  position: absolute; top: 20px; right: 20px;
  width: 40px; height: 40px; border-radius: 50%;
  background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.1);
  color: var(--text-primary); cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  font-size: 18px; z-index: 10; transition: all 0.3s ease;
}
.modal-close:hover { background: rgba(255,255,255,0.2); transform: scale(1.1); }
.modal-body { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; padding: 40px; }
.modal-image {
  width: 100%; height: 380px; border-radius: 16px; overflow: hidden;
  background: rgba(255,255,255,0.05);
  display: flex; align-items: center; justify-content: center;
}
.modal-image img { width: 100%; height: 100%; object-fit: cover; }
.modal-placeholder { display: flex; align-items: center; justify-content: center; width: 100%; height: 100%; }
.big-icon { font-size: 100px; }
.modal-info { display: flex; flex-direction: column; gap: 16px; }
.modal-title { font-size: 28px; font-weight: 700; color: var(--text-primary); letter-spacing: -0.02em; margin: 0; }
.modal-subtitle { font-size: 14px; color: var(--text-secondary); margin: 0; }
.modal-description { font-size: 14px; color: var(--text-secondary); line-height: 1.7; margin: 0; }
.modal-technologies h4 {
  font-size: 12px; font-weight: 600; color: var(--text-secondary);
  margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.08em;
}
.tech-tags { display: flex; flex-wrap: wrap; gap: 8px; }
.tech-tag {
  padding: 5px 12px; background: rgba(10,132,255,0.1); color: var(--accent);
  border-radius: 10px; font-size: 12px; font-weight: 500; border: 1px solid rgba(10,132,255,0.2);
}
.modal-actions { display: flex; gap: 12px; margin-top: auto; }
.modal-btn {
  flex: 1; padding: 11px 20px; border-radius: 12px; font-size: 14px; font-weight: 600;
  display: flex; align-items: center; justify-content: center;
  text-decoration: none; transition: all 0.2s ease;
}
.modal-btn.primary { background: var(--accent); color: #fff; }
.modal-btn.primary:hover { background: var(--accent-hover); transform: scale(1.02); }
.modal-btn.secondary {
  background: rgba(255,255,255,0.08); color: var(--text-primary);
  border: 1px solid rgba(255,255,255,0.12);
}
.modal-btn.secondary:hover { background: rgba(255,255,255,0.14); }

.modal-fade-enter-active, .modal-fade-leave-active { transition: opacity 0.3s ease; }
.modal-fade-enter-from, .modal-fade-leave-to { opacity: 0; }

@media (max-width: 968px) {
  .projects { padding: 80px 0; }
  .section-title { font-size: 36px; }
  .slider-container { padding: 0 20px; }
  .project-card { min-width: calc(100vw - 80px); max-width: calc(100vw - 80px); }
  .modal-body { grid-template-columns: 1fr; padding: 24px; gap: 24px; }
  .modal-actions { flex-direction: column; }
}
</style>
