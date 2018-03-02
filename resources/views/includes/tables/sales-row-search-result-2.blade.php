<tr>
  <td>{{ $sale->created_at }}</td>
  <td>{{ $sale->bill_id }}</td>
  <td>{{ $sale->client_name  }}</td>
  <td><strong><i>
  {{ $sale->getBillAmountDecimalFormat() . ' ' . MarketPlex\Store::currencyIcon() }}
  </i></strong></td>
  <td><strong><i>
  {{ $sale->getCurrentDueAmountDecimalFormat() . ' ' . MarketPlex\Store::currencyIcon() }}
  </i></strong></td>
  <td>
    <div class="clearfix">
		  <p class="text-left">
        <div class="row">
          <div class="col-md-3"> 
            <a  href="{{ route('user::sales.edit', [ 
                    'sale' => $sale,
                    'api_token' => Auth::user()->api_token
                ]) }}" class="btn btn-info btn-flat btn-xs" role="button">Edit</a>
          </div>
          <div class="col-md-4"> 
            <a  href="/api/v1/sales/{{ $sale->id }}/invoice?download=0&api_token={{ Auth::user()->api_token }}"
                class="show-invoice btn btn-info btn-flat btn-xs"
                data-sale="{{ $sale->id }}"
                role="button">Invoice</a>
          </div>
        </div>
      </p>
    </div>
    <!--<a href="#" data-toggle="modal" data-target="" class="btn btn-danger btn-sm">Delete</a>-->
  </td>
</tr> 