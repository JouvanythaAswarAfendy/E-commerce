<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Proses Pembayaran') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full mx-auto p-8 bg-white rounded-3xl shadow-xl text-center border border-gray-100">
            
            <div class="w-20 h-20 bg-stone-50 text-primary rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            </div>

            <h3 class="text-2xl font-bold text-gray-900 mb-2">Selesaikan Pembayaran</h3>
            <p class="text-gray-500 mb-8">Silakan klik tombol di bawah ini untuk memproses pembayaran melalui sistem aman Midtrans.</p>
            
            <div class="bg-gray-50 rounded-xl p-4 mb-8 text-left">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm text-gray-500">Order ID:</span>
                    <span class="text-sm font-semibold text-gray-900">{{ $order->order_id }}</span>
                </div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm text-gray-500">Total Bayar:</span>
                    <span class="text-lg font-bold text-primary">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                </div>
            </div>

            <button id="pay-button" class="w-full py-4 px-6 bg-primary hover:bg-[#4a1f1f] text-white font-bold rounded-xl transition-all shadow-md hover:shadow-lg focus:ring-4 focus:ring-primary/10">
                Lanjutkan Pembayaran
            </button>
            <a href="{{ route('dashboard') }}" class="block mt-4 text-sm text-gray-500 hover:text-gray-800 transition-colors">Lihat Dashboard</a>
        </div>
    </div>

    <!-- Midtrans Snap JS -->
    <script type="text/javascript"
        src="{{ config('services.midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
        data-client-key="{{ config('services.midtrans.client_key') ?? env('MIDTRANS_CLIENT_KEY') }}"></script>
    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function(){
            // SnapToken acquired from previous step
            snap.pay('{{ $snapToken }}', {
                // Optional
                onSuccess: function(result){
                    window.location.href = "{{ route('midtrans.finish') }}?order_id=" + result.order_id + "&status_code=" + result.status_code + "&transaction_status=" + result.transaction_status;
                },
                // Optional
                onPending: function(result){
                    window.location.href = "{{ route('dashboard') }}?status=pending";
                },
                // Optional
                onError: function(result){
                    window.location.href = "{{ route('dashboard') }}?status=error";
                }
            });
        };
        
        // Auto click to make it seamless
        setTimeout(() => {
            document.getElementById('pay-button').click();
        }, 1000);
    </script>
</x-app-layout>
