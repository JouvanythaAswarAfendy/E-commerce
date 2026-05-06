<x-app-layout>
    <div class="min-h-screen bg-stone-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            
            {{-- Action Buttons (Hide on print) --}}
            <div class="mb-8 flex justify-between items-center print:hidden">
                <a href="{{ route('orders.show', $order->order_id) }}" class="inline-flex items-center gap-2 text-stone-500 hover:text-stone-800 font-bold text-sm uppercase tracking-widest transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Kembali
                </a>
                <button onclick="window.print()" class="inline-flex items-center gap-2 bg-primary text-white px-6 py-2.5 rounded-xl font-black text-xs uppercase tracking-widest shadow-md hover:bg-[#4a1f1f] transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231a1.125 1.125 0 01-1.12-1.227L6.34 18m11.318-5.318a4.5 4.5 0 00-6.364 0m6.364 0c.456.456.816.989 1.057 1.575m-7.421-1.575a4.5 4.5 0 016.364 0m-6.364 0a4.5 4.5 0 00-1.057 1.575m4.174-1.575c.062-1.353.09-2.703.084-4.053m0 4.053c.006-1.35-.022-2.7-.084-4.053m0 0a3 3 0 015.824-1.356m-5.824 1.356a3 3 0 00-5.824-1.356m0 0a2.25 2.25 0 00-1.944 1.49l-1.04 3.12a2.25 2.25 0 00-.059.505V8.25a2.25 2.25 0 002.25 2.25h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v1.125" />
                    </svg>
                    Cetak Invoice
                </button>
            </div>

            {{-- Invoice Container --}}
            <div class="bg-white shadow-xl rounded-3xl overflow-hidden border border-stone-200 print:shadow-none print:border-none print:rounded-none">
                
                {{-- Header --}}
                <div class="px-10 py-12 bg-stone-900 text-white flex flex-col md:flex-row justify-between gap-8">
                    <div>
                        <h1 class="text-4xl font-black uppercase tracking-tighter mb-2">Invoice</h1>
                        <p class="text-stone-400 text-sm font-bold uppercase tracking-[0.3em]">#{{ $order->order_id }}</p>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-black text-primary mb-1">E-Commerce Store</div>
                        <p class="text-stone-400 text-xs">Jl. Contoh No. 123, Jakarta, Indonesia</p>
                        <p class="text-stone-400 text-xs">support@ecommerce.com | +62 812 3456 789</p>
                    </div>
                </div>

                {{-- Billing Info --}}
                <div class="p-10 grid grid-cols-1 md:grid-cols-2 gap-12 border-b border-stone-100">
                    <div>
                        <p class="text-[10px] font-black text-stone-400 uppercase tracking-widest mb-4">Ditagihkan Kepada</p>
                        <h3 class="text-xl font-black text-stone-900 mb-2">{{ $order->user->name }}</h3>
                        <p class="text-stone-500 text-sm leading-relaxed max-w-xs">
                            {{ $order->shipping_address ?: 'Alamat tidak tersedia' }}
                        </p>
                        <p class="mt-2 text-stone-500 text-sm font-bold">{{ $order->user->email }}</p>
                    </div>
                    <div class="md:text-right">
                        <p class="text-[10px] font-black text-stone-400 uppercase tracking-widest mb-4">Detail Transaksi</p>
                        <div class="space-y-2">
                            <div class="flex md:justify-end gap-4 text-sm">
                                <span class="text-stone-400 font-bold uppercase tracking-wider text-[10px]">Tanggal:</span>
                                <span class="text-stone-900 font-black">{{ $order->created_at->format('d F Y') }}</span>
                            </div>
                            <div class="flex md:justify-end gap-4 text-sm">
                                <span class="text-stone-400 font-bold uppercase tracking-wider text-[10px]">Metode Bayar:</span>
                                <span class="text-stone-900 font-black uppercase">{{ $order->payment_method ?: 'Midtrans' }}</span>
                            </div>
                            <div class="flex md:justify-end gap-4 text-sm">
                                <span class="text-stone-400 font-bold uppercase tracking-wider text-[10px]">Status:</span>
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ strtolower($order->status) === 'selesai' ? 'bg-green-100 text-green-700' : 'bg-stone-100 text-stone-700' }}">
                                    {{ $order->status }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Table --}}
                <div class="p-10">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b-2 border-stone-100">
                                <th class="py-4 text-[10px] font-black text-stone-400 uppercase tracking-widest">Deskripsi Produk</th>
                                <th class="py-4 text-[10px] font-black text-stone-400 uppercase tracking-widest text-center">Qty</th>
                                <th class="py-4 text-[10px] font-black text-stone-400 uppercase tracking-widest text-right">Harga</th>
                                <th class="py-4 text-[10px] font-black text-stone-400 uppercase tracking-widest text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-50">
                            @foreach($order->items as $item)
                            <tr>
                                <td class="py-6">
                                    <div class="font-black text-stone-900">{{ $item->product?->name ?? 'Produk dihapus' }}</div>
                                    @if($item->size)
                                        <div class="text-[10px] text-stone-400 font-bold uppercase tracking-wider mt-1">Ukuran: {{ $item->size }}</div>
                                    @endif
                                </td>
                                <td class="py-6 text-center font-bold text-stone-600">{{ $item->quantity }}x</td>
                                <td class="py-6 text-right font-bold text-stone-600">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td class="py-6 text-right font-black text-stone-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Footer / Totals --}}
                <div class="p-10 bg-stone-50/50 flex flex-col md:flex-row justify-between items-start gap-8">
                    <div class="max-w-xs">
                        <p class="text-[10px] font-black text-stone-400 uppercase tracking-widest mb-2">Catatan</p>
                        <p class="text-xs text-stone-500 leading-relaxed italic">
                            Terima kasih telah berbelanja di toko kami. Jika ada kendala dengan pesanan ini, silakan hubungi customer service kami dengan melampirkan ID Pesanan.
                        </p>
                    </div>
                    <div class="w-full md:w-64 space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-stone-400 font-bold uppercase tracking-widest text-[10px]">Total Belanja</span>
                            <span class="text-stone-900 font-bold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-stone-400 font-bold uppercase tracking-widest text-[10px]">Biaya Pengiriman</span>
                            <span class="text-stone-900 font-bold">Rp 0</span>
                        </div>
                        <div class="pt-3 border-t border-stone-200 flex justify-between items-center">
                            <span class="text-stone-900 font-black uppercase tracking-widest text-xs">Total Akhir</span>
                            <span class="text-2xl font-black text-primary">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
                
                {{-- Signature/Thank you --}}
                <div class="px-10 py-8 border-t border-stone-100 flex justify-between items-center text-[10px] font-bold text-stone-400 uppercase tracking-[0.2em]">
                    <div>Dicetak pada {{ now()->format('d/m/Y H:i') }}</div>
                    <div class="text-primary font-black">Authorized by E-Commerce Store</div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            body {
                background-color: white !important;
            }
            .min-h-screen {
                padding: 0 !important;
                background-color: white !important;
            }
            nav, footer {
                display: none !important;
            }
        }
    </style>
</x-app-layout>
