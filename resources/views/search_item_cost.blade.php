@extends('layout', [
    'namePage' => 'Item Cost Updating',
    'activePage' => 'dashboard',
])

@section('content')
<div class="content bg-white">
	<div class="content-header pt-0">
		<div class="container-fluid">
			<div class="row pt-3">
				<div class="col-sm-12">
                    <h5 class="font-weight-bold">List of Item Prices</h5>
                    <div class="card card-danger card-outline">
                        <div class="card-header p-0">
                            <div class="d-flex flex-row">
                                <div class="p-2" style="width: 250px;">
                                    <div class="form-group m-0 font-responsive">
                                        <select name="ig" class="form-control form-control-sm" id="ig">
                                            <option value="">Select Item Group</option>
                                            @foreach ($item_groups as $ig)
                                            <option value="{{ $ig->name }}" data-isgroup="{{ $ig->is_group }}">{{ $ig->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="p-2 d-none" id="ig1-div" style="width: 250px;">
                                    <div class="form-group m-0 font-responsive">
                                        <select name="ig1" class="form-control form-control-sm" id="ig1"></select>
                                    </div>
                                </div>
                                <div class="p-2 d-none" id="ig2-div" style="width: 250px;">
                                    <div class="form-group m-0 font-responsive">
                                        <select name="ig2" class="form-control form-control-sm" id="ig2"></select>
                                    </div>
                                </div>
                                <div class="p-2 d-none" id="ig3-div" style="width: 250px;">
                                    <div class="form-group m-0 font-responsive">
                                        <select name="ig3" class="form-control form-control-sm" id="ig3"></select>
                                    </div>
                                </div>
                                <div class="p-2 d-none" id="ig4-div" style="width: 250px;">
                                    <div class="form-group m-0 font-responsive">
                                        <select name="ig4" class="form-control form-control-sm" id="ig4"></select>
                                    </div>
                                </div>
                                <div class="p-2 d-none" id="ig5-div" style="width: 250px;">
                                    <div class="form-group m-0 font-responsive">
                                        <select name="ig5" class="form-control form-control-sm" id="ig5"></select>
                                    </div>
                                </div>
                                <div class="p-2 d-none" id="variant-of-div" style="width: 250px;">
                                    <div class="form-group m-0 font-responsive">
                                        <select name="variant_of" class="form-control form-control-sm" id="variant-of"></select>
                                    </div>
                                </div>
                                <div class="p-2">
                                    <button class="btn btn-primary btn-sm font-responsive" id="get-items-btn" type="button">Get Item(s)</button>
                                </div>
                                <div class="p-2">
                                    <a href="/search_item_cost" class="btn btn-secondary btn-sm font-responsive">Reset</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="row">
                                <div class="col-md-6 offset-md-3 p-2">
                                    <div id="item-templates-tbl"></div>
                                </div>
                            </div>
                        </div>
                    </div>
				</div>
			</div>
		</div>
	</div>
</div>

<style>
    .select2-selection__rendered {
        line-height: 35px !important;
    }
    .select2-container .select2-selection--single {
        height: 30px !important;
        padding-top: 1.5%;
    }
    .select2-selection__arrow {
        height: 36px !important;
    }
</style>
@endsection

@section('script')
<script>
    $(document).ready(function(){
        $('#ig').select2();

        $(document).on('select2:select', '#ig', function(e){
            var is_group = $(this).find(":selected").data('isgroup');
            $('#ig1').empty();
            $('#ig2').empty();
            $('#ig3').empty();
            $('#ig4').empty();
            $('#ig5').empty();
            $('#ig1-div').addClass('d-none');
            $('#ig2-div').addClass('d-none');
            $('#ig3-div').addClass('d-none');
            $('#ig4-div').addClass('d-none');
            $('#ig5-div').addClass('d-none');
            item_group_per_parent(e.params.data.text, is_group, '#ig1');
        });

        $(document).on('select2:select', '#ig1', function(e){
            item_group_per_parent(e.params.data.text, e.params.data.is_group, '#ig2');
        });

        $(document).on('select2:select', '#ig2', function(e){
            item_group_per_parent(e.params.data.text, e.params.data.is_group, '#ig3');
        });

        $(document).on('select2:select', '#ig3', function(e){
            item_group_per_parent(e.params.data.text, e.params.data.is_group, '#ig4');
        });

        function item_group_per_parent(parent, is_group, el) {
            if (is_group) {
                switch(el) {
                    case '#ig1':
                        callback = ig1callback;
                        break;
                    case '#ig2':
                        callback = ig2callback;
                        break;
                    case '#ig3':
                        callback = ig3callback;
                        break;
                    case '#ig4':
                        callback = ig4callback;
                        break;
                    case '#ig5':
                        callback = ig5callback;
                        break;
                    default:
                        callback = null;
                }

                $.ajax({
                    type: 'GET',
                    url: '/item_group_per_parent/' + parent,
                    success: callback
                });
            } else {
                $('#variant-of-div').removeClass('d-none');
                $('#variant-of').select2({
                    placeholder: 'Select Parent Item',
                    ajax: {
                        url: '/get_parent_item',
                        method: 'GET',
                        dataType: 'json',
                        data: function (data) {
                            return {
                                q: data.term,
                                itemgroup: $('#ig').val(),
                                itemgroup1: $('#ig1').val(),
                                itemgroup2: $('#ig2').val(),
                                itemgroup3: $('#ig3').val(),
                                itemgroup4: $('#ig4').val(),
                                itemgroup5: $('#ig5').val(),
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
        }

        function ig1callback(data) {
            $('#ig1').empty();
            $('#ig1').select2({
                placeholder: "Item Group Level 1",
                data: data
            });

            $('#ig1').val(null).trigger('change');

            $('#ig1-div').removeClass('d-none');
        } 

        function ig2callback(data) {
            $('#ig2').empty();
            $('#ig2').select2({
                placeholder: "Item Group Level 2",
                data: data
            });

            $('#ig2').val(null).trigger('change');

            $('#ig2-div').removeClass('d-none');
        } 

        function ig3callback(data) {
            $('#ig3').empty();
            $('#ig3').select2({
                placeholder: "Item Group Level 3",
                data: data
            });

            $('#ig3').val(null).trigger('change');

            $('#ig3-div').removeClass('d-none');
        } 

        function ig4callback(data) {
            $('#ig4').empty();
            $('#ig4').select2({
                placeholder: "Item Group Level 4",
                data: data
            });

            $('#ig4').val(null).trigger('change');

            $('#ig4-div').removeClass('d-none');
        } 

        function ig5callback(data) {
            $('#ig5').empty();
            $('#ig5').select2({
                placeholder: "Item Group Level 5",
                data: data
            });

            $('#ig5').val(null).trigger('change');

            $('#ig5-div').removeClass('d-none');
        } 

        function load_parent_items(page) {
            var data = {
                itemgroup: $('#ig').val(),
                itemgroup1: $('#ig1').val(),
                itemgroup2: $('#ig2').val(),
                itemgroup3: $('#ig3').val(),
                itemgroup4: $('#ig4').val(),
                itemgroup5: $('#ig5').val(),
                page: page,
                list: 1,
                variant_of: $('#variant-of').val()
            };

            if ($('#variant-of').val()) {
                window.location = '/view_variants/' + $('#variant-of').val();
            } else {
                $.ajax({
                    type: "GET",
                    url: "/get_parent_item",
                    data: data,
                    success: function (data) {
                        $('#item-templates-tbl').html(data);
                    }
                });
            }
        }

        $(document).on('click', '#item-templates-pagination a', function(event){
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            load_parent_items(page);
        });

        $('#get-items-btn').click(function(e) {
            e.preventDefault();
            load_parent_items();
        });

        $('#reset-btn').click(function(e) {
            e.preventDefault();

            window.location = '/search_item_cost';
        });
    });
</script>
@endsection