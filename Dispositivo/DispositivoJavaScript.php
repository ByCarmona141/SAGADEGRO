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
</script>