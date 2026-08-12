<a data-position="left" data-intro="Regresa al formulario anterior" title="ir atrás" href="<?= isset($_SERVER['HTTP_REFERER']) ? substr($_SERVER['HTTP_REFERER'], strripos($_SERVER['HTTP_REFERER'], '/') + 1, strlen($_SERVER['HTTP_REFERER'])):'Escritorio
.php'; ?>" class="btn
btn-danger">
	<i class="fa fa-arrow-left"></i>&nbsp; ir atrás
</a>