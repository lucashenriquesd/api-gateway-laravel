<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
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

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255|string',
            'email' => 'required|max:255|string',
            'password' => 'required|max:255|string',
        ]);
    
        $user = new User;
    
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->uuid = Str::uuid();
    
        $user->save();
        
        return $user;
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
