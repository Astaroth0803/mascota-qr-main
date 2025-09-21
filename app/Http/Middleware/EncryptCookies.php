<?php

namespace App\Http\Middleware;

use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

class EncryptCookies extends Middleware
{
    /**
     * Los nombres de las cookies que no se deben encriptar.
     *
     * @var array
     */
    protected $except = [
        //
    ];
}
