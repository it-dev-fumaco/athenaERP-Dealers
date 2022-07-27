@extends('layout', [
    'namePage' => 'Delivery Report',
    'activePage' => 'beginning_inventory',
])

@section('content')
<div class="content">
	<div class="content-header p-0">
        <div class="container">
            <div class="row pt-1">
                <div class="col-md-12 p-0 m-0">
                    <div class="card card-lightblue">
                        @if (session()->has('success'))
                            @php
                                $received = session()->get('success');
                            @endphp
                            <div class="modal fade" id="receivedDeliveryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-navy">
                                            <h5 class="modal-title" id="exampleModalLabel">Delivered Item(s)</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true" style="color: #fff">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body" style="font-size: 10pt;">
                                            <span>{{ $received['message'] }}</span> <br>
                                            <span>Branch: <b>{{ $received['branch'] }}</b></span> <br>
                                            @if (isset($received['is_cancelled']))
                                            <span>Total Amount: <b>₱ {{ number_format($received['amount'], 2) }}</b></span>
                                            @else
                                            <span>Total Amount: <b>₱ {{ number_format(collect($received)->sum('amount'), 2) }}</b></span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <script>
                                $(document).ready(function(){
                                    $('#receivedDeliveryModal').modal('show');
                                });
                            </script>
                        @endif
                        <div class="card-header text-center p-2">
                            <span class="font-weight-bolder d-block text-uppercase" style="font-size: 11pt;">
                                @if ($type == 'all')
                                    Delivery Report
                                @else
                                    Incoming Deliveries                                    
                                @endif
                            </span>
                        </div>
                        <div class="card-body p-1">
                            @if(session()->has('error'))
                            <div class="callout callout-danger font-responsive text-center pr-1 pl-1 pb-3 pt-3 m-2">{!! session()->get('error') !!}</div>
                            @endif
                            <table class="table" style='font-size: 10pt;'>
                                <thead>
                                    <th class="text-center p-1 align-middle">Store</th>
                                </thead>
                                <tbody>
                                    @forelse ($ste_arr as $ste)
                                    @php
                                        $delivery_date = Carbon\Carbon::parse($ste['delivery_date'])->format('M d, Y').' - '.Carbon\Carbon::parse($ste['posting_time'])->format('h:i a');
                                    @endphp
                                    <tr>
                                        <td class="text-left p-1 align-middle">
                                            <a href="#" data-toggle="modal" data-target="#{{ $ste['name'] }}-Modal">{{ $ste['to_consignment'] }}</a>
                                            <small class="d-block"><b>{{ $ste['name'] }}</b> | <b>Delivery Date:</b> {{ $delivery_date }}</small>
                                            @if ($ste['delivery_status'] == 1)
                                                <small class="d-block"><b>Date Received:</b> {{ Carbon\Carbon::parse($ste['date_received'])->format('M d, Y - h:i a') }}</small>
                                            @endif
                                            <span class="badge badge-{{ $ste['status'] == 'Pending' ? 'warning' : 'success' }}">{{ $ste['status'] }}</span>
                                            @if ($ste['status'] == 'Delivered')
                                                <span class="badge badge-{{ $ste['delivery_status'] == 0 ? 'warning' : 'success' }}">{{ $ste['delivery_status'] == 0 ? 'To Receive' : 'Received' }}</span>
                                            @endif
                                           
                                            <div class="modal fade" id="{{ $ste['name'] }}-Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <form action="/promodiser/receive/{{ $ste['name'] }}" method="get">
                                                        <div class="modal-header bg-navy">
                                                            <h6 class="modal-title">Delivered Item(s)</h6>
                                                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <h5 class="text-center font-responsive font-weight-bold m-0">{{ $ste['to_consignment'] }}</h5>
                                                            <small class="d-block text-center mb-2">{{ $ste['name'] }} | Delivery Date: {{ $delivery_date }}</small>
                                                            @if ($ste['delivery_status'] == 1)
                                                                <small class="d-block"><b>Date Received:</b> {{ Carbon\Carbon::parse($ste['date_received'])->format('M d, Y - h:i a') }}</small>
                                                            @endif
                                                            <div class="callout callout-info text-center">
                                                                <small><i class="fas fa-info-circle"></i> Once items are received, stocks will be automatically added to your current inventory.</small>
                                                            </div>
                                                            <table class="table" style="font-size: 9pt;">
                                                                <thead>
                                                                    <th class="text-center p-1 align-middle" style="width: 40%">Item Code</th>
                                                                    <th class="text-center p-1 align-middle" style="width: 30%">Delivered Qty</th>
                                                                    <th class="text-center p-1 align-middle" style="width: 30%">Rate</th>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($ste['items'] as $item)
                                                                    @php
                                                                        $id = $ste['name'].'-'.$item['item_code'];
                                                                        $img = $item['image'] ? "/img/" . $item['image'] : "/icon/no_img.png";
                                                                        $img_webp = $item['image'] ? "/img/" . explode('.', $item['image'])[0].'.webp' : "/icon/no_img.webp";
                                                                    @endphp
                                                                    <tr>
                                                                        <td class="text-left p-1 align-middle" style="border-bottom: 0 !important;">
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
                                                                            <span class="d-block font-weight-bold">{{ number_format($item['delivered_qty'] * 1) }}</span>
                                                                            <span class="d-none font-weight-bold" id="{{ $ste['name'].'-'.$item['item_code'] }}-qty">{{ $item['delivered_qty'] * 1 }}</span>
                                                                            <small>{{ $item['stock_uom'] }}</small>
                                                                        </td>
                                                                        <td class="text-center p-1 align-middle">
                                                                            <input type="text" name="item_codes[]" class="d-none" value="{{ $item['item_code'] }}"/>
                                                                            <input type="text" value='{{ $item['price'] > 0 ? number_format($item['price'], 2) : null }}' class='form-control text-center price' name='price[{{ $item['item_code'] }}]' data-target='{{ $ste['name'].'-'.$item['item_code'] }}' placeholder='0' required>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="3" class="text-justify pt-0 pb-1 pl-1 pr-1" style="border-top: 0 !important;">
                                                                            <span class="item-description">{!! strip_tags($item['description']) !!}</span> <br>
                                                                            Amount: ₱ <span id="{{ $ste['name'].'-'.$item['item_code'] }}-amount" min='1' class='font-weight-bold amount'>{{ number_format($item['delivered_qty'] * $item['price'], 2) }}</span>
                                                                        </td>
                                                                    </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <div class="modal-footer">
                                                            @if ($ste['status'] == 'Delivered' && $ste['delivery_status'] == 0)
                                                                <input type="checkbox" name="update_price" class="d-none" readonly>
                                                                <input type="checkbox" name="receive_delivery" class="d-none" checked readonly>
                                                                <button type="submit" class="btn btn-primary w-100 submit-once">Receive</button>
                                                            @else
                                                                <input type="checkbox" name="update_price" class="d-none" checked readonly>
                                                                <input type="checkbox" name="receive_delivery" class="d-none" readonly>
                                                                <button type="submit" class="btn btn-info w-100 submit-once">Update Prices</button>
                                                                <button type="button" class="btn btn-secondary w-100" data-toggle="modal" data-target="#cancel-{{ $ste['name'] }}-Modal">
                                                                    Cancel
                                                                </button>
                                                                  
                                                                <div class="modal fade" id="cancel-{{ $ste['name'] }}-Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header bg-navy">
                                                                                <h5 class="modal-title" id="exampleModalLabel">Cancel</h5>
                                                                                <button type="button" class="close" onclick="close_modal('#cancel-{{ $ste['name'] }}-Modal')">
                                                                                <span aria-hidden="true" style="color: #fff;">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                Cancel {{ $ste['name'] }}?
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <a href="/promodiser/cancel/received/{{ $ste['name'] }}" class="btn btn-primary w-100 submit-once">Confirm</a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="2" class="text-center text-uppercase text-muted align-middle">
                                            @if ($type == 'all')
                                                No delivery record(s)
                                            @else
                                                No incoming deliveries
                                            @endif
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="mt-3 ml-3 clearfix pagination d-block">
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
        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
        }

        /* Firefox */
        input[type=number] {
        -moz-appearance: textfield;
        }
        .modal{
            background-color: rgba(0,0,0,0.4);
        }
    </style>
@endsection

@section('script')
    <script>
        $(document).ready(function(){
            var showTotalChar = 150, showChar = "Show more", hideChar = "Show less";

            $('.price').keyup(function(){
                var target = $(this).data('target');
                var price = $(this).val().replace(/,/g, '');
                if($.isNumeric($(this).val()) && price > 0 || $(this).val().indexOf(',') > -1 && price > 0){
                    var qty = parseInt($('#'+target+'-qty').text());
                    var total_amount = price * qty;

                    const amount = total_amount.toLocaleString('en-US', {maximumFractionDigits: 2});
                    $('#'+target+'-amount').text(amount);
                }else{
                    $('#'+target+'-amount').text('0');
                    $(this).val('');
                }
            });

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
        });
    </script>
@endsection