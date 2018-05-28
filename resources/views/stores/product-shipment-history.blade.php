@extends('layouts.app-dashboard-admin')
@section('title', 'Products Shipments')
@section('title-module-name')
<a href="{{ route('user::stores') }}">Store</a>
{{ ' > ' }}
<a href="{{ route('user::store.products', [ 'store' => $product->store , 'api_token' => Auth::user()->api_token]) }}">Products</a>
{{ ' > ' }}
{{ 'Shipments' }}
@endsection

@section('modals')

  @component('includes.modals.modal-confirm-action', [ 'hidden_method' => 'DELETE' ])
  @endcomponent

  @component('includes.modals.modal-dialog', [
  							'modal_id' => 'product_shipment',
  							'submitable' => false,
  							'reminder' => false,
  							'action_target_id' => 'product_shipment_action',
  							'modal_title' => $store_name.' : Product Shipment'
  						])
      
	<div class="box box-info">    
	<div class="box-body">
	<div class="row padTB"> 
	<div class="col-lg-6 col-lg-offset-3">
	<div class="box box-noborder">
	              
	@component('includes.message.error-summary', [ 'name' => 'product_shipment' ])
	  <ul></ul>
	@endcomponent
	<form id="product-shipment-form">

		{!! csrf_field() !!}

		{{ Form::hidden('shipment_direction', 'CHECKED_IN', array('id' => 'shipment_direction')) }}
		{{ Form::hidden('product_id', $product, array('id' => 'product_id')) }}

		<div class="box-body">
		<div class="form-group">
			<label for="product_shipment_date"><strong>Date:</strong></label>
			<div class="row">
			  <div class="col-md-12">
			      <input type="date" class="form-control" id="product_shipment_date" name="product_shipment_date" />
                    <span class="form-text text-muted">
                       The date of store/ supply
                    </span>
			      <span class="help-block hidden">
			          <strong></strong>
			      </span>
			  </div>
			</div>
		</div>
		<div class="form-group">
			<label for="product_title"><strong>Title:</strong></label>
			<div class="row">
			  <div class="col-md-12">
			      <input type="text" class="form-control" id="product_title" name="product_title"
			      			value="{{ $product ? $product->title : '' }}" required />
                    <span class="form-text text-muted">
                       The title of product for supply/ store
                    </span>
			      <span class="help-block hidden">
			          <strong></strong>
			      </span>
			  </div>
			</div>
		</div>
		<div class="form-group">
			<label for="supplier_title"><strong>Supplier/ Manufacturer/ Dealer:</strong></label>
			<div class="row">
			  <div class="col-md-12">
			      <input type="text" class="form-control" id="supplier_title" name="supplier_title"
			      			value="{{ $product ? $product->marketManufacturer() : '' }}" required />
                    <span class="form-text text-muted">
                       The Supplier/ Manufacturer/ Dealer of the product for supply/ store
                    </span>
			      <span class="help-block hidden">
			          <strong></strong>
			      </span>
			  </div>
			</div>
		</div>
		<div class="form-group">
			<label for="product_price"><strong>Price:</strong></label>
			<div class="row">
			  <div class="col-md-8">
			      <input type="text" class="form-control" id="product_price" name="product_price" value="0.00"
			      			onkeypress="return validate(event)" required />
	                <span class="form-text text-muted">
	                   The unit price of the product for supply/ store. (e.g. 1000 {{ env('STORE_CURRENCY_TEXT') }} /10 KG )
	                </span>
			      <span class="help-block hidden">
			          <strong></strong>
			      </span>
			  </div>
			</div>
		</div>
		<div class="form-group">
			<label for="unit_type"><strong>Unit Type:</strong></label>
			<div class="row">
			  <div class="col-md-12">

				<input type="radio" id="unit_type.quantity" class="unit_type"
									name="unit_type" value="quantity"/>
				<label for="quantity">Quantity</label>

				<input type="radio" id="unit_type.weight" class="unit_type"
									name="unit_type" value="weight" checked />
				<label for="weight">Weight</label>

				<span class="help-block hidden">
				  <strong></strong>
				</span>
			  </div>
			  <div class="col-md-8">
					<label for="unit_type"><strong>Supply/ Shipment Unit:</strong></label>
					<input type="text" class="form-control" id="store_unit" name="store_unit" value="1.00"
					onkeypress="return validate(event)" required />
                    <span class="form-text text-muted">
                       A unit amount of product for supply or shipment (e.g. 10 KG or 10 items for price 1000 {{ env('STORE_CURRENCY_TEXT') }} )
                    </span>
			  </div>
			  <div class="col-md-1">
					<label for="unit" class="unit_label">{{ ' KG' }}</label>
			  </div>
			</div>
		</div>
		<div class="form-group">
			<label for="stored_unit_total"><strong>Supply/ Shipment Total Unit:</strong></label>
			<div class="row">
			  <div class="col-md-8">
			      <input type="text" class="form-control"
			      			id="stored_unit_total" name="stored_unit_total" value="1.00"
			      			onkeypress="return validate(event)" required />
                    <span class="form-text text-muted">
                       A total unit amount of product for supply or shipment (e.g. 10000 KG or 10000 items )
                    </span>
			      <span class="help-block hidden">
			          <strong></strong>
			      </span>
			  </div>
			  <div class="col-md-1">
					<label for="unit" class="unit_label">{{ ' KG' }}</label>
			  </div>
			</div>
		</div>
		<div class="form-group">
			<label for="product_price_total"><strong>Total Stored:</strong></label>
			<div class="row">
			  <div class="col-md-12">
			  	<table>
			  		<thead>
			  		<tr>
			  		<th width="10%">Unit:</th>
			  		<td width="20%"><strong id="unit_total">0.00</strong><label for="unit" class="unit_label">{{ ' KG' }}</label></td>
			  		</tr>
			  		</thead>
			  		<tbody>
			  		<tr>
			  		<th width="10%">Price:</th>
			  		<td width="20%"><strong id="price_total">0.00</strong></td>
			  		</tr>
			  		</thead>
			  	</table>
			  </div>
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

  @endcomponent
@endsection

@section('header-scripts')

<script src="https://code.jquery.com/jquery-2.2.4.js"
		integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous">
</script>

<script type="text/javascript" src="/vendor/request-clients/data-request-clients.js"></script>
<script type="text/javascript">	

$(document).ready( () => {
	
	document.getElementById("product-shipment-form").reset();
	
	$('#product_shipment').on('hide.bs.modal', (e) => {
		document.getElementById("product-shipment-form").reset();
	});

	$('#product_shipment').on('shown.bs.modal', (e) => {

		var button = e.relatedTarget;	    
	    if (button != null)
	    {
	        $('#shipment_direction').val(button.id == 'product-checkin' ? 'CHECKED_IN' : 'CHECKED_OUT');
	        $('.modal-title').text("{{ $store_name }} : Product Shipment - " + (button.id == 'product-checkin' ? 'Check In' : 'Check Out'));
	    }

		changeContext();
		calculate();
	    
	    function calculatePriceTotal(unitTotalNumber)
	    {
	    	if (isNaN(unitTotalNumber))
	    	{
	    		alert('calculated unit total number format is invalid!');
	    		return;
	    	}
	    	var salePrice = Number($('#product_price').val());
	    	var priceTotalNumber = salePrice * unitTotalNumber;
	    	$('#price_total').text(Decimal(priceTotalNumber));
	    }

	    function getCalculateUnitTotal()
	    {
			var selected_value = $("input[name='unit_type']:checked").val();
			var isQuantitySelected = selected_value == "quantity";
	    	var storedUnitTotal = Number($('#stored_unit_total').val());
	    	var storedUnit = Number($('#store_unit').val());
	    	if (storedUnit > storedUnitTotal)
	    	{
	    		alert("\"Supply/ Shipment Unit\" must be equal or less than \"\"Supply/ Shipment Unit Total\". Please check your input.");
	    		$('#store_unit').val($('#stored_unit_total').val());
	    		$('#unit_total').text(isQuantitySelected ? storedUnitTotal : Decimal(storedUnitTotal));
	    		return;
	    	}
	    	if (storedUnit <= 0)
	    	{
	    		alert("Supply/ Shipment Unit is invalid. Please check your input.");
	    		$('#store_unit').val($('#stored_unit_total').val());
	    		$('#unit_total').text(isQuantitySelected ? storedUnitTotal : Decimal(storedUnitTotal));
	    		return;
	    	}
	    	var unitTotalNumber = storedUnitTotal / storedUnit;
	    	var unitTotalNumberText = isQuantitySelected ? unitTotalNumber.toString() : Decimal(unitTotalNumber);
	    	$('#unit_total').text(unitTotalNumberText);
	    	return unitTotalNumber;
	    }

		function calculate()
		{
			calculatePriceTotal(getCalculateUnitTotal());
		}

		function toNumberInput(textTypeHtmlInputElement)
		{	
			if (textTypeHtmlInputElement.attr('type') !== 'text')		
			{
				return;
			}
	    	textTypeHtmlInputElement.attr('type', 'number');
	    	textTypeHtmlInputElement.attr('min', 1);
	    	textTypeHtmlInputElement.val(Number(textTypeHtmlInputElement.val()));	
		}

		function toTextInput(numberTypeHtmlInputElement)
		{	
			if (numberTypeHtmlInputElement.attr('type') !== 'number')		
			{
				return;
			}
	    	numberTypeHtmlInputElement.attr('type', 'text');
	    	numberTypeHtmlInputElement.val(Decimal(numberTypeHtmlInputElement.val()));	
		}

		function changeContext()
		{
			var selected_value = $("input[name='unit_type']:checked").val();
	    	$('.unit_label').text(selected_value == "quantity" ? "" : " KG" );
	    	if (selected_value == "quantity")
	    	{
		    	toNumberInput($('#store_unit'));
		    	toNumberInput($('#stored_unit_total'));
	    	}
	    	else
	    	{
		    	toTextInput($('#store_unit'));
		    	toTextInput($('#stored_unit_total'));
	    	}
	    	$('#unit_total').text(selected_value == "quantity"
	    			? Number($('#unit_total').text()) : Decimal(Number($('#unit_total').text())));
		}

	    $('#stored_unit_total').change(calculate);
	    $('#product_price').change(calculate);
	    $('#store_unit').change(calculate);
	    $(".unit_type").change(changeContext);

		var frm = new FormRequestManager('product-shipment-form');
		frm.errorBoardName = 'product_shipment';
		frm.id = '#product-shipment-form';
		var route = "{{ route('user::products.shipments.store', [ 'product' => $product, 'api_token' => Auth::user()->api_token ]) }}";
		frm.readyForAction(route,
				[],
				"{{ route('user::products.shipments', [ 'product' => $product, 'api_token' => Auth::user()->api_token ]) }}",
				true,
				$('#product_shipment_action'),
				'click');
		window.form = frm;
	});
});
</script>
@endsection

@section('footer-scripts')

<script>

// cross browser string trimming
String.prototype.trimmed = function() {
    return this.replace(
        /^(\s|&nbsp;|<br\s*\/?>)+?|(\s|&nbsp;|<br\s*\/?>)+?$/ig, ' '
    ).trim();
}

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

function validate(event)
{
	return event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57) || event.which === 8;
}

function HasSingleDot(str)
{
	return (str.split(".").length - 1) === 1;
}   

$('div.shipments-container').ready( () => {

	DataManager.serviceUrl = "{{ route('user::products.shipments.table', [ 'product' => $product, 'api_token' => Auth::user()->api_token ]) }}";
	DataManager.payload = { api_token: "{{ Auth::user()->api_token }}" };
	DataManager.dataType = 'html';
	DataManager.onLoad = function(data) {
		$('div.shipments-container').html(data);

		$('.remove-shipment').click( (e) => {
		  e.preventDefault();

		  $('#confirmation_modal').modal({ show: true });
		  // console.log($(e.currentTarget).data('shipment'));
		  const route = '/api/v1/products/shipments/' + $(e.currentTarget).data('shipment') + "?api_token={{ Auth::user()->api_token }}";
		  $("#modal-confirm-action-btn").attr("formaction", route);
		  $("#warning-message").text("Your shipment will be removed.");
		});
	};
	DataManager.request();
});
</script>

@endsection

@section('content')
<div class="box box-info"> 
<div class="box-body">
<div class="row padTB"> 

<!--History-->
<div class="row">

<div class="col-xs-12">

<div class="box box-noborder">

	<div class="box-header">
	<h3 class="box-title">Product Shipment History:</h3>
	</div><!-- /.box-header -->
	<div class="box-body">


	<div class="row">
	<div class="col-xs-12">
        <button id="product-checkin" class="btn btn-info btn-flat laravel-bootstrap-modal-form-open"
        										data-toggle="modal" data-target="#product_shipment" type="button">
        		<i class="fa fa-lg fa-plus-square"></i>&ensp; Product Checkin</button>
        <button id="product-checkout" class="btn btn-info btn-flat laravel-bootstrap-modal-form-open"
        										data-toggle="modal" data-target="#product_shipment" type="button">
        		<i class="fa fa-lg fa-minus-square"></i>&ensp; Product Checkout</button>
	</div>
	<div class="col-xs-12 shipments-container">

	</div>
	</div>
	</div><!-- /.box-body -->




</div><!-- /.box -->  
</div>
</div>
</div>
</div>
</div>
@endsection