@extends('layouts.app-store-front')
@section('title', 'Sales')
@section('title-module-name', 'Sales')

@section('header-styles')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <style>
      td, th, input {
        text-align: center;
        vertical-align: middle;
      }
      .top_card{
        margin-bottom: 30px;
      }
    </style>
@endsection

@section('footer-scripts')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="/vendor/inzaana/js/product/product.js" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue"></script>
    <script>
    // $("#showName").click(function(e){
    //   e.preventDefault();
    //   $("#showNameFiled").slideToggle();
    // });
    // $("#showStore").click(function(e){
    //   e.preventDefault();
    //   $("#showStoreFiled").slideToggle();
    // });
    // $("#showProduct").click(function(e){
    //   e.preventDefault();
    //   $("#showProductFiled").slideToggle();
    // });
    $(document).ready(function(){      
      var i=1;  

      $('#addBillingRow').click(function(){  
           i++;  
           $('#dynamic_field_shipping').append('<tr id="row'+i+'" class="dynamic-added"><td><input id="purpose" name="purpose" required="required" type="text"></td><td><input id="amount" name="amount" required="required" type="text" v-model="value"></td><td><input id="quantity" name="quantity" required="required" type="number" min="0" value="0" v-model="quantity"></td><td><p >@{{ calc }}</p></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn-sm btn_remove">X</button></td></tr>');  
      });  

      $('#addPayRow').click(function(){  
           i++;  
           $('#dynamic_field_pay').append('<tr id="row'+i+'" class="dynamic-added"><td><p>12/10/17</p></td><td><select class="form-control" id="trans_option"><option>By Cash (hand to hand)</option><option>By Cash (cheque deposit)</option><option>By Cash (electronic transfer)</option><option>By Cheque (hand to hand)</option></select></td><td><input id="paid_amount" name="paid_amount" required="required" type="text"></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn-sm btn_remove">X</button></td></tr>');
      });
      
      $('#addBankRow').click(function(){  
           i++;  
           $('#dynamic_field_bank').append('<tr id="row'+i+'" class="dynamic-added"><td><p>12/10/17</p></td><td><input id="bank_title" name="bank_title" required="required" type="text"></td><td><select class="form-control" id="bank_ac_no"><option>151035654646001</option><option>151035654646002</option><option>151035654646003</option></select></td><td><input id="paid_amount" name="paid_amount" required="required" type="text"></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn-sm btn_remove">X</button></td></tr>');
      });
      
      $(document).on('click', '.btn_remove', function(){  
           var button_id = $(this).attr("id");   
           $('#row'+button_id+'').remove();  
      }); 

  }); 
  
        
  var app=new Vue({
    		el: "#app",
    		data: {
    	    value: '',
    	    quantity: '',
    	    total: 0
    	  },
    	computed: {
        		
        	calc: function() {
    			
          return this.total = parseFloat(this.value) * parseFloat(this.quantity); 			
    
          },
    	}
  });
    </script>
@endsection

@section('modals')
    <div id="modal_container">{{--Modal load here--}}</div>
@endsection

@section('content')
@include('includes.message.message') 
<div class="wow fadeIn" data-wow-delay="0.2s" id="app">
  <!--<div class="row">-->
  <!--      <div class="col-12">-->
  <!--        <div class="card top_card">-->
  <!--          <form class="form-horizontal" method="post" action="">-->
  <!--            {{ csrf_field() }}-->
  <!--            <div class="card-header text-center"><h4><strong>Daily Income/Expense Entry</strong></h4></div>-->
  <!--            <div class="card-block">-->
  <!--            <br>-->
  <!--            <h3>Transaction</h3>-->
  <!--            <table class="table table-bordered">-->
  <!--        			<thead>-->
  <!--        			  <tr>        -->
  <!--        				<th>Amount</th>-->
  <!--        				<th>Purpose</th>-->
  <!--        				<th>Type</th>-->
  <!--        			  </tr>-->
  <!--        			</thead>-->
  <!--        			<tbody>-->
  <!--        			  <tr>-->
  <!--        			      <td><input id="ac_name" name="ac_name" required="required" type="text"></td>-->
  <!--        				    <td><input id="p_name" name="p_name" required="required" type="text"></td>-->
  <!--        				    <td>-->
  <!--      			        <select class="form-control" id="shipping">-->
  <!--                            <option>Income</option>-->
  <!--                            <option>Expense</option>-->
  <!--                    </select>-->
  <!--        			      </td>-->
  <!--        			  </tr>-->
  <!--        			</tbody>-->
  <!--          	</table>-->
  <!--          	<div class="form-group">-->
  <!--              <strong><i class="col-md-11">Stored Amount:</i><span class="col-md-1 float-right" style="padding-right:10em">6469</span></strong>-->
  <!--            </div>-->
  <!--            <div class="form-group">-->
  <!--                <div class="col-md-6 col-sm-6 col-xs-12">-->
  <!--                    <button type="submit" class="btn btn-primary btn-submit-fix">Show Income</button>-->
  <!--                </div>-->
  <!--            </div>  -->
  <!--            </div>  -->
  <!--          </form>  -->
  <!--        </div>-->
  <!--      </div>-->
        <div class="col-12">
          <!--SHIPPING METHOD-->
          <div class="card">
            <form class="form-horizontal" method="post" action="">
              {{ csrf_field() }}
              <div class="card-header text-center"><h4><strong>Sales Data</strong></h4></div>
              <div class="card-block">
                    <div class="form-group">
                        <label for="bill_id"><strong>Billing ID:</strong></label>
                        <div class="col-md-12">
                            <input type="text" name="bill_id" value="" />
                        </div>
                    </div>
                    <div class="form-group">
                      <label for="client_name"><strong>Business/Client/Company Name:</strong></label>
                      <div class="row">
                        <div class="col-md-10">
                          <select class="form-control" id="client_name">
                            <option>Name 1</option>
                            <option>Name 2</option>
                            <option>Name 3</option>
                            <option>Name 4</option>
                            <option>Name 5</option>
                          </select>
                        </div>
                        <div class="col-md-2">
                          <button id="showName" class="btn btn-success btn-sm float-right"><strong>Add</strong></button>
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
                    <div class="col-md-10">
                      <label for="poduct_billing"><strong>Product Billing:</strong></label>
                    </div>
                    <div class="col-md-2">
                      <button id="add_product_row" class="btn btn-success btn-sm float-right"><strong>Add</strong></button>
                    </div>
                  </div>
                  <table class="table table-bordered">
            			<thead>
            			  <tr>        
            				<th width="25%">Title</th>
            				<th width="25%">Store</th>
            				<th width="10%">Quantity</th>
            				<th width="30%">Total</th>
            				<th width="10%">#</th>
            			  </tr>
            			</thead>
            			<tbody>
            			  <tr>
        			        <td><p>Product Name</p></td>
        			        <td><p>Store Name</p></td>
        				    <td><input id="store_name" name="store_name" required="required" type="number" min="0"></td>
        				    <td><p>123</p></td>
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
                      <div class="col-md-1">
                        <button type="button" id="addBillingRow" class="btn btn-success btn-sm fa fa-plus fa-3x float-right"></button>
                      </div>
                    </div>
                    <table class="table table-bordered" id="dynamic_field_shipping">
              			<thead>
              			  <tr>        
              				<th width="25%">Purpose</th>
              				<th width="25%">Amount</th>
              				<th width="10%">Quantity</th>
              				<th width="30%">Total</th>
              				<th width="10%">#</th>
              			  </tr>
              			</thead>
              			<tbody>
              			  <tr>
          			        <td>
          			          <input id="purpose" name="purpose" required="required" type="text">
          			        </td>
          			        <td>
          			          <input id="amount" name="amount" required="required" type="text"  v-model="value">
          			          </td>
          				      <td>
          				        <input id="quantity" name="quantity" required="required" type="number" min="0" value="0"  v-model="quantity">
          				      </td>
          				      <td>
          				        <p>@{{ calc }}</p>
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
                        <td><strong>65656</strong></td>
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
          				      <td><input id="paid_amount" name="paid_amount" required="required" type="text"></td>
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
                        <td><strong>586</strong></td>
                      </tr>
                      <tr>
                        <td><strong><i>Previous Due:</i></strong></td>
                        <td><strong>256</strong></td>
                      </tr>
                    </tbody>
                  </table>
                  <div class="row">
                    <div class="col-md-11">
                      <h4><strong>Bank Deposit</strong></h4>
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
              				<th width="25%">Bank Title</th>
              				<th width="25%">Account No</th>
              				<th width="30%">Deposit Amount</th>
              				<th width="10%">#</th>
              			  </tr>
              			</thead>
              			<tbody>
              			  <tr>
              			    <td><p>12/10/17</p></td>
              			    <td><input id="bank_title" name="bank_title" required="required" type="text"></td>
          			        <td>
          			        <select class="form-control" id="bank_ac_no">
                          <option>151035654646001</option>
                          <option>151035654646002</option>
                          <option>151035654646003</option>
                        </select>
          			        </td>
          			        <td><input id="paid_amount" name="paid_amount" required="required" type="text"></td>
          			        <td>
          				      </td>
              			  </tr>
            			  </tbody>
            		    </table>
                    </div>
                    <br>
                    <div class="form-group">
                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-primary btn-submit-fix">Save Order</button>
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

