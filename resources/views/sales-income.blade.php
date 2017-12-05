@extends('layouts.app-store-front')
@section('title', 'Sales Income')
@section('title-module-name', 'Sales Income')

@section('header-styles')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <!-- Bootstrap Date-Picker Plugin -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
    <style type="text/css">
      .card_top{
        margin-bottom:2em;
      }
      .card_income_result{
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
      $(document).ready(function(){
        var date_input_from=$('input[name="from_date"]'); //our date input has the name "date"
        var date_input_to=$('input[name="to_date"]'); //our date input has the name "date"
        var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
        var options={
          format: 'mm/dd/yyyy',
          container: container,
          todayHighlight: true,
          autoclose: true,
        };
        date_input_from.datepicker(options);
        date_input_to.datepicker(options);
        
      $('#search_sales_income').click(function(e) {
      e.preventDefault();
        $('.card_income_result').show();
      });

      });
    </script>
@endsection

@section('modals')
    <div id="modal_container">{{--Modal load here--}}</div>
@endsection

@section('content')
@include('includes.message.message') 
<div class="wow fadeIn" data-wow-delay="0.2s" id="app">
  <div class="row">
        <div class="col-12">
          <div class="card card_top">
            <form class="form-horizontal" method="post" action="">
              {{ csrf_field() }}
              <div class="card-header text-center"><h4><strong>Sales Income Search</strong></h4></div>
              <div class="card-block">
                <div class="row">
                  <div class="col-6">
                    <div class="form-group"> <!-- Date input -->
                      <label class="control-label" for="from_date"><h5><strong>From</strong></h5></label>
                      <input class="form-control" id="from_date" name="from_date" placeholder="MM/DD/YYY" type="text"/>
                    </div>                    
                  </div>
                  <div class="col-6">
                    <div class="form-group"> <!-- Date input -->
                      <label class="control-label" for="to_date"><h5><strong>To</strong></h5></label>
                      <input class="form-control" id="to_date" name="to_date" placeholder="MM/DD/YYY" type="text"/>
                    </div>                    
                  </div>
                </div>
                <div class="form-group text-center"> <!-- Submit button -->
                  <button class="btn btn-primary" id="search_sales_income" name="search_sales_income" type="submit">Show Sales Income</button>
                </div>
              </div>  
            </form>  
          </div>
        </div>
<!--form end here-->
  </div>
<!--Sales Search Results-->
    <div class="row">
        <div class="col-12">
          <div class="card card_income_result wow fadeIn" data-wow-delay="0.2s" id="app">
            <div class="card-header text-center"><h4><strong>Sales Income Results</strong></h4></div>
            <div class="card-block">
            <h5><strong>Income</strong></h5>
              <table class="table">
                <thead>
                  <tr>
                    <th>Date</th>
                    <th>Bill ID</th>
                    <th>Client/Company</th>
                    <th>Total</th>
                    <th>Due</th>
                    <th>Paid</th>
                  </tr>
                </thead>
                <tbody>
                <tr>
                  <td>12-12-17</td>
                  <td>Bill info</td>
                  <td>Client info</td>
                  <td><strong><i>238.00</i></strong></td>
                  <td><strong><i>238.00</i></strong></td>
                  <td><strong><i>238.00</i></strong></td>
                </tr>
                <tr>
                  <td>Total</td>
                  <td></td>
                  <td></td>
                  <td><strong><i>238.00</i></strong></td>
                  <td><strong><i>238.00</i></strong></td>
                  <td><strong><i>238.00</i></strong></td>
                </tr>     
                </tbody>
              </table>
              <h5><strong>Expenses</strong></h5>
              <table class="table">
                <thead>
                  <tr>
                    <th>Purpose</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th>Amount</th>
                  </tr>
                </thead>
                <tbody>
                <tr>
                  <td>Purpose info</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td><strong><i>238.00</i></strong></td>
                </tr>
                <tr>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td>Total</td>
                  <td><strong><i>238.00</i></strong></td>
                </tr>
                </tbody>
              </table>
              <div class="row">
                <div class="col-6">
                  <div class="form-group"> <!-- Date input -->
                    <strong>Vault total: <i>238.00</i></strong>
                  </div>                    
                  <div class="form-group"> <!-- Date input -->
                    <strong>Cash total: <i>138.00</i></strong>
                  </div>
                </div>
              </div>
              <br>
              <h5><strong>Expenses Cheque</strong></h5>
              <table class="table">
                <thead>
                  <tr>
                    <th>Bank</th>
                    <th>Branch</th>
                    <th>Account</th>
                    <th>Amount</th>
                  </tr>
                </thead>
                <tbody>
                <tr>
                  <td>Bank info</td>
                  <td>Branch info</td>
                  <td>Account info</td>
                  <td><strong><i>0.00</i></strong></td>
                </tr>
                <tr>
                  <td></td>
                  <td></td>
                  <td>Total</td>
                  <td><strong><i>0.00</i></strong></td>
                </tr>
                </tbody>
              </table>
            </div>  
          </div>
        </div>
  </div>

</div>
@endsection

