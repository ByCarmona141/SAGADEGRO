<?php
    
    global $SessionGroupId;
    global $GlobalConfig;
    
    $Clientes = GetValue("
            select count(*) as Cantidad
            from Cliente", "Cantidad");
    
    $Empleados = GetValue("
            select count(*) as Cantidad
            from Empleado", "Cantidad");
    
    $rfcs = GetValue("
            select count(*) as Cantidad
            from RFCCliente", "Cantidad");
    
    $Usuarios = GetValue("
            select count(*) as Cantidad
            from CelaUsuario", "Cantidad");
    
    $UsuariosCliente = GetValue("
            select count(*) as Cantidad
            from CelaUsuario where Rol in (select id from CelaRol where Grupo = 5)", "Cantidad");
    
    $dashboard_indicador = json_decode($GlobalConfig['dash_ind'], TRUE);
    
    if(in_array($SessionGroupId, $dashboard_indicador)){
        ?>
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="widget widget-stats bg-primary">
                    <div class="stats-icon"><i class="fa fa-users"></i></div>
                    <div class="stats-info">
                        <h4>Cantidad de Clientes</h4>
                        <p><?= $Clientes ?></p>
                    </div>
                    <div class="stats-link">
                        <a href="Cliente">Ir a <i class="fa fa-arrow-alt-circle-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="widget widget-stats bg-info">
                    <div class="stats-icon"><i class="fa fa-users-cog"></i></div>
                    <div class="stats-info">
                        <h4>Cantidad de Empleados </h4>
                        <p><?= number_format($Empleados, 0, '', ',') ?></p>
                    </div>
                    <div class="stats-link">

                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="widget widget-stats bg-warning">
                    <div class="stats-icon"><i class="fa fa-file"></i></div>
                    <div class="stats-info">
                        <h4>Cantidad de RFC Clientes </h4>
                        <p><?= number_format($rfcs, 0, '', ',') ?></p>
                    </div>
                    <div class="stats-link">

                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="widget widget-stats bg-secondary">
                    <div class="stats-icon"><i class="fa fa-users"></i></div>
                    <div class="stats-info">
                        <h4>Cantidad de Usuarios </h4>
                        <p><?= number_format($Usuarios, 0, '', ',') ?></p>
                    </div>
                    <div class="stats-link">

                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="widget widget-stats bg-danger">
                    <div class="stats-icon"><i class="fa fa-user-alt"></i></div>
                    <div class="stats-info">
                        <h4>Cantidad de Usuarios de Clientes </h4>
                        <p><?= number_format($UsuariosCliente, 0, '', ',') ?></p>
                    </div>
                    <div class="stats-link">

                    </div>
                </div>
            </div>
        </div>
        <?php
    }
