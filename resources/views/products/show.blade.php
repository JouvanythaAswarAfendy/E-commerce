{{-- resources/views/products/show.blade.php --}}
<x-app-layout>
    <main class="py-12 bg-white" x-data="{ 
        selectedSize: '', 
        qty: 1, 
        sizes: {{ $product->sizes->toJson() }},
        basePrice: {{ $product->price }},
        get displayPrice() {
            let sizeObj = this.sizes.find(s => s.size === this.selectedSize);
            return sizeObj && sizeObj.price ? sizeObj.price : this.basePrice;
        },
        get currentStock() {
            let sizeObj = this.sizes.find(s => s.size === this.selectedSize);
            return sizeObj ? sizeObj.stock : 0;
        },
        selectedImage: 0,
        images: {{ json_encode(array_map(fn($i) => asset('storage/' . $i), $product->images ?? [])) }}
    }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Breadcrumb --}}
            <nav class="mb-8 text-[10px] uppercase tracking-widest text-stone-400">
                <a href="{{ url('/') }}" class="hover:text-stone-900 transition-colors">Beranda</a>
                <span class="mx-3">/</span>
                <a href="{{ url('/products') }}" class="hover:text-stone-900 transition-colors">Produk</a>
                <span class="mx-3">/</span>
                <span class="text-stone-900 font-bold">{{ $product->name }}</span>
            </nav>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 lg:gap-24 items-start">
                
                {{-- Product Gallery --}}
                <div class="space-y-4">
                    <div class="aspect-[4/5] bg-stone-50 rounded-lg overflow-hidden border border-stone-100">
                        <template x-if="images.length > 0">
                            <img :src="images[selectedImage]" 
                                 class="w-full h-full object-cover transition-opacity duration-500" 
                                 alt="{{ $product->name }}">
                        </template>
                        <template x-if="images.length === 0">
                            <div class="w-full h-full flex items-center justify-center text-stone-300">
                                <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        </template>
                    </div>
                    
                    {{-- Thumbnails --}}
                    <div class="flex gap-3 overflow-x-auto pb-2 scrollbar-hide">
                        <template x-for="(img, index) in images" :key="index">
                            <button @click="selectedImage = index" 
                                    :class="selectedImage === index ? 'border-[#622A2A]' : 'border-stone-200 hover:border-stone-400'"
                                    class="w-20 aspect-[4/5] flex-shrink-0 bg-stone-50 rounded border-2 transition-all duration-300">
                                <img :src="img" class="w-full h-full object-cover">
                            </button>
                        </template>
                    </div>
                </div>

                {{-- Product Details --}}
                <div class="space-y-8">
                    <div class="space-y-2">
                        <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#622A2A]">
                            {{ $product->category->parent->name ?? $product->category->name }}
                        </span>
                        <h1 class="text-3xl font-bold text-[#1a1a1a] leading-tight">
                            {{ $product->name }}
                        </h1>
                        <p class="text-xl font-bold text-[#622A2A]">
                            Rp <span x-text="new Intl.NumberFormat('id-ID').format(displayPrice)"></span>
                        </p>
                    </div>

                    <div class="space-y-6">
                        {{-- Size Selection --}}
                        @if($product->sizes->count() > 0)
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold uppercase tracking-widest text-stone-900 font-primary">UKURAN</span>
                                <template x-if="selectedSize">
                                    <span class="text-[10px] text-stone-400 uppercase tracking-widest font-bold">STOK: <span x-text="currentStock" class="text-stone-900 border-[#622A2A]"></span></span>
                                </template>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="s in sizes" :key="s.id">
                                    <button @click="selectedSize = s.size; qty = 1"
                                            :disabled="s.stock <= 0"
                                            :class="{
                                                'bg-[#622A2A] text-white border-[#622A2A]': selectedSize === s.size,
                                                'bg-white text-stone-800 border-stone-200 hover:border-[#622A2A]': selectedSize !== s.size && s.stock > 0,
                                                'bg-stone-50 text-stone-300 border-stone-100 cursor-not-allowed': s.stock <= 0
                                            }"
                                            class="w-14 h-10 border flex items-center justify-center transition-all duration-300 rounded font-primary">
                                        <span class="text-[10px] font-bold" x-text="s.size"></span>
                                    </button>
                                </template>
                            </div>
                        </div>
                        @else
                        <div class="space-y-3">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-[#622A2A]">STOK</span>
                            <span class="text-[10px] font-bold text-stone-900 uppercase tracking-widest font-primary">{{ $product->stock }}</span>
                        </div>
                        @endif

                        {{-- Quantity Selection --}}
                        <div class="space-y-3" x-show="!sizes.length || selectedSize">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-[#622A2A] font-primary">JUMLAH</span>
                            <div class="flex items-center border border-stone-200 w-max bg-white rounded">
                                <button type="button" @click="qty = Math.max(1, qty - 1)" 
                                        class="w-10 h-10 flex items-center justify-center hover:bg-stone-50 transition-colors text-stone-900 text-lg border-r border-stone-100">−</button>
                                <input type="number" name="qty" x-model="qty" readonly
                                       class="w-12 text-center border-0 focus:ring-0 text-xs font-bold text-stone-900">
                                <button type="button" @click="qty = Math.min(sizes.length ? currentStock : {{ $product->stock }}, qty + 1)"
                                        class="w-10 h-10 flex items-center justify-center hover:bg-stone-50 transition-colors text-stone-900 text-lg border-l border-stone-100">+</button>
                            </div>
                        </div>

                        {{-- Deskripsi Produk --}}
                        <div class="py-6 border-t border-stone-100 space-y-3 font-primary">
                            <h3 class="text-[10px] font-bold uppercase tracking-widest text-[#622A2A]">DESKRIPSI</h3>
                            <div class="text-[13px] text-stone-600 leading-relaxed font-light whitespace-pre-wrap tracking-wide">{!! nl2br(e($product->description)) !!}</div>
                        </div>

                        {{-- Action Buttons --}}
                        <form action="{{ route('cart.store') }}" method="POST" class="space-y-3 pt-2">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="size" :value="selectedSize">
                            <input type="hidden" name="qty" :value="qty">

                            <div class="flex flex-col gap-2">
                                <button type="submit" 
                                        :disabled="(sizes.length && !selectedSize) || (sizes.length ? currentStock <= 0 : {{ $product->stock }} <= 0)"
                                        :class="(sizes.length && !selectedSize) || (sizes.length ? currentStock <= 0 : {{ $product->stock }} <= 0) ? 'bg-stone-100 text-stone-300 border-stone-100 cursor-not-allowed' : 'bg-white text-[#622A2A] border-[#622A2A] hover:bg-[#622A2A] hover:text-white'"
                                        class="w-full py-4 font-bold uppercase text-[12px] tracking-[0.2em] transition-all duration-300 flex items-center justify-center rounded-lg border-2">
                                    TAMBAH KE KERANJANG
                                </button>
                                
                                <button type="submit" formaction="{{ route('checkout.direct') }}"
                                        :disabled="(sizes.length && !selectedSize) || (sizes.length ? currentStock <= 0 : {{ $product->stock }} <= 0)"
                                        :class="(sizes.length && !selectedSize) || (sizes.length ? currentStock <= 0 : {{ $product->stock }} <= 0) ? 'bg-stone-100 text-stone-300 cursor-not-allowed' : 'bg-[#622A2A] text-white hover:bg-[#4e2222] border-none'"
                                        class="w-full py-4 font-bold uppercase text-[12px] tracking-[0.2em] transition-all duration-300 flex items-center justify-center rounded-lg shadow-sm">
                                    BELI SEKARANG
                                </button>
                            </div>

                            <template x-if="sizes.length && !selectedSize">
                                <p class="text-[9px] text-center text-red-500 uppercase tracking-widest font-bold mt-2">Silakan pilih ukuran terlebih dahulu</p>
                            </template>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </main>
</x-app-layout>
