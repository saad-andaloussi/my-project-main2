<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservationStarting extends Notification implements ShouldQueue
{
    use Queueable;

    public $reservation;

    /**
     * Create a new notification instance.
     */
    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
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
        return (new MailMessage)
            ->subject('Votre réservation commence bientôt')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Votre réservation pour la ressource ' . $this->reservation->resource->name . ' commence dans 1 heure.')
            ->line('Détails:')
            ->line('• Début: ' . $this->reservation->start_time->format('d/m/Y H:i'))
            ->line('• Fin: ' . $this->reservation->end_time->format('d/m/Y H:i'))
            ->line('• Ressource: ' . $this->reservation->resource->name)
            ->action('Voir la réservation', route('reservations.show', $this->reservation))
            ->line('Assurez-vous que tout est prêt.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'reservation_id' => $this->reservation->id,
            'resource_name' => $this->reservation->resource->name,
            'start_time' => $this->reservation->start_time,
            'message' => 'Votre réservation commence bientôt.',
        ];
    }
}
