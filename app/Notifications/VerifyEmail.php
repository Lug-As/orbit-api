<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailBase;
use URL;

class VerifyEmail extends VerifyEmailBase
{
//    use Queueable;

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
