@extends('layout', [
    'namePage' => $purpose == 'Material Transfer' ? 'Stock Transfers List' : 'Sales Returns List',
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
                            <span class="font-weight-bolder d-block text-uppercase" style="font-size: 11pt;">{{ $purpose == 'Material Transfer' ? 'Stock Transfers List' : 'Sales Returns List'}}</span>
                        </div>
                        <div class="card-body p-1">
                            <div class="d-flex flex-row align-items-center justify-content-between">
                                <div class="p-0 col-8 mx-auto text-center">
                                    <span class="font-responsive text-uppercase d-inline-block">{{ \Carbon\Carbon::now()->format('F d, Y') }}</span>
                                </div>
                                @if (Auth::user()->user_group == 'Promodiser')
                                    <div class="p-1 col-4 text-right">
                                        <a href="/stock_transfer/form" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Create</a>
                                    </div>    
                                @endif
                            </div>
                            @if(session()->has('success'))
                                <div class="callout callout-success font-responsive text-center pr-1 pl-1 pb-3 pt-3 m-2">
                                    {{ session()->get('success') }}
                                </div>
                            @endif
                            @if(session()->has('error'))
                                <div class="callout callout-danger font-responsive text-center pr-1 pl-1 pb-3 pt-3 m-2">
                                    {{ session()->get('error') }}
                                </div>
                            @endif
                       <div id="stock-transfer-list-display"></div>
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
        .morectnt span {
            display: none;
        }
        .modal{
            background-color: rgba(0,0,0,0.4);
        }
        @media (max-width: 575.98px) {
            .mobile-first-row{
                width: 70%;
            }
        }
        @media (max-width: 767.98px) {
            .mobile-first-row{
                width: 70%;
            }
        }
        @media only screen and (min-device-width : 768px) and (max-device-width : 1024px) and (orientation : portrait) {
            .mobile-first-row{
                width: 70%;
            }
        }

    </style>
@endsection

@section('script')
    <script>
        $(function () {
            var showTotalChar = 150, showChar = "Show more", hideChar = "Show less";
            $('.item-description').each(function() {
                var content = $(this).text();
                if (content.length > showTotalChar) {
                    var con = content.substr(0, showTotalChar);
                    var hcon = content.substr(showTotalChar, content.length - showTotalChar);
                    var txt = con + '<span class="dots">...</span><span class="morectnt"><span>' + hcon + '</span>&nbsp;&nbsp;<a href="#" class="show-more">' + showChar + '</a></span>';
                    $(this).html(txt);
                }
            });

            $(".show-more").click(function(e) {
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

            getStockTransferList();
            function getStockTransferList(page) {
                $.ajax({
                    type: "GET",
                    url: "/get_stock_transfer_list/{{ $purpose }}?page=" + page,
                    success: function (data) {
                    $('#stock-transfer-list-display').html(data);
                    }
                });
            }

            $(document).on('click', '#stock-transfer-pagination a', function(event){
                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                getStockTransferList(page);
            });
        });
    </script>    
@endsection