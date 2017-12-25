<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="A dashboard template that will keep you updated all information user friendly way.">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <title>{{ config('app.vendor', 'Laravel') }} | @yield('title')</title>

    <!-- Add to homescreen for Chrome on Android -->
    <meta name="mobile-web-app-capable" content="yes">
    <link rel="icon" sizes="192x192" href="/vendor/images/android-desktop.png">

    <!-- Add to homescreen for Safari on iOS -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="Dashboard">
    <link rel="apple-touch-icon-precomposed" href="/vendor/images/ios-desktop.png">

    <!-- Tile icon for Win8 (144x144 + tile color) -->
    <meta name="msapplication-TileImage" content="/vendor/images/touch/ms-touch-icon-144x144-precomposed.png">
    <meta name="msapplication-TileColor" content="#3372DF">

    <link rel="shortcut icon" href="/vendor/images/favicon.png">

    <!-- SEO: If your mobile URL is different from the desktop URL, add a canonical link to the desktop page https://developers.google.com/webmasters/smartphone-sites/feature-phones -->
    <!--
    <link rel="canonical" href="http://www.example.com/">
    -->

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.cyan-light_blue.min.css">
    <link rel="stylesheet" href="/vendor/styles.css">

    <style>
    #view-source {
      position: fixed;
      display: block;
      right: 0;
      bottom: 0;
      margin-right: 40px;
      margin-bottom: 40px;
      z-index: 900;
    }
    
    .block-title{
      padding: 0em 0.7em;
    }
    .margin-choose-file-button{
      margin: 1em;
    }

    /*Small screen*/
    @media screen and (max-width: 750px){
        
        body{
             width: fit-content;
        }
        
    }

    /*Medium screen*/
    @media screen and (min-width: 992px) {
       /*.card{*/
       /*     background: red;*/
       /*  }*/

    }

    /*Large screen*/
    @media screen and (min-width: 1200px) {
         /*.card{*/
         /*   background: red;*/
         /*}      */

    }
    
    </style>

    @include('includes.styles.inzaana')
    @yield('header-style')
  </head>
  <body>
    <div class="demo-layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
      <header class="demo-header mdl-layout__header mdl-color--grey-100 mdl-color-text--grey-600">
        <div class="mdl-layout__header-row">
          <span class="mdl-layout-title">@yield('title-module-name')</span>
          <div class="mdl-layout-spacer"></div>
          <div class="mdl-textfield mdl-js-textfield mdl-textfield--expandable">
            <label class="mdl-button mdl-js-button mdl-button--icon" for="search">
              <i class="material-icons">search</i>
            </label>
            <div class="mdl-textfield__expandable-holder">
              <input class="mdl-textfield__input" type="text" id="search">
              <label class="mdl-textfield__label" for="search">Enter your query...</label>
            </div>
          </div>
          @include('includes.menus.title-bar-menu', [ 'activities' => MarketPlex\Helpers\AccountProfileManager::getActivities() ])
        </div>
      </header>
      @include('includes.drawer',
      [
        'activities' => MarketPlex\Helpers\AccountProfileManager::getActivities(),
        'modules' => MarketPlex\Helpers\ModuleManager::getModules()
      ])
      <main class="mdl-layout__content mdl-color--grey-100">
        @include('flash')
        @yield('content')
      </main>
    </div>
    @yield('modals')
    @include('icons.graphs-svg-demo')
    <!-- <a href="https://github.com/google/material-design-lite/blob/mdl-1.x/templates/dashboard/" target="_blank" id="view-source" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--colored mdl-color-text--white">View Source</a> -->

    @include('includes.scripts.inzaana')
    @yield('footer-scripts')
    <script src="https://code.getmdl.io/1.3.0/material.min.js"></script>

    <!-- <script src="{{ asset('/vendor/inzaana/browser-events/browser-event-navigation.js') }}" type="text/javascript"></script> -->
    <script type="text/javascript">
          
      // $(auth).ready(function () {

      //     auth.logout = function() {
      //       window.location.href = '/logout';
      //     }

      // });
    </script>
  
  </body>
</html>
