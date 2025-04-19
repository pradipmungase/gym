<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class WebPushNotification extends Notification
{
    use Queueable;

    protected $notificationData;

    public function __construct(array $notificationData = [])
    {
        $this->notificationData = $notificationData;
    }

    public function via($notifiable)
    {
        return [WebPushChannel::class];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title($this->notificationData['title'] ?? 'Default Title')
            ->body($this->notificationData['body'] ?? 'Default Message')
            ->action($this->notificationData['action_text'] ?? 'View', 'view_action')
            ->data([
                'url' => url('/admin/dashboard'),
                'user_id' => $notifiable->id,
                'custom_data' => $this->notificationData['custom_data'] ?? null
            ]);
    }
}