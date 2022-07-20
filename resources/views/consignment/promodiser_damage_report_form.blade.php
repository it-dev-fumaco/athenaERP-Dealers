@extends('layout', [
    'namePage' => 'Damage Report',
    'activePage' => 'beginning_inventory',
])

@section('content')
    <div class="content">
        <div class="content-header p-0">
            <div class="container">
                <div class="row pt-1">
                    <div class="col-md-12 p-0 m-0">
                        <div class="card card-lightblue">
                            <div class="card-header text-center p-2" id="report">
                                
                                <span class="font-responsive font-weight-bold text-uppercase d-inline-block">Damaged Report Form</span>
                            </div>
                            <div class="card-body p-0">
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
                                <h6 class="text-center mt-2 font-weight-bolder">{{ \Carbon\Carbon::now()->format('F d, Y') }}</h6>
                                <form action="/promodiser/damage_report/submit" method="post">
                                    @csrf
                                    <div class="container">
                                        <div class="row pt-2 pb-2">
                                            <div class="col-8">
                                                <select name="branch" id="branch" class="form-control form-control-sm">
                                                    <option value="" disabled selected>Select a Branch</option>
                                                    @foreach ($assigned_consignment_store as $store)
                                                        <option value="{{ $store }}" {{ !isset($beginning_inventory[$store]) ? 'disabled' : null }}>{{ $store }}</option>
                                                    @endforeach 
                                                </select>
                                            </div>
                                            <div class="col-4">
                                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" id='add-modal-btn' data-target="#add-Modal" disabled>
                                                    <i class="fa fa-plus"></i> Add Item
                                                </button>

                                                <!-- add item modal -->
                                                <div class="modal fade" id="add-Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-navy">
                                                                <h5 class="modal-title" id="exampleModalLabel">Select an Item</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form></form>
                                                                <select id="item-selection" class="form-control"></select>

                                                                <table class="table table-striped d-none" id="item-selection-table">
                                                                    <thead>
                                                                        <th class="font-responsive text-center p-1 align-middle" style="width: 42%">Item Code</th>
                                                                        <th class="font-responsive text-center p-1 align-middle">Damaged Qty</th>
                                                                        <th class="font-responsive text-center p-1 align-middle">Price</th>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td class="text-justify p-1 align-middle" colspan="3">
                                                                                <div class="d-flex flex-row justify-content-center align-items-center">
                                                                                    <div class="p-1 col-2 text-center">
                                                                                        <div class="d-none">
                                                                                            <span id="webp-display"></span>
                                                                                            <span id="img-display"></span>
                                                                                            <span id="alt-display"></span>
                                                                                            <span id="max-display"></span>
                                                                                        </div>
                                                                                        <picture>
                                                                                            <source srcset="" id="new-src-img-webp" type="image/webp">
                                                                                            <source srcset="" id="new-src-img" type="image/jpeg">
                                                                                            <img src="" alt="" id="new-img" class="img-thumbna1il" alt="User Image" width="40" height="40">
                                                                                        </picture>
                                                                                    </div>
                                                                                    <div class="p-1 col m-0">
                                                                                        <span class="font-weight-bold font-responsive"><span id="item-code-display"></span></span>
                                                                                    </div>
                                                                                    <div class="p-0 col-4">
                                                                                        <div class="input-group p-1">
                                                                                            <div class="input-group-prepend p-0">
                                                                                                <button class="btn btn-outline-danger btn-xs new-item-qtyminus" style="padding: 0 5px 0 5px;" type="button">-</button>
                                                                                            </div>
                                                                                            <div class="custom-a p-0">
                                                                                                <input type="text" class="form-control form-control-sm qty new-item-validate new-item-stock" id="new-item-stock" value="0" style="text-align: center; width: 47px">
                                                                                            </div>
                                                                                            <div class="input-group-append p-0">
                                                                                                <button class="btn btn-outline-success btn-xs new-item-qtyplus" style="padding: 0 5px 0 5px;" type="button">+</button>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-3 text-center">
                                                                                        <span id="selected-item-price" style='font-size: 10pt;'></span>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="p-1" id="description-display" style="font-size: 9.5pt !important;"></div>
                                                                                <div class="p-1" style="font-size: 9.5pt !important;">
                                                                                    <textarea id="reason-display" class="form-control" placeholder='Describe the damage...' rows=5></textarea>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-primary" id='add-item' disabled>Confirm</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- add item modal -->

                                            </div>
                                        </div>
                                        <div class="row">
                                            <table class="table table-striped" id="selected-items-table" style="font-size: 9pt;">
                                                <thead>
                                                    <th class="font-responsive text-center p-1 align-middle">Item Code</th>
                                                    <th class="font-responsive text-center p-1 align-middle">Damaged Qty</th>
                                                    <th class="font-responsive text-center p-1 align-middle">Price</th>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td colspan=3 class="text-center" id='placeholder'>
                                                            Please select item(s)
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <div class="col-12 text-right">
                                                <div class="m-2">
                                                    <button type="submit" class="btn btn-primary btn-block submit-once" id="submit-btn" disabled><i id="submit-logo" class="fas fa-check"></i> SUBMIT</button>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="number" value='0' id="item-counter" class='d-none'/>
                                    </div>
                                </form>
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
        $(document).ready(function (){
            $('#branch').change(function (){
                get_items($(this).val());

                $('.item-codes').each(function (){
                    remove_items($(this).val());
                });

                $('#add-modal-btn').prop('disabled', false);

                items_array = [];
                clear_add_table();

                validate_submit();
            });

            var items_array = new Array();
            function get_items(branch){
				$('#item-selection').select2({
                    templateResult: formatState,
                    placeholder: 'Select an item',
                    allowClear: true,
                    ajax: {
                        url: '/beginning_inv/get_received_items/' + branch,
                        method: 'GET',
                        dataType: 'json',
                        data: function (data) {
                            return {
                                q: data.term, // search term
                                excluded_items: items_array
                            };
                        },
                        processResults: function (response) {
                            return {
                                results:response
                            };
                        },
                        cache: true
                    }
                });
            }

            function formatState (opt) {
                if (!opt.id) {
                    return opt.text;
                }

                var optimage = opt.webp;
                if(optimage.indexOf('/icon/no_img') != -1){
                    optimage = opt.img;
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

            $('#add-item').click(function (){
                add_item('#selected-items-table');
                truncate_description();
                close_modal('#add-Modal');

                clear_add_table();
                validate_submit();
            });

            function clear_add_table(){
                $('#item-code-display').text('');
                $('#description-display').text('');
                $('#img-display').text('');
                $('#webp-display').text('');
                $('#alt-display').text('');
                $('#max-display').text('');
                $('#reason-display').val('');

                $('#new-item-stock').val('');
                $('#selected-item-price').text('');
                $("#item-selection").empty().trigger('change');

                $('#item-selection-table').addClass('d-none');
            }

            $(document).on('select2:select', '#item-selection', function(e){
                // Display
                $('#item-code-display').text(e.params.data.id); // item code
                $('#description-display').text(e.params.data.description); // description
                $('#selected-item-price').text(e.params.data.price); // description
                $('#new-img').attr('src', e.params.data.img); // image

                $('#new-src-img-webp').attr('src', e.params.data.webp); // webp
                $('#new-src-img').attr('src', e.params.data.img); // image
                $('#new-item-stock').data('max', e.params.data.max); // max

                // hidden values
                $('#webp-display').text(e.params.data.webp);
                $('#img-display').text(e.params.data.img);
                $('#alt-display').text(e.params.data.alt);
                $('#max-display').text(e.params.data.max);
                
                $('#new-item-stock').val(0);

                $('#item-selection-table').removeClass('d-none');
                $('#add-item').prop('disabled', false);
                truncate_description();
            });
            var showTotalChar = 98, showChar = "Show more", hideChar = "Show less";

            truncate_description();
            function truncate_description(){
                $('.item-description').each(function() {
                    var content = $(this).text();
                    if (content.length > showTotalChar) {
                        var con = content.substr(0, showTotalChar);
                        var hcon = content.substr(showTotalChar, content.length - showTotalChar);
                        var txt = con + '<span class="dots">...</span><span class="morectnt"><span>' + hcon + '</span>&nbsp;&nbsp;<a href="#" class="show-more">' + showChar + '</a></span>';
                        $(this).html(txt);
                    }
                });
            }

            $('table#selected-items-table').on('click', '.show-more', function (e){
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

            $('table#selected-items-table').on('click', '.qtyplus', function(e){
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
                validate_submit();
            });

            // This button will decrement the value till 0
            $('table#selected-items-table').on('click', '.qtyminus', function(e){
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
                validate_submit();
            });

            $('table#item-selection-table').on('click', '.new-item-qtyplus', function(e){
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
            $('table#item-selection-table').on('click', '.new-item-qtyminus', function(e){
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

            $('table#selected-items-table').on('click', '.remove-item', function(e){
                var item_code = $(this).data('id');
                remove_items(item_code);
                validate_submit();
            });

            $('table#selected-items-table').on('keyup', '.reason', function (e){
                validate_submit();
            });

            $('table#selected-items-table').on('keyup', '.dmg-qty', function (e){
                validate_submit();
            });


            function remove_items(item_code){
                $('#row-' + item_code).remove();

                items_array = jQuery.grep(items_array, function(value) {
                    return value != item_code;
                });

                $('#item-counter').val(parseInt($('#item-counter').val()) - 1);
            }

            function add_item(table){
                var item_code = $('#item-code-display').text();
                var description = $('#description-display').text();
                var image = $('#img-display').text();
                var webp = $('#webp-display').text();
                var alt = $('#alt-display').text();
                var max = $('#max-display').text();
                var reason = $('#reason-display').val();

                var stock = $('#new-item-stock').val();
                var price = $('#selected-item-price').text();

                var existing = $('#selected-items-table').find('#row-' + item_code).eq(0).length;
                if (existing) {
                    showNotification("warning", 'Item <b>' + item_code + '</b> already exists in the list.', "fa fa-info");
                    $("#item-selection").empty().trigger('change');
					return false;
                }

                var row = '<tr id="row-' + item_code + '">' +
                    '<td class="text-justify p-1 align-middle" colspan="3">' +
                        '<input type="text" name="item_code[]" id="' + item_code + '" class="d-none item-codes" value="' + item_code + '" />' +
                        '<div class="d-flex flex-row justify-content-center align-items-center">' +
                            '<div class="p-1 col-2 text-center">' +
                                '<picture>' +
                                    '<source srcset="' + webp + '" type="image/webp" class="img-thumbna1il" alt="User Image" width="40" height="40">' +
                                    '<source srcset="' + image + '" type="image/jpeg" class="img-thumbna1il" alt="User Image" width="40" height="40">' +
                                    '<img src="' + image + '" alt="' + alt + '" class="img-thumbna1il" alt="User Image" width="40" height="40">' +
                                '</picture>' +
                            '</div>' +
                            '<div class="p-1 col m-0">' +
                                '<span class="font-weight-bold font-responsive">' + item_code + '</span>' +
                            '</div>' +
                            '<div class="p-0 col-4 offset-1">' +
                                '<div class="input-group p-1">' +
                                    '<div class="input-group-prepend p-0">' +
                                        '<button class="btn btn-outline-danger btn-xs qtyminus" style="padding: 0 5px 0 5px;" type="button">-</button>' +
                                    '</div>' +
                                    '<div class="custom-a p-0">' +
                                        '<input type="text" class="form-control form-control-sm qty validate dmg-qty" id="' + item_code + '-stock" value="' + stock + '" data-item-code="' + item_code + '" data-max="' + max + '" name="damaged_qty[' + item_code + ']" style="text-align: center; width: 47px" required>' +
                                    '</div>' +
                                    '<div class="input-group-append p-0">' +
                                        '<button class="btn btn-outline-success btn-xs qtyplus" style="padding: 0 5px 0 5px;" type="button">+</button>' +
                                    '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="p-1 col-2 text-right">' +
                                '<span id="selected-item-price" style="font-size: 10pt; white-space: nowrap;">' + price + '</span>' +
                            '</div>' +
                            '<div class="p-1 col font-responsive remove-item" style="width: 15px !important; color: red; cursor: pointer" data-id="' + item_code + '"><i class="fa fa-remove"></i></div>' +
                        '</div>' +
                        '<div class="p-1 item-description" style="font-size: 9.5pt !important;">' +
                            description + 
                        '</div>' +
                        '<div class="p-1" style="font-size: 9.5pt !important;">' +
                            '<textarea name="reason[' + item_code +']" class="form-control reason" id="reason-' + item_code + '" placeholder="Describe the damage..." rows="5" required>' + reason + '</textarea>' +
                        '</div>' +
                    '</td>' +
                '</tr>';

                $(table).prepend(row);

                if(jQuery.inArray(item_code, items_array) === -1){
                    items_array.push(item_code);
                }

                truncate_description();
                $('#item-counter').val(parseInt($('#item-counter').val()) + 1);

                validate_submit();
            }

            function validate_submit(){
                // check item count
                var item_count_check = 0;
                if($('#item-counter').val() > 0){
                    item_count_check = 1;
                    $('#placeholder').addClass('d-none');
                }else{
                    $('#placeholder').removeClass('d-none');
                }

                // check if damage reasons for all items are filled up
                var reason_arr = new Array();
                $('.reason').each(function(){
                    reason_arr.push($(this).val() != '' ? 1 : 0);
                });
                var reason_check = Math.min.apply(Math, reason_arr);
                // var reason_check = 1;

                // check if all qty inputs are numbers and more than 0
                var qty_arr = new Array();
                $('.validate.dmg-qty').each(function(){
                    if($.isNumeric($(this).val()) && parseInt($(this).val()) > 0){
                        qty_arr.push(1);
                        $(this).css('border', '1px solid #CED4DA');
                    }else{
                        qty_arr.push(0);
                        $(this).css('border', '1px solid red');
                    }
                });
                var qty_check = Math.min.apply(Math, qty_arr);

                // validate if form is ready to submit
                if(item_count_check == 1 && reason_check == 1 && qty_check == 1){
                    $('#submit-btn').prop('disabled', false);
                }else{
                    $('#submit-btn').prop('disabled', true);
                }
            }

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
        });
    </script>
@endsection