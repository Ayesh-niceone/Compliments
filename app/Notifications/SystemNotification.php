<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class SystemNotification extends Notification
{
    use Queueable;

    protected $message;
    protected $data;

    public function __construct($message, $data = [])
    {
        $this->message = $message;
        $this->data = $data;
    }

    // Choose channels: database, mail, broadcast, etc.
    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    // For email notifications
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('New Notification')
                    ->line($this->message)
                    ->action('View', url('/'))
                    ->line('Thank you for using our system!');
    }

    // For database notifications
    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->message,
            'data' => $this->data,
        ];
    }

    // Optional: for real-time notifications (Pusher / Laravel Echo)
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => $this->message,
            'data' => $this->data,
        ]);
    }
}
