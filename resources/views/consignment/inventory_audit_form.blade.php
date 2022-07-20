@extends('layout', [
    'namePage' => 'Inventory Audit Form',
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
                                    <a href="/inventory_audit" class="btn btn-secondary m-0" style="width: 60px;"><i class="fas fa-arrow-left"></i></a>
                                </div>
                                <div class="p-1 col-8">
                                    <span class="font-weight-bolder d-block font-responsive text-uppercase">Inventory Audit Form</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-1">
                            @if(session()->has('error'))
                            <div class="callout callout-danger font-responsive text-center pr-1 pl-1 pb-3 pt-3 m-2" style="font-size: 10pt;">
                                {!! session()->get('error') !!}
                            </div>
                            @endif
                            <h6 class="font-weight-bold text-center m-1 text-uppercase" style="font-size: 10pt;">{{ $branch }}</h6>
                            <h5 class="text-center mt-1 font-weight-bolder font-responsive">{{ $duration }}</h5>
                            <div class="callout callout-info font-responsive text-center pr-3 pl-3 pb-3 pt-3 m-2" style="font-size: 10pt;">
                                <span class="d-block"><i class="fas fa-info-circle"></i> Instructions: Enter your current physical count of quantity per item as of <u>TODAY</u>.</span>
                            </div>
                            <form action="/submit_inventory_audit_form" method="POST" autocomplete="off" id="inventory-report-entry-form">
                                @csrf
                                <input type="hidden" name="transaction_date" value="{{ $transaction_date }}">
                                <input type="hidden" name="branch_warehouse" value="{{ $branch }}">
                                <input type="hidden" name="audit_date_from" value="{{ $inventory_audit_from }}">
                                <input type="hidden" name="audit_date_to" value="{{ $transaction_date }}">
                                <div class="form-group m-2">
                                    <input type="text" class="form-control" placeholder="Search Items" id="search-filter">
                                </div>
                                <table class="table" style="font-size: 8pt;" id="items-table">
                                    <thead>
                                        <th class="text-center p-1" style="width: 30%;">ITEM CODE</th>
                                        <th class="text-center p-1" style="width: 40%;">AUDIT QTY</th>
                                        <th class="text-center p-1" style="width: 15%;">ACTUAL</th>
                                        <th class="text-center p-1" style="width: 15%;">SOLD</th>
                                    </thead>
                                    <tbody>
                                        @forelse ($items as $row)
                                        @php
                                            $id = $row->item_code;
                                            $img = array_key_exists($row->item_code, $item_images) ? "/img/" . $item_images[$row->item_code][0]->image_path : "/icon/no_img.png";
                                            $img_webp = array_key_exists($row->item_code, $item_images) ? "/img/" . explode('.',$item_images[$row->item_code][0]->image_path)[0].'.webp' : "/icon/no_img.webp";
                                            $sold_qty = array_key_exists($row->item_code, $item_total_sold) ? ($item_total_sold[$row->item_code] * 1) : 0;
                                            $consigned_qty = array_key_exists($row->item_code, $consigned_stocks) ? ($consigned_stocks[$row->item_code] * 1) : 0;

                                            $img_count = array_key_exists($row->item_code, $item_images) ? count($item_images[$row->item_code]) : 0;

                                            $qty = 0;
                                            if(session()->has('error')) {
                                                $data = session()->get('old_data');
                                                $qty = $data['item'][$row->item_code]['qty'];
                                            }
                                        @endphp
                                        <tr style="border-bottom: 0 !important;" class="item-row {{ (session()->has('error') && session()->has('item_code') && session()->get('item_code') == $row->item_code) ? 'bg-warning' : '' }}">
                                            <td class="text-justify p-1 align-middle" style="border-bottom: 10px !important;">
                                                <div class="d-flex flex-row justify-content-start align-items-center">
                                                    <div class="p-1 text-left">
                                                        <input type="hidden" name="item[{{ $row->item_code }}][description]" value="{!! strip_tags($row->description) !!}">
                                                        <a href="{{ asset('storage/') }}{{ $img }}" data-toggle="mobile-lightbox" data-gallery="{{ $row->item_code }}" data-title="{{ $row->item_code }}">
                                                            <picture>
                                                                <source srcset="{{ asset('storage'.$img_webp) }}" type="image/webp" alt="{{ str_slug(explode('.', $img)[0], '-') }}" width="40" height="40">
                                                                <source srcset="{{ asset('storage'.$img) }}" type="image/jpeg" alt="{{ str_slug(explode('.', $img)[0], '-') }}" width="40" height="40">
                                                                <img src="{{ asset('storage'.$img) }}" alt="" width="40" height="40">
                                                            </picture>
                                                        </a>
                                                    </div>
                                                    <div class="p-1 m-0">
                                                        <span class="font-weight-bold">{{ $row->item_code }}</span>
                                                        <div class="d-none">{!! strip_tags($row->description) !!}</div>
                                                    </div>
                                                </div>
                                                <div class="modal fade" id="mobile-{{ $row->item_code }}-images-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">{{ $row->item_code }}</h5>
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
                                                                                    <source id="mobile-{{ $row->item_code }}-webp-image-src" srcset="{{ asset('storage/').$img_webp }}" type="image/webp" class="d-block w-100" style="width: 100% !important;">
                                                                                    <source id="mobile-{{ $row->item_code }}-orig-image-src" srcset="{{ asset('storage/').$img }}" type="image/jpeg" class="d-block w-100" style="width: 100% !important;">
                                                                                    <img class="d-block w-100" id="mobile-{{ $row->item_code }}-image" src="{{ asset('storage/').$img }}" alt="{{ Illuminate\Support\Str::slug(explode('.', $img)[0], '-') }}">
                                                                                </picture>
                                                                            </div>
                                                                            <span class='d-none5' id="mobile-{{ $row->item_code }}-image-data">0</span>
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
                                            <td class="text-justify p-0 align-middle" style="border-bottom: 0 !important;">
                                                <div class="d-flex flex-row justify-content-center align-items-center">
                                                    <div class="p-0">
                                                        <div class="input-group p-1 justify-content-center">
                                                            <div class="input-group-prepend p-0">
                                                                <button class="btn btn-outline-danger btn-xs qtyminus" style="padding: 0 5px 0 5px;" type="button">-</button>
                                                            </div>
                                                            <div class="custom-a p-0">
                                                                <input type="number" class="form-control form-control-sm qty item-audit-qty" value="" name="item[{{ $row->item_code }}][qty]" style="text-align: center; width: 60px;" required id="{{ $row->item_code }}">
                                                            </div>
                                                            <div class="input-group-append p-0">
                                                                <button class="btn btn-outline-success btn-xs qtyplus" style="padding: 0 5px 0 5px;" type="button">+</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center p-1 align-middle font-weight-bold" style="border-bottom: 0 !important;">
                                                <span class="d-block item-consigned-qty">{{ $consigned_qty }}</span>
                                                <span class="d-none orig-item-consigned-qty">{{ $consigned_qty }}</span>
                                            </td>
                                            <td class="text-center p-1 align-middle font-weight-bold" style="border-bottom: 0 !important;">
                                                <span class="d-block item-sold-qty">{{ $sold_qty }}</span>
                                                <span class="d-none orig-item-sold-qty">{{ $sold_qty }}</span>
                                                <span class="d-none item-price">{{ $row->price }}</span>
                                            </td>
                                        </tr>
                                        <tr class="{{ (session()->has('error') && session()->has('item_code') && session()->get('item_code') == $row->item_code) ? 'bg-warning' : '' }}">
                                            <td colspan="4" style="border-top: 0 !important;">
                                                <span class="d-none">{{ $row->item_code }}</span>
                                                <div class="item-description">{!! strip_tags($row->description) !!}</div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td class="text-center font-weight-bold text-uppercase text-muted" colspan="4">No item(s) found</td>
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
                <h5 class="modal-title"><i class="fas fa-info-circle"></i> CONFIRM INVENTORY AUDIT</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form></form>
                <p class="text-center mt-0">
                    <span class="d-block">Click <strong>"CONFIRM"</strong> to submit your current physical count of quantity per item as of <strong><u>{{ \Carbon\Carbon::parse($transaction_date)->format('F d, Y') }}</u></strong>.</span>
                </p>
                <div class="text-center mb-3 mt-2" style="font-size: 9pt;">
                    <span class="d-block font-weight-bolder mt-4">{{ $branch }}</span>
                    <small class="d-block">Branch / Store</small>
                </div>
                <div class="text-center mb-3 mt-3" style="font-size: 9pt;">
                    <span class="d-block font-weight-bolder mt-3">{{ $duration }}</span>
                    <small class="d-block">Cut-off Period</small>
                </div>
                <p class="text-center mb-0 mt-4 font-weight-bolder text-uppercase border-bottom">Sales Report Summary</p>
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
                <div class="row pt-5">
                    <div class="col-6">
                        <button type="button" class="btn btn-primary btn-block" id="confirm-inventory-report-btn"><i class="fas fa-check"></i> CONFIRM</button>
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
                    <span class="d-block">Enter your current physical count of quantity per item as of <strong><u>{{ \Carbon\Carbon::parse($transaction_date)->format('F d, Y') }}</u></strong>.</span>
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
                <p class="text-center text-uppercase mt-0 font-weight-bold">{{ session()->get('success') }}</p>
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
                    <div class="p-2">
                        <a href="/inventory_audit" class="btn btn-secondary font-responsive"><i class="fas fa-list"></i> Return to List</a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="notifications-modal" tabindex="-1" role="dialog" aria-labelledby="notifications-modal-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-navy">
                <h5 class="modal-title"><i class="fas fa-info-circle"></i> WARNING</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="font-size: 10pt;">
                <form></form>
                <p class="text-center mt-0">
                    <span class="d-block">Please enter physical count of quantity for the following item(s):</span>
                </p>
                <div id="inc-item-codes">
                    <ul></ul>
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

            var empty_inputs = [];
            $('.item-audit-qty').each(function() {
                if (!$(this).val()) {
                    var id = $(this).attr('id');
                    empty_inputs.push(id);
                }
            });

            if(empty_inputs.length > 0) {
                $('#inc-item-codes ul').empty();
                $.each(empty_inputs, function(e, item){
                    $('#inc-item-codes ul').append('<li class="wrong-item-code">'+ item +'</li>');
                });

                $('#notifications-modal').modal('show');

                return false;
            }

            var total_sold_qty = total_sales_amount = 0;
            $('.item-row').each(function() {
                var item_price = parseFloat($(this).find('.item-price').eq(0).text());
                var orig_sold_qty = parseInt($(this).find('.orig-item-sold-qty').eq(0).text());
                var audit_qty = parseInt($(this).find('.item-audit-qty').eq(0).val());
                var orig_consigned_qty = parseInt($(this).find('.orig-item-consigned-qty').eq(0).text());
                total_sold_qty += orig_sold_qty;
                total_sales_amount += (orig_sold_qty * item_price);

                var new_item_sold_qty = orig_consigned_qty - audit_qty;
                if (new_item_sold_qty > -1) {
                    total_sold_qty += new_item_sold_qty;
                    total_sales_amount += (new_item_sold_qty * item_price);
                }
            });

            $('#total-qty-sold').text(total_sold_qty.toLocaleString());
            $('#total-sales-amount').text(formatToCurrency(total_sales_amount));

            $('#confirmation-modal').modal('show');
        });

        $('#confirm-inventory-report-btn').click(function(e){
            e.preventDefault();
            $('#inventory-report-entry-form').submit();
        });

        $('.qtyplus').click(function(e){
            // Stop acting like a button
            e.preventDefault();
            // Get the field name
            var fieldName = $(this).parents('.input-group').find('.qty').eq(0);
            // Get its current value
            var currentVal = parseInt(fieldName.val());
            // get consigned qty
            var origConsigned = parseInt($(this).parents('tr').find('.orig-item-consigned-qty').eq(0).text());
            var consignedField = $(this).parents('tr').find('.item-consigned-qty').eq(0);
            // get sold qty
            var origSold = parseInt($(this).parents('tr').find('.orig-item-sold-qty').eq(0).text());
            var soldField = $(this).parents('tr').find('.item-sold-qty').eq(0);
            // If is not undefined
            if (!isNaN(currentVal)) {
                // Increment
                fieldName.val(currentVal + 1);
            } else {
                // Otherwise put a 0 there
                fieldName.val(0);
            }
            var new_sold_qty = 0;
            new_sold_qty = (origConsigned - fieldName.val());
            if (new_sold_qty > -1) {
                soldField.text(new_sold_qty + origSold);
            } else {
                soldField.text(0 + origSold);
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
             // get consigned qty
            var origConsigned = parseInt($(this).parents('tr').find('.orig-item-consigned-qty').eq(0).text());
            var consignedField = $(this).parents('tr').find('.item-consigned-qty').eq(0);
            // get sold qty
            var origSold = parseInt($(this).parents('tr').find('.orig-item-sold-qty').eq(0).text());
            var soldField = $(this).parents('tr').find('.item-sold-qty').eq(0);
            // If it isn't undefined or its greater than 0
            if (!isNaN(currentVal) && currentVal > 0) {
                // Decrement one
                fieldName.val(currentVal - 1);
            } else {
                // Otherwise put a 0 there
                fieldName.val(0);
            }
            var new_sold_qty = 0;
            new_sold_qty = (origConsigned- parseInt(fieldName.val()));
            if (new_sold_qty > -1) {
                soldField.text(new_sold_qty + origSold);
            } else {
                soldField.text(0 + origSold);
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