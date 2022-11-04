<?php

namespace App\Notifications;

use App\Models\AssetService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ServiceDueNotification extends Notification
{
    use Queueable;

    private $service;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(AssetService $service)
    {
        $this->service = $service;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("Asset Due Service Notification #{$this->service->id}")
            ->greeting("Hello {$notifiable->first_name}")
            ->line("The asset {$this->service->asset->name} is due service in {$this->service->days_remaining} days.")
            ->action('View Details', url('/'))
            ->line('Thank you!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'subject' => "Asset due service",
            'content' => "The asset {$this->service->asset->name} is due service in {$this->service->days_remaining} days.",
            'service_id' => $this->service->id,
            'notification_type' => "Service Due Notification"
        ];
    }
}
