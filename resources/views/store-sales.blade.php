@extends('layouts.app-dashboard-admin')
@section('title', 'Store Sales')
@section('title-module-name')
<a href="{{ route('user::stores') }}">Store</a> {{ ' > Sales' }}
@endsection

@section('content')
<div class="box box-noborder">

<div class="box-header with-border">
<h3 class="box-title">Showing sales from your store <strong><i>{{ $store->name }}</i></strong></h3>
</div>
<div class="box-body table-responsive no-padding">

<table id="parent" class="table table-hover">

<thead class="text-center">
<tr>
	<th>Date</th>
	<th>Bill ID</th>
	<th>Client/Company</th>
	<th>Total Bill</th>
	<th>Due</th>
	<th>Action</th>
</tr>
</thead>

<tbody>
	@include('includes.tables.sales-row-search-result')
</tbody>
</table>
{{ $sales->appends([ 'api_token' => Auth::user()->api_token ])->links() }}

</div>
</div>
@endsection