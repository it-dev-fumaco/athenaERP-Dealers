<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function salesReport(Request $request){
        if(!$request->report_type){
            return view('external_reports.sales_report');
        }

        $export_excel = $request->export;

        $start = new Carbon('first day of January ' . $request->year);
        $end = new Carbon('last day of December ' . $request->year);

        $report_type = $request->report_type;

        $item_codes = ['LR00440', 'DO00433', 'DO00435', 'BT00673', 'BT00674', 'BT00675', 'BT00677', 'BT00678', 'BT00679', 'BT00686', 'BT00681', 'BT00683', 'BT00684'];

        if($report_type == 'lazada_orders'){
            $query = DB::table('tabStock Entry as ste')
                ->join('tabStock Entry Detail as sted', 'ste.name', 'sted.parent')
                ->where('ste.purpose', 'Material Issue')->where('ste.docstatus', 1)
                ->where('ste.remarks', 'like', '%lazada%')
                // ->whereIn('sted.item_code', $item_codes)
                ->whereBetween('ste.posting_date', [$start, $end])
                ->select('ste.name', 'ste.posting_date', 'ste.purpose', 'ste.remarks', 'sted.item_code', 'sted.description', 'sted.transfer_qty', 'sted.stock_uom', 'sted.date_modified', 'sted.status', 'sted.session_user')
                ->orderBy('ste.posting_date', 'asc')
                ->orderBy('sted.item_code', 'asc')->get();
        }

        if($report_type == 'withdrawals'){
            $query = DB::table('tabStock Entry as ste')
                ->join('tabStock Entry Detail as sted', 'ste.name', 'sted.parent')
                ->where('ste.purpose', 'Manufacture')->where('ste.docstatus', 1)
                ->whereIn('sted.item_code', $item_codes)
                ->whereBetween('ste.posting_date', [$start, $end])
                ->select('ste.work_order', 'ste.posting_date', 'ste.sales_order_no', 'ste.so_customer_name', 'ste.project', 'ste.name', 'sted.item_code', 'sted.description', 'sted.transfer_qty', 'sted.stock_uom')
                ->orderBy('ste.posting_date', 'asc')
                ->orderBy('sted.item_code', 'asc')->get();
        }

        if($report_type == 'sales_orders'){
            $query = DB::table('tabDelivery Note as dr')
                ->join('tabDelivery Note Item as dri', 'dr.name', 'dri.parent')
                ->whereIn('dr.status', ['Completed', 'To Bill'])->where('dr.docstatus', 1)
                ->whereIn('dri.item_code', $item_codes)
                ->whereBetween('dr.posting_date', [$start, $end])
                ->select('dr.posting_date', 'dr.sales_order', 'dr.customer', 'dr.project', 'dr.name', 'dri.item_code', 'dri.description', 'dri.qty', 'dri.stock_uom', 'dr.status')
                ->orderBy('dr.posting_date', 'asc')
                ->orderBy('dri.item_code', 'asc')->get();
        }

        return view('external_reports.sales_report_table', compact('query', 'report_type', 'export_excel'));
    }

    public function salesReportSummary(Request $request, $year){
        $item_codes = ['LR00440', 'DO00433', 'DO00435', 'BT00673', 'BT00674', 'BT00675', 'BT00677', 'BT00678', 'BT00679', 'BT00686', 'BT00681', 'BT00683', 'BT00684'];

        $lazada_orders = DB::table('tabStock Entry as ste')
            ->join('tabStock Entry Detail as sted', 'ste.name', 'sted.parent')
            ->where('ste.purpose', 'Material Issue')->where('ste.docstatus', 1)
            ->where('ste.remarks', 'like', '%lazada%')
            ->where(DB::raw('YEAR(ste.posting_date)'), $year)
            // ->whereIn('sted.item_code', $item_codes)
            ->select('sted.item_code', 'sted.transfer_qty', DB::raw('MONTH(ste.posting_date) as month'), DB::raw('YEAR(ste.posting_date) as year'))->get();

        $withdrawals = DB::table('tabStock Entry as ste')
            ->join('tabStock Entry Detail as sted', 'ste.name', 'sted.parent')
            ->where('ste.purpose', 'Manufacture')->where('ste.docstatus', 1)
            ->whereIn('sted.item_code', $item_codes)
            ->where(DB::raw('YEAR(ste.posting_date)'), $year)
            ->select('sted.item_code', 'sted.transfer_qty', DB::raw('MONTH(ste.posting_date) as month'), DB::raw('YEAR(ste.posting_date) as year'))->get();

        $sales_orders = DB::table('tabDelivery Note as dr')
            ->join('tabDelivery Note Item as dri', 'dr.name', 'dri.parent')
            ->whereIn('dr.status', ['Completed', 'To Bill'])->where('dr.docstatus', 1)
            ->whereIn('dri.item_code', $item_codes)
            ->where(DB::raw('YEAR(dr.posting_date)'), $year)
            ->select('dri.item_code', 'dri.qty', DB::raw('MONTH(dr.posting_date) as month'), DB::raw('YEAR(dr.posting_date) as year'))->get();

        $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        $result = [];
        foreach($item_codes as $item_code){
            $item_info = DB::table('tabItem')->where('name', $item_code)->first();
            $item_description = ($item_info) ? $item_info->description : null;
            $per_month = [];
            foreach($months as $i => $month){
                $i++;
                $lazada_orders_qty = collect($lazada_orders)->where('item_code', $item_code)->where('month', $i)->sum('transfer_qty');
                $withdrawals_qty = collect($withdrawals)->where('item_code', $item_code)->where('month', $i)->sum('transfer_qty');
                $sales_orders_qty = collect($sales_orders)->where('item_code', $item_code)->where('month', $i)->sum('qty');

                $per_month[] = [
                    'month' => $month,
                    'lazada' => $lazada_orders_qty,
                    'sales' => $sales_orders_qty,
                    'withdrawals' => $withdrawals_qty
                ];
            }

            $total_so_qty = collect($per_month)->sum('sales');
            $total_laz_qty = collect($per_month)->sum('lazada');
            $total_ste_qty = collect($per_month)->sum('withdrawals');
            $total_qty = $total_ste_qty + $total_laz_qty + $total_so_qty;

            $result[] = [
                'item_code' => $item_code,
                'description' => $item_description,
                'per_month' => $per_month,
                'total_so_qty' => $total_so_qty,
                'total_laz_qty' => $total_laz_qty,
                'total_ste_qty' => $total_ste_qty,
                'overall_total' => $total_qty
            ];
        }

        $report_type = 'summary';
        $export_excel = $request->export;

        return view('external_reports.sales_report_table', compact('result', 'report_type', 'months', 'export_excel'));
    }
}
