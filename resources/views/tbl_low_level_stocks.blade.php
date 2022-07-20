<div class="float-right mr-3 mt-1 mb-1 low-lvl-stock-total">
    Total: <span class="badge badge-info">{{ $low_level_stocks->total() }}</span>
</div>
<table class="table table-bordered table-hover m-0">
    <col class="low-lvl-stk-tbl-item-desc"><!-- Item Description -->
    <col style="width: 15%;"><!-- Warehouse -->
    <col style="width: 11%;"><!-- Re-order Qty -->
    <col style="width: 11%;"><!-- Min. Stock Qty -->
    <col style="width: 10%;"><!-- Actual Qty -->
    <col style="width: 10%;"><!-- Action -->
    <thead style="font-size: 0.82rem;">
        <th class="text-center align-middle">Item Description</th>
        <th class="text-center align-middle d-none d-lg-table-cell">Warehouse</th>
        <th class="text-center align-middle d-none d-lg-table-cell p-1">Re-order Qty</th>
        <th class="text-center align-middle d-none d-lg-table-cell p-1">Min. Stock Qty</th>
        <th class="text-center align-middle d-none d-lg-table-cell">Actual Qty</th>
        <th class="text-center align-middle d-none d-lg-table-cell">Action</th>
    </thead>
    <tbody>
        @forelse ($low_level_stocks as $n => $row)
        <tr>
            <td class="text-justify p-2 align-middle font-responsive">
                @php
                    $img = ($row['image']) ? "/img/" . $row['image'] : "/icon/no_img.png";
                    $img_webp = ($row['image']) ? "/img/" . explode('.',$row['image'])[0].'.webp' : "/icon/no_img.webp";
                @endphp
                <div class="row">
                    <div class="col-2">
                        <a href="{{ asset('storage/') . $img }}" data-toggle="lightbox" data-gallery="{{ $row['item_code'] }}" data-title="{{ $row['item_code'] }}">
                            {{-- <img src="{{ asset('storage/').$img }}" class="img w-100"> --}}
                            @if(!Storage::disk('public')->exists('/img/'.explode('.', $row['image'])[0].'.webp'))
                                <img src="{{ asset('storage/').$img }}" class="img w-100">
                            @elseif(!Storage::disk('public')->exists('/img/'.$row['image']))
                                <img src="{{ asset('storage/').$img_webp }}" class="img w-100">
                            @else
                                <picture>
                                    <source srcset="{{ asset('storage'.$img_webp) }}" type="image/webp" class="img w-100">
                                    <source srcset="{{ asset('storage'.$img) }}" type="image/jpeg" class="img w-100">
                                    <img src="{{ asset('storage'.$img) }}" alt="{{ Illuminate\Support\Str::slug(explode('.', $img)[0], '-') }}" class="img w-100">
                                </picture>
                            @endif
                        </a>
                    </div>
                    <div class="col-10">
                        <a href="#" class="view-item-details" data-item-code="{{ $row['item_code'] }}" data-item-classification="{{ $row['item_classification'] }}">
                            <span class="d-block1 font-weight-bold text-dark item-code">{{ $row['item_code'] }}</span>
                            <span class="d-none item-description">{{ $row['description'] }}</span>
                        </a>
                        <small class="font-italic">{!! str_limit($row['description'], $limit = 50, $end = '...') !!}</small>
                        <div class="d-block d-lg-none">
                            <b>Warehouse: </b><small class="warehouse">{{ $row['warehouse'] }}</small>
                        </div>
                    </div>
                </div>
                {{-- For Mobile --}}
                <div class="d-block d-lg-none w-100">
                    <table class="table" style="width: 100% !important">
                        <tr>
                            <th class="text-center p-1" colspan=3>Quantity</th>
                        </tr>
                        <tr>
                            <th class="p-1">Re-order</th>
                            <th class="p-1">Min. Stock</th>
                            <th class="p-1">Actual</th>
                        </tr>
                        <tr>
                            <td class="p-1">
                                {{ number_format($row['warehouse_reorder_qty'] * 1) }} {{ $row['stock_uom'] }}
                            </td>
                            <td class="p-1">
                                <strong>{{ number_format($row['warehouse_reorder_level'] * 1) }}</strong> {{ $row['stock_uom'] }}
                            </td>
                            <td class="p-1">
                                <span class="badge badge-{{ ($row['actual_qty'] > $row['warehouse_reorder_level']) ? 'success' : 'danger' }} low-lvl-stock-badge">{{ number_format($row['actual_qty'] * 1) }} {{ $row['stock_uom'] }}</span>
                            </td>
                        </tr>
                    </table>
                </div>
                {{-- For Mobile --}}
            </td>
            <td class="text-center p-1 align-middle d-none d-lg-table-cell">
                <small class="warehouse">{{ $row['warehouse'] }}</small>
            </td>
            <td class="text-center p-1 align-middle d-none d-lg-table-cell reorder-qty" style="font-size:12px">{{ number_format($row['warehouse_reorder_qty'] * 1) }} <small>{{ $row['stock_uom'] }}</small></td>
            <td class="text-center p-1 align-middle d-none d-lg-table-cell"><strong>{{ number_format($row['warehouse_reorder_level'] * 1) }} <small>{{ $row['stock_uom'] }}</small></strong></td>
            <td class="text-center p-1 align-middle d-none d-lg-table-cell">
                <span class="badge badge-{{ ($row['actual_qty'] > $row['warehouse_reorder_level']) ? 'success' : 'danger' }}" style="font-size: 11pt;">{{ number_format($row['actual_qty'] * 1) }} <small>{{ $row['stock_uom'] }}</small></span>
            </td>
            <td class="text-center p-1 align-middle d-none d-lg-table-cell">
                @if(!$row['existing_mr'])
                <button class="btn btn-primary btn-sm create-mr-btn" data-id="{{ $row['id'] }}">
                    <span class="d-none d-md-inline">Create</span><span class="d-inline d-md-none">+</span> MR
                </button>
                @else
                <button class="btn btn-success btn-sm" disabled><i class="fas fa-check"></i> MR</button>
                <small class="d-block mt-1">{{ $row['existing_mr'] }}</small>
                @endif
            </td>
        </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center font-weight-bold">No Record(s) found.</td>
            </tr>
        @endforelse
    </tbody>
</table>
<div class="card-footer clearfix" id="low-level-stocks-pagination" style="font-size: 12pt;">
	{{ $low_level_stocks->links() }}
</div>