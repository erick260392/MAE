<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MAE - Iniciar sesión</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen" style="background-image: linear-gradient(120deg, rgba(4,10,20,0.9), rgba(7,18,34,0.82)), url('https://images.pexels.com/photos/4483610/pexels-photo-4483610.jpeg?auto=compress&cs=tinysrgb&w=1600'); background-size: cover; background-position: center;">
    <div class="pointer-events-none fixed inset-0 overflow-hidden">
        <div class="absolute left-0 top-0 h-28 w-28 border-l-[18px] border-t-[18px] border-mae-gold"></div>
        <div class="absolute bottom-0 right-0 h-28 w-28 border-b-[18px] border-r-[18px] border-mae-gold"></div>
    </div>
    <div class="relative flex min-h-screen items-center justify-center px-4">
    {{ $slot }}
    </div>
    @livewireScripts
</body>
</html>
