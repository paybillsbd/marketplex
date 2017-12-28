<tr class="expenses" id="row{{ $row_id }}">
	<td>
	<p>{{ $datetime }} <input name="expense_id[]" type="hidden" value="{{ isset($expense_id) ? $expense_id : -1 }}" /></p></td>
	<td>
	@if( isset($expense_purpose) )
		<p>{{ $expense_purpose }} </p>
	@else
		<input 	id="expense_purpose" type="text" name="expense_purpose[]" class="form-control" required />
	@endif
	</td>
	<td>
	@if( isset($expense_amount) )
		<p class="decimal">{{ number_format($expense_amount, 2) }} </p>
	@else
		<input 	id="expense_amount" class="decimal form-control" name="expense_amount[]"
				value="{{ number_format(0.00, 2) }}" type="text"
				onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.which === 8" required />
	@endif
	</td>
	<td>
	<a href="" name="remove" id="{{ $row_id }}" class="btn_remove">X</a>
	</td>
</tr>