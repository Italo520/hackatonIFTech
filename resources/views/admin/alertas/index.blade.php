@extends('layouts.admin')

@section('title', 'Gestão de Alertas')

@section('content')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card mb-4">
    <div class="card-header"><h3 class="card-title">Novo Alerta</h3></div>
    <div class="card-body">
        <form action="{{ route('admin.alertas.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Título</label>
                <input type="text" name="titulo" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Corpo do Alerta</label>
                <textarea name="corpo" class="form-control" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Urgência</label>
                <select name="urgencia" class="form-select">
                    <option value="info">Info</option>
                    <option value="aviso">Aviso</option>
                    <option value="emergencia">Emergência</option>
                </select>
            </div>
            <button class="btn btn-primary" type="submit">Publicar Alerta</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table card-table table-vcenter">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Urgência</th>
                    <th>Data</th>
                </tr>
            </thead>
            <tbody>
                @foreach($alertas as $a)
                <tr>
                    <td>{{ $a->titulo }}</td>
                    <td><span class="badge bg-{{ $a->urgencia === 'emergencia' ? 'danger' : 'info' }}">{{ $a->urgencia }}</span></td>
                    <td>{{ $a->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
