<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PersonalAccessToken;
use App\Models\User;

class PersonalAccessTokenController extends Controller
{
    public function index()
    {
        return PersonalAccessToken::all();
    }

    public function destroy(User $user, $deviceName)
    {
        $user = PersonalAccessToken::where([
            'tokenable_type' => 'App\Models\User',
            'tokenable_id' => $user->id,
        ])->firstOrFail();
        $userName = $user->name;
        $user->delete();

        return "User $userName of uuid $uuid deleted";
    }
}
