@extends('layouts.pwa')

@section('content')
<div class="row text-center mb-4">
    <div class="col-6 mb-3">
        <div class="card bg-primary text-white shadow-sm">
            <div class="card-body py-4">
                <h5 class="card-title">O que fazer</h5>
            </div>
        </div>
    </div>
    <div class="col-6 mb-3">
        <div class="card bg-success text-white shadow-sm">
            <div class="card-body py-4">
                <h5 class="card-title">Onde ficar</h5>
            </div>
        </div>
    </div>
    <div class="col-6 mb-3">
        <div class="card bg-warning text-dark shadow-sm">
            <div class="card-body py-4">
                <h5 class="card-title">Onde comer</h5>
            </div>
        </div>
    </div>
    <div class="col-6 mb-3">
        <div class="card bg-info text-white shadow-sm">
            <div class="card-body py-4">
                <h5 class="card-title">Eventos</h5>
            </div>
        </div>
    </div>

    <div class="col-12 mt-4">
        <div class="card border-danger text-danger shadow-sm">
            <div class="card-body py-3">
                <h5 class="card-title mb-0">Telefones de Emergência</h5>
            </div>
        </div>
    </div>
</div>
@endsection
