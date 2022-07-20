<ul>
	@foreach(array_keys($groups) as $group)
    	@php
			$next_level = isset($all[$group]) ? collect($all[$group])->groupBy('name') : [];
		@endphp
		<li style="margin-left: -10px">
            <span class="w-100 sub-item {{ request('group') == $group ? 'selected-tree-item' : 'tree-item' }}">
				<a style="font-size: 10pt; letter-spacing: -1px !important; color: inherit !important;" href="{!! request()->fullUrlWithQuery(['group' => $group]) !!}">
					<i class="far {{ $next_level ? 'fa-folder-open' : 'fa-file' }}"></i>&nbsp;{{ $group }}
				</a>
            </span>
			@if($next_level)
				@include('search_results_item_group_tree', ['all' => $all, 'groups' => $next_level->toArray(), 'current_lvl' => $current_lvl + 1, 'prev_obj' => $group])
			@endif
		</li>
	@endforeach
</ul>