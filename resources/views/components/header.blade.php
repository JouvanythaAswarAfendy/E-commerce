{{-- resources/views/components/header.blade.php --}}

<div x-data="{ menuOpen: false }">

    {{-- Sticky Header --}}
    <header class="sticky top-0 z-50 bg-primary shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">

                {{-- Logo --}}
                <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                    <x-logo size="md" />
                    <div class="hidden sm:block">
                        <h1 class="text-xl font-bold text-white group-hover:text-stone-200 transition-colors uppercase tracking-[0.2em]">
                            Gdo Tinoel Craft
                        </h1>
                        <p class="text-xs text-white/80">Kerajinan Akrilik & Manik-manik</p>
                    </div>
                </a>

                {{-- Desktop Navigation (Visual Dropdown) --}}
                <nav class="hidden lg:flex items-center gap-1">

                    @foreach($categories as $parent)
                        <div class="relative group h-20 flex items-center">
                            <a href="{{ url('/products?category=' . $parent->id) }}"
                               class="px-5 py-2 text-white/80 hover:text-white font-medium uppercase text-xs tracking-widest transition-colors flex items-center gap-1">
                                {{ $parent->name }}
                                @if($parent->children->count() > 0)
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                @endif
                            </a>
                            @if($parent->children->count() > 0)
                                <div class="absolute left-0 top-full hidden group-hover:block bg-white border-t-2 border-[#622A2A] shadow-lg z-50 min-w-max">
                                    <ul class="py-2">
                                        @foreach($parent->children as $child)
                                        <li>
                                            <a href="{{ url('/products?category=' . $child->id) }}"
                                               class="block px-6 py-2 text-stone-600 hover:text-[#622A2A] hover:bg-stone-50 text-sm transition-colors">
                                                {{ $child->name }}
                                            </a>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    @endforeach

                </nav>


                {{-- Right Side Icons --}}
                <div class="flex items-center gap-3">

                    {{-- Search --}}
                    <button @click="$dispatch('open-search')"
                        class="p-2 text-white hover:text-white hover:bg-white/10 rounded-lg transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </button>

                    {{-- Notifications (Hanya saat login) --}}
                    @auth
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open"
                                class="relative p-2 text-white hover:text-white hover:bg-white/10 rounded-lg transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                                </svg>
                                @if(auth()->user()->unreadNotifications->count() > 0)
                                    <span class="absolute top-0.5 right-0.5 bg-red-600 text-white text-[9px] font-bold rounded-full w-3.5 h-3.5 flex items-center justify-center border border-primary z-10">
                                        {{ auth()->user()->unreadNotifications->count() }}
                                    </span>
                                @endif
                            </button>

                            {{-- Dropdown Notifications --}}
                            <div x-show="open" @click.outside="open = false" x-cloak x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                                class="fixed mt-16 w-[400px] bg-white rounded-xl shadow-2xl border border-stone-200 overflow-hidden z-[999]"
                                style="right: 16px; top: 0;">
                                <div class="px-5 py-4 border-b border-stone-100 flex items-center justify-between bg-stone-50/50">
                                    <h3 class="text-xs font-bold text-stone-900 uppercase tracking-wider">Notifikasi</h3>
                                    @if(auth()->user()->unreadNotifications->count() > 0)
                                        <form action="{{ route('notifications.markAllRead') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-[10px] text-primary hover:underline font-bold uppercase tracking-tight">Tandai semua dibaca</button>
                                        </form>
                                    @endif
                                </div>
                                <div class="max-h-[450px] overflow-y-auto divide-y divide-stone-100">
                                    @forelse(auth()->user()->notifications as $notification)
                                        <a href="{{ route('notifications.read', $notification->id) }}"
                                           class="flex items-start gap-4 p-4 hover:bg-stone-50 transition-colors {{ $notification->read_at ? '' : 'bg-primary/5' }}">
                                            {{-- Status icon --}}
                                            <div class="flex-shrink-0 mt-0.5">
                                                @php $st = $notification->data['status'] ?? ''; @endphp
                                                @if($st === 'diproses')
                                                    <span class="inline-flex items-center justify-center w-9 h-9 aspect-square rounded-full bg-yellow-100 border border-yellow-200">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="text-yellow-600"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    </span>
                                                @elseif($st === 'dikirim')
                                                    <span class="inline-flex items-center justify-center w-9 h-9 aspect-square rounded-full bg-blue-100 border border-blue-200">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="text-blue-600"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
                                                    </span>
                                                @elseif($st === 'selesai')
                                                    <span class="inline-flex items-center justify-center w-9 h-9 aspect-square rounded-full bg-green-100 border border-green-200">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="text-green-600"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    </span>
                                                @elseif($st === 'dibatalkan')
                                                    <span class="inline-flex items-center justify-center w-9 h-9 aspect-square rounded-full bg-red-100 border border-red-200">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="text-red-600"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center justify-center w-9 h-9 aspect-square rounded-full bg-stone-100 border border-stone-200">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="text-stone-500"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                                                    </span>
                                                @endif
                                            </div>
                                            {{-- Content --}}
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center justify-between gap-2 mb-0.5">
                                                    <p class="text-[11px] font-bold text-primary uppercase tracking-widest">
                                                        @if(isset($notification->data['order_id']))
                                                            #{{ $notification->data['order_id'] }}
                                                        @else
                                                            INFO
                                                        @endif
                                                    </p>
                                                    @if(!$notification->read_at)
                                                        <div class="w-1.5 h-1.5 rounded-full bg-primary"></div>
                                                    @endif
                                                </div>
                                                <p class="text-sm text-stone-800 leading-normal break-words {{ $notification->read_at ? 'font-normal' : 'font-semibold' }}">
                                                    {{ $notification->data['message'] ?? 'Ada pembaruan pada pesanan Anda.' }}
                                                </p>
                                                <p class="text-[11px] text-stone-400 mt-1.5 flex items-center gap-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    {{ $notification->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                        </a>
                                    @empty
                                        <div class="px-4 py-12 text-center">
                                            <div class="w-16 h-16 bg-stone-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" class="text-stone-400">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                                                </svg>
                                            </div>
                                            <p class="text-sm font-bold text-stone-900">Belum ada notifikasi</p>
                                            <p class="text-xs text-stone-500 mt-1">Status pesananmu akan muncul di sini.</p>
                                        </div>
                                    @endforelse
                                </div>
                                @if(auth()->user()->notifications->count() > 0)
                                    <div class="px-5 py-4 border-t border-stone-100 bg-stone-50/50 text-center">
                                        <a href="{{ route('dashboard') }}?tab=orders" class="text-[11px] text-primary font-bold uppercase tracking-widest hover:underline">Lihat Semua Pesanan →</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endauth

                    {{-- Cart (Hanya untuk pembeli atau tamu) --}}
                    @if(!auth()->check() || auth()->user()->role !== 'penjual')
                    <a href="{{ url('/cart') }}"
                        class="relative p-2 text-white hover:text-white hover:bg-white/10 rounded-lg transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                        </svg>
                        {{-- Cart count badge --}}
                        @auth
                            @php $cartCount = \App\Models\Cart::where('user_id', auth()->id())->sum('qty'); @endphp
                            @if ($cartCount > 0)
                                <span
                                    class="absolute top-0 right-0 bg-[#4e2222] text-white text-[10px] font-bold rounded-full w-4 h-4 flex items-center justify-center border border-white/20">
                                    {{ $cartCount }}
                                </span>
                            @endif
                        @endauth
                    </a>
                    @endif

                    {{-- User Menu (sudah login) --}}
                    @auth
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open"
                                class="p-2 text-white hover:text-white hover:bg-white/10 rounded-lg transition-colors flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                                    viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd" />
                                </svg>
                            </button>

                            <div x-show="open" @click.outside="open = false" x-transition
                                class="absolute right-0 top-full mt-2 bg-white border border-stone-200 rounded-lg shadow-lg py-2 min-w-[200px] z-50">
                                <div class="px-4 py-2 border-b border-stone-100 mb-1 max-w-[250px]">
                                    <p class="text-sm font-semibold text-stone-900 truncate w-full" title="{{ Auth::user()->name }}">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-stone-500 truncate w-full" title="{{ Auth::user()->email }}">{{ Auth::user()->email }}</p>
                                </div>
                                @if(Auth::user()->role === 'penjual')
                                    <a href="{{ url('/admin') }}" class="block px-4 py-2 text-stone-700 hover:text-primary hover:bg-stone-50 text-sm transition-colors font-bold">
                                        Panel Admin
                                    </a>
                                @else
                                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-stone-700 hover:text-primary hover:bg-stone-50 text-sm transition-colors">
                                        Dashboard
                                    </a>
                                @endif
                                <hr class="my-2 border-stone-100">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50 text-sm transition-colors flex items-center gap-2">
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        {{-- Belum login --}}
                        <a href="{{ route('login') }}" class="hidden sm:block px-4 py-2 text-white hover:text-stone-300 font-bold uppercase text-xs tracking-wider transition-colors">
                            Masuk
                        </a>
                    @endauth

                    {{-- Mobile Menu Button --}}
                    <button @click="menuOpen = !menuOpen" class="lg:hidden p-2 text-white hover:bg-white/10 rounded-lg transition-colors">
                        <svg x-show="!menuOpen" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                        <svg x-show="menuOpen" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" style="display:none;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                </div>
            </div>

            {{-- Mobile Menu --}}
            <nav x-show="menuOpen" x-transition class="lg:hidden bg-primary border-t border-white/10 py-4 space-y-1">
                @foreach($categories as $parent)
                    <div x-data="{ open: false }">
                        <div class="flex items-center justify-between px-8 py-2">
                            <a href="{{ url('/products?category=' . $parent->id) }}"
                               class="text-white/70 text-sm hover:text-white transition-colors">
                                {{ $parent->name }}
                            </a>
                            @if($parent->children->count() > 0)
                                <button @click="open = !open" class="text-white/50 hover:text-white p-1">
                                    <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                            @endif
                        </div>
                        @if($parent->children->count() > 0)
                            <div x-show="open" class="bg-white/10 py-1">
                                @foreach($parent->children as $child)
                                    <a href="{{ url('/products?category=' . $child->id) }}"
                                       class="block px-12 py-2 text-white/50 text-xs hover:text-white transition-colors">
                                        {{ $child->name }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
                @guest
                    <hr class="my-2 border-white/10">
                    <a href="{{ route('login') }}" class="block px-8 py-2 text-white font-bold uppercase text-sm tracking-wider hover:text-white transition-colors">Masuk</a>
                @endguest
            </nav>

        </div>
    </header>

    {{-- Search Overlay Removed Here - Now in x-search-modal --}}

</div>

