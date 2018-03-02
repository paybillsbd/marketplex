@extends('layouts.app-dashboard-admin')
@section('title', 'Sales')
@section('title-module-name', 'Sales')

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
/*      .modal-dialog {
          width: 560px;
          height:900px !important;
      }
      .modal-body {
        max-height: calc(100vh - 143px);
        overflow-y: auto;
      }*/
      .modal-content {
          /* 80% of window height */
          height: 100%;
      }
    </style>

@endsection

@section('footer-scripts')

<!--     <script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script> -->

    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <!-- Bootstrap Date-Picker Plugin -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
    <script>
      var FormRequestManager = {
          id: "#submit-form",
          _shouldRedirect: true,
          _redirectUrl: '/home',
          _route: '',
          _data: {},
          _validationErrors: [],
          _onValidationError: function(data) {
              FormRequestManager._hideValidationErrors();
              var response = data.responseJSON;

              // ref: https://stackoverflow.com/questions/20881213/converting-json-object-into-javascript-array
              var validationErrors = Object.values(response);
              var validationErrorFields = Object.keys(response);
              // console.log(validationErrorFields);

              validationErrors.forEach(function(error, index) {
                  FormRequestManager._showInvalidInput(validationErrorFields[index], error);
              });

              FormRequestManager._showValidationSummary();
          },
          // ref: https://stackoverflow.com/questions/25227544/add-class-to-parent-div-with-specific-input
          _showInvalidInput: function(inputId, validationText) {

              // ref: https://stackoverflow.com/questions/1144783/how-to-replace-all-occurrences-of-a-string-in-javascript
              inputId = inputId.replace(new RegExp('\\.', 'g'), "\\.");

              FormRequestManager._validationErrors.push(validationText);
              var targetInput = $("#" + inputId);
              var formGroup = targetInput.closest(".form-group");
              formGroup.addClass('has-error');
              var helpBlock = formGroup.find(".help-block").first();
              console.log(helpBlock.html());
              helpBlock.removeClass('hidden');
              helpBlock.find("strong").first().text(validationText);
              targetInput.focus();
          },
          // ref: https://stackoverflow.com/questions/25227544/add-class-to-parent-div-with-specific-input
          _hideInvalidInput: function(inputId) {

              var targetInput = $("#" + inputId);
              targetInput.closest(".form-group").removeClass('has-error');
              var helpBlock = targetInput.closest(".help-block");
              helpBlock.addClass('hidden');
              helpBlock.closest("strong").empty();
          },
          _hideValidationErrors: function() {

              $( ".form-group" ).removeClass( "has-error" );
              $( ".help-block" ).addClass( "hidden" );
              $( ".error-summary" ).addClass( "hidden" );
              $( ".error-summary" ).find("ul").first().empty();
              FormRequestManager._validationErrors = [];  
          },
          _showValidationSummary: function() {

              var errors = '';
              FormRequestManager._validationErrors.forEach(function(item, index) {
                  errors += '<li>' + item + '</li>';
              });
              $( ".error-summary" ).removeClass( "hidden" );    
              $( ".error-summary" ).find("ul").first().html(errors);
          },
          _reset: function() {

              document.getElementById(FormRequestManager.id).reset();
          },
          _onJsonReceived: function (json) {},
          _onSuccess: function(data) {
              // success logic
              // alert(JSON.stringify(data));
              if (data.code == 200) // OK
              {                        
                  if (data.message !== undefined)                    
                  {
                      alert( "Success! " +  data.message );
                  }
                  if (FormRequestManager._shouldRedirect)
                  {
                    window.location.href = "{{ route('user::sales.index', [ 'api_token' => Auth::user()->api_token ]) }}";
                  }
                  else
                  {
                      // alert(JSON.stringify(data));
                      FormRequestManager._onJsonReceived(data);
                  }
              }
              else
              {
                  FormRequestManager._reset();
                  alert( "Sorry! " +  data.message );
              }
              FormRequestManager._hideValidationErrors();
          },  
          _onError: function(jqXHR, textStatus, errorThrown) {

              FormRequestManager._hideValidationErrors();
              var now = new Date(Date.now());
              // alert(jqXHR.status);
              if (jqXHR.status == 404 || jqXHR.status == 422 || jqXHR.status == 400) 
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
                  msg = 'Server could not process your submitted data.';
                  console.log('Requested JSON parse failed.');
                  // $('body').html(jqXHR.responseText);
              } else if (textStatus === 'timeout') {
                  msg = 'Time out error.';
              } else if (textStatus === 'abort') {
                  msg = 'Ajax request aborted.';
              } else if (jqXHR.status == 503) {
                  msg = 'Something went wrong: [' + jqXHR.status + '][' + errorThrown + '].\n';
                  $('body').html(jqXHR.responseText);
              } else {
                  msg = 'Uncaught Error: [' + jqXHR.status + '][ ' + textStatus + ' ][' + errorThrown + '].\n' + jqXHR.responseText;
                  $('body').html(jqXHR.responseText);
              }              
              // Render the errors with js ...
              alert(msg + 'The operations are failed! The issues are logged dated: ' + now.toLocaleDateString()
                        + '\nfor the assistance of your service provider.');
          },
          _onSubmit: function(event) {
                        
              event.preventDefault();
              var _this = FormRequestManager;
              $.ajax({
                    type: 'post',
                    url: _this._route,
                    data: $( ":input" ).serializeArray(), //{ inputs: $( ":input" ).serializeArray(), extra: _this._data },
                    dataType: 'json',
                    statusCode: {
                          422: _this._onValidationError,
                          400: function(data) {
                              var response = data.responseJSON;
                              alert( response.message == undefined ? 'Unknown error!' : response.message );
                          }
                    },
                    success: _this._onSuccess,
                    error: _this._onError 
              });

          },
          ready: function(url, data, redirectUrl) {

            this._route = url;
            this._data = data;
            this._redirectUrl = redirectUrl;
            this._shouldRedirect = redirectUrl !== null;
            var _this = this;

            $(this.id).ready(function() {
                  $(_this.id).submit(_this._onSubmit);
            });
          },
          requestJSON: function(url, data, redirectUrl, onJsonReceived) {

            this._onJsonReceived = onJsonReceived;
            this.ready(url, data, redirectUrl);
          }
      };  

      var DataManager = {
              _onFail: function (jqXHR, textStatus, errorThrown) {
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
          request: function(method, url, payload, onLoad) {

              if (method.toString().toLowerCase() === 'post')
              {
                  $.post( url, payload, onLoad, "json" ).fail(this._onFail);
              }
              else if (method.toString().toLowerCase() === 'get')
              {
                  $.get( url, payload, onLoad).fail(this._onFail);
              }
          }
      };


      var ViewContentManager = {
          // @param: view_name - name of the view to load
          // @param: payload - the data to bind to the view content
          // @param: table_id - id of the table to update
          append: function(view_name, payload, selector) {

              DataManager.request('get', '/api/v1/templates/' + view_name + '?api_token={{ Auth::user()->api_token }}', payload, function(data) {

                  $(selector).append(data);
              });
          },
          // @param: view_name - name of the view to replace
          // @param: payload - the data to bind to the view content
          // @param: table_id - id of the table to update
          replace: function(view_name, payload, selector) {

              DataManager.request('get', '/api/v1/templates/' + view_name + '?api_token={{ Auth::user()->api_token }}', payload, function(data) {

                  $(selector).html(data);
              });
          }
      };

      var frmSearchSale = FormRequestManager;
      frmSearchSale.id = '#search-sale-form';
      var route = "{{ route('user::sales.search', [ 'api_token' => Auth::user()->api_token ]) }}";
      frmSearchSale.requestJSON(route, [], null, function(json) {

          if (json.sales !== undefined)
          {
              var payload = { sales: JSON.stringify(json.sales) };
              ViewContentManager.replace('sales-row-search-result', payload, '#search-result-table > tbody');
              $('#pagination-container').empty();
              // payload = { sales: JSON.stringify(json.sales), queries: JSON.stringify(json.queries) };
              // DataManager.request('get', '/api/v1/paginations/sale-transaction-links?api_token={{ Auth::user()->api_token }}', payload, function(data) {

              //     // console.log(data);
              //     $('#pagination-container').html(data);
              // });
          }
      });

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
          $('.show-invoice').click(function(e) {
              e.preventDefault();

              $('#invoice_modal').modal({ show: true });
              $('#invoice-viewer').empty();
              var iframe = $('<iframe>');
              iframe.attr('src', "/api/v1/sales/" + $(this).data('sale') + "/invoice?download=0&api_token={{ Auth::user()->api_token }}");
              iframe.attr('height', window.innerHeight * 0.75);
              $('#invoice-viewer').append(iframe);
              $('#invoice-print').attr('href', "/api/v1/sales/" + $(this).data('sale') + "/invoice?download=0&api_token={{ Auth::user()->api_token }}");
              $('#invoice-download').attr('href', "/api/v1/sales/" + $(this).data('sale') + "/invoice?download=1&api_token={{ Auth::user()->api_token }}");
          });   
      })
    </script>
@endsection

@section('modals')
    <div class="modal fade" id="invoice_modal" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <a  type="button" id="invoice-print" href="#"
                data-toggle="tooltip" data-placement="top" title="Print Invoice">
              <span aria-hidden="true">
              <i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">print</i></span>
            </a>
            <a  type="button" id="invoice-download" href="#"
                data-toggle="tooltip" data-placement="top" title="Download Invoice">
              <span aria-hidden="true">
              <i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">file_download</i></span>
            </a>
            <button type="button" class="close" data-dismiss="modal">
              <span aria-hidden="true">&times;</span>
              <span class="sr-only">Close</span>
            </button>
            <h4 class="modal-title">Invoice Viewer</h4>
          </div>
          <div class="modal-body">
            <div id="invoice-viewer"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-warning" data-dismiss="modal">OK</button>
          </div>
        </div>
      </div>
    </div>
@endsection

@section('content')
<div class="box box-info">    
    <div class="box-body">
      <div class="row padTB"> 
          <div class="col-lg-6 col-lg-offset-3">
            <div class="box box-noborder">
              
              @component('includes.message.error-summary')
                  <ul></ul>
              @endcomponent
              <!-- form start -->
              <form id="search-sale-form" role="form">

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
                                id="queries.from_date" name="queries[from_date]"/>
                            <span class="help-block hidden">
                                <strong></strong>
                            </span>
                      </div>                    
                    </div>
                    <div class="col-6">
                      <div class="form-group"> <!-- Date input -->
                        <label class="control-label" for="to_date"><h5><strong>To</strong></h5></label>
                        <input  type="date"
                                class="form-control"
                                id="queries.to_date" name="queries[to_date]"/>
                            <span class="help-block hidden">
                                <strong></strong>
                            </span>
                      </div>                    
                    </div>
                  </div>
                </div>  
                <!-- box-body -->

                <div class="box-footer text-right"> <!-- Submit button -->
                  <div class="row">
                    <div class="col-md-10"> </div>
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
    
    <!--Interests-->
    <div class="row">

    <div class="col-xs-12">

    <div class="box">
      <div class="box-header">
      <h3 class="box-title">You might be interested to see:</h3>
      </div><!-- /.box-header -->
    <div class="box-body">


    <div class="row">
    <div class="col-xs-4">
          <a  href="" id="sales_today" name="sales_today" 
              class="btn btn-info btn-flat btn-sm" role="button">Today's Sales</a>
    </div>
    </div>

    </div><!-- /.box-body -->
    </div><!-- /.box -->  
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

            <div class="row">
            <div class="col-xs-4"> 
                  <a  href="{{ route('user::sales.create', [ 'api_token' => Auth::user()->api_token ]) }}"
                      class="btn btn-info btn-flat btn-sm" role="button">New Sale</a>
            </div>
            </div>

            <table id="search-result-table" class="table">
                <thead>
                  <tr>
                    <th>Date</th>
                    <th>Bill ID</th>
                    <th>Client/Company</th>
                    <th>Total Bill</th>
                    <th>Due</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @include('includes.tables.sales-row-search-result') 
                </tbody>
            </table>
            <div id="pagination-container">{{ $sales->appends($paginator_appends)->links() }}</div>

          </div><!-- /.box-body -->
        </div><!-- /.box -->  
      </div>

    </div>
  
  
</div>

</div>

@endsection

