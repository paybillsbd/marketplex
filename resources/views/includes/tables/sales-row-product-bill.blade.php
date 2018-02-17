<tr id="row{{ $row_id }}" class="product_bill" data-row-id="{{ $row_id }}" data-product-id="{{ $product_id }}">
	<td>
	<p>
	{{ $datetime }}
	<input 	name="product_bills[{{ $row_id }}][product_bill_id]" type="hidden" value="{{ isset($product_bill_id) ? $product_bill_id : -1 }}" />
	<input 	name="product_bills[{{ $row_id }}][product_id]" type="hidden" value="{{ $product_id }}" />
	</p></td>
	<td><p>{{ $product_title }}</p></td>
	<td><p>{{ $store_name }}</p></td>
	<td>
		@isset($invoice)
		
		<p>{{ isset($bill_quantity) ? $bill_quantity : 0 }}</p>
		
		@else
        <div class="form-group">
		<input 	id="product_bills.{{ $row_id }}.product_quantity" name="product_bills[{{ $row_id }}][product_quantity]"
				class="form-control" data-product-id="{{ $product_id }}"
				value="{{ isset($bill_quantity) ? $bill_quantity : 0 }}" type="number"
				min="0" max="{{ isset($product_available_quantity) ? $product_available_quantity : '' }}"
				onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.which === 8" required />
		</div>
		@endisset
	</td>
	<td><strong><i class="multTotal">{{ number_format(isset($bill_price) ? ($bill_price * $bill_quantity) : 0.00, 2) }}</i></strong></td>

	@if ( ! isset($invoice) )
	<td>
	<a 	href="" name="remove" id="{{ $row_id }}" class="btn_remove fa fa-close fa-2x" data-product-id="{{ $product_id }}"
		data-toggle="tooltip" data-placement="top" title="Remove this entry"></a></td>
	@endif
</tr>