<tr class="ship_bill" id="row{{ $row_id }}">
	<td>
	{{ $datetime }} <input name="shipping_bill_id[]" type="hidden" value="{{ isset($shipping_bill_id) ? $shipping_bill_id : -1 }}" /></td>
	<td>
	@if( isset($shipping_purpose) )
		<p>{{ $shipping_purpose }}</p>
	@else
	<input id="shipping_purpose" type="text" name="shipping_purpose[]" class="form-control" required />
	@endif
	</td>
	<td>
		<input 	id="bill_amount" class="decimal form-control" name="bill_amount[]" value="{{ number_format( isset($bill_amount) ? $bill_amount : 0.00, 2) }}"
				type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.which === 8" required />
	</td>
	<td>
		<input 	id="bill_quantity" class="decimal form-control" name="bill_quantity[]" min="0" value="{{ isset($bill_quantity) ? $bill_quantity : 0 }}"
				type="number" onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.which === 8" required />
	</td>
	<td>
	<strong><i><span class="multTotal">{{ number_format(isset($bill_amount) ? ($bill_quantity * $bill_amount) : 0.00, 2) }}</span></i></strong>
	</td>
	<td>
	<a href="" name="remove" id="{{ $row_id }}" class="btn_remove">X</a>
	</td>
</tr>