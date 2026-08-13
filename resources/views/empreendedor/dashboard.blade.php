@extends('layouts.pwa')

@section('content')
<h2>Painel do Empreendedor</h2>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($prestador)
    <div class="card mt-3">
        <div class="card-body">
            <h5>Meu Negócio: {{ $prestador->dados['nome_negocio'] ?? '' }}</h5>
            <p>Status: <strong>{{ strtoupper($prestador->status) }}</strong></p>
            @if($prestador->status === 'aprovado')
                <div class="alert alert-success">Selo: Validado pelo Município ✓</div>
            @endif
        </div>
    </div>
@else
    <p>Você não possui negócios cadastrados.</p>
    <a href="{{ route('empreendedor.create') }}" class="btn btn-primary">Cadastrar</a>
@endif

@endsection
