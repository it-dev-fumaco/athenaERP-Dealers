@extends('layout', [
    'namePage' => 'Inventory Audit Item(s)',
    'activePage' => 'dashboard',
])

@section('content')
<div class="content">
	<div class="content-header p-0">
        <div class="container">
            <div class="row pt-1">
                <div class="col-md-12 p-0 m-0">
                    <div class="card card-lightblue">
                        <div class="card-header text-center p-1">
                            <div class="d-flex flex-row align-items-center">
                                <div class="p-0 col-2 text-left">
                                    <a href="/inventory_audit" class="btn btn-secondary m-0" style="width: 60px;"><i class="fas fa-arrow-left"></i></a>
                                </div>
                                <div class="p-1 col-8">
                                    <span class="font-weight-bolder d-block font-responsive text-uppercase">Inventory Audit Item(s)</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-1">
                            <h5 class="font-responsive font-weight-bold text-center m-1 text-uppercase d-block">{{ $store }}</h5>
                            <h6 class="text-center mt-2 font-weight-bolder font-responsive">{{ $duration }}</h6>

                            <span class="d-block text-center font-responsive m-1">Total Sales: <b>{{ '₱ ' . number_format($total_sales, 2) }}</b></span>
                            <table class="table" style="font-size: 8pt;">
                                <thead class="border-top">
                                    <th class="text-center align-middle p-1" style="width: 33%;">ITEM CODE</th>
                                    <th class="text-center align-middle p-1" style="width: 26%;">OPENING QTY</th>
                                    <th class="text-center align-middle p-1" style="width: 20%;">SOLD</th>
                                    <th class="text-center align-middle p-1" style="width: 21%;">AUDIT QTY</th>
                                </thead>
                                <tbody>
                                    @forelse ($result as $row)
                                    <tr style="border-bottom: 0 !important;">
                                        <td class="text-justify p-1 align-middle" style="border-bottom: 0 !important;">
                                            <div class="d-flex flex-row justify-content-start align-items-center">
                                                <div class="p-0 text-left">
                                                    <a href="{{ asset('storage/') }}{{ $row['img'] }}" data-toggle="mobile-lightbox" data-gallery="{{ $row['item_code'] }}" data-title="{{ $row['item_code'] }}">
                                                        <picture>
                                                            <source srcset="{{ asset('storage'.$row['img_webp']) }}" type="image/webp" alt="{{ str_slug(explode('.', $row['img'])[0], '-') }}" width="40" height="40">
                                                            <source srcset="{{ asset('storage'.$row['img']) }}" type="image/jpeg" alt="{{ str_slug(explode('.', $row['img'])[0], '-') }}" width="40" height="40">
                                                            <img src="{{ asset('storage'.$row['img']) }}" alt="{{ str_slug(explode('.', $row['img'])[0], '-') }}" width="40" height="40">
                                                        </picture>
                                                    </a>
                                                </div>
                                                <div class="p-1 m-0">
                                                    <span class="font-weight-bold">{{ $row['item_code'] }}</span>
                                                </div>
                                            </div>
                                            <div class="modal fade" id="mobile-{{ $row['item_code'] }}-images-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">{{ $row['item_code'] }}</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form></form>
                                                            <div id="image-container" class="container-fluid">
                                                                <div id="carouselExampleControls" class="carousel slide" data-interval="false">
                                                                    <div class="carousel-inner">
                                                                        <div class="carousel-item active">
                                                                            <picture>
                                                                                <source id="mobile-{{ $row['item_code'] }}-webp-image-src" srcset="{{ asset('storage/').$row['img_webp'] }}" type="image/webp" class="d-block w-100" style="width: 100% !important;">
                                                                                <source id="mobile-{{ $row['item_code'] }}-orig-image-src" srcset="{{ asset('storage/').$row['img'] }}" type="image/jpeg" class="d-block w-100" style="width: 100% !important;">
                                                                                <img class="d-block w-100" id="mobile-{{ $row['item_code'] }}-image" src="{{ asset('storage/').$row['img'] }}" alt="{{ Illuminate\Support\Str::slug(explode('.', $row['img'])[0], '-') }}">
                                                                            </picture>
                                                                        </div>
                                                                        <span class='d-none5' id="mobile-{{ $row['item_code'] }}-image-data">0</span>
                                                                    </div>
                                                                    @if ($row['img_count'] > 1)
                                                                    <a class="carousel-control-prev" href="#carouselExampleControls" onclick="prevImg('{{ $row['item_code'] }}')" role="button" data-slide="prev" style="color: #000 !important">
                                                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                                        <span class="sr-only">Previous</span>
                                                                    </a>
                                                                    <a class="carousel-control-next" href="#carouselExampleControls" onclick="nextImg('{{ $row['item_code'] }}')" role="button" data-slide="next" style="color: #000 !important">
                                                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                                        <span class="sr-only">Next</span>
                                                                    </a>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center p-1 align-middle font-weight-bold" style="border-bottom: 0 !important;">
                                            <span class="d-block">{{ $row['opening_qty'] }}</span>
                                        </td>
                                        <td class="text-center p-1 align-middle font-weight-bold" style="border-bottom: 0 !important;">
                                            <span class="d-block">{{ $row['sold_qty'] }}</span>
                                        </td>
                                        <td class="text-center p-1 align-middle font-weight-bold" style="border-bottom: 0 !important;">
                                            <span class="d-block">{{ $row['audit_qty'] }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" style="border-top: 0 !important;" class="pt-0 pb-2 pl-2 prl-2"><div class="item-description">{!! strip_tags($row['description']) !!}</div></td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td class="text-center font-weight-bold text-uppercase text-muted" colspan="4">No item(s) found</td>
                                    </tr> 
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="m-2">
                                <span class="d-block font-responsive">Total: <b>{{ count($list) }}</b></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	</div>
</div>

<style>
    .morectnt span {
        display: none;
    }
</style>
@endsection

@section('script')
<script>
    $(function () {
        var showTotalChar = 98, showChar = "Show more", hideChar = "Show less";
        $('.item-description').each(function() {
            var content = $(this).text();
            if (content.length > showTotalChar) {
                var con = content.substr(0, showTotalChar);
                var hcon = content.substr(showTotalChar, content.length - showTotalChar);
                var txt = con + '<span class="dots">...</span><span class="morectnt"><span>' + hcon + '</span>&nbsp;&nbsp;<a href="#" class="showmoretxt">' + showChar + '</a></span>';
                $(this).html(txt);
            }
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
    });
</script>
@endsection