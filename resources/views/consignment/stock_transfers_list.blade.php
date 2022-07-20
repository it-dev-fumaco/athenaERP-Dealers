@extends('layout', [
    'namePage' => $purpose == 'Material Transfer' ? 'Stock Transfers List' : 'Sales Returns List',
    'activePage' => 'beginning_inventory',
])

@section('content')
<div class="content">
	<div class="content-header p-0">
        <div class="container">
            <div class="row pt-1">
                <div class="col-md-12 p-0 m-0">
                    <div class="card card-lightblue">
                        <div class="card-header text-center p-2">
                            <span class="font-weight-bolder d-block text-uppercase" style="font-size: 11pt;">{{ $purpose == 'Material Transfer' ? 'Stock Transfers List' : 'Sales Returns List'}}</span>
                        </div>
                        <div class="card-body p-1">
                            <div class="d-flex flex-row align-items-center justify-content-between">
                                <div class="p-0 col-8 mx-auto text-center">
                                    <span class="font-responsive text-uppercase d-inline-block">{{ \Carbon\Carbon::now()->format('F d, Y') }}</span>
                                </div>
                                @if (Auth::user()->user_group == 'Promodiser')
                                    <div class="p-1 col-4 text-right">
                                        <a href="/stock_transfer/form" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Create</a>
                                    </div>    
                                @endif
                            </div>
                            @if(session()->has('success'))
                                <div class="callout callout-success font-responsive text-center pr-1 pl-1 pb-3 pt-3 m-2">
                                    {{ session()->get('success') }}
                                </div>
                            @endif
                            @if(session()->has('error'))
                                <div class="callout callout-danger font-responsive text-center pr-1 pl-1 pb-3 pt-3 m-2">
                                    {{ session()->get('error') }}
                                </div>
                            @endif
                        <div class="container-fluid">
                            <span class="float-right p-2" style="font-size: 10pt;"><b>Total: </b>{{ $stock_transfers->total() }}</span>
                        </div>

                            <table class="table" style="font-size: 10pt;">
                                <thead class="border-top">
                                    <th class="text-center p-1 d-none d-lg-table-cell">Date</th>
                                    <th class="text-center p-1 d-none d-lg-table-cell">Transaction Type</th>
                                    @if ($purpose == 'Material Transfer') {{-- Stock Transfers and Returns --}}
                                    <th class="text-center p-1 mobile-first-row">
                                        <span class="d-none d-lg-inline">From Warehouse</span>
                                        <span class="d-inline d-lg-none">Details</span>
                                    </th>
                                    <th class="text-center p-1 d-none d-lg-table-cell">To Warehouse</th>
                                    @else {{-- Sales Returns --}}
                                    <th class="text-center p-1 mobile-first-row">
                                        <span class="d-none d-lg-inline">Warehouse</span>
                                        <span class="d-inline d-lg-none">Details</span>
                                    </th>
                                    @endif
                                    <th class="text-center p-1 d-none d-lg-table-cell">Submitted By</th>
                                    <th class="text-center p-1 d-none d-lg-table-cell">Status</th>
                                    <th class="text-center p-1">Action</th>
                                </thead>
                                <tbody>
                                @forelse ($ste_arr as $ste)
                                @php
                                    if($ste['docstatus'] == 1){
                                        $badge = 'success';
                                        $status = 'Approved';
                                    }else{
                                        $badge = 'primary';
                                        $status = Auth::user()->user_group == 'Promodiser' ? 'For Approval' : 'To Submit in ERP';
                                    }
                                @endphp
                                <tr>
                                    <td class="text-center p-1 d-none d-lg-table-cell">{{ Carbon\Carbon::parse($ste['date'])->format('M d, Y - h:i a') }}</td>
                                    <td class="text-center p-1 d-none d-lg-table-cell"><span class="font-weight-bold">{{ $ste['transfer_type'] }}</span></td>
                                    @if ($purpose == 'Material Transfer') {{-- Stock Transfers and Returns --}}
                                    <td class="p-1">
                                        <div class="d-none d-lg-inline text-center">
                                            {{ $ste['from_warehouse'] }}
                                        </div>
                                        <div class="d-inline d-lg-none text-left">
                                            <span class="font-weight-bold">{{ $ste['transfer_type'] }}</span>&nbsp;<span class="badge badge-{{ $badge }}">{{ $status }}</span>
                                        </div>
                                    </td>
                                    <td class="d-none p-1 d-lg-table-cell">{{ $ste['to_warehouse'] == 'Quarantine Warehouse - FI' ? 'Fumaco - Plant 2' : $ste['to_warehouse'] }}</td>
                                    @else {{-- Sales Returns --}}
                                    <td class="p-1">
                                        <div class="d-none d-lg-inline text-center">
                                            {{ $ste['to_warehouse'] }}
                                        </div>
                                        <div class="d-inline d-lg-none text-left">
                                            <span class="font-weight-bold">{{ $ste['transfer_type'] }}</span>&nbsp;<span class="badge badge-{{ $badge }}">{{ $status }}</span>
                                        </div>
                                    </td>
                                    @endif
                                    <td class="text-center p-1 d-none d-lg-table-cell">{{ $ste['owner'] }}</td>
                                    <td class="text-center p-1 d-none d-lg-table-cell">
                                        <span class="badge badge-{{ $badge }}">{{ $status }}</span>
                                    </td>
                                    <td class="text-center p-1">
                                        <a href="#" data-toggle="modal" data-target="#{{ $ste['name'] }}-Modal">View items</a>
                                        <!-- Modal -->
                                        <div class="modal fade" id="{{ $ste['name'] }}-Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-navy">
                                                        <h6 class="modal-title"><b>{{ $ste['transfer_type'] }}</b>&nbsp;<span class="badge badge-{{ $badge }}">{{ $status }}</span></h6>
                                                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        @if ($purpose == 'Material Transfer') {{-- Stock Transfers and Returns --}}
                                                        <span class="d-block text-left"><b>From: </b> {{ $ste['from_warehouse'] }}</span>
                                                        <span class="d-block text-left"><b>To: </b> {{ $ste['to_warehouse'] }}</span>
                                                        @else {{-- Sales Returns --}}
                                                        <span class="d-block text-left">{{ $ste['to_warehouse'] }}</span>
                                                        @endif
                                                        <small class="d-block text-left mb-2">{{ $ste['owner'] }} - {{ Carbon\Carbon::parse($ste['date'])->format('M d, Y - h:i a') }}</small>
                                                       
                                                        <table class="table" style="font-size: 9pt;">
                                                            <thead>
                                                                <th class="text-center p-1 align-middle">Item Code</th>
                                                                @if ($purpose == 'Material Transfer') {{-- Stock Transfers and Returns --}}
                                                                <th class="text-center p-1 align-middle">Stock Qty</th>
                                                                <th class="text-center p-1 align-middle">Qty to Transfer</th>
                                                                @else {{-- Sales Returns --}}
                                                                <th class="text-center p-1 align-middle">Return Qty</th>
                                                                @endif
                                                            </thead>
                                                            @foreach ($ste['items'] as $item)
                                                                <tr>
                                                                    <td class="text-center p-1">
                                                                        <div class="d-flex flex-row justify-content-start align-items-center">
                                                                            <div class="p-1 text-left">
                                                                                <a href="{{ asset('storage/') }}{{ $item['image'] }}" data-toggle="mobile-lightbox" data-gallery="{{ $item['item_code'] }}" data-title="{{ $item['item_code'] }}">
                                                                                    <picture>
                                                                                        <source srcset="{{ asset('storage'.$item['webp']) }}" type="image/webp" alt="{{ str_slug(explode('.', $item['image'])[0], '-') }}" width="40" height="40">
                                                                                        <source srcset="{{ asset('storage'.$item['image']) }}" type="image/jpeg" alt="{{ str_slug(explode('.', $item['image'])[0], '-') }}" width="40" height="40">
                                                                                        <img src="{{ asset('storage'.$item['image']) }}" alt="{{ str_slug(explode('.', $item['image'])[0], '-') }}" width="40" height="40">
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
                                                                                                    <source id="mobile-{{ $item['item_code'] }}-webp-image-src" srcset="{{ asset('storage/').$item['webp'] }}" type="image/webp" class="d-block w-100" style="width: 100% !important;">
                                                                                                    <source id="mobile-{{ $item['item_code'] }}-orig-image-src" srcset="{{ asset('storage/').$item['image'] }}" type="image/jpeg" class="d-block w-100" style="width: 100% !important;">
                                                                                                    <img class="d-block w-100" id="mobile-{{ $item['item_code'] }}-image" src="{{ asset('storage/').$item['image'] }}" alt="{{ Illuminate\Support\Str::slug(explode('.', $item['image'])[0], '-') }}">
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
                                                                    @if ($purpose == 'Material Transfer') {{-- Stock Transfers and Returns --}}
                                                                    <td class="text-center p-1 align-middle">
                                                                        <span class="d-block font-weight-bold">{{ $item['consigned_qty'] * 1 }}</span>
                                                                        <small>{{ $item['uom'] }}</small>
                                                                    </td>
                                                                    @endif
                                                                    <td class="text-center p-1 align-middle">
                                                                        <span class="d-block font-weight-bold">{{ $item['transfer_qty'] * 1 }}</span>
                                                                        <small>{{ $item['uom'] }}</small>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="3" class="text-justify pt-0 pb-1 pl-1 pr-1" style="border-top: 0 !important;">
                                                                        <span class="item-description">{!! strip_tags($item['description']) !!}</span>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </table>
                                                    </div>
                                                    <div class="text-center m-3 {{ $ste['docstatus'] == 1 ? 'd-none' : null }}">
                                                        <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#cancel-{{ $ste['name'] }}-Modal">Cancel Request</button>
                                                    </div>
                                                    <div class="modal fade" id="cancel-{{ $ste['name'] }}-Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-navy">
                                                                    <span class="modal-title">{{ $ste['transfer_type'] }}&nbsp;<span class="badge badge-{{ $badge }}">{{ $status }}</span></span>
                                                                    <button type="button" class="close text-white" onclick="close_modal('#cancel-{{ $ste['name'] }}-Modal')">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body p-2">
                                                                    <form></form>
                                                                    <h6 class="mb-3">Cancel {{ $ste['transfer_type'] }} Request?</h6>
                                                                    <div class="text-center m-2">
                                                                        <a href="/stock_transfer/cancel/{{ $ste['name'] }}" class="btn btn-primary submit-once">Confirm</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Modal -->
                                    </td>
                                </tr>
                                <tr class="d-lg-none">
                                    <td colspan="2" class="p-1 border-top-0 border-bottom">
                                        @if ($purpose == 'Material Transfer') {{-- Stock Transfers and Returns --}}
                                            <b>From: </b>{{ $ste['from_warehouse'] }} <br>
                                            <b>To: </b>{{ $ste['to_warehouse'] == 'Quarantine Warehouse - FI' ? 'Fumaco - Plant 2' : $ste['to_warehouse'] }} <br>
                                        @else {{-- Sales Returns --}}
                                            <b>{{ $ste['to_warehouse'] }}</b> <br>
                                        @endif
                                        <small>{{ $ste['owner'] }} - {{ Carbon\Carbon::parse($ste['date'])->format('M d, Y - h:i a') }}</small>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2"><span class="d-block text-center text-uppercase text-muted">No record(s) found</span></td>
                                </tr>
                                @endforelse
                                </tbody>
                            </table>
                            <div class="mt-3 ml-3 clearfix pagination d-block">
                                {{ $stock_transfers->links() }}
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
        .modal{
            background-color: rgba(0,0,0,0.4);
        }
        @media (max-width: 575.98px) {
            .mobile-first-row{
                width: 70%;
            }
        }
        @media (max-width: 767.98px) {
            .mobile-first-row{
                width: 70%;
            }
        }
        @media only screen and (min-device-width : 768px) and (max-device-width : 1024px) and (orientation : portrait) {
            .mobile-first-row{
                width: 70%;
            }
        }

    </style>
@endsection

@section('script')
    <script>
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

        $(".show-more").click(function(e) {
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
    </script>    
@endsection