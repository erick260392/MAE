<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Mangueras y Conexiones MAE — Hidráulicas y Neumáticas' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="text-white">

    <div class="pointer-events-none fixed inset-0 z-0 overflow-hidden">
        <div class="absolute left-0 top-0 h-28 w-28 border-l-[18px] border-t-[18px] border-mae-gold"></div>
        <div class="absolute right-0 top-0 h-28 w-28 border-r-[18px] border-t-[18px] border-mae-gold"></div>
        <div class="absolute bottom-0 left-0 h-28 w-28 border-b-[18px] border-l-[18px] border-mae-gold"></div>
        <div class="absolute bottom-0 right-0 h-28 w-28 border-b-[18px] border-r-[18px] border-mae-gold"></div>
    </div>

    <div class="relative z-10">
        <div class="border-b border-white/10 bg-[#050d19]/80 text-xs text-[#b7c6da] backdrop-blur-xl">
            <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-3 px-4 py-2 sm:px-6 lg:px-8">
                <div class="flex flex-wrap items-center gap-4">
                    <span class="mae-kicker !tracking-[0.24em]">Soluciones industriales desde 2015</span>
                    <span class="hidden h-4 w-px bg-white/12 sm:block"></span>
                    <span class="font-semibold text-white/80">Hidráulico</span>
                    <span class="font-semibold text-white/80">Neumático</span>
                    <span class="font-semibold text-white/80">Industrial</span>
                </div>
                <div class="flex flex-wrap items-center gap-4">
                    <span>Lun-Sáb 8am - 6pm</span>
                    <span class="hidden h-4 w-px bg-white/12 sm:block"></span>
                    <a href="mailto:conexiones.mangueras@hotmail.com" class="transition hover:text-white">conexiones.mangueras@hotmail.com</a>
                </div>
            </div>
        </div>

        {{-- Navbar --}}
        <header class="sticky top-0 z-40 border-b border-white/10 bg-[#071220]/86 shadow-[0_18px_60px_rgba(0,0,0,0.35)] backdrop-blur-xl">
            <div class="mx-auto flex h-20 max-w-7xl items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
                <a href="{{ route('catalog') }}" class="flex items-center gap-4">
                    <div class="rounded-2xl border border-mae-gold/40 bg-white/6 p-2 shadow-[0_12px_30px_rgba(0,0,0,0.22)]">
                        @if(file_exists(public_path('images/logo.png')))
                            <img src="/images/logo.png" alt="MAE" class="h-11 w-11 object-contain">
                        @endif
                    </div>
                    <div>
                        <p class="font-display text-3xl font-bold uppercase tracking-[0.18em] text-white">MAE</p>
                        <p class="font-display text-sm font-semibold uppercase tracking-[0.24em] text-mae-gold">Mangueras y Conexiones</p>
                    </div>
                </a>
                <div class="flex items-center gap-3 lg:gap-5">
                    <a href="tel:+525542305373" class="hidden items-center gap-2 text-sm font-semibold text-[#b9c7d9] transition hover:text-white xl:flex">
                        <svg class="h-4 w-4 text-mae-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        55 4230 5373
                    </a>
                    <a href="https://wa.me/525542305373" target="_blank" class="hidden items-center gap-2 text-sm font-semibold text-[#b9c7d9] transition hover:text-white lg:flex">
                        <svg class="h-4 w-4 text-mae-gold" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        WhatsApp
                    </a>
                    <a href="https://www.facebook.com/profile.php?id=61563654431552" target="_blank" rel="noopener noreferrer" class="hidden items-center gap-2 text-sm font-semibold text-[#b9c7d9] transition hover:text-white lg:flex">
                        <svg class="h-4 w-4 text-mae-gold" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12.07C22 6.477 17.523 2 12 2S2 6.477 2 12.07c0 5.055 3.657 9.245 8.438 9.93v-7.03H7.898v-2.9h2.54V9.845c0-2.522 1.492-3.915 3.777-3.915 1.094 0 2.238.197 2.238.197v2.476h-1.26c-1.241 0-1.628.775-1.628 1.57v1.897h2.773l-.443 2.9h-2.33V22c4.78-.685 8.437-4.875 8.437-9.93z"/></svg>
                        Facebook
                    </a>
                    <livewire:catalog.quote-cart />
                    <a href="{{ route('admin.login') }}" title="Acceso administrador" class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-white/12 bg-white/6 text-white transition hover:border-mae-gold/70 hover:text-mae-gold">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </a>
                </div>
            </div>
        </header>

        {{-- Contenido --}}
        <main>
            {{ $slot }}
        </main>

        {{-- Footer --}}
        <footer class="mt-16 border-t border-mae-gold/30 bg-[#050d19]/92 text-[#b8c6d8]">
            <div class="mx-auto grid max-w-7xl grid-cols-1 gap-8 px-4 py-12 sm:px-6 lg:grid-cols-[1.2fr_1fr_1fr] lg:px-8">
                <div class="space-y-4">
                    <p class="font-display text-5xl font-bold uppercase tracking-[0.18em] text-white">MAE</p>
                    <p class="font-display text-xl font-semibold uppercase tracking-[0.16em] text-mae-gold">Mangueras y Conexiones</p>
                    <p class="max-w-md text-sm leading-7 text-[#b8c6d8]">Calidad que se conecta, confianza que da seguridad y soluciones industriales que impulsan el trabajo de tus clientes desde 2015.</p>
                    <div class="flex flex-wrap gap-3 pt-2">
                        <span class="mae-pill">Hidráulico</span>
                        <span class="mae-pill">Neumático</span>
                        <span class="mae-pill">Industrial</span>
                    </div>
                </div>
                <div class="space-y-4">
                    <p class="mae-kicker">Contacto</p>
                    <div class="space-y-3 text-sm">
                        <a href="tel:+525542305373" class="flex items-center gap-3 transition hover:text-white">
                            <span class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-mae-gold/50 text-mae-gold">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            </span>
                            <span class="font-semibold text-white">+52 55 4230 5373</span>
                        </a>
                        <a href="mailto:conexiones.mangueras@hotmail.com" class="flex items-center gap-3 transition hover:text-white">
                            <span class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-mae-gold/50 text-mae-gold">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M2.25 6.75A2.25 2.25 0 014.5 4.5h15a2.25 2.25 0 012.25 2.25v10.5A2.25 2.25 0 0119.5 19.5h-15a2.25 2.25 0 01-2.25-2.25V6.75zm1.84-.53l7.41 5.64a.75.75 0 00.91 0l7.41-5.64"/></svg>
                            </span>
                            <span>conexiones.mangueras@hotmail.com</span>
                        </a>
                        <div class="flex items-center gap-3">
                            <span class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-mae-gold/50 text-mae-gold">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </span>
                            <span>Lun-Sáb 8am - 6pm</span>
                        </div>
                    </div>
                </div>
                <div class="space-y-4">
                    <p class="mae-kicker">Ubicación</p>
                    <div class="mae-card-dark p-5">
                        <p class="text-sm leading-7 text-[#d8e2ef]">Benito Juárez 33-29, Habitacional, 54038 Tlalnepantla, Méx.</p>
                        <div class="mt-4 flex flex-wrap gap-3">
                            <a href="https://www.google.com/maps/dir/?api=1&destination=Benito+Ju%C3%A1rez+33-29+Habitacional+54038+Tlalnepantla+Mexico" target="_blank" class="mae-btn-primary">Cómo llegar</a>
                            <a href="https://www.facebook.com/profile.php?id=61563654431552" target="_blank" rel="noopener noreferrer" class="mae-btn-secondary">Facebook</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="border-t border-white/10 py-4 text-center text-xs text-[#8ea4c1]">
                © {{ date('Y') }} Mangueras y Conexiones MAE. Todos los derechos reservados.
            </div>
        </footer>

        {{-- Botón flotante WhatsApp --}}
        <a href="https://wa.me/525542305373" target="_blank"
            class="fixed bottom-6 right-6 z-50 inline-flex h-14 w-14 items-center justify-center rounded-full border border-white/20 bg-mae-gold text-[#09131f] shadow-[0_18px_40px_rgba(244,179,26,0.3)] transition hover:scale-105 hover:bg-mae-gold-soft">
            <svg class="h-7 w-7" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
            </svg>
        </a>
    </div>

    @livewireScripts
</body>
</html>
