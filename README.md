# CV Web App – Laravel 13 + Vue 3

Portafolio personal SPA basado en el diseño de cristian-gonzalez.dev.  
Tema negro, acento `#0A84FF`, glass morphism.

## Stack

- **Backend**: Laravel 13 (PHP 8.2+) con Laravel Sail (Docker)
- **Frontend**: Vue 3 (Composition API) + Vite
- **Estilos**: CSS Scoped por componente + variables CSS globales + glass morphism
- **Fuente**: SF Pro / Inter (sistema)
- **Base de datos**: MySQL 8.4 (via Sail)

---

## Estructura del proyecto

```
resources/js/
├── app.js                          # Entry point: monta Vue sobre #app
├── bootstrap.js                    # Configura axios globalmente
├── App.vue                         # Raíz de la SPA: compone secciones y gestiona scrollTo
└── components/
    ├── NavBar.vue                  # Navbar fija, blur al scroll, menú móvil, indicador de sección activa
    ├── HeroSection.vue             # Hero: foto, nombre, título, 3 botones CTA
    ├── SkillsSection.vue           # Grid de skills con filtros por categoría
    ├── ExperienceSection.vue       # Cards de experiencia laboral con hover
    ├── EducationSection.vue        # Timeline de educación + certificaciones
    ├── ProjectsSection.vue         # Slider horizontal + modal de detalle
    ├── ContactSection.vue          # Info de contacto + formulario (simulado)
    └── FooterSection.vue           # Footer con links y redes sociales

resources/css/
└── app.css                         # Variables CSS globales + utilidad .glass

resources/views/
└── app.blade.php                   # Shell HTML: <div id="app"> + @vite()

routes/
└── web.php                         # Catch-all → siempre devuelve app.blade.php

public/
└── images/                         # Imágenes servidas estáticamente
    └── LuisGuapo.jpeg              # Foto de perfil actual
```

---

## Comandos de desarrollo

```bash
# Levantar entorno Docker
sail up -d

# Instalar dependencias JS (primera vez o tras cambiar package.json)
sail npm install

# Servidor de desarrollo con HMR
sail npm run dev

# Build para producción
sail npm run build

# Detener Docker
sail down
```

La app corre en `http://localhost` (puerto 80). Vite corre en `http://localhost:5173`.

---

## Personalización de datos

Todos los datos están hardcodeados como objetos/arrays en el `<script setup>` de cada componente. No hay base de datos ni API todavía.

| Componente              | Qué editar                                      |
|-------------------------|-------------------------------------------------|
| `HeroSection.vue`       | `data.name`, `data.title`, `data.description`, `data.avatar` |
| `SkillsSection.vue`     | Array `skills` con categorías y tecnologías     |
| `ExperienceSection.vue` | Array `jobs` con empresa, cargo, fechas, logros |
| `EducationSection.vue`  | Arrays `education` y `certifications`           |
| `ProjectsSection.vue`   | Array `projects` con nombre, tech, links        |
| `ContactSection.vue`    | Email, teléfono, ubicación, redes               |
| `FooterSection.vue`     | Links de redes sociales y copyright             |
| `NavBar.vue`            | `logo-text` (nombre en el logo)                 |

### Imágenes

Coloca las imágenes directamente en `public/images/` y referencialas como `/images/archivo.jpg`.  
No uses `storage/`; no hay symlink configurado.

### Colores

En `resources/css/app.css`, variables en `:root`:

```css
--accent:       #0A84FF;   /* Color principal */
--accent-hover: #409CFF;
--bg-primary:   #000000;
--text-primary: #ffffff;
--text-secondary: #a1a1a1;
```

---

## Historial de cambios

### v0.1 — Prototipo inicial
- Generación del prototipo completo: `App.vue` + 8 componentes Vue
- Variables CSS globales y clase `.glass` en `app.css`
- Shell Blade (`app.blade.php`) con `@vite` y `<div id="app">`
- Ruta catch-all en `web.php` para SPA
- `vite.config.js` con plugins de Laravel y Vue
- `package.json` con dependencias mínimas (vue, vite, axios, laravel-vite-plugin)

### v0.2 — Fix de arranque
- Creación de `resources/js/bootstrap.js` (configura axios, cabeceras CSRF)
- Este archivo faltaba y causaba el error `Failed to resolve import "./bootstrap"`

### v0.3 — Foto de perfil
- Creación de `public/images/` como carpeta para assets estáticos
- Foto de perfil ubicada en `public/images/LuisGuapo.jpeg`
- `HeroSection.vue`: `avatar` apunta a `/images/LuisGuapo.jpeg`

### v0.4 — Cambios rápidos de UI
- **NavBar**: eliminado el estilo azul del botón "Contacto" (se quitó el binding `:class="{ 'contact-btn': item.id === 'contact' }"` en desktop y mobile)
- **HeroSection**: añadido tercer botón "¡Contáctame!" con estilo `btn-ghost` (transparente, solo borde) que hace scroll a la sección `#contact`
- **NavBar**: implementado indicador de sección activa con `IntersectionObserver`; la variable reactiva `activeSection` se actualiza automáticamente al hacer scroll; el enlace activo recibe la clase `active` que muestra una línea azul (`::after`) bajo el texto

---

## Formulario de contacto (pendiente — backend)

El formulario en `ContactSection.vue` actualmente simula el envío con un `setTimeout`.  
Para conectarlo a Laravel:

```php
// routes/api.php
Route::post('/contact', [ContactController::class, 'send']);
```

```js
// ContactSection.vue — reemplazar la simulación por:
import axios from 'axios'
await axios.post('/api/contact', form.value)
```
