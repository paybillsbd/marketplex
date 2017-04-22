<?php $__env->startSection('title', 'Stores'); ?>
<?php $__env->startSection('title-module-name', 'Stores'); ?>

<?php $__env->startSection('header-style'); ?>
  <link rel="stylesheet" href="/jquery-validation/css/screen.css">
  <style type="text/css">

  #edit-profile-form label.error {
    margin-left: 10px;
    width: auto;
    display: inline;
  }

  .hide{
    display: none;
  }

  </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer-scripts'); ?>
<!-- 
<script src="/jquery-validation/lib/jquery.js"></script>
<script src="/jquery-validation/dist/jquery.validate.js"></script>
<script src="/form-validation/edit-store-validation.js"></script>
<script src="/form-validation/edit-profile-validation.js"></script>
<script>
    $().ready(onReadyEditStoreValidation);
    $('#phone_number').keypress(validateNumber);
    $('#postcode').keypress(validateNumber);

    $( "input[name='store_name']" ).focusout(onFocusOutRequestForStoreSuggestion);

</script>
 -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="box box-info">    
    <div class="box-body">
      <div class="row padTB"> 
          <div class="col-lg-6 col-lg-offset-3">
            <div class="box box-noborder">

              <div class="box-header with-border">
                <h3 class="box-title">Add your Store</h3>
              </div>

              <!-- form start -->
              <form role="form" id="edit-store-form" action="<?php echo e(isset($store) ? route('user::stores.update', [$store]) : route('user::stores.create')); ?>" method="POST">

                <?php echo csrf_field(); ?>


                <div class="box-body">
                  <div class="form-group<?php echo e($errors->has('name') ? ' has-error' : ''); ?>">
                    <label for="Store-name">Store</label>
                    <input type="text" class="form-control" value="<?php echo e(isset($store) ? $store->name : ''); ?>" id="store_name" name="store_name" placeholder="Add your Store name here...">
                    <?php if($errors->has('name')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('name')); ?></strong>
                        </span>
                    <?php endif; ?>            					
                    
                    <!--Store name suggestion. Just change the visibility to show/hide it : visible/hide-->
                    <!-- <div class="input-group input-group-lg">
                    <p>
                    <label>
                      <span class="glyphicon glyphicon-random"></span>
                      <label id="suggestions"></label>
                    </label>
                    </p>
                    </div> 
 -->
                  </div>

                    <div>
                        <label for="contact-number"> Contact Number</label>

                        <div class="row col-sm-12 col-md-12 col-lg-12">
                            <div class="row col-sm-1 col-md-1 col-lg-1" style="text-align: right">code</div>
                            <div class="form-group col-sm-2 col-md-2 col-lg-2">
                                <div>
                                    <select name="code" text="code" class="form-control">
                                      <?php $__currentLoopData = $area_codes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $area_code): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($area_code); ?>" <?php echo e($phone_number[0] == $key ? 'selected' : ''); ?>><?php echo e($area_code); ?></option>
                                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>

                            </div>
                            <div class="col-sm-7 col-md-7 col-lg-7 form-group<?php echo e($errors->has('phone_number') ? ' has-error' : ''); ?>">
                                <input type="text" class="form-control" value="<?php echo e(isset($phone_number[1]) ? $phone_number[1] : ''); ?>" id="phone_number" name="phone_number" placeholder="Phone number...">

                                <?php if($errors->has('phone_number')): ?>
                                    <span class="help-block">
                                      <strong><?php echo e($errors->first('phone_number')); ?></strong>
                                  </span>
                                <?php endif; ?>
                            </div>
                            <div class="col-sm-1 col-md-1 col-lg-1"><button type="button" class=" hide btn btn-primary">verify</button></div>
                        </div>
                    </div>

				          <div class="form-group">
                    <label for="address">Address</label>
					           <input type="text" class="form-control" value="<?php echo e(isset($address['HOUSE']) ? $address['HOUSE'] : old('address_flat_house_floor_building')); ?>" id="address_flat_house_floor_building" name="address_flat_house_floor_building" placeholder="Flat / house no / floor / Building">
                    <br/>
                    <input type="text" class="form-control" value="<?php echo e(isset($address['STREET']) ? $address['STREET'] : old('address_colony_street_locality')); ?>" id="address_colony_street_locality" name="address_colony_street_locality" placeholder="Colony / Street / Locality">
                    <br/>
                    <input type="text" class="form-control" value="<?php echo e(isset($address['LANDMARK']) ? $address['LANDMARK'] : old('address_landmark')); ?>" id="address_landmark" name="address_landmark" placeholder="Landmark (optional)">
                    <br/>
                    <input type="text" class="form-control" value="<?php echo e(isset($address['TOWN']) ? $address['TOWN'] : old('address_town_city')); ?>" id="address_town_city" name="address_town_city" placeholder="Town / City">
                    <br/>                  
                  </div>

                  <div class="form-group<?php echo e($errors->has('description') ? ' has-error' : ''); ?>">
                    <label for="description">Store Description</label>
                    <textarea placeholder="Add Store description here..." class="form-control" rows="5" id="description" name="description"><?php echo e(isset($store) ? $store->description : ''); ?></textarea>
                    <?php if($errors->has('description')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('description')); ?></strong>
                        </span>
                    <?php endif; ?>
                  </div>
                </div><!-- /.box-body -->

                <div class="box-footer text-right">
                  <button type="submit" class="btn btn-info btn-flat<?php echo e(MarketPlex\Store::storeCreated() && !isset($store) ? ' disabled' : ''); ?>"><?php echo e(isset($store) ? 'Update' : 'Add'); ?> Store</button>
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
                  <h3 class="box-title">My Store List</h3>
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
                      <th class="text-center">Store Name</th>

                      <th class="text-center">Address</th>
                      <th class="text-center">Status</th>
                      <th class="text-center">Action</th>
                    </tr>
                    <?php if(isset($stores)): ?>
                      <?php $__currentLoopData = $stores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $store): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <tr>
                        <td class="text-center" id="child">
                          <?php echo $__env->make('includes.store-redirect-link', [ 'url' => 'localhost', 'title' => $store->name ], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                        </td>

                        <td class="text-center" id="child"><?php echo e(MarketPlex\Helpers\ContactProfileManager::tidyAddress($store->address)); ?></td>

                        <td class="text-center" id="child"><?php echo $__env->make('includes.approval-label', [ 'status' => $store->status, 'labelText' => $store->getStatus() ], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?></td>

                        <td class="text-center" id="child">
                          <form id="store-modification-form-edit" class="form-horizontal" method="GET" >
                            <input formaction="<?php echo e(route('user::stores.edit', [$store])); ?>" id="store-edit-btn" class="btn btn-info btn-flat btn-xs" type="submit" value="Edit"></input>
                          </form>
                          <form id="store-modification-form-delete" class="form-horizontal" method="POST" >
                            <?php echo csrf_field(); ?>

                            <input formaction="<?php echo e(route('user::stores.delete', [$store])); ?>" id="store-delete-btn" class="btn btn-info btn-flat btn-xs" type="submit" value="Delete"></input>
                          </form>
                        </td>

                      </tr>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div>
          </div>
    <!--end of recently added product-->

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app-dashboard-admin', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>