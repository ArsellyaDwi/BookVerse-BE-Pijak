<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\DeliveryAddress;
use App\Models\DeliveryMethod;
use Illuminate\Http\Request;

class DeliveryMethodController extends Controller
{
    public function index()
    {
        $data = DeliveryMethod::all();
        return response()->json([
            "data" => $data
        ]);
    }
}
