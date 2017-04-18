<h5><strong>Product Spec</strong></h5>        
<p class="slidePara1">
  <table id="spac_table" class="table table-hover table-condensed table-bordered text-center spec-table">
      <thead>
      <tr>
          <th>Specification</th>
          <th>Conditions</th>
      </tr>
      </thead>
      <tbody><!-- $product->specialSpecs() -->

        @forelse($product->specialSpecs() as $spec_title => $properties)
            @include('includes.product-special-specs', [ 'key' => $spec_title, 'properties' => $properties, 'is_operational_view' => false ])
        @empty
            @include('includes.product-specs-empty')
        @endforelse

      </tbody>
  </table>
</p>