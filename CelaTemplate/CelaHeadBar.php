<?php /*<a class="navbar-brand" href="#x"><img src="assets/images/logo-white.png" alt=""></a>
<button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-toggle" aria-controls="navbar-toggle" aria-expanded="false" aria-label="Toggle navigation">
	<span class="icon-bar top-bar"></span>
	<span class="icon-bar middle-bar"></span>
	<span class="icon-bar bottom-bar"></span>
	<span class="sr-only">Toggle navigation</span>
</button><!-- / navbar-toggler -->

<div class="collapse navbar-collapse" id="navbar-toggle">
	<ul class="navbar-nav ml-auto">
		<li class="nav-item dropdown">
			<a class="nav-link" href="#" >
				<i class="fa fa-group mr-5 fs-14 va-middle"></i>
				<span class="va-middle"></span>
			</a>
		</li><!-- / dropdown -->

		<li class="nav-item dropdown">
			<a class="nav-link" href="CelaUsuario?<?= EncodeThis('Key[]=' . $SessionUserId . '&Action=Actualizar'); ?>" ><i class="fa fa-user mr-5 fs-14 va-middle"></i> <span class="va-middle"><?= strtoupper('USUARIO: ' . $SessionUser); ?></span></a>
		</li><!-- / dropdown -->

		<li class="nav-item">
			<a class="nav-link opc-100" href="Salir"><i class="fa fa-sign-out mr-5 fs-14 va-middle bl-1 border-white pl-50"></i> <span class="va-middle">Salir</span></a>
		</li><!-- / nav-item -->
	</ul><!-- / navbar-nav -->
</div><!-- / navbar-collapse -->*/ ?>


<div id="header" class="app-header app-header-inverse">

	<div class="navbar-header">
		<a href="Escritorio" class="navbar-brand"><?php /*<i class="fab fa-facebook-square fa-lg"></i> <b>MSPV</b> Portal de
			<small>clientes</small>*/ ?>
			<img src="repositorio/configuracion/logo.png" style="height: 50px !important; min-height: 50px !important;"/>
		</a>
		<button type="button" class="navbar-mobile-toggler" data-toggle="app-sidebar-mobile">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
	</div>


	<div class="navbar-nav">
		<?php /*<div class="navbar-item navbar-form">
			<form action="" method="POST" name="search">
				<div class="form-group">
					<input type="text" class="form-control" placeholder="Enter keyword"/>
					<button type="submit" class="btn btn-search"><i class="fa fa-search"></i></button>
				</div>
			</form>
		</div>
		<div class="navbar-item dropdown">
			<a href="page_with_fixed_footer.html#" data-bs-toggle="dropdown"
				class="navbar-link dropdown-toggle icon">
				<i class="fa fa-bell"></i>
				<span class="badge">5</span>
			</a>

			<div class="dropdown-menu media-list dropdown-menu-end">
				<div class="dropdown-header">NOTIFICATIONS (5)</div>
				<a href="javascript:;" class="dropdown-item media">
					<div class="media-left">
						<i class="fa fa-bug media-object bg-gray-400"></i>
					</div>
					<div class="media-body">
						<h6 class="media-heading">Server Error Reports <i
								class="fa fa-exclamation-circle text-danger"></i></h6>

						<div class="text-muted fs-10px">3 minutes ago</div>
					</div>
				</a>
				<a href="javascript:;" class="dropdown-item media">
					<div class="media-left">
						<img src="../assets/img/user/user-1.jpg" class="media-object" alt=""/>
						<i class="fab fa-facebook-messenger text-blue media-object-icon"></i>
					</div>
					<div class="media-body">
						<h6 class="media-heading">John Smith</h6>

						<p>Quisque pulvinar tellus sit amet sem scelerisque tincidunt.</p>

						<div class="text-muted fs-10px">25 minutes ago</div>
					</div>
				</a>
				<a href="javascript:;" class="dropdown-item media">
					<div class="media-left">
						<img src="../assets/img/user/user-2.jpg" class="media-object" alt=""/>
						<i class="fab fa-facebook-messenger text-blue media-object-icon"></i>
					</div>
					<div class="media-body">
						<h6 class="media-heading">Olivia</h6>

						<p>Quisque pulvinar tellus sit amet sem scelerisque tincidunt.</p>

						<div class="text-muted fs-10px">35 minutes ago</div>
					</div>
				</a>
				<a href="javascript:;" class="dropdown-item media">
					<div class="media-left">
						<i class="fa fa-plus media-object bg-gray-400"></i>
					</div>
					<div class="media-body">
						<h6 class="media-heading"> New User Registered</h6>

						<div class="text-muted fs-10px">1 hour ago</div>
					</div>
				</a>
				<a href="javascript:;" class="dropdown-item media">
					<div class="media-left">
						<i class="fa fa-envelope media-object bg-gray-400"></i>
						<i class="fab fa-google text-warning media-object-icon fs-14px"></i>
					</div>
					<div class="media-body">
						<h6 class="media-heading"> New Email From John</h6>

						<div class="text-muted fs-10px">2 hour ago</div>
					</div>
				</a>

				<div class="dropdown-footer text-center">
					<a href="javascript:;" class="text-decoration-none">View more</a>
				</div>
			</div>
		</div>*/ ?>
		<div class="navbar-item navbar-user dropdown">
			<a href="page_with_fixed_footer.html#" class="navbar-link dropdown-toggle d-flex align-items-center"
				data-bs-toggle="dropdown">
				<img src="../assets/img/user/user-13.jpg" alt=""/>
				<span class="d-none d-md-inline"><?= GetValue('SELECT NombreCompleto FROM CelaUsuario WHERE id = ' . $SessionUserId . ';', 'NombreCompleto') ?></span> <b class="caret ms-6px"></b>
			</a>

			<div class="dropdown-menu dropdown-menu-end me-1">
				<a href="javascript:;" class="dropdown-item"><?= strtoupper($SessionGroup); ?></a>
				<div class="dropdown-divider"></div>
				<a href="CelaUsuario?<?= EncodeThis('Key[]=' . $SessionUserId . '&Action=Actualizar'); ?>" class="dropdown-item"><i class="fas fa-user"></i> Ver Perfil</a>
				<a href="Salir" class="dropdown-item"><i class="fas fa-sign-out-alt"></i> Salir</a>
			</div>
		</div>
		<div class="navbar-item dropdown">
			<a href="page_with_fixed_footer.html#" data-bs-toggle="dropdown" class="navbar-link dropdown-toggle icon">
				<i class="fa fa-cogs"></i>
			</a>
			<div class="dropdown-menu media-list dropdown-menu-end">
				<div class="dropdown-header">OPCIONES</div>
				<a id="LockSession" class="dropdown-item" title="Bloquear pantalla"><i class="fas fa-unlock-alt"></i>&nbsp;&nbsp;Bloquear sesi&oacute;n</a>
				<a href="#CelaModalAcercaDe" data-toggle="modal" title="Acerca de" class="dropdown-item"><i class="fas fa-info"></i>&nbsp;&nbsp;Acerca de</a>
			</div>
		</div>
	</div>

</div>