<?php

namespace App\Notifications;

// use App\Laravue\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class AuditTrail extends Notification // implements ShouldQueue
{
    use Queueable;
    public $title;
    public $description;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($title, $description)
    {
        //
        $this->title = $title;
        $this->description = $description;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [/*'mail',*/ 'database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line($this->title)
            // ->action('Notification Action', url('/'))

            ->line($this->description)
            ->line('You can login to your account to view details.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
            'title' => $this->title,
            'description' => $this->description,
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'title' => $this->title,
            'description' => $this->description,
        ]);
    }

    // public function toPushNotification($notifiable)
    // {
    //     return PusherMessagE::create()
    //         ->iOS()
    //         ->badge(1)
    //         ->sound('success')
    //         ->body("Your {$notifiable->service} account was approved!");
    // }
}
