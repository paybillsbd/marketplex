@extends('layouts.app-store-front')
@section('title', 'Sales Search Results')
@section('title-module-name', 'Sales Search Results')

@section('header-styles')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <!-- Bootstrap Date-Picker Plugin -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
    <style>
      td, th, input {
        text-align: center;
        vertical-align: middle;
      }
    </style>
@endsection

@section('footer-scripts')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="/vendor/inzaana/js/product/product.js" type="text/javascript"></script>
@endsection

@section('modals')
    <div id="modal_container">{{--Modal load here--}}</div>
@endsection

@section('content')
@include('includes.message.message') 
<div class="wow fadeIn" data-wow-delay="0.2s" id="app">
  <div class="row">
        <div class="col-12">
          <div class="card">
              <div class="card-header text-center"><h4><strong>Sales Search Results</strong></h4></div>
              <div class="card-block">
              <table class="table">
                  <thead>
                    <tr>
                      <th>Bill ID</th>
                      <th>Client/Company</th>
                      <th>Total Bill</th>
                      <th>Total Due</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                  <tr>
                    <td>info</td>
                    <td>info</td>
                    <td>info</td>
                    <td>info</td>
                    <td>
                      <div class="clearfix">
                       <a href="#" data-toggle="modal" data-target="" class="btn btn-success btn-sm">Edit</a>
                       <!--<a href="#" data-toggle="modal" data-target="" class="btn btn-danger btn-sm">Delete</a>-->
                      </div>
                    </td>
                  </tr>     
                  </tbody>
                  <tbody>
                  <tr>
                    <td>info</td>
                    <td>info</td>
                    <td>info</td>
                    <td>info</td>
                    <td>
                      <div class="clearfix">
                       <a href="#" data-toggle="modal" data-target="" class="btn btn-success btn-sm">Edit</a>
                      </div>
                    </td>
                  </tr>     
                  </tbody>
                  <tbody>
                  <tr>
                    <td>info</td>
                    <td>info</td>
                    <td>info</td>
                    <td>info</td>
                    <td>
                      <div class="clearfix">
                       <a href="#" data-toggle="modal" data-target="" class="btn btn-success btn-sm">Edit</a>
                      </div>
                    </td>
                  </tr>     
                  </tbody>
                  <tbody>
                  <tr>
                    <td>info</td>
                    <td>info</td>
                    <td>info</td>
                    <td>info</td>
                    <td>
                      <div class="clearfix">
                       <a href="#" data-toggle="modal" data-target="" class="btn btn-success btn-sm">Edit</a>
                      </div>
                    </td>
                  </tr>     
                  </tbody>
              </table>
              </div>  
          </div>
        </div>
<!--form end here-->
  </div>
</div>
@endsection

