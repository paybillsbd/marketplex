<h4>Categories:</h4>
<br>
<div class="list-group">
    @foreach($categories as $key => $category)            
        <a href="#" class="list-group-item {{ $key == 2 ? 'active': '' }}">{{ $category->name }}</a>  
    @endforeach
</div>