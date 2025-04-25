<?php

namespace App\Listeners;

use App\Events\ContactDeletedEvent;
use App\Notifications\ContactDeleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;

class SendContactDeletedNotification
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
    public function handle(ContactDeletedEvent $event): void
    {
        $user = Auth::user();
        $user->notify(new ContactDeleted( $event->contact));
    }
}
