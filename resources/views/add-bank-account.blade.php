@extends('layouts.app-dashboard-admin')
@section('title', 'Bank Account')
@section('title-module-name', 'Create Bank Account')

@section('header-styles')
    <!-- Styles -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endsection

@section('footer-scripts')
    <script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>
    <script type="text/javascript" src="/vendor/request-clients/data-request-clients.js"></script>
    <script type="text/javascript">

      var frmAccount = new FormRequestManager('account');
      frmAccount.id = '#account-form';
      var route = "{{ route('user::banks.store', [ 'api_token' => Auth::user()->api_token ]) }}";
      frmAccount.ready(route, [], document.referrer);
      window.form = frmAccount;
    </script>
@endsection

@section('content') 
<div class="box box-info">    
    <div class="box-body">
      <div class="row padTB"> 
          <div class="col-lg-6 col-lg-offset-3">
            <div class="box box-noborder">
            @component('includes.message.error-summary')
                <ul></ul>
            @endcomponent

            <form id="account-form">

            {!! csrf_field() !!}

            <div class="modal-body">
                <div class="form-group">
                  <input  type="text" name="new_bank_name" id="new_bank_name" class="form-control"
                          placeholder="Bank Name" required />
                  <span class="help-block hidden">
                      <strong></strong>
                  </span>
                </div>
                <div class="form-group">
                  <input type="text" name="bank_branch_name" id="bank_branch_name" class="form-control" placeholder="Branch Name" required />
                </div>
                <div class="form-group">
                  <input type="text" name="bank_acc_no" id="bank_acc_no" class="form-control" placeholder="Account No" required />
                </div>
                <div class="form-group">
                  <textarea class="form-control" rows="5" name="bank_detail" id="bank_detail"
                            placeholder="Bank Detail"></textarea>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-warning" onclick="return window.location.href=document.referrer" data-dismiss="modal">Cancel</button>
              <button type="sumbit" class="btn btn-info ">Save</button>
            </div>
            </form>
              <!--end of form-->
</div>
</div>
</div>
</div>
</div>
@endsection