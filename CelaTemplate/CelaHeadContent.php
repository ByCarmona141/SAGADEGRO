<div class="panel-heading">
	<h4 class="panel-title"><?= $FormTitle; ?></h4>

	<div class="panel-heading-btn">
		<a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand">
			<i class="fa fa-expand"></i>
		</a>
		<a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse">
			<i class="fa fa-minus"></i>
		</a>
		<a href="javascript:;" class="btn btn-xs btn-icon btn-info btn-help" data-intro="Ayuda general">
			<i class="fa fa-question"></i>
		</a>
		<a title="Componente" class="btn btn-xs btn-icon btn-compoenete btn-default" href="CelaComponente?<?= EncodeThis('Component=' . str_replace('.php', '', $Form)); ?>" data-position="left" data-intro="Componentes del Formulario">
			<i class="fa fa-cogs"></i>
		</a>
	</div>
</div>
