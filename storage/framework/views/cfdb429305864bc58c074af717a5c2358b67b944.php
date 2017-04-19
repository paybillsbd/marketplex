<?php $__env->startSection('title', 'Home'); ?>
<?php $__env->startSection('title-module-name', 'Home'); ?>

<?php $__env->startSection('content'); ?>
  <!-- <?php echo $__env->make('includes.dashboard.home', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?> -->
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app-dashboard-admin', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>