<template>
  <section id="experience" class="experience">
    <div class="container">
      <div class="section-header">
        <h2 class="section-title">Experiencia</h2>
        <p class="section-subtitle">Mi trayectoria profesional en el desarrollo web</p>
      </div>

      <div class="experience-list">
        <div
          v-for="job in jobs"
          :key="job.company"
          class="experience-card glass"
        >
          <div class="card-aside">
            <div class="company-logo">
              <img v-if="job.logo" :src="job.logo" :alt="job.company" />
              <span v-else class="logo-fallback">{{ job.company[0] }}</span>
            </div>
            <span class="period">{{ job.period }}</span>
          </div>

          <div class="card-body">
            <div class="card-header">
              <h3 class="position">{{ job.position }}</h3>
              <a v-if="job.url" :href="job.url" target="_blank" rel="noopener" class="company-link">
                {{ job.company }} · {{ job.location }}
              </a>
              <span v-else class="company-name">{{ job.company }} · {{ job.location }}</span>
            </div>

            <p class="description">{{ job.description }}</p>

            <ul v-if="job.highlights" class="highlights">
              <li v-for="h in job.highlights" :key="h">{{ h }}</li>
            </ul>

            <div v-if="job.urls" class="production-urls">
              <span class="urls-label">Proyectos en producción:</span>
              <div class="urls-list">
                <a v-for="u in job.urls" :key="u.href" :href="u.href" target="_blank" rel="noopener" class="url-chip">
                  {{ u.label }}
                </a>
              </div>
            </div>

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
const jobs = [
  {
    company: 'im3dia comunicación',
    location: 'Albacete',
    period: 'Mar 2026 – Jun 2026',
    position: 'Desarrollador Web · Prácticas DAW',
    logo: '/images/imedia.jpg',
    url: null,
    description: 'Desarrollo full-stack en entorno de producción real con Laravel y Vue 2/3 en SPAs con soporte multiidioma. Implementación de módulos completos, documentación de APIs y testing avanzado.',
    highlights: [
      'Desarrollo full-stack (Laravel + Vue 2/3) en SPAs con soporte multiidioma',
      'Implementación de módulos completos full stack sobre paneles de administración',
      'Documentación de APIs con Swagger / OpenAPI, corrección de bugs y mejoras de eficiencia y seguridad',
      'Testing avanzado con API testing, Vitest y Playwright; pruebas automatizadas con stack de IA local',
    ],
    urls: [
      { label: 'forestales.net',         href: 'https://forestales.net' },
      { label: 'sierradelsegura.com',    href: 'https://sierradelsegura.com' },
      { label: 'lamanchuelarural.com',   href: 'https://lamanchuelarural.com' },
    ],
    tags: ['Laravel', 'Vue 2/3', 'Swagger', 'Vitest', 'Playwright', 'MySQL', 'Docker'],
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

.experience-list {
  display: flex;
  flex-direction: column;
  gap: 32px;
  max-width: 900px;
  margin: 0 auto;
}

.experience-card {
  display: grid;
  grid-template-columns: 160px 1fr;
  gap: 40px;
  border-radius: 24px;
  padding: 36px;
  transition: all 0.3s ease;
}
.experience-card:hover {
  transform: translateY(-4px);
  border-color: rgba(255,255,255,0.2);
  box-shadow: 0 16px 48px rgba(0,0,0,0.4);
}

.card-aside {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 16px;
  padding-top: 4px;
}
.company-logo {
  width: 120; height: 56px;
  border-radius: 5px;
  overflow: hidden;
  background: rgba(255,255,255,0.06);
  border: 1px solid rgba(255,255,255,0.1);
  display: flex; align-items: center; justify-content: center;
}
.company-logo img { width: 100%; height: 100%; object-fit: contain; }
.logo-fallback {
  font-size: 28px; font-weight: 700;
  background: linear-gradient(135deg, var(--accent), #5ac8fa);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}
.period {
  font-size: 13px; font-weight: 500;
  color: var(--accent);
  text-align: center;
  padding: 6px 14px;
  background: rgba(0,113,227,0.1);
  border: 1px solid rgba(0,113,227,0.25);
  border-radius: 10px;
  white-space: nowrap;
}

.card-header { margin-bottom: 14px; }
.position { font-size: 22px; font-weight: 700; color: var(--text-primary); margin-bottom: 6px; letter-spacing: -0.02em; }
.company-link,
.company-name {
  font-size: 15px; color: var(--text-secondary); font-weight: 500;
  text-decoration: none;
}
.company-link { transition: color 0.2s; }
.company-link:hover { color: var(--accent); }

.description {
  font-size: 15px; color: var(--text-secondary);
  line-height: 1.65; margin-bottom: 16px;
}

.highlights {
  list-style: none;
  display: flex; flex-direction: column; gap: 8px;
  margin-bottom: 20px;
}
.highlights li {
  font-size: 14px; color: var(--text-secondary);
  line-height: 1.6;
  padding-left: 18px;
  position: relative;
}
.highlights li::before {
  content: '▸';
  position: absolute; left: 0;
  color: var(--accent);
}

.production-urls { margin-bottom: 20px; }
.urls-label { font-size: 13px; color: var(--text-secondary); font-weight: 500; display: block; margin-bottom: 10px; }
.urls-list { display: flex; flex-wrap: wrap; gap: 8px; }
.url-chip {
  padding: 5px 12px;
  background: rgba(255,255,255,0.05);
  border: 1px solid rgba(255,255,255,0.1);
  border-radius: 8px;
  font-size: 13px; color: var(--text-secondary);
  text-decoration: none;
  transition: all 0.2s;
}
.url-chip:hover { color: var(--accent); border-color: rgba(0,113,227,0.4); }

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
  .section-title { font-size: 36px; }
  .experience-card {
    grid-template-columns: 1fr;
    gap: 24px;
    padding: 24px;
  }
  .card-aside { flex-direction: row; align-items: center; justify-content: flex-start; }
  .period { white-space: normal; }
}
</style>
