@extends('item_attributes_updating.item_attrib_layout', [
    'namePage' => 'ERPInventory',
    'activePage' => 'dashboard',
])

@section('content')
    <div class="container-fluid align-center">
        <div class="modal fade" id="preloader-modal" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h6 class="text-center m-0"><i class="fas fa-spinner"></i> Updating item. Please wait.</h6>
                        <button type="button" class="btn btn-default mt-3 d-none btn-sm" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        @if(\Session::has('notFound'))
            <div class="col-md-8 offset-md-2 alert alert-danger text-center mt-2">
                <span id="notFound">{!! \Session::get('notFound') !!}</span>
            </div>

            <div class="col-md-8 offset-md-2 text-center">
                <a href="/search">
                    <button type="button" class="btn btn-primary">Go back to Search Page</button>
                </a>
            </div>
        @else
        <div class="col-md-8 offset-md-2 pt-2 mb-0">
            <div class="alert alert-warning" role="alert">
                Editing of Attribute Values
                Warning:
                <ul>
                    <li>Items attributes will be updated, all link attributes and transactions will also be updated</li>
                    <li>All Items using those attribute value will also update</li>
                </ul>                
            </div>
        </div>
        <div class="col-md-8 card bg-white p-2" style="margin: 0 auto !important;">
            <div class="box">
                <div class="box-body">
                    <div class="col-md-12">
                        @if(\Session::has('success'))
                            <div class="col-md-12 alert alert-success text-center">
                                <span id="successMessage">{!! \Session::get('success') !!}</span>
                            </div>
                        @endif
                    </div>
                    <table class="table table-bordered m-0">
                        <tbody>
                            <tr>
                                <th class="text-center">Attribute</th>
                                <th class="text-center">Attribute Value</th>
                                <th class="text-center">Attribute Value Update</th>
                                <th class="text-center">Attribute Abbreviation</th>
                                <th class="text-center">-</th>
                            </tr>
                            <form id="updateForm" action="/update_attribute" method="POST">
                                {{-- @foreach($parentDesc as $desc)
                                    <h3>Update <b>{{ $item_code }}</b> Attributes</h3>
                                    <input type="text" id="itemCodeValue" name="parDesc" value="{{ $desc->description }}" readonly hidden/>
                                    <span>Variant of <b>{{ $itemDesc->variant_of }}</b> - {{ $desc->description }}</span>
                                    <br/>
                                    <span>Item Description</span>
                                    <textarea class="form-control" rows="3" name="item_description" readonly>{{ $itemDesc->description }}</textarea>
                                    <br/>
                                @endforeach --}}
                                @foreach($parentDesc as $desc)
                                    <h4 class="text-center">Update <b>{{ $item_code }}</b> Item Attributes</h4>
                                    <input type="text" id="itemCodeValue" name="parDesc" value="{{ $desc->description }}" readonly hidden/>
                                    <div class="text-justify">
                                        <small>{{ $itemDesc->description }}</small>
                                    </div>
                                    <span class="d-block text-left mt-2 mb-1">Variant of <b>{{ $itemDesc->variant_of }}</b> - {{ $desc->description }}</span>
                                @endforeach
                                
                                <input type="text" id="itemCodeValue" name="itemCode" value="{{ $item_code }}" readonly hidden/>
                                @csrf
                                @forelse($attribute_values as $value)
                                    <tr>
                                        <td>
                                            <input type="text" name="attribName[]" value="{{ $value['attribute'] }}" readonly hidden/>
                                            {{ $value['attribute'] }}
                                        </td>
                                        <td>
                                            <input type="text" name="currentAttrib[]" value="{{ $value['attribute_value'] }}" readonly hidden/>
                                            {{ $value['attribute_value'] }}
                                        </td>
                                        <td class="p-1">
                                            <input type="text" id="attribVal" class="form-control" name="attrib[]" value="{{ $value['attribute_value'] }}" required/>
                                        </td>
                                        <td class="p-1">
                                            <input type="text" name="currentAbbr[]" value="{{ $value['abbr'] }}" readonly hidden/>
                                            <input type="text" id="attribAbbr" class="form-control" name="abbr[]" value="{{ $value['abbr'] }}" maxlength="5" required/>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-info">{{ $value['count'] }}</span>
                                        </td>
                                    </tr>
                                @empty
                                <tr>
                                    <td colspan="12" class="text-center">No result(s) found.</td>
                                    <input type="text" id="emptyVal" class="form-control" name="empty" value="" required hidden/>
                                </tr>
                                @endforelse
                                <tr>
                                    <td colspan="12">
                                        <a href="{{ url()->previous() }}" class="btn btn-default"><i class="fas fa-arrow-left"></i> Back</a>
                                        <button id="submitBtn" type="submit" class="submitBtn btn btn-primary float-right"><i class="fas fa-check"></i> Update Attribute</button></td>
                                </tr>
                            </form>       
                        </tbody>
                    </table> 
                </div>
            </div>
        </div>
        @endif
    </div>
    <div class="container" style="background-color: rgba(0,0,0,0); height: 100px;"></div>
@endsection

@section('script')
{{-- <script>
    $(document).ready(function(e){
        $('#updateForm').submit(function(e){
            e.preventDefault();

            $('#preloader-modal').modal('show');

            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: $(this).serialize(),
                success: function(response){
                    if (response.status) {
                        $('#preloader-modal h6').html(response.message);
                        $('#preloader-modal button').removeClass('d-none');
                    }else{
                        $('#preloader-modal h6').html(response.message);
                        $('#preloader-modal button').removeClass('d-none');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $('#preloader-modal').modal('hide');
                    alert('An error occured.');
                }
            });
        });
    });
</script> --}}

@endsection