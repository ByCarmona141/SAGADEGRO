<?php
    include '../CelaTemplate/CelaTableTools.php';
?>
<div class="row">
    <div class="col-md-12">
        <table id="Table_CelaProceso" class="table table-striped table-bordered  table-hover datatable" data-source="<?= $ServerSource; ?>" data-function="<?= $ServerFunction; ?>" data-form="<?= $RouteForm; ?>">
            <thead>
            <tr>
                <th width="1%" title="Seleccionar todo">
                    <div class="text-center">
                        <label>
                            <input  type="checkbox" id="All<?= $Table; ?>" data-intro="Selecciona todos los registros de esta p&aacute;gina" data-position="bottom"/>
                        </label>
                    </div>
                </th>
                <th class="sortable" width="9%"><div class="text-center"> pid </div></th>
                <th class="sortable" width="10%"><div class="text-center"> Status </div></th>
                <th class="sortable" width="20%"><div class="text-center"> Script </div></th>
                <th class="sortable" width="20%"><div class="text-center"> Parametros </div></th>
                <th class="sortable" width="20%"><div class="text-center"> Resultado </div></th>
                <th class="sortable" width="20%"><div class="text-center"> Fecha de inicio </div></th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
