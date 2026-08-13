@extends('layouts.admin')

@section('title', 'Gestão de Atrativos')

@section('content')
<div class="card">
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Status</th>
                    <th class="w-1"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($atrativos as $atrativo)
                <tr>
                    <td>{{ $atrativo->id }}</td>
                    <td>{{ $atrativo->nome }}</td>
                    <td class="text-muted">{{ $atrativo->status }}</td>
                    <td><a href="#">Edit</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
