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
      input[type="number"]{
        text-align: right;
      }
      textarea{
        height: 5em;
      }
      
      .btn_remove {
        color:red;
      }
      /*Italic bolsd amount input*/
      .amount, .paid-amount, .deposit-amount, .multTotal{
          font-weight: bold;
          font-style: italic;
          text-align: right;
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
          onLoad: function(data) {},
          request: function() {

              // alert(serviceUrl);
              $.get(this.serviceUrl, this.onLoad).fail(function(jqXHR, textStatus, errorThrown) {
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
             $('#dynamic_field_shipping').append('<tr class="ship_bill" id="row'+i+'"><td>' + d.toLocaleDateString() + '</td><td><input id="purpose" type="text" name="purpose" class="form-control" required></td><td><input id="amount" class="amount form-control" name="amount" type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.which === 8" required></td><td><input id="quantity" class="form-control" name="quantity" required="required" type="number" min="0" value="0" onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.which === 8"></td><td><strong><i><span class="multTotal">0.00</span></i></strong></td><td><a href="" name="remove" id="'+i+'" class="btn_remove">X</a></td></tr>');  
        });   

        $('#addPayRow').click(function(){  
             i++;  
             $('#dynamic_field_pay').append('<tr id="row'+i+'" class="dynamic-added bill_payment"><td><p>' + d.toLocaleDateString() + '</p></td><td><select class="form-control" id="trans_option"><option>By Cash (hand to hand)</option><option>By Cash (cheque deposit)</option><option>By Cash (electronic transfer)</option><option>By Cheque (hand to hand)</option></select></td><td><input id="paid_amount" name="paid_amount" class="paid-amount form-control" required="required" type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.which === 8"></td><td><a href="" name="remove" id="'+i+'" class="btn_remove">X</a></td></tr>');
        });
        
        $('#addBankRow').click(function(){  
             i++;  
             $('#dynamic_field_bank').append('<tr id="row'+i+'" class="dynamic-added"><td><p>' + d.toLocaleDateString() + '</p></td><td><select class="form-control" id="deposit_method" row-id="'+i+'"><option>Bank</option><option>Vault</option></select></td><td><input id="bank_title" name="bank_title" class="form-control" required="required" type="text" row-id="'+i+'"></td><td><select class="form-control" id="bank_ac_no" row-id="'+i+'"><option>151035654646001</option><option>151035654646002</option><option>151035654646003</option></select></td><td><input id="deposit_amount" name="deposit_amount" class="deposit-amount form-control" required="required" type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.which === 8" row-id="'+i+'"></td><td><a href="" name="remove" id="'+i+'" class="btn_remove">X</a></td></tr>');
        });

        $('#add_product_bill').click(function(){ 
            
          i++;

          DataManager.serviceUrl = '/api/v1/products/' + $('#product_name').val() + '/price?api_token={{ Auth::user()->api_token }}';
          DataManager.onLoad = function(data) {

              priceCollection["row" + i] = data.price;

              $('#product_bill_table').append('<tr id="row'+i+'" class="product_bill" > <td>' + d.toLocaleDateString() + '</td> <td><p>' + data.title + '</p></td> <td><p>' + data.store_name + '</p></td> <td><input id="product_quantity" name="product_quantity" required="required" type="number" min="0" class="form-control" onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.which === 8"></td> <td><strong class="multTotal"><i>0.00</i></strong></td> <td><a href="" name="remove" id="'+i+'" class="btn_remove">X</a></td> </tr>');
          };
          DataManager.request();

        });
        
        $(document).on('click', '.btn_remove', function(e){
             e.preventDefault();
             var button_id = $(this).attr("id");
             $('#row'+button_id+'').remove();
             multInputs();
             calculateDue();
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
        

         function multInputs() {

             var grandTotal = 0.0;
              // for each row:
              $("tr.ship_bill").each(function () {
                 // get the values from this row:
                 var amount = $('#amount', this).val();
                 var quantity = $('#quantity', this).val();
                 var total = (amount * 1.0) * (quantity * 1.0);
                 $('.multTotal',this).text(total);
                 grandTotal += total;
              });
              $("tr.product_bill").each(function () {

                var quantity = $('#product_quantity', this).val();
                var unitPrice = priceCollection[this.id];
                var total = (unitPrice * 1.0) * (quantity * 1.0);
                $('.multTotal', this).text(total);
                grandTotal += total;
                $("#grandTotal").text(grandTotal);
                $("#current_due").text(grandTotal);
                // $("#prev_due").text(grandTotal);
              });
              $("#grandTotal").text(grandTotal);
              $("#current_due").text(grandTotal);
              // $("#prev_due").text(grandTotal);
         }

         function calculateDue()
         {
             var totalPaid = 0.0;
             var grandTotalBill = $("#grandTotal").text();
              // for each row:
              $("tr.bill_payment").each(function () {
                 // get the values from this row:
                 var amount = $('#paid_amount', this).val();
                 totalPaid += amount * 1.0;
              });

              var grandTotalDue = (grandTotalBill * 1.0) - totalPaid;
              if (grandTotalDue < 0.0)
              {
                  alert("Client has paid more than due payment!" );
              }
              if (grandTotalDue == 0.0)
              {
                  alert("Client has paid full due payment!" );
              }
              $("#current_due").text(grandTotalDue);
         }
         
         $("tbody").on('change', '.ship_bill input', multInputs);
         $("tbody").on('change', '.product_bill input', multInputs);
         $("tbody").on('change', '.bill_payment input', calculateDue);

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
                            <input type="text" class="form-control" name="bill_id" value="" />
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
                      <label for="poduct_billing"><strong>Product Billing:</strong></label>
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
                    
                      <!--jQuery will add input fileds here-->
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
                    </tbody>
                  </table>
                  </div>
                  <table class="table table-bordered">
                    <tbody>
                      <tr>
                        <td width="50%"><strong><i>Bill Amount:</i></strong></td>
                        <td><strong><i><span id="grandTotal">0.00</span></i></strong></td>
                      </tr>
                    </tbody>
                  </table>
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
                    </tbody>
                    </table>
                    </div>
                    <h4 class="text-center"><strong>Dues</strong></h4>
                  <table class="table table-bordered">
                    <tbody>
                      <tr>
                        <td width="50%"><strong><i>Current Due:</i></strong></td>
                        <td><strong><i id="current_due">0.00</i></strong></td>
                      </tr>
                      <tr>
                        <td><strong><i>Previous Due:</i></strong></td>
                        <td><strong><i id="prev_due">0.00</i></strong></td>
                      </tr>
                    </tbody>
                  </table>
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

