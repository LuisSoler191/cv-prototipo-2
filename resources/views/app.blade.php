<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Luis Soler – Full Stack Developer</title>
    <meta name="description" content="Desarrollador web Full Stack especializado en Laravel y Vue 3. Construyendo aplicaciones modernas y escalables.">

    <!-- Open Graph -->
    <meta property="og:title" content="Luis Soler – Full Stack Developer">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:image" content="{{ url('/images/og-image.png') }}">
    <meta property="og:description" content="Desarrollador web junior especializado en back-end y full stack con Laravel y Vue.js. Experiencia en entornos de producción real e integración de IA local.">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/icono-ls.png">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div id="app"></div>
</body>
</html>
