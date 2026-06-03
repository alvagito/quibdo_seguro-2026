<?php
namespace App\Exceptions;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontFlash = ['password'];

    public function register(): void
    {
        $this->renderable(function (Throwable $e, $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                $status = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;
                return new JsonResponse([
                    'success' => false,
                    'message' => $e->getMessage(),
                    'data'    => null,
                ], $status);
            }
        });
    }
}
