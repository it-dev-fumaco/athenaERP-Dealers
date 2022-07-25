@extends('layout', [
    'namePage' => 'Products Sold Form',
    'activePage' => 'dashboard',
])

@section('content')
<div class="content">
	<div class="content-header p-0">
        <div class="container">
            <div class="row pt-1">
                <div class="col-md-12 p-0 m-0">
                    <div class="card card-lightblue">
                        <div class="card-header text-center p-1">
                            <div class="d-flex flex-row align-items-center">
                                <div class="p-0 col-2 text-left">
                                    <a href="/view_calendar_menu/{{ $branch }}" class="btn btn-secondary m-0" style="width: 60px;"><i class="fas fa-arrow-left"></i></a>
                                </div>
                                <div class="p-1 col-8">
                                    <span class="font-weight-bolder d-block font-responsive text-uppercase">Product Sold Entry</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-1">
                            @if(session()->has('error'))
                            <div class="callout callout-danger font-responsive text-center pr-1 pl-1 pb-3 pt-3 m-2" style="font-size: 10pt;">
                                {!! session()->get('error') !!}
                            </div>
                            @endif
                            <span id="branch-name" class="font-weight-bolder d-block text-center" style="font-size: 11pt;">{{ $branch }}</span>
                            <h5 class="text-center mt-1 font-weight-bolder">{{ \Carbon\Carbon::parse($transaction_date)->format('F d, Y') }}</h5>
                            <div class="callout callout-info font-responsive text-center pr-2 pl-2 pb-3 pt-3 m-2" style="font-size: 10pt;">
                                <span class="d-block"><i class="fas fa-info-circle"></i> Instructions: Enter your item quantity sold for this date.</span>
                            </div>
                            <form action="/submit_product_sold_form" method="POST" autocomplete="off" id="sales-report-entry-form">
                                @csrf
                                <input type="hidden" name="transaction_date" value="{{ $transaction_date }}">
                                <input type="hidden" name="branch_warehouse" value="{{ $branch }}">
                                <div class="form-group m-2">
                                    <input type="text" class="form-control form-control-sm" placeholder="Search Items" id="search-filter">
                                </div>
                                <table class="table table-bordered" style="font-size: 8pt;" id="items-table">
                                    <thead>
                                        <th class="text-center p-1" style="width: 55%;">ITEM DESCRIPTION</th>
                                        <th class="text-center p-1" style="width: 45%;">QTY SOLD</th>
                                    </thead>
                                    <tbody>
                                        @forelse ($items as $row)
                                        @php
                                            $id = $row->item_code;
                                            $img = array_key_exists($row->item_code, $item_images) ? "/img/" . $item_images[$row->item_code][0]->image_path : "/icon/no_img.png";
                                            $img_webp = array_key_exists($row->item_code, $item_images) ? "/img/" . explode('.',$item_images[$row->item_code][0]->image_path)[0].'.webp' : "/icon/no_img.webp";
                                            $qty = array_key_exists($row->item_code, $existing_record) ? ($existing_record[$row->item_code] * 1) : 0;
                                            $consigned_qty = array_key_exists($row->item_code, $consigned_stocks) ? ($consigned_stocks[$row->item_code] * 1) : 0;

                                            $img_count = array_key_exists($row->item_code, $item_images) ? count($item_images[$row->item_code]) : 0;

                                            if(session()->has('error')) {
                                                $data = session()->get('old_data');
                                                $qty = $data['item'][$row->item_code]['qty'];
                                            }
                                        @endphp
                                        <tr>
                                            <td class="text-justify p-1 align-middle" colspan="2">
                                                <div class="d-flex flex-row justify-content-center align-items-center">
                                                    <div class="p-1 col-2 text-center">
                                                        <input type="hidden" name="item[{{ $row->item_code }}][description]" value="{!! strip_tags($row->description) !!}">
                                                        <input type="hidden" name="item[{{ $row->item_code }}][stock_uom]" value="{!! strip_tags($row->stock_uom) !!}">
                                                        <a href="{{ asset('storage/') }}{{ $img }}" data-toggle="mobile-lightbox" data-gallery="{{ $row->item_code }}" data-title="{{ $row->item_code }}">
                                                        <picture>
                                                            <source srcset="{{ asset('storage'.$img_webp) }}" type="image/webp" class="img-thumbna1il" alt="User Image" width="40" height="40">
                                                            <source srcset="{{ asset('storage'.$img) }}" type="image/jpeg" class="img-thumbna1il" alt="User Image" width="40" height="40">
                                                            <img src="{{ asset('storage'.$img) }}" alt="{{ str_slug(explode('.', $img)[0], '-') }}" class="img-thumbna1il" alt="User Image" width="40" height="40">
                                                        </picture>
                                                    </a>
                                                    </div>
                                                    <div class="p-1 col-5 m-0">
                                                        <span class="font-weight-bold">{{ $row->item_code }}</span>
                                                    </div>
                                                    <div class="p-1 col-5">
                                                        <div class="input-group p-1 justify-content-center">
                                                            <div class="input-group-prepend p-0">
                                                                <button class="btn btn-outline-danger btn-xs qtyminus" style="padding: 0 5px 0 5px;" type="button">-</button>
                                                            </div>
                                                            <div class="custom-a p-0">
                                                                <input type="number" class="form-control form-control-sm qty item-sold-qty" value="{{ $qty }}" name="item[{{ $row->item_code }}][qty]" style="text-align: center; width: 80px;" data-max="{{ $consigned_qty }}" data-price="{{ $row->price }}">
                                                            </div>
                                                            <div class="input-group-append p-0">
                                                                <button class="btn btn-outline-success btn-xs qtyplus" style="padding: 0 5px 0 5px;" type="button">+</button>
                                                            </div>
                                                        </div>
                                                        <div class="text-center">
                                                            <small>Available: {{ $consigned_qty }}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-row">
                                                    <div class="p-1 text-justify">
                                                        <div class="item-description">{!! strip_tags($row->description) !!}</div>
                                                    </div>
                                                </div>

                                                <div class="modal fade" id="mobile-{{ $row->item_code }}-images-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">{{ $row->item_code }}</h5>
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
                                                                                    <source id="mobile-{{ $row->item_code }}-webp-image-src" srcset="{{ asset('storage/').$img_webp }}" type="image/webp" class="d-block w-100" style="width: 100% !important;">
                                                                                    <source id="mobile-{{ $row->item_code }}-orig-image-src" srcset="{{ asset('storage/').$img }}" type="image/jpeg" class="d-block w-100" style="width: 100% !important;">
                                                                                    <img class="d-block w-100" id="mobile-{{ $row->item_code }}-image" src="{{ asset('storage/').$img }}" alt="{{ Illuminate\Support\Str::slug(explode('.', $img)[0], '-') }}">
                                                                                </picture>
                                                                            </div>
                                                                            <span class='d-none' id="mobile-{{ $row->item_code }}-image-data">0</span>
                                                                        </div>
                                                                        @if ($img_count > 1)
                                                                        <a class="carousel-control-prev" href="#carouselExampleControls" onclick="prevImg('{{ $row->item_code }}')" role="button" data-slide="prev" style="color: #000 !important">
                                                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                                            <span class="sr-only">Previous</span>
                                                                        </a>
                                                                        <a class="carousel-control-next" href="#carouselExampleControls" onclick="nextImg('{{ $row->item_code }}')" role="button" data-slide="next" style="color: #000 !important">
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
                                        </tr> 
                                        @empty
                                        <tr>
                                            <td class="text-center font-weight-bold" colspan="2">No item(s) found.</td>
                                        </tr> 
                                        @endforelse
                                    </tbody>
                                </table>
                                <div class="m-3">
                                    <button type="button" id="submit-form" class="btn btn-primary btn-block" {{ count($items) <= 0 ? 'disabled' : ''  }}><i class="fas fa-check"></i> SUBMIT</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	</div>
</div>


<div class="modal fade" id="confirmation-modal" tabindex="-1" role="dialog" aria-labelledby="instructions-modal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-navy">
                <h5 class="modal-title"><i class="fas fa-info-circle"></i> CONFIRM SALES ENTRY</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form></form>
                <p class="text-center mt-0">
                    <span class="d-block">Click <strong>"CONFIRM"</strong> to submit your sales report entry for this date <strong><u>{{ \Carbon\Carbon::parse($transaction_date)->format('F d, Y') }}</u></strong>.</span>
                </p>
                <div class="text-center mb-3 mt-3" style="font-size: 9pt;">
                    <span class="d-block font-weight-bolder mt-4">{{ $branch }}</span>
                    <small class="d-block">Branch / Store</small>
                </div>
                <div class="d-flex flex-row mt-1 justify-content-between">
                    <div class="p-1 col-6 text-center">
                        <span class="d-block font-weight-bolder" id="total-qty-sold" style="font-size: 12pt;">0</span>
                        <small class="d-block" style="font-size: 7pt;">Total Qty Sold</small>
                    </div>
                    <div class="p-1 col-6 text-center">
                        <span class="d-block font-weight-bolder" id="total-sales-amount" style="font-size: 12pt;">0</span>
                    <small class="d-block" style="font-size: 7pt;">Total Sales Amount</small>
                    </div>
                </div>
                <div class="row pt-4">
                    <div class="col-6">
                        <button type="button" class="btn btn-primary btn-block" id="confirm-sales-report-btn"><i class="fas fa-check"></i> CONFIRM</button>
                    </div>
                    <div class="col-6">
                        <button type="button" class="btn btn-secondary btn-block" data-dismiss="modal"><i class="fas fa-times"></i> CLOSE</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="instructions-modal" tabindex="-1" role="dialog" aria-labelledby="instructions-modal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-navy">
                <h5 class="modal-title"><i class="fas fa-info-circle"></i> INSTRUCTIONS</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form></form>
                <p class="text-center mt-0">
                    <span class="d-block">Enter your item quantity sold</span>
                    <span class="d-block">for this date <strong><u>{{ \Carbon\Carbon::parse($transaction_date)->format('F d, Y') }}</u></strong>.</span>
                </p>
                <div class="text-center mb-3 mt-3" style="font-size: 9pt;">
                    <span class="d-block font-weight-bolder mt-4">{{ $branch }}</span>
                    <small class="d-block">Branch / Store</small>
                </div>
                <div class="d-flex flex-row justify-content-center">
                    <div class="p-2">
                        <button type="button" class="btn btn-primary" data-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i> CLOSE</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="success-modal" tabindex="-1" role="dialog" aria-labelledby="success-modalTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <form></form>
                <div class="d-flex flex-row justify-content-end">
                    <div class="p-1">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
                @if(session()->has('success'))
                <p class="text-success text-center mb-0" style="font-size: 5rem; margin-top: -40px;">
                    <i class="fas fa-check-circle"></i>
                </p>
                <p class="text-center text-uppercase mt-0 font-weight-bold">Product Sold is Saved</p>
               <hr>
                <p class="text-center mb-0 mt-4 font-weight-bolder text-uppercase">Sales Report Summary</p>
                <div class="text-center mb-2" style="font-size: 9pt;">
                    <span class="d-block font-weight-bold mt-3">{{ session()->get('branch') }}</span>
                    <small class="d-block">Branch / Store</small>
                    <span class="d-block font-weight-bold mt-3">{{ \Carbon\Carbon::parse(session()->get('transaction_date'))->format('F d, Y') }}</span>
                    <small class="d-block">Transaction Date</small>
                </div>
                <div class="d-flex flex-row mt-1 justify-content-between">
                    <div class="p-1 col-6 text-center">
                        <span class="d-block font-weight-bolder" style="font-size: 12pt;">{{ number_format(session()->get('total_qty_sold')) }}</span>
                        <small class="d-block" style="font-size: 7pt;">Total Qty Sold</small>
                    </div>
                    <div class="p-1 col-6 text-center">
                        <span class="d-block font-weight-bolder" style="font-size: 12pt;">{{ '₱ ' . number_format(session()->get('grand_total'), 2) }}</span>
                    <small class="d-block" style="font-size: 7pt;">Total Sales Amount</small>
                    </div>
                </div>
                <div class="d-flex flex-row justify-content-center">
                    <div class="pt-4">
                        <a href="/view_calendar_menu/{{ $branch }}" class="btn btn-secondary font-responsive"><i class="far fa-calendar-alt"></i> Return to Calendar</a>
                    </div>
                </div>
                <div class="d-flex flex-row justify-content-between">
                    <div class="p-2">
                        <a href="/view_product_sold_form/{{ $branch }}/{{ \Carbon\Carbon::parse($transaction_date)->subDay()->format('Y-m-d') }}" class="btn btn-primary btn-sm font-responsive">
                            <i class="fas fa-arrow-left"></i> {{ \Carbon\Carbon::parse($transaction_date)->subDay()->format('F d, Y') }}
                        </a>
                    </div>
                    <div class="p-2">
                        <a href="/view_product_sold_form/{{ $branch }}/{{ \Carbon\Carbon::parse($transaction_date)->addDay()->format('Y-m-d') }}" class="btn btn-primary btn-sm font-responsive">
                            {{ \Carbon\Carbon::parse($transaction_date)->addDay()->format('F d, Y') }} <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
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
    .morectnt span {
        display: none;
    }
</style>
@endsection

@section('script')
<script>
    $(function () {
        @if (!session()->has('success'))
        $('#instructions-modal').modal('show');
        @endif
        @if (session()->has('success'))
        $('#success-modal').modal('show');
        @endif

        const formatToCurrency = amount => {
            return "₱ " + amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, "$&,");
        };

        $('#submit-form').click(function(e) {
            e.preventDefault();

            var total_sold_qty = 0;
            var total_sales_amount = 0;
            $('.item-sold-qty').each(function() {
                total_sold_qty += parseInt($(this).val());
                var amount = parseInt($(this).val()) * parseFloat($(this).data('price'));
                total_sales_amount += amount;
            });

            $('#total-qty-sold').text(total_sold_qty);
            $('#total-sales-amount').text(formatToCurrency(total_sales_amount));

            $('#confirmation-modal').modal('show');
        });

        $('#confirm-sales-report-btn').click(function(e){
            e.preventDefault();
            $('#sales-report-entry-form').submit();
        });

        $('.qtyplus').click(function(e){
            // Stop acting like a button
            e.preventDefault();
            // Get the field name
            var fieldName = $(this).parents('.input-group').find('.qty').eq(0);
            // get max value
            var max = fieldName.data('max');
            // Get its current value
            var currentVal = parseInt(fieldName.val());
            // If is not undefined
            if (!isNaN(currentVal)) {
                // Increment
                if (currentVal < max) {
                    fieldName.val(currentVal + 1);
                }
            } else {
                // Otherwise put a 0 there
                fieldName.val(0);
            }
        });
        // This button will decrement the value till 0
        $(".qtyminus").click(function(e) {
            // Stop acting like a button
            e.preventDefault();
            // Get the field name
            var fieldName = $(this).parents('.input-group').find('.qty').eq(0);
            // Get its current value
            var currentVal = parseInt(fieldName.val());
            // If it isn't undefined or its greater than 0
            if (!isNaN(currentVal) && currentVal > 0) {
                // Decrement one
                fieldName.val(currentVal - 1);
            } else {
                // Otherwise put a 0 there
                fieldName.val(0);
            }
        });

        $("#search-filter").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#items-table tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
        
        var showTotalChar = 98, showChar = "Show more", hideChar = "Show less";
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
    });
</script>
@endsection