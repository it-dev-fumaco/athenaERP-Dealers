@extends('layout', [
    'namePage' => 'Internal Transfer',
    'activePage' => 'material-transfer',
    'nameDesc' => 'Incoming'
])

@section('content')
<div class="content" ng-app="myApp" ng-controller="stockCtrl" id="anglrCtrl">
	<div class="content-header pt-0">
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-12">
					<div class="card card-gray-dark card-outline">
						<div class="card-header p-0 pt-1 border-bottom-0">
							<div class="row m-1">
								<div class="col-xl-4 d-md-none d-lg-none d-xl-inline-block">
									<h5 class="card-title m-1 font-weight-bold">Internal Transfer</h5>
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
						<div class="alert m-3 text-center" ng-show="custom_loading_spinner">
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
										<tr ng-repeat="x in mt_filtered = (mt | filter:searchText | filter: fltr | orderBy: ['status', 'transaction_date'])">
											<td class="text-center">
												<span class="d-block font-weight-bold">@{{ x.creation }}</span>
												<small class="d-block mt-1">@{{ x.work_order }} - @{{ x.parent }}</small>
												<div class="d-block d-lg-none">
													<img src="dist/img/icon.png" class="img-circle update-item checkout" data-id="@{{ x.name }}">
												</div>
											</td>
											<td class="text-justify">
												<div class="d-block font-weight-bold">
													<a href="/get_item_details/@{{ x.item_code }}" target="_blank" style="color: inherit !important">
														<span class="font-weight-bold">@{{ x.item_code }}</span>
													</a>
													<span class="badge badge-success mr-2" ng-if="x.status === 'Issued'">@{{ x.status }}</span>
													<span class="badge badge-warning mr-2" ng-if="x.status === 'For Checking'">@{{ x.status }}</span>
													<span>@{{ x.s_warehouse }}</span>
													<i class="fas fa-arrow-right ml-2 mr-2"></i> 
													<span>@{{ x.t_warehouse }}</span>
												</div>
												<span class="d-block">@{{ x.description }}</span>
												<span class="d-none d-lg-block mt-3" ng-hide="x.part_nos == ''"><b>Part No(s):</b> @{{ x.part_nos }}</span>
												<small class="d-none d-lg-block mt-2" ng-hide="x.owner == null"><b>Requested by:</b> @{{ x.owner }}</small>
												<div class="d-block d-lg-none">
													<table class="table font-responsive">
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
															<td class="p-1">@{{ x.qty | number:2 }}</td>
														</tr>
														<tr>
															<td class="p-1"><b>Available Stock:</b></td>
															<td class="p-1"><span class="badge badge-@{{ x.available_qty > 0 ? 'success' : 'danger' }}">@{{ x.available_qty | number:2 }}</span></td>
														</tr>
														<tr>
															<td class="p-1"><b>Ref. No.</b></td>
															<td class="p-1" style="white-space: nowrap !important">@{{ x.sales_order_no }}</td>
														</tr>
														<tr>
															<td colspan=2 class="p-1 text-center">
																@{{ x.transfer_as }}
															</td>
														</tr>
													</table>
												</div>
											</td>
											<td class="text-center d-none d-lg-table-cell">
												<span class="d-block" style="font-size: 14pt;">@{{ x.qty | number:2 }}</span>
												<span class="d-block mt-3" style="font-size: 10pt;">Available Stock:</span>
												<span class="badge badge-@{{ x.available_qty > 0 ? 'success' : 'danger' }}">@{{ x.available_qty | number:2 }}</span>
											</td>
											<td class="text-center d-none d-lg-table-cell">
												<span class="d-block">@{{ x.sales_order_no }}</span>
												<span class="d-block">@{{ x.transfer_as }}</span>
											</td>
											<td class="text-center d-none d-lg-table-cell">
												<img src="dist/img/icon.png" class="img-circle update-item checkout" data-id="@{{ x.name }}">
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
@endsection

@section('script')
<script>
	
	$(document).ready(function(){
		$.ajaxSetup({
			headers: {
			  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		
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
		$scope.custom_loading_spinner = true;
			$http.get("/material_transfer?arr=1").then(function (response) {
				$scope.mt = response.data.records;
				$scope.custom_loading_spinner = false;
			});
		}
		
		$scope.loadData();
	});
</script>
@endsection