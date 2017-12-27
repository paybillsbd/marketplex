<tr id="row{{ $row_id }}" class="dynamic-added bank_deposit">
	<td>
		<p>{{ $datetime }} <input name="bank_deposit_id" type="hidden"></p></td>
	<td>
	<select class="form-control" id="deposit_method" row-id="{{ $row_id }}">
		<option>Bank</option>
		<option>Vault</option>
	</select>
	</td>
	<td>
	<input id="bank_title" name="bank_title" class="form-control" required="required" type="text" row-id="{{ $row_id }}" /></td>
	<td>
	<select class="form-control" id="bank_ac_no" row-id="{{ $row_id }}">
		<option>151035654646001</option>
		<option>151035654646002</option>
		<option>151035654646003</option>
	</select></td>
	<td>
	<input 	id="deposit_amount" name="deposit_amount" class="deposit-amount form-control"
			type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.which === 8" row-id="{{ $row_id }}" value="0.00" required /></td>
	<td>
	<a href="" name="remove" id="{{ $row_id }}" class="btn_remove">X</a></td>
</tr>