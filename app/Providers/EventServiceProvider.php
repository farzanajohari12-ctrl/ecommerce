<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use App\Models\LoginLog;
use Illuminate\Auth\Events\Failed;
use App\Notifications\LoginAlertNotification;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        \Event::listen(Login::class, function ($event) {
            $event->user->notify(new LoginAlertNotification());
            
            LoginLog::create([
                'user_id' => $event->user->id,
                'event' => 'login',
                'ip_address' => request()->ip(),
                'created_at' => now(),
            ]);
        });

        \Event::listen(Logout::class, function ($event) {
            LoginLog::create([
                'user_id' => $event->user->id,
                'event' => 'logout',
                'ip_address' => request()->ip(),
                'created_at' => now(),
            ]);
        });

        // Listen to Failed Logins
        \Event::listen(Failed::class, function ($event) {
            \App\Models\LoginLog::create([
                'user_id' => null,
                'event' => 'login',
                'email_attempted' => $event->credentials['email'] ?? null,
                'ip_address' => request()->ip(),
                'successful' => false,
                'created_at' => now(),
            ]);
        });
    }
}
