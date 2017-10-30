<html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
</head>
<body>
    <div class="card text-center">
      <div class="card-header">
        <h2>Order Confirmed!</h2>
      </div>
      <div class="card-body">
        <p class="card-text">Hello Admin, your product order has been placed.</p>  
        <h6 class="card-title bg-dark text-white">Order Details</h6>
        <table class="table">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Product Name</th>
              <th scope="col">Quantity</th>
              <th scope="col">Price</th>
            </tr>
          </thead>
          <?php $i=0 ?>
          @foreach($input['order']['order_items'] as $index => $product)
          <tbody>
            <tr>
              <th scope="row">
              <?php $i++ ?>
              {{ $i }}
              </th>
              <td>{{ $product->name }}</td>
              <td>{{ $product->qty }}</td>
              <td><span>{{ MarketPlex\Store::currencyIcon() }}</span>{{ $product->price }}</td>
            </tr>
          </tbody>
          @endforeach
        </table>
        <div class="card-footer">
          <h4>Total Price</h4><h6><span>{{ MarketPlex\Store::currencyIcon() }}</span>{{ $input['order']['total_price'] }}</h6>
        </div>
        <h6 class="card-title bg-dark text-white">Delivery Details</h6>
        <p scope="col">Address: {{ $input['user']['country'] }}</p>
        <p scope="col">Contact: {{ $input['user']['phone_number'] }}</p>
        <p scope="col">Email: {{ $input['user']['email_address'] }}</p>
      </div>

    </div>
</body>
</html>