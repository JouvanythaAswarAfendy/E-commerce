{{-- resources/views/dashboard.blade.php --}}
<x-app-layout>
    <div class="min-h-screen bg-stone-50">

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16" x-data="{ tab: '{{ request()->query('tab', 'orders') }}' }">
            
            @if(session('status') === 'profile-updated' || session('status') === 'password-updated')
                <div class="mb-6 p-4 bg-green-50 text-green-700 rounded-xl border border-green-200 flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span>Profil berhasil diperbarui!</span>
                </div>
            @endif

            @if(request()->query('status') == 'success')
                <div class="mb-6 p-4 bg-green-50 text-green-700 rounded-xl border border-green-200 flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span>Pembayaran berhasil! Pesanan Anda segera kami proses.</span>
                </div>
            @elseif(request()->query('status') == 'pending')
                <div class="mb-6 p-4 bg-yellow-50 text-yellow-700 rounded-xl border border-yellow-200 flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Pembayaran tertunda. Silakan selesaikan pembayaran sesuai instruksi.</span>
                </div>
            @elseif(request()->query('status') == 'error')
                <div class="mb-6 p-4 bg-red-50 text-red-700 rounded-xl border border-red-200 flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Terjadi kesalahan saat pembayaran. Silakan coba lagi.</span>
                </div>
            @endif


            {{-- Stats Cards --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-12">

                {{-- Total Pesanan --}}
                <div class="bg-white border border-stone-200 rounded-lg p-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-stone-500 text-sm mb-2 font-medium uppercase tracking-wider">Total Pesanan</p>
                            <p class="text-3xl font-bold text-stone-900">{{ $totalOrders ?? 0 }}</p>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" class="text-stone-400">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                        </svg>
                    </div>
                </div>

                {{-- Sedang Diproses --}}
                <div class="bg-white border border-stone-200 rounded-lg p-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-stone-500 text-sm mb-2 font-medium uppercase tracking-wider">Sedang Diproses</p>
                            <p class="text-3xl font-bold text-stone-900">{{ $processingOrders ?? 0 }}</p>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" class="text-stone-400">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>

                {{-- Total Belanja --}}
                <div class="bg-white border border-stone-200 rounded-lg p-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-stone-500 text-sm mb-2 font-medium uppercase tracking-wider">Total Belanja</p>
                            <p class="text-2xl font-bold text-stone-900">Rp
                                {{ number_format($totalSpent ?? 0, 0, ',', '.') }}</p>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" class="text-stone-400">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75" />
                        </svg>
                    </div>
                </div>

                {{-- Member Sejak --}}
                <div class="bg-white border border-stone-200 rounded-lg p-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-stone-500 text-sm mb-2 font-medium uppercase tracking-wider">Member Sejak</p>
                            <p class="text-xl font-bold text-stone-900">{{ Auth::user()->created_at->format('M Y') }}
                            </p>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" class="text-stone-400">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                    </div>
                </div>

            </div>

            {{-- Main Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">

                {{-- Sidebar --}}
                <aside class="md:col-span-1">
                    <div class="bg-white border border-stone-200 rounded-lg p-6 sticky top-24">

                        {{-- Avatar & Info --}}
                        <div class="text-center mb-6 pb-6 border-b border-stone-100">
                            <div
                                class="w-16 h-16 bg-primary text-white rounded-full mx-auto mb-3 flex items-center justify-center text-xl font-bold">
                                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                            </div>
                            <h3 class="font-semibold text-stone-900 truncate w-full px-4" title="{{ Auth::user()->name }}">{{ Auth::user()->name }}</h3>
                            <p class="text-xs text-stone-500 truncate w-full px-4 mt-1" title="{{ Auth::user()->email }}">{{ Auth::user()->email }}</p>
                        </div>

                        {{-- Nav --}}
                        <nav class="space-y-1">
                            <button @click="tab = 'orders'"
                                :class="tab === 'orders' ? 'bg-primary text-white' : 'text-stone-700 hover:bg-stone-100'"
                                class="w-full flex items-center gap-3 px-4 py-3 rounded-lg transition-colors text-sm font-semibold">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                </svg>
                                Pesanan Saya
                            </button>

                            <button @click="tab = 'profile'"
                                :class="tab === 'profile' ? 'bg-primary text-white' : 'text-stone-700 hover:bg-stone-100'"
                                class="w-full flex items-center gap-3 px-4 py-3 rounded-lg transition-colors text-sm font-semibold">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                </svg>
                                Edit Profil
                            </button>

                            <hr class="my-3 border-stone-100">

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center gap-3 px-4 py-3 rounded-lg transition-colors text-sm font-semibold text-red-500 hover:bg-red-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                                    </svg>
                                    Logout
                                </button>
                            </form>
                        </nav>

                    </div>
                </aside>

                {{-- Main Content area --}}
                <div class="md:col-span-3">
                    
                    {{-- Tab: Pesanan --}}
                    <section x-show="tab === 'orders'">
                        <h2 class="text-2xl font-semibold text-stone-900 mb-6">Pesanan Saya</h2>

                        @forelse ($orders as $order)
                            <div class="bg-white border border-stone-100 rounded-lg p-6 mb-4 hover:shadow-md transition-all group">
                                <div class="flex items-center justify-between gap-6 w-full">
                                    {{-- ID Pesanan --}}
                                    <div class="flex flex-col">
                                        <p class="text-xs text-stone-400 mb-1 uppercase tracking-widest font-bold">ID Pesanan</p>
                                        <p class="font-semibold text-stone-900 text-sm truncate">#{{ $order->order_id }}</p>
                                    </div>

                                    {{-- Tanggal --}}
                                    <div class="flex flex-col">
                                        <p class="text-xs text-stone-400 mb-1 uppercase tracking-widest font-bold">Tanggal</p>
                                        <p class="text-sm text-stone-600 font-medium whitespace-nowrap">{{ $order->created_at->format('d M Y') }}</p>
                                    </div>

                                    {{-- Total --}}
                                    <div class="flex flex-col mr-auto">

                                        <p class="text-xs text-stone-400 mb-1 uppercase tracking-widest font-bold">Total</p>
                                        <p class="font-semibold text-stone-900 text-sm whitespace-nowrap">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                                    </div>

                                    {{-- Status --}}
                                    <div class="flex flex-col">
                                        <p class="text-xs text-stone-400 mb-1 uppercase tracking-widest font-bold">Status</p>
                                        @php
                                            $st = strtolower($order->status);
                                            $statusLabel = ucfirst($st);
                                            $statusClass = match ($st) {
                                                'selesai'    => 'bg-green-50 text-green-700 border-green-100',
                                                'dikirim'    => 'bg-blue-50 text-blue-700 border-blue-100',
                                                'diproses'   => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                                'dibatalkan' => 'bg-red-50 text-red-700 border-red-100',
                                                default      => 'bg-stone-100 text-stone-700 border-stone-200',
                                            };
                                        @endphp
                                        <span class="inline-block px-3 py-1 text-[11px] rounded-lg font-bold border {{ $statusClass }} leading-none">
                                            {{ $st === 'pending' ? 'Belum Dibayar' : $statusLabel }}
                                        </span>
                                    </div>

                                    {{-- Action --}}
                                    <div class="flex items-center gap-2">
                                        @if($st === 'pending')
                                            <a href="{{ route('orders.pay', $order->order_id) }}" 
                                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-primary text-white hover:bg-[#4a1f1f] transition-all shadow-sm group/btn">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75-8.25V5.25A2.25 2.25 0 014.5 3h15a2.25 2.25 0 012.25 2.25v13.5A2.25 2.25 0 0119.5 21h-15a2.25 2.25 0 01-2.25-2.25V8.25z" />
                                                </svg>
                                                Bayar
                                            </a>
                                            <form action="{{ route('orders.cancel', $order->order_id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                                                @csrf
                                                <button type="submit" 
                                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-red-50 text-red-600 border border-red-100 hover:bg-red-700 hover:text-white transition-all shadow-sm group/btn">
                                                    Batalkan
                                                </button>
                                            </form>
                                        @elseif($st === 'dikirim')
                                            <form action="{{ route('orders.confirm_received', $order->order_id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin pesanan sudah diterima?')">
                                                @csrf
                                                <button type="submit" 
                                                   style="background-color: #1a7a4a; color: #ffffff;"
                                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-green-800 transition-all shadow-sm group/btn">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    Pesanan Diterima
                                                </button>
                                            </form>
                                        @endif
                                        <a href="{{ route('orders.show', $order->order_id) }}" 
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-stone-50 border border-stone-100 text-stone-600 hover:bg-primary hover:text-white hover:border-primary transition-all shadow-sm group/btn">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.644C3.399 8.21 7.31 5 12 5c4.69 0 8.511 3.21 9.964 6.678.043.102.043.219 0 .322C20.511 15.79 16.59 19 12 19c-4.69 0-8.511-3.21-9.964-6.678z" />
                                            </svg>
                                            Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="bg-white border border-stone-200 rounded-lg p-12 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"
                                    class="text-stone-300 mx-auto mb-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                </svg>
                                <p class="text-stone-500 text-sm mb-4">Belum ada pesanan</p>
                                <a href="{{ url('/products') }}"
                                    class="inline-block px-6 py-2 bg-primary text-white text-sm font-semibold rounded-lg hover:bg-[#4a1f1f] transition-colors uppercase tracking-wider">
                                    Mulai Belanja
                                </a>
                            </div>
                        @endforelse

                        {{-- Pagination --}}
                        @if ($orders->hasPages())
                            <div class="mt-6">
                                {{ $orders->links() }}
                            </div>
                        @endif
                    </section>

                    {{-- Tab: Profile --}}
                    <section x-show="tab === 'profile'" style="display:none;">
                        <h2 class="text-2xl font-semibold text-stone-900 mb-6">Edit Profil</h2>
                        
                        <div class="space-y-6">
                            <div class="bg-white border border-stone-200 rounded-lg p-6 sm:p-8">
                                <div class="max-w-xl">
                                    @include('profile.partials.update-profile-information-form')
                                </div>
                            </div>
                
                            <div class="bg-white border border-stone-200 rounded-lg p-6 sm:p-8">
                                <div class="max-w-xl">
                                    @include('profile.partials.update-password-form')
                                </div>
                            </div>
                
                            <div class="bg-white border border-stone-200 rounded-lg p-6 sm:p-8">
                                <div class="max-w-xl">
                                    @include('profile.partials.delete-user-form')
                                </div>
                            </div>
                        </div>
                    </section>

                </div>

            </div>

            </div>
        </main>

    </div>
</x-app-layout>
