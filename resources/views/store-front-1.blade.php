@extends('layouts.app-store-front')
@section('title', 'Store')
@section('title-module-name', 'Store')

@section('content')
<div class="row">

    <!--Sidebar-->
    <div class="col-lg-4 wow fadeIn" data-wow-delay="0.2s">

        <div class="widget-wrapper">
            @include('includes.frontend.categories')
        </div>

        <div class="widget-wrapper wow fadeIn" data-wow-delay="0.4s">
            @include('includes.frontend.subscription')
        </div>

    </div>
    <!--/.Sidebar-->

    <!--Main column-->
    <div class="col-lg-8">

        <!--First row-->
        <div class="row wow fadeIn" data-wow-delay="0.4s">
            <div class="col-lg-12">
                <div class="divider-new">
                    <h2 class="h2-responsive">What's new?</h2>
                </div>        
                
                @include('includes.frontend.carousel')
        
            </div>
        </div>
        <!--/.First row-->
        <br>
        <hr class="extra-margins">

        <!--Second row-->
        <div class="row">
            @include('includes.frontend.product')

            
        </div>
        <!--/.Second row-->

    </div>
    <!--/.Main column-->

</div>
@endsection