<tr id="row{{ $row_id }}" class="product_bill" >
	<td>
		<p>{{ $datetime }} <input name="product_bill_id" type="hidden"></p></td>
	<td><p>{{ $product_title }}</p></td>
	<td><p>{{ $store_name }}</p></td>
	<td>
	<input 	id="product_quantity" name="product_quantity" 
			type="number" min="0" class="form-control" onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.which === 8" required /></td>
	<td><strong class="multTotal"><i>0.00</i></strong></td>
	<td>
	<a href="" name="remove" id="{{ $row_id }}" class="btn_remove">X</a></td>
</tr>