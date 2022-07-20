
@forelse  ($result as $store => $row)
<span class="d-block m-2 font-weight-bold font-responsive text-center">{{ $store }}</span>
@foreach  ($row as $row1)
<div class="d-flex flex-row border-top justify-content-between align-items-center">
    <div class="p-1 font-responsive ml-2 {{ strtolower($row1['status']) == 'late' ? 'text-danger' : '' }}">
       {{ \Carbon\Carbon::parse($row1['audit_date_from'])->format('M. d, Y') }} - {{ \Carbon\Carbon::parse($row1['audit_date_to'])->format('M. d, Y') }}
    </div>
    <div class="p-1 font-responsive ml-2 text-nowrap">
        {{ '₱ ' . number_format($row1['total_sales'], 2) }}
     </div>
    <div class="p-1 font-responsive">
        <a href="/view_inventory_audit_items/{{ $store }}/{{ $row1['audit_date_from'] }}/{{ $row1['audit_date_to'] }}" class="btn btn-info btn-sm"><i class="fas fa-search"></i></a>
    </div>
</div>
@endforeach
@empty
<div class="d-block text-center font-responsive m-0 text-uppercase text-muted border-top border-bottom pb-2 pt-2">No record(s) found</div>
@endforelse

<div class="float-right m-2" id="submitted-inventory-audit-list-pagination">{{ $query->links('pagination::bootstrap-4') }}</div>