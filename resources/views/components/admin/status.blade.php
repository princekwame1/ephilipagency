@props(['status', 'label' => null])
@php
    $styles = [
        'new' => 'bg-gold-100 text-gold-800',
        'pending' => 'bg-gold-100 text-gold-800',
        'contacted' => 'bg-royal-50 text-royal-700',
        'scheduled' => 'bg-royal-700 text-white',
        'published' => 'bg-emerald-50 text-emerald-700',
        'approved' => 'bg-emerald-50 text-emerald-700',
        'available' => 'bg-emerald-50 text-emerald-700',
        'draft' => 'bg-ink/5 text-ink/60',
        'closed' => 'bg-ink/5 text-ink/50',
        'rejected' => 'bg-red-50 text-red-600',
    ];
@endphp
<span {{ $attributes->merge(['class' => 'inline-flex whitespace-nowrap rounded-full px-2.5 py-1 text-xs font-semibold '.($styles[$status] ?? 'bg-royal-50 text-royal-700')]) }}>{{ $label ?? ucfirst(str_replace('_', ' ', $status)) }}</span>
