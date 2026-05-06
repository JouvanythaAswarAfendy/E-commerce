{{-- resources/views/home.blade.php --}}
<x-app-layout>

    {{-- Dual Hero Banner --}}
    <section class="grid grid-cols-1 md:grid-cols-2 gap-0">

        {{-- Dekorasi Banner --}}
        <a href="{{ url('/products?category=dekorasi') }}" class="relative overflow-hidden group h-96 md:h-[500px] block">
            <div class="absolute inset-0 bg-black/10 group-hover:bg-black/30 transition-colors duration-300 z-10"></div>
            <img src="https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?w=800&h=500&fit=crop"
                alt="Koleksi Dekorasi"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
            <div class="absolute inset-0 flex items-end p-8 md:p-12 z-20">
                <div class="text-white">
                    <h2 class="text-4xl md:text-5xl font-semibold mb-2">Dekorasi</h2>
                    <p class="text-white/80 uppercase text-xs tracking-widest">Jelajahi Koleksi</p>
                </div>
            </div>
        </a>

        {{-- Aksesori Banner --}}
        <a href="{{ url('/products?category=aksesori') }}"
            class="relative overflow-hidden group h-96 md:h-[500px] block">
            <div class="absolute inset-0 bg-black/10 group-hover:bg-black/30 transition-colors duration-300 z-10"></div>
            <img src="https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?w=800&h=500&fit=crop"
                alt="Koleksi Aksesori"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
            <div class="absolute inset-0 flex items-end p-8 md:p-12 z-20">
                <div class="text-white">
                    <h2 class="text-4xl md:text-5xl font-semibold mb-2">Aksesori</h2>
                    <p class="text-white/80 uppercase text-xs tracking-widest">Jelajahi Koleksi</p>
                </div>
            </div>
        </a>

    </section>

    {{-- Produk Unggulan --}}
    <section class="py-20 md:py-28 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex items-center justify-between mb-12">
                <div>
                    <h2 class="text-4xl md:text-5xl font-semibold text-stone-900 mb-3">Produk Unggulan</h2>
                    <p class="text-stone-500">Koleksi terpopuler dari pelanggan kami</p>
                </div>
                <a href="{{ url('/products') }}"
                    class="hidden md:flex items-center gap-2 text-[#622A2A] font-semibold hover:translate-x-1 transition-transform text-sm">
                    Lihat Semua
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                @forelse($featuredProducts as $product)
                    <a href="{{ route('products.show', $product->id) }}"
                        class="group bg-white border border-stone-200 hover:border-[#622A2A] rounded-lg overflow-hidden transition-all duration-300 hover:shadow-lg">
                        <div class="aspect-square overflow-hidden relative bg-stone-100">
                            @if ($product->images)
                                <img src="{{ asset('storage/' . collect($product->images)->first()) }}"
                                    alt="{{ $product->name }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-stone-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"
                                        class="text-stone-300">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                    </svg>
                                </div>
                            @endif
                            <div
                                class="absolute top-3 right-3 bg-[#622A2A] text-white px-3 py-1 rounded text-xs font-semibold">
                                Favorit
                            </div>
                        </div>
                        <div class="p-4">
                            <p class="text-xs text-stone-400 mb-1 uppercase tracking-wider">
                                {{ $product->category->name ?? '-' }}</p>
                            <h3
                                class="text-sm font-semibold text-stone-900 mb-2 line-clamp-2 group-hover:text-[#622A2A] transition-colors">
                                {{ $product->name }}
                            </h3>
                            <div class="flex items-center justify-between mt-3">
                                <span class="text-lg font-bold text-[#622A2A]">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </span>
                                <span
                                    class="p-2 bg-stone-100 text-stone-700 group-hover:bg-[#622A2A] group-hover:text-white rounded transition-colors text-sm">→</span>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-4 text-center py-12 text-stone-400">Belum ada produk unggulan.</div>
                @endforelse
            </div>

            <a href="{{ url('/products') }}"
                class="flex md:hidden mt-8 justify-center px-8 py-3 bg-primary text-white font-semibold uppercase text-xs tracking-wider rounded-lg hover:bg-[#4a1f1f] transition-colors">
                Lihat Semua Produk
            </a>
        </div>
    </section>

    {{-- Jelajahi Kategori --}}
    <section class="py-20 md:py-28 bg-stone-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-4xl md:text-5xl font-semibold text-stone-900 mb-12 text-center">Jelajahi Kategori</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach ([['name' => 'Dekorasi', 'icon' => '✨', 'desc' => 'Bunga, Vas, Gantungan', 'slug' => 'dekorasi'], ['name' => 'Aksesori', 'icon' => '💎', 'desc' => 'Gelang, Kalung, Kunci', 'slug' => 'aksesori'], ['name' => 'Suvenir', 'icon' => '🎁', 'desc' => 'Hadiah, Souvenir Unik', 'slug' => 'suvenir']] as $cat)
                    <a href="{{ url('/products?category=' . $cat['slug']) }}"
                        class="group p-8 bg-white border border-stone-200 rounded-lg hover:border-[#622A2A] hover:shadow-lg transition-all text-center">
                        <div class="text-5xl mb-4">{{ $cat['icon'] }}</div>
                        <h3
                            class="text-2xl font-semibold text-stone-900 mb-2 group-hover:text-[#622A2A] transition-colors">
                            {{ $cat['name'] }}
                        </h3>
                        <p class="text-sm text-stone-500 mb-4">{{ $cat['desc'] }}</p>
                        <div
                            class="flex items-center justify-center gap-2 text-[#622A2A] font-semibold opacity-0 group-hover:opacity-100 transition-opacity text-sm">
                            Lihat
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Tentang Kami --}}
    <section class="py-20 md:py-28 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div>
                    <span class="text-xs uppercase tracking-widest text-[#622A2A] font-semibold mb-4 block">Tentang
                        Kami</span>
                    <h2 class="text-4xl md:text-5xl font-semibold text-stone-900 mb-6">Kerajinan Tangan Berkualitas</h2>
                    <div class="space-y-4 text-stone-500 leading-relaxed text-sm">
                        <p>Gdo Tinoel Craft adalah UMKM yang berdedikasi untuk menciptakan kerajinan tangan berkualitas
                            tinggi menggunakan bahan akrilik dan manik pilihan.</p>
                        <p>Setiap produk dirancang dengan cermat dan dibuat oleh pengrajin berpengalaman kami untuk
                            memastikan kepuasan pelanggan.</p>
                        <p>Kami percaya bahwa setiap produk handmade membawa cerita unik dan nilai seni yang tak
                            ternilai.</p>
                    </div>
                    <a href="{{ url('/products') }}"
                        class="inline-block mt-8 px-8 py-3 bg-primary text-white font-semibold uppercase text-xs tracking-wider rounded-lg hover:bg-[#4a1f1f] transition-colors">
                        Mulai Belanja
                    </a>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <img src="https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?w=400&h=400&fit=crop"
                        alt="Craft 1" class="w-full aspect-square object-cover rounded-lg" />
                    <img src="https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?w=400&h=400&fit=crop"
                        alt="Craft 2" class="w-full aspect-square object-cover rounded-lg" />
                    <img src="https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?w=400&h=400&fit=crop"
                        alt="Craft 3" class="w-full aspect-square object-cover rounded-lg" />
                    <img src="https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?w=400&h=400&fit=crop"
                        alt="Craft 4" class="w-full aspect-square object-cover rounded-lg" />
                </div>
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="bg-primary text-white py-16 md:py-24">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6">
            <h2 class="text-4xl md:text-5xl font-semibold">Siap Mempercantik Rumahmu?</h2>
            <p class="text-stone-400 text-lg max-w-2xl mx-auto">
                Temukan koleksi lengkap kerajinan tangan berkualitas dengan harga terjangkau.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center pt-4">
                <a href="{{ url('/products') }}"
                    class="px-8 py-3 bg-white text-stone-900 font-semibold uppercase text-xs tracking-wider rounded-lg hover:bg-stone-100 transition-colors">
                    Belanja Sekarang
                </a>
                @guest
                    <a href="{{ route('register') }}"
                        class="px-8 py-3 bg-white/10 border border-white/30 text-white font-semibold uppercase text-xs tracking-wider rounded-lg hover:bg-white/20 transition-colors">
                        Daftar Sekarang
                    </a>
                @endguest
            </div>
        </div>
    </section>

</x-app-layout>
