
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<!-- <link href="https://fonts.maateen.me/bensen/font.css" rel="stylesheet"> -->
<style>
.page-break {
    page-break-after: always;
}
/*@font-face {
    font-family: 'FreeSerif';
    src: url('http://savannah.gnu.org/projects/freefont/');
    font-weight: normal;
    font-style: normal;
}*/
/*
 * Lohit Bengali (Bengali) http://www.google.com/fonts/earlyaccess
 */
/*@font-face {
  font-family: 'Lohit Bengali';
  font-style: normal;
  font-weight: 400;
  src: {{ public_path('/fonts/Lohit-Bengali.ttf') }} format('truetype');
}*/
table, th, td {
	border-color: black;
	text-align: center;
	border-collapse: collapse;
  border: 2px solid black;

/*    font-family: 'Lohit Bengali', sans-serif;
    font-size: 14px;*/
}
.decimal {
	text-align: right;
}

</style>
</head>

<h1 class="text-center">Sales Invoice</h1>

<h3 class="text-center"><label for="client"><strong>Business/Client/Company Name:</strong>{{ $sale->client_name }}</label></h3>

<table>
  <tr>
    <td width="50%">Date & Time:</td>
    <td width="50%">{{ isset($sale) ? $sale->created_at : 'Unknown' }}</td>
  </tr>
  <tr>
    <td width="50%">Bill ID:</td>
    <td width="50%">{{ isset($sale) ? $sale->bill_id : 'Unknown' }}</td>
  </tr>
<tbody>
</tbody>
</table>

<h4><strong>Billing</strong></h4>

<h5><strong>Product Billing:</strong></h5>
<div class="row">
<div class="col-md-12">
<table  class="table table-bordered" id="product_bill_table">
<thead>
  <tr>
  <th width="10%">Date</th>
  <th width="20%">Title</th>
  <th width="20%">Store</th>
  <th width="10%">Quantity</th>
  <th width="35%">Total</th>
  </tr>
</thead>
<tbody>
    @if( isset($sale) )
      @forelse( $sale->productbills as $bill )
        @include('includes.tables.sales-row-product-bill', [
        	'invoice' => true,
            'row_id' => 1,
            'product_bill_id' => $bill->id,
            'product_id' => $bill->product_id,
            'datetime' => Carbon\Carbon::parse($bill->created_at)->format('m/d/Y h:m'),
            'product_title' => $bill->product->title,
            'store_name' => $bill->product->store->name,
            'bill_price' => $bill->product->mrp,
            'bill_quantity' => $bill->quantity,
            'product_available_quantity' => $bill->product->available_quantity
        ])
      @empty
        @component('includes.tables.empty-table-message', [ 'colspan' => 5, 'level' => 'info', 'message' => $messages['empty_table']['sale_product'] ])
            <div class="alert alert-warning">No records added yet</div>
        @endcomponent
      @endforelse
    @else
        @component('includes.tables.empty-table-message', [ 'colspan' => 5, 'level' => 'info', 'message' => $messages['empty_table']['sale_product'] ])
            <div class="alert alert-warning">No records added yet</div>
        @endcomponent
    @endif
</tbody>
</table>
</div>
</div>

<div class="text-center"><b>Page 1 of 5</b></div>

<div class="page-break"></div>

<h5><strong>Shipping Billing:</strong></h5>
<div class="row">
  	<div class="col-md-12">
    <table  class="table table-bordered" id="dynamic_field_shipping">
    <thead>
      <tr>        
      <th width="10%">Date</th>
      <th width="20%">Purpose</th>
      <th width="20%">Amount</th>
      <th width="10%">Quantity</th>
      <th width="35%">Total</th>
      </tr>
    </thead>
    <tbody>
        @if( isset($sale) )
          @forelse( $sale->shippingbills as $bill )
            @include('includes.tables.sales-row-shipping-bill', [
                'invoice' => true,
                'row_id' => 1,
                'shipping_bill_id' => $bill->id,
                'datetime' => Carbon\Carbon::parse($bill->created_at)->format('m/d/Y h:m'),
                'shipping_purpose' => $bill->purpose,
                'bill_amount' => $bill->amount,
                'bill_quantity' => $bill->quantity ])
          @empty
            @component('includes.tables.empty-table-message', [ 'colspan' => 5, 'level' => 'info', 'message' => $messages['empty_table']['product_shipping'] ])
                <div class="alert alert-warning">No records added yet</div>
            @endcomponent
          @endforelse
        @else
            @component('includes.tables.empty-table-message', [ 'colspan' => 5, 'level' => 'info', 'message' => $messages['empty_table']['product_shipping'] ])
                <div class="alert alert-warning">No records added yet</div>
            @endcomponent
        @endif
    </tbody>
  </table>
  </div>
</div>

<div class="row">
  	<div class="col-md-12">
	<table class="table table-bordered">
	<tbody>
	  <tr>
	    <td width="60%"><strong><i>Bill Amount:</i></strong></td>
	    <td width="40%"><strong><i>
	    <span id="grandTotal">{{ (isset($sale) ? $sale->getBillAmountDecimalFormat() : number_format(0.00, 2)) }}</span>
	    {{ ' ' . MarketPlex\Store::currencyText() }}</i></strong></td>
	  </tr>
	</tbody>
	</table>
  </div>
</div>

<div class="text-center"><b>Page 2 of 5</b></div>

<div class="page-break"></div>

<h5><strong>Payment:</strong></h5>
<div class="row">
  	<div class="col-md-12">
    <table  class="table table-bordered" id="dynamic_field_pay">
    <thead>
      <tr>
      <th width="10%">Date</th>       
      <th width="50%">Method</th>
      <th width="35%">Paid Amount</th>
      </tr>
    </thead>
    <tbody>
        @if( isset($sale) )
          @forelse( $sale->billpayments as $payment )
            @include('includes.tables.sales-row-paid-bill', [
                'invoice' => true,
                'row_id' => 1,
                'paid_bill_id' => $payment->id,
                'datetime' => Carbon\Carbon::parse($payment->created_at)->format('m/d/Y h:m'),
                'paid_amount' => $payment->amount,
                'trans_option' => $payment->method ])
          @empty
            @component('includes.tables.empty-table-message', [ 'colspan' => 6, 'level' => 'info', 'message' => $messages['empty_table']['bill_payment'] ])
                <div class="alert alert-warning">No records added yet</div>
            @endcomponent
          @endforelse
        @else
            @component('includes.tables.empty-table-message', [ 'colspan' => 6, 'level' => 'info', 'message' => $messages['empty_table']['bill_payment'] ])
                <div class="alert alert-warning">No records added yet</div>
            @endcomponent
        @endif
    </tbody>
    </table>
  </div>
</div>

<h5><strong>Dues:</strong></h5>
<table class="table table-bordered">
<tbody>
  <tr>
    <td width="60%"><strong><i>Current Due:</i></strong></td>
    <td width="40%"><strong>
    <i id="current_due" class="decimal">{{ isset($sale) ? $sale->getCurrentDueAmountDecimalFormat() : number_format(0.00, 2) }}</i>
    {{' ' . MarketPlex\Store::currencyText() }}</strong></td>
  </tr>
  <tr>
    <td width="60%"><strong><i>Previous Due:</i></strong></td>
    <td width="40%"><strong><i id="prev_due" class="decimal">{{ isset($sale) ? $sale->getPreviousDueAmountDecimalFormat() : number_format(0.00, 2) }}</i>
    {{' ' . MarketPlex\Store::currencyText() }}</i></strong></td>
  </tr>
  <tr>
    <td width="60%"><strong><i>Total Due ( {{ isset($sale) ? $sale->client_name : 'This Client' }}):</i></strong></td>
    <td width="40%"><strong><i id="total_due" class="decimal">{{ isset($sale) ? $sale->getTotalDueAmountDecimalFormat() : number_format(0.00, 2) }}</i>
    {{' ' . MarketPlex\Store::currencyText() }}</strong></td>
  </tr>
</tbody>
</table>

<div class="text-center"><b>Page 3 of 5</b></div>