@extends('layouts.admin')

@section('title', 'Gestão de Usuários & Controle de Acesso (RBAC)')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1">Gestão de Usuários & Matriz RBAC</h4>
        <p class="text-muted small mb-0">Controle de acesso baseado em papéis (Role-Based Access Control) com 8 níveis hierárquicos e isolamento de permissões.</p>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-outline-primary rounded-pill px-3 py-2 fw-semibold d-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalMatrizRbac">
            <i class="bi bi-grid-3x3-gap-fill"></i>
            <span>Ver Matriz de Permissões</span>
        </button>
        <button type="button" class="btn btn-primary rounded-pill px-3 py-2 fw-semibold d-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalNovoUsuario">
            <i class="bi bi-person-plus-fill"></i>
            <span>Novo Usuário</span>
        </button>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm d-flex align-items-center gap-2 mb-4" role="alert">
        <i class="bi bi-check-circle-fill fs-5"></i>
        <div>{{ session('success') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm d-flex align-items-center gap-2 mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill fs-5"></i>
        <div>{{ session('error') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
    </div>
@endif

<!-- Cards de Estatísticas por Categoria de Papel -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-xl">
        <div class="card border-0 rounded-4 shadow-sm bg-white p-3 h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary" style="width: 44px; height: 44px;">
                    <i class="bi bi-people-fill fs-5"></i>
                </div>
                <div>
                    <div class="text-muted small text-uppercase fw-bold" style="font-size: 0.68rem; letter-spacing: 0.5px;">Total Geral</div>
                    <div class="fs-4 fw-bold text-dark lh-1 mt-1">{{ $stats['total'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl">
        <div class="card border-0 rounded-4 shadow-sm bg-white p-3 h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary text-white" style="width: 44px; height: 44px;">
                    <i class="bi bi-shield-fill-check fs-5"></i>
                </div>
                <div>
                    <div class="text-muted small text-uppercase fw-bold" style="font-size: 0.68rem; letter-spacing: 0.5px;">Super Admins</div>
                    <div class="fs-4 fw-bold text-primary lh-1 mt-1">{{ $stats['super_admin'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl">
        <div class="card border-0 rounded-4 shadow-sm bg-white p-3 h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success" style="width: 44px; height: 44px;">
                    <i class="bi bi-bank2 fs-5"></i>
                </div>
                <div>
                    <div class="text-muted small text-uppercase fw-bold" style="font-size: 0.68rem; letter-spacing: 0.5px;">Gestores Públicos</div>
                    <div class="fs-4 fw-bold text-success lh-1 mt-1">{{ $stats['gestores'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-6 col-xl">
        <div class="card border-0 rounded-4 shadow-sm bg-white p-3 h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center bg-warning bg-opacity-15 text-warning-emphasis" style="width: 44px; height: 44px;">
                    <i class="bi bi-shop fs-5"></i>
                </div>
                <div>
                    <div class="text-muted small text-uppercase fw-bold" style="font-size: 0.68rem; letter-spacing: 0.5px;">Parceiros</div>
                    <div class="fs-4 fw-bold text-dark lh-1 mt-1">{{ $stats['empreendedores'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-xl">
        <div class="card border-0 rounded-4 shadow-sm bg-white p-3 h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center bg-dark bg-opacity-10 text-dark" style="width: 44px; height: 44px;">
                    <i class="bi bi-compass fs-5"></i>
                </div>
                <div>
                    <div class="text-muted small text-uppercase fw-bold" style="font-size: 0.68rem; letter-spacing: 0.5px;">Turistas (PWA)</div>
                    <div class="fs-4 fw-bold text-dark lh-1 mt-1">{{ $stats['turistas'] }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filtros e Barra de Pesquisa -->
<div class="card border-0 shadow-sm rounded-4 bg-white p-3 mb-4">
    <form method="GET" action="{{ route('admin.usuarios.index') }}" class="row g-2 align-items-center">
        <div class="col-12 col-md-6 col-lg-5">
            <div class="input-group">
                <span class="input-group-text bg-light border-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="q" value="{{ request('q') }}" class="form-control bg-light border-0 shadow-none" placeholder="Buscar por nome ou e-mail...">
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-lg-4">
            <select name="role" class="form-select bg-light border-0 shadow-none">
                <option value="">Todos os Perfis (RBAC)</option>
                @foreach($rolesDisponiveis as $roleKey => $roleInfo)
                    <option value="{{ $roleKey }}" {{ request('role') === $roleKey ? 'selected' : '' }}>
                        {{ $roleInfo['nome'] }} ({{ $roleKey }})
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-12 col-sm-6 col-md-2 col-lg-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-semibold w-100">
                <i class="bi bi-funnel-fill me-1"></i> Filtrar
            </button>
            @if(request('q') || request('role'))
                <a href="{{ route('admin.usuarios.index') }}" class="btn btn-light rounded-circle p-2 d-flex align-items-center justify-content-center flex-shrink-0" title="Limpar Filtros" style="width: 38px; height: 38px;">
                    <i class="bi bi-x-lg text-secondary"></i>
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Tabela de Usuários -->
<div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden mb-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Usuário</th>
                    <th>E-mail</th>
                    <th>Perfil RBAC Atual</th>
                    <th>Cadastrado em</th>
                    <th class="text-end pe-4">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($usuarios as $u)
                    @php
                        $roleConfig = $rolesDisponiveis[$u->role] ?? [
                            'nome' => ucfirst($u->role),
                            'badge' => 'bg-secondary text-white',
                            'icone' => 'bi-person',
                            'descricao' => 'Perfil customizado.',
                        ];
                    @endphp
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white shadow-sm" style="width: 38px; height: 38px; font-size: 0.85rem; background: linear-gradient(135deg, var(--bs-primary), #0a9396);">
                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $u->name }}</div>
                                    @if($u->id === auth()->id())
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2" style="font-size: 0.65rem;">Sua Conta (Ativa)</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="text-secondary small">
                            <i class="bi bi-envelope me-1 text-muted"></i>{{ $u->email }}
                        </td>
                        <td>
                            <span class="badge {{ $roleConfig['badge'] }} rounded-pill px-3 py-1.5 fw-semibold d-inline-flex align-items-center gap-1.5 shadow-sm" style="font-size: 0.75rem;">
                                <i class="bi {{ $roleConfig['icone'] }}"></i>
                                <span>{{ $roleConfig['nome'] }}</span>
                            </span>
                        </td>
                        <td class="text-muted small">
                            {{ $u->created_at ? $u->created_at->format('d/m/Y H:i') : 'Carga Inicial' }}
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-inline-flex gap-1.5">
                                <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-semibold d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#modalEditRole{{ $u->id }}" title="Alterar Perfil RBAC">
                                    <i class="bi bi-shield-shaded"></i>
                                    <span>Alterar Papel</span>
                                </button>
                                
                                @if($u->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.usuarios.destroy', $u->id) }}" class="d-inline m-0" onsubmit="return confirm('Tem certeza que deseja excluir o usuário {{ $u->name }}? Esta ação é irreversível.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-circle p-0 d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Excluir Usuário">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>

                            <!-- Modal de Edição de Perfil RBAC -->
                            <div class="modal fade text-start" id="modalEditRole{{ $u->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden">
                                        <form method="POST" action="{{ route('admin.usuarios.update-role', $u->id) }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header border-0 pb-0 pt-4 px-4 bg-light">
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary text-white" style="width: 40px; height: 40px;">
                                                        <i class="bi bi-shield-lock-fill fs-5"></i>
                                                    </div>
                                                    <div>
                                                        <h5 class="modal-title fw-bold text-dark m-0">Alterar Perfil RBAC</h5>
                                                        <span class="text-secondary small">{{ $u->name }} ({{ $u->email }})</span>
                                                    </div>
                                                </div>
                                                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body p-4">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold text-dark small">Selecione o Novo Nível de Acesso</label>
                                                    <select name="role" class="form-select rounded-3 py-2 shadow-none" required>
                                                        @foreach($rolesDisponiveis as $roleKey => $roleInfo)
                                                            <option value="{{ $roleKey }}" {{ $u->role === $roleKey ? 'selected' : '' }}>
                                                                {{ $roleInfo['nome'] }} ({{ $roleKey }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="p-3 bg-light rounded-3 border">
                                                    <div class="fw-bold small text-dark mb-1"><i class="bi bi-info-circle-fill text-primary me-1"></i> Diretriz de Segurança:</div>
                                                    <div class="text-secondary small" style="font-size: 0.78rem; line-height: 1.4;">
                                                        A alteração tem efeito imediato na próxima requisição do usuário. Cada papel define exatamente quais rotas e menus ficam visíveis no painel administrativo.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0 bg-light p-3">
                                                <button type="button" class="btn btn-outline-secondary rounded-pill px-3 fw-semibold" data-bs-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Salvar Alterações</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-people display-4 d-block mb-2 text-secondary opacity-50"></i>
                            <h6 class="fw-bold text-dark mb-1">Nenhum usuário encontrado</h6>
                            <p class="small mb-0">Tente ajustar os filtros de busca ou cadastre um novo usuário.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($usuarios->hasPages())
        <div class="p-3 border-top d-flex justify-content-center">
            {{ $usuarios->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

<!-- Modal Novo Usuário -->
<div class="modal fade" id="modalNovoUsuario" tabindex="-1" aria-labelledby="modalNovoUsuarioLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden">
            <form method="POST" action="{{ route('admin.usuarios.store') }}">
                @csrf
                <div class="modal-header border-0 pb-0 pt-4 px-4 bg-light">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary text-white" style="width: 40px; height: 40px;">
                            <i class="bi bi-person-plus-fill fs-5"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold text-dark m-0" id="modalNovoUsuarioLabel">Cadastrar Novo Usuário</h5>
                            <span class="text-secondary small">Criação de credencial e atribuição de papel RBAC</span>
                        </div>
                    </div>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Nome Completo</label>
                        <input type="text" name="name" class="form-control rounded-3 shadow-none" placeholder="Ex: João da Silva" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">E-mail Corporativo / Login</label>
                        <input type="email" name="email" class="form-control rounded-3 shadow-none" placeholder="Ex: joao@governo.pb.gov.br" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Papel no Sistema (Role RBAC)</label>
                        <select name="role" class="form-select rounded-3 shadow-none" required>
                            @foreach($rolesDisponiveis as $roleKey => $roleInfo)
                                <option value="{{ $roleKey }}" {{ $roleKey === 'gestor_conteudo' ? 'selected' : '' }}>
                                    {{ $roleInfo['nome'] }} — {{ $roleInfo['descricao'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Senha de Acesso Inicial</label>
                        <input type="password" name="password" class="form-control rounded-3 shadow-none" placeholder="Mínimo 6 caracteres" required minlength="6">
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light p-3">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-3 fw-semibold" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">
                        <i class="bi bi-check2 me-1"></i> Criar Usuário
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Matriz RBAC Completa -->
<div class="modal fade" id="modalMatrizRbac" tabindex="-1" aria-labelledby="modalMatrizRbacLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
        <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden">
            <div class="modal-header border-0 pb-0 pt-4 px-4 bg-light">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary text-white" style="width: 42px; height: 42px;">
                        <i class="bi bi-grid-3x3-gap-fill fs-5"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark m-0" id="modalMatrizRbacLabel">Matriz de Permissões RBAC (8 Perfis)</h5>
                        <span class="text-secondary small">Isolamento rigoroso de rotas e privilégios conforme RN-006</span>
                    </div>
                </div>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle small text-center mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-start">Módulo / Recurso</th>
                                <th>Super Admin</th>
                                <th>Prefeito</th>
                                <th>Secretário</th>
                                <th>Gestor Conteúdo</th>
                                <th>Gestor Cadastros</th>
                                <th>Atendente</th>
                                <th>Empreendedor</th>
                                <th>Turista</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-start fw-bold"><i class="bi bi-speedometer2 text-primary me-1"></i> Dashboard & KPIs</td>
                                <td><span class="badge bg-success">Total</span></td>
                                <td><span class="badge bg-success">Executivo</span></td>
                                <td><span class="badge bg-success">Setorial</span></td>
                                <td><span class="badge bg-secondary">Leitura</span></td>
                                <td><span class="badge bg-secondary">Leitura</span></td>
                                <td><span class="badge bg-secondary">Leitura</span></td>
                                <td><span class="badge bg-light text-muted">—</span></td>
                                <td><span class="badge bg-light text-muted">—</span></td>
                            </tr>
                            <tr>
                                <td class="text-start fw-bold"><i class="bi bi-geo-alt text-info me-1"></i> Atrativos Turísticos</td>
                                <td><span class="badge bg-success">CRUD Total</span></td>
                                <td><span class="badge bg-secondary">Leitura</span></td>
                                <td><span class="badge bg-success">CRUD Total</span></td>
                                <td><span class="badge bg-success">CRUD Total</span></td>
                                <td><span class="badge bg-light text-muted">—</span></td>
                                <td><span class="badge bg-secondary">Leitura</span></td>
                                <td><span class="badge bg-info">Próprios</span></td>
                                <td><span class="badge bg-secondary">PWA</span></td>
                            </tr>
                            <tr>
                                <td class="text-start fw-bold"><i class="bi bi-calendar-event text-warning me-1"></i> Eventos & Shows</td>
                                <td><span class="badge bg-success">CRUD Total</span></td>
                                <td><span class="badge bg-secondary">Leitura</span></td>
                                <td><span class="badge bg-success">CRUD Total</span></td>
                                <td><span class="badge bg-success">CRUD Total</span></td>
                                <td><span class="badge bg-light text-muted">—</span></td>
                                <td><span class="badge bg-secondary">Leitura</span></td>
                                <td><span class="badge bg-light text-muted">—</span></td>
                                <td><span class="badge bg-secondary">PWA</span></td>
                            </tr>
                            <tr>
                                <td class="text-start fw-bold"><i class="bi bi-map text-success me-1"></i> Roteiros e Itinerários</td>
                                <td><span class="badge bg-success">CRUD Total</span></td>
                                <td><span class="badge bg-secondary">Leitura</span></td>
                                <td><span class="badge bg-success">CRUD Total</span></td>
                                <td><span class="badge bg-success">CRUD Total</span></td>
                                <td><span class="badge bg-light text-muted">—</span></td>
                                <td><span class="badge bg-secondary">Leitura</span></td>
                                <td><span class="badge bg-light text-muted">—</span></td>
                                <td><span class="badge bg-secondary">PWA</span></td>
                            </tr>
                            <tr>
                                <td class="text-start fw-bold"><i class="bi bi-shop text-primary me-1"></i> Validação de Parceiros</td>
                                <td><span class="badge bg-success">Aprovar/Rejeitar</span></td>
                                <td><span class="badge bg-secondary">Leitura</span></td>
                                <td><span class="badge bg-success">Aprovar/Rejeitar</span></td>
                                <td><span class="badge bg-light text-muted">—</span></td>
                                <td><span class="badge bg-success">Aprovar/Rejeitar</span></td>
                                <td><span class="badge bg-light text-muted">—</span></td>
                                <td><span class="badge bg-info">Submissão</span></td>
                                <td><span class="badge bg-light text-muted">—</span></td>
                            </tr>
                            <tr>
                                <td class="text-start fw-bold"><i class="bi bi-shield-exclamation text-danger me-1"></i> Alertas Defesa Civil</td>
                                <td><span class="badge bg-success">Publicar</span></td>
                                <td><span class="badge bg-success">Publicar</span></td>
                                <td><span class="badge bg-success">Publicar</span></td>
                                <td><span class="badge bg-light text-muted">—</span></td>
                                <td><span class="badge bg-light text-muted">—</span></td>
                                <td><span class="badge bg-light text-muted">—</span></td>
                                <td><span class="badge bg-light text-muted">—</span></td>
                                <td><span class="badge bg-secondary">Notificação</span></td>
                            </tr>
                            <tr>
                                <td class="text-start fw-bold"><i class="bi bi-file-earmark-arrow-down text-dark me-1"></i> Relatórios CSV</td>
                                <td><span class="badge bg-success">Exportar</span></td>
                                <td><span class="badge bg-success">Exportar</span></td>
                                <td><span class="badge bg-success">Exportar</span></td>
                                <td><span class="badge bg-light text-muted">—</span></td>
                                <td><span class="badge bg-light text-muted">—</span></td>
                                <td><span class="badge bg-light text-muted">—</span></td>
                                <td><span class="badge bg-light text-muted">—</span></td>
                                <td><span class="badge bg-light text-muted">—</span></td>
                            </tr>
                            <tr>
                                <td class="text-start fw-bold"><i class="bi bi-shield-lock text-dark me-1"></i> Auditoria & Logs</td>
                                <td><span class="badge bg-danger text-white">Exclusivo</span></td>
                                <td><span class="badge bg-light text-muted">—</span></td>
                                <td><span class="badge bg-light text-muted">—</span></td>
                                <td><span class="badge bg-light text-muted">—</span></td>
                                <td><span class="badge bg-light text-muted">—</span></td>
                                <td><span class="badge bg-light text-muted">—</span></td>
                                <td><span class="badge bg-light text-muted">—</span></td>
                                <td><span class="badge bg-light text-muted">—</span></td>
                            </tr>
                            <tr>
                                <td class="text-start fw-bold"><i class="bi bi-people-fill text-warning-emphasis me-1"></i> Gestão RBAC & Usuários</td>
                                <td><span class="badge bg-danger text-white">Exclusivo</span></td>
                                <td><span class="badge bg-light text-muted">—</span></td>
                                <td><span class="badge bg-light text-muted">—</span></td>
                                <td><span class="badge bg-light text-muted">—</span></td>
                                <td><span class="badge bg-light text-muted">—</span></td>
                                <td><span class="badge bg-light text-muted">—</span></td>
                                <td><span class="badge bg-light text-muted">—</span></td>
                                <td><span class="badge bg-light text-muted">—</span></td>
                            </tr>
                            <tr>
                                <td class="text-start fw-bold"><i class="bi bi-journal-bookmark-fill text-info me-1"></i> Documentação & Swagger</td>
                                <td><span class="badge bg-danger text-white">Exclusivo</span></td>
                                <td><span class="badge bg-light text-muted">—</span></td>
                                <td><span class="badge bg-light text-muted">—</span></td>
                                <td><span class="badge bg-light text-muted">—</span></td>
                                <td><span class="badge bg-light text-muted">—</span></td>
                                <td><span class="badge bg-light text-muted">—</span></td>
                                <td><span class="badge bg-light text-muted">—</span></td>
                                <td><span class="badge bg-light text-muted">—</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer border-0 bg-light p-3">
                <button type="button" class="btn btn-dark rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
@endsection
