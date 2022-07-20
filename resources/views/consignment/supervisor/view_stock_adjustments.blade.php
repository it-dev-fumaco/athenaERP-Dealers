@extends('layout', [
    'namePage' => 'Stock Adjustments',
    'activePage' => 'dashboard',
])

@section('content')
<div class="content">
	<div class="content-header p-0">
        <div class="container">
            <div class="row pt-1">
                <div class="col-md-12 p-0 m-0">
                    <div class="row">
                        <div class="col-2">
                            <div style="margin-bottom: -43px;">
                                <a href="/" class="btn btn-secondary" style="width: 80px;"><i class="fas fa-arrow-left"></i></a>
                            </div>
                        </div>
                        <div class="col-8 col-lg-8 p-0">
                            <h4 class="text-center font-weight-bold m-2 text-uppercase">Stock Adjustments</h4>
                        </div>
                    </div>
                    <div class="card card-secondary card-outline">
                        <div class="card-body p-2">
                            @if(session()->has('success'))
                            <div class="callout callout-success font-responsive text-center pr-1 pl-1 pb-3 pt-3 m-2">{{ session()->get('success') }}</div>
                            @endif
                            @if(session()->has('error'))
                            <div class="callout callout-danger font-responsive text-center pr-1 pl-1 pb-3 pt-3 m-2">{{ session()->get('error') }}</div>
                            @endif
                            <ul class="nav nav-pills">
                                <li class="nav-item">
                                    <a class="nav-link active font-responsive" id="pending-tab" data-toggle="pill" href="#pending-content" role="tab" href="#">Stock Adjustment History</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link font-responsive" id="inventory-audit-history-tab" data-toggle="pill" href="#inventory-audit-history-content" role="tab" href="#">Activity Logs</a>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="pending-content" role="tabpanel" aria-labelledby="pending-tab">
                                    <form action="/beginning_inv_list" method="get">
                                        <div id="accordion" class="mt-2">
                                            <button type="button" class="btn btn-link border-bottom btn-block text-left d-xl-none d-lg-none" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne" style="font-size: 10pt;">
                                                <i class="fa fa-filter"></i> Filters
                                            </button>
                                            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                                                <div class="row p-2">
                                                    <div class="col-12 col-lg-3 col-xl-3">
                                                        <input type="text" class="form-control filters-font" name="search" value="{{ request('search') ? request('search') : null }}" placeholder="Search"/>
                                                    </div>
                                                    <div class="col-12 col-lg-2 col-xl-2 mt-2 mt-lg-0">
                                                        @php
                                                            $statuses = ['For Approval', 'Approved', 'Cancelled'];
                                                        @endphp
                                                        <select name="status" class="form-control filters-font">
                                                            <option value="" disabled {{ Auth::user()->user_group == 'Promodiser' && !request('status') ? 'selected' : null }}>Select a status</option>
                                                            <option value="All" {{ request('status') ? ( request('status') == 'All' ? 'selected' : null) : null }}>Select All</option>
                                                            @foreach ($statuses as $status)
                                                            @php
                                                                $selected = null;
                                                                if(request('status')){
                                                                    if(request('status') == $status){
                                                                        $selected = 'selected';
                                                                    }
                                                                }else{
                                                                    if(Auth::user()->user_group == 'Consignment Supervisor'){
                                                                        $selected = $status == 'For Approval' ? 'selected' : null;
                                                                    }
                                                                }
                                                            @endphp
                                                            <option value="{{ $status }}" {{ $selected }}>{{ $status }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-12 col-lg-2 col-xl-2 mt-2 mt-lg-0">
                                                        <select name="store" class="form-control filters-font" id="consignment-store-select">
                                                            <option value="" disabled {{ !request('store') ? 'selected' : null }}>Select a store</option>
                                                            @foreach ($consignment_stores as $store)
                                                            <option value="{{ $store }}" {{ request('store') == $store ? 'selected' : null }}>{{ $store }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-12 col-lg-3 col-xl-3 mt-2 mt-lg-0">
                                                        <input type="text" name="date" id="date-filter" class="form-control filters-font" value="" />
                                                    </div>
                                                    <div class="col-12 col-lg-2 col-xl-2 mt-2 mt-lg-0">
                                                        <button type="submit" class="btn btn-primary filters-font btn-block"><i class="fas fa-search"></i> Search</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <table class="table table-striped" style="font-size: 9pt;">
                                        <thead>
                                            <th class="font-responsive align-middle p-2 text-center d-none d-lg-table-cell">Date</th>
                                            <th class="font-responsive align-middle p-2 text-center">Store</th>
                                            <th class="font-responsive align-middle p-2 text-center">Total Qty</th>
                                            <th class="font-responsive align-middle p-2 text-center">Total Value</th>
                                            <th class="font-responsive align-middle p-2 text-center d-none d-lg-table-cell">Submitted by</th>
                                            <th class="font-responsive align-middle p-2 text-center d-none d-lg-table-cell">Status</th>
                                            <th class="font-responsive align-middle p-2 text-center last-ro1w">Action</th>
                                        </thead>
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
                                                <td class="font-responsive align-middle p-2 text-center d-none d-lg-table-cell">
                                                    <span style="white-space: nowrap">{{ $inv['transaction_date'] }}</span>
                                                </td>
                                                <td class="font-responsive align-middle p-2 text-left text-xl-center">
                                                    <span class="d-block">{{ $inv['branch'] }}</span>
                                                    <small class="d-block text-left d-lg-none">
                                                        <span class="d-block"><b>By:</b>&nbsp;{{ $inv['owner'] }}</span>
                                                        <span class="d-block"><b>Date:</b>&nbsp;{{ $inv['transaction_date'] }}</span>
                                                    </small>
                                                </td>
                                                <td class="font-responsive align-middle p-2 text-center">{{ number_format($inv['qty']) }}</td>
                                                <td class="font-responsive align-middle p-2 text-center">{{ '₱ ' . number_format($inv['amount'], 2) }}</td>
                                                <td class="font-responsive align-middle p-2 text-center d-none d-lg-table-cell">{{ $inv['owner'] }}</td>
                                                <td class="font-responsive align-middle p-2 text-center d-none d-lg-table-cell">
                                                    <span class="badge badge-{{ $badge }}">{{ $inv['status'] }}</span>
                                                </td>
                                                <td class="font-responsive align-middle p-2 text-center">
                                                    <a href="#" class="d-block modal-trigger" data-branch='{{ $inv["branch"] }}' data-toggle="modal" data-target="#{{ $inv['name'] }}-Modal">View Items</a>
                                                    <span class="badge badge-{{ $badge }} d-xl-none d-lg-none">{{ $inv['status'] }}</span>
                                                        
                                                    <div class="modal fade" id="{{ $inv['name'] }}-Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-xl" role="document">
                                                            <div class="modal-content">
                                                                <form action="{{ $modal_form }}" method="post">
                                                                    @csrf
                                                                    <div class="modal-header bg-navy">
                                                                        <h6 class="modal-title">{{ $inv['branch'] }} <span class="badge badge-{{ $badge }} d-inline-block ml-2">{{ $inv['status'] }}</span></h6>
                                                                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="row">
                                                                            <div class="pt-0 pr-2 pl-2 pb-2 col-6 col-lg-4 col-xl-4 text-left">
                                                                                <dl class="row">
                                                                                    <dt class="col-12 col-xl-4 col-lg-6">Inventory Date:</dt>
                                                                                    <dd class="col-12 col-xl-8 col-lg-6">{{ $inv['transaction_date'] }}</dd>
                                                                                  
                                                                                    <dt class="col-12 col-xl-4 col-lg-6">Submitted By:</dt>
                                                                                    <dd class="col-12 col-xl-8 col-lg-6">{{ $inv['owner'] }}</dd>
                                                                                </dl>
                                                                            </div>
                                                                            <div class="pt-0 pr-2 pl-2 pb-2 col-6 col-lg-4 col-xl-4 text-left">
                                                                                <dl class="row">
                                                                                    <dt class="col-12 col-xl-4 col-lg-6">Total Qty:</dt>
                                                                                    <dd class="col-12 col-xl-8 col-lg-6">{{ number_format($inv['qty']) }}</dd>
                                                                                  
                                                                                    <dt class="col-12 col-xl-4 col-lg-6">Total Value:</dt>
                                                                                    <dd class="col-12 col-xl-8 col-lg-6">{{ '₱ ' . number_format($inv['amount'], 2) }}</dd>
                                                                                </dl>
                                                                            </div>
                                                                            <div class="pt-0 pr-2 pl-2 pb-2 col-12 col-lg-4 col-xl-4 text-left">
                                                                                @if (Auth::user()->user_group == 'Consignment Supervisor' && $inv['status'] == 'For Approval')
                                                                                @php
                                                                                    $status_selection = [
                                                                                        ['title' => 'Approve', 'value' => 'Approved'],
                                                                                        ['title' => 'Cancel', 'value' => 'Cancelled']
                                                                                    ];
                                                                                @endphp
                                                                                        
                                                                                <div class="input-group">
                                                                                    <select class="custom-select font-responsive" name="status" required>
                                                                                        <option value="" selected disabled>Select a status</option>
                                                                                        @foreach ($status_selection as $status)
                                                                                        <option value="{{ $status['value'] }}">{{ $status['title'] }}</option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                    <div class="input-group-append">
                                                                                        <button class="btn btn-primary" type="submit" id="{{ $inv['name'] }}-submit">Submit</button>
                                                                                    </div>
                                                                                </div>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                        
                                                                        <span id="item-count-{{ $inv['name'] }}" class="d-none">{{ count($inv['items']) }}</span>
                                                                        <table class="table table-striped items-table" style="font-size: 10pt;">
                                                                            <thead>
                                                                                <th class="text-center p-2 align-middle col-lg-4 col-3" style="width: 36%">Item Code</th>
                                                                                <th class="text-center p-2 align-middle col-lg-2 col-3" style='width: 16%'>Opening Stock</th>
                                                                                <th class="text-center p-2 align-middle col-lg-2 col-3" style='width: 16%'>Price</th>
                                                                                @if ($inv['status'] == 'Approved')
                                                                                    <th class="text-center p-2 align-middle col-lg-2 col-3"style='width: 16%'>-</th>
                                                                                @else
                                                                                    <th class="text-center p-2 align-middle col-lg-2 col-3"style='width: 16%'>Action</th>
                                                                                @endif
                                                                            </thead>
                                                                            @forelse ($inv['items'] as $i => $item)
                                                                                @php
                                                                                    $target = $inv['name'].'-'.$item['item_code'];
                                                                            
                                                                                    $img = $item['image'] ? "/img/" . $item['image'] : "/icon/no_img.png";
                                                                                    $img_webp = $item['image'] ? "/img/" . explode('.', $item['image'])[0].'.webp' : "/icon/no_img.webp";
                                                                                @endphp
                                                                                <tr id="row-{{ $target }}">
                                                                                    <td class="text-center p-1 align-middle">
                                                                                        <div class="d-flex flex-row justify-content-start align-items-center" id="{{ $target }}-container">
                                                                                            <div class="p-2 text-left">
                                                                                                <a href="{{ asset('storage/') }}{{ $img }}" data-toggle="mobile-lightbox" data-gallery="{{ $item['item_code'] }}" data-title="{{ $item['item_code'] }}">
                                                                                                    <picture>
                                                                                                        <source srcset="{{ asset('storage'.$img_webp) }}" type="image/webp" width="60" height="60">
                                                                                                        <source srcset="{{ asset('storage'.$img) }}" type="image/jpeg" width="60" height="60">
                                                                                                        <img src="{{ asset('storage'.$img) }}" alt="{{ str_slug(explode('.', $img)[0], '-') }}" width="60" height="60">
                                                                                                    </picture>
                                                                                                </a>
                                                                                            </div>
                                                                                            <div class="p-2 text-left">
                                                                                                <b>{!! ''.$item['item_code'] !!}</b>
                                                                                                <span class="d-none d-xl-inline"> - {!! strip_tags($item['item_description']) !!}</span>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="p-2 w-100 text-left d-none" id="{{ $target }}-selection">
                                                                                            <div class="row">
                                                                                                <div class="col-9">
                                                                                                    <select class="form-control replacement-item" id="{{ $target }}-replacement" data-original-code='{{ $item['item_code'] }}' style="width: 200px !important;"></select>
                                                                                                </div>
                                                                                                <div class="col-3" style="display: flex; justify-content: center; align-items: center;">
                                                                                                    <span class="undo-replacement" data-item-code="{{ $item['item_code'] }}" data-target="{{ $target }}" data-name="{{ $inv['name'] }}"><i class="fa fa-undo"></i> Reset</span>
                                                                                                </div>
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
                                                                                                        <div class="container-fluid">
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
                                                                                    <td class="text-center p-1 align-middle text-nowrap">
                                                                                        <b id="{{ $inv['name'].'-'.$item['item_code'] }}-qty">{!! $item['opening_stock'] !!}<br></b>
                                                                                        @if ($inv['status'] == 'Approved')
                                                                                            <input id="{{ $inv['name'].'-'.$item['item_code'] }}-new-qty" type="text" class="form-control text-center d-none" name="item[{{ $item['item_code'] }}][qty]" value={{ $item['opening_stock'] }} style="font-size: 10pt;"/>
                                                                                        @endif
                                                                                        <small>{{ $item['uom'] }}</small>
                                                                                    </td>
                                                                                    <td class="text-center p-1 align-middle">
                                                                                        @if (Auth::user()->user_group == 'Consignment Supervisor' && $inv['status'] == 'For Approval')
                                                                                            ₱ <input type="text" name="price[{{ $item['item_code'] }}][]" id="item-price-{{ $item['item_code'] }}" value="{{ number_format($item['price'], 2) }}" style="text-align: center; width: 60px" required/>
                                                                                            <input id="item-qty-{{ $item['item_code'] }}" type="text" class="d-none" name="qty[{{ $item['item_code'] }}][]" value={{ $item['opening_stock'] }} style="font-size: 10pt;"/>
                                                                                        @elseif ($inv['status'] == 'Approved')
                                                                                            <input id="{{ $inv['name'].'-'.$item['item_code'] }}-new-price" type="text" class="form-control text-center d-none" name="item[{{ $item['item_code'] }}][price]" value={{ $item['price'] }} style="font-size: 10pt;"/>
                                                                                            <span id="{{ $inv['name'].'-'.$item['item_code'] }}-price">₱ {{ number_format($item['price'], 2) }}</span>
                                                                                        @else
                                                                                            ₱ {{ number_format($item['price'], 2) }}
                                                                                        @endif
                                                                                    </td>
                                                                                    @if ($inv['status'] == 'Approved')
                                                                                        <td class="text-center p-1 align-middle">
                                                                                            <span class="btn btn-primary btn-xs edit-stock_qty" data-reference="{{ $inv['name'].'-'.$item['item_code'] }}" data-name="{{ $inv['name'] }}"><i class="fa fa-edit"></i></span>
                                                                                        </td>
                                                                                    @else
                                                                                        <td class="text-center p-1 align-middle">
                                                                                            <div class="btn-group" role="group" aria-label="Basic example">
                                                                                                <button type="button" class="btn btn-xs btn-outline-primary p-1 change-item" data-target="{{ $target }}" style="font-size: 9pt;">Change</button>
                                                                                                <button type="button" class="btn btn-xs btn-outline-secondary p-1 remove-item" data-name="{{ $inv['name'] }}" data-target="{{ $target }}" style="font-size: 9pt;">Remove</button>
                                                                                            </div>
                                                                                        </td>
                                                                                    @endif
                                                                                </tr>
                                                                                <tr class="d-xl-none">
                                                                                    <td colspan="4" class="text-justify pt-0 pb-1 pl-1 pr-1" style="border-top: 0 !important;">
                                                                                        <div class="w-100 item-description">{!! strip_tags($item['item_description']) !!}</div>
                                                                                    </td>
                                                                                </tr>
                                                                            @empty
                                                                            <tr>
                                                                                <td class="text-center text-uppercase text-muted" colspan="4">No Item(s)</td>
                                                                            </tr>
                                                                            @endforelse
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
                                                                                            <a href="/cancel/approved_beginning_inv/{{ $inv['name'] }}" class="btn btn-primary w-100">Confirm</a>
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
                                                <td class="font-responsive text-center text-muted text-uppercase p-2" colspan="7">
                                                    No submitted beginning inventory
                                                </td>
                                            </tr>
                                        @endforelse
                                    </table>
                                    <div class="float-right mt-4">
                                        {{ $beginning_inventory->appends(request()->input())->links('pagination::bootstrap-4') }}
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="inventory-audit-history-content" role="tabpanel" aria-labelledby="inventory-audit-history-tab">
                                    <div id="activity-logs-el" class="p-2"></div>
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

@section('style')
    <style>
        table .items-table{
            table-layout: fixed;
            width: 100%;   
        }
        .morectnt span {
            display: none;
        }
        .last-row{
            width: 20% !important;
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
        .undo-replacement{
            cursor: pointer;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }
        @media (max-width: 575.98px) {
            .last-row{
                width: 35%;
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
            .last-row{
                width: 35%;
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
            .last-row{
                width: 35%;
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
            loadActivityLogs();
            function loadActivityLogs(page) {
                $.ajax({
                    type: "GET",
                    url: "/get_activity_logs?page=" + page ,
                    success: function (response) {
                        $('#activity-logs-el').html(response);
                    }
                });
            }

            $('.remove-item').click(function (){
                var name = $(this).data('name');
                var target = $(this).data('target');

                remove_row(name, target);
                validate_submit(name);
            });

            function remove_row(name, target){
                $('#row-' + target).remove();
                $('#item-count-' + name).text(parseInt($('#item-count-' + name).text()) - 1);
            }

            function validate_submit(name){
                var count = parseInt($('#item-count-' + name).text());
                if(count <= 0){
                    $('#' + name + '-submit').prop('disabled', true);
                }else{
                    $('#' + name + '-submit').prop('disabled', false);
                }
            }

            $('.change-item').click(function (){
                $('#'+$(this).data('target')+'-container').removeClass('d-flex').addClass('d-none');
                $('#'+$(this).data('target')+'-selection').removeClass('d-none');
                $('#'+$(this).data('target')+'-replacement').prop('required', true);
            });

            $('.undo-replacement').click(function(){
                var replaced_code = $('#'+$(this).data('target')+'-replacement').val();
                if(replaced_code != ''){
                    excluded_items_arr = jQuery.grep(excluded_items_arr, function(value){
                        return value != replaced_code;
                    });
                }

                $('#'+$(this).data('target')+'-container').addClass('d-flex').removeClass('d-none');
                $('#'+$(this).data('target')+'-selection').addClass('d-none');
                $('#'+$(this).data('target')+'-replacement').prop('required', false);
                $('#'+$(this).data('target')+'-replacement').empty().trigger('change');

                $('#item-price-'+$(this).data('item-code')).attr('name', 'price[' + $(this).data('item-code') + '][]');
                $('#item-qty-'+$(this).data('item-code')).attr('name', 'qty[' + $(this).data('item-code') + '][]');
            });

            var excluded_items_arr = new Array();
            $('.modal-trigger').click(function(){
                excluded_items_arr = [];
                var active_branch = $(this).data('branch');
                $('.replacement-item').select2({
                    templateResult: formatState,
                    placeholder: 'Select an Item',

                    ajax: {
                        url: '/get_items/'+active_branch,
                        method: 'GET',
                        dataType: 'json',
                        data: function (data) {
                            return {
                                q: data.term, // search term
                                excluded_items: excluded_items_arr,
                                all_items: 1
                            };
                        },
                        processResults: function (response) {
                            return {
                                results: response.items
                            };
                        },
                        cache: true
                    }
                });
            });

            $(document).on('select2:select', '.replacement-item', function(e){
                var item_code = e.params.data.id;
                excluded_items_arr.push(item_code);
                var original_item_code = $(this).data('original-code');

                $('#item-price-'+original_item_code).attr('name', 'price[' + item_code + '][]');
                $('#item-qty-'+original_item_code).attr('name', 'qty[' + item_code + '][]');
            });

            function formatState (opt) {
                if (!opt.id) {
                    return opt.text;
                }

                var optimage = opt.image_webp;
                if(optimage.indexOf('/icon/no_img') != -1){
                    optimage = opt.image;
                }

                if(!optimage){
                    return opt.text;
                } else {
                    var $opt = $(
                    '<span><img src="' + optimage + '" width="40px" /> ' + opt.text + '</span>'
                    );
                    return $opt;
                }
            };

            $(document).on('click', '#activity-logs-pagination a', function(event){
                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                loadActivityLogs(page);
            });
            
            var from_date = '{{ request("date") ? Carbon\Carbon::parse(explode(" to ", request("date"))[0])->format("Y-M-d") : Carbon\Carbon::now()->subDays(7)->format("Y-M-d")  }}';
            var to_date = '{{ request("date") ? Carbon\Carbon::parse(explode(" to ", request("date"))[1])->format("Y-M-d") : Carbon\Carbon::now()->format("Y-M-d")  }}';
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

            var showTotalChar = 150, showChar = "Show more", hideChar = "Show less";
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

            $('#consignment-store-select').select2({
                placeholder: "Select Store",
                ajax: {
                    url: '/consignment_stores',
                    method: 'GET',
                    dataType: 'json',
                    data: function (data) {
                        return {
                            q: data.term // search term
                        };
                    },
                    processResults: function (response) {
                        return {
                            results: response
                        };
                    },
                    cache: true
                }
            });
        });
    </script>
@endsection