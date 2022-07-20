@extends('item_attributes_updating.item_attrib_layout', [
    'namePage' => 'ERPInventory',
    'activePage' => 'dashboard',
])

@section('content')
    <div class="container-fluid align-center">
        <div class="row">
            <div class="col-md-4 offset-md-4">
                <form action="/viewParentItemDetails" class="form-inline mb-2" method="GET">
                    <div class="form-group" style="margin: 0 auto !important;">   
                        <label>Parent Item Code </label>
                        <input type="text" class="form-control m-2" name="item_code" value="{{ request('item_code') }}" required/>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
                    </div>
                </form>
            </div>
            @if (request('item_code'))
                @if($itemDetails && $itemDetails->has_variants == 0)
                <div class="col-md-4 offset-md-4">
                    <div class="alert alert-warning">
                        <h5 class="text-center"><i class="fas fa-exclamation-circle"></i> Item <b>{{ request('item_code') }}</b> is not a parent/template item.</h5>
                    </div>
                </div>
                @elseif($itemDetails)
                <div class="col-md-4 offset-md-4 align-middle">
                    <h5 class="text-left m-1 pt-2">Template Item: <span class="font-weight-bold">{{ $itemDetails->name }}</span> <small>{{ $itemDetails->description }} <a href="#" class="ml-2" data-toggle="modal" data-target="#modal-default"><i class="fas fa-edit"></i> Edit</a></small></h5>
                    
                </div>

                <div class="modal fade" id="modal-default">
                    <form action="/updateParentItem/{{ $itemDetails->name }}" autocomplete="off" method="post">
                        @csrf
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                <h4 class="modal-title">Change Template Item Name</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="">Item Code</label>
                                        <input type="text" class="form-control" value="{{ $itemDetails->name }}" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Item Name</label>
                                        <input type="text" class="form-control" name="item_name" value="{{ $itemDetails->item_name }}" placeholder="Enter Item Name">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Description</label>
                                        <textarea name="description" class="form-control" placeholder="Enter Item Description">{{ $itemDetails->description }}</textarea>
                                    </div>
                                    <div class="alert alert-info text-center p-3">
                                        <small><i class="fas fa-info-circle"></i> <b>Note:</b> Item description of the variants of this item will also be updated.</small>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </div>
                            </div>
                        <!-- /.modal-content -->
                        </div>
                    </form>
                    <!-- /.modal-dialog -->
                </div>
                <!-- /.modal -->

                <div class="col-md-4 offset-md-4">
                    <div class="card card-secondary card-outline mt-2">
                        <div class="card-header">
                            <h5 class="card-title m-0">Item Attribute(s) <span class="badge badge-info">{{ count($attributes) }}</span></h5>
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
                            @if (\Session::has('message'))
                            <div class="alert {{ (\Session::get('status') == 1) ? 'alert-success' : 'alert-danger' }} alert-dismissible text-center">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <i class="fas fa-check"></i> {!! \Session::get('message') !!}
                            </div>
                            @endif
                            <table class="table table-bordered table-hover m-0">
                                <col style="width: 10%;">
                                <col style="width: 65%;">
                                <col style="width: 25%;">
                                <thead>
                                    <tr>
                                        <th class="text-center">No.</th>
                                        <th class="text-center">Attribute Name</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($attributes as $attr)
                                        <tr>
                                            <td class="text-center align-middle">{{ $attr->idx }}</td>
                                            <td class="text-justify align-middle">{{ $attr->attribute }}</td>
                                            <td class="text-center align-middle">
                                                <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modal{{ $attr->name }}"><i class="fas fa-trash"></i> Delete</button>
                                            </td>
                                        </tr>
                                        <div class="modal fade" id="modal{{ $attr->name }}">
                                            <form action="/deleteItemAttribute/{{ $itemDetails->name }}" method="POST" autocomplete="off">
                                                @csrf
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Delete Attribute</h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <input type="hidden" name="attribute" value="{{ $attr->attribute }}">
                                                            <p class="text-center">Attribute <span class="font-weight-bold">{{ $attr->attribute }}</span> will be deleted to all variants of <span class="font-weight-bold">{{ $attr->parent }}</span>.</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary">Continue</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    @empty
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="text-center m-2">
                        <a href="{{ url()->previous() }}" class="btn btn-default"><i class="fas fa-arrow-left"></i> Back</a>
                    </div>
                </div>
                @else
                <div class="col-md-4 offset-md-4">
                    <div class="alert alert-warning">
                        <h5 class="text-center"><i class="fas fa-exclamation-circle"></i> Item <b>{{ request('item_code') }}</b> not found.</h5>
                    </div>
                </div>
                @endif
            @endif
        </div>
    </div>
@endsection
@section('script')

@endsection