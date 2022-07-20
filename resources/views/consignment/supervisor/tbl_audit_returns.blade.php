<style>
    .tbl-custom-height {
        height: 700px;
    }
</style>
@if (count($list) > 0)
<h5 class="text-center text-uppercase mt-2 p-2 border-bottom font-weight-bolder">Returns / Transfers</h5>
<div class="table-responsive p-0 {{ count($list) > 0 ? 'tbl-custom-height' : '' }}">
    <table class="table table-bordered table-striped table-head-fixed" style="font-size: 9pt;">
        <thead class="border-top bg-navy">
            <th class="text-center font-responsive p-2 align-middle first" style="width: 55%;">Item Code</th>
            <th class="text-center font-responsive p-2 align-middle" style="width: 15%;">Qty</th>
            <th class="text-center font-responsive p-2 align-middle" style="width: 15%;">Rate</th>
            <th class="text-center font-responsive p-2 align-middle" style="width: 15%;">Amount</th>
        </thead>
        <tbody>
            @forelse ($list as $d)
            <tr>
                <td class="text-justify p-1 align-middle">
                    <p class="m-0">
                        <span class="font-weight-bold">{{ $d->item_code }}</span> - 
                        <span class="item-description">{!! strip_tags($d->description) !!}</span>
                    </p>
                    <small class="d-block font-italic mt-2"><b>Created by:</b> {{ ucwords(str_replace('.', ' ', explode('@', $d->owner)[0])) . ' - ' . \Carbon\Carbon::parse($d->creation)->format('M. d, Y h:i A') }}</small>
                </td>
                <td class="text-center p-1 align-middle font-weight-bold">
                    <span class="d-block">{{ number_format($d->transfer_qty) }}</span>
                    <small class="d-block">{{ $d->stock_uom }}</small>
                </td>
                <td class="text-center p-1 align-middle font-weight-bold">
                    <span class="d-block">{{ '₱ ' . number_format($d->basic_rate, 2) }}</span>
                </td>
                <td class="text-center p-1 align-middle font-weight-bold">
                    <span class="d-block">{{ '₱ ' . number_format($d->basic_amount, 2) }}</span>
                </td>
            </tr>
            @empty
            <tr>
                <td class="text-center font-weight-bold text-uppercase text-muted" colspan="4">No record(s) found</td>
            </tr> 
            @endforelse
        </tbody>
    </table>
</div>
@endif

<script>
     $(function () {
        var showTotalChar = 120, showChar = "Show more", hideChar = "Show less";
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