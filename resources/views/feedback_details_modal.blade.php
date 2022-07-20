
<div class="modal-content">@csrf
    <div class="modal-header">
        <h5 class="modal-title">Feedback <small class="badge badge-warning">To Receive</small></h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="box-header with-border">
                    <span style="font-size: 12pt;" class="box-title">
                        <span>{{ $q['src_warehouse'] }} <i class="fas fa-angle-double-right mr-2 ml-2"></i> {{ $q['fg_warehouse'] }}</span>
                        <input type="text" name="src_wh" value="{{ $q['src_warehouse'] }}" hidden/>
                        <input type="text" name="to_wh" value="{{ $q['fg_warehouse'] }}" hidden/>
                    </span>
                </div>
                <input type="hidden" name="production_order" value="{{ $q['production_order'] }}">
                <div class="box-body" style="font-size: 12pt;">
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label>Barcode</label>
                            <input type="text" class="form-control col-md-12" name="barcode" placeholder="Barcode" value="" required>
                        </div>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-4 mt-3">
                                    @php
                                        $img = ($q['img']) ? "/img/" . $q['img'] : "/icon/no_img.png";
                                        $img_webp = ($q['img']) ? "/img/" . explode('.', $q['img'])[0].'.webp' : "/icon/no_img.webp";
                                    @endphp
                                    <a href="{{ asset('storage/') . '' . $img }}" data-toggle="lightbox" data-gallery="{{ $q['item_code'] }}" data-title="{{ $q['item_code'] }}">
                                        {{-- <img class="display-block img-thumbnail" src="{{ asset('storage/') }}{{ $img }}" style="width: 100%;" class="item_image"> --}}
                                        @if(!Storage::disk('public')->exists('/img/'.explode('.', $q['img'])[0].'.webp'))
                                            <img class="display-block img-thumbnail item_image w-100" src="{{ asset('storage/') }}{{ $img }}">
                                        @elseif(!Storage::disk('public')->exists('/img/'.$q['img']))
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
                                    <span class="d-block font-weight-bold">{{ $q['item_code'] }}</span>
                                    <input type="text" name="itemCode" value="{{ $q['item_code'] }}" hidden/>
                                    <small class="d-block text-justify">{{ $q['description'] }}</small>
                                    <input type="text" name="itemDesc" value="{{ $q['description'] }}" hidden/>
                                    <div class="row mt-2">
                                        <div class="col-6">
                                            <dl>
                                                <dt>Qty to Receive</dt>
                                                <dd class="badge {{ ($q['qty_to_receive'] > 0) ? 'badge-success' : 'badge-danger' }}" style="font-size: 12pt;">{{ $q['qty_to_receive'] }} {{ $q['stock_uom'] }}</dd>
                                                <input type="text" name="f_qty" value="{{ $q['qty_to_receive'] }}" hidden />
                                            </dl>
                                        </div>
                                        <div class="col-6">
                                            <dl>
                                                <dt class="mt-1">Reference No:</dt>
                                                <dd>{{ $q['sales_order'] }}{{ $q['material_request'] }}</dd>
                                                <input type="text" name="ref_number" value="{{ $q['sales_order'] }}{{ $q['material_request'] }}" hidden/>
                                            </dl>
                                        </div>
                                    </div>
                                    <label>Actual Quantity Received</label>
                                    <input type="number" class="form-control" name="fg_completed_qty" placeholder="Quantity" max="{{ $q['qty_to_receive'] }}" required>
                                    <input type="number" class="form-control" name="ofeedback_qty" value="{{ $q['feedback_qty'] }}" required hidden>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> CLOSE</button>
        <button type="submit" class="btn btn-primary btn-lg"><i class="fa fa-check"></i> Submit</button>
    </div>
</div>