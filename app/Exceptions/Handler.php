<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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
     */
    public function report(Exception $exception)
    {
        $exceptVendorReport = [
            114 => [
                'livewire/livewire/src/ComponentConcerns/HandlesActions.php',
            ]
        ];
        $allowReport = true;
        $filePath = $exception->getFile();

        if (strpos($filePath, 'vendor/') !== false) {
            if (in_array($exception->getLine(), array_keys($exceptVendorReport))) {
                $exceptionPaths = $exceptVendorReport[$exception->getLine()];

                if (count($exceptionPaths) != 0) {
                    $partialPath = explode('vendor/', $filePath);

                    if ($partialPath > 1) {
                        $partialPath = $partialPath[1];
        
                        if (in_array($partialPath, $exceptionPaths)) {
                            $allowReport = false;
                        }
                    }
                }
            }
        }

        if ($allowReport) {
            parent::report($exception);
        }
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        return parent::render($request, $exception);
    }
}
