<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\StockReservation;
use DB;
use Carbon\Carbon;
use Auth;

class StockReservationController extends Controller
{
   public function get_stock_reservation(Request $request, $item_code = null){
      $webList = $inhouseList = $consignmentList = [];

      return view('stock_reservation.list', compact('consignmentList', 'webList', 'inhouseList', 'item_code'));
   }
}