@extends('layouts.admin')

@section('title', 'Fila de Validação de Empreendedores')

@section('content')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="table-responsive">
        <table class="table card-table table-vcenter text-nowrap datatable">
            <thead>
                <tr>
                    <th>Nome do Negócio</th>
                    <th>Tipo</th>
                    <th>Documentos</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($prestadores as $p)
                <tr>
                    <td>{{ $p->dados['nome_negocio'] ?? 'N/A' }}</td>
                    <td>{{ $p->tipo }}</td>
                    <td><a href="#">Ver Doc</a></td>
                    <td>
                        <form action="{{ route('admin.prestadores.update', $p->id) }}" method="POST" class="d-inline">
                            @csrf @method('PUT')
                            <input type="hidden" name="status" value="aprovado">
                            <button class="btn btn-success btn-sm">Aprovar</button>
                        </form>
                        <form action="{{ route('admin.prestadores.update', $p->id) }}" method="POST" class="d-inline">
                            @csrf @method('PUT')
                            <input type="hidden" name="status" value="rejeitado">
                            <button class="btn btn-danger btn-sm">Rejeitar</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
