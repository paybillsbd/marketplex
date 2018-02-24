@if ( is_string($sales) )

	@if (! collect(json_decode($sales))->IsEmpty())
	    <ul class="pagination">
	        {{-- Previous Page Link --}}
	        @if ($paginator->onFirstPage())
	            <li class="disabled"><span>&laquo;</span></li>
	        @else
	            <li><a href="{{ collect(json_decode($sales))->forPage($result_page, 3) }}" rel="prev">&laquo;</a></li>
	        @endif

	        {{-- Next Page Link --}}
	        @if ($paginator->hasMorePages())
	            <li><a href="{{ $paginator->nextPageUrl() }}" rel="next">&raquo;</a></li>
	        @else
	            <li class="disabled"><span>&raquo;</span></li>
	        @endif
	    </ul>
	@endif

@else

{{ $sales->appends([ 'api_token' => Auth::user()->api_token ])->links() }}

@endif