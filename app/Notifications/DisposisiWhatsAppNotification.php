<?php

namespace App\Notifications;

use Twilio\Rest\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class DisposisiWhatsAppNotification extends Notification
{
    use Queueable;
    protected $dispotition;

    /**
     * Create a new notification instance.
     */
    public function __construct($dispotition)
    {
        $this->dispotition = $dispotition;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['whatsapp'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    // public function toWhatsApp($notifiable)
    // {
    //     $twilio = new Client(config('services.twilio.sid'), config('services.twilio.auth_token'));

    //     $message = "Halo {$notifiable->nama},\n"
    //         . "Anda menerima disposisi baru dari pimpinan:\n"
    //         // . "Nomor Surat: {$this->dispotition->suratMasuk->nomor_surat}\n"
    //         // . "Perihal: {$this->dispotition->suratMasuk->perihal}\n"
    //         // . "Catatan: {$this->dispotition->catatan}\n"
    //         . "Silakan cek aplikasi untuk detail lebih lanjut.";

    //     $twilio->messages->create(
    //         "whatsapp:{$notifiable->nomor_whatsapp}", // Nomor tujuan (format: whatsapp:+628xxx)
    //         [
    //             "from" => config('services.twilio.whatsapp_number'),
    //             "body" => $message
    //         ]
    //     );
    // } 

    public function toWhatsApp($notifiable)
    {
        $response = Http::post('https://api.gateway-whatsapp.com/send', [
            'api_key' => 'your_api_key',
            'number' => $notifiable->nomor_whatsapp,
            'message' => "Pesan disposisi...",
        ]);
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
