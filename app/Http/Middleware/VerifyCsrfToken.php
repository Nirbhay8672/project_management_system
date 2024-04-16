<?php
https://translate.google.co.in/?sl=en&tl=gu&op=translate
namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'http://127.0.0.1:8000/post-login',
        'http://127.0.0.1:8000/projects/add-website',
    ];
}