@extends('layouts.admin')

@section('title', 'Auditoria & Logs de Inteligência')

@section('content')
<div class="mb-4">
    <h4 class="fw-bold text-dark mb-1">Logs de Auditoria e Assistente IA</h4>
    <p class="text-muted small mb-0">Rastreabilidade de consultas, respostas geradas pela IA e métricas de uso em conformidade com a LGPD.</p>
</div>

<div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden mb-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Data / Hora</th>
                    <th>Tipo de Registro</th>
                    <th>Mensagem / Consulta</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td class="ps-4 small text-muted font-monospace">
                            {{ $log->created_at ? $log->created_at->format('d/m/Y H:i:s') : now()->format('d/m/Y H:i:s') }}
                        </td>
                        <td><span class="badge bg-primary-subtle text-primary">Interação IA</span></td>
                        <td>
                            <div class="small text-dark fw-semibold">{{ Str::limit($log->prompt ?? $log->mensagem ?? 'Consulta de Roteiro', 70) }}</div>
                        </td>
                        <td><span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1">Processado</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                            <i class="bi bi-shield-check fs-1 d-block mb-2 text-primary opacity-50"></i>
                            Nenhum log registrado recentemente.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
