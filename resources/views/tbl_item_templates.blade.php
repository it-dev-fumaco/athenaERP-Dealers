<table class="table table-bordered table-striped table-sm table-hover" style="font-size: 9pt;">
    <thead>
        <th class="text-center p-1" style="width: 20%;">Item Code</th>
        <th class="text-center p-1" style="width: 60%;">Description</th>
        <th class="text-center p-1" style="width: 20%;">Action</th>
    </thead>
    <tbody>
        @forelse ($list as $row)
        <tr>
            <td class="text-center align-middle p-0 font-weight-bold">{{ $row->name }}</td>
            <td class="text-justify align-middle">{{ $row->description }}</td>
            <td class="text-center align-middle p-2">
                <a href="/view_variants/{{ $row->name }}" class="btn btn-secondary btn-sm" style="font-size: 9pt;">View Variant(s)</a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="3" class="text-center align-middle p-3 font-weight-bold">No item(s) found.</td>
        </tr>
        @endforelse
    </tbody>
</table>

<div class="box-footer clearfix" id="item-templates-pagination" style="font-size: 9pt;">
    {{ $list->links() }}
</div>