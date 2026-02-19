<?php

namespace App\Notifications;

use App\Models\Incident;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class IncidentReported extends Notification implements ShouldQueue
{
    use Queueable;

    public $incident;

    /**
     * Create a new notification instance.
     */
    public function __construct(Incident $incident)
    {
        $this->incident = $incident;
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
            ->subject('Incident critique détecté: ' . $this->incident->title)
            ->greeting('Alerte Urgente')
            ->line('Un incident a été signalé:')
            ->line('Ressource: ' . $this->incident->resource->name)
            ->line('Titre: ' . $this->incident->title)
            ->line('Gravité: ' . strtoupper($this->incident->severity))
            ->line('Description: ' . $this->incident->description)
            ->line('Signalé par: ' . $this->incident->user->name)
            ->action('Voir l\'incident', route('incidents.show', $this->incident))
            ->line('Veuillez vérifier et traiter cette affaire rapidement.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'incident_id' => $this->incident->id,
            'resource_name' => $this->incident->resource->name,
            'title' => $this->incident->title,
            'severity' => $this->incident->severity,
            'message' => 'Nouvel incident signalé.',
        ];
    }
}
