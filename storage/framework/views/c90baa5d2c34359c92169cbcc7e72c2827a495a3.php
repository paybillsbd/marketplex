<div class="demo-drawer mdl-layout__drawer mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
	<header class="demo-drawer-header">
	    <?php echo $__env->make('includes.menus.account-profile-menu-collapsible', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
	</header>
	<?php echo $__env->make('includes.navigations.nav-modules-vendor', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
</div>