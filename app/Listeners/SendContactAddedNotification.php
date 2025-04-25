<?php

namespace App\Listeners;

use App\Events\ContactAddedEvent;
use App\Notifications\ContactAdded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;

class SendContactAddedNotification
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
    public function handle(ContactAddedEvent $event): void
    {
        $user = Auth::user();
        $user->notify(new ContactAdded( $event->contact));

    }
}
