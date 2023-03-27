<?php

namespace App\Listeners;

use App\Events\ProductAdded;
use App\Mail\ProductAddedEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendProductAddedEmail
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
    public function handle(ProductAdded $event): void
    {
       \Mail::to(auth()->user()->email)->send(new ProductAddedEmail($event->product));
    }
}
