<img src="<?php echo e(MarketPlex\Helpers\AccountProfileManager::ICONS['user']); ?>" class="demo-avatar">
<div class="demo-avatar-dropdown">
	<span><?php echo e(Auth::user()->name); ?></span>
	<div class="mdl-layout-spacer"></div>
	<button id="accbtn" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon">
	  <i class="material-icons" role="presentation">arrow_drop_down</i>
	  <span class="visuallyhidden">Account Activities</span>
	</button>
	<ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="accbtn">
		<?php echo $__env->renderEach('includes.menus.account-profile-menu-items', $activities, 'activity'); ?>
		<?php echo $__env->make('includes.menus.menu-item-logout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
	</ul>
</div>