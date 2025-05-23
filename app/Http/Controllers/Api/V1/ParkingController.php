<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Parking;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\ParkingResource;
use App\Services\ParkingPriceService;

/**
 * @group Parking
 */
class ParkingController extends Controller
{
    public function start(Request $request)
    {
        $parkingData = $request->validate([
            'vehicle_id' => [
                'required',
                'integer',
                'exists:vehicles,id,deleted_at,NULL,user_id,' . auth()->id(),
            ],
            'zone_id'    => ['required', 'integer', 'exists:zones,id'],
        ]);
        //if (Parking::active()->where('vehicle_id', $request->vehicle_id)->whereNull('stop_time')->exists())
        // to allow the user to park with the same vehicle more than once only after checking out.
        if (Parking::active()->where('vehicle_id', $request->vehicle_id)->exists()) {
            return response()->json([
                'errors' => ['general' => ['Can\'t start parking twice using same vehicle. Please stop currently active parking.']],
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $parking = Parking::create($parkingData);
        $parking->load('vehicle', 'zone');
        return ParkingResource::make($parking);
    }
    public function show(Parking $parking)
    {
        return ParkingResource::make($parking);
    }


    public function stop(Parking $parking)
    {
        if ($parking->stop_time) {
            return response()->json(
                ['errors' => ['general' => ['Parking already stopped.']],],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $parking->update([
            'stop_time' => now(),
            'total_price' => ParkingPriceService::calculatePrice($parking->zone_id, $parking->start_time),
        ]);

        return ParkingResource::make($parking);
    }
}
