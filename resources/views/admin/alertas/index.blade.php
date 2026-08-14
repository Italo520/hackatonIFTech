@extends('layouts.admin')

@section('title', 'Alertas & Defesa Civil')

@section('content')
<div class="row g-4 mb-4">
    <!-- Formulário para Disparar Alerta -->
    <div class="col-12 col-xl-5">
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
            <div class="d-flex align-items-center gap-2 mb-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: rgba(155, 34, 38, 0.1); color: #9b2226;">
                    <i class="bi bi-broadcast fs-5"></i>
                </div>
                <div>
                    <h5 class="fw-bold text-dark mb-0">Emitir Comunicado / Alerta</h5>
                    <span class="text-muted small">Notificação transmitida aos turistas no PWA</span>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.alertas.store') }}" class="d-flex flex-column gap-3">
                @csrf
                <div>
                    <label class="form-label fw-bold small text-secondary">Título do Alerta</label>
                    <input type="text" name="titulo" class="form-control" placeholder="Ex: Maré Alta / Chuvas Intensas" required>
                </div>

                <div>
                    <label class="form-label fw-bold small text-secondary">Nível de Urgência</label>
                    <select name="urgencia" class="form-select">
                        <option value="info">Informativo (Azul)</option>
                        <option value="aviso" selected>Atenção / Meteorologia (Amarelo)</option>
                        <option value="urgente">Urgência / Defesa Civil (Vermelho)</option>
                    </select>
                </div>

                <div>
                    <label class="form-label fw-bold small text-secondary">Mensagem Completa</label>
                    <textarea name="corpo" class="form-control" rows="4" placeholder="Instruções de segurança, telefones de apoio e orientações..." required></textarea>
                </div>

                <button type="submit" class="btn btn-danger w-100 py-2.5 rounded-3 fw-bold shadow-sm mt-2">
                    <i class="bi bi-send-fill me-1"></i> Publicar Alerta Imediato
                </button>
            </form>
        </div>
    </div>

    <!-- Histórico de Alertas Publicados -->
    <div class="col-12 col-xl-7">
        <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden h-100">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                <h5 class="fw-bold text-dark mb-0">Histórico de Alertas</h5>
                <span class="text-muted small">Alertas emitidos anteriormente</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Alerta</th>
                                <th>Nível</th>
                                <th>Data de Envio</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($alertas as $alerta)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark">{{ $alerta->titulo }}</div>
                                        <div class="text-muted small" style="font-size: 0.78rem;">{{ $alerta->corpo }}</div>
                                    </td>
                                    <td>
                                        @if($alerta->urgencia === 'urgente')
                                            <span class="badge bg-danger rounded-pill px-2.5 py-1">Urgente</span>
                                        @elseif($alerta->urgencia === 'aviso')
                                            <span class="badge bg-warning text-dark rounded-pill px-2.5 py-1">Aviso</span>
                                        @else
                                            <span class="badge bg-info text-white rounded-pill px-2.5 py-1">Informativo</span>
                                        @endif
                                    </td>
                                    <td class="small text-muted font-monospace">
                                        {{ $alerta->created_at ? $alerta->created_at->format('d/m/Y H:i') : now()->format('d/m/Y H:i') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-5 text-muted">
                                        <i class="bi bi-check-circle fs-1 d-block mb-2 text-success opacity-50"></i>
                                        Nenhum alerta registrado até o momento.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
