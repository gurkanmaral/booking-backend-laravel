<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'userId'=> 'required',
            'houseId'=> 'required',
            'start_date' => 'required',
            'end_date' =>'required'
        ]);

        $booking = new Booking([
            'user_id' => $validatedData['userId'],
            'house_id'=>$validatedData['houseId'],
            'start_date'=>$validatedData['start_date'],
            'end_date'=>$validatedData['end_date'],
        ]);

        try {
           
            $booking->save();
          
            return response()->json(['message' => 'Booking created successfully'], 201);
        } catch (\Exception $e) {
           
            return response()->json(['error' => 'Failed to create house'], 500);
        }

    }
}
