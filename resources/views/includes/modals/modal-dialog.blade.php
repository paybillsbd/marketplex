<div class="modal fade" id="{{ $modal_id }}" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">

        @foreach( isset($actions) ? $actions : [] as $action )
        <a  type="button" id="{{ $action['action_id'] }}" href="#"
            data-toggle="tooltip" data-placement="top" title="{{ $action['icon_tooltip'] }}">
          <span aria-hidden="true">
          <i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">$action['icon_name']</i></span>
        </a>
        @endforeach

        <button type="button" class="close" data-dismiss="modal">
          <span aria-hidden="true">&times;</span>
          <span class="sr-only">Close</span>
        </button>
        <h4 class="modal-title">{{ $modal_title }}</h4>
      </div>
      <div class="modal-body">
        {{ $slot }}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-dismiss="modal">{{ $reminder ? 'OK' : 'Cancel' }}</button>
        <button type="{{ $submitable ? 'submit' : 'button' }}"
                id="{{ !isset($action_target_id) ? 'btn-target' : $action_target_id }}" class="btn btn-primary btn-flat">Save</button>
      </div>
    </div>
  </div>
</div>