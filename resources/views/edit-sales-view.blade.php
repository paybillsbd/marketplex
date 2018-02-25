@extends('layouts.app-store-front')
@section('title', 'Edit Sales')
@section('title-module-name', 'Edit Sales')

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
      
      a{
        color:red;
      }
      /*Italic bolsd amount input*/
      #amount, #paid_amount, #deposit_amount{
          font-weight: bold;
          font-style: italic;
      }
      
    </style>
@endsection

@section('footer-scripts')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="/vendor/inzaana/js/product/product.js" type="text/javascript"></script>
    <script>
    $(document).ready(function(){      
      
      $('#addPayRow').click(function(){  
           i++;  
           $('#dynamic_field_pay').append('<tr id="row'+i+'" class="dynamic-added"><td><p>12/10/17</p></td><td><select class="form-control" id="trans_option"><option>By Cash (hand to hand)</option><option>By Cash (cheque deposit)</option><option>By Cash (electronic transfer)</option><option>By Cheque (hand to hand)</option></select></td><td><input id="paid_amount" name="paid_amount" required="required" type="number" min="0"></td><td><a href="" name="remove" id="'+i+'" class="btn_remove">X</a></td></tr>');
      });
      
      $('#addBankRow').click(function(){  
           i++;  
           $('#dynamic_field_bank').append('<tr id="row'+i+'" class="dynamic-added" ><td><p>12/10/17</p></td><td><select class="form-control" id="deposit_method"><option>Bank</option><option>Vault</option></select></td><td><input id="bank_title" name="bank_title" required="required" type="text"></td><td><select class="form-control" id="bank_ac_no"><option>151035654646001</option><option>151035654646002</option><option>151035654646003</option></select></td><td><input id="deposit_amount" name="deposit_amount" required="required" type="number" min="0"></td><td><a href="" name="remove" id="'+i+'" class="btn_remove">X</a></td></tr>');
      });
      
      $(document).on('click', '.btn_remove', function(e){
           e.preventDefault();
           var button_id = $(this).attr("id");
           $('#row'+button_id+'').remove();
      }); 
      
      $('select').change(function() {
          var selected = $('#deposit_method option:selected');
          if(selected.html() == 'Vault'){
            $('#bank_title , #bank_ac_no').hide();
          }else{
            $('#bank_title , #bank_ac_no').show();
          }
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
          <!--SHIPPING METHOD-->
          <div class="card">
            <form class="form-horizontal" method="post" action="">
              {{ csrf_field() }}
              <div class="card-header text-center"><h4><strong>Edit Sales Data</strong></h4></div>
              <div class="card-block">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="bill_id"><strong>Billing ID:</strong></label>
                        <p>1</p>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="selected_client_name"><strong>Business/Client/Company Name:</strong></label>
                        <p>Client Name</p>
                      </div>
                    </div>
                  </div>
                  <h4><strong>Billing</strong></h4>
                  <div class="form-group">
                  <div class="row">
                    <div class="col-md-10">
                      <label for="poduct_billing"><strong>Product Billing:</strong></label>
                    </div>
                  </div>
                  <table class="table table-bordered">
            			<thead>
            			  <tr>
          			    <th width="10%">Date</th>
            				<th width="20%">Title</th>
            				<th width="20%">Store</th>
            				<th width="10%">Quantity</th>
            				<th width="30%">Total</th>
            				<th width="10%">#</th>
            			  </tr>
            			</thead>
            			<tbody>
            			  <tr>
            			    <td>12/12/17</td>
        			        <td><p>Product Name</p></td>
        			        <td><p>Store Name</p></td>
          				    <td><strong><i><spa>3</span></i></strong></td>
          				    <td><strong><i>238.00</i></strong></td>
          				    <td></td>
            			  </tr>
            			</tbody>
            		  </table>
                    </div>
                    <div class="form-group">
                    <div class="row">
                      <div class="col-md-11">
                        <label for="shipping_billing"><strong>Shipping Billing:</strong></label>
                      </div>
                    </div>
                    <table class="table table-bordered" id="dynamic_field_shipping">
              			<thead>
              			  <tr>        
              				<th width="10%">Date</th>
              				<th width="20%">Purpose</th>
              				<th width="20%">Amount</th>
              				<th width="10%">Quantity</th>
              				<th width="30%">Total</th>
              				<th width="10%">#</th>
              			  </tr>
              			</thead>
              			<tbody>
              			  <tr class="ship_bill">
          			        <td>
          			          12/12/17
          			        </td>
          			        <td>
          			          <p>Purpose Name</p>
          			        </td>
          			        <td>
          			          <p>213.00</p>
          			        </td>
          				      <td>
          				        <p>5</p>
          				      </td>
          				      <td>
          				        <strong><i><span class="multTotal">238.00</span></i></strong>
          				      </td>
          				      <td>
          				      </td>
              			  </tr>
              			</tbody>
            		  </table>
                  </div>
                  <table class="table table-bordered">
                    <tbody>
                      <tr>
                        <td width="50%"><strong><i>Bill Amount:</i></strong></td>
                        <td><strong><i><span id="grandTotal">230.00</span></i></strong></td>
                      </tr>
                    </tbody>
                  </table>
                  <div class="row">
                    <div class="col-md-11">
                      <h4><strong>Payment</strong></h4>
                    </div>
                    <div class="col-md-1">
                      <button type="button" id="addPayRow" class="btn btn-success btn-sm fa fa-plus fa-3x float-right"></button>
                    </div>
                  </div>
                    <div class="form-group">
                    <table class="table table-bordered" id="dynamic_field_pay">
              			<thead>
              			  <tr>
              			  <th width="25%">Paid Date</th>       
              				<th width="25%">Payment method</th>
              				<th width="40%">Paid Amount</th>
              				<th width="10%">#</th>
              			  </tr>
              			</thead>
              			<tbody>
              			  <tr>
              			    <td><p>12/10/17</p></td>
          			        <td>
          			        <select class="form-control" id="trans_option">
                          <option>By Cash (hand to hand)</option>
                          <option>By Cash (cheque deposit)</option>
                          <option>By Cash (electronic transfer)</option>
                          <option>By Cheque (hand to hand)</option>
                        </select>
          			        </td>
          				      <td><input id="paid_amount" name="paid_amount" required="required" type="number"  min="0"></td>
          				      <td>
          				      </td>
              			  </tr>
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
                    <div class="col-md-10">
                      <h4><strong>Bank Deposit</strong></h4>
                    </div>
                    <div class="col-md-1">
                      <div class="clearfix">
                      	<a href="#" data-toggle="modal" data-target="#add_bank" class="btn btn-info btn-sm float-right">Add Bank</a>
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
                                  <input type="text" name="bank_name" placeholder="Bank Name" required />
                                </div>
                                <div class="form-group">
                                  <input type="text" name="bank_branch" placeholder="Branch Name" required />
                                </div>
                                <div class="form-group">
                                  <textarea class="form-control" rows="5" name="bank_info" id="bank_info" placeholder="Bank Info" required></textarea>
                    				    </div>
                    				</div>
                    				<div class="modal-footer">
                    					<button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
                    					<button type="sumbit" class="btn btn-success">Save</button>
                    				</div>
                    			</div>
                    		</div>
                      </div>
                    </div>
                    <div class="col-md-1">
                      <button type="button" id="addBankRow" class="btn btn-success btn-sm fa fa-plus fa-3x float-right"></button>
                    </div>
                  </div>
                    <div class="form-group">
                    <table class="table table-bordered" id="dynamic_field_bank">
              			<thead>
              			  <tr>  
              			  <th width="10%">Deposit Date</th>
              			  <th width="10%">Deposit Method</th>
              				<th width="25%">Bank Title</th>
              				<th width="25%">Account No</th>
              				<th width="20%">Deposit Amount</th>
              				<th width="10%">#</th>
              			  </tr>
              			</thead>
              			<tbody>
              			  <tr>
              			    <td><p>12/10/17</p></td>
              			    <td>
              			      <select class="form-control" id="deposit_method">
                            <option>Bank</option>
                            <option>Vault</option>
                          </select>
                        </td>
              			    <td><input id="bank_title" name="bank_title" required="required" type="text"></td>
          			        <td>
          			        <select class="form-control" id="bank_ac_no">
                          <option>151035654646001</option>
                          <option>151035654646002</option>
                          <option>151035654646003</option>
                        </select>
          			        </td>
          			        <td><input id="deposit_amount" name="deposit_amount" required="required" type="number" min="0"></td>
          			        <td>
          				      </td>
              			  </tr>
            			  </tbody>
            		    </table>
                    </div>
                    <br>
                    <div class="form-group">
                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-primary btn-submit-fix">Save</button>
                        </div>
                    </div>
              </div>
            </form>
          </div>
          <!--SHIPPING METHOD END-->
      </div>
<!--form end here-->
  </div>
</div>
@endsection

