<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class DisableCsrfForPosts extends Middleware
{
    protected $except = [
        'posts',
        'posts/*',
    ];
}
