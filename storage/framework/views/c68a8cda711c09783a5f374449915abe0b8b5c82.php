<?php $__env->startSection('title', 'Store'); ?>
<?php $__env->startSection('title-module-name', 'Store'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">

    <!--Sidebar-->
    <div class="col-lg-4 wow fadeIn" data-wow-delay="0.2s">

        <div class="widget-wrapper">
            <?php echo $__env->make('includes.frontend.categories', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        </div>

        <div class="widget-wrapper wow fadeIn" data-wow-delay="0.4s">
            <?php echo $__env->make('includes.frontend.subscription', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        </div>

    </div>
    <!--/.Sidebar-->

    <!--Main column-->
    <div class="col-lg-8">

        <!--First row-->
        <div class="row wow fadeIn" data-wow-delay="0.4s">
            <div class="col-lg-12">
                <div class="divider-new">
                    <h2 class="h2-responsive">What's new?</h2>
                </div>        
                
                <?php echo $__env->make('includes.frontend.carousel', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        
            </div>
        </div>
        <!--/.First row-->
        <br>
        <hr class="extra-margins">

        <!--Second row-->
        <div class="row">        
            <?php echo $__env->make('includes.frontend.product', [ 'products' => $paginated_products ], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>         
        </div>
        <!--/.Second row-->

    </div>
    <!--/.Main column-->

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app-store-front', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>