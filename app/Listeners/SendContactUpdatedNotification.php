<?php

namespace App\Listeners;

use App\Events\ContactUpdatedEvent;
use App\Notifications\ContactUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;

class SendContactUpdatedNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ContactUpdatedEvent $event): void
    {
        $user = Auth::user();
        $user->notify(new ContactUpdated( $event->contact));
    }
}
