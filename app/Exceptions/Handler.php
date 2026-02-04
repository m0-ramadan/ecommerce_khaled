<?php

namespace App\Exceptions;

use Throwable;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Route;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    use ApiResponseTrait;

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->renderable(function (Throwable $e, $request) {

            if ($request->expectsJson() || $request->is('api/*')) {

                // 🔹 الحالة 1: المستخدم غير مصرح له
                if ($e instanceof AuthenticationException) {
                    return $this->errorResponse(
                        'يجب تسجيل الدخول للوصول إلى هذه الصفحة.',
                        401,
                        'UNAUTHENTICATED'
                    );
                }

                // 🔹 الحالة 2: Route غير معرف أصلًا
                if ($e instanceof RouteNotFoundException) {
                    return $this->errorResponse(
                        'المسار المطلوب غير موجود أو لم يتم تعريفه.',
                        404,
                        'ROUTE_NOT_FOUND'
                    );
                }

                // 🔹 الحالة 3: الرابط غير صحيح (404 فعلي)
                if ($e instanceof NotFoundHttpException) {
                    // نحاول نعرف هل الرابط ده Route فعلي في النظام ولا لأ
                    $path = $request->path();
                    $routeExists = collect(Route::getRoutes())->contains(function ($route) use ($path) {
                        return trim($route->uri(), '/') === trim($path, '/');
                    });

                    if ($routeExists) {
                        return $this->errorResponse(
                            'يجب تسجيل الدخول للوصول إلى هذه الصفحة.',
                            401,
                            'UNAUTHENTICATED'
                        );
                    }

                    return $this->errorResponse(
                        'الرابط المطلوب غير صحيح أو غير موجود.',
                        404,
                        'URL_NOT_FOUND'
                    );
                }

                // 🔹 الحالة 4: أي خطأ عام آخر
                return $this->errorResponse(
                    config('app.debug') ? $e->getMessage() : 'حدث خطأ غير متوقع، برجاء المحاولة لاحقًا.',
                    500,
                    class_basename($e)
                );
            }
        });

        $this->renderable(function (HttpException $e) {
            if ($e->getStatusCode() == 429) {
                return response()->json([
                    'success' => false,
                    'message' => 'تم تجاوز عدد الطلبات المسموح بها. الرجاء المحاولة لاحقاً.',
                    'error' => 'RATE_LIMIT_EXCEEDED'
                ], 429);
            }
        });

        $this->renderable(function (ValidationException $e, $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'بيانات غير صالحة',
                    'errors' => $e->errors(),
                    'error' => 'VALIDATION_ERROR'
                ], 422);
            }
        });
    }
}
