<h4>Categories:</h4>
<br>
<div class="list-group">
    @foreach($categories as $id => $name)            
        <a href="{{ route('store-front.categories.filter', [$id]) }}" class="list-group-item {{ $id == $active_category ? 'active': '' }}">{{ $name }}</a>  
    @endforeach
</div>