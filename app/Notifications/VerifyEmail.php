<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailBase;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;
use URL;

class VerifyEmail extends VerifyEmailBase
{
//    use Queueable;


    /**
     * Get the verify email notification mail message for the given URL.
     *
     * @param string $url
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    protected function buildMailMessage($url)
    {
        return (new MailMessage)
            ->subject(Lang::get('Email Address Verification') . ' - ' . config('app.name'))
            ->line(Lang::get('Please click the button below to verify your email address.'))
            ->action(Lang::get('Verify Email Address'), $url)
            ->line(Lang::get('If you did not create an account, no further action is required.'));
    }

    /**
     * Get the verification URL for the given notifiable.
     *
     * @param mixed $notifiable
     * @return string
     */
    protected function verificationUrl($notifiable)
    {
        $temporarySignedURL = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(60),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
        return $this->getFrontendUrl($temporarySignedURL);
    }

    protected function getFrontendUrl($temporarySignedURL)
    {
        $prefix = config('frontend.url') . config('frontend.email_verify_url');
        if (!$prefix) {
            throw new \Exception("Frontend URL hasn't been setted");
        }
        $params = parse_url($temporarySignedURL);
        $path = trim($params['path'], '/');
        $parts = array_slice(explode('/', $path), -2, 2);
        $id = $parts[0];
        $hash = $parts[1];
        $query = $params['query'];
        return "{$prefix}/{$id}/{$hash}?{$query}";
    }
}
