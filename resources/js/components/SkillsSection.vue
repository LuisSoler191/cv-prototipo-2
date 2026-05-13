<template>
  <section id="skills" class="skills">
    <div class="container">
      <div class="section-header">
        <h2 class="section-title">Habilidades</h2>
        <p class="section-subtitle">Tecnologías y herramientas que domino</p>
      </div>

      <div class="filter-container">
        <div class="filters glass">
          <button
            v-for="cat in categories"
            :key="cat"
            class="filter-btn"
            :class="{ active: activeFilter === cat }"
            @click="activeFilter = cat"
          >
            {{ cat }}
          </button>
        </div>
      </div>

      <div class="skills-grid">
        <transition-group name="skill-fade">
          <div
            v-for="skill in filteredSkills"
            :key="skill.name"
            class="skill-card glass"
          >
            <div class="skill-icon">
              <img :src="skill.icon" :alt="skill.name" loading="lazy" />
            </div>
            <h3 class="skill-name">{{ skill.name }}</h3>
            <span class="skill-category">{{ skill.category }}</span>
          </div>
        </transition-group>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, computed } from 'vue'

const activeFilter = ref('Todos')

const categories = ['Todos', 'Frontend', 'Backend', 'DevOps']

// Adapta estos skills a los tuyos. Añade tus iconos en public/images/skills/
const skills = [
  { name: 'Vue 3',       category: 'Frontend', icon: '/images/skills/vue.svg' },
  { name: 'JavaScript',  category: 'Frontend', icon: '/images/skills/js.svg' },
  { name: 'TypeScript',  category: 'Frontend', icon: '/images/skills/typescript.svg' },
  { name: 'HTML',        category: 'Frontend', icon: '/images/skills/html.svg' },
  { name: 'CSS',         category: 'Frontend', icon: '/images/skills/css.svg' },
  { name: 'Tailwind',    category: 'Frontend', icon: '/images/skills/tailwind.svg' },
  { name: 'Figma',       category: 'Frontend', icon: '/images/skills/figma.svg' },
  { name: 'Laravel',     category: 'Backend',  icon: '/images/skills/laravel.svg' },
  { name: 'PHP',         category: 'Backend',  icon: '/images/skills/php.svg' },
  { name: 'MySQL',       category: 'Backend',  icon: '/images/skills/mysql.svg' },
  { name: 'REST API',    category: 'Backend',  icon: '/images/skills/rest-api.svg' },
  { name: 'Git',         category: 'DevOps',   icon: '/images/skills/git.svg' },
  { name: 'Docker',      category: 'DevOps',   icon: '/images/skills/docker.svg' },
]

const filteredSkills = computed(() =>
  activeFilter.value === 'Todos'
    ? skills
    : skills.filter(s => s.category === activeFilter.value)
)
</script>

<style scoped>
.skills { padding: 100px 0; position: relative; }
.section-header { text-align: center; margin-bottom: 48px; }
.section-title {
  font-size: 48px; font-weight: 700;
  letter-spacing: -0.03em; margin-bottom: 16px;
  background: linear-gradient(135deg, #fff, #a1a1a1);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}
.section-subtitle { font-size: 18px; color: var(--text-secondary); }

.filter-container { display: flex; justify-content: center; margin-bottom: 48px; }
.filters {
  display: inline-flex; gap: 8px;
  padding: 8px; border-radius: 16px;
}
.filter-btn {
  padding: 10px 24px;
  background: transparent;
  color: var(--text-secondary);
  font-size: 14px; font-weight: 500;
  border-radius: 12px; border: none;
  transition: all 0.3s cubic-bezier(0.4,0,0.2,1);
  cursor: pointer;
}
.filter-btn:hover { color: var(--text-primary); background: rgba(255,255,255,0.05); }
.filter-btn.active {
  background: rgba(0,113,227,0.2);
  color: var(--accent);
  border: 1px solid rgba(0,113,227,0.3);
  box-shadow: 0 4px 12px rgba(0,113,227,0.2);
}

.skills-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
  gap: 24px;
  max-width: 1000px;
  margin: 0 auto;
}
.skill-card {
  padding: 32px 24px;
  border-radius: 20px;
  text-align: center;
  transition: all 0.3s cubic-bezier(0.4,0,0.2,1);
  cursor: pointer;
}
.skill-card:hover {
  transform: translateY(-8px);
  border-color: rgba(255,255,255,0.2);
  box-shadow: 0 12px 40px rgba(0,0,0,0.4);
}
.skill-card:hover .skill-icon { transform: scale(1.1); }
.skill-icon {
  width: 64px; height: 64px;
  margin: 0 auto 16px;
  display: flex; align-items: center; justify-content: center;
  transition: transform 0.3s ease;
}
.skill-icon img {
  width: 48px; height: 48px;
  object-fit: contain;
  filter: drop-shadow(0 2px 8px rgba(0,0,0,0.3));
}
.skill-name { font-size: 16px; font-weight: 600; color: var(--text-primary); margin-bottom: 4px; }
.skill-category { font-size: 12px; color: var(--text-secondary); font-weight: 500; opacity: 0.7; }

/* Transition group */
.skill-fade-enter-active,
.skill-fade-leave-active { transition: all 0.3s ease; }
.skill-fade-enter-from,
.skill-fade-leave-to { opacity: 0; transform: scale(0.9); }

@media (max-width: 768px) {
  .skills { padding: 80px 0; }
  .section-title { font-size: 36px; }
  .section-subtitle { font-size: 16px; }
  .filter-container { overflow-x: auto; padding: 0 16px; }
  .filters { min-width: max-content; }
  .skills-grid { grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 16px; }
}
@media (max-width: 480px) {
  .skills-grid { grid-template-columns: repeat(2, 1fr); }
}
</style>
