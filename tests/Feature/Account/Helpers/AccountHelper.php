<?php

namespace Tests\Feature\Account\Helpers;

use App\Modules\Account\Models\Account;
use App\Modules\User\Models\User;

class AccountHelper
{
    public static function create(array $properties = [], bool $save = true)
    {
        $user = User::factory()->create();
        $account = Account::factory()->for($user)->make($properties);

        if ($save) {
            $account->save();
        }

        return $save ? $account : $account->refresh();
    }
}