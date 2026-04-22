@props(['name', 'size' => 5, 'class' => ''])

@php
    $sizeClass = match($size) {
        3 => 'size-3',
        4 => 'size-4',
        5 => 'size-5',
        6 => 'size-6',
        8 => 'size-8',
        10 => 'size-10',
        12 => 'size-12',
        default => 'size-5'
    };
@endphp

<i data-lucide="{{ $name }}" {{ $attributes->merge(['class' => "{$sizeClass} {$class}"]) }}></i>
