<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" id="hdrbtn">
	<i class="material-icons">more_vert</i>
</button>
<ul class="mdl-menu mdl-js-menu mdl-js-ripple-effect mdl-menu--bottom-right" for="hdrbtn">
	<?php echo $__env->renderEach('includes.menus.account-profile-menu-items', $activities, 'activity'); ?>
	<?php echo $__env->make('includes.menus.menu-item-logout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
</ul>