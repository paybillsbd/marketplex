<tr class="expenses" id="row{{ $row_id }}">
	<td>
	<p>{{ $datetime }} <input name="expense_id" type="hidden"></p></td>
	<td>
	<input id="purpose" type="text" name="purpose" class="form-control" required></td>
	<td>
	<input 	id="expense_amount" class="amount form-control" name="expense_amount"
			type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.which === 8" value="0.00" required /></td>
	<td>
	<a href="" name="remove" id="{{ $row_id }}" class="btn_remove">X</a>
	</td>
</tr>