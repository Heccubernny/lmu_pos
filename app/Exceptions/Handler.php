<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        // Report any unhandled exception
        $this->reportable(function (Throwable $e) {
            //
        });

        // Render HTTP exceptions (404, 500, etc.) with custom views
        $this->renderable(function (NotFoundHttpException $e, Request $request) {
            if (! $request->expectsJson()) {
                return response()->view('errors.404', ['exception' => $e], 404);
            }
            return response()->json(['error' => 'Resource not found.'], 404);
        });

        $this->renderable(function (ModelNotFoundException $e, Request $request) {
            if (! $request->expectsJson()) {
                return response()->view('errors.404', ['exception' => $e], 404);
            }
            return response()->json(['error' => 'Resource not found.'], 404);
        });

        $this->renderable(function (TokenMismatchException $e, Request $request) {
            if (! $request->expectsJson()) {
                return back()->withInput()->with('error', 'Your session has expired. Please refresh the page and try again.');
            }
            return response()->json(['error' => 'CSRF token mismatch. Please refresh and try again.'], 419);
        });

        $this->renderable(function (AuthenticationException $e, Request $request) {
            if (! $request->expectsJson()) {
                return redirect()->guest(route('login'))->with('error', 'Please log in to access this page.');
            }
            return response()->json(['error' => 'Unauthenticated.'], 401);
        });

        $this->renderable(function (ValidationException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error'   => 'Validation failed.',
                    'errors'  => $e->errors(),
                ], 422);
            }
            // Let default Laravel validation handling redirect back with errors
        });

        $this->renderable(function (HttpException $e, Request $request) {
            $statusCode = $e->getStatusCode();
            if (! $request->expectsJson()) {
                // Serve custom 500 view for all 5xx errors
                if ($statusCode >= 500) {
                    return response()->view('errors.500', ['exception' => $e], $statusCode);
                }
                // Generic HTTP error view if exists, otherwise fall through
                if (view()->exists("errors.{$statusCode}")) {
                    return response()->view("errors.{$statusCode}", ['exception' => $e], $statusCode);
                }
            }
        });
    }
}
