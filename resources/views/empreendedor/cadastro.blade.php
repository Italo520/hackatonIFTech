@extends('layouts.pwa')

@section('content')
<div class="container px-3 py-4" style="max-width: 600px;">
    <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
        <div class="d-flex align-items-center gap-3 mb-4">
            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: rgba(0, 95, 115, 0.1); color: var(--bs-primary);">
                <i class="bi bi-shop fs-4"></i>
            </div>
            <div>
                <h4 class="fw-bold text-dark mb-0">Cadastro de Parceiro</h4>
                <p class="text-muted small mb-0">Credencie seu negócio turístico e receba o Selo Oficial</p>
            </div>
        </div>

        <form action="{{ route('empreendedor.store') }}" method="POST" class="d-flex flex-column gap-3">
            @csrf
            <div>
                <label class="form-label fw-bold small text-secondary">Tipo de Atividade</label>
                <select name="tipo" class="form-select bg-light" required>
                    <option value="gastronomia">Gastronomia / Restaurante</option>
                    <option value="hospedagem">Pousada / Hotel / Hospedagem</option>
                    <option value="guia">Guia de Turismo / Passeios</option>
                    <option value="artesanato">Artesanato / Comércio Local</option>
                </select>
            </div>

            <div>
                <label class="form-label fw-bold small text-secondary">Nome do Estabelecimento / Empresa</label>
                <input type="text" name="nome_negocio" class="form-control bg-light" placeholder="Ex: Pousada Recanto dos Corais" required>
            </div>

            <div>
                <label class="form-label fw-bold small text-secondary">Documento / Alvará / Cadastur (Link ou Número)</label>
                <input type="text" name="documento" class="form-control bg-light" placeholder="Ex: CNPJ 00.000.000/0001-00 ou Link PDF" required>
            </div>

            <button type="submit" class="btn btn-primary rounded-pill py-2.5 fw-bold shadow-sm mt-2">
                <i class="bi bi-send-check me-1"></i> Enviar para Validação
            </button>
        </form>
    </div>
</div>
@endsection
