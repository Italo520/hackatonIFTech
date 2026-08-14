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

    <form method="POST" action="{{ route('password.store') }}" class="d-flex flex-column gap-3">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <label for="email" class="form-label fw-bold small text-secondary mb-1">E-mail</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" class="form-control bg-light border-start-0 ps-0 shadow-none">
            </div>
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="form-label fw-bold small text-secondary mb-1">Nova Senha</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                <input id="password" type="password" name="password" required autocomplete="new-password" class="form-control bg-light border-start-0 ps-0 shadow-none">
            </div>
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="form-label fw-bold small text-secondary mb-1">Confirmar Senha</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-shield-check"></i></span>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="form-control bg-light border-start-0 ps-0 shadow-none">
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2.5 rounded-3 fw-bold shadow-sm mt-2">
            Redefinir Senha
        </button>
    </form>
</x-guest-layout>
