@extends('layouts.pwa')

@section('content')
<h2>Privacidade (LGPD)</h2>
<div class="card mt-3">
    <div class="card-body">
        <h5>Exportar meus dados</h5>
        <button class="btn btn-primary" onclick="exportData()">Exportar JSON</button>
    </div>
</div>

<div class="card mt-3 border-danger">
    <div class="card-body">
        <h5 class="text-danger">Excluir Conta e Dados</h5>
        <p>Esta ação é irreversível.</p>
        <form onsubmit="deleteData(event)">
            <input type="password" id="senha_delete" class="form-control mb-2" placeholder="Digite sua senha para confirmar" required>
            <button type="submit" class="btn btn-danger">Excluir Tudo</button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function exportData() {
        fetch('/api/v1/privacidade/exportar', {
            headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') } // Mock token integration
        })
        .then(res => res.json())
        .then(data => {
            const blob = new Blob([JSON.stringify(data, null, 2)], {type: "application/json"});
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'meus_dados_destino_turistico.json';
            a.click();
        });
    }

    function deleteData(e) {
        e.preventDefault();
        fetch('/api/v1/privacidade/excluir', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + localStorage.getItem('token')
            },
            body: JSON.stringify({ password: document.getElementById('senha_delete').value })
        }).then(res => {
            if (res.ok) {
                alert("Conta excluída.");
                window.location.href = '/';
            } else {
                alert("Erro ao excluir. Verifique sua senha.");
            }
        });
    }
</script>
@endpush
@endsection
