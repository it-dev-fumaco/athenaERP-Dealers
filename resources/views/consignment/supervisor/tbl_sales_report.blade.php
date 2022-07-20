<div class="container-fluid bg-white p-0" style="overflow: auto">
<table class="table table-bordered w-100" id="report-table" style="font-size: 10pt;">
    <tr>
        <th class="text-center font-responsive align-middle p-1" rowspan=2 colspan=2 style="width: 20%;">Promodiser</th>
        <th class="text-center font-responsive align-middle p-1" rowspan=2 style="width: 5%;">Opening Stock</th>
        <th class="text-center font-responsive align-middle p-1" rowspan=2 style="width: 5%;">Opening Value</th>
        <th class="text-center font-responsive align-middle p-1" colspan={{ count($cutoff_periods) }}>Sales Per Cutt Off Date</th>
        <th class="text-center font-responsive align-middle p-1" rowspan=2 style="width: 8%;">Total</th>
    </tr>
    <tr>
        @foreach ($cutoff_periods as $period)
            <th class="text-center font-responsive align-middle p-1">{{ Carbon\Carbon::parse($period)->format('M-d') }}</th>
        @endforeach
    </tr>
    @foreach ($report_arr as $report)
        <tr>
            <td class="text-left font-responsive align-middle p-1 pl-4" colspan={{ count($cutoff_periods) + 5 }}>
                <span style="color: #001F3F">{{ $report['user'] }}</span>
            </td>
        </tr>
        @foreach ($report['assigned_warehouses'] as $warehouse)
            @php
                $opening_stock = isset($opening_stocks_arr[$report['user']][$warehouse->warehouse]['qty']) ? $opening_stocks_arr[$report['user']][$warehouse->warehouse]['qty'] : 0;
                $total_amount_sold_per_warehouse = isset($product_sold[$report['user']][$warehouse->warehouse]) ? collect($product_sold[$report['user']][$warehouse->warehouse])->sum('amount') : 0;
                $total_value_per_warehouse = isset($total_amount_arr[$report['user']][$warehouse->warehouse]) ? collect($total_amount_arr[$report['user']][$warehouse->warehouse])->sum('amount') : 0;
            @endphp
            <tr {{ $hidezero == 'true' && $total_amount_sold_per_warehouse <= 0 ? 'hidden' : ''  }}>
                <td class="p-1">&nbsp;</td>
                <td class="text-left font-responsive align-middle p-1" style=" font-size: 8pt;">{{ $warehouse->warehouse }}</td>
                <td class="text-center font-responsive align-middle p-1">
                    @if ($opening_stock > 0)
                        <span style="white-space: nowrap; font-size: 8pt;">{{ number_format($opening_stock) }}</span>
                    @else
                        <span class="text-muted">-</span>
                    @endif
                    <span class="opening-stocks d-none">{{ $opening_stock * 1 }}</span>
                </td>
                <td class="text-center font-responsive align-middle p-1">
                    @if ($total_value_per_warehouse > 0)
                        <span style="white-space: nowrap; font-size: 8pt;">₱ {{ number_format($total_value_per_warehouse) }}</span>
                    @else
                        <span class="text-muted">-</span>
                    @endif
                    <span class="opening-values d-none">{{ $total_value_per_warehouse }}</span>
                </td>
                @foreach ($cutoff_periods as $period)
                    @php
                        $amount = isset($product_sold[$report['user']][$warehouse->warehouse][$period]['amount']) ? $product_sold[$report['user']][$warehouse->warehouse][$period]['amount'] : 0;
                    @endphp
                    <td class="text-center font-responsive align-middle p-1">
                        @if ($amount > 0)
                            <span style="white-space: nowrap; font-size: 8pt;">₱ {{ number_format($amount) }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                        <span class="cutoff {{ $period }} d-none" data-period='{{ $period }}'>{{ $amount }}</span>
                    </td>
                @endforeach
                <td class="text-center font-responsive align-middle p-1">
                    @if ($total_amount_sold_per_warehouse > 0)
                        <span style="white-space: nowrap; font-size: 8pt;">₱ {{ number_format($total_amount_sold_per_warehouse) }}</span>
                    @else
                        <span class="text-muted">-</span>
                    @endif
                    <span class="total-per-warehouse d-none">{{ $total_amount_sold_per_warehouse }}</span>
                </td>
            </tr>
        @endforeach
    @endforeach
    <tr>
        <td class="text-center font-responsive font-weight-bold align-middle p-1">Total: </td>
        <td class="p-1">&nbsp;</td>
        <td class="text-center font-responsive align-middle p-1">
            <span id="total-opening-stocks" style="white-space: nowrap; font-size: 8pt;"></span>
        </td>
        <td class="text-center font-responsive align-middle p-1">
            <span id="total-opening-values" style="white-space: nowrap; font-size: 8pt;"></span>
        </td>
        @foreach ($cutoff_periods as $period)
            <td class="text-center font-responsive align-middle p-1">
                <span id="total-of-cutoff-{{ $period }}" style="white-space: nowrap; font-size: 8pt;"></span>
            </td>
        @endforeach
        <td class="text-center font-responsive align-middle p-1">
            <span id="total-of-all-warehouse" style="white-space: nowrap; font-size: 8pt;"></span>
        </td>
    </tr>
</table>
</div>
<style>
    #report-table th{
        color: #fff;
        background-color: #001F3F;
    }
</style>

<script>
    $(document).ready(function (){
        var total_product_sold = 0;
        var total_opening_stocks = 0;
        var total_opening_values = 0;

        get_total_per_cutoff();
        function get_total_per_cutoff(){
            $('.cutoff').each(function(){
                var period = $(this).data('period');
                var val = 0;
                $('.cutoff.'+period).each(function(){
                    val += parseInt($(this).text());
                    const cutoff = val.toLocaleString('en-US', {maximumFractionDigits: 2});
                    
                    var cutoff_total = null;
                    if(val > 0){
                        cutoff_total = '₱ ' + cutoff;
                        $('#total-of-cutoff-'+period).removeClass('text-muted');
                    }else{
                        cutoff_total = '-';
                        $('#total-of-cutoff-'+period).addClass('text-muted');
                    }

                    $('#total-of-cutoff-'+period).text(cutoff_total);
                });
            });
        }

        get_total_product_sold();
        function get_total_product_sold(){
            $('.total-per-warehouse').each(function(){
                total_product_sold += parseInt($(this).text());
            });
            
            const formatted = total_product_sold.toLocaleString('en-US', {maximumFractionDigits: 2});

            var sold_total = null;
            if(total_product_sold > 0){
                sold_total = '₱ ' + formatted;
                $('#total-of-all-warehouse').removeClass('text-muted');
            }else{
                sold_total = '-';
                $('#total-of-all-warehouse').addClass('text-muted');
            }

            $('#total-of-all-warehouse').text(sold_total);
        }
        
        get_total_opening_stocks();
        function get_total_opening_stocks(){
            $('.opening-stocks').each(function(){
                total_opening_stocks += parseInt($(this).text());
            });

            const formatted = total_opening_stocks.toLocaleString('en-US', {maximumFractionDigits: 2});
            
            var stock_total = null;
            if(total_opening_stocks > 0){
                stock_total = formatted;
                $('#total-opening-stocks').removeClass('text-muted');
            }else{
                stock_total = '-';
                $('#total-opening-stocks').addClass('text-muted');
            }

            $('#total-opening-stocks').text(stock_total);
        }

        get_total_opening_values();
        function get_total_opening_values(){
            $('.opening-values').each(function(){
                total_opening_values += parseInt($(this).text());
            });

            const formatted = total_opening_values.toLocaleString('en-US', {maximumFractionDigits: 2});
            
            var values_total = null;
            if(total_opening_values > 0){
                values_total = '₱ ' + formatted;
                $('#total-opening-values').removeClass('text-muted');
            }else{
                values_total = '-';
                $('#total-opening-values').addClass('text-muted');
            }

            $('#total-opening-values').text(values_total);
        }
    });
</script>