var DataManager = {
    serviceUrl: '/',
    _onFail: function(jqXHR, textStatus, errorThrown) {
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
    },
    onLoad: function(data) {},
    request: function(method, payload) {

        if (method.toString().toLowerCase() === 'post')
        {
            $.post( this.serviceUrl, payload, this.onLoad, "json" ).fail(_onFail);
        }
        else if (method.toString().toLowerCase() === 'get')
        {
            $.get( this.serviceUrl, payload, this.onLoad).fail(_onFail);
        }
    }
};

var FormRequestManager = {
    id: "#submit-form",
    _shouldRedirect: true,
    _redirectUrl: '/home',
    _route: '',
    _data: {},
    _validationErrors: [],
    _onValidationError: function(data) {
        FormRequestManager._hideValidationErrors();
        var response = data.responseJSON;

        // ref: https://stackoverflow.com/questions/20881213/converting-json-object-into-javascript-array
        var validationErrors = Object.values(response);
        var validationErrorFields = Object.keys(response);
        // console.log(validationErrorFields);

        validationErrors.forEach(function(error, index) {
            FormRequestManager._showInvalidInput(validationErrorFields[index], error);
        });

        FormRequestManager._showValidationSummary();
    },
    // ref: https://stackoverflow.com/questions/25227544/add-class-to-parent-div-with-specific-input
    _showInvalidInput: function(inputId, validationText) {

        // ref: https://stackoverflow.com/questions/1144783/how-to-replace-all-occurrences-of-a-string-in-javascript
        inputId = inputId.replace(new RegExp('\\.', 'g'), "\\.");

        FormRequestManager._validationErrors.push(validationText);
        var targetInput = $("#" + inputId);
        var formGroup = targetInput.closest(".form-group");
        formGroup.addClass('has-error');
        var helpBlock = formGroup.find(".help-block").first();
        console.log(helpBlock.html());
        helpBlock.removeClass('hidden');
        helpBlock.find("strong").first().text(validationText);
        targetInput.focus();
    },
    // ref: https://stackoverflow.com/questions/25227544/add-class-to-parent-div-with-specific-input
    _hideInvalidInput: function(inputId) {

        var targetInput = $("#" + inputId);
        targetInput.closest(".form-group").removeClass('has-error');
        var helpBlock = targetInput.closest(".help-block");
        helpBlock.addClass('hidden');
        helpBlock.closest("strong").empty();
    },
    _hideValidationErrors: function() {

        $( ".form-group" ).removeClass( "has-error" );
        $( ".help-block" ).addClass( "hidden" );
        $( ".error-summary" ).addClass( "hidden" );
        $( ".error-summary" ).find("ul").first().empty();
        FormRequestManager._validationErrors = [];  
    },
    _showValidationSummary: function() {

        var errors = '';
        FormRequestManager._validationErrors.forEach(function(item, index) {
            errors += '<li>' + item + '</li>';
        });
        $( ".error-summary" ).removeClass( "hidden" );    
        $( ".error-summary" ).find("ul").first().html(errors);
    },
    _reset: function() {

        document.getElementById(FormRequestManager.id).reset();
    },
    _onJsonReceived: function (json) {},
    _onSuccess: function(data) {
        // success logic
        // alert(JSON.stringify(data));
        if (data.code == 200) // OK
        {                        
            if (data.message !== undefined)                    
            {
                alert( "Success! " +  data.message );
            }
            if (FormRequestManager._shouldRedirect)
            {
              window.location.href = "{{ route('user::sales.index', [ 'api_token' => Auth::user()->api_token ]) }}";
            }
            else
            {
                // alert(JSON.stringify(data));
                FormRequestManager._onJsonReceived(data);
            }
        }
        else
        {
            FormRequestManager._reset();
            alert( "Sorry! " +  data.message );
        }
        FormRequestManager._hideValidationErrors();
    },  
    _onError: function(jqXHR, textStatus, errorThrown) {

        FormRequestManager._hideValidationErrors();
        var now = new Date(Date.now());
        // alert(jqXHR.status);
        if (jqXHR.status == 404 || jqXHR.status == 422 || jqXHR.status == 400) 
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
            msg = 'Server could not process your submitted data.';
            console.log('Requested JSON parse failed.');
            $('body').html(jqXHR.responseText);
        } else if (textStatus === 'timeout') {
            msg = 'Time out error.';
        } else if (textStatus === 'abort') {
            msg = 'Ajax request aborted.';
        } else if (jqXHR.status == 503) {
            msg = 'Something went wrong: [' + jqXHR.status + '][' + errorThrown + '].\n';
            $('body').html(jqXHR.responseText);
        } else {
            msg = 'Uncaught Error: [' + jqXHR.status + '][ ' + textStatus + ' ][' + errorThrown + '].\n' + jqXHR.responseText;
            $('body').html(jqXHR.responseText);
        }              
        // Render the errors with js ...
        alert(msg + 'The operations are failed! The issues are logged dated: ' + now.toLocaleDateString()
                  + '\nfor the assistance of your service provider.');
    },
    _onSubmit: function(event) {
                  
        event.preventDefault();
        var _this = FormRequestManager;
        $.ajax({
              type: 'post',
              url: _this._route,
              data: $( ":input" ).serializeArray(), //{ inputs: $( ":input" ).serializeArray(), extra: _this._data },
              dataType: 'json',
              statusCode: {
                    422: _this._onValidationError,
                    400: function(data) {
                        var response = data.responseJSON;
                        alert( response.message == undefined ? 'Unknown error!' : response.message );
                    }
              },
              success: _this._onSuccess,
              error: _this._onError 
        });

    },
    ready: function(url, data, redirectUrl) {

      this._route = url;
      this._data = data;
      this._redirectUrl = redirectUrl;
      this._shouldRedirect = redirectUrl !== null;
      var _this = this;

      $(this.id).ready(function() {
            $(_this.id).submit(_this._onSubmit);
      });
    },
    requestJSON: function(url, data, redirectUrl, onJsonReceived) {

      this._onJsonReceived = onJsonReceived;
      this.ready(url, data, redirectUrl);
    }
};

var ViewContentManager = {
    // @param: view_name - name of the view to load
    // @param: payload - the data to bind to the view content
    // @param: table_id - id of the table to update
    append: function(view_name, payload, selector) {

        DataManager.serviceUrl = '/api/v1/templates/' + view_name + '?api_token={{ Auth::user()->api_token }}';
        DataManager.onLoad = function(data) {

            $(selector).append(data);
        };
        DataManager.request('get', payload);
    },
    // @param: view_name - name of the view to replace
    // @param: payload - the data to bind to the view content
    // @param: table_id - id of the table to update
    replace: function(view_name, payload, selector) {

        DataManager.serviceUrl = '/api/v1/templates/' + view_name + '?api_token={{ Auth::user()->api_token }}';
        DataManager.onLoad = function(data) {

            $(selector).html(data);
        };
        DataManager.request('get', payload);
    }
};