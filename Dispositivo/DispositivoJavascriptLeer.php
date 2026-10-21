<?php
// Archivo: Dispositivo/DispositivoJavascriptLeer.php
// Descripción: Archivo que contiene el Javascript para la gestión de dispositivos
// Autor: M. Carlos Alvarez Carmona
// Fecha: 2026-10-20
// Versión: 1.0.0
?>
<script>
    function copyToClipboard(elemento) {
        var $temp = $("<input>");

        $("body").append($temp);
        $temp.val($(elemento).text()).select();
        document.execCommand("copy");
        $temp.remove();

        Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: 'El texto ha sido copiado',
            showConfirmButton: false,
            timer: 1500
        });
    }
</script>