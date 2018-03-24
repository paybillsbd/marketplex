@forelse( (is_string($sales) ? json_decode($sales) : $sales) as $sale )
<tr>
  <td>{{ $sale->created_at }}</td>
  <td>{{ $sale->bill_id }}</td>
  <td>{{ $sale->client_name  }}</td>
  <td><strong><i>
  {{
      ( is_string($sales) ?
        MarketPlex\SaleTransaction::getBillAmountByBillId($sale->bill_id)
        : $sale->getBillAmountDecimalFormat()) . ' ' . MarketPlex\Store::currencyIcon()
  }}
  </i></strong></td>
  <td><strong><i>
  {{
      ( is_string($sales) ?
        MarketPlex\SaleTransaction::getCurrentDueAmountByBillId($sale->bill_id)
        : $sale->getCurrentDueAmountDecimalFormat()) . ' ' . MarketPlex\Store::currencyIcon()
  }}
  </i></strong></td>
  <td>
    <div class="clearfix">
		  <p class="text-left">
        <div class="row">
          <div class="col-md-3">                
            <a  href="{{ route('user::sales.edit', [ 
                    'sale' => is_string($sales) ? $sale->id : $sale,
                    'api_token' => Auth::user()->api_token
                ]) }}" role="button"
                data-toggle="tooltip" data-placement="top" title="Edit sale (last modified: {{ $sale->updated_at }} )">
              <span aria-hidden="true">
              <i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">mode_edit</i></span>
            </a>
          </div>
          <div class="col-md-4">              
            <a  href="/api/v1/sales/{{ $sale->id }}/invoice?download=0&api_token={{ Auth::user()->api_token }}"
                role="button" class="show-invoice" data-sale="{{ $sale->id }}"
                data-toggle="tooltip" data-placement="top" title="Invoice of this sale">
              <span aria-hidden="true">
              <i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">receipt</i></span>
            </a>
          </div>
        </div>
      </p>
    </div>
    <!--<a href="#" data-toggle="modal" data-target="" class="btn btn-danger btn-sm">Delete</a>-->
  </td>
</tr> 
@empty

    @include('includes.tables.empty-sales-table-message')

@endforelse