<?php

namespace App\Listeners;

use App\Services\ActivityLogger;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Registered;

class AuthEventListener
{
    protected ActivityLogger $activityLogger;

    public function __construct()
    {
        $this->activityLogger = new ActivityLogger();
    }

    /**
     * Handle user login events.
     */
    public function handleLogin(Login $event): void
    {
        $this->activityLogger->logAuth('login', $event->user->id, [
            'guard' => $event->guard,
            'remember' => request()->has('remember'),
            'login_time' => now(),
        ]);
    }

    /**
     * Handle user logout events.
     */
    public function handleLogout(Logout $event): void
    {
        $this->activityLogger->logAuth('logout', $event->user->id, [
            'guard' => $event->guard,
            'logout_time' => now(),
        ]);
    }

    /**
     * Handle failed login attempts.
     */
    public function handleFailed(Failed $event): void
    {
        $this->activityLogger->logAuth('failed_login', null, [
            'guard' => $event->guard,
            'credentials' => $event->credentials,
            'attempt_time' => now(),
        ]);
    }

    /**
     * Handle user registration events.
     */
    public function handleRegistered(Registered $event): void
    {
        $this->activityLogger->logAuth('register', $event->user->id, [
            'registration_time' => now(),
        ]);
    }

    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe($events): array
    {
        return [
            Login::class => 'handleLogin',
            Logout::class => 'handleLogout',
            Failed::class => 'handleFailed',
            Registered::class => 'handleRegistered',
        ];
    }
}