<div class="row">
	<div class="col-md-3">
<?php
	if (isset($Privileges['Crear']) && $Privileges['Crear'] == 1) {
?>
		<a href="<?= $Table.'?' . EncodeThis('Action=Crear'); ?>" class="btn btn-primary btn-block margin-bottom" data-localized="mailbox.COMPOSE">Redactar</a>
<?php
	}
?>
		<div class="box box-solid">
			<div class="box-header with-border">
				<h3 class="box-title" data-localize="mailbox.FOLDERS">Buzones</h3>
			</div>
			<div class="box-body no-padding">
				<ul class="nav nav-pills nav-stacked">
					<li class="<?= $Action != 'Sent' && $Action != 'Trash' ? 'active':'' ?>">
						<a href="CelaMessage?<?= EncodeThis('Action=Inbox'); ?>">
							<i class="fa fa-inbox"></i> <span data-localize="mailbox.MAINBOX">Inbox</span>
							<span class="label label-primary pull-right"><?= $Unread; ?></span>
						</a>
					</li>
					<li class="<?= $Action == 'Sent' ? 'active':'' ?>">
						<a href="CelaMessage?<?= EncodeThis('Action=Sent'); ?>">
							<i class="fa fa-envelope-o"></i> <span data-localize="mailbox.SENT">Enviados</span>
							<span class="label label-warning pull-right"><?= $Sent; ?></span>
						</a>
					</li>
					<li class="<?= $Action == 'Trash' ? 'active':'' ?>">
						<a href="CelaMessage?<?= EncodeThis('Action=Trash'); ?>">
							<i class="fa fa-trash-alt"></i>&nbsp; <span data-localize="mailbox.TRASH">Basurero</span>
							<span class="label label-danger pull-right"><?= $Trash; ?></span>
						</a>
					</li>
				</ul>
			</div>
			<!-- /.box-body -->
		</div>
	</div>
	<!-- /.col -->
	<div class="col-md-9">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title" data-localize="mailbox.BOX<?= $Action; ?>"><?= $Action; ?></h3>
		<?php
			if($Action == 'Inbox'){
		?>
				<div class="box-tools pull-right">
					<div class="has-feedback">
						<input type="text" autocomplete="off" placeholder="Buscar..."
							class="form-control input-sm DataTableFilter" id="CelaInputSearch<?= $Table; ?>"
							data-tablesearch="Table_<?= $Table; ?>">
						<span class="glyphicon glyphicon-search form-control-feedback"></span>
					</div>
				</div>
				<!-- /.box-tools -->
		<?php
			}
		?>
			</div>
			<!-- /.box-header -->
			<div class="box-body no-padding">
			<?php
				include $MailView;
			?>
			</div>
			<!-- /.box-body -->
		</div>
		<!-- /. box -->
	</div>
	<!-- /.col -->
</div>
<!-- /.row -->