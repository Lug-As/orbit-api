<?php

namespace App\Notifications;

use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountCreated extends Notification
{
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        /** @var \App\Models\Account $notifiable */
        return (new MailMessage)
            ->subject('Поздравляем! Аккаунт подтвержден!')
            ->greeting('Поздравляем!')
            ->line('Ваш Тик-Ток аккаунт успешно подтвержден на сайте [orbitaa.ru](' . config('frontend.url') . '). Ниже ссылка на него.')
            ->action('Ваш аккаунт', config('frontend.url') . '/accounts/' . $notifiable->id)
            ->line('Что дальше? Можете приступить к поиску рекламных проектов [здесь](https://orbitaa.ru/projects) и оставлять отклики, чтобы рекламодатели заметили Вас.');
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
            //
        ];
    }
}
