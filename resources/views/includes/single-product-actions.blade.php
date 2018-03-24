<div class="row">
  <div class="col-md-3">
  <a  href="{{ route('user::products.edit', [$product]) }}"
      data-toggle="tooltip" data-placement="top" title="Edit {{ $product->title }}">
    <span aria-hidden="true">
    <i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">mode_edit</i></span>
  </a>
  </div>
  <div class="col-md-3">
  <a  href="{{ route('user::products.edit', [$product]) }}" type="button" id="product_del_btn"
  		data-toggle="modal" data-target="#confirm_remove_{{ $product->id }}_disabled"
  		data-product_id="{{ $product->id }}" data-url="{{ route('user::products.delete', [$product]) }}"
  		title="Remove {{ $product->title }}">
    <span aria-hidden="true">
    <i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">delete</i></span>
  </a>
  </div>
</div>