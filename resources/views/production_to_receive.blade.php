@extends('layout', [
	'namePage' => 'Feedback - To Receive',
    'activePage' => 'feedback',
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
									<h5 class="card-title m-1 font-weight-bold">Feedback List</h5>
								</div>
								<div class="col-xl-1 col-lg-2 col-md-2">
									<button type="button" class="btn btn-block btn-primary" ng-click="loadData()"><i class="fas fa-sync-alt"></i> Refresh</button>
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
									   <span class="badge bg-info" style="font-size: 12pt;">@{{ mt_filtered.length }}</span>
									</div>
								</div>
							</div>
						</div>
						<div class="alert m-3 text-center" ng-show="custom_loading_spinner_1">
							<h5 class="m-0"><i class="fas fa-sync-alt fa-spin"></i> <span class="ml-2">Loading ...</span></h5>
						</div>
						<div class="table-responsive p-0">
							<table class="table table-hover dashboard-table" style="font-size: 10pt;">
								<col style="width: 17%;">
								<col style="width: 33%;">
								<col style="width: 15%;">
								<col style="width: 10%;">
								<col style="width: 15%;">
								<col style="width: 10%;">
								<thead>
									<tr>
										<th scope="col" class="text-center">Transaction</th>
										<th scope="col" class="text-center d-lg-none">Details</th>
										<th scope="col" class="text-center d-none d-lg-table-cell">Item Description</th>
										<th scope="col" class="text-center d-none d-lg-table-cell">Qty to Manufacture</th>
										<th scope="col" class="text-center d-none d-lg-table-cell">Qty to Receive</th>
										<th scope="col" class="text-center d-none d-lg-table-cell">Delivery Date</th>
										<th scope="col" class="text-center d-none d-lg-table-cell">Ref. No.</th>
										<th scope="col" class="text-center d-none d-lg-table-cell">Actions</th>
									</tr>
								</thead>
								<tbody>
									<tr ng-repeat="x in mt_filtered = (pr | filter:searchText | filter: fltr)">
										<td class="text-center">
											<span class="d-block font-weight-bold">@{{ x.created_at }}</span>
											<small class="d-block mt-1 production-order">@{{ x.production_order }}</small>
											<div class="d-block d-lg-none">
												<img src="dist/img/check.png" class="img-circle checkout feedback-details" data-id="@{{ x.production_order }}">
											</div>
										</td>
										<td class="text-justify">
											<div class="d-block font-weight-bold">
												{{-- <span class="view-item-details item-code" data-item-code="@{{ x.item_code }}"><b>@{{ x.item_code }}</b></span> --}}
												<a href="/get_item_details/@{{ x.item_code }}" target="_blank" style="color: inherit !important">
													<span class="item-code"><b>@{{ x.item_code }}</b></span>
												</a>
												<span class="badge badge-warning">To Receive</span>
												<i class="fas fa-arrow-right ml-3 mr-2"></i> <span class="target-warehouse">@{{ x.fg_warehouse }}</span>
											</div>
											<span class="d-block description">@{{ x.description }}</span>
											<small class="d-none d-lg-block mt-2" ng-hide="x.owner == null"><b>Requested by:</b> @{{ x.owner }}</small>
											<div class="d-block d-lg-none">
												<table class="table">
													<tr>
														<td class="p-1"><b>Requested by:</b></td>
														<td class="p-1">@{{ x.owner }}</td>
													</tr>
													<tr>
														<td class="p-1"><b>Qty to Manufacture:</b></td>
														<td class="p-1"><span class="qty">@{{ x.qty_to_manufacture }}</span></td>
													</tr>
													<tr>
														<td class="p-1"><b>Qty to Receive</b></td>
														<td class="p-1"><span class="badge badge-success qty">@{{ x.qty_to_receive }}</span></td>
													</tr>
													<tr>
														<td class="p-1"><b>Delivery Date:</b></td>
														<td class="p-1">
															<span class="badge badge-danger" ng-if="x.delivery_status == 'late'" style="font-size: 10pt;">@{{ x.delivery_date }}</span>
															<span ng-if="x.delivery_status == null">@{{ x.delivery_date }}</span>
														</td>
													</tr>
													<tr>
														<td class="p-1"><b>Ref. No.:</b></td>
														<td class="p-1">
															<span class="reference-no">@{{ x.sales_order_no }}@{{ x.material_request }}</span>
														</td>
													</tr>
													<tr>
														<td colspan=2 class="text-center p-1">
															<small class="customer">@{{ x.customer }}</small>
														</td>
													</tr>
												</table>
											</div>
										</td>
										<td class="text-center d-none d-lg-table-cell">
											<span class="qty" style="font-size: 12pt;"><b>@{{ x.qty_to_manufacture }}</b></span>
										</td>
										<td class="text-center d-none d-lg-table-cell">
											<span class="badge badge-success qty" style="font-size: 12pt;"><b>@{{ x.qty_to_receive }}</b></span>
										</td>
										<td class="text-center d-none d-lg-table-cell">
											<span class="badge badge-danger" ng-if="x.delivery_status == 'late'" style="font-size: 10pt;">@{{ x.delivery_date }}</span>
											<span ng-if="x.delivery_status == null">@{{ x.delivery_date }}</span>
										</td>
										<td class="text-center d-none d-lg-table-cell">
											<span class="reference-no d-block">@{{ x.sales_order_no }}@{{ x.material_request }}</span>
											<small class="customer d-block">@{{ x.customer }}</small>
										</td>
										<td class="text-center d-none d-lg-table-cell">
											<img src="dist/img/check.png" class="img-circle checkout feedback-details" data-id="@{{ x.production_order }}">
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

<div class="modal fade" id="receive-item-modal">
	<form method="POST" action="/create_feedback" autocomplete="off">
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

		$(document).on('click', '.feedback-details', function(){
			var id = $(this).data('id');
			$.ajax({
				type: 'GET',
				url: '/feedback_details/' + id,
				success: function(response){
					$('#receive-item-modal').modal('show');
					$('#receive-item-modal .modal-dialog').html(response);
				}
			});
		});

		$('#receive-item-modal form').validate({
			rules: {
				barcode: {
					required: true,
				},
          		fg_completed_qty: {
					required: true,
				},
			},
			messages: {
				barcode: {
					required: "Please enter barcode",
				},
				fg_completed_qty: {
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
							$('#receive-item-modal').modal('hide');
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
			$http.get("/production_to_receive?arr=1").then(function (response) {
				$scope.pr = response.data.records;
				$scope.custom_loading_spinner_1 = false;
			});
		}
	 
		$scope.loadData();
	});
</script>
@endsection