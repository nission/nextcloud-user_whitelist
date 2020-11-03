<div id="app-settings">
	<div id="app-settings-header">
		<button class="settings-button"
				data-apps-slide-toggle="#app-settings-content"
		></button>
		<?php foreach($_['users'] as $user) { ?>
			<?php echo $user->getName(); ?>
        <?php } ?>
	</div>
	<div id="app-settings-content">
		<!-- Your settings in here -->
	</div>
</div>
