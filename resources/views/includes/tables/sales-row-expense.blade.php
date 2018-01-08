<tr class="expenses" id="row{{ $row_id }}" data-row-id="{{ $row_id }}">
	<td>
	<p>{{ $datetime }} <input name="expenses[{{ $row_id }}][expense_id]" type="hidden" value="{{ isset($expense_id) ? $expense_id : -1 }}" /></p></td>
	<td>
		<div class="form-group">	
		<input 	id="expenses.{{ $row_id }}.expense_purpose" type="text" name="expenses[{{ $row_id }}][expense_purpose]" class="form-control"
				value="{{ isset($expense_purpose) ? $expense_purpose : '' }}" required />
		</div>
	</td>
	<td>
		<div class="form-group">	
		<input 	id="expenses.{{ $row_id }}.expense_amount" class="decimal form-control" name="expenses[{{ $row_id }}][expense_amount]"
				value="{{ number_format( isset($expense_amount) ? $expense_amount : 0.00, 2) }}" type="text"
				onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.which === 8" required />
		</div>
	</td>
	<td>
	<a href="" name="remove" id="{{ $row_id }}" class="btn_remove">X</a>
	</td>
</tr>