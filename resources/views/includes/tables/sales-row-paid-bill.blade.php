<tr id="row{{ $row_id }}" class="dynamic-added bill_payment">
	<td>
	<p>{{ $datetime }} <input name="paid_bill_id" type="hidden"></p></td>
	<td>
	<select class="form-control" id="trans_option">
		<option>By Cash (hand to hand)</option>
		<option>By Cash (cheque deposit)</option>
		<option>By Cash (electronic transfer)</option>
		<option>By Cheque (hand to hand)</option>
	</select>
	</td>
	<td>
	<input 	id="paid_amount" name="paid_amount" class="paid-amount form-control"
			type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.which === 8" value="0.00" required /></td>
	<td>
	<a href="" name="remove" id="{{ $row_id }}" class="btn_remove">X</a>
	</td>
</tr>