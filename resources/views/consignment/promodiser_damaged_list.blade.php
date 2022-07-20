@extends('layout', [
    'namePage' => 'Damaged Items List',
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
                                <span class="font-responsive font-weight-bold text-uppercase d-inline-block">Damaged Items List</span>
                            </div>
                            <div class="card-body p-0">
                                @if(session()->has('success'))
                                <div class="p-2">
                                    <div class="callout callout-success font-responsive text-center pr-1 pl-1 pb-3 pt-3 m-2">
                                        {{ session()->get('success') }}
                                    </div>
                                </div>
                            @endif
                            @if(session()->has('error'))
                                <div class="p-2">
                                    <div class="callout callout-danger font-responsive text-center pr-1 pl-1 pb-3 pt-3 m-2">
                                        {{ session()->get('error') }}
                                    </div>
                                </div>
                            @endif
                                <div class="col-12">
                                    <input type="text" class="form-control mt-2 mb-2" id="item-search" name="search" placeholder="Search" style="font-size: 9pt"/>
                                </div>
                               <table class="table" id="items-table" style="font-size: 9.5pt">
                                    <thead class="border-top">
                                        <th class="text-center p-1 align-middle" style="width: 75%">Item Code</th>
                                        <th class="text-center p-1 align-middle" style="width: 25%">Action</th>
                                    </thead>
                                    @forelse ($damaged_arr as $i => $item)
                                        <tr>
                                            <td class="text-center p-1 align-middle" style="width: 75%">
                                                <div class="d-none"><!-- For Search -->
                                                    {{ $item['store'] }} <br>
                                                    {{ $item['damage_description'] }} <br>
                                                    {{ $item['promodiser'] }} <br>
                                                    {{ Carbon\Carbon::parse($item['creation'])->format('M d, Y - h:i a') }}
                                                </div>
                                                <div class="d-flex flex-row align-items-center">
                                                    <div class="p-1 text-center">
                                                        <a href="{{ asset('storage/') }}{{ $item['image'] }}" data-toggle="mobile-lightbox" data-gallery="{{ $item['item_code'] }}" data-title="{{ $item['item_code'] }}">
                                                            <picture>
                                                                <source srcset="{{ asset('storage/'.$item['webp']) }}" type="image/webp" width="40" height="40">
                                                                <source srcset="{{ asset('storage/'.$item['image']) }}" type="image/jpeg" width="40" height="40">
                                                                <img src="{{ asset('storage'.$item['image']) }}" alt="{{ str_slug(explode('.', $item['image'])[0], '-') }}" width="40" height="40">
                                                            </picture>
                                                        </a>
                                                    </div>
                                                    <div class="p-1 m-0">
                                                        <span class="font-weight-bold">{{ $item['item_code'] }}</span>
                                                        <span class="badge badge-{{ $item['status'] == 'Returned' ? 'success' : 'primary' }}">{{ $item['status'] == 'Returned' ? $item['status'] : 'For Return' }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center p-1 align-middle" style="width: 25%">
                                                <a href='#' data-toggle="modal" data-target="#view-item-details-{{ $i }}-Modal">View</a>
                                                  
                                                <!-- Modal -->
                                                <div class="modal fade" id="view-item-details-{{ $i }}-Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-navy">
                                                                <h6 class="modal-title"><b>Damaged Items</b>&nbsp;<span class="badge badge-{{ $item['status'] == 'Returned' ? 'success' : 'primary' }}">{{ $item['status'] == 'Returned' ? $item['status'] : 'For Return' }}</span></h6>
                                                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <h6 class="text-left m-0 p-0">{{ $item['store'] }}</h6>
                                                                <small class="d-block text-left">{{ $item['promodiser'].' - '.Carbon\Carbon::parse($item['creation'])->format('M d, Y - h:i a') }}</small>
                                                                <div class="callout callout-info text-center mt-2">
                                                                    <small><i class="fas fa-info-circle"></i> Consignment Supervisor will notify that there are damaged/defective item in your store.</small>
                                                                </div>
                                                                <table class="table">
                                                                    <thead>
                                                                        <th class="p-1 align-middle text-center" style="width: 65% !important;">Item Code</th>
                                                                        <th class="p-1 align-middle text-center">Qty</th>
                                                                    </thead>
                                                                    <tr>
                                                                        <td class="p-1 text-left align-middle">
                                                                            <div class="d-flex flex-row align-items-center">
                                                                                <div class="p-1 text-center">
                                                                                    <a href="{{ asset('storage/') }}{{ $item['image'] }}" data-toggle="mobile-lightbox" data-gallery="{{ $item['item_code'] }}" data-title="{{ $item['item_code'] }}">
                                                                                        <picture>
                                                                                            <source srcset="{{ asset('storage/'.$item['webp']) }}" type="image/webp" width="40" height="40">
                                                                                            <source srcset="{{ asset('storage/'.$item['image']) }}" type="image/jpeg" width="40" height="40">
                                                                                            <img src="{{ asset('storage'.$item['image']) }}" alt="{{ str_slug(explode('.', $item['image'])[0], '-') }}" width="40" height="40">
                                                                                        </picture>
                                                                                    </a>
                                                                                </div>
                                                                                <div class="p-1 m-0">
                                                                                    <span class="font-weight-bold">{{ $item['item_code'] }}</span>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                        <td class="p-1 align-middle text-center">
                                                                            <div class="container" style="display: flex; justify-content: center; align-items: center;">
                                                                                <div>
                                                                                    <b>{{ number_format($item['damaged_qty']) }}</b> <br>
                                                                                    <small>{{ $item['uom'] }}</small>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="2" class="text-justify p-1 align-middle" style="border-top: 0 !important;">
                                                                            <p>{!! strip_tags($item['item_description']) !!}</p>
                                                                            <p class="mt-1"><b>Reason: </b> {{ $item['damage_description'] }}</p>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                            @if ($item['status'] != 'Returned')
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary w-100" data-toggle="modal" data-target="#confirm-{{ $i }}-Modal">Return to Plant</button>

                                                                    <!-- Modal -->
                                                                    <div class="modal fade confirm" id="confirm-{{ $i }}-Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                        <div class="modal-dialog" role="document">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header" style='background-color: #001F3F; color: #fff'>
                                                                                    <h5 class="modal-title" id="exampleModalLabel">Return to Plant</h5>
                                                                                    <button type="button" onclick="close_modal('#confirm-{{ $i }}-Modal')" style="background-color: rgba(0,0,0,0); border: none;">
                                                                                        <span aria-hidden="true" style="color: #fff">&times;</span>
                                                                                    </button>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    Return {{ $item['item_code'] }} to Plant?
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <a href="/damaged/return/{{ $item['name'] }}" class="btn btn-primary w-100 submit-once" disabled>Confirm</a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="border-top: 0 !important;" class="pt-0 pb-2 pl-2 prl-2">
                                                <div class="d-none"><!-- For Search -->
                                                    {{ $item['item_code'] }}
                                                </div>
                                                <div class="item-description">{!! strip_tags($item['item_description']) !!}</div>
                                                <span class="d-block mt-1 font-weight-bold">{{ $item['store'] }}</span>
                                                <b>Reason: </b> {{ $item['damage_description'] }} <br>
                                                <b>Qty: </b> {{ number_format($item['damaged_qty']) }} <small style="white-space: nowrap">{{ $item['uom'] }}</small>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-uppercase text-muted text-center p-1 align-middle">No damaged item(s) reported</td>
                                        </tr>
                                    @endforelse
                               </table>
                               <div class="mt-3 ml-3 clearfix pagination" style="display: block;">
                                    <div class="col-md-4 float-right">
                                        {{ $damaged_items->links() }}
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
        table {
            table-layout: fixed;
            width: 100%;   
        }
        .morectnt span {
            display: none;
        }
        .modal .confirm{
            background-color: rgba(0,0,0,0.4);
        }

    </style>
@endsection

@section('script')
    <script>
        var showTotalChar = 85, showChar = "Show more", hideChar = "Show less";
        $('.item-description').each(function() {
            var content = $(this).text();
            if (content.length > showTotalChar) {
                var con = content.substr(0, showTotalChar);
                var hcon = content.substr(showTotalChar, content.length - showTotalChar);
                var txt = con + '<span class="dots">...</span><span class="morectnt"><span>' + hcon + '</span>&nbsp;&nbsp;<a href="#" class="showmoretxt">' + showChar + '</a></span>';
                $(this).html(txt);
            }
        });

        // $('.test').click(function(){
        //     if($(this).is('a')){
		// 			console.log('test');
        //     }
        // });

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

        $("#item-search").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#items-table tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    </script>
@endsection