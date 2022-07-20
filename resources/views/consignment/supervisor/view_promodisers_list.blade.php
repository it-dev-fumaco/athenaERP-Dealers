@extends('layout', [
    'namePage' => 'Promodisers List',
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
                            <h4 class="text-center font-weight-bold m-2 text-uppercase">Promodiser(s) List</h4>
                        </div>
                    </div>
                    <div class="card card-secondary card-outline">
                        <div class="card-header text-center">
                            <span class="font-weight-bolder d-block font-responsive">Assigned Store Promodiser(s) List</span>
                        </div>
                        <style>
                            table tbody:nth-child(even) {
                                background-color: #F2F4F4;
                            }
                        </style>
                        <div class="card-body p-3">
                            <table class="table table-bordered table-strip1ed" style="font-size: 9pt;" border="1">
                                <thead class="border-top">
                                    <th class="text-center font-responsive p-2 align-middle" style="width: 20%;">Promodiser Name</th>
                                    <th class="text-center font-responsive p-2 align-middle" style="width: 45%;">Assigned Store</th>
                                    <th class="text-center font-responsive p-2 align-middle" style="width: 10%;">Opening</th>
                                    <th class="text-center font-responsive p-2 align-middle" style="width: 20%;">Last Login</th>
                                    <th class="text-center font-responsive p-2 align-middle" style="width: 5%;">-</th>
                                </thead>
                                @forelse ($result as $row)
                                @php
                                    $store_count = count($row['stores']);
                                    $rowspan = $store_count > 1 ? $store_count : 0;
                                    $rowspan = $rowspan > 0 ? 'rowspan="'. $rowspan .'"' : '';
                                @endphp
                                <tbody>
                                    <tr>
                                        <td class="text-center p-1 align-middle" {!! $rowspan !!}>{{ $row['promodiser_name'] }}</td>
                                        <td class="text-center p-1 align-middle">{{ $row['stores'][0] }}</td>
                                        <td class="text-center p-1 align-middle {{ in_array($row['stores'][0], array_keys($stores_with_beginning_inventory)) ? 'bg-success' : 'bg-gray' }}">
                                            {!! array_key_exists($row['stores'][0], $stores_with_beginning_inventory) ? \Carbon\Carbon::parse($stores_with_beginning_inventory[$row['stores'][0]])->format('m-d-Y') : '&nbsp;' !!}
                                        </td>
                                        <td class="text-center p-1 align-middle" {!! $rowspan !!}>{{ $row['last_login'] ? \Carbon\Carbon::parse($row['last_login'])->format('F d, Y h:i A') : null }}</td>
                                        <td class="text-center p-1 align-middle" {!! $rowspan !!}>
                                            <a href="/edit_promodiser/{{ $row['id'] }}" class="btn btn-primary btn-xs"><i class="fas fa-user-edit"></i></a>
                                        </td>
                                    </tr>
                                    @if (count($row['stores']) > 1)
                                    @foreach ($row['stores'] as $store)
                                    @if (!$loop->first)
                                    <tr>
                                        <td class="text-center p-1 align-middle">{{ $store }}</td>
                                        <td class="text-center p-1 align-middle {{ in_array($store, array_keys($stores_with_beginning_inventory)) ? 'bg-success' : 'bg-gray' }}">
                                            {!! array_key_exists($store, $stores_with_beginning_inventory) ? \Carbon\Carbon::parse($stores_with_beginning_inventory[$store])->format('m-d-Y') : '&nbsp;' !!}
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach
                                    @endif




{{-- 
                                    <div class="modal fade" id="edit-{{ $row['id'] }}-modal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <form action="#" method="POST">
                                                @csrf
                                                <div class="modal-content">
                                                    <div class="modal-header bg-navy">
                                                        <h5 class="modal-title">Edit Promodiser</h5>
                                                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="d-flex flex-row align-items-center mb-1">
                                                            <div class="p-1 col-10">
                                                                <h6 class="text-center font-weight-bold p-0 m-0">Promodiser Name: {{ $row['promodiser_name'] }}</h6>
                                                            </div>
                                                            <div class="p-1 col-2 text-center">
                                                            </div>
                                                        </div>
                                                        <div class="d-flex flex-row align-items-center mb-1">
                                                            <div class="p-1 col-10">
                                                                <h6 class="text-center font-weight-bold p-0 m-0">Assigned Consignment Store(s)</h6>
                                                            </div>
                                                            <div class="p-1 col-2 text-center">
                                                                <button type="button" class="btn btn-primary btn-sm add-row-store"><i class="fas fa-plus"></i> Add Row</button>
                                                            </div>
                                                        </div>
                                                        <div class="assigned-stores">
                                                            @foreach ($row['stores'] as $store)
                                                            <div class="d-flex flex-row border">
                                                                <div class="p-2 col-10">
                                                                    <input class="form-control form-control-sm basicAutoComplete" type="text" data-url="/consignment_warehouses" placeholder="Enter Branch / Store" autocomplete="off" value="{{ $store }}" required>
                                                                </div>
                                                                <div class="p-2 col-2 text-center">
                                                                    <button type="button" class="btn btn-danger btn-sm remove-row-store"><i class="fas fa-trash-alt"></i></button>
                                                                </div>
                                                            </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary"><i class="fas fa-check"></i> UPDATE</button>
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i> CLOSE</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div> --}}













                                </tbody>
                                @empty
                                <tbody>
                                    <tr>
                                        <td class="text-center font-weight-bold text-uppercase text-muted" colspan="5">No record(s) found</td>
                                    </tr> 
                                </tbody>
                                @endforelse
                            </table>
                            <div class="float-left m-2">Total: <b>{{ $total_promodisers }}</b></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	</div>
</div>
@endsection

@section('script')
<script type="text/javascript" src="{{ asset('js/bootstrap-autocomplete.min.js') }}"></script>
<script>
    $(function () {
        $('.basicAutoComplete').autoComplete({
            minLength: 1,
            autoSelect: false,
        });

        $('.add-row-store').click(function(e) {
            e.preventDefault();

            var ht = '<div class="d-flex flex-row border">' +
                '<div class="p-2 col-10">' +
                '<input class="form-control form-control-sm basicAutoComplete" type="text" data-url="/consignment_warehouses" placeholder="Enter Branch / Store" autocomplete="off" value="" required>' +
                '</div><div class="p-2 col-2 text-center">' +
                '<button type="button" class="btn btn-danger btn-sm remove-row-store"><i class="fas fa-trash-alt"></i></button>' +
                '</div></div>';
                
            $(this).closest('.modal-body').find('.assigned-stores').append(ht);

            $('.basicAutoComplete').autoComplete({
                minLength: 1,
                autoSelect: false,
            });
        });

        $(document).on('click', '.remove-row-store', function(e) {
            e.preventDefault();
            $(this).closest('.flex-row').remove();
        });
    });
</script>
@endsection