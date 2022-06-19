<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Table;
use App\Models\Reservation;

class ManageReservationController extends Controller
{
    public function checkAvailability(Request $request)
    {
        $capacity = $request->capacity;
        $from = new Carbon($request->from);    
        $to = new Carbon($request->to);
        $tablesCount = Table::count();
        $compatibleTables = Table::with('reservations')->withCount('reservations')->where('capacity', '>=', $capacity)->orderBy('reservations_count')->get();
        foreach($compatibleTables as $table)
        {
            if(count($table->reservations) > 0)
            {
                if($table->reservations[count($table->reservations)-1]->to_time <= $from)
                    return response()->json(['data' => $table->id], 200);
                
                if($table->reservations[0]->from_time >= $to)
                    return response()->json(['data' => $table->id], 200);
    
                for ($i=1; $i < count($table->reservations); $i++) { 
                    $fromDate = new Carbon($table->reservations[$i]->from_time);
                    $toDate = new Carbon($table->reservations[$i-1]->to_time);
                    
                    if($fromDate->gte($to) && $toDate->lte($from))
                    {
                        return response()->json(['data' => $table->id], 200);
                    }
                }
            }
            else
            {
                return response()->json(['data' => $table->id], 200);
            }
        }
        return response()->json(['data' => false], 200);
    }

    public function reserveTable(Request $request)
    {
        $response = $this->checkAvailability($request);
        $response = json_decode($response->getContent());
        if($response->data != false)
        {
            $reservation = Reservation::create([
                'table_id' => $response->data,
                'customer_id' => $request->customer_id,
                'from_time' => $request->from,
                'to_time' => $request->to,
            ]);
            return response()->json(['data' => $reservation], 200);
        }
        else
        {
            $compatibleTable = Table::withCount('reservations')->where('capacity', '>=', $request->capacity)->orderBy('reservations_count')->first();
            $reservation = Reservation::create([
                'table_id' => $compatibleTable->id,
                'customer_id' => $request->customer_id,
                'from_time' => $request->from,
                'to_time' => $request->to,
                'waiting' => true,
            ]);
            return response()->json(['data' => $reservation], 200);
        }
        return response()->json(['data' => false], 200);
    }
}
