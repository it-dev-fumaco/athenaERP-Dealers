@extends('layout', [
    'namePage' => 'Inventory Summary',
    'activePage' => 'beginning_inventory',
])

@section('content')
<div class="content">
	<div class="content-header p-0">
        <div class="container">
            <div class="row pt-1">
                <div class="col-md-12 p-0 m-0">
                    <div class="card card-lightblue">
                        <div class="card-header text-center p-2">
                            @if (count($assigned_consignment_stores) > 1)
                                <select id="warehouse" class="form-control">
                                    @foreach ($assigned_consignment_stores as $store)
                                        <option value="{{ $store }}" {{ $store == $branch ? 'selected' : null }}>{{ $store }}</option>
                                    @endforeach
                                </select>
                            @else
                                <span class="font-weight-bolder d-block text-uppercase" style="font-size: 11pt;">
                                    {{ $branch }}
                                </span>
                            @endif
                        </div>
                        <div class="card-body p-3">
                            <div class="col-12">
                                <input type="text" class="form-control mb-2" id="item-search" name="search" placeholder="Search" style="font-size: 9pt"/>
                            </div>
                            <table class="table table-striped" id='items-table' style="font-size: 10pt;">
                                <thead class="border-top">
                                    <tr>
                                        <th class="text-center align-middle p-1" style="width: 55%">Item Description</th>
                                        <th class="text-center align-middle p-1">Available Qty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($inv_summary as $item)
                                        @php
                                            $orig_exists = 0;
                                            $webp_exists = 0;

                                            $img = '/icon/no_img.png';
                                            $webp = '/icon/no_img.webp';

                                            if(isset($item_image[$item->item_code])){
                                                $orig_exists = Storage::disk('public')->exists('/img/'.$item_image[$item->item_code][0]->image_path) ? 1 : 0;
                                                $webp_exists = Storage::disk('public')->exists('/img/'.explode('.', $item_image[$item->item_code][0]->image_path)[0].'.webp') ? 1 : 0;

                                                $webp = $webp_exists == 1 ? '/img/'.explode('.', $item_image[$item->item_code][0]->image_path)[0].'.webp' : null;
                                                $img = $orig_exists == 1 ? '/img/'.$item_image[$item->item_code][0]->image_path : null;

                                                if($orig_exists == 0 && $webp_exists == 0){
                                                    $img = '/icon/no_img.png';
                                                    $webp = '/icon/no_img.webp';
                                                }
                                            }
                                        @endphp
                                        <tr>
                                            <td class='p-1' colspan=2>
                                                <div class="row">
                                                    <div class="col-2">
                                                        <a href="{{ asset('storage/').$img }}" data-toggle="mobile-lightbox" data-gallery="{{ $item->item_code }}" data-title="{{ $item->item_code }}">
                                                            <picture>
                                                                <source srcset="{{ asset('storage'.$webp) }}" type="image/webp">
                                                                <source srcset="{{ asset('storage'.$img) }}" type="image/jpeg">
                                                                <img src="{{ asset('storage'.$img) }}" alt="{{ str_slug(explode('.', $img)[0], '-') }}" class="w-100">
                                                            </picture>
                                                        </a>
                                                    </div>
                                                    <div class="col-3" style="display: flex; justify-content: center; align-items: center;">
                                                        <b>{{ $item->item_code }}</b>
                                                    </div>
                                                    <div class="col-4 offset-2" style="display: flex; justify-content: center; align-items: center;">
                                                        <div class="text-center">
                                                            <b>{{ number_format($item->consigned_qty) }}</b><br>
                                                            <small>{{ $item->stock_uom }}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row p-2 text-justify">
                                                    <div class="item-description" style="font-size: 10pt;">{!! strip_tags($item->description) !!}</div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan=2 class="text-center">
                                                No item(s) found. 
                                            </td>
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

@section('style')
    <style>
        table {
            table-layout: fixed;
            width: 100%;   
        }
        .morectnt span {
            display: none;
        }
    </style>
@endsection

@section('script')
    <script>
        var showTotalChar = 85, showChar = "Show more", hideChar = "Show less";
        $('.item-description').each(function() {
            var content = $(this).text();
            if (content.length > showTotalChar) {
                var con = content.substr(0, showTotalChar);
                var hcon = content.substr(showTotalChar, content.length - showTotalChar);
                var txt = con + '<span class="dots">...</span><span class="morectnt"><span>' + hcon + '</span>&nbsp;&nbsp;<a href="#" class="showmoretxt">' + showChar + '</a></span>';
                $(this).html(txt);
            }
        });

        $('#warehouse').change(function(){
            window.location.href = "/inventory_items/" + $(this).val();
        });

        $(".showmoretxt").click(function(e) {
            e.preventDefault();
            if ($(this).hasClass("sample")) {
                $(this).removeClass("sample");
                $(this).text(showChar);
            } else {
                $(this).addClass("sample");
                $(this).text(hideChar);
            }

            $(this).parent().prev().toggle();
            $(this).prev().toggle();
            return false;
        });

        $("#item-search").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#items-table tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    </script>
@endsection