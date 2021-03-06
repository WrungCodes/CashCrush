<?php

namespace App\Exceptions;

use App\Exceptions\HandleError;
use App\Exceptions\HandleErrors;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

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
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
    public function render($request, Exception $exception)
    {
        Log::error($exception);

        if ($exception instanceof ValidationException) {
            return $this->invalidJson($request, $exception);
        }

        if ($exception instanceof TokenExpiredException) {
            return response()->json([
                'status' => 401,
                'message' => "Token Expired",
                'error' => $exception->getMessage(),
            ], 401);
        }

        if ($exception instanceof TokenInvalidException) {
            return response()->json([
                'status' => 401,
                'message' => "Invalid Token",
                'error' => $exception->getMessage(),
            ], 401);
        }

        if ($exception instanceof JWTException) {
            return response()->json([
                'status' => 401,
                'message' => "No Token Provided",
                'error' => $exception->getMessage(),
            ], 401);
        }

        try {
            return (new HandleError($exception))->execute();
        } catch (\Throwable $th) {
            return parent::render($request, $exception);
        }

        // if (!empty($exception->getMessage())) {
        //     return response()->json([
        //         'status' => 500,
        //         'message' => "server error",
        //         'error' => $exception->getMessage(),
        //     ], 500);
        // }

        return parent::render($request, $exception);
    }
}
