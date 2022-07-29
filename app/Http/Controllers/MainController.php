<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;
use App\StockReservation;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use GuzzleHttp\Exception\ConnectException;
use Auth;
use DB;
use Webp;
use File;
use ZipArchive;
use \Illuminate\Pagination\Paginator;

use Carbon\CarbonPeriod;

class MainController extends Controller
{
    public function getCutoffDate($transaction_date) {
        $transactionDate = Carbon::parse($transaction_date);

        $start_date = Carbon::parse($transaction_date)->subMonth();
        $end_date = Carbon::parse($transaction_date)->addMonth();

        $period = CarbonPeriod::create($start_date, '1 month' , $end_date);

        $sales_report_deadline = DB::table('tabConsignment Sales Report Deadline')
            ->select('1st_cutoff_date', '2nd_cutoff_date')->first();

        $cutoff_1 = $sales_report_deadline ? $sales_report_deadline->{'1st_cutoff_date'} : 0;
        $cutoff_2 = $sales_report_deadline ? $sales_report_deadline->{'2nd_cutoff_date'} : 0;

        $transaction_date = $transactionDate->format('d-m-Y');
        
        $cutoff_period = [];
        foreach ($period as $date) {
            $date1 = $date->day($cutoff_1);
            if ($date1 >= $start_date && $date1 <= $end_date) {
                $cutoff_period[] = $date->format('d-m-Y');
            }
            $date2 = $date->day($cutoff_2);
            if ($date2 >= $start_date && $date2 <= $end_date) {
                $cutoff_period[] = $date->format('d-m-Y');
            }
        }

        $cutoff_period[] = $transaction_date;
        // sort array with given user-defined function
        usort($cutoff_period, function ($time1, $time2) {
            return strtotime($time1) - strtotime($time2);
        });

        $transaction_date_index = array_search($transaction_date, $cutoff_period);
        // set cutoff date
        $period_from = Carbon::parse($cutoff_period[$transaction_date_index - 1])->startOfDay();
        $period_to = Carbon::parse($cutoff_period[$transaction_date_index + 1])->endOfDay();
        
        return [$period_from, $period_to];
    }

    public function index(Request $request){
        $user = Auth::user()->frappe_userid;
        if(Auth::user()->user_group == 'User'){
            return redirect('/search_results');
        }

        if(Auth::user()->user_group == 'Promodiser'){
            $assigned_consignment_store = DB::table('tabAssigned Consignment Warehouse')->where('parent', $user)->pluck('warehouse');
            
            $total_pending_inventory_audit = 0;
            if (count($assigned_consignment_store) > 0) {
                $currentDateTime = Carbon::now();

                $start_date = Carbon::now()->subMonth();
                $end_date = Carbon::now()->addMonth();

                $period = CarbonPeriod::create($start_date, '1 month' , $end_date);

                $sales_report_deadline = DB::table('tabConsignment Sales Report Deadline')
                    ->select('1st_cutoff_date', '2nd_cutoff_date')->first();

                $cutoff_1 = $sales_report_deadline ? $sales_report_deadline->{'1st_cutoff_date'} : 0;
                $cutoff_2 = $sales_report_deadline ? $sales_report_deadline->{'2nd_cutoff_date'} : 0;

                $date_now = $currentDateTime->format('d-m-Y');
                
                $cutoff_period = [];
                foreach ($period as $date) {
                    $date1 = $date->day($cutoff_1);
                    if ($date1 >= $start_date && $date1 <= $end_date) {
                        $cutoff_period[] = $date->format('d-m-Y');
                    }
                    $date2 = $date->day($cutoff_2);
                    if ($date2 >= $start_date && $date2 <= $end_date) {
                        $cutoff_period[] = $date->format('d-m-Y');
                    }
                }

                $cutoff_period[] = $date_now;
                // sort array with given user-defined function
                usort($cutoff_period, function ($time1, $time2) {
                    return strtotime($time1) - strtotime($time2);
                });

                $date_now_index = array_search($date_now, $cutoff_period);
                // set duration from and duration to
                $duration_from = $cutoff_period[$date_now_index - 1];
                $duration_to = $cutoff_period[$date_now_index + 1];

                $duration = Carbon::parse($duration_from)->addDay()->format('M d, Y') . ' - ' . Carbon::parse($duration_to)->format('M d, Y');

                $total_item_sold = DB::table('tabConsignment Sales Report as csr')
                    ->join('tabConsignment Sales Report Item as csri', 'csr.name', 'csri.parent')
                    ->where('csri.qty', '>', 0)->where('csr.status', '!=', 'Cancelled')
                    ->whereIn('csr.branch_warehouse', $assigned_consignment_store)
                    ->whereBetween('csr.transaction_date', [Carbon::parse($duration_from)->format('Y-m-d'), Carbon::parse($duration_to)->format('Y-m-d')])
                    ->groupBy('csri.item_code')->count();

                $inv_summary = DB::table('tabBin as b')
                    ->join('tabItem as i', 'i.name', 'b.item_code')
                    ->where('i.disabled', 0)->where('i.is_stock_item', 1)
                    ->whereIn('b.warehouse', $assigned_consignment_store)
                    ->where('consigned_qty', '>', 0)
                    ->select('b.warehouse', 'b.consigned_qty')
                    ->get()->toArray();

                $inv_summary = collect($inv_summary)->groupBy('warehouse');

                $inventory_summary = [];
                foreach ($inv_summary as $warehouse => $row) {
                    $inventory_summary[$warehouse] = [
                        'items_on_hand' => collect($row)->count(),
                        'total_qty' => collect($row)->sum('consigned_qty'),
                    ];
                }

                // get total pending inventory audit
                $stores_with_beginning_inventory = DB::table('tabConsignment Beginning Inventory as w')
                    ->where('status', 'Approved')
                    ->whereIn('branch_warehouse', $assigned_consignment_store)
                    ->orderBy('branch_warehouse', 'asc')
                    ->select(DB::raw('MAX(transaction_date) as transaction_date'), 'branch_warehouse')
                    ->groupBy('branch_warehouse')->pluck('transaction_date', 'branch_warehouse')
                    ->toArray();

                $inventory_audit_per_warehouse = DB::table('tabConsignment Inventory Audit Report')
                    ->whereIn('branch_warehouse', array_keys($stores_with_beginning_inventory))
                    ->select(DB::raw('MAX(transaction_date) as transaction_date'), 'branch_warehouse')
                    ->groupBy('branch_warehouse')->pluck('transaction_date', 'branch_warehouse')
                    ->toArray();

                $end = Carbon::now()->endOfDay();

                $first_cutoff = Carbon::createFromFormat('m/d/Y', $end->format('m') .'/'. $cutoff_1 .'/'. $end->format('Y'))->endOfDay();
                $second_cutoff = Carbon::createFromFormat('m/d/Y', $end->format('m') .'/'. $cutoff_2 .'/'. $end->format('Y'))->endOfDay();
    
                if ($first_cutoff->gt($end)) {
                    $end = $first_cutoff;
                }
    
                if ($second_cutoff->gt($end)) {
                    $end = $second_cutoff;
                }
    
                $cutoff_date = $this->getCutoffDate($end->endOfDay());
                $period_from = $cutoff_date[0]->addDay();
                $period_to = $cutoff_date[1];

                foreach ($assigned_consignment_store as $store) {
                    $beginning_inventory_transaction_date = array_key_exists($store, $stores_with_beginning_inventory) ? $stores_with_beginning_inventory[$store] : null;
                    $last_inventory_audit_date = array_key_exists($store, $inventory_audit_per_warehouse) ? $inventory_audit_per_warehouse[$store] : null;

                    $start = null;
                    if ($beginning_inventory_transaction_date) {
                        $start = Carbon::parse($beginning_inventory_transaction_date);
                    }

                    if ($last_inventory_audit_date) {
                        $start = Carbon::parse($last_inventory_audit_date);
                    }

                    if ($start) {
                        $last_audit_date = $start;

                        $start = $start->startOfDay();
            
                        $check = Carbon::parse($start)->between($period_from, $period_to);
                        if ($last_audit_date->endOfDay()->lt($end) && $beginning_inventory_transaction_date) {
                            if (!$check) {
                                $total_pending_inventory_audit++;
                            }
                        }
                    }
                }

                // get total stock adjustments
                $total_stock_adjustments = DB::table('tabConsignment Beginning Inventory')->whereIn('branch_warehouse', $assigned_consignment_store)->count();

                // get incoming / to receive items
                $beginning_inventory_start = DB::table('tabConsignment Beginning Inventory')->orderBy('transaction_date', 'asc')->pluck('transaction_date')->first();

                $beginning_inventory_start_date = $beginning_inventory_start ? Carbon::parse($beginning_inventory_start)->startOfDay()->format('Y-m-d') : Carbon::parse('2022-06-25')->startOfDay()->format('Y-m-d');
            
                $beginning_inventory_items = DB::table('tabConsignment Beginning Inventory as cb')
                    ->join('tabConsignment Beginning Inventory Item as cbi', 'cb.name', 'cbi.parent')
                    ->whereIn('cb.branch_warehouse', $assigned_consignment_store)->where('cb.status', '!=', 'Cancelled')
                    ->select('cb.branch_warehouse', 'cbi.item_code', 'cb.status')->get();
                $beginning_inventory_items = collect($beginning_inventory_items)->groupBy('branch_warehouse');

                $bin_items = DB::table('tabBin')->whereIn('warehouse', $assigned_consignment_store)->where('consigned_qty', 0)->select('warehouse', 'item_code', 'consigned_qty')->get();
                $bin_items = collect($bin_items)->groupBy('warehouse');

                $branches = array_keys($bin_items->toArray());

                $branches_with_pending_beginning_inventory = [];
                foreach($branches as $branch){
                    $items_with_beginning_inventory = isset($beginning_inventory_items[$branch]) ? collect($beginning_inventory_items[$branch])->pluck('item_code') : [];
                    if(isset($bin_items[$branch])){
                        $item_array = collect($bin_items[$branch])->pluck('item_code');
                        $count = 1;
                        foreach($item_array as $item_code){
                            if(!in_array($item_code, collect($items_with_beginning_inventory)->toArray())){
                                $branches_with_pending_beginning_inventory[$branch] = $count++;
                            }
                        }
                    }
                }

                return view('consignment.index_promodiser', compact('assigned_consignment_store', 'duration', 'inventory_summary', 'total_item_sold', 'total_pending_inventory_audit', 'total_stock_adjustments',  'branches_with_pending_beginning_inventory'));
            }

            return redirect('/search_results');
        }

        if(Auth::user()->user_group == 'Consignment Supervisor'){
            return $this->viewConsignmentDashboard();
        }

        return view('index');
    }

    public function getTotalStockTransfer() {
        $athenaerp_api = DB::table('api_setup')->where('type', 'athenaerp_api')->first();
        if ($athenaerp_api) {
            try {
                $assigned_consignment_store = DB::table('tabAssigned Consignment Warehouse')
                    ->where('parent', Auth::user()->frappe_userid)->pluck('warehouse');
                $headers = [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer '. $athenaerp_api->api_key,
                    'Accept-Language' => 'en',
                    'Accept' => 'application/json',
                ];

                $client = new \GuzzleHttp\Client();
                $res = $client->request('GET', $athenaerp_api->base_url.'/api/get_total_stock_transfer', [
                    'query' => ['assigned_consignment_store' => $assigned_consignment_store->toArray()],
                    'headers' => $headers,
                ]);

                if ($res->getStatusCode() == 200) {
                    $res = json_decode((string) $res->getBody());
                    $res = collect($res)->toArray();
                    
                    return number_format($res['data']);
                }
            } catch (ConnectException $e) {
                return 0;
            }
        }

        return 0;
    }

    public function getPendingToReceiveItems() {
        $delivery_report_query = [];
        $athenaerp_api = DB::table('api_setup')->where('type', 'athenaerp_api')->first();
        if ($athenaerp_api) {
            try {
                $now = Carbon::now();

                $assigned_consignment_store = DB::table('tabAssigned Consignment Warehouse')
                    ->where('parent', Auth::user()->frappe_userid)->pluck('warehouse');

                $beginning_inventory_start = DB::table('tabConsignment Beginning Inventory')->orderBy('transaction_date', 'asc')->pluck('transaction_date')->first();

                $beginning_inventory_start_date = $beginning_inventory_start ? Carbon::parse($beginning_inventory_start)->startOfDay()->format('Y-m-d') : Carbon::parse('2022-06-25')->startOfDay()->format('Y-m-d');

                $headers = [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer '. $athenaerp_api->api_key,
                    'Accept-Language' => 'en',
                    'Accept' => 'application/json',
                ];

                $client = new \GuzzleHttp\Client();
                $res = $client->request('GET', $athenaerp_api->base_url.'/api/get_pending_to_receive_items', [
                    'query' => ['assigned_consignment_store' => $assigned_consignment_store->toArray(), 'beginning_inventory_start_date' => $beginning_inventory_start_date],
                    'headers' => $headers,
                ]);

                if ($res->getStatusCode() == 200) {
                    $res = json_decode((string) $res->getBody());
                    $res = collect($res)->toArray();
                    
                    $delivery_report_query = $res['data'];
                }
            } catch (ConnectException $e) {
                $delivery_report_query = [];
            }
        }

        $delivery_report = collect($delivery_report_query)->groupBy('name');

        $item_codes = collect($delivery_report_query)->map(function ($q){
            return $q->item_code;
        });

        $source_warehouses = collect($delivery_report_query)->map(function ($q){
            return $q->s_warehouse;
        });

        $target_warehouses = collect($delivery_report_query)->map(function ($q){
            return $q->t_warehouse;
        });

        $warehouses = collect($source_warehouses)->merge($target_warehouses)->unique();

        $item_prices = DB::table('tabBin')->whereIn('warehouse', $warehouses)->whereIn('item_code', $item_codes)->select('warehouse', 'consignment_price', 'item_code')->get();
        $prices_arr = [];

        foreach($item_prices as $item){
            $prices_arr[$item->warehouse][$item->item_code] = [
                'price' => $item->consignment_price
            ];
        }

        $item_images = DB::table('tabItem Images')->whereIn('parent', $item_codes)->select('parent', 'image_path')->orderBy('idx', 'asc')->get();
        $item_image = collect($item_images)->groupBy('parent');

        $list = [];
        foreach($delivery_report as $ste => $row){
            $items_arr = [];
            foreach($row as $item){
                $ref_warehouse = $row[0]->transfer_as == 'Consignment' ? $row[0]->t_warehouse : $row[0]->s_warehouse;
                $items_arr[] = [
                    'item_code' => $item->item_code,
                    'description' => $item->description,
                    'image' => isset($item_image[$item->item_code]) ? $item_image[$item->item_code][0]->image_path : null,
                    'img_count' => isset($item_image[$item->item_code]) ? count($item_image[$item->item_code]) : 0,
                    'delivered_qty' => $item->transfer_qty,
                    'stock_uom' => $item->stock_uom,
                    'price' => isset($prices_arr[$ref_warehouse][$item->item_code]) ? $prices_arr[$ref_warehouse][$item->item_code]['price'] : 0,
                    'delivery_status' => $item->consignment_status
                ];
            }

            $status_check = collect($items_arr)->map(function($q){
                return $q['delivery_status'] ? 1 : 0; // return 1 if status is Received
            })->toArray();

            $delivery_date = Carbon::parse($row[0]->delivery_date);
          
            if($row[0]->item_status == 'Issued' && $now > $delivery_date){
                $status = 'Delivered';
            }else{
                $status = 'Pending';
            }

            $list[] = [
                'name' => $row[0]->name,
                'from' => $row[0]->from_warehouse,
                'to_consignment' => $row[0]->t_warehouse,
                'status' => $status,
                'items' => $items_arr,
                'creation' => $row[0]->creation,
                'delivery_date' => $row[0]->delivery_date,
                'delivery_status' => min($status_check) == 0 ? 0 : 1, // check if there are still items to receive
                'posting_time' => $row[0]->posting_time
            ];
        }

        return view('consignment.tbl_pending_to_receive_items', compact('list'));
    }

    public function viewConsignmentDashboard() {
        $currentDateTime = Carbon::now();

        $start_date = Carbon::now()->subMonth();
        $end_date = Carbon::now()->addMonth();

        $period = CarbonPeriod::create($start_date, '1 month' , $end_date);

        $sales_report_deadline = DB::table('tabConsignment Sales Report Deadline')->first();

        $cutoff_1 = $sales_report_deadline ? $sales_report_deadline->{'1st_cutoff_date'} : 0;
        $cutoff_2 = $sales_report_deadline ? $sales_report_deadline->{'2nd_cutoff_date'} : 0;

        $date_now = $currentDateTime->format('d-m-Y');
        
        $cutoff_period = [];
        foreach ($period as $date) {
            $date1 = $date->day($cutoff_1);
            if ($date1 >= $start_date && $date1 <= $end_date) {
                $cutoff_period[] = $date->format('d-m-Y');
            }
            $date2 = $date->day($cutoff_2);
            if ($date2 >= $start_date && $date2 <= $end_date) {
                $cutoff_period[] = $date->format('d-m-Y');
            }
        }

        $cutoff_period[] = $date_now;
        // sort array with given user-defined function
        usort($cutoff_period, function ($time1, $time2) {
            return strtotime($time1) - strtotime($time2);
        });

        $date_now_index = array_search($date_now, $cutoff_period);
        // set duration from and duration to
        $duration_from = $cutoff_period[$date_now_index - 1];
        $duration_to = $cutoff_period[$date_now_index + 1];

        $duration = Carbon::parse($duration_from)->format('M d, Y') . ' - ' . Carbon::parse($duration_to)->format('M d, Y');

        $consignment_branches = DB::table('tabWarehouse Users as wu')
            ->join('tabAssigned Consignment Warehouse as acw', 'wu.name', 'acw.parent')
            ->join('tabWarehouse as w', 'w.name', 'acw.warehouse')
            ->where('wu.user_group', 'Promodiser')
            ->select('w.warehouse_name', 'w.name', 'w.is_group', 'w.disabled')
            ->orderBy('w.warehouse_name', 'asc')->get()->toArray();

        $active_consignment_branches = collect($consignment_branches)->where('is_group', 0)->where('disabled', 0);

        $promodisers = DB::table('tabWarehouse Users')->where('user_group', 'Promodiser')->count();

        $consignment_branches_with_beginning_inventory = DB::table('tabConsignment Beginning Inventory')
            ->where('status', 'Approved')->whereIn('branch_warehouse', array_column($consignment_branches, 'name'))
            ->distinct('branch_warehouse')->pluck('branch_warehouse')->count();

        if (count($consignment_branches) > 0) {
            $beginning_inv_percentage = number_format(($consignment_branches_with_beginning_inventory / count($consignment_branches)) * 100, 2);
        } else {
            $beginning_inv_percentage = 0;
        }

        // get total stock transfer
        $athenaerp_api = DB::table('api_setup')->where('type', 'athenaerp_api')->first();
        if ($athenaerp_api) {
            try {
                $headers = [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer '. $athenaerp_api->api_key,
                    'Accept-Language' => 'en',
                    'Accept' => 'application/json',
                ];

                $client = new \GuzzleHttp\Client();
                $res = $client->request('GET', $athenaerp_api->base_url.'/api/get_total_stock_transfer', [
                    'headers' => $headers,
                ]);

                if ($res->getStatusCode() == 200) {
                    $res = json_decode((string) $res->getBody());
                    $res = collect($res)->toArray();
                    
                    $total_stock_transfers = $res['data'];
                }
            } catch (ConnectException $e) {
                $total_stock_transfers = 0;
            }
        }

        // get total stock adjustments
        $total_stock_adjustments = DB::table('tabConsignment Beginning Inventory')->count();

        $total_item_sold = DB::table('tabConsignment Sales Report as csr')
            ->join('tabConsignment Sales Report Item as csri', 'csr.name', 'csri.parent')
            ->where('csri.qty', '>', 0)->where('csr.status', '!=', 'Cancelled')
            ->whereBetween('csr.transaction_date', [Carbon::parse($duration_from)->format('Y-m-d'), Carbon::parse($duration_to)->format('Y-m-d')])
            ->groupBy('csri.item_code')->count();

        $total_pending_inventory_audit = 0;
        // get total pending inventory audit
        $stores_with_beginning_inventory = DB::table('tabConsignment Beginning Inventory as w')
            ->where('status', 'Approved')->select(DB::raw('MAX(transaction_date) as transaction_date'), 'branch_warehouse')
            ->orderBy('branch_warehouse', 'asc')->groupBy('branch_warehouse')
            ->pluck('transaction_date', 'branch_warehouse')->toArray();

        $inventory_audit_per_warehouse = DB::table('tabConsignment Inventory Audit Report')
            ->whereIn('branch_warehouse', array_keys($stores_with_beginning_inventory))
            ->select(DB::raw('MAX(transaction_date) as transaction_date'), 'branch_warehouse')
            ->groupBy('branch_warehouse')->pluck('transaction_date', 'branch_warehouse')
            ->toArray();

        $end = Carbon::now()->endOfDay();

        $first_cutoff = Carbon::createFromFormat('m/d/Y', $end->format('m') .'/'. $cutoff_1 .'/'. $end->format('Y'))->endOfDay();
        $second_cutoff = Carbon::createFromFormat('m/d/Y', $end->format('m') .'/'. $cutoff_2 .'/'. $end->format('Y'))->endOfDay();

        if ($first_cutoff->gt($end)) {
            $end = $first_cutoff;
        }

        if ($second_cutoff->gt($end)) {
            $end = $second_cutoff;
        }

        $cutoff_date = $this->getCutoffDate($end->endOfDay());
        $period_from = $cutoff_date[0]->addDay();
        $period_to = $cutoff_date[1];

        $pending = [];
        foreach (array_keys($stores_with_beginning_inventory) as $store) {
            $beginning_inventory_transaction_date = array_key_exists($store, $stores_with_beginning_inventory) ? $stores_with_beginning_inventory[$store] : null;
            $last_inventory_audit_date = array_key_exists($store, $inventory_audit_per_warehouse) ? $inventory_audit_per_warehouse[$store] : null;

            if ($beginning_inventory_transaction_date) {
                $start = Carbon::parse($beginning_inventory_transaction_date);
            }

            if ($last_inventory_audit_date) {
                $start = Carbon::parse($last_inventory_audit_date);
            }

            $last_audit_date = $start;

            $start = $start->startOfDay();

            $check = Carbon::parse($start)->between($period_from, $period_to);
            if ($last_audit_date->endOfDay()->lt($end) && $beginning_inventory_transaction_date) {
                if (!$check) {
                    $total_pending_inventory_audit++;
                }
            }
        }

        $start_date = Carbon::parse('2022-01-25')->startOfDay()->format('Y-m-d');
        $end_date = Carbon::now();

        $period = CarbonPeriod::create($start_date, '28 days' , $end_date);

        $sales_report_deadline = DB::table('tabConsignment Sales Report Deadline')->first();

        $cutoff_filters = [];
        if ($sales_report_deadline) {
            $cutoff_1 = $sales_report_deadline->{'1st_cutoff_date'};
            $cutoff_2 = $sales_report_deadline->{'2nd_cutoff_date'};
            
            $cutoff_period = [];
            foreach ($period as $date) {
                $from = $to = null;
                $date1 = $date->day($cutoff_1);
                if ($date1 >= $start_date && $date1 <= $end_date) {
                    $cutoff_period[] = $date->format('Y-m-d');
                }
                $date2 = $date->day($cutoff_2);
                if ($date2 >= $start_date && $date2 <= $end_date) {
                    $cutoff_period[] = $date->format('Y-m-d');
                }
            }
    
            $cutoff_period[] = $end_date->format('Y-m-d');;
            // sort array with given user-defined function
            usort($cutoff_period, function ($time1, $time2) {
                return strtotime($time1) - strtotime($time2);
            });
    
            foreach ($cutoff_period as $n => $cutoff_date) {
                if (array_key_exists($n + 1, $cutoff_period)) {
                    $cutoff_filters[] = [
                        'id' => $cutoff_period[$n] .'/'. $cutoff_period[$n + 1],
                        'cutoff_start' => Carbon::parse($cutoff_period[$n])->format('M. d, Y'),
                        'cutoff_end' => Carbon::parse($cutoff_period[$n + 1])->format('M. d, Y'),
                    ];
                }
            }
        }

        $sales_report_included_years = [];
        for ($i = 2022; $i <= date('Y') ; $i++) { 
            $sales_report_included_years[] = $i;
        }

        return view('consignment.index_consignment_supervisor', compact('duration', 'total_item_sold', 'beginning_inv_percentage', 'promodisers', 'active_consignment_branches', 'consignment_branches', 'consignment_branches_with_beginning_inventory', 'total_stock_transfers', 'total_pending_inventory_audit', 'total_stock_adjustments', 'cutoff_filters', 'sales_report_included_years'));
    }

    public function search_results(Request $request){

        $athenaerp_api = DB::table('api_setup')->where('type', 'athenaerp_api')->first();
        $api_connected = true;
        $list = [];
        if ($athenaerp_api) {
            try {
                $headers = [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer '. $athenaerp_api->api_key,
                    'Accept-Language' => 'en',
                    'Accept' => 'application/json',
                ];

                $client = new \GuzzleHttp\Client();
                $res = $client->request('GET', $athenaerp_api->base_url.'/api/get_search_results', [
                    'query' => [
                        'searchString' => $request->searchString,
                        'frappe_userid' => Auth::user()->frappe_userid,
                        'assigned_to_me' => $request->assigned_to_me,
                        'wh' => $request->wh,
                        'check_qty' => $request->check_qty,
                        'user_group' => Auth::user()->user_group,
                        'classification' => $request->classification,
                        'brand' => $request->brand,
                        'group' => $request->group,
                        'get_total' => $request->get_total,
                        'name' => Auth::user()->name,
                        'wh_user' => Auth::user()->wh_user,
                        'department' => Auth::user()->department,
                        'page' => $request->page,
                    ],
                    'headers' => $headers,
                ]);

                if ($res->getStatusCode() == 200) {
                    $res = (array) json_decode((string) $res->getBody());
                    
                    $list = $res['data'];
                }
            } catch (ConnectException $e) {
                $list = [];
                $api_connected = false;
            }
        }

        $item_list = $list->item_list;
        $items = $list->items;
        $itemClass = $list->itemClass;
        $all = (array) ($list->all);
        $item_groups = (array) ($list->item_groups);
        $item_group_array = (array) ($list->item_group_array);
        $breadcrumbs = $list->breadcrumbs;
        $total_items = $list->total_items;
        $root = $list->root;
        $allowed_department = $list->allowed_department;
        $user_department = $list->user_department;

        $numOfPages = $items->last_page;
        $current_page = $items->current_page;
        $has_next_page = $items->next_page_url;
        $has_previous_page = $items->prev_page_url;
        $next_page = $current_page + 1;
        $total_records = $items->total;

        return view('search_results', compact('item_list', 'items', 'itemClass', 'all', 'item_groups', 'item_group_array', 'breadcrumbs', 'total_items', 'root', 'allowed_department', 'user_department', 'numOfPages', 'current_page', 'has_next_page', 'has_previous_page', 'next_page', 'total_records'));

        $search_str = explode(' ', $request->searchString);

        $user = null;
        $assigned_consignment_store = [];
        if($request->assigned_to_me){
            $user = Auth::user()->frappe_userid;
            $assigned_consignment_store = DB::table('tabAssigned Consignment Warehouse')->where('parent', $user)->pluck('warehouse');
        }

        $select_columns = [
            'tabItem.name as item_code',
            'tabItem.description',
            'tabItem.item_group',
            'tabItem.stock_uom',
            'tabItem.custom_item_cost',
            'tabItem.item_classification',
            'tabItem.item_group_level_1 as lvl1',
            'tabItem.item_group_level_2 as lvl2',
            'tabItem.item_group_level_3 as lvl3',
            'tabItem.item_group_level_4 as lvl4',
            'tabItem.item_group_level_5 as lvl5',
            $request->wh ? 'd.default_warehouse' : null
        ];

        $select_columns = array_filter($select_columns);

        $check_qty = 1;
        if($request->has('check_qty')){
            $check_qty = $request->check_qty == 'on' ? 1 : 0;
        }

        $allow_warehouse = [];
        $is_promodiser = Auth::user()->user_group == 'Promodiser' ? 1 : 0;
        if ($is_promodiser) {
            $allowed_parent_warehouse_for_promodiser = DB::table('tabWarehouse Access as wa')
                ->join('tabWarehouse as w', 'wa.warehouse', 'w.parent_warehouse')
                ->where('wa.parent', Auth::user()->name)->where('w.is_group', 0)
                ->where('w.stock_warehouse', 1)
                ->pluck('w.name')->toArray();

            $allowed_warehouse_for_promodiser = DB::table('tabWarehouse Access as wa')
                ->join('tabWarehouse as w', 'wa.warehouse', 'w.name')
                ->where('wa.parent', Auth::user()->name)->where('w.is_group', 0)
                ->where('w.stock_warehouse', 1)
                ->pluck('w.name')->toArray();

            $consignment_stores = DB::table('tabAssigned Consignment Warehouse')->where('parent', Auth::user()->frappe_userid)->pluck('warehouse')->toArray();

            $allow_warehouse = array_merge($allowed_parent_warehouse_for_promodiser, $allowed_warehouse_for_promodiser);
            $allow_warehouse = array_merge($allow_warehouse, $consignment_stores);
        }

        $item_codes_based_on_warehouse_assigned = [];
        if(isset($request->assigned_to_me)){
            $item_codes_based_on_warehouse_assigned = DB::table('tabBin')->whereIn('warehouse', $assigned_consignment_store)->select('item_code', 'warehouse')->get();
            $item_codes_based_on_warehouse_assigned = array_keys(collect($item_codes_based_on_warehouse_assigned)->groupBy('item_code')->toArray());
        }

        $itemQ = DB::table('tabItem')->where('tabItem.disabled', 0)
            ->where('tabItem.has_variants', 0)->where('tabItem.is_stock_item', 1)
            ->when($request->searchString, function ($query) use ($search_str, $request) {
                return $query->where(function($q) use ($search_str, $request) {
                    foreach ($search_str as $str) {
                        $q->where('tabItem.description', 'LIKE', "%".$str."%");
                    }

                    $q->orWhere('tabItem.name', 'LIKE', "%".$request->searchString."%")
                        ->orWhere('tabItem.item_group', 'LIKE', "%".$request->searchString."%")
                        ->orWhere('tabItem.item_classification', 'LIKE', "%".$request->searchString."%")
                        ->orWhere('tabItem.stock_uom', 'LIKE', "%".$request->searchString."%")
                        ->orWhere(DB::raw('(SELECT GROUP_CONCAT(DISTINCT supplier_part_no SEPARATOR "; ") FROM `tabItem Supplier` WHERE parent = `tabItem`.name)'), 'LIKE', "%".$request->searchString."%");
                });
            })
            ->when($request->classification, function($q) use ($request){
                return $q->where('tabItem.item_classification', $request->classification);
            })
            ->when($request->brand, function($q) use ($request){
                return $q->where('tabItem.brand', $request->brand);
            })
            ->when($check_qty && !$is_promodiser, function($q) {
                return $q->where(DB::raw('(SELECT SUM(`tabBin`.actual_qty) FROM `tabBin` JOIN tabWarehouse ON tabWarehouse.name = `tabBin`.warehouse WHERE `tabBin`.item_code = `tabItem`.name and `tabWarehouse`.stock_warehouse = 1)'), '>', 0);
            })
            ->when($request->assigned_to_me, function($q) use ($item_codes_based_on_warehouse_assigned){
                return $q->whereIn('tabItem.name', $item_codes_based_on_warehouse_assigned);
            })
            ->when($request->wh, function($q) use ($request){
                return $q->join('tabItem Default as d', 'd.parent', 'tabItem.name')
                    ->where('d.default_warehouse', $request->wh);
            })
            ->select($select_columns);

        $itemClassQuery = Clone $itemQ;
        $itemsQuery = Clone $itemQ;
        $itemsGroupQuery = Clone $itemQ;

        $itemClass = $itemClassQuery->select('tabItem.item_classification')->distinct('tabItem.item_classification')->orderby('tabItem.item_classification','asc')->get();
        $items = $itemsQuery->orderBy('tabItem.modified', 'desc')->get();//->paginate(20);        

        $included_item_groups = [];
        if($request->group){ // Item Group Filter
            $items = $items->map(function ($q) use ($request){
                if(in_array($request->group, [$q->item_group, $q->lvl1, $q->lvl2, $q->lvl3, $q->lvl4, $q->lvl5])){
                    return $q;
                }
            });
    
            $items = $items->filter(function ($q){
                return !is_null($q);
            });
    
            $included_item_groups = $items->groupBy('item_group', 'lvl1', 'lvl2', 'lvl3', 'lvl4', 'lvl5')->toArray();
        }

        $itemGroups = $itemsGroupQuery
            ->when($request->group, function ($q) use ($included_item_groups){
                return $q->whereIn('item_group', array_keys($included_item_groups))
                    ->orWhereIn('item_group_level_1', array_keys($included_item_groups))
                    ->orWhereIn('item_group_level_2', array_keys($included_item_groups))
                    ->orWhereIn('item_group_level_3', array_keys($included_item_groups))
                    ->orWhereIn('item_group_level_4', array_keys($included_item_groups))
                    ->orWhereIn('item_group_level_5', array_keys($included_item_groups));
            })
            ->select('item_group', 'item_group_level_1', 'item_group_level_2', 'item_group_level_3', 'item_group_level_4', 'item_group_level_5')
            ->groupBy('item_group', 'item_group_level_1', 'item_group_level_2', 'item_group_level_3', 'item_group_level_4', 'item_group_level_5')
            ->get()->toArray();

        $a = array_column($itemGroups, 'item_group');
        $a1 = array_column($itemGroups, 'item_group_level_1');
        $a2 = array_column($itemGroups, 'item_group_level_2');
        $a3 = array_column($itemGroups, 'item_group_level_3');
        $a4 = array_column($itemGroups, 'item_group_level_4');
        $a5 = array_column($itemGroups, 'item_group_level_5');

        $igs = array_unique(array_merge($a, $a1, $a2, $a3, $a4, $a5));

        $breadcrumbs = [];
        if($request->group){
            $selected_group = DB::table('tabItem Group')->where('item_group_name', $request->group)->first();
            if($selected_group){
                session()->forget('breadcrumbs');
                if(!session()->has('breadcrumbs')){
                    session()->put('breadcrumbs', []);
                }

                session()->push('breadcrumbs', $request->group);
                $this->breadcrumbs($selected_group->parent_item_group);

                $breadcrumbs = array_reverse(session()->get('breadcrumbs'));
            }
        }

        $total_items = count($items);

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        // Create a new Laravel collection from the array data3
        $itemCollection = collect($items);
        // Define how many items we want to be visible in each page
        $perPage = 20;
        // Slice the collection to get the items to display in current page
        $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();
        // Create our paginator and pass it to the view
        $paginatedItems= new LengthAwarePaginator($currentPageItems , count($itemCollection), $perPage);
        // set url path for generted links
        $paginatedItems->setPath($request->url());
        $items = $paginatedItems;

        $url = $request->fullUrl();
        $items->withPath($url);

        if($request->searchString != ''){
            DB::table('tabAthena Inventory Search History')->insert([
                'name' => uniqid(),
                'creation' => Carbon::now(),
                'modified' => Carbon::now(),
                'modified_by' => Auth::user()->wh_user,
                'owner' => Auth::user()->wh_user,
                'search_string' => $request->searchString,
                'total_result' => $total_items
            ]);
        }
        
        if($request->get_total){
            return number_format($total_items);
        }

        $item_codes = array_column($items->items(), 'item_code');

        $item_inventory = DB::table('tabBin')->join('tabWarehouse', 'tabBin.warehouse', 'tabWarehouse.name')->whereIn('item_code', $item_codes)
            ->when($is_promodiser, function($q) use ($allow_warehouse) {
                return $q->whereIn('warehouse', $allow_warehouse);
            })
            ->where('stock_warehouse', 1)->where('tabWarehouse.disabled', 0)
            ->select('item_code', 'warehouse', 'location', 'actual_qty', 'tabBin.consigned_qty', 'stock_uom', 'parent_warehouse')
            ->get();

        $item_warehouses = array_column($item_inventory->toArray(), 'warehouse');

        $item_inventory = collect($item_inventory)->groupBy('item_code')->toArray();

        $stock_reservation = StockReservation::whereIn('item_code', $item_codes)
            ->whereIn('warehouse', $item_warehouses)->whereIn('status', ['Active', 'Partially Issued'])
            ->selectRaw('SUM(reserve_qty) as total_reserved_qty, SUM(consumed_qty) as total_consumed_qty, CONCAT(item_code, "-", warehouse) as item')
            ->groupBy('item_code', 'warehouse')->get();
        $stock_reservation = collect($stock_reservation)->groupBy('item')->toArray();

        $ste_total_issued = DB::table('tabStock Entry Detail')->where('docstatus', 0)->where('status', 'Issued')
            ->whereIn('item_code', $item_codes)->whereIn('s_warehouse', $item_warehouses)
            ->selectRaw('SUM(qty) as total_issued, CONCAT(item_code, "-", s_warehouse) as item')
            ->groupBy('item_code', 's_warehouse')->get();
        $ste_total_issued = collect($ste_total_issued)->groupBy('item')->toArray();

        $at_total_issued = DB::table('tabAthena Transactions as at')
            ->join('tabPacking Slip as ps', 'ps.name', 'at.reference_parent')
            ->join('tabPacking Slip Item as psi', 'ps.name', 'psi.parent')
            ->join('tabDelivery Note as dr', 'ps.delivery_note', 'dr.name')
            ->whereIn('at.reference_type', ['Packing Slip', 'Picking Slip'])
            ->where('dr.docstatus', 0)->where('ps.docstatus', '<', 2)
            ->where('psi.status', 'Issued')->whereIn('at.item_code', $item_codes)
            ->whereIn('psi.item_code', $item_codes)->whereIn('at.source_warehouse', $item_warehouses)
            ->selectRaw('SUM(at.issued_qty) as total_issued, CONCAT(at.item_code, "-", at.source_warehouse) as item')
            ->groupBy('at.item_code', 'at.source_warehouse')
            ->get();

        $at_total_issued = collect($at_total_issued)->groupBy('item')->toArray();

        $lowLevelStock = DB::table('tabItem Reorder')
            ->whereIn('parent', $item_codes)->whereIn('warehouse', $item_warehouses)
            ->selectRaw('SUM(warehouse_reorder_level) as total_warehouse_reorder_level, CONCAT(parent, "-", warehouse) as item')
            ->groupBy('parent', 'warehouse')->get();
        $lowLevelStock = collect($lowLevelStock)->groupBy('item')->toArray();

        $item_image_paths = DB::table('tabItem Images')->whereIn('parent', $item_codes)->orderBy('idx', 'asc')->get();
        $item_image_paths = collect($item_image_paths)->groupBy('parent')->toArray();

        $part_nos_query = DB::table('tabItem Supplier')->whereIn('parent', $item_codes)
            ->select('parent', DB::raw('GROUP_CONCAT(supplier_part_no) as supplier_part_nos'))->groupBy('parent')->pluck('supplier_part_nos', 'parent');

        $user_department = Auth::user()->department;
        // get departments NOT allowed to view prices
        $allowed_department = DB::table('tabDeparment with Price Access')->pluck('department')->toArray();

        $last_purchase_order = [];
        $last_landed_cost_voucher = [];
        $price_settings = [];
        $website_prices = [];
        if (in_array($user_department, $allowed_department) || in_array(Auth::user()->user_group, ['Manager', 'Director'])) {
            $last_purchase_order = DB::table('tabPurchase Order as po')->join('tabPurchase Order Item as poi', 'po.name', 'poi.parent')
                ->where('po.docstatus', 1)->whereIn('poi.item_code', $item_codes)->select('poi.base_rate', 'poi.item_code', 'po.supplier_group')->orderBy('po.creation', 'desc')->get();

            $last_landed_cost_voucher = DB::table('tabLanded Cost Voucher as a')->join('tabLanded Cost Item as b', 'a.name', 'b.parent')
                ->where('a.docstatus', 1)->whereIn('b.item_code', $item_codes)->select('a.creation', 'b.item_code', 'b.rate', 'b.valuation_rate', DB::raw('ifnull(a.posting_date, a.creation) as transaction_date'), 'a.posting_date')->orderBy('transaction_date', 'desc')->get();
            
            $last_purchase_order_rates = collect($last_purchase_order)->groupBy('item_code')->toArray();
            $last_landed_cost_voucher_rates = collect($last_landed_cost_voucher)->groupBy('item_code')->toArray();

            $website_prices = DB::table('tabItem Price')->where('price_list', 'Website Price List')->where('selling', 1)
                ->whereIn('item_code', $item_codes)->orderBy('modified', 'desc')->pluck('price_list_rate', 'item_code')->toArray();

            $price_settings = DB::table('tabSingles')->where('doctype', 'Price Settings')
                ->whereIn('field', ['minimum_price_computation', 'standard_price_computation', 'is_tax_included_in_rate'])->pluck('value', 'field')->toArray();
        }

        $minimum_price_computation = array_key_exists('minimum_price_computation', $price_settings) ? $price_settings['minimum_price_computation'] : 0;
        $standard_price_computation = array_key_exists('standard_price_computation', $price_settings) ? $price_settings['standard_price_computation'] : 0;
        $is_tax_included_in_rate = array_key_exists('is_tax_included_in_rate', $price_settings) ? $price_settings['is_tax_included_in_rate'] : 0;

        $item_list = [];
        foreach ($items as $row) {
            $item_images = [];
            if (array_key_exists($row->item_code, $item_image_paths)) {
                $item_images = $item_image_paths[$row->item_code];
            }

            $part_nos = Arr::exists($part_nos_query, $row->item_code) ? $part_nos_query[$row->item_code] : null;

            $site_warehouses = [];
            $consignment_warehouses = [];
            $item_inventory_arr = [];
            if (array_key_exists($row->item_code, $item_inventory)) {
                $item_inventory_arr = $item_inventory[$row->item_code];
            }
            foreach ($item_inventory_arr as $value) {
                $reserved_qty = 0;
                if (array_key_exists($value->item_code . '-' . $value->warehouse, $stock_reservation)) {
                    $reserved_qty = $stock_reservation[$value->item_code . '-' . $value->warehouse][0]['total_reserved_qty'];
                }

                $consumed_qty = 0;
                if (array_key_exists($value->item_code . '-' . $value->warehouse, $stock_reservation)) {
                    $consumed_qty = $stock_reservation[$value->item_code . '-' . $value->warehouse][0]['total_consumed_qty'];
                }

                $reserved_qty = $reserved_qty - $consumed_qty;

                $issued_qty = 0;
                if (array_key_exists($value->item_code . '-' . $value->warehouse, $ste_total_issued)) {
                    $issued_qty = $ste_total_issued[$value->item_code . '-' . $value->warehouse][0]->total_issued;
                }

                if (array_key_exists($value->item_code . '-' . $value->warehouse, $at_total_issued)) {
                    $issued_qty += $at_total_issued[$value->item_code . '-' . $value->warehouse][0]->total_issued;
                }

                $actual_qty = $value->actual_qty - $issued_qty;

                $warehouse_reorder_level = 0;
                if (array_key_exists($value->item_code . '-' . $value->warehouse, $lowLevelStock)) {
                    $warehouse_reorder_level = $lowLevelStock[$value->item_code . '-' . $value->warehouse][0]->total_warehouse_reorder_level;
                }

                $available_qty = ($actual_qty > $reserved_qty) ? $actual_qty - $reserved_qty : 0;
                if($value->parent_warehouse == "P2 Consignment Warehouse - FI" && !$is_promodiser) {
                    $consignment_warehouses[] = [
                        'warehouse' => $value->warehouse,
                        'location' => $value->location,
                        'reserved_qty' => $reserved_qty,
                        'actual_qty' => $value->actual_qty,
                        'available_qty' => $available_qty,
                        'consigned_qty' => $value->consigned_qty > 0 ? $value->consigned_qty : 0,
                        'stock_uom' => $value->stock_uom ? $value->stock_uom : $row->stock_uom,
                        'warehouse_reorder_level' => $warehouse_reorder_level,
                        'parent_warehouse' => $value->parent_warehouse
                    ];
                }else{
                    if(Auth::user()->user_group == 'Promodiser' && $value->parent_warehouse == "P2 Consignment Warehouse - FI"){
                        $available_qty = $value->consigned_qty > 0 ? $value->consigned_qty : 0;
                    }

                    $site_warehouses[] = [
                        'warehouse' => $value->warehouse,
                        'location' => $value->location,
                        'reserved_qty' => $reserved_qty,
                        'actual_qty' => $value->actual_qty,
                        'available_qty' => $available_qty,
                        'consigned_qty' => $value->consigned_qty > 0 ? $value->consigned_qty : 0,
                        'stock_uom' => $value->stock_uom ? $value->stock_uom : $row->stock_uom,
                        'warehouse_reorder_level' => $warehouse_reorder_level,
                        'parent_warehouse' => $value->parent_warehouse
                    ];
                }
            }

            $last_purchase_order_rates = collect($last_purchase_order)->groupBy('item_code')->toArray();
            $last_landed_cost_voucher_rates = collect($last_landed_cost_voucher)->groupBy('item_code')->toArray();

            $item_rate = 0;
            $last_purchase_order_arr = array_key_exists($row->item_code, $last_purchase_order_rates) ? $last_purchase_order_rates[$row->item_code][0] : [];
            if ($last_purchase_order_arr) {
                if ($last_purchase_order_arr->supplier_group == 'Imported') {
                    $last_landed_cost_voucher = array_key_exists($row->item_code, $last_landed_cost_voucher_rates) ? $last_landed_cost_voucher_rates[$row->item_code][0] : [];
                    
                    if ($last_landed_cost_voucher) {
                        $item_rate = $last_landed_cost_voucher->valuation_rate;
                    }
                } else {
                    $item_rate = $last_purchase_order_arr->base_rate;
                }
            }

            if ($item_rate <= 0) {
                $item_rate = $row->custom_item_cost;
            }

            $minimum_selling_price = $item_rate * $minimum_price_computation;
            $default_price = $item_rate * $standard_price_computation;
            if ($is_tax_included_in_rate) {
                $default_price = ($item_rate * $standard_price_computation) * 1.12;
            }

            $website_price = array_key_exists($row->item_code, $website_prices) ? $website_prices[$row->item_code] : 0;

            $default_price = ($website_price > 0) ? $website_price : $default_price;

            $item_list[] = [
                'name' => $row->item_code,
                'description' => $row->description,
                'item_image_paths' => $item_images,
                'part_nos' => $part_nos,
                'item_group' => $row->item_group,
                'stock_uom' => $row->stock_uom,
                'item_classification' => $row->item_classification,
                'item_inventory' => $site_warehouses,
                'consignment_warehouses' => $consignment_warehouses,
                'default_price' => $default_price,
            ];
        }

        $root = DB::table('tabItem Group')->where('parent_item_group', '')->pluck('name')->first();

        $item_group = DB::table('tabItem Group')
            ->when(array_filter($request->all()), function ($q) use ($igs, $request){
                $q->whereIn('item_group_name', $igs)
                    ->orWhere('item_group_name', 'LIKE', '%'.$request->searchString.'%');
            })
            ->select('name','parent','item_group_name','parent_item_group','is_group','old_parent', 'order_no')
            ->orderByRaw('LENGTH(order_no)', 'ASC')
            ->orderBy('order_no', 'ASC')
            ->get();

        $all = collect($item_group)->groupBy('parent_item_group');

        $item_groups = collect($item_group)->where('parent_item_group', $root)->where('is_group', 1)->groupBy('name')->toArray();
        $sub_items = array_filter($request->all()) ? collect($item_group)->where('parent_item_group', '!=', $root) : [];

        $arr = [];
        if($sub_items){
            $item_group_arr = [];
            $igs_collection = collect($item_group)->groupBy('item_group_name');
            session()->forget('igs_array');
            if(!session()->has('igs_array')){
                session()->put('igs_array', []);
            }

            foreach($sub_items as $a){
                if(!in_array($a->item_group_name, session()->get('igs_array'))){
                    session()->push('igs_array', $a->item_group_name);
                }

                $this->check_item_group_tree($a->parent_item_group, $igs_collection);
            }

            $igs_array = session()->get('igs_array');

            $arr = array_filter($igs_array);
        }

        $item_group_array = $this->item_group_tree(1, $item_groups, $all, $arr);

        return view('search_results', compact('item_list', 'items', 'itemClass', 'all', 'item_groups', 'item_group_array', 'breadcrumbs', 'total_items', 'root', 'allowed_department', 'user_department'));
    }

    public function load_suggestion_box(Request $request){
        $athenaerp_api = DB::table('api_setup')->where('type', 'athenaerp_api')->first();
        $api_connected = true;
        $list = [];
        if ($athenaerp_api) {
            try {
                $headers = [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer '. $athenaerp_api->api_key,
                    'Accept-Language' => 'en',
                    'Accept' => 'application/json',
                ];

                $client = new \GuzzleHttp\Client();
                $res = $client->request('GET', $athenaerp_api->base_url.'/api/get_item_suggestions', [
                    'query' => ['search_string' => $request->search_string],
                    'headers' => $headers,
                ]);

                if ($res->getStatusCode() == 200) {
                    $res = json_decode((string) $res->getBody());
                    $res = collect($res)->toArray();
                    
                    $list = $res['data'];
                }
            } catch (ConnectException $e) {
                $list = [];
                $api_connected = false;
            }
        }

        if (!$api_connected) {
            $search_str = explode(' ', $request->search_string);
            $list = DB::table('tabItem')
                ->where('disabled', 0)->where('is_stock_item', 1)
                ->where(function($q) use ($search_str, $request) {
                    foreach ($search_str as $str) {
                        $q->where('tabItem.description', 'LIKE', "%".$str."%");
                    }
                    $q->orWhere('tabItem.name', 'like', '%'.$request->search_string.'%')
                    ->orWhere('item_classification', 'like', '%'.$request->search_string.'%')
                    ->orWhere('item_group', 'like', '%'.$request->search_string.'%')
                    ->orWhere('stock_uom', 'like', '%'.$request->search_string.'%');
                })
                ->select('tabItem.name', 'description', 'item_image_path')
                ->orderBy('tabItem.name', 'asc')->limit(8)->get();
        }

        $item_codes = collect($list)->map(function ($q){
            return $q->name;
        });

        $image_collection = DB::table('tabItem Images')->whereIn('parent', $item_codes)->orderBy('idx', 'asc')->get();
        $image = collect($image_collection)->groupBy('parent');

        return view('suggestion_box', compact('list', 'image'));
    }

    public function get_select_filters(Request $request){
        $athenaerp_api = DB::table('api_setup')->where('type', 'athenaerp_api')->first();
        $list = [];
        if ($athenaerp_api) {
            try {
                $headers = [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer '. $athenaerp_api->api_key,
                    'Accept-Language' => 'en',
                    'Accept' => 'application/json',
                ];

                $client = new \GuzzleHttp\Client();
                $res = $client->request('GET', $athenaerp_api->base_url.'/api/get_select_filters', [
                    'query' => ['q' => $request->q],
                    'headers' => $headers,
                ]);

                if ($res->getStatusCode() == 200) {
                    $res = json_decode((string) $res->getBody());
                    $res = collect($res)->toArray();
                    
                    $list = $res['data'];

                    return response()->json([
                        'warehouses' => $list->warehouses,
                        'warehouse_users' => $list->warehouse_users,
                        'source_warehouse' => $list->source_warehouse,
                        'target_warehouse' => $list->target_warehouse,
                        'warehouse' => $list->warehouse,
                        'session_user' => $list->session_user,
                        'item_groups' => $list->item_groups,
                        'item_class_filter' => $list->item_class_filter,
                        'item_classification' => $list->item_classification,
                        'brand' => $list->brand
                    ]);
                }
            } catch (ConnectException $e) {
                return [];
            }
        }
    }

    public function get_item_details(Request $request, $item_code){
        $athenaerp_api = DB::table('api_setup')->where('type', 'athenaerp_api')->first();
        $api_connected = true;
        $list = [];
        if ($athenaerp_api) {
            try {
                $headers = [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer '. $athenaerp_api->api_key,
                    'Accept-Language' => 'en',
                    'Accept' => 'application/json',
                ];

                $client = new \GuzzleHttp\Client();
                $res = $client->request('GET', $athenaerp_api->base_url.'/api/get_item_profile_details/' . $item_code, [
                    'query' => [
                        'frappe_userid' => Auth::user()->frappe_userid,
                        'user_group' => Auth::user()->user_group,
                        'name' => Auth::user()->name,
                        'department' => Auth::user()->department,
                        'page' => $request->page,
                    ],
                    'headers' => $headers,
                ]);

                if ($res->getStatusCode() == 200) {
                    $res = (array) json_decode((string) $res->getBody());
                    
                    $list = $res['data'];

                    $is_tax_included_in_rate = $list->is_tax_included_in_rate;
                    $item_details = $list->item_details;
                    $item_attributes = collect($list->item_attributes)->toArray();
                    $site_warehouses = $list->site_warehouses;
                    $item_images = $list->item_images;
                    $item_alternatives = collect($list->item_alternatives)->toArray();
                    $consignment_warehouses = $list->consignment_warehouses;
                    $user_group = $list->user_group;
                    $minimum_selling_price = $list->minimum_selling_price;
                    $default_price = $list->default_price;
                    $attribute_names = collect($list->attribute_names)->toArray();
                    $co_variants = $list->co_variants;
                    $numOfPages = $co_variants->last_page;
                    $current_page = $co_variants->current_page;
                    $has_next_page = $co_variants->next_page_url;
                    $has_previous_page = $co_variants->prev_page_url;
                    $next_page = $current_page + 1;
                    $total_records = $co_variants->total;
                    $co_variants = $co_variants->data;
                    $attributes = collect($list->attributes)->toArray();
                    $variants_price_arr = collect($list->variants_price_arr)->toArray();
                    $item_rate = $list->item_rate;
                    $last_purchase_date = $list->last_purchase_date;
                    $allowed_department = $list->allowed_department;
                    $user_department = $list->user_department;
                    $avgPurchaseRate = $list->avgPurchaseRate;
                    $last_purchase_rate = $list->last_purchase_rate;
                    $variants_cost_arr = $list->variants_cost_arr;
                    $variants_min_price_arr = $list->variants_min_price_arr;
                    $actual_variant_stocks = collect($list->actual_variant_stocks)->toArray();
                    $item_stock_available = $list->item_stock_available;
                    $manual_rate = $list->manual_rate;
                    $manual_price_input = $list->manual_price_input;

                    return view('item_profile', compact('is_tax_included_in_rate', 'item_details', 'item_attributes', 'site_warehouses', 'item_images', 'item_alternatives', 'consignment_warehouses', 'user_group', 'minimum_selling_price', 'default_price', 'attribute_names', 'co_variants', 'attributes', 'variants_price_arr', 'item_rate', 'last_purchase_date', 'allowed_department', 'user_department', 'avgPurchaseRate', 'last_purchase_rate', 'variants_cost_arr', 'variants_min_price_arr', 'actual_variant_stocks', 'item_stock_available', 'manual_rate', 'manual_price_input', 'numOfPages', 'current_page', 'has_next_page', 'has_previous_page', 'next_page', 'total_records'));
                }
            } catch (ConnectException $e) {
                $list = [];
                $api_connected = false;
            }
        }

        // $item_details = DB::table('tabItem')->where('name', $item_code)->first();

        // if(!$item_details){
        //     abort(404);
        // }

        // if($request->json){
        //     return response()->json($item_details);
        // }

        // $allow_warehouse = [];
        // $is_promodiser = Auth::user()->user_group == 'Promodiser' ? 1 : 0;
        // if ($is_promodiser) {
        //     $allowed_parent_warehouse_for_promodiser = DB::table('tabWarehouse Access as wa')
        //         ->join('tabWarehouse as w', 'wa.warehouse', 'w.parent_warehouse')
        //         ->where('wa.parent', Auth::user()->name)->where('w.is_group', 0)
        //         ->where('w.stock_warehouse', 1)
        //         ->pluck('w.name')->toArray();

        //     $allowed_warehouse_for_promodiser = DB::table('tabWarehouse Access as wa')
        //         ->join('tabWarehouse as w', 'wa.warehouse', 'w.name')
        //         ->where('wa.parent', Auth::user()->name)->where('w.is_group', 0)
        //         ->where('w.stock_warehouse', 1)
        //         ->pluck('w.name')->toArray();

        //     $consignment_stores = DB::table('tabAssigned Consignment Warehouse')->where('parent', Auth::user()->frappe_userid)->pluck('warehouse')->toArray();

        //     $allow_warehouse = array_merge($allowed_parent_warehouse_for_promodiser, $allowed_warehouse_for_promodiser);
        //     $allow_warehouse = array_merge($allow_warehouse, $consignment_stores);
        // }

        // $user_department = Auth::user()->department;
        // $user_group = Auth::user()->user_group;
        // // get departments allowed to view prices
        // $allowed_department = DB::table('tabDeparment with Price Access')->pluck('department')->toArray();

        // $item_rate = 0;
        // $last_purchase_date = null;
        // $website_price = [];
        // $minimum_selling_price = 0;
        // $default_price = 0;
        // $avgPurchaseRate = '₱ 0.00';
        // $last_purchase_rate = 0;
        // $manual_rate = 0;
        // $is_tax_included_in_rate = 0;
        // if (in_array($user_department, $allowed_department) || in_array($user_group, ['Manager', 'Director'])) {
        //     $avgPurchaseRate = $this->avgPurchaseRate($item_code);
        //     $last_purchase_order = DB::table('tabPurchase Order as po')->join('tabPurchase Order Item as poi', 'po.name', 'poi.parent')
        //         ->where('po.docstatus', 1)->where('poi.item_code', $item_code)->select('poi.base_rate', 'po.supplier_group', 'po.creation')->orderBy('po.creation', 'desc')->first();

        //     if ($last_purchase_order) {
        //         $last_purchase_date = Carbon::parse($last_purchase_order->creation)->format('M. d, Y h:i:A');
        //         if ($last_purchase_order->supplier_group == 'Imported') {
        //             $last_landed_cost_voucher = DB::table('tabLanded Cost Voucher as a')
        //                 ->join('tabLanded Cost Item as b', 'a.name', 'b.parent')
        //                 ->where('a.docstatus', 1)->where('b.item_code', $item_code)
        //                 ->select('a.creation', 'a.name as purchase_order', 'b.item_code', 'b.valuation_rate', DB::raw('ifnull(a.posting_date, a.creation) as transaction_date'), 'a.posting_date')
        //                 ->orderBy('transaction_date', 'desc')
        //                 ->first();
    
        //             if ($last_landed_cost_voucher) {
        //                 $item_rate = $last_landed_cost_voucher->valuation_rate;
        //             }
        //         } else {
        //             $item_rate = $last_purchase_order->base_rate;
        //         }
        //     }

        //     $price_settings = DB::table('tabSingles')->where('doctype', 'Price Settings')
        //         ->whereIn('field', ['minimum_price_computation', 'standard_price_computation', 'is_tax_included_in_rate'])->pluck('value', 'field')->toArray();

        //     $minimum_price_computation = array_key_exists('minimum_price_computation', $price_settings) ? $price_settings['minimum_price_computation'] : 0;
        //     $standard_price_computation = array_key_exists('standard_price_computation', $price_settings) ? $price_settings['standard_price_computation'] : 0;
        //     $is_tax_included_in_rate = array_key_exists('is_tax_included_in_rate', $price_settings) ? $price_settings['is_tax_included_in_rate'] : 0;

        //     $last_purchase_rate = $item_rate;

        //     if ($item_rate <= 0) {
        //         $manual_rate = 1;
        //         $item_rate = $item_details->custom_item_cost;
        //     }

        //     $minimum_selling_price = $item_rate * $minimum_price_computation;
        //     $default_price = $item_rate * $standard_price_computation;

        //     if($is_tax_included_in_rate) {
        //         $default_price = ($item_rate * $standard_price_computation) * 1.12;
        //     }

        //     $website_price = DB::table('tabItem Price')
        //         ->where('price_list', 'Website Price List')->where('selling', 1)
        //         ->where('item_code', $item_code)->orderBy('modified', 'desc')
        //         ->select('price_list_rate', 'price_list')->first();

        //     $default_price = ($website_price) ? $website_price->price_list_rate : $default_price;
        // }

        // // get item inventory stock list
        // $item_inventory = DB::table('tabBin')->join('tabWarehouse', 'tabBin.warehouse', 'tabWarehouse.name')->where('item_code', $item_code)
        //     ->when($is_promodiser, function($q) use ($allow_warehouse) {
        //         return $q->whereIn('warehouse', $allow_warehouse);
        //     })
        //     ->where('stock_warehouse', 1)->where('tabWarehouse.disabled', 0)
        //     ->select('item_code', 'warehouse', 'location', 'actual_qty', 'consigned_qty', 'stock_uom', 'parent_warehouse')
        //     ->get()->toArray();

        // $stock_warehouses = array_column($item_inventory, 'warehouse');

        // $stock_reserves = [];
        // if (count($stock_warehouses) > 0) {
        //     $stock_reserves = StockReservation::where('item_code', $item_code)
        //         ->whereIn('warehouse', $stock_warehouses)->whereIn('status', ['Active', 'Partially Issued'])
        //         ->selectRaw('SUM(reserve_qty) as reserved_qty, SUM(consumed_qty) as consumed_qty, warehouse')
        //         ->groupBy('warehouse')->get();

        //     $stock_reserves = collect($stock_reserves)->groupBy('warehouse')->toArray();

        //     $ste_issued = DB::table('tabStock Entry Detail')->where('docstatus', 0)->where('status', 'Issued')
        //         ->where('item_code', $item_code)->whereIn('s_warehouse', $stock_warehouses)
        //         ->selectRaw('SUM(qty) as qty, s_warehouse')->groupBy('s_warehouse')
        //         ->pluck('qty', 's_warehouse')->toArray();

        //     $at_issued = DB::table('tabAthena Transactions as at')
        //         ->join('tabPacking Slip as ps', 'ps.name', 'at.reference_parent')
        //         ->join('tabPacking Slip Item as psi', 'ps.name', 'psi.parent')
        //         ->join('tabDelivery Note as dr', 'ps.delivery_note', 'dr.name')
        //         ->whereIn('at.reference_type', ['Packing Slip', 'Picking Slip'])
        //         ->where('dr.docstatus', 0)->where('ps.docstatus', '<', 2)
        //         ->where('psi.status', 'Issued')->where('at.item_code', $item_code)
        //         ->where('psi.item_code', $item_code)->whereIn('at.source_warehouse', $stock_warehouses)
        //         ->selectRaw('SUM(at.issued_qty) as qty, at.source_warehouse')->groupBy('at.source_warehouse')
        //         ->pluck('qty', 'source_warehouse')->toArray();
        // }

        // $site_warehouses = [];
        // $consignment_warehouses = [];
        // foreach ($item_inventory as $value) {
        //     $reserved_qty = array_key_exists($value->warehouse, $stock_reserves) ? $stock_reserves[$value->warehouse][0]['reserved_qty'] : 0;
        //     $consumed_qty = array_key_exists($value->warehouse, $stock_reserves) ? $stock_reserves[$value->warehouse][0]['consumed_qty'] : 0;
        //     $ste_issued_qty = array_key_exists($value->warehouse, $ste_issued) ? $ste_issued[$value->warehouse] : 0;
        //     $at_issued_qty = array_key_exists($value->warehouse, $at_issued) ? $at_issued[$value->warehouse] : 0;

        //     $issued_qty = $ste_issued_qty + $at_issued_qty;
        //     $reserved_qty = $reserved_qty - $consumed_qty;

        //     $actual_qty = $value->actual_qty - $issued_qty;
        //     $available_qty = ($actual_qty > $reserved_qty) ? $actual_qty - $reserved_qty : 0;
        //     if($value->parent_warehouse == "P2 Consignment Warehouse - FI" && !$is_promodiser) {
        //         $consignment_warehouses[] = [
        //             'warehouse' => $value->warehouse,
        //             'location' => $value->location,
        //             'reserved_qty' => $reserved_qty,
        //             'actual_qty' => $value->actual_qty,
        //             'available_qty' => $available_qty,
        //             'stock_uom' => $value->stock_uom,
        //         ];
        //     }else{
        //         if(Auth::user()->user_group == 'Promodiser' && $value->parent_warehouse == "P2 Consignment Warehouse - FI"){
        //             $available_qty = $value->consigned_qty > 0 ? $value->consigned_qty : 0;
        //         }

        //         $site_warehouses[] = [
        //             'warehouse' => $value->warehouse,
        //             'location' => $value->location,
        //             'reserved_qty' => $reserved_qty,
        //             'actual_qty' => $value->actual_qty,
        //             'available_qty' => $available_qty,
        //             'stock_uom' => $value->stock_uom,
        //         ];
        //     }
        // }

        // $item_stock_available = collect($consignment_warehouses)->sum('available_qty');
        // if($item_stock_available <= 0) {
        //     $item_stock_available = collect($site_warehouses)->sum('available_qty');
        // }

        // // get item images
        // $item_images = DB::table('tabItem Images')->where('parent', $item_code)->orderBy('idx', 'asc')->pluck('image_path')->toArray();
        // // get item alternatives from production order item table in erp
        // $item_alternatives = [];
        // $production_item_alternatives = DB::table('tabWork Order Item as p')->join('tabItem as i', 'p.item_alternative_for', 'i.name')
        //     ->where('p.item_code', $item_details->name)->where('p.item_alternative_for', '!=', $item_details->name)->where('i.stock_uom', $item_details->stock_uom)
        //     ->select('i.item_code', 'i.description')->orderBy('p.modified', 'desc')->get()->toArray();

        // $production_item_alternative_item_codes = array_column($production_item_alternatives, 'item_code');
        // $item_alternative_images = DB::table('tabItem Images')->whereIn('parent', $production_item_alternative_item_codes)->orderBy('idx', 'asc')->pluck('image_path')->toArray();
        // $production_item_alt_actual_stock = DB::table('tabBin')->whereIn('item_code', $production_item_alternative_item_codes)->selectRaw('SUM(actual_qty) as actual_qty, item_code')
        //     ->groupBy('item_code')->pluck('actual_qty', 'item_code')->toArray();
        // foreach($production_item_alternatives as $a){
        //     $item_alternative_image = array_key_exists($a->item_code, $item_alternative_images) ? $item_alternative_images[$a->item_code] : null;
        //     $actual_stocks = array_key_exists($a->item_code, $production_item_alt_actual_stock) ? $production_item_alt_actual_stock[$a->item_code] : 0;

        //     if(count($item_alternatives) < 7){
        //         $item_alternatives[] = [
        //             'item_code' => $a->item_code,
        //             'description' => $a->description,
        //             'item_alternative_image' => $item_alternative_image,
        //             'actual_stocks' => $actual_stocks
        //         ];
        //     }
        // }

        // $item_attributes = DB::table('tabItem Variant Attribute')->where('parent', $item_code)->orderBy('idx', 'asc')->pluck('attribute_value', 'attribute')->toArray();
        // // get item alternatives based on parent item code
        // $q = DB::table('tabItem')->where('variant_of', $item_details->variant_of)->where('name', '!=', $item_details->name)->orderBy('modified', 'desc')->get();
        // $alternative_item_codes = collect($q)->map(function($q){
        //     return $q->name;
        // });
        
        // // get actual stock qty of all item alternatives
        // $actual_stocks_query = DB::table('tabBin')->whereIn('item_code', $alternative_item_codes)->selectRaw('item_code, SUM(actual_qty) as actual_qty')->groupBy('item_code')->get();
        // $actual_stocks = collect($actual_stocks_query)->groupBy('item_code');
        
        // // get total reserved and consumed qty of all item alternatives
        // $stock_reserves_query = StockReservation::whereIn('item_code', $alternative_item_codes)->whereIn('status', ['Active', 'Partially Issued'])->selectRaw('SUM(reserve_qty) as reserved_qty, SUM(consumed_qty) as consumed_qty, item_code')->groupBy('item_code')->get();
        // $alternative_reserves = collect($stock_reserves_query)->groupBy('item_code');
        
        // // get draft issued ste of all item alternatives
        // $ste_issued_query = DB::table('tabStock Entry Detail')->where('docstatus', 0)->whereIn('item_code', $alternative_item_codes)->where('status', 'Issued')->selectRaw('SUM(qty) as qty, item_code')->groupBy('item_code')->get();
        // $alternatives_issued_ste = collect($ste_issued_query)->groupBy('item_code');
        
        // // get draft issued packing slip/drs of all item alternatives
        // $at_issued_query = DB::table('tabAthena Transactions as at')
        //     ->join('tabPacking Slip as ps', 'ps.name', 'at.reference_parent')
        //     ->join('tabPacking Slip Item as psi', 'ps.name', 'psi.parent')
        //     ->join('tabDelivery Note as dr', 'ps.delivery_note', 'dr.name')
        //     ->whereIn('at.reference_type', ['Packing Slip', 'Picking Slip'])
        //     ->where('dr.docstatus', 0)->where('ps.docstatus', '<', 2)
        //     ->where('psi.status', 'Issued')
        //     ->whereIn('at.item_code', $alternative_item_codes)
        //     ->whereRaw('psi.item_code = at.item_code')
        //     ->selectRaw('SUM(at.issued_qty) as qty, at.item_code')->groupBy('at.item_code')
        //     ->get();
        // $alternatives_issued_at = collect($at_issued_query)->groupBy('item_code');
        
        // foreach($q as $a){
        //     $item_alternative_image = DB::table('tabItem Images')->where('parent', $a->item_code)->orderBy('idx', 'asc')->first();
            
        //     $total_reserved = isset($alternative_reserves[$a->item_code]) ? $alternative_reserves[$a->item_code]->sum('reserved_qty') : 0;
        //     $total_consumed = isset($alternative_reserves[$a->item_code]) ? $alternative_reserves[$a->item_code]->sum('consumed_qty') : 0;
        
        //     $total_issued_ste = isset($alternatives_issued_ste[$a->item_code]) ? $alternatives_issued_ste[$a->item_code]->sum('qty') : 0;
        //     $total_isset_at = isset($alternatives_issued_at[$a->item_code]) ? $alternatives_issued_at[$a->item_code]->sum('qty') : 0;
            
        //     $total_issued = $total_issued_ste + $total_isset_at;
        //     $remaining_reserved = $total_reserved - $total_consumed;
        
        //     $actual_qty = isset($actual_stocks[$a->item_code]) ? $actual_stocks[$a->item_code][0]->actual_qty : 0;
        //     $available_qty = $actual_qty - ($total_issued + $remaining_reserved); // get available qty by subtracting the sum of reserved qty and draft issued picking slip/dr's to the actual qty
        //     $available_qty = $available_qty > 0 ? $available_qty : 0;
        
        //     if(count($item_alternatives) < 7){
        //         $item_alternatives[] = [
        //             'item_code' => $a->item_code,
        //             'description' => $a->description,
        //             'item_alternative_image' => ($item_alternative_image) ? $item_alternative_image->image_path : null,
        //             'actual_stocks' => $available_qty
        //         ];
        //     }
        // }
        
        // if(count($item_alternatives) <= 0) {
        //     $q = DB::table('tabItem')->where('item_classification', $item_details->item_classification)->where('name', '!=', $item_details->name)->orderBy('modified', 'desc')->get();
        //     foreach($q as $a){
        //         $item_alternative_image = DB::table('tabItem Images')->where('parent', $a->item_code)->orderBy('idx', 'asc')->first();
        
        //         $total_reserved = isset($alternative_reserves[$a->item_code]) ? $alternative_reserves[$a->item_code]->sum('reserved_qty') : 0;
        //         $total_consumed = isset($alternative_reserves[$a->item_code]) ? $alternative_reserves[$a->item_code]->sum('consumed_qty') : 0;
                
        //         $total_issued_ste = isset($alternatives_issued_ste[$a->item_code]) ? $alternatives_issued_ste[$a->item_code]->sum('qty') : 0;
        //         $total_isset_at = isset($alternatives_issued_at[$a->item_code]) ? $alternatives_issued_at[$a->item_code]->sum('qty') : 0;
                
        //         $total_issued = $total_issued_ste + $total_isset_at;
        //         $remaining_reserved = $total_reserved - $total_consumed;
        
        //         $actual_qty = isset($actual_stocks[$a->item_code]) ? $actual_stocks[$a->item_code][0]->actual_qty : 0;
        //         $available_qty = $actual_qty - ($total_issued + $remaining_reserved); // get available qty by subtracting the sum of reserved qty and draft issued picking slip/dr's to the actual qty
        //         $available_qty = $available_qty > 0 ? $available_qty : 0;
        
        //         if(count($item_alternatives) < 7){
        //             $item_alternatives[] = [
        //                 'item_code' => $a->item_code,
        //                 'description' => $a->description,
        //                 'item_alternative_image' => ($item_alternative_image) ? $item_alternative_image->image_path : null,
        //                 'actual_stocks' => $available_qty
        //             ];
        //         }
        //     }
        // }

        // $item_alternatives = collect($item_alternatives)->sortByDesc('actual_stocks')->toArray();

        // // variants
        // $co_variants = DB::table('tabItem')->where('variant_of', $item_details->variant_of)->where('name', '!=', $item_details->name)->where('disabled', 0)->select('name', 'item_name', 'custom_item_cost')->paginate(10);
        // $variant_item_codes = array_column($co_variants->items(), 'name');

        // $variants_price_arr = [];
        // $variants_cost_arr = [];
        // $variants_min_price_arr = [];
        // $actual_variant_stocks = [];
        // $manual_price_input = [];
        // if (in_array($user_department, $allowed_department) || in_array(Auth::user()->user_group, ['Manager', 'Director'])) {
        //     // get item cost for items with 0 last purchase rate
        //     $item_custom_cost = [];
        //     foreach ($co_variants->items() as $row) {
        //         $item_custom_cost[$row->name] = $row->custom_item_cost;
        //     }

        //     $variants_last_purchase_order = DB::table('tabPurchase Order as po')->join('tabPurchase Order Item as poi', 'po.name', 'poi.parent')
        //         ->where('po.docstatus', 1)->whereIn('poi.item_code', $variant_item_codes)->select('poi.base_rate', 'poi.item_code', 'po.supplier_group')->orderBy('po.creation', 'desc')->get();

        //     $variants_last_landed_cost_voucher = DB::table('tabLanded Cost Voucher as a')->join('tabLanded Cost Item as b', 'a.name', 'b.parent')
        //         ->where('a.docstatus', 1)->whereIn('b.item_code', $variant_item_codes)->select('a.creation', 'b.item_code', 'b.rate', 'b.valuation_rate', DB::raw('ifnull(a.posting_date, a.creation) as transaction_date'), 'a.posting_date')->orderBy('transaction_date', 'desc')->get();
            
        //     $variants_last_purchase_order_rates = collect($variants_last_purchase_order)->groupBy('item_code')->toArray();
        //     $variants_last_landed_cost_voucher_rates = collect($variants_last_landed_cost_voucher)->groupBy('item_code')->toArray();

        //     $variants_website_prices = DB::table('tabItem Price')->where('price_list', 'Website Price List')->where('selling', 1)
        //         ->whereIn('item_code', $variant_item_codes)->orderBy('modified', 'desc')->pluck('price_list_rate', 'item_code')->toArray();

        //     foreach($variant_item_codes as $variant){
        //         $variants_default_price = 0;
        //         $variant_rate = 0;
        //         if(array_key_exists($variant, $variants_last_purchase_order_rates)){
        //             if($variants_last_purchase_order_rates[$variant][0]->supplier_group == 'Imported'){
        //                 $variant_rate = isset($variants_last_landed_cost_voucher[$variant]) ? $variants_last_landed_cost_voucher[$variant][0]->valuation_rate : 0;
        //             }else{
        //                 $variant_rate = $variants_last_purchase_order_rates[$variant][0]->base_rate;
        //             }
        //         }

        //         $is_manual_rate = 0;
        //         // custom item cost 
        //         if ($variant_rate <= 0) {
        //             if (array_key_exists($variant, $item_custom_cost)) {
        //                 $variant_rate = $item_custom_cost[$variant];
        //                 $is_manual_rate = 1;
        //             } else {
        //                 $variant_rate = 0;
        //             }
        //         }

        //         if($is_tax_included_in_rate) {
        //             $variants_default_price = ($variant_rate * $standard_price_computation) * 1.12;
        //         }

        //         $variants_default_price = array_key_exists($variant, $variants_website_prices) ? $variants_website_prices[$variant] : $variants_default_price;
        //         $variants_price_arr[$variant] = $variants_default_price;
        //         $variants_cost_arr[$variant] = $variant_rate;
        //         $variants_min_price_arr[$variant] = $variant_rate * $minimum_price_computation;
        //         $manual_price_input[$variant] = $is_manual_rate;
        //     }
        // }

        // $actual_variant_stocks = DB::table('tabBin')->whereIn('item_code', $variant_item_codes)->selectRaw('SUM(actual_qty) as actual_qty, item_code')->groupBy('item_code')->pluck('actual_qty', 'item_code')->toArray();

        // array_push($variant_item_codes, $item_details->name);

        // $attributes_query = DB::table('tabItem Variant Attribute')->whereIn('parent', $variant_item_codes)->select('parent', 'attribute', 'attribute_value')->orderBy('idx', 'asc')->get();

        // $attribute_names = collect($attributes_query)->map(function ($q){
        //     return $q->attribute;
        // })->unique();

        // $attributes = [];
        // foreach ($attributes_query as $row) {
        //     $attributes[$row->parent][$row->attribute] = $row->attribute_value;
        // }

        // return view('item_profile', compact('is_tax_included_in_rate', 'item_details', 'item_attributes', 'site_warehouses', 'item_images', 'item_alternatives', 'consignment_warehouses', 'user_group', 'minimum_selling_price', 'default_price', 'attribute_names', 'co_variants', 'attributes', 'variants_price_arr', 'item_rate', 'last_purchase_date', 'allowed_department', 'user_department', 'avgPurchaseRate', 'last_purchase_rate', 'variants_cost_arr', 'variants_min_price_arr', 'actual_variant_stocks', 'item_stock_available', 'manual_rate', 'manual_price_input'));
    }

    public function get_athena_transactions(Request $request, $item_code){
        $user_group = Auth::user()->user_group;
        $athenaerp_api = DB::table('api_setup')->where('type', 'athenaerp_api')->first();
        $list = [];
        if ($athenaerp_api) {
            try {
                $headers = [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer '. $athenaerp_api->api_key,
                    'Accept-Language' => 'en',
                    'Accept' => 'application/json',
                ];

                $client = new \GuzzleHttp\Client();
                $res = $client->request('GET', $athenaerp_api->base_url.'/api/get_athena_transaction_history/' . $item_code, [
                    'query' => [
                        'trg_wh' => $request->trg_wh,
                        'user_group' => $user_group,
                        'src_wh' => $request->src_wh,
                        'wh_user' => Auth::user()->wh_user,
                        'page' => $request->page,
                        'ath_dates' => $request->ath_dates
                    ],
                    'headers' => $headers,
                ]);

                if ($res->getStatusCode() == 200) {
                    $res = (array) json_decode((string) $res->getBody());
                    
                    $result = $res['data'];

                    $list = $result->list;
                    $logs = $result->logs;

                    $numOfPages = $logs->last_page;
                    $current_page = $logs->current_page;
                    $has_next_page = $logs->next_page_url;
                    $has_previous_page = $logs->prev_page_url;
                    $next_page = $current_page + 1;
                    $total_records = $logs->total;
                   
                    return view('tbl_athena_transactions', compact('list', 'item_code', 'user_group', 'numOfPages', 'current_page', 'has_next_page', 'has_previous_page', 'next_page', 'total_records'));
                }
            } catch (ConnectException $e) {
                $list = [];
                $api_connected = false;
            }
        }

        // $user_group = Auth::user()->user_group;
        // $logs = DB::table('tabAthena Transactions')->where('item_code', $item_code)
        //     ->when($request->wh_user != '' and $request->wh_user != 'null', function($q) use ($request){
        //         return $q->where('warehouse_user', $request->wh_user);
        //     })
        //     ->when($request->src_wh != '' and $request->src_wh != 'null', function($q) use ($request){
        //         return $q->where('source_warehouse', $request->src_wh);
        //     })
        //     ->when($request->trg_wh != '' and $request->trg_wh != 'null', function($q) use ($request){
        //         return $q->where('target_warehouse', $request->trg_wh);
        //     })
        //     ->when($request->ath_dates != '' and $request->ath_dates != 'null', function($q) use ($request){
        //         $dates = explode(' to ', $request->ath_dates);
        //         $from = Carbon::parse($dates[0]);
        //         $to = Carbon::parse($dates[1])->endOfDay();
        //         return $q->whereBetween('transaction_date',[$from, $to]);
        //     })
        //     ->orderBy('transaction_date', 'desc')->paginate(15);

        // $ste_names = array_column($logs->items(), 'reference_parent');

        // $ste_remarks = DB::table('tabStock Entry')->whereIn('name', $ste_names)
        //     ->select('purpose', 'transfer_as', 'receive_as', 'issue_as', 'name')->get();
        
        // $ste_remarks = collect($ste_remarks)->groupBy('name')->toArray();

        // $list = [];
        // foreach($logs as $row){
        //     $ps_ref = ['Packing Slip', 'Picking Slip'];
        //     $reference_type = (in_array($row->reference_type, $ps_ref)) ? 'Packing Slip' : $row->reference_type;

        //     $existing_reference_no = DB::table('tab'.$reference_type)->where('name', $row->reference_parent)->first();
        //     if(!$existing_reference_no){
        //         $status = 'DELETED';
        //     }else{
        //         if ($existing_reference_no->docstatus == 2 or $row->docstatus == 2) {
        //             $status = 'CANCELLED';
        //         } elseif ($existing_reference_no->docstatus == 0) {
        //             $status = 'DRAFT';
        //         } else {
        //             $status = 'SUBMITTED';
        //         }
        //     }

        //     $remarks = [];
        //     $remarks = (array_key_exists($row->reference_parent, $ste_remarks)) ? $ste_remarks[$row->reference_parent] : [];
        //     if (count($remarks) > 0) {
        //         if($remarks[0]->purpose == 'Material Issue') {
        //             $remarks = $remarks[0]->issue_as;
        //         } elseif ($remarks[0]->purpose == 'Material Transfer') {
        //             $remarks = $remarks[0]->transfer_as;
        //         } elseif ($remarks[0]->purpose == 'Material Receipt') {
        //             $remarks = $remarks[0]->receive_as;
        //         } elseif ($remarks[0]->purpose == 'Material Transfer for Manufacture') {
        //             $remarks = 'Materials Withdrawal';
        //         } else {
        //             $remarks = '-';
        //         }
        //     } else {
        //         $remarks = null;
        //     }
            
        //     $list[] = [
        //         'reference_name' => $row->reference_name,
        //         'item_code' => $row->item_code,
        //         'reference_parent' => $row->reference_parent,
        //         'item_code' => $row->item_code,
        //         'source_warehouse' => $row->source_warehouse,
        //         'target_warehouse' => $row->target_warehouse,
        //         'reference_type' => $row->purpose,
        //         'issued_qty' => $row->issued_qty * 1,
        //         'reference_no' => $row->reference_no,
        //         'transaction_date' => $row->transaction_date,
        //         'warehouse_user' => $row->warehouse_user,
        //         'status' => $status,
        //         'remarks' => $remarks
        //     ];
        // }

        // return view('tbl_athena_transactions', compact('list', 'logs', 'item_code', 'user_group'));
    }

    public function get_stock_ledger(Request $request, $item_code){
        $athenaerp_api = DB::table('api_setup')->where('type', 'athenaerp_api')->first();
        $list = [];
        $user_group = Auth::user()->user_group;
        if ($athenaerp_api) {
            try {
                $headers = [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer '. $athenaerp_api->api_key,
                    'Accept-Language' => 'en',
                    'Accept' => 'application/json',
                ];

                $client = new \GuzzleHttp\Client();
                $res = $client->request('GET', $athenaerp_api->base_url.'/api/get_stock_ledger/' . $item_code, [
                    'query' => [
                        'wh_user' => Auth::user()->wh_user,
                        'erp_wh' => $request->erp_wh,
                        'erp_d' => $request->erp_d,
                        'page' => $request->page,
                    ],
                    'headers' => $headers,
                ]);

                if ($res->getStatusCode() == 200) {
                    $res = (array) json_decode((string) $res->getBody());
                    
                    $result = $res['data'];

                    $list = $result->list;
                    $logs = $result->logs;

                    $numOfPages = $logs->last_page;
                    $current_page = $logs->current_page;
                    $has_next_page = $logs->next_page_url;
                    $has_previous_page = $logs->prev_page_url;
                    $next_page = $current_page + 1;
                    $total_records = $logs->total;
                   
                    return view('tbl_stock_ledger', compact('list', 'item_code', 'user_group', 'numOfPages', 'current_page', 'has_next_page', 'has_previous_page', 'next_page', 'total_records'));
                }
            } catch (ConnectException $e) {
                $list = [];
                $api_connected = false;
            }
        }

        // $logs = DB::table('tabStock Ledger Entry as sle')->where('sle.item_code', $item_code)
        //     ->select(DB::raw('(SELECT GROUP_CONCAT(name) FROM `tabPacking Slip` where delivery_note = sle.voucher_no) as dr_voucher_no'))
        //     ->addSelect(DB::raw('
        //         (CASE
        //             WHEN (SELECT GROUP_CONCAT(purpose) FROM `tabStock Entry` where name = sle.voucher_no) IN ("Material Transfer for Manufacture", "Material Transfer", "Material Issue") THEN (SELECT date_modified FROM `tabStock Entry Detail` where parent = sle.voucher_no and item_code = sle.item_code limit 1)
        //             WHEN (SELECT GROUP_CONCAT(purpose) FROM `tabStock Entry` where name = sle.voucher_no) in ("Manufacture") THEN (SELECT modified FROM `tabStock Entry` where name = sle.voucher_no)
        //             WHEN sle.voucher_type in ("Picking Slip", "Packing Slip", "Delivery Note") THEN (SELECT psi.date_modified FROM `tabPacking Slip` as ps join `tabPacking Slip Item` as psi on ps.name = psi.parent where ps.delivery_note = sle.voucher_no and item_code = sle.item_code limit 1)
        //         ELSE
        //             sle.posting_date
        //         END) as ste_date_modified'
        //     ))
        //     ->addSelect(DB::raw('
        //         (CASE
        //             WHEN (SELECT GROUP_CONCAT(purpose) FROM `tabStock Entry` where name = sle.voucher_no) IN ("Material Transfer for Manufacture", "Material Transfer", "Material Issue") THEN (SELECT session_user FROM `tabStock Entry Detail` where parent = sle.voucher_no and item_code = sle.item_code limit 1)
        //             WHEN sle.voucher_type in ("Picking Slip", "Packing Slip", "Delivery Note") THEN (SELECT psi.session_user FROM `tabPacking Slip` as ps join `tabPacking Slip Item` as psi on ps.name = psi.parent where ps.delivery_note = sle.voucher_no and item_code = sle.item_code limit 1)
        //         END) as ste_session_user'
        //     ))
        //     ->addSelect(DB::raw('(SELECT GROUP_CONCAT(purpose) FROM `tabStock Entry` where name = sle.voucher_no) as ste_purpose'))
        //     ->addSelect(DB::raw('(SELECT GROUP_CONCAT(sales_order_no) FROM `tabStock Entry` where name = sle.voucher_no) as ste_sales_order'))
        //     ->addSelect(DB::raw('(SELECT GROUP_CONCAT(DISTINCT purchase_order) FROM `tabPurchase Receipt Item` where parent = sle.voucher_no and item_code = sle.item_code) as pr_voucher_no'))
        //     ->addSelect('sle.voucher_type', 'sle.voucher_no', 'sle.warehouse', 'sle.actual_qty', 'sle.qty_after_transaction', 'sle.posting_date')
        //     ->when($request->wh_user != '' and $request->wh_user != 'null', function($q) use ($request){
		// 		return $q->where(DB::raw('
        //             (CASE
        //                 WHEN (SELECT GROUP_CONCAT(purpose) FROM `tabStock Entry` where name = sle.voucher_no) IN ("Material Transfer for Manufacture", "Material Transfer", "Material Issue") THEN (SELECT session_user FROM `tabStock Entry Detail` where parent = sle.voucher_no and item_code = sle.item_code limit 1)
        //                 WHEN sle.voucher_type in ("Picking Slip", "Packing Slip", "Delivery Note") THEN (SELECT psi.session_user FROM `tabPacking Slip` as ps join `tabPacking Slip Item` as psi on ps.name = psi.parent where ps.delivery_note = sle.voucher_no and item_code = sle.item_code limit 1)
        //             END)'
        //         ), $request->wh_user);
        //     })
        //     ->when($request->erp_wh != '' and $request->erp_wh != 'null', function($q) use ($request){
		// 		return $q->where('sle.warehouse', $request->erp_wh);
        //     })
        //     ->when($request->erp_d != '' and $request->erp_d != 'null', function($q) use ($request){
        //         $dates = explode(' to ', $request->erp_d);

		// 		return $q->whereBetween(DB::raw('
        //             (CASE
        //                 WHEN (SELECT GROUP_CONCAT(purpose) FROM `tabStock Entry` where name = sle.voucher_no) IN ("Material Transfer for Manufacture", "Material Transfer", "Material Issue") THEN (SELECT date_modified FROM `tabStock Entry Detail` where parent = sle.voucher_no and item_code = sle.item_code limit 1)
        //                 WHEN (SELECT GROUP_CONCAT(purpose) FROM `tabStock Entry` where name = sle.voucher_no) in ("Manufacture") THEN (SELECT modified FROM `tabStock Entry` where name = sle.voucher_no)
        //                 WHEN sle.voucher_type in ("Picking Slip", "Packing Slip", "Delivery Note") THEN (SELECT psi.date_modified FROM `tabPacking Slip` as ps join `tabPacking Slip Item` as psi on ps.name = psi.parent where ps.delivery_note = sle.voucher_no and item_code = sle.item_code limit 1)
        //             ELSE
        //                 sle.posting_date
        //             END)'
        //         ), [$dates[0], $dates[1]]);
        //     })
        //     ->orderBy('sle.posting_date', 'desc')->orderBy('sle.posting_time', 'desc')
        //     ->orderBy('sle.name', 'desc')->paginate(20);
            
        // $list = [];
        // foreach($logs as $row){
        //     if($row->voucher_type == 'Delivery Note'){
        //         $voucher_no = $row->dr_voucher_no;
        //         $transaction = 'Picking Slip';
        //     }elseif($row->voucher_type == 'Purchase Receipt'){
        //         $transaction = $row->voucher_type;
        //     }elseif($row->voucher_type == 'Stock Reconciliation'){
        //         $transaction = $row->voucher_type;
        //         $voucher_no = $row->voucher_no;
        //     }else{
        //         $transaction = $row->ste_purpose;
        //         $voucher_no = $row->voucher_no;
        //     }

        //     if($row->voucher_type == 'Delivery Note'){
        //         $ref_no = $voucher_no;
        //     }elseif($row->voucher_type == 'Purchase Receipt'){
        //         $voucher_no = $row->pr_voucher_no;
        //         $ref_no = $voucher_no;
        //     }elseif($row->voucher_type == 'Stock Entry'){
        //         $ref_no = $row->ste_sales_order;
        //     }elseif($row->voucher_type == 'Stock Reconciliation'){
        //         $ref_no = $voucher_no;
        //     }else{
        //         $ref_no = null;
        //     }

        //     $date_modified = $row->ste_date_modified;
        //     $session_user = $row->ste_session_user;

        //     if($date_modified and $date_modified != '--'){
        //         $date_modified = Carbon::parse($date_modified);
        //     }

        //     $list[] = [
        //         'voucher_no' => $voucher_no,
        //         'warehouse' => $row->warehouse,
        //         'transaction' => $transaction,
        //         'actual_qty' => $row->actual_qty * 1,
        //         'qty_after_transaction' => $row->qty_after_transaction * 1,
        //         'ref_no' => $ref_no,
        //         'date_modified' => $date_modified,
        //         'session_user' => $session_user,
        //         'posting_date' => $row->posting_date,
        //     ];
        // }

        // return view('tbl_stock_ledger', compact('list', 'logs', 'item_code'));
    }

    public function consignmentSalesReport($warehouse, Request $request) {
        $year = $request->year ? $request->year : Carbon::now()->format('Y');
        $query = DB::table('tabConsignment Sales Report')
            ->where('status', '!=', 'Cancelled')
            ->whereYear('transaction_date', $year)->where('branch_warehouse', $warehouse)
            ->selectRaw('MONTH(transaction_date) as transaction_month, SUM(grand_total) as grand_total')
            ->groupBy('transaction_month')->pluck('grand_total', 'transaction_month')->toArray();
        
        $result = [];
        $month_name = [null, 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'];

        $month_now = (int)Carbon::now()->format('m');
        for ($i=1; $i <= $month_now; $i++) { 
            $result[$month_name[$i]] = array_key_exists($i, $query) ? $query[$i] : 0;
        }

        return [
            'labels' => collect($result)->keys(),
            'data' => array_values($result)
        ];
    }
}