@extends('layout', [
    'namePage' => 'Search Result(s)',
    'activePage' => 'search_results',
])

@section('content')
<div class="content p-0 m-0">
	<div class="content-header p-0 m-0">
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-12">
					<div class="row">
						<div class="col-12">
							<div class="container-fluid d-block d-xl-none">
								<div class="row mb-2">
									<div class="col-7">
										<p class="card-title mt-1 font-weight-bold" style="font-size: 8pt">
											@if(request('searchString') && request('searchString') != '') 
												Search result(s) for "{{ request('searchString') }}"
											@else
												Item List
											@endif
										</p>
									</div>
									<div class="col-5 text-right">
										@if (array_filter(request()->all()))
											<p class="card-title mt-1 ml-4 font-weight-bold float-right" style="font-size: 8pt">
												<a href="/search_results">
													<i class="fa fa-refresh"></i>&nbsp;Clear Searches
												</a>
											</p>
										@endif
									</div>
								</div>
								<div class="row mb-2">
									<span class="text-muted" style="font-size: 8pt">
										@foreach ($breadcrumbs as $breadcrumb)
											{{ !$loop->first ? ' / ' : null }}<a href="{!! request()->fullUrlWithQuery(['group' => $breadcrumb]) !!}" class="text-muted" style="text-decoration: none !important; text-transform: none !important;"><span style="{{ $loop->last ? 'font-weight: 700; color: #212529' : null }}">{{ $breadcrumb }}</span></a>
										@endforeach
									</span>
								</div>
							</div>
							<div id="accordion" class="col-12 card card-gray card-outline m-0 p-0">
								<div class="card m-0">
									<div class="row m-0 p-0">
										<div class="col-8">
											@php
												$promodiser_restriction = Auth::user()->user_group == 'Promodiser' ? 1 : 0;
											@endphp
											<button class="float-left btn text-left pt-0 d-block d-xl-none" data-toggle="modal" data-target="#mobile-filters-modal">
												<p class="card-title mt-2 ml-0" style="font-size: 10pt !important">
													<i class="fa fa-bars"></i>&nbsp;Filters
												</p>
											</button>

											<button class="float-left btn text-left pt-0 collapsed d-block" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
												<p class="card-title mt-2 ml-0" style="font-size: 10pt !important">
													<i class="fa fa-plus"></i>&nbsp;Advanced Filters
												</p>
											</button>

											<p class="card-title mt-2 ml-4 d-none d-xl-inline" style="font-size: 14px;">
												@if(request('searchString') && request('searchString') != '') 
													Search result(s) for "{{ request('searchString') }}"
												@else
													Item List
												@endif
												&nbsp;
												<small class="text-muted">
													@foreach ($breadcrumbs as $breadcrumb)
														{{ !$loop->first ? ' / ' : null }}<a href="{!! request()->fullUrlWithQuery(['group' => $breadcrumb == $root ? null : $breadcrumb]) !!}" class="text-muted" style="text-decoration: none !important; text-transform: none !important;"><span style="{{ $loop->last ? 'font-weight: 700; color: #212529' : null }}">{{ $breadcrumb }}</span></a>
													@endforeach
												</small>
											</p>
											@if (array_filter(request()->all()))
												<p class="card-title mt-2 ml-4 d-none d-xl-inline" style="font-size: 14px;">
													<a href="/search_results">
														<i class="fa fa-refresh"></i>&nbsp;Clear Searches
													</a>
												</p>
											@endif

											<!-- Filters Modal -->
											<div class="modal left fade" id="mobile-filters-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
												<div class="modal-dialog" role="document">
													<div class="modal-content">
														<div class="modal-body">
															<div class="text-right pb-2">
																<i class="fa fa-close" onclick="close_modal('#mobile-filters-modal')" style="cursor: pointer"></i>
															</div>
															<div class="tree container"><!-- Item Group -->
																<ul style="padding-left: 0 !important">
																	@foreach (array_keys($item_groups) as $item)
																		@php
																			$lvl2 = isset($item_group_array[$item]['lvl2']) ? $item_group_array[$item]['lvl2'] : [];
																		@endphp
																		<li>
																			<span class="w-100 {{ !$lvl2 ? 'p-2' : 'p-0' }}" style="border: none !important">
																				<a style="color: #000; font-size: 10pt;" href="{!! $lvl2 ? request()->fullUrlWithQuery(['group' => $item]) : request()->fullUrlWithQuery(['searchString' => null, 'group' => $item, 'wh' => null, 'classification' => null]) !!}">
																					<div class="btn-group w-100" role="group" aria-label="Basic example">
																						<button type="button" class="btn w-25" style="background-color: #001F3F; color: #fff"><i class="far {{ $lvl2 ? 'fa-folder-open' : 'fa-file' }}"></i></button>
																						<button type="button" class="btn w-75" style="border: 2px solid #001F3F">{{ $item }}</button>
																					</div>
																				</a>
																			</span>
																			@if ($lvl2)
																				@include('search_results_item_group_tree', ['all' => $all, 'groups' => $lvl2, 'current_lvl' => 2, 'prev_obj' => $item])
																			@endif
																		</li>
																	@endforeach
																</ul>
															</div>
														</div>

													</div><!-- modal-content -->
												</div><!-- modal-dialog -->
											</div><!-- modal -->
										</div>
										<div class="col-4 text-right">
											<p class="font-weight-bold m-1 font-responsive d-inline total">TOTAL: <span class="badge badge-info font-responsive total">{{ number_format($total_items) }}</span></p>
										</div>
									</div>
									
									<div id="collapseOne" class="collapse border border-outline-secondary collapse" aria-labelledby="headingOne" data-parent="#accordion">
										<div class="card-body p-0">
											<div class="col-12 mx-auto">
												<div class="row pt-2">
													<div class="col-12 col-md-1 col-xl-2 mx-auto general-filter-label text-left text-md-right" style="font-size: 10pt; white-space: nowrap">
														<label class="font-responsive">Search For:</label>
													</div>
													<div class="col-12 col-md-2 col-xl-2 mx-auto pb-2 pb-xl-0">
														<div class="form-group text-left m-0 w-100 mx-auto" id="item-class-filter-parent" style="font-size: 10pt;">
															<select id="item-class-filter" class="btn btn-default"></select>
														</div>
													</div>
													<div class="col-12 col-md-{{ $promodiser_restriction ? 2 : 3 }} col-xl-2 mx-auto pb-2 pb-xl-0">
														<div class="form-group text-left m-0 w-100 mx-auto" id="warehouse-filter-parent" style="font-size: 10pt;">
															<select name="warehouse" id="warehouse-filter" class="form-control"></select>
														</div>
													</div>
													<div class="col-12 col-md-{{ $promodiser_restriction ? 2 : 3 }} col-xl-2 mx-auto">
														<div class="form-group text-left m-0 w-100 mx-auto pb-2 pb-xl-0" id="brand-filter-parent" style="font-size: 10pt;">
															<select name="brand" id="brand-filter" class="form-control"></select>
														</div>
													</div>
													@if (!$promodiser_restriction)
													<div class="col-12 col-md-2 col-xl-{{ $promodiser_restriction ? 2 : 4 }} mx-auto checkbox-container">
														<div class="row">
															<div class="form-group m-0r col-12 m-0">
																@php
																	$check_qty = 1;
																	if(request('check_qty')){
																		$check_qty = request('check_qty') == 'on' ? 1 : 0;
																	}
																@endphp
																<label style="white-space: nowrap">
																	<input type="checkbox" class="minimal cb-2" id="cb-2" {{ $check_qty == 1 ? 'checked' : null }} >
																	
																	<span style="font-size: 12px;">Hide out of stock</span>
																</label>
															</div>
														</div>
													</div>
													@endif
													@if ($promodiser_restriction)
													<div class="col-8 col-md-3 col-xl-2 mx-auto text-center">
														<div class="form-group m-0r">
															<label style="white-space: nowrap">
																<input type="checkbox" class="minimal" id="promodiser-warehouse" {{ (request('assigned_to_me')) ? 'checked' : null }} >
																
																<span class="filter-font">Warehouse Assigned to Me</span>
															</label>
														</div>
													</div>
													@endif
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-2 d-none {{ $item_groups ? 'd-xl-block' : null }}">
											<div class="card mb-3 pt-0">
												@php
													$category = collect(array_keys($item_groups))->chunk(3);
												@endphp
												<div class="tab-content">
													@for($i = 0; $i < count($category); $i++)
														<div id="class-category-{{ $i + 1 }}" class="container tab-pane {{ $i == 0 ? 'active' : null }}" style="padding: 8px 0 0 0;">
															<div class="tree container"><!-- Item Group -->
																<ul style="padding-left: 0 !important" >
																	@foreach ($category[$i] as $item)
																		@php
																			$lvl2 = isset($item_group_array[$item]['lvl2']) ? $item_group_array[$item]['lvl2'] : [];
																		@endphp
																		<li class="{{ !$lvl2 ? 'p-2' : 'p-0' }}">
																			<span class="p-0 w-75 tree-item" style="border: none !important">
																				<a style="color: inherit; font-size: 10pt;" href="{!! request()->fullUrlWithQuery(['group' => $item]) !!}">
																					<div class="btn-group w-100" role="group" aria-label="Basic example">
																						<button type="button" class="btn w-25 p-0" style="background-color: #001F3F; color: #fff"><i class="far {{ $lvl2 ? 'fa-folder-open' : 'fa-file' }}"></i></button>
																						<button type="button" class="btn w-75 p-0" style="border: 2px solid #001F3F; font-size: 10pt; color: inherit">{{ $item }}</button>
																					</div>
																				</a>
																			</span>
																			@if ($lvl2)
																				@include('search_results_item_group_tree', ['all' => $all, 'groups' => $lvl2, 'current_lvl' => 2, 'prev_obj' => $item])
																			@endif
																		</li>
																	@endforeach
																</ul>
															</div>
														</div>
													@endfor
												</div>
												@if (count($category) > 1)
													<ul class="nav nav-tabs" role="tablist">
														@foreach ($category as $i => $item)
															<li class="nav-item">
																<a class="nav-link {{ $loop->first ? 'active' : null }}" data-toggle="tab" href="#class-category-{{ $i + 1 }}">{{ $i + 1 }}</a>
															</li>
														@endforeach
													</ul>
												@endif
											</div>
										</div>
										<div class="col-12 col-xl-{{ $item_groups ? '10' : '12' }}">
											<div class="container-fluid m-0">
												@forelse ($item_list as $row)
													<div class="mb-1"></div>
													<div class="d-none d-xl-block border border-outline-secondary"><!-- Desktop -->
														<div class="row m-0">
															<div class="col-1 p-1">
																@php
																	$img = isset($row['item_image_paths'][0]) ? "/img/" . $row['item_image_paths'][0]->image_path : "/icon/no_img.png";
																	$img_webp = isset($row['item_image_paths'][0]) ? "/img/" . explode('.',$row['item_image_paths'][0]->image_path)[0].'.webp' : "/icon/no_img.webp";
																@endphp
																<a href="{{ asset('storage/') }}{{ $img }}" data-toggle="lightbox" data-gallery="{{ $row['name'] }}" data-title="{{ $row['name'] }}">
																	@if(isset($row['item_image_paths'][0]) && !Storage::disk('public')->exists('/img/'.explode('.', $row['item_image_paths'][0]->image_path)[0].'.webp'))
																		<img src="{{ asset('storage/').$img }}" class="img w-100">
																	@elseif(isset($row['item_image_paths'][0]) && !Storage::disk('public')->exists('/img/'.$row['item_image_paths'][0]->image_path))
																		<img src="{{ asset('storage/').$img_webp }}" class="img w-100">
																	@else
																		<picture>
																			<source srcset="{{ asset('storage'.$img_webp) }}" type="image/webp" class="img-responsive hover" style="width: 100% !important;">
																			<source srcset="{{ asset('storage'.$img) }}" type="image/jpeg" class="img-responsive hover" style="width: 100% !important;">
																			<img src="{{ asset('storage'.$img) }}" alt="{{ Illuminate\Support\Str::slug(explode('.', $img)[0], '-') }}" class="img-responsive hover" style="width: 100% !important;">
																		</picture>
																	@endif
																</a>
					
																<div class="modal fade" id="{{ $row['name'] }}-images-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
																	<div class="modal-dialog" role="document">
																		<div class="modal-content">
																			<div class="modal-header">
																			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																				<span aria-hidden="true">&times;</span>
																			</button>
																			</div>
																			<div class="modal-body">
																				<div id="image-container" class="container-fluid">
																					<div id="carouselExampleControls" class="carousel slide" data-interval="false">
																						<div class="carousel-inner">
																							<div class="carousel-item active">
																								{{-- <img class="d-block w-100" id="{{ $row['name'] }}-image" src="{{ asset('storage/').$img_webp }}" alt="{{ Illuminate\Support\Str::slug(explode('.', $img_webp)[0], '-') }}"> --}}
																								<picture>
																									<source id="{{ $row['name'] }}-webp-image-src" srcset="{{ asset('storage/').$img_webp }}" type="image/webp" class="d-block w-100" style="width: 100% !important;">
																									<source id="{{ $row['name'] }}-orig-image-src" srcset="{{ asset('storage/').$img }}" type="image/jpeg" class="d-block w-100" style="width: 100% !important;">
																									<img class="d-block w-100" id="{{ $row['name'] }}-image" src="{{ asset('storage/').$img }}" alt="{{ Illuminate\Support\Str::slug(explode('.', $img)[0], '-') }}">
																								</picture>
																							</div>
																							<span class='d-none' id="{{ $row['name'] }}-image-data">0</span>
																						</div>
																						<a class="carousel-control-prev" href="#carouselExampleControls" onclick="prevImg('{{ $row['name'] }}')" role="button" data-slide="prev" style="color: #000 !important">
																							<span class="carousel-control-prev-icon" aria-hidden="true"></span>
																							<span class="sr-only">Previous</span>
																						</a>
																						<a class="carousel-control-next" href="#carouselExampleControls" onclick="nextImg('{{ $row['name'] }}')" role="button" data-slide="next" style="color: #000 !important">
																							<span class="carousel-control-next-icon" aria-hidden="true"></span>
																							<span class="sr-only">Next</span>
																						</a>
																					</div>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
					
																<div class="text-center mt-2 mb-1">
																	<div class="d-flex flex-row">
																		<div class="p-1 col-6">
																			{{-- <a href="#" class="view-item-details" data-item-code="{{ $row['name'] }}" data-item-classification="{{ $row['item_classification'] }}"> --}}
																			<a href="/get_item_details/{{ $row['name'] }}">
																				<div class="btn btn-primary btn-xs btn-block">
																					<i class="fa fa-search"></i> <span class="d-inline d-md-none" style="font-size: 10pt">View Item Details</span>
																				</div>
																			</a>
																		</div>
																		<div class="p-1 col-6">
																			<a href="#" class="cLink d-none d-xl-inline" value="Print Barcode" onClick="javascript:void window.open('/print_barcode/{{ $row['name'] }}','1445905018294','width=450,height=700,toolbar=0,menubar=0,location=0,status=1,scrollbars=1,resizable=1,left=0,top=0');return false;">
																				<div class="btn btn-warning btn-xs btn-block">
																					<i class="fa fa-qrcode"></i>
																				</div>
																			</a>
																		</div>
																	</div>
																</div>
															</div>
															<div class="col-6 p-1">
																<div class="col-md-12 m-0 text-justify" >
																	<span class="font-italic item-class" >{{ $row['item_classification'] }}</span><br/>
																	<span class="text-justify item-name" style="font-size: 10pt !important;"><b>{{ $row['name'] }}</b> - {!! strip_tags($row['description']) !!}</span>
																	@if ($row['part_nos'])
																		<br>
																		<span class="text-justify item-name"><b>Part No(s)</b> {{ $row['part_nos'] }} </span>
																	@endif
																	@if (in_array($user_department, $allowed_department) && !in_array(Auth::user()->user_group, ['Manager', 'Director']) && $row['default_price'] > 0)
																	<p class="mt-3 mb-2">
																		<span class="d-block font-weight-bold" style="font-size: 15pt;">{{ '₱ ' . number_format($row['default_price'], 2, '.', ',') }}</span>
																		<span class="d-block" style="font-size: 9pt;">Standard Selling Price</span>
																	</p>
																	@endif
																	@if(in_array(Auth::user()->user_group, ['Manager', 'Director']) && $row['default_price'] > 0)
																	<p class="mt-3 mb-2">
																		<span class="d-block font-weight-bold" style="font-size: 15pt;">{{ '₱ ' . number_format($row['default_price'], 2, '.', ',') }}</span>
																		<span class="d-block" style="font-size: 9pt;">Standard Selling Price</span>
																	</p>
																	@endif
																</div>
															</div>
															<div class="col-5 p-1">
																@if ($row['item_inventory'])
																	<table class="table table-sm table-bordered warehouse-table table-hover">
																		<thead>
																			<tr>
																				<th class="text-center wh-cell">Warehouse</th>
																				<th class="text-center qtr-cell text-muted">Reserved Qty</th>
																				<th class="text-center qtr-cell">Available Qty</th>
																			</tr>
																		</thead>
																		@foreach($row['item_inventory'] as $inv)
																			<tr>
																				<td class="text-center" >
																					{{ $inv['warehouse'] }}
																					@if ($inv['location'])
																						<small class="text-muted font-italic"> - {{ $inv['location'] }}</small>
																					@endif
																				</td>
																				<td class="text-center">
																					<small class="text-muted">{{ $inv['reserved_qty'] * 1 }}  {{ $inv['stock_uom'] }}</small>
																				</td>
																				<td class="text-center">
																					@php
																						if($inv['available_qty'] == 0){
																							$uom_badge = 'secondary';
																						}else if($inv['available_qty'] <= $inv['warehouse_reorder_level']){
																							$uom_badge = 'warning';
																						}else{
																							$uom_badge = 'success';
																						}
																					@endphp
																					<span class="badge badge-{{ $uom_badge }}" style="font-size: 14px; margin: 0 auto;">{{ $inv['available_qty'] * 1 }} <small>{{ $inv['stock_uom'] }}</small></span>
																				</td>
																			</tr>
																		@endforeach
																	</table>
																@else
																	<div class="h-75 d-flex align-items-center">
																		<p class="pt-2 mx-auto">No Available Stock on All Warehouses</p>
																	</div>
																@endif
																<div class="col-md-12"><!-- View Consignment Warehouse (Desktop View) -->
																	@if(Auth::user() && Auth::user()->user_group != 'Promodiser' and count($row['consignment_warehouses']) > 0)
																	<div class="text-center">
																		<a href="#" class="btn btn-primary uppercase p-1" data-toggle="modal" data-target="#vcw{{ $row['name'] }}" style="font-size: 11px;">View Consignment Warehouse</a>
																	</div>
						
																	<div class="modal fade" id="vcw{{ $row['name'] }}" tabindex="-1" role="dialog">
																		<div class="modal-dialog modal-xl" role="document">
																			<div class="modal-content">
																				<div class="modal-header">
																					<h4 class="modal-title consignment-head">{{ $row['name'] }} - Consignment Warehouse(s) </h4>
																					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
																				</div>
																				<form></form>
																				<div class="modal-body">
																					<table class="table table-hover m-0">
																						<col style="width: 70%;">
																						<col style="width: 30%;">
																						<tr>
																							<th class="consignment-th text-center">Warehouse</th>
																							<th class="consignment-th text-center">Available Qty</th>
																							<th class="consignment-th text-center">In Store</th>
																						</tr>
																						@forelse($row['consignment_warehouses'] as $con)
																						<tr>
																							<td class="consignment-name">
																								{{ $con['warehouse'] }}
																								@if ($con['location'])
																									<small class="text-muted font-italic">- {{ $con['location'] }}</small>
																								@endif
																							</td>
																							<td class="text-center">
																								<span class="badge badge-{{ ($con['available_qty'] > 0) ? 'success' : 'secondary' }}" style="font-size: 15px; margin: 0 auto;">{{ $con['actual_qty'] * 1 }} <small>{{ $con['stock_uom'] }}</small></span>
																							</td>
																							<td class="text-center">
																								<span class="badge badge-{{ ($con['consigned_qty'] > 0) ? 'success' : 'secondary' }}" style="font-size: 15px; margin: 0 auto;">{{ $con['consigned_qty'] * 1 }} <small>{{ $con['stock_uom'] }}</small></span>
																							</td>
																						</tr>
																						@empty
																						<tr>
																							<td class="text-center font-italic" colspan="3">NO WAREHOUSE ASSIGNED</td>
																						</tr>
																						@endforelse
																					</table>
																				</div>
																				<div class="modal-footer">
																					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
																				</div>
																			</div>
																		</div>
																	</div>
																	@endif
																</div><!-- View Consignment Warehouse -->
															</div>
														</div>
													</div>
													<div class="d-block d-xl-none border border-outline-secondary"><!-- Mobile/Tablet -->
														<div class="row m-0">
															<div class="col-3 col-lg-2 col-xl-3 p-1">
																@php
																	$img = isset($row['item_image_paths'][0]) ? "/img/" . $row['item_image_paths'][0]->image_path : "/icon/no_img.png";
																	$img_webp = isset($row['item_image_paths'][0]) ? "/img/" . explode('.',$row['item_image_paths'][0]->image_path)[0].'.webp' : "/icon/no_img.webp";
																@endphp
																<a href="{{ asset('storage/') }}{{ $img }}" data-toggle="mobile-lightbox" data-gallery="{{ $row['name'] }}" data-title="{{ $row['name'] }}">
																	{{-- <img src="{{ asset('storage/') .''. $img }}" alt="{{ Illuminate\Support\Str::slug(explode('.', $img)[0], '-') }}" class="search-img img-responsive hover w-100"> --}}
																	@if(isset($row['item_image_paths'][0]) && !Storage::disk('public')->exists('/img/'.explode('.', $row['item_image_paths'][0]->image_path)[0].'.webp'))
																		<img src="{{ asset('storage/').$img }}" class="img w-100">
																	@elseif(isset($row['item_image_paths'][0]) && !Storage::disk('public')->exists('/img/'.$row['item_image_paths'][0]->image_path))
																		<img src="{{ asset('storage/').$img_webp }}" class="img w-100">
																	@else
																		<picture>
																			<source srcset="{{ asset('storage'.$img_webp) }}" type="image/webp" class="img-responsive hover" style="width: 100% !important;">
																			<source srcset="{{ asset('storage'.$img) }}" type="image/jpeg" class="img-responsive hover" style="width: 100% !important;">
																			<img src="{{ asset('storage'.$img) }}" alt="{{ Illuminate\Support\Str::slug(explode('.', $img)[0], '-') }}" class="img-responsive hover" style="width: 100% !important;">
																		</picture>
																	@endif
																</a>
				
																<div class="modal fade" id="mobile-{{ $row['name'] }}-images-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
																	<div class="modal-dialog" role="document">
																		<div class="modal-content">
																			<div class="modal-header">
																			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																				<span aria-hidden="true">&times;</span>
																			</button>
																			</div>
																			<div class="modal-body">
																				<div id="image-container" class="container-fluid">
																					<div id="carouselExampleControls" class="carousel slide" data-interval="false">
																						<div class="carousel-inner">
																							<div class="carousel-item active">
																								{{-- <img class="d-block w-100" id="mobile-{{ $row['name'] }}-image" src="{{ asset('storage/').$img }}" alt="{{ Illuminate\Support\Str::slug(explode('.', $img)[0], '-') }}"> --}}
																								<picture>
																									<source id="mobile-{{ $row['name'] }}-webp-image-src" srcset="{{ asset('storage/').$img_webp }}" type="image/webp" class="d-block w-100" style="width: 100% !important;">
																									<source id="mobile-{{ $row['name'] }}-orig-image-src" srcset="{{ asset('storage/').$img }}" type="image/jpeg" class="d-block w-100" style="width: 100% !important;">
																									<img class="d-block w-100" id="mobile-{{ $row['name'] }}-image" src="{{ asset('storage/').$img }}" alt="{{ Illuminate\Support\Str::slug(explode('.', $img)[0], '-') }}">
																								</picture>
																							</div>
																							<span class='d-none' id="mobile-{{ $row['name'] }}-image-data">0</span>
																						</div>
																						<a class="carousel-control-prev" href="#carouselExampleControls" onclick="prevImg('{{ $row['name'] }}')" role="button" data-slide="prev" style="color: #000 !important">
																							<span class="carousel-control-prev-icon" aria-hidden="true"></span>
																							<span class="sr-only">Previous</span>
																						</a>
																						<a class="carousel-control-next" href="#carouselExampleControls" onclick="nextImg('{{ $row['name'] }}')" role="button" data-slide="next" style="color: #000 !important">
																							<span class="carousel-control-next-icon" aria-hidden="true"></span>
																							<span class="sr-only">Next</span>
																						</a>
																					</div>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
																
																{{-- <a href="#" class="view-item-details mt-2 mb-2 d-block" data-item-code="{{ $row['name'] }}" data-item-classification="{{ $row['item_classification'] }}"> --}}
																<a href="/get_item_details/{{ $row['name'] }}">
																	<div class="btn btn-sm btn-primary w-100">
																		<i class="fa fa-search font-responsive"></i> <span class="d-inline font-responsive">View</span>
																	</div>
																</a>
															</div>
															<div class="col-9 col-lg-10 col-xl-9">
																<span class="font-italic item-class">{{ $row['item_classification'] }} - {!! $row['item_group'] !!}</span><br/>
																<span class="text-justify item-name"><span style="font-weight: 900 !important">{{ $row['name'] }}</span> - {!! strip_tags($row['description']) !!}</span>
																@if ($row['part_nos'])
																	<br>
																	<span class="text-justify item-name"><b>Part No(s)</b> {{ $row['part_nos'] }} </span>
																@endif
																@if (in_array($user_department, $allowed_department) && !in_array(Auth::user()->user_group, ['Manager', 'Director']) && $row['default_price'] > 0) 
																<p class="mt-3 mb-2">
																	<span class="d-block font-weight-bold" style="font-size: 15pt;">{{ '₱ ' . number_format($row['default_price'], 2, '.', ',') }}</span>
																	<span class="d-block" style="font-size: 9pt;">Standard Selling Price</span>
																</p>
																@endif
																@if(in_array(Auth::user()->user_group, ['Manager', 'Director']) && $row['default_price'] > 0)
																<p class="mt-3 mb-2">
																	<span class="d-block font-weight-bold" style="font-size: 15pt;">{{ '₱ ' . number_format($row['default_price'], 2, '.', ',') }}</span>
																	<span class="d-block" style="font-size: 9pt;">Standard Selling Price</span>
																</p>
																@endif
																<div class="d-none d-md-block">
																	@if ($row['item_inventory'])
																		<table class="table table-sm table-bordered warehouse-table table-striped">
																			<thead>
																				<tr>
																					<th class="text-center wh-cell">Warehouse</th>
																					<th class="text-center qtr-cell text-muted">Reserved Qty</th>
																					<th class="text-center qtr-cell">Available Qty</th>
																				</tr>
																			</thead>
																			@foreach($row['item_inventory'] as $inv)
																				<tr>
																					<td class="text-center" >
																						{{ $inv['warehouse'] }}
																						@if ($inv['location'])
																							<small class="text-muted font-italic"> - {{ $inv['location'] }}</small>
																						@endif
																					</td>
																					<td class="text-center">
																						<small class="text-muted">{{ $inv['reserved_qty'] * 1 }}  {{ $inv['stock_uom'] }}</small>
																					</td>
																					<td class="text-center">
																						@php
																							if($inv['available_qty'] == 0){
																								$uom_badge = 'secondary';
																							}else if($inv['available_qty'] <= $inv['warehouse_reorder_level']){
																								$uom_badge = 'warning';
																							}else{
																								$uom_badge = 'success';
																							}
																						@endphp
																						<span class="badge badge-{{ $uom_badge }}" style="font-size: 14px; margin: 0 auto;">{{ $inv['available_qty'] * 1 }} <small>{{ $inv['stock_uom'] }}</small></span>
																					</td>
																				</tr>
																			@endforeach
																		</table>
																	@else
																		<div class="h-100 d-flex align-items-center">
																			<p class="pt-2 mx-auto">No Available Stock on All Warehouses</p>
																		</div>
																	@endif
																	
																	<div class="container-fluid mb-2"><!-- View Consignment Warehouse(Tablet View) -->
																		@if(Auth::user() && Auth::user()->user_group != 'Promodiser' and count($row['consignment_warehouses']) > 0)
																		<div class="text-center">
																			<a href="#" class="btn btn-primary uppercase p-1" data-toggle="modal" data-target="#tablet-vcw{{ $row['name'] }}" style="font-size: 11px;">View Consignment Warehouse</a>
																		</div>
							
																		<div class="modal fade" id="tablet-vcw{{ $row['name'] }}" tabindex="-1" role="dialog">
																			<div class="modal-dialog modal-xl" role="document">
																				<div class="modal-content">
																					<div class="modal-header">
																						<h5 class="modal-title consignment-head">{{ $row['name'] }} - Consignment Warehouse(s) </h5>
																						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
																					</div>
																					<form></form>
																					<div class="modal-body">
																						<table class="table table-hover m-0" style='font-size: 10pt;'>
																							<col style="width: 70%;">
																							<col style="width: 30%;">
																							<tr>
																								<th class="consignment-th text-center">Warehouse</th>
																								<th class="consignment-th text-center">Available Qty</th>
																								<th class="consignment-th text-center">In Store</th>
																							</tr>
																							@forelse($row['consignment_warehouses'] as $con)
																							<tr>
																								<td class="consignment-name">
																									{{ $con['warehouse'] }}
																									@if ($con['location'])
																										<small class="text-muted font-italic">- {{ $con['location'] }}</small>
																									@endif
																								</td>
																								<td class="text-center">
																									<span class="badge badge-{{ ($con['available_qty'] > 0) ? 'success' : 'secondary' }}" style="font-size: 15px; margin: 0 auto;">{{ $con['actual_qty'] * 1 }} <small>{{ $con['stock_uom'] }}</small></span>
																								</td>
																								<td class="text-center">
																									<span class="badge badge-{{ ($con['consigned_qty'] > 0) ? 'success' : 'secondary' }}" style="font-size: 15px; margin: 0 auto;">{{ $con['consigned_qty'] * 1 }} <small>{{ $con['stock_uom'] }}</small></span>
																								</td>
																							</tr>
																							@empty
																							<tr>
																								<td class="text-center font-italic" colspan="3">NO WAREHOUSE ASSIGNED</td>
																							</tr>
																							@endforelse
																						</table>
																					</div>
																					<div class="modal-footer">
																						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
																					</div>
																				</div>
																			</div>
																		</div>
																		@endif
																	</div><!-- View Consignment Warehouse -->
																</div>
															</div>
														</div>
														<div class="row m-0 p-1 d-block d-md-none">
															<div class="container-fluid mb-1">
																@if ($row['item_inventory'])
																	<table class="table table-sm table-bordered warehouse-table table-striped m-0 p-0">
																		<thead>
																			<tr>
																				<th class="text-center wh-cell">Warehouse</th>
																				<th class="text-center qtr-cell text-muted">Reserved Qty</th>
																				<th class="text-center qtr-cell">Available Qty</th>
																			</tr>
																		</thead>
																		@foreach($row['item_inventory'] as $inv)
																			<tr>
																				<td class="text-center" >
																					{{ $inv['warehouse'] }}
																					@if ($inv['location'])
																						<small class="text-muted font-italic"> - {{ $inv['location'] }}</small>
																					@endif
																				</td>
																				<td class="text-center">
																					<small class="text-muted">{{ $inv['reserved_qty'] * 1 }}  {{ $inv['stock_uom'] }}</small>
																				</td>
																				<td class="text-center">
																					{{-- @if($inv['available_qty'] == 0)
																						<span class="badge badge-secondary" style="font-size: 14px; margin: 0 auto;">{{ $inv['available_qty'] * 1 . ' ' . $inv['stock_uom'] }}</span>
																					@elseif($inv['available_qty'] <= $inv['warehouse_reorder_level'])
																						<span class="badge badge-warning" style="font-size: 14px; margin: 0 auto;">{{ $inv['available_qty'] * 1 . ' ' . $inv['stock_uom'] }}</span>
																					@else
																						<span class="badge badge-success" style="font-size: 14px; margin: 0 auto;">{{ $inv['available_qty'] * 1 . ' ' . $inv['stock_uom'] }}</span>
																					@endif --}}
																					@php
																						if($inv['available_qty'] == 0){
																							$uom_badge = 'secondary';
																						}else if($inv['available_qty'] <= $inv['warehouse_reorder_level']){
																							$uom_badge = 'warning';
																						}else{
																							$uom_badge = 'success';
																						}
																					@endphp
																					<span class="badge badge-{{ $uom_badge }}" style="font-size: 14px; margin: 0 auto;">{{ $inv['available_qty'] * 1 }} <small>{{ $inv['stock_uom'] }}</small></span>
																				</td>
																			</tr>
																		@endforeach
																	</table>
																@else
																	<p class="text-center pt-2 font-responsive">No Available Stock on All Warehouses</p>
																@endif
																
																<div class="container-fluid"><!-- View Consignment Warehouse(Mobile View) -->
																	@if(Auth::user() && Auth::user()->user_group != 'Promodiser' and count($row['consignment_warehouses']) > 0)
																	<div class="text-center">
																		<a href="#" class="btn btn-primary uppercase p-1" data-toggle="modal" data-target="#mobile-vcw{{ $row['name'] }}" style="font-size: 11px;">View Consignment Warehouse</a>
																	</div>
						
																	<div class="modal fade" id="mobile-vcw{{ $row['name'] }}" tabindex="-1" role="dialog">
																		<div class="modal-dialog modal-xl" role="document">
																			<div class="modal-content">
																				<div class="modal-header">
																					<h4 class="modal-title consignment-head">{{ $row['name'] }} - Consignment Warehouse(s) </h4>
																					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
																				</div>
																				<form></form>
																				<div class="modal-body">
																					<table class="table table-hover m-0">
																						<col style="width: 70%;">
																						<col style="width: 30%;">
																						<tr>
																							<th class="consignment-th text-center">Warehouse</th>
																							<th class="consignment-th text-center">Available Qty</th>
																							<th class="consignment-th text-center">In Store</th>
																						</tr>
																						@forelse($row['consignment_warehouses'] as $con)
																						<tr>
																							<td class="consignment-name">
																								{{ $con['warehouse'] }}
																								@if ($con['location'])
																									<small class="text-muted font-italic">- {{ $con['location'] }}</small>
																								@endif
																							</td>
																							<td class="text-center">
																								<span class="badge badge-{{ ($con['available_qty'] > 0) ? 'success' : 'secondary' }}" style="font-size: 15px; margin: 0 auto;">{{ $con['actual_qty'] * 1 . ' ' . $con['stock_uom'] }}</span>
																							</td>
																							<td class="text-center">
																								<span class="badge badge-{{ ($con['consigned_qty'] > 0) ? 'success' : 'secondary' }}" style="font-size: 15px; margin: 0 auto;">{{ $con['consigned_qty'] * 1 }} <small>{{ $con['stock_uom'] }}</small></span>
																							</td>
																						</tr>
																						@empty
																						<tr>
																							<td class="text-center font-italic" colspan="3">NO WAREHOUSE ASSIGNED</td>
																						</tr>
																						@endforelse
																					</table>
																				</div>
																				<div class="modal-footer">
																					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
																				</div>
																			</div>
																		</div>
																	</div>
																	@endif
																</div><!-- View Consignment Warehouse -->
															</div>
														</div>
													</div>
												@empty
													<div class="col-md-12 text-center" style="padding: 25px;">
														<h5>No result(s) found / Stocks not available</h5>
													</div>
												@endforelse
											</div><!-- new table -->
				
											<div class="mt-3 ml-3 clearfix pagination" style="display: block;">
												<div class="col-md-4 float-right">
													{{ $items->links() }}
												</div>
											</div>
										</div>
									</div>
									
								</div><!-- Card End -->
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>

<style>
	html,body
{
    width: 100% !important;
    height: 100% !important;
    margin: 0px !important;
    padding: 0px !important;
    overflow-x: hidden !important; 
	position:relative;
}
	.itemClassContainer{
		min-height: 1px;
		/* overflow: auto; */
		white-space: nowrap;
		z-index: -9999;
	}
	.itemClassBubble{
		color: #000;
		text-decoration: none !important;
		text-transform: none !important;
		transition: .4s;
		padding: 1px;
		background-color: rgba(255,255,255, 0);
		border: none;
	}

	.responsive-item-code{
		font-size: 12pt
	}
	.responsive-description{
		font-size: 10pt
	}
	.category-btn-grp{
		transition: .4s;
	}

	.category-btn-grp:hover{
		box-shadow: 8px 1px 11px #001F3F;
	}

	.cLink{
		text-decoration: none !important;
		text-transform: none !important;
	}

	.tbl-custom-hover:hover,
		th.hover,
		td.hover,
		tr.hoverable:hover {
			background-color: #DCDCDC;
		}

		.nohover:hover {
		background-color: #fff;
		}
	
	.search-img{
		width: 100%;
		max-width: 100%;
	}
	.search-thumbnail{
		width: 200px;
	}
	.item-class{
		font-size: 12px;
	}
	.item-name, .warehouse-table{
		font-size: 13px;
	}
	.wh-cell{
		width: 50%;
	}
	.qty-cell{
		width: 25%;
	}
	.pagination{
		font-size: 15px;
	}
	.category-abbr-btn{
		background-color: #001F3F;
		color: #fff;
		border-radius: 5px 0 0 5px;
		font-size: 20px;
	}
	.category-name-btn{
		background-color: #fff;
		border-radius: 0 5px 5px 0
	}
	.stock-ledger-table-font{
		font-size: 11pt;
	}
	.category-btn{
		transition: .4s;
	}
	.category-btn:hover{
		box-shadow: #001F3F 2px 2px 8px;
	}
	.custom-border{
		box-shadow: 8px 1px 10px #001F3F;
	}

	.modal.left .modal-dialog{
		position: fixed;
		margin: auto;
		width: 320px !important;
		height: 100%;
		-webkit-transform: translate3d(0%, 0, 0);
		    -ms-transform: translate3d(0%, 0, 0);
		     -o-transform: translate3d(0%, 0, 0);
		        transform: translate3d(0%, 0, 0);
	}

	.modal.left .modal-content{
		height: 100%;
		overflow-y: auto;
	}
	
	.modal.left .modal-body{
		padding: 15px 15px 80px;
	}

	/*Left*/
	.modal.left.fade .modal-dialog{
		-webkit-transition: opacity 0.3s linear, left 0.3s ease-out;
		   -moz-transition: opacity 0.3s linear, left 0.3s ease-out;
		     -o-transition: opacity 0.3s linear, left 0.3s ease-out;
		        transition: opacity 0.3s linear, left 0.3s ease-out;
	}
	
	.modal.left.fade.in .modal-dialog{
		left: 0;
	}

	.filter-container{
		text-align: right;
	}

	.checkbox-container{
		text-align: center;
	}

	.tree li {
		list-style-type:none;
		margin:0;
		/* padding:10px 5px 0 5px; */
		position:relative
	}
	.tree li::before, 
	.tree li::after {
		content:'';
		left:-20px;
		position:absolute;
		right:auto
	}
	.tree li::before {
		border-left:2px solid #000;
		bottom:50px;
		height:100%;
		top:0;
		width:1px
	}
	.tree li::after {
		border-top:2px solid #000;
		height:20px;
		top:15px;
		width:25px
	}
	.tree li span {
		-moz-border-radius:5px;
		-webkit-border-radius:5px;
		border-radius:3px;
		display:inline-block;
		padding:3px 8px;
		text-decoration:none;
		cursor:pointer;
		transition: .4s;
		color: #000;
	}

	.tree>ul>li::before,
	.tree>ul>li::after {
		border:0
	}
	.tree li:last-child::before {
		height:15px
	}

	[aria-expanded="false"] > .expanded,
	[aria-expanded="true"] > .collapsed {
		display: none;
	}

	.tree-item{
		color: #000;
		transition: .4s;
	}

	.tree-item:hover{
		color: #fff !important;
		background-color: #001F3F !important;
	}

	.selected-tree-item{
		background-color: #001F3F;
		color: #fff !important;
	}

	.general-filter-label{
		text-align: right;
	}

	.select2-container--default .select2-selection--single{
		padding: 0 !important;
		height: 25px !important;
	}

	.select2-container--default .select2-selection--single .select2-selection__arrow {
		height: 25px !important;
	}

	.filter-font{
		font-size: 9pt;
	}

	.total{
		font-size: 13pt;
	}

	@media (max-width: 575.98px) {
        .font-responsive, .responsive-item-code, .stock-ledger-table-font, .total{
			font-size: 10pt !important;
		}
		.item-class, .item-name{
			font-size: 9pt !important;
		}
		.search-img, .search-thumbnail{
			max-width: 220px !important;
		}
		.consignment-head{
			font-size: 11pt;
		}
		.wh-cell{
			width: 40% !important;
		}
		.qty-cell{
			width: 30% !important;
		}
		.badge, .consignment-name, .warehouse-table, .consignment-th{
			font-size: 8pt !important;
		}
		.pagination{
			font-size: 9pt !important;
			padding: 0 !important;
			margin: 0 auto !important;
		}
		.page-link{
			padding: 10px !important;
		}
		.category-abbr-btn{
			font-size: 16px;
		}
		.filter-container{
			text-align: left !important;
		}
		.checkbox-container{
			text-align: left !important;
		}
		.general-filter-label{
			text-align: left;
		}
		.filter-font{
			font-size: 8pt;
		}
    }
  	@media (max-width: 767.98px) {
        .font-responsive, .responsive-description, .stock-ledger-table-font, .total{
			font-size: 10pt !important;
		}
		.search-img, .search-thumbnail{
			max-width: 220px !important;
		}
		.consignment-head{
			font-size: 11pt;
		}
		.wh-cell{
			width: 40% !important;
		}
		.qty-cell{
			width: 30% !important;
		}
		.badge, .consignment-name, .warehouse-table, .consignment-th{
			font-size: 8pt !important;
		}
		.pagination{
			font-size: 9pt !important;
			padding: 0 !important;
			margin: 0 auto !important;
		}
		.page-link{
			padding: 10px !important;
		}
		.category-abbr-btn{
			font-size: 16px;
		}
		.filter-container{
			text-align: left !important;
		}
		.checkbox-container{
			text-align: left !important;
		}
		.general-filter-label{
			text-align: left;
		}
		.filter-font{
			font-size: 8pt;
		}
    }
	@media only screen and (min-device-width : 768px) and (max-device-width : 1024px) and (orientation : portrait) {
		.total{
			font-size: 10pt !important;
		}
		.modal.left .modal-dialog{
			width: 240px;
		}
		.filter-container{
			text-align: left !important;
		}
		.checkbox-container{
			text-align: left !important;
		}
		.general-filter-label{
			text-align: left;
		}
		.filter-font{
			font-size: 8pt;
		}
	}
	@media only screen and (min-device-width : 768px) and (orientation : landscape) {
		.total{
			font-size: 10pt !important;
		}
		.filter-font{
			font-size: 8pt;
		}
	}
</style>
@endsection

@section('script')
	<script>
		$(document).ready(function (){
			if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) { // mobile/tablet
				$('#collapseOne').removeClass('show');
			}else{ // desktop
				$('#collapseOne').addClass('show');
			}
		});
	</script>
@endsection	