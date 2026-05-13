<template>
  <nav class="navbar" :class="{ scrolled: isScrolled }">
    <div class="container">
      <div class="navbar-content">
        <div class="logo" @click="$emit('scroll-to', 'hero')">
          <span class="logo-text">Luis Soler</span>
        </div>

        <!-- Desktop nav -->
        <ul class="nav-links desktop">
          <li v-for="item in navItems" :key="item.id">
            <a @click="$emit('scroll-to', item.id)" :class="{ active: activeSection === item.id }">
              {{ item.label }}
            </a>
          </li>
        </ul>

        <!-- Mobile hamburger -->
        <button class="mobile-menu-btn" :class="{ open: mobileOpen }" @click="mobileOpen = !mobileOpen" aria-label="Toggle menu">
          <span></span><span></span><span></span>
        </button>
      </div>
    </div>

    <!-- Mobile overlay -->
    <div class="mobile-menu-overlay" :class="{ active: mobileOpen }" @click="mobileOpen = false"></div>
    <div class="mobile-menu" :class="{ active: mobileOpen }">
      <div class="mobile-menu-header">
        <span class="mobile-logo">Luis Soler</span>
      </div>
      <ul class="mobile-nav-links">
        <li v-for="item in navItems" :key="item.id">
          <a @click="nav(item.id)" :class="{ active: activeSection === item.id }">
            {{ item.label }}
          </a>
        </li>
      </ul>
    </div>
  </nav>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

const emit = defineEmits(['scroll-to'])

const isScrolled = ref(false)
const mobileOpen = ref(false)
const activeSection = ref('hero')

const navItems = [
  { id: 'hero',       label: 'Inicio' },
  { id: 'skills',     label: 'Habilidades' },
  { id: 'experience', label: 'Experiencia' },
  { id: 'education',  label: 'Educación' },
  { id: 'projects',   label: 'Proyectos' },
  { id: 'contact',    label: 'Contacto' },
]

function onScroll() {
  isScrolled.value = window.scrollY > 50
}

function nav(id) {
  mobileOpen.value = false
  emit('scroll-to', id)
}

let observer = null

onMounted(() => {
  window.addEventListener('scroll', onScroll)

  const sections = navItems.map(item => document.getElementById(item.id)).filter(Boolean)

  observer = new IntersectionObserver(
    (entries) => {
      const visible = entries
        .filter(e => e.isIntersecting)
        .sort((a, b) => b.intersectionRatio - a.intersectionRatio)
      if (visible.length > 0) {
        activeSection.value = visible[0].target.id
      }
    },
    { threshold: 0.3 }
  )

  sections.forEach(section => observer.observe(section))
})

onUnmounted(() => {
  window.removeEventListener('scroll', onScroll)
  if (observer) observer.disconnect()
})
</script>

<style scoped>
.navbar {
  position: fixed;
  top: 0; left: 0; right: 0;
  z-index: 1100;
  padding: 16px 0;
  transition: all 0.3s cubic-bezier(0.4,0,0.2,1);
}
.navbar.scrolled {
  background: rgba(0,0,0,0.8);
  backdrop-filter: blur(20px) saturate(180%);
  -webkit-backdrop-filter: blur(20px) saturate(180%);
  padding: 12px 0;
}
.navbar-content {
  display: flex;
  align-items: center;
  justify-content: space-between;
}
.logo { cursor: pointer; }
.logo-text {
  font-size: 20px;
  font-weight: 600;
  letter-spacing: -0.02em;
  background: linear-gradient(135deg, #fff, #a1a1a1);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}
.nav-links {
  display: flex;
  align-items: center;
  gap: 32px;
  list-style: none;
}
.nav-links a {
  font-size: 14px;
  font-weight: 500;
  color: var(--text-secondary);
  cursor: pointer;
  transition: color 0.3s ease;
}
.nav-links a:hover { color: var(--text-primary); }
.nav-links a.active {
  color: var(--text-primary);
  position: relative;
}
.nav-links a.active::after {
  content: '';
  position: absolute;
  bottom: -4px;
  left: 0; right: 0;
  height: 2px;
  background: var(--accent);
  border-radius: 1px;
}
.nav-links a.contact-btn {
  padding: 7px 16px;
  background: var(--accent);
  color: var(--text-primary);
  border-radius: 10px;
  transition: all 0.2s ease;
}
.nav-links a.contact-btn:hover {
  background: var(--accent-hover);
  transform: scale(1.02);
}
.mobile-menu-btn {
  display: none;
  flex-direction: column;
  justify-content: center;
  gap: 5px;
  background: transparent;
  border: none;
  padding: 8px;
  cursor: pointer;
  width: 40px; height: 40px;
  z-index: 1101;
  position: relative;
}
.mobile-menu-btn span {
  width: 24px; height: 2px;
  background: var(--text-primary);
  border-radius: 2px;
  transition: all 0.3s ease;
  display: block;
}
.mobile-menu-btn.open span:nth-child(1) { transform: rotate(45deg) translate(5px, 5px); }
.mobile-menu-btn.open span:nth-child(2) { opacity: 0; transform: translateX(20px); }
.mobile-menu-btn.open span:nth-child(3) { transform: rotate(-45deg) translate(6px, -6px); }
.mobile-menu-overlay {
  position: fixed; inset: 0;
  background: rgba(0,0,0,0.8);
  opacity: 0; visibility: hidden;
  transition: all 0.3s ease;
  z-index: 1098;
}
.mobile-menu-overlay.active { opacity: 1; visibility: visible; }
.mobile-menu {
  display: none;
  position: fixed; top: 0; right: 0; bottom: 0;
  width: 85%; max-width: 400px;
  background: #000;
  transform: translateX(100%);
  transition: transform 0.3s cubic-bezier(0.4,0,0.2,1);
  z-index: 1099;
  overflow-y: auto;
}
.mobile-menu.active { transform: translateX(0); }
.mobile-menu-header {
  padding: 24px;
  border-bottom: 1px solid rgba(255,255,255,0.1);
}
.mobile-logo {
  font-size: 20px; font-weight: 600;
  background: linear-gradient(135deg, #fff, #a1a1a1);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}
.mobile-nav-links { list-style: none; }
.mobile-nav-links li { border-bottom: 1px solid rgba(255,255,255,0.1); }
.mobile-nav-links a {
  display: block;
  padding: 20px 24px;
  font-size: 18px; font-weight: 500;
  color: var(--text-secondary);
  cursor: pointer;
  transition: all 0.3s ease;
}
.mobile-nav-links a:hover { color: var(--text-primary); background: rgba(255,255,255,0.05); }
.mobile-nav-links a.contact-btn {
  margin: 16px 24px;
  padding: 12px 24px;
  background: var(--accent);
  color: var(--text-primary);
  border-radius: 10px;
  text-align: center;
  font-size: 16px;
}
@media (max-width: 768px) {
  .navbar {
    background: rgba(0,0,0,0.95);
    backdrop-filter: blur(20px);
    border-bottom: 1px solid rgba(255,255,255,0.1);
  }
  .nav-links.desktop { display: none; }
  .mobile-menu-btn { display: flex; }
  .mobile-menu { display: block; }
}
</style>
