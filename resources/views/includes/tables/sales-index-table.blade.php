<table id="search-result-table" class="table">
    <thead>
      <tr>
        <th>Date</th>
        <th>Bill ID</th>
        <th>Client/Company</th>
        <th>Total Bill</th>
        <th>Due</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      @each('includes.tables.sales-row-search-result-2', $sales, 'sale', 'includes.tables.empty-sales-table-message') 
    </tbody>
</table>

@if (! $sales->IsEmpty())
    <ul class="pagination">
        {{-- Previous Page Link --}}
        @if ($page == 1)
            <li class="disabled"><span>@lang('pagination.previous')</span></li>
        @else
            <li><a href="#" id="prev-sale" rel="prev">@lang('pagination.previous')</a></li>
        @endif

        {{-- Next Page Link --}}
        @if (! $nextSales->IsEmpty())
            <li><a href="#" id="next-sale" rel="next">@lang('pagination.next')</a></li>
        @else
            <li class="disabled"><span>@lang('pagination.next')</span></li>
        @endif
    </ul>
@endif