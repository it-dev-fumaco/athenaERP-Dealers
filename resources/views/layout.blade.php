<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>ERP Inventory - {{ $namePage }}</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="csrf-token" content="{{ csrf_token() }}">

	{{--  <!-- Google Font: Source Sans Pro -->  --}}
	<link rel="stylesheet" href="{{ asset('/updated/custom/font.css') }}">
	<link rel="stylesheet" href="{{ asset('/updated/icons/font-awesome.min.css') }}">
	{{--  <!-- Font Awesome Icons -->  --}}
	<link rel="stylesheet" href="{{ asset('/updated/plugins/fontawesome-free/css/all.min.css') }}">
	{{--  <!-- Ekko Lightbox -->  --}}
	<link rel="stylesheet" href="{{ asset('/updated/plugins/ekko-lightbox/ekko-lightbox.css') }}">
	{{--  <!-- Theme style -->  --}}
	<link rel="stylesheet" href="{{ asset('/updated/dist/css/adminlte.min.css') }}">
	<!-- Select2 -->
	<link rel="stylesheet" href="{{ asset('/updated/plugins/select2/css/select2.min.css') }}">
	<!-- iCheck for checkboxes and radio inputs -->
	<link rel="stylesheet" href="{{ asset('/updated/plugins/iCheck/all.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('css/daterangepicker.css') }}" />
	<!-- fullCalendar -->
	<link rel="stylesheet" href="{{ asset('/updated/plugins/fullcalendar/main.css') }}">
	<!-- datepicker -->
	<script type="text/javascript" src="{{ asset('js/datetimepicker/jquery.min.js') }}"></script>
	<style>
		* {
			-webkit-overflow-scrolling: touch !important;
		}
		html, body{
			scroll-behavior: smooth !important;
		}
		#loader-wrapper {
			position: fixed;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			z-index: 99999;
		}
		#loader {
			display: block;
			position: relative;
			left: 50%;
			top: 50%;
			width: 150px;
			height: 150px;
			margin: -75px 0 0 -75px;
			border-radius: 50%;
			border: 3px solid transparent;
			border-top-color: #3498db;
			-webkit-animation: spin 2s linear infinite; /* Chrome, Opera 15+, Safari 5+ */
			animation: spin 2s linear infinite; /* Chrome, Firefox 16+, IE 10+, Opera */
			z-index: 999999;
		}
		#loader:before {
			content: "";
			position: absolute;
			top: 5px;
			left: 5px;
			right: 5px;
			bottom: 5px;
			border-radius: 50%;
			border: 3px solid transparent;
			border-top-color: #e74c3c;
			-webkit-animation: spin 3s linear infinite; /* Chrome, Opera 15+, Safari 5+ */
			animation: spin 3s linear infinite; /* Chrome, Firefox 16+, IE 10+, Opera */
		}
		#loader:after {
			content: "";
			position: absolute;
			top: 15px;
			left: 15px;
			right: 15px;
			bottom: 15px;
			border-radius: 50%;
			border: 3px solid transparent;
			border-top-color: #f9c922;
			-webkit-animation: spin 1.5s linear infinite; /* Chrome, Opera 15+, Safari 5+ */
			animation: spin 1.5s linear infinite; /* Chrome, Firefox 16+, IE 10+, Opera */
		}
		@-webkit-keyframes spin {
			0%   { 
				-webkit-transform: rotate(0deg);  /* Chrome, Opera 15+, Safari 3.1+ */
				-ms-transform: rotate(0deg);  /* IE 9 */
				transform: rotate(0deg);  /* Firefox 16+, IE 10+, Opera */
			}
			100% {
				-webkit-transform: rotate(360deg);  /* Chrome, Opera 15+, Safari 3.1+ */
				-ms-transform: rotate(360deg);  /* IE 9 */
				transform: rotate(360deg);  /* Firefox 16+, IE 10+, Opera */
			}
		}
		@keyframes spin {
			0%   { 
				-webkit-transform: rotate(0deg);  /* Chrome, Opera 15+, Safari 3.1+ */
				-ms-transform: rotate(0deg);  /* IE 9 */
				transform: rotate(0deg);  /* Firefox 16+, IE 10+, Opera */
			}
			100% {
				-webkit-transform: rotate(360deg);  /* Chrome, Opera 15+, Safari 3.1+ */
				-ms-transform: rotate(360deg);  /* IE 9 */
				transform: rotate(360deg);  /* Firefox 16+, IE 10+, Opera */
			}
		}
		#loader-wrapper .loader-section {
			position: fixed;
			top: 0;
			width: 100%;
			height: 100%;
			background-color:  #e5e7e9 ;
			z-index: 1000;
			-webkit-transform: translateX(0);  /* Chrome, Opera 15+, Safari 3.1+ */
			-ms-transform: translateX(0);  /* IE 9 */
			transform: translateX(0);  /* Firefox 16+, IE 10+, Opera */
		}
		.loaded #loader {
			opacity: 0;
			-webkit-transition: all 0.3s ease-out;  
			transition: all 0.3s ease-out;
		}
		.loaded #loader-wrapper {
			visibility: hidden;
			-webkit-transform: translateY(-100%);  /* Chrome, Opera 15+, Safari 3.1+ */
			-ms-transform: translateY(-100%);  /* IE 9 */
			transform: translateY(-100%);  /* Firefox 16+, IE 10+, Opera */
			-webkit-transition: all 0.3s 1s ease-out;  
			transition: all 0.3s 1s ease-out;
		}
		@font-face { font-family: 'Poppins'; src: url({{ asset('font/Poppins/Poppins-Regular.ttf') }}); } 
		*:not(i):not(.fa){
			font-family: 'Poppins' !important;
			letter-spacing: 0.4px;
		}
		.blink-reservation {
			animation: blinker 1.10s linear infinite;
		}
		@keyframes blinker {
			50% {
				opacity: 0;
			}
		}
		#item-preloader {
			display: flex;
			justify-content: center;
			align-items: center;
			opacity: 0.5;
			background: rgba( 235, 237, 239 );
			transition: opacity 200ms ease-in-out;
			border-radius: 4px;
			width: 100%;
			height: 100%;
			margin: 0;
			min-height: 300px;
		}
		.remove{
			position: absolute;
			top: 15%;
			right: 0;
			transform: translate(-50%, -50%);
			-ms-transform: translate(-50%, -50%);
			background-color: #d9534f;
			color: white;
			font-size: 16px;
			padding: 5px 10px;
			border: none;
			cursor: pointer;
			border-radius: 2px;
			text-align: center;
		}
		.col-md-13 {
			width: 19%;
			margin: 0.5%;
		}
		.imgPreview {
			border: 1px solid #ddd;
			border-radius: 4px;
			padding: 5px;
		}
		.upload-btn{
			padding: 6px 12px;
		}
		.fileUpload {
			position: relative;
			overflow: hidden;
			font-size: 9pt;
		}
		.fileUpload input.upload {
			position: absolute;
			top: 0;
			right: 0;
			margin: 0;
			padding: 0;
			cursor: pointer;
			opacity: 0;
			filter: alpha(opacity=0);
		}
		#btn {
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
		#btn:hover {
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
			width: 95%;
			display:none;
			overflow:hidden;
			padding: 0;
			background-color: white;
			display: block;
			z-index: 11;
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
		.active_dash {
			-moz-box-shadow: 0 0 5px 5px #888;
			-webkit-box-shadow: 0 0 5px 5px#888;
			box-shadow: 0 0 5px 5px #888;
		}
		.checkout {
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
		.checkout:hover {
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
		.mob-dropdown-container{
			position: absolute !important;
			right: 0 !important;
			top: 5 !important;
		}
		.low-lvl-stk-tbl-item-desc, .tbl-athena-logs-tbl-item-description{
			width: 35%;
		}
		.tbl-athena-logs-table{
			font-size: 0.8rem;
		}
		.low-lvl-stock-badge{
			font-size: 11pt;
		}
		.returns-badge, #reserved-items-pagination{
			font-size: 12pt;
		}
		.dashboard-btn-grp{
			text-align: right;
		}
		@media (max-width: 575.98px) {
			.copyright{
				font-size: 10pt;
			}
			.responsive-modal-title{
				font-size: 12pt;
			}
			.low-lvl-stk-tbl-item-desc{
				width: 100% !important;
			}
			.tbl-athena-logs-tbl-item-description{
				width: 90% !important;
			}
			.font-responsive, .tbl-athena-logs-table, .low-lvl-stock-badge, .returns-badge, #reserved-items-pagination{
				font-size: 10pt;
			}
			.inv-accuracy-tbl-item-class{
				padding: 5px !important;
			}
			.dashboard-table td{
				word-break: break-word !important;
			}
			.dashboard-btn-grp{
				text-align: center !important;
			}
			.page-link{
				padding: 9px !important;
			}
			.low-lvl-stock-total{
				margin-top: 13px !important;
			}
			.tbl-athena-logs-tbl-date{
				width: 20% !important;
			}
			.tbl-athena-logs-tbl-warehouse{
				width: auto !important;
			}
			.tbl-athena-logs-tbl-qty{
				width: auto !important;
			}
			.tbl-athena-logs-tbl-ref{
				width: auto !important;
			}
			.tbl-athena-logs-tbl-user{
				width: auto !important;
			}
		}
	  	@media (max-width: 767.98px) {
			.copyright{
				font-size: 10pt;
			}
			.responsive-modal-title{
				font-size: 12pt;
			}
			.low-lvl-stk-tbl-item-desc{
				width: 100% !important;
			}
			.tbl-athena-logs-tbl-item-description{
				width: 90% !important;
			}
			.font-responsive, .tbl-athena-logs-table, .low-lvl-stock-badge, .returns-badge, #reserved-items-pagination{
				font-size: 10pt;
			}
			.inv-accuracy-tbl-item-class{
				padding: 5px !important;
			}
			.dashboard-table td{
				word-break: break-word !important;
			}
			.dashboard-btn-grp{
				text-align: center !important;
			}
			.page-link{
				padding: 9px !important;
			}
			.low-lvl-stock-total{
				margin-top: 13px !important;
			}
			.tbl-athena-logs-tbl-date{
				width: 20% !important;
			}
			.tbl-athena-logs-tbl-warehouse{
				width: auto !important;
			}
			.tbl-athena-logs-tbl-qty{
				width: auto !important;
			}
			.tbl-athena-logs-tbl-ref{
				width: auto !important;
			}
			.tbl-athena-logs-tbl-user{
				width: auto !important;
			}
		}
		@media only screen and (min-device-width : 768px) and (max-device-width : 1024px) and (orientation : portrait) {
			.low-lvl-stk-tbl-item-desc{
				width: 100% !important;
			}
			.tbl-athena-logs-tbl-date{
				width: 20% !important;
			}
			.tbl-athena-logs-tbl-warehouse{
				width: auto !important;
			}
			.tbl-athena-logs-tbl-qty{
				width: auto !important;
			}
			.tbl-athena-logs-tbl-ref{
				width: auto !important;
			}
			.tbl-athena-logs-tbl-user{
				width: auto !important;
			}
		}
		.select2{
			width: 100% !important;
			outline: none !important;
		}
		.select2-selection__rendered {
			line-height: 31px !important;
			outline: none !important;
		}
		.select2-container .select2-selection--single {
			height: 37px !important;
			padding-top: 1.5%;
			outline: none !important;
		}
		.select2-selection__arrow {
			height: 36px !important;
		}
		.tbl-athena-logs-tbl-date{
			width: 17%;
		}
		.tbl-athena-logs-tbl-warehouse{
			width: 18%;
		}
		.tbl-athena-logs-tbl-qty{
			width: 10%;
		}
		.tbl-athena-logs-tbl-ref{
			width: 10%;
		}
		.tbl-athena-logs-tbl-user{
			width: 15%;
		}

		textarea:focus, input:focus, select:focus{
			outline: none;
		}
	</style>
	@yield('style')
</head>
<body class="hold-transition layout-top-nav">
	<div id="loader-wrapper">
		<div id="loader"></div>
		<div class="loader-section section-left"></div>
		<div class="loader-section section-right"></div>
	</div>
	<div class="wrapper">
		<nav class="navbar p-0 navbar-expand-lg navbar-light navbar-navy">
			<div class="container-fluid">
				<div class="d-flex flex-grow-1">
					<div class="row w-100 p-0 m-0">
						<div class="col-xl-9 col-lg-10 col-md-12">
							<div class="row">
								<div class="col-10 col-md-9 col-xl-5 col-lg-3 text-md-center text-sm-left">
									<a href="/" class="navbar-brand">
										<span class="brand-text text-white d-sm-block d-md-none d-lg-none" style="font-size: 1.3rem;">Athena<b>ERP </b><span class="d-md-inline-block d-lg-none d-xl-inline-block"> Inventory</span></span>
										<span class="brand-text text-white d-none d-md-block d-lg-block" style="font-size: 1.7rem;">Athena<b>ERP </b><span class="d-md-inline-block d-lg-none d-xl-inline-block"> Inventory</span></span>
									</a>
								</div>
								<div class="col-2 col-md-3 d-block d-lg-none">
									<li class="nav-item dropdown p-0 mob-dropdown-container" style="list-style-type: none !important;">
										<a class="nav-link text-white p-0" data-toggle="dropdown" href="#">
											<div class="btn-group icon-container mt-2" role="group">
												<img src="{{ asset('dist/img/avatar04.png') }}" class="img-circle" alt="User Image" width="30" height="30"><i class="fas fa-caret-down ml-2 mt-1"></i>
											</div>
										</a>
										<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
											<a href="#" class="dropdown-item w-100">
												<center>
													<i class="fas fa-user"></i> <span class="d-xl-inline-block">{{ Auth::check() ? Auth::user()->full_name : null }}</span>
												</center>
											</a>
											<a href="/logout" class="dropdown-item w-100" style="color: #0074CC !important">
												<center><i class="fas fa-sign-out-alt"></i> <span class="d-xl-inline-block">Sign Out</span></center>
											</a>
										</div>
									</li>
								</div>
								<div class="col-md-12 col-xl-7 col-lg-9 p-0 mx-auto align-middle">
									<form role="search" method="GET" action="/search_results" id="search-form" class="pb-1">
										@php
											$check_qty = request('check_qty') ? request('check_qty') : 'on';
										@endphp
										<input type="hidden" id="cb-1" name="check_qty" value="{{ $check_qty }}">
										<input type="checkbox" id="assigned-to-me" name="assigned_to_me" hidden>
										<input type="hidden" name="wh" id="wh-1" value="{{ request('wh') }}">
										<input type="hidden" name="group" id="grp-1" value="{{ request('group') }}">
										<input type="hidden" name="classification" id="class-1" value="{{ request('classification') }}">
										<input type="hidden" name="brand" id="brand-1" value="{{ request('brand') }}">
										<div class="input-group p-1">
											<input type="text" class="form-control" autocomplete="off" placeholder="Search" name="searchString" id="searchid" value="{{ request('searchString') }}">
											<button class="btn btn-default" type="submit">
												<i class="fas fa-search"></i> <span class="d-none d-xl-inline-block">Search</span>
											</button>
										</div>
									</form>
									<div id="suggesstion-box" class="mr-2 ml-2"></div>
								</div>
							</div>
						</div>
						<div class="d-none d-lg-block col-xl-3 col-lg-2 col-md-2 align-middle pb-0">
							<ul class="order-1 order-md-3 navbar-nav navbar-no-expand mb-0 align-middle">
								<li class="nav-item dropdown col-8 text-right">
									<a class="nav-link text-white" data-toggle="dropdown" href="#">
										<img src="{{ asset('dist/img/avatar04.png') }}" class="img-circle" alt="User Image" width="30" height="30">
										<span class="text-white d-md-none d-lg-none d-xl-inline-block" style="font-size: 13pt;">{{ Auth::check() ? Auth::user()->full_name : null }}</span>
									</a>
								</li>
								<li class="d-none d-lg-block nav-item dropdown text-right">
									<a href="/logout" class="btn btn-default m-1"><i class="fas fa-sign-out-alt"></i> <span class="d-md-none d-lg-none d-xl-inline-block">Sign Out</span></a>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</nav>
		<div class="content-wrapper">
			@if (Auth::user() && Auth::user()->user_group == 'Promodiser')
			<div class="row p-0 m-0 font-responsive">
				<div class="col-md-12 p-2">
					<div class="btn-group w-100" role="group" aria-label="Button group with nested dropdown">
						<a href="/" class="btn btn-default" style="width: 33%;">Home</a>
						<div class="btn-group" role="group" style="width: 33%;">
							<button id="btnGroupDrop" type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Inventory</button>
							<div class="dropdown-menu dropdown-menu-center" aria-labelledby="btnGroupDrop1">
								<a class="dropdown-item" href="/beginning_inventory_list">Beginning Inventory</a>
								<a class="dropdown-item" href="/promodiser/damage_report/form">Damaged Items Entry</a>
							</div>
						</div>
						<div class="btn-group" role="group" style="width: 33%;">
							<button id="btnGroupDrop2" type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Report</button>
							<div class="dropdown-menu dropdown-menu-right" aria-labelledby="btnGroupDrop2">
								<a class="dropdown-item" href="/promodiser/delivery_report/all">Delivery Report</a>
								<a class="dropdown-item" href="/damage_report/list">Damaged Item Report</a>
								<a class="dropdown-item" href="/stock_transfer/list/Material Receipt">Sales Returns Report</a>
							</div>
						</div>
					</div>
				</div>
			</div>
			@endif
			
			@if(!in_array($activePage, ['search_results', 'dashboard', 'error_page', 'item_profile', 'audit_list', 'import_from_ecommerce', 'beginning_inventory']))
			<div class="row m-0 pb-0">
				<div class="col-xl-5 p-3">
					<h2 class="d-none d-lg-block"><a href="/" class="btn btn-default pt-2 pr-4 pb-2 pr-3 pl-3 mr-2 ">
						<i class="fas fa-home"></i>
					</a>&nbsp;{{ $namePage }} <small class="text-muted">{{ $nameDesc }}</small></h2>
					<h4 class="d-block d-lg-none"><a href="/" class="btn btn-default">
						<i class="fas fa-home"></i>
					</a>&nbsp;{{ $namePage }} <small class="text-muted">{{ $nameDesc }}</small></h4>
				</div>
				<div class="col-xl-7 pt-2 dashboard-btn-grp">
					<a class="btn btn-app bg-primary" href="/returns">
						<i class="fas fa-undo"></i> <span class="d-none d-lg-inline">Returns</span>
					</a>
					<a class="btn btn-app bg-info" href="/production_to_receive">
						<i class="far fa-check-circle"></i> <span class="d-none d-lg-inline">Feedback</span>
					</a>
					<a class="btn btn-app bg-gray-dark" href="/material_transfer">
						<i class="fas fa-exchange-alt"></i> <span class="d-none d-lg-inline">Transfer</span>
					</a>
					<a class="btn btn-app bg-purple" href="/receipts">
						<i class="fas fa-boxes"></i> <span class="d-none d-lg-inline">PO Receipts</span>
					</a>
					<a class="btn btn-app bg-olive" href="/material_transfer_for_manufacture">
						<i class="fas fa-tasks"></i> <span class="d-none d-lg-inline">Withdrawals</span>
					</a>
					<a class="btn btn-app bg-indigo" href="/material_issue">
						<i class="fas fa-dolly"></i> <span class="d-none d-lg-inline">Material Issue</span>
					</a>
					<a class="btn btn-app bg-navy" href="/picking_slip">
						<i class="fas fa-truck"></i> <span class="d-none d-lg-inline">Deliveries</span>
					</a>
					<a class="btn btn-app bg-teal" href="/replacements">
						<i class="fas fa-retweet"></i> <span class="d-none d-lg-inline">Replacement</span>
					</a>
				</div>
			</div>
			@endif
			<!-- /.content-header -->
		
			<!-- Main content -->
			@yield('content')
			<!-- /.content -->
			
		</div>
	<!-- /.content-wrapper -->

	<div class="modal fade" id="view-item-details-modal" tabindex="-1" role="dialog" aria-labelledby="ItemDetails">
		<div class="modal-dialog" role="document" style="min-width: 90%;">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title responsive-modal-title">Item Inquiry</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form></form>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="nav-tabs-custom">
								<ul class="nav nav-tabs" id="item-tabs" role="tablist">
									<li class="nav-item">
										<a class="nav-link active" data-toggle="pill" href="#tab_1" role="tab" aria-controls="custom-tabs-three-1" aria-selected="true">
											<span class="d-none d-md-block">Item Info</span>
											<i class="fas fa-info d-block d-md-none"></i>
										</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" data-toggle="pill" href="#tab_2" role="tab" aria-controls="custom-tabs-three-2" aria-selected="false">
											<span class="d-none d-md-block">Athena Transactions</span>
											<i class="fas fa-boxes d-block d-md-none"></i>
										</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" data-toggle="pill" href="#tab_3" role="tab" aria-controls="custom-tabs-three-3" aria-selected="false">
											<span class="d-none d-md-block">ERP Submitted Transaction Histories</span>
											<i class="fas fa-history d-block d-md-none"></i>
										</a>
									</li>
									@if(Auth::check() and in_array(Auth::user()->user_group, ['Inventory Manager']))
									<li class="nav-item">
										<a class="nav-link" data-toggle="pill" href="#tab_4" role="tab" aria-controls="custom-tabs-three-4" aria-selected="false">
											<span class="d-none d-md-block">Stock Reservations</span>
											<i class="fas fa-warehouse d-block d-md-none"></i>
										</a>
									</li>
									@endif
								</ul>
								<div class="tab-content">
									<div class="tab-pane active" id="tab_1">
										<div id="item-preloader">
											<div class="spinner-border" role="status">
												<span class="sr-only">Loading...</span>
											</div>
										</div>
										<div id="item-detail-content"></div>
									</div>
									<div class="tab-pane" id="tab_2">
										<div class="row">
											<div class="col-md-12">
												
												<div class="col-md-3 p-0" style="display: inline-block;">
													<div class="form-group m-1">
														<div class="input-group">
															<div class="input-group-prepend">
																<span class="input-group-text">
																	<i class="far fa-calendar-alt"></i>
																</span>
															</div>
															<input type="text" name="dates" class="form-control float-right font-responsive" id="ath_dates" placeholder="Select a Range">
														</div>
													</div>
												</div>
												<div class="col-md-2 p-2" style="display: inline-block">
													<div class="form-group m-0 font-responsive" id="ath-src-warehouse-filter-parent" style="z-index: 1050">
														<select name="ath-src-warehouse" id="ath-src-warehouse-filter" class="form-control"></select>
													</div>
												</div>
												<div class="col-md-2 p-2" style="display: inline-block">
													<div class="form-group m-0 font-responsive" id="ath-to-warehouse-filter-parent" style="z-index: 1050">
														<select name="ath-to-warehouse" id="ath-to-warehouse-filter" class="form-control"></select>
													</div>
												</div>
												<div class="col-md-2 p-2" style="display: inline-block">
													<div class="form-group m-0 font-responsive" id="warehouse-user-filter-parent" style="z-index: 1050">
														<select name="warehouse_user" id="warehouse-user-filter" class="form-control"></select>
													</div>
												</div>
												<div class="col-md-2" style="display: inline-block">
													<button class="btn btn-secondary font-responsive" id="athReset">Reset Filters</button>
												</div>
												<div class="box-body table-responsive no-padding font-responsive" id="athena-transactions-table"></div>
											</div>
										</div>
									</div>
									<div class="tab-pane" id="tab_3">
										<div class="row">
											<div class="col-md-12">
												<div class="col-md-3 p-0" style="display: inline-block;">
													<div class="form-group m-1">
														<div class="input-group">
															<div class="input-group-prepend">
																<span class="input-group-text">
																	<i class="far fa-calendar-alt"></i>
																</span>
															</div>
															<input type="text" name="erpdates" class="form-control float-right font-responsive" id="erp_dates">
														</div>
													</div>
												</div>
												<div class="col-md-3 p-2" style="display: inline-block">
													<div class="form-group m-0 font-responsive" id="erp-warehouse-filter-parent" style="z-index: 1050">
														<select name="erp-warehouse" id="erp-warehouse-filter" class="form-control"></select>
													</div>
												</div>
												<div class="col-md-3 p-2" style="display: inline-block">
													<div class="form-group m-0 font-responsive" id="erp-warehouse-user-filter-parent" style="z-index: 1050">
														<select name="erp-warehouse-user" id="erp-warehouse-user-filter" class="form-control"></select>
													</div>
												</div>
												<div class="col-md-2" style="display: inline-block">
													<button class="btn btn-secondary font-responsive" id="erpReset">Reset Filters</button>
												</div>
												<div class="box-body table-responsive no-padding font-responsive" id="stock-ledger-table"></div>
											</div>
										</div>
									</div>
									<div class="tab-pane" id="tab_4">
										<div class="row">
											<div class="col-md-12">
												@php
													$attr = null;
													if(Auth::check()){
														$attr = (!in_array(Auth::user()->user_group, ['Inventory Manager'])) ? 'disabled' : '';
													}
												@endphp
												<div class="float-right m-2">
													<button class="btn btn-primary font-responsive" id="add-stock-reservation-btn" {{ $attr }}>New Stock Reservation</button>
												</div>
												<div class="box-body table-responsive no-padding font-responsive" id="stock-reservation-table"></div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" id="resetAll" class="btn btn-default font-responsive" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	{{-- <div class="modal fade" id="warehouseLocationModal" tabindex="-1" role="dialog" aria-labelledby="warehouseLocationModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-md" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="warehouseLocationModalLabel">Update Warehouse Location</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div id="warehouse-location" class="container-fluid"></div>
				</div>
			</div>
		</div>
	</div> --}}

	<!-- Modal -->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="myModalLabel">Modal title</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
				<form></form>
				<div class="modal-body">
					<p id="desc"></p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal -->
	<div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="myModalLabel1">Modal title</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
				<form></form>
				<div class="modal-body">
					<p id="desc1"></p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
			
	<div class="modal fade" id="upload-image-modal" tabindex="-1" role="dialog" aria-labelledby="Upload Image">
		<form method="POST" action="/upload_item_image" enctype="multipart/form-data">
			@csrf
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title">Upload Image</h4>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group" id="upload_edit_form">
									<input type="hidden" name="item_code" class="item-code">
									<div class="fileUpload btn btn-primary upload-btn mb-3">
										<span>Browse Image(s)</span>
										<input type="file" name="item_image[]" class="upload" id="browse-img" multiple />
									</div>
									<div class="row">
										<div class="col-md-12" id="image-previews"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary btn-lg">Upload</button>
					</div>
				</div>
			</div>
		</form>
	</div>


	<div class="modal fade" id="add-stock-reservation-modal">
		<form id="stock-reservation-form" method="POST" action="/create_reservation" autocomplete="off">
			@csrf
			<div class="modal-dialog" style="min-width: 40%;">
		  		<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title">New Stock Reservation</h4>
			  			<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>
					<div class="modal-body">
						<div class="row m-2">
							<div class="col-md-6">
								<div class="form-group">
									<label for="">Item Code</label>
									<input type="text" class="form-control" name="item_code" id="item-code-c" readonly>
								</div>
								<div class="form-group">
									<label for="">Description</label>
									<textarea rows="4" name="description" class="form-control" style="height: 124px;" id="description-c" readonly></textarea>
								</div>
								<div class="form-group">
									<label for="">Notes</label>
									<textarea rows="4" class="form-control" name="notes" style="height: 124px;"></textarea>
								</div>
								<div class="form-group for-in-house-type d-none">
									<label for="validity-c">Validity in Day(s)</label>
									<input type="number" class="form-control" id="validity-c" min="0" value="0">
								</div>
							</div>
							<div class="col-md-6">
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label for="">Warehouse</label>
											<select class="form-control" name="warehouse" id="select-warehouse-c"></select>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="">Reserve Qty</label>
											<input type="text" name="reserve_qty" class="form-control" value="0">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="" class="d-block">Available Qty</label>
											<span id="warehouse-badge" class="badge badge-danger">
												<span id="available-qty-c-text">0</span>
												<span id="stock-uom-c-text"></span>
											</span>
											<input type="hidden" class="form-control" id="available-qty-c" value="0">
										</div>
									</div>
									<div class="col-md-6 d-none">
										<div class="form-group">
											<label for="">Stock UoM</label>
											<input type="hidden" name="stock_uom" class="form-control" id="stock-uom-c" readonly>
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<label for="">Reservation Type</label>
											<select name="type" class="form-control" id="select-type-c" required>
												<option value="">Select Type</option>
												<option value="In-house">In-house</option>
												<option value="Consignment">Consignment</option>
												<option value="Website Stocks">Website Stocks</option>
											</select>
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group for-in-house-type d-none">
											<label for="">Sales Person</label>
											<select class="form-control" name="sales_person" id="select-sales-person-c"></select>
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group for-consignment d-none">
											<label for="">Branch Warehouse</label>
											<select class="form-control" name="consignment_warehouse" id="select-branch-warehouse-c"></select>
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group for-in-house-type d-none">
											<label for="">Project</label>
											<select class="form-control" name="project" id="select-project-c"></select>
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group for-in-house-type d-none">
											<label>Valid until</label>
											<input type="text" name="valid_until" class="form-control" id="date-valid-until-c">
										</div>
									</div>
								</div>                                        
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> CLOSE</button>
						<button type="submit" class="btn btn-primary btn-lg"><i class="fa fa-check"></i> SAVE</button>
					</div>
				</div>
			</div>
		</form>
	</div>

	<div class="modal fade" id="edit-stock-reservation-modal">
		<form id="edit-reservation-form" method="POST" action="/update_reservation" autocomplete="off">
			@csrf
			<div class="modal-dialog" style="min-width: 40%;">
		  		<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title">Edit Stock Reservation</h4>
			  			<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>
					<div class="modal-body">
						<div class="row m-2">
							<div class="col-md-6">
								<div class="form-group">
									<input type="hidden" name="id" id="stock-reservation-id-e">
									<label for="">Item Code</label>
									<input type="text" class="form-control" name="item_code" id="item-code-e" readonly>
								</div>
								<div class="form-group">
									<label for="">Description</label>
									<textarea rows="4" name="description" class="form-control" style="height: 124px;" id="description-e" readonly></textarea>
								</div>
								<div class="form-group">
									<label for="">Notes</label>
									<textarea rows="4" class="form-control" name="notes" id="notes-e" style="height: 124px;"></textarea>
								</div>
								<div class="form-group for-in-house-type d-none">
									<label for="validity-e">Validity in Day(s)</label>
									<input type="number" class="form-control" id="validity-e" min="0" value="0">
								</div>
							</div>
							<div class="col-md-6">
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label for="">Warehouse</label>
											<select class="form-control" id="select-warehouse-e" readonly></select>
											<input type="hidden" class="form-control" name="warehouse" id="warehouse-e">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="">Reserve Qty</label>
											<input type="text" name="reserve_qty" class="form-control" value="0" id="reserve-qty-e">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="" class="d-block">Available Qty</label>
											<span class="badge badge-danger">
												<span id="available-qty-e-text">0</span>
												<span id="stock-uom-e-text" style="word-wrap: break-word !important; white-space: normal !important"></span>
											</span>
											<input type="hidden" class="form-control" name="available_qty" id="available-qty-e" value="0" readonly>
										</div>
									</div>
									<div class="col-md-6 d-none">
										<div class="form-group">
											<label for="">Stock UoM</label>
											<input type="text" name="stock_uom" class="form-control" id="stock-uom-e" readonly>
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<label for="">Reservation Type</label>
											<select name="type" class="form-control" id="select-type-e" readonly>
												<option value="">Select Type</option>
												<option value="In-house">In-house</option>
												<option value="Consignment">Consignment</option>
												<option value="Website Stocks">Website Stocks</option>
											</select>
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group for-in-house-type d-none">
											<label for="">Sales Person</label>
											<select class="form-control" name="sales_person" id="select-sales-person-e"></select>
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group for-consignment d-none">
											<label for="">Branch Warehouse</label>
											<select class="form-control" name="consignment_warehouse" id="select-branch-warehouse-e"></select>
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group for-in-house-type d-none">
											<label for="">Project</label>
											<select class="form-control" name="project" id="select-project-e"></select>
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group for-in-house-type d-none">
											<label>Valid until</label>
											<input type="text" name="valid_until" class="form-control" id="date-valid-until-e">
										</div>
									</div>
								</div>                                        
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> CLOSE</button>
						<button type="submit" class="btn btn-primary btn-lg"><i class="fa fa-check"></i> UPDATE</button>
					</div>
				</div>
			</div>
		</form>
	</div>

	<div class="modal fade" id="cancel-stock-reservation-modal">
		<form id="cancel-reservation-form" method="POST" action="/cancel_reservation" autocomplete="off">
			@csrf
			<div class="modal-dialog" style="min-width: 40%;">
		  		<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title">Cancel Stock Reservation</h4>
			  			<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>
					<div class="modal-body">
						<input type="hidden" name="stock_reservation_id">
						<h5 class="text-center">Cancel Stock Reservation No. <span class="font-weight-bold reservation-id">-</span>?</h5>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> CLOSE</button>
						<button type="submit" class="btn btn-primary btn-lg"><i class="fa fa-check"></i> CONFIRM</button>
					</div>
				</div>
			</div>
		</form>
	</div>

  <!-- Main Footer -->
  <footer class="main-footer font-responsive">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
		<a href="https://adminlte.io">AdminLTE.io</a></strong> Version 3.1.0
    </div>
    <!-- Default to the left -->
    <strong class="copyright">Copyright &copy; 2021 <a href="http://fumaco.com">FUMACO Inc</a>.</strong> All rights reserved.
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

<script src="{{ asset('/updated/plugins/daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ asset('/updated/plugins/inputmask/jquery.inputmask.min.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('/updated/plugins/select2/js/select2.min.js') }}"></script>
<!-- bootstrap datepicker -->
<script src="{{ asset('/updated/plugins/datepicker/bootstrap-datepicker.js') }}"></script>
<!-- iCheck 1.0.1 -->
<script src="{{ asset('/updated/plugins/iCheck/icheck.min.js') }}"></script>
<!-- ChartJS -->
<script src="{{ asset('/updated/plugins/chart.js/Chart.min.js') }}"></script>

<script src="{{ asset('/js/angular.min.js') }}"></script>
<script src="{{ asset('/js/bootstrap-notify.js') }}"></script>
<!-- jquery-validation -->
<script src="{{ asset('/updated/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('/updated/plugins/jquery-validation/additional-methods.min.js') }}"></script>

<!-- datepicker -->
<script type="text/javascript" src="{{ asset('js/datetimepicker/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/datetimepicker/daterangepicker.min.js') }}"></script>
	@yield('script')

	<script>
		document.getElementById('loader-wrapper').removeAttribute('hidden');
		$(document).ready(function(){
			$('#loader-wrapper').attr('hidden', true);
			$(document).on('click', '.create-mr-btn', function(e){
				e.preventDefault();

				$.ajax({
					type: 'GET',
					url: '/create_material_request/' + $(this).data('id'),
					success: function(response){
						if (response.status) {
							showNotification("success", response.message, "fa fa-check");
							get_low_stock_level_items();
						}else{
							showNotification("danger", response.message, "fa fa-info");
						}
					},
					error: function(jqXHR, textStatus, errorThrown) {
					}
				});
			});

			$(document).on('select2:select', '#select-warehouse-c', function(e){
				var data = e.params.data;
				var warehouse = data.id;
				var item_code = $('#item-code-c').val();

				$.ajax({
					type: 'GET',
					url: '/get_available_qty/' + item_code + '/' + warehouse,
					success: function(response){
						$('#available-qty-c').val(response);
						var badge_color = (response > 0) ? 'badge-success' : 'badge-danger';
						$('#available-qty-c-text').text(response).parent().removeClass('badge-danger badge-success').addClass(badge_color);
					}
				});
			});

			$(document).on('select2:select', '#select-warehouse-e', function(e){
				var data = e.params.data;
				var warehouse = data.id;
				var item_code = $('#item-code-e').val();

				$.ajax({
					type: 'GET',
					url: '/get_available_qty/' + item_code + '/' + warehouse,
					success: function(response){
						$('#available-qty-e').val(response);
						var badge_color = (response > 0) ? 'badge-success' : 'badge-danger';
						$('#available-qty-e-text').text(response).parent().removeClass('badge-danger badge-success').addClass(badge_color);
					}
				});
			});

			let getParam = new URLSearchParams(window.location.search);
			let grp = getParam.get('group');
			let whName = getParam.get('wh');
			let classification = getParam.get('classification');
			let brand = getParam.get('brand');

			if(whName === ''){
				whPlaceholder = "Warehouse";
			}else if(whName != null){
				whPlaceholder = whName;
			}else{
				whPlaceholder = "Warehouse";
			}

			if(grp === ''){
				grpPlaceholder = "Item Group";
			}else if(grp != null){
				grpPlaceholder = grp;
			}else{
				grpPlaceholder = "Item Group";
			}

			if(classification === ''){
				classPlaceholder = "Item Classification";
			}else if(classification != null){
				classPlaceholder = classification;
			}else{
				classPlaceholder = "Item Classification";
			}

			if(brand === ''){
				brandPlaceholder = "Brand";
			}else if(brand != null){
				brandPlaceholder = brand;
			}else{
				brandPlaceholder = "Brand";
			}

			// Search Results Warehouse Filter
			$('#warehouse-filter').select2({
				dropdownParent: $('#warehouse-filter-parent'),
				placeholder: whPlaceholder,
				ajax: {
					url: '/get_select_filters',
					method: 'GET',
					dataType: 'json',
					data: function (data) {
						return {
							q: data.term // search term
						};
					},
					processResults: function (response) {
						return {
							results: response.warehouses
						};
					},
					cache: true
				}
			});

			$(document).on('select2:select', '#warehouse-filter', function(e){
				var data = e.params.data;
				$('#wh-1').val(data.id);
				$('#search-form').submit();
			});

			function get_low_stock_level_items(page) {
				$.ajax({
					type: "GET",
					url: "/get_low_stock_level_items?page=" + page,
					success: function (data) {
						$('#low-level-stock-table').html(data);
					}
				});
			}

			function get_reserved_items(page) {
				$.ajax({
					type: "GET",
					url: "/get_reserved_items?page=" + page,
					success: function (data) {
						$('#reserved-items-div').html(data);
					}
				});
			}
			
			$('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
				checkboxClass: 'icheckbox_minimal-blue',
				radioClass: 'iradio_minimal-blue'
			});

			$('.cb-2').on('ifChecked', function(event){
				$("#cb-1").val('on');
				$('#search-form').submit();
			});

			$('.cb-2').on('ifUnchecked', function(event){
				$("#cb-1").val('off');
				$('#search-form').submit();
			});

			// Warehouse Assigned to me - Promodiser
			$('#promodiser-warehouse').on('ifChecked', function(event){
				$("#assigned-to-me").prop("checked", true);
				$('#search-form').submit();
			});

			$('#promodiser-warehouse').on('ifUnchecked', function(event){
				$("#assigned-to-me").prop("checked", false);
				$('#search-form').submit();
			});

			$('#mobile-promodiser-warehouse').on('ifChecked', function(event){
				$("#assigned-to-me").prop("checked", true);
				$('#search-form').submit();
			});

			$('#mobile-promodiser-warehouse').on('ifUnchecked', function(event){
				$("#assigned-to-me").prop("checked", false);
				$('#search-form').submit();
			});
			// Warehouse Assigned to me - Promodiser

			$(document).on('click', '.cancel-stock-reservation-btn', function(e){
				e.preventDefault();
				var reservation_id = $(this).data('reservation-id');
				$('#cancel-stock-reservation-modal .reservation-id').text(reservation_id);
				$('#cancel-stock-reservation-modal input[name="stock_reservation_id"]').val(reservation_id);
				$('#cancel-stock-reservation-modal').modal('show');
			});

			$('#add-stock-reservation-btn').click(function(e){
				e.preventDefault();
				$('#select-warehouse-c').val(null).trigger('change');
				$('#select-sales-person-c').val(null).trigger('change');
				$('#select-branch-warehouse-c').val(null).trigger('change');
				$('#select-project-c').val(null).trigger('change');
				$('#available-qty-c').val(0);
				$('#available-qty-c-text').text(0);
				$('#warehouse-badge').removeClass('badge-success');
				$('#warehouse-badge').addClass('badge-danger');
				$("#date-valid-until-c").datepicker("update", new Date());
				$.ajax({
					type: "GET",
					url: "/get_item_details/" + $('#selected-item-code').text() + "?json=true",
					dataType: 'json',
					contentType: 'application/json',
					success: function (data) {
						$('#item-code-c').val(data.name);
						$('#description-c').val(data.description);
						$('#stock-uom-c').val(data.stock_uom);
						$('#stock-uom-c-text').text(data.stock_uom);
						$('#add-stock-reservation-modal').modal('show');
					}
				});
			});

			$('#select-warehouse-e').select2({
				dropdownParent: $('#edit-stock-reservation-modal'),
				placeholder: 'Select Warehouse',
				ajax: {
					url: '/warehouses_with_stocks',
					method: 'GET',
					dataType: 'json',
					data: function (data) {
						return {
							item_code: $('#item-code-e').val(),
							q: data.term // search term
						};
					},
					processResults: function (response) {
						return {
							results:response
						};
					},
					cache: true
				}
			});

			$('#select-type-e').change(function(){
				if($(this).val()) {
					if($(this).val() == 'In-house') {
						$('.for-in-house-type').removeClass('d-none');
						$('.for-online-shop-type').addClass('d-none');
						$('.for-consignment').addClass('d-none');
					} else if ($(this).val() == 'Consignment') {
						$('.for-in-house-type').addClass('d-none');
						$('.for-online-shop-type').addClass('d-none');
						$('.for-consignment').removeClass('d-none');
					} else {
						$('.for-in-house-type').addClass('d-none');
						$('.for-online-shop-type').removeClass('d-none');
						$('.for-consignment').addClass('d-none');
					}
				}
			});

			$('#select-project-e').select2({
				dropdownParent: $('#edit-stock-reservation-modal'),
				placeholder: 'Select Project',
				ajax: {
					url: '/projects',
					method: 'GET',
					dataType: 'json',
					data: function (data) {
						return {
							q: data.term // search term
						};
					},
					processResults: function (response) {
						return {
							results:response
						};
					},
					cache: true
				}
			});

			$('#select-branch-warehouse-e').select2({
				dropdownParent: $('#edit-stock-reservation-modal'),
				placeholder: 'Select Branch',
				ajax: {
					url: '/consignment_warehouses',
					method: 'GET',
					dataType: 'json',
					data: function (data) {
						return {
							q: data.term // search term
						};
					},
					processResults: function (response) {
						return {
							results:response
						};
					},
					cache: true
				}
			});

			$('#select-sales-person-e').select2({
				dropdownParent: $('#edit-stock-reservation-modal'),
				placeholder: 'Select Sales Person',
				ajax: {
					url: '/sales_persons',
					method: 'GET',
					dataType: 'json',
					data: function (data) {
						return {
							q: data.term // search term
						};
					},
					processResults: function (response) {
						return {
							results:response
						};
					},
					cache: true
				}
			});

			$('#select-warehouse-c').select2({
				dropdownParent: $('#add-stock-reservation-modal'),
				placeholder: 'Select Warehouse',
				ajax: {
					url: '/warehouses_with_stocks',
					method: 'GET',
					dataType: 'json',
					data: function (data) {
						return {
							item_code: $('#item-code-c').val(),
							q: data.term // search term
						};
					},
					processResults: function (response) {
						return {
							results:response
						};
					},
					cache: true
				}
			});

			$('#select-branch-warehouse-c').select2({
				dropdownParent: $('#add-stock-reservation-modal'),
				placeholder: 'Select Branch',
				ajax: {
					url: '/consignment_warehouses',
					method: 'GET',
					dataType: 'json',
					data: function (data) {
						return {
							q: data.term // search term
						};
					},
					processResults: function (response) {
						return {
							results:response
						};
					},
					cache: true
				}
			});

			$('#select-type-c').change(function(){
				if($(this).val()) {
					if($(this).val() == 'In-house') {
						$('.for-in-house-type').removeClass('d-none');
						$('.for-online-shop-type').addClass('d-none');
						$('.for-consignment').addClass('d-none');
						$('#select-sales-person-c').prop('required', true);
						$('#select-project-c').prop('required', true);
						$('#select-branch-warehouse-c').prop('required', false);
					} else if ($(this).val() == 'Consignment') {
						$('.for-in-house-type').addClass('d-none');
						$('.for-online-shop-type').addClass('d-none');
						$('.for-consignment').removeClass('d-none');
						$('#select-sales-person-c').prop('required', false);
						$('#select-project-c').prop('required', false);
						$('#select-branch-warehouse-c').prop('required', true);
					} else {
						$('.for-in-house-type').addClass('d-none');
						$('.for-online-shop-type').removeClass('d-none');
						$('.for-consignment').addClass('d-none');
						$('#select-sales-person-c').prop('required', true);
						$('#select-project-c').prop('required', true);
						$('#select-branch-warehouse-c').prop('required', false);
					}
				}
			});

			$('#select-project-c').select2({
				dropdownParent: $('#add-stock-reservation-modal'),
				placeholder: 'Select Project',
				ajax: {
					url: '/projects',
					method: 'GET',
					dataType: 'json',
					data: function (data) {
						return {
							q: data.term // search term
						};
					},
					processResults: function (response) {
						return {
							results:response
						};
					},
					cache: true
				}
			});

			$('#select-sales-person-c').select2({
				dropdownParent: $('#add-stock-reservation-modal'),
				placeholder: 'Select Sales Person',
				ajax: {
					url: '/sales_persons',
					method: 'GET',
					dataType: 'json',
					data: function (data) {
						return {
							q: data.term // search term
						};
					},
					processResults: function (response) {
						return {
							results:response
						};
					},
					cache: true
				}
			});

			$("#validity-c").bind('keyup mouseup', function () {
				var newdate = new Date();
				newdate.setDate(newdate.getDate() + parseInt($(this).val()));
				$('#date-valid-until-c').datepicker('setDate', newdate);
			});

			$("#validity-e").bind('keyup mouseup', function () {
				var newdate = new Date();
				newdate.setDate(newdate.getDate() + parseInt($(this).val()));
				$('#date-valid-until-e').datepicker('setDate', newdate);
			});

			$('#date-valid-until-c').datepicker({
				startDate: new Date(),
				format: 'yyyy-mm-dd',
				autoclose: true
			});

			$('#date-valid-until-e').datepicker({
				startDate: new Date(),
				format: 'yyyy-mm-dd',
				autoclose: true
			});

			$(document).on('click', '.edit-stock-reservation-btn', function(e){
				e.preventDefault();
				$.ajax({
					type: "GET",
					url: "/get_stock_reservation_details/" + $(this).data('reservation-id'),
					dataType: 'json',
					contentType: 'application/json',
					success: function (data) {
						var selected_warehouse = $('#select-warehouse-e');
						var selected_warehouse_option = new Option(data.warehouse, data.warehouse, true, true);
						selected_warehouse.append(selected_warehouse_option).trigger('change');
						selected_warehouse.select2({disabled:'readonly'});
						if(data.consignment_warehouse) {
							var selected_branch = $('#select-branch-warehouse-e');
							var selected_branch_option = new Option(data.consignment_warehouse, data.consignment_warehouse, true, true);
							selected_branch.append(selected_branch_option).trigger('change');
							selected_branch.select2({disabled:'readonly'});
						}
						if(data.sales_person) {
							var selected_sales_person = $('#select-sales-person-e');
							var selected_sales_person_option = new Option(data.sales_person, data.sales_person, true, true);
							selected_sales_person.append(selected_sales_person_option).trigger('change');
						}
						if(data.project) {
							var selected_project = $('#select-project-e');
							var selected_project_option = new Option(data.project, data.project, true, true);
							selected_project.append(selected_project_option).trigger('change');
						}
						if(data.type == 'In-house') {
							$('.for-in-house-type').removeClass('d-none');
							$('.for-online-shop-type').addClass('d-none');
							$('.for-consignment').addClass('d-none');
						} else if (data.type == 'Consignment') {
							$('.for-in-house-type').addClass('d-none');
							$('.for-online-shop-type').addClass('d-none');
							$('.for-consignment').removeClass('d-none');
						} else {
							$('.for-in-house-type').addClass('d-none');
							$('.for-online-shop-type').removeClass('d-none');
							$('.for-consignment').addClass('d-none');
						}
						$.ajax({
							type: 'GET',
							url: '/get_available_qty/' + data.item_code + '/' + data.warehouse,
							success: function(response){
								var available_qty = parseInt(response); // + (data.reserve_qty - data.consumed_qty);
								var badge_color = (available_qty > 0) ? 'badge-success' : 'badge-danger';
								$('#available-qty-e-text').text(available_qty).parent().removeClass('badge-danger badge-success').addClass(badge_color);
								$('#available-qty-e').val(available_qty);
							}
						});
						var now = new Date("{{ Carbon\Carbon::now()->format('Y-m-d') }}");
						var date = new Date(data.valid_until);
						var date_difference = date.getTime() - now.getTime();
						var validity_in_days = date_difference > 0 ? date_difference / (1000 * 60 * 60 * 24) : 0;
						validity_in_days = Math.floor(validity_in_days) > 0 ? Math.floor(validity_in_days) : 0;
						$('#stock-reservation-id-e').val(data.name);
						$('#warehouse-e').val(data.warehouse);
						$('#item-code-e').val(data.item_code);
						$('#description-e').val(data.description);
						$('#stock-uom-e').val(data.stock_uom);
						$('#stock-uom-e-text').text(data.stock_uom);
						$('#notes-e').val(data.notes);
						$('#select-type-e').val(data.type);
						$('#reserve-qty-e').val(data.reserve_qty);
						$('#status-e').val(data.status);
						$('#date-valid-until-e').val(data.valid_until);
						$('#validity-e').val(validity_in_days);
						$('#edit-stock-reservation-modal').modal('show');
					}
				});
			});

			$(document).on('click', '[data-toggle="lightbox"]', function(event) {
                event.preventDefault();
				var item_code = $(this).data('title');
				$('#'+item_code+'-images-modal').modal('show');
			});

			$(document).on('click', '[data-toggle="mobile-lightbox"]', function(event) {
                event.preventDefault();
				var item_code = $(this).data('title');
				$('#mobile-'+item_code+'-images-modal').modal('show');
			});

			$.ajaxSetup({
				headers: {
				  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});

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

			$('#myModal1').on('hide.bs.modal', function(){
				$('#update-item-modal').modal('hide');
				$('#update-ps-modal').modal('hide');
				$('#update-item-return-modal').modal('hide');
				$('#add-stock-reservation-modal').modal('hide');
				$('#cancel-stock-reservation-modal').modal('hide');
				$('#edit-stock-reservation-modal').modal('hide');
				$('#confirmation-modal').modal('hide');
				$('#sales-return-modal').modal('hide');
			});
			
			$('#myModal').on("hidden.bs.modal", function () {
				$("body").addClass("modal-open");
			});

			$('.modal').on("hidden.bs.modal", function () {
				$(this).find('form')[0].reset();
				$('.for-in-house-type').addClass('d-none');
				$('.for-online-shop-type').addClass('d-none');
				$('.for-consignment').addClass('d-none');
			});

			$(document).on('click', '.view-item-details', function(e){
				e.preventDefault();
				var item_code = $(this).data('item-code');
				var item_classification = $(this).data('item-classification');
				$('#view-item-details-modal .modal-title').text(item_code + " [" + item_classification + "]");
				$('#view-item-details-modal').modal('show');
				view_item_details(item_code);
			});

			$(document).on('submit', '.cancel-modal form', function(e){
				e.preventDefault();
				$.ajax({
					type: 'POST',
					url: $(this).attr('action'),
					data: $(this).serialize(),
					success: function(response){
						if (response.status) {
							showNotification("success", response.message, "fa fa-check");
							$('.cancel-modal').modal('hide');
							get_athena_transactions(response.item_code);
						}else{
							showNotification("danger", response.message, "fa fa-info");
						}
					},
				});
			});

			function get_athena_transactions(item_code, page){
				if(item_code){
					var ath_src = $('#ath-src-warehouse-filter').val();
					var ath_trg = $('#ath-to-warehouse-filter').val();
					var ath_user = $('#warehouse-user-filter').val();
					var ath_drange = $('#ath_dates').val();
					$.ajax({
						type: 'GET',
						url: '/get_athena_transactions/' + item_code + '?page=' + page + '&wh_user=' + ath_user + '&src_wh=' + ath_src + '&trg_wh=' + ath_trg + '&ath_dates=' + ath_drange,
						success: function(response){
							$('#athena-transactions').html(response);
						}
					});
				}
			}

			$(document).on('click', '#low-level-stocks-pagination a', function(event){
				event.preventDefault();
				var page = $(this).attr('href').split('page=')[1];
				get_low_stock_level_items(page);
			});

			$(document).on('click', '#reserved-items-pagination a', function(event){
				event.preventDefault();
				var page = $(this).attr('href').split('page=')[1];
				get_reserved_items(page);
			});

			$(document).on('click', '#stock-reservations-pagination-1 a', function(event){
				event.preventDefault();
				var item_code = $(this).closest('div').data('item-code');
				var page = $(this).attr('href').split('page=')[1];
				get_stock_reservation(page);
			});

			$(document).on('click', '#stock-reservations-pagination-2 a', function(event){
				event.preventDefault();
				var item_code = $(this).closest('div').data('item-code');
				var page = $(this).attr('href').split('page=')[1];
				get_stock_reservation(page);
			});

			$(document).on('click', '#stock-reservations-pagination-3 a', function(event){
				event.preventDefault();
				var item_code = $(this).closest('div').data('item-code');
				var page = $(this).attr('href').split('page=')[1];
				get_stock_reservation(page);
			});
			
			$(document).on('click', '.upload-item-image', function(e){
				e.preventDefault();
				$('.img_upload').remove();
				var item_code = $(this).data('item-code');
				get_item_images(item_code);
				$('#upload-image-modal input[name="item_code"]').val(item_code);
				$('#image-preview').attr('src', $(this).data('image'));
				$('#upload-image-modal').modal('show');
			});

			function get_item_images(item_code){
				var storage = "{{ asset('storage/img/') }}";
				$.ajax({
					type: 'GET',
					url: '/get_item_images/' + item_code,
					success: function(response){
						$.each(response, function(i, d){
							var image_src = storage + '/' + d;
							var image_src_webp = storage + '/' + d.split('.')[0] + '.webp';
							$("<div class=\"col-md-4 pip img_upload\">" +
							"<input type=\"hidden\" name=\"existing_images[]\" value=\"" + i + "\">" +
							// "<img class=\"img-thumbnail\" src=\"" + image_src + "\">" +
							"<picture>" +
							"<source srcset=\"" + image_src_webp + "\" type=\"image/webp\">" +
							"<source srcset=\"" + image_src + "\" type=\"image/jpeg\">" +
							"<img src=\"" + image_src + "\" class=\"img-thumbnail\">" +
							"</picture>" +
							"<span class=\"add-fav remove\">&times;</span>" +
							"</div>").insertAfter("#image-previews");
						});
					}
				});
			}

			$(document).on('click', '.remove', function(){
				$(this).parent(".pip").remove();
			});

			if (window.File && window.FileList && window.FileReader) {
				$("#browse-img").on("change", function(e) {
					var files = e.target.files,
					filesLength = files.length;
					for (var i = 0; i < filesLength; i++) {
						var f = files[i]
						var fileReader = new FileReader();
						fileReader.onload = (function(e) {
							var file = e.target;
							$("<div class=\"col-md-4 pip img_upload\">" +
								"<input type=\"hidden\" name=\"existing_images[]\">" +
							"<img class=\"img-thumbnail\" src=\"" + e.target.result + "\">" +
							"<span class=\"add-fav remove\">&times;</span>" +
							"</div>").insertAfter("#image-previews");
							$(".remove").click(function(){
								$(this).parent(".pip").remove();
							});
						});
						fileReader.readAsDataURL(f);
					}
				});
			} else {
				alert("Your browser doesn't support to File API");
			}

			$('#upload-image-modal form').submit(function(e){
				e.preventDefault();
				var item_code = $(this).find('.item-code').eq(0).val();
				$.ajax({
					type: 'POST',
					url: $(this).attr('action'),
					data: new FormData(this),
					cache: false,
					contentType: false,
					processData: false,
					success: function(response){
						$('#myModal').modal('show'); 
						$('#myModalLabel').html('Message');
						$('#desc').html(response.message);
						view_item_details(item_code);
						$('#upload-image-modal').modal('hide');
					},
				});
			});

			$(document).on('show.bs.modal', '.modal', function (event) {
				var zIndex = 1040 + (10 * $('.modal:visible').length);
				$(this).css('z-index', zIndex);
				setTimeout(function() {
					$('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
				}, 0);
			});

			$(document).on('hidden.bs.modal', '.modal', function () {
				$('.modal:visible').length && $(document.body).addClass('modal-open');
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
			// Transactions Warehouse Users Filter
			// Athena Transactions Pagination
			$(document).on('click', '#athena-transactions-pagination a', function(event){
				event.preventDefault();
				var item_code = $(this).closest('div').data('item-code');
				var page = $(this).attr('href').split('page=')[1];//+ath_user_filter+ath_src_wh;
				get_athena_transactions(item_code, page);
			});
			
			//Athena Warehouse Users
			$('#warehouse-user-filter').select2({//athena warehouse users
				dropdownParent: $('#warehouse-user-filter-parent'),
				placeholder: 'Warehouse User',
				ajax: {
					url: '/get_select_filters',
					method: 'GET',
					dataType: 'json',
					data: function (data) {
						return {
							q: data.term // search term
						};
					},
					processResults: function (response) {
						return {
							results: response.warehouse_users
						};
					},
					cache: true
				}
			});

			$(document).on('select2:select', '#warehouse-user-filter', function(e){
				var item_code = $('#selected-item-code').text();
				get_athena_transactions(item_code);
			});
			//Athena Warehouse Users
			//Athena Source Warehouse
			$('#ath-src-warehouse-filter').select2({
				dropdownParent: $('#ath-src-warehouse-filter-parent'),
				placeholder: 'Source Warehouse',
				ajax: {
					url: '/get_select_filters',
					method: 'GET',
					dataType: 'json',
					data: function (data) {
						return {
							q: data.term // search term
						};
					},
					processResults: function (response) {
						return {
							results: response.source_warehouse
						};
					},
					cache: true
				}
			});

			$(document).on('select2:select', '#ath-src-warehouse-filter', function(e){
				var item_code = $('#selected-item-code').text();
				get_athena_transactions(item_code);
			});
			//Athena Source Warehouse
			//Athena Target Warehouse
			$('#ath-to-warehouse-filter').select2({
				dropdownParent: $('#ath-to-warehouse-filter-parent'),
				placeholder: 'Target Warehouse',
				ajax: {
					url: '/get_select_filters',
					method: 'GET',
					dataType: 'json',
					data: function (data) {
						return {
							q: data.term // search term
						};
					},
					processResults: function (response) {
						return {
							results: response.target_warehouse
						};
					},
					cache: true
				}
			});

			$(document).on('select2:select', '#ath-to-warehouse-filter', function(e){
				var item_code = $('#selected-item-code').text();
				get_athena_transactions(item_code);
			});
			//Athena Target Warehouse
			//Athena Month
			$('#ath_dates').on('change', function(e){ 
				var item_code = $('#selected-item-code').text();
				get_athena_transactions(item_code);
			})
			//Athena Month
			// ERP Warehouse Users
			$('#erp-warehouse-user-filter').select2({//warehouse users
				dropdownParent: $('#erp-warehouse-user-filter-parent'),
				placeholder: 'Select Warehouse User',
				ajax: {
					url: '/get_select_filters',
					method: 'GET',
					dataType: 'json',
					data: function (data) {
						return {
							q: data.term // search term
						};
					},
					processResults: function (response) {
						return {
							results: response.warehouse_users
						};
					},
					cache: true
				}
			});

			$('#erp-warehouse-filter').select2({
				dropdownParent: $('#erp-warehouse-filter-parent'),
				placeholder: 'Select Warehouse',
				ajax: {
					url: '/get_select_filters',
					method: 'GET',
					dataType: 'json',
					data: function (data) {
						return {
							q: data.term // search term
						};
					},
					processResults: function (response) {
						return {
							results: response.warehouse
						};
					},
					cache: true
				}
			});
			// ERP Warehouse
			// Brand filter
			$('#brand-filter').select2({
				dropdownParent: $('#brand-filter-parent'),
				placeholder: brandPlaceholder,
				ajax: {
					url: '/get_select_filters',
					method: 'GET',
					dataType: 'json',
					data: function (data) {
						return {
							q: data.term // search term
						};
					},
					processResults: function (response) {
						return {
							results: response.brand
						};
					},
					cache: true
				}
			});

			$(document).on('select2:select', '#brand-filter', function(e){
				var data = e.params.data;
				$('#brand-1').val(data.id);
				$('#search-form').submit();
			});
			// Brand filter
			// Item class filter
			$('#item-class-filter').select2({
				dropdownParent: $('#item-class-filter-parent'),
				placeholder: classPlaceholder,
				ajax: {
					url: '/get_select_filters',
					method: 'GET',
					dataType: 'json',
					data: function (data) {
						return {
							q: data.term // search term
						};
					},
					processResults: function (response) {
						return {
							results: response.item_class_filter
						};
					},
					cache: true
				}
			});

			$(document).on('select2:select', '#item-class-filter', function(e){
				var data = e.params.data;
				$('#class-1').val(data.id);
				$('#search-form').submit();
			});
		});

		$('.submit-once').click(function(){
			if(!$(this).hasClass('submitted')){
				$(this).addClass('submitted');
			}else{
				$(this).prop('disabled', true);
				if($(this).is('a')){
					$(this).off("click").attr('href', "javascript: void(0);");
				}
			}
		});

		function close_modal(modal){
			$(modal).modal('hide');
		}

		function nextImg(item_code){
			if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) { // mobile/tablet
				var current_img = $('#mobile-'+item_code+'-image-data').text();
			}else{ // desktop
				var current_img = $('#'+item_code+'-image-data').text();
			}
			$.ajax({
				type: "GET",
				url: "/search_results_images",
				data: { 
					img_key: parseInt(current_img) + 1,
					item_code: item_code,
					dir: 'next'
				},
				success: function (data) {
					if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) { //mobile/tablet
						$('#mobile-'+data.item_code+'-image').attr('src', data.orig_image_path);
						$('#mobile-'+data.item_code+'-webp-image-src').attr('srcset', data.webp_image_path);
						$('#mobile-'+data.item_code+'-orig-image-src').attr('srcset', data.orig_image_path);
						$('#mobile-'+data.item_code+'-image').prop('alt', data.alt);
						$('#mobile-'+data.item_code+'-image-data').text(data.current_img_key);
					}else{ // desktop
						$('#'+data.item_code+'-image').attr('src', data.orig_image_path);
						$('#'+data.item_code+'-webp-image-src').attr('srcset', data.webp_image_path);
						$('#'+data.item_code+'-orig-image-src').attr('srcset', data.orig_image_path);
						$('#'+data.item_code+'-image').prop('alt', data.alt);
						$('#'+data.item_code+'-image-data').text(data.current_img_key);
					}
				}
			});
		}

		function prevImg(item_code){
			if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) { // mobile/tablet
				var current_img = $('#mobile-'+item_code+'-image-data').text();
			}else{ // desktop
				var current_img = $('#'+item_code+'-image-data').text();
			}
			$.ajax({
				type: "GET",
				url: "/search_results_images",
				data: { 
					img_key: parseInt(current_img) - 1,
					item_code: item_code,
					dir: 'prev'
				},
				success: function (data) {
					if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) { //mobile/tablet
						$('#mobile-'+data.item_code+'-image').attr('src', data.orig_image_path);
						$('#mobile-'+data.item_code+'-webp-image-src').attr('srcset', data.webp_image_path);
						$('#mobile-'+data.item_code+'-orig-image-src').attr('srcset', data.orig_image_path);
						$('#mobile-'+data.item_code+'-image').prop('alt', data.alt);
						$('#mobile-'+data.item_code+'-image-data').text(data.current_img_key);
					}else{ // desktop
						$('#'+data.item_code+'-image').attr('src', data.orig_image_path);
						$('#'+data.item_code+'-webp-image-src').attr('srcset', data.webp_image_path);
						$('#'+data.item_code+'-orig-image-src').attr('srcset', data.orig_image_path);
						$('#'+data.item_code+'-image').prop('alt', data.alt);
						$('#'+data.item_code+'-image-webp').prop('data', data.webp_image_path);
						$('#'+data.item_code+'-image-data').text(data.current_img_key);
					}
				}
			});
		}
	</script>
</body>
</html>

