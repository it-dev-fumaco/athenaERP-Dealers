<table class="table table-striped table-bordered">
    <thead>
        <th class="text-center text-uppercase font-responsive p-2">
            <span class="d-none d-lg-block">Period</span>
            <span class="d-lg-none">Details</span>
        </th>
        <th class="text-center text-uppercase font-responsive p-2 d-none d-lg-table-cell">Store</th>
        <th class="text-center text-uppercase font-responsive p-2">Promodiser</th>
    </thead>
    <tbody>
        @forelse ($pending as $row)
            <tr>
                <td class="font-responsive">
                    @if (!$row['beginning_inventory_date'])
                    <span class="d-block text-uppercase text-muted">- Create beginning inventory -</span>
                    @else
                    <span class="d-block {{ $row['is_late'] ? 'text-danger' : '' }}">{{ $row['duration'] }}</span>
                    @endif
                    <span class="d-block d-lg-none">{{ $row['store'] }}</span>
                   </td>
                <td class="font-responsive text-center d-none d-lg-table-cell">{{ $row['store'] }}</td>
                <td class="font-responsive text-center">{{ $row['promodisers'] }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="text-center text-uppercase text-muted">No pending for submission of inventory audit found</td>
            </tr>
        @endforelse
    </tbody>
</table>