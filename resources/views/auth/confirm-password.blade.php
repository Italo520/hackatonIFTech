<x-guest-layout>
    <div class="mb-3 text-secondary small text-center">
        Esta é uma área segura do sistema. Por favor, confirme sua senha antes de continuar.
    </div>

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

    <form method="POST" action="{{ route('password.confirm') }}" class="d-flex flex-column gap-3">
        @csrf

        <!-- Password -->
        <div>
            <label for="password" class="form-label fw-bold small text-secondary mb-1">Senha</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                <input id="password" type="password" name="password" required autocomplete="current-password" class="form-control bg-light border-start-0 ps-0 shadow-none" placeholder="••••••••">
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2.5 rounded-3 fw-bold shadow-sm mt-1">
            Confirmar
        </button>
    </form>
</x-guest-layout>
