
<li class="mdl-menu__item">
    <a class="mdl-navigation__link" href="<?php echo e(route('logout')); ?>"
        onclick="event.preventDefault();
                 document.getElementById('logout-form').submit();">
        <i class="material-icons" role="presentation">exit_to_app</i>Logout
    </a>

    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
        <?php echo e(csrf_field()); ?>

    </form>
</li>