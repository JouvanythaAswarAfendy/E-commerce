@php
    $lowStockProducts = \App\Models\Product::query()
        ->where(function ($query) {
            $query->where(function ($q) {
                $q->doesntHave('sizes')
                  ->where('stock', '<=', 5);
            })->orWhereHas('sizes', function ($q) {
                $q->where('stock', '<=', 5);
            });
        })
        ->with('sizes')
        ->limit(10)
        ->get();

    $count = \App\Models\Product::query()
        ->where(function ($query) {
            $query->where(function ($q) {
                $q->doesntHave('sizes')
                  ->where('stock', '<=', 5);
            })->orWhereHas('sizes', function ($q) {
                $q->where('stock', '<=', 5);
            });
        })
        ->count();
@endphp

<div x-data="{ open: false }" class="relative flex items-center">
    <button @click="open = !open" class="relative p-2 text-gray-500 transition-colors rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
        </svg>
        
        @if($count > 0)
            <span class="absolute top-1.5 right-1.5 flex items-center justify-center w-3.5 h-3.5 text-[9px] font-bold text-white bg-danger-600 rounded-full ring-2 ring-white dark:ring-gray-900">
                {{ $count }}
            </span>
        @endif
    </button>

    <div x-show="open" 
         @click.away="open = false"
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute right-0 z-50 mt-2 w-72 origin-top-right rounded-xl bg-white p-2 shadow-lg ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 top-full"
         style="top: 100%; right: 0;">

        
        <div class="px-3 py-2 border-b border-gray-100 dark:border-gray-800">
            <p class="text-xs font-bold text-gray-950 dark:text-white uppercase tracking-wider">Peringatan Stok Menipis</p>
        </div>

        <div class="max-h-64 overflow-y-auto mt-1">
            @forelse($lowStockProducts as $product)
                <a href="{{ \App\Filament\Resources\RestockResource::getUrl('index') }}" class="block px-3 py-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $product->name }}</p>
                            <p class="text-xs text-danger-600 font-bold">
                                @if($product->sizes->count() > 0)
                                    Stok: {{ $product->sizes->where('stock', '<=', 5)->map(fn($s) => "{$s->size}({$s->stock})")->implode(', ') }}
                                @else
                                    Stok: {{ $product->stock }}
                                @endif
                            </p>
                        </div>
                    </div>
                </a>
            @empty
                <div class="px-3 py-4 text-center">
                    <p class="text-sm text-gray-500">Semua stok aman</p>
                </div>
            @endforelse
        </div>

        @if($count > 0)
            <div class="mt-1 pt-1 border-t border-gray-100 dark:border-gray-800">
                <a href="{{ \App\Filament\Resources\RestockResource::getUrl('index') }}" class="block text-center py-2 text-xs font-bold text-primary-600 hover:text-primary-500 transition-colors uppercase tracking-widest">
                    Lihat Semua ({{ $count }})
                </a>
            </div>
        @endif
    </div>
</div>
