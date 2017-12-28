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

      $(document).ready(function(){      
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

            var rowTemplate = 'sales-row-bank-deposit';
            DataManager.serviceUrl = '/api/v1/templates/' + rowTemplate + '?api_token={{ Auth::user()->api_token }}';
            DataManager.payload = {
              row_id: i,
              datetime: d.toLocaleDateString(),
              "bank_accounts[]": [ '151035654646001', '151035654646002', '151035654646003' ]
            };
            DataManager.onLoad = function(data) {

                $('#dynamic_field_bank').append(data);
            };
            DataManager.request();
        });

        $('#add_product_bill').click(function(){ 
            
          i++;

          DataManager.serviceUrl = '/api/v1/products/' + $('#product_name').val() + '/price?api_token={{ Auth::user()->api_token }}';
          DataManager.onLoad = function(data) {

              priceCollection["row" + i] = data.price;

              var rowTemplate = 'sales-row-product-bill';
              DataManager.serviceUrl = '/api/v1/templates/' + rowTemplate + '?api_token={{ Auth::user()->api_token }}';
              DataManager.payload = {
                row_id: i,
                datetime: d.toLocaleDateString(),
                product_title: data.title,
                store_name: data.store_name,
                product_available_quantity: data.available_quantity
              };
              DataManager.onLoad = function(data) {

                  $('#product_bill_table').append(data);
              };
              DataManager.request();
          };
          DataManager.request();

        });
        
        $(document).on('click', '.btn_remove', function(e){
             e.preventDefault();
             var button_id = $(this).attr("id");
             $('#row'+button_id+'').remove();
             multInputs();
        }); 

        $("tbody").on('change', "#deposit_method", function() {
          var rowId = $(this).attr("row-id");
          if ($(this).val() == 'Vault'){
            $("#bank_title[row-id='"+rowId+"'], #bank_ac_no[row-id='"+rowId+"']").hide();
          }
          else if ($(this).val() == 'Bank'){
        	$("#bank_title[row-id='"+rowId+"'], #bank_ac_no[row-id='"+rowId+"']").show();
          }
        
        });

        function Decimal(numberText)
        {
            if (Number(numberText) == 0.0)
            {
                return Number(numberText).toPrecision(3);
            }
            if (numberText.toString().indexOf('.') > -1)
            {
                return Number(numberText);
            }
            return Number(numberText).toPrecision(numberText.toString().length + 2);
        }
        

        function multInputs() {

             var grandTotal = 0.0;
              // for each row:
              $("tr.ship_bill").each(function () {
                 // get the values from this row:
                 var amount = $('#bill_amount', this).val();
                 $('#bill_amount', this).val(Decimal(amount));
                 var quantity = $('#bill_quantity', this).val();
                 var total = Number(amount) * Number(quantity);

                 $('.multTotal',this).text(Decimal(total));
                 grandTotal += total;
              });
              $("tr.product_bill").each(function () {

                  var quantity = $('#product_quantity', this).val();
                  var unitPrice = priceCollection[this.id];
                  var total = Number(unitPrice) * Number(quantity);
                  $('.multTotal', this).text(Decimal(total));
                  grandTotal += total;
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
                 var amount = Number($('#paid_amount', this).val());
                  $('#paid_amount', this).val(amount);
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

                  var amount = Number($('#expense_amount', this).val());
                  $('#expense_amount', this).val(Decimal(amount));
                  totalExpense += amount;
              });
              $("tr.bank_deposit").each(function () {

                  var amount = Number($('#deposit_amount', this).val());
                  $('#deposit_amount', this).val(Decimal(amount));
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
          <div class="modal-body">
              <div class="form-group">
                <input type="text" name="client_name" class="form-control" placeholder="Client/Company/Business Name" required />
              </div>
              <div class="form-group">
                <textarea class="form-control" rows="5" name="client_info" id="client_info" placeholder="Client/Company/Business Info" required></textarea>
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
            <h4 class="modal-title">Add Bank</h4>
          </div>
          {{ Form::open(['route' => [ 'user::clients.store', '' ] ]) }}
          <div class="modal-body">
              <div class="form-group">
                <input type="text" name="bank_name" class="form-control" placeholder="Bank Name" required />
              </div>
              <div class="form-group">
                <input type="text" name="bank_branch" class="form-control" placeholder="Branch Name" required />
              </div>
              <div class="form-group">
                <textarea class="form-control" rows="5" name="bank_info" id="bank_info" placeholder="Bank Info" required></textarea>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
            <button type="sumbit" class="btn btn-info ">Save</button>
          </div>
          {{ Form::close() }}
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
             
              {{ Form::open(['route' => [ isset($sale) ? 'user::sales.update' : 'user::sales.store', isset($sale) ? $sale : '' ] ]) }}

              <div class="box-body">
                    <div class="form-group">
                        <label for="bill_id"><strong>Billing ID:</strong></label>
                        <div class="col-md-12">
                            <input type="text" class="form-control" name="bill_id" value="{{ isset($sale) ? $sale->bill_id : '' }}" />
                        </div>
                    </div>
                    <div class="form-group">
                      <label for="selected_client_name"><strong>Business/Client/Company Name:</strong></label>
                      <div class="row">
                        <div class="col-md-10">

                          <input type="text" name="client" id="client" class="form-control" value="{{ isset($sale) ? $sale->client_name : '' }}" />

                          @if ($errors->has('client'))
                              <span class="help-block">
                                <strong>{{ $errors->first('client') }}</strong>
                              </span>
                          @endif
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
                          <input type="hidden" name="product_id" value="{{ 1 }}" />
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
                                'datetime' => Carbon\Carbon::createFromFormat('m/d/Y', $bill->created_at),
                                'product_title' => $bill->title,
                                'store_name' => $bill->store_name,
                                'bill_price' => $bill->product->mrp,
                                'bill_quantity' => $bill->quantity ])
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
                                'datetime' => Carbon\Carbon::createFromFormat('m/d/Y', $bill->created_at),
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
                        <td width="60%"><strong><i>Bill Amount:</i></strong></td>
                        <td width="40%"><strong><i><span id="grandTotal">0.00</span></i></strong></td>
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
                                'paid_bill_id' => $bill->id,
                                'datetime' => Carbon\Carbon::createFromFormat('m/d/Y', $payment->created_at),
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
                            <td width="60%"><strong><i>Current Due:</i></strong></td>
                            <td width="40%"><strong><i id="current_due" class="decimal">0.00</i></strong></td>
                          </tr>
                          <tr>
                            <td width="60%"><strong><i>Previous Due:</i></strong></td>
                            <td width="40%"><strong><i id="prev_due" class="decimal">0.00</i></strong></td>
                          </tr>
                          <tr>
                            <td width="60%"><strong><i>Total Due (This Client):</i></strong></td>
                            <td width="40%"><strong><i id="total_due" class="decimal">0.00</i></strong></td>
                          </tr>
                        </tbody>
                      </table>
                  </div>
                  <div class="row">
                    <div class="col-md-8">
                      <h4><strong>Bank Deposit</strong></h4>
                    </div>
                    <div class="col-md-3">
                        <a href="#" data-toggle="modal" data-target="#add_bank" class="btn btn-info btn-sm float-right">Add Bank</a>
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
                                'bank_deposit_id' => $bill->id,
                                'datetime' => Carbon\Carbon::createFromFormat('m/d/Y', $deposit->created_at),
                                'deposit_method' => $deposit->method,
                                'deposit_amount' => $deposit->amount,
                                'bank_account_no' => $deposit->bank_account_no,
                                'bank_title' => $deposit->bank_title ])
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
                                'expense_id' => $bill->id,
                                'datetime' => Carbon\Carbon::createFromFormat('m/d/Y', $expense->created_at),
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
            <!--</form>-->
            {{ Form::close() }}
          </div>
              <!--end of form-->

            </div>
          </div>
    </div>
</div>
@endsection

