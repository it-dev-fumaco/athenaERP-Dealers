@extends('layout', [
    'namePage' => 'Inventory Audit List',
    'activePage' => 'dashboard',
])

@section('content')
<div class="content">
	<div class="content-header p-0">
        <div class="container">
            <div class="row pt-1">
                <div class="col-md-12 p-0 m-0">
                    <div class="card card-lightblue">
                        @if (count($pending) > 0)
                        <div class="card-header text-center p-2">
                            <span class="font-weight-bolder d-block text-uppercase" style="font-size: 11pt;">Inventory Audit List</span>
                        </div>
                        <div class="card-body p-1">
                            <div class="p-2">
                                <span class="text-center mt-1 mb-2 d-block font-responsive text-uppercase">Pending for Submission</span>
                                @forelse ($pending as $store => $row)
                                @if(count($row) > 0)
                                <span class="d-block m-2 font-weight-bold font-responsive text-left">{{ $store }}</span>
                                @foreach ($row as $pcia)
                                <div class="d-flex flex-row border-top justify-content-between align-items-center">
                                    <div class="p-1 font-responsive ml-2">
                                        @if (!$pcia['beginning_inventory_date'])
                                        <span class="d-block text-uppercase text-muted">- Create beginning inventory -</span>
                                        @else
                                        <span class="d-block {{ $pcia['is_late'] ? 'text-danger' : '' }}">{{ $pcia['duration'] }} </span>
                                        @endif
                                    </div>
                                    <div class="p-1 font-responsive">
                                        @if (!$pcia['beginning_inventory_date'])
                                        <a href="/beginning_inventory" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Create</a>
                                        @else
                                        <a href="/view_inventory_audit_form/{{ $store }}/{{ $pcia['today'] }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Create</a>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                                @endif
                                @empty
                                <div class="d-block text-center font-responsive m-0 text-uppercase text-muted border-top border-bottom pb-2 pt-2">No record(s) found</div>
                                @endforelse
                            </div>
                        </div>
                        @endif
                        <div class="card-header text-center p-2 bg-lightblue border-0 rounded-0">
                            <span class="font-weight-bolder d-block text-uppercase" style="font-size: 11pt;">Inventory Audit History</span>
                        </div>
                        <div class="card-body p-1">
                            <form id="inventory-audit-history-form" method="GET">
                                <div class="d-flex flex-row align-items-center mt-2">
                                    <div class="p-0 col-8">
                                        <select class="form-control form-control-sm inventory-audit-history-filter" name="store">
                                            <option value="">Select Store</option>
                                            @foreach ($assigned_consignment_stores as $assigned_store)
                                            <option value="{{ $assigned_store }}">{{ $assigned_store }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="p-1 col-3">
                                        <select class="form-control form-control-sm inventory-audit-history-filter" name="year">
                                            @foreach ($select_year as $year)
                                            <option value="{{ $year }}" {{ date('Y') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="p-0 col-1">
                                        <a href="#" class="btn btn-sm btn-secondary inventory-audit-history-refresh"><i class="fas fa-sync"></i></a>
                                    </div>
                                </div>
                            </form>
                            <div id="submitted-inventory-audit-el" class="p-2"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	</div>
</div>
@endsection

@section('script')
<script>
    $(function () {
        $(document).on('change', '.inventory-audit-history-filter', function(e) {
            e.preventDefault();
            loadSubmittedInventoryAudit();
        });

        $(document).on('click', '.inventory-audit-history-refresh', function(e) {
            e.preventDefault();
            loadSubmittedInventoryAudit();
        });

        loadSubmittedInventoryAudit();
        function loadSubmittedInventoryAudit(page) {
			$.ajax({
				type: "GET",
				url: "/submitted_inventory_audit?page=" + page,
                data: $('#inventory-audit-history-form').serialize(),
				success: function (response) {
                    $('#submitted-inventory-audit-el').html(response);
				}
			});
		}

        $(document).on('click', '#submitted-inventory-audit-list-pagination a', function(event){
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            loadSubmittedInventoryAudit(page);
        });

        
    });
</script>
@endsection