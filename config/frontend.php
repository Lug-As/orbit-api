<?php

return [
    'url' => env('FRONTEND_URL', 'https://front.end'),
    // path to my frontend page with query param queryURL(temporarySignedRoute URL)
    'email_verify_url' => env('FRONTEND_EMAIL_VERIFY_URL', '/verify-email'),
    'password_change_url' => env('FRONTEND_PASSWORD_CHANGE_URL', '/change-password'),
];
