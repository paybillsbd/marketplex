@isset($errors)
@if(count($errors) > 0)

    <div class="alert alert-danger">
        
        <ul>
            @foreach($errors->all() as $error)
            
                <li>{{ $error }}</li>
        
            @endforeach
        </ul>
        
    </div>
    
@endif
@endisset

<div class="flash-message wow fadeIn" data-wow-delay="0.2s">
  @foreach (['danger', 'warning', 'success', 'info'] as $msg)
    @if(Session::has('alert-' . $msg))
        <div class="alert alert-{{ $msg }} alert-dismissible fade show">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <center><strong>{{ Session::get('alert-' . $msg) }}</strong></center>
        </div>       
    @endif
  @endforeach
</div>
    
