<tr class="ship_bill" id="row{{ $row_id }}" data-row-id="{{ $row_id }}">
	<td>
	{{ $datetime }} <input name="shipping_bills[{{ $row_id }}][shipping_bill_id]" type="hidden" value="{{ isset($shipping_bill_id) ? $shipping_bill_id : -1 }}" /></td>
	<td>
        <div class="form-group">
		<input id="shipping_bills.{{ $row_id }}.shipping_bill_id" type="text" name="shipping_bills[{{ $row_id }}][shipping_purpose]" class="form-control" value="{{ isset($shipping_purpose) ? $shipping_purpose : '' }}" required />
		</div>
	</td>
	<td>
        <div class="form-group">
		<input 	id="shipping_bills.{{ $row_id }}.bill_amount" class="decimal form-control"
				name="shipping_bills[{{ $row_id }}][bill_amount]" value="{{ number_format( isset($bill_amount) ? $bill_amount : 0.00, 2) }}"
				type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.which === 8" required />
		</div>
	</td>
	<td>
        <div class="form-group">
		<input 	id="shipping_bills.{{ $row_id }}.bill_quantity" class="decimal form-control"
				name="shipping_bills[{{ $row_id }}][bill_quantity]" min="0" value="{{ isset($bill_quantity) ? $bill_quantity : 0 }}"
				type="number" onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.which === 8" required />
		</div>
	</td>
	<td>
	<strong><i><span class="multTotal">{{ number_format(isset($bill_amount) ? ($bill_quantity * $bill_amount) : 0.00, 2) }}</span></i></strong>
	</td>
	<td>
	<a href="" name="remove" id="{{ $row_id }}" class="btn_remove fa fa-close fa-2x" data-toggle="tooltip" data-placement="top" title="Remove this entry"></a>
	</td>
</tr>