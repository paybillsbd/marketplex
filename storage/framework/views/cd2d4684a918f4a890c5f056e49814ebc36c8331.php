<?php if(session()->has('flash_notification.message')): ?>
    <div class="alert alert-<?php echo e(Session::get('flash_notification.level')); ?>"><?php echo e(session('flash_notification.message')); ?></div>
<?php endif; ?>