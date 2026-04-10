<div class="w-full max-w-sm">
    <div class="bg-gray-800 rounded-2xl shadow-xl p-8">
        <div class="text-center mb-8">
            @if(file_exists(public_path('images/logo.png')))
                <img src="/images/logo.png" alt="MAE" class="h-12 mx-auto mb-4">
            @endif
            <h1 class="text-2xl font-bold text-white">Mangueras y Conexiones <span class="text-orange-500">MAE</span></h1>
            <p class="text-gray-400 text-sm mt-1">Panel de administración</p>
        </div>

        <form wire:submit="login" class="space-y-4">
            <div>
                <label class="block text-sm text-gray-300 mb-1">Correo electrónico</label>
                <input wire:model="email" type="email" autocomplete="email"
                    class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-orange-500">
                @error('email') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm text-gray-300 mb-1">Contraseña</label>
                <input wire:model="password" type="password" autocomplete="current-password"
                    class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-orange-500">
                @error('password') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <button type="submit"
                class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2.5 rounded-lg text-sm transition-colors">
                Iniciar sesión
            </button>
        </form>
    </div>
</div>
