<?php

namespace App\Http\Controllers;

use App\Models\House;
use Illuminate\Http\Request;

class HouseController extends Controller
{

    public function index()
    {

        $houses = House::paginate(20);


        return response()->json(['houses' => $houses], 200);

    }
    public function show(House $house)
    {
        $house = $house->load(['user', 'bookings']);
        return response()->json(['house' => $house], 200);
    }

    public function store(House $house,Request $request)
    {      
        $validatedData = $request->validate([
            'userId'=>'required',
            'type' => 'required',
            'name' => 'required|min:3|max:100',
            'description' => 'required|min:3|max:2000',
            'price'=>'required|min:1|max:1000000',
            'location'=>'required|min:3|max:100',
            'image_url_1' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'image_url_2' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'image_url_3' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'image_url_4' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $imagePaths = [];

        foreach(['image_url_1', 'image_url_2', 'image_url_3', 'image_url_4'] as $image) {
            if($request->hasFile($image)) {
                $imagePath = $request->file($image)->store('images','public');
                $imagePaths[$image] = $imagePath;
            }
        }

        $house = new House([
            'user_id' => $validatedData['userId'],
            'type'=>$validatedData['type'],
            'name'=>$validatedData['name'],
            'description' => $validatedData['description'],
            'price' => $validatedData['price'],
            'location' => $validatedData['location'],
            'image_url_1' => $imagePaths['image_url_1'],
            'image_url_2' => $imagePaths['image_url_2'],
            'image_url_3' => $imagePaths['image_url_3'],
            'image_url_4' => $imagePaths['image_url_4'],
        ]);

        try {
           
            $house->save();
            
           
            return response()->json(['message' => 'House created successfully'], 201);
        } catch (\Exception $e) {
        
            return response()->json(['error' => 'Failed to create house'], 500);
        }


    }
    public function search(Request $request)
    {
        $filters = $request->only(['location', 'start_date', 'end_date','type','sort']);

        $houses = House::query()
            ->when($filters['location'], function ($query, $location) {
                return $query->where('location', 'like', '%' . $location . '%');
            })
            ->when(isset($filters['type']), function ($query) use ($filters) {
                return $query->where('type', $filters['type']);
            })
            ->whereDoesntHave('bookings', function ($query) use ($filters) {
                $query->where('end_date', '>', $filters['start_date'])
                      ->where('start_date', '<', $filters['end_date']);
            })
            ->when(isset($filters['sort']), function ($query) use ($filters) {
                return $query->orderBy('price', $filters['sort']);
            }, function ($query) {
          
                return $query->orderBy('price', 'desc');
            })
            ->paginate(10);

          return response()->json(['houses' => $houses], 200);
    }

   

}
