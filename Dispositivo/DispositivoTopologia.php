<div class="row col-md-12 mb-3">
	<div class="col-md-2 text-left">
		<?php
			if (isset($Privileges['Crear']) && $Privileges['Crear'] == 1) {
				?>
				<a href="<?= $Table.'?' . EncodeThis(  'Action=Crear' . (isset($Vars) && $Vars != '' ? '&' . $Vars:'')); ?>" title="Agregar" class="btn btn btn-success" data-position="top" data-intro="Insertar nuevo <?= $Table; ?>" id="<?= $Table; ?>Bot_onCrear">
			<span>
				<i class="fa fa-plus"></i>&nbsp; Agregar
			</span>
				</a>
				<?php
			}
		?>
	</div>
	<div class="col-md-3 text-left form-inline">
		
	</div>
	<div class="col-md-3 text-left form-inline">
	<?php
			if (isset($Privileges['Leer']) && $Privileges['Leer'] == 1) {
				?>
				<a href="<?= $Table.'?' ; ?>" title="Listado" class="btn btn btn-primary" data-position="top" data-intro="Cambiar Vista <?= $Table; ?>" id="<?= $Table; ?>Bot_onListado">
			<span>
				<i class="fas fa-list"></i>&nbsp; Listado
			</span>
				</a>
				<?php
			}
		?>
	</div>
	<div class="col-md-4 text-right">
		
	</div>
</div>

<?php
/**
 * Recibe desde el controlador:
 *   $Dispositivos  → array formateado por DispositivoTopologia()
 */

$dispositivosJson = json_encode($Dispositivos, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);

/* ============================================================
   Generar URLs pre-codificadas con EncodeThis() para:
   1. Redirigir a Acceso.php?Action=Crear&Dispositivo=ID
   2. Redirigir a Dispositivo.php?Action=Actualizar&Key[]=ID
   ============================================================ */
$AccesoUrls = array();
$DispositivoEditUrls = array();

if (isset($Dispositivos) && is_array($Dispositivos)) {
    foreach ($Dispositivos as $d) {
        // URL para crear acceso en el módulo Acceso
        $AccesoUrls[$d['id']] = 'Acceso?' . EncodeThis('Action=Crear&Dispositivo=' . $d['id']);

        // URL para actualizar/editar el dispositivo (mismo patrón CELA)
        $DispositivoEditUrls[$d['id']] = 'Dispositivo?' . EncodeThis('Key[]=' . $d['id'] . '&Action=Actualizar');
    }
}
$AccesoUrlsJson = json_encode($AccesoUrls);
$DispositivoEditUrlsJson = json_encode($DispositivoEditUrls);
?>

<!-- Toolbar de la topología -->
<div class="row" style="margin-bottom: 15px;">
    <div class="col-md-12">
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-default" onclick="location.reload()">
                <i class="fa fa-refresh"></i> Actualizar
            </button>
            <button type="button" class="btn btn-default" onclick="sgGraph.fitToContent()">
                <i class="fa fa-arrows-alt"></i> Ajustar vista
            </button>
            <button type="button" class="btn btn-default" onclick="sgGraph.layout()">
                <i class="fa fa-sitemap"></i> Reorganizar
            </button>
        </div>

        <div class="btn-group" role="group" style="margin-left: 10px;">
            <input type="text" id="sg-buscar" class="form-control" placeholder="Buscar dispositivo..." 
                   style="display: inline-block; width: 220px;" onkeyup="buscarDispositivo(this.value)">
        </div>

        <div class="btn-group" role="group" style="margin-left: 10px;">
            <select id="sg-filtro-tipo" class="form-control" style="display: inline-block; width: 150px;" onchange="filtrarPorTipo(this.value)">
                <option value="">Todos los tipos</option>
                <option value="router">Router</option>
                <option value="modem">Módem</option>
                <option value="camera">Cámara</option>
                <option value="ap">Access Point</option>
                <option value="switch">Switch</option>
                <option value="firewall">Firewall</option>
                <option value="server">Servidor</option>
                <option value="other">Otros</option>
            </select>
        </div>

        <div class="btn-group" role="group" style="margin-left: 5px;">
            <select id="sg-filtro-estado" class="form-control" style="display: inline-block; width: 140px;" onchange="filtrarPorEstado(this.value)">
                <option value="">Todos los estados</option>
                <option value="active">Activos</option>
                <option value="inactive">Inactivos</option>
            </select>
        </div>
    </div>
</div>

<!-- Leyenda de tipos -->
<div class="row" style="margin-bottom: 10px;">
    <div class="col-md-12">
        <span style="font-size: 12px; color: #666; margin-right: 15px;"><strong>Leyenda:</strong></span>
        <span class="label" style="background: #dbeafe; color: #1d4ed8; margin-right: 8px;">🌐 Router</span>
        <span class="label" style="background: #dcfce7; color: #15803d; margin-right: 8px;">📡 Módem</span>
        <span class="label" style="background: #fef3c7; color: #b45309; margin-right: 8px;">📹 Cámara</span>
        <span class="label" style="background: #f3e8ff; color: #7c3aed; margin-right: 8px;">📶 AP</span>
        <span class="label" style="background: #e0e7ff; color: #3730a3; margin-right: 8px;">🔀 Switch</span>
        <span class="label" style="background: #cffafe; color: #0e7490; margin-right: 8px;">🖥️ Servidor</span>
        <span class="label" style="background: #fee2e2; color: #b91c1c; margin-right: 8px;">🛡️ Firewall</span>
    </div>
</div>

<!-- Contenedor del grafo -->
<div class="row">
    <div class="col-md-12">
        <div class="w-100" id="sg-graph-container" style="height: 68vh; min-height: 500px; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0;"></div>
    </div>
</div>

<!-- Toast de notificación -->
<div id="sg-toast" class="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999; display: none; min-width: 300px;"></div>

<!-- Carga de SagaGraph -->
<script src="assets/js/sagagraph.js"></script>

<script>
(function() {
    'use strict';

    var dispositivosOriginales = <?= $dispositivosJson ?>;
    var dispositivosFiltrados = dispositivosOriginales.slice();

    /* URLs pre-codificadas con EncodeThis() desde PHP */
    var accesoUrls = <?= $AccesoUrlsJson ?>;
    var dispositivoEditUrls = <?= $DispositivoEditUrlsJson ?>;

    if (typeof SagaGraph === 'undefined') {
        document.getElementById('sg-graph-container').innerHTML = 
            '<div style="padding:40px;text-align:center;color:#b91c1c;">' +
            '<i class="fa fa-exclamation-triangle" style="font-size:48px;margin-bottom:15px;"></i><br>' +
            '<strong>Error al cargar la librería SagaGraph.</strong><br>' +
            'Verifica que <code>assets/js/sagagraph.js</code> exista.' +
            '</div>';
        return;
    }

    var graph = new SagaGraph('sg-graph-container', {
        nodeWidth: 240,
        nodeHeight: 190,
        gapX: 90,
        gapY: 130,
        minZoom: 0.15,
        maxZoom: 3
    });

    graph
        .setData(dispositivosFiltrados)
        .on('onRegistrarAcceso', function(device) {
            // Redirige a Acceso.php?Action=Crear&Dispositivo=ID (codificado con EncodeThis)
            if (accesoUrls[device.id]) {
                window.location.href = accesoUrls[device.id];
            }
        })
        .on('onVerDetalle', function(device) {
            // Redirige a Dispositivo.php?Action=Actualizar&Key[]=ID (codificado con EncodeThis)
            if (dispositivoEditUrls[device.id]) {
                window.location.href = dispositivoEditUrls[device.id];
            }
        })
        .on('onNodeClick', function(device) {
            console.log('Nodo seleccionado:', device);
        })
        .on('onLayoutComplete', function() {
            graph.fitToContent();
        })
        .layout();

    window.sgGraph = graph;

    // Buscador
    window.buscarDispositivo = function(query) {
        var q = query.toLowerCase().trim();
        if (!q) {
            dispositivosFiltrados = dispositivosOriginales.slice();
        } else {
            dispositivosFiltrados = dispositivosOriginales.filter(function(d) {
                return (d.name && d.name.toLowerCase().indexOf(q) !== -1) ||
                       (d.ip && d.ip.toLowerCase().indexOf(q) !== -1) ||
                       (d.mac && d.mac.toLowerCase().indexOf(q) !== -1) ||
                       (d.location && d.location.toLowerCase().indexOf(q) !== -1);
            });
        }
        aplicarFiltrosAdicionales();
    };

    // Filtros
    var filtroTipo = '';
    var filtroEstado = '';

    window.filtrarPorTipo = function(tipo) {
        filtroTipo = tipo;
        aplicarFiltrosAdicionales();
    };

    window.filtrarPorEstado = function(estado) {
        filtroEstado = estado;
        aplicarFiltrosAdicionales();
    };

    function aplicarFiltrosAdicionales() {
        var resultado = dispositivosFiltrados.slice();
        if (filtroTipo) {
            resultado = resultado.filter(function(d) { return d.type === filtroTipo; });
        }
        if (filtroEstado) {
            resultado = resultado.filter(function(d) { return d.status === filtroEstado; });
        }
        if (resultado.length === 0) {
            mostrarToast('No se encontraron dispositivos con esos filtros', 'warning');
        }
        graph.setData(resultado).layout();
    }

    // Toast
    function mostrarToast(mensaje, tipo) {
        var toast = document.getElementById('sg-toast');
        toast.className = 'alert alert-' + tipo;
        toast.innerHTML = '<i class="fa fa-' + (tipo === 'success' ? 'check' : tipo === 'warning' ? 'exclamation-triangle' : 'times') + '"></i> ' + mensaje;
        toast.style.display = 'block';
        setTimeout(function() { toast.style.display = 'none'; }, 4000);
    }
})();
</script>
