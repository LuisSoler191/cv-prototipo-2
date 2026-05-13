PROYECTO: CV Web App SPA
========================
Portafolio personal de Luis Soler Valdivia.
Stack: Laravel 13 + Vue 3 + Vite + Laravel Sail (Docker).
Diseño inspirado en cristian-gonzalez.dev: tema negro, acento #0A84FF, glass morphism.


ENTORNO Y COMANDOS
==================
El proyecto corre dentro de Docker vía Laravel Sail.
TODOS los comandos deben ejecutarse con el prefijo "sail", nunca directamente.

  sail up -d              # Levantar contenedores en background
  sail down               # Detener contenedores
  sail npm install        # Instalar dependencias JS
  sail npm run dev        # Dev server con HMR (Vite en puerto 5173)
  sail npm run build      # Build de producción (genera public/build/)
  sail artisan <cmd>      # Cualquier comando Artisan de Laravel
  sail php <cmd>          # Ejecutar PHP dentro del contenedor

La app responde en http://localhost (puerto 80).
Vite responde en http://localhost:5173 (solo en desarrollo).

Si "sail" no está en el PATH: ./vendor/bin/sail <comando>


ARQUITECTURA: CÓMO FUNCIONA TODO
=================================

1. FLUJO DE UNA PETICIÓN
   Navegador → cualquier URL
   → Laravel (routes/web.php) → catch-all → devuelve resources/views/app.blade.php
   → El navegador carga app.css + app.js (compilados por Vite)
   → Vue 3 monta la SPA sobre <div id="app">
   → A partir de aquí todo es JavaScript, Laravel no interviene más

2. RUTA CATCH-ALL (routes/web.php)
   Route::get('/{any?}', fn() => view('app'))->where('any', '.*');
   Una sola ruta. Cualquier URL devuelve el mismo blade.
   Sin esto, recargar la página en /contacto daría 404.

3. SHELL HTML (resources/views/app.blade.php)
   Contiene:
   - <meta name="csrf-token"> → necesario para peticiones POST futuras
   - <div id="app"></div> → aquí monta Vue
   - @vite(['resources/css/app.css', 'resources/js/app.js'])
     En dev: apunta a localhost:5173 (HMR activo)
     En prod: apunta a public/build/ (archivos con hash)

4. COMPILADOR (vite.config.js)
   Plugins:
   - laravel-vite-plugin: define los entry points, integra con @vite()
   - @vitejs/plugin-vue: transforma .vue → JS que el navegador entiende
   Alias: vue → vue/dist/vue.esm-bundler.js (incluye el compilador de templates)

5. ENTRY POINT (resources/js/app.js)
   import './bootstrap';        // Configura axios globalmente
   import '../css/app.css';     // Estilos globales
   import { createApp } from 'vue';
   import App from './App.vue';
   createApp(App).mount('#app'); // Vue toma control del <div id="app">

6. BOOTSTRAP (resources/js/bootstrap.js)
   import axios from 'axios';
   window.Axios = axios;
   axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
   Necesario para que Laravel reconozca peticiones AJAX y gestione CSRF.

7. ESTILOS GLOBALES (resources/css/app.css)
   Define variables CSS en :root (sistema de diseño centralizado):
     --bg-primary: #000000
     --bg-secondary: #0a0a0a
     --text-primary: #ffffff
     --text-secondary: #a1a1a1
     --accent: #0A84FF
     --accent-hover: #409CFF
     --glass-bg: rgba(255,255,255,0.05)
     --glass-border: rgba(255,255,255,0.1)
     --glass-shadow: rgba(0,0,0,0.3)
   Utilidad .glass: background glass-bg + backdrop-filter blur(10px)
   Scrollbar personalizada (fina, oscura)
   Los estilos de componentes individuales son scoped y NO interfieren aquí.

8. COMPONENTE RAÍZ (resources/js/App.vue)
   Orquesta todos los componentes hijos.
   Única lógica: función scrollTo(sectionId) que llama a
     document.getElementById(sectionId).scrollIntoView({ behavior: 'smooth' })
   Los hijos emiten el evento 'scroll-to' con el id de la sección destino.
   App.vue escucha @scroll-to="scrollTo" y ejecuta el scroll.
   Patrón: hijos emiten hacia arriba ($emit), el padre actúa.


COMPONENTES VUE — UBICACIÓN Y RESPONSABILIDAD
==============================================

Todos en resources/js/components/

NavBar.vue
  - Navbar fija (position: fixed, z-index: 1100)
  - Fondo transparente → blur oscuro al hacer scroll > 50px (clase .scrolled)
  - Array navItems define los enlaces: hero, skills, experience, education, projects, contact
  - Cada enlace emite 'scroll-to' con su id al hacer click
  - IntersectionObserver detecta qué sección ocupa ≥30% del viewport
    → actualiza activeSection (ref reactivo)
    → el enlace activo recibe clase .active → línea azul ::after debajo
  - Menú hamburguesa en móvil (<768px): slide-in desde la derecha
  - onMounted: registra listener de scroll + IntersectionObserver
  - onUnmounted: limpia listener + observer.disconnect() (evita memory leaks)

HeroSection.vue (id="hero")
  - Foto de perfil: <img> apuntando a data.avatar = '/images/LuisGuapo.jpeg'
  - Las imágenes van en public/images/ y se referencian como /images/archivo
  - Nombre, título, descripción: objeto data hardcodeado en <script setup>
  - 3 botones CTA:
      btn-secondary "Ver mi trabajo" → scroll a #projects
      btn-ghost "Descargar CV" → pendiente implementar descarga
      btn-primary "¡Contáctame!" → scroll a #contact
  - Animación float (gradiente superior derecha) + pulse (glow circular bajo la foto)
  - Responsive: columna en <968px, full-width buttons en <640px

SkillsSection.vue (id="skills")
  - Grid de tarjetas de tecnologías con filtros por categoría
  - Datos en array skills en el script

ExperienceSection.vue (id="experience")
  - Cards de experiencia laboral con hover
  - Datos en array jobs en el script

EducationSection.vue (id="education")
  - Timeline de formación académica + certificaciones
  - Datos en arrays education y certifications

ProjectsSection.vue (id="projects")
  - Slider horizontal de proyectos + modal de detalle
  - Datos en array projects

ContactSection.vue (id="contact")
  - Info de contacto (email, teléfono, ubicación)
  - Formulario con campos: nombre, email, asunto, mensaje
  - ACTUALMENTE: simula envío con setTimeout (no conectado al backend)
  - PENDIENTE: conectar a POST /api/contact con axios

FooterSection.vue
  - Links de redes sociales, copyright
  - Recibe @scroll-to de App.vue para el link "volver arriba"


PATRONES VUE 3 USADOS EN EL PROYECTO
======================================

ref(valor)
  Variable reactiva. Al cambiar .value, Vue re-renderiza lo que la usa.
  Ejemplo: const isScrolled = ref(false)

:class="{ nombre: condicion }"
  Añade/quita la clase CSS 'nombre' según la condición booleana.
  Ejemplo: :class="{ scrolled: isScrolled }"

v-for="item in array" :key="item.id"
  Renderiza un elemento por cada ítem del array.
  :key es obligatorio para que Vue identifique cada nodo eficientemente.

@click="funcion()" o @click="$emit('evento', dato)"
  Manejador de eventos. $emit lanza un evento personalizado hacia el padre.

defineEmits(['nombre-evento'])
  Declara los eventos que puede emitir este componente.
  El padre escucha con @nombre-evento="handler".

onMounted(() => { ... })
  Se ejecuta cuando el componente ya está en el DOM.
  Único momento seguro para interactuar con el navegador (window, document).

onUnmounted(() => { ... })
  Se ejecuta al destruir el componente.
  SIEMPRE limpiar aquí: removeEventListener, observer.disconnect(), clearInterval.

<style scoped>
  CSS local al componente. Los selectores no afectan a otros componentes.
  Vue añade un atributo data-v-xxxxxxxx a los elementos para aislarlos.


ARCHIVOS QUE NO DEBES TOCAR (generados o de Laravel core)
===========================================================
  bootstrap/app.php              # Bootstrapping de Laravel
  bootstrap/providers.php        # Registro de service providers
  config/                        # Configuración de Laravel (db, mail, cache...)
  database/migrations/           # Migraciones de base de datos
  composer.json / composer.lock  # Dependencias PHP (modificar solo con composer)
  package-lock.json              # Lockfile de npm (se regenera solo)
  public/build/                  # Output de "npm run build" (no editar a mano)
  storage/                       # Logs, caché, sesiones (no editar a mano)
  vendor/                        # Dependencias PHP instaladas (no editar)
  node_modules/                  # Dependencias JS instaladas (no editar)


ARCHIVOS QUE SÍ EDITAS HABITUALMENTE
======================================
  resources/js/components/*.vue  # Componentes: datos, lógica, estilos
  resources/js/App.vue           # Solo si añades nuevos componentes o lógica global
  resources/css/app.css          # Variables CSS, estilos globales
  resources/views/app.blade.php  # Solo si cambias el <head> (título, meta, favicon)
  routes/web.php                 # Si añades rutas de API (usar routes/api.php mejor)
  public/images/                 # Imágenes de perfil, proyectos, logos
  .env                           # Variables de entorno (no subir a git)


BASE DE DATOS
=============
Motor: MySQL 8.4 (contenedor Docker "mysql" en compose.yaml)
Conexión configurada en .env:
  DB_CONNECTION=mysql
  DB_HOST=mysql          ← nombre del servicio Docker, no "localhost"
  DB_PORT=3306
  DB_DATABASE=laravel
  DB_USERNAME=sail

Actualmente el proyecto NO usa la base de datos en el frontend.
Todos los datos del CV están hardcodeados en los componentes Vue.
Si en el futuro se añade un panel de administración o API:
  sail artisan migrate           # Crear tablas
  sail artisan make:model Foo -m # Crear modelo + migración


DOCKER / SAIL — DETALLES
=========================
compose.yaml define dos servicios:
  laravel.test: contenedor PHP 8.5 con Nginx, sirve la app en el puerto 80
  mysql: MySQL 8.4, expuesto en el puerto 3306

Vite dentro de Docker:
  El puerto 5173 está expuesto en compose.yaml.
  El HMR (Hot Module Replacement) funciona en el navegador del host
  porque Vite escucha en 0.0.0.0 dentro del contenedor.

Volumen principal:
  '.:/var/www/html' → el código fuente del host se monta directamente.
  Cualquier cambio en tu editor es inmediato dentro del contenedor.


FLUJO DE UN CAMBIO TÍPICO
==========================
1. sail up -d (si no está corriendo)
2. sail npm run dev (Vite en modo watch con HMR)
3. Editar cualquier .vue → el navegador se actualiza solo
4. Para cambios en PHP (rutas, controladores) → no hace falta reiniciar,
   Laravel sirve cada petición en frío (no hay estado persistente en PHP)
5. Para cambios en .env → sail down && sail up -d


ESTADO ACTUAL DEL PROYECTO (26 mayo 2026)
==========================================
Completado:
  - Prototipo visual completo (8 secciones)
  - Entorno Sail funcionando
  - Foto de perfil integrada
  - Navbar con indicador de sección activa (IntersectionObserver)
  - 3 botones CTA en el hero con jerarquía visual (primary / secondary / ghost)
  - Botón Contacto del navbar igualado visualmente al resto

Pendiente:
  - Formulario de contacto conectado al backend (actualmente simulado)
  - Descarga real del CV (botón "Descargar CV" sin acción)
  - Datos reales del CV en todos los componentes (skills, experiencia, proyectos)
  - Posibles mejoras: animaciones de entrada, modo claro, i18n, panel de admin
