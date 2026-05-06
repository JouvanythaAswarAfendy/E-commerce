{{-- resources/views/components/logo.blade.php --}}
@props(['size' => 'md'])

@php
    $sizeMap = [
        'sm' => 'h-8',
        'md' => 'h-10',
        'lg' => 'h-16',
        'xl' => 'h-24',
    ];
    $sizeClass = $sizeMap[$size] ?? $sizeMap['md'];
@endphp

<div class="flex items-center gap-2">
    <div x-data="{ imgError: false }">
        <img src="{{ asset('images/logo-gdo.png') }}"
             alt="Gdo Tinoel Craft"
             x-on:error="imgError = true"
             x-show="!imgError"
             class="{{ $sizeClass }} w-auto object-contain rounded-full ring-2 ring-white">

        <div x-show="imgError"
             class="{{ $sizeClass }} flex items-center justify-center bg-primary rounded-lg text-white font-bold text-lg"
             style="display:none;">
            G
        </div>
    </div>
</div>