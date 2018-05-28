<table  class="table table-bordered"
        data-empty-message="{{ $messages['empty_table']['product_shipment'] }}">
<thead>
  <tr>
  <th width="8%">Date</th>       
  <th width="25%">Title</th>
  <th width="25%">Supplier/ Vendor</th>
  <th width="12%">Item Count</th>
  <th width="10%">Store Price</th>
  <th width="20%">Actions</th>
  </tr>
</thead>
<tbody>
    @forelse( (isset($shipments) ? $shipments : []) as $shipment )
    <tr>
      <td>{{ $shipment->created_at->format('Y-m-d') }}</td>
      <td>{{ $shipment->title }}</td>
      <td>{{ $shipment->supplier }}</td>
      <td>
      <i class="fa fa-lg fa-{{ $shipment->store_unit == 'CHECKED_IN' ? 'plus' : 'minus' }}-square"></i>
      {{ $shipment->getItemCountFormat() . $shipment->getUnit() }}
      </td>
      <td>{{ $shipment->getItemTotalPriceFormat() }}</td>
      <td>
        <div class="row">
          <div class="col-md-3">
          <a  href="#" class="remove-shipment" data-shipment="{{ $shipment->id }}" 
              data-toggle="tooltip" data-placement="top" title="Remove {{ $shipment->title }}">
            <span aria-hidden="true">
            <i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">delete</i></span>
          </a>
          </div>
        </div>
      </td>
    </tr>
    @empty
      @component('includes.tables.empty-table-message', [ 'colspan' => 6, 'level' => 'info', 'message' => $messages['empty_table']['product_shipment'] ])
          <div class="alert alert-warning">No records added yet</div>
      @endcomponent
    @endforelse
</tbody>
</table>
@if (!empty($shipments))
{{ $shipments->appends($paginator_appends)->links() }}
@endif