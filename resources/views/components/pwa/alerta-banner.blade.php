@props([
    'titulo',
    'descricao',
    'urgencia' => 'aviso',
    'responsavel' => null,
    'contatoEmergencia' => null,
    'validoAte' => null,
    'id' => null,
    'dismissible' => true,
])

@php
    $isUrgente = in_array(strtolower($urgencia), ['urgente', 'emergencia', 'perigo']);
    $bgClass = $isUrgente ? 'bg-danger text-white' : (strtolower($urgencia) === 'aviso' ? 'bg-warning-subtle text-dark border border-warning' : 'bg-primary-subtle text-dark border border-primary-subtle');
    $badgeClass = $isUrgente ? 'bg-white text-danger' : (strtolower($urgencia) === 'aviso' ? 'bg-warning text-dark' : 'bg-primary text-white');
    $icon = $isUrgente ? 'bi-exclamation-triangle-fill text-warning' : 'bi-megaphone-fill text-primary';
@endphp

<div class="card border-0 rounded-4 p-3 shadow-sm mb-2 {{ $bgClass }}" id="alerta-card-{{ $id ?? 'temp' }}" role="alert" aria-live="assertive">
    <div class="d-flex justify-content-between align-items-start gap-2">
        <div class="d-flex gap-2.5 align-items-start">
            <div class="fs-4 flex-shrink-0 mt-1" aria-hidden="true">
                <i class="bi {{ $icon }}"></i>
            </div>
            <div>
                <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                    <span class="badge {{ $badgeClass }} rounded-pill px-2.5 py-1 text-uppercase fw-bold" style="font-size: 0.65rem; letter-spacing: 0.5px;">
                        {{ $responsavel ?? 'Defesa Civil' }} • {{ ucfirst($urgencia) }}
                    </span>
                    @if($validoAte)
                        <span class="small opacity-75 font-monospace" style="font-size: 0.7rem;">
                            <i class="bi bi-clock me-1"></i>Válido até {{ $validoAte }}
                        </span>
                    @endif
                </div>
                <h3 class="fw-bold mb-1 fs-6 text-inherit">{{ $titulo }}</h3>
                <p class="small mb-2 opacity-90" style="font-size: 0.82rem; line-height: 1.35;">{{ $descricao }}</p>
                
                @if($contatoEmergencia)
                    <div class="small fw-semibold mb-2 opacity-90" style="font-size: 0.78rem;">
                        <i class="bi bi-telephone-fill me-1" aria-hidden="true"></i> {{ $contatoEmergencia }}
                    </div>
                @endif

                <div class="d-flex align-items-center gap-2 flex-wrap">
                    @if($id)
                        <button type="button" class="btn btn-sm {{ $isUrgente ? 'btn-light text-danger' : 'btn-primary' }} rounded-pill px-3 py-1 fw-bold" data-bs-toggle="modal" data-bs-target="#modalAlerta{{ $id }}" style="font-size: 0.75rem;">
                            <i class="bi bi-shield-exclamation me-1" aria-hidden="true"></i> Ver Orientações Oficiais
                        </button>
                    @endif
                    @if($dismissible && $id)
                        <button type="button" class="btn btn-sm btn-link text-decoration-none p-0 {{ $isUrgente ? 'text-white' : 'text-secondary' }} opacity-75 small" onclick="if(window.AlertasManager) window.AlertasManager.dismissHomeBanner({{ $id }}); else document.getElementById('alerta-card-{{ $id }}').remove();" style="font-size: 0.75rem;">
                            Dispensar
                        </button>
                    @endif
                </div>
            </div>
        </div>
        @if($dismissible && $id)
            <button type="button" class="btn-close {{ $isUrgente ? 'btn-close-white' : '' }} opacity-50" onclick="if(window.AlertasManager) window.AlertasManager.dismissHomeBanner({{ $id }}); else document.getElementById('alerta-card-{{ $id }}').remove();" aria-label="Fechar e marcar como visto" title="Marcar como visto"></button>
        @endif
    </div>
</div>
