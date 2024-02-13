<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    
    public function show($id)
    {
        $user = User::find($id);
        $user = $user->load(['bookings.house.user','houses']);
        return response()->json(['user' => $user], 200);
    }

    public function getOneUser($id)
    {
        $user = User::find($id);

        return response()->json([$user],200);
    }
}
