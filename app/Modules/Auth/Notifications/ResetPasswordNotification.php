<?php

declare(strict_types=1);

namespace App\Modules\Auth\Notifications;

use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    public function __construct(private string $token) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('auth::messages.reset_subject'))
            ->greeting(__('auth::messages.reset_greeting'))
            ->salutation(__('auth::messages.reset_salutation'))
            ->line(__('auth::messages.reset_intro'))
            ->action(__('auth::messages.reset_action'), $this->resetUrl($notifiable))
            ->line(__('auth::messages.reset_expire', ['minutes' => config('auth.passwords.users.expire')]))
            ->line(__('auth::messages.reset_outro'));
    }

    /**
     * The link points to the SPA screen that completes the change.
     */
    private function resetUrl(CanResetPassword $notifiable): string
    {
        return sprintf(
            '%s/auth/reset-password?token=%s&email=%s',
            rtrim((string) config('app.frontend_url'), '/'),
            $this->token,
            urlencode($notifiable->getEmailForPasswordReset()),
        );
    }
}
