
<!--Carousel Wrapper-->
<div id="carousel-example-1z" class="carousel slide carousel-fade" {{ count($categories) > 0 ? 'data-ride="carousel"' : '' }}>
    <!--Indicators-->
    <ol class="carousel-indicators">
        @for ($i = 0; $i < count($categories); $i++)
            <li data-target="#carousel-example-1z" data-slide-to="{{ $i }}" class="{{ $i == 0 ? 'active': '' }}"></li>
        @endfor    
    </ol>
    <!--/.Indicators-->
    <!--Slides-->
    <div class="carousel-inner" role="listbox">
        @foreach($categories as $key => $item) 
        <div class="carousel-item {{ $key == MarketPlex\Category::first()->id ? 'active' : '' }}">
            <img src="{{ MarketPlex\Category::find($key)->imageWhatsNew() }}" class="img-responsive carousel-img" alt="slide {{ $key }}">
            <div class="carousel-caption">
                <h4>{{ MarketPlex\Category::find($key)->name }}</h4>
                <br>
            </div>
        </div>
        @endforeach
    </div>
    <!--/.Slides-->
    <!--Controls-->
    <a class="carousel-control-prev" href="#carousel-example-1z" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carousel-example-1z" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
    <!--/.Controls-->
</div>
<!--/.Carousel Wrapper-->
