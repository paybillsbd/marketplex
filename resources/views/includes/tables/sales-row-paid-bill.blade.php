<tr id="row{{ $row_id }}" class="dynamic-added bill_payment">
	<td>
	<p>
	{{ $datetime }} <input name="paid_bill_id[]" type="hidden" value="{{ isset($paid_bill_id) ? $paid_bill_id : -1 }}" /></p></td>
	<td>
	@if( isset($trans_option) )
	<p> {{ MarketPlex\BillPayment::getPaymentMethodText( $trans_option ) }} </p>
	@else
	<select class="form-control" id="trans_option" name="trans_option[]">
		<option value="by_cash_hand2hand" selected>{{ MarketPlex\BillPayment::getPaymentMethodText('by_cash_hand2hand') }}</option>
		<option value="by_cash_cheque_deposit">{{ MarketPlex\BillPayment::getPaymentMethodText('by_cash_cheque_deposit') }}</option>
		<option value="by_cash_electronic_trans">{{ MarketPlex\BillPayment::getPaymentMethodText('by_cash_electronic_trans') }}</option>
		<option value="by_cheque_hand2hand">{{ MarketPlex\BillPayment::getPaymentMethodText('by_cheque_hand2hand') }}</option>
	</select>
	@endif
	</td>
	<td>
	@if( isset($paid_amount) )
		<p class="decimal"> {{ number_format($paid_amount, 2) }} </p>
	@else
	<input 	id="paid_amount" name="paid_amount[]" class="decimal form-control"
			value="{{ number_format(0.00, 2) }}" type="text"
			onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.which === 8" required />
	@endif
	</td>
	<td>
	<a href="" name="remove" id="{{ $row_id }}" class="btn_remove">X</a>
	</td>
</tr>