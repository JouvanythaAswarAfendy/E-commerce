<x-app-layout>

    {{-- SECTION 1: Hero / Jelajahi Koleksi --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 mt-4 mb-10">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 h-[500px] lg:h-[600px]">
            {{-- Left Split --}}
            <a href="{{ url('/products?category=dekorasi') }}" class="relative rounded-3xl overflow-hidden group shadow-md">
                <img src="{{ asset('images/tempat-tisu.jpeg') }}" alt="Dekorasi" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent group-hover:via-black/30 transition-colors duration-500"></div>
                <div class="absolute inset-x-0 bottom-0 p-10 flex flex-col items-center justify-end text-center">
                    <h2 class="text-3xl lg:text-4xl font-bold text-white mb-6 tracking-[0.2em] transform translate-y-2 group-hover:translate-y-0 transition-transform duration-300">DEKORASI</h2>
                    <span class="inline-flex items-center gap-2 text-white bg-white/20 backdrop-blur-md px-8 py-3 rounded-full text-sm font-bold uppercase tracking-widest hover:bg-white/30 transition-colors opacity-0 group-hover:opacity-100 transform translate-y-4 group-hover:translate-y-0 transition-all duration-300">
                        Jelajahi Dekorasi
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
                    </span>
                </div>
            </a>
            
            {{-- Right Split --}}
            <a href="{{ url('/products?category=aksesori') }}" class="relative rounded-3xl overflow-hidden group shadow-md">
                <img src="{{ asset('images/Beaded Hello Kitty.jpeg') }}" alt="Aksesori" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent group-hover:via-black/30 transition-colors duration-500"></div>
                <div class="absolute inset-x-0 bottom-0 p-10 flex flex-col items-center justify-end text-center">
                    <h2 class="text-3xl lg:text-4xl font-bold text-white mb-6 tracking-[0.2em] transform translate-y-2 group-hover:translate-y-0 transition-transform duration-300">AKSESORI</h2>
                    <span class="inline-flex items-center gap-2 text-white bg-white/20 backdrop-blur-md px-8 py-3 rounded-full text-sm font-bold uppercase tracking-widest hover:bg-white/30 transition-colors opacity-0 group-hover:opacity-100 transform translate-y-4 group-hover:translate-y-0 transition-all duration-300">
                        Jelajahi Aksesori
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
                    </span>
                </div>
            </a>
        </div>
    </section>

    {{-- SECTION 2: Produk Unggulan --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="mb-12 text-center">
            <h2 class="text-3xl md:text-4xl font-extrabold text-stone-900 tracking-tight">Produk Unggulan</h2>
            <p class="text-stone-500 mt-3 font-medium text-sm md:text-base">Pilihan akrilik dan manik terbaik dari pengrajin Gdo Tinoel Craft.</p>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($featuredProducts->take(4) as $product)
            {{-- Product Card --}}
            <a href="{{ route('products.show', $product->id) }}" class="bg-white rounded-2xl shadow-sm border border-stone-100 overflow-hidden group hover:shadow-xl transition-all duration-300 block">
                <div class="aspect-square bg-stone-100 relative overflow-hidden">
                    <img src="{{ asset('storage/' . (is_array($product->images) ? ($product->images[0] ?? 'images/placeholder.png') : $product->images)) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                        <span class="bg-white text-stone-900 hover:bg-stone-100 transform translate-y-4 group-hover:translate-y-0 transition-all duration-300 px-4 py-2 rounded-md font-semibold text-sm">Lihat Detail</span>
                    </div>
                </div>
                <div class="p-6">
                    <p class="text-xs font-bold text-stone-400 uppercase tracking-widest mb-1">{{ $product->category->parent->name ?? $product->category->name }}</p>
                    <h3 class="font-bold text-stone-900 text-lg group-hover:text-primary transition-colors line-clamp-1">{{ $product->name }}</h3>
                    <p class="text-primary font-bold mt-2 text-lg">Rp {{ number_format($product->display_price, 0, ',', '.') }}</p>
                </div>
            </a>
            @endforeach
        </div>
        
        <div class="mt-14 text-center">
            <a href="{{ url('/products') }}" class="inline-block border-2 border-primary text-primary hover:bg-primary hover:text-white px-10 py-4 rounded-lg font-bold text-sm uppercase tracking-[0.15em] transition-colors">
                Lihat Semua Produk
            </a>
        </div>
    </section>

    {{-- SECTION 3: Tentang Kami --}}
    <section class="bg-white py-24 mb-10 border-t border-b border-stone-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                {{-- Left Text --}}
                <div class="max-w-xl">
                    <p class="text-primary font-bold text-xs uppercase tracking-[0.2em] mb-4">TENTANG KAMI</p>
                    <h2 class="text-4xl lg:text-5xl font-extrabold text-stone-900 mb-6 leading-tight">Kerajinan Tangan Berkualitas</h2>
                    <div class="space-y-4 text-stone-600 leading-relaxed font-medium">
                        <p>Gdo Tinoel Craft adalah UMKM yang berdedikasi untuk menciptakan kerajinan tangan berkualitas tinggi menggunakan bahan akrilik dan manik pilihan.</p>
                        <p>Setiap produk dirancang dengan cermat dan dibuat oleh pengrajin berpengalaman kami untuk memastikan kepuasan pelanggan.</p>
                        <p>Kami percaya bahwa setiap produk handmade membawa cerita unik dan nilai seni yang tak ternilai.</p>
                    </div>
                    <div class="mt-10">
                        <a href="{{ url('/products') }}" class="inline-flex justify-center px-8 py-4 bg-primary text-white font-bold rounded-lg uppercase tracking-widest text-sm hover:bg-primary/90 transition-all shadow-lg shadow-primary/30">
                            MULAI BELANJA
                        </a>
                    </div>
                </div>
                
                {{-- Right Images (2x2 Grid) --}}
                <div class="grid grid-cols-2 gap-4">
                    <div class="aspect-square bg-stone-100 rounded-3xl overflow-hidden shadow-sm">
                        <img src="{{ asset('images/jualan.jpg') }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-700">
                    </div>
                    <div class="aspect-square bg-stone-100 rounded-3xl overflow-hidden shadow-sm lg:mt-10">
                        <img src="{{ asset('images/jualan(1).jpg') }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-700">
                    </div>
                    <div class="aspect-[4/5] bg-stone-100 rounded-3xl overflow-hidden shadow-sm lg:-mt-10">
                        <img src="{{ asset('images/tempat-tisu(1).jpeg') }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-700">
                    </div>
                    <div class="aspect-square bg-stone-100 rounded-3xl overflow-hidden shadow-sm">
                        <img src="{{ asset('images/logo-gdo.png') }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-700">
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- SECTION 4: Call to Action (Ajakan) --}}
    <section class="bg-primary text-white py-24 my-20">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h2 class="text-4xl lg:text-5xl font-extrabold mb-6 tracking-tight">Siap Mempercantik Rumahmu?</h2>
            <p class="text-white/80 text-lg mb-10 max-w-2xl mx-auto font-medium">Temukan koleksi lengkap kerajinan tangan berkualitas dengan harga terjangkau.</p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ url('/products') }}" class="inline-block bg-white text-primary px-10 py-4 font-bold rounded-md uppercase tracking-wider hover:bg-stone-100 transition-colors shadow-lg">
                    BELANJA SEKARANG
                </a>
                <a href="{{ route('register') }}" class="inline-block bg-transparent border-2 border-white/60 text-white px-10 py-4 font-bold rounded-md uppercase tracking-wider hover:bg-white/10 hover:border-white transition-colors">
                    DAFTAR SEKARANG
                </a>
            </div>
        </div>
    </section>

</x-app-layout>
