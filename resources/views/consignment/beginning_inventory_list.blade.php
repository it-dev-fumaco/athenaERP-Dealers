@extends('layout', [
    'namePage' => 'Stock Adjustments List',
    'activePage' => 'dashboard',
])

@section('content')
<div class="content">
	<div class="content-header p-0">
		<div class="container">
			<div class="row pt-1">
				<div class="col-md-12 p-0 m-0">
					<div class="card card-lightblue">
						<div class="card-header p-2">
                            <div class="d-flex flex-row align-items-center justify-content-between" style="font-size: 9pt;">
                                <div class="p-0">
                                    <span class="font-responsive font-weight-bold text-uppercase m-0 p-0">Stock Adjustments List</span>
                                </div>
                                <div class="p-0">
                                    <a href="/beginning_inventory" class="btn btn-sm btn-primary m-0"><i class="fas fa-plus"></i> Create</a>
                                </div>
                            </div>
                        </div>
						<div class="card-body p-0">
							@if(session()->has('success'))
							<div class="callout callout-success font-responsive text-center pr-1 pl-1 pb-3 pt-3 m-2">{{ session()->get('success') }}</div>
							@endif
							@if(session()->has('error'))
							<div class="callout callout-danger font-responsive text-center pr-1 pl-1 pb-3 pt-3 m-2">{{ session()->get('error') }}</div>
							@endif
							<div id="accordion">
								<button type="button" class="btn btn-link border-bottom btn-block text-left" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne" style="font-size: 10pt;">
									<i class="fa fa-filter"></i> Filters
								</button>
								<div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
									<div class="card-body p-0">
										<form action="/beginning_inv_list" method="get">
											<div class="row p-2">
												<div class="col-12 col-lg-4 col-xl-4">
													<input type="text" class="form-control filters-font" name="search" value="{{ request('search') ? request('search') : null }}" placeholder="Search"/>
												</div>
												<div class="col-12 col-lg-2 col-xl-2 mt-2 mt-lg-0">
													<select name="store" class="form-control filters-font">
														<option value="" disabled {{ !request('store') ? 'selected' : null }}>Select a store</option>
														@foreach ($consignment_stores as $store)
														<option value="{{ $store }}" {{ request('store') == $store ? 'selected' : null }}>{{ $store }}</option>
														@endforeach
													</select>
												</div>
												<div class="col-12 col-lg-4 col-xl-2 mt-2 mt-lg-0">
													<input type="text" name="date" id="date-filter" class="form-control filters-font" value="" />
												</div>
												<div class="col-12 col-lg-2 col-xl-1 mt-2 mt-lg-0">
													<button type="submit" class="btn btn-primary filters-font w-100"><i class="fas fa-search"></i> Search</button>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
							
							<table class="table" style="font-size: 9pt;">
								<thead>
									<th class="p-1 text-center align-middle d-none d-lg-table-cell">Date</th>
									<th class="p-1 text-center align-middle mobile-first">Store</th>
									<th class="p-1 text-center align-middle d-none d-lg-table-cell">Total items</th>
									<th class="p-1 text-center align-middle d-none d-lg-table-cell">Amount</th>
									<th class="p-1 text-center align-middle d-none d-lg-table-cell">Submitted by</th>
									<th class="p-1 text-center align-middle d-none d-lg-table-cell">Status</th>
									<th class="p-1 text-center align-middle last-row">Action</th>
								</thead>
								<tbody>
								@forelse ($inv_arr as $inv)
									@php
										$badge = 'secondary';
										if($inv['status'] == 'For Approval'){
											$badge = 'primary';
										}else if($inv['status'] == 'Approved'){
											$badge = 'success';
										}else if($inv['status'] == 'Cancelled'){
											$badge = 'secondary';
										}

										$modal_form = Auth::user()->user_group == 'Consignment Supervisor' && $inv['status'] == 'For Approval' ? '/approve_beginning_inv/'.$inv['name'] : '/stock_adjust/submit/'.$inv['name'];
									@endphp
									<tr>
										<td class="p-2 text-center align-middle d-none d-lg-table-cell">
											<span style="white-space: nowrap">{{ $inv['transaction_date'] }}</span>
										</td>
										<td class="p-2 text-left align-middle text-xl-center">
											<span class="d-block">{{ $inv['branch'] }}</span>
											<small class="d-lg-none">By: {{ $inv['owner'] }} - {{ $inv['transaction_date'] }}</small>
											<div class="row p-0 d-lg-none">
												<div class="col-4"><b>Qty: </b>{{ number_format($inv['qty']) }}</div>
												<div class="col-8"><b>Amount: </b>₱ {{ number_format($inv['amount'], 2) }}</div>
											</div>
										</td>
										<td class="p-2 text-center align-middle d-none d-lg-table-cell">{{ number_format($inv['qty']) }}</td>
										<td class="p-2 text-center align-middle d-none d-lg-table-cell">₱ {{ number_format($inv['amount'], 2) }}</td>
										<td class="p-2 text-center align-middle d-none d-lg-table-cell">{{ $inv['owner'] }}</td>
										<td class="p-2 text-center align-middle d-none d-lg-table-cell">
											<span class="badge badge-{{ $badge }}">{{ $inv['status'] }}</span>
										</td>
										<td class="text-center align-middle p-2">
											<a href="#" data-toggle="modal" data-target="#{{ $inv['name'] }}-Modal">View Items</a>
											<span class="badge badge-{{ $badge }} d-xl-none">{{ $inv['status'] }}</span>
													
											<div class="modal fade" id="{{ $inv['name'] }}-Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
												<div class="modal-dialog modal-xl modal-dialog-centered" role="document">
													<div class="modal-content">
														<form action="{{ $modal_form }}" method="post">
															@csrf
															<div class="modal-header bg-navy">
																<div class="row text-left">
																	<div class="col-12">
																		<h5>Beginning Inventory</h5>
																	</div>
																	<div class="col-12">
																		<h6 class="font-responsive">{{ $inv['branch'] }}</h6>
																	</div>
																</div>
																<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
																	<span aria-hidden="true">&times;</span>
																</button>
															</div>
															<div class="modal-body p-2">
																<span class="d-block text-left">Inventory Date:<b>{{ $inv['transaction_date'] }}</b></span>
																<span class="d-block text-left">Submitted By:<b>{{ $inv['owner'] }}</b></span>
															
																<table class="table mt-2" style="font-size: 9pt;">
																	<thead>
																		<th class="text-center p-1 align-middle">Item Code</th>
																		<th class="text-center p-1 align-middle">Opening Stock</th>
																		<th class="text-center p-1 align-middle">Price</th>
																		@if ($inv['status'] == 'Approved')
																		<th class="text-center p-1 align-middle">-</th>
																		@endif
																	</thead>
																	<tbody>
																		@forelse ($inv['items'] as $item)
																		@php
																			$img = $item['image'] ? "/img/" . $item['image'] : "/icon/no_img.png";
																			$img_webp = $item['image'] ? "/img/" . explode('.', $item['image'])[0].'.webp' : "/icon/no_img.webp";
																		@endphp
																		<tr>
																			<td class="text-center p-1 align-middle">
																				<div class="d-flex flex-row justify-content-start align-items-center">
																					<div class="p-1 text-left">
																						<a href="{{ asset('storage/') }}{{ $img }}" data-toggle="mobile-lightbox" data-gallery="{{ $item['item_code'] }}" data-title="{{ $item['item_code'] }}">
																							<picture>
																							<source srcset="{{ asset('storage'.$img_webp) }}" type="image/webp" alt="{{ str_slug(explode('.', $img)[0], '-') }}" width="40" height="40">
																							<source srcset="{{ asset('storage'.$img) }}" type="image/jpeg" alt="{{ str_slug(explode('.', $img)[0], '-') }}" width="40" height="40">
																							<img src="{{ asset('storage'.$img) }}" alt="{{ str_slug(explode('.', $img)[0], '-') }}" width="40" height="40">
																							</picture>
																						</a>
																					</div>
																					<div class="p-1 m-0">
																						<span class="font-weight-bold">{{ $item['item_code'] }}</span>
																					</div>
																				</div>


																				<div class="modal fade" id="mobile-{{ $item['item_code'] }}-images-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
																					<div class="modal-dialog modal-dialog-centered" role="document">
																					  <div class="modal-content">
																						 <div class="modal-header">
																							<h5 class="modal-title">{{ $item['item_code'] }}</h5>
																							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																							  <span aria-hidden="true">&times;</span>
																							</button>
																						 </div>
																						 <div class="modal-body">
																							<form></form>
																							<div id="image-container" class="container-fluid">
																										 <div id="carouselExampleControls" class="carousel slide" data-interval="false">
																											  <div class="carousel-inner">
																													<div class="carousel-item active">
																														 <picture>
																															  <source id="mobile-{{ $item['item_code'] }}-webp-image-src" srcset="{{ asset('storage/').$img_webp }}" type="image/webp" class="d-block w-100" style="width: 100% !important;">
																															  <source id="mobile-{{ $item['item_code'] }}-orig-image-src" srcset="{{ asset('storage/').$img }}" type="image/jpeg" class="d-block w-100" style="width: 100% !important;">
																															  <img class="d-block w-100" id="mobile-{{ $item['item_code'] }}-image" src="{{ asset('storage/').$img }}" alt="{{ Illuminate\Support\Str::slug(explode('.', $img)[0], '-') }}">
																														 </picture>
																													</div>
																													<span class='d-none' id="mobile-{{ $item['item_code'] }}-image-data">0</span>
																											  </div>
																											  @if ($item['img_count'] > 1)
																											  <a class="carousel-control-prev" href="#carouselExampleControls" onclick="prevImg('{{ $item['item_code'] }}')" role="button" data-slide="prev" style="color: #000 !important">
																													<span class="carousel-control-prev-icon" aria-hidden="true"></span>
																													<span class="sr-only">Previous</span>
																											  </a>
																											  <a class="carousel-control-next" href="#carouselExampleControls" onclick="nextImg('{{ $item['item_code'] }}')" role="button" data-slide="next" style="color: #000 !important">
																													<span class="carousel-control-next-icon" aria-hidden="true"></span>
																													<span class="sr-only">Next</span>
																											  </a>
																											  @endif
																										 </div>
																									</div>
																							  </div>
																						 </div>
																					</div>
																			  </div>
																			</td>
																			<td class="text-center p-1 align-middle">
																				<b id="{{ $inv['name'].'-'.$item['item_code'] }}-qty">{!! $item['opening_stock'] !!}</b>
																				@if ($inv['status'] == 'Approved')
																				<input id="{{ $inv['name'].'-'.$item['item_code'] }}-new-qty" type="text" class="form-control text-center d-none" name="item[{{ $item['item_code'] }}][qty]" value={{ $item['opening_stock'] }} style="font-size: 10pt;"/>
																				@endif
																				<small class="d-block">{{ $item['uom'] }}</small>
																			</td>
																			<td class="text-center p-1 align-middle" style="white-space: nowrap">
																				@if (Auth::user()->user_group == 'Consignment Supervisor' && $inv['status'] == 'For Approval')
																				₱ <input type="text" name="price[{{ $item['item_code'] }}][]" value="{{ number_format($item['price'], 2) }}" style="text-align: center; width: 60px" required/>
																				@elseif ($inv['status'] == 'Approved')
																				<input id="{{ $inv['name'].'-'.$item['item_code'] }}-new-price" type="text" class="form-control text-center d-none" name="item[{{ $item['item_code'] }}][price]" value={{ $item['price'] }} style="font-size: 10pt;"/>
																				<span id="{{ $inv['name'].'-'.$item['item_code'] }}-price">₱ {{ number_format($item['price'], 2) }}</span>
																				@else
																				₱ {{ number_format($item['price'], 2) }}
																				@endif
																			</td>
																			@if ($inv['status'] == 'Approved')
																			<td class="text-center">
																				<span class="btn btn-primary btn-xs edit-stock_qty" data-reference="{{ $inv['name'].'-'.$item['item_code'] }}" data-name="{{ $inv['name'] }}"><i class="fa fa-edit"></i></span>
																			</td>
																			@endif
																		</tr>
																		<tr class="d-xl-none">
																			<td colspan="4" class="text-justify pt-0 pb-1 pl-1 pr-1" style="border-top: 0 !important;">
																				<div class="w-100 item-description">{{ strip_tags($item['item_description']) }}</div>
																			</td>
																		</tr>
																		@empty
																		<tr>
																			<td class="text-center text-uppercase text-muted" colspan="4">No Item(s)</td>
																		</tr>
																		@endforelse
																	</tbody>
																</table>
															</div>
															{{-- Update button for approved records --}}
															@if ($inv['status'] == 'Approved')
															<div class="modal-footer">
																<div class="container-fluid" id="{{ $inv['name'] }}-stock-adjust-update-btn" style="display: none">
																	<button type="submit" class="btn btn-info w-100">Update</button>
																</div>
																<div class="container-fluid">
																	<button type="button" class="btn btn-secondary w-100" data-toggle="modal" data-target="#cancel-{{ $inv['name'] }}-Modal">
																		Cancel
																	</button>
																	  
																	  <!-- Modal -->
																	<div class="modal fade" id="cancel-{{ $inv['name'] }}-Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
																		<div class="modal-dialog" role="document">
																			<div class="modal-content">
																				<div class="modal-header bg-navy">
																					<h6 id="exampleModalLabel">Cancel Beginning Inventory?</h6>
																					<button type="button" class="close">
																					<span aria-hidden="true" style="color: #fff" onclick="close_modal('#cancel-{{ $inv['name'] }}-Modal')">&times;</span>
																					</button>
																				</div>
																				<div class="modal-body">
																					@if ($inv['sold'])
																						<div class="callout callout-danger text-justify">
																							<i class="fas fa-info-circle"></i> Canceling beginnning inventory record will also cancel submitted product sold records of the following:
																						</div>
																						<div class="container-fluid" id="cancel-{{ $inv['name'] }}-container">
																							<table class="table">
																								<tr>
																									<th class="text-center" style='width: 60%;'>Item</th>
																									<th class="text-center" style="width: 20%;">Qty</th>
																									<th class="text-center" style="width: 20%;">Amount</th>
																								</tr>
																								@foreach($inv['sold'] as $item)
																									<tr>
																										<td class="p-0" colspan=3>
																											<div class="p-0 row">
																												<div class="col-6">
																													<div class="row">
																														<div class="col-4">
																															<picture>
																																<source srcset="{{ asset('storage'.$item['webp']) }}" type="image/webp">
																																<source srcset="{{ asset('storage'.$item['image']) }}" type="image/jpeg">
																																<img src="{{ asset('storage'.$item['image']) }}" alt="{{ str_slug(explode('.', $item['image'])[0], '-') }}" width="40" height="40">
																															</picture>
																														</div>
																														<div class="col-8" style="display: flex; justify-content: center; align-items: center;">
																															<b>{{ $item['item_code'] }}</b>
																														</div>
																													</div>
																												</div>
																												<div class="col-3 pt-2">
																													<b>{{ number_format($item['qty']) }}</b> <br>
																													<small>{{ $item['uom'] }}</small>
																												</div>
																												<div class="col-3" style="display: flex; justify-content: center; align-items: center;">
																													₱ {{ number_format($item['price'], 2) }}
																												</div>
																											</div>
																											<div class="text-justify item-description">
																												{{ $item['description'] }}
																											</div>
																											<div class="text-justify pt-1 pb-2">
																												<b>Transaction Date:</b>&nbsp;{{ Carbon\Carbon::parse($item['date'])->format('F d, Y') }}
																											</div>
																										</td>
																									</tr>
																								@endforeach
																							</table>
																						</div>
																					@else
																						<div class="callout callout-danger text-justify">
																							<i class="fas fa-info-circle"></i> Canceling beginnning inventory record will also cancel submitted product sold records.
																						</div>
																					@endif
																				</div>
																				<div class="modal-footer">
																					<a href="/cancel/approved_beginning_inv/{{ $inv['name'] }}" class="btn btn-primary w-100 submit-once">Confirm</a>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
															@endif
														</form>
													</div>
												</div>
											</div>
										</td>
									</tr>
								@empty
									<tr>
										<td class="text-center text-uppercase text-muted" colspan="7">No submitted beginning inventory</td>
									</tr>
								@endforelse

								</tbody>
								
						  </table>
						  <div class="float-right mt-4">
								{{ $beginning_inventory->links('pagination::bootstrap-4') }}
						  </div>
						
						  </div>
						</div>
              
                </div>
            </div>
        </div>
	</div>
</div>
@endsection

@section('style')
    <style>
        .morectnt span {
            display: none;
        }
        .last-row{
            width: 15% !important;
        }
		.mobile-first{
			width: 35% !important;
		}
        .filters-font{
            font-size: 13px !important;
        }
        .item-code-container{
            text-align: justify;
            padding: 10px;
        }
		.modal{
			background-color: rgba(0,0,0,0.4);
		}
        @media (max-width: 575.98px) {
			.mobile-first{
				width: 50% !important;
			}
            .last-row{
                width: 20%;
            }
            .filters-font{
                font-size: 9pt;
            }
            .item-code-container{
                 display: flex;
                 justify-content: center;
                 align-items: center;
            }
        }
        @media (max-width: 767.98px) {
			.mobile-first{
				width: 50% !important;
			}
            .last-row{
                width: 20%;
            }
            .filters-font{
                font-size: 9pt;
            }
            .item-code-container{
                 display: flex;
                 justify-content: center;
                 align-items: center;
            }
        }
        @media only screen and (min-device-width : 768px) and (max-device-width : 1024px) and (orientation : portrait) {
			.mobile-first{
				width: 50% !important;
			}
            .last-row{
                width: 20%;
            }
            .filters-font{
                font-size: 9pt;
            }
            .item-code-container{
                 display: flex;
                 justify-content: center;
                 align-items: center;
            }
        }
    </style>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            var from_date = '{{ request("date") ? Carbon\Carbon::parse(explode(" to ", request("date"))[0])->format("Y-M-d") : $earliest_date }}';
            var to_date = '{{ request("date") ? Carbon\Carbon::parse(explode(" to ", request("date"))[1])->format("Y-M-d") : Carbon\Carbon::now()->format("Y-M-d") }}';

            $('#date-filter').daterangepicker({
                opens: 'left',
                startDate: from_date,
                endDate: to_date,
                locale: {
                    format: 'YYYY-MMM-DD',
                    separator: " to "
                },
            });

            $(document).on('click', '.show-more', function(e) {
                e.preventDefault();
                if ($(this).hasClass("sample")) {
                    $(this).removeClass("sample");
                    $(this).text(showChar);
                } else {
                    $(this).addClass("sample");
                    $(this).text(hideChar);
                }

                $(this).parent().prev().toggle();
                $(this).prev().toggle();
                return false;
            });

            $(document).on('click', '.edit-stock_qty', function(){
                var reference = $(this).data('reference');
                $('#'+reference+'-qty').addClass('d-none');
                $('#'+reference+'-new-qty').removeClass('d-none');
				$('#'+reference+'-price').addClass('d-none');
                $('#'+reference+'-new-price').removeClass('d-none');
                $('#'+$(this).data('name')+'-stock-adjust-update-btn').slideDown();
            });

            var showTotalChar = 98, showChar = "Show more", hideChar = "Show less";
            $('.item-description').each(function() {
                var content = $(this).text();
                if (content.length > showTotalChar) {
                    var con = content.substr(0, showTotalChar);
                    var hcon = content.substr(showTotalChar, content.length - showTotalChar);
                    var txt = con + '<span class="dots">...</span><span class="morectnt"><span>' + hcon + '</span>&nbsp;&nbsp;<a href="#" class="show-more">' + showChar + '</a></span>';
                    $(this).html(txt);
                }
            });

            // always show filters on pc, allow collapse of filters on mobile
            if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) { // mobile/tablet
				$('#headingOne').removeClass('d-none');
                $('#collapseOne').removeClass('show');
			}else{ // desktop
                $('#headingOne').addClass('d-none');
                $('#collapseOne').addClass('show');
			}
        });
    </script>
@endsection