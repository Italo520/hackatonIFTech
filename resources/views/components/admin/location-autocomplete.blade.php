<div class="col-12 geo-autocomplete-wrapper position-relative">
    <label class="form-label fw-bold small text-primary d-flex align-items-center justify-content-between mb-1">
        <span><i class="bi bi-geo-alt-fill me-1"></i> Busca Rápida de Endereço & GPS (OpenStreetMap)</span>
        <span class="badge bg-primary-subtle text-primary fw-normal" style="font-size: 0.7rem;">Auto-preenchimento</span>
    </label>
    <div class="position-relative">
        <div class="position-absolute top-50 start-0 translate-middle-y ps-3 text-muted" style="pointer-events: none;" aria-hidden="true">
            <i class="bi bi-search"></i>
        </div>
        <input 
            type="text" 
            class="form-control ps-5 geo-search-input rounded-3 bg-light border-0 shadow-none" 
            placeholder="Digite para buscar no mapa (ex: Farol do Cabo Branco, Tambaú)..." 
            autocomplete="off"
            aria-label="Buscar endereço no OpenStreetMap para preenchimento automático"
        >
        <div class="geo-results-dropdown position-absolute start-0 w-100 bg-white shadow-lg rounded-3 border mt-1 d-none" style="z-index: 1060; max-height: 220px; overflow-y: auto;"></div>
    </div>
</div>
