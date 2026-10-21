<?php
// Archivo: Dispositivo/DispositivoJavaScript.php
// Descripción: Archivo que contiene el JavaScript para la gestión de dispositivos
// Autor: M. Carlos Alvarez Carmona
// Fecha: 2026-10-10
// Versión: 1.0.0
?>
<script>
    function validarMAC() {
        const mac = document.getElementById("mac").value.trim();

        const macRegex = /^([0-9A-Fa-f]{2}[:-]){5}[0-9A-Fa-f]{2}$/;

        if (macRegex.test(mac)) {
            alert("MAC válida");
        } else {
            alert("MAC inválida");
        }
    }
</script>