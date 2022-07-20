@extends('layout', [
    'namePage' => 'Edit Promodiser',
    'activePage' => 'dashboard',
])

@section('content')
<div class="content">
	<div class="content-header p-0">
        <div class="container">
            <div class="row pt-1">
                <div class="col-md-12 p-0 m-0">
                    <div class="row">
                        <div class="col-2">
                            <div style="margin-bottom: -43px;">
                                <a href="/" class="btn btn-secondary" style="width: 80px;"><i class="fas fa-arrow-left"></i></a>
                            </div>
                        </div>
                        <div class="col-10 col-lg-8 p-0">
                            <h4 class="text-center font-weight-bold m-2 text-uppercase">Edit Promodiser</h4>
                        </div>
                    </div>
                    <div class="card card-secondary card-outline">
                        <div class="d-flex flex-row align-items-center mr-3 mt-3 ml-3">
                            <div class="p-1 col-6 text-left">Name: <b>{{ $promodiser->full_name }}</b></div>
                            <div class="p-1 col-6 text-center">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="cb-disabled" {{ $promodiser->enabled ? '' : 'checked' }}>
                                    <label class="form-check-label" for="cb-disabled">Disabled</label>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-row align-items-center ml-3">
                            <div class="p-1 col-6 text-left">Last Login: <b>{{ $promodiser->last_login ? \Carbon\Carbon::parse($promodiser->last_login)->format('F d, Y h:i A') : null }}</b></div>
                        </div>
                        <div class="card-body p-3">
                            <div class="d-flex flex-row align-items-center mb-1">
                                <div class="p-1 col-12">
                                    <h6 class="text-center font-weight-bold p-0 m-0">Assigned Consignment Store(s)</h6>
                                </div>
                            </div>
                            <table class="table table-bordered table-strip1ed" style="font-size: 9pt;" id="assigned-stores">
                                <thead class="border-top">
                                    <th class="text-center font-responsive p-2 align-middle" style="width: 80%;">Store / Branch</th>
                                    <th class="text-center font-responsive p-1 align-middle" style="width: 20%;">
                                        <button type="button" class="btn btn-primary btn-sm add-row-store"><i class="fas fa-plus"></i> Add Row</button>
                                    </th>
                                </thead>
                                <tbody>
                                @forelse ($assigned_stores as $row)
                                <tr>
                                    <td class="text-center p-1 align-middle">
                                        <select class="form-control select-branch-warehouse" name="consignment_warehouse" id="select-branch-warehouse-c"></select>
                                        {{-- <input class="form-control form-control-sm basicAutoComplete" type="text" data-url="/consignment_warehouses" placeholder="Enter Branch / Store" autocomplete="off" value="{{ $row->warehouse }}" required> --}}
                                    </td>
                                    <td class="text-center p-1 align-middle"><button type="button" class="btn btn-danger btn-sm remove-row-store"><i class="fas fa-trash-alt"></i></button></td>
                                </tr>
                                @empty
                                <tr>
                                    <td class="text-center font-weight-bold text-uppercase text-muted" colspan="5">No assigned store(s) found</td>
                                </tr> 
                                @endforelse
                            </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	</div>
</div>
@endsection

@section('script')
{{-- <script type="text/javascript" src="{{ asset('js/bootstrap-autocomplete.min.js') }}"></script> --}}
<script>
    $(function () {
        // $('.basicAutoComplete').autoComplete({
        //     minLength: 1,
        // });

        $('.add-row-store').click(function(e) {
            e.preventDefault();

            var ht = '<tr>' +
                '<td class="text-center p-1 align-middle">' +
                '<input class="form-control form-control-sm basicAutoComplete" type="text" data-url="/consignment_warehouses" placeholder="Enter Branch / Store" autocomplete="off" required>' +
                '</td>' +
                '<td class="text-center p-1 align-middle"><button type="button" class="btn btn-danger btn-sm remove-row-store"><i class="fas fa-trash-alt"></i></button></td>' +
                '</tr>';
                
            $('#assigned-stores tbody').append(ht);

            // $('.basicAutoComplete').autoComplete({
            //     minLength: 1,
            // });
        });

        $(document).on('click', '.remove-row-store', function(e) {
            e.preventDefault();
            $(this).closest('tr').remove();
        });

        $('.select-branch-warehouse').select2({
            // dropdownParent: $('#assigned-stores'),
            placeholder: 'Select Branch',
            ajax: {
                url: '/consignment_warehouses',
                method: 'GET',
                dataType: 'json',
                data: function (data) {
                    return {
                        q: data.term // search term
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
    });
</script>
@endsection