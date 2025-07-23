<?php

namespace App\Providers;

use App\Models\Post;
use App\Policies\PostPolicy;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        //
    }

    protected array $policies = [
        Post::class => PostPolicy::class,
    ];

    public function boot(): void
    {
        //
    }
}
