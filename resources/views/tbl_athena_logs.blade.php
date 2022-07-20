<div class="table-responsive p-0" style="height: 650px;">
    <table class="table table-bordered table-hover tbl-athena-logs-table dashboard-table">
        <col class="tbl-athena-logs-tbl-date"><!-- Date -->
        <col class="tbl-athena-logs-tbl-item-description"><!-- Item Description -->
        <col class="tbl-athena-logs-tbl-warehouse"><!-- Warehouse -->
        <col class="tbl-athena-logs-tbl-qty"><!-- Qty -->
        <col class="tbl-athena-logs-tbl-ref"><!-- Ref. No. -->
        <col class="tbl-athena-logs-tbl-user"><!-- Transact by -->
        <thead>
            <tr>
                <th scope="col" class="text-center align-middle">Date</th>
                <th scope="col" class="text-center align-middle">Item Description</th>
                <th scope="col" class="text-center align-middle d-none d-lg-table-cell">Warehouse</th>
                <th scope="col" class="text-center align-middle d-none d-lg-table-cell">Qty</th>
                <th scope="col" class="text-center align-middle d-none d-lg-table-cell">Ref. No.</th>
                <th scope="col" class="text-center align-middle d-none d-lg-table-cell p-1">Transact by</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($list as $row)
            <tr>
                <td class="text-center align-middle p-2">
                    <span class="d-block font-weight-bold">{{ \Carbon\Carbon::parse($row->transaction_date)->format('M-d-Y h:i:A') }}</span>
                    @if (strpos($row->transaction_type, 'Out'))
                    <span class="badge badge-warning" style="font-size: 0.7rem;">{{ $row->transaction_type }}</span>
                    @elseif (strpos($row->transaction_type, 'In'))
                    <span class="badge badge-success" style="font-size: 0.7rem;">{{ $row->transaction_type }}</span>
                    @elseif ($row->transaction_type == 'Stock Reconciliation')
                    <span class="badge badge-secondary" style="font-size: 0.7rem;">Stock Adjustment</span>
                    @else
                    <span class="badge badge-danger" style="font-size: 0.7rem;">Unknown</span>
                    @endif
                </td>
                <td class="text-justify align-middle">
                    <span class="font-weight-bold font-responsive">{{ $row->item_code }}</span> - {{ str_limit($row->description, $limit = 70, $end = '...') }}<br/>
                </td>
                <td class="text-center d-none d-lg-table-cell align-middle">{{ ($row->s_warehouse) ? $row->s_warehouse : $row->t_warehouse }}</td>
                <td class="text-center d-none d-lg-table-cell align-middle font-weight-bold" style="font-size: 0.9rem;">{{ number_format($row->qty * 1) }}</td>
                <td class="text-center d-none d-lg-table-cell align-middle">
                    <span class="d-block">{{ $row->reference_no }}</span>
                    @if ($row->transaction_type != 'Stock Reconciliation')
                    <span class="d-block">{{ $row->reference_parent }}</span>
                    @endif
                </td>
                <td class="text-center d-none d-lg-table-cell align-middle">
                    @if ($row->transaction_type != 'Stock Reconciliation')
                    {{ $row->user }}
                    @else
                    {{ ucwords(str_replace('.', ' ', explode('@', $row->user)[0])) }}
                    @endif
                </td>
            </tr>
            <tr>
                <td class="d-lg-none" colspan=6>
                    <table class="table">
                        <tr>
                            <td class="p-1">
                                <b>Warehouse:</b>
                            </td>
                            <td class="p-1">
                                {{ ($row->s_warehouse) ? $row->s_warehouse : $row->t_warehouse }}
                            </td>
                        </tr>
                        <tr>
                            <td class="p-1">
                                <b>Qty:</b>
                            </td>
                            <td class="p-1">
                                {{ number_format($row->qty * 1) }}
                            </td>
                        </tr>
                        <tr>
                            <td class="p-1">
                                <b>Ref. No:</b>
                            </td>
                            <td class="p-1">
                                {{ $row->reference_no }}
                            </td>
                        </tr>
                        @if ($row->transaction_type != 'Stock Reconciliation')
                            <tr>
                                <td></td>
                                <td class="p-1">
                                    <span class="d-block">{{ $row->reference_parent }}</span>
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <td class="p-1"><b>Transact By:</b></td>
                            <td class="p-1">
                                @if ($row->transaction_type != 'Stock Reconciliation')
                                    {{ $row->user }}
                                @else
                                    {{ ucwords(str_replace('.', ' ', explode('@', $row->user)[0])) }}
                                @endif
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center align-middle">No Record(s) Found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>