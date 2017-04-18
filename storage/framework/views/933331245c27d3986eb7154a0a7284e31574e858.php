<!--columnn-->
<?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

<div class="col-lg-4">
    <!--Card-->
    <div class="card  wow fadeIn" data-wow-delay="0.2s">
     
        <!--Card image-->
        <div class="view overlay hm-white-slight">
            <img src="<?php echo e($product->thumbnail()); ?>" class="img-fluid" alt="">
            <a href="#">
                <div class="mask"></div>
            </a>
        </div>
        <!--/.Card image-->

        <!--Card content-->
        <div class="card-block">
            <!--Title-->

            <h4 class="card-title"> <?php echo e($product->title); ?></h4>
            <!--Text--> 
            <p class="card-text"> <?php echo e($product->status); ?> </p>
            <a href="#" class="btn btn-default">Buy now for <strong><?php echo e($product->mrp); ?>$</strong></a>
        </div>
        <!--/.Card content-->

    </div>
    <br/>
    <!--/.Card-->
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<div class="container text-center">
  <?php echo e($products->links('includes.frontend.pagination')); ?>

</div>

<!--end column -->