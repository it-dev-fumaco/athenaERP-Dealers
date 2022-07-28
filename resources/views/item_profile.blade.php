@extends('layout', [
    'namePage' => 'Item Profile',
    'activePage' => 'item_profile',
])

@section('content')
    <div class="container-fluid p-3">
        <div class="row">
            <div class="col-md-12">
                <div class="back-btn">
                    <img src="{{ asset('storage/icon/back.png') }}" style="width: 45px; cursor: pointer;" id="back-btn">
                </div>
                <ul class="nav nav-tabs" role="tablist" style="font-size: 10pt;">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#item-info">
                            <span class="d-none d-md-block">Item Info</span>
                            <i class="fas fa-info d-block d-md-none"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="get-athena-transactions" data-toggle="tab" href="#athena-logs">
                            <span class="d-none d-md-block">Athena Transactions</span>
                            <i class="fas fa-boxes d-block d-md-none"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#history">
                            <span class="d-none d-md-block">ERP Submitted Transaction Histories</span>
                            <i class="fas fa-history d-block d-md-none"></i>
                        </a>
                    </li>
                    @if(Auth::check() and in_array(Auth::user()->user_group, ['Inventory Manager']))
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tab_4">
                            <span class="d-none d-md-block">Stock Reservations</span>
                            <i class="fas fa-warehouse d-block d-md-none"></i>
                        </a>
                    </li>
                    @endif
                    @if (in_array($user_group, ['Manager', 'Director']))
                    <li class="nav-item">
                        <a class="nav-link d-none d-md-block" data-toggle="tab" href="#purchase-history">Purchase Rate History</a>
                        <a class="nav-link d-block d-md-none" data-toggle="tab" href="#purchase-history"><i class="fa fa-shopping-cart"></i></a>
                    </li>
                    @endif
                </ul>
                <div class="tab-content">
                    <div id="item-info" class="container-fluid tab-pane active bg-white">
                        <div class="row">
                            @php
                                $mngr_col = null;
                                if(in_array($user_department, $allowed_department) && !in_array($user_group, ['Manager', 'Director']) && $default_price > 0 || in_array($user_group, ['Manager', 'Director'])){
                                    $mngr_col = 'col-lg-9';
                                }
                            @endphp
                            <div class="col-12 {{ $mngr_col }} col-xl-9">
                                <div class="box box-solid mt-2">
                                    <div class="row">
                                        @php
                                            $img_1 = (array_key_exists(0, $item_images)) ? '/img/' . $item_images[0] : '/icon/no_img.png';
                                            $img_1_webp = (array_key_exists(0, $item_images)) ? '/img/' . explode('.', $item_images[0])[0].'.webp' : '/icon/no_img.webp';
                                            $img_1_alt = (array_key_exists(0, $item_images)) ? Illuminate\Support\Str::slug(explode('.', $img_1)[0], '-') : null;
                                            
                                            $img_2 = (array_key_exists(1, $item_images)) ? '/img/' . $item_images[1] : '/icon/no_img.png';
                                            $img_2_webp = (array_key_exists(1, $item_images)) ? '/img/' . explode('.', $item_images[1])[0].'.webp' : '/icon/no_img.webp';
                                            $img_2_alt = (array_key_exists(1, $item_images)) ? Illuminate\Support\Str::slug(explode('.', $img_2)[0], '-') : null;
                                            
                                            $img_3 = (array_key_exists(2, $item_images)) ? '/img/' . $item_images[2] : '/icon/no_img.png';
                                            $img_3_webp = (array_key_exists(2, $item_images)) ? '/img/' . explode('.', $item_images[2])[0].'.webp' : '/icon/no_img.webp';
                                            $img_3_alt = (array_key_exists(2, $item_images)) ? Illuminate\Support\Str::slug(explode('.', $img_3)[0], '-') : null;
                                            
                                            $img_4 = (array_key_exists(3, $item_images)) ? '/img/' . $item_images[3] : '/icon/no_img.png';
                                            $img_4_webp = (array_key_exists(3, $item_images)) ? '/img/' . explode('.', $item_images[3])[0].'.webp' : '/icon/no_img.webp';
                                            $img_4_alt = (array_key_exists(3, $item_images)) ? Illuminate\Support\Str::slug(explode('.', $img_4)[0], '-') : null;
                                        @endphp
                                        <div class="col-md-3 col-lg-2">
                                            <div class="row">
                                                <div class="col-12">
                                                    <a href="{{ asset('storage/') . $img_1 }}" data-toggle="lightbox" data-gallery="{{ $item_details->name }}" data-title="{{ $item_details->name }}">
                                                        <picture>
                                                            <source srcset="{{ asset('storage'.$img_1_webp) }}" type="image/webp" alt="{{ $img_1_alt }}">
                                                            <source srcset="{{ asset('storage'.$img_1) }}" type="image/jpeg" alt="{{ $img_1_alt }}">
                                                            <img src="{{ asset('storage/') .''. $img_1 }}" alt="{{ $img_1_alt }}" class="img-responsive {{ array_key_exists(0, $item_images) ? null : '' }}" style="width: 100% !important; {{ array_key_exists(0, $item_images) ? null : 'min-height: 200px' }}">
                                                        </picture>
                                                    </a>
                                                </div>
                                                <div class="col-4 mt-2">
                                                    <a href="{{ asset('storage/'.$img_2) }}" data-toggle="lightbox" data-gallery="{{ $item_details->name }}" data-title="{{ $item_details->name }}">
                                                        <picture>
                                                            <source srcset="{{ asset('storage'.$img_2_webp) }}" type="image/webp" alt="{{ $img_2_alt }}">
                                                            <source srcset="{{ asset('storage'.$img_2) }}" type="image/jpeg" alt="{{ $img_2_alt }}">
                                                            <img src="{{ asset('storage'.$img_2) }}" alt="{{ $img_2_alt }}" alt="{{ $img_2_alt }}" class="img-responsive hover" style="width: 100% !important;">
                                                        </picture>
                                                    </a>
                                                </div>
                                                <div class="col-4 mt-2"> 
                                                    <a href="{{ asset('storage/'.$img_3) }}" data-toggle="lightbox" data-gallery="{{ $item_details->name }}" data-title="{{ $item_details->name }}">
                                                        <picture>
                                                            <source srcset="{{ asset('storage'.$img_3_webp) }}" type="image/webp" alt="{{ $img_3_alt }}">
                                                            <source srcset="{{ asset('storage'.$img_3) }}" type="image/jpeg" alt="{{ $img_3_alt }}">
                                                            <img src="{{ asset('storage'.$img_3) }}" alt="{{ $img_3_alt }}" alt="{{ $img_3_alt }}" class="img-responsive hover" style="width: 100% !important;">
                                                        </picture>
                                                    </a>
                                                </div>
                                                <div class="col-4 mt-2">
                                                    <a href="{{ asset('storage'.$img_4) }}" data-toggle="lightbox" data-gallery="{{ $item_details->name }}" data-title="{{ $item_details->name }}">
                                                        <div class="text-white">
                                                            <picture>
                                                                <source srcset="{{ asset('storage'.$img_4_webp) }}" type="image/webp" alt="{{ $img_4_alt }}">
                                                                <source srcset="{{ asset('storage'.$img_4) }}" type="image/jpeg" alt="{{ $img_4_alt }}">
                                                                <img src="{{ asset('storage'.$img_4) }}" alt="{{ $img_4_alt }}" alt="{{ $img_4_alt }}" class="img-responsive hover" style="width: 100% !important;">
                                                            </picture>
                                                            @if(count($item_images) > 4)
                                                                <div class="card-img-overlay text-center">
                                                                    <h5 class="card-title m-1 font-weight-bold">MORE</h5>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="col-md-12 text-center pt-3">
                                                    <button class="btn btn-primary btn-sm upload-item-image w-100" data-item-code="{{ $item_details->name }}"><i class="fa fa-camera" style="font-size: 20px"></i></button>
                                                </div>

                                                <div class="modal fade" id="{{ $item_details->name }}-images-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                                                                <picture>
                                                                                    <source id="{{ $item_details->name }}-webp-image-src" srcset="{{ asset('storage/').$img_1_webp }}" type="image/webp" class="d-block w-100" style="width: 100% !important;">
                                                                                    <source id="{{ $item_details->name }}-orig-image-src" srcset="{{ asset('storage/').$img_1 }}" type="image/jpeg" class="d-block w-100" style="width: 100% !important;">
                                                                                    <img class="d-block w-100" id="{{ $item_details->name }}-image" src="{{ asset('storage/').$img_1 }}" alt="{{ Illuminate\Support\Str::slug(explode('.', $img_1)[0], '-') }}">
                                                                                </picture>
                                                                            </div>
                                                                            <span class='d-none' id="{{ $item_details->name }}-image-data">0</span>
                                                                        </div>
                                                                        <a class="carousel-control-prev" href="#carouselExampleControls" onclick="prevImg('{{ $item_details->name }}')" role="button" data-slide="prev" style="color: #000 !important">
                                                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                                            <span class="sr-only">Previous</span>
                                                                        </a>
                                                                        <a class="carousel-control-next" href="#carouselExampleControls" onclick="nextImg('{{ $item_details->name }}')" role="button" data-slide="next" style="color: #000 !important">
                                                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                                            <span class="sr-only">Next</span>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-9 col-lg-10">
                                            <br class="d-block d-md-none"/>
                                            <dl class="ml-3">
                                                <dt class="responsive-item-code" style="font-size: 14pt;"><span id="selected-item-code">{{ $item_details->name }}</span> {{ $item_details->brand }}</dt>
                                                <dd class="responsive-description" style="font-size: 11pt;" class="text-justify mb-2">{!! $item_details->description !!}</dd>
                                            </dl>
                                            <div class="d-block d-lg-none">
                                                <p class="mt-2 mb-2 text-center">
                                                    @if (in_array($user_department, $allowed_department) && !in_array($user_group, ['Manager', 'Director']) && $default_price > 0) 
                                                    <span class="d-block font-weight-bold mt-3" style="font-size: 17pt;">{{ '₱ ' . number_format($default_price, 2, '.', ',') }}</span>
                                                    <span class="d-block responsive-description" style="font-size: 11pt;">Standard Selling Price</span>
                                                    @if ($is_tax_included_in_rate)
                                                    <small class="text-muted font-italic" style="font-size: 7.5pt;">* VAT inclusive</small>
                                                    @endif
                                                    @endif

                                                    @if (in_array($user_group, ['Manager', 'Director']))
                                                        @if ($default_price > 0)
                                                        <span class="d-block font-weight-bold mt-3" style="font-size: 17pt;">{{ '₱ ' . number_format($default_price, 2, '.', ',') }}</span>
                                                        <span class="d-block" style="font-size: 11pt;">Standard Selling Price</span>
                                                        @if ($is_tax_included_in_rate)
                                                        <small class="text-muted font-italic" style="font-size: 7.5pt;">* VAT inclusive</small>
                                                        @endif
                                                        @endif
                                                        @if ($minimum_selling_price > 0)
                                                        <span class="d-block font-weight-bold mt-3" style="font-size: 15pt;">{{ '₱ ' . number_format($minimum_selling_price, 2, '.', ',') }}</span>
                                                        <span class="d-block" style="font-size: 9pt;">Minimum Selling Price</span>
                                                        @endif
                                                        @if ($last_purchase_rate > 0)
                                                        <span class="d-block font-weight-bold mt-3" style="font-size: 11pt;">{{ '₱ ' . number_format($last_purchase_rate, 2, '.', ',') }}</span>
                                                        <span class="d-inline-block" style="font-size: 9pt;">Last Purchase Rate</span>
                                                        <span class="d-inline-block font-weight-bold font-italic" style="font-size: 9pt;">- {{ $last_purchase_date }}</span>
                                                        @endif
                                                        @if ($avgPurchaseRate > 0)
                                                        <span class="d-block font-weight-bold avg-purchase-rate-div mt-3" style="font-size: 11pt;">{{ $avgPurchaseRate }}</span>
                                                        <span class="d-inline-block" style="font-size: 9pt;">Average Purchase Rate</span>
                                                        @endif
                                                    @endif
                                                </p>
                                            </div>
                                            <div class="card-header border-bottom-0 p-1 ml-3">
                                                <h3 class="card-title m-0 font-responsive"><i class="fa fa-box-open"></i> Stock Level</h3>
                                                @if(in_array($user_group, ['Warehouse Personnel', 'Inventory Manager']) and $site_warehouses)
                                                    <button type="button" class="btn btn-primary p-1 float-right" data-toggle="modal" data-target="#warehouseLocationModal" style="font-size: 12px;">
                                                        Update Warehouse Location
                                                    </button>
                                                    
                                                    <div class="modal fade" id="warehouseLocationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Update Warehouse Location</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form action="/edit_warehouse_location" method="post">
                                                                        @csrf
                                                                        @forelse ($site_warehouses as $warehouse)
                                                                            <div class="form-group row">
                                                                                <label for="location" class="col-12 col-form-label">{{ $warehouse['warehouse'] }}</label>
                                                                                <div class="col-12">
                                                                                    <input type="text" name="location[]" class="form-control" value="{{ $warehouse['location'] }}" placeholder="Location">
                                                                                    <input type="text" name="warehouses[]" class="d-none" value="{{ $warehouse['warehouse'] }}" readonly>
                                                                                </div>
                                                                            </div>
                                                                        @empty
                                                                            <center><p>No Stock(s)</p></center>
                                                                        @endforelse
                                                                        <input type="text" name="item_code" value="{{ $item_details->name }}" hidden readonly>
                                                                        <button class="btn btn-primary float-right" type="submit">Submit</button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="box box-solid p-0 ml-3">
                                                <div class="box-header with-border">
                                                    <div class="box-body">
                                                        <table class="table table-striped table-bordered table-hover responsive-description" style="font-size: 11pt;">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col" rowspan=2 class="font-responsive text-center p-1 align-middle">Warehouse</th>
                                                                    <th scope="col" colspan=3 class="font-responsive text-center p-1">Quantity</th>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="col" class="font-responsive text-center p-1 text-muted">Reserved</th>
                                                                    <th scope="col" class="font-responsive text-center p-1">Actual</th>
                                                                    <th scope="col" class="font-responsive text-center p-1">Available</th>
                                                                </tr>
                                                            </thead>
                                                            @forelse ($site_warehouses as $stock)
                                                            @php
                                                                $stock = collect($stock)->toArray();
                                                            @endphp
                                                            <tr>
                                                                <td class="p-1 font-responsive">
                                                                    {{ $stock['warehouse'] }}
                                                                    @if ($stock['location'])
                                                                        <small class="text-muted font-italic"> - {{ $stock['location'] }}</small>
                                                                    @endif
                                                                </td>
                                                                <td class="text-center p-1 font-responsive">
                                                                    <span class="text-muted">{{ number_format((float)$stock['reserved_qty'], 2, '.', '') .' '. $stock['stock_uom'] }}</span>
                                                                </td>
                                                                <td class="text-center p-1 font-responsive">{{ number_format((float)$stock['actual_qty'], 2, '.', '') .' '. $stock['stock_uom'] }}</td>
                                                                <td class="text-center p-1">
                                                                    <span class="badge badge-{{ ($stock['available_qty'] > 0) ? 'success' : 'secondary' }} responsive-description" style="font-size: 10pt;">{{ number_format((float)$stock['available_qty'], 2, '.', '') . ' ' . $stock['stock_uom'] }}</span>
                                                                </td>
                                                            </tr>
                                                            @empty
                                                            <tr>
                                                                <td colspan="4" class="text-center font-responsive">No Stock(s)</td>
                                                            </tr>
                                                            @endforelse
                                                        </table>
                                                        @if(count($consignment_warehouses) > 0)
                                                            <div class="text-center">
                                                                <a href="#" class="btn btn-primary uppercase p-1 responsive-description" data-toggle="modal" data-target="#vcww{{ $item_details->name }}" style="font-size: 12px;">View Consignment Warehouse</a>
                                                            </div>
                        
                                                            <div class="modal fade" id="vcww{{ $item_details->name }}" tabindex="-1" role="dialog">
                                                                <div class="modal-dialog modal-xl" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h4 class="modal-title">{{ $item_details->name }} - Consignment Warehouse(s) </h4>
                                                                            <button type="button" class="close" onclick="close_modal('#vcww{{ $item_details->name }}')" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                        </div>
                                                                        <form></form>
                                                                        <div class="modal-body">
                                                                            <table class="table table-hover m-0">
                                                                                <col style="width: 70%;">
                                                                                <col style="width: 30%;">
                                                                                <tr>
                                                                                    <th class="text-center responsive-description">Warehouse</th>
                                                                                    <th class="text-center responsive-description">Available Qty</th>
                                                                                </tr>
                                                                                @forelse($consignment_warehouses as $con)
                                                                                <tr>
                                                                                    <td class="responsive-description">
                                                                                        {{ $con['warehouse'] }}
                                                                                        @if ($con['location'])
                                                                                            <small class="text-muted font-italic"> - {{ $con['location'] }}</small>
                                                                                        @endif
                                                                                    </td>
                                                                                    <td class="text-center responsive-description"><span class="badge badge-{{ ($con['available_qty'] > 0) ? 'success' : 'secondary' }}" style="font-size: 15px; margin: 0 auto;">{{ $con['actual_qty'] * 1 . ' ' . $con['stock_uom'] }}</span></td>
                                                                                </tr>
                                                                                @empty
                                                                                <tr>
                                                                                    <td class="text-center font-italic" colspan="3">NO WAREHOUSE ASSIGNED</td>
                                                                                </tr>
                                                                                @endforelse
                                                                            </table>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-default" onclick="close_modal('#vcww{{ $item_details->name }}')">Close</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if (in_array($user_department, $allowed_department) && !in_array($user_group, ['Manager', 'Director']) && $default_price > 0 || in_array($user_group, ['Manager', 'Director'])) 
                            <div class="d-none d-lg-block col-lg-3">
                                <div class="box box-solid h-100">
                                    <div class="box-body table-responsive no-padding h-100" style="display: flex; justify-content: center; align-items: center;">
                                        <p class="mt-2 mb-2 text-center">
                                            @if (in_array($user_department, $allowed_department) && !in_array($user_group, ['Manager', 'Director']) && $default_price > 0) 
                                            <span class="d-block font-weight-bold" style="font-size: 17pt;">{{ '₱ ' . number_format($default_price, 2, '.', ',') }}</span>
                                            <span class="d-block" style="font-size: 11pt;">Standard Selling Price</span>
                                            @if ($is_tax_included_in_rate)
                                            <small class="text-muted font-italic" style="font-size: 7.5pt;">* VAT inclusive</small>
                                            @endif
                                            @endif

                                            @if (in_array($user_group, ['Manager', 'Director']))
                                                @if ($default_price > 0)
                                                <span class="d-block font-weight-bold" style="font-size: 17pt;">{{ '₱ ' . number_format($default_price, 2, '.', ',') }}</span>
                                                <span class="d-block" style="font-size: 11pt;">Standard Selling Price</span>
                                                @if ($is_tax_included_in_rate)
                                                <small class="text-muted font-italic" style="font-size: 7.5pt;">* VAT inclusive</small>
                                                @endif
                                                @endif
                                                @if ($minimum_selling_price > 0)
                                                    <span class="d-block font-weight-bold mt-3" style="font-size: 11pt;">{{ '₱ ' . number_format($minimum_selling_price, 2, '.', ',') }}</span>
                                                    <span class="d-block" style="font-size: 9pt;">Minimum Selling Price</span>
                                                @endif
                                                @if ($last_purchase_rate > 0)
                                                    <span class="d-block font-weight-bold mt-3" style="font-size: 11pt;">{{ '₱ ' . number_format($last_purchase_rate, 2, '.', ',') }}</span>
                                                    <span class="d-inline-block" style="font-size: 9pt;">Last Purchase Rate</span>
                                                    <span class="d-inline-block font-weight-bold font-italic" style="font-size: 9pt;">- {{ $last_purchase_date }}</span>
                                                @endif
                                                @if ($avgPurchaseRate > 0)
                                                <span class="d-block font-weight-bold avg-purchase-rate-div mt-3" style="font-size: 11pt;">{{ $avgPurchaseRate }}</span>
                                                <span class="d-inline-block" style="font-size: 9pt;">Average Purchase Rate</span>
                                                @endif
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if (collect($co_variants)->count() > 0)
                            <div class="col-12">
                                <div class="card-header border-bottom-0">
                                    <h3 class="card-title font-responsive mt-5"><i class="fas fa-project-diagram"></i> Variants</h3>
                                </div>
                            </div>
                            @endif
                            <div class="container col-12 mt-2">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div id="example">
                                            <table class="table table-sm table-bordered table-striped variants-table">
                                                <thead>
                                                    <tr>
                                                        <th scope="col" class="text-center align-middle" style="background-color: #CCD1D1;">Item Code</th>
                                                        @foreach ($attribute_names as $attribute_name)
                                                        <th scope="col" class="text-center align-middle" style="width: 350px;">{{ $attribute_name }}</th>
                                                        @endforeach
                                                        <th scope="col" class="text-center align-middle">Stock Availability</th>
                                                        @if (in_array($user_department, $allowed_department) && !in_array($user_group, ['Manager', 'Director'])) 
                                                        <th scope="col" class="text-center text-nowrap align-middle" style="width: 300px;">Standard Price</th>
                                                        @endif
                                                        @if (in_array($user_group, ['Manager', 'Director']))
                                                        <th scope="col" class="text-center text-nowrap align-middle" style="width: 300px;">Cost</th>
                                                        <th scope="col" class="text-center text-nowrap align-middle" style="width: 300px;">Min. Selling Price</th>
                                                        <th scope="col" class="text-center text-nowrap align-middle" style="width: 300px;">Standard Price</th>
                                                        @endif
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr class="highlight-row">
                                                        <th scope="row" class="text-center align-middle" style="background-color: #001F3F !important;">{{ $item_details->name }}</th>
                                                        @foreach ($attribute_names as $attribute_name)
                                                        <td class="text-center align-middle">{{ array_key_exists($attribute_name, $item_attributes) ? $item_attributes[$attribute_name] : null }}</td>
                                                        @endforeach
                                                        <td class="text-center align-middle text-nowrap variants-table">
                                                            <span class="badge badge-{{ ($item_stock_available > 0) ? 'success' : 'secondary' }} font-responsive">{{ ($item_stock_available > 0) ? 'In Stock' : 'Unavailable' }}</span>
                                                        </td>
                                                        @if (in_array($user_department, $allowed_department) && !in_array($user_group, ['Manager', 'Director'])) 
                                                        <td class="text-center align-middle text-nowrap">
                                                            @if ($default_price > 0)
                                                            {{ '₱ ' . number_format($default_price, 2, '.', ',') }}
                                                            @else
                                                            --
                                                            @endif
                                                        </td>
                                                        @endif
                                                        @if (in_array($user_group, ['Manager', 'Director']))
                                                        <td class="text-center align-middle text-nowrap">
                                                            @if ($manual_rate)
                                                            <center>
                                                                <span class="entered-price d-none">0.00</span>
                                                                <form action="/update_item_price/{{ $item_details->name }}" method="POST" autocomplete="off" class="update-price-form" data-id="{{ $item_details->name }}-computed-price">
                                                                    @csrf
                                                                    <div class="input-group" style="width: 120px;">
                                                                        <input type="text" class="form-control form-control-sm" name="price" placeholder="0.00" value="{{ $item_rate }}" required>
                                                                        <div class="input-group-append">
                                                                            <button class="btn btn-secondary btn-sm" type="submit"><i class="fas fa-check"></i></button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </center>
                                                            @else
                                                            @if ($item_rate > 0)
                                                                {{ '₱ ' . number_format($item_rate, 2, '.', ',') }}
                                                            @else
                                                            <center>
                                                                <span class="entered-price d-none">0.00</span>
                                                                <form action="/update_item_price/{{ $item_details->name }}" method="POST" autocomplete="off" class="update-price-form" data-id="{{ $item_details->name }}-computed-price">
                                                                    @csrf
                                                                    <div class="input-group" style="width: 120px;">
                                                                        <input type="text" class="form-control form-control-sm" name="price" placeholder="0.00" required>
                                                                        <div class="input-group-append">
                                                                            <button class="btn btn-secondary btn-sm" type="submit"><i class="fas fa-check"></i></button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </center>
                                                            @endif
                                                            @endif
                                                        </td>
                                                        <td class="text-center align-middle text-nowrap">
                                                            @if ($minimum_selling_price > 0)
                                                            <span id="{{ $item_details->name }}-computed-price-min">{{ '₱ ' . number_format($minimum_selling_price, 2, '.', ',') }}</span>
                                                            @else
                                                            <span id="{{ $item_details->name }}-computed-price-min">--</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center align-middle text-nowrap">
                                                            @if ($default_price > 0)
                                                            <span id="{{ $item_details->name }}-computed-price">{{ '₱ ' . number_format($default_price, 2, '.', ',') }}</span>
                                                            @else
                                                            <span id="{{ $item_details->name }}-computed-price">--</span>
                                                            @endif
                                                        </td>
                                                        @endif
                                                    </tr>
                                                    @foreach ($co_variants as $variant)
                                                    <tr class="variants-table">
                                                        <td class="text-center align-middle font-weight-bold text-dark" style="background-color: #CCD1D1;">
                                                            <a href="/get_item_details/{{ $variant->name }}">{{ $variant->name }}</a>
                                                        </td>
                                                        @foreach ($attribute_names as $attribute_name)
                                                        @php
                                                            $attr_val = null;
                                                            if (array_key_exists($variant->name, $attributes)) {
                                                                $attributes_variant_name = collect($attributes[$variant->name])->toArray();
                                                                $attr_val = array_key_exists($attribute_name, $attributes_variant_name) ? $attributes_variant_name[$attribute_name] : null;
                                                            }
                                                        @endphp
                                                        <td class="text-center align-middle p-2">{{ $attr_val }}</td>
                                                        @endforeach
                                                        @php
                                                            $avail_stock = array_key_exists($variant->name, $actual_variant_stocks) ? $actual_variant_stocks[$variant->name] : 0;
                                                        @endphp
                                                        <td class="text-center align-middle text-nowrap variants-table">
                                                            <span class="badge badge-{{ ($avail_stock > 0) ? 'success' : 'secondary' }} font-responsive">{{ ($avail_stock > 0) ? 'In Stock' : 'Unavailable' }}</span>
                                                        </td>
                                                        @php
                                                            $price = 0;
                                                            if(array_key_exists($variant->name, $variants_price_arr)){
                                                                $price = $variants_price_arr[$variant->name];
                                                            }
                                                        @endphp
                                                        @if (in_array($user_department, $allowed_department) && !in_array($user_group, ['Manager', 'Director'])) 
                                                        <td class="text-center align-middle text-nowrap">
                                                            @if ($price > 0)
                                                            {{ '₱ ' . number_format($price, 2, '.', ',') }}
                                                            @else
                                                            --
                                                            @endif
                                                        </td>
                                                        @endif
                                                        @if (in_array($user_group, ['Manager', 'Director']))
                                                        <td class="text-center align-middle text-nowrap">
                                                            @php
                                                                $cost = 0;
                                                                if(array_key_exists($variant->name, $variants_cost_arr)){
                                                                    $cost = $variants_cost_arr[$variant->name];
                                                                }
                                                                $is_manual = 0;
                                                                if(array_key_exists($variant->name, $manual_price_input)){
                                                                    $is_manual = $manual_price_input[$variant->name];
                                                                }
                                                            @endphp
                                                             @if ($is_manual)
                                                             <center>
                                                                 <span class="entered-price d-none">0.00</span>
                                                                 <form action="/update_item_price/{{ $variant->name }}" method="POST" autocomplete="off" class="update-price-form" data-id="{{ $variant->name }}-computed-price">
                                                                     @csrf
                                                                     <div class="input-group" style="width: 120px;">
                                                                         <input type="text" class="form-control form-control-sm" name="price" placeholder="0.00" value="{{ $cost }}" required>
                                                                         <div class="input-group-append">
                                                                             <button class="btn btn-secondary btn-sm" type="submit"><i class="fas fa-check"></i></button>
                                                                         </div>
                                                                     </div>
                                                                 </form>
                                                            </center>
                                                            @else
                                                            @if ($cost > 0)
                                                               {{ '₱ ' . number_format($cost, 2, '.', ',') }}
                                                            @else
                                                            <center>
                                                                <span class="entered-price d-none">0.00</span>
                                                                <form action="/update_item_price/{{ $variant->name }}" method="POST" autocomplete="off" class="update-price-form" data-id="{{ $variant->name }}-computed-price">
                                                                    @csrf
                                                                    <div class="input-group" style="width: 120px;">
                                                                        <input type="text" class="form-control form-control-sm" name="price" placeholder="0.00" required>
                                                                        <div class="input-group-append">
                                                                            <button class="btn btn-secondary btn-sm" type="submit"><i class="fas fa-check"></i></button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </center>
                                                            @endif
                                                            @endif
                                                        </td>
                                                        <td class="text-center align-middle text-nowrap">
                                                            @php
                                                                $minprice = 0;
                                                                if(array_key_exists($variant->name, $variants_min_price_arr)){
                                                                    $minprice = $variants_min_price_arr[$variant->name];
                                                                }
                                                            @endphp
                                                            @if ($minprice > 0)
                                                            <span id="{{ $variant->name }}-computed-price-min">{{ '₱ ' . number_format($minprice, 2, '.', ',') }}</span>
                                                            @else
                                                            <span id="{{ $variant->name }}-computed-price-min">--</span>
                                                            @endif
                                                        </td>
                                                         <td class="text-center align-middle text-nowrap">
                                                            @if ($price > 0)
                                                            <span id="{{ $variant->name }}-computed-price">{{ '₱ ' . number_format($price, 2, '.', ',') }}</span>
                                                            @else
                                                            <span id="{{ $variant->name }}-computed-price">--</span>
                                                            @endif
                                                        </td>
                                                        @endif
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="m-2">
                                    @if(isset($total_records) && $total_records > 0)
                                    @php
                                        $ends_count = 2;  //how many items at the ends (before and after [...])
                                        $middle_count = 2;  //how many items before and after current page
                                        $dots = false;
                                        $prev = $current_page - 1;
                                    @endphp
                                    <ul class="pagination">
                                        <li class="page-item {{ (1 < $current_page) ? '' : 'disabled' }}">
                                        <a href="{!! request()->fullUrlWithQuery(['page' => $prev]) !!}" class="page-link">Previous</a>
                                        </li>
                                        @for ($i = 1; $i <= $numOfPages; $i++) 
                                        @if ($i == $current_page)
                                        <li class="page-item active">
                                            <span class="page-link">{{ $i }}</span>
                                        </li>
                                        @php
                                            $dots = true;
                                        @endphp
                                        @else
                                            @if ($i <= $ends_count || ($current_page && $i >= $current_page - $middle_count && $i <= $current_page + $middle_count) || $i > $numOfPages - $ends_count) 
                                            <li class="page-item"><a class="page-link" href="{!! request()->fullUrlWithQuery(['page' => $i]) !!}">{{ $i }}</a></li>
                                            @php
                                                $dots = true;
                                            @endphp
                                            @elseif ($dots)
                                            <li class="page-item disabled">
                                                <a class="page-link" href="#">&hellip;</a>
                                            </li>
                                            @php
                                            $dots = false;
                                            @endphp
                                            @endif
                                        @endif
                                        @endfor
                                        <li class="page-item {{ ($current_page < $numOfPages || -1 == $numOfPages) ? '' : 'disabled' }}">
                                            <a class="page-link" href="{!! request()->fullUrlWithQuery(['page' => $next_page]) !!}">Next</a>
                                        </li>
                                    </ul>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="card-header border-bottom-0">
                                    <h3 class="card-title font-responsive mb-3 mt-5"><i class="fas fa-filter"></i> Item Alternatives</h3>
                                </div>
                                <div class="d-flex flex-row flex-nowrap overflow-auto">
                                    @forelse($item_alternatives as $a)
                                    @php
                                        $a = collect($a)->toArray();
                                    @endphp
                                    <div class="custom-body m-1">
                                        <div class="card card-default">
                                            <div class="card-body p-0">
                                                <div class="col-12">
                                                    <div class="d-flex flex-row">
                                                        <div class="pt-2 pb-2 pr-1 pl-1">
                                                            @php
                                                                $img = ($a['item_alternative_image']) ? '/img/' . explode('.', $a['item_alternative_image'])[0].'.jpg' : '/icon/no_img.jpg';
                                                                $img_webp = ($a['item_alternative_image']) ? '/img/' . explode('.', $a['item_alternative_image'])[0].'.webp' : '/icon/no_img.webp';
                                                            @endphp
                                                            <a href="{{ asset('storage' . $img) }}" data-toggle="lightbox" data-gallery="{{ $a['item_code'] }}" data-title="{{ $a['item_code'] }}">
                                                                <picture>
                                                                    <source srcset="{{ asset('storage'.$img_webp) }}" type="image/webp" class="rounded" width="80" height="80">
                                                                    <source srcset="{{ asset('storage'.$img) }}" type="image/jpeg" class="rounded" width="80" height="80">
                                                                    <img src="{{ asset('storage'.$img) }}" class="rounded" width="80" height="80">
                                                                </picture>
                                                            </a>
                                                        </div>
                                                        <a href="/get_item_details/{{ $a['item_code'] }}" class="text-dark" style="font-size: 9pt;">
                                                            <div class="p-1 text-justify">
                                                                <span class="font-weight-bold font-responsive">{{ $a['item_code'] }}</span>
                                                                <small class="font-italic font-responsive" style="font-size: 9pt;">{{ str_limit($a['description'], $limit = 78, $end = '...') }}</small>
                                                                <br>
                                                                <span class="badge badge-{{ ($a['actual_stocks'] > 0) ? 'success' : 'secondary' }} font-responsive">{{ ($a['actual_stocks'] > 0) ? 'In Stock' : 'Unavailable' }}</span>
                                                            </div>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <div class="col-md-12">
                                        <h5 class="text-center font-responsive">No Item Alternative(s)</h5>
                                    </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
        
                    <div id="athena-logs" class="container-fluid tab-pane bg-white p-2">
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
                            <button class="btn btn-secondary font-responsive btn-sm" id="athReset">Reset Filters</button>
                        </div>
                        <div id="athena-transactions" class="col-12"></div>
                    </div>
        
                    <div id="history" class="container-fluid tab-pane bg-white p-2">
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
                                    <button class="btn btn-secondary font-responsive btn-sm" id="erpReset">Reset Filters</button>
                                </div>
                                <div class="box-body table-responsive no-padding font-responsive" id="stock-ledger-table"></div>
                            </div>
                        </div>
                        <div id="stock-ledger" class="col-12"></div>
                    </div>
                    @if (in_array($user_group, ['Manager', 'Director']))
                    <div id="purchase-history" class="container-fluid tab-pane bg-white">
                        <div id="purchase-history-div" class="p-3 col-12"></div>
                    </div>
                    @endif
                    <div class="container-fluid tab-pane bg-white" id="tab_4">
                        <div class="row">
                            <div class="col-md-12">
                                @php
                                    $attr = null;
                                    if(Auth::check()){
                                        $attr = (!in_array(Auth::user()->user_group, ['Inventory Manager'])) ? 'disabled' : '';
                                    }
                                @endphp
                                <div class="float-right m-2">
                                    <button class="btn btn-primary font-responsive btn-sm" id="add-stock-reservation-btn" {{ $attr }}>New Stock Reservation</button>
                                </div>
                                <div class="box-body table-responsive no-padding font-responsive" id="stock-reservation-table"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        #example tr > *:first-child {
            position: -webkit-sticky;
            position: sticky;
            left: 0;
            min-width: 7rem;
            z-index: 1;
        }

        #example tr > *:first-child::before {
            content: "";
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            z-index: -1;
        }
        .custom-body {
            min-width: 406px;
            max-width: 406px;
        }

        .table-highlight{
            border: 2px solid rgba(0, 31, 63, 0.3) !important;
        }

        .highlight-row{
            background-color: #001F3F !important;
            color: #fff;
            box-shadow: 2px 2px 8px #000000;
        }
        .variant-tabs{
            border-top: 1px solid #DEE2E6 !important;
        }

        .variant-tabs .nav-item .active{
            border-top: none !important;
            border-bottom: 1px solid #DEE2E6 !important;
        }
        .back-btn{
            position: absolute;
            right: 70px;
            top: -10px;
        }
        .responsive-item-code{
            font-size: 14pt;
        }
        .responsive-description{
            font-size: 11pt;
        }
        .variants-table{
            font-size: 9pt;
        }
        @media (max-width: 575.98px) {
            #example tr > *:first-child {
                min-width: 5rem;
            }
            .pagination{
                font-size: 10pt !important;
            }
            .responsive-item-code{
                font-size: 12pt !important;
            }
            .responsive-description{
                font-size: 9pt !important;
            }
            .variants-table{
                font-size: 8pt !important;
            }
        }
        @media (max-width: 767.98px) {
            #example tr > *:first-child {
                min-width: 5rem;
            }
            .pagination{
                font-size: 10pt !important;
            }
            .responsive-item-code{
                font-size: 12pt !important;
            }
            .responsive-description{
                font-size: 9pt !important;
            }
            .variants-table{
                font-size: 8pt !important;
            }
        }
        @media only screen and (min-device-width : 768px) and (max-device-width : 1024px) and (orientation : portrait) {
            .pagination{
                font-size: 10pt !important;
            }
            .back-btn{
                right: 0;
            }
            .responsive-item-code{
                font-size: 12pt !important;
            }
            .responsive-description{
                font-size: 9pt !important;
            }
            .variants-table{
                font-size: 8pt !important;
            }
        }
        @media only screen and (min-device-width : 768px) and (orientation : landscape) {
            .pagination{
                font-size: 10pt !important;
            }
            .back-btn{
                right: 0;
            }
            .responsive-item-code{
                font-size: 12pt !important;
            }
            .responsive-description{
                font-size: 9pt !important;
            }
            .variants-table{
                font-size: 8pt !important;
            }
        }
    </style>
@endsection
@section('script')
    <script>

        $(document).on('submit', '.update-price-form', function(e){
            e.preventDefault();

            var entered_price_computed = $(this).data('id');

            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: $(this).serialize(),
                success: function(response){
                    $('#' + entered_price_computed).text(response.standard_price);
                    $('#' + entered_price_computed + '-min').text(response.min_price);
                    showNotification("success", 'Item price updated.', "fa fa-check");
                }
            });
        });

        $('#back-btn').on('click', function(e){
            e.preventDefault();
            window.history.back();
        });

        get_athena_transactions();
        function get_athena_transactions(page){
            var item_code = '{{ $item_details->name }}';
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

        get_stock_reservation();
        function get_stock_reservation(page){
            var item_code = '{{ $item_details->name }}';
            $.ajax({
                type: 'GET',
                url: '/get_stock_reservation/' + item_code + '?page=' + page,
                success: function(response){
                    $('#stock-reservation-table').html(response);
                }
            });
        }

        $(document).on('click', '#stock-reservations-pagination-1 a', function(event){
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            get_stock_reservation(page);
        });

        $('#stock-reservation-form').submit(function(e){
            e.preventDefault();

            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: $(this).serialize(),
                success: function(response){
                    if (response.error) {
                        showNotification("danger", response.modal_message, "fa fa-info");
                    }else{
                        get_stock_reservation();
                        showNotification("success", response.modal_message, "fa fa-check");
                        $('#add-stock-reservation-modal').modal('hide');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                }
            });
        });

        $('#edit-reservation-form').submit(function(e){
            e.preventDefault();

            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: $(this).serialize(),
                success: function(response){
                    if (response.error) {
                        showNotification("danger", response.modal_message, "fa fa-info");
                    }else{
                        get_stock_reservation();
                        showNotification("success", response.modal_message, "fa fa-check");
                        $('#edit-stock-reservation-modal').modal('hide');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                }
            });
        });

        $('#cancel-reservation-form').submit(function(e){
            e.preventDefault();

            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: $(this).serialize(),
                success: function(response){
                    if (response.error) {
                        showNotification("danger", response.modal_message, "fa fa-info");
                    }else{
                        get_stock_reservation();
                        showNotification("success", response.modal_message, "fa fa-check");
                        $('#cancel-stock-reservation-modal').modal('hide');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                }
            });
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

        get_stock_ledger();
        function get_stock_ledger(page){
            var item_code = '{{ $item_details->name }}';
            var erp_user = $('#erp-warehouse-user-filter').val();
            var erp_wh = $('#erp-warehouse-filter').val();
            var erp_d = $('#erp_dates').val();
            $.ajax({
                type: 'GET',
                url: '/get_stock_ledger/' + item_code + '?page=' + page + '&wh_user=' + erp_user + '&erp_wh=' + erp_wh + '&erp_d=' + erp_d,
                success: function(response){
                    $('#stock-ledger').html(response);
                }
            });
        }

        $('#erp_dates').on('change', function(e){ 
            get_stock_ledger();
        });

        $(document).on('select2:select', '#erp-warehouse-user-filter', function(e){
            get_stock_ledger();
        });

        $(document).on('select2:select', '#erp-warehouse-filter', function(e){
        	get_stock_ledger();
        });

        $(document).on('click', '#stock-ledger-pagination a', function(event){
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            get_stock_ledger(page);
        });

        @if (in_array($user_group, ['Manager', 'Director']))
        get_purchase_history();
        @endif

        function get_purchase_history(page){
            var item_code = '{{ $item_details->name }}';
            $.ajax({
                type: 'GET',
                url: '/purchase_rate_history/' + item_code + '?page=' + page,
                success: function(response){
                    $('#purchase-history-div').html(response);
                }
            });
        }

        $(document).on('click', '#purchase-history-pagination a', function(event){
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            get_purchase_history(page);
        });

        $("#ath_dates").daterangepicker({
            placeholder: 'Select Date Range',
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            locale: {
                // format: 'YYYY-MM-DD',
                format: 'YYYY-MMM-DD',
                separator: " to "
            },
            startDate: moment().subtract(30, 'days'), endDate: moment(),
            // startDate: '2018-06-01', endDate: moment(),
        });
        $("#ath_dates").val('');
        $("#ath_dates").attr("placeholder","Select Date Range");

        $("#erp_dates").daterangepicker({
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            locale: {
                format: 'YYYY-MMM-DD',
                separator: " to "
            },
            startDate: moment().subtract(30, 'days'), endDate: moment(),
        });

        $("#erp_dates").val('');
		$("#erp_dates").attr("placeholder","Select Date Range");

        $('#erpReset').click(function(){
            $('#erp-warehouse-filter').empty();
            $('#erp-warehouse-user-filter').empty();
            $(function() {
                $("#erp_dates").daterangepicker({
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                    },
                    locale: {
                        format: 'YYYY-MMM-DD',
                        separator: " to "
                    },
                    // startDate: moment().subtract(30, 'days'), endDate: moment(),
                    startDate: '2018-01-01', endDate: moment(),

                });
            });
            $("#erp_dates").val('');
            $("#erp_dates").attr("placeholder","Select Date Range");
            get_stock_ledger();
        })

        $('#resetAll').click(function(){
            $('#ath-to-warehouse-filter').empty();
            $('#ath-src-warehouse-filter').empty();
            $('#warehouse-user-filter').empty();
            $('#erp-warehouse-filter').empty();
            $('#erp-warehouse-user-filter').empty();
            $(function() {
                $("#ath_dates").daterangepicker({
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                    },
                    locale: {
                        format: 'YYYY-MMM-DD',
                        separator: " to "
                    },
                    startDate: moment().subtract(30, 'days'), endDate: moment(),
                });
            });
            $(function() {
                $("#erp_dates").daterangepicker({
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                    },
                    locale: {
                        format: 'YYYY-MMM-DD',
                        separator: " to "
                    },
                    startDate: moment().subtract(30, 'days'), endDate: moment(),
                });
            });
            $("#erp_dates").val('');
            $("#erp_dates").attr("placeholder","Select Date Range");
            $("#ath_dates").val('');
            $("#ath_dates").attr("placeholder","Select Date Range");
        });
    </script>
@endsection