<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoiceAdd extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    private $inovice;
    public function __construct($invoice)
    {
        $this->invoice=$invoice;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = 'http://127.0.0.1:8000/invoices_all/'.$this->invoice;
        return (new MailMessage)
                    ->subject('إضافة فاتورة جديدة')
                    ->line('إضافة فاتورة جديدة')
                    ->action('عرض الفاتورة الجديدة', $url)
                    ->line('شكرا لاستخدامك موقع الفواتير الخاص بنا');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
