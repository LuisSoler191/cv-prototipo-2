<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| La SPA en Vue 3 maneja toda la navegación del lado del cliente.
| Laravel solo sirve el index.blade.php para cualquier ruta.
|--------------------------------------------------------------------------
*/

Route::get('/{any?}', function () {
    return view('app');
})->where('any', '.*');
