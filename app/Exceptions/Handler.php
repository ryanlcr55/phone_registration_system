<?php

namespace App\Exceptions;

use App\Http\Responses\CustomResponse;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

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

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     */
    public function render($request,  $exception)
    {
        $statusCode = CustomException::ERROR_CODE_UNKNOWN;
        if ($exception instanceof CustomException) {
            $statusCode = $exception->getCode();
        }

        return (new CustomResponse(
            null,
            $statusCode,
            config('app.debug') ? $exception->getMessage() : ''
        ))->toResponse($request);
    }

}
