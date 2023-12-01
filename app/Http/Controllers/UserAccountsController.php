<?php

namespace App\Http\Controllers;

use App\Modules\Account\Resources\AccountResource;
use App\Modules\User\Models\User;
use App\Modules\User\Resources\UserResource;
use Illuminate\Http\JsonResponse;

class UserAccountsController extends Controller
{
    public function index(User $user): JsonResponse
    {
        return response()->json([
            'user' => new UserResource($user),
            'accounts' => AccountResource::collection($user->accounts),
        ]);
    }
}
