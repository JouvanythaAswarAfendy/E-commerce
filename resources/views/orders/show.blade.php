<x-app-layout>
    <div class="min-h-screen bg-stone-50/50 pb-20">
        <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

            {{-- Back Button --}}
            <a href="{{ route('dashboard') }}?tab=orders"
               class="inline-flex items-center gap-2 text-stone-500 hover:text-primary text-sm font-bold mb-10 transition-all group uppercase tracking-widest">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" class="group-hover:-translate-x-1 transition-transform">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                </svg>
                Kembali ke Pesanan
            </a>

            <div class="space-y-8">
                
                {{-- SECTION 1: Header & Status --}}
                <div class="bg-white rounded-2xl border border-stone-200 shadow-sm overflow-hidden mb-8">
                    <div class="px-8 py-6 flex flex-col md:flex-row md:items-center md:justify-between gap-6 bg-white">
                        <div>
                            <p class="text-[10px] text-[#622A2A] uppercase tracking-[0.2em] font-bold mb-1.5">Informasi Transaksi</p>
                            <h1 class="text-2xl font-black text-stone-900 flex items-center gap-3">
                                #{{ $order->order_id }}
                            </h1>
                        </div>
                        @php
                            $st = strtolower($order->status);
                            $statusClass = match ($st) {
                                'selesai'    => 'status-selesai bg-green-50 text-green-700 border-green-200',
                                'dikirim'    => 'status-dikirim bg-blue-50 text-blue-700 border-blue-200',
                                'diproses'   => 'status-diproses bg-yellow-50 text-yellow-700 border-yellow-200',
                                'dibatalkan' => 'status-dibatalkan bg-red-50 text-red-700 border-red-200',
                                default      => 'status-default bg-stone-50 text-stone-600 border-stone-200',
                            };
                            $statusLabel = match ($st) {
                                'pending'    => 'Menunggu Pembayaran',
                                'diproses'   => 'Sedang Diproses',
                                'dikirim'    => 'Dalam Pengiriman',
                                'selesai'    => 'Pesanan Selesai',
                                'dibatalkan' => 'Dibatalkan',
                                default      => ucfirst($order->status),
                            };
                            $isLunas = in_array($st, ['diproses', 'dikirim', 'selesai', 'paid', 'success', 'challenge', 'settlement']);
                        @endphp
                        <div class="flex items-center gap-3 ml-auto">

                            <span class="px-5 py-2 rounded-full text-xs font-black border {{ $statusClass }} uppercase tracking-widest shadow-sm">
                                {{ $statusLabel }}
                            </span>

                        </div>
                    </div>

                    {{-- Grid Info (3 Kolom: Tgl/Waktu, Item, Total + Lunas) --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 border-t border-stone-100 divide-x divide-stone-100">
                        <div class="px-8 py-6">
                            <p class="text-[10px] uppercase tracking-widest font-bold text-stone-400 mb-1">TANGGAL & WAKTU PESANAN</p>
                            <p class="font-bold text-stone-900">{{ $order->created_at->format('d M Y') }}</p>
                            <p class="text-sm text-stone-500">{{ $order->created_at->format('H:i') }} WIB</p>
                        </div>
                        <div class="px-8 py-6">
                            <p class="text-[10px] uppercase tracking-widest font-bold text-stone-400 mb-2">Total Item</p>
                            <p class="text-sm font-bold text-stone-800">{{ $order->items->count() }} Produk</p>
                        </div>
                        <div class="pl-4 pr-8 py-6 flex items-center justify-between">

                            <div>
                                <p class="text-[10px] uppercase tracking-widest font-bold text-stone-400 mb-2">Total Transaksi</p>
                                <p class="text-lg font-black text-primary">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SECTION 2: Produk Dipesan --}}
                <div class="mb-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="h-6 w-1.5 bg-primary rounded-full"></div>
                        <h3 class="text-sm font-extrabold uppercase tracking-widest text-[#622A2A]">Produk Dipesan</h3>
                    </div>
                    <div class="space-y-4">
                        @foreach ($order->items as $item)
                            <div class="bg-white p-5 rounded-2xl border border-stone-200 shadow-sm hover:shadow-md transition-shadow flex items-center gap-6 overflow-hidden">
                                {{-- Product Image --}}
                                <div class="flex-shrink-0 w-24 h-24 rounded-lg bg-stone-100 border border-stone-100 overflow-hidden shadow-inner">
                                    @php
                                        $images = $item->product?->images;
                                        $firstImage = (is_array($images) && count($images) > 0) ? $images[0] : null;
                                    @endphp
                                    @if($firstImage)
                                        <img src="{{ asset('storage/' . $firstImage) }}"
                                             alt="{{ $item->product->name }}"
                                             class="w-full h-full object-cover rounded-lg">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-stone-50 rounded-lg">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1" class="text-stone-300">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                {{-- Product Info --}}
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-bold text-stone-900 text-lg truncate mb-1">{{ $item->product?->name ?? 'Produk tidak tersedia' }}</h3>
                                    <div class="flex flex-wrap items-center gap-4 text-sm text-stone-500">
                                        @if($item->size)
                                            <div class="flex items-center gap-1.5">
                                                <span class="text-[10px] uppercase font-bold text-stone-300 tracking-wider">Ukuran:</span>
                                                <span class="font-bold text-stone-700 bg-stone-100 px-2 py-0.5 rounded text-xs">{{ $item->size }}</span>
                                            </div>
                                        @endif
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-[10px] uppercase font-bold text-stone-300 tracking-wider">Jumlah:</span>
                                            <span class="font-bold text-stone-700">{{ $item->quantity }}x</span>
                                        </div>
                                    </div>
                                </div>
                                {{-- Price / Subtotal --}}
                                <div class="text-right ml-auto flex-shrink-0 border-l border-stone-100 pl-8 hidden sm:block">
                                    <p class="text-[10px] text-stone-400 uppercase font-bold tracking-widest mb-1">SUBTOTAL</p>
                                    <p class="font-black text-stone-900 text-lg">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                                    <p class="text-[11px] text-stone-400 font-medium">Rp {{ number_format($item->price, 0, ',', '.') }} / unit</p>
                                </div>
                                {{-- Spacer to push subtotal left --}}
                                <div class="w-3 hidden sm:block"></div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- SECTION 3: Alamat Pengiriman --}}
                <div class="mb-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="h-6 w-1.5 bg-primary rounded-full"></div>
                        <h3 class="text-sm font-extrabold uppercase tracking-widest text-[#622A2A]">ALAMAT PENGIRIMAN</h3>
                    </div>
                    <div class="bg-white p-6 rounded-2xl border border-stone-200 shadow-sm relative overflow-hidden group">
                        <div class="absolute top-0 right-0 p-4 opacity-10">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="text-primary">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                            </svg>
                        </div>
                        <div class="flex items-start gap-2 relative z-10">
                            <p class="text-sm text-stone-600 leading-relaxed font-medium">
                                {{ $order->shipping_address ?: 'Alamat tidak dicantumkan.' }}
                            </p>
                        </div>
                    </div>
                </div>

            </div>

        </main>
    </div>
</x-app-layout>
