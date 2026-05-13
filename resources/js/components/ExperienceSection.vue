<template>
  <section id="experience" class="experience">
    <div class="container">
      <div class="section-header">
        <h2 class="section-title">Conoce mi Experiencia</h2>
        <p class="section-subtitle">Mi trayectoria profesional en el desarrollo web</p>
      </div>

      <div class="experience-grid">
        <div
          v-for="job in jobs"
          :key="job.company"
          class="experience-card glass"
        >
          <div class="card-image">
            <img :src="job.logo" :alt="job.company" />
            <div class="image-overlay"></div>
          </div>
          <div class="card-content">
            <div class="card-header">
              <h3 class="company">{{ job.company }}</h3>
              <span class="period">{{ job.period }}</span>
            </div>
            <h4 class="position">{{ job.position }}</h4>
            <p class="description">{{ job.description }}</p>
            <div class="technologies">
              <span class="tech-tag" v-for="tag in job.tags" :key="tag">{{ tag }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
// Adapta con tu propia experiencia
const jobs = [
  {
    company: 'Empresa Actual',
    period: 'Ene 2024 – Presente',
    position: 'Full Stack Developer',
    description: 'Desarrollo de aplicaciones web con Laravel y Vue 3. Diseño de APIs RESTful y arquitectura de sistemas escalables para clientes enterprise.',
    logo: '/images/companies/empresa.png',
    tags: ['Laravel', 'Vue 3', 'MySQL', 'Docker'],
  },
  {
    company: 'Empresa Anterior',
    period: 'Mar 2022 – Dic 2023',
    position: 'Backend Developer',
    description: 'Mantenimiento y evolución de plataforma e-commerce. Optimización de consultas, integración de pasarelas de pago y desarrollo de microservicios.',
    logo: '/images/companies/empresa2.png',
    tags: ['PHP', 'REST API', 'PostgreSQL'],
  },
  {
    company: 'Startup XYZ',
    period: 'Jun 2020 – Feb 2022',
    position: 'Frontend Developer',
    description: 'Implementación de interfaces de usuario modernas y responsivas. Colaboración estrecha con diseño UX y equipos de producto.',
    logo: '/images/companies/empresa3.png',
    tags: ['Vue 2', 'Vuetify', 'JavaScript'],
  },
]
</script>

<style scoped>
.experience {
  padding: 100px 0;
  background: linear-gradient(180deg, transparent 0%, rgba(0,113,227,0.03) 50%, transparent 100%);
}
.section-header { text-align: center; margin-bottom: 64px; }
.section-title {
  font-size: 48px; font-weight: 700;
  letter-spacing: -0.03em; margin-bottom: 16px;
  background: linear-gradient(135deg, #fff, #a1a1a1);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}
.section-subtitle { font-size: 18px; color: var(--text-secondary); }

.experience-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
  gap: 32px;
}
.experience-card {
  border-radius: 24px;
  overflow: hidden;
  transition: all 0.4s cubic-bezier(0.4,0,0.2,1);
  cursor: pointer;
}
.experience-card:hover {
  transform: translateY(-12px);
  border-color: rgba(255,255,255,0.2);
  box-shadow: 0 16px 48px rgba(0,0,0,0.5);
}
.experience-card:hover .card-image img { transform: scale(1.1); }
.experience-card:hover .image-overlay { background: rgba(0,113,227,0.3); }
.card-image {
  position: relative;
  width: 100%; height: 200px;
  overflow: hidden;
}
.card-image img {
  width: 100%; height: 100%;
  object-fit: cover;
  transition: transform 0.4s ease;
}
.image-overlay {
  position: absolute; inset: 0;
  background: rgba(0,0,0,0.3);
  transition: background 0.4s ease;
}
.card-content { padding: 24px; }
.card-header {
  display: flex; justify-content: space-between;
  align-items: flex-start; margin-bottom: 12px; gap: 16px;
}
.company { font-size: 20px; font-weight: 600; color: var(--text-primary); flex: 1; }
.period { font-size: 13px; color: var(--accent); font-weight: 500; white-space: nowrap; }
.position { font-size: 16px; font-weight: 500; color: var(--text-secondary); margin-bottom: 12px; }
.description { font-size: 14px; color: var(--text-secondary); line-height: 1.6; margin-bottom: 16px; }
.technologies { display: flex; flex-wrap: wrap; gap: 8px; }
.tech-tag {
  padding: 6px 12px;
  background: rgba(0,113,227,0.1);
  border: 1px solid rgba(0,113,227,0.3);
  border-radius: 12px;
  font-size: 12px; font-weight: 500;
  color: var(--accent);
}

@media (max-width: 768px) {
  .experience { padding: 80px 0; }
  .section-header { margin-bottom: 48px; }
  .section-title { font-size: 36px; }
  .section-subtitle { font-size: 16px; }
  .experience-grid { grid-template-columns: 1fr; gap: 24px; }
}
</style>
