<tr id="row{{ $row_id }}" class="dynamic-added bill_payment" data-row-id="{{ $row_id }}">
	<td>
	<p>
	{{ $datetime }} <input name="payments[{{ $row_id }}][paid_bill_id]" type="hidden" value="{{ isset($paid_bill_id) ? $paid_bill_id : -1 }}" /></p></td>
	<td>
		@isset($invoice)
		<p>{{ MarketPlex\BillPayment::getPaymentMethodText($trans_option) }}</p>
		@else
			<div class="form-group">
			<select class="form-control" id="payments.{{ $row_id }}.trans_option" name="payments[{{ $row_id }}][trans_option]">
			@if( isset($trans_option) )
				<option 
					value="by_cash_hand2hand"
					{{ $trans_option == 'by_cash_hand2hand' ? 'selected' : '' }}>{{ MarketPlex\BillPayment::getPaymentMethodText($trans_option) }}</option>
				<option
					value="by_cash_cheque_deposit"
					{{ $trans_option == 'by_cash_cheque_deposit' ? 'selected' : '' }}>{{ MarketPlex\BillPayment::getPaymentMethodText($trans_option) }}</option>
				<option
					value="by_cash_electronic_trans"
					{{ $trans_option == 'by_cash_electronic_trans' ? 'selected' : '' }}>{{ MarketPlex\BillPayment::getPaymentMethodText($trans_option) }}</option>
				<option
					value="by_cheque_hand2hand"
					{{ $trans_option == 'by_cheque_hand2hand' ? 'selected' : '' }}>{{ MarketPlex\BillPayment::getPaymentMethodText($trans_option) }}</option>
			@else
				<option value="by_cash_hand2hand" selected>{{ MarketPlex\BillPayment::getPaymentMethodText('by_cash_hand2hand') }}</option>
				<option value="by_cash_cheque_deposit">{{ MarketPlex\BillPayment::getPaymentMethodText('by_cash_cheque_deposit') }}</option>
				<option value="by_cash_electronic_trans">{{ MarketPlex\BillPayment::getPaymentMethodText('by_cash_electronic_trans') }}</option>
				<option value="by_cheque_hand2hand">{{ MarketPlex\BillPayment::getPaymentMethodText('by_cheque_hand2hand') }}</option>
			@endif
			</select>
		@endisset
		</div>
	</td>
	<td>
		@isset($invoice)
		<i>{{ number_format( isset($paid_amount) ? $paid_amount : 0.00, 2) }}</i>
		@else
		<div class="form-group">
		<input 	id="payments.{{ $row_id }}.paid_amount" name="payments[{{ $row_id }}][paid_amount]" class="decimal form-control"
				value="{{ number_format( isset($paid_amount) ? $paid_amount :  0.00, 2) }}" type="text"
				onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.which === 8" required />
		</div>
		@endisset
	</td>
	@if( ! isset($invoice) )
	<td>
	<a href="" name="remove" id="{{ $row_id }}" class="btn_remove fa fa-close fa-2x" data-toggle="tooltip" data-placement="top" title="Remove this entry"></a>
	</td>
	@endif
</tr>