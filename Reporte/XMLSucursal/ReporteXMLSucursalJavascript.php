<?php
    $Table = 'XMLSucursal';
?>

<script type="f8293aa45fd314b76f6f06dd-text/javascript">
    
    function SearchXMLSucursal(){
        /*Se obtienen los criterios de búsqueda*/
        var Entregable = $('#Entregable').val();
        
        /*Se asignan los citerios de búsqueda*/
        $.post('AjaxsFunctions.php', {
            'Function': 'Encrypt',
            'Key': '<?= $_COOKIE['CelaRandom']; ?>',
            'String': '{"Entregable":"' + Entregable + '"}'
        },function(data){
            $('#Table_Cortes').data('params', data);
            /*Se invoca la función de búsqueda*/
            CelaTable['Table_Cortes'].fnFilter();
        });
    }
    
    $(document).ready(function(){
        CelaTable['Table_XMLSucursal'].on( 'draw.dt', function () {
            if(CelaQuery['Table_XMLSucursal'] == ''){
                $('#BotonDescargarFiltro').attr('href', '#');
            }else{
                $.post('AjaxsFunctions', {
                    Function:   'EncodeThisAjaxs',
                    String:     'All=Query&Query=' + CelaQuery['Table_XMLSucursal'] + '&Action=Descargar'
                },
                function(data){
                    $('#BotonDescargarFiltro').attr('href', 'Nomina?' + data);
                });
            }
        });
    });
    
    $(document).delegate('.Selected<?= $Table; ?>', 'change', function(){
        GetAllSelected<?= $Table; ?>();
    });

    $(document).delegate('#All<?= $Table; ?>', 'change', function(){
        $('.Selected<?= $Table; ?>').each(function(){
            $(this).prop('checked', $('#All<?= $Table; ?>').is(':checked'));
        });
        GetAllSelected<?= $Table; ?>();
    });

    function GetAllSelected<?= $Table; ?>(){
        var Get = '', Cont=0;
        $('.Selected<?= $Table; ?>').each(function(){
            if($(this).is(':checked')){
                var id  = $(this).data('index');
                Get += 'Key[]='+id+'&';
                Cont++;
            }
        });

        Get = Get.substring(0, Get.length - 1);
        if(Get != ''){
            $.post('AjaxsFunctions', {
                Function:   'EncodeThisAjaxs',
                String:     Get + '&Action=Descargar'
            },
            function(data){
                if(Cont < 2){
                    $('#<?= $Table; ?>BotonDescargar').attr('disabled', 'disabled');
                    $('#<?= $Table; ?>BotonDescargar').attr('href', '#');
                }else{
                    $('#<?= $Table; ?>BotonDescargar').removeAttr('disabled');
                    $('#<?= $Table; ?>BotonDescargar').attr('href', 'Nomina?' + data);
                }
            });
        }else{
            var href = '#';
            $('#<?= $Table; ?>BotonDescargar').attr('disabled', 'disabled');
            $('#<?= $Table; ?>BotonDescargar').attr('href', '#');
        }
    }
    
    $('#PeriodoInicio').change(function(){
        CelaTable['Table_XMLSucursal'].fnFilter($('#PeriodoInicio').val(), 5);
    });
    $('#PeriodoFin').change(function(){
        CelaTable['Table_XMLSucursal'].fnFilter($('#PeriodoFin').val(), 6);
    });

    
</script>
<!-- end: Table Script-->
