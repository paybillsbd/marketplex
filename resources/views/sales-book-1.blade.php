@extends('layouts.app-dashboard-admin')
@section('title', 'Sales')
@section('title-module-name', 'Sales')

@section('header-styles')
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
      
    </style>
@endsection

@section('footer-scripts')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="/vendor/inzaana/js/product/product.js" type="text/javascript"></script>
    <script>
    $(document).ready(function(){      
      var i=1;  

      $('#addBillingRow').click(function(){  
           i++;  
           $('#dynamic_field_shipping').append('<tr class="ship_bill" id="row'+i+'"><td>12/12/17</td><td><input id="purpose" type="text" name="purpose" class="form-control" required></td><td><input id="amount" class="amount form-control" name="amount" type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.which === 8" required></td><td><input id="quantity" class="form-control" name="quantity" required="required" type="number" min="0" value="0" onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.which === 8"></td><td><strong><i><span class="multTotal">0.00</span></i></strong></td><td><a href="" name="remove" id="'+i+'" class="btn_remove">X</a></td></tr>');  
      });   

      $('#addPayRow').click(function(){  
           i++;  
           $('#dynamic_field_pay').append('<tr id="row'+i+'" class="dynamic-added"><td><p>12/10/17</p></td><td><select class="form-control" id="trans_option"><option>By Cash (hand to hand)</option><option>By Cash (cheque deposit)</option><option>By Cash (electronic transfer)</option><option>By Cheque (hand to hand)</option></select></td><td><input id="paid_amount" name="paid_amount" class="paid-amount form-control" required="required" type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.which === 8"></td><td><a href="" name="remove" id="'+i+'" class="btn_remove">X</a></td></tr>');
      });
      
      $('#addBankRow').click(function(){  
           i++;  
           $('#dynamic_field_bank').append('<tr id="row'+i+'" class="dynamic-added" ><td><p>12/10/17</p></td><td><select class="form-control" id="deposit_method" row-id="'+i+'"><option>Bank</option><option>Vault</option></select></td><td><input id="bank_title" name="bank_title" class="form-control" required="required" type="text" row-id="'+i+'"></td><td><select class="form-control" id="bank_ac_no" row-id="'+i+'"><option>151035654646001</option><option>151035654646002</option><option>151035654646003</option></select></td><td><input id="deposit_amount" name="deposit_amount" class="deposit-amount form-control" required="required" type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.which === 8" row-id="'+i+'"></td><td><a href="" name="remove" id="'+i+'" class="btn_remove">X</a></td></tr>');
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
      

       function multInputs() {
           var mult = 0.0;
           // for each row:
           $("tr.ship_bill").each(function () {
               // get the values from this row:
               var amount = $('#amount', this).val();
               var quantity = $('#quantity', this).val();
               var total = (amount * 1) * (quantity * 1)
               $('.multTotal',this).text(total);
               mult += total;
           });
           $("#grandTotal").text(mult);
       }
       
       $("tbody").on('change', '.ship_bill input', multInputs);

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
        </div>
      </div>
    </div>
@endsection

@section('content')
@include('includes.message.message') 
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
                          <select class="form-control" id="selected_client_name">
                            <option>Name 1</option>
                            <option>Name 2</option>
                            <option>Name 3</option>
                            <option>Name 4</option>
                            <option>Name 5</option>
                          </select>
                        </div>
                        <div class="col-md-2">
                          <div class="clearfix">
                            <a href="#" data-toggle="modal" data-target="#add_client" class="btn btn-info btn-sm float-right">Add</a>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="store_name_input"><strong>Store:</strong></label>
                      <div class="row">
                        <div class="col-md-12">
                          <select class="form-control" id="store_name_input">
                            <option>Store 1</option>
                            <option>Store 2</option>
                            <option>Store 3</option>
                            <option>Store 4</option>
                            <option>Store 5</option>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="product_name"><strong>Product:</strong></label>
                      <div class="row">
                        <div class="col-md-12">
                          <input type="hidden" name="product_id" value="{{ 1 }}" />
                          <select class="form-control" id="product_name">
                            <option>Product 1</option>
                            <option>Product 2</option>
                            <option>Product 3</option>
                            <option>Product 4</option>
                            <option>Product 5</option>
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
                        <a href="#" data-toggle="modal" data-target="#add_billing_data" class="btn btn-info btn-sm float-right">Add</a>
                      </div>
                    </div>
                    
                  </div>
                  <table class="table table-bordered">
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
                    <tr>
                      <td>12/12/17</td>
                      <td><p>Product Name</p></td>
                      <td><p>Store Name</p></td>
                      <td><input id="store_quantity" name="store_quantity" required="required" type="number" min="0" class="form-control" onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.which === 8"></td>
                      <td><strong class="multTotal"><i>659.00</i></strong></td>
                      <td><a href="" name="remove" id="0" class="btn_remove">X</a></td>
                    </tr>
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
                        <td><strong></i>586</i></strong></td>
                      </tr>
                      <tr>
                        <td><strong><i>Previous Due:</i></strong></td>
                        <td><strong><i>256</i></strong></td>
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

