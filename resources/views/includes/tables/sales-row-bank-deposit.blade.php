<tr id="row{{ $row_id }}" class="dynamic-added bank_deposit">
	<td>
		<p>
		{{ $datetime }} <input name="bank_deposit_id[]" type="hidden" value="{{ isset($bank_deposit_id) ? $bank_deposit_id : -1 }}" />
		</p>
	</td>
	<td>
		<select class="form-control" id="deposit_method" name="deposit_method[]" row-id="{{ $row_id }}">
			@if( isset($deposit_method) )
				<option value="bank" {{ $deposit_method == 'bank' ? 'selected' : '' }}>Bank</option>
				<option value="vault" {{ $deposit_method == 'vault' ? 'selected' : '' }}>Vault</option>
			@else
				<option value="bank" selected>Bank</option>
				<option value="vault">Vault</option>
			@endif
		</select>
	</td>
	<td>
		@if( isset($bank_title) )
			<p>{{ $bank_title }}</p>
		@else
			<input id="bank_title" name="bank_title[]" class="form-control" type="text" row-id="{{ $row_id }}" required />
		@endif
	</td>
	<td>
		@if( isset($bank_account_no) )
			<p>{{ $bank_account_no }}</p>
		@else
			<select class="form-control" id="bank_ac_no" name="bank_ac_no[]" row-id="{{ $row_id }}">
				<option value="">-- Select --</option>
				@foreach( $bank_accounts as $acc)
					<option value="{{ $acc }}">{{ $acc }}</option>
				@endforeach
			</select>
		@endif
	</td>
	<td>
		@if(isset($deposit_amount))
			<p class="decimal">{{ number_format($deposit_amount, 2) }}</p>
		@else
		<input 	id="deposit_amount" name="deposit_amount[]" class="decimal form-control"
				type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.which === 8" row-id="{{ $row_id }}"
				value="{{ number_format(0.00, 2) }}" required />
		@endif
	</td>
	<td><a href="" name="remove" id="{{ $row_id }}" class="btn_remove">X</a></td>
</tr>