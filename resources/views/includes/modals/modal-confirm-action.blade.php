<div class="modal fade" id="confirmation_modal" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        </a>
        <button type="button" class="close" data-dismiss="modal">
          <span aria-hidden="true">&times;</span>
          <span class="sr-only">Close</span>
        </button>
        <h4 class="modal-title">Are you sure?</h4>
      </div>
      <div class="modal-body">
        <p id="warning-message"></p>
        {{ $slot }}
      </div>
      <div class="modal-footer">
        <form>
        {!! csrf_field() !!}
        <input formmethod="POST" formaction="{{ $ok_action or '' }}" formnovalidate="formnovalidate" 
                id="modal-confirm-action-btn" class="btn btn-success" type="submit" value="OK" />
        <button type="button" id="btn-confirm" class="btn btn-warning" data-dismiss="modal">Cancel</button>
        </form>
      </div>
    </div>
  </div>
</div>