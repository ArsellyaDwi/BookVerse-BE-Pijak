<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\DeliveryAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class DeliveryAddressController extends Controller
{
    public function index()
    {
        $addresses = DeliveryAddress::where('user_id', Auth::id())
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $addresses
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'province' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'village' => 'required|string|max:255',
            'address' => 'required|string',
            'lat' => 'nullable|numeric',
            'long' => 'nullable|numeric',
            'is_default' => 'boolean'
        ]);

        $user = Auth::user();

        // If this address is set as default, remove default from other addresses
        if ($request->is_default) {
            DeliveryAddress::where('user_id', $user->id)->update(['is_default' => false]);
        }

        $address = DeliveryAddress::create([
            'user_id' => $user->id,
            'province' => $request->province,
            'city' => $request->city,
            'district' => $request->district,
            'village' => $request->village,
            'address' => $request->address,
            'lat' => $request->lat,
            'long' => $request->long,
            'is_default' => $request->is_default ?? false
        ]);

        // If this is the first address, make it default
        if (DeliveryAddress::where('user_id', $user->id)->count() === 1) {
            $address->update(['is_default' => true]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Address added successfully',
            'data' => $address
        ]);
    }

    public function update(Request $request, $id)
    {
        $address = DeliveryAddress::where('user_id', Auth::id())->where('id', $id)->first();

        if (!$address) {
            return response()->json([
                'success' => false,
                'message' => 'Address not found'
            ], 404);
        }

        $request->validate([
            'province' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'village' => 'required|string|max:255',
            'address' => 'required|string',
            'lat' => 'nullable|numeric',
            'long' => 'nullable|numeric',
            'is_default' => 'boolean'
        ]);

        // If this address is set as default, remove default from other addresses
        if ($request->is_default && !$address->is_default) {
            DeliveryAddress::where('user_id', Auth::id())->update(['is_default' => false]);
        }

        $address->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Address updated successfully',
            'data' => $address
        ]);
    }

    public function destroy($id)
    {
        $address = DeliveryAddress::where('user_id', Auth::id())->where('id', $id)->first();

        if (!$address) {
            return response()->json([
                'success' => false,
                'message' => 'Address not found'
            ], 404);
        }

        $wasDefault = $address->is_default;
        $address->delete();

        // If deleted address was default, set another address as default
        if ($wasDefault) {
            $newDefault = DeliveryAddress::where('user_id', Auth::id())->first();
            if ($newDefault) {
                $newDefault->update(['is_default' => true]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Address deleted successfully'
        ]);
    }

    public function setDefault($id)
    {
        $address = DeliveryAddress::where('user_id', Auth::id())->where('id', $id)->first();

        if (!$address) {
            return response()->json([
                'success' => false,
                'message' => 'Address not found'
            ], 404);
        }

        // Remove default from all addresses
        DeliveryAddress::where('user_id', Auth::id())->update(['is_default' => false]);

        // Set this address as default
        $address->update(['is_default' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Default address set successfully',
            'data' => $address
        ]);
    }
}
