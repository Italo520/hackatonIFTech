@extends('layouts.pwa')

@section('content')
<div class="d-flex flex-column h-100 bg-light" style="margin-top: -1rem;">
    <!-- Header Especial IA -->
    <div class="p-4 text-white shadow-sm" style="background: linear-gradient(135deg, var(--bs-primary), var(--bs-secondary)); border-bottom-left-radius: 24px; border-bottom-right-radius: 24px;">
        <div class="d-flex align-items-center gap-3 mb-2">
            <div class="rounded-circle d-flex align-items-center justify-content-center border border-white border-opacity-25" style="width: 48px; height: 48px; background: rgba(255,255,255,0.2); backdrop-filter: blur(4px);">
                <i class="bi bi-robot fs-4"></i>
            </div>
            <div>
                <h1 class="fw-bold fs-5 mb-0" style="letter-spacing: -0.01em;">Guia Bonito IA</h1>
                <span class="small text-white-50">Sempre online para ajudar</span>
            </div>
        </div>
        <p class="small text-white opacity-75 mt-3 mb-1">Como posso tornar sua viagem incrível hoje?</p>
    </div>

    <!-- Abas: Chat vs Gerador de Roteiro -->
    <div class="px-3" style="margin-top: -20px; z-index: 10;">
        <div class="bg-white rounded-pill shadow-sm border p-1 d-flex w-100">
            <button class="btn btn-primary rounded-pill flex-grow-1 fw-bold btn-sm" style="min-height: 36px;">Conversar</button>
            <button class="btn btn-link text-decoration-none text-secondary rounded-pill flex-grow-1 fw-semibold btn-sm" style="min-height: 36px;">Criar Roteiro</button>
        </div>
    </div>

    <!-- Chat Area -->
    <div class="flex-grow-1 px-3 py-4 overflow-auto no-scrollbar d-flex flex-column gap-3">
        
        <!-- Mensagem Bot -->
        <div class="d-flex gap-2 w-100" style="max-width: 90%;">
            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white flex-shrink-0" style="width: 32px; height: 32px; margin-top: 4px;">
                <i class="bi bi-robot small"></i>
            </div>
            <div class="bg-white p-3 shadow-sm border text-dark" style="border-radius: 16px; border-top-left-radius: 4px; font-size: 0.9rem;">
                Olá! Sou seu guia virtual com IA de Bonito-MS. Você pode me perguntar sobre:
                <ul class="mb-0 mt-2 text-primary fw-medium" style="padding-left: 1.2rem;">
                    <li>Onde comer peixe hoje?</li>
                    <li>O que fazer com crianças?</li>
                    <li>Qual a diferença entre a Gruta Azul e São Miguel?</li>
                </ul>
            </div>
        </div>

        <!-- Mensagem User -->
        <div class="d-flex gap-2 w-100 align-self-end flex-row-reverse" style="max-width: 90%;">
            <div class="p-3 shadow-sm text-white" style="background-color: var(--bs-primary); border-radius: 16px; border-top-right-radius: 4px; font-size: 0.9rem;">
                Onde posso comer pratos típicos no centro, que seja acessível para cadeirantes?
            </div>
        </div>

        <!-- Mensagem Bot com Card -->
        <div class="d-flex gap-2 w-100" style="max-width: 90%;">
            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white flex-shrink-0" style="width: 32px; height: 32px; margin-top: 4px;">
                <i class="bi bi-robot small"></i>
            </div>
            <div class="bg-white p-3 shadow-sm border text-dark w-100" style="border-radius: 16px; border-top-left-radius: 4px; font-size: 0.9rem;">
                <p class="mb-3">A <strong class="text-primary">Casa do João</strong> é perfeita para isso! Eles têm acessibilidade para cadeirantes e um dos melhores Pintados da região.</p>
                
                <a href="{{ route('pwa.atrativo', 4) }}" class="d-flex bg-light rounded-3 border text-decoration-none text-dark overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" style="width: 70px; height: 70px; object-fit: cover;">
                    <div class="p-2 d-flex flex-column justify-content-center">
                        <span class="small fw-bold mb-1">Casa do João</span>
                        <span class="text-secondary" style="font-size: 0.7rem;">Gastronomia • Centro</span>
                    </div>
                </a>
                
                <div class="d-flex gap-3 mt-3 pt-3 border-top">
                    <button class="btn btn-link p-0 text-decoration-none text-secondary d-flex align-items-center gap-1" style="font-size: 0.75rem;"><i class="bi bi-hand-thumbs-up"></i> Útil</button>
                    <button class="btn btn-link p-0 text-decoration-none text-secondary d-flex align-items-center gap-1" style="font-size: 0.75rem;"><i class="bi bi-hand-thumbs-down"></i> Impreciso</button>
                </div>
            </div>
        </div>

    </div>

    <!-- Input Area -->
    <div class="p-3 bg-white border-top pb-4">
        <div class="position-relative d-flex align-items-center">
            <button class="btn btn-link position-absolute start-0 text-secondary border-0 p-0 ms-3" style="z-index: 5;">
                <i class="bi bi-mic-fill fs-5"></i>
            </button>
            <input type="text" class="form-control rounded-pill bg-light border-0 ps-5 pe-5 shadow-none" placeholder="Pergunte ao guia..." style="height: 48px;">
            <button class="btn btn-primary position-absolute end-0 rounded-circle d-flex align-items-center justify-content-center me-1" style="width: 40px; height: 40px; z-index: 5;">
                <i class="bi bi-send-fill small"></i>
            </button>
        </div>
        <div class="text-center mt-2">
            <span class="text-secondary" style="font-size: 0.65rem;">A IA pode cometer erros. Verifique informações importantes.</span>
        </div>
    </div>
</div>
@endsection
