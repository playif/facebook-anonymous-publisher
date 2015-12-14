<?php namespace App\Http\Middleware;

use Closure;

class VeryBasicAuth
{
    public function handle($request, Closure $next)
    {
        $veryBasicAuthUser = config('publisher.user');
        $veryBasicAuthPass = config('publisher.password');
        $veryBasicAuthEnvs = ['local', 'dev', 'development', 'staging', 'production', 'testing'];
        $veryBasicAuthMsg  = 'Please login.';

        if (in_array(app()->environment(), $veryBasicAuthEnvs)) {
            if ($request->getUser() != $veryBasicAuthUser || $request->getPassword() != $veryBasicAuthPass) {
                $headers = array('WWW-Authenticate' => 'Basic');
                return response($veryBasicAuthMsg, 401, $headers);
            }
        }

        return $next($request);
    }
}
