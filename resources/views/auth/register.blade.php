<x-guest-layout>
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-3 small mb-3" role="alert">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="d-flex flex-column gap-3">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="form-label fw-bold small text-secondary mb-1">Nome Completo</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-person"></i></span>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" class="form-control bg-light border-start-0 ps-0 shadow-none" placeholder="Seu nome">
            </div>
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="form-label fw-bold small text-secondary mb-1">E-mail</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" class="form-control bg-light border-start-0 ps-0 shadow-none" placeholder="seu.email@exemplo.com">
            </div>
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="form-label fw-bold small text-secondary mb-1">Senha</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                <input id="password" type="password" name="password" required autocomplete="new-password" class="form-control bg-light border-start-0 ps-0 shadow-none" placeholder="Mínimo 8 caracteres">
            </div>
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="form-label fw-bold small text-secondary mb-1">Confirmar Senha</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-shield-check"></i></span>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="form-control bg-light border-start-0 ps-0 shadow-none" placeholder="Repita a senha">
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2.5 rounded-3 fw-bold shadow-sm mt-2">
            Criar Conta
        </button>

        <div class="text-center mt-2">
            <a href="{{ route('login') }}" class="small text-decoration-none text-primary">
                Já possui uma conta? Entrar
            </a>
        </div>
    </form>
</x-guest-layout>
