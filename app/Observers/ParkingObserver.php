<?php

namespace App\Observers;

use App\Models\Parking;

class ParkingObserver
{
    public function creating(Parking $parking)
    {
        if (auth()->check()) {
            $parking->user_id = auth()->id();
        }
        $parking->start_time = now();
    }

    
    public function created(Parking $parking): void
    {
        //
    }

    /**
     * Handle the Parking "updated" event.
     */
    public function updated(Parking $parking): void
    {
        //
    }

    /**
     * Handle the Parking "deleted" event.
     */
    public function deleted(Parking $parking): void
    {
        //
    }

    /**
     * Handle the Parking "restored" event.
     */
    public function restored(Parking $parking): void
    {
        //
    }

    /**
     * Handle the Parking "force deleted" event.
     */
    public function forceDeleted(Parking $parking): void
    {
        //
    }
}
