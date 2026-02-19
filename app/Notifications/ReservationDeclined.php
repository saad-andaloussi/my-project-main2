<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservationDeclined extends Notification implements ShouldQueue
{
    use Queueable;

    public $reservation;
    public $reason;

    /**
     * Create a new notification instance.
     */
    public function __construct(Reservation $reservation, $reason = null)
    {
        $this->reservation = $reservation;
        $this->reason = $reason;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('Votre réservation a été refusée')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Votre réservation pour la ressource ' . $this->reservation->resource->name . ' a été refusée.')
            ->line('Période demandée: ' . $this->reservation->start_time->format('d/m/Y H:i') . ' à ' . $this->reservation->end_time->format('d/m/Y H:i'));

        if ($this->reason) {
            $mail->line('Raison: ' . $this->reason);
        }

        return $mail
            ->action('Faire une nouvelle demande', route('reservations.create'))
            ->line('Merci de votre compréhension.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'reservation_id' => $this->reservation->id,
            'resource_name' => $this->reservation->resource->name,
            'reason' => $this->reason,
            'message' => 'Votre réservation a été refusée.',
        ];
    }
}
