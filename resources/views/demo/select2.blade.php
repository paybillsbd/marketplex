@extends('layouts.app-dashboard-admin')
@section('title', 'Demo')
@section('title-module-name', 'Demo')

@section('header-styles')
<!-- Styles -->
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<!-- Bootstrap -->
<link href="/vendor/dist/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="/vendor/dist/bootstrap/css/bootstrap-theme.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" />
<!-- 
<link href="/vendor/dist/select2-4.0.3/css/select2.css" rel="stylesheet">
<link href="/vendor/dist/select2-4.0.3/css/select2-bootstrap.css" rel="stylesheet"> -->

@endsection

@section('footer-scripts')
<script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>

<!-- Scripts -->
<script src="/vendor/dist/bootstrap/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>

<script type="text/javascript">

// // data format select2
// $options = [
//     [ 'id' => 1, 'text' => 'Client 1' ],
//     [ 'id' => 1, 'text' => 'Client 2' ],
//     [ 'id' => 1, 'text' => 'Client 3' ],
//     [ 'id' => 1, 'text' => 'Client 4' ],
// ];
// $data = [
//     'items' => $options,
//     'total_count' => count($options)
// ];
// return response()->json($data);
// 
// API: '/api/v1/settings/clients?api_token=A6hT4W1G3HZeZXmuV5mz8Fcf4iCLLPFvKhMSD0afwuZfFycB9tb4jEkI2cCZ'

$(".clients").select2({
    placeholder: 'I want to pay for ...',
    theme: "bootstrap",
    ajax: {
        delay: 250,
        allowClear: true,
        dataType: 'json',
        data: function (params) {
          var query = {
            search: params.term,
            page: params.page
          }

          // Query paramters will be ?search=[term]&page=[page]
          return query;
        },
        headers: {
            "Accept": "application/json"
        },
        processResults: function (data, params) {
            // parse the results into the format expected by Select2
            // since we are using custom formatting functions we do not need to
            // alter the remote JSON data, except to indicate that infinite
            // scrolling can be used
            params.page = params.page || 1;

            return {
              results: data.items,
              pagination: {
                more: (params.page * 10) < data.total_count

              }
            };
        },
    }

});

</script>

@endsection

@section('content')

  @include('includes.menus.selectbox-searchable-async-simple',
  [
  		'route' => route('user::clients.index', [ 'api_token' => Auth::user()->api_token ]),
  		'context' => 'clients'
  ])

@endsection