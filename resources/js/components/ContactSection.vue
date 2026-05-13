<template>
  <section id="contact" class="contact">
    <div class="container">
      <div class="section-header">
        <h2 class="section-title">Contáctame</h2>
        <p class="section-subtitle">¿Tienes un proyecto en mente? Hablemos</p>
      </div>

      <div class="contact-content">
        <!-- Info cards -->
        <div class="contact-info">
          <a class="info-card glass" href="mailto:tu@email.com">
            <div class="info-icon">📧</div>
            <div class="info-text">
              <span class="info-label">Email</span>
              <span class="info-value">tu@email.com</span>
            </div>
          </a>
          <a class="info-card glass" href="tel:+34600000000">
            <div class="info-icon">📞</div>
            <div class="info-text">
              <span class="info-label">Teléfono</span>
              <span class="info-value">+34 600 000 000</span>
            </div>
          </a>
          <div class="info-card glass">
            <div class="info-icon">📍</div>
            <div class="info-text">
              <span class="info-label">Ubicación</span>
              <span class="info-value">España</span>
            </div>
          </div>

          <div class="social-links">
            <h3>Sígueme en</h3>
            <div class="social-icons">
              <a href="https://github.com" target="_blank" aria-label="GitHub" class="social-icon glass">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                </svg>
              </a>
              <a href="https://linkedin.com" target="_blank" aria-label="LinkedIn" class="social-icon glass">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                </svg>
              </a>
            </div>
          </div>
        </div>

        <!-- Contact Form -->
        <form class="contact-form glass" @submit.prevent="submitForm" novalidate>
          <div class="form-group">
            <label for="name">Nombre</label>
            <input v-model="form.name" type="text" id="name" name="name" placeholder="Luis Soler" required />
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <input v-model="form.email" type="email" id="email" name="email" placeholder="tu@email.com" required />
          </div>
          <div class="form-group">
            <label for="subject">Asunto</label>
            <input v-model="form.subject" type="text" id="subject" name="subject" placeholder="¿De qué quieres hablar?" required />
          </div>
          <div class="form-group">
            <label for="message">Mensaje</label>
            <textarea v-model="form.message" id="message" name="message" placeholder="Cuéntame sobre tu proyecto..." rows="5" required></textarea>
          </div>
          <button type="submit" class="submit-btn" :disabled="sending" :class="{ loading: sending }">
            <span v-if="!sending">Enviar mensaje →</span>
            <span v-else class="spinner"></span>
          </button>
          <p v-if="sent" class="success-msg">✓ Mensaje enviado. ¡Gracias!</p>
        </form>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue'

const form = ref({ name: '', email: '', subject: '', message: '' })
const sending = ref(false)
const sent = ref(false)

async function submitForm() {
  sending.value = true
  // Aquí llamas a tu ruta de Laravel: POST /api/contact
  // Ejemplo: await axios.post('/api/contact', form.value)
  await new Promise(r => setTimeout(r, 1200)) // Simulación
  sending.value = false
  sent.value = true
  form.value = { name: '', email: '', subject: '', message: '' }
}
</script>

<style scoped>
.contact { padding: 100px 0; }
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

.contact-content {
  display: grid; grid-template-columns: 1fr 1.5fr;
  gap: 48px; max-width: 1100px; margin: 0 auto;
}
.contact-info { display: flex; flex-direction: column; gap: 20px; }
.info-card {
  display: flex; align-items: center; gap: 20px;
  padding: 20px; border-radius: 16px;
  text-decoration: none; color: inherit;
  transition: all 0.3s ease;
}
.info-card:hover { transform: translateX(8px); border-color: rgba(255,255,255,0.2); }
.info-icon {
  font-size: 28px; width: 56px; height: 56px;
  display: flex; align-items: center; justify-content: center;
  background: rgba(10,132,255,0.1); border-radius: 12px;
}
.info-text { display: flex; flex-direction: column; gap: 4px; }
.info-label { font-size: 13px; color: var(--text-secondary); font-weight: 500; }
.info-value { font-size: 16px; color: var(--text-primary); font-weight: 600; }
.social-links { margin-top: 24px; }
.social-links h3 { font-size: 18px; font-weight: 600; color: var(--text-primary); margin-bottom: 16px; }
.social-icons { display: flex; gap: 12px; }
.social-icon {
  width: 44px; height: 44px; border-radius: 12px;
  display: flex; align-items: center; justify-content: center;
  color: var(--text-secondary); text-decoration: none;
  transition: all 0.3s ease;
}
.social-icon:hover { color: var(--accent); transform: translateY(-4px); background: rgba(255,255,255,0.1); }

/* Form */
.contact-form {
  padding: 40px; border-radius: 20px;
  background: rgba(255,255,255,0.05);
  border: 1px solid rgba(255,255,255,0.08);
}
.form-group { margin-bottom: 16px; }
.form-group label {
  display: block; font-size: 13px; font-weight: 400;
  color: rgba(255,255,255,0.6); margin-bottom: 8px;
}
.form-group input,
.form-group textarea {
  width: 100%; padding: 13px 16px;
  background: rgba(255,255,255,0.08);
  border: 1px solid rgba(255,255,255,0.15);
  border-radius: 10px; color: var(--text-primary);
  font-size: 15px; font-family: inherit;
  transition: all 0.2s ease;
}
.form-group input::placeholder,
.form-group textarea::placeholder { color: rgba(255,255,255,0.4); }
.form-group input:hover,
.form-group textarea:hover { border-color: rgba(255,255,255,0.25); }
.form-group input:focus,
.form-group textarea:focus {
  outline: none;
  background: rgba(255,255,255,0.1);
  border-color: var(--accent);
  box-shadow: 0 0 0 4px rgba(10,132,255,0.15);
}
.form-group textarea { resize: vertical; min-height: 120px; }
.submit-btn {
  width: 100%; padding: 13px 24px;
  background: var(--accent); color: var(--text-primary);
  font-size: 15px; font-weight: 500;
  border-radius: 10px; border: none; cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  gap: 8px; transition: all 0.2s ease;
  margin-top: 24px;
}
.submit-btn:hover:not(:disabled) { background: var(--accent-hover); transform: scale(1.01); }
.submit-btn:disabled { opacity: 0.6; cursor: not-allowed; }
.spinner {
  width: 16px; height: 16px;
  border: 2px solid rgba(255,255,255,0.3);
  border-top-color: #fff;
  border-radius: 50%;
  animation: spin 0.8s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }
.success-msg { margin-top: 16px; color: #34c759; font-size: 14px; text-align: center; }

@media (max-width: 968px) {
  .contact { padding: 80px 0; }
  .section-title { font-size: 36px; }
  .contact-content { grid-template-columns: 1fr; gap: 32px; }
}
</style>
