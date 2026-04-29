<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\DeliveryAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeliveryAddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = DeliveryAddress::all();
        return response()->json([
            "data" => $data
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'province' => 'required',
            'city' => 'required',
            'district' => 'required',
            'village' => 'required',
            'address' => 'nullable',
            'lat' => 'required',
            'long' => 'required',
            'is_default' => 'required',
        ]);

        $created = DeliveryAddress::create([
            'user_id' => Auth::guard('api')->user()->id,
            'province' => $data['province'],
            'city' => $data['city'],
            'district' => $data['district'],
            'village' => $data['village'],
            'address' => $data['address'],
            'lat' => $data['lat'],
            'long' => $data['long'],
            'is_default' => $data['is_default'],
        ]);

        return response()->json([
            'data' => $created,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = DeliveryAddress::findOrFail($id);
         return response()->json([
            'data' => $data,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'province' => 'required',
            'city' => 'required',
            'district' => 'required',
            'village' => 'required',
            'address' => 'nullable',
            'lat' => 'required',
            'long' => 'required',
            'is_default' => 'required',
        ]);

        $da = DeliveryAddress::findOrFail($id);

        $updated = tap($da)->update([
            'user_id' => Auth::guard('api')->user()->id,
            'province' => $data['province'],
            'city' => $data['city'],
            'district' => $data['district'],
            'village' => $data['village'],
            'address' => $data['address'],
            'lat' => $data['lat'],
            'long' => $data['long'],
            'is_default' => $data['is_default'],
        ]);

        return response()->json([
            'data' => $updated,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DeliveryAddress::destroy($id);

        return response()->json([
            'message' => 'Deleted succesfully',
        ]);
    }
}
