<br class="d-md-none">
<table class="table table-sm table-bordered table-striped table-hover" style="font-size: 9pt;">
    <thead>
    <tr>
        <th scope="col" class="text-center">Transaction No.</th>
        <th class="text-center d-md-none">Details</th>
        <th scope="col" class="text-center d-none d-sm-table-cell">From Warehouse</th>
        <th scope="col" class="text-center d-none d-sm-table-cell">To Warehouse</th>
        <th scope="col" class="text-center d-none d-sm-table-cell">Transaction</th>
        <th scope="col" class="text-center d-none d-sm-table-cell">Issued Qty</th>
        <th scope="col" class="text-center d-none d-sm-table-cell">Ref. No.</th>
        <th scope="col" class="text-center d-none d-sm-table-cell">Date</th>
        <th scope="col" class="text-center d-none d-sm-table-cell">Transact by</th>
        <th scope="col" class="text-center d-none d-sm-table-cell">Remarks</th>
    </tr>
    </thead>
    <tbody>
        @forelse ($list as $row)
        @php
        $row = collect($row)->toArray();
            if(in_array($row['status'], ['CANCELLED', 'DELETED'])){
                $label = 'badge-danger';
            }elseif($row['status'] == 'DRAFT'){
                $label = 'badge-warning';
            }else{
                $label = 'badge-primary';
            }
        @endphp
        <tr>
            <td class="text-center">
                <span class="d-block">{{ $row['reference_parent'] }}<br/></span>
                <span class="badge {{ $label }}">{{ $row['status'] }}</span>
                <div class="d-md-none mt-2">
                    @if($user_group == 'Inventory Manager')
                    <button type="button" id="cancel-btn" class="btn btn-danger btn-sm cancel-transaction" data-toggle="modal" data-target="#mob-cancel-transaction-modal-{{ $row['reference_parent'] }}" {{ $row['status'] == 'DRAFT' ? '' : 'disabled' }}>
                        Cancel
                    </button>
                    <div class="modal fade cancel-modal" id="mob-cancel-transaction-modal-{{ $row['reference_parent'] }}" tabindex="999" aria-labelledby="cancel-transaction" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="/cancel_transaction" method="POST">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="cancel-transaction-label">Confirm Cancel</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body text-center">
                                        Cancel <b>{{ $row['reference_parent'] }}</b> Transaction?
                                        <input type="text" name="athena_transaction_number" value="{{ $row['reference_parent'] }}" required hidden readonly/>
                                        <input type="text" name="athena_reference_name" value="{{ $row['reference_name'] }}" required hidden readonly/>
                                        <input type="text" name="itemCode" value="{{ $row['item_code'] }}" required hidden readonly/>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Yes</button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </td>
            <td class="d-md-none" style="width: 60%;">
                <span><b>Transaction: </b>{{ $row['reference_type'] }}</span><br>
                <span><b>Issued Qty: </b>{{ $row['issued_qty'] }}</span><br>
                <span><b>Date: </b>{{ $row['transaction_date'] }}</span><br>
            </td>
            <td class="text-center d-none d-sm-table-cell">{{ $row['source_warehouse'] }}</td>
            <td class="text-center d-none d-sm-table-cell">{{ $row['target_warehouse'] }}</td>
            <td class="text-center d-none d-sm-table-cell">{{ $row['reference_type'] }}</td>
            <td class="text-center d-none d-sm-table-cell">{{ $row['issued_qty'] }}</td>
            <td class="text-center d-none d-sm-table-cell">{{ $row['reference_no'] }}</td>
            <td class="text-center d-none d-sm-table-cell">{{ $row['transaction_date'] }}</td>
            <td class="text-center d-none d-sm-table-cell">{{ $row['warehouse_user'] }}</td>
            <td class="text-center d-none d-sm-table-cell">{{ $row['remarks'] }}</td>
            @if($user_group == 'Inventory Manager')
                <td class="text-center d-none d-sm-table-cell">
                    <button type="button" id="cancel-btn" class="btn btn-secondary btn-sm cancel-transaction" data-toggle="modal" data-target="#cancel-transaction-modal-{{ $row['reference_parent'] }}" {{ $row['status'] == 'DRAFT' ? '' : 'disabled' }}>
                        Cancel
                    </button>
                    <div class="modal fade cancel-modal" id="cancel-transaction-modal-{{ $row['reference_parent'] }}" tabindex="999" aria-labelledby="cancel-transaction" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="/cancel_transaction" method="POST">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="cancel-transaction-label">Confirm Cancel</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body text-center">
                                        Cancel <b>{{ $row['reference_parent'] }}</b> Transaction?
                                        <input type="text" name="athena_transaction_number" value="{{ $row['reference_parent'] }}" required hidden readonly/>
                                        <input type="text" name="athena_reference_name" value="{{ $row['reference_name'] }}" required hidden readonly/>
                                        <input type="text" name="itemCode" value="{{ $row['item_code'] }}" required hidden readonly/>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Yes</button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </td>
            @endif
        </tr>
        <tr class="d-md-none">
            <td colspan=12>
                <table class="table table-bordered p-0 font-responsive">
                    <tr>
                        <td colspan=2 class="text-center p-1"><b>Warehouse</b></td>
                    </tr>
                    <tr>
                        <td class="p-1"><b>From:</b></td>
                        <td class="p-1">{{ $row['source_warehouse'] }}</td>
                    </tr>
                    <tr>
                        <td class="p-1"><b>To:</b></td>
                        <td class="p-1">{{ $row['target_warehouse'] }}</td>
                    </tr>
                    <tr>
                        <td class="p-1"><b>User:</b></td>
                        <td class="p-1">{{ $row['warehouse_user'] }}</td>
                    </tr>
                </table>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="9" style="text-align:center;">No Records Found.</td>
        </tr>
        @endforelse
    </tbody>
</table>

<div class="box-footer clearfix" id="athena-transactions-pagination" data-item-code="{{ $item_code }}" style="font-size: 10pt;">
    @if(isset($total_records) && $total_records > 0)
    @php
        $ends_count = 2;  //how many items at the ends (before and after [...])
        $middle_count = 2;  //how many items before and after current page
        $dots = false;
        $prev = $current_page - 1;
    @endphp
    <ul class="pagination">
        <li class="page-item {{ (1 < $current_page) ? '' : 'disabled' }}">
        <a href="{!! request()->fullUrlWithQuery(['page' => $prev]) !!}" class="page-link">Previous</a>
        </li>
        @for ($i = 1; $i <= $numOfPages; $i++) 
        @if ($i == $current_page)
        <li class="page-item active">
            <span class="page-link">{{ $i }}</span>
        </li>
        @php
            $dots = true;
        @endphp
        @else
            @if ($i <= $ends_count || ($current_page && $i >= $current_page - $middle_count && $i <= $current_page + $middle_count) || $i > $numOfPages - $ends_count) 
            <li class="page-item"><a class="page-link" href="{!! request()->fullUrlWithQuery(['page' => $i]) !!}">{{ $i }}</a></li>
            @php
                $dots = true;
            @endphp
            @elseif ($dots)
            <li class="page-item disabled">
                <a class="page-link" href="#">&hellip;</a>
            </li>
            @php
            $dots = false;
            @endphp
            @endif
        @endif
        @endfor
        <li class="page-item {{ ($current_page < $numOfPages || -1 == $numOfPages) ? '' : 'disabled' }}">
            <a class="page-link" href="{!! request()->fullUrlWithQuery(['page' => $next_page]) !!}">Next</a>
        </li>
    </ul>
    @endif
</div>
<style>
    .cancel-modal{
        background: rgba(0, 0, 0, .7);
    }
</style>