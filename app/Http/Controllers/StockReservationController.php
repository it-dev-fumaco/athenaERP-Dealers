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
   // public function user_allowed_warehouse($user){
   //    $allowed_parent_warehouses = DB::table('tabWarehouse Access')
   //        ->where('parent', $user)->pluck('warehouse');

   //    return DB::table('tabWarehouse')
   //        ->whereIn('parent_warehouse', $allowed_parent_warehouses)->pluck('name');
   // }

   // public function create_reservation(Request $request){
   //    DB::connection('mysql')->beginTransaction();
   //    try {
   //       // restrict zero qty
   //       if($request->reserve_qty <= 0) {
   //          return response()->json(['error' => 1, 'modal_title' => 'Stock Reservation', 'modal_message' => 'Reserve Qty must be greater than 0.']);
   //       }

   //       $bin_details = DB::connection('mysql')->table('tabBin')
   //          ->where('item_code', $request->item_code)
   //          ->where('warehouse', $request->warehouse)
   //          ->first();

   //       if(!$bin_details) {
   //          return response()->json(['error' => 1, 'modal_title' => 'No Stock', 'modal_message' => 'No available stock.']);
   //       }

   //       $stock_reservation_qty = DB::table('tabStock Reservation')->where('item_code', $request->item_code)
   //          ->where('warehouse', $request->warehouse)->where('type', 'In-house')->where('status', 'Active')->sum('reserve_qty');

   //       $total_reserved_qty = $stock_reservation_qty + $bin_details->website_reserved_qty;

   //       $available_qty = $bin_details->actual_qty - $total_reserved_qty;

   //       if($available_qty < $request->reserve_qty) {
   //          return response()->json(['error' => 1, 'modal_title' => 'Insufficient Stock', 'modal_message' => 'Qty not available for <b> ' . $request->item_code . '</b> in <b>' . $request->s_warehouse . '</b><br><br>Available qty is <b>' . $available_qty . '</b>, you need <b>' . $request->reserve_qty . '</b>']);
   //       }

   //       if($request->type == 'In-house'){
   //          if(Carbon::createFromFormat('Y-m-d', $request->valid_until) <= Carbon::now()){
   //             return response()->json(['error' => 1, 'modal_title' => 'Invalid Date', 'modal_message' => 'Validity date cannot be less than or equal to date today.']);
   //          }
   //       }

   //       if($request->type == 'Consignment' && !$request->consignment_warehouse) {
   //          return response()->json(['error' => 1, 'modal_title' => 'Select Branch', 'modal_message' => 'Please select Branch.']);
   //       }

   //       $existing_stock_reservation = StockReservation::where('item_code', $request->item_code)
   //          ->where('warehouse', $request->warehouse)->where('sales_person', $request->sales_person)
   //          ->where('type', $request->type)->where('project', $request->project)->where('consignment_warehouse', $request->consignment_warehouse)
   //          ->whereIn('status', ['Active', 'Partially Issued'])->exists();
         
   //       if($existing_stock_reservation){
   //          return response()->json(['error' => 1, 'modal_title' => 'Already Exists', 'modal_message' => 'Stock Reservation already exists.']);
   //       }

   //       $latest_name = StockReservation::max('name');
	// 		$latest_name_exploded = explode("-", $latest_name);
	// 		$new_id = (!$latest_name) ? 1 : $latest_name_exploded[1] + 1;
	// 		$new_id = str_pad($new_id, 5, '0', STR_PAD_LEFT);
	// 		$new_id = 'STR-'.$new_id;

   //       $now = Carbon::now();
   //       $stock_reservation = new StockReservation;
   //       $stock_reservation->name = $new_id;
   //       $stock_reservation->creation = $now->toDateTimeString();
   //       $stock_reservation->modified = null;
   //       $stock_reservation->modified_by = null;
   //       $stock_reservation->owner = Auth::user()->wh_user;
   //       $stock_reservation->description = $request->description;
   //       $stock_reservation->notes = $request->notes;
   //       $stock_reservation->created_by = Auth::user()->full_name;
   //       $stock_reservation->stock_uom = $request->stock_uom;
   //       $stock_reservation->item_code = $request->item_code;
   //       $stock_reservation->warehouse = $request->warehouse;
   //       $stock_reservation->type = $request->type;
   //       $stock_reservation->reserve_qty = $request->reserve_qty;
   //       $stock_reservation->valid_until = ($request->type == 'In-house') ? Carbon::createFromFormat('Y-m-d', $request->valid_until) : null;
   //       $stock_reservation->sales_person = ($request->type == 'In-house') ? $request->sales_person : null;
   //       $stock_reservation->project = ($request->type == 'In-house') ? $request->project : null;
   //       $stock_reservation->consignment_warehouse = ($request->type == 'Consignment') ? $request->consignment_warehouse : null;
   //       $stock_reservation->save();

   //       if($request->type == 'Website Stocks'){
   //          if($bin_details) {
   //             $new_reserved_qty = $request->reserve_qty + $bin_details->website_reserved_qty;

   //             $values = [
   //                "modified" => Carbon::now()->toDateTimeString(),
   //                "modified_by" => Auth::user()->wh_user,
   //                "website_reserved_qty" => $new_reserved_qty,
   //             ];
      
   //             DB::connection('mysql')->table('tabBin')->where('name', $bin_details->name)->update($values);
   //          }
   //       }

   //       DB::connection('mysql')->commit();

   //       return response()->json(['error' => 0, 'modal_title' => 'Stock Reservation', 'modal_message' => 'Stock Reservation No. ' . $new_id . ' has been created.']);
   //    } catch (Exception $e) {
   //       DB::connection('mysql')->rollback();

   //       return response()->json(['error' => 1, 'modal_title' => 'Stock Reservation', 'modal_message' => 'There was a problem creating Stock Reservation.']);
   //    }
   // }

   public function get_stock_reservation(Request $request, $item_code = null){
      // $webList = StockReservation::when($item_code, function($q) use ($item_code){
      //    $q->where('item_code', $item_code)->where('type', 'Website Stocks')->orderby('creation', 'desc');
      // })->paginate(10);

      // $inhouseList = StockReservation::when($item_code, function($q) use ($item_code){
      //    $q->where('item_code', $item_code)->where('type', 'In-house')->orderby('valid_until', 'desc');
      // })->paginate(10);

      // $consignmentList = StockReservation::when($item_code, function($q) use ($item_code){
      //    $q->where('item_code', $item_code)->where('type', 'Consignment')->orderby('valid_until', 'desc');
      // })->paginate(10);

      $webList = $inhouseList = $consignmentList = [];

      return view('stock_reservation.list', compact('consignmentList', 'webList', 'inhouseList', 'item_code'));
   }

   // public function cancel_reservation(Request $request){
   //    DB::connection('mysql')->beginTransaction();
   //    try {
   //       $now = Carbon::now();
   //       $stock_reservation = StockReservation::find($request->stock_reservation_id);
   //       $stock_reservation->modified = $now->toDateTimeString();
   //       $stock_reservation->modified_by = Auth::user()->wh_user;
   //       $stock_reservation->status = 'Cancelled';
   //       $stock_reservation->save();

   //       if($stock_reservation->type == 'Website Stocks'){
   //          $bin_details = DB::connection('mysql')->table('tabBin')
   //             ->where('item_code', $stock_reservation->item_code)
   //             ->where('warehouse', $stock_reservation->warehouse)
   //             ->first();

   //          if($bin_details) {
   //             $new_reserved_qty = $bin_details->website_reserved_qty - $stock_reservation->reserve_qty;

   //             $new_reserved_qty = ($new_reserved_qty <= 0) ? 0 : $new_reserved_qty;

   //             $values = [
   //                "modified" => Carbon::now()->toDateTimeString(),
   //                "modified_by" => Auth::user()->wh_user,
   //                "website_reserved_qty" => $new_reserved_qty,
   //             ];
      
   //             DB::connection('mysql')->table('tabBin')->where('name', $bin_details->name)->update($values);
   //          }
   //       }

   //       DB::connection('mysql')->commit();

   //       return response()->json(['error' => 0, 'modal_title' => 'Stock Reservation', 'modal_message' => 'Stock Reservation No. ' . $request->stock_reservation_id . ' has been cancelled.']);
   //    } catch (Exception $e) {
   //       DB::connection('mysql')->rollback();

   //       return response()->json(['error' => 1, 'modal_title' => 'Stock Reservation', 'modal_message' => 'There was a problem cancelling Stock Reservation.']);
   //    }
   // }

   // public function get_stock_reservation_details($id){
   //    return StockReservation::find($id);
   // }

   // public function update_reservation(Request $request){
   //    DB::connection('mysql')->beginTransaction();
   //    try {
   //       if($request->reserve_qty <= 0) {
   //          return response()->json(['error' => 0, 'modal_title' => 'Stock Reservation', 'modal_message' => 'Reserve Qty must be greater than 0.']);
   //       }

   //       // get total partially issued qty
   //       $partially_issued_qty = DB::table('tabStock Reservation')->where('item_code', $request->item_code)->where('warehouse', $request->warehouse)->where('type', 'In-house')->where('status', 'Partially Issued')->where('sales_person', $request->sales_person)->sum('consumed_qty');

   //       if($partially_issued_qty > 0 && $partially_issued_qty >= $request->reserve_qty){
   //          return response()->json(['error' => 1, 'modal_title' => 'Stock Reservation', 'modal_message' => 'Reserve qty must be greater than the partially issued qty for this reservation.']);
   //       }

   //       $bin_details = DB::connection('mysql')->table('tabBin')
   //          ->where('item_code', $request->item_code)
   //          ->where('warehouse', $request->warehouse)
   //          ->first();

   //       if(!$bin_details) {
   //          return response()->json(['error' => 1, 'modal_title' => 'No Stock', 'modal_message' => 'No available stock.']);
   //       }
   //       // get total reserved qty from stock reservation table
   //       $stock_reservation_qty = DB::table('tabStock Reservation')->where('item_code', $request->item_code)
   //          ->where('warehouse', $request->warehouse)->where('type', 'In-house')->where('status', 'Active')->sum('reserve_qty');
   //       // total reserved qty = total reserved qty from stock reservation table + website reserved qty from tabbin table
   //       $total_reserved_qty = $stock_reservation_qty + $bin_details->website_reserved_qty;

   //       $now = Carbon::now();
   //       $stock_reservation = StockReservation::find($request->id);
   //       $stock_reservation->modified = $now->toDateTimeString();
   //       $stock_reservation->modified_by = Auth::user()->wh_user;
   //       $stock_reservation->notes = $request->notes;
         
   //       // calculate reserved qty
   //       $reserved_qty = ($stock_reservation->type == 'In house') ? $stock_reservation->reserve_qty : $bin_details->website_reserved_qty;
   //       $available_qty = ($request->available_qty + ($reserved_qty - $stock_reservation->consumed_qty));

   //       if($available_qty < $request->reserve_qty) {
   //          return response()->json(['error' => 1, 'modal_title' => 'Insufficient Stock', 'modal_message' => 'Qty not available for <b> ' . $request->item_code . '</b> in <b>' . $request->s_warehouse . '</b><br><br>Available qty is <b>' . $available_qty . '</b>, you need <b>' . $request->reserve_qty . '</b>']);
   //       }

   //       if($stock_reservation->type == 'Website Stocks'){
   //          $reserved_qty = abs($stock_reservation->reserve_qty - $request->reserve_qty);
            
   //          if($bin_details) {
   //             $new_reserved_qty = $bin_details->website_reserved_qty;
   //             if($stock_reservation->reserve_qty > $request->reserve_qty){
   //                $new_reserved_qty = $bin_details->website_reserved_qty - $reserved_qty;
   //             }

   //             if($stock_reservation->reserve_qty < $request->reserve_qty){
   //                $new_reserved_qty = $bin_details->website_reserved_qty + $reserved_qty;
   //             }

   //             $new_reserved_qty = ($new_reserved_qty <= 0) ? 0 : $new_reserved_qty;

   //             $values = [
   //                "modified" => Carbon::now()->toDateTimeString(),
   //                "modified_by" => Auth::user()->wh_user,
   //                "website_reserved_qty" => $new_reserved_qty,
   //             ];
      
   //             DB::connection('mysql')->table('tabBin')->where('name', $bin_details->name)->update($values);
   //          }
   //       }

   //       $stock_reservation->warehouse = $request->warehouse;
   //       $stock_reservation->reserve_qty = $request->reserve_qty;
   //       $stock_reservation->valid_until = ($stock_reservation->type == 'In-house') ? Carbon::parse($request->valid_until)->format('Y-m-d') : null;
   //       $stock_reservation->sales_person = ($stock_reservation->type == 'In-house') ? $request->sales_person : null;
   //       $stock_reservation->project = ($stock_reservation->type == 'In-house') ? $request->project : null;
   //       $stock_reservation->consignment_warehouse = ($stock_reservation->type == 'Consignment') ? $request->consignment_warehouse : null;
   //       $stock_reservation->save();

   //       DB::connection('mysql')->commit();

   //       return response()->json(['error' => 0, 'modal_title' => 'Stock Reservation', 'modal_message' => 'Stock Reservation No. ' . $request->id . ' has been updated.']);
   //    } catch (Exception $e) {
   //       DB::connection('mysql')->rollback();
   //       return response()->json(['error' => 1, 'modal_title' => 'Stock Reservation', 'modal_message' => 'There was a problem updating Stock Reservation.']);
   //    }
   // }
   
   // public function get_warehouse_with_stocks(Request $request){
   //    $user = Auth::user()->frappe_userid;
   //    $allowed_warehouses = $this->user_allowed_warehouse($user);

   //    return DB::table('tabWarehouse as w')->join('tabBin as b', 'b.warehouse', 'w.name')
   //       ->where('w.disabled', 0)->where('w.is_group', 0)
   //       ->whereIn('w.name', $allowed_warehouses)
   //       ->where('b.item_code', $request->item_code)
   //       ->when($request->q, function($q) use ($request){
   //          return $q->where('w.name', 'like', '%'.$request->q.'%');
   //       })
   //       ->select('w.name as id', 'w.name as text')
   //       ->orderBy('w.modified', 'desc')->limit(10)->get();
   // }
}