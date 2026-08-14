<x-guest-layout>
    <div class="mb-3 text-secondary small text-center">
        Obrigado por se registrar! Antes de começar, por favor verifique seu endereço de e-mail clicando no link que acabamos de enviar.
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success rounded-3 small mb-3 text-center" role="alert">
            Um novo link de verificação foi enviado para o endereço de e-mail fornecido durante o cadastro.
        </div>
    @endif

    <div class="d-flex flex-column gap-2 mt-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn btn-primary w-100 py-2 rounded-3 fw-bold shadow-sm">
                Reenviar E-mail de Verificação
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-secondary w-100 py-2 rounded-3 small">
                Sair da Conta
            </button>
        </form>
    </div>
</x-guest-layout>
