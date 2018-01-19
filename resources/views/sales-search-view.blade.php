@extends('layouts.app-dashboard-admin')
@section('title', 'Sales Search')
@section('title-module-name', 'Sales Search')

@section('header-styles')

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <!-- Bootstrap Date-Picker Plugin -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
    <style type="text/css">
      .card_top{
        margin-bottom:2em;
      }
      .card_result{
        display:none;
      }
      
      td, th {
        text-align: center;
        vertical-align: middle;
      }
    </style>
@endsection

@section('footer-scripts')

    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script src="/vendor/inzaana/js/product/product.js" type="text/javascript"></script>
    <!-- Bootstrap Date-Picker Plugin -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
    <script>

      var DataManager = {
          serviceUrl: '/',
          _onFail: function(jqXHR, textStatus, errorThrown) {
                if (jqXHR.status == 404)
                  return;
                var msg = '';
                if (jqXHR.status === 0) {
                    msg = 'Not connected.\n Verify Network.';
                } else if (jqXHR.status == 404) {
                    msg = 'Requested page not found. [404]';
                } else if (jqXHR.status == 401) {
                    msg = errorThrown + '. [' + jqXHR.status + ']';
                } else if (jqXHR.status == 500) {
                    msg = 'Internal Server Error [500].';
                } else if (textStatus === 'parsererror') {
                    msg = 'Requested JSON parse failed.';
                } else if (textStatus === 'timeout') {
                    msg = 'Time out error.';
                } else if (textStatus === 'abort') {
                    msg = 'Ajax request aborted.';
                } else {
                    msg = 'Uncaught Error: [' + jqXHR.status + '][ ' + textStatus + ' ][' + errorThrown + '].\n' + jqXHR.responseText;
                }                
                // Render the errors with js ...
                alert(msg);
          },
          onLoad: function(data) {},
          request: function(method, payload) {

              if (method.toString().toLowerCase() === 'post')
              {
                  $.post( this.serviceUrl, payload, this.onLoad, "json" ).fail(_onFail);
              }
              else if (method.toString().toLowerCase() === 'get')
              {
                  $.get( this.serviceUrl, payload, this.onLoad).fail(_onFail);
              }
          }
      };

      $(document).ready(function() {
          
          // #### instead using basic HTML5 input datetime-local
          // var date_input_from=$('input[name="from_date"]'); //our date input has the name "date"
          // var date_input_to=$('input[name="to_date"]'); //our date input has the name "date"
          // var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
          // var options={
          //   format: 'dd/mm/yyyy',
          //   container: container,
          //   todayHighlight: true,
          //   autoclose: true,
          // };
          // date_input_from.datepicker(options);
          // date_input_to.datepicker(options);

          $('#sales_today').click(function(e) {
              e.preventDefault();
              window.location.href = "{{ isset($route_query_today) ? $route_query_today : ''  }}";

          });
      
      })
    </script>
@endsection

@section('modals')
    <div id="modal_container">{{--Modal load here--}}</div>
@endsection

@section('content')
@include('includes.message.message')

<div class="box box-info">    
    <div class="box-body">
      <div class="row padTB"> 
          <div class="col-lg-6 col-lg-offset-3">
            <div class="box box-noborder">
              <!-- form start -->
              <form role="form"
                    method="post"
                    action="{{ route('user::sales.search', [ 'api_token' => Auth::user()->api_token ]) }}">

                {{ csrf_field() }}

                <div class="box-body">
                  <div class="row">
                    <div class="col-6">
                      <div class="form-group">
                        <label class="control-label" for="billing_id"><h5><strong>Billing ID</strong></h5></label>
                        <input  type="text"
                                id="queries.billing_id" name="queries[billing_id]"
                                class="form-control" placeholder="Billing ID"/>
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="form-group">
                        <label class="control-label" for="client_name"><h5><strong>Client Name</strong></h5></label>
                        <input  type="text"
                                id="queries.client_name" name="queries[client_name]"
                                class="form-control" placeholder="Client Name"/>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-6">
                      <div class="form-group"> <!-- Date input -->
                        <label class="control-label" for="from_date"><h5><strong>From</strong></h5></label>
                        <input  type="date"
                                class="form-control"
                                id="queries.from_date" name="queries[from_date]" placeholder="DD/MM/YYYY"/>
                      </div>                    
                    </div>
                    <div class="col-6">
                      <div class="form-group"> <!-- Date input -->
                        <label class="control-label" for="to_date"><h5><strong>To</strong></h5></label>
                        <input  type="date"
                                class="form-control"
                                id="queries.to_date" name="queries[to_date]" placeholder="DD/MM/YYYY"/>
                      </div>                    
                    </div>
                  </div>
                </div>  
                <!-- box-body -->

                <div class="box-footer text-right"> <!-- Submit button -->
                  <div class="row">
                    <div class="col-md-6"> </div>
                    <div class="col-md-2">
                          <a  href="" id="sales_today" name="sales_today" 
                              class="btn btn-info btn-flat btn-sm" role="button">Today's Sales</a>
                    </div>
                    <div class="col-md-2"> 
                          <a  href="{{ route('user::sales.create', [ 'api_token' => Auth::user()->api_token ]) }}"
                              class="btn btn-info btn-flat btn-sm" role="button">New Sale</a>
                    </div>
                    <div class="col-md-2">
                      <button class="btn btn-info btn-flat btn-sm" id="search_sales" name="search_sales" type="submit">Search Sales</button>
                    </div>                    
                </div>
              </form>  
              <!--end of form-->

            </div>
          </div>
    </div>
</div>

  <!--Search Results-->
    <div class="row">

          <div class="col-xs-12">

            <div class="box">
              <div class="box-header">
                <h3 class="box-title">Sales search results</h3>
              </div><!-- /.box-header -->
              <div class="box-body table-responsive no-padding">

              <table class="table">
                  <thead>
                    <tr>
                      <th>Date</th>
                      <th>Bill ID</th>
                      <th>Client/Company</th>
                      <th>Total Bill</th>
                      <th>Total Due</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach( $sales as $sale )
                    <tr>
                      <td>{{ $sale->created_at }}</td>
                      <td>{{ $sale->bill_id }}</td>
                      <td>{{ $sale->client_name  }}</td>
                      <td><strong><i>{{ $sale->getBillAmountDecimalFormat() . ' ' . MarketPlex\Store::currencyIcon() }}</i></strong></td>
                      <td><strong><i>{{ $sale->getCurrentDueAmountDecimalFormat() . ' ' . MarketPlex\Store::currencyIcon() }}</i></strong></td>
                      <td>
                        <div class="clearfix">
                  			  <p class="text-left">  
                            <a  href="{{ route('user::sales.edit', [ 'sale' => $sale, 'api_token' => Auth::user()->api_token ]) }}"
                                class="btn btn-info btn-flat btn-xs" role="button">Edit</a>
                          </p>
                        </div>
                        <!--<a href="#" data-toggle="modal" data-target="" class="btn btn-danger btn-sm">Delete</a>-->
                      </td>
                    </tr>  
                    @endforeach   
                  </tbody>
              </table>

            </div><!-- /.box-body -->
          </div><!-- /.box -->  
        </div>

      </div>
  
  
</div>

</div>

@endsection

