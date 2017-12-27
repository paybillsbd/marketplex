<tr class="ship_bill" id="row{{ $row_id }}">
	<td>{{ $datetime }} <input name="shpping_bill_id" type="hidden"> </td>
	<td>
	<input id="purpose" type="text" name="purpose" class="form-control" required />
	</td>
	<td>
	<input id="amount" class="amount form-control" name="amount" type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.which === 8" value="0.00" required />
	</td>
	<td>
	<input 	id="quantity" class="form-control" name="quantity"
			type="number" min="0" value="0" onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.which === 8" required />
	</td>
	<td>
	<strong><i><span class="multTotal">0.00</span></i></strong>
	</td>
	<td>
	<a href="" name="remove" id="{{ $row_id }}" class="btn_remove">X</a>
	</td>
</tr>