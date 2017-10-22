@extends('layouts.app-store-front')
@section('title', 'About Us')
@section('title-module-name', 'About Us')

@section('content')
<div class="row">
    <div class="col-md-12 view hm-black-strong">
    
        <img style="width:100%; max-height: 450px!important;" src="{{'images/about.jpg'}}" class="figure-img img-fluid">
    </div>
</div>
<br>
<div class="row">
    <div class="col-md-5">

        <!--Card-->
        <div class="card">
            <!--Card content-->
            <div class="card-block">
                <!--Title-->
                <h5 class="card-title"><b>BUSINESS INFO</b></h5><hr>
                <!--Text-->
                <p class="card-text">Founded in March,2016</p>

                <h5 class="card-title"><b>CONTACT INFO</b></h5><hr>
                <!--Text-->
                <p class="card-text">
                   Call {{ env('VENDOR_CONTACT_NO') }} <br>
                   
                   {{ config('mail.admin.address') }} <br>
                   {{ config('app.url') }} <br> 
                </p>
                <a href="#" class="btn btn-primary">Message</a>
            </div>
            <!--/.Card content-->

        </div>
        <!--/.Card-->
    </div>
    <div class="col-md-7">
        
        <!--Card-->
        <div class="card">
            <!--Card content-->
            <div class="card-block">
                <!--Title-->
                <h5 class="card-title"><b>STORY</b></h5><hr>
                <!--Text-->
                <p class="card-text">
                    {{ config('app.vendor') }}(Foundation of Unity & Honesty Association of Bangladesh) is an organization which is working for the development of Bangladesh. Leather Industry, Jute products, Block n batik works in bed-sheets, hand loom sharee, boutiques, Nakshi Katha (designed hand made blanket) are some possible sectors in Bangladesh. The environment and culture of Bangladesh is very suitable for leather industry. {{ config('app.vendor') }} is trying to take full advantage of it . Bangladesh has great future in leather industry and {{ config('app.vendor') }} wants to be a part of it. Although these positive environment our workers in leather sector are not getting proper value of their products. {{ config('app.vendor') }} is trying to give them the better pricing by removing the middlemans in leather industries. On the other hand rural people of Bangladesh has great talent and creativity, but they are not getting the opportunities to show it and get proper facilities what they deserve. So this creative work is being under rated. {{ config('app.vendor') }} is giving opportunities to those rural people of Bangladesh to uphold their talent worldwide and get proper honor of their creative work. {{ config('app.vendor') }} is trying to support those deprived people by providing training, education and works. Not only giving the works but also trying to spread their works in worldwide and local market with great price. So that everybody related to this sector earn well, feed well and live well. We will not rest until making them happy. So join us, support us to remove poverty and ill paying.
                </p>

            </div>
            <!--/.Card content-->

        </div>
        <!--/.Card-->
    </div>
</div>
@endsection