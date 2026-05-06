<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Products') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex flex-col md:flex-row gap-8">
                <!-- Sidebar / Categories -->
                <div class="w-full md:w-1/4">
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 sticky top-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Kategori</h3>
                        <ul class="space-y-2">
                            <li>
                                <a href="{{ route('products.index') }}" 
                                   class="block px-4 py-2 rounded-lg transition-colors {{ !request('category') ? 'bg-primary text-white font-semibold shadow-sm' : 'text-stone-600 hover:bg-stone-50' }}">
                                    Semua Produk
                                </a>
                            </li>
                            @foreach($categories as $parent)
                                <li>
                                    <a href="{{ route('products.index', ['category' => $parent->id]) }}" 
                                       class="block px-4 py-2 rounded-lg transition-colors {{ request('category') == $parent->id ? 'bg-primary text-white font-semibold shadow-sm' : 'text-stone-600 hover:bg-stone-50' }}">
                                        {{ $parent->name }}
                                    </a>
                                    @if($parent->children->count() > 0)
                                        <ul class="ml-4 mt-1 space-y-1 border-l border-gray-100">
                                            @foreach($parent->children as $child)
                                                <li>
                                                    <a href="{{ route('products.index', ['category' => $child->id]) }}" 
                                                       class="block px-4 py-1.5 rounded-lg text-sm transition-colors {{ request('category') == $child->id ? 'text-primary font-bold' : 'text-stone-500 hover:text-primary hover:bg-stone-50' }}">
                                                        {{ $child->name }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="w-full md:w-3/4">
                    @if(session('error'))
                        <div class="mb-6 p-4 bg-red-50 text-red-700 rounded-xl border border-red-200">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($products->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
                            @foreach($products as $product)
                                <div class="bg-white group relative rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 border border-stone-100 flex flex-col h-full">
                                    {{-- Image with Aspect Ratio 1:1 --}}
                                    <div class="aspect-square relative overflow-hidden bg-stone-50">
                                        @php
                                            $image = is_array($product->images) && count($product->images) > 0 ? $product->images[0] : null;
                                        @endphp
                                        <img src="{{ $image ? asset('storage/'.$image) : 'https://placehold.co/600x600?text=Produk' }}" 
                                             alt="{{ $product->name }}" 
                                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                                             onerror="this.src='https://placehold.co/600x600?text=Produk'">
                                        
                                        {{-- Hover Overlay (Minimalist) --}}
                                        <div class="absolute inset-0 bg-stone-900/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                            <a href="{{ route('products.show', $product->id) }}" 
                                               class="bg-white text-stone-900 px-6 py-2 rounded-full font-bold text-xs uppercase tracking-widest shadow-lg transform translate-y-4 group-hover:translate-y-0 transition-all duration-300 whitespace-nowrap">
                                                Lihat Detail
                                            </a>
                                        </div>
                                    </div>

                                    {{-- Product Info --}}
                                    <div class="p-6 flex flex-col flex-grow">
                                        <div class="mb-1">
                                            <span class="text-xs font-bold text-stone-400 uppercase tracking-widest">
                                                {{ $product->category->name ?? 'Koleksi' }}
                                            </span>
                                        </div>
                                        <a href="{{ route('products.show', $product->id) }}" class="mb-2">
                                            <h3 class="font-bold text-stone-900 text-lg group-hover:text-primary transition-colors line-clamp-1">
                                                {{ $product->name }}
                                            </h3>
                                        </a>
                                        <div class="mt-auto">
                                            <p class="text-primary font-bold text-lg">
                                                @if($product->sizes->count() > 0)
                                                    <span class="text-xs text-stone-400 font-normal mr-1 italic">Mulai</span>
                                                @endif
                                                Rp {{ number_format($product->display_price, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-12">
                            {{ $products->links() }}
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-20 text-center">
                            <div class="w-20 h-20 bg-stone-100 rounded-full flex items-center justify-center mb-6">
                                <svg class="w-10 h-10 text-stone-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                            </div>
                            <h3 class="text-xl font-bold text-stone-700">Produk tidak ditemukan</h3>
                            <p class="text-stone-500 mt-2">Coba pilih kategori lain atau periksa kembali nanti.</p>
                            <a href="{{ route('products.index') }}" class="mt-6 text-primary font-bold hover:underline">Lihat Semua Produk</a>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
