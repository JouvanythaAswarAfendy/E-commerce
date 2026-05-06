<x-app-layout>
    <div class="py-12 bg-gradient-to-br from-stone-50 to-stone-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 text-red-700 rounded-lg border border-red-200 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('checkout.process') }}" method="POST">
                @csrf
                
                @if(isset($isDirect) && $isDirect)
                    <input type="hidden" name="is_direct" value="1">
                    <input type="hidden" name="product_id" value="{{ $product_id }}">
                    <input type="hidden" name="qty" value="{{ $qty }}">
                    <input type="hidden" name="size" value="{{ $size }}">
                @endif

                <div class="flex flex-col md:flex-row gap-8 items-start">
                    <!-- Column Left: Alamat Pengiriman (60% width) -->
                    <div class="w-full md:w-[60%]">
                        <div class="bg-white rounded-xl shadow-sm border border-stone-100 p-8 md:p-10">
                            <h3 class="text-[16px] md:text-[18px] font-bold text-[#622A2A] mb-10 uppercase tracking-[0.2em] border-b border-stone-50 pb-4 font-primary">
                                ALAMAT PENGIRIMAN
                            </h3>
                            
                            <div class="space-y-8">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                                    <div>
                                        <label class="block text-[13px] md:text-[14px] font-bold uppercase tracking-[0.1em] text-stone-500 mb-3 font-primary">NAMA LENGKAP</label>
                                        <input type="text" value="{{ Auth::user()->name }}" disabled class="w-full rounded-lg border-stone-100 bg-stone-50 text-stone-400 text-[14px] md:text-[15px] font-medium p-4 focus:ring-0">
                                    </div>
                                    <div>
                                        <label class="block text-[13px] md:text-[14px] font-bold uppercase tracking-[0.1em] text-stone-500 mb-3 font-primary">EMAIL</label>
                                        <input type="email" value="{{ Auth::user()->email }}" disabled class="w-full rounded-lg border-stone-100 bg-stone-50 text-stone-400 text-[14px] md:text-[15px] font-medium p-4 focus:ring-0">
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <label for="shipping_address" class="block text-[13px] md:text-[14px] font-bold uppercase tracking-[0.1em] text-[#622A2A] mb-3 font-primary">ALAMAT LENGKAP <span class="text-red-500 font-bold">*</span></label>
                                    <textarea id="shipping_address" name="shipping_address" rows="5" required placeholder="Contoh: Jl. Mangga No. 12, RT 01/RW 02, Kec. Serpong, Tangerang" class="w-full rounded-lg border-stone-200 shadow-sm focus:border-[#622A2A] focus:ring-0 p-4 text-[14px] md:text-[15px] leading-relaxed text-stone-700 placeholder:text-stone-300"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Column Right: Pesanan & Ringkasan (40% width) -->
                    <div class="w-full md:w-[40%] md:sticky md:top-24">
                        <div class="bg-white rounded-xl shadow-sm border border-stone-100 p-8 md:p-10">
                            <h3 class="text-[16px] md:text-[18px] font-bold text-[#622A2A] mb-10 uppercase tracking-[0.2em] border-b border-stone-50 pb-4 font-primary">
                                PESANAN ANDA
                            </h3>
                            
                            <div class="space-y-6 mb-10 max-h-[40vh] overflow-y-auto pr-2 scrollbar-hide">
                                @foreach($carts as $cart)
                                    <div class="flex flex-col text-sm border-b border-stone-50 pb-6 last:border-0 last:pb-0">
                                        <div class="flex justify-between items-start mb-2">
                                            <div class="text-[13px] md:text-[14px] font-bold text-[#1a1a1a] line-clamp-2 pr-6 uppercase tracking-wider leading-relaxed font-primary">
                                                {{ $cart->product->name }}
                                            </div>
                                            <div class="font-bold text-[#622A2A] flex-shrink-0 text-[14px]">
                                                Rp {{ number_format($cart->subtotal, 0, ',', '.') }}
                                            </div>
                                        </div>
                                        <div class="text-stone-400 text-[12px] md:text-[13px] font-bold uppercase tracking-widest flex items-center gap-4 font-primary">
                                            <span>JUMLAH: {{ $cart->qty }}</span>
                                            @if($cart->size)
                                                <span class="text-[#622A2A] bg-stone-50 px-2 py-0.5 rounded">UKURAN: {{ $cart->size }}</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="pt-8 mb-10">
                                <div class="flex justify-between items-center">
                                    <span class="text-[15px] md:text-[16px] text-stone-500 font-bold uppercase tracking-[0.1em] font-primary">TOTAL TAGIHAN</span>
                                    <span class="text-[20px] md:text-[22px] text-[#622A2A] font-extrabold tracking-tight font-primary">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <button type="submit" class="w-full py-4 px-6 bg-[#622A2A] hover:bg-[#4e2222] text-white text-center font-bold rounded-lg transition-all duration-300 shadow-xl hover:shadow-2xl hover:-translate-y-0.5 flex items-center justify-center gap-3 uppercase text-[12px] tracking-[0.2em] group font-primary">
                                BAYAR SEKARANG
                            </button>

                            <div class="mt-4 pt-4 flex flex-col items-center">
                                <p class="text-[10px] font-bold text-stone-400 uppercase tracking-widest font-primary">METODE PEMBAYARAN</p>
                                <img src="{{ asset('images/Midtrans.png') }}" alt="Midtrans" class="w-24 h-auto grayscale hover:grayscale-0 transition-all duration-500 opacity-70 hover:opacity-100">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
