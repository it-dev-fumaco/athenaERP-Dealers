@if($is_stock_entry)
<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">Deliveries <small class="badge {{ ($data['status'] == 'For Checking') ? 'badge-warning' : 'badge-success'  }}">{{ $data['status'] }}</small></h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="box-header with-border">
                    <h5 class="box-title">
                        <span>{{ $data['s_warehouse'] }}</span>
                    </h5>
                </div>
                <input type="hidden" name="child_tbl_id" value="{{ $data['name'] }}">
                <input type="hidden" name="is_stock_entry" value="1">
                <input type="hidden" name="has_reservation" value="{{ ($data['stock_reservation']) ? 1 : 0 }}">
                <input type="hidden" name="deduct_reserve" value="0">
                <div class="box-body" style="font-size: 12pt;">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Barcode</label>
                            <input type="text" class="form-control" name="barcode" placeholder="Barcode" value="{{ $data['validate_item_code'] }}" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Qty</label>
                            <input type="text" class="form-control" name="qty" placeholder="Qty" value="{{ $data['qty'] }}" required>
                        </div>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-4 mt-3">
                                    @php
                                        $img = ($data['img']) ? "/img/" . $data['img'] : "/icon/no_img.png";
                                        $img_webp = ($data['img']) ? "/img/" . explode('.', $data['img'])[0].'.webp' : "/icon/no_img.webp";
                                    @endphp
                                    <a href="{{ asset('storage/') . '' . $img }}" data-toggle="lightbox" data-gallery="{{ $data['item_code'] }}" data-title="{{ $data['item_code'] }}">
                                        {{-- <img class="display-block img-thumbnail" src="{{ asset('storage/') }}{{ $img }}" style="width: 100%;" class="item_image"> --}}
                                        @if(!Storage::disk('public')->exists('/img/'.explode('.', $data['img'])[0].'.webp'))
                                            <img class="display-block img-thumbnail item_image w-100" src="{{ asset('storage/') }}{{ $img }}">
                                        @elseif(!Storage::disk('public')->exists('/img/'.$data['img']))
                                            <img class="display-block img-thumbnail item_image w-100" src="{{ asset('storage/') }}{{ $img_webp }}">
                                        @else
                                            <picture>
                                                <source srcset="{{ asset('storage'.$img_webp) }}" type="image/webp">
                                                <source srcset="{{ asset('storage'.$img) }}" type="image/jpeg">
                                                <img src="{{ asset('storage'.$img) }}" alt="{{ Illuminate\Support\Str::slug(explode('.', $img)[0], '-') }}" class="display-block img-thumbnail item_image w-100">
                                            </picture>
                                        @endif
                                    </a>
                                </div>
                                <div class="col-8 mt-3">
                                    <span class="d-block font-weight-bold">{{ $data['item_code'] }}</span>
                                    <small class="d-block text-justify">{{ $data['description'] }}</small>
                                    <dl>
                                        <dt>Available Qty</dt>
                                        <dd><span style="font-size: 12pt;" class="badge {{ ($data['available_qty'] > 0) ? 'badge-success' : 'badge-danger' }}">{{ $data['available_qty'] . ' ' . $data['stock_uom'] }}</span></dd>
                                        <dt class="mt-1">Reference No:</dt>
                                        <dd>{{ $data['ref_no'] }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                        @if($data['stock_reservation'])
                        <div class="col-md-12 mt-2 p-2">
                            <div class="callout callout-info p-1 m-0">
                                <h6 class="m-2 font-weight-bold blink-reservation text-info"><i class="icon fas fa-info-circle"></i> Reservation found on this item</h6>
                                <dl class="row p-0 m-0" id="sr-d">
                                    @if ($data['stock_reservation']->type == 'Consignment')
                                    <dt class="col-sm-4">Branch</dt>
                                    <dd class="col-sm-8">{{ $data['stock_reservation']->consignment_warehouse }}</dd>
                                    @else
                                    <dt class="col-sm-4">Sales Person</dt>
                                    <dd class="col-sm-8">{{ $data['stock_reservation']->sales_person }}</dd>
                                    <dt class="col-sm-4">Project</dt>
                                    <dd class="col-sm-8">{{ $data['stock_reservation']->project }}</dd>
                                    @endif
                                    <dt class="col-sm-4">Reserved Qty</dt>
                                    <dd class="col-sm-8">{{ $data['stock_reservation']->reserve_qty - $data['stock_reservation']->consumed_qty }} {{ $data['stock_reservation']->stock_uom }}</dd>
                                </dl>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="deduct_reserve" value="0">
    <div class="modal-footer">
        @if($data['stock_reservation'])
        <button type="button" class="btn btn-warning" id="btn-deduct-res"><i class="fa fa-check"></i> DEDUCT FROM RESERVED</button>
        @else
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> CLOSE</button>
        @endif
        <button type="button" class="btn btn-primary btn-lg" id="btn-check-out"><i class="fa fa-check"></i> CHECK OUT</button>
    </div>
</div>
@endif

@if(!$is_stock_entry)
    <div class="modal-content">
        <div class="modal-header">
              <h5 class="modal-title">Deliveries <small class="badge {{ ($data['status'] == 'For Checking') ? 'badge-warning' : 'badge-success'  }}">{{ $data['status'] }}</small></h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="box-header with-border">
                        <h5 class="box-title">{{ $data['warehouse'] }}</h5>
                    </div>
                    <input type="hidden" name="child_tbl_id" value="{{ $data['id'] }}">
                    <input type="hidden" name="is_stock_entry" value="1">
                    <input type="hidden" name="has_reservation" value="{{ ($data['stock_reservation']) ? 1 : 0 }}">
                    <input type="hidden" name="deduct_reserve" value="0">
                    <input type="hidden" name="is_bundle" value="{{ ($data['is_bundle'] === false) ? 0 : 1 }}">
                    <input type="hidden" name="warehouse" value="{{ $data['warehouse'] }}">
                    <input type="hidden" name="dri_name" value="{{ $data['dri_name'] }}">
                    <input type="hidden" name="sales_order" value="{{ $data['sales_order'] }}">
                    <div class="box-body" style="font-size: 12pt;">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Barcode</label>
                                <input type="text" class="form-control" name="barcode" placeholder="Barcode" value="{{ $data['barcode'] }}" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Qty</label>
                                <input type="text" class="form-control" name="qty" placeholder="Qty" value="{{ $data['qty'] }}" required>
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-4 mt-2">
                                        @php
                                            $img = ($data['item_image']) ? "/img/" . explode('.', $data['item_image'])[0].'.webp' : "/icon/no_img.webp";
                                        @endphp
                                        <a href="{{ asset('storage/') . '' . $img }}" data-toggle="lightbox" data-gallery="{{ $data['item_code'] }}" data-title="{{ $data['item_code'] }}">
                                            <img class="display-block img-thumbnail" src="{{ asset('storage/') . '' . $img }}" style="width: 100%;" class="item_image">
                                        </a>
                                    </div>
                                    <div class="col-8 mt-2">
                                        <span class="item_code_txt font-weight-bold"></span> 
                                        <p class="description"></p>
                                        <span class="font-weight-bold">{{ $data['item_code'] }}</span> <span class="badge badge-info {{ ($data['is_bundle'] === false) ? 'd-none' : '' }}" style="font-size: 11pt;">Product Bundle</span>
                                        <small class="d-block text-justify">{{ $data['description'] }}</small>
                                        <dl>
                                            <dt>UoM</dt>
                                            <dd>{{ $data['uom'] }}</dd>
                                            <dt>Available Qty</dt>
                                            <dd><span style="font-size: 12pt;" class="badge {{ ($data['available_qty'] > 0) ? 'badge-success' : 'badge-danger' }}">{{ $data['available_qty'] . ' ' . $data['stock_uom'] }}</span></dd>
                                            <dt class="mt-1">Reference No:</dt>
                                            <dd> 
                                                <span class="d-block">{{ $data['delivery_note'] }}</span>
                                                <span class="d-block">{{ $data['sales_order'] }}</span>
                                            </dd>
                                        </dl>
                                    </div>
                                    @if (count($data['uom_conversion']) > 1)
                                    <div class="col-md-12 text-center">
                                        <span class="font-weight-bold d-blo1ck">UoM Conversion:</span>
                                        {{ number_format($data['uom_conversion'][0]->conversion_factor) . ' ' . $data['uom_conversion'][1]->uom .' = ' . number_format($data['uom_conversion'][1]->conversion_factor) . ' ' . $data['uom_conversion'][0]->uom }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @if($data['stock_reservation'])
                            <div class="col-md-12 mt-2 p-2">
                                <div class="callout callout-info p-1 m-0">
                                    <h6 class="m-2 font-weight-bold blink-reservation text-info"><i class="icon fas fa-info-circle"></i> Reservation found on this item</h6>
                                    <dl class="row p-0 m-0" id="sr-d">
                                        @if ($data['stock_reservation']->type == 'Consignment')
                                        <dt class="col-sm-4">Branch</dt>
                                        <dd class="col-sm-8">{{ $data['stock_reservation']->consignment_warehouse }}</dd>
                                        @elseif ($data['stock_reservation']->type == 'Website Stocks')
                                        <dt class="col-sm-4">Reference No.</dt>
                                        <dd class="col-sm-8">{{ $data['stock_reservation']->reference_no }}</dd>
                                        @else
                                        <dt class="col-sm-4">Sales Person</dt>
                                        <dd class="col-sm-8">{{ $data['stock_reservation']->sales_person }}</dd>
                                        <dt class="col-sm-4">Project</dt>
                                        <dd class="col-sm-8">{{ $data['stock_reservation']->project }}</dd>
                                        @endif
                                        <dt class="col-sm-4">Reserved Qty</dt>
                                        @if ($data['is_bundle'])
                                        <dd class="col-sm-8">{{ $data['qty'] }} {{ $data['stock_uom'] }}</dd>
                                        @else
                                        <dd class="col-sm-8">{{ $data['stock_reservation']->reserve_qty - $data['stock_reservation']->consumed_qty }} {{ $data['stock_reservation']->stock_uom }}</dd>
                                        @endif
                                    </dl>
                                </div>
                            </div>
                            @endif
                    
                            <div class="col-md-12 mt-2 {{ ($data['is_bundle'] === false) ? 'd-none' : '' }}">
                                <h6 class="text-center font-weight-bold text-uppercase">Product Bundle Item(s)</h6>
                                <table class="table table-sm table-bordered" style="font-size: 0.8rem;">
                                    <col style="width: 50%;">
                                    <col style="width: 20%;">
                                    <col style="width: 30%;">
                                    <thead>
                                        <th class="text-center">Item Description</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-center">Available Qty</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($data['product_bundle_items'] as $row)
                                        <tr>
                                            <td class="text-justify align-middle">
                                                <span class="font-weight-bold">{{ $row['item_code'] }}</span> <small>{{ $row['description'] }}</small></td>
                                            <td class="text-center align-middle">
                                                <span class="d-block font-weight-bold">{{ $row['qty'] }}</span>
                                                <small>{{ $row['uom'] }}</small>
                                            </td>
                                            <td class="text-center align-middle">
                                                <span class="badge {{ ($row['available_qty'] > 0) ? 'badge-success' : 'badge-danger' }}"  style="font-size: 0.7rem;">{{ $row['available_qty'] . ' ' . $row['uom'] }}</span>
                                                <span class="d-block" style="font-size: 9pt;">{{ $row['warehouse'] }}</span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="deduct_reserve" value="0">
        <div class="modal-footer">
            @if($data['stock_reservation'])
            <button type="button" class="btn btn-warning" id="btn-deduct-res-1"><i class="fa fa-check"></i> DEDUCT FROM RESERVED</button>
            @else
            <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> CLOSE</button>
            @endif
            <button type="button" class="btn btn-primary btn-lg" id="btn-check-out-1"><i class="fa fa-check"></i> CHECK OUT</button>
        </div>
    </div>
@endif