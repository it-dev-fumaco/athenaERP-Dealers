@extends('layout', [
	'namePage' => 'PO Receipts',
    'activePage' => 'receipts',
	'nameDesc' => 'Incoming'
])

@section('content')
<div class="content" ng-app="myApp" ng-controller="stockCtrl">
	<div class="content-header pt-0">
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-12">
					<div class="card card-purple card-outline">
						<div class="card-header p-0 pt-1 border-bottom-0">
							<div class="row m-1">
								<div class="col-xl-4 d-md-none d-lg-none d-xl-inline-block">
									<h5 class="card-title m-1 font-weight-bold">PO Receipts</h5>
								</div>
								<div class="col-xl-1 col-lg-2 col-md-2">
									<button type="button" class="btn btn-block btn-primary" ng-click="loadData()">
										<i class="fas fa-sync-alt"></i> Refresh
									</button>
								</div>
								<div class="col-xl-3 col-lg-5 col-md-5">
									<div class="form-group">
										<input type="text" class="form-control" placeholder="Search" ng-model="fltr" autofocus>
									</div>
								</div>
								<div class="col-xl-2 col-lg-2 col-md-2">
									<div class="form-group">
										<select class="form-control" ng-model="searchText">
											<option></option>
											<option ng-repeat="y in wh">@{{ y.name }}</option>
										</select>
									</div>
								</div>
								<div class="col-xl-2 col-lg-3 col-md-3">
									<div class="text-center m-1">
									   <span class="font-weight-bold">TOTAL RESULT:</span>
									   <span class="badge bg-info" style="font-size: 12pt;">@{{ mi_filtered.length }}</span>
									</div>
								</div>
							</div>
						</div>
						<div class="alert m-3 text-center" ng-show="custom_loading_spinner">
							<h5 class="m-0"><i class="fas fa-sync-alt fa-spin"></i> <span class="ml-2">Loading ...</span></h5>
						</div>
						<div class="card-body p-0">
							<div class="table-responsive p-0">
								<table class="table table-hover dashboard-table" style="font-size: 10pt;">
									<col style="width: 10%;"><!-- Purchase Receipt -->
									<col style="width: 17%;"><!-- Transaction Date -->
									<col style="width: 43%;"><!-- Item Description -->
									<col style="width: 15%;"><!-- Qty -->
									<col style="width: 15%;"><!-- Ref. No. -->
									<thead>
										<tr>
											<th scope="col" class="text-center">Purchase Receipt</th>
											<th scope="col" class="text-center d-lg-none">Details</th>
											<th scope="col" class="text-center d-none d-lg-table-cell">Transaction Date</th>
											<th scope="col" class="text-center d-none d-lg-table-cell">Item Description</th>
											<th scope="col" class="text-center d-none d-lg-table-cell">Qty</th>
											<th scope="col" class="text-center d-none d-lg-table-cell">Ref. No.</th>
										</tr>
									</thead>
									<tbody>
										<tr ng-repeat="x in mi_filtered = (mi | filter:searchText | filter: fltr)">
											<td class="text-center"><span class="d-block mt-1">@{{ x.parent }}</span></td>
											<td class="text-center">
												<span class="d-block font-weight-bold">@{{ x.creation }}</span>
												<div class="d-block d-lg-none">
													<div class="font-weight-bold">
														{{-- <span class="view-item-details font-weight-bold" data-item-code="@{{ x.item_code }}">@{{ x.item_code }}</span> --}}
														<a href="/get_item_details/@{{ x.item_code }}" target="_blank" style="color: inherit !important">
															<span class="font-weight-bold">@{{ x.item_code }}</span>
														</a>
														<span class="badge badge-success mr-2" ng-if="x.status === 'Received'">@{{ x.status }}</span>
														<span class="badge badge-warning mr-2" ng-if="x.status === 'To Receive'">@{{ x.status }}</span>
														<i class="fas fa-arrow-right ml-2 mr-2"></i> 
														<span>@{{ x.warehouse }}</span>
													</div>
													<span class="d-block">@{{ x.description }}</span>'
													<table class="table">
														<tr>
															<td class="p-1"><b>Part No(s):</b></td>
															<td class="p-1">@{{ x.part_nos }}</td>
														</tr>
														<tr>
															<td class="p-1"><b>Created by:</b></td>
															<td class="p-1">@{{ x.owner }}</td>
														</tr>
														<tr>
															<td class="p-1"><b>Qty:</b></td>
															<td class="p-1">@{{ x.qty | number:2 }}</td>
														</tr>
														<tr>
															<td class="p-1"><b>Available Stock:</b></td>
															<td class="p-1">@{{ x.available_qty | number:2 }}</td>
														</tr>
														<tr>
															<td class="p-1"><b>Ref. No.:</b></td>
															<td class="p-1">@{{ x.ref_no }}</td>
														</tr>
													</table>
												</div>
											</td>
											<td class="text-justify d-none d-lg-table-cell">
												<div class="d-block font-weight-bold">
													<span class="view-item-details font-weight-bold" data-item-code="@{{ x.item_code }}">@{{ x.item_code }}</span>
													<span class="badge badge-success mr-2" ng-if="x.status === 'Received'">@{{ x.status }}</span>
													<span class="badge badge-warning mr-2" ng-if="x.status === 'To Receive'">@{{ x.status }}</span>
													<i class="fas fa-arrow-right ml-2 mr-2"></i> 
													<span>@{{ x.warehouse }}</span>
												</div>
												<span class="d-block">@{{ x.description }}</span>
												<span class="d-block mt-3" ng-hide="x.part_nos == ''"><b>Part No(s):</b> @{{ x.part_nos }}</span>
												<small class="d-block mt-2" ng-hide="x.owner == null"><b>Created by:</b> @{{ x.owner }}</small>
											</td>
											<td class="text-center d-none d-lg-table-cell">
												<span class="d-block" style="font-size: 14pt;">@{{ x.qty | number:2 }}</span>
												<span class="d-block mt-3" style="font-size: 10pt;">Available Stock:</span>
												<span class="badge badge-@{{ x.available_qty > 0 ? 'success' : 'danger' }}">@{{ x.available_qty | number:2 }}</span>
											</td>
											<td class="text-center d-none d-lg-table-cell">@{{ x.ref_no }}</td>
										</tr>
									</tbody>
								</table>
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
	var app = angular.module('myApp', []);
	app.controller('stockCtrl', function($scope, $http, $interval, $window, $location) {
		$http.get("/get_parent_warehouses").then(function (response) {
			$scope.wh = response.data.wh;
		});
		
		$scope.loadData = function(){
			$scope.custom_loading_spinner = true;
			$http.get("/receipts?arr=1").then(function (response) {
				$scope.mi = response.data.records;
				$scope.custom_loading_spinner = false;
			});
		}
		
		$scope.loadData();
	});
</script>
@endsection