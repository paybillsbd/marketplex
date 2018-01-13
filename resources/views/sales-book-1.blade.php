@extends('layouts.app-dashboard-admin')
@section('title', 'Sales')
@section('title-module-name', 'Sales')

@section('header-styles')
    <!-- Styles -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <style>
      td, th {
        text-align: center;
        vertical-align: middle;
      }
      
      table{
        text-align: center;
        vertical-align: middle;
      }
      input[type="number"], input[class="decimal"], .decimal, .multTotal {
        direction: RTL;
        font-weight: bold;
        font-style: italic;
      }
      textarea{
        height: 5em;
      }
      
      .btn_remove {
        color:red;
      }

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
    <script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>

    <script type="text/javascript">
      $(document).ready(function(){  

        var tags = [ 'Bar', 'Far', 'Dar'];

        $( "#client" ).autocomplete({
            source: tags; //"{{ route('user::clients.index', [ 'api_token' => Auth::user()->api_token ]) }}",
            minLength: 2,
            select: function( event, ui ) {
              alert( "Selected: " + ui.item.value + " aka " + ui.item.label );
            }
        });
      });
    </script>

    <script>

      var DataManager = {
          serviceUrl: '',
          payload: {},
          onLoad: function(data) {},
          request: function() {

              $.get(this.serviceUrl, this.payload, this.onLoad).fail(function(jqXHR, textStatus, errorThrown) {
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
              });
          }
      };

      var ViewContentManager = {
          // @param: view_name - name of the view to load
          // @param: payload - the data to bind to the view content
          // @param: table_id - name of the view to load
          append: function(view_name, payload, selector) {

              DataManager.serviceUrl = '/api/v1/templates/' + view_name + '?api_token={{ Auth::user()->api_token }}';
              DataManager.payload = payload;
              DataManager.onLoad = function(data) {

                  $(selector).append(data);
              };
              DataManager.request();
          }
      };

      $(document).ready(function() {      
        var i=1;  
        var d = new Date(Date.now());
        var priceCollection = [];

        $('#addBillingRow').click(function(){  
            
            i++; 

            var rowTemplate = 'sales-row-shipping-bill';
            DataManager.serviceUrl = '/api/v1/templates/' + rowTemplate + '?api_token={{ Auth::user()->api_token }}';
            DataManager.payload = { row_id: i,  datetime: d.toLocaleDateString() };
            DataManager.onLoad = function(data) {

                $('#dynamic_field_shipping').append(data);
            };
            DataManager.request(); 
        });   

        $('#addPayRow').click(function(){  
             
            i++;  

            var rowTemplate = 'sales-row-paid-bill';
            DataManager.serviceUrl = '/api/v1/templates/' + rowTemplate + '?api_token={{ Auth::user()->api_token }}';
            DataManager.payload = { row_id: i,  datetime: d.toLocaleDateString() };
            DataManager.onLoad = function(data) {

                $('#dynamic_field_pay').append(data);
            };
            DataManager.request();
        });  

        $('#add_expense_btn').click(function(){  
             
            i++;  

            var rowTemplate = 'sales-row-expense';
            DataManager.serviceUrl = '/api/v1/templates/' + rowTemplate + '?api_token={{ Auth::user()->api_token }}';
            DataManager.payload = { row_id: i,  datetime: d.toLocaleDateString() };
            DataManager.onLoad = function(data) {

                $('#dynamic_field_expenses').append(data);
            };
            DataManager.request();
        });
        
        $('#addBankRow').click(function(){  
             
            i++;

            var accountsMgr = DataManager;
            accountsMgr.serviceUrl = '{{ route("user::banks.index", [ "api_token" => Auth::user()->api_token ]) }}';
            accountsMgr.onLoad = function(json) {

                ViewContentManager.append('sales-row-bank-deposit', {
                  row_id: i,
                  datetime: d.toLocaleDateString(),
                  "bank_accounts": JSON.stringify(json.accounts)
                }, '#dynamic_field_bank');

            };
            accountsMgr.request();
        });

        $('#add_product_bill').click(function() { 
            
          i++;

          DataManager.serviceUrl = '/api/v1/products/' + $('#product_name').val() + '/price?api_token={{ Auth::user()->api_token }}';
          DataManager.onLoad = function(data) {

              priceCollection["row" + i] = data.price;

              // the product already listed in the table
              if ($("tr.product_bill").find("input:hidden[value='" + data.product_id + "']").length > 0)
              {
                  var product_quantity = $("tr.product_bill").find("input[data-product-id='" + data.product_id + "']").first();
                  if (Number(product_quantity.val()) < data.available_quantity)
                  {
                      product_quantity.val(Number(product_quantity.val()) + 1);
                  }
                  // calculate pricing after manipulating quantity inputs
                  multInputs();
                  return;
              }

              ViewContentManager.append('sales-row-product-bill', {
                row_id: i,
                product_id: data.product_id,
                datetime: d.toLocaleDateString(),
                product_title: data.title,
                store_name: data.store_name,
                product_available_quantity: data.available_quantity
              }, '#product_bill_table');
          };
          DataManager.request();

        });
        
        $(document).on('click', '.btn_remove', function(e) {

              e.preventDefault();
              var button_id = $(this).attr("id");
              $('#row' + button_id).remove();
              multInputs();
        });

        $("tbody").on('change', ".deposit_method", function() {

              var rowId = $(this).attr("row-id");
              var bankingFields = $("#deposits\\." + rowId + "\\.bank_title, #deposits\\." + rowId + "\\.bank_ac_no");

              if ($(this).val() == 'vault')
              {
                  bankingFields.hide();
              }
              else if ($(this).val() == 'bank')
              {
            	    bankingFields.show();
              }        
        });

        $("tbody").on('change', ".deposit_account", function() {

              var rowId = $(this).attr("row-id");
              var depositAccountInput = $('#deposits\\.' + rowId + '\\.bank_ac_no');
              var depositDetailLabel = $('#deposits\\.' + rowId + '\\.bank_title');

              // alert( 'Account: ' + depositAccountInput.val() + ', Title: ' + depositDetailLabel.val());

              var accountsMgr = DataManager;
              accountsMgr.serviceUrl = '/api/v1/settings/banks/' + depositAccountInput.val();
              accountsMgr.serviceUrl += '?api_token={{ Auth::user()->api_token }}';
              accountsMgr.onLoad = function(json) {
                  depositDetailLabel.html(json.summary_html);
              };
              accountsMgr.request();      
              // alert( 'Request sent to: ' + accountsMgr.serviceUrl);
        });

        function Decimal(numberText)
        {
            var number = Number(numberText);
            // specially take care the pure zero value
            if (number == 0.0)
            {
                return number.toPrecision(3);
            }
            // if already formatted to decimal just return it
            if (numberText.toString().indexOf('.') > -1)
            {
                return numberText;
            }
            // otherwise ... keep precision minding the input length
            return number.toPrecision(numberText.toString().length + 2);
        }        

        function multInputs() {

             var grandTotal = 0.0;
              // for each row:
              $("tr.ship_bill").each(function () {
                 // get the values from this row:
                 var billAmmountInput = $('#shipping_bills\\.' + $(this).data('row-id') + '\\.bill_amount', this);
                 var amount = billAmmountInput.val();
                 billAmmountInput.val(Decimal(amount));
                 var quantity = $('#shipping_bills\\.' + $(this).data('row-id') + '\\.bill_quantity', this).val();
                 var total = Number(amount) * Number(quantity);

                 $('.multTotal',this).text(Decimal(total));
                 grandTotal += total;
              });
              $("tr.product_bill").each(function () {

                  // must escape the id selector for dot (.) if contains any
                  // ref: https://stackoverflow.com/questions/605630/how-to-select-html-nodes-by-id-with-jquery-when-the-id-contains-a-dot
                  var productQuantityInput = $('#product_bills\\.' + $(this).data('row-id') + '\\.product_quantity');
                  var quantity = productQuantityInput.val();
                  var unitPrice = priceCollection[this.id];

                  var totalPriceLabel = $('.multTotal', this);

                  if (isNaN(unitPrice))
                  {
                      DataManager.serviceUrl = '/api/v1/products/' + $(this).data('product-id') + '/price?api_token={{ Auth::user()->api_token }}';
                      DataManager.onLoad = function(data) {
                          var total = Number(data.price) * Number(quantity);
                          totalPriceLabel.text(Decimal(total));
                          grandTotal += total;

                          $("#grandTotal").text(Decimal(grandTotal));
                          calculateDue();
                      };
                      DataManager.request();
                  }
                  else
                  {
                      var total = Number(unitPrice) * Number(quantity);
                      totalPriceLabel.text(Decimal(total));
                      grandTotal += total;
                  }
              });
              $("#grandTotal").text(Decimal(grandTotal));
              calculateDue();
         }

         function calculateDue()
         {
             var totalPaid = 0.0;
             var grandTotalBill = $("#grandTotal").text();
              // for each row:
              $("tr.bill_payment").each(function () {
                 // get the values from this row:
                 var amountInput = $('#payments\\.' + $(this).data('row-id') + '\\.paid_amount', this);
                 var amount = Number(amountInput.val());
                 amountInput.val(Decimal(amount));
                 totalPaid += amount;
              });

              var grandTotalDue = Number(grandTotalBill) - Number(totalPaid);

              if (grandTotalBill > 0.0)
              {
                  if (grandTotalDue < 0.0)
                  {
                      alert("Client has paid more than due payment!" );
                  }
                  if (grandTotalDue == 0.0)
                  {
                      alert("Client has paid full due payment!" );
                  }
              }              
              $("#current_due").text(Decimal(grandTotalDue));
              // $("#prev_due").text(grandTotal);
              var totalDue = Number($("#prev_due").text()) + Number(grandTotalDue);
              $("#total_due").text(Decimal(totalDue));
         }

         function calculateExpenses()
         {           
             var totalExpense = 0.0;
             var grandTotalBill = Number($("#grandTotal").text());
             var currentDue = Number($("#current_due").text());
              $("tr.expenses").each(function () {

                  var expensesAmountInput = $('#expenses\\.' + $(this).data('row-id') + '\\.expense_amount', this);
                  var amount = Number(expensesAmountInput.val());
                  expensesAmountInput.val(Decimal(amount));
                  totalExpense += amount;
              });
              $("tr.bank_deposit").each(function () {

                  var rowId = $(this).data('row-id');
                  var depositAmountInput = $('#deposits\\.' + rowId + '\\.deposit_amount', this);
                  var amount = Number(depositAmountInput.val());
                  depositAmountInput.val(Decimal(amount));
                  totalExpense += amount;
              });

              if (grandTotalBill == 0.0 && currentDue == 0.0)
              {
                  if (totalExpense > 0.0)
                  {
                      alert("Warning! You have no sales income from this client!");
                      return;
                  }
              }
              var totalIncome = grandTotalBill - currentDue;
              if (grandTotalBill > 0.0 && totalIncome < totalExpense)
              {
                  alert("Warning! You have expenses/ deposits more than your 'sales income' from this client!\nTotal Expense: " + Decimal(totalExpense) + "\nTotal Paid:" + Decimal(totalIncome));
              }
         }
         
         $("tbody").on('change', '.ship_bill input', multInputs);
         $("tbody").on('change', '.product_bill input', multInputs);
         $("tbody").on('change', '.bill_payment input', calculateDue);
         $("tbody").on('change', '.expenses input', calculateExpenses);
         $("tbody").on('change', '.bank_deposit input', calculateExpenses);

    }); 

      $( "#store" ).change(function() {

          var selectedVal = $(this).val();
          if (selectedVal == -1)
          {
              return;
          }

          DataManager.serviceUrl = '/api/v1/stores/' + selectedVal + '/products?api_token={{ Auth::user()->api_token }}';
          DataManager.onLoad = function(data) {

            var content = '<option value="-1">-- Select --</option>';//JSON.stringify(data);
            // alert(content);
            data.forEach(function(item, index){
                content += '<option value="' + item.id + '">' + item.title + '</option>'; 
            });

            $('#product_name').html(content);
          };
          DataManager.request();
      });
    </script>
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
        _onSuccess: function(data) {
            // success logic
            if (data.code == 200) // OK
            {                        
                alert( "Success! " +  data.message );
                if (FormRequestManager._shouldRedirect)
                {
                  window.location.href = "{{ route('user::sales.index', [ 'api_token' => Auth::user()->api_token ]) }}";
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
                $('body').html(jqXHR.responseText);
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
            alert(msg + 'The operationas are failed! The issues are logged dated: ' + now.toLocaleDateString()
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
        }
    };

    var frm = FormRequestManager;
    frm.id = '#sale-form';
    var route = "{{ route(isset($sale) ? 'user::sales.update' : 'user::sales.store', isset($sale) ? [ 'sale' => $sale, 'api_token' => Auth::user()->api_token ] : [ 'api_token' => Auth::user()->api_token ]) }}";
    frm.ready(route, [], "{{ route('user::sales.index', [ 'api_token' => Auth::user()->api_token ]) }}");

    </script>
    <script type="text/javascript">
      $('#add_bank').ready(function() {
          $(this).modal({ 'show' : ($(this).find('div.has-error').length > 0) });
      });

      var frmAccount = FormRequestManager;
      frmAccount.id = '#account-form';
      frmAccount.ready("{{ route('user::banks.store', [ 'api_token' => Auth::user()->api_token ]) }}", [], null);
    </script>
@endsection

@section('modals')
    <div id="modal_container">{{--Modal load here--}}</div>

    <div class="modal fade" id="add_client" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">
              <span aria-hidden="true">&times;</span>
              <span class="sr-only">Close</span>
            </button>
            <h4 class="modal-title">Add Client</h4>
          </div>
          {{ Form::open(['route' => [ 'user::clients.store', '' ] ]) }}
          {!! csrf_field() !!}
          <div class="modal-body">
              <div class="form-group">
                <input type="text" id="new_client_name" name="new_client_name" class="form-control" placeholder="Client/Company/Business Name" required />
              </div>
              <div class="form-group">
                <textarea class="form-control" rows="5" name="client_detail" id="client_detail" placeholder="Client/Company/Business Detail"></textarea>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
            <button type="sumbit" class="btn btn-info">Save</button>
          </div>
          {{ Form::close() }}
        </div>
      </div>
    </div>

    <div class="modal fade" id="add_bank" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">
              <span aria-hidden="true">&times;</span>
              <span class="sr-only">Close</span>
            </button>
            <h4 class="modal-title">Add Bank Account</h4>
          </div>

          @component('includes.message.error-summary')
              <ul></ul>
          @endcomponent

          <form id="account-form">

          {!! csrf_field() !!}

          <div class="modal-body">
              <div class="form-group">
                <input  type="text" name="new_bank_name" id="new_bank_name" class="form-control"
                        placeholder="Bank Name" required />
                <span class="help-block hidden">
                    <strong></strong>
                </span>
              </div>
              <div class="form-group">
                <input type="text" name="bank_branch_name" id="bank_branch_name" class="form-control" placeholder="Branch Name" required />
              </div>
              <div class="form-group">
                <input type="text" name="bank_acc_no" id="bank_acc_no" class="form-control" placeholder="Account No" required />
              </div>
              <div class="form-group">
                <textarea class="form-control" rows="5" name="bank_detail" id="bank_detail"
                          placeholder="Bank Detail"></textarea>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
            <button type="sumbit" class="btn btn-info ">Save</button>
          </div>
          </form>

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

              <form id="sale-form">

              {!! csrf_field() !!}

              <div class="box-body">
                    <div class="form-group">
                        <label for="bill_id"><strong>Billing ID:</strong></label>
                        <div class="col-md-12">
                            <input type="text" class="form-control" id="bill_id" name="bill_id" value="{{ isset($sale) ? $sale->bill_id : '' }}" />
                            <span class="help-block hidden">
                                <strong></strong>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                      <label for="client"><strong>Business/Client/Company Name:</strong></label>
                      <div class="row">
                        <div class="col-md-10">
                          <input  type="text" name="client" id="client" class="form-control" value="{{ isset($sale) ? $sale->client_name : '' }}" />
                            <span class="help-block hidden">
                                <strong></strong>
                            </span>
                        </div>
                        <div class="col-md-2">
                          <div class="clearfix">
                            <!-- <a href="#" data-toggle="modal" data-target="#add_client" class="btn btn-info btn-sm float-right">Add</a> -->
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="store_name_input"><strong>Store:</strong></label>
                      <div class="row">
                        <div class="col-md-12">
                          <!-- @include('includes.menus.selectbox-searchable-async-simple', [ 'route' => route('user::clients.index'), 'context' => 'search-services' ]) -->
                          <select class="form-control" id="store" name="store" data-placeholder="Select a store" >
                            <option value="-1">-- Select --</option>
                            @foreach( $stores as $title => $value )
                              <option value="{{ $value }}">{{ $title }}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="product_name"><strong>Product:</strong></label>
                      <div class="row">
                        <div class="col-md-12">
                          <select class="form-control" id="product_name" name="product_name" data-placeholder="Select a product">
                                <option value="-1">-- Select --</option>
                          </select>
                        </div>
                      </div>
                  </div>
                  <h4><strong>Billing</strong></h4>
                  <div class="form-group">
                    <div class="row">

                      <div class="col-md-11">
                        <label for="product_billing"><strong>Product Billing:</strong></label>
                      </div>
                      <div class="col-md-1">
                        <div class="clearfix">
                          <button type="button" id="add_product_bill" class="btn btn-info btn-sm float-right">Add</button>
                        </div>
                      </div>
                      
                    </div>
                    <table class="table table-bordered" id="product_bill_table">
                    <thead>
                      <tr>
                      <th width="10%">Date</th>
                      <th width="20%">Title</th>
                      <th width="20%">Store</th>
                      <th width="10%">Quantity</th>
                      <th width="35%">Total</th>
                      <th width="5%">#</th>
                      </tr>
                    </thead>
                    <tbody>
                        <!--jQuery will add input fields here-->
                        @if( isset($sale) )
                          @foreach( $sale->productbills as $bill )
                            @include('includes.tables.sales-row-product-bill', [
                                'row_id' => $row++,
                                'product_bill_id' => $bill->id,
                                'product_id' => $bill->product_id,
                                'datetime' => Carbon\Carbon::parse($bill->created_at)->format('m/d/Y h:m'),
                                'product_title' => $bill->product->title,
                                'store_name' => $bill->product->store->name,
                                'bill_price' => $bill->product->mrp,
                                'bill_quantity' => $bill->quantity,
                                'product_available_quantity' => $bill->product->available_quantity
                            ])
                          @endforeach
                        @endif
                    </tbody>
                    </table>
                    </div>
                    <div class="form-group">
                    <div class="row">
                      <div class="col-md-11">
                        <label for="shipping_billing"><strong>Shipping Billing:</strong></label>
                      </div>
                      <div class="col-md-1">
                        <button type="button" id="addBillingRow" class="btn btn-info btn-sm fa fa-plus fa-1x float-right"></button>
                      </div>
                    </div>
                    <table class="table table-bordered" id="dynamic_field_shipping">
                    <thead>
                      <tr>        
                      <th width="10%">Date</th>
                      <th width="20%">Purpose</th>
                      <th width="20%">Amount</th>
                      <th width="10%">Quantity</th>
                      <th width="35%">Total</th>
                      <th width="5%">#</th>
                      </tr>
                    </thead>
                    <tbody>
                      <!--jQuery will add input fileds here-->
                        @if( isset($sale) )
                          @foreach( $sale->shippingbills as $bill )
                            @include('includes.tables.sales-row-shipping-bill', [
                                'row_id' => $row++,
                                'shipping_bill_id' => $bill->id,
                                'datetime' => Carbon\Carbon::parse($bill->created_at)->format('m/d/Y h:m'),
                                'shipping_purpose' => $bill->purpose,
                                'bill_amount' => $bill->amount,
                                'bill_quantity' => $bill->quantity ])
                          @endforeach
                        @endif
                    </tbody>
                  </table>
                  </div>
                  <div class="form-group">
                  <table class="table table-bordered">
                    <tbody>
                      <tr>
                        <td width="60%"><strong><i>Bill Amount ({{ MarketPlex\Store::currencyIcon() }}):</i></strong></td>
                        <td width="40%"><strong><i><span id="grandTotal">{{ isset($sale) ? $sale->getBillAmountDecimalFormat() : 0.00 }}</span></i></strong></td>
                      </tr>
                    </tbody>
                  </table>
                  </div>
                  <div class="row">
                    <div class="col-md-11">
                      <h4><strong>Payment</strong></h4>
                    </div>
                    <div class="col-md-1">
                      <button type="button" id="addPayRow" class="btn btn-info btn-submit-fix fa fa-plus fa-1x float-right"></button>
                    </div>
                  </div>
                    <div class="form-group">
                    <table class="table table-bordered" id="dynamic_field_pay">
                    <thead>
                      <tr>
                      <th width="10%">Date</th>       
                      <th width="50%">Method</th>
                      <th width="35%">Paid Amount</th>
                      <th width="5%">#</th>
                      </tr>
                    </thead>
                    <tbody>
                      <!--Payment row will be added here by jQuery-->
                        @if( isset($sale) )
                          @foreach( $sale->billpayments as $payment )
                            @include('includes.tables.sales-row-paid-bill', [
                                'row_id' => $row++,
                                'paid_bill_id' => $payment->id,
                                'datetime' => Carbon\Carbon::parse($payment->created_at)->format('m/d/Y h:m'),
                                'paid_amount' => $payment->amount,
                                'trans_option' => $payment->method ])
                          @endforeach
                        @endif
                    </tbody>
                    </table>
                    </div>
                    <div class="form-group">
                      <h4><strong>Dues</strong></h4>
                      <table class="table table-bordered">
                        <tbody>
                          <tr>
                            <td width="60%"><strong><i>Current Due ({{ MarketPlex\Store::currencyIcon() }}):</i></strong></td>
                            <td width="40%"><strong><i id="current_due" class="decimal">{{ isset($sale) ? $sale->getCurrentDueAmountDecimalFormat() : 0.00 }}</i></strong></td>
                          </tr>
                          <tr>
                            <td width="60%"><strong><i>Previous Due ({{ MarketPlex\Store::currencyIcon() }}):</i></strong></td>
                            <td width="40%"><strong><i id="prev_due" class="decimal">{{ isset($sale) ? $sale->getPreviousDueAmountDecimalFormat() : 0.00 }}</i></strong></td>
                          </tr>
                          <tr>
                            <td width="60%"><strong><i>Total Due ({{ MarketPlex\Store::currencyIcon() }}) (This Client):</i></strong></td>
                            <td width="40%"><strong><i id="total_due" class="decimal">{{ isset($sale) ? $sale->getTotalDueAmountDecimalFormat() : 0.00 }}</i></strong></td>
                          </tr>
                        </tbody>
                      </table>
                  </div>
                  <div class="row">
                    <div class="col-md-8">
                      <h4><strong>Bank Deposit</strong></h4>
                    </div>
                    <div class="col-md-3">
                        <a href="#" data-toggle="modal" data-target="#add_bank" class="btn btn-info btn-sm float-right">Add Bank Account</a>
                    </div>
                    <div class="col-md-1">
                      <button type="button" id="addBankRow" class="btn btn-info btn-sm fa fa-plus fa-1x float-right"></button>
                    </div>
                  </div>
                    <div class="form-group">
                    <table class="table table-bordered" id="dynamic_field_bank">
                    <thead>
                      <tr>  
                      <th width="10%">Date</th>
                      <th width="10%">Method</th>
                      <th width="20%">Bank Title</th>
                      <th width="20%">Account No</th>
                      <th width="35%">Deposit Amount</th>
                      <th width="5%">#</th>
                      </tr>
                    </thead>
                    <tbody>
                      <!--Bank deposit row will be added here by jQuery-->
                        @if( isset($sale) )
                          @foreach( $sale->deposits as $deposit )
                            @include('includes.tables.sales-row-bank-deposit', [
                                'row_id' => $row++,
                                'bank_deposit_id' => $deposit->id,
                                'datetime' => Carbon\Carbon::parse($deposit->created_at)->format('m/d/Y h:m'),
                                'deposit_method' => $deposit->method,
                                'deposit_amount' => $deposit->amount,
                                'bank_account_no' => $deposit->bank_account_no,
                                'bank_accounts' => MarketPlex\Bank::all()
                            ])
                          @endforeach
                        @endif
                    </tbody>
                    </table>
                    </div>
                    <div class="form-group">
                      <div class="row">
                        <div class="col-md-11">
                          <h4><strong>Expenses</strong></h4>
                        </div>
                        <div class="col-md-1">
                          <button type="button" id="add_expense_btn" class="btn btn-info btn-sm fa fa-plus fa-1x float-right"></button>
                        </div>
                      </div>
                      <table class="table table-bordered" id="dynamic_field_expenses">
                        <thead>
                          <tr>        
                          <th width="10%">Date</th>
                          <th width="50%">Purpose</th>
                          <th width="35%">Amount</th>
                          <th width="5%">#</th>
                          </tr>
                        </thead>
                        <tbody>
                          <!--jQuery will add input fileds here-->
                        @if( isset($sale) )
                          @foreach( $sale->expenses as $expense )
                            @include('includes.tables.sales-row-expense', [
                                'row_id' => $row++,
                                'expense_id' => $expense->id,
                                'datetime' => Carbon\Carbon::parse($expense->created_at)->format('m/d/Y h:m'),
                                'expense_purpose' => $expense->purpose,
                                'expense_amount' => $expense->amount ])
                          @endforeach
                        @endif
                        </tbody>
                      </table>
                    </div>
                    <br>
                    <div class="form-group">
                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-info btn-lg btn-block btn-submit-fix">Save</button>
                        </div>
                    </div>
              </div>
            </form>
              <!--end of form-->
          </div>

            </div>
          </div>
    </div>
</div>
@endsection