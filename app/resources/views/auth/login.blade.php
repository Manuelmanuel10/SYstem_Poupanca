<x-guest-layout>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label fw-semibold text-secondary" style="font-size:13px;">ENDEREÇO DE EMAIL</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                    placeholder="seu@email.com" value="{{ old('email') }}" required autofocus>
            </div>
            @error('email')<div class="text-danger mt-1" style="font-size:13px;">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold text-secondary" style="font-size:13px;">PALAVRA-PASSE</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                    placeholder="••••••••" required>
            </div>
            @error('password')<div class="text-danger mt-1" style="font-size:13px;">{{ $message }}</div>@enderror
        </div>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label text-secondary" for="remember" style="font-size:14px;">Lembrar-me</label>
            </div>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" style="font-size:14px;">Esqueceu a senha?</a>
            @endif
        </div>
        <button type="submit" class="btn btn-login btn-primary w-100 text-white">
            <i class="fas fa-sign-in-alt me-2"></i>Entrar no Sistema
        </button>
    </form>
</x-guest-layout>
