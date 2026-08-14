<x-guest-layout>
    <!-- Session Status / Alerts -->
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 small mb-3" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

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

    <form method="POST" action="{{ route('login') }}" class="d-flex flex-column gap-3">
        @csrf

        <!-- Email -->
        <div>
            <label for="email" class="form-label fw-bold small text-secondary mb-1">E-mail</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                <input id="email" type="email" name="email" value="{{ old('email', 'super_admin@demo.com') }}" required autofocus autocomplete="username" class="form-control bg-light border-start-0 ps-0 shadow-none @error('email') is-invalid @enderror" placeholder="seu.email@exemplo.com">
            </div>
        </div>

        <!-- Senha -->
        <div>
            <div class="d-flex justify-content-between align-items-center mb-1">
                <label for="password" class="form-label fw-bold small text-secondary mb-0">Senha</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="small text-decoration-none text-primary" style="font-size: 0.78rem;">Esqueceu a senha?</a>
                @endif
            </div>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                <input id="password" type="password" name="password" value="password" required autocomplete="current-password" class="form-control bg-light border-start-0 ps-0 shadow-none @error('password') is-invalid @enderror" placeholder="••••••••">
            </div>
        </div>

        <!-- Lembrar-me -->
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="remember" id="remember_me" checked>
            <label class="form-check-label small text-secondary" for="remember_me">
                Lembrar neste dispositivo
            </label>
        </div>

        <!-- Botão Entrar -->
        <button type="submit" class="btn btn-primary w-100 py-2.5 rounded-3 fw-bold shadow-sm mt-1">
            <i class="bi bi-box-arrow-in-right me-1"></i> Acessar Painel
        </button>
    </form>

    <!-- Contas de Demonstração Rápidas -->
    <div class="mt-4 pt-3 border-top">
        <div class="text-muted fw-bold small text-uppercase mb-2 text-center" style="font-size: 0.68rem; letter-spacing: 0.5px;">
            Acesso Rápido de Teste (Demo)
        </div>
        <div class="d-flex flex-wrap gap-1 justify-content-center">
            <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-2.5 py-1 demo-login-btn" style="font-size: 0.72rem;" data-email="super_admin@demo.com" data-pass="password" title="Acesso Super Administrador">
                <i class="bi bi-shield-lock-fill text-primary"></i> Super Admin
            </button>
            <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-2.5 py-1 demo-login-btn" style="font-size: 0.72rem;" data-email="gestor_conteudo@demo.com" data-pass="password" title="Gestor de Conteúdo">
                <i class="bi bi-pencil-square text-success"></i> Gestor Conteúdo
            </button>
            <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-2.5 py-1 demo-login-btn" style="font-size: 0.72rem;" data-email="empreendedor@demo.com" data-pass="password" title="Empreendedor">
                <i class="bi bi-shop text-warning"></i> Empreendedor
            </button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.demo-login-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const emailInput = document.getElementById('email');
                    const passInput = document.getElementById('password');
                    if (emailInput) emailInput.value = this.getAttribute('data-email');
                    if (passInput) passInput.value = this.getAttribute('data-pass');
                });
            });
        });
    </script>
</x-guest-layout>
