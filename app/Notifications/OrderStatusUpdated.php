<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusUpdated extends Notification
{
    use Queueable;

    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $order = $this->order->load(['items.product']);
        $user  = $notifiable;

        return (new MailMessage)
            ->subject('Update Status Pesanan #' . $this->order->order_id . ' - Gdo Tinoel Craft')
            ->view('emails.order_status_updated', compact('order', 'user'));
    }

    public function toArray($notifiable)
    {
        $statusMessage = match ($this->order->status) {
            'pending'    => 'Pesanan kamu sedang menunggu pembayaran.',
            'diproses'   => 'Pembayaran berhasil dikonfirmasi! Pesanan kamu sedang kami proses.',
            'dikirim'    => 'Pesanan kamu sedang dalam perjalanan ke alamatmu.',
            'selesai'    => 'Pesanan kamu telah berhasil diterima. Terima kasih!',
            'dibatalkan' => 'Pesanan kamu telah dibatalkan.',
            default      => 'Status pesanan #' . $this->order->order_id . ' telah diperbarui menjadi ' . $this->order->status . '.',
        };

        return [
            'order_id' => $this->order->order_id,
            'id'       => $this->order->id,
            'status'   => $this->order->status,
            'message'  => 'Pesanan #' . $this->order->order_id . ': ' . $statusMessage,
        ];
    }
}
