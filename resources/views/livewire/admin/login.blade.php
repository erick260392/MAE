<div class="w-full max-w-sm">
    <div class="mae-panel p-8">
        <div class="text-center mb-8">
            <div class="mx-auto mb-4 inline-flex rounded-2xl border border-mae-gold/40 bg-white/6 p-3">
                @if(file_exists(public_path('images/logo.png')))
                    <img src="/images/logo.png" alt="MAE" class="h-20 w-20 object-contain">
                @endif
            </div>
            <p class="mae-kicker">Acceso administrador</p>
            <h1 class="mt-2 font-display text-4xl font-bold uppercase tracking-[0.18em] text-white">MAE</h1>
            <p class="mt-2 text-sm text-[#a9bdd8]">Control de catálogo, cotizaciones y operación interna.</p>
        </div>

        <form wire:submit="login" class="space-y-4">
            <div>
                <label class="mb-1 block text-sm font-semibold text-[#d7e2ef]">Correo electrónico</label>
                <input wire:model="email" type="email" autocomplete="email"
                    class="mae-input">
                @error('email') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-1 block text-sm font-semibold text-[#d7e2ef]">Contraseña</label>
                <input wire:model="password" type="password" autocomplete="current-password"
                    class="mae-input">
                @error('password') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="mae-btn-primary w-full py-3">
                Iniciar sesión
            </button>
        </form>
    </div>
</div>
