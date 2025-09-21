<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Indica si se debe añadir la cookie XSRF-TOKEN en la respuesta.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * Las URIs que se excluirán de la verificación CSRF.
     *
     * @var array
     */
    protected $except = [
        //
    ];
}
