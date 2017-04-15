<!--columnn-->
@foreach($products as $key=>$item)

<div class="col-lg-4">
    <!--Card-->
    <div class="card  wow fadeIn" data-wow-delay="0.2s">
     
        <!--Card image-->
        <div class="view overlay hm-white-slight">
            <img src="http://mdbootstrap.com/img/Photos/Horizontal/E-commerce/Products/img%20(32).jpg" class="img-fluid" alt="">
            <a href="#">
                <div class="mask"></div>
            </a>
        </div>
        <!--/.Card image-->

        <!--Card content-->
        <div class="card-block">
            <!--Title-->

            <h4 class="card-title">{{$item}}</h4>
            <!--Text-->
            <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. </p>
            <a href="#" class="btn btn-default">Buy now for <strong>10$</strong></a>
        </div>
        <!--/.Card content-->

    </div>
    <!--/.Card-->
</div>
@endforeach

<!--end columnn-->