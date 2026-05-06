<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Status Pesanan - Gdo Tinoel Craft</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f5f5f4; color: #1c1917; }
        .wrapper { max-width: 600px; margin: 32px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
        .header { background-color: #622A2A; padding: 32px 40px; text-align: center; }
        .header .brand { color: #ffffff; font-size: 22px; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; }
        .header .tagline { color: rgba(255,255,255,0.75); font-size: 12px; margin-top: 4px; }
        .body { padding: 40px; }
        .greeting { font-size: 18px; font-weight: 600; color: #1c1917; margin-bottom: 12px; }
        .intro { font-size: 14px; color: #57534e; line-height: 1.6; margin-bottom: 28px; }
        .status-badge { display: inline-block; padding: 10px 24px; border-radius: 999px; font-size: 14px; font-weight: 700; margin-bottom: 28px; }
        .status-pending    { background: #fef3c7; color: #92400e; }
        .status-diproses   { background: #fef9c3; color: #854d0e; }
        .status-dikirim    { background: #dbeafe; color: #1e40af; }
        .status-selesai    { background: #dcfce7; color: #166534; }
        .status-dibatalkan { background: #fee2e2; color: #991b1b; }
        .status-default    { background: #f5f5f4; color: #44403c; }
        .status-description { font-size: 13px; color: #78716c; margin-bottom: 28px; line-height: 1.5; }
        .order-box { background: #fafaf9; border: 1px solid #e7e5e4; border-radius: 10px; padding: 24px; margin-bottom: 28px; }
        .order-box h3 { font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em; color: #a8a29e; margin-bottom: 16px; font-weight: 600; }
        .order-row { display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 10px; }
        .order-row:last-child { margin-bottom: 0; padding-top: 12px; border-top: 1px dashed #e7e5e4; }
        .order-label { font-size: 13px; color: #78716c; }
        .order-value { font-size: 13px; color: #1c1917; font-weight: 600; text-align: right; max-width: 280px; }
        .order-total { font-size: 15px; color: #622A2A; font-weight: 700; }
        .cta-btn { display: block; text-align: center; background: #622A2A; color: #ffffff !important; text-decoration: none; padding: 14px 32px; border-radius: 8px; font-size: 14px; font-weight: 700; letter-spacing: 0.05em; margin-bottom: 28px; }
        .divider { border: none; border-top: 1px solid #e7e5e4; margin: 28px 0; }
        .footer { background: #1c1917; padding: 28px 40px; text-align: center; }
        .footer .store-name { color: #ffffff; font-size: 14px; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; }
        .footer .footer-text { color: #78716c; font-size: 11px; margin-top: 8px; line-height: 1.6; }
        @media (max-width: 600px) {
            .body { padding: 24px; }
            .header { padding: 24px; }
            .footer { padding: 24px; }
            .order-row { flex-direction: column; gap: 2px; }
            .order-value { text-align: left; }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        {{-- Header --}}
        <div class="header">
            <div class="brand">🎀 Gdo Tinoel Craft</div>
            <div class="tagline">Kerajinan Akrilik &amp; Manik-manik</div>
        </div>

        {{-- Body --}}
        <div class="body">
            <p class="greeting">Halo, {{ $user->name }}! 👋</p>
            <p class="intro">
                Ada kabar terbaru mengenai pesananmu. Berikut update status pesanan kamu di <strong>Gdo Tinoel Craft</strong>:
            </p>

            {{-- Status Badge --}}
            @php
                $statusClass = match($order->status) {
                    'pending'    => 'status-pending',
                    'diproses'   => 'status-diproses',
                    'dikirim'    => 'status-dikirim',
                    'selesai'    => 'status-selesai',
                    'dibatalkan' => 'status-dibatalkan',
                    default      => 'status-default',
                };
                $statusLabel = match($order->status) {
                    'pending'    => '⏳ Menunggu Pembayaran',
                    'diproses'   => '⚙️ Sedang Diproses',
                    'dikirim'    => '🚚 Dalam Pengiriman',
                    'selesai'    => '✅ Selesai',
                    'dibatalkan' => '❌ Dibatalkan',
                    default      => ucfirst($order->status),
                };
                $statusDesc = match($order->status) {
                    'pending'    => 'Pesanan kamu sedang menunggu pembayaran. Segera selesaikan pembayaran agar pesanan dapat kami proses.',
                    'diproses'   => 'Pembayaran berhasil dikonfirmasi! Pesanan kamu sedang kami proses dan akan segera dikirimkan.',
                    'dikirim'    => 'Pesanan kamu sedang dalam perjalanan menuju alamat yang kamu daftarkan. Mohon bersabar ya!',
                    'selesai'    => 'Pesanan kamu telah berhasil diterima. Terima kasih telah berbelanja di Gdo Tinoel Craft! 🎉',
                    'dibatalkan' => 'Pesanan kamu telah dibatalkan. Jika kamu merasa ini adalah kesalahan, silakan hubungi kami.',
                    default      => 'Status pesananmu telah diperbarui.',
                };
                $firstItem = $order->items->first();
                $productName = $firstItem?->product?->name ?? '-';
                $moreItems = $order->items->count() > 1 ? ' (+' . ($order->items->count() - 1) . ' produk lainnya)' : '';
            @endphp

            <div style="text-align:center;">
                <span class="status-badge {{ $statusClass }}">{{ $statusLabel }}</span>
            </div>

            <p class="status-description">{{ $statusDesc }}</p>

            {{-- Order Details Box --}}
            <div class="order-box">
                <h3>Detail Pesanan</h3>
                <div class="order-row">
                    <span class="order-label">Nomor Pesanan</span>
                    <span class="order-value">#{{ $order->order_id }}</span>
                </div>
                <div class="order-row">
                    <span class="order-label">Produk</span>
                    <span class="order-value">{{ $productName }}{{ $moreItems }}</span>
                </div>
                <div class="order-row">
                    <span class="order-label">Tanggal Pesanan</span>
                    <span class="order-value">{{ $order->created_at->format('d M Y, H:i') }} WIB</span>
                </div>
                <div class="order-row">
                    <span class="order-label">Total Pembayaran</span>
                    <span class="order-value order-total">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- CTA Button --}}
            <a href="{{ route('orders.show', $order->order_id) }}" class="cta-btn">
                Lihat Detail Pesanan →
            </a>

            <hr class="divider">

            <p style="font-size:13px; color:#78716c; line-height:1.6; text-align:center;">
                Jika kamu memiliki pertanyaan, balas email ini atau hubungi kami.<br>
                Terima kasih sudah berbelanja di <strong style="color:#622A2A;">Gdo Tinoel Craft</strong>! 🎀
            </p>
        </div>

        {{-- Footer --}}
        <div class="footer">
            <div class="store-name">Gdo Tinoel Craft</div>
            <div class="footer-text">
                Kerajinan Akrilik &amp; Manik-manik Berkualitas<br>
                Email ini dikirim secara otomatis, mohon tidak membalas langsung.<br>
                © {{ date('Y') }} Gdo Tinoel Craft. All rights reserved.
            </div>
        </div>
    </div>
</body>
</html>
