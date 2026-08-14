<x-guest-layout>
    <div class="mb-3 text-secondary small text-center">
        Esqueceu sua senha? Digite seu e-mail abaixo e enviaremos um link de redefinição.
    </div>

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

    <form method="POST" action="{{ route('password.email') }}" class="d-flex flex-column gap-3">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="form-label fw-bold small text-secondary mb-1">E-mail</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="form-control bg-light border-start-0 ps-0 shadow-none" placeholder="seu.email@exemplo.com">
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2.5 rounded-3 fw-bold shadow-sm mt-1">
            Enviar Link de Redefinição
        </button>

        <div class="text-center mt-2">
            <a href="{{ route('login') }}" class="small text-decoration-none text-primary">
                <i class="bi bi-arrow-left"></i> Voltar ao login
            </a>
        </div>
    </form>
</x-guest-layout>
