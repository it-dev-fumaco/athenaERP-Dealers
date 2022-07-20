<ul class="products-list product-list-in-card pl-2 pr-2">
    @forelse ($list as $item)
    <li class="item">
        <div class="product-img">
            @php
                $img = ($item['image']) ? "/img/" . $item['image'] : "/icon/no_img.png";
                $img_webp = ($item['image']) ? "/img/" . explode('.', $item['image'])[0] : "/icon/no_img.webp";
            @endphp
            <a href="{{ asset('storage/') }}{{ $img }}" data-toggle="lightbox" data-gallery="{{ $item['item_code'] }}" data-title="{{ $item['item_code'] }}">
                {{-- <img src="{{ asset('storage/') }}{{ $img }}" class="img-size-50"> --}}
                @if(!Storage::disk('public')->exists('/img/'.explode('.', $item['image'])[0].'.webp'))
                    <img src="{{ asset('storage/').$img }}" class="img-size-50">
                @elseif(!Storage::disk('public')->exists('/img/'.$item['image']))
                    <img src="{{ asset('storage/').$img_webp }}" class="img-size-50">
                @else
                    <picture>
                        <source srcset="{{ asset('storage'.$img_webp) }}" type="image/webp" class="img-size-50">
                        <source srcset="{{ asset('storage'.$img) }}" type="image/jpeg" class="img-size-50">
                        <img src="{{ asset('storage'.$img) }}" alt="{{ Illuminate\Support\Str::slug(explode('.', $img)[0], '-') }}" class="img-size-50">
                    </picture>
                @endif
            </a>
        </div>
        <div class="product-info">
            <div class="col-md-8 float-left bg-white" style="display: inline-block">
                {{-- <span class="font-weight-bold product-title">{{ $item['item_code'] }}</span> --}}
                <a href="#" class="view-item-details" data-item-code="{{ $item['item_code'] }}" data-item-classification="{{ $item['item_classification'] }}">
                    <span class="d-block font-weight-bold text-dark item-code">{{ $item['item_code'] }}</span>
                </a>
                <small class="d-block font-italic">{{ str_limit($item['description'], $limit = 25, $end = '...') }}</small>
            </div>

            <div class="col-md-4 float-right text-center bg-white" style="display: inline-block">
                <span class="font-italic font-weight-bold text-right">
                    <small><b>{{ $item['qty'] }}</b></small> <span style="font-size: 10px;">{{ $item['stock_uom'] }}</span>
                </span>
                <span class="font-italic" style="font-size: 10px;"><br/>{{ $item['warehouse'] }}</span>
            </div>
        </div>
    </li>
    @empty
    <li class="item">
        <h5 class="text-center">No Record(s) found.</h5>
    </li>
    @endforelse 
</ul>

<div class="col-md-10 clearfix" id="reserved-items-pagination" style="font-size: 12pt;">
    {{ $list->links() }}
</div>