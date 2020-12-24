<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        return User::all();
    }

    public function show(User $user)
    {
        return $user;
    }

    public function getColumnType()
    {
        $table = 'users';
        $columns = Schema::getColumnListing($table);
        $a = [];

        foreach ($columns as $column) {
            $a[$column] = Schema::getColumnType($table, $column);
        }
        return $a;
    }

    public function requestToken(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);
    
        $user = User::where('email', $request->email)->first();
    
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user->tokens()->where([
            'tokenable_id' => $user->id,
            'name' => $request->device_name
        ])->delete();
    
        return response()->json(
            ['message' => $user->createToken($request->device_name)->plainTextToken]
        );
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255|string',
            'email' => 'required|max:255|string',
            'password' => 'required|max:255|string',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'uuid' => Str::uuid(),
        ]);

        $token = $user->createToken('login');

        $user->save();

        return ['token' => $token->plainTextToken];
    }

    public function update(Request $request, User $user)
    {
        $user->fill([
            'name' => $request->name
        ])->save();

        return $user;
    }

    public function updateUuid(Request $request, User $user)
    {
        $generatedUuid = Str::uuid();
        $user->uuid = $generatedUuid;
    
        $user->save();
        
        return $generatedUuid;
    }

    public function destroy($uuid)
    {
        $user = User::where('uuid', $uuid)->firstOrFail();
        $userName = $user->name;
        $user->delete();

        return "User $userName of uuid $uuid deleted";
    }
}
