@extends('layouts.admin')

@section('title', 'Dashboard KPIs')

@section('content')
<div class="row row-deck row-cards">
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Atrativos Ativos</div>
                </div>
                <div class="h1 mb-3">{{ \App\Models\Atrativo::where('status', 'ativo')->count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Eventos Programados</div>
                </div>
                <div class="h1 mb-3">{{ \App\Models\Evento::where('status', 'ativo')->count() }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
