
<?php if( $status == 'ON_APPROVAL' ): ?>
<span class="label label-warning"><?php echo e($labelText); ?></span>
<?php elseif( $status == 'REJECTED' ): ?>
<span class="label label-danger"><?php echo e($labelText); ?></span>
<?php elseif( $status == 'APPROVED' ): ?>
<span class="label label-success"><?php echo e($labelText); ?></span>
<?php endif; ?>