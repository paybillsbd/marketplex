<tr id="row{{ $row_id }}" class="product_bill" >
	<td>
	<p>
	{{ $datetime }} <input name="product_bill_id[]" type="hidden" value="{{ isset($product_bill_id) ? $product_bill_id : -1 }}" />
	</p></td>
	<td><p>{{ $product_title }}</p></td>
	<td><p>{{ $store_name }}</p></td>
	<td>
	@if ( isset($bill_quantity) )
		<p class="decimal">{{ $bill_quantity }}</p>
	@else
		<input 	id="product_quantity" name="product_quantity[]" class="form-control" value="0" type="number"
				min="0" max="{{ isset($product_available_quantity) ? $product_available_quantity : '' }}"
				onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.which === 8" required />
	@endif
	</td>
	<td><strong><i class="multTotal">{{ number_format(isset($bill_price) ? ($bill_price * $bill_quantity) : 0.00, 2) }}</i></strong></td>
	<td>
	<a href="" name="remove" id="{{ $row_id }}" class="btn_remove">X</a></td>
</tr>