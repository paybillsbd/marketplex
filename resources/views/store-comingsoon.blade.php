<!DOCTYPE html>
<html>
    <head>
        <title> {{ config('app.vendor') }} | Coming soon</title>
        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
        <link href="/bower_components/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css">
        <link href="/bower_components/bootstrap/dist/css/bootstrap.css" rel="stylesheet" type="text/css">
        <link href="/bower_components/bootstrap/dist/css/bootstrap-theme.css" rel="stylesheet" type="text/css">
        <link href="/bower_components/bootstrap-social/bootstrap-social.css" rel="stylesheet" type="text/css">
        <link href="/bower_components/bootstrap-social/bootstrap-social.css" rel="stylesheet" type="text/css">
        <link href="/css/content-clearance.css" rel="stylesheet" type="text/css">

        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                display: table;
                font-weight: 100;
                font-family: 'Lato';
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-size: 72px;
                margin-bottom: 40px;
                color: #B0BEC5;
                font-weight: bold;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="title">Store coming soon!</div>
                @if( env('STORE_SOCIAL_URL_SHOW', false) === true )
                    @include('includes.frontend.social-media-url')
                @endif

                <div class="vert-clearance"></div>

                @include('includes.frontend.company-portfolio-referral', [
                    'is_forced_aligned_center' => true
                ])
            </div>
        </div>
        <script src="/bower_components/jquery/dist/jquery.js" type="text/javascript"></script>
        <script src="/bower_components/bootstrap/dist/js/bootstrap.js" type="text/javascript"></script>
        <script src="/js/social-media-url.js" type="text/javascript"></script>
    </body>
</html>
