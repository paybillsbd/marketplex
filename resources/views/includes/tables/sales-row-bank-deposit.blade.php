<tr id="row{{ $row_id }}" class="dynamic-added bank_deposit" data-row-id="{{ $row_id }}">
	<td>
		<p>
		{{ $datetime }} <input name="deposits[{{ $row_id }}][bank_deposit_id]" type="hidden" value="{{ isset($bank_deposit_id) ? $bank_deposit_id : -1 }}" />
		</p>
	</td>
	<td>
		<div class="form-group">			
		<select class="form-control deposit_method" id="deposits.{{ $row_id }}.deposit_method" name="deposits[{{ $row_id }}][deposit_method]" row-id="{{ $row_id }}">			
			<option value="bank"{{ !isset($deposit_method) || $deposit_method == 'bank' ? ' selected' : '' }}>Bank</option>
			<option value="vault"{{ isset($deposit_method) && $deposit_method == 'vault' ? ' selected' : '' }}>Vault</option>
		</select>
		</div>
	</td>
	<td>
		<div class="form-group{{ isset($deposit_method) && $deposit_method == 'vault' ? ' hidden' : '' }}">
		<p id="deposits.{{ $row_id }}.bank_title"> {{ isset($bank_title) ? $bank_title : '' }} </p>
		</div>
	</td>
	<td>
		<div class="form-group{{ isset($deposit_method) && $deposit_method == 'vault' ? ' hidden' : '' }}">			
		<select class="form-control" id="deposits.{{ $row_id }}.bank_ac_no" name="deposits[{{ $row_id }}][bank_ac_no]" row-id="{{ $row_id }}">
			<option value="" selected>-- Select --</option>
			@foreach( $bank_accounts as $acc)
				<option value="{{ $acc }}"{{ isset($bank_account_no) && $bank_account_no == $acc ? ' selected' : '' }}>{{ $acc }}</option>
			@endforeach
		</select>
		</div>
	</td>
	<td>
		<div class="form-group">			
		<input 	id="deposits.{{ $row_id }}.deposit_amount" name="deposits[{{ $row_id }}][deposit_amount]" class="decimal form-control" row-id="{{ $row_id }}"
				type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.which === 8"
				value="{{ number_format(isset($deposit_amount) ? $deposit_amount : 0.00, 2) }}" required />
		</div>
	</td>
	<td><a href="" name="remove" id="{{ $row_id }}" class="btn_remove">X</a></td>
</tr>