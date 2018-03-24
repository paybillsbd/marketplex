@extends('layouts.app-dashboard-admin')
@section('title', 'Store Products')
@section('title-module-name')
<a href="{{ route('user::stores') }}">Store</a> {{ ' > Products' }}
@endsection

@section('modals')
    <div id="modal_container">{{--Modal load here--}}</div>
    <div id="alert_modal_container">{{--Alert Modal load here--}}</div>
@endsection

@section('content')
<div class="box box-noborder">

<div class="box-header with-border">
<h3 class="box-title">Showing products from your store <strong><i>{{ $store->name }}</i></strong></h3>
</div>

@include('includes.product-table-searchable', [ 'title' => 'Store products', 'is_hidden' => [ 'store' => true ] ])

</div>
</div>
@endsection

@section('footer-scripts')

<script src="{{ asset('/vendor/inzaana/js/product/product.js') }}" type="text/javascript"></script>

@endsection

@section('header-style')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <style>
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
        .deleted-product {
            text-decoration: line-through;
            background-color: rgba(64,0,200,0.1);
        }
    </style>
    <!--end of date picker css-->
@endsection