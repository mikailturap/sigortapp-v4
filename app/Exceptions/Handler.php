<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        // Gerekirse burada raporlanabilir geri çağrıları kaydedebilirsiniz
    }

    public function render($request, Throwable $e)
    {
        if ($e instanceof PostTooLargeException) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Yüklediğiniz veriler çok büyük. Lütfen en fazla 4 dosya ve her biri 5MB olacak şekilde yükleyin.'
                ], 413);
            }
            return back()->withErrors([
                'files' => 'Yüklenen toplam veri boyutu çok büyük. Lütfen en fazla 4 dosya ve her biri 5MB olacak şekilde yükleyin.'
            ])->withInput();
        }

        return parent::render($request, $e);
    }
}


