{{-- resources/views/errors/404.blade.php --}}
<x-app-layout>

    <main class="flex-1 flex items-center justify-center min-h-[60vh]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center py-32">
            <div class="space-y-6">
                <div class="text-9xl md:text-[120px] font-light text-stone-200 mb-8 select-none">404</div>
                <h1 class="text-4xl md:text-5xl font-semibold text-stone-900">Halaman Tidak Ditemukan</h1>
                <p class="text-lg text-stone-500 max-w-lg mx-auto">
                    Maaf, halaman yang Anda cari tidak dapat ditemukan. Mungkin sudah dipindahkan atau dihapus.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center pt-8">
                    <a href="{{ url('/') }}"
                        class="inline-block px-8 py-3 bg-stone-900 text-white font-semibold uppercase text-xs tracking-wider hover:bg-[#622A2A] transition-colors rounded-lg">
                        Kembali ke Beranda
                    </a>
                    <button onclick="window.history.back()"
                        class="inline-block px-8 py-3 bg-stone-100 text-stone-700 font-semibold uppercase text-xs tracking-wider hover:bg-stone-200 transition-colors rounded-lg border border-stone-200">
                        Kembali
                    </button>
                </div>
            </div>
        </div>
    </main>

</x-app-layout>
