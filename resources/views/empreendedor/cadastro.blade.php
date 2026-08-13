@extends('layouts.pwa')

@section('content')
<h2>Cadastro de Empreendedor</h2>
<form action="{{ route('empreendedor.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label>Tipo de Negócio</label>
        <select name="tipo" class="form-control" required>
            <option value="hospedagem">Hospedagem</option>
            <option value="gastronomia">Gastronomia</option>
            <option value="guia">Guia</option>
        </select>
    </div>
    <div class="mb-3">
        <label>Nome do Negócio</label>
        <input type="text" name="nome_negocio" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Upload Documento (Link mockup para MVP)</label>
        <input type="text" name="documento" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Solicitar Validação</button>
</form>
@endsection
