<?php

namespace App\Http\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BasicAuth
{
    /**
     * Basic Авторизация.
     *
     * @param Request $request
     * @param Closure $next
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function handle(Request $request, Closure $next) : Response
    {
        $request_basic_auth = response()->make('Invalid credentials.', 401, ['WWW-Authenticate' => 'Basic']);

        if (! ($request->header('PHP_AUTH_USER') && $request->header('PHP_AUTH_PW'))) {
            return $request_basic_auth;
        }

        $username = $request->header('PHP_AUTH_USER');
        $password = $request->header('PHP_AUTH_PW');

        if (! ($username === config('auth.basic.username') && $password === config('auth.basic.password'))) {
            return $request_basic_auth;
        }

        return $next($request);
    }
}
