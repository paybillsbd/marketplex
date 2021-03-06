@extends('layouts.app-dashboard-admin')
@section('title', 'Category')
@section('title-module-name', 'Category')

@section('content')
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Add Category</h3>
    </div>
    
    <div class="box-body">
      <div class="row padTB"> 
          <div class="col-lg-6 col-lg-offset-3">
            <div class="box box-noborder">

              <div class="box-header with-border">
                <h3 class="box-title">Add your product category</h3>
              </div>

              <!-- form start -->
              <form role="form" action="{{ isset($categoryEdit) ? route('user::categories.update', $categoryEdit->id) : route('user::categories.create') }}" method="POST">

                {!! csrf_field() !!}

                <div class="box-body">
                  <div class="form-group">
                    <label for="category-name">Category</label>
                    <input type="text" class="form-control" value="{{ isset($categoryEdit) ? $categoryEdit->name : '' }}" id="category-name" name="category-name" placeholder="Add your category name here...">
                  </div>
                  <div class="form-group">
                    <label for="description">Category Description</label>
                    <textarea placeholder="Add category description here..." class="form-control" rows="5" id="description" name="description">{{ isset($categoryEdit) ? $categoryEdit->description : '' }}</textarea>
                  </div>
                </div><!-- /.box-body -->

                <div class="box-footer text-right">
                  <button type="submit" class="btn btn-info btn-flat">{{ isset($categoryEdit) ? 'Update' : 'Add' }} Category</button>
                </div>
              </form>
              <!--end of form-->

            </div>
          </div>
    </div>
</div>
    
    <!--recently added product-->
    <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Recently Added Category</h3>
                  <div class="box-tools">
                    <div class="input-group" style="width: 150px;">
                      <input type="text" name="table_search" class="form-control input-sm pull-right" placeholder="Search">
                      <div class="input-group-btn">
                        <button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                  </div>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                  <table id="parent" class="table table-hover">
                    <tr>
                      <!-- <th class="text-center hidden">ID</th> -->
                      <th class="text-center">Category Name</th>
                      <th class="text-center">Description</th>
                      <th class="text-center">Action</th>
                    </tr>
                    @foreach($categories as $category)
                    <tr>
                      <!-- <td class="text-center" id="child"><a href="">001</a> </td> -->
                      <td class="text-center" id="child"><a href="">{{ $category->name or 'Chocolate'}}</a></td>
                      <td class="text-center" id="child"><a href="">{{ $category->description or 'This is a description'}}</a></td>
                      <td class="text-center" id="child">
                        <form id="category-edit-form" class="form-horizontal">
                          <input formmethod="GET" formaction="{{ route('user::categories.edit', [$category->id]) }}" id="category-edit-btn" class="btn btn-info btn-flat btn-xs" type="submit" value="Edit"></input>
                        </form>
                        <form id="category-delete-form" class="form-horizontal">
                          {!! csrf_field() !!}
                          <input formmethod="POST" formaction="{{ route('user::categories.delete', [$category->id]) }}" id="category-delete-btn" class="btn btn-info btn-flat btn-xs" type="submit" value="Delete"></input>
                        </form>
                      </td>
                    </tr>
                    @endforeach
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div>
          </div>
    <!--end of recently added product-->

@endsection