<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index (Request $request) 
    {
        $user = $request->user();

        $receivedUsersIds = $user->sentChats()->pluck('receiver_id')->unique();
        $sentUsersIds = $user->receivedChats()->pluck('sender_id')->unique(); 

        $chatUserIds = $receivedUsersIds->merge($sentUsersIds)->unique();

        $chatUsers = User::whereIn('id',$chatUserIds)->get();

  

        return response()->json($chatUsers);
    }

    public function show(User $user,Request $request)
    {
        $currentUser = $request->user();
        
        $chats = Chat::where(function ($query) use ($currentUser, $user) {
            $query->where('sender_id', $currentUser->id)->where('receiver_id', $user->id);
        })->orWhere(function ($query) use ($currentUser, $user) {
            $query->where('sender_id', $user->id)->where('receiver_id', $currentUser->id);
        })->get();
    
        // Return the chats as a JSON response
        return response()->json($chats);


        
    }

    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message'=>'required|string',
        ]);

        $chat = new Chat();
        $chat->sender_id = $request->user()->id;
        $chat->receiver_id = $request->receiver_id;
        $chat->message = $request->message;
        $chat->save();


    }
}
