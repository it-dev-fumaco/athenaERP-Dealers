@extends('layout', [
	'namePage' => 'Deliveries',
	'activePage' => 'picking-slip',
	'nameDesc' => 'Outgoing'
])

@section('content')
<div class="content" ng-app="myApp" ng-controller="stockCtrl" id="anglrCtrl">
	<div class="content-header pt-0">
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-12">
					<div class="card card-navy card-outline">
						<div class="card-header p-0 pt-1 border-bottom-0">
							<div class="row m-1">
								<div class="col-xl-4 d-md-none d-lg-none d-xl-inline-block">
									<h5 class="card-title m-1 font-weight-bold">Deliveries</h5>
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
									   <span class="badge bg-info" style="font-size: 12pt;">@{{ ps_filtered.length }}</span>
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
									<col style="width: 15%;"><!-- Transaction -->
									<col style="width: 40%;"><!-- Item Description -->
									<col style="width: 10%;"><!-- Qty -->
									<col style="width: 10%;"><!-- Delivery Date -->
									<col style="width: 15%;"><!-- Ref. No. -->
									<col style="width: 10%;"><!-- Actions -->
									<thead>
										<tr>
											<th scope="col" class="text-center">Transaction</th>
											<th scope="col" class="text-center d-lg-none">Details</th>
											<th scope="col" class="text-center d-none d-lg-table-cell">Item Description</th>
											<th scope="col" class="text-center d-none d-lg-table-cell">Qty</th>
											<th scope="col" class="text-center d-none d-lg-table-cell">Delivery Date</th>
											<th scope="col" class="text-center d-none d-lg-table-cell">Ref. No.</th>
											<th scope="col" class="text-center d-none d-lg-table-cell">Actions</th>
										</tr>
									</thead>
									<tbody>
										<tr ng-repeat="x in ps_filtered = (ps | filter:searchText | filter: fltr)">
											<td class="text-center">
												<span class="d-block font-weight-bold">@{{ x.creation }}</span>
												<small class="d-block mt-1">@{{ x.name }}</small>
												<div class="d-block d-lg-none">
													<img src="dist/img/icon.png" ng-hide="x.type != 'picking_slip'" class="img-circle checkout update-ps"  data-id="@{{ x.id }}">
													<img src="dist/img/icon.png"  ng-hide="x.type != 'stock_entry'" class="img-circle update-item checkout" data-id="@{{ x.id }}">
												</div>
											</td>
											<td class="text-justify">
												<div class="d-block font-weight-bold">
													{{-- <span class="view-item-details font-weight-bold" data-item-code="@{{ x.item_code }}">@{{ x.item_code }}</span> --}}
													<a href="/get_item_details/@{{ x.item_code }}" target="_blank" style="color: inherit !important">
														<span class="font-weight-bold">@{{ x.item_code }}</span>
													</a>
													<span class="badge badge-success mr-2" ng-if="x.status === 'Issued'">@{{ x.status }}</span>
													<span class="badge badge-warning mr-2" ng-if="x.status === 'For Checking'">@{{ x.status }}</span>
													<i class="fas fa-arrow-right ml-2 mr-2"></i> 
													<span>@{{ x.warehouse }}</span>
												</div>
												<span class="d-block">@{{ x.description }}</span>
												<span class="d-none d-lg-block mt-3" ng-hide="x.part_nos == ''"><b>Part No(s):</b> @{{ x.part_nos }}</span>
												<small class="d-none d-lg-block mt-2" ng-hide="x.owner == null"><b>Requested by:</b> @{{ x.owner }}</small>
												<div class="table-responsive d-block d-lg-none">
													<table class="table">
														<tr>
															<td class="p-1"><b>Part No(s):</b></td>
															<td class="p-1">@{{ x.part_nos }}</td>
														</tr>
														<tr>
															<td class="p-1"><b>Requested by:</b></td>
															<td class="p-1">@{{ x.owner }}</td>
														</tr>
														<tr>
															<td class="p-1"><b>Qty:</b></td>
															<td class="p-1">@{{ x.qty | number:2 }} @{{ x.stock_uom }}</td>
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
															<td class="p-1">@{{ x.sales_order }}</td>
														</tr>
														<tr>
															<td colspan=2 class="text-center p-1">@{{ x.customer }}</td>
														</tr>
														<tr>
															<td colspan=2 class="text-center p-1">@{{ x.classification }}</td>
														</tr>
													</table>
												</div>
											</td>
											<td class="text-center d-none d-lg-table-cell">
												<span class="d-block" style="font-size: 14pt;">@{{ x.qty | number:2 }}</span>
												<span class="d-block">@{{ x.stock_uom }}</span>
											</td>
											<td class="text-center d-none d-lg-table-cell">
												<span class="badge badge-danger" ng-if="x.delivery_status == 'late'" style="font-size: 10pt;">@{{ x.delivery_date }}</span>
												<span ng-if="x.delivery_status == null">@{{ x.delivery_date }}</span>
											</td>
											<td class="text-center d-none d-lg-table-cell">
												<span class="d-block">@{{ x.sales_order }}</span>
												<span class="d-none">@{{ x.delivery_note }}</span>
												<small class="d-block mt-2">@{{ x.customer }}</small>
												<small class="d-block mt-3">@{{ x.classification }}</small>
											</td>
											<td class="text-center d-none d-lg-table-cell">
												<img src="dist/img/icon.png" ng-hide="x.type != 'picking_slip'" class="img-circle checkout update-ps"  data-id="@{{ x.id }}">
												<img src="dist/img/icon.png"  ng-hide="x.type != 'stock_entry'" class="img-circle update-item checkout" data-id="@{{ x.id }}">
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
<div class="modal fade" id="ps-modal">
	<form method="POST" action="/checkout_picking_slip_item">
		@csrf
		<div class="modal-dialog" style="min-width: 35% !important;"></div>
	</form>
</div>
@endsection
@section('script')
<script>
	$(document).ready(function(){
		$(document).on('click', '#btn-deduct-res', function(e){
			e.preventDefault();
			$('#ste-modal input[name="deduct_reserve"]').val(1);
      		$('#ste-modal form').submit();
		});

		$(document).on('click', '#btn-check-out', function(e){
			e.preventDefault();
			$('#ste-modal input[name="deduct_reserve"]').val(0);
      		$('#ste-modal form').submit();
		});

		$(document).on('click', '#btn-deduct-res-1', function(e){
			e.preventDefault();
			$('#ps-modal input[name="deduct_reserve"]').val(1);
      		$('#ps-modal form').submit();
		});

		$(document).on('click', '#btn-check-out-1', function(e){
			e.preventDefault();
			$('#ps-modal input[name="deduct_reserve"]').val(0);
      		$('#ps-modal form').submit();
		});
		
		$(document).on('click', '.update-ps', function(){
			var id = $(this).data('id');
			$.ajax({
				type: 'GET',
				url: '/get_ps_details/' + id,
				success: function(response){
					$('#ps-modal').modal('show');
					$('#ps-modal .modal-dialog').html(response);
				}
			});
		});

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

		$('#ps-modal form').validate({
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
							$('#ps-modal').modal('hide');
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
        $http.get("/view_deliveries?arr=1").then(function (response) {
          $scope.ps = response.data.picking;
          $scope.custom_loading_spinner_1 = false;
        });
      }
    
      $scope.loadData();
	 });
</script>
@endsection