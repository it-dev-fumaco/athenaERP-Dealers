@extends('item_attributes_updating.item_attrib_layout', [
    'namePage' => 'ERPInventory',
    'activePage' => 'dashboard',
])

@section('content')
    <div class="container-fluid align-center p-4">
        <div class="modal fade" id="preloader-modal" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h6 class="text-center m-0"><i class="fas fa-spinner"></i> Updating items. Please wait.</h6>
                        <button type="button" class="btn btn-default mt-3 d-none btn-sm" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 align-middle">
                <div class="float-left">
                    <a href="{{ url()->previous() }}" class="btn btn-default"><i class="fas fa-arrow-left"></i> Back</a>
                </div>
                <h4 class="text-left mt-1 ml-5 mb-1 pl-5">Template Item: <span class="font-weight-bold">{{ $itemParent->name }}</span> <small style="bordeR:">{{ $itemParent->description }}</small></h4>
            </div>
            <div class="col-md-4 form-inline">
                <div class="form-group col-8">
                    <select class="form-control" id="selec-item-attr"></select>
                </div>
                <button type="button" class="btn btn-primary ml-2" id="add-column"><i class="fas fa-plus"></i> Add</button>
                <button type="button" class="btn btn-secondary ml-2" id="reset-column"><i class="fas fa-redo"></i> Reset</button>
            </div>
            <div class="col-md-12 mt-3">
                <div class="alert alert-info">
                    <h5><i class="fas fa-info-circle"></i> Note:</h5>
                    Adding new attributes and value  to an existing variant code, will require to update as well the other variants codes on the same parent code.
                </div>
                <div class="p-3 alert alert-warning alert-dismissible text-center d-none align-middle" id="alertNotif">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5 class="m-0"><i class="icon fas fa-exclamation-circle"></i> <span>-</span></h5>
                </div>
                <div class="card card-secondary card-outline">
                    <div class="card-header">
                        <h5 class="card-title m-0">Item Variant(s) <span class="badge badge-info">{{ collect($completeAttr)->count() }}</span></h5>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                              <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                              <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-2">
                        <form action="/insert_attribute" method="POST" id="form-add">
                            {{-- @csrf --}}
                            <div style="height: 500px; max-width: 100%; overflow: auto;">
                                <table class="table table-bordered table-hover" id="variants-table" style="font-size: 0.9rem !important;">
                                    <thead>
                                        <tr>
                                            <th class="text-center align-middle text-nowrap p-2">Is Disabled</th>
                                            <th class="text-center align-middle text-nowrap p-2">Item Code</th>
                                            @foreach ($itemAttributes as $itemAttribute)
                                            <th class="text-center align-middle text-nowrap p-2">{{ $itemAttribute }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($completeAttr as $item_code => $row)
                                        <input type="hidden" name="itemCode" value="{{ $item_code }}">
                                        <tr>
                                            <td class="text-center align-middle p-2">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="hidden" value="{{ $row['disabled'] }}" name="is_disabled">
                                                    <input class="custom-control-input cb-1" type="checkbox" id="is-disabled-cb-{{ $item_code }}" {{ ($row['disabled']) ? 'checked' : '' }}>
                                                    <label for="is-disabled-cb-{{ $item_code }}" class="custom-control-label">Disabled</label>
                                                </div>
                                            </td>
                                            <td class="text-center align-middle font-weight-bold p-2">{{ $item_code }}</td>
                                            @foreach ($itemAttributes as $itemAttribute)
                                            <td class="text-center align-middle p-2">{{ $completeAttr[$item_code][$itemAttribute] }}</td>
                                            @endforeach
                                        </tr>
                                        @endforeach
                                        <input type="hidden" id="parentItem" value="{{ $itemParent->name }}">
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-12 text-center mt-3">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-check"></i> Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-12 mt-3">
                <div class="card collapsed-card card-danger card-outline">
                    <div class="card-header">
                        <h5 class="card-title m-0">Item Variants with Incomplete Attribute(s) <span class="badge badge-danger">{{ collect($incompleteAttr)->count() }}</span></h5>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                              <i class="fas fa-plus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                              <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-2">
                        <form action="/insert_attribute" method="POST" id="form-add-1">
                            <div style="height: 500px; max-width: 100%; overflow: auto;">
                                <table class="table table-bordered table-hover" style="font-size: 0.9rem !important;">
                                    <thead>
                                        <tr>
                                            <th class="text-center align-middle text-nowrap p-2">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" id="check-all" type="checkbox">
                                                    <label for="check-all" class="custom-control-label">Is Disabled</label>
                                                </div>
                                            </th>
                                            <th class="text-center align-middle text-nowrap p-2">Item Code</th>
                                            @foreach ($itemAttributes as $itemAttribute)
                                            <th class="text-center align-middle text-nowrap p-2">{{ $itemAttribute }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($incompleteAttr as $item_code => $row)
                                        <input type="hidden" name="itemCode" value="{{ $item_code }}">
                                        <tr>
                                            <td class="text-center align-middle p-2">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="hidden" class="tb-2" value="{{ $row['disabled'] }}" name="is_disabled">
                                                    <input class="custom-control-input cb-1 cb-2" type="checkbox" id="is-disabled-cb-{{ $item_code }}-1" {{ ($row['disabled']) ? 'checked' : '' }}>
                                                    <label for="is-disabled-cb-{{ $item_code }}-1" class="custom-control-label">Disabled</label>
                                                </div>
                                            </td>
                                            <td class="text-center align-middle font-weight-bold p-2">{{ $item_code }}</td>
                                            @foreach ($itemAttributes as $itemAttribute)
                                            <td class="text-center align-middle p-2">{{ (isset($incompleteAttr[$item_code][$itemAttribute])) ? $incompleteAttr[$item_code][$itemAttribute] : null }}</td>
                                            @endforeach
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-12 text-center mt-3">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-check"></i> Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <select id="attributeValues" class="d-none"></select>
    </div>

    <style>
		.select2{
			width: 100% !important;
		}
		.select2-selection__rendered {
			line-height: 27px !important;
		}
		.select2-container .select2-selection--single {
			height: 37px !important;
			/* padding-top: 1.5%; */
		}
		.select2-selection__arrow {
			height: 36px !important;
		}
	</style>

@endsection

@section('script')
    <script>
    $(document).ready(function() {
        $('#add-column').click(function(e){
            e.preventDefault();

            var column_name = $('#selec-item-attr').val();

            if(column_name) {
                var existing_columns = [];
                $('#variants-table tr').find('th').each(function(){
                    existing_columns.push($(this).text());
                });

                if(!existing_columns.includes(column_name)){
                    $('#alertNotif').addClass('d-none');
                    $('#variants-table').find('tr').each(function(){
                        $(this).find('td').last().after('<td><input type="hidden" name="newAttr" value="' + column_name + '"><select class="form-control custom-select2" name="newAttrVal" required> ' + $('#attributeValues').html() + '</select></td>');
                        $(this).find('th').last().after('<th class="text-center align-middle">' + column_name + '<input type="hidden" id="attributeName" value="' + column_name + '"></th>');
                    });
                } else {
                    $('#alertNotif').removeClass('d-none');
                    $('#alertNotif span').html('Attribute <b>' + column_name + "</b> already exists");
                }

                $(this).attr('disabled', true);

                $('.custom-select2').select2();
            }
        });

        $('#reset-column').click(function(e){
            e.preventDefault();

            location.reload(); 
        });

        $('#preloader-modal').on('hidden.bs.modal', function (e) {
            location.reload(); 
        });

        $('#selec-item-attr').select2({
            dropdownParent: $('#selec-item-attr').parent(),
            placeholder: 'Select Item Attribute',
            ajax: {
                url: '/getAttributes',
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

        $(document).on('select2:select', '#selec-item-attr', function(e){
            var data = e.params.data;
            
            $('#attributeValues').empty();
            $.ajax({
                type:"GET",
                url:"{{url('attribute_dropdown')}}?attribute_name="+encodeURIComponent(data.id),
                success:function(res){
                    $('#attributeValues').append('<option value="" selected disabled>- Select Value -</option>');
                    $.each(res,function(key,value){
                        $('#attributeValues').append('<option value="'+value+'">'+value+'</option>');
                    });
                }
            });
        });

        $('.cb-1').click(function(e){
            if($(this).prop("checked") == true){
                $(this).prev().val(1);
            } else {
                $(this).prev().val(0);
            }

            setPropCheckAll();
        });

        function setPropCheckAll(){
            var numberOfChecked = $('input:checkbox.cb-2:checked').length;
            var totalCheckboxes = $('input:checkbox.cb-2').length;

            if(numberOfChecked != totalCheckboxes) {
                $("#check-all").prop('checked', false);
            } else {
                $("#check-all").prop('checked', true);
            }
        }

        function getSerializedArrayFormData(arr){
            var data = {};
            $.each(arr, function () {
                if (data[this.name]) {
                    if (!data[this.name].push) {
                        data[this.name] = [data[this.name]];
                    }
                    data[this.name].push(this.value || '');
                } else {
                    data[this.name] = this.value || '';
                }
            });

            return data;
        }

        $('#check-all').click(function(e){
            if($(this).prop("checked") == true){
                $("input:checkbox.cb-2").prop('checked', true);
                $("input:text.tb-2").val(1);
            } else {
                $("input:checkbox.cb-2").prop('checked', false);
                $("input:text.tb-2").val(0);
            }

            setPropCheckAll();
        });

        $('#form-add').submit(function(e){
            e.preventDefault();

            submitAjax('#form-add');
        });

        function submitAjax(form) {
            $('#preloader-modal').modal('show');

            var action = $(form).attr('action');
            var formData = $(form).serializeArray();
            var splittedArr = splitArrayIntoChunksOfLen(formData, 400);
            var n = 1;
            var affectedRows = 0;
            $.each(splittedArr, function(i, d){
                var data = getSerializedArrayFormData(d);

                var arr = {
                    data, 
                    "_token": "{{ csrf_token() }}",
                    idx: $('#idx').val(),
                    parentItem: $('#parentItem').val(),
                    attributeName: $('#attributeName').val(),
                }

                $.ajax({
                    type: 'POST',
                    url: action,
                    data: arr,
                    success: function(response){
                        affectedRows = affectedRows + response.count;
                        if(n == splittedArr.length) {
                            if (response.status) {
                                var message = (response.displayCount) ? '<b>' + affectedRows + '</b> out of <b>' + affectedRows + '</b> ' + response.message : response.message;
                                $('#preloader-modal h6').html(message);
                                $('#preloader-modal button').removeClass('d-none');
                            }else{
                                $('#preloader-modal h6').text(response.message);
                                $('#preloader-modal button').addClass('d-none');
                            }
                        }
                        n++;
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        $('#preloader-modal').modal('hide');
                        alert('An error occured.');
                    }
                });
            });
        }

        $('#form-add-1').submit(function(e){
            e.preventDefault();

            submitAjax('#form-add-1');
        });

        function splitArrayIntoChunksOfLen(arr, len) {
            var chunks = [], i = 0, n = arr.length;
            while (i < n) {
                chunks.push(arr.slice(i, i += len));
            }

            return chunks;
        }
    });
    </script>
@endsection