{{-- resources/views/components/search-modal.blade.php --}}
<div x-data="{ 
        isOpen: false, 
        query: '', 
        results: [], 
        isLoading: false,
        fetchResults() {
            if (this.query.length < 2) {
                this.results = [];
                return;
            }
            this.isLoading = true;
            fetch(`/api/search?q=${encodeURIComponent(this.query)}`)
                .then(res => res.json())
                .then(data => {
                    this.results = data.products;
                    this.isLoading = false;
                })
                .catch(() => {
                    this.isLoading = false;
                });
        }
     }"
     @open-search.window="isOpen = true; $nextTick(() => $refs.searchInput.focus())"
     @keydown.window.escape="isOpen = false"
     x-show="isOpen"
     x-cloak
     class="fixed inset-0"
     style="z-index: 9999999;">
    
    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-stone-900/40 backdrop-blur-sm transition-opacity" 
         x-show="isOpen"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="isOpen = false"></div>

    {{-- Modal --}}
    <div class="flex items-start justify-center min-h-screen p-4 sm:p-0 pt-10 sm:pt-24">
        <div x-show="isOpen"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             @click.away="isOpen = false"
             class="relative w-full max-w-2xl bg-white rounded-lg shadow-2xl overflow-hidden flex flex-col max-h-[80vh]">
            
            {{-- Input Section --}}
            <div class="px-5 py-4 border-b border-stone-100" x-data="{ isFocused: false }">
                <div class="relative flex items-center bg-white border rounded-md overflow-hidden transition-all duration-300"
                     :style="isFocused ? 'border-color: #622A2A; box-shadow: 0 0 0 1px #622A2A;' : 'border-color: #e5e7eb;'">
                    {{-- Input --}}
                    <input type="text" 
                           x-model="query"
                           @input.debounce.300ms="fetchResults()"
                           @focus="isFocused = true"
                           @blur="isFocused = false"
                           x-ref="searchInput"
                           placeholder="Cari produk..." 
                           class="w-full border-none focus:ring-0 focus:ring-offset-0 focus:outline-none text-sm py-3 pl-4 pr-12 text-stone-800 placeholder-stone-400"
                           style="outline: none !important; box-shadow: none !important; border: none !important;">
                    
                    {{-- Controls (X then Search Icon) --}}
                    <div class="absolute flex items-center gap-3" style="right: 1.25rem;">
                        <button x-show="query.length > 0" 
                                @click="query = ''; results = []"
                                class="p-1 text-stone-300 hover:text-stone-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                        <div class="h-4 w-[1px] bg-stone-200" x-show="query.length > 0"></div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-stone-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Result Dropdown --}}
            <div class="overflow-y-auto max-h-[60vh] bg-white">
                <template x-if="isLoading">
                    <div class="flex items-center justify-center py-10">
                        <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-primary"></div>
                    </div>
                </template>

                <template x-if="!isLoading && results.length > 0">
                    <div class="flex flex-col">
                        <template x-for="res in results" :key="res.id">
                            <a :href="res.url" class="flex items-center gap-4 px-5 py-3 hover:bg-stone-50 transition-colors border-b border-stone-50 last:border-0 group">
                                <img :src="res.image" :alt="res.name" class="w-12 h-12 object-cover rounded flex-shrink-0">
                                <div class="flex-grow min-w-0">
                                    <p class="text-sm font-medium text-stone-700 truncate capitalize group-hover:text-primary transition-colors" x-text="res.name"></p>
                                    <p class="text-xs font-bold text-primary mt-0.5" x-text="'Rp ' + res.price"></p>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-stone-300 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                            </a>
                        </template>
                    </div>
                </template>

                <template x-if="!isLoading && query.length >= 2 && results.length === 0">
                    <div class="px-5 py-10 text-center">
                        <p class="text-sm text-stone-400 font-medium italic">Produk tidak ditemukan</p>
                    </div>
                </template>

                <template x-if="query.length < 2 && !isLoading">
                    <div class="px-5 py-8 text-center text-stone-300">
                        <p class="text-[10px] uppercase font-bold tracking-widest leading-loose">Masukkan kata kunci<br>untuk mulai mencari produk</p>
                    </div>
                </template>
            </div>

            {{-- View All Footer --}}
            <template x-if="results.length > 0">
                <div class="p-4 bg-stone-50/50 border-t border-stone-100 text-center">
                    <a :href="'/products?search=' + encodeURIComponent(query)" class="text-[11px] font-bold text-stone-400 hover:text-primary transition-colors uppercase tracking-widest">
                        Lihat semua hasil pencarian
                    </a>
                </div>
            </template>
        </div>
    </div>
</div>
