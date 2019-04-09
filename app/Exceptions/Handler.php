<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;

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
     * @param  \Exception $exception
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
        /*     if($e->getStatusCode()===404)
             {
                 $response = [
                     'status'  => 'NOT FOUND',
                     'code'    => $e->getStatusCode(),
                     'message' => _('No Encontrado') . '.',
                     'content' => $e,
                 ];
             }
             else if ($e->getStatusCode()===500)
             {
                 $response = [
                     'status'  => 'FAILED',
                     'code'    => $e->getStatusCode(),
                     'message' => _('Ocurrio un error interno') . '.',
                     'content' => $e->getMessage(),
                 ];

             }

             Log::error($response);
             return response()->json($response);*/
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
//      return parent::render($request, $e);

            $response = [
                'status' => 'FAILED',
                'code' => $e->getCode(),
                'message' => __('Ocurrio un error interno') . '.',
                'content' => $e->getMessage(),
            ];
Log::error('error_image',[$e]);
        if ($e->getCode() === 404) {
            $response = [
                'status' => 'NOT FOUND',
                'code' => $e->getCode(),
                'message' => __('No Encontrado') . '.',
                'content' => $e,
            ];
        } else if ($e->getCode() === 500) {
            $response = [
                'status' => 'FAILED',
                'code' => $e->getCode(),
                'message' => __('Ocurrio un error interno') . '.',
                'content' => $e->getMessage(),
            ];

        } else if ($e->getCode() === 401) {
            $response = [
                'status' => 'UNAUTHENTICATED',
                'code' => $e->getCode(),
                'message' => __('No se ha autenticado') . '.',
                'content' => $e->getMessage(),
            ];

        } else if ($e->getCode() === 405) {
            $response = [
                'status' => 'METHOD NOT ALLOWED',
                'code' => $e->getCode(),
                'message' => __('Metodo no soportado') . '.',
                'content' => $e->getMessage(),
            ];

        }else if ($e->getCode() === 403) {
            $response = [
                'status' => 'UNAUTHORIZED',
                'code' => $e->getCode(),
                'message' => __('Sin Permisos') . '.',
                'content' => $e->getMessage(),
            ];

        }
        return response()->json($response);
    }
}
