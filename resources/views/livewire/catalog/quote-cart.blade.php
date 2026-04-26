<div
    x-data="{
        storageKey: 'mae.quote-cart',
        hasRestored: false,
        items: $wire.entangle('items').live,
        showCart: $wire.entangle('showCart').live,
        currentStep: $wire.entangle('currentStep').live,
        submitted: $wire.entangle('submitted').live,
        name: $wire.entangle('name').live,
        phone: $wire.entangle('phone').live,
        company: $wire.entangle('company').live,
        email: $wire.entangle('email').live,
        city: $wire.entangle('city').live,
        notes: $wire.entangle('notes').live,
        init() {
            this.restore();

            [
                'items',
                'showCart',
                'currentStep',
                'submitted',
                'name',
                'phone',
                'company',
                'email',
                'city',
                'notes',
            ].forEach((property) => {
                this.$watch(property, () => this.persist());
            });
        },
        restore() {
            const rawState = localStorage.getItem(this.storageKey);

            if (! rawState) {
                this.hasRestored = true;
                return;
            }

            try {
                const state = JSON.parse(rawState);
                $wire.restoreStateFromBrowser(state);
            } catch (error) {
                localStorage.removeItem(this.storageKey);
            }

            this.hasRestored = true;
        },
        persist() {
            if (! this.hasRestored) {
                return;
            }

            if (this.submitted) {
                localStorage.removeItem(this.storageKey);
                return;
            }

            if (Object.keys(this.items ?? {}).length === 0 && ! this.name && ! this.phone && ! this.company && ! this.email && ! this.city && ! this.notes) {
                localStorage.removeItem(this.storageKey);
                return;
            }

            localStorage.setItem(this.storageKey, JSON.stringify({
                items: this.items,
                showCart: this.showCart,
                currentStep: this.currentStep,
                name: this.name,
                phone: this.phone,
                company: this.company,
                email: this.email,
                city: this.city,
                notes: this.notes,
            }));
        },
    }"
>
    <button @click="$wire.showCart = true" class="relative inline-flex h-11 items-center gap-2 rounded-full border border-white/12 bg-white/6 px-4 text-sm font-semibold text-[#c7d3e0] transition hover:border-mae-gold/50 hover:text-white">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        <span class="hidden sm:inline">Cotización</span>
        @if($count > 0)
            <span class="absolute -right-1 -top-1 flex h-5 w-5 items-center justify-center rounded-full bg-mae-gold text-[11px] font-bold text-[#09131f]">{{ $count }}</span>
        @endif
    </button>

    <template x-teleport="body">
    <div x-cloak x-show="$wire.showCart" x-transition.opacity class="fixed inset-0 z-[9999] flex items-stretch justify-end" style="display:none">
        <div class="absolute inset-0 bg-[#02060d]/72 backdrop-blur-[2px]" @click="$wire.showCart = false"></div>

        <div class="relative flex w-full max-w-lg flex-col overflow-hidden border-l border-white/10 bg-[#071220]/96 text-white shadow-[0_24px_80px_rgba(0,0,0,0.48)] backdrop-blur-xl" @click.stop>
            <div class="flex items-center justify-between border-b border-white/10 px-6 py-5">
                <div>
                    <p class="mae-kicker">Solicitud</p>
                    <h2 class="mt-1 font-display text-2xl font-bold uppercase tracking-[0.12em] text-white">Mi cotización</h2>
                </div>
                <button @click="$wire.showCart = false" class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-white/10 bg-white/5 text-[#a9bdd8] transition hover:border-mae-gold/60 hover:text-white">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            @if(! $submitted)
                <div class="px-6 pt-4">
                    <div class="grid grid-cols-2 gap-2">
                        <div @class([
                            'rounded-xl border px-3 py-2.5 text-center font-display text-xs font-semibold uppercase tracking-[0.14em]',
                            'border-mae-gold/70 bg-mae-gold text-[#09131f]' => $currentStep === 1,
                            'border-white/10 bg-white/5 text-[#9fb1c9]' => $currentStep !== 1,
                        ])>
                            1. Resumen
                        </div>
                        <div @class([
                            'rounded-xl border px-3 py-2.5 text-center font-display text-xs font-semibold uppercase tracking-[0.14em]',
                            'border-mae-gold/70 bg-mae-gold text-[#09131f]' => $currentStep === 2,
                            'border-white/10 bg-white/5 text-[#9fb1c9]' => $currentStep !== 2,
                        ])>
                            2. Contacto
                        </div>
                    </div>
                </div>
            @endif

            <div class="flex-1 overflow-y-auto px-6 py-4">
                @if($submitted)
                    <div class="py-12 text-center">
                        <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full border border-mae-gold/40 bg-mae-gold/12">
                            <svg class="h-8 w-8 text-mae-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <h3 class="font-display text-2xl font-bold uppercase tracking-[0.12em] text-white">Cotización enviada</h3>
                        <p class="mt-2 text-sm text-[#c5d3e2]">Tu solicitud ya quedó registrada.</p>
                        <p class="mb-6 mt-2 text-sm font-semibold text-mae-gold">Folio: {{ $submittedQuoteFolio }}</p>
                        <a href="https://wa.me/525542305373" target="_blank" class="inline-flex items-center gap-2 rounded-lg bg-[#1f9d57] px-5 py-2.5 text-sm font-medium text-white transition hover:bg-[#168049]">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            Contactar por WhatsApp
                        </a>
                        <button wire:click="startNewQuote" class="mx-auto mt-3 block text-sm font-medium text-[#a9bdd8] transition hover:text-white">
                            Nueva cotización
                        </button>
                    </div>
                @elseif($currentStep === 2)
                    <div>
                        <button wire:click="backToSummaryStep" class="mb-4 flex items-center gap-1 text-sm font-medium text-[#a9bdd8] transition hover:text-white">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            Volver al resumen
                        </button>
                        <h3 class="mb-1 font-display text-2xl font-bold uppercase tracking-[0.12em] text-white">Tus datos de contacto</h3>
                        <p class="mb-4 text-sm text-[#c5d3e2]">Déjanos lo necesario para prepararte una cotización más precisa.</p>

                        <form wire:submit.prevent="submitQuote" class="space-y-3">
                            <div>
                                <label class="mb-1 block text-sm text-[#a9bdd8]">Nombre <span class="text-red-400">*</span></label>
                                <input wire:model="name" type="text" class="mae-input">
                                @error('name') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="mb-1 block text-sm text-[#a9bdd8]">Teléfono / WhatsApp <span class="text-red-400">*</span></label>
                                <input wire:model="phone" type="text" class="mae-input">
                                @error('phone') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="col-span-2 sm:col-span-1">
                                    <label class="mb-1 block text-sm text-[#a9bdd8]">Empresa</label>
                                    <input wire:model="company" type="text" class="mae-input">
                                </div>
                                <div class="col-span-2 sm:col-span-1">
                                    <label class="mb-1 block text-sm text-[#a9bdd8]">Ciudad</label>
                                    <input wire:model="city" type="text" class="mae-input">
                                    @error('city') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            <div>
                                <label class="mb-1 block text-sm text-[#a9bdd8]">Correo electrónico</label>
                                <input wire:model="email" type="email" class="mae-input">
                                @error('email') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="mb-1 block text-sm text-[#a9bdd8]">Comentarios generales</label>
                                <textarea wire:model="notes" rows="3" class="mae-input" placeholder="Medidas especiales, urgencia, etc."></textarea>
                                @error('notes') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                            </div>
                            <div class="rounded-xl border border-white/10 bg-white/5 px-4 py-3">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-[#c5d3e2]">Productos</span>
                                    <span class="font-semibold text-white">{{ count($items) }}</span>
                                </div>
                            </div>
                            <button type="submit" wire:loading.attr="disabled" wire:target="submitQuote" class="mae-btn-primary mt-2 w-full justify-center py-3 font-display text-sm uppercase tracking-[0.14em] data-loading:pointer-events-none data-loading:opacity-60">
                                <span wire:loading.remove wire:target="submitQuote">Enviar cotización</span>
                                <span wire:loading.inline-flex wire:target="submitQuote" class="hidden items-center gap-2">
                                    <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                    </svg>
                                    Enviando...
                                </span>
                            </button>
                        </form>
                    </div>
                @else
                    @if(empty($items))
                        <div class="py-12 text-center text-[#a9bdd8]">
                            <svg class="mx-auto mb-3 h-12 w-12 text-[#5b7090]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <p class="font-display text-lg font-semibold uppercase tracking-[0.12em] text-white">Tu cotización está vacía.</p>
                        </div>
                    @else
                        <div class="mb-4 rounded-xl border border-mae-gold/20 bg-mae-gold/8 px-4 py-3">
                            <p class="text-sm font-medium text-white">Resumen de tu solicitud</p>
                            <p class="mt-1 text-xs text-[#d2deea]">Ajusta cantidades y agrega detalles por producto para que podamos cotizarte mejor.</p>
                        </div>
                        <div class="space-y-3">
                            @foreach($items as $productId => $item)
                                <div class="rounded-2xl border border-white/10 bg-white/4 p-4">
                                    <div class="flex items-start gap-3">
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-semibold text-white">{{ $item['name'] }}</p>
                                            <p class="mt-1 text-xs text-[#a9bdd8]">Unidad: {{ $item['unit'] }}</p>
                                        </div>
                                        <button wire:click="remove({{ $productId }})" type="button" class="text-[#6f84a3] transition hover:text-red-400">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>

                                    <div class="mt-3 flex items-center gap-2">
                                        <button wire:click="decrement({{ $productId }})" type="button" class="flex h-9 w-9 items-center justify-center rounded-full border border-white/10 bg-white/6 text-base font-bold text-white transition hover:border-mae-gold/50 hover:text-mae-gold">−</button>
                                        <input
                                            type="number"
                                            min="{{ $item['unit'] === 'metro' ? '0.1' : '1' }}"
                                            step="{{ $item['unit'] === 'metro' ? '0.1' : '1' }}"
                                            value="{{ $item['quantity'] }}"
                                            wire:change="updateQuantity({{ $productId }}, $event.target.value)"
                                            class="w-20 rounded-lg border border-white/10 bg-[#09182f]/80 px-3 py-2 text-center text-sm font-medium text-white focus:border-mae-gold focus:outline-none"
                                        >
                                        <button wire:click="increment({{ $productId }})" type="button" class="flex h-9 w-9 items-center justify-center rounded-full border border-white/10 bg-white/6 text-base font-bold text-white transition hover:border-mae-gold/50 hover:text-mae-gold">+</button>
                                        <span class="ml-auto rounded-full border border-mae-gold/20 bg-mae-gold/8 px-3 py-1 text-xs font-semibold text-mae-gold-soft">
                                            {{ $item['quantity'] }} {{ $item['unit'] }}
                                        </span>
                                    </div>

                                    <div class="mt-3">
                                        <label class="mb-1 block text-xs font-semibold text-[#c5d3e2]">Detalles del producto</label>
                                        <textarea
                                            rows="2"
                                            wire:change="updateItemNotes({{ $productId }}, $event.target.value)"
                                            class="mae-input text-sm"
                                            placeholder="Medida, tipo de conexión, presión, urgencia o alguna especificación"
                                        >{{ $item['notes'] }}</textarea>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endif
            </div>

            @if($currentStep === 1 && ! $submitted && ! empty($items))
                <div class="border-t border-white/10 px-6 py-4">
                    @error('items') <p class="mb-2 text-xs text-red-400">{{ $message }}</p> @enderror
                    <div class="mb-3 flex items-center justify-between text-sm">
                        <span class="font-medium text-[#c5d3e2]">Productos seleccionados</span>
                        <span class="font-semibold text-white">{{ count($items) }}</span>
                    </div>
                    <button wire:click="goToContactStep" type="button" class="mae-btn-primary mb-2 w-full justify-center py-3 font-display text-sm uppercase tracking-[0.14em]">
                        Continuar con mis datos
                    </button>
                    <a href="https://wa.me/525542305373" target="_blank" class="flex w-full items-center justify-center gap-2 rounded-lg bg-[#1f9d57] py-3 text-sm font-semibold text-white transition hover:bg-[#168049]">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        Contactar por WhatsApp
                    </a>
                </div>
            @endif
        </div>
    </div>
    </template>
</div>
