@extends('layout', [
    'namePage' => 'Delivery Report',
    'activePage' => 'beginning_inventory',
])

@section('content')
<div class="content">
	<div class="content-header p-0">
        <div class="container">
            <div class="row pt-1">
                <div class="col-md-12 p-0 m-0">
                    <div class="card card-lightblue">
                        @if (session()->has('success'))
                            @php
                                $received = session()->get('success');
                            @endphp
                            <div class="modal fade" id="receivedDeliveryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-navy">
                                            <h5 class="modal-title" id="exampleModalLabel">Delivered Item(s)</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true" style="color: #fff">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body" style="font-size: 10pt;">
                                            <span>{{ $received['message'] }}</span> <br>
                                            <span>Branch: <b>{{ $received['branch'] }}</b></span> <br>
                                            @if (isset($received['is_cancelled']))
                                            <span>Total Amount: <b>₱ {{ number_format($received['amount'], 2) }}</b></span>
                                            @else
                                            <span>Total Amount: <b>₱ {{ number_format(collect($received)->sum('amount'), 2) }}</b></span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <script>
                                $(document).ready(function(){
                                    $('#receivedDeliveryModal').modal('show');
                                });
                            </script>
                        @endif
                        <div class="card-header text-center p-2">
                            <span class="font-weight-bolder d-block text-uppercase" style="font-size: 11pt;">
                                @if ($type == 'all')
                                    Delivery Report
                                @else
                                    Incoming Deliveries                                    
                                @endif
                            </span>
                        </div>
                        <div class="card-body p-1">
                            @if(session()->has('error'))
                            <div class="callout callout-danger font-responsive text-center pr-1 pl-1 pb-3 pt-3 m-2">{!! session()->get('error') !!}</div>
                            @endif
                            <div id="delivery-list-display"></div>
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
        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
        }

        /* Firefox */
        input[type=number] {
        -moz-appearance: textfield;
        }
        .modal{
            background-color: rgba(0,0,0,0.4);
        }
    </style>
@endsection

@section('script')
    <script>
        $(document).ready(function(){
            var showTotalChar = 150, showChar = "Show more", hideChar = "Show less";

            $('.price').keyup(function(){
                var target = $(this).data('target');
                var price = $(this).val().replace(/,/g, '');
                if($.isNumeric($(this).val()) && price > 0 || $(this).val().indexOf(',') > -1 && price > 0){
                    var qty = parseInt($('#'+target+'-qty').text());
                    var total_amount = price * qty;

                    const amount = total_amount.toLocaleString('en-US', {maximumFractionDigits: 2});
                    $('#'+target+'-amount').text(amount);
                }else{
                    $('#'+target+'-amount').text('0');
                    $(this).val('');
                }
            });

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

            getDeliveryList();
            function getDeliveryList(page) {
                $.ajax({
                    type: "GET",
                    url: "/get_delivery_list/{{ $type }}?page=" + page,
                    success: function (data) {
                    $('#delivery-list-display').html(data);
                    }
                });
            }

            $(document).on('click', '#delivery-pagination a', function(event){
                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                getDeliveryList(page);
            });
        });
    </script>
@endsection