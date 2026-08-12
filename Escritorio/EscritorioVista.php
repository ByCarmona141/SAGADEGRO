<div class="alert alert-<?= $StatusMessage; ?> fade in" role="alert">
	<button class="close" data-dismiss="alert" type="button">
		<span aria-hidden="true">&times;</span>
		<span class="sr-only">Close</span>
	</button>
	<strong>
		<?php
			if(isset($IconMessage) && $IconMessage != '') {
				?>
				<i class="fa fa-<?= $IconMessage ?> fa-lg"></i>&nbsp;
				<?php
			}
		?>
		<?= $TitleMessage; ?>
	</strong>&nbsp;
	<?= $TextMessage; ?>
</div>