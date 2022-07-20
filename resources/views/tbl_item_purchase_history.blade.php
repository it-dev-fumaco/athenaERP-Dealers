<h5 class="m-2">Item Purchase Rate History</h5>
<table class="table table-sm table-striped table-bordered" style="font-size: 9pt;">
    <thead>
        <th class="text-center" style="width: 10%;">Transaction Date</th>
        <th class="text-center" style="width: 10%;">Purchase Order No.</th>
        <th class="text-center" style="width: 25%;">Supplier</th>
        <th class="text-center" style="width: 10%;">Supplier Group</th>
        <th class="text-center" style="width: 15%;">Qty</th>
        <th class="text-center" style="width: 15%;">Rate</th>
        <th class="text-center" style="width: 15%;">Valuation Rate</th>
    </thead>
    <tbody>
        @forelse ($list as $row)
        <tr>
            <td class="text-center">{{ \Carbon\Carbon::parse($row->transaction_date)->format('M-d-Y h:i:A') }}</td>
            <td class="text-center">{{ $row->name }}</td>
            <td class="text-center">{{ $row->supplier }}</td>
            <td class="text-center">{{ $row->supplier_group }}</td>
            <td class="text-center">{{ number_format($row->qty) . ' ' . $row->stock_uom }}</td>
            <td class="text-center">{{ '₱ ' . number_format($row->base_rate, 2, '.', ',') }}</td>
            <td class="text-center">
                @php
                    $valuation_rate = array_key_exists($row->name, $item_valuation_rates) ? $item_valuation_rates[$row->name] : 0;
                @endphp
                @if ($valuation_rate > 0)
                {{ '₱ ' . number_format($valuation_rate, 2, '.', ',') }}
                @else
                N/A
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-center font-weight-bold text-muted">No record(s) found.</td>
        </tr>
        @endforelse
    </tbody>
</table>

<div class="box-footer clearfix" id="purchase-history-pagination" style="font-size: 16pt;">
    {{ $list->links() }}
</div>

