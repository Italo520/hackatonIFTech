@props([
    'tipo' => 'primary',
    'icon' => null,
])

@php
    $classes = match($tipo) {
        'success' => 'bg-success-subtle text-success border border-success-subtle',
        'warning' => 'bg-warning-subtle text-dark border border-warning-subtle',
        'danger' => 'bg-danger-subtle text-danger border border-danger-subtle',
        'info' => 'bg-info-subtle text-info-emphasis border border-info-subtle',
        'dark' => 'bg-dark text-white',
        default => 'bg-primary-subtle text-primary border border-primary-subtle',
    };
@endphp

<span {{ $attributes->merge(['class' => "badge rounded-pill px-2.5 py-1 fw-semibold {$classes}"]) }} style="font-size: 0.72rem;">
    @if($icon)
        <i class="bi {{ $icon }} me-1" aria-hidden="true"></i>
    @endif
    {{ $slot }}
</span>
