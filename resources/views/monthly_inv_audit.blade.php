@extends('layout', [
    'namePage' => 'Monthly Inventory Audit',
    'activePage' => 'audit_list',
])

@section('content')
<div class="content">
	<div class="content-header pt-0">
		<div class="container-fluid">
			<div class="row pt-3">
				<div class="col-sm-12">
                    <div class="card card-secondary card-outline">
                        <div class="card-body p-0">
                            <form action="/monthly_inventory_audit" method="get">
                                <div class="row">
                                    <div class="col-12 col-xl-2 p-2 p-xl-3">
                                        <input type="text" class="form-control font-responsive" name="search" placeholder="Search" aria-describedby="basic-addon2">
                                    </div>
                                    <div class="col-12 col-xl-2 p-2 p-xl-3">
                                        <select name="store" class="form-control font-responsive">
                                            <option value="" disabled {{ !request('search') ? 'selected' : null }}>Select Store</option>
                                            @foreach ($assigned_consignment_store as $store)
                                                <option value="{{ $store }}">{{ $store }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-xl-2 p-2 p-xl-3">
                                        <input type="text" class="form-control font-responsive" name="date" id="date-selector" value=""/>
                                    </div>
                                    <div class="col-12 col-xl-2 p-2 p-xl-3">
                                        <button class="font-responsive btn btn-outline-secondary" type="submit">Search</button>
                                    </div>
                                    <div class="col-12 col-xl-2 offset-xl-1 p-3">
                                        <a href="/monthly_inv_audit_form" class="font-responsive btn btn-primary float-right">Add Monthly Inventory Audit</a>
                                    </div>
                                </form>
                            </div>
                            <table class="table table-bordered"> 
                                <tr>
                                    <th class="font-responsive">
                                        <span class="d-none d-md-block">Name</span>
                                        <span class="d-block d-md-none text-center">Details</span>
                                    </th>
                                    <th class="font-responsive d-none d-sm-table-cell">Warehouse</th>
                                    <th class="font-responsive d-none d-sm-table-cell">Date</th>
                                    <th class="font-responsive d-none d-sm-table-cell">Employee</th>
                                    <th class="font-responsive">Average Accuracy Rate</th>
                                </tr>
                                @forelse ($inv_audit as $audit)
                                    <tr>
                                        <td>
                                            <span class="font-responsive" style="white-space: nowrap">{{ $audit->name }}</span>
                                            <div class="d-block d-md-none">
                                                <span class="font-responsive"><b>Warehouse:</b> {{ $audit->warehouse }}</span><br>
                                                <span class="font-responsive"><b>Date:</b> {{ \Carbon\Carbon::parse($audit->from)->format('M d, Y h:i A').' - '.\Carbon\Carbon::parse($audit->to)->format('M d, Y h:i A') }}</span><br>
                                                <span class="font-responsive"><b>Employee:</b> {{ $audit->employee_name }}</span>
                                            </div>
                                        </td>
                                        <td class="d-none d-sm-table-cell">{{ $audit->warehouse }}</td>
                                        <td class="d-none d-sm-table-cell">{{ \Carbon\Carbon::parse($audit->from)->format('M d, Y h:i A').' - '.\Carbon\Carbon::parse($audit->to)->format('M d, Y h:i A') }}</td>
                                        <td class="d-none d-sm-table-cell">{{ $audit->employee_name }}</td>
                                        <td>
                                            <span class="font-responsive badge badge-{{ $audit->average_accuracy_rate == 0 ? 'danger' : 'success' }}">{{ number_format($audit->average_accuracy_rate, 2) }}%</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center" colspan=12>No result(s) found.</td>
                                    </tr>
                                @endforelse
                            </table>
                            <div class="mt-3 ml-3 clearfix pagination" style="display: block;">
                                <div class="col-md-4 float-right">
                                    {{ $inv_audit->links() }}
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
        $(document).ready(function() {
            var from_date = "{{ request('date') ? explode(' - ', request('date'))[0] : null }}";
            var to_date = "{{ request('date') ? explode(' - ', request('date'))[1] : null }}";

            $('#date-selector').daterangepicker({
                startDate: from_date ? from_date : moment().subtract(30, 'days'),
                endDate: to_date ? to_date : moment(),
                locale: {
                    format: 'MMM DD, YYYY'
                }
            });
        });
    </script>
@endsection