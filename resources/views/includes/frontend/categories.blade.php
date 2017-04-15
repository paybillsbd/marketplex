<h4>Categories:</h4>
<br>
<div class="list-group">
    @foreach($categories as $key=>$item)            
        <a href="#" class="list-group-item {{$key==2?'active':''}}">{{ $item }}</a>  
    @endforeach
</div>