<?php

namespace App\Services;

use App\Models\Zone;
use Carbon\Carbon;

use function PHPUnit\Framework\isNull;

class ParkingPriceService
{

    public static function calculatePrice(int $zone_id, string $startTime, string $stopTime = null): int
    {
        $start = new Carbon($startTime);
        // بجيب ال stop الل انا حاطه في الداتا بيز وانا بعمل ابديت في الستوب و احوله لكربووون
        $stop = (!is_null($stopTime)) ? new Carbon($stopTime) : now();
        $totalTimeByMinutes = abs($stop->diffInMinutes($start));
        $priceByMinutes = Zone::find($zone_id)->price_per_hour / 60;
        return ceil($totalTimeByMinutes * $priceByMinutes);

    }
}
