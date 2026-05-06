<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LowStockAlert extends Notification implements ShouldQueue
{
    use Queueable;

    protected $productName;
    protected $size;
    protected $stock;

    /**
     * Create a new notification instance.
     */
    public function __construct($productName, $stock, $size = null)
    {
        $this->productName = $productName;
        $this->stock = $stock;
        $this->size = $size;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $subject = '⚠️ Peringatan Stok Rendah: ' . $this->productName;
        $message = "Stok untuk produk **{$this->productName}** " . ($this->size ? "ukuran **{$this->size}** " : "") . "hampir habis.";

        return (new MailMessage)
            ->subject($subject)
            ->greeting('Halo Admin!')
            ->line($message)
            ->line('Sisa stok saat ini: **' . $this->stock . '**')
            ->action('Cek Stok di Dashboard', url('/admin/restock'))
            ->line('Segera lakukan restok agar pelanggan tetap bisa berbelanja.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable): array
    {
        return [
            'product_name' => $this->productName,
            'size' => $this->size,
            'stock' => $this->stock,
            'message' => "Stok {$this->productName} " . ($this->size ? "({$this->size}) " : "") . "rendah: {$this->stock} unit tersisa.",
        ];
    }
}
