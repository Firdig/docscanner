<?php

namespace App\Http\Middleware;

use App\Services\ActivityLogger;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogAuthActivity
{
    protected ActivityLogger $activityLogger;

    public function __construct()
    {
        $this->activityLogger = new ActivityLogger();
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Log login success
        if ($request->routeIs('login') && $request->isMethod('POST') && Auth::check()) {
            $this->activityLogger->logAuth('login', Auth::id(), [
                'login_at' => now(),
                'remember' => $request->has('remember'),
            ]);
        }

        return $response;
    }
}