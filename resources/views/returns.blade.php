@extends('layout', [
    'namePage' => 'Sales Returns',
    'activePage' => 'returns',
	'nameDesc' => 'Incoming'
])

@section('content')
<div class="content" ng-app="myApp" ng-controller="stockCtrl" id="anglrCtrl">
	<div class="content-header pt-0">
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-12">
					<div class="card card-primary card-outline">
						<div class="card-header p-0 pt-1 border-bottom-0">
							<div class="row m-1">
								<div class="col-xl-4 d-md-none d-lg-none d-xl-inline-block">
									<h5 class="card-title m-1 font-weight-bold">Sales Returns</h5>
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
									   <span class="badge bg-info" style="font-size: 12pt;">@{{ return_filtered.length + mr_ret_filtered.length}}</span>
									</div>
								</div>
							</div>
						</div>
						<div class="alert m-3 text-center" ng-show="custom_loading_spinner_1">
							<h5 class="m-0"><i class="fas fa-sync-alt fa-spin"></i> <span class="ml-2">Loading ...</span></h5>
						</div>
						<div class="card-body p-0">
							<div class="table-responsive p-0">
								<table class="table table-hover dashboard-table" style="font-size: 10pt;">
									<col style="width: 17%;"><!-- Transaction -->
									<col style="width: 43%;"><!-- Item Description -->
									<col style="width: 15%;"><!-- Qty -->
									<col style="width: 15%;"><!-- Ref. No. -->
									<col style="width: 10%;"><!-- Actions -->
									<thead>
										<tr>
											<th scope="col" class="text-center">Transaction</th>
											<th scope="col" class="text-center d-lg-none">Details</th>
											<th scope="col" class="text-center d-none d-lg-table-cell">Item Description</th>
											<th scope="col" class="text-center d-none d-lg-table-cell">Qty</th>
											<th scope="col" class="text-center d-none d-lg-table-cell">Ref. No.</th>
											<th scope="col" class="text-center d-none d-lg-table-cell">Actions</th>
										</tr>
									</thead>
									<tbody>
										<tr ng-repeat="r in mr_ret_filtered = (mr_ret | filter:searchText | filter: fltr | orderBy: ['status', 'transaction_date'])">
										  	<td class="text-center">
											  	<span class="d-block font-weight-bold">@{{ r.creation }}</span>
											  	<small class="d-block mt-1">@{{ r.name }}</small>
												<div class="d-block d-lg-none">
													<img src="dist/img/icon.png" class="img-circle checkout update-item" ng-hide="r.reference_doc == 'delivery_note'" data-id="@{{ r.c_name }}">
													<img src="dist/img/icon.png" class="img-circle checkout update-item-return" ng-hide="r.reference_doc == 'stock_entry'" data-id="@{{ r.c_name }}">
												</div>
											</td>
										  	<td class="text-justify">
												<div class="d-block font-weight-bold">
													@{{ r.item_code }}
													<span class="badge badge-success" ng-if="r.status === 'Returned'">@{{ r.status }}</span>
													<span class="badge badge-warning" ng-if="r.status === 'For Checking'">@{{ r.status }}</span>
													<span class="badge badge-warning" ng-if="r.status === 'For Return'">@{{ r.status }}</span>
													<i class="fas fa-arrow-right ml-3 mr-2"></i> @{{ r.t_warehouse }}
												</div>
												<span class="d-block">@{{ r.description }}</span>
												<small class="d-none d-lg-block mt-2" ng-hide="r.owner == null"><b>Requested by:</b> @{{ r.owner }}</small>
												<div class="table-reponsive d-block d-lg-none">
													<table class="table">
														<tr>
															<td class="p-1"><b>Requested by:</b></td>
															<td class="p-1">@{{ r.owner }}</td>
														</tr>
														<tr>
															<td class="p-1"><b>Qty:</b></td>
															<td class="p-1">@{{ r.transfer_qty }}</td>
														</tr>
														<tr>
															<td class="p-1"><b>Ref. No.:</b></td>
															<td class="p-1">@{{ r.sales_order_no }}</td>
														</tr>
														<tr>
															<td colspan=2 class="text-center p-1">@{{ r.so_customer_name }}</td>
														</tr>
													</table>
												</div>
											</td>
											<td class="text-center d-none d-lg-table-cell" style="font-size: 14pt;">@{{ r.transfer_qty }}</td>
											<td class="text-center d-none d-lg-table-cell">
												<span class="d-block">@{{ r.sales_order_no }}</span>
												<small>@{{ r.so_customer_name }}</small>
											</td>
											<td class="text-center d-none d-lg-table-cell">
												<img src="dist/img/icon.png" class="img-circle checkout update-item" ng-hide="r.reference_doc == 'delivery_note'" data-id="@{{ r.c_name }}">
												<img src="dist/img/icon.png" class="img-circle checkout update-item-return" ng-hide="r.reference_doc == 'stock_entry'" data-id="@{{ r.c_name }}">
											</td>
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
<div class="modal fade" id="ste-modal">
	<form method="POST" action="/submit_transaction">
		@csrf
		<div class="modal-dialog" style="min-width: 35% !important;"></div>
	</form>
</div>
<div class="modal fade" id="dr-modal">
	<form method="POST" action="/submit_dr_sales_return">
		@csrf
		<div class="modal-dialog" style="min-width: 35% !important;"></div>
	</form>
</div>
@endsection
@section('script')
<script>
	$(document).ready(function(){
		$.ajaxSetup({
			headers: {
			  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

		$(document).on('click', '.update-item', function(){
			var id = $(this).data('id');
			$.ajax({
				type: 'GET',
				url: '/get_ste_details/' + id,
				success: function(response){
					$('#ste-modal').modal('show');
					$('#ste-modal .modal-dialog').html(response);
				}
			});
		});

		$(document).on('click', '.update-item-return', function(){
			var id = $(this).data('id');
			$.ajax({
				type: 'GET',
				url: '/get_dr_return_details/' + id,
				success: function(response){
					$('#dr-modal').modal('show');
					$('#dr-modal .modal-dialog').html(response);
				}
			});
		});

		$('#dr-modal form').validate({
			rules: {
				barcode: {
					required: true,
				},
          		qty: {
					required: true,
				},
			},
			messages: {
				barcode: {
					required: "Please enter barcode",
				},
				qty: {
					required: "Please enter quantity",
				},
			},
			errorElement: 'span',
			errorPlacement: function (error, element) {
				error.addClass('invalid-feedback');
				element.closest('.form-group').append(error);
			},
			highlight: function (element, errorClass, validClass) {
				$(element).addClass('is-invalid');
			},
			unhighlight: function (element, errorClass, validClass) {
				$(element).removeClass('is-invalid');
			},
			submitHandler: function(form) {
				$.ajax({
					type: 'POST',
					url: $(form).attr('action'),
					data: $(form).serialize(),
					success: function(response){
						if (response.status) {
							showNotification("success", response.message, "fa fa-check");
							angular.element('#anglrCtrl').scope().loadData();
							$('#dr-modal').modal('hide');
						}else{
							showNotification("danger", response.message, "fa fa-info");
						}
					},
					error: function(jqXHR, textStatus, errorThrown) {
					}
				});
			}
		});

		$('#ste-modal form').validate({
			rules: {
				barcode: {
					required: true,
				},
          		qty: {
					required: true,
				},
			},
			messages: {
				barcode: {
					required: "Please enter barcode",
				},
				qty: {
					required: "Please enter quantity",
				},
			},
			errorElement: 'span',
			errorPlacement: function (error, element) {
				error.addClass('invalid-feedback');
				element.closest('.form-group').append(error);
			},
			highlight: function (element, errorClass, validClass) {
				$(element).addClass('is-invalid');
			},
			unhighlight: function (element, errorClass, validClass) {
				$(element).removeClass('is-invalid');
			},
			submitHandler: function(form) {
				$.ajax({
					type: 'POST',
					url: $(form).attr('action'),
					data: $(form).serialize(),
					success: function(response){
						if (response.status) {
							showNotification("success", response.message, "fa fa-check");
							angular.element('#anglrCtrl').scope().loadData();
							$('#ste-modal').modal('hide');
						}else{
							showNotification("danger", response.message, "fa fa-info");
						}
					},
					error: function(jqXHR, textStatus, errorThrown) {
					}
				});
			}
		});

		function showNotification(color, message, icon){
			$.notify({
				icon: icon,
				message: message
			},{
				type: color,
				timer: 500,
				z_index: 1060,
				placement: {
					from: 'top',
					align: 'center'
				}
			});
		}
	});

	var app = angular.module('myApp', []);
	app.controller('stockCtrl', function($scope, $http, $interval, $window, $location) {
        $http.get("/get_parent_warehouses").then(function (response) {
			$scope.wh = response.data.wh;
        });
		
		$scope.loadData = function(){
			$scope.custom_loading_spinner_1 = true;
			$http.get("/get_mr_sales_return").then(function (response) {
				$scope.mr_ret = response.data.mr_return;
				$scope.custom_loading_spinner_1 = false;
			});
		}
	 
		$scope.loadData();
	 });
</script>
@endsection