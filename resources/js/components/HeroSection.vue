<template>
  <section id="hero" class="header">
    <div class="container">
      <div class="header-content">
        <div class="profile-image-container">
          <div class="profile-image-wrapper glass">
            <img class="profile-image" :src="data.avatar" :alt="data.name" />
          </div>
          <div class="glow-effect"></div>
        </div>
        <div class="header-text">
          <h1 class="name">{{ data.name }}</h1>
          <p class="title">{{ data.title }}</p>
          <p class="description">{{ data.description }}</p>
          <div class="header-actions">
            <button class="btn-secondary" @click="$emit('scroll-to', 'projects')">Ver mi trabajo</button>
            <a class="btn-ghost" href="/cv/CV-Luis-Soler.pdf" download>Descargar CV</a>
            <button class="btn-primary" @click="$emit('scroll-to', 'contact')">¡Contáctame!</button>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
defineEmits(['scroll-to'])

const data = {
  name: 'Luis Soler Valdivia',
  title: 'Desarrollador Web · Full Stack / Back-End',
  description: 'Desarrollador web junior con formación en DAW y experiencia práctica en entornos de producción real. Especializado en back-end y full stack con Laravel y Vue.js, con especial interés en la integración de IA local en flujos de trabajo propios.',
  avatar: '/images/LuisGuapo.jpeg',
}
</script>

<style scoped>
.header {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 120px 0 80px;
  position: relative;
  overflow: hidden;
}
.header::before {
  content: '';
  position: absolute;
  top: -50%; right: -20%;
  width: 800px; height: 800px;
  background: radial-gradient(circle, rgba(0,113,227,0.15) 0%, transparent 70%);
  border-radius: 50%;
  filter: blur(80px);
  animation: float 20s ease-in-out infinite;
}
.header-content {
  display: flex;
  align-items: center;
  gap: 80px;
  position: relative;
  z-index: 1;
}
.profile-image-container { position: relative; }
.profile-image-wrapper {
  width: 280px; height: 280px;
  border-radius: 50%;
  overflow: hidden;
  position: relative;
  z-index: 2;
}
.profile-image {
  width: 100%; height: 100%;
  object-fit: cover;
  object-position: top center;
}
.glow-effect {
  position: absolute;
  top: 50%; left: 50%;
  transform: translate(-50%, -50%);
  width: 320px; height: 320px;
  background: radial-gradient(circle, rgba(0,113,227,0.3) 0%, transparent 70%);
  border-radius: 50%;
  filter: blur(40px);
  z-index: 1;
  animation: pulse 3s ease-in-out infinite;
}
.header-text { flex: 1; max-width: 600px; }
.name {
  font-size: 56px;
  font-weight: 700;
  letter-spacing: -0.03em;
  margin-bottom: 12px;
  background: linear-gradient(135deg, #fff, #a1a1a1);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}
.title {
  font-size: 20px;
  font-weight: 500;
  color: var(--accent);
  margin-bottom: 20px;
  letter-spacing: 0.01em;
}
.description {
  font-size: 18px;
  color: var(--text-secondary);
  line-height: 1.7;
  margin-bottom: 40px;
}
.header-actions { display: flex; gap: 12px; align-items: center; }
.header-actions button,
.header-actions a {
  padding: 11px 24px;
  font-size: 15px; font-weight: 500;
  border-radius: 12px;
  transition: all 0.2s ease;
  letter-spacing: -0.01em;
  border: none;
  cursor: pointer;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}
.btn-primary {
  background: var(--accent);
  color: var(--text-primary);
}
.btn-primary:hover { background: var(--accent-hover); transform: scale(1.02); }
.btn-primary:active { transform: scale(0.98); }
.btn-secondary {
  background: rgba(255,255,255,0.08);
  color: var(--text-primary);
  border: 1px solid rgba(255,255,255,0.12) !important;
}
.btn-secondary:hover { background: rgba(255,255,255,0.12); }
.btn-ghost {
  background: transparent;
  color: var(--text-secondary);
  border: 1px solid rgba(255,255,255,0.2) !important;
}
.btn-ghost:hover { color: var(--text-primary); border-color: rgba(255,255,255,0.4) !important; }

@keyframes float {
  0%, 100% { transform: translate(0,0); }
  50% { transform: translate(-30px,-30px); }
}
@keyframes pulse {
  0%, 100% { opacity: 0.5; transform: translate(-50%,-50%) scale(1); }
  50% { opacity: 0.8; transform: translate(-50%,-50%) scale(1.1); }
}
@media (max-width: 968px) {
  .header-content { flex-direction: column; gap: 48px; text-align: center; }
  .name { font-size: 48px; }
  .title { font-size: 18px; }
  .description { font-size: 16px; }
  .header-actions { justify-content: center; }
}
@media (max-width: 640px) {
  .header { padding: 100px 0 60px; }
  .profile-image-wrapper { width: 220px; height: 220px; }
  .glow-effect { width: 260px; height: 260px; }
  .name { font-size: 36px; }
  .title { font-size: 16px; }
  .description { font-size: 15px; }
  .header-actions { flex-direction: column; width: 100%; }
  .header-actions button,
  .header-actions a { width: 100%; }
}
</style>
