<div class="row" style="font-size: 10pt;">
    <div class="col-md-12">
        <div class="d-flex flex-row align-items-center border-bottom mb-2 mt-1 p-2">
            <div class="p-0 col-4 text-left">
                <p class="m-1 font-details">Promodiser(s): <span class="font-weight-bold">{{ $summary['promodisers'] }}</span></p>
            </div>
            <div class="p-0 col-4 text-center">
                <h5 class="text-uppercase font-weight-bolder m-0">Sales</h5>
            </div>
            <div class="p-0 col-2 text-left">
                <p class="m-1 font-details">Total Qty Sold: <span class="font-weight-bold">{{ $summary['total_qty_sold'] }}</span></p>
            </div>
            <div class="p-0 col-2 text-left">
                <p class="m-1 font-details">Total Sales: <span class="font-weight-bold">{{ $summary['total_sales'] }}</span></p>
            </div>
        </div>
    </div>
</div>

<table class="table table-bordered table-striped" style="font-size: 9pt;">
    <thead class="bg-navy">
        <tr>
            <th class="text-center font-responsive p-2 align-middle" rowspan="2" style="width: 500px;">Item Code</th>
            <th class="text-center font-responsive p-2 align-middle" rowspan="2">Rate</th>
            <th class="text-center font-responsive p-2 align-middle" rowspan="2">Opening Qty</th>
            <th class="text-center font-responsive p-2 align-middle" colspan="{{ count($transaction_dates) }}">Sold Qty</th>
            <th class="text-center font-responsive p-2 align-middle" rowspan="2">Amount</th>
            <th class="text-center font-responsive p-2 align-middle" rowspan="2">Available Qty</th>
        </tr>
        <tr>
            @foreach ($transaction_dates as $transaction_date)
            <th class="text-center p-2 align-middle font-weight-bold">
                {{ \Carbon\Carbon::parse($transaction_date)->format('Y/m/d') }}
            </th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @forelse ($sales as $item_code => $s)
        <tr>
            <td class="text-justify p-2 align-middle">
                <p class="m-0">
                    <span class="font-weight-bold">{{ $item_code }}</span> - 
                    <span class="item-description">{!! strip_tags($s['description']) !!}</span>
                </p>
            </td>
            <td class="text-center p-2 align-middle">
                <span class="d-block text-nowrap">{{ '₱ ' . number_format($s['price'], 2) }}</span>
            </td>
            <td class="text-center p-2 align-middle">
                <span class="d-block text-nowrap">{{ number_format($s['opening_qty']) }}</span>
            </td>
            @foreach ($transaction_dates as $transaction_date)
                
            <td class="text-center p-2 align-middle">
                @php
                    $sold_qty = array_key_exists($transaction_date, $sales[$item_code]['per_day']) ? number_format($sales[$item_code]['per_day'][$transaction_date]) : 0;
                @endphp
                @if ($sold_qty > 0)
                <span class="d-block font-weight-bold" style="font-size: 11pt;"> {{ $sold_qty }}</span>
                @else
                <span class="d-block"> {{ $sold_qty }}</span>
                @endif
           
            </td>
            @endforeach
            <td class="text-center p-2 align-middle">
                <span class="d-block text-nowrap">{{ '₱ ' . number_format($s['amount'], 2) }}</span>
            </td>
            <td class="text-center p-2 align-middle">
                <span class="d-block text-nowrap">{{ number_format($s['ending_qty']) }}</span>
            </td>
        </tr>
        @empty
        <tr>
            <td class="text-center font-weight-bold text-uppercase text-muted" colspan="6">No record(s) found</td>
        </tr> 
        @endforelse
    </tbody>
</table>
<p class="m-1 font-details text-right">Total Item(s): <span class="font-weight-bold">{{ $summary['total_items'] }}</span></p>

<script>
    $(function () {
       var showTotalChar = 95, showChar = "Show more", hideChar = "Show less";
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