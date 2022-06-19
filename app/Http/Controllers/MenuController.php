<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Meal;

class MenuController extends Controller
{
    public function menu()
    {
        $menu = Meal::select(['id', 'price', 'description', 'discount', 'quantity_available'])->where('quantity_available', '>', 0)->get();
        return response()->json(['data' => $menu], 200);
    }
}
