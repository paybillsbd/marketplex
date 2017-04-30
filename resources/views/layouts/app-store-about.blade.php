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

    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

    <!-- Template styles -->
    <style rel="stylesheet">
        /* TEMPLATE STYLES */
      
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

    @include('includes.navbar');

    <main>

        <!--Main layout-->
        <div class="container">
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
                               Call 01708-529870 <br>
                               
                               fuhabd@gmail.com <br>
                               http://www.fuhabd.com <br> 
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
                                FUHA BD (Foundation of Unity & Honesty Association of Bangladesh) is an organization which is working for the development of Bangladesh. Leather Industry, Jute products, Block n batik works in bed-sheets, hand loom sharee, boutiques, Nakshi Katha (designed hand made blanket) are some possible sectors in Bangladesh. The environment and culture of Bangladesh is very suitable for leather industry. FUHA is trying to take full advantage of it . Bangladesh has great future in leather industry and FUHA BD wants to be a part of it. Although these positive environment our workers in leather sector are not getting proper value of their products. FUHA BD is trying to give them the better pricing by removing the middlemans in leather industries. On the other hand rural people of Bangladesh has great talent and creativity, but they are not getting the opportunities to show it and get proper facilities what they deserve. So this creative work is being under rated. FUHA BD is giving opportunities to those rural people of Bangladesh to uphold their talent worldwide and get proper honor of their creative work. FUHA BD is trying to support those deprived people by providing training, education and works. Not only giving the works but also trying to spread their works in worldwide and local market with great price. So that everybody related to this sector earn well, feed well and live well. We will not rest until making them happy. So join us, support us to remove poverty and ill paying.
                            </p>

                        </div>
                        <!--/.Card content-->

                    </div>
                    <!--/.Card-->
                </div>
            </div>
        </div>
        <!--/.Main layout-->

    </main>

    <!--Footer-->
    <footer class="page-footer center-on-small-only">

        {{-- @include('includes.store-front-footer-detail') --}}

        <!--Copyright-->
        <div class="footer-copyright">
            <div class="container-fluid">
                © 2017 Copyright: <a href="http://paybillsbd.asdtechltd.com"> Alternative System & Development Technology Ltd. (ASDTechLtd.) </a>
            </div>
        </div>
        <!--/.Copyright-->

    </footer>
    <!--/.Footer-->


    <!-- SCRIPTS -->

    <!-- JQuery -->
    <script type="text/javascript" src="/vendor/mdb/js/jquery-2.2.3.min.js"></script>

    <!-- Bootstrap tooltips -->
    <script type="text/javascript" src="/vendor/mdb/js/tether.min.js"></script>

    <!-- Bootstrap core JavaScript -->
    <script type="text/javascript" src="/vendor/mdb/js/bootstrap.min.js"></script>

    <!-- MDB core JavaScript -->
    <script type="text/javascript" src="/vendor/mdb/js/mdb.min.js"></script>
    
    <script>
    new WOW().init();
    </script>
    
</body>

</html>