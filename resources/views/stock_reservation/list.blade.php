@if(count($webList) > 0)
<h6 class="font-weight-bold text-uppercase font-responsive"><i class="fas fa-box"></i> Website Stock Reservations</h6>
<table class="table table-hover table-bordered table-sm stock-ledger-table-font" style="font-size: 9pt !important;">
    <thead>
        <tr>
            <th class="text-center p-1" style="width: 10% !important">Transaction</th>
            <th class="text-center p-1 d-md-none">Details</th>
            <th class="text-center p-1 d-none d-sm-table-cell" style="width: 10% !important">Reserved Qty</th>
            <th class="text-center p-1 d-none d-sm-table-cell" style="width: 10% !important">Issued Qty</th>
            <th class="text-center p-1 d-none d-sm-table-cell">Warehouse</th>
            <th class="text-center p-1 d-none d-sm-table-cell" style="width: 10% !important">Status</th>
            <th class="text-center p-1 d-none d-sm-table-cell" style="width: 12% !important">Created by</th>
            <th class="text-center p-1 d-none d-sm-table-cell" style="width: 12% !important">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($webList as $row)
        @php
            $badge = '';
            if($row->status == 'Active'){
                $badge = 'primary';
            }else if(round($row->consumed_qty) > 0 && $row->consumed_qty < $row->reserve_qty || $row->status == 'Partially Issued'){
                $badge = 'info';
            }else if($row->reserve_qty == round($row->consumed_qty) && $row->status != 'Expired' || $row->status == 'Issued'){
                $badge = 'success';
            }else if($row->valid_until < Carbon\Carbon::today() || $row->status == 'Expired' || $row->status == 'Cancelled'){
                $badge = 'secondary';
            }

            $attr = (!in_array(Auth::user()->user_group, ['Inventory Manager'])) ? 'disabled' : '';
            $attr_cancelled = ($row->status == 'Cancelled') ? 'disabled' : '';

            $reserved_qty = (floor($row->reserve_qty) != $row->reserve_qty * 1) ? number_format($row->reserve_qty, 4) : $row->reserve_qty * 1;
            $consumed_qty = (floor($row->consumed_qty) != $row->consumed_qty * 1) ? number_format($row->consumed_qty, 4) : $row->consumed_qty * 1;
        @endphp
        <tr>
            <td class="text-center align-middle p-1">
                <span class="d-block font-weight-bold">{{ date('M-d-Y', strtotime($row->creation)) }}</span>
                <small>{{ $row->name }}</small>
                <div class="col-10 d-md-none mx-auto">
                    @if (in_array($row->status, ['Active', 'Partially Issued']))
                        <button type="button" class="btn btn-info btn-sm edit-stock-reservation-btn" data-reservation-id="{{ $row->name }}" {{ $attr }} {{ $attr_cancelled }}>Update</button>
                        <button type="button" class="btn btn-danger btn-sm cancel-stock-reservation-btn" data-reservation-id="{{ $row->name }}" {{ $attr }} {{ $attr_cancelled }}>Cancel</button>
                    @else
                        <br>
                        No Actions Available
                    @endif
                </div>
            </td>
            <td class="d-md-none font-responsive" style="width: 70%">
                <center><span class="badge badge-{{ $badge }}" style="font-size: 10pt;">{{ $row->status }}</span></center><br/>
                <span><b>Reserved Qty:</b> {{ ($reserved_qty).' '.$row->stock_uom }}</span><br>
                <span><b>Issued Qty:</b> {{ ($consumed_qty).' '.$row->stock_uom }}</span><br>
                <span><b>Warehouse:</b> {{ $row->warehouse }}</span><br>
                <span><b>Created by:</b> {{ $row->created_by }}</span><br>
            </td>
            <td class="text-center align-middle text-break p-1 d-none d-sm-table-cell">
                <span class="font-weight-bold">{{ ($reserved_qty) }}</span>
                <small>{{ $row->stock_uom }}</small>
            </td>
            <td class="text-center align-middle text-break p-1 d-none d-sm-table-cell">
                <span class="font-weight-bold">{{ ($consumed_qty) }}</span>
                <small>{{ $row->stock_uom }}</small>
            </td>
            <td class="text-center align-middle p-1 d-none d-sm-table-cell">{{ $row->warehouse }}</td>
            <td class="text-center align-middle p-1 d-none d-sm-table-cell">
                <span class="badge badge-{{ $badge }}" style="font-size: 9pt;">{{ $row->status }}</span>
            </td>
            <td class="text-center align-middle p-1 d-none d-sm-table-cell">{{ $row->created_by }}</td>
            <td class="text-center align-middle p-1 d-none d-sm-table-cell">
                @if (in_array($row->status, ['Active', 'Partially Issued']))
                    <button type="button" class="btn btn-info btn-sm edit-stock-reservation-btn" data-reservation-id="{{ $row->name }}" {{ $attr }} {{ $attr_cancelled }}>Update</button>
                    <button type="button" class="btn btn-danger btn-sm cancel-stock-reservation-btn" data-reservation-id="{{ $row->name }}" {{ $attr }} {{ $attr_cancelled }}>Cancel</button>
                @else
                    No Actions Available
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="box-footer clearfix" id="stock-reservations-pagination-1" data-item-code="{{ $item_code }}" style="font-size: 16pt;">
	{{-- {{ $webList->links() }} --}}
</div>
@endif

@iF(count($consignmentList) > 0)
<h6 class="font-weight-bold text-uppercase font-responsive"><i class="fas fa-box"></i> Consignment Reservations</h6>
<table class="table table-hover table-bordered table-sm stock-ledger-table-font" style="font-size: 9pt !important;">
    <thead>
        <tr>
            <th class="text-center p-1" style="width: 10% !important">Transaction</th>
            <th class="text-center p-1 d-md-none">Details</th>
            <th class="text-center p-1 d-none d-sm-table-cell" style="width: 10% !important">Reserved Qty</th>
            <th class="text-center p-1 d-none d-sm-table-cell" style="width: 10% !important">Issued Qty</th>
            <th class="text-center p-1 d-none d-sm-table-cell">Warehouse</th>
            <th class="text-center p-1 d-none d-sm-table-cell">Branch</th>
            <th class="text-center p-1 d-none d-sm-table-cell" style="width: 10% !important">Status</th>
            <th class="text-center p-1 d-none d-sm-table-cell" style="width: 12% !important">Created by</th>
            <th class="text-center p-1 d-none d-sm-table-cell" style="width: 12% !important">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($consignmentList as $row1)
        @php
            $badge = '';
            if($row1->status == 'Active'){
                $badge = 'primary';
            }else if(round($row1->consumed_qty) > 0 && $row1->consumed_qty < $row1->reserve_qty || $row1->status == 'Partially Issued'){
                $badge = 'info';
            }else if($row1->reserve_qty == round($row1->consumed_qty) && $row1->status != 'Expired' || $row1->status == 'Issued'){
                $badge = 'success';
            }else if($row1->valid_until < Carbon\Carbon::today() || $row1->status == 'Expired' || $row1->status == 'Cancelled'){
                $badge = 'secondary';
            }

            $attr = (!in_array(Auth::user()->user_group, ['Inventory Manager'])) ? 'disabled' : '';

            $reserved_qty = (floor($row1->reserve_qty) != $row1->reserve_qty * 1) ? number_format($row1->reserve_qty, 4) : $row1->reserve_qty * 1;
            $consumed_qty = (floor($row1->consumed_qty) != $row1->consumed_qty * 1) ? number_format($row1->consumed_qty, 4) : $row1->consumed_qty * 1;
        @endphp
        <tr>
            <td class="text-center align-middle p-1">
                <span class="d-block font-weight-bold">{{ date('M-d-Y', strtotime($row1->creation)) }}</span>
                <small>{{ $row1->name }}</small>
                <div class="d-md-none col-10 mx-auto">
                    @if (in_array($row1->status, ['Active', 'Partially Issued']))
                        <button type="button" class="btn btn-info btn-sm edit-stock-reservation-btn font-responsive w-100" data-reservation-id="{{ $row1->name }}" {{ $attr }}>Edit</button>
                        <button type="button" class="btn btn-danger btn-sm cancel-stock-reservation-btn font-responsive w-100" data-reservation-id="{{ $row1->name }}" {{ $attr }}>Cancel</button>
                    @else
                        <br>
                        No Actions Available
                    @endif
                </div>
            </td>
            <td class="d-md-none font-responsive" style="width: 70%">
                <center><span class="badge badge-{{ $badge }}" style="font-size: 10pt;">{{ $row1->status }}</span></center><br/>
                <span><b>Reserved Qty:</b> {{ ($reserved_qty).' '.$row1->stock_uom }}</span><br>
                <span><b>Issued Qty:</b> {{ ($consumed_qty).' '.$row1->stock_uom }}</span><br>
                <span><b>Warehouse:</b> {{ $row1->warehouse }}</span><br>
                <span><b>Branch:</b> {{ $row1->consignment_warehouse }}</span><br>
                <span><b>Created by:</b> {{ $row1->created_by }}</span>
            </td>
            <td class="text-center align-middle text-break p-1 d-none d-sm-table-cell">
                <span class="font-weight-bold">{{ ($reserved_qty) }}</span>
                <small>{{ $row1->stock_uom }}</small>
            </td>
            <td class="text-center align-middle text-break p-1 d-none d-sm-table-cell">
                <span class="font-weight-bold">{{ ($consumed_qty) }}</span>
                <small>{{ $row1->stock_uom }}</small>
            </td>
            <td class="text-center align-middle p-1 d-none d-sm-table-cell">{{ $row1->warehouse }}</td>
            <td class="text-center align-middle text-break p-1 d-none d-sm-table-cell">{{ $row1->consignment_warehouse }}</td>
            <td class="text-center align-middle p-1 d-none d-sm-table-cell">
                <span class="badge badge-{{ $badge }}" style="font-size: 9pt;">{{ $row1->status }}</span>
            </td>
            <td class="text-center align-middle p-1 d-none d-sm-table-cell">{{ $row1->created_by }}</td>
            <td class="text-center align-middle p-1 d-none d-sm-table-cell">
                @if (in_array($row1->status, ['Active', 'Partially Issued']))
                    <button type="button" class="btn btn-info btn-sm edit-stock-reservation-btn" data-reservation-id="{{ $row1->name }}" {{ $attr }}>Edit</button>
                    <button type="button" class="btn btn-danger btn-sm cancel-stock-reservation-btn" data-reservation-id="{{ $row1->name }}" {{ $attr }}>Cancel</button>
                @else
                    No Actions Available
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="box-footer clearfix" id="stock-reservations-pagination-2" data-item-code="{{ $item_code }}" style="font-size: 16pt;">
	{{-- {{ $consignmentList->links() }} --}}
</div>
@endif

<h6 class="font-weight-bold text-uppercase font-responsive"><i class="fas fa-box"></i> In-house Reservations</h6>
<table class="table table-hover table-bordered table-sm font-responsive" style="font-size: 9pt !important;">
    <thead>
        <tr>
            <th class="text-center p-1" style="width: 10% !important">Transaction</th>
            <th class="text-center p-1 d-md-none">Details</th>
            <th class="text-center p-1 d-none d-sm-table-cell" style="width: 10% !important">Reserved Qty</th>
            <th class="text-center p-1 d-none d-sm-table-cell" style="width: 10% !important">Issued Qty</th>
            <th class="text-center p-1 d-none d-sm-table-cell">Warehouse</th>
            <th class="text-center p-1 d-none d-sm-table-cell">Sales Person</th>
            <th class="text-center p-1 d-none d-sm-table-cell">Validity</th>
            <th class="text-center p-1 d-none d-sm-table-cell" style="width: 10% !important">Status</th>
            <th class="text-center p-1 d-none d-sm-table-cell" style="width: 12% !important">Created by</th>
            <th class="text-center p-1 d-none d-sm-table-cell" style="width: 12% !important">Action</th>
        </tr>
    </thead>
    <tbody>
    @forelse ($inhouseList as $row2)<!-- In-house -->
        @php
            $badge = '';
            if($row2->status == 'Active'){
                $badge = 'primary';
            }else if(round($row2->consumed_qty) > 0 && $row2->consumed_qty < $row2->reserve_qty || $row2->status == 'Partially Issued'){
                $badge = 'info';
            }else if($row2->reserve_qty == round($row2->consumed_qty) && $row2->status != 'Expired' || $row2->status == 'Issued'){
                $badge = 'success';
            }else if($row2->valid_until < Carbon\Carbon::today() || $row2->status == 'Expired' || $row2->status == 'Cancelled'){
                $badge = 'secondary';
            }
            
            $attr = (!in_array(Auth::user()->user_group, ['Inventory Manager'])) ? 'disabled' : '';

            $reserved_qty = (floor($row2->reserve_qty) != $row2->reserve_qty * 1) ? number_format($row2->reserve_qty, 4) : $row2->reserve_qty * 1;
            $consumed_qty = (floor($row2->consumed_qty) != $row2->consumed_qty * 1) ? number_format($row2->consumed_qty, 4) : $row2->consumed_qty * 1;
        @endphp
        <tr>
            <td class="text-center align-middle p-1">
                <span class="d-block font-weight-bold">{{ date('M-d-Y', strtotime($row2->creation)) }}</span>
                <small>{{ $row2->name }}</small>
                <div class="d-md-none col-10 mx-auto">
                    @if (in_array($row2->status, ['Active', 'Partially Issued']))
                        <button type="button" class="btn btn-info btn-sm edit-stock-reservation-btn font-responsive w-100" data-reservation-id="{{ $row2->name }}" {{ $attr }}>Edit</button><br/>
                        <button type="button" class="btn btn-danger btn-sm cancel-stock-reservation-btn font-responsive w-100" data-reservation-id="{{ $row2->name }}" {{ $attr }}>Cancel</button>
                    @else
                        <br>
                        No Actions Available
                    @endif
                </div>
            </td>
            <td class="d-md-none font-responsive" style="width: 70%">
                <center><span class="badge badge-{{ $badge }}" style="font-size: 10pt;">{{ $row2->status }}</span></center><br/>
                <span><b>Reserved Qty:</b> {{ ($reserved_qty).' '.$row2->stock_uom }}</span><br>
                <span><b>Issued Qty:</b> {{ ($consumed_qty).' '.$row2->stock_uom }}</span><br>
                <span><b>Warehouse:</b> {{ $row2->warehouse }}</span><br>
                <span><b>Sales Person:</b> {{ $row2->sales_person }}</span><br>
                <span><b>Validity:</b> {{ ($row2->valid_until) ? $row2->valid_until : '-' }}</span><br>
                <span><b>Created by:</b> {{ $row2->created_by }}</span>
            </td>
            <td class="text-center align-middle text-break p-1 d-none d-sm-table-cell">
                <span class="font-weight-bold">{{ ($reserved_qty) }}</span>
                <small>{{ $row2->stock_uom }}</small>
            </td>
            <td class="text-center align-middle text-break p-1 d-none d-sm-table-cell">
                <span class="font-weight-bold">{{ ($consumed_qty) }}</span>
                <small>{{ $row2->stock_uom }}</small>
            </td>
            <td class="text-center align-middle p-1 d-none d-sm-table-cell">{{ $row2->warehouse }}</td>
            <td class="text-center align-middle text-break p-1 d-none d-sm-table-cell">{{ $row2->sales_person }}</td>
            <td class="text-center align-middle text-break p-1 d-none d-sm-table-cell">{{ ($row2->valid_until) ? $row2->valid_until : '-' }}</td>
            <td class="text-center align-middle p-1 d-none d-sm-table-cell">
                <span class="badge badge-{{ $badge }}" style="font-size: 9pt;">{{ $row2->status }}</span>
            </td>
            <td class="text-center align-middle p-1 d-none d-sm-table-cell">{{ $row2->created_by }}</td>
            <td class="text-center align-middle p-1 d-none d-sm-table-cell">
                @if (in_array($row2->status, ['Active', 'Partially Issued']))
                    <button type="button" class="btn btn-info btn-sm edit-stock-reservation-btn" data-reservation-id="{{ $row2->name }}" {{ $attr }}>Edit</button>
                    <button type="button" class="btn btn-danger btn-sm cancel-stock-reservation-btn" data-reservation-id="{{ $row2->name }}" {{ $attr }}>Cancel</button>
                @else
                    No Actions Available
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="12" class="text-center">No record(s) found.</td>
        </tr>
        @endforelse<!-- In-house -->
    </tbody>
</table>

<div class="box-footer clearfix" id="stock-reservations-pagination-3" data-item-code="{{ $item_code }}" style="font-size: 16pt;">
	{{-- {{ $inhouseList->links() }} --}}
</div>