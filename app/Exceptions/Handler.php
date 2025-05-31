<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class Handler extends ExceptionHandler
{
    protected $dontReport = [];
    protected $dontFlash = ['current_password', 'password', 'password_confirmation'];

    public function register(): void
    {
        $this->renderable(function (AuthenticationException $e, $request) {
            return response()->json([
                'message' => 'Unauthenticated.',
                'status' => 'error'
            ], 401);
        });

        $this->renderable(function (RouteNotFoundException $e, $request) {
            return response()->json([
                'message' => 'Unauthenticated.',
                'status' => 'error'
            ], 401);
        });
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->is('api/*') || $request->wantsJson()) {
            return response()->json([
                'message' => 'Unauthenticated.',
                'status' => 'error'
            ], 401);
        }

        return response()->json([
            'message' => 'Unauthenticated.',
            'status' => 'error'
        ], 401);
    }
}
