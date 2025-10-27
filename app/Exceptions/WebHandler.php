<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Http\Response;

class WebHandler
{
    public function render($request, Throwable $e): Response
    {
        // Misal tampilkan view error custom
        return response()->view('errors.generic', [
            'message' => $e->getMessage(),
        ], 500);
    }
}
