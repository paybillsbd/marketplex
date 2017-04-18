<div id="tab-messages" class="tab-pane fade in<?php echo e(($tab == 'bulk_product_entry_tab') ? ' active' : ''); ?>">
    <div class=" form-horizontal">

        <form action="<?php echo e(route('user::products.upload.csv')); ?>" method="POST" enctype="multipart/form-data" id="js-upload-form">

            <?php echo csrf_field(); ?>


            <div>
                <label class="col-sm-3 control-label">
                    <h5>Select store</h5>
                    
                    <button class="btn btn-primary btn-flat">Download CSV</button>
                
                </label>
                <div class="col-sm-7">

                    <select name="stores" id="stores" class="form-control select2" multiple="multiple" data-placeholder="Select Store" style="width: 100%;">
                        <?php $__currentLoopData = $stores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $name_as_url => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($name_as_url); ?>"> <?php echo e($name_as_url); ?>.inzaana.com ( <?php echo e($name); ?> ) </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>

                </div>
            </div>


            <div class="text-center">

                <!-- Standard Form -->
                <br>
                
                <div class="form-inline">
                    <div class="form-group margin-choose-file-button">
                        <input type="file" class="input-control" name="csv" id="csv" multiple>
                    </div>
                </div>

                <!-- Drop Zone -->
                
            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary btn-flat">Upload Files</button>
            </div>

        </form>


    </div>
</div>