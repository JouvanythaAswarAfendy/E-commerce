{{-- resources/views/cart/index.blade.php --}}
<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 text-green-700 rounded-xl border border-green-200">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 text-red-700 rounded-xl border border-red-200">
                    {{ session('error') }}
                </div>
            @endif

            <div class="flex flex-col md:flex-row gap-8">
                <!-- Cart Items -->
                <div class="w-full md:w-2/3">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6 border-b border-gray-100">
                            <h3 class="text-xl font-bold text-gray-900 font-primary uppercase tracking-widest">KERANJANG BELANJA</h3>
                        </div>
                        
                        @if($carts->count() > 0)
                            <div class="divide-y divide-gray-100">
                                @foreach($carts as $cart)
                                    <div class="p-6 flex flex-col sm:flex-row items-start sm:items-center gap-6">
                                        <!-- Image -->
                                        <div class="w-24 h-24 rounded-xl bg-gray-50 flex-shrink-0 overflow-hidden border border-stone-100">
                                            @php
                                                $image = is_array($cart->product->images) && count($cart->product->images) > 0 ? $cart->product->images[0] : null;
                                            @endphp
                                            @if($image)
                                                <img src="{{ asset('storage/'.$image) }}" alt="{{ $cart->product->name }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-gray-300">
                                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16"></path></svg>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <!-- Info -->
                                        <div class="flex-grow">
                                            <h4 class="text-lg font-bold text-[#1a1a1a] leading-tight mb-1 font-primary">
                                                <a href="{{ route('products.show', $cart->product->id) }}" class="hover:text-[#622A2A] transition-colors">
                                                    {{ $cart->product->name }}
                                                </a>
                                            </h4>
                                            <div class="flex items-center gap-3 mb-2">
                                                <span class="text-[10px] font-bold uppercase tracking-widest text-[#622A2A] font-primary">{{ $cart->product->category->name ?? 'Koleksi' }}</span>
                                                @if($cart->size)
                                                    <span class="text-[10px] px-2 py-0.5 bg-stone-50 text-[#622A2A] rounded font-bold uppercase tracking-widest border border-stone-100 font-primary">UKURAN: {{ $cart->size }}</span>
                                                @endif
                                            </div>
                                            <div class="text-[#622A2A] font-extrabold font-primary">Rp {{ number_format($cart->price, 0, ',', '.') }}</div>
                                        </div>

                                        <!-- Actions -->
                                        <div class="flex items-center gap-6">
                                            <!-- Qty -->
                                            <form action="{{ route('cart.update', $cart->id) }}" method="POST" class="flex items-center border border-gray-200 rounded-lg overflow-hidden bg-white shadow-sm">
                                                @csrf
                                                @method('PUT')
                                                <input type="number" name="qty" value="{{ $cart->qty }}" min="1" class="w-16 border-none text-center bg-transparent focus:ring-0 text-gray-900 font-bold" onchange="this.form.submit()">
                                            </form>

                                            <!-- Remove -->
                                            <form action="{{ route('cart.destroy', $cart->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-red-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Hapus Item">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="p-12 text-center">
                                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-stone-50 text-stone-300 mb-6 font-primary">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-700 font-primary uppercase tracking-widest">KERANJANG ANDA KOSONG</h3>
                                <p class="text-gray-500 mt-2 font-primary">Sepertinya Anda belum menambahkan produk apapun.</p>
                                <a href="{{ route('products.index') }}" class="inline-block mt-8 px-8 py-3 bg-[#622A2A] text-white font-bold uppercase text-[12px] tracking-widest rounded-lg hover:bg-[#4e2222] transition-all shadow-md font-primary">
                                    MULAI BELANJA
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="w-full md:w-1/3">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 sticky top-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-8 uppercase tracking-widest font-primary">RINGKASAN BELANJA</h3>
                        
                        <div class="space-y-4 mb-8 font-primary">
                            <div class="flex justify-between text-gray-600 text-sm">
                                <span>Subtotal ({{ $carts->sum('qty') }} item)</span>
                                <span class="font-bold text-stone-900">Rp {{ number_format($carts->sum('subtotal'), 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-gray-600 text-sm">
                                <span>Pengiriman</span>
                                <span class="text-green-600 font-bold uppercase text-[10px] tracking-widest">GRATIS</span>
                            </div>
                            <div class="border-t border-gray-100 pt-6 flex justify-between items-center">
                                <span class="text-lg font-bold text-gray-900">Total</span>
                                <span class="text-2xl font-extrabold text-[#622A2A]">Rp {{ number_format($carts->sum('subtotal'), 0, ',', '.') }}</span>
                            </div>
                        </div>

                        @if($carts->count() > 0)
                            <a href="{{ route('checkout.index') }}" class="block w-full py-4 bg-[#622A2A] hover:bg-[#4e2222] text-white text-center font-bold rounded-lg transition-all shadow-md uppercase text-[12px] tracking-[0.2em] font-primary">
                                LANJUT KE PEMBAYARAN
                            </a>
                        @endif

                        <div class="mt-4 mb-6 flex flex-col items-center">
                            <p class="text-[10px] font-bold uppercase tracking-widest text-stone-400 font-primary">METODE PEMBAYARAN</p>
                            <img src="{{ asset('images/Midtrans.png') }}" alt="Midtrans" class="w-24 h-auto mx-auto">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
