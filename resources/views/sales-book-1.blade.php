@extends('layouts.app-dashboard-admin')
@section('title', 'Sales')
@section('title-module-name')
<a href="{{ route('user::sales.index', [ 'api_token' => Auth::user()->api_token ]) }}">Sales</a> {{ ' > New' }}
@endsection


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

      #client-autocomplete {
        z-index: 4;
        border-color: black;
        border-collapse: collapse;
        border: 2px solid black;
      }
      
    </style>
@endsection

@section('footer-scripts')

    <script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>

    <script type="text/javascript" src="/vendor/request-clients/data-request-clients.js"></script>

    <script>

      $(document).ready(function() {

          
        var i = $('tr').length + 4;  
        var d = new Date(Date.now());

        // cross browser string trimming
        String.prototype.trimmed = function(){
            return this.replace(
                /^(\s|&nbsp;|<br\s*\/?>)+?|(\s|&nbsp;|<br\s*\/?>)+?$/ig, ' '
            ).trim();
        }

        $('#client').keyup(function(event) {

              var autocompleteElement = $('#client-autocomplete');
              autocompleteElement.removeClass('hidden');
              autocompleteElement.addClass('ui-autocomplete-loading');
              autocompleteElement.css('zIndex', "5");
              // alert(autocompleteElement.css('zIndex'));
              // alert(autocompleteElement.attr('id'));

              DataManager.serviceUrl = "{{ route('user::sales.search.clients', [ 'api_token' => Auth::user()->api_token ]) }}";
              DataManager.payload = { queries: { client_name: $(this).val() } };
              DataManager.onLoad = function(data) {

                  autocompleteElement.empty();
                  data.forEach(function(item, index) {

                      autocompleteElement.append('<div class="row"><div class="col-md-10 autocomplete-item">' + item + '</div></div>');
                      autocompleteElement.find('div[class="col-md-10 autocomplete-item"]').click(function() {

                          $('#client').val($(this).text());
                          autocompleteElement.addClass('hidden');
                      });
                  });
              };
              DataManager.request();
        });

        $('#addBillingRow').click(function() {

            ViewContentManager.append('sales-row-shipping-bill', {
                row_id: i++,
                datetime: d.toLocaleDateString(),
                api_token: "{{ Auth::user()->api_token }}"
                
            }, '#dynamic_field_shipping');
        });   

        $('#addPayRow').click(function(){

            ViewContentManager.append('sales-row-paid-bill', {
                row_id: i++,
                datetime: d.toLocaleDateString(),
                api_token: "{{ Auth::user()->api_token }}"
            }, '#dynamic_field_pay');
        });  

        $('#add_expense_btn').click(function(){  
             
            ViewContentManager.append('sales-row-expense', {
                row_id: i++,
                datetime: d.toLocaleDateString(),
                api_token: "{{ Auth::user()->api_token }}"
            }, '#dynamic_field_expenses');
        });
        
        $('#addBankRow').click(function(){  
             
            var accountsMgr = DataManager;
            accountsMgr.serviceUrl = '{{ route("user::banks.index", [ "api_token" => Auth::user()->api_token ]) }}';
            accountsMgr.onLoad = function(json) {

                ViewContentManager.append('sales-row-bank-deposit', {
                  row_id: i++,
                  datetime: d.toLocaleDateString(),
                  api_token: "{{ Auth::user()->api_token }}",
                  deposit_method: 'bank',
                  "bank_accounts": JSON.stringify(json.accounts)
                }, '#dynamic_field_bank');

            };
            accountsMgr.request();
        });

        $('#add_product_bill').click(function() {

          DataManager.serviceUrl = '/api/v1/products/' + $('#product_name').val();
          DataManager.serviceUrl += '/price?api_token={{ Auth::user()->api_token }}';
          DataManager.onLoad = function(data) {

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

		      console.log('product data loaded');

              ViewContentManager.append('sales-row-product-bill', {
                row_id: i++,
                product_id: data.product_id,
                datetime: d.toLocaleDateString(),
                api_token: "{{ Auth::user()->api_token }}",
                product_title: data.title,
                store_name: data.store_name,
                product_available_quantity: data.available_quantity
              }, '#product_bill_table');
          };
          DataManager.request();

        });
        
        $(document).on('click', '.btn_remove', function(e) {

              e.preventDefault();
              var tableRowElement = $('#row' + $(this).attr("id"));              
              var tableElement = tableRowElement.closest('table');

              ViewContentManager.appendEmpty('empty-table-message', {
                colspan: 6,
                level: 'info',
                api_token: "{{ Auth::user()->api_token }}",
                message: tableElement.data('empty-message'),
              }, '#' + tableElement.attr('id'));

              tableRowElement.remove();
              multInputs();
        });

        $("tbody").on('change', ".deposit_method", function() {

              var rowId = $(this).attr("row-id");
              var bankingFields = $("#deposits\\." + rowId + "\\.bank_title, #deposits\\." + rowId + "\\.bank_ac_no");
              
              $(this).attr('title', $(this).val() + ' is selected');

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
              var depositDetailLabel = $('#deposits\\.' + rowId + '\\.bank_title');
              var _this = $(this);

              var accountsMgr = DataManager;
              accountsMgr.serviceUrl = '/api/v1/settings/banks/' + $(this).val();
              accountsMgr.serviceUrl += '?api_token={{ Auth::user()->api_token }}';
              accountsMgr.payload = { row: rowId };
              accountsMgr.onLoad = function(json) {
                  depositDetailLabel.html(json.summary_html + json.hidden_inputs);
                  _this.attr('title', json.account_no + ' is selected');
              };
              accountsMgr.request();
        });

        function NumberText(numberTextTrimmed)
        {
            return numberTextTrimmed.indexOf(',') > -1 ? numberTextTrimmed.replace(/,/g, '') : numberTextTrimmed;
        }

        function Decimal(numberText)
        {
            var rawNumberText = NumberText(isNaN(numberText) ? numberText.trimmed() : numberText.toString().trimmed());
            var number = Number(rawNumberText);
            // specially take care the pure zero value
            if (number == 0.0)
            {
                return number.toPrecision(3);
            }
            // if already formatted to decimal just return it
            if (rawNumberText.indexOf('.') > -1)
            {
                return rawNumberText;
            }
            // otherwise ... keep precision minding the input length
            return number.toPrecision(rawNumberText.length + 2);
        }        

        function multInputs() {

             var grandTotal = 0.0;
              // for each row:
              $("tr.ship_bill").each(function () {
                 // get the values from this row:
                 var billAmmountInput = $('#shipping_bills\\.' + $(this).data('row-id') + '\\.bill_amount', this);
                 billAmmountInput.val(Decimal(billAmmountInput.val()));
                 var quantity = $('#shipping_bills\\.' + $(this).data('row-id') + '\\.bill_quantity', this).val();
                 var total = Number(NumberText(billAmmountInput.val().trimmed())) * Number(quantity);
                 $('.multTotal',this).text(Decimal(total));
                 grandTotal += total;
              });
              var reqCount = 0;
              $("tr.product_bill").each(function () {

                  // must escape the id selector for dot (.) if contains any
                  // ref: https://stackoverflow.com/questions/605630/how-to-select-html-nodes-by-id-with-jquery-when-the-id-contains-a-dot
                  var productQuantityInput = $('#product_bills\\.' + $(this).data('row-id') + '\\.product_quantity');
                  var quantity = productQuantityInput.val();
                  var totalPriceLabel = $('.multTotal', this);
                  
                  DataManager.serviceUrl = '/api/v1/products/' + $(this).data('product-id') + '/price?api_token={{ Auth::user()->api_token }}';
                  DataManager.onLoad = function(data) {
                      var total = Number(data.price) * Number(quantity);
                      totalPriceLabel.text(Decimal(total));
                      grandTotal += total;

                      ++reqCount;
                  };
                  DataManager.request();
              });

              wait(function() { return $("tr.product_bill").length == reqCount; }, function() {
                    $("#grandTotal").text(Decimal(grandTotal));
                    calculateDue();
              }, 300);
         }

         // conditionCallback: a callback that returns a boolean logic which is a break condition
         // taskCallback: a callback that should be called when condition is met
         function wait(conditionCallback, taskCallback, milliseconds)
         {
              var timer = setTimeout(function()
              {
                  if (conditionCallback())
                  {
                      clearTimeout(timer);
                      if (taskCallback !== null)
                      {
                          taskCallback();
                      }
                  }
              }, milliseconds);
         }

         function calculateDue()
         {
             var totalPaid = 0.0;
             var grandTotalBill = Number(NumberText($("#grandTotal").text().trimmed()));
              // for each row:
              $("tr.bill_payment").each(function () {
                 // get the values from this row:
                 var amountInput = $('#payments\\.' + $(this).data('row-id') + '\\.paid_amount', this);
                 amountInput.val(Decimal(amountInput.val()));
                 totalPaid += Number(NumberText(amountInput.val()));
              });
              var grandTotalDue = grandTotalBill - totalPaid;
              // alert('Bill:' + grandTotalBill + 'Paid:' + totalPaid);
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
              $("#current_due").text(Decimal(grandTotalDue.toString()));
              // $("#prev_due").text(grandTotal);
              var totalDue = Number(NumberText($("#prev_due").text().trimmed())) + grandTotalDue;
              $("#total_due").text(Decimal(totalDue.toString()));
         }

         function calculateExpenses()
         {           
             var totalExpense = 0.0;
             var grandTotalBill = Number(NumberText($("#grandTotal").text().trimmed()));
             var currentDue = Number(NumberText($("#current_due").text().trimmed()));
              $("tr.expenses").each(function () {

                  var expensesAmountInput = $('#expenses\\.' + $(this).data('row-id') + '\\.expense_amount', this);
                  expensesAmountInput.val(Decimal(expensesAmountInput.val()));
                  totalExpense += Number(NumberText(expensesAmountInput.val().trimmed()));
              });
              $("tr.bank_deposit").each(function () {

                  var rowId = $(this).data('row-id');
                  var depositAmountInput = $('#deposits\\.' + rowId + '\\.deposit_amount', this);
                  depositAmountInput.val(Decimal(depositAmountInput.val()));
                  totalExpense += Number(NumberText(depositAmountInput.val().trimmed()));
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
                  alert("Warning! You have expenses/ deposits more than your 'sales income' from this client!\nTotal Expense: " + Decimal(totalExpense.toString()) + "\nTotal Paid:" + Decimal(totalIncome.toString()));
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
    var frm = new FormRequestManager('sales');
    frm.errorBoardName = 'sales';
    frm.id = '#sale-form';
    var route = "{{ route( (isset($sale) ? 'user::sales.update' : 'user::sales.store'), isset($sale) ? [ 'sale' => $sale, 'api_token' => Auth::user()->api_token ] : [ 'api_token' => Auth::user()->api_token ]) }}";
    frm.ready(route, [], "{{ route('user::sales.index', [ 'api_token' => Auth::user()->api_token ]) }}", "{{ !isset($sale) }}");
    window.form = frm;

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

    <div class="modal fade" id="show_other_dues" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">
              <span aria-hidden="true">&times;</span>
              <span class="sr-only">Close</span>
            </button>
            <h4 class="modal-title">Show all dues of <i id="client_name">{{ isset($sale) ? $sale->client_name : '' }}</i></h4>
          </div>
          <table class="table">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Bill ID</th>
                  <th>Due</th>
                </tr>
              </thead>
              <tbody>
              @forelse( (isset($dues) ? $dues : []) as $sale )
              <tr>
                <td>{{ $sale->created_at }}</td>
                <td><a  href="{{ route('user::sales.edit', [ 
                          'sale' => $sale,
                          'api_token' => Auth::user()->api_token
                      ]) }}">{{ $sale->bill_id }}</a></td>
                <td><strong><i>
                {{ $sale->getCurrentDueAmountDecimalFormat() . ' ' . MarketPlex\Store::currencyIcon() }}
                </i></strong></td>
              </tr>
              @empty

                  @component('includes.tables.empty-table-message', [ 'colspan' => 6 ])
                     <div class="alert alert-info text-center">No due records found from your this client ...</div>
                  @endcomponent

              @endforelse
              <tr>
                <td colspan="2" style="text-align:right;">Total Due:</td>
                <td><i><strong>
                {{
                    (isset($sale) ? $sale->getTotalDueAmountDecimalFormat() : number_format(0.00, 2) ) . ' ' . MarketPlex\Store::currencyIcon()
                }}</strong></i></td>
              </tr>
              </tbody>
          </table>
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
              
              @component('includes.message.error-summary', [ 'name' => 'sales' ])
                  <ul></ul>
              @endcomponent

              <form id="sale-form">

              {!! csrf_field() !!}

              <div class="box-body">
                    <div class="form-group">
                        <label for="bill_id"><strong>Billing ID:</strong></label>
                        <div class="row">
                          <div class="col-md-12">
                              <input type="text" class="form-control" id="bill_id" name="bill_id" value="{{ isset($sale) ? $sale->bill_id : $bill_id }}" {{ isset($sale) ? 'disabled' : '' }}/>
                              <span class="help-block hidden">
                                  <strong></strong>
                              </span>
                          </div>
                        </div>
                    </div>
                    <div class="form-group">
                      <label for="client"><strong>Business/Client/Company Name:</strong></label>
                      <div class="row">
                        <div class="col-md-10">
                          <input  type="text" name="client" id="client" class="form-control"
                                  value="{{ isset($sale) ? $sale->client_name : '' }}" />
                          <div id="client-autocomplete" class="hidden">
                          </div>
                          <span class="help-block hidden">
                              <strong></strong>
                          </span>
                        </div>
                        <div class="col-md-2">
                          <div class="clearfix">
                            <!-- <a href="#" data-toggle="modal" data-target="#add_client" class="btn btn-info btn-sm float-right">Add</a> -->
                            @if (isset($sale))
                            <a href="#" data-toggle="modal" data-target="#show_other_dues" class="btn btn-info btn-sm float-right">View all dues</a>
                            @endif
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
                          <button type="button" id="add_product_bill" class="btn btn-info btn-sm float-right"
                                  data-toggle="tooltip" data-placement="top" title="{{ $messages['help']['add_product'] }}">Add</button>
                        </div>
                      </div>
                      
                    </div>
                    <table  class="table table-bordered" id="product_bill_table"
                            data-empty-message="{{ $messages['empty_table']['sale_product'] }}">
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
                          @forelse( $sale->productbills as $bill )
                            @include('includes.tables.sales-row-product-bill', [
                                'row_id' => $row++,
                                'product_bill_id' => $bill->id,
                                'product_id' => $bill->product_id,
                                'datetime' => Carbon\Carbon::parse($bill->created_at)->format('m/d/Y h:m'),
                                'product_title' => $bill->product ? $bill->product->title : 'Unknown',
                                'store_name' => $bill->product && $bill->product->store ? $bill->product->store->name : 'Unknown store',
                                'bill_price' => $bill->product ? $bill->product->mrp : 0.00,
                                'bill_quantity' => $bill->quantity,
                                'product_available_quantity' => $bill->product ? $bill->product->available_quantity : 0
                            ])
                          @empty
                            @component('includes.tables.empty-table-message', [ 'colspan' => 6, 'level' => 'info', 'message' => $messages['empty_table']['sale_product'] ])
                                <div class="alert alert-warning">No records added yet</div>
                            @endcomponent
                          @endforelse
                        @else
                            @component('includes.tables.empty-table-message', [ 'colspan' => 6, 'level' => 'info', 'message' => $messages['empty_table']['sale_product'] ])
                                <div class="alert alert-warning">No records added yet</div>
                            @endcomponent
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
                        <button type="button" id="addBillingRow" class="btn btn-info btn-sm fa fa-plus fa-1x float-right"
                                data-toggle="tooltip" data-placement="top" title="{{ $messages['help']['add_shipping'] }}"></button>
                      </div>
                    </div>
                    <table  class="table table-bordered" id="dynamic_field_shipping"
                            data-empty-message="{{ $messages['empty_table']['product_shipping'] }}">
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
                          @forelse( $sale->shippingbills as $bill )
                            @include('includes.tables.sales-row-shipping-bill', [
                                'row_id' => $row++,
                                'shipping_bill_id' => $bill->id,
                                'datetime' => Carbon\Carbon::parse($bill->created_at)->format('m/d/Y h:m'),
                                'shipping_purpose' => $bill->purpose,
                                'bill_amount' => $bill->amount,
                                'bill_quantity' => $bill->quantity ])
                          @empty
                            @component('includes.tables.empty-table-message', [ 'colspan' => 6, 'level' => 'info', 'message' => $messages['empty_table']['product_shipping'] ])
                                <div class="alert alert-warning">No records added yet</div>
                            @endcomponent
                          @endforelse
                        @else
                            @component('includes.tables.empty-table-message', [ 'colspan' => 6, 'level' => 'info', 'message' => $messages['empty_table']['product_shipping'] ])
                                <div class="alert alert-warning">No records added yet</div>
                            @endcomponent
                        @endif
                    </tbody>
                  </table>
                  </div>
                  <div class="form-group">
                  <table class="table table-bordered">
                    <tbody>
                      <tr>
                        <td width="60%"><strong><i>Bill Amount:</i></strong></td>
                        <td width="40%"><strong><i>
                        <span id="grandTotal">{{ (isset($sale) ? $sale->getBillAmountDecimalFormat() : number_format(0.00, 2)) }}</span>
                        {{ ' ' . MarketPlex\Store::currencyIcon() }}</i></strong></td>
                      </tr>
                    </tbody>
                  </table>
                  </div>
                  <div class="row">
                    <div class="col-md-11">
                      <h4><strong>Payment:</strong></h4>
                    </div>
                    <div class="col-md-1">
                      <button type="button" id="addPayRow" class="btn btn-info btn-submit-fix fa fa-plus fa-1x float-right"
                              data-toggle="tooltip" data-placement="top" title="{{ $messages['help']['add_paid_bill'] }}"></button>
                    </div>
                  </div>
                    <div class="form-group">
                    <table  class="table table-bordered" id="dynamic_field_pay"
                            data-empty-message="{{ $messages['empty_table']['bill_payment'] }}">
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
                          @forelse( $sale->billpayments as $payment )
                            @include('includes.tables.sales-row-paid-bill', [
                                'row_id' => $row++,
                                'paid_bill_id' => $payment->id,
                                'datetime' => Carbon\Carbon::parse($payment->created_at)->format('m/d/Y h:m'),
                                'paid_amount' => $payment->amount,
                                'trans_option' => $payment->method ])
                          @empty
                            @component('includes.tables.empty-table-message', [ 'colspan' => 6, 'level' => 'info', 'message' => $messages['empty_table']['bill_payment'] ])
                                <div class="alert alert-warning">No records added yet</div>
                            @endcomponent
                          @endforelse
                        @else
                            @component('includes.tables.empty-table-message', [ 'colspan' => 6, 'level' => 'info', 'message' => $messages['empty_table']['bill_payment'] ])
                                <div class="alert alert-warning">No records added yet</div>
                            @endcomponent
                        @endif
                    </tbody>
                    </table>
                    </div>
                    <div class="form-group">
                      <h4><strong>Dues:</strong></h4>
                      <table class="table table-bordered">
                        <tbody>
                          <tr>
                            <td width="60%"><strong><i>Current Due:</i></strong></td>
                            <td width="40%"><strong>
                            <i id="current_due" class="decimal">{{ isset($sale) ? $sale->getCurrentDueAmountDecimalFormat() : number_format(0.00, 2) }}</i>
                            {{' ' . MarketPlex\Store::currencyIcon() }}</strong></td>
                          </tr>
                          <tr>
                            <td width="60%"><strong><i>Previous Due:</i></strong></td>
                            <td width="40%"><strong><i id="prev_due" class="decimal">{{ isset($sale) ? $sale->getPreviousDueAmountDecimalFormat() : number_format(0.00, 2) }}</i>
                            {{' ' . MarketPlex\Store::currencyIcon() }}</i></strong></td>
                          </tr>
                          <tr>
                            <td width="60%"><strong><i>Total Due ( {{ isset($sale) ? $sale->client_name : 'This Client' }}):</i></strong></td>
                            <td width="40%"><strong><i id="total_due" class="decimal">{{ isset($sale) ? $sale->getTotalDueAmountDecimalFormat() : number_format(0.00, 2) }}</i>
                            {{' ' . MarketPlex\Store::currencyIcon() }}</strong></td>
                          </tr>
                        </tbody>
                      </table>
                  </div>
                  <div class="row">
                    <div class="col-md-8">
                      <h4><strong>Bank Deposit:</strong></h4>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('user::banks.create', [ 'api_token' => Auth::user()->api_token ]) }}" class="btn btn-info btn-sm float-right">Add Bank Account</a>
                    </div>
                    <div class="col-md-1">
                      <button type="button" id="addBankRow" class="btn btn-info btn-sm fa fa-plus fa-1x float-right"
                              data-toggle="tooltip" data-placement="top" title="{{ $messages['help']['add_deposit'] }}"></button>
                    </div>
                  </div>
                    <div class="form-group">
                    <table  class="table table-bordered" id="dynamic_field_bank"
                            data-empty-message="{{ $messages['empty_table']['deposit'] }}">
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
                          @forelse( $sale->deposits as $deposit )
                            @include('includes.tables.sales-row-bank-deposit', [
                                'row_id' => $row++,
                                'bank_deposit_id' => $deposit->id,
                                'datetime' => Carbon\Carbon::parse($deposit->created_at)->format('m/d/Y h:m'),
                                'deposit_method' => $deposit->method,
                                'deposit_amount' => $deposit->amount,
                                'bank_account_no' => $deposit->bank_account_no,
                                'bank_accounts' => MarketPlex\Bank::all()
                            ])
                          @empty
                            @component('includes.tables.empty-table-message', [ 'colspan' => 6, 'level' => 'info', 'message' => $messages['empty_table']['deposit'] ])                               
                                <div class="alert alert-warning">No records added yet</div>
                            @endcomponent
                          @endforelse
                        @else
                            @component('includes.tables.empty-table-message', [ 'colspan' => 6, 'level' => 'info', 'message' => $messages['empty_table']['deposit'] ])
                                <div class="alert alert-warning">No records added yet</div>
                            @endcomponent
                        @endif
                    </tbody>
                    </table>
                    </div>
                    <div class="form-group">
                      <div class="row">
                        <div class="col-md-11">
                          <h4><strong>Expenses:</strong></h4>
                        </div>
                        <div class="col-md-1">
                          <button type="button" id="add_expense_btn" class="btn btn-info btn-sm fa fa-plus fa-1x float-right"
                                  data-toggle="tooltip" data-placement="top" title="{{ $messages['help']['add_expense'] }}"></button>
                        </div>
                      </div>
                      <table  class="table table-bordered" id="dynamic_field_expenses"
                              data-empty-message="{{ $messages['empty_table']['expense'] }}">
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
                          @forelse( $sale->expenses as $expense )
                            @include('includes.tables.sales-row-expense', [
                                'row_id' => $row++,
                                'expense_id' => $expense->id,
                                'datetime' => Carbon\Carbon::parse($expense->created_at)->format('m/d/Y h:m'),
                                'expense_purpose' => $expense->purpose,
                                'expense_amount' => $expense->amount ])
                          @empty
                            @component('includes.tables.empty-table-message', [ 'colspan' => 6, 'level' => 'info', 'message' => $messages['empty_table']['expense'] ])
                                <div class="alert alert-warning">No records added yet</div>
                            @endcomponent
                          @endforelse
                        @else
                            @component('includes.tables.empty-table-message', [ 'colspan' => 6, 'level' => 'info', 'message' => $messages['empty_table']['expense'] ])
                                <div class="alert alert-warning">No records added yet</div>
                            @endcomponent
                        @endif
                        </tbody>
                      </table>
                    </div>
                    <br>
                    <div class="form-group">
                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-info btn-lg btn-block btn-submit-fix"
                                    data-toggle="tooltip" data-placement="top" title="{{ $messages['help']['save_sale'] }}">Save</button>
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
