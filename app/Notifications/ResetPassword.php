<?php


namespace App\Notifications;


use Carbon\Carbon;
use Illuminate\Auth\Notifications\ResetPassword as BaseResetPassword;
use URL;

class ResetPassword extends BaseResetPassword
{
    public function __construct($token)
    {
        parent::__construct($token);
        self::$createUrlCallback = function ($notifiable, $token) {
            return $this->resetUrl($notifiable, $token);
        };
    }

    /**
     * Get the verification URL for the given notifiable.
     *
     * @param mixed $notifiable
     * @return string
     */
    protected function resetUrl($notifiable, $token)
    {
        $temporarySignedURL = URL::temporarySignedRoute(
            'password.reset',
            Carbon::now()->addMinutes(60),
            [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ]
        );
        return $this->getFrontendUrl($temporarySignedURL);
    }

    protected function getFrontendUrl($temporarySignedURL)
    {
        $prefix = config('frontend.url') . config('frontend.password_change_url');
        if (!$prefix) {
            throw new \Exception("Frontend password change URL hasn't been set");
        }
        $params = parse_url($temporarySignedURL);
        $query = $params['query'];
        return "{$prefix}?{$query}";
    }
}
