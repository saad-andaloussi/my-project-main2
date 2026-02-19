<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservationApproved extends Notification implements ShouldQueue
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
     *
     * @return array<int, string>
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
            ->subject('Votre réservation a été approuvée')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Votre réservation pour la ressource ' . $this->reservation->resource->name . ' a été approuvée.')
            ->line('Période: ' . $this->reservation->start_time->format('d/m/Y H:i') . ' à ' . $this->reservation->end_time->format('d/m/Y H:i'))
            ->line('Prix total: €' . $this->reservation->total_price)
            ->action('Voir la réservation', route('reservations.show', $this->reservation))
            ->line('Merci d\'utiliser notre service!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'reservation_id' => $this->reservation->id,
            'resource_name' => $this->reservation->resource->name,
            'start_time' => $this->reservation->start_time,
            'end_time' => $this->reservation->end_time,
            'message' => 'Votre réservation a été approuvée.',
        ];
    }
}
