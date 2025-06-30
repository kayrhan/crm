<?php

namespace App\Http\Controllers;

use App\SummaryOrder;
use Illuminate\Http\Request;

class SummaryOrderController extends Controller
{
    public function updateOrder(Request $req)
    {
        try {

            foreach ($req->new_order_ids as $index => $id) {

                $summaryOrder = SummaryOrder::where("user_id",$id)->first();
                $summaryOrder->order = $index + 1;
                $summaryOrder->save();
            }
            return response()->json("success");
        } catch (\Throwable $th) {
            return response()->json("error");
        }
    }
}
