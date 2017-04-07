<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" id="hdrbtn">
	<i class="material-icons">more_vert</i>
</button>
<ul class="mdl-menu mdl-js-menu mdl-js-ripple-effect mdl-menu--bottom-right" for="hdrbtn">
	@each('includes.menus.account-profile-menu-items', $activities, 'activity')
</ul>