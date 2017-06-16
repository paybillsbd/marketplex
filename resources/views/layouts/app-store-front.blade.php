<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>{{ config('app.vendor') }} | @yield('title')</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.0/css/font-awesome.min.css">

    <!-- Bootstrap core CSS -->
    <link href="/vendor/mdb/css/bootstrap.min.css" rel="stylesheet">

    <!-- Material Design Bootstrap -->
    <link href="/vendor/mdb/css/mdb.min.css" rel="stylesheet">    
    <link href="/vendor/mdb/css/style.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="{{ asset('/vendor/inzaana/css/css-m/main.css')}}" rel="stylesheet">
    
    @yield('header-styles')

    <!-- Template styles -->
    <style rel="stylesheet">
        /* TEMPLATE STYLES */
      
        main {
            padding-top: 3rem;
            padding-bottom: 2rem;
        }
        
        .img-responsive,
        .thumbnail > img,
        .thumbnail a > img,
        .carousel-inner > .carousel-item > img,
        .carousel-inner > .carousel-item > a > img {
          display: block;
          max-width: 100%;
          height: auto;
        }

        .carousel-img{
            max-height: 100%!important;
            width: 100%;
        }

        .card-img{
            height: 100%;  
            width: 100%!important;
        }

        .card .view{
            min-height: 280px!important;
        }
/*
        .item-card{
            min-height: 500px!important; 
                     
        }*/

        .carousel .carousel-inner .carousel-item{
            height: 580px!important;
        }     
        

        // Small screen
        @media (min-width: 768px) {
            
        }

        // Medium screen
        @media (min-width: 992px) {
           .card{
                background: red;
             }
        }

        // Large screen
        @media (min-width: 1200px) {
             .card{
                background: red;
             }      
        }

        .widget-wrapper {
            padding-bottom: 2rem;
            border-bottom: 1px solid #e0e0e0;
            margin-bottom: 2rem;
        }
        
        .extra-margins {
            margin-top: 1rem;
            margin-bottom: 2.5rem;
        }
        
        .divider-new {
            margin-top: 0;
        }
        
          .navbar {
            background-color: #414a5c;
        }
        
        footer.page-footer {
            background-color: #414a5c;
            margin-top: 2rem;
        }
    </style>

</head>

<body>

    @include('includes.navbar')

    <main>

        <!--Main layout-->
        <div class="container">
            @include('flash')
            @yield('content')
        </div>
        <!--/.Main layout-->

    </main>

    <!--Footer-->
    <footer class="page-footer center-on-small-only">

        {{-- @include('includes.store-front-footer-detail') --}}

        <!--Copyright-->
        <div class="footer-copyright">
            <div class="container-fluid">
                © 2017 Copyright: <a href="http://portfolio.asdtechltd.com" target="_blank"> Alternative System & Development Technology Ltd. (ASDTechLtd.) </a>
            </div>
        </div>
        <!--/.Copyright-->

    </footer>
    <!--/.Footer-->
    
    @yield('modals')


    <!-- SCRIPTS -->

    <!-- JQuery -->
    <script type="text/javascript" src="/vendor/mdb/js/jquery-2.2.3.min.js"></script>

    <!-- Bootstrap tooltips -->
    <script type="text/javascript" src="/vendor/mdb/js/tether.min.js"></script>

    <!-- Bootstrap core JavaScript -->
    <script type="text/javascript" src="/vendor/mdb/js/bootstrap.min.js"></script>

    <!-- MDB core JavaScript -->
    <script type="text/javascript" src="/vendor/mdb/js/mdb.min.js"></script>

    @yield('footer-scripts')
    
    <script>
    new WOW().init();
    </script>
    
</body>

</html>