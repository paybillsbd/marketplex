

var DataManager = {
  serviceUrl: '',
  payload: {},
  onLoad: function(data) {},
  request: function() {

      $.get(this.serviceUrl, this.payload, this.onLoad).fail(function(jqXHR, textStatus, errorThrown) {
        if (jqXHR.status == 404)
        {
            console.log(textStatus, errorThrown);
            return;
        }
        var msg = '';
        if (jqXHR.status === 0) {
            msg = 'Not connected.\n Verify Network.';
        } else if (jqXHR.status == 404) {
            msg = 'Requested page not found. [404]';
        } else if (jqXHR.status == 403) {
            msg = textStatus + '. [403]';
            //$('body').html(jqXHR.responseText);
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
      });
  }
};

var ViewContentManager = {

  _setUp: function(view_name, payload) {

      DataManager.serviceUrl = '/api/v1/templates/' + view_name;
      DataManager.payload = payload;

  },
  // @param: view_name - name of the view to load
  // @param: payload - the data to bind to the view content
  // @param: table_id - name of the view to load
  append: function(view_name, payload, selector) {

      this._setUp(view_name, payload);

      DataManager.onLoad = function(data) {

          if ( $(selector).find('div.empty-row').length === 1 )
          {
              $(selector + ' > tbody').html(data); 
          }
          else
          {
              $(selector).append(data);
              $(selector + ' > tbody').find('#' + payload.row_id)[0].focus();
          }
      };
      DataManager.request();
  },
  appendEmpty: function(view_name, payload, selector) {

      this._setUp(view_name, payload);

      DataManager.onLoad = function(data) {

          if ( $(selector).find('a.btn_remove').length === 0 )
          {
              $(selector).append(data);
          }
      };
      DataManager.request();              
  }
};

var FormRequestManager = function(formName) {

      var _formName = formName;

      return {
        id: "#submit-form",
        errorBoardName: 'error-board',
        _errorBoardSelector: function() {
            return '.error-summary[data-name="' + window.form.errorBoardName + '"]';
        },
        _shouldRedirect: true,
        _redirectUrl: '/home',
        _route: '',
        _method: 'post',
        _data: {},
        _validationErrors: [],
        _onFail: function(errorCode, jsonResp) {},
        _onValidationError: function(data) {

            var _this = window.form;
            _this._hideValidationErrors();
            var response = data.responseJSON;

            // ref: https://stackoverflow.com/questions/20881213/converting-json-object-into-javascript-array
            var validationErrors = Object.values(response);
            var validationErrorFields = Object.keys(response);
            // console.log(validationErrorFields);

            validationErrors.forEach(function(error, index) {
                _this._showInvalidInput(validationErrorFields[index], error);
            });

            _this._showValidationSummary();
        },
        // ref: https://stackoverflow.com/questions/25227544/add-class-to-parent-div-with-specific-input
        _showInvalidInput: function(inputId, validationText) {

            // ref: https://stackoverflow.com/questions/1144783/how-to-replace-all-occurrences-of-a-string-in-javascript
            inputId = inputId.replace(new RegExp('\\.', 'g'), "\\.");

            window.form._validationErrors.push(validationText);

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

            var _this = window.form;

            $( ".form-group" ).removeClass( "has-error" );
            $( ".help-block" ).addClass( "hidden" );
            $( _this._errorBoardSelector() ).addClass( "hidden" );
            $( _this._errorBoardSelector() ).find("ul").first().empty();
            _this._validationErrors = [];  
        },
        _showValidationSummary: function() {

            var errors = '';
            var _this = window.form;
            _this._validationErrors.forEach(function(item, index) {
                errors += '<li>' + item + '</li>';
            });
            $( _this._errorBoardSelector() ).removeClass( "hidden" );    
            $( _this._errorBoardSelector() ).find("ul").first().html(errors);
        },
        _reset: function() {

            document.getElementById(window.form.id).reset();
        },
        _onSuccess: function(data) {
            var _this = window.form;
            // success logic
            if (data.code == 200) // OK
            {
                if (data.message !== undefined)                    
                {
                    alert( "Success! " +  data.message );
                }
                if (_this._shouldRedirect)
                {
                    window.location.href = _this._redirectUrl;
                }
            }
            else
            {
                alert( "Sorry! " + (data.message !== undefined ? data.message : 'Something went wrong...') );
                _this._reset();
                _this._onFail(data.code, data);
            }
            _this._hideValidationErrors();
        },
        _onError: function(jqXHR, textStatus, errorThrown) {

            window.form._hideValidationErrors();
            var now = new Date(Date.now());
            // alert(jqXHR.status);
            // alert(errorThrown);
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
                console.log('Requested JSON parse failed: ' + errorThrown + '\nPlease check your ajax send request method (e.g. post/get/put/delete) are set as defined in routes');
                // $('body').html(jqXHR.responseText);
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
            var _this = window.form;
            // alert(this.id);
            // alert(JSON.stringify($(_this.id + " :input" ).serializeArray()));
            $.ajax({
                  type: _this._method,
                  url: _this._route,
                  data: $(_this.id + " :input, " + _this.id + ":input:hidden" ).serializeArray(),
                  dataType: 'json',
                  statusCode: {
                        422: _this._onValidationError,
                        400: function(data) {
                            var response = data.responseJSON;
                            alert( response.message == undefined ? 'Unknown error!' : response.message );

                            _this._onFail(response.code, response);
                        }
                  },
                  success: _this._onSuccess,
                  error: _this._onError 
            });
        },
        ready: function(url, data, redirectUrl, create) {

          this._route = url;
          this._data = data;
          this._redirectUrl = redirectUrl;
          this._shouldRedirect = redirectUrl !== null;
          this._method = create ? 'post' : 'put';
          var _this = this;

          $(this.id).ready(function() {
              $(_this.id).submit(_this._onSubmit);
          });
        }
    }
};