<?php

namespace Tests\Feature\Rate\Helpers;

use App\Modules\Rate\Models\Rate;

class RateHelper
{
    public static function create(array $properties = [], bool $save = true)
    {
        $rate = Rate::factory()->make($properties);

        if ($save) {
            $rate->save();
        }

        return $save ? $rate : $rate->refresh();
    }
}