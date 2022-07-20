<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ERP Inventory</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  {{--  <!-- Google Font: Source Sans Pro -->  --}}
  <link rel="stylesheet" href="{{ asset('/updated/custom/font.css') }}">
  {{--  <!-- Font Awesome Icons -->  --}}
  <link rel="stylesheet" href="{{ asset('/updated/plugins/fontawesome-free/css/all.min.css') }}">
  {{--  <!-- Ekko Lightbox -->  --}}
  <link rel="stylesheet" href="{{ asset('/updated/plugins/ekko-lightbox/ekko-lightbox.css') }}">
  {{--  <!-- Theme style -->  --}}
  <link rel="stylesheet" href="{{ asset('/updated/dist/css/adminlte.min.css') }}">
  <!-- daterange picker -->
  <link rel="stylesheet" href="{{ asset('/updated/plugins/daterangepicker/daterangepicker-bs3.css') }}">
  <link rel="stylesheet" href="{{ asset('/updated/plugins/datepicker/datepicker3.css') }}">
</head>
<body class="hold-transition layout-top-nav">
	<div class="wrapper">
		<nav class="main-header navbar navbar-expand-md navbar-light navbar-navy">
			<div class="container-fluid">
				<form role="search" method="GET" action="/">
					<div class="d-flex flex-grow-1">
						<div class="col-md-3 text-center">
							<a href="/" class="navbar-brand">
								<span class="brand-text text-white" style="font-size: 28pt;"><b>ERP</b>Inventory</span>
							</a>
						</div>
						<div class="col-md-6">
							<div class="row">
								<div class="col-md-1 div-reset" style="min-height: 40px;">
									<button class="btn btn-default d-inline-block" type="button" onclick="document.getElementById('searchid').value = ''">
										<i class="fas fa-sync"></i>
									</button>
								</div>
								<div class="col-md-7 div-search-box" style="min-height: 40px;">
									<input type="text" class="form-control" placeholder="Search Item..." name="searchString" id="searchid" autocomplete="off" value="{{ request('searchString') }}">
									<div id="suggesstion-box"></div>
								</div>
								<div class="col-md-4">
									<div class="row">
										<div class="col-md-6 div-cb-remove text-white" style="min-height: 40px;">
											<label style="font-size: 8pt;">
												<div class="d-inline-block">
													<input type="checkbox" name="check_qty" {{ (request('check_qty')) ? 'checked' : null }} style="width: 15px; height: 15px;">
												</div>
												<div style="width: 70%;" class="cb_remove_zero_qty d-inline-block text-center">Remove zero-qty items</div>
											</label>
										</div>
										<div class="col-md-6 div-search" style="min-height: 40px;">
											<button class="btn btn-block btn-default" type="submit" name="search">
												<i class="fas fa-search"></i> Search
											</button>
										</div>
									</div>
								</div>
								<div class="col-md-4 div-select1" style="min-height: 40px;">
									<select class="form-control" id="group" name="group" style="width: 100%;"></select>
								</div>
								<div class="col-md-4 div-select2" style="min-height: 40px;">
									<select class="form-control" id="classification" name="classification" style="width: 100%;"></select>
								</div>
								<div class="col-md-4 div-select3" style="min-height: 40px;">
									<select class="form-control" id="warehouse-search" name="wh" style="width: 100%;"></select>
								</div>
							</div>
						</div>
						<div class="col-md-3 text-center">
							<img src="dist/img/avatar04.png" class="img-circle" alt="User Image" width="30" height="30">
							<span class="text-white" style="font-size: 13pt;">{{ Auth::user()->full_name }}</span>
							<a href="/logout" class="btn btn-default ml-1"><i class="fas fa-sign-out-alt"></i> Sign out</a>
						</div>
					</div>
				</form>
			</div>
		</nav>
		

	<style>
	
		
		#btn
		{
			display:inline-block;
			border:0;
			position: relative;
			-webkit-transition: all 200ms ease-in;
			-webkit-transform: scale(1); 
			-ms-transition: all 200ms ease-in;
			-ms-transform: scale(1); 
			-moz-transition: all 200ms ease-in;
			-moz-transform: scale(1);
			transition: all 200ms ease-in;
			transform: scale(1);
		}

		#btn:hover
		{
			box-shadow: 0px 0px 50px #000000;
			z-index: 2;
			-webkit-transition: all 200ms ease-in;
			-webkit-transform: scale(1);
			-ms-transition: all 200ms ease-in;
			-ms-transform: scale(1.5);   
			-moz-transition: all 200ms ease-in;
			-moz-transform: scale(1);
			transition: all 200ms ease-in;
			transform: scale(1.2);
		}

		#suggesstion-box {
			position:absolute;
			width:95%;
			display:none;
			overflow:hidden;
			border:1px #CCC solid;
			background-color: white;
			display: block;
			z-index: 11;
		}

		.div-select1{
			padding: 3px 5px 0 0 ;
		}
		.div-select2{
			padding: 3px 5px 0 5px;
		}
		.div-select3{
			padding: 3px 0 0 5px;
		}
		.div-search-box{
			padding: 0 5px 0 0;
		}
			.div-search{
			padding: 0; 
		}
		.div-reset{
			padding: 0; 
		}
		.div-cb-remove{
			padding: 0;
		}
	
		@media  only screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:landscape) {
			/* For landscape layouts only */
			.cb_remove_zero_qty{
				font-size: 0.75em;
			}
			.div-search, .div-cb-remove{
				padding: 0; 
			}
			#suggesstion-box{
				width:98%;
			}
			.div-select1{
				padding: 3px 5px 0 0 ;
			}
			.div-select2{
				padding: 3px 5px 0 5px;
			}
			.div-select3{
				padding: 3px 0 0 5px;
			}
			.div-search-box{
				padding: 0 5px 0 0;
			}
			.ste-purpose-txt{
				font-size: 14pt;
			}
			.div-ste{
				padding-right: 0;
			}
		}

		#d {
			display: inline-block;
			border: 0;
			position: relative;
			-webkit-transition: all 200ms ease-in;
			-webkit-transform: scale(1);
			-ms-transition: all 200ms ease-in;
			-ms-transform: scale(1);
			-moz-transition: all 200ms ease-in;
			-moz-transform: scale(1);
			transition: all 200ms ease-in;
			transform: scale(1);
		}

		#d:hover {
			box-shadow: 0px 0px 50px #000000;
			z-index: 2;
			-webkit-transition: all 200ms ease-in;
			-webkit-transform: scale(1);
			-ms-transition: all 200ms ease-in;
			-ms-transform: scale(1.5);
			-moz-transition: all 200ms ease-in;
			-moz-transform: scale(1);
			transition: all 200ms ease-in;
			transform: scale(1.2);
		}

	
	</style>

  	<div class="content-wrapper">
		<div class="content-header pb-0">
			<div class="container-fluid m-0">
				<div class="row text-uppercase">
					
				</div>
			</div>
    	</div>
		<div class="content">
			<div class="content-header pt-0">
				<div class="container-fluid">
					<div class="row mb-2">
						<div class="col-sm-9">
							<h2>Sales Report</h2>
						</div>
						<div class="col-sm-1">
							<button type="button" class="btn btn-block btn-success" id="export-report"><i class="fas fa-file-excel mr-1"></i> Excel</button>
						</div>
						<div class="col-sm-1">
							<button type="button" class="btn btn-block btn-primary" id="refresh-report"><i class="fas fa-sync-alt mr-1"></i> Refresh</button>
						</div>
						<div class="col-sm-1">
							<div class="form-group">
								<select class="form-control" id="year-filter">
									@for ($i = 2019; $i < (now()->year + 2); $i++)
									<option value="{{ $i }}" {{ ($i == (now()->year)) ? 'selected' : '' }}>{{ $i }}</option>
									@endfor
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="card card-danger card-outline">
								<div class="card-header p-0 pt-1 border-bottom-0">
									<ul class="nav nav-tabs" role="tablist">
										<li class="nav-item">
											<a class="nav-link active font-weight-bold" data-toggle="pill" href="#custom-tabs-three-1" role="tab" aria-controls="custom-tabs-three-1" aria-selected="true">Summary</a>
										</li>
										<li class="nav-item">
											<a class="nav-link font-weight-bold" data-toggle="pill" href="#custom-tabs-three-2" role="tab" aria-controls="custom-tabs-three-2" aria-selected="false">Sales Orders</a>
										</li>
										<li class="nav-item">
											<a class="nav-link font-weight-bold" data-toggle="pill" href="#custom-tabs-three-3" role="tab" aria-controls="custom-tabs-three-3" aria-selected="false">Lazada Orders</a>
										</li>
										<li class="nav-item">
											<a class="nav-link font-weight-bold" data-toggle="pill" href="#custom-tabs-three-4" role="tab" aria-controls="custom-tabs-three-4" aria-selected="false">Production Order - Withdrawals</a>
										</li>
									</ul>
								</div>
								<div class="card-body p-0">
						<div class="tab-content" id="custom-tabs-three-tabContent">
							<div class="tab-pane fade show active" id="custom-tabs-three-1" role="tabpanel" aria-labelledby="custom-tabs-three-1-tab">
								<div class="row m-0 p-0">
									<div class="col-md-6 p-2">
										<h4 class="text-center p-0 m-0 font-weight-bold text-uppercase">Sales Summary <span id="summary-year"></span></h4>
									</div>
									<div class="col-md-6 text-center p-2">
										<span class="font-weight-bold">SO</span><span class="mr-3"> - Sales Order Delivered Qty</span>
										<span class="font-weight-bold">STE</span><span class="mr-3"> - Withdrawal Qty for Production</span>
										<span class="font-weight-bold">LAZ</span><span class="mr-3"> - Lazada Ordered Qty</span>
									</div>
									<div class="col-md-12 m-0 p-0">
										<div class="alert m-3 text-center" id="custom_loading_spinner_1">
											<h5 class="m-0"><i class="fas fa-sync-alt fa-spin"></i> <span class="ml-2">Loading ...</span></h5>
										</div>
										<!-- Summary -->
										<div class="table-responsive p-0" id="summary-content"></div>
									</div>
								</div>
							</div>
							<div class="tab-pane fade" id="custom-tabs-three-2" role="tabpanel" aria-labelledby="custom-tabs-three-2-tab">
								<div class="row m-0 p-0">
									<div class="col-md-12 m-0 p-0">
										<div class="alert m-3 text-center" id="custom_loading_spinner_2">
											<h5 class="m-0"><i class="fas fa-sync-alt fa-spin"></i> <span class="ml-2">Loading ...</span></h5>
										</div>
										<div class="table-responsive p-0" id="sales-orders-content"></div>
									</div>
								</div>
							</div>
							<div class="tab-pane fade" id="custom-tabs-three-3" role="tabpanel" aria-labelledby="custom-tabs-three-3-tab">
								<div class="row m-0 p-0">
									<div class="col-md-12 m-0 p-0">
										<div class="alert m-3 text-center" id="custom_loading_spinner_3">
											<h5 class="m-0"><i class="fas fa-sync-alt fa-spin"></i> <span class="ml-2">Loading ...</span></h5>
										</div>
										<div class="table-responsive p-0" id="lazada-orders-content"></div>
									</div>
								</div>
							</div>
							<div class="tab-pane fade" id="custom-tabs-three-4" role="tabpanel" aria-labelledby="custom-tabs-three-4-tab">
								<div class="row m-0 p-0">
									<div class="col-md-12 m-0 p-0">
										<div class="alert m-3 text-center" id="custom_loading_spinner_4">
											<h5 class="m-0"><i class="fas fa-sync-alt fa-spin"></i> <span class="ml-2">Loading ...</span></h5>
										</div>
										<div class="table-responsive p-0" id="withdrawals-content"></div>
									</div>
								</div>
							</div>
						</div>
						
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- /.content -->
		
	</div>
	<!-- /.content-wrapper -->

	<iframe id="frame-so" class="d-none"></iframe>
	<iframe id="frame-laz" class="d-none"></iframe>
	<iframe id="frame-ste" class="d-none"></iframe>
	<iframe id="frame-sum" class="d-none"></iframe>

 <!-- Main Footer -->
 <footer class="main-footer">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
		<a href="https://adminlte.io">AdminLTE.io</a></strong> Version 3.1.0
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2020 <a href="http://fumaco.com">FUMACO Inc</a>.</strong> All rights reserved.
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="{{ asset('/updated/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('/updated/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- Ekko Lightbox -->
<script src="{{ asset('/updated/plugins/ekko-lightbox/ekko-lightbox.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('/updated/dist/js/adminlte.min.js') }}"></script>

	<script>
		$(document).ready(function(){
			var year = $('#year-filter').val();
			$('#summary-year').text(year);
			load_reports(year);
			$('#year-filter').change(function(e){
				var year = $(this).val();
				$('#summary-year').text(year);
				load_reports(year);
			});

			$('#refresh-report').click(function(e){
				e.preventDefault();
				var year = $('#year-filter').val();
				$('#summary-year').text(year);
				load_reports(year);
			});

			$('#export-report').click(function(e){
				e.preventDefault();
				var year = $('#year-filter').val();
				$('#summary-year').text(year);
				export_reports(year);
			});

			function export_reports(year){
				sales_summary_report(year, '#frame-sum', '#custom_loading_spinner_1', 1);
				sales_report(year, 'sales_orders', '#frame-so', '#custom_loading_spinner_2', 1);
				sales_report(year, 'withdrawals', '#frame-ste', '#custom_loading_spinner_3', 1);
				sales_report(year, 'lazada_orders', '#frame-laz', '#custom_loading_spinner_4', 1);
			}

			function load_reports(year){
				sales_summary_report(year, '#summary-content', '#custom_loading_spinner_1', 0);
				sales_report(year, 'sales_orders', '#sales-orders-content', '#custom_loading_spinner_2', 0);
				sales_report(year, 'withdrawals', '#withdrawals-content', '#custom_loading_spinner_3', 0);
				sales_report(year, 'lazada_orders', '#lazada-orders-content', '#custom_loading_spinner_4', 0);
			}

			function sales_report(year, type, el, spinner, export_excel){
				$(spinner).removeClass('d-none');
				$.ajax({
					type: "GET",
					url: "/sales_report?report_type=" + type + "&year=" + year + "&export=" + export_excel,
					success: function (data) {
						if(export_excel){
							$(el).attr('src', this.url);
						}else{
							$(el).html(data);
							$(spinner).addClass('d-none');
						}
					}
				});
			}

			function sales_summary_report(year, el, spinner, export_excel){
				$(spinner).removeClass('d-none');
				$.ajax({
					type: "GET",
					url: "/sales_summary_report/" + year  + "?export=" + export_excel,
					success: function (data) {
						if(export_excel){
							$(el).attr('src', this.url);
						}else{
							$(el).html(data);
							$(spinner).addClass('d-none');
						}
					}
				});
			}

			function load_suggestion_box(){
				var search_string = $('#searchid').val();
				$.ajax({
					type: "GET",
					url: "/load_suggestion_box",
					data: {search_string: search_string},
					success: function (data) {
						$("#suggesstion-box").show();
						$("#suggesstion-box").html(data);
						$("#searchid").css("background", "#FFF");
					}
				});
			}

			get_select_filters();
			function get_select_filters(){
				$('#group').empty();
				$('#classification').empty();
				$('#wh').empty();

				var group = '<option value="">All Item Groups</option>';
				var classification = '<option value="">All Item Classification</option>';
				var wh = '<option value="">All Warehouse</option>';
				$.ajax({
					url: "/get_select_filters",
					type:"GET",
					success: function(data){
						$.each(data.warehouses, function(i, v){
							wh += '<option value="' + v + '">' + v + '</option>';
						});

						$.each(data.item_groups, function(i, v){
							group += '<option value="' + v + '">' + v + '</option>';
						});

						$.each(data.item_classification, function(i, v){
							classification += '<option value="' + v + '">' + v + '</option>';
						});

						$('#group').append(group);
						$('#classification').append(classification);
						$('#warehouse-search').append(wh);

						$('#group').val('');
						$('#classification').val('');
						$('#warehouse-search').val('');
					}
				});
			}

			$("#searchid").keyup(function () {
				load_suggestion_box();
		  	});

			$('body').click(function () {
					$("#suggesstion-box").hide();
			});

			$(document).on('click', '.selected-item', function(e){
				$("#searchid").val($(this).data('val'));
				$("#suggesstion-box").hide();
			});

		
			
			$('#myModal').on("hidden.bs.modal", function () {
				$("body").addClass("modal-open");
			});
		
			$('.modal').on("hidden.bs.modal", function () {
				$(this).find('form')[0].reset();
			});

			$(document).on('click', '.view-item-details', function(e){
				e.preventDefault();

				var item_code = $(this).data('item-code');

				view_item_details(item_code);
			});

			function view_item_details(item_code){
				$.ajax({
					type: 'GET',
					url: '/get_item_details/' + item_code,
					success: function(response){
						$('#item-detail-content').html(response);
						$('#view-item-details-modal').modal('show');
					}
				});

				get_athena_transactions(item_code);
				get_stock_ledger(item_code);
			}

			function get_athena_transactions(item_code, page){
				$.ajax({
					type: 'GET',
					url: '/get_athena_transactions/' + item_code + '?page=' + page,
					success: function(response){
						$('#athena-transactions-table').html(response);
					}
				});
			}

			$(document).on('click', '#athena-transactions-pagination a', function(event){
				event.preventDefault();
				var item_code = $(this).closest('div').data('item-code');
				var page = $(this).attr('href').split('page=')[1];
				get_athena_transactions(item_code, page);
			});

			function get_stock_ledger(item_code, page){
				$.ajax({
					type: 'GET',
					url: '/get_stock_ledger/' + item_code + '?page=' + page,
					success: function(response){
						$('#stock-ledger-table').html(response);
					}
				});
			}

			$(document).on('click', '#stock-ledger-pagination a', function(event){
				event.preventDefault();
				var item_code = $(this).closest('div').data('item-code');
				var page = $(this).attr('href').split('page=')[1];
				get_stock_ledger(item_code, page);
			});

			$(document).on('show.bs.modal', '.modal', function (event) {
				var zIndex = 1040 + (10 * $('.modal:visible').length);
				$(this).css('z-index', zIndex);
				setTimeout(function() {
					$('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
				}, 0);
			});
			
			$('#view-item-details-modal').on("hidden.bs.modal", function () {
				$('#item-tabs a[href="#tab_1"]').tab('show');
			});
		});
	</script>
</body>
</html>

