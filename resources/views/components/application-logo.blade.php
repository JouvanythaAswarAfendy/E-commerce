{{-- resources/views/components/application-logo.blade.php --}}
@props(['size' => 'md'])

@php
    $sizeMap = [
        'sm' => 'w-8 h-8',
        'md' => 'w-10 h-10',
        'lg' => 'w-16 h-16',
    ];
    $sizeClass = $sizeMap[$size] ?? $sizeMap['md'];
@endphp

<svg viewBox="0 0 200 200" class="{{ $sizeClass }}" fill="none" xmlns="http://www.w3.org/2000/svg">
    <circle cx="100" cy="100" r="95" fill="#FAF6F6" stroke="#622A2A" stroke-width="2" />
    <circle cx="70" cy="60" r="18" fill="#622A2A" opacity="0.9" />
    <circle cx="65" cy="55" r="5" fill="white" opacity="0.4" />
    <circle cx="130" cy="70" r="18" fill="#8B3E3E" opacity="0.85" />
    <circle cx="125" cy="65" r="5" fill="white" opacity="0.4" />
    <circle cx="100" cy="100" r="20" fill="#622A2A" />
    <circle cx="95" cy="95" r="6" fill="white" opacity="0.4" />
    <circle cx="75" cy="135" r="18" fill="#A0563C" opacity="0.8" />
    <circle cx="70" cy="130" r="5" fill="white" opacity="0.4" />
    <circle cx="125" cy="140" r="18" fill="#622A2A" opacity="0.9" />
    <circle cx="120" cy="135" r="5" fill="white" opacity="0.4" />
    <path d="M 70 60 Q 85 80 100 100 Q 110 115 125 140" stroke="#622A2A" stroke-width="2" opacity="0.3" />
    <path d="M 130 70 Q 115 85 100 100 Q 90 110 75 135" stroke="#622A2A" stroke-width="2" opacity="0.3" />
    <ellipse cx="90" cy="90" rx="8" ry="10" fill="white" opacity="0.3" />
</svg>
