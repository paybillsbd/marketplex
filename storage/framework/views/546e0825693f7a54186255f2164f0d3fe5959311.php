<h4>Categories:</h4>
<br>
<div class="list-group">
    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>            
        <a href="#" class="list-group-item <?php echo e($key == 2 ? 'active': ''); ?>"><?php echo e($category->name); ?></a>  
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>