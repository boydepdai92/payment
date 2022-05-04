<?php

namespace App\Exceptions;

use App\Constant\Result;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        if ($e instanceof AuthorizationException) {
            return response()->json(['code' => Result::CODE_FORBIDDEN, 'message' => Result::getMessage(Result::CODE_FORBIDDEN)], ResponseAlias::HTTP_OK);
        } else if ($e instanceof AuthenticationException) {
            return response()->json(['code' => Result::CODE_UNAUTHORIZED, 'message' => Result::getMessage(Result::CODE_UNAUTHORIZED)], ResponseAlias::HTTP_UNAUTHORIZED);
        } else if ($e instanceof ValidationException) {
            $errors = $e->errors();
            return response()->json(['code' => Result::CODE_FAIL, 'message' => Result::getMessage(Result::CODE_FAIL), 'errors' => $errors], ResponseAlias::HTTP_BAD_REQUEST);
        } else if ($e instanceof NotFoundHttpException) {
            return response()->json(['code' => Result::CODE_NOT_FOUND, 'message' => Result::getMessage(Result::CODE_NOT_FOUND)], ResponseAlias::HTTP_NOT_FOUND);
        }

        return response()->json(['code' => Result::CODE_FAIL, 'message' => Result::getMessage(Result::CODE_FAIL)], ResponseAlias::HTTP_SERVICE_UNAVAILABLE);
    }
}
