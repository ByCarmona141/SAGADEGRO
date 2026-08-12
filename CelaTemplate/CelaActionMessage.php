
<div class="alert alert-<?= $StatusMessage; ?> <?= isset($NoDimiss) && $NoDimiss == 1 ? '':'alert-dismissible'; ?> fade show" role="alert">
	<?php
		if(isset($IconMessage) && $IconMessage != '') {
			?>
			<i class="fa <?= $IconMessage ?> fa-lg va-middle"></i>&nbsp;
			<?php
		}
	?>
	<span><strong><?= $TitleMessage; ?></strong>&nbsp;<?= $TextMessage; ?></span>
	<button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
</div><!-- / alert -->