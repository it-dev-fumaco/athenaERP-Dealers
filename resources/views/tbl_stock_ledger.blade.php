<br class="d-md-none">
<table class="table table-hover table-sm table-bordered table-striped" style="font-size: 9pt;">
    <thead>
      <tr>
        <th scope="col" class="col-xs-2 font-responsive text-center">Transaction No.</th>
        <th scope="col" class="col-xs-2 font-responsive text-center d-md-none">Details</th>
        <th scope="col" class="col-xs-2 font-responsive text-center d-none d-sm-table-cell">Warehouse</th>
        <th scope="col" class="col-xs-3 font-responsive text-center d-none d-sm-table-cell">Transaction</th>
        <th scope="col" class="col-xs-1 font-responsive text-center d-none d-sm-table-cell">Qty</th>
        <th scope="col" class="col-xs-1 font-responsive text-center d-none d-sm-table-cell">Balance Qty</th>
        <th scope="col" class="col-xs-1 font-responsive text-center d-none d-sm-table-cell">Ref. No.</th>
        <th scope="col" class="col-xs-1 font-responsive text-center d-none d-sm-table-cell">Date</th>
        <th scope="col" class="col-xs-2 font-responsive text-center d-none d-sm-table-cell">Transact by</th>
      </tr>
    </thead>
    <tbody>
    @forelse ($list as $row)
    <tr>
        <td class="text-center font-responsive">{{ $row['voucher_no'] }}</td>
        <td class="d-md-none font-responsive" style="width: 70%">
          <span><b>Warehouse:</b> {{ $row['warehouse'] }}</span><br>
          <span><b>Transaction:</b> {{ $row['transaction'] }}</span><br>
          <span><b>Qty:</b> {{ $row['actual_qty'] }}</span><br>
          <span><b>Balance Qty:</b> {{ $row['qty_after_transaction'] }}</span><br>
          <span><b>Ref. No.:</b> {{ $row['ref_no'] }}</span><br>
          <span><b>Date:</b> {{ $row['date_modified'] }}</span><br>
          <span><b>Transact by:</b> {{ $row['session_user'] }}</span>
        </td>
        <td class="text-center font-responsive d-none d-sm-table-cell">{{ $row['warehouse'] }}</td>
        <td class="text-center font-responsive d-none d-sm-table-cell">{{ $row['transaction'] }}</td>
        <td class="text-center font-responsive d-none d-sm-table-cell">{{ $row['actual_qty'] }}</td>
        <td class="text-center font-responsive d-none d-sm-table-cell">{{ $row['qty_after_transaction'] }}</td>
        <td class="text-center font-responsive d-none d-sm-table-cell">{{ $row['ref_no'] }}</td>
        <td class="text-center font-responsive d-none d-sm-table-cell">{{ $row['date_modified'] }}</td>
        <td class="text-center font-responsive d-none d-sm-table-cell">{{ $row['session_user'] }}</td>
      </tr>
    @empty
    <tr>
      <td colspan="8" style="text-align:center;">No Records Found.</td>
    </tr>
    @endforelse
    </tbody>
  </table>
  <div class="box-footer clearfix" id="stock-ledger-pagination" data-item-code="{{ $item_code }}" style="font-size: 10pt;">
    {{-- {{ $logs->links() }} --}}
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