<template>
  <div class="chat-widget">
    <!-- Burbuja flotante -->
    <button class="chat-bubble" @click="toggleChat" :class="{ open: isOpen }" aria-label="Abrir chat">
      <svg v-if="!isOpen" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
        <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/>
      </svg>
      <svg v-else width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
        <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
      </svg>
      <span v-if="!isOpen && unread > 0" class="unread-badge">{{ unread }}</span>
    </button>

    <!-- Ventana de chat -->
    <transition name="chat-slide">
      <div v-if="isOpen" class="chat-window glass">
        <!-- Header -->
        <div class="chat-header">
          <div class="chat-header-info">
            <div class="chat-avatar">🤖</div>
            <div>
              <p class="chat-title">Asistente de Luis v0.1 (BETA)</p>
              <p class="chat-status">
                <span class="status-dot" :class="{ thinking: isLoading }"></span>
                {{ isLoading ? 'Escribiendo...' : 'En línea' }}
              </p>
            </div>
          </div>
          <button class="chat-close" @click="toggleChat">✕</button>
        </div>

        <!-- Mensajes -->
        <div class="chat-messages" ref="messagesContainer">
          <div
            v-for="(msg, i) in messages"
            :key="i"
            class="message"
            :class="msg.role"
          >
            <div class="message-bubble">
              <span v-html="formatMessage(msg.content)"></span>
            </div>
          </div>

          <!-- Indicador de carga -->
          <div v-if="isLoading" class="message assistant">
            <div class="message-bubble loading">
              <span class="dot"></span>
              <span class="dot"></span>
              <span class="dot"></span>
            </div>
          </div>
        </div>

        <!-- Input -->
        <div class="chat-input-area">
          <textarea
            v-model="inputText"
            ref="inputRef"
            placeholder="Escribe tu pregunta..."
            rows="1"
            @keydown.enter.prevent="handleEnter"
            @input="autoResize"
            :disabled="isLoading"
          ></textarea>
          <button class="send-btn" @click="sendMessage" :disabled="isLoading || !inputText.trim()">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
              <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
            </svg>
          </button>
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { ref, nextTick } from 'vue'
import axios from 'axios'

const isOpen = ref(false)
const isLoading = ref(false)
const inputText = ref('')
const unread = ref(1)
const messagesContainer = ref(null)
const inputRef = ref(null)

// Mensaje de bienvenida hardcodeado — no llama al modelo
const messages = ref([
  {
    role: 'assistant',
    content: '¡Hola! Soy el asistente de Luis. Puedo contarte sobre su experiencia, proyectos o stack técnico. ¿En qué puedo ayudarte?'
  }
])

function toggleChat() {
  isOpen.value = !isOpen.value
  if (isOpen.value) {
    unread.value = 0
    nextTick(() => {
      scrollToBottom()
      inputRef.value?.focus()
    })
  }
}

async function sendMessage() {
  const text = inputText.value.trim()
  if (!text || isLoading.value) return

  // Añadir mensaje del usuario
  messages.value.push({ role: 'user', content: text })
  inputText.value = ''
  resetTextarea()
  isLoading.value = true
  await nextTick()
  scrollToBottom()

  // Historial para enviar al backend (sin el mensaje de bienvenida hardcodeado)
  const history = messages.value
    .slice(1, -1) // excluye bienvenida y el mensaje que acabamos de añadir
    .map(m => ({ role: m.role, content: m.content }))

  try {
    const { data } = await axios.post('/api/chat', {
      message: text,
      history,
    })

    messages.value.push({
      role: 'assistant',
      content: data.reply || 'No he podido obtener respuesta.'
    })
  } catch (err) {
    const msg = err.response?.status === 429
      ? 'Demasiadas peticiones. Espera un momento.'
      : 'El asistente no está disponible ahora mismo. Intenta más tarde.'

    messages.value.push({ role: 'assistant', content: msg })
  } finally {
    isLoading.value = false
    await nextTick()
    scrollToBottom()
  }
}

function handleEnter(e) {
  // Shift+Enter = salto de línea, Enter solo = enviar
  if (e.shiftKey) return
  sendMessage()
}

function scrollToBottom() {
  if (messagesContainer.value) {
    messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight
  }
}

function autoResize(e) {
  const el = e.target
  el.style.height = 'auto'
  el.style.height = Math.min(el.scrollHeight, 120) + 'px'
}

function resetTextarea() {
  if (inputRef.value) {
    inputRef.value.style.height = 'auto'
  }
}

// Convierte saltos de línea y **negrita** a HTML básico
function formatMessage(text) {
  return text
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
    .replace(/\n/g, '<br>')
}
</script>

<style scoped>
.chat-widget {
  position: fixed;
  bottom: 28px;
  right: 28px;
  z-index: 9999;
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  gap: 12px;
}

/* Burbuja */
.chat-bubble {
  width: 56px;
  height: 56px;
  border-radius: 50%;
  background: var(--accent);
  color: #fff;
  border: none;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 4px 20px rgba(10, 132, 255, 0.4);
  transition: transform 0.2s ease, background 0.2s ease;
  position: relative;
  flex-shrink: 0;
}
.chat-bubble:hover { transform: scale(1.08); background: var(--accent-hover); }
.chat-bubble.open { background: rgba(255,255,255,0.12); box-shadow: none; }

.unread-badge {
  position: absolute;
  top: -4px;
  right: -4px;
  background: #ff3b30;
  color: #fff;
  font-size: 11px;
  font-weight: 700;
  width: 18px;
  height: 18px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

/* Ventana */
.chat-window {
  width: 360px;
  height: 500px;
  border-radius: 20px;
  display: flex;
  flex-direction: column;
  overflow: hidden;
  border: 1px solid var(--glass-border);
  box-shadow: 0 8px 40px rgba(0,0,0,0.5);
}

/* Header */
.chat-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 16px 20px;
  border-bottom: 1px solid var(--glass-border);
  background: rgba(255,255,255,0.04);
  flex-shrink: 0;
}
.chat-header-info { display: flex; align-items: center; gap: 12px; }
.chat-avatar { font-size: 28px; line-height: 1; }
.chat-title { font-size: 14px; font-weight: 600; color: var(--text-primary); margin: 0; }
.chat-status { font-size: 12px; color: var(--text-secondary); margin: 2px 0 0; display: flex; align-items: center; gap: 5px; }
.status-dot {
  width: 7px; height: 7px;
  border-radius: 50%;
  background: #30d158;
  display: inline-block;
  transition: background 0.3s;
}
.status-dot.thinking { background: var(--accent); animation: pulse-dot 1s ease-in-out infinite; }
.chat-close {
  background: none; border: none; color: var(--text-secondary);
  cursor: pointer; font-size: 16px; padding: 4px; line-height: 1;
  transition: color 0.2s;
}
.chat-close:hover { color: var(--text-primary); }

/* Mensajes */
.chat-messages {
  flex: 1;
  overflow-y: auto;
  padding: 16px;
  display: flex;
  flex-direction: column;
  gap: 10px;
  scrollbar-width: thin;
  scrollbar-color: rgba(255,255,255,0.1) transparent;
}
.message { display: flex; }
.message.user { justify-content: flex-end; }
.message.assistant { justify-content: flex-start; }

.message-bubble {
  max-width: 80%;
  padding: 10px 14px;
  border-radius: 16px;
  font-size: 14px;
  line-height: 1.5;
}
.message.user .message-bubble {
  background: var(--accent);
  color: #fff;
  border-bottom-right-radius: 4px;
}
.message.assistant .message-bubble {
  background: rgba(255,255,255,0.08);
  color: var(--text-primary);
  border-bottom-left-radius: 4px;
}

/* Loading dots */
.message-bubble.loading {
  display: flex;
  align-items: center;
  gap: 5px;
  padding: 14px 16px;
}
.dot {
  width: 7px; height: 7px;
  border-radius: 50%;
  background: var(--text-secondary);
  animation: bounce 1.2s ease-in-out infinite;
}
.dot:nth-child(2) { animation-delay: 0.2s; }
.dot:nth-child(3) { animation-delay: 0.4s; }

/* Input */
.chat-input-area {
  display: flex;
  align-items: flex-end;
  gap: 8px;
  padding: 12px 16px;
  border-top: 1px solid var(--glass-border);
  background: rgba(255,255,255,0.03);
  flex-shrink: 0;
}
.chat-input-area textarea {
  flex: 1;
  background: rgba(255,255,255,0.07);
  border: 1px solid var(--glass-border);
  border-radius: 12px;
  color: var(--text-primary);
  font-size: 14px;
  padding: 10px 14px;
  resize: none;
  outline: none;
  line-height: 1.5;
  min-height: 40px;
  max-height: 120px;
  font-family: inherit;
  transition: border-color 0.2s;
}
.chat-input-area textarea:focus { border-color: var(--accent); }
.chat-input-area textarea::placeholder { color: var(--text-secondary); }
.chat-input-area textarea:disabled { opacity: 0.5; }

.send-btn {
  width: 40px; height: 40px;
  border-radius: 12px;
  background: var(--accent);
  color: #fff;
  border: none;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  transition: background 0.2s, transform 0.1s;
}
.send-btn:hover:not(:disabled) { background: var(--accent-hover); transform: scale(1.05); }
.send-btn:disabled { opacity: 0.4; cursor: not-allowed; }

/* Animaciones */
.chat-slide-enter-active { animation: slide-up 0.25s ease; }
.chat-slide-leave-active { animation: slide-up 0.2s ease reverse; }
@keyframes slide-up {
  from { opacity: 0; transform: translateY(16px) scale(0.97); }
  to   { opacity: 1; transform: translateY(0) scale(1); }
}
@keyframes bounce {
  0%, 80%, 100% { transform: translateY(0); }
  40%           { transform: translateY(-6px); }
}
@keyframes pulse-dot {
  0%, 100% { opacity: 1; }
  50%       { opacity: 0.4; }
}

/* Mobile */
@media (max-width: 480px) {
  .chat-widget { bottom: 16px; right: 16px; }
  .chat-window { width: calc(100vw - 32px); height: 420px; }
}
</style>
