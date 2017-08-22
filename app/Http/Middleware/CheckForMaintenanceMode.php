<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Application;
use Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CheckForMaintenanceMode
{
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function handle($request, Closure $next)
    {
        if ($this->app->isDownForMaintenance() && ! $this->isIpWhiteListed()) {
            throw new HttpException(503);
        }

        return $next($request);
    }

    private function isIpWhiteListed()
    {
        $ip = Request::getClientIp();
        $allowed = explode(',', getenv('WHEN_DOWN_WHITELIST_THIS_IPS'));

        return in_array($ip, $allowed);
    }
}
