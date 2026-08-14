@extends('layouts.pwa')

@section('content')
<div class="container px-3 py-4" style="max-width: 650px;">
    <div class="card border-0 shadow-sm rounded-4 bg-white p-4 p-sm-5">
        <div class="d-flex align-items-center gap-3 mb-4">
            <div class="rounded-circle d-flex align-items-center justify-content-center text-primary" style="width: 52px; height: 52px; background: rgba(0, 95, 115, 0.1);">
                <i class="bi bi-shop fs-3"></i>
            </div>
            <div>
                <h4 class="fw-bold text-dark mb-0">Credenciamento de Empreendedor</h4>
                <p class="text-muted small mb-0">Cadastre seu negócio turístico e receba o Selo Oficial do Município</p>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show rounded-3 small mb-4" role="alert">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('empreendedor.store') }}" method="POST" class="d-flex flex-column gap-3">
            @csrf

            @guest
                <!-- Informações de Acesso (Nova Conta) -->
                <div class="p-3 rounded-4 bg-light border mb-2">
                    <h6 class="fw-bold text-dark mb-1 small text-uppercase" style="letter-spacing: 0.5px;">Dados de Acesso do Responsável</h6>
                    <p class="text-muted small mb-3">Estes dados serão usados para acessar o Painel do Parceiro.</p>

                    <div class="row g-2">
                        <div class="col-12">
                            <label class="form-label fw-bold small text-secondary mb-1">Nome do Responsável</label>
                            <input type="text" name="name" class="form-control bg-white shadow-none" value="{{ old('name') }}" placeholder="Ex: Maria Silva" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold small text-secondary mb-1">E-mail Profissional</label>
                            <input type="email" name="email" class="form-control bg-white shadow-none" value="{{ old('email') }}" placeholder="contato@seunegocio.com.br" required>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-bold small text-secondary mb-1">Senha de Acesso</label>
                            <input type="password" name="password" class="form-control bg-white shadow-none" placeholder="Mínimo 8 caracteres" required>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-bold small text-secondary mb-1">Confirmar Senha</label>
                            <input type="password" name="password_confirmation" class="form-control bg-white shadow-none" placeholder="Repita a senha" required>
                        </div>
                    </div>
                </div>
            @endguest

            <!-- Informações do Estabelecimento -->
            <div class="p-3 rounded-4 bg-light border">
                <h6 class="fw-bold text-dark mb-1 small text-uppercase" style="letter-spacing: 0.5px;">Dados do Estabelecimento Turístico</h6>
                <p class="text-muted small mb-3">Informações públicas que serão validadas e exibidas aos turistas.</p>

                <div class="d-flex flex-column gap-3">
                    <div>
                        <label class="form-label fw-bold small text-secondary mb-1">Tipo de Atividade</label>
                        <select name="tipo" class="form-select bg-white shadow-none" required>
                            <option value="gastronomia" {{ old('tipo') == 'gastronomia' ? 'selected' : '' }}>Gastronomia / Restaurante / Bar</option>
                            <option value="hospedagem" {{ old('tipo') == 'hospedagem' ? 'selected' : '' }}>Pousada / Hotel / Hospedagem</option>
                            <option value="guia" {{ old('tipo') == 'guia' ? 'selected' : '' }}>Guia de Turismo / Passeios & Mergulho</option>
                            <option value="artesanato" {{ old('tipo') == 'artesanato' ? 'selected' : '' }}>Artesanato / Comércio Cultural</option>
                            <option value="aventura" {{ old('tipo') == 'aventura' ? 'selected' : '' }}>Ecoturismo & Aventura</option>
                        </select>
                    </div>

                    <div>
                        <label class="form-label fw-bold small text-secondary mb-1">Nome Fantasia / Estabelecimento</label>
                        <input type="text" name="nome_negocio" class="form-control bg-white shadow-none" value="{{ old('nome_negocio') }}" placeholder="Ex: Pousada Recanto dos Corais" required>
                    </div>

                    <div>
                        <label class="form-label fw-bold small text-secondary mb-1">Município Sede</label>
                        <select name="municipio_id" class="form-select bg-white shadow-none">
                            @foreach($municipios as $m)
                                <option value="{{ $m->id }}" {{ old('municipio_id') == $m->id ? 'selected' : '' }}>{{ $m->nome }} ({{ $m->uf }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row g-2">
                        <div class="col-sm-6">
                            <label class="form-label fw-bold small text-secondary mb-1">Telefone / WhatsApp</label>
                            <input type="text" name="telefone" class="form-control bg-white shadow-none" value="{{ old('telefone') }}" placeholder="(83) 99999-0000">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-bold small text-secondary mb-1">CNPJ / CPF / Cadastur</label>
                            <input type="text" name="documento" class="form-control bg-white shadow-none" value="{{ old('documento') }}" placeholder="00.000.000/0001-00" required>
                        </div>
                    </div>

                    <div>
                        <label class="form-label fw-bold small text-secondary mb-1">Endereço Completo</label>
                        <input type="text" name="endereco" class="form-control bg-white shadow-none" value="{{ old('endereco') }}" placeholder="Rua, número, bairro">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary rounded-pill py-3 fw-bold shadow-sm mt-2">
                <i class="bi bi-send-check me-1"></i> Enviar Cadastro para Validação
            </button>

            <div class="text-center mt-2">
                <a href="{{ route('login') }}" class="small text-decoration-none text-muted">
                    Já é parceiro cadastrado? Faça login aqui
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
