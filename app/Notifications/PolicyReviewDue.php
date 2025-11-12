<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Policy\Policy;

class PolicyReviewDue extends Notification implements ShouldQueue
{
    use Queueable;

    protected $policy;

    /**
     * Create a new notification instance.
     *
     * @param  \App\Models\Policy\Policy  $policy
     * @return void
     */
    public function __construct(Policy $policy)
    {
        $this->policy = $policy;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = url("/isms/policies/{$this->policy->id}");

        return (new MailMessage)
            ->subject('Policy Review Required: ' . $this->policy->title)
            ->line("The policy '{$this->policy->title}' is due for review on {$this->policy->review_date->format('Y-m-d')}.")
            ->line('As the policy owner, you are responsible for reviewing and updating this policy.')
            ->action('Review Policy', $url)
            ->line('Thank you for helping maintain our information security standards!');
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
            'policy_id' => $this->policy->id,
            'title' => $this->policy->title,
            'review_date' => $this->policy->review_date->format('Y-m-d'),
            'message' => "The policy '{$this->policy->title}' is due for review."
        ];
    }
}
