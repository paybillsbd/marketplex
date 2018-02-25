@extends('layouts.app')
@section('title', 'Demo')
@section('title-module-name', 'Demo')

@section('header-styles')

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<style type="text/css">
  

      .ui-autocomplete {
          max-height: 100px;
          overflow-y: auto;
          /* prevent horizontal scrollbar */
          overflow-x: hidden;
      }

      /* IE 6 doesn't support max-height
       * we use height instead, but this forces the menu to always be this tall
       */
      * html .ui-autocomplete {
          height: 100px;
      }
      .ui-autocomplete-loading {
          background: white url('http://loading.io/loader/?use=eyJzaXplIjo4LCJzcGVlZCI6MSwiY2JrIjoiI2ZmZmZmZiIsImMxIjoiIzAwYjJmZiIsImMyIjoiMTIiLCJjMyI6IjciLCJjNCI6IjIwIiwiYzUiOiI1IiwiYzYiOiIzMCIsInR5cGUiOiJkZWZhdWx0In0=') right center no-repeat;
      }

</style>

@endsection

@section('footer-scripts')
    <script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>
    <script type="text/javascript">
    $( function() {
        var availableTags = [
          "ActionScript",
          "AppleScript",
          "Asp",
          "BASIC",
          "C",
          "C++",
          "Clojure",
          "COBOL",
          "ColdFusion",
          "Erlang",
          "Fortran",
          "Groovy",
          "Haskell",
          "Java",
          "JavaScript",
          "Lisp",
          "Perl",
          "PHP",
          "Python",
          "Ruby",
          "Scala",
          "Scheme"
        ];

        // '/api/v1/settings/clients?api_token=A6hT4W1G3HZeZXmuV5mz8Fcf4iCLLPFvKhMSD0afwuZfFycB9tb4jEkI2cCZ'
        $( "#tags" ).autocomplete({
          source: "{{ route('user::clients.index', [ 'api_token' => Auth::user()->api_token ]) }}", // or, availableTags = [];
          minLength: 2,
          select: function( event, ui ) {
            alert( "Selected: " + ui.item.value + " aka " + ui.item.label );
          }
        });
      } );
    </script>

@endsection

@section('content')
<label for="tags">Tags: </label>
  <input id="tags">
@endsection