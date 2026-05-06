<x-filament-panels::page>
    <div class="space-y-6">
        {{-- 1. Filter Section --}}
        <x-filament::section>
            <x-slot name="heading">
                Filter Tanggal
            </x-slot>

            <div class="flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-[200px]">
                    <x-filament::input.wrapper>
                        <x-slot name="prefix">
                            Dari
                        </x-slot>
                        <x-filament::input
                            type="date"
                            wire:model="dateFrom"
                        />
                    </x-filament::input.wrapper>
                </div>

                <div class="flex-1 min-w-[200px]">
                    <x-filament::input.wrapper>
                        <x-slot name="prefix">
                            Sampai
                        </x-slot>
                        <x-filament::input
                            type="date"
                            wire:model="dateTo"
                        />
                    </x-filament::input.wrapper>
                </div>

                <div class="flex gap-2">
                    <x-filament::button wire:click="applyFilter">
                        Terapkan
                    </x-filament::button>
                    
                    <x-filament::button color="gray" wire:click="$set('dateFrom', null); $set('dateTo', null); applyFilter();">
                        Reset
                    </x-filament::button>
                </div>
            </div>
        </x-filament::section>

        {{-- 2. Stats Overview --}}
        @livewire(\App\Filament\Pages\SalesReport\Widgets\StatsOverview::class, ['dateFrom' => $dateFrom, 'dateTo' => $dateTo])

        {{-- 3. Online Orders Table --}}
        <x-filament::section>
            <x-slot name="heading">
                Pesanan Online
            </x-slot>

            <div class="overflow-x-auto border border-gray-200 rounded-lg dark:border-gray-700">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-800 dark:text-gray-400">
                        <tr>
                            <th class="px-6 py-3">No. Pesanan</th>
                            <th class="px-6 py-3">Waktu</th>
                            <th class="px-6 py-3">Produk</th>
                            <th class="px-6 py-3 text-center">Jumlah</th>
                            <th class="px-6 py-3 text-right">Total</th>
                            <th class="px-6 py-3 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-900">
                        @forelse ($onlineOrders as $order)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $order->order_id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4">
                                    <div class="space-y-1">
                                        @foreach ($order->items as $item)
                                            <div class="text-xs">
                                                {{ $item->product->name ?? 'Produk Terhapus' }}
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">{{ $order->items->sum('quantity') }}</td>
                                <td class="px-6 py-4 text-right font-semibold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $statusColor = match ($order->status) {
                                            'selesai' => 'success',
                                            'diproses', 'dikirim' => 'info',
                                            'dibatalkan' => 'danger',
                                            'pending' => 'warning',
                                            default => 'warning',
                                        };
                                        $statusLabel = match ($order->status) {
                                            'pending' => 'Belum Bayar',
                                            'diproses' => 'Diproses',
                                            'dikirim' => 'Dikirim',
                                            'selesai' => 'Selesai',
                                            'dibatalkan' => 'Dibatalkan',
                                            default => ucfirst($order->status),
                                        };
                                    @endphp
                                    <x-filament::badge :color="$statusColor">
                                        {{ $statusLabel }}
                                    </x-filament::badge>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">Tidak ada pesanan online.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-filament::section>

        {{-- 4. Offline Transactions Table --}}
        <x-filament::section>
            <x-slot name="heading">
                Transaksi Offline
            </x-slot>

            <div class="overflow-x-auto border border-gray-200 rounded-lg dark:border-gray-700">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-800 dark:text-gray-400">
                        <tr>
                            <th class="px-6 py-3">No. Transaksi</th>
                            <th class="px-6 py-3">Waktu</th>
                            <th class="px-6 py-3">Produk</th>
                            <th class="px-6 py-3 text-center">Jumlah</th>
                            <th class="px-6 py-3 text-right">Total</th>
                            <th class="px-6 py-3 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-900">
                        @forelse ($offlineTransactions as $transaction)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $transaction->transaction_code }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4">
                                    <div class="space-y-1">
                                        @foreach ($transaction->items as $item)
                                            <div class="text-xs">
                                                {{ $item->product_name ?? ($item->product->name ?? 'Produk') }}
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">{{ $transaction->items->sum('qty') }}</td>
                                <td class="px-6 py-4 text-right font-semibold">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $statusColor = match ($transaction->status) {
                                            'selesai' => 'success',
                                            'dibayar' => 'info',
                                            'dibatalkan' => 'danger',
                                            default => 'warning',
                                        };
                                    @endphp
                                    <x-filament::badge :color="$statusColor">
                                        {{ ucfirst($transaction->status) }}
                                    </x-filament::badge>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">Tidak ada transaksi offline.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-filament::section>

        {{-- 5. Monthly Revenue Chart (Paling Bawah) --}}
        @livewire(\App\Filament\Widgets\MonthlyRevenueChart::class, ['dateFrom' => $dateFrom, 'dateTo' => $dateTo])
    </div>
</x-filament-panels::page>
