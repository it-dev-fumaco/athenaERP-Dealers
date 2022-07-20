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
                    <div class="row">
                        <div class="col-2">
                            <div style="margin-bottom: -43px;">
                                <a href="/" class="btn btn-secondary" style="width: 80px;"><i class="fas fa-arrow-left"></i></a>
                            </div>
                        </div>
                        <div class="col-8 col-lg-8 p-0">
                            <h4 class="text-center font-weight-bold m-2 text-uppercase">Inventory Audit List</h4>
                        </div>
                    </div>
                    <div class="card card-secondary card-outline">
                        <div class="card-body p-2">
                            <ul class="nav nav-pills">
                                <li class="nav-item">
                                    <a class="nav-link active font-responsive" id="pending-tab" data-toggle="pill" href="#pending-content" role="tab" href="#">Pending for Submission</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link font-responsive" id="inventory-audit-history-tab" data-toggle="pill" href="#inventory-audit-history-content" role="tab" href="#">Inventory Audit History</a>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="pending-content" role="tabpanel" aria-labelledby="pending-tab">
                                    <form action="#" id="pending-inventory-audit-filter-form">
                                        <div class="row p-1 mt-1 mb-1">
                                            <div class="col-10 col-lg-6">
                                                <select class="form-control" name="store" id="consignment-store-select">
                                                    <option value="">Select Store</option>
                                                </select>
                                            </div>
                                            <div class="col-2 p-0">
                                                <a href="#" class="btn btn-secondary d-inline-block float-left ml-1 consignment-store-refresh"><i class="fas fa-undo"></i></a>
                                            </div>
                                        </div>
                                    </form>
                                    <div id="beginning-inventory-list-el"></div>
                                </div>
                                <div class="tab-pane fade" id="inventory-audit-history-content" role="tabpanel" aria-labelledby="inventory-audit-history-tab">
                                    <form id="inventory-audit-history-form" method="GET">
                                        <div class="d-flex flex-row align-items-center mt-2">
                                            <div class="p-1 col-6">
                                                <select class="form-control inventory-audit-history-filter store" name="store" id="consignment-store-select-history">
                                                    <option value="">Select Store</option>
                                                </select>
                                            </div>
                                            <div class="p-1 col-4 col-lg-2">
                                                <select class="form-control inventory-audit-history-filter year" name="year">
                                                    @foreach ($select_year as $year)
                                                    <option value="{{ $year }}" {{ date('Y') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="p-1 col-2">
                                                <a href="#" class="btn btn-secondary inventory-audit-history-refresh"><i class="fas fa-undo"></i></a>
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

        $(document).on('change', "#consignment-store-select", function(e) {
            e.preventDefault();
            get_pending_inventory_audit();
        });

        $(document).on('click', '.inventory-audit-history-refresh', function(e) {
            e.preventDefault();
            $(".inventory-audit-history-filter.store").empty().trigger('change');
            $('.inventory-audit-history-filter.year').val('{{ Carbon\Carbon::now()->format("Y") }}').trigger('change');
            loadSubmittedInventoryAudit();
        });

        $(document).on('click', '.consignment-store-refresh', function(e) {
            e.preventDefault();
            $("#consignment-store-select").empty().trigger('change');
        });

        get_pending_inventory_audit();
        function get_pending_inventory_audit(page) {
            $.ajax({
                type: "GET",
                url: "/pending_submission_inventory_audit?page=" + page,
                data: $('#pending-inventory-audit-filter-form').serialize(),
                success: function (data) {
                    $('#beginning-inventory-list-el').html(data);
                }
            });
        }

        loadSubmittedInventoryAudit();
        function loadSubmittedInventoryAudit(page) {
			$.ajax({
				type: "GET",
				url: "/submitted_inventory_audit?page=" + page ,
                data: $('#inventory-audit-history-form').serialize(),
				success: function (response) {
                    $('#submitted-inventory-audit-el').html(response);
				}
			});
		}

        $('#consignment-store-select').select2({
            placeholder: "Select Store",
            allowClear: true,
            ajax: {
                url: '/consignment_stores',
                method: 'GET',
                dataType: 'json',
                data: function (data) {
                    return {
                        q: data.term // search term
                    };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });

        
        $('#consignment-store-select-history').select2({
            placeholder: "Select Store",
            allowClear: true,
            ajax: {
                url: '/consignment_stores',
                method: 'GET',
                dataType: 'json',
                data: function (data) {
                    return {
                        q: data.term // search term
                    };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });

        $(document).on('click', '#inventory-audit-history-pagination a', function(event){
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            loadSubmittedInventoryAudit(page);
        });
    });
</script>
@endsection