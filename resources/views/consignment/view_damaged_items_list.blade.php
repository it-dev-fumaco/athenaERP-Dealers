@extends('layout', [
    'namePage' => 'Stock Transfers Report',
    'activePage' => 'dashboard',
])

@section('content')
<div class="content">
	<div class="content-header p-0">
        <div class="container">
            <div class="row pt-1">
                <div class="col-md-12 p-0 m-0">
                    <div class="row">
                        <div class="col-3">
                            <div style="margin-bottom: -43px;">
                                <a href="/" class="btn btn-secondary" style="width: 80px;"><i class="fas fa-arrow-left"></i></a>
                            </div>
                        </div>
                        <div class="col-7 col-lg-6 p-0">
                            <h4 class="text-center font-weight-bold m-2 text-uppercase">Stock Transfers</h4>
                        </div>
                    </div>
                    <div class="card card-secondary card-outline">
                        <div class="card-body p-2">
                            <ul class="nav nav-pills m-0" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active font-responsive" data-toggle="tab" href="#stock_transfers">Stock Transfer History</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link font-responsive" data-toggle="tab" href="#damaged_items">Damaged Item List</a>
                                </li>
                            </ul>
                            
                            <div class="tab-content">
                                <div id="stock_transfers" class="tab-pane active">
                                    <!-- Stock Transfers -->
                                    <form action="/stocks_report/list" method="get">
                                        <div id="accordion" class="mt-2">
                                            <button type="button" class="btn btn-link border-bottom btn-block text-left d-xl-none d-lg-none" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne" style="font-size: 10pt;">
                                                <i class="fa fa-filter"></i> Filters
                                            </button>
                                            <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                                                <div class="row p-2">
                                                    <div class="col-12 col-xl-2 col-lg-2">
                                                        <input type="text" name="tab1_q" class="form-control" placeholder='Search' style='font-size: 10pt;'/>
                                                    </div>
                                                    <div class="col-12 mt-2 mt-lg-0 col-xl-2 col-lg-2">
                                                        <select name="tab1_purpose" id='status' class="form-control" style="font-size: 10pt;">
                                                            @php
                                                                $purposes = ['Store Transfer', 'Consignment', 'For Return', 'Sales Return'];
                                                            @endphp 
                                                            <option value="" selected>Select Purpose</option>
                                                            @foreach ($purposes as $purpose)
                                                            <option value="{{ $purpose }}">{{ $purpose }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-12 mt-2 mt-lg-0 col-xl-2 col-lg-2">
                                                        <select name="source_warehouse" id="source-warehouse" class="form-control" style="font-size: 10pt;"></select>
                                                    </div>
                                                    <div class="col-12 mt-2 mt-lg-0 col-xl-2 col-lg-2">
                                                        <select name="target_warehouse" id="target-warehouse" class="form-control" style="font-size: 10pt;"></select>
                                                    </div>
                                                    <div class="col-12 mt-2 mt-lg-0 col-xl-2 col-lg-2">
                                                        <select name="tab1_status" id='status' class="form-control" style="font-size: 10pt;">
                                                            @php
                                                                $status = [
                                                                    ['title' => 'Select All', 'value' => 'All'],
                                                                    ['title' => 'For Approval', 'value' => 0],
                                                                    ['title' => 'Approved', 'value' => 1]
                                                                ];
                                                            @endphp 
                                                            <option value="" disabled selected>Select a status</option>
                                                            @foreach ($status as $s)
                                                            <option value="{{ $s['value'] }}">{{ $s['title'] }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-12 col-xl-2 col-lg-2 mt-2 mt-lg-0">
                                                        <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-search"></i> Search</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <table class="table table-striped" style="font-size: 9pt;">
                                        <thead>
                                            <th class="text-center p-2 align-middle d-none d-xl-table-cell" id='first-row'>Date</th>
                                            <th class="text-center p-2 align-middle" id='second-row'>
                                                <span class="d-block d-xl-none">Details</span>
                                                <span class="d-none d-xl-block">Purpose</span>
                                            </th>
                                            <th class="text-center p-2 align-middle d-none d-xl-table-cell" style="width: 20%">From</th>
                                            <th class="text-center p-2 align-middle d-none d-xl-table-cell" style="width: 20%">To</th>
                                            <th class="text-center p-2 align-middle d-none d-xl-table-cell" style="width: 10%">Created by</th>
                                            <th class="text-center p-2 align-middle d-none d-xl-table-cell" style="width: 10%">Status</th>
                                            <th class="text-center p-2 align-middle" style="width: 10%">Action</th>
                                        </thead>
                                        <tbody>
                                            @forelse ($ste_arr as $ste)
                                            @php
                                                if($ste['status'] == 'Approved'){
                                                    $badge = 'success';
                                                }else{
                                                    $badge = 'primary';
                                                }
                                            @endphp
                                            <tr>
                                                <td class="text-center p-2 align-middle d-none d-xl-table-cell">{{ $ste['creation'] }}</td>
                                                <td class="text-center p-2 align-middle">
                                                    <span class="d-block text-left text-lg-center text-xl-center font-weight-bold"> {{ $ste['transfer_as'] }}</span>
                                                    <div class="d-block d-xl-none text-left">
                                                        <b>From: </b> {{ $ste['source_warehouse'] }} <br>
                                                        <b>To: </b> {{ $ste['target_warehouse'] }} <br>
                                                        <b>Purpose: </b> {{ $ste['transfer_as'] }} <br>
                                                        {{ $ste['submitted_by'] }} - {{ $ste['creation'] }}
                                                    </div>
                                                </td>
                                                <td class="text-center p-2 align-middle d-none d-xl-table-cell">{{ $ste['source_warehouse'] }}</td>
                                                <td class="text-center p-2 align-middle d-none d-xl-table-cell">{{ $ste['target_warehouse'] }}</td>
                                                <td class="text-center p-2 align-middle d-none d-xl-table-cell">{{ $ste['submitted_by'] }}</td>
                                                <td class="text-center p-2 align-middle d-none d-xl-table-cell">
                                                    <span class="badge badge-{{ $badge }}">{{ $ste['status'] }}</span>
                                                </td>
                                                <td class="text-center p-2 align-middle">
                                                    <a href="#" data-toggle="modal" data-target="#{{ $ste['name'] }}-Modal" style="font-size: 10pt;" class="d-block">View Items</a>
                                                    <span class="badge badge-{{ $badge }} d-xl-none">{{ $ste['status'] }}</span>
                                                    <!-- Modal -->
                                                    <div class="modal fade" id="{{ $ste['name'] }}-Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-xl" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-navy">
                                                                    <h6 class="modal-title">{{ $ste['transfer_as'] }} <span class="badge badge-{{ $badge }} d-inline-block ml-2">{{ $ste['status'] }}</span></h6>
                                                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="row pb-0 mb-3">
                                                                        <div class="pt-0 pr-2 pl-2 pb-0 col-6 text-left m-0">
                                                                            <dl class="row p-0 m-0">
                                                                                <dt class="col-12 col-xl-3 col-lg-2 p-1 m-0">Source:</dt>
                                                                                <dd class="col-12 col-xl-9 col-lg-10 p-1 m-0">{{ $ste['source_warehouse'] }}</dd>
                                                                                <dt class="col-12 col-xl-3 col-lg-2 p-1 m-0">Target:</dt>
                                                                                <dd class="col-12 col-xl-9 col-lg-10 p-1 m-0">{{ $ste['target_warehouse'] }}</dd>
                                                                            </dl>
                                                                        </div>
                                                                        <div class="pt-0 pr-2 pl-2 pb-0 col-6 text-left m-0">
                                                                            <dl class="row p-0 m-0">
                                                                                <dt class="col-12 col-xl-4 col-lg-6 p-1 m-0">Transaction Date:</dt>
                                                                                <dd class="col-12 col-xl-8 col-lg-6 p-1 m-0">{{ $ste['creation'] }}</dd>
                                                                                <dt class="col-12 col-xl-4 col-lg-6 p-1 m-0">Submitted by:</dt>
                                                                                <dd class="col-12 col-xl-8 col-lg-6 p-1 m-0">{{ $ste['submitted_by'] }}</dd>
                                                                            </dl>   
                                                                        </div>
                                                                    </div>
                                                                    <table class="table table-striped" style="font-size: 10pt;">
                                                                        <thead>
                                                                            <th class="text-center p-2 align-middle" width="50%">Item Code</th>
                                                                            <th class="text-center p-2 align-middle">Stock Qty</th>
                                                                            <th class="text-center p-2 align-middle">Qty to Transfer</th>
                                                                        </thead>
                                                                        @foreach ($ste['items'] as $item)
                                                                            <tr>
                                                                                <td class="text-center p-1 align-middle">
                                                                                    <div class="d-flex flex-row justify-content-start align-items-center">
                                                                                        <div class="p-2 text-left">
                                                                                            <a href="{{ asset('storage/') }}{{ $item['image'] }}" data-toggle="mobile-lightbox" data-gallery="{{ $item['item_code'] }}" data-title="{{ $item['item_code'] }}">
                                                                                                <picture>
                                                                                                    <source srcset="{{ asset('storage'.$item['webp']) }}" type="image/webp" width="60" height="60">
                                                                                                    <source srcset="{{ asset('storage'.$item['image']) }}" type="image/jpeg" width="60" height="60">
                                                                                                    <img src="{{ asset('storage'.$item['image']) }}" alt="{{ str_slug(explode('.', $item['image'])[0], '-') }}" width="60" height="60">
                                                                                                </picture>
                                                                                            </a>
                                                                                        </div>
                                                                                        <div class="p-2 text-left">
                                                                                            <b>{!! ''.$item['item_code'] !!}</b>
                                                                                            <span class="d-none d-xl-inline"> - {!! strip_tags($item['description']) !!}</span>
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
                                                                                                                        <source id="mobile-{{ $item['item_code'] }}-webp-image-src" srcset="{{ asset('storage/').$item['webp'] }}" type="image/webp" class="d-block w-100" style="width: 100% !important;">
                                                                                                                        <source id="mobile-{{ $item['item_code'] }}-orig-image-src" srcset="{{ asset('storage/').$item['image'] }}" type="image/jpeg" class="d-block w-100" style="width: 100% !important;">
                                                                                                                        <img class="d-block w-100" id="mobile-{{ $item['item_code'] }}-image" src="{{ asset('storage/').$item['image'] }}" alt="{{ Illuminate\Support\Str::slug(explode('.', $item['image'])[0], '-') }}">
                                                                                                                    </picture>
                                                                                                                </div>
                                                                                                                <span class='d-none' id="mobile-{{ $item['item_code'] }}-image-data">0</span>
                                                                                                            </div>
                                                                                                            <a class="carousel-control-prev" href="#carouselExampleControls" onclick="prevImg('{{ $item['item_code'] }}')" role="button" data-slide="prev" style="color: #000 !important">
                                                                                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                                                                                <span class="sr-only">Previous</span>
                                                                                                            </a>
                                                                                                            <a class="carousel-control-next" href="#carouselExampleControls" onclick="nextImg('{{ $item['item_code'] }}')" role="button" data-slide="next" style="color: #000 !important">
                                                                                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                                                                                <span class="sr-only">Next</span>
                                                                                                            </a>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td class="text-center p-1 align-middle">
                                                                                    <b>{{ $item['consigned_qty'] * 1 }}</b><br/><small>{{ $item['uom'] }}</small>
                                                                                </td>
                                                                                <td class="text-center p-1 align-middle">
                                                                                    <b>{{ $item['transfer_qty'] * 1 }}</b><br/><small>{{ $item['uom'] }}</small>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="d-xl-none">
                                                                                <td colspan="3" class="text-justify pt-0 pb-1 pl-1 pr-1" style="border-top: 0 !important;">
                                                                                    <div class="w-100 item-description">{!! strip_tags($item['description']) !!}</div>
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="7" class="text-center font-responsive text-uppercase text-muted p-2">No record(s) found</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    <div class="float-left m-2">Total: <b>{{ number_format($total_records) }}</b></div>
                                    <div class="float-right m-2" id="beginning-inventory-list-pagination">
                                        @if(isset($total_records) && $total_records > 0)
                                        @php
                                            $ends_count = 2;  //how many items at the ends (before and after [...])
                                            $middle_count = 2;  //how many items before and after current page
                                            $dots = false;
                                            $prev = $current_page - 1;
                                        @endphp
                                        <ul class="pagination">
                                            <li class="page-item {{ (1 < $current_page) ? '' : 'disabled' }}">
                                            <a href="{{ \Request::url() .'?page='.$prev }}" class="page-link">Previous</a>
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
                                                <li class="page-item"><a class="page-link" href="{{ \Request::url() .'?page='.$i }}">{{ $i }}</a></li>
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
                                                <a class="page-link" href="{{ \Request::url() .'?page='.$next_page }}">Next</a>
                                            </li>
                                        </ul>
                                        @endif
                                    </div>
                                </div>
                                <!-- Stock Transfers -->

                            <!-- Damaged Items -->
                            <div id="damaged_items" class="tab-pane">
                                <form action="/stocks_report/list" method="GET">
                                    <div id="accordion2" class="mt-2">
                                        <button type="button" class="btn btn-link border-bottom btn-block text-left d-xl-none d-lg-none" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo" style="font-size: 10pt;">
                                            <i class="fa fa-filter"></i> Filters
                                        </button>
                                    </div>
                                    
                                    <div id="collapseTwo" class="collapse" aria-labelledby="headingOne" data-parent="#accordion2">
                                        <div class="row p-2">
                                            <div class="col-12 col-xl-4 col-lg-4 mt-2 mt-lg-0">
                                                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search Item" style='font-size: 10pt'/>
                                            </div>
                                            <div class="col-12 col-xl-4 col-lg-4 mt-2 mt-lg-0">
                                                @php
                                                    $statuses = ['For Approval', 'Approved', 'Cancelled'];
                                                @endphp
                                                <select class="form-control" name="store" id="consignment-store-select">
                                                    <option value="">Select Store</option>
                                                    @foreach ($statuses as $status)
                                                    <option value="{{ $status }}">{{ $status }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-12 col-xl-2 col-lg-2 mt-2 mt-lg-0">
                                                <button class="btn btn-primary btn-block"><i class="fas fa-search"></i> Search</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <table class="table table-striped" style="font-size: 9pt;">
                                    <thead>
                                        <th class="text-center p-2 align-middle d-none d-xl-table-cell" style="width: 10%;">Date</th>
                                        <th class="text-center p-2 align-middle" style="width: 35%;">Item Description</th>
                                        <th class="text-center p-2 align-middle d-none d-xl-table-cell" style="width: 10%;">Qty</th>
                                        <th class="text-center p-2 align-middle d-none d-xl-table-cell" style="width: 20%;">Store</th>
                                        <th class="text-center p-2 align-middle d-none d-xl-table-cell" style="width: 20%;">Damage Description</th>
                                        <th class="text-center p-2 align-middle d-none d-xl-table-cell" style="width: 5%;">-</th>
                                    </thead>
                                    @forelse ($items_arr as $i => $item)
                                        <tr>
                                            <td class="p-1 text-center align-middle d-none d-xl-table-cell">{{ $item['creation'] }}</td>
                                            <td class="p-1 text-justify align-middle">
                                                <div class="d-flex flex-row align-items-center">
                                                    <div class="p-1">
                                                        <picture>
                                                            <source srcset="{{ asset('storage/'.$item['webp']) }}" type="image/webp">
                                                            <source srcset="{{ asset('storage'.$item['image']) }}" type="image/jpeg">
                                                            <img src="{{ asset('storage/'.$item['image']) }}" alt="{{ str_slug(explode('.', $item['image'])[0], '-') }}" width="70">
                                                        </picture>
                                                    </div>
                                                    <div class="p-1">
                                                        <span class="d-block font-weight-bold">{{ $item['item_code'] }}</span>
                                                        <small class="d-block item-description">{!! strip_tags($item['description']) !!}</small>
    
                                                        <small class="d-block mt-2">Created by: <b>{{ $item['promodiser'] }}</b></small>
                                                    </div>
                                                </div>
                                                <div class="d-block d-xl-none" style="font-size: 9pt;">
                                                    <b>Damaged Qty: </b>{{ $item['damaged_qty'] }}&nbsp;<small>{{ $item['uom'] }}</small> <br>
                                                    <b>Store: </b> {{ $item['store'] }} <br>
                                                    <b>Damage Description: </b> {{ $item['damage_description'] }} <br>
                                                    <b>Date: </b> {{ $item['creation'] }}
                                                </div>
                                            </td>
                                            <td class="p-1 text-center align-middle d-none d-xl-table-cell">
                                                <span class="d-block font-weight-bold">{{ $item['damaged_qty'] }}</span>
                                                <small>{{ $item['uom'] }}</small>
                                            </td>
                                            <td class="p-1 text-center align-middle d-none d-xl-table-cell">{{ $item['store'] }}</td>
                                            <td class="p-1 text-center align-middle d-none d-xl-table-cell">{{ $item['damage_description'] }}</td>
                                            <td class="p-1 text-center align-middle d-none d-xl-table-cell">
                                                <a href="#" class="btn btn-primary btn-sm"><i class="fas fa-retweet"></i></a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No record(s) found.</td>
                                        </tr>
                                    @endforelse
                                </table>
                                <div class="float-left m-2">Total: <b>{{ $damaged_items->total() }}</b></div>
                                <div class="float-right m-2" id="beginning-inventory-list-pagination">{{ $damaged_items->links('pagination::bootstrap-4') }}</div>
                             
                            </div>
                            <!-- Damaged Items -->
                        </div>
                        </div>
                        <!-- Nav tabs -->
                       
                      
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .morectnt span {
        display: none;
    }
    .modal{
        background-color: rgba(0,0,0,0.4);
    }
    table {
        table-layout: fixed;
        width: 100%;   
    }
    #first-row, #second-row{
        width: 10%;
    }
    @media (max-width: 575.98px) {
        #second-row{
            width: 30%;
        }
        .select2-container--default .select2-selection--single{
            padding: 5px !important;
            font-size: 10pt !important;
        }
    }
  	@media (max-width: 767.98px) {
        #second-row{
            width: 30%;
        }
        .select2-container--default .select2-selection--single{
            padding: 5px !important;
            font-size: 10pt !important;
        }
    }
	@media only screen and (min-device-width : 768px) and (max-device-width : 1024px) and (orientation : portrait) {
        #second-row{
            width: 30%;
        }
        .select2-container--default .select2-selection--single{
            padding: 5px !important;
            font-size: 10pt !important;
        }
	}
</style>
@endsection

@section('script')
<script>
    var showTotalChar = 110, showChar = "Show more", hideChar = "Show less";
    $('.item-description').each(function() {
        var content = $(this).text();
        if (content.length > showTotalChar) {
            var con = content.substr(0, showTotalChar);
            var hcon = content.substr(showTotalChar, content.length - showTotalChar);
            var txt = con + '<span class="dots">...</span><span class="morectnt"><span>' + hcon + '</span>&nbsp;&nbsp;<a href="#" class="showmoretxt">' + showChar + '</a></span>';
            $(this).html(txt);
        }
    });

    $(".showmoretxt").click(function(e) {
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

    function show_modal(modal){
        $(modal).modal('show');
    }

    if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) { // mobile/tablet
        $('#collapseOne').removeClass('show');
        $('#collapseTwo').removeClass('show');
    }else{ // desktop
        $('#collapseOne').addClass('show');
        $('#collapseTwo').addClass('show');
    }

    $('#source-warehouse').select2({
        placeholder: 'Source Warehouse',
        allowClear: true,
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

    $('#target-warehouse').select2({
        placeholder: 'Target Warehouse',
        allowClear: true,
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
</script>
@endsection