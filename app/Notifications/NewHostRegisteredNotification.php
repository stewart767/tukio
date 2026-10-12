<?php

namespace App\Notifications;

use App\Models\Host;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewHostRegisteredNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Host $host
    ) {
        //
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
        $eventTypeLabels = [
            'wedding' => 'Wedding',
            'birthday' => 'Birthday',
            'kitchen_party' => 'Kitchen Party',
            'send_off' => 'Send Off',
            'corporate' => 'Corporate',
        ];

        $eventType = $eventTypeLabels[$this->host->event_type] ?? ucfirst($this->host->event_type);

        return (new MailMessage)
            ->subject('New Host Registration - ' . $this->host->full_name)
            ->greeting('Hello Admin,')
            ->line('A new host has registered on the MyCard platform.')
            ->line('**Host Details:**')
            ->line('**Name:** ' . $this->host->full_name)
            ->line('**Email:** ' . $this->host->email)
            ->line('**Phone:** ' . $this->host->phone)
            ->line('**Event Type:** ' . $eventType)
            ->line('**Number of Guests:** ' . number_format($this->host->number_of_guests))
            ->line('**Total Cost:** TZS ' . number_format($this->host->total_cost, 2))
            ->action('View All Hosts', route('admin.hosts.index'))
            ->line('Please review the host account and take any necessary actions.')
            ->salutation('Best regards, MyCard System');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'host_id' => $this->host->id,
            'host_name' => $this->host->full_name,
            'host_email' => $this->host->email,
        ];
    }
}
