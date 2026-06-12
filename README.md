# luissoler.dev — CV Web App

Portafolio personal SPA con chatbot IA integrado. Diseño propio inspirado en tendencias dark theme con glass morphism y acento `#0A84FF`.

**Demo en vivo**: [luissoler.dev](https://luissoler.dev)

---

## Stack

| Capa | Tecnología |
|------|------------|
| Backend | Laravel 11, PHP 8.3+ |
| Frontend | Vue 3 (Composition API) + Vite |
| Estilos | CSS Scoped + variables CSS globales + glass morphism |
| Docker | Laravel Sail (MySQL 8.4) |
| Chatbot IA | qwen3.6-35B vía llama.cpp (inferencia local) |
| Despliegue | Caddy + Sail en Ubuntu 24.04, DNS/SSL vía Cloudflare |

---

## Arquitectura

SPA de página única. Laravel sirve el shell HTML y dos endpoints API; Vue 3 gestiona toda la interfaz del cliente.

```
Navegador → cualquier URL
  ↓
Laravel (catch-all en web.php) → app.blade.php
  ↓
Vite carga app.css + app.js
  ↓
Vue 3 monta la SPA sobre <div id="app">
  ↓
Desde aquí, todo es cliente. Laravel solo interviene en:
  POST /api/contact  →  envía email vía Gmail SMTP
  POST /api/chat     →  proxy al modelo local (streaming SSE)
```

### Componentes Vue

Todos en `resources/js/components/`:

| Componente | Funcionalidad |
|------------|---------------|
| `NavBar.vue` | Navbar fija con blur al scroll, menú móvil responsive, scroll spy con `IntersectionObserver` |
| `HeroSection.vue` | Foto de perfil, nombre, título profesional, 3 botones CTA con jerarquía visual |
| `SkillsSection.vue` | Grid de tecnologías con iconos SVG y filtros por categoría |
| `ExperienceSection.vue` | Cards de experiencia laboral con hover effects |
| `EducationSection.vue` | Timeline de formación académica + certificaciones |
| `ProjectsSection.vue` | Slider horizontal de proyectos, modal de detalle, botón "Ver demo" al chatbot |
| `ContactSection.vue` | Formulario de contacto conectado a `POST /api/contact` → email |
| `FooterSection.vue` | Links sociales, copyright, botón "volver arriba" |
| `ChatWidget.vue` | Chatbot IA flotante con streaming SSE en tiempo real |

### Endpoints API

| Método | Ruta | Descripción |
|--------|------|-------------|
| `POST` | `/api/contact` | Valida formulario → envía email a través de `ContactFormMail` (Gmail SMTP) |
| `POST` | `/api/chat` | Proxy al modelo local `qwen3.6-35B` — devuelve tokens en streaming SSE |

---

## Chatbot IA

El chatbot es el elemento diferenciador del proyecto. Corresponde un modelo `qwen3.6-35B` en inferencia local mediante `llama.cpp`.

### Cómo funciona

```
Usuario escribe en ChatWidget.vue
  ↓
POST /api/chat { message, history }
  ↓
ChatController: valida input, aplica rate limit (20 req/min)
  ↓
Construye conversación: [system prompt ~1070 tokens] + [historial] + [mensaje]
  ↓
Llama a llama-server local → stream de tokens
  ↓
Backend retransmite tokens como SSE → frontend los renderiza token a token
```

### Características implementadas

- **Streaming real**: tokens llegan al navegador conforme el modelo los genera (`read(256)` + doble `flush()`)
- **Spinner de primer mensaje**: durante los ~5s de prompt processing del primer turno, se muestra "Recabando información..."
- **KV cache**: mensajes posteriores responden en <2s (el contexto se reutiliza)
- **Popup de bienvenida**: aparece a los 5s, se desvanece a los 10s si no hay interacción
- **Integración con proyectos**: el botón "Ver demo" del proyecto Portfolio abre directamente el chatbot
- **Rate limiting**: 20 peticiones/minuto por IP para prevenir abuso
- **Manejo de errores**: 429 (límite excedido), 503 (modelo no disponible)

---

## Estructura del proyecto

```
resources/js/
├── app.js                          # Entry point: crea y monta app Vue
├── bootstrap.js                    # Configura axios global + cabeceras CSRF
├── App.vue                         # Raíz SPA: orquesta componentes + scroll + ChatWidget
└── components/
    ├── NavBar.vue
    ├── HeroSection.vue
    ├── SkillsSection.vue
    ├── ExperienceSection.vue
    ├── EducationSection.vue
    ├── ProjectsSection.vue
    ├── ContactSection.vue
    ├── FooterSection.vue
    └── ChatWidget.vue              # Chatbot IA con streaming SSE

resources/css/
└── app.css                         # Variables CSS globales + resets + scrollbar

resources/views/
├── app.blade.php                   # Shell HTML: <div id="app"> + @vite()
└── emails/contact.blade.php        # Template del email del formulario de contacto

app/
├── Http/Controllers/
│   └── ChatController.php          # Backend del chatbot: validación, rate limit, streaming
└── Mail/
    └── ContactFormMail.php         # Mailable del formulario de contacto

routes/
├── web.php                         # Catch-all GET → app.blade.php (SPA)
└── api.php                         # POST /api/contact + POST /api/chat

public/
├── images/                         # Assets estáticos (foto, proyectos, skills)
└── cv/                             # CV en PDF descargable
```

---

## Desarrollo local

Todos los comandos se ejecutan a través de Laravel Sail (Docker):

```bash
# Levantar entorno
sail up -d

# Instalar dependencias (primera vez)
sail npm install

# Servidor de desarrollo con HMR
sail npm run dev

# Build de producción
sail npm run build

# Detener
sail down
```

La app responde en `http://localhost` (puerto 80). Vite en `http://localhost:5173` (solo desarrollo).

> Si `sail` no está en el PATH: usa `./vendor/bin/sail <comando>`

### Flujo de trabajo típico

1. `sail up -d` (si no está corriendo)
2. `sail npm run dev` (Vite en modo watch con HMR)
3. Editar cualquier `.vue` → el navegador se actualiza automáticamente
4. Cambios en PHP se aplican sin reiniciar (Laravel sirve cada petición en frío)
5. Cambios en `.env` → `sail down && sail up -d`

---

## Personalización de datos

Los datos del CV están como objetos/arrays en el `<script setup>` de cada componente:

| Componente | Qué editar |
|------------|------------|
| `HeroSection.vue` | `data.name`, `data.title`, `data.description`, `data.avatar` |
| `SkillsSection.vue` | Array `skills` con categorías y tecnologías |
| `ExperienceSection.vue` | Array `jobs` con empresa, cargo, fechas, logros |
| `EducationSection.vue` | Arrays `education` y `certifications` |
| `ProjectsSection.vue` | Array `projects` con nombre, tech, links |
| `ContactSection.vue` | Email, teléfono, ubicación, redes |
| `FooterSection.vue` | Links de redes sociales y copyright |
| `NavBar.vue` | `logo-text` (nombre en el logo) |
| `ChatController.php` | `$systemPrompt` (lo que el chatbot sabe sobre ti) |

### Imágenes

Colócalas en `public/images/` y referencia como `/images/archivo.jpg`.

### Colores

En `resources/css/app.css`, variables en `:root`:

```css
--accent:         #0A84FF;   /* Color principal */
--accent-hover:   #409CFF;
--bg-primary:     #000000;
--bg-secondary:   #0a0a0a;
--text-primary:   #ffffff;
--text-secondary: #a1a1a1;
--glass-bg:       rgba(255,255,255,0.05);
--glass-border:   rgba(255,255,255,0.1);
```

---

## Historial de cambios

### v0.4 — UI refinements
- **NavBar**: indicador de sección activa con `IntersectionObserver` (línea azul bajo enlace activo)
- **NavBar**: botón "Contacto" igualado visualmente al resto del menú
- **HeroSection**: tercer botón CTA "¡Contáctame!" con estilo `btn-ghost`

### v0.3 — Foto de perfil
- `public/images/` para assets estáticos
- Foto de perfil integrada en HeroSection

### v0.2 — Fix de arranque
- `resources/js/bootstrap.js`: configura axios + cabeceras CSRF

### v0.1 — Prototipo inicial
- 8 componentes Vue + shell Blade + catch-all SPA
- Variables CSS globales, clase `.glass`, sistema de diseño centralizado

### v1.0 — Chatbot IA + formulario de contacto funcional
- **ChatWidget.vue**: chatbot flotante con burbuja, popup de bienvenida, historial en memoria
- **ChatController.php**: backend con rate limiting, validación, proxy a modelo local
- **Streaming SSE real**: tokens llegan al frontend conforme el modelo los genera
- **Spinner "Recabando información"**: feedback durante los ~5s de prompt processing del primer mensaje
- **Formulario de contacto**: conectado a `POST /api/contact` → envío real de email vía Gmail SMTP
- **Integración Projects → Chatbot**: botón "Ver demo" abre el chatbot vía `CustomEvent`
- **Auto-scroll inteligente**: desactivado durante streaming, se ejecuta al finalizar respuesta

---

## Licencia

Código abierto para aprendizaje y referencia. Diseño y contenido del CV son propiedad de Luis Soler Valdivia.
