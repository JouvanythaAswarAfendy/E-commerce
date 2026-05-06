@props(['type' => 'default'])

@php
    $base = 'w-full rounded-lg border p-4 text-sm';

    $variants = [
        'default' => 'bg-gray-100 text-gray-800 border-gray-300',
        'error' => 'bg-red-100 text-red-700 border-red-300',
        'success' => 'bg-green-100 text-green-700 border-green-300',
    ];

    $classes = $variants[$type] ?? $variants['default'];
@endphp

<div {{ $attributes->merge(['class' => "$base $classes"]) }}>
    {{ $slot }}
</div>
