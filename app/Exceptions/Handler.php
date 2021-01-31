<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (NotFoundHttpException $e) {
            $prev = $e->getPrevious();
            if ($prev) {
                $modelName = str_replace('App\\Models\\', '', $prev->getModel());
                $desc = $modelName . ' not found.';
            } else {
                $desc = 'Page not found.';
            }
            $code = 404;
            return response()->json([
                'error' => [
                    'code' => $code,
                    'message' => 'Not Found',
                    'description' => $desc,
                ],
            ], $code);
        });
        $this->renderable(function (AccessDeniedHttpException $e) {
            $code = 403;
            return response()->json([
                'error' => [
                    'code' => $code,
                    'message' => 'This action is unauthorized.',
                ],
            ], $code);
        });
        $this->renderable(function (MethodNotAllowedHttpException $e) {
            $code = 405;
            $msg = $e->getMessage();
            return response()->json([
                'error' => [
                    'code' => $code,
                    'message' => $msg,
                ],
            ], $code)->withHeaders($e->getHeaders());
        });
        $this->renderable(function (AuthenticationException $e) {
            $code = 401;
            return response()->json([
                'error' => [
                    'code' => $code,
                    'message' => 'Unauthenticated.',
                ],
            ], $code);
        });
        $this->renderable(function (HttpException $e) {
            $code = $e->getStatusCode();
            return response()->json([
                'error' => [
                    'code' => $code,
                    'message' => $e->getMessage(),
                ],
            ], $code);
        });
    }
}
