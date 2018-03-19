@extends('layouts.app-dashboard-admin')
@section('title', 'Products')
@section('title-module-name', 'Products')

@section('header-style')
    <link href="{{ URL::asset('/vendor/inzaana/css/select2.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ URL::asset('/vendor/inzaana/css/dragdrop.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ URL::asset('/vendor/inzaana/css/newStyle.css') }}" rel="stylesheet" type="text/css">

    <!--for date picker only-->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.min.css" />
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <style>
        .btn-file {
            position: relative;
            overflow: hidden;
        }
        .btn-file input[type=file] {
            position: absolute;
            top: 0;
            right: 0;
            min-width: 100%;
            min-height: 100%;
            font-size: 100px;
            text-align: right;
            filter: alpha(opacity=0);
            opacity: 0;
            outline: none;
            background: white;
            cursor: inherit;
            display: block;
        }

        .ui-autocomplete {
            max-height: 100px;
            overflow-y: auto;
            /* prevent horizontal scrollbar */
            overflow-x: hidden;
        }

        /* IE 6 doesn't support max-height
         * we use height instead, but this forces the menu to always be this tall
         */
        * html .ui-autocomplete {
            height: 100px;
        }
        .ui-autocomplete-loading {
            background: white url('http://loading.io/loader/?use=eyJzaXplIjo4LCJzcGVlZCI6MSwiY2JrIjoiI2ZmZmZmZiIsImMxIjoiIzAwYjJmZiIsImMyIjoiMTIiLCJjMyI6IjciLCJjNCI6IjIwIiwiYzUiOiI1IiwiYzYiOiIzMCIsInR5cGUiOiJkZWZhdWx0In0=') right center no-repeat;
        }
    </style>
    <!--end of date picker css-->
@endsection

@section('modals')
    @include('includes.modals.modal-product-add')

    @each('includes.product-delete-confirm-modal', $products, 'product')

    <div id="modal_container">{{--Modal load here--}}</div>
    <div id="alert_modal_container">{{--Alert Modal load here--}}</div>
@endsection


@section('content')
<div class="box box-info">
<div class="box-body">
<div class="row padTB">
    {{--@include('includes.frontend.search-panel')--}}

    <div class="box box-widget">
        <div class="box-footer box-comments{{ $productsCount == 0 ? '' : ' hidden' }}">
            <div class="box-comment">
                <div class="col-lg-6">
                    <h4 class="C-header">If it is not in {{ config('app.vendor') }}'s catalog:</h4>
                </div>
                <div class="col-lg-6 text-right">
                    <button id="product-form-open-button" class="btn btn-info btn-flat laravel-bootstrap-modal-form-open" data-toggle="modal" data-target="#addProduct" type="button"><i class="fa fa-lg fa-plus-square"></i>&ensp; Add Product</button>
                </div>
            </div>
        </div>
    </div>
</div>
</div>       

<!--recently added product-->
<div class="row">
<div class="col-xs-12">
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Recently Added product</h3>
            <div class="box-tools">
                <div class="input-group" style="width: 150px;">
                    <input type="text" name="table_search" id="search_box" class="form-control input-sm pull-right" placeholder="Search">
                    <div class="input-group-btn">
                        <button class="btn btn-sm btn-default" id="search_by_button_click"><i class="fa fa-search"></i></button>
                    </div>
                </div>
            </div>
        </div><!-- /.box-header -->
        <div class="box-body table-responsive no-padding">
            <div id="delete_all"></div>
            <div id="load_table_dom">
                @include('includes.product-table')
            </div><!-- /.box-body -->
    </div><!-- /.box -->
</div>
</div>

<!--end of recently added product-->

@endsection

@section('footer-scripts')
<script src="{{ asset('/vendor/inzaana/form-validation/add-product-validation.js') }}" type="text/javascript"></script>

<script src="{{ asset('/vendor/inzaana/js/select2.full.min.js') }}" type="text/javascript"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{ asset('/vendor/inzaana/js/product/product.js') }}" type="text/javascript"></script>
<script>
    function matchStart (term, text) {
        if (text.toUpperCase().indexOf(term.toUpperCase()) == 0) {
            return true;
        }

        return false;
    }

    $.fn.select2.amd.require(['select2/compat/matcher'], function (oldMatcher) {
        $("select").select2({
            matcher: oldMatcher(matchStart)
        })
    });
</script>

<script type="text/javascript">

    function productFormEvents()
    {
        $('#product-form-open-button').click(function(){
            console.log($('#product-create-form').attr("action"));
            $('#product-create-form').attr("action", "{{ route('user::products.create') }}");
            console.log('Changed action to:' + $('#product-create-form').attr("action"));
        });

        $('#embed_video_url').focusout(onUrlPaste);
    }

    function specControlEvents()
    {
        $('#input').prop("hidden", "");
        $('.add-option').prop("hidden", "hidden");

        $('#control_type').val("input");
        $('#control_type').change();

        $('#control_type').change( function(event) {

            var controlId = this.value;
            var showDefault = false;

            if(this.value == 'dropdown' || this.value == 'options')
            {
                $('#' + this.value).prop("hidden", "");
                $('.add-option').prop("hidden", "");
            }
            else if(this.value == 'spinner')
            {
                $('#' + this.value).prop("hidden", "");
                $('.add-option').prop("hidden", "hidden");
            }
            else
            {
                showDefault = true;
            }
            $.each($('.spec-controls'), function(index, value) {

                if(value.id != controlId)
                {
                    $('#' + value.id).prop("hidden", "hidden");
                }
            });
            if(showDefault)
            {
                $('#input').prop("hidden", "");
                $('.add-option').prop("hidden", "hidden");
            }
        });

        var isEdit = $('div.is-edit').length > 0;
        var specs = '';
        var spec_count = $('#spec_count').val();
        var options = '';
        var optionCount = 0;
        var firstTime = true;

        $('#apply_spec').click( function(e) {

            e.preventDefault();

            ++spec_count;

            var specValues = optionCount > 0 ? '' : $('#single_spec').val();

            var selectedControlType = $('#control_type').val();
            console.log(selectedControlType);

            if(selectedControlType == 'dropdown')
            {
                specValues = $('select#optdropdown option').map( function() {
                    return this.value;
                }).get().join(",");
            }
            if(selectedControlType == 'options')
            {
                specValues = $("input[type='radio']").map(function() {
                    var idVal = $(this).attr("id");
                    return $("label[for='"+idVal+"']").text();
                }).get().join(",");
            }
            if(selectedControlType == 'spinner')
            {
                specValues = $('#optspinner_min').val() + ' ~ ' + $('#optspinner_max').val();
            }

            specs += '<tr>';
            specs += '<td>' + $('#spec_title').val() + ' <input name="title_' + spec_count + '" type="text" value="' + $('#spec_title').val() + '" hidden></td>';
            specs += '<td>' +  $('#control_type').val() + ' <input name="option_' + spec_count + '" type="text" value="' +  $('#control_type').val() + '" hidden></td>';
            specs += '<td>' + specValues + ' <input name="values_' + spec_count + '" type="text" value="' + specValues + '" hidden></td>';
            specs += '<td><a href="#" id="delete_me" class="btn btn-xs btn-danger">x</a></td>';
            specs += '</tr>';

            console.log(specValues);
            if($('#spec_count').val() == 0)
                $('table.spec-table tbody').html(specs);
            else
                $('table.spec-table tbody').append(specs);

            specs = '';

            $('#spec_count').val($('table.spec-table tbody tr').length);

            console.log('applied specs: ' + $('#spec_count').val());

            $('#option_input').val("");
            $('#spec_title').val("");
            $('#single_spec').val("");

            // IMPORTANT to reset options entered last time
            options = '';
            optionCount = 0;
        });

        $('#add-option-btn').click(function(e) {

            e.preventDefault();

            var selectedControlType = $('#control_type').val();
            console.log(selectedControlType);

            var optionInput = $('#option_input').val();
            if(selectedControlType == 'dropdown')
            {
                ++optionCount;
                options += '<option>' + optionInput + '</option>';
                $('#optdropdown').html(options);
            }
            if(selectedControlType == 'options')
            {
                ++optionCount;
                options += '<div class="radio">';
                options += '<label for="optradio_' + optionCount + '"><input type="radio" id="optradio_' + optionCount + '" name="optradio_' + optionCount + '">' + optionInput + '</label>';
                options += '</div>';
                $('#options').html(options);
            }
            $('#option_input').val("");
        });
    }

    function productModalEvents()
    {
        var showModal = ($('div.has-error').length > 0 || $('div.is-edit').length > 0);
        $('#addProduct').modal({ 'show' : showModal });

        if ($('div.has-error').length > 0)
        {
            $('#store_name').val('{{ old("store_name") }}');
        }
        if ($('div.is-edit').length > 0)
        {
            $('#store_name').val('{{ $product && $product->store ? $product->store->name_as_url : old("store_name") }}');
        }

        $('#addProduct').on('hidden.bs.modal', function () {
            $('#product-create-form').find('input[type="text"]').val('');
            $('#product-create-form').find('input[name="available_quantity"]').val('{{ MarketPlex\Product::MIN_AVAILABLE_QUANTITY }}');
            
            var defaultTable = '';
            defaultTable += '<tr>';
            defaultTable += '<td>---- <input name="title_empty" type="text" value="" hidden></td>';
            defaultTable += '<td>---- <input name="option_empty" type="text" value="" hidden></td>';
            defaultTable += '<td>---- <input name="values_empty" type="text" value="" hidden></td>';
            defaultTable += '</tr>';
            $('table.spec-table tbody').html(defaultTable);


            var fileLimit = 5;
            var default_image_filename = '{{ MarketPlex\ProductMedia::IMAGES_PATH_PUBLIC . MarketPlex\ProductMedia::DEFAULT_IMAGE }}';
            for(var i = 1; i < fileLimit; ++i)
                $('#blah-' + i).attr('src', default_image_filename);
        });
    }

    // Ready
    $('#generalTabContent').ready(function() {
        productModalEvents();
        onChangeEmbedVideoCheck();
        $( "#has_embed_video" ).change(onChangeEmbedVideoCheck);
        spinnerEvents();
        specControlEvents();
        productFormEvents();
    });

    function onChangeEmbedVideoCheck() {

        if($('.embed_video').is(':hidden'))
        {
            $( ".embed_video" ).hide( "fast", function() {});

            if(!$('#has_embed_video').is(':checked'))
            {
                $( "#has_embed_video" ).prop('checked', 'checked');
            }
        }
        if($('#has_embed_video').is(':checked') || $( ".embed_video" ).hasClass( "has-error" ))
        {
            $( ".embed_video" ).show( 1000 );
        }
        else
        {
            $( ".embed_video" ).hide( "fast", function() {});
            $( ".embed_video" ).removeClass( "has-error" );
            $( ".embed_video" ).find("strong").html("");
        }
    }
</script>
<script>
    $(document).on('click','#delete_me',function(e){
        e.preventDefault();
        $(this).parent().parent().remove();
    });
</script>

<script>

    // + function($) {
    //       'use strict';

    //       // UPLOAD CLASS DEFINITION
    //       // ======================

    //       var dropZone = document.getElementById('drop-zone');
    //       var uploadForm = document.getElementById('js-upload-form');

    //       var startUpload = function(files) {
    //           console.log(files)
    //           // var fileView = '<ul>';
    //           // $.each(files, function(index, item){
    //           //     fileView += '<li>' + item.name + '</li>';
    //           // });
    //           // fileView += '<ul>';
    //           // $('#drop-zone').html(fileView);
    //       }


    //       // uploadForm.addEventListener('submit', function(e) {
    //       //     var uploadFiles = document.getElementById('csv').files;
    //       //     e.preventDefault()


    //       //     startUpload(uploadFiles)
    //       // });

    //       dropZone.ondrop = function(e) {
    //           e.preventDefault();
    //           this.className = 'upload-drop-zone';

    //           startUpload(e.dataTransfer.files)
    //       }

    //       dropZone.ondragover = function() {
    //           this.className = 'upload-drop-zone drop';
    //           return false;
    //       }

    //       dropZone.ondragleave = function() {
    //           this.className = 'upload-drop-zone';
    //           return false;
    //       }


    //   }(jQuery);

</script>

<script> /*for spinner*/
    function spinnerEvents()
    {
        $('.spinner .btn:first-of-type').on('click', function() {
            var btn = $(this);
            var input = btn.closest('.spinner').find('input');
            if (input.attr('max') == undefined || parseInt(input.val()) < parseInt(input.attr('max'))) {
                input.val(parseInt(input.val(), 10) + 1);
            } else {
                btn.next("disabled", true);
            }
        });
        $('.spinner .btn:last-of-type').on('click', function() {
            var btn = $(this);
            var input = btn.closest('.spinner').find('input');
            if (input.attr('min') == undefined || parseInt(input.val()) > parseInt(input.attr('min'))) {
                input.val(parseInt(input.val(), 10) - 1);
            } else {
                btn.prev("disabled", true);
            }
        });
    }

</script>

<!-- background image loader on browse -->
<script type="text/javascript">

    var totalMediaLoaded = 0;
    var fileLimit = 5;
    var reader = new FileReader();
    reader.addEventListener("load", function() {

        var imgHTML = $('#preview-image-' + (++totalMediaLoaded));
        var imageIndexToLoad = totalMediaLoaded;
        while(imageIndexToLoad++)
        {
            var default_image_filename = {{ MarketPlex\ProductMedia::DEFAULT_IMAGE }};
            if(imgHTML.attr("src").indexOf(default_image_filename) > -1)
            {
                setBackgroundImage( imgHTML , reader.result);
                break;
            }
            imgHTML = $('#preview-image-' + imageIndexToLoad);
        }
    }, false);

    function setBackgroundImage(control, image_url) {
        // console.log("[DEBUG] Setting BG Image : " + control.attr("id") + " : " + image_url );
        // control.css("background-image", "url(" + image_url + ")");
        control.attr("src", image_url);
        // console.log("[DEBUG] BG Image URL : " + control.css("background-image"));
        // console.log("[DEBUG] BG Image URL : " + control.attr("src"));
    }
    function onBrowseFile(event) {
        // var fileName = $(this).val();
        // console.log(fileName);
        var file = event.target.files[0];
        if (file && totalMediaLoaded < fileLimit)
        {
            reader.readAsDataURL(file);
        }
    }

    for(var i = 1; i < fileLimit; ++i)
        $('#upload_image_' + i).change( onBrowseFile );

</script>
@endsection
