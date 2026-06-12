<template>
  <div class="chat-widget">
    <!-- Burbuja flotante (oculta cuando ventana abierta) -->
    <button v-if="!isOpen" class="chat-bubble" @click="onBubbleClick" aria-label="Abrir chat">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
        <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/>
      </svg>
      <span v-if="unread > 0" class="unread-badge">{{ unread }}</span>
    </button>

    <!-- Popup "¡Hola!" -->
    <transition name="popup-fade">
      <div v-if="showPopup" class="chat-popup">¡Hola!</div>
    </transition>

    <!-- Ventana de chat -->
    <transition name="chat-slide">
      <div v-if="isOpen" class="chat-window" ref="chatWindowRef">
        <!-- Header -->
        <div class="chat-header">
          <div class="chat-header-info">
            <div class="chat-avatar">🤖</div>
            <div>
              <p class="chat-title">Asistente de Luis v0.3 (BETA)</p>
              <p class="chat-status">
                <span class="status-dot" :class="{ thinking: isLoading || isStreaming }"></span>
                {{ (isLoading || isStreaming) ? 'Escribiendo...' : 'En línea' }}
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

          <!-- Indicador de carga: spinner primer mensaje, dots siguientes -->
           <div v-if="isLoading && !isStreaming && isFirstRealMessage" class="message assistant">
             <div class="message-bubble loading-spinner">
               <div class="spinner-ring"></div>
               <span class="spinner-text">Recabando información...</span>
             </div>
           </div>
           <div v-if="isLoading && !isStreaming && !isFirstRealMessage" class="message assistant">
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
            :disabled="isLoading || isStreaming"
          ></textarea>
          <button class="send-btn" @click="sendMessage" :disabled="isLoading || isStreaming || !inputText.trim()">
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
import { ref, nextTick, computed, watch, onMounted, onUnmounted } from 'vue'

const isOpen = ref(false)
const isLoading = ref(false)
const isStreaming = ref(false)
const inputText = ref('')
const unread = ref(1)
const messagesContainer = ref(null)
const inputRef = ref(null)
const chatWindowRef = ref(null)
let clickOnBubble = false

function adjustChatHeight() {
  const cw = document.querySelector('.chat-window')
  if (!cw) return
  const widget = document.querySelector('.chat-widget')
  if (!widget) return
  const widgetRect = widget.getBoundingClientRect()
  const bubbleSpace = 68
  const maxVh = (700 / window.innerHeight) * 100
  const spaceVh = ((widgetRect.bottom - bubbleSpace) / window.innerHeight) * 100
  const desiredVh = Math.min(maxVh, Math.max(30, spaceVh))
  cw.style.maxHeight = desiredVh + 'vh'
  cw.style.height = desiredVh + 'vh'
}

const messages = ref([
  {
    role: 'assistant',
    content: '¡Hola! Soy el asistente de Luis. Puedo contarte sobre su experiencia, proyectos o stack técnico. ¿En qué puedo ayudarte?'
  }
])

watch(isOpen, (newVal) => {
  if (newVal) {
    setTimeout(adjustChatHeight, 100)
  }
})

const isFirstRealMessage = computed(() => {
  const realMessages = messages.value.filter(m => m.role === 'user')
  return realMessages.length === 0
})

const showPopup = ref(false)
let popupShowTimer = null
let popupHideTimer = null

function dismissPopup() {
  showPopup.value = false
  if (popupHideTimer) { clearTimeout(popupHideTimer); popupHideTimer = null }
}

function onBubbleClick() {
  clickOnBubble = true
  dismissPopup()
  toggleChat()
}

function openChat() {
  dismissPopup()
  if (!isOpen.value) {
    isOpen.value = true
    unread.value = 0
    nextTick(() => {
      scrollToBottom()
      inputRef.value?.focus()
    })
  }
}

function onOpenChatEvent() {
  openChat()
}

function onKeydown(e) {
  if (e.key === 'Escape' && isOpen.value) {
    toggleChat()
  }
}

function onClickOutside(e) {
  if (!isOpen.value) return
  if (clickOnBubble) { clickOnBubble = false; return }
  const widget = document.querySelector('.chat-widget')
  if (!widget || widget.contains(e.target)) return
  toggleChat()
}

onMounted(() => {
  window.addEventListener('open-chat', onOpenChatEvent)
  window.addEventListener('keydown', onKeydown)
  window.addEventListener('resize', adjustChatHeight)
  document.addEventListener('click', onClickOutside)
  popupShowTimer = setTimeout(() => {
    showPopup.value = true
    popupHideTimer = setTimeout(dismissPopup, 5000)
  }, 5000)
})

onUnmounted(() => {
  window.removeEventListener('open-chat', onOpenChatEvent)
  window.removeEventListener('keydown', onKeydown)
  window.removeEventListener('resize', adjustChatHeight)
  document.removeEventListener('click', onClickOutside)
  if (popupShowTimer) { clearTimeout(popupShowTimer); popupShowTimer = null }
  if (popupHideTimer) { clearTimeout(popupHideTimer); popupHideTimer = null }
})

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

  messages.value.push({ role: 'user', content: text })
  inputText.value = ''
  resetTextarea()
  isLoading.value = true
  isStreaming.value = false
  await nextTick()
  scrollToBottom()

  const history = messages.value
    .slice(1, -1)
    .map(m => ({ role: m.role, content: m.content }))

  messages.value.push({ role: 'assistant', content: '' })
  const assistantMsg = messages.value[messages.value.length - 1]

  try {
    const response = await fetch('/api/chat', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ message: text, history }),
    })

    if (!response.ok) {
      const errData = await response.json().catch(() => ({}))
      assistantMsg.content = errData.error || 'El asistente no está disponible ahora mismo.'
      return
    }

    const reader = response.body.getReader()
    const decoder = new TextDecoder('utf-8')
    let buffer = ''

    while (true) {
      const { done, value } = await reader.read()
      if (done) break

      buffer += decoder.decode(value, { stream: true })
      const lines = buffer.split('\n')
      buffer = lines.pop() || ''

      for (const line of lines) {
        if (!line.startsWith('data: ')) continue
        const data = line.slice(6)
        if (data === '[DONE]') break
        try {
          const json = JSON.parse(data)
          if (json.error) {
            assistantMsg.content = json.error
            return
          }
          if (json.token) {
            if (!isStreaming.value) {
              isStreaming.value = true
              isLoading.value = false
            }
            assistantMsg.content += json.token
          }
        } catch { /* skip malformed SSE */ }
      }
    }

    if (!assistantMsg.content) {
      assistantMsg.content = 'No he podido obtener respuesta.'
    }
  } catch (err) {
    if (!assistantMsg.content) {
      assistantMsg.content = 'El asistente no está disponible ahora mismo. Intenta más tarde.'
    }
  } finally {
    isLoading.value = false
    isStreaming.value = false
    await nextTick()
    scrollToBottom()
  }
}

function handleEnter(e) {
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
  width: min(490px, calc(100vw - 56px));
  max-height: calc(100vh - 28px);
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

/* Popup "¡Hola!" */
.chat-popup {
  position: absolute;
  bottom: 72px;
  right: 0;
  background: #222222;
  color: var(--text-primary);
  padding: 12px 24px;
  border-radius: 12px;
  font-size: 24px;
  font-weight: 600;
  box-shadow: 0 4px 20px rgba(0,0,0,0.5);
  border: 1px solid rgba(255,255,255,0.08);
  white-space: nowrap;
  pointer-events: none;
}

.popup-fade-enter-active,
.popup-fade-leave-active {
  transition: opacity 0.4s ease, transform 0.4s ease;
}
.popup-fade-enter-from,
.popup-fade-leave-to {
  opacity: 0;
  transform: translateY(8px);
}

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
  width: 100%;
  max-height: 700px;
  min-height: 350px;
  border-radius: 20px;
  display: flex;
  flex-direction: column;
  overflow: hidden;
  border: 1px solid rgba(255,255,255,0.08);
  box-shadow: 0 8px 40px rgba(0,0,0,0.5);
  background: #1a1a1a;
}

/* Header */
.chat-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 16px 20px;
  border-bottom: 1px solid rgba(255,255,255,0.08);
  background: #222222;
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
  background: #2a2a2a;
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

/* Spinner primer mensaje */
.message-bubble.loading-spinner {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 14px 16px;
}
.spinner-ring {
  width: 18px;
  height: 18px;
  border: 2px solid rgba(255,255,255,0.15);
  border-top-color: var(--accent);
  border-radius: 50%;
  animation: spin 0.8s linear infinite;
  flex-shrink: 0;
}
.spinner-text {
  font-size: 13px;
  color: var(--text-secondary);
}
@keyframes spin {
  to { transform: rotate(360deg); }
}

/* Input */
.chat-input-area {
  display: flex;
  align-items: flex-end;
  gap: 8px;
  padding: 12px 16px;
  border-top: 1px solid rgba(255,255,255,0.08);
  background: #222222;
  flex-shrink: 0;
}
.chat-input-area textarea {
  flex: 1;
  background: #1e1e1e;
  border: 1px solid rgba(255,255,255,0.12);
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
@media (max-width: 600px) {
  .chat-widget { bottom: 16px; right: 16px; }
  .chat-window { width: calc(100vw - 32px); max-height: 500px; min-height: 300px; }
}
</style>
