<?php

namespace App\Http\Controllers;

use App\Http\Resources\AccountResource;
use App\Http\Resources\UserResource;
use App\Modules\User\Models\User;
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
