<template>
  <footer class="footer">
    <div class="container">
      <div class="footer-content">
        <div class="footer-brand">
          <div class="brand-header">
            <h3 class="brand-name">Luis Soler</h3>
          </div>
          <p class="brand-tagline">Construyendo el futuro digital, una línea de código a la vez.</p>
          <div class="social-links">
            <a href="https://github.com" target="_blank" aria-label="GitHub" class="social-link glass">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
              </svg>
            </a>
            <a href="https://linkedin.com" target="_blank" aria-label="LinkedIn" class="social-link glass">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
              </svg>
            </a>
          </div>
        </div>

        <div class="footer-links">
          <div class="links-column">
            <h4>Navegación</h4>
            <ul>
              <li v-for="item in quickLinks" :key="item.id">
                <a @click="$emit('scroll-to', item.id)">{{ item.label }}</a>
              </li>
            </ul>
          </div>
          <div class="links-column">
            <h4>Recursos</h4>
            <ul>
              <li><a href="#">Blog</a></li>
              <li><a @click="$emit('scroll-to', 'projects')">Proyectos</a></li>
              <li><a @click="$emit('scroll-to', 'contact')">Contacto</a></li>
              <li><a href="#">Descargar CV</a></li>
            </ul>
          </div>
        </div>
      </div>

      <div class="footer-bottom">
        <p class="copyright">© {{ currentYear }} Luis Soler. Todos los derechos reservados.</p>
        <p class="made-with">Hecho con ❤️ y Laravel + Vue 3</p>
      </div>
    </div>
  </footer>
</template>

<script setup>
defineEmits(['scroll-to'])

const currentYear = new Date().getFullYear()

const quickLinks = [
  { id: 'hero',       label: 'Inicio' },
  { id: 'skills',     label: 'Habilidades' },
  { id: 'experience', label: 'Experiencia' },
  { id: 'education',  label: 'Educación' },
  { id: 'projects',   label: 'Proyectos' },
]
</script>

<style scoped>
.footer {
  padding: 80px 0 32px;
  background: linear-gradient(180deg, transparent 0%, rgba(0,0,0,0.5) 100%);
  border-top: 1px solid var(--glass-border);
  margin-top: 100px;
}
.footer-content {
  display: grid; grid-template-columns: 1.5fr 1fr;
  gap: 64px; margin-bottom: 48px;
}
.footer-brand { max-width: 400px; }
.brand-header { display: flex; align-items: center; gap: 12px; margin-bottom: 12px; }
.brand-name {
  font-size: 24px; font-weight: 700;
  background: linear-gradient(135deg, #fff, #a1a1a1);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  margin: 0;
}
.brand-tagline {
  font-size: 15px; color: var(--text-secondary);
  line-height: 1.6; margin-bottom: 24px; max-width: 400px;
}
.social-links { display: flex; gap: 12px; }
.social-link {
  width: 44px; height: 44px;
  display: flex; align-items: center; justify-content: center;
  border-radius: 12px; color: var(--text-primary);
  text-decoration: none; transition: all 0.3s ease;
}
.social-link:hover {
  background: rgba(10,132,255,0.2);
  border-color: var(--accent);
  transform: translateY(-4px);
  color: var(--accent);
}
.footer-links {
  display: grid; grid-template-columns: repeat(2, 1fr); gap: 48px;
}
.links-column h4 {
  font-size: 16px; font-weight: 600;
  color: var(--text-primary); margin-bottom: 16px;
}
.links-column ul { list-style: none; display: flex; flex-direction: column; gap: 12px; }
.links-column a {
  font-size: 14px; color: var(--text-secondary);
  cursor: pointer; transition: all 0.3s ease;
  display: inline-block; text-decoration: none;
}
.links-column a:hover { color: var(--accent); transform: translateX(4px); }
.footer-bottom {
  padding-top: 32px;
  border-top: 1px solid var(--glass-border);
  display: flex; justify-content: space-between; align-items: center;
}
.copyright, .made-with {
  font-size: 14px; color: var(--text-secondary);
}
@media (max-width: 768px) {
  .footer { padding: 60px 0 24px; margin-top: 80px; }
  .footer-content { grid-template-columns: 1fr; gap: 48px; }
  .footer-links { grid-template-columns: 1fr; gap: 32px; }
  .footer-bottom { flex-direction: column; gap: 12px; text-align: center; }
}
</style>
