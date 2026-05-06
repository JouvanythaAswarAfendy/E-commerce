<div x-data="{ open: false }" class="border-b">

    <button @click="open = !open" class="flex justify-between w-full py-4 font-medium">
        <span>{{ $title ?? 'Judul' }}</span>
        <span x-show="!open">+</span>
        <span x-show="open">-</span>
    </button>

    <div x-show="open" class="pb-4 text-sm">
        {{ $slot }}
    </div>

</div>
