<header class="bg-white shadow sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 flex justify-between items-center h-16">

        <!-- Logo -->
        <a href="/" class="flex items-center gap-2">
            @include('components.logo')
            <span class="font-semibold">Gdo Tinoel Craft</span>
        </a>

        <!-- Menu -->
        <nav class="flex gap-6">
            <a href="#" class="hover:text-blue-500">Decorations</a>
            <a href="#" class="hover:text-blue-500">Accessories</a>
            <a href="#" class="hover:text-blue-500">Souvenirs</a>
        </nav>

        <!-- Cart -->
        <a href="#" class="relative">
            🛒
            <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs px-1 rounded-full">0</span>
        </a>

    </div>
</header>
