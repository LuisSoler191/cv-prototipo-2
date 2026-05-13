<template>
  <section id="education" class="education">
    <div class="container">
      <div class="section-header">
        <h2 class="section-title">Educación</h2>
        <p class="section-subtitle">Mi formación académica y profesional</p>
      </div>

      <div class="education-timeline">
        <div
          v-for="(item, i) in education"
          :key="item.degree"
          class="timeline-item glass"
          :class="{ reverse: i % 2 !== 0 }"
        >
          <div class="timeline-marker"></div>
          <div class="timeline-content">
            <span class="period">{{ item.period }}</span>
            <h3 class="degree">{{ item.degree }}</h3>
            <h4 class="institution">{{ item.institution }}</h4>
            <p class="description">{{ item.description }}</p>
          </div>
        </div>
      </div>

      <!-- Certifications -->
      <div class="certifications-section">
        <div class="certifications-header">
          <h3 class="certifications-title">Licencias y Certificaciones</h3>
          <p class="certifications-subtitle">Formación continua y especialización profesional</p>
        </div>
        <div class="certifications-grid">
          <div
            v-for="cert in certifications"
            :key="cert.name"
            class="certification-card glass"
          >
            <div class="cert-header">
              <div class="cert-logo" :class="cert.logoClass">
                <svg v-if="cert.issuerType === 'linkedin'" width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                </svg>
                <svg v-else-if="cert.issuerType === 'microsoft'" width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M0 0v11.408h11.408V0zm12.594 0v11.408H24V0zM0 12.594V24h11.408V12.594zm12.594 0V24H24V12.594z"/>
                </svg>
                <span v-else class="cert-icon">🎓</span>
              </div>
              <div class="cert-date">{{ cert.date }}</div>
            </div>
            <h4 class="cert-name">{{ cert.name }}</h4>
            <p class="cert-issuer">{{ cert.issuer }}</p>
            <div class="cert-skills">
              <span class="skill-badge" v-for="skill in cert.skills" :key="skill">{{ skill }}</span>
            </div>
            <a v-if="cert.url" :href="cert.url" target="_blank" rel="noopener" class="cert-link">
              Mostrar credencial →
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
const education = [
  {
    period: '2018 – 2024',
    degree: 'Ingeniería Informática',
    institution: 'Universidad XYZ',
    description: 'Formación integral en desarrollo de software, arquitectura de sistemas y ciencias de la computación.',
  },
  {
    period: '2013 – 2017',
    degree: 'Bachillerato Tecnológico',
    institution: 'IES XYZ',
    description: 'Especialización en sistemas informáticos y redes. Proyecto fin de ciclo con mención de honor.',
  },
]

const certifications = [
  {
    name: 'Vue 3 – De cero a experto',
    issuer: 'Udemy',
    issuerType: 'other',
    logoClass: '',
    date: 'Mar 2024',
    skills: ['Vue 3', 'Composition API', 'Pinia'],
    url: '#',
  },
  {
    name: 'Laravel 11: Aplicaciones modernas',
    issuer: 'LinkedIn Learning',
    issuerType: 'linkedin',
    logoClass: 'linkedin',
    date: 'Ene 2024',
    skills: ['Laravel', 'PHP', 'Eloquent ORM'],
    url: '#',
  },
  {
    name: 'Fundamentos de Cloud Computing',
    issuer: 'Microsoft & LinkedIn',
    issuerType: 'microsoft',
    logoClass: 'microsoft',
    date: 'Nov 2023',
    skills: ['Azure', 'DevOps', 'Cloud'],
    url: '#',
  },
]
</script>

<style scoped>
.education { padding: 100px 0; }
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

/* Timeline */
.education-timeline {
  max-width: 800px; margin: 0 auto;
  position: relative;
}
.education-timeline::before {
  content: '';
  position: absolute; left: 50%; top: 0; bottom: 0;
  width: 2px;
  background: linear-gradient(180deg, transparent, var(--glass-border), transparent);
  transform: translateX(-50%);
}
.timeline-item {
  position: relative;
  margin-bottom: 48px; padding: 32px;
  border-radius: 20px;
  width: calc(50% - 40px);
  transition: all 0.3s ease;
}
.timeline-item:hover {
  transform: translateY(-8px);
  border-color: rgba(255,255,255,0.2);
  box-shadow: 0 12px 40px rgba(0,0,0,0.4);
}
.timeline-item:nth-child(odd)  { margin-left: 0; }
.timeline-item:nth-child(even) { margin-left: auto; }
.timeline-marker {
  position: absolute; top: 40px;
  width: 16px; height: 16px;
  background: var(--accent);
  border: 3px solid var(--bg-primary);
  border-radius: 50%;
  box-shadow: 0 0 0 4px rgba(0,113,227,0.2);
}
.timeline-item:nth-child(odd)  .timeline-marker { right: -48px; }
.timeline-item:nth-child(even) .timeline-marker { left: -48px; }
.period {
  display: inline-block;
  padding: 6px 16px;
  background: rgba(0,113,227,0.1);
  border: 1px solid rgba(0,113,227,0.3);
  border-radius: 12px;
  font-size: 13px; font-weight: 500;
  color: var(--accent);
  margin-bottom: 16px;
}
.degree { font-size: 22px; font-weight: 600; color: var(--text-primary); margin-bottom: 8px; }
.institution { font-size: 16px; font-weight: 500; color: var(--text-secondary); margin-bottom: 12px; }
.description { font-size: 14px; color: var(--text-secondary); line-height: 1.6; }

/* Certifications */
.certifications-section { margin-top: 80px; }
.certifications-header { text-align: center; margin-bottom: 48px; }
.certifications-title {
  font-size: 36px; font-weight: 700;
  letter-spacing: -0.03em; margin-bottom: 12px;
  background: linear-gradient(135deg, #fff, #a1a1a1);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}
.certifications-subtitle { font-size: 16px; color: var(--text-secondary); }
.certifications-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
  gap: 24px; max-width: 1000px; margin: 0 auto;
}
.certification-card {
  padding: 28px; border-radius: 20px;
  display: flex; flex-direction: column; gap: 16px;
  transition: all 0.3s ease;
}
.certification-card:hover {
  transform: translateY(-8px);
  border-color: rgba(255,255,255,0.2);
  box-shadow: 0 12px 40px rgba(0,0,0,0.4);
}
.cert-header { display: flex; justify-content: space-between; align-items: center; }
.cert-logo {
  width: 48px; height: 48px;
  display: flex; align-items: center; justify-content: center;
  background: rgba(255,255,255,0.05);
  border-radius: 10px;
}
.cert-logo.linkedin { background: rgba(10,102,194,0.15); color: #0a66c2; }
.cert-logo.microsoft { background: rgba(0,120,212,0.15); color: #0078d4; }
.cert-date {
  font-size: 13px; color: var(--text-secondary);
  padding: 6px 12px; background: rgba(255,255,255,0.05); border-radius: 8px;
}
.cert-name { font-size: 18px; font-weight: 600; color: var(--text-primary); line-height: 1.4; }
.cert-issuer { font-size: 14px; color: var(--text-secondary); font-weight: 500; }
.cert-skills { display: flex; flex-wrap: wrap; gap: 8px; }
.skill-badge {
  padding: 6px 12px;
  background: rgba(10,132,255,0.1);
  border: 1px solid rgba(10,132,255,0.3);
  border-radius: 12px;
  font-size: 12px; font-weight: 500; color: var(--accent);
}
.cert-link {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 8px 16px;
  background: rgba(10,132,255,0.1);
  color: var(--accent); border-radius: 8px;
  font-size: 14px; font-weight: 500;
  text-decoration: none;
  transition: all 0.3s ease;
  width: fit-content;
}
.cert-link:hover { background: rgba(10,132,255,0.2); transform: translateX(4px); }

@media (max-width: 768px) {
  .education { padding: 80px 0; }
  .section-title { font-size: 36px; }
  .education-timeline::before { left: 8px; }
  .timeline-item {
    width: calc(100% - 32px);
    margin-left: 32px !important;
    padding: 20px;
  }
  .timeline-marker { left: -40px !important; right: auto !important; width: 12px; height: 12px; }
  .certifications-section { margin-top: 60px; }
  .certifications-title { font-size: 28px; }
  .certifications-grid { grid-template-columns: 1fr; gap: 20px; }
}
</style>
