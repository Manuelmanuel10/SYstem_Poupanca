<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        {{-- ── Secção: Dados da Conta ── --}}
        <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-3">Dados de Acesso</p>

        <div>
            <x-input-label for="name" value="Nome completo" />
            <x-text-input id="name" class="block mt-1 w-full" type="text"
                name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="email" value="E-mail" />
            <x-text-input id="email" class="block mt-1 w-full" type="email"
                name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" value="Palavra-passe" />
            <x-text-input id="password" class="block mt-1 w-full" type="password"
                name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Confirmar palavra-passe" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        {{-- ── Secção: Dados do Negócio ── --}}
        <hr class="my-5 border-gray-200">
        <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-3">Dados do Negócio</p>

        <div>
            <x-input-label for="nome_negocio" value="Nome do negócio / organização" />
            <x-text-input id="nome_negocio" class="block mt-1 w-full" type="text"
                name="nome_negocio" :value="old('nome_negocio')" required />
            <x-input-error :messages="$errors->get('nome_negocio')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="telefone" value="Telefone (opcional)" />
            <x-text-input id="telefone" class="block mt-1 w-full" type="text"
                name="telefone" :value="old('telefone')" placeholder="+258 8X XXX XXXX" />
            <x-input-error :messages="$errors->get('telefone')" class="mt-2" />
        </div>

        {{-- ── Secção: Escolha do Plano ── --}}
        <hr class="my-5 border-gray-200">
        <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-3">Escolha o seu Plano</p>

        <x-input-error :messages="$errors->get('plano')" class="mb-2" />

        <div class="grid grid-cols-1 gap-3" id="planos">

            {{-- Plano Básico --}}
            <label class="plano-card flex items-start gap-3 border-2 rounded-lg p-4 cursor-pointer
                          {{ old('plano') === 'basico' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-blue-300' }}">
                <input type="radio" name="plano" value="basico" class="mt-1 accent-blue-600"
                    {{ old('plano', 'basico') === 'basico' ? 'checked' : '' }}>
                <div class="flex-1">
                    <div class="flex justify-between items-center">
                        <span class="font-semibold text-gray-800">Básico</span>
                        <span class="text-sm font-bold text-blue-700">500 MZN<span class="text-gray-400 font-normal">/mês</span></span>
                    </div>
                    <p class="text-xs text-gray-500 mt-0.5">Até 3 grupos · Até 30 membros por grupo</p>
                </div>
            </label>

            {{-- Plano Standard --}}
            <label class="plano-card flex items-start gap-3 border-2 rounded-lg p-4 cursor-pointer
                          {{ old('plano') === 'standard' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-blue-300' }}">
                <input type="radio" name="plano" value="standard" class="mt-1 accent-blue-600"
                    {{ old('plano') === 'standard' ? 'checked' : '' }}>
                <div class="flex-1">
                    <div class="flex justify-between items-center">
                        <span class="font-semibold text-gray-800">Standard</span>
                        <span class="text-sm font-bold text-blue-700">1.000 MZN<span class="text-gray-400 font-normal">/mês</span></span>
                    </div>
                    <p class="text-xs text-gray-500 mt-0.5">Até 10 grupos · Membros ilimitados · Relatórios PDF</p>
                </div>
            </label>

            {{-- Plano Premium --}}
            <label class="plano-card flex items-start gap-3 border-2 rounded-lg p-4 cursor-pointer
                          {{ old('plano') === 'premium' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-blue-300' }}">
                <input type="radio" name="plano" value="premium" class="mt-1 accent-blue-600"
                    {{ old('plano') === 'premium' ? 'checked' : '' }}>
                <div class="flex-1">
                    <div class="flex justify-between items-center">
                        <span class="font-semibold text-gray-800">Premium</span>
                        <span class="text-sm font-bold text-blue-700">2.000 MZN<span class="text-gray-400 font-normal">/mês</span></span>
                    </div>
                    <p class="text-xs text-gray-500 mt-0.5">Grupos ilimitados · Membros ilimitados · Relatórios + suporte prioritário</p>
                </div>
            </label>
        </div>

        <p class="text-xs text-gray-400 mt-2">30 dias de acesso gratuito após o registo. Pagamento posterior.</p>

        <div class="flex items-center justify-between mt-6">
            <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                Já tem conta?
            </a>
            <x-primary-button>Criar conta</x-primary-button>
        </div>
    </form>

    <script>
        // Destaca o plano seleccionado
        document.querySelectorAll('input[name="plano"]').forEach(radio => {
            radio.addEventListener('change', () => {
                document.querySelectorAll('.plano-card').forEach(card => {
                    card.classList.remove('border-blue-500', 'bg-blue-50');
                    card.classList.add('border-gray-200');
                });
                radio.closest('.plano-card').classList.add('border-blue-500', 'bg-blue-50');
                radio.closest('.plano-card').classList.remove('border-gray-200');
            });
        });
    </script>
</x-guest-layout>
