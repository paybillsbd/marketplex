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
        <a  href="{{ route('user::sales.edit', [ 
                'sale' => is_string($sales) ? $sale->id : $sale,
                'api_token' => Auth::user()->api_token
            ]) }}"
            class="btn btn-info btn-flat btn-xs" role="button">Edit</a>
      </p>
    </div>
    <!--<a href="#" data-toggle="modal" data-target="" class="btn btn-danger btn-sm">Delete</a>-->
  </td>
</tr> 
@empty

    @component('includes.message.empty-table-message', [ 'colspan' => 6 ])
       <div class="alert alert-info text-center">No sales records found from your query ...</div>
    @endcomponent

@endforelse