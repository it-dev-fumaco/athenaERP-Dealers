@extends('layout', [
    'namePage' => 'Damaged Items List',
    'activePage' => 'beginning_inventory',
])

@section('content')
    <div class="content">
        <div class="content-header p-0">
            <div class="container">
                <div class="row pt-1">
                    <div class="col-md-12 p-0 m-0">
                        <div class="card card-secondary card-outline">
                            <div class="card-header text-center">
                                <span class="font-responsive font-weight-bold text-uppercase d-inline-block">Damaged Items List</span>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-bordered" style="font-size: 10pt;">
                                    <tr>
                                        <th class="text-center" id="date-table">Date</th>
                                        <th class="text-center">
                                            <span class="d-block d-md-none">Details</span>
                                            <span class="d-none d-md-block">Item</span>
                                        </th>
                                        <th class="text-center d-none d-sm-table-cell">Store</th>
                                        <th class="text-center d-none d-sm-table-cell">Qty</th>
                                        <th class="text-center d-none d-sm-table-cell">Action</th>
                                    </tr>
                                    @foreach ($items_arr as $i => $item)
                                        <tr>
                                            <td class="text-center">
                                                {{ $item['creation'] }}
                                                
                                            </td>
                                            <td class="text-center">
                                                <span class="d-none d-md-block">
                                                    {{ $item['item_code'] }}
                                                </span>
                                                <div class="text-left d-block d-md-none">
                                                    <b>{{ $item['item_code'] }}</b> <br>
                                                    {{ $item['store'] }} <br><br>
                                                    <b>Damaged Qty:</b> {{ $item['damaged_qty'] }}&nbsp;<small>{{ $item['uom'] }}</small>
                                                    <center>
                                                        <a href="#" data-toggle="modal" data-target="#dmg-{{ $i }}-Modal-mobile">
                                                            View Details
                                                        </a>
                                                    </center>

                                                    <!-- Modal(mobile) -->
                                                    <div class="modal fade" id="dmg-{{ $i }}-Modal-mobile" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header" style="background-color: #001F3F; color: #fff;">
                                                                    <h5 class="modal-title" id="exampleModalLabel">{{ $item['item_code'] }} - Damage Report</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true" style="color: #fff;">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <table class="table table-bordered">
                                                                        <tr>
                                                                            <th class="text-center" style="width: 65%">Item</th>
                                                                            <th class="text-center">Damaged Qty</th>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="2">
                                                                                <div class="row" style="font-size: 10pt;">
                                                                                    <div class="col-2 col-md-3">
                                                                                        <picture>
                                                                                            <source srcset="{{ asset('storage/'.$item['webp']) }}" type="image/webp">
                                                                                            <source srcset="{{ asset('storage'.$item['image']) }}" type="image/jpeg">
                                                                                            <img src="{{ asset('storage/'.$item['image']) }}" alt="{{ str_slug(explode('.', $item['image'])[0], '-') }}" class="img-thumbna1il" alt="User Image" width="100%">
                                                                                        </picture>
                                                                                    </div>
                                                                                    <div class="col-6 col-md-4" style="display: flex; justify-content: center; align-items: center;">
                                                                                        <b>{{ $item['item_code'] }}</b>
                                                                                    </div>
                                                                                    <div class="col-4 offset-md-1" style="display: flex; justify-content: center; align-items: center;">
                                                                                        <b>{{ $item['damaged_qty'] }}</b>&nbsp;<small>{{ $item['uom'] }}</small>
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="2">
                                                                                <div class="item-description col-12 text-justify p-2">
                                                                                    {{ strip_tags($item['description']) }}
                                                                                </div>
                                                                                <div class="col-12 text-justify p-2">
                                                                                    <b>Damage Description</b><br>
                                                                                    {{ $item['damage_description'] }}
                                                                                </div>
                                                                                <div class="col-12 text-justify">
                                                                                    <b>Reported by:</b> {{ $item['promodiser'] }}
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center d-none d-sm-table-cell">{{ $item['store'] }}</td>
                                            <td class="text-center d-none d-sm-table-cell"><b>{{ $item['damaged_qty'] }}</b>&nbsp;<small>{{ $item['uom'] }}</small></td>
                                            <td class="text-center d-none d-sm-table-cell">
                                                <a href="#" data-toggle="modal" data-target="#dmg-{{ $i }}-Modal">
                                                    View Details
                                                </a>
                                                  
                                                <!-- Modal(large) -->
                                                <div class="modal fade" id="dmg-{{ $i }}-Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header" style="background-color: #001F3F; color: #fff;">
                                                                <h5 class="modal-title" id="exampleModalLabel">{{ $item['item_code'] }} - Damage Report</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true" style="color: #fff;">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <table class="table table-bordered">
                                                                    <tr>
                                                                        <th class="text-center" style="width: 65%">Item</th>
                                                                        <th class="text-center">Damaged Qty</th>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="2">
                                                                            <div class="row" style="font-size: 10pt;">
                                                                                <div class="col-2 col-md-3">
                                                                                    <picture>
                                                                                        <source srcset="{{ asset('storage/'.$item['webp']) }}" type="image/webp">
                                                                                        <source srcset="{{ asset('storage'.$item['image']) }}" type="image/jpeg">
                                                                                        <img src="{{ asset('storage/'.$item['image']) }}" alt="{{ str_slug(explode('.', $item['image'])[0], '-') }}" class="img-thumbna1il" alt="User Image" width="100%">
                                                                                    </picture>
                                                                                </div>
                                                                                <div class="col-6 col-md-4" style="display: flex; justify-content: center; align-items: center;">
                                                                                    <b>{{ $item['item_code'] }}</b>
                                                                                </div>
                                                                                <div class="col-4 offset-md-1" style="display: flex; justify-content: center; align-items: center;">
                                                                                    <b>{{ $item['damaged_qty'] }}</b>&nbsp;<small>{{ $item['uom'] }}</small>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="2">
                                                                            <div class="item-description col-12 text-justify p-2">
                                                                                {{ strip_tags($item['description']) }}
                                                                            </div>
                                                                            <div class="col-12 text-justify p-2">
                                                                                <b>Damage Description</b><br>
                                                                                {{ $item['damage_description'] }}
                                                                            </div>
                                                                            <div class="col-12 text-justify">
                                                                                <b>Reported by:</b> {{ $item['promodiser'] }}
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
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
        #date-table{
            width: auto;
        }
        @media (max-width: 575.98px) {
            #date-table{
                width: 35%;
            }
        }
        @media (max-width: 767.98px) {
            #date-table{
                width: 35%;
            }
        }
        @media only screen and (min-device-width : 768px) and (max-device-width : 1024px) and (orientation : portrait) {
            #date-table{
                width: 35%;
            }
        }

    </style>
@endsection

@section('script')
    <script>
        var showTotalChar = 140, showChar = "Show more", hideChar = "Show less";
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
    </script>
@endsection