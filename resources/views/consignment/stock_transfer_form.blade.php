@extends('layout', [
    'namePage' => 'Request Stock Transfer',
    'activePage' => 'beginning_inventory',
])

@section('content')
<div class="content">
	<div class="content-header p-0">
        <div class="container">
            <div class="row pt-1">
                <div class="col-md-12 p-0 m-0">
                    <div class="card card-secondary card-outline">
                        <div class="card-header text-center">
                            <h6 class="text-center mt-1 font-weight-bold">Stock Transfer Request</h6>
                        </div>
                        <div class="card-header text-center font-weight-bold">
                            <span class="font-responsive font-weight-bold text-uppercase d-inline-block">{{ \Carbon\Carbon::now()->format('F d, Y') }}</span>
                        </div>
                        <div class="card-body p-1">
                            @if(session()->has('error'))
                                <div class="callout callout-danger font-responsive text-center pr-1 pl-1 pb-3 pt-3 m-2">
                                    {{ session()->get('error') }}
                                </div>
                            @endif
                            <form action="/stock_transfer/submit" method="post">
                                @csrf
                                <div class="row p-1" style="font-size: 9pt">
                                    @php
                                        $purpose = ['Store Transfer', 'For Return', 'Sales Return'];
                                    @endphp
                                    <div class="col-2 pt-2">
                                        <label for="transfer_as">Purpose</label>
                                    </div>
                                    <div class="col-10 pt-1">
                                        <select name="transfer_as" id='transfer-as' class="form-control" required style="font-size: 9pt">
                                            <option value="" disabled selected>Select Purpose</option>
                                            @foreach ($purpose as $p)
                                                <option value="{{ $p }}">{{ $p }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row p-1" id="source" style="font-size: 9pt">
                                    <div class="col-2 pt-2">
                                        <label for="source_warehouse">From</label>
                                    </div>
                                    <div class="col-10">
                                        <select name="source_warehouse" id='src-warehouse' class="form-control" required style="font-size: 9pt">
                                            <option value="" disabled selected>Select Source Warehouse</option>
                                            @foreach ($assigned_consignment_stores as $store)
                                                <option value="{{ $store }}">{{ $store }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row p-1 mt-2" id="target" style="font-size: 9pt; display: none">
                                    <div class="col-2 pt-2">
                                        <label for="target_warehouse">To</label>
                                    </div>
                                    <div class="col-10">
                                        <input type="text" name="default_warehouse" id="wh-for-return" class="form-control" value="Fumaco - Plant 2" readonly style="font-size: 10pt">
                                        <div id="target-warehouse-container">
                                            <select name="target_warehouse" id="target-warehouse" class="form-control" disabled style="font-size: 9pt"></select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row p-1 mt-2" id="items-to-return" style="display: none">
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-7">
                                                <input type="text" class="form-control form-control-sm" id="item-search" name="search" autocomplete="off" placeholder="Search"/>
                                            </div>
                                            <div class="col-5">
                                                <button type="button" class="btn btn-primary w-100" id="open-item-modal" style="font-size: 10pt;" data-toggle="modal" data-target="#add-item-Modal" disabled><i class="fa fa-plus"></i> Add item</button>

                                                <div class="modal fade" id="add-item-Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-navy">
                                                                <h5 class="modal-title" id="exampleModalLabel">Add an Item</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true" style="color: #fff">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <select id="received-items" class="form-control" style="font-size: 9pt;"></select>
                                                                <br><br>
                                                                <div class="container-fluid d-none" id="items-container">
                                                                    <table class="table" id='items-selection-table' style="font-size: 10pt;">
                                                                        <tr>
                                                                            <th class="text-center" style="width: 40%">Item</th>
                                                                            <th class="text-center" style="width: 25%"><span class='qty-col'>Stocks</span></th>
                                                                            <th class="text-center transfer-text">Qty to Transfer</th>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="3">
                                                                                <div class="row">
                                                                                    <div class="p-0 col-2 text-center">
                                                                                        <picture>
                                                                                            <source srcset="" id='webp-src-display' type="image/webp">
                                                                                            <source srcset="" id='img-src-display' type="image/jpeg">
                                                                                            <img src="" alt="" id='img-src' class="img-thumbnailm" alt="User Image" width="40" height="40">
                                                                                        </picture>
                                                                                        <div class="d-none">
                                                                                            <span id="img-text"></span>
                                                                                            <span id="webp-text"></span>
                                                                                            <span id="alt-text"></span>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="p-1 col-3 m-0" style="display: flex; justify-content: center; align-items: center;">
                                                                                        <span id='item-code-text' class="font-weight-bold"></span>
                                                                                    </div>
                                                                                    <div class="col-3" style="display: flex; justify-content: center; align-items: center; height: 44px">
                                                                                        <div class="text-center">
                                                                                            {{-- <b><span id="stocks-text"></span></b><br><small><span id="uom-text"></span></small> --}}
                                                                                            <div>
                                                                                                <b><span id="stocks-text"></span></b><br>
                                                                                                <small><span id="uom-text"></span></small>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col p-0">
                                                                                        <div class="input-group p-1 ml-3">
                                                                                            <div class="input-group-prepend p-0">
                                                                                                <button class="btn btn-outline-danger btn-xs qtyminus" style="padding: 0 5px 0 5px;" type="button">-</button>
                                                                                            </div>
                                                                                            <div class="custom-a p-0">
                                                                                                <input type="text" class="form-control form-control-sm qty" value="0" id="qty-input" data-max="0" style="text-align: center; width: 40px">
                                                                                            </div>
                                                                                            <div class="input-group-append p-0">
                                                                                                <button class="btn btn-outline-success btn-xs qtyplus" style="padding: 0 5px 0 5px;" type="button">+</button>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-12 text-justify">
                                                                                        <span id="description-text"></span>
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" id="add-item" class="btn btn-primary w-100" disabled>Add item</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="container-fluid mt-2">
                                        <table class="table" id='items-table' style="font-size: 9pt">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" style="width: 40%">Item</th>
                                                    <th class="text-center" style="width: 25%"><span class='qty-col'>Stocks</span></th>
                                                    <th class="text-center transfer-text">Qty to Transfer</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr id="placeholder">
                                                    <td colspan=3 class='text-center'>Please Select an Item</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="container-fluid mt-2 text-center">
                                        <button type="submit" id="submit-btn" class="btn btn-primary w-100 d-none submit-once">Submit</button>
                                    </div>

                                    <span id="counter" class='d-none'>0</span>
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
        .morectnt span {
            display: none;
        }
    </style>
@endsection

@section('script')
    <script>
        $(document).ready(function (){
            $('#transfer-as').change(function (){
                $('#target').slideDown();
                var src = $('#src-warehouse').val();
                $('.qty-col').text('Stocks');
                if($(this).val() == 'Store Transfer'){
                    if($('#source').is(':hidden')){
                        $('#source').slideDown();
                    }
                    $('#wh-for-return').addClass('d-none');

                    $('#target-warehouse-container').removeClass('d-none');
                    $('#target-warehouse').prop('required', true);
                    if($('#src-warehouse').val() == ''){
                        $('#target-warehouse').attr("disabled", true);
                    }

                    $('#src-warehouse').prop('required', true);
                    $('.transfer-text').text('Qty to Transfer');
                }else if($(this).val() == 'For Return'){
                    if($('#source').is(':hidden')){
                        $('#source').slideDown();
                    }
                    $('#wh-for-return').removeClass('d-none');

                    $('#target-warehouse').prop('required', false);
                    $('#target-warehouse-container').addClass('d-none');

                    $('#src-warehouse').prop('required', true);
                    $('.transfer-text').text('Qty to Transfer');

                    $('#items-to-return').slideDown();
                }else{ // sales returns
                    $('#wh-for-return').addClass('d-none');

                    $('#target-warehouse-container').removeClass('d-none');
                    $('#target-warehouse').prop('required', true);
                    $('#target-warehouse').attr("disabled", false);

                    $('#src-warehouse').prop('required', false);
                    $('.transfer-text').text('Qty Returned');
                    $('.qty-col').text('Qty Sold');

                    if($('#source').is(':visible')){
                        $('#source').slideUp();
                    }

                    src = null;
                }

                $("#target-warehouse").empty().trigger('change');
                $("#received-items").empty().trigger('change');

                $('.items-list').each(function() {
                    var item_code = $(this).val();
                    remove_items(item_code);
                });

                $('#submit-btn').addClass('d-none');
                $('#placeholder').removeClass('d-none');
                $('#items-container').addClass('d-none');

                get_received_items(src);
                reset_placeholders();

                items_array = [];
            });

            $('#src-warehouse').change(function(){
                var src = $(this).val();
                get_received_items(src);

                $('#target-warehouse').attr("disabled", false);

                $('#placeholder').removeClass('d-none');
                $('#items-container').addClass('d-none');
                $("#received-items").empty().trigger('change');

                $('.items-list').each(function() {
                    var item_code = $(this).val();
                    remove_items(item_code);
                });
                
                $('#open-item-modal').prop('disabled', false);

                reset_placeholders();
                validate_submit();

                items_array = [];
            });

            $('#target-warehouse').select2({
                placeholder: 'Select Target Warehouse',
                allowClear: true,
                ajax: {
                    url: '/consignment_stores',
                    method: 'GET',
                    dataType: 'json',
                    data: function (data) {
                        return {
                            q: data.term, // search term
                            assigned_to_me: $('#transfer-as').val() == 'Sales Return' ? 1 : 0
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

            $(document).on('select2:select', '#target-warehouse', function(e){
                $('#items-to-return').slideDown();
                if($('#transfer-as').val() == 'Sales Return'){
                    var warehouse = e.params.data.text;
                    get_received_items(warehouse);
                    
                    $('.items-list').each(function() {
                        var item_code = $(this).val();
                        remove_items(item_code);
                    });
                    items_array = [];

                    validate_submit();
                    reset_placeholders();

                    $('#placeholder').removeClass('d-none');
                    $('#open-item-modal').prop('disabled', false);
                }
            });

            function get_received_items(branch){
                $('#received-items').select2({
                    templateResult: formatState,
                    placeholder: 'Select an Item',
                    allowClear: true,
                    ajax: {
                        url: '/beginning_inv/get_received_items/' + branch,
                        method: 'GET',
                        dataType: 'json',
                        data: function (data) {
                            return {
                                q: data.term, // search term
                                excluded_items: items_array,
                                purpose: $('#transfer-as').val()
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
                    '<span><img src="' + optimage + '" width="50px" /> ' + opt.text + '</span>'
                    );
                    return $opt;
                }
            };

            validate_submit();
            function validate_submit(){
                var inputs = new Array();
                var max_check = new Array();

                $('.validate.qty.to-return').each(function (){
                    var max = $(this).data('max');
                    var val = $(this).val();

                    if($.isNumeric(val) && parseInt(val) > 0 && parseInt(val) <= parseInt(max)){
                        $(this).css('border', '1px solid #CED4DA');
                        inputs.push(1);
                    }else{
                        $(this).css('border', '1px solid red');
                        inputs.push(0);
                    }
                });

                var stocks_check = inputs.length > 0 ? Math.min.apply(Math, inputs) : 0;

                if(parseInt($('#counter').text()) > 0 && stocks_check == 1){
                    $('#submit-btn').prop('disabled', false);
                }else{
                    $('#submit-btn').prop('disabled', true);
                }
            }

            function remove_items(item_code){
                $('.row-' + item_code).remove();
                var val = parseInt($('#counter').text()) - 1;

                items_array = jQuery.grep(items_array, function(value) {
                    return value != item_code;
                });

                val = val > 0 ? val : 0;
                $('#counter').text(val);
            }

            function reset_placeholders(){
                $('#qty-input').val(0);
                $('#img-text').text(null);
                $('#alt-text').text(null);
                $('#uom-text').text(null);
                $('#webp-text').text(null);
                $('#stocks-text').text(null);
                $('#qty-input').data('max', 0);
                $('#img-src').attr('src', null);
                $('#item-code-text').text(null);
                $('#description-text').text(null);
                $('#img-src-display').attr('src', null);
                $('#webp-src-display').attr('src', null);
            }
            
            $(document).on('select2:select', '#received-items', function(e){
                $('#img-text').text(e.params.data.img);
                $('#alt-text').text(e.params.data.alt);
                $('#uom-text').text(e.params.data.uom);
                $('#webp-text').text(e.params.data.webp);
                $('#stocks-text').text(e.params.data.max);
                $('#item-code-text').text(e.params.data.id);
                $('#img-src').attr('src', e.params.data.img);
                $('#qty-input').data('max', e.params.data.max);
                $('#img-src-display').attr('src', e.params.data.img);
                $('#webp-src-display').attr('src', e.params.data.webp);
                $('#description-text').text(e.params.data.description);
                
                $('#add-item').prop('disabled', false);
                $('#items-container').removeClass('d-none');
            });

            // Modal Add/Subtract Controls
            $('table#items-selection-table').on('click', '.qtyplus', function(e){
                // Stop acting like a button
                e.preventDefault();
                // Get the field name
                var fieldName = $(this).parents('.input-group').find('.qty').eq(0);
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

            $('table#items-selection-table').on('click', '.qtyminus', function(e){
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
            // Modal Add/Subtract Controls

            $("#item-search").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#items-table tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });

            var items_array = new Array();
            $('#add-item').click(function (){
                var img = $('#img-text').text();
                var alt = $('#alt-text').text();
                var qty = $('#qty-input').val();
                var uom = $('#uom-text').text();
                var webp = $('#webp-text').text();
                var stocks = $('#stocks-text').text();
                var item_code = $('#item-code-text').text();
                var description = $('#description-text').text();

                var existing = $('#items-table').find('.' + item_code).eq(0).length;
                if (existing) {
                    showNotification("warning", 'Item <b>' + item_code + '</b> already exists in the list.', "fa fa-info");
					return false;
                }

                var row = '<tr class="row-' + item_code + ' ' + item_code + '">' +
                    '<td colspan=3 class="text-center p-0">' +
                        '<div class="d-none">' + description + '</div>' + // reference for search
                        '<div class="row">' +
                            '<input name="item_code[]" class="items-list d-none" value="' + item_code + '" id="' + item_code + '" />' +
                            '<div class="p-1 col-2 text-center">' +
                                '<picture>' +
                                    '<source srcset="' + webp + '" type="image/webp">' +
                                    '<source srcset="' + img + '" type="image/jpeg">' +
                                    '<img src="' + img + '" alt="' + alt + '" class="img-thumbnail" alt="User Image" width="40" height="40">' +
                                '</picture>' +
                            '</div>' +
                            '<div class="p-1 col-2 m-0" style="display: flex; justify-content: center; align-items: center;">' +
                                '<span class="font-weight-bold">' + item_code + '</span>' +
                            '</div>' +
                            '<div class="col-3 offset-1" style="display: flex; justify-content: center; align-items: center; height: 44px">' +
                                '<div><span><b>' + stocks + '</b></span><br/>' +
                                '<small>' + uom + '</small></div>' +
                            '</div>' +
                            '<div class="col p-0">' +
                                '<div class="input-group p-1 ml-2">' +
                                    '<div class="input-group-prepend p-0">' +
                                        '<button class="btn btn-outline-danger btn-xs qtyminus" style="padding: 0 5px 0 5px;" type="button">-</button>' +
                                    '</div>' +
                                    '<div class="custom-a p-0">' +
                                        '<input type="text" class="form-control form-control-sm validate qty to-return" id="qty-' + item_code + '" value="' + qty + '" data-item-code="' + item_code + '" name="item[' + item_code + '][transfer_qty]" data-max="' + stocks + '" style="text-align: center; width: 40px">' +
                                    '</div>' +
                                    '<div class="input-group-append p-0">' +
                                        '<button class="btn btn-outline-success btn-xs qtyplus" style="padding: 0 5px 0 5px;" type="button">+</button>' +
                                    '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-1 text-center remove-item" data-item-code="' + item_code + '">' +
                                '<i class="fa fa-remove" style="color: red"></i>' +
                            '</div>' +
                        '</div>' +
                    '</td>' +
                '</tr>' +
                '<tr class="row-' + item_code + '">' +
                    '<td colspan=3 class="text-justify p-2" style="font-size: 10pt;">' +
                        '<div class="d-none">' + item_code + '</div>' + // reference for search
                        '<div class="item-description">' + description + '</div>' +
                    '</td>' +
                '</tr>';

                if(jQuery.inArray(item_code, items_array) === -1){
                    items_array.push(item_code);
                }

                $('#counter').text(parseInt($('#counter').text()) + 1);
                $("#received-items").empty().trigger('change');
                $('#items-container').addClass('d-none');
                $('#submit-btn').removeClass('d-none');
                $('#add-item').prop('disabled', true);
                $('#items-table tbody').prepend(row);
                $('#placeholder').addClass('d-none');

                close_modal('#add-item-Modal');
                reset_placeholders();
                validate_submit();
                cut_text();
            });

            $('table#items-table').on('keyup', '.validate', function (e){
                validate_submit();
            })

            $('table#items-table').on('click', '.remove-item', function (e){
                e.preventDefault();
                var item_code = $(this).data('item-code');

                remove_items(item_code);
                validate_submit();
            });

            $('table#items-table').on('click', '.qtyplus', function(e){
                // Stop acting like a button
                e.preventDefault();
                // Get the field name
                var fieldName = $(this).parents('.input-group').find('.qty').eq(0);
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
            $('table#items-table').on('click', '.qtyminus', function(e){
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

            cut_text();
            var showTotalChar = 90, showChar = "Show more", hideChar = "Show less";
            function cut_text(){
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

            $('table#items-table').on('click', '.show-more', function(e){
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