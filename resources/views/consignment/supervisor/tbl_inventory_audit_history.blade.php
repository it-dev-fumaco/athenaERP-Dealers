<table class="table table-bordered table-striped" style="font-size: 9pt;">
    <thead>
        <th class="text-center font-responsive align-middle p-2">Transaction Date</th>
        <th class="text-center font-responsive align-middle p-2 d-none d-lg-table-cell">Store</th>
        <th class="text-center font-responsive align-middle p-2 d-none d-lg-table-cell">Period</th>
        <th class="text-center font-responsive align-middle p-2 d-none d-lg-table-cell">Total Qty Sold</th>
        <th class="text-center font-responsive align-middle p-2 d-none d-lg-table-cell">Total Sales</th>
        <th class="text-center font-responsive align-middle p-2 d-none d-lg-table-cell">Promodiser</th>
        <th class="text-center font-responsive align-middle p-2">Action</th>
    </thead>
    <tbody>
        @forelse($result as $row)
        <tr>
            <td class="text-center font-responsive align-middle p-2 d-none d-lg-table-cell">{{ \Carbon\Carbon::parse($row['transaction_date'])->format('F d, Y') }}</td>
            <td class="text-left font-responsive align-middle p-2">
                <span class="d-lg-none">{{ \Carbon\Carbon::parse($row['audit_date_from'])->format('F d, Y') }} - {{ \Carbon\Carbon::parse($row['audit_date_to'])->format('F d, Y') }}</span>
                <span class="d-block">{{ $row['branch_warehouse'] }}</span>
                <span class="d-block d-lg-none"><b>Total Qty Sold: </b>{{ number_format($row['total_qty_sold']) }}</span>
                <span class="d-block d-lg-none"><b>Total Sales: </b>{{ '₱ ' . number_format($row['total_sales'], 2) }}</span>
                <span class="d-block d-lg-none">{{ $row['promodiser'] }} - {{ \Carbon\Carbon::parse($row['transaction_date'])->format('F d, Y') }}</span>
            </td>
            <td class="text-center font-responsive align-middle p-2 d-none d-lg-table-cell">{{ \Carbon\Carbon::parse($row['audit_date_from'])->format('F d, Y') }} - {{ \Carbon\Carbon::parse($row['audit_date_to'])->format('F d, Y') }}</td>
            <td class="text-center font-responsive align-middle p-2 d-none d-lg-table-cell">{{ number_format($row['total_qty_sold']) }}</td>
            <td class="text-center font-responsive align-middle p-2 d-none d-lg-table-cell">{{ '₱ ' . number_format($row['total_sales'], 2) }}</td>
            <td class="text-center font-responsive align-middle p-2 d-none d-lg-table-cell">{{ $row['promodiser'] }}</td>
            <td class="text-center font-responsive align-middle p-2">
                <a href="/view_inventory_audit_items/{{ $row['branch_warehouse'] }}/{{ $row['audit_date_from'] }}/{{ $row['audit_date_to'] }}" class="btn btn-info btn-sm" style="width: 70px;"><i class="fas fa-search"></i></a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center font-responsive text-uppercase text-muted p-2">No record(s) found</td>
        </tr>
        @endforelse
    </tbody>
</table>

<div class="float-left m-2">Total: <b>{{ $list->total() }}</b></div>
<div class="float-right m-2" id="inventory-audit-history-pagination">{{ $list->links('pagination::bootstrap-4') }}</div>