<?php $__env->startSection('title', '404 not found'); ?>

<?php $__env->startSection('header-style'); ?>
 <link href="<?php echo e(URL::asset('css/404.css')); ?>" rel="stylesheet" type="text/css">  
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="error-template">
                <h1>Oops!</h1>
                <div class="col-xs-12 col-md-8 col-md-offset-2 marTop100 text-center">
                 <img class="img-responsive 404banner" src="<?php echo e(asset('images/404banner.png')); ?>"> 
                    <div class="error-details">Sorry, an error has occured, Requested page not found!</div>
                    <div> <?php echo $__env->make('errors', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?> </div> 
                </div>               
                <div class="error-actions col-xs-12 col-md-8 col-md-offset-2 marTop100 text-center">
                    <a href="<?php echo e(url('/')); ?>" class="btn btn btn-info"><span class="glyphicon glyphicon-home"></span>
                        Take Me Home </a><a href="#" class="btn btn-default "><span class="glyphicon glyphicon-envelope"></span> Contact Support </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>