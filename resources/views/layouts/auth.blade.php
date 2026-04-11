<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MAE - Iniciar sesión</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-[#1e3a5f] min-h-screen flex items-center justify-center" style="background-image: url('https://images.pexels.com/photos/4483610/pexels-photo-4483610.jpeg?auto=compress&cs=tinysrgb&w=1600'); background-size: cover; background-position: center;">
    {{ $slot }}
    @livewireScripts
</body>
</html>
