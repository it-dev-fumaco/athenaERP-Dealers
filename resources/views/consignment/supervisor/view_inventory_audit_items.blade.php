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
                    <div class="row">
                        <div class="col-2">
                            <div style="margin-bottom: -43px;">
                                <a href="/inventory_audit" class="btn btn-secondary" style="width: 80px;"><i class="fas fa-arrow-left"></i></a>
                            </div>
                        </div>
                        <div class="col-10 col-lg-8 p-0">
                            <h4 class="text-center font-weight-bold m-2 text-uppercase">Inventory Audit Item(s)</h4>
                        </div>
                    </div>
                    <div class="card card-secondary card-outline">
                        <div class="card-header text-center">
                            <span class="font-weight-bolder d-block font-responsive">{{ $store }}</span>
                        </div>
                        <div class="card-body p-3">
                            <h5 class="text-center mt-2 font-weight-bolder font-responsive">{{ $duration }}</h5>
                            <div class="d-flex flex-row align-items-end">
                                <div class="p-0 col-4 text-left">
                                    <p class="m-1 font-details">Promodiser(s): <span class="font-weight-bold">{{ $promodisers }}</span></p>
                                </div>
                                <div class="p-1 col-4 text-center">
                                    <p class="m-1 font-details">Total Qty Sold: <span class="font-weight-bold">{{ collect($result)->sum('sold_qty') }}</span></p>
                                </div>
                                <div class="p-1 col-4 text-right">
                                    <p class="m-1 font-details">Total Sales: <span class="font-weight-bold">{{ '₱ ' . number_format(collect($result)->sum('total_value'), 2) }}</span></p>
                                </div>
                            </div>
                            <table class="table table-bordered table-striped" style="font-size: 10pt;">
                                <thead class="border-top">
                                    <th class="text-center font-responsive p-2 align-middle first" style="width: 55%;">Item Code</th>
                                    <th class="text-center font-responsive p-2 align-middle" style="width: 15%;">Opening Stock</th>
                                    <th class="text-center font-responsive p-2 align-middle" style="width: 15%;">Sold Qty</th>
                                    <th class="text-center font-responsive p-2 align-middle" style="width: 15%;">Audit Qty</th>
                                </thead>
                                <tbody>
                                    @forelse ($result as $row)
                                    <tr>
                                        <td class="text-justify p-1 align-middle">
                                            <div class="d-flex flex-row justify-content-start align-items-center">
                                                <div class="p-1 text-left">
                                                    <a href="{{ asset('storage/') }}{{ $row['img'] }}" data-toggle="mobile-lightbox" data-gallery="{{ $row['item_code'] }}" data-title="{{ $row['item_code'] }}">
                                                        <picture>
                                                            <source srcset="{{ asset('storage'.$row['img_webp']) }}" type="image/webp">
                                                            <source srcset="{{ asset('storage'.$row['img']) }}" type="image/jpeg">
                                                            <img src="{{ asset('storage'.$row['img']) }}" alt="{{ str_slug(explode('.', $row['img'])[0], '-') }}" class="row-img">
                                                        </picture>
                                                    </a>
                                                </div>
                                                <div class="p-1 m-0">
                                                    <span class="d-block font-weight-bold">{{ $row['item_code'] }}</span>
                                                    <small class="item-description d-none d-xl-block">{!! strip_tags($row['description']) !!}</small>
                                                </div>
                                            </div>

                                            <div class="modal fade" id="mobile-{{ $row['item_code'] }}-images-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
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
                                        <td class="text-center p-1 align-middle font-weight-bold">
                                            <span class="d-block">{{ $row['opening_qty'] }}</span>
                                        </td>
                                        <td class="text-center p-1 align-middle font-weight-bold">
                                            <span class="d-block">{{ number_format($row['sold_qty']) }}</span>
                                        </td>
                                        <td class="text-center p-1 align-middle font-weight-bold">
                                            <span class="d-block">{{ $row['audit_qty'] }}</span>
                                        </td>
                                    </tr>
                                    <tr class="d-xl-none">
                                        <td class="p-2 text-justify" colspan=4>
                                            <span class="item-description">{!! strip_tags($row['description']) !!}</span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td class="text-center font-weight-bold text-uppercase text-muted" colspan="2">No item(s) found</td>
                                    </tr> 
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="m-2">
                                Total: <b>{{ count($list) }}</b>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	</div>
</div>

<style>
    table {
        table-layout: fixed;
        width: 100%;   
    }
    .morectnt span {
        display: none;
    }
    .row-img{
        width: 70px;
        height: 70px;
    }
    .first{
        width: 70%;
    }
    @media (max-width: 575.98px) {
        #items-table{
            font-size: 10pt;
        }
        .first{
            width: 35%;
        }
        .row-img{
            width: 50px;
            height: 50px;
        }
    }
    @media (max-width: 767.98px) {
        #items-table{
            font-size: 10pt;
        }
        .first{
            width: 35%;
        }
        .row-img{
            width: 50px;
            height: 50px;
        }
    }
    @media only screen and (min-device-width : 768px) and (max-device-width : 1024px) and (orientation : portrait) {
        #items-table{
            font-size: 10pt;
        }
        .first{
            width: 35%;
        }
        .row-img{
            width: 50px;
            height: 50px;
        }
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