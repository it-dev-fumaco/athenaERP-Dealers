
@if ($report_type == 'lazada_orders')
@php
if ($export_excel == 1){
    header("Content-Disposition: attachment; filename=Lazada Orders.xls");
    header("Content-Type: application/vnd.ms-excel");
}
@endphp
<table class="table table-striped">
    <col style="width: 8%;">
    <col style="width: 8%;">
    <col style="width: 10%;">
    <col style="width: 8%;">
    <col style="width: 16%;">
    <col style="width: 6%;">
    <col style="width: 5%;">
    <col style="width: 15%;">
    <col style="width: 10%;">
    <col style="width: 5%;">
    <col style="width: 8%;">
    <thead>
        <th class="text-center">STE No.</th>
        <th class="text-center">Posting Date</th>
        <th class="text-center">Purpose</th>
        <th class="text-center">Item Code</th>
        <th class="text-center">Description</th>
        <th class="text-center">Qty</th>
        <th class="text-center">UOM</th>
        <th class="text-center">Remarks</th>
        <th class="text-center">Date Issued</th>
        <th class="text-center">Status</th>
        <th class="text-center">Issued By</th>
    </thead>
    @forelse ($query as $a)
    <tr>
        <td class="text-center">{{ $a->name }}</td>
        <td class="text-center">{{ $a->posting_date }}</td>
        <td class="text-center">{{ $a->purpose }}</td>
        <td class="text-center">{{ $a->item_code }}</td>
        <td class="text-center">{{ $a->description }}</td>
        <td class="text-center">{{ $a->transfer_qty }}</td>
        <td class="text-center">{{ $a->stock_uom }}</td>
        <td class="text-center">{{ $a->remarks }}</td>
        <td class="text-center">{{ $a->date_modified }}</td>
        <td class="text-center">{{ $a->status }}</td>
        <td class="text-center">{{ $a->session_user }}</td>
    </tr>
    @empty
    <tr>
        <td class="text-center" colspan="11">No Records</td>
    </tr>
    @endforelse
</table>    
@endif

@if ($report_type == 'withdrawals')
@php
if ($export_excel == 1){
    header("Content-Disposition: attachment; filename=Production Order - Withdrawals.xls");
    header("Content-Type: application/vnd.ms-excel");
}
@endphp
<table class="table table-striped">
    <col style="width: 10%;">
    <col style="width: 10%;">
    <col style="width: 10%;">
    <col style="width: 10%;">
    <col style="width: 10%;">
    <col style="width: 10%;">
    <col style="width: 10%;">
    <col style="width: 10%;">
    <col style="width: 10%;">
    <col style="width: 10%;">
    <thead>
        <th class="text-center">Prod. No</th>
        <th class="text-center">Posting Date</th>
        <th class="text-center">Sales Order</th>
        <th class="text-center">Customer</th>
        <th class="text-center">Project</th>
        <th class="text-center">STE No.</th>
        <th class="text-center">Item Code</th>
        <th class="text-center">Description</th>
        <th class="text-center">Qty</th>
        <th class="text-center">UOM</th>
    </thead>
    @forelse ($query as $a)
    <tr>
        <td class="text-center">{{ $a->production_order }}</td>
        <td class="text-center">{{ $a->posting_date }}</td>
        <td class="text-center">{{ $a->sales_order_no }}</td>
        <td class="text-center">{{ $a->so_customer_name }}</td>
        <td class="text-center">{{ $a->project }}</td>
        <td class="text-center">{{ $a->name }}</td>
        <td class="text-center">{{ $a->item_code }}</td>
        <td class="text-center">{{ $a->description }}</td>
        <td class="text-center">{{ $a->transfer_qty }}</td>
        <td class="text-center">{{ $a->stock_uom }}</td>
    </tr>
    @empty
    <tr>
        <td class="text-center" colspan="10">No Records</td>
    </tr>
    @endforelse
</table>    
@endif


@if ($report_type == 'sales_orders')
@php
if ($export_excel == 1){
    header("Content-Disposition: attachment; filename=Sales Orders.xls");
    header("Content-Type: application/vnd.ms-excel");
}
@endphp
<table class="table table-striped">
    <col style="width: 10%;">
    <col style="width: 10%;">
    <col style="width: 10%;">
    <col style="width: 10%;">
    <col style="width: 10%;">
    <col style="width: 10%;">
    <col style="width: 27%;">
    <col style="width: 8%;">
    <col style="width: 5%;">
    <thead>
        <th class="text-center">Transaction Date</th>
        <th class="text-center">Sales Order</th>
        <th class="text-center">Customer</th>
        <th class="text-center">Project</th>
        <th class="text-center">DR No.</th>
        <th class="text-center">Item Code</th>
        <th class="text-center">Description</th>
        <th class="text-center">Qty</th>
        <th class="text-center">UOM</th>
    </thead>
    @forelse ($query as $a)
    <tr>
        <td class="text-center">{{ $a->posting_date }}</td>
        <td class="text-center">{{ $a->sales_order }}</td>
        <td class="text-center">{{ $a->customer }}</td>
        <td class="text-center">{{ $a->project }}</td>
        <td class="text-center">{{ $a->name }}</td>
        <td class="text-center">{{ $a->item_code }}</td>
        <td class="text-center">{{ $a->description }}</td>
        <td class="text-center">{{ $a->qty }}</td>
        <td class="text-center">{{ $a->stock_uom }}</td>
    </tr>
    @empty
    <tr>
        <td class="text-center" colspan="9">No Records</td>
    </tr>
    @endforelse
</table>    
@endif




@if ($report_type == 'summary')
@php
if ($export_excel == 1){
    header("Content-Disposition: attachment; filename=Sales Report Summary.xls");
    header("Content-Type: application/vnd.ms-excel");
}
@endphp
<table class="table table-bordered" border="1" style="width: 2790px;">
    <thead>
        <tr>
            <th class="text-center align-middle p-1 text-uppercase" rowspan="2" style="width: 100px;">Item Code</th>
            <th class="text-center align-middle p-1 text-uppercase" rowspan="2" style="width: 350px;">Description</th>
            @foreach ($months as $month)
            <th class="text-center p-1 text-uppercase" colspan="3" style="font-size: 15pt;">{{ $month }}</th>
            @endforeach
            <th class="text-center p-1 text-uppercase" colspan="3" style="font-size: 15pt;">Total</th>
            <th class="text-center p-1 text-uppercase" rowspan="2" style="font-size: 12pt;">Overall Total</th>
        </tr>
        <tr>
            @foreach ($months as $month)
            <th class="text-center p-0" style="width: 60px !important;">SO</th>
            <th class="text-center p-0" style="width: 60px !important;">STE</th>
            <th class="text-center p-0" style="width: 60px !important;">LAZ</th>
            @endforeach
            <th class="text-center p-0" style="width: 60px !important;">SO</th>
            <th class="text-center p-0" style="width: 60px !important;">STE</th>
            <th class="text-center p-0" style="width: 60px !important;">LAZ</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($result as $row)
        <tr>
            <td class="text-center font-weight-bold p-1">{{ $row['item_code'] }}</td>
            <td class="text-justify p-1">{{ $row['description'] }}</td>
            @foreach ($row['per_month'] as $month)
            <td class="text-center p-0 align-middle">{{ $month['sales'] }}</td>
            <td class="text-center p-0 align-middle">{{ $month['withdrawals'] }}</td>
            <td class="text-center p-0 align-middle">{{ $month['lazada'] }}</td>
            @endforeach
            <td class="text-center p-0 align-middle font-weight-bold">{{ $row['total_so_qty'] }}</td>
            <td class="text-center p-0 align-middle font-weight-bold">{{ $row['total_ste_qty'] }}</td>
            <td class="text-center p-0 align-middle font-weight-bold">{{ $row['total_laz_qty'] }}</td>
            <td class="text-center p-0 align-middle font-weight-bold">{{ $row['overall_total'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif
