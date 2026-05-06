{{-- resources/views/components/footer.blade.php --}}

<footer class="bg-[#482b2b] text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Footer Top --}}
        <div class="py-16 border-b border-white/10">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12">

                {{-- Brand --}}
                <div class="pr-8">
                    <div class="mb-4">
                        <h3 class="font-bold text-lg uppercase tracking-widest text-white">Gdo Tinoel Craft</h3>
                    </div>
                    <p class="text-white/80 text-sm leading-relaxed mb-6 font-medium">
                        Kerajinan tangan dengan desain modern menggunakan bahan akrilik dan manik berkualitas tinggi.
                    </p>
                    <div class="flex gap-4">
                        {{-- Instagram --}}
                        <a href="https://instagram.com" class="text-white/80 hover:text-white transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" /></svg>
                        </a>
                        {{-- Facebook --}}
                        <a href="https://facebook.com" class="text-white/80 hover:text-white transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" /></svg>
                        </a>
                    </div>
                </div>

                {{-- Belanja --}}
                <div class="px-2">
                    <h4 class="font-bold mb-5 uppercase text-[11px] tracking-widest text-white">BELANJA</h4>
                    <ul class="space-y-3 text-sm text-white/80 font-medium">
                        <li><a href="{{ url('/products?category=dekorasi') }}" class="hover:text-white hover:underline transition-colors block py-0.5">Dekorasi</a></li>
                        <li><a href="{{ url('/products?category=aksesori') }}" class="hover:text-white hover:underline transition-colors block py-0.5">Aksesori</a></li>
                        <li><a href="{{ url('/products') }}" class="hover:text-white hover:underline transition-colors block py-0.5">Semua Produk</a></li>
                    </ul>
                </div>

                {{-- Akun --}}
                <div class="px-2">
                    <h4 class="font-bold mb-5 uppercase text-[11px] tracking-widest text-white">AKUN</h4>
                    <ul class="space-y-3 text-sm text-white/80 font-medium">
                        <li><a href="{{ route('login') }}" class="hover:text-white hover:underline transition-colors block py-0.5">Masuk</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-white hover:underline transition-colors block py-0.5">Daftar</a></li>
                        <li><a href="{{ route('dashboard') }}" class="hover:text-white hover:underline transition-colors block py-0.5">Pesanan Saya</a></li>
                        <li><a href="{{ url('/cart') }}" class="hover:text-white hover:underline transition-colors block py-0.5">Keranjang Belanja</a></li>
                    </ul>
                </div>

                {{-- Kontak --}}
                <div class="px-2">
                    <h4 class="font-bold mb-5 uppercase text-[11px] tracking-widest text-white">KONTAK</h4>
                    <ul class="space-y-4 text-sm text-white/80 font-medium">
                        <li>
                            <a href="mailto:gdoastini@gmail.com" class="flex items-center gap-3 hover:text-white transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg>
                                gdoastini@gmail.com
                            </a>
                        </li>
                        <li>
                            <a href="tel:+6285891228336" class="flex items-center gap-3 hover:text-white transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" /></svg>
                                +62 858 9122 8336
                            </a>
                        </li>
                        <li>
                            <div class="flex items-start gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" class="mt-0.5 flex-shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                                <span>Perum CKM Blok C4 No. 23 RT. 48 RW. 14, Bengle, Majalaya, Karawang, Jawa Barat, Indonesia</span>
                            </div>
                        </li>
                    </ul>
                </div>

            </div>
        </div>

        {{-- Footer Bottom --}}
        <div class="py-6">
            <div class="flex flex-col md:flex-row items-center justify-between text-xs text-white/50 font-medium">
                <p>&copy; {{ date('Y') }} Gdo Tinoel Craft. Semua hak dilindungi.</p>
                <div class="flex flex-wrap gap-x-6 gap-y-2 mt-4 md:mt-0">
                    <a href="#" class="hover:text-white transition-colors uppercase tracking-[0.1em]">Kebijakan Privasi</a>
                    <a href="#" class="hover:text-white transition-colors uppercase tracking-[0.1em]">Syarat & Ketentuan</a>
                    <a href="#" class="hover:text-white transition-colors uppercase tracking-[0.1em]">Sitemap</a>
                </div>
            </div>
        </div>

    </div>
</footer>
