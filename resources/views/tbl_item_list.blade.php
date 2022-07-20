{{--  <div class="box-body table-responsive no-padding">
	<table class="table table-condensed">
	<thead style="background-color:#E7E7E7">
		<tr>
			<th scope="col" class="col-xs-2" style="text-align:center;">IMAGE</th>
			<th scope="col" class="col-xs-4" style="text-align:center;">ITEM DESCRIPTION</th>
			<th scope="col" class="col-xs-2" style="text-align:center;">ITEM GROUP</th>
			<th scope="col" class="col-xs-2" style="text-align:center;">UoM</th>
			<th scope="col" class="col-xs-2" style="text-align:center;">CLASSIFICATION</th>
		</tr>
	</thead>

	@forelse ($item_list as $row)
	@php
		 $count_wh = count($row['item_inventory']);
		 $rowspan = ($count_wh > 0) ? ($count_wh + 2) : 3;
	@endphp
	<tbody>
		<tr>
			<td rowspan="{{ $rowspan }}" class="text-center">
				<a class='sample' data-height='720' data-lighter='samples/sample-01.jpg' data-width='1280' href='img/1601702564-LR00443.jpeg'>
					<img src='{{ asset('storage/icon/barcode.png') }}' width="200">
				</a>
				  	<div class="imgButton">
						<img src="{{ asset('storage/icon/barcode.png') }}" class="img-circle" id="btn" name="barcode" value="Print Barcode" onClick="javascript:void window.open('barcode.php?item_id=LR00443','1445905018294','width=450,height=700,toolbar=0,menubar=0,location=0,status=1,scrollbars=1,resizable=1,left=0,top=0');return false;"
							 width="40px">
						&nbsp;&nbsp;
						<img src="{{ asset('storage/icon/report.png') }}" class="img-circle" name="history" id="btn" value="Transaction Histories" onClick="javascript:void window.open('transaction_histories.php?item_id=LR00443','1445905018294','width=1100,height=700,toolbar=0,menubar=0,location=0,status=1,scrollbars=1,resizable=1,left=0,top=0');return false;" width="40px">
						&nbsp;&nbsp;
						<img src="{{ asset('storage/icon/upload.png') }}" class="img-circle" name="upload" id="btn" value="Upload Image" width="40px" onClick="javascript:void window.open('upload_image.php?item_id=LR00443','1445905018294','width=600,height=200,toolbar=0,menubar=0,location=0,status=1,scrollbars=1,resizable=1,left=600,top=300');return false;">
				  </div>
			 </td>
			 <td rowspan="{{ $rowspan }}"><b>{{ $row['name'] }}</b><br>{{ $row['description'] }}<br><br><b>Part No(s):{{ $row['part_nos'] }}</b></td>
			 <td class="text-center" rowspan="{{ $rowspan }}">{{ $row['item_group'] }}</td>
			 <td class="text-center">{{ $row['stock_uom'] }}</td>
			 <td class="text-center">{{ $row['item_classification'] }}</td>
		</tr>
		<tr>
			 <th style="text-align:center;">Warehouse</th>
			 <th style="text-align:center;">Quantity</th>
		</tr>
		@forelse($row['item_inventory'] as $inv)
		<tr>
			<td>{{ $inv->warehouse }}</td>
			<td style="text-align:center;">{{ $inv->actual_qty * 1 }} {{ $inv->stock_uom }}</td>
		</tr>
		@empty
		<tr><td colspan="5" style="text-align:center;">NO WAREHOUSE ASSIGNED</td></tr>
			
		@endforelse
		
  	</tbody>
  	<tr class="nohover">
		<td colspan="5">&nbsp;</td>
  	</tr>
	@empty
	<tr class="nohover">
		<td colspan="5" style="text-align: center;"><br><label style="font-size: 16pt;">No result(s) found.</label><br>&nbsp;</td>
	</tr>
	@endforelse
</table>


                    
</div>

<div class="box-footer clearfix" style="font-size: 16pt;">
	{{ $items->links() }}
</div>  --}}