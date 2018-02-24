
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

i {
	text-align: right;
}

</style>
</head>

<h1 class="text-center">Sales Invoice</h1>

<h3 class="text-center"><label for="client"><strong>Business/Client/Company Name:</strong>{{ ' ' . $sale->client_name }}</label></h3>

<table width="100%">
  <tr>
    <td width="20%" style="text-align: right;">Date & Time:</td>
    <td width="80%" style="text-align: left;">{{ isset($sale) ? $sale->created_at : 'Unknown' }}</td>
  </tr>
  <tr>
    <td width="20%" style="text-align: right;">Bill ID:</td>
    <td width="80%" style="text-align: left;">{{ isset($sale) ? $sale->bill_id : 'Unknown' }}</td>
  </tr>
<tbody>
</tbody>
</table>

<h4><strong>Billing</strong></h4>

<h5><strong>Product Billing:</strong></h5>

@if( isset($sale) )

  @for ($i = 1; $i <= ($sale->productbills->count() < $per_page_max_record_count ? 1 : $sale->productbills->count() / $per_page_max_record_count); $i++ )
    <table  class="table table-bordered" id="product_bill_table" width="100%">
    <thead>
      <tr>
      <th width="10%">Date</th>
      <th width="25%">Title</th>
      <th width="20%">Store</th>
      <th width="10%">Quantity</th>
      <th width="35%">Total</th>
      </tr>
    </thead>
    <tbody>
          @forelse( $sale->productbills->forPage($i, $per_page_max_record_count) as $bill )

            @include('includes.tables.sales-row-product-bill', [
            	  'invoice' => true,
                'row_id' => 1,
                'product_bill_id' => $bill->id,
                'product_id' => $bill->product_id,
                'datetime' => Carbon\Carbon::parse($bill->created_at)->format('m/d/Y h:m'),
                'product_title' => $bill->product ? $bill->product->title : 'Unknown',
                'store_name' => $bill->product ? $bill->product->store->name : 'Unknown',
                'bill_price' => $bill->product ? $bill->product->mrp : 0.00,
                'bill_quantity' => $bill->quantity,
                'product_available_quantity' => $bill->product ? $bill->product->available_quantity : 0
            ])
          @empty
            @component('includes.tables.empty-table-message', [ 'colspan' => 5, 'level' => 'info', 'message' => $messages['empty_table']['sale_product'] ])
                <div class="alert alert-warning">No records added yet</div>
            @endcomponent
          @endforelse
    </tbody>
    </table>

    @if ( $sale->productbills->forPage($i, $per_page_max_record_count)->count() == $per_page_max_record_count )

      @if ( $page_count_enabled )
      <div style="text-align: center;"><b>Page {{ $page_count++ }} of {{ $total_page_count }}</b></div>
      @endif

    <div class="page-break"></div>

    @endif

  @endfor
@else
<table  class="table table-bordered" id="product_bill_table" width="100%">
<thead>
  <tr>
  <th width="10%">Date</th>
  <th width="25%">Title</th>
  <th width="20%">Store</th>
  <th width="10%">Quantity</th>
  <th width="35%">Total</th>
  </tr>
</thead>
<tbody>
    @component('includes.tables.empty-table-message', [ 'colspan' => 5, 'level' => 'info', 'message' => $messages['empty_table']['sale_product'] ])
        <div class="alert alert-warning">No records added yet</div>
    @endcomponent
</tbody>
</table>
@endif

<h5><strong>Shipping Billing:</strong></h5>

@if( isset($sale) )
  
  @for ($i = 1; $i <= ($sale->shippingbills->count() < $per_page_max_record_count ? 1 : ceil($sale->shippingbills->count() / $per_page_max_record_count)); $i++ )
    <table class="table table-bordered" id="dynamic_field_shipping" width="100%">
    <thead>
      <tr>        
      <th width="10%">Date</th>
      <th width="25%">Purpose</th>
      <th width="20%">Amount</th>
      <th width="10%">Quantity</th>
      <th width="35%">Total</th>
      </tr>
    </thead>
    <tbody>
          @forelse( $sale->shippingbills->forPage($i, $per_page_max_record_count) as $bill )

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
    </tbody>
    </table>

    @if ( $sale->shippingbills->forPage($i, $per_page_max_record_count)->count() == $per_page_max_record_count )

      @if ( $page_count_enabled )
      <div style="text-align: center;"><b>Page {{ $page_count++ }} of {{ $total_page_count }}</b></div>
      @endif

    <div class="page-break"></div>

    @endif

  @endfor
@else
  <table class="table table-bordered" id="dynamic_field_shipping" width="100%">
  <thead>
    <tr>        
    <th width="10%">Date</th>
    <th width="25%">Purpose</th>
    <th width="20%">Amount</th>
    <th width="10%">Quantity</th>
    <th width="35%">Total</th>
    </tr>
  </thead>
  <tbody>
      @component('includes.tables.empty-table-message', [ 'colspan' => 5, 'level' => 'info', 'message' => $messages['empty_table']['product_shipping'] ])
          <div class="alert alert-warning">Nothing shipped</div>
      @endcomponent
  </tbody>
  </table>
@endif

<table class="table table-bordered" width="100%">
<tbody>
  <tr>
    <td width="65%" style="text-align: right;"><strong><i>Bill Amount:</i></strong></td>
    <td width="35%" style="text-align: right;"><strong><i>
    <span id="grandTotal">{{ (isset($sale) ? $sale->getBillAmountDecimalFormat() : number_format(0.00, 2)) }}</span>
    {{ ' ' . MarketPlex\Store::currencyText() }}</i></strong></td>
  </tr>
</tbody>
</table>

@if ( ($sale->productbills->count() + $sale->shippingbills->count() == $per_page_max_record_count) )

  @if ( $page_count_enabled )
  <div style="text-align: center;"><b>Page {{ $page_count++ }} of {{ $total_page_count }}</b></div>
  @endif

<div class="page-break"></div>

@endif

<h5><strong>Payment:</strong></h5>

@if( isset($sale) )
  
  @for ($i = 1; $i <= ($sale->billpayments->count() < $per_page_max_record_count ? 1 : $sale->billpayments->count() / $per_page_max_record_count); $i++ )
    <table  class="table table-bordered" id="dynamic_field_pay" width="100%">
    <thead>
      <tr>
      <th width="10%">Date</th>       
      <th width="55%">Method</th>
      <th width="35%">Paid Amount</th>
      </tr>
    </thead>
    <tbody>
          @forelse( $sale->billpayments->forPage($i, $per_page_max_record_count) as $payment )

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
    </tbody>
    </table>

    @if ( $sale->billpayments->forPage($i, $per_page_max_record_count)->count() == $per_page_max_record_count
          || ($sale->getInvoiceRecordsCount() == $per_page_max_record_count) )

      @if ( $page_count_enabled )
      <div style="text-align: center;"><b>Page {{ $page_count++ }} of {{ $total_page_count }}</b></div>
      @endif

    <div class="page-break"></div>

    @endif

  @endfor
@else
  <table  class="table table-bordered" id="dynamic_field_pay" width="100%">
  <thead>
    <tr>
    <th width="10%">Date</th>       
    <th width="55%">Method</th>
    <th width="35%">Paid Amount</th>
    </tr>
  </thead>
  <tbody>
      @component('includes.tables.empty-table-message', [ 'colspan' => 6, 'level' => 'info', 'message' => $messages['empty_table']['bill_payment'] ])
          <div class="alert alert-warning">Nothing paid</div>
      @endcomponent
  </tbody>
  </table>
@endif

<h5><strong>Dues:</strong></h5>
<table class="table table-bordered" width="100%">
<tbody>
  <tr>
    <td width="60%" style="text-align: right;"><strong><i>Current Due:</i></strong></td>
    <td width="35%" style="text-align: right;"><strong>
    <i id="current_due" class="decimal">{{ isset($sale) ? $sale->getCurrentDueAmountDecimalFormat() : number_format(0.00, 2) }}</i>
    {{' ' . MarketPlex\Store::currencyText() }}</strong></td>
  </tr>
  <tr>
    <td width="60%" style="text-align: right;"><strong><i>Previous Due:</i></strong></td>
    <td width="35%" style="text-align: right;"><strong><i id="prev_due" class="decimal">{{ isset($sale) ? $sale->getPreviousDueAmountDecimalFormat() : number_format(0.00, 2) }}</i>
    {{' ' . MarketPlex\Store::currencyText() }}</i></strong></td>
  </tr>
  <tr>
    <td width="60%" style="text-align: right;"><strong><i>Total Due ( {{ isset($sale) ? $sale->client_name : 'This Client' }}):</i></strong></td>
    <td width="35%" style="text-align: right;"><strong><i id="total_due" class="decimal">{{ isset($sale) ? $sale->getTotalDueAmountDecimalFormat() : number_format(0.00, 2) }}</i>
    {{' ' . MarketPlex\Store::currencyText() }}</strong></td>
  </tr>
</tbody>
</table>

@if ( $page_count == $total_page_count )

  @if ( $page_count_enabled )
  <div style="text-align: center;"><b>Page {{ $page_count++ }} of {{ $total_page_count }}</b></div>
  @endif

@endif