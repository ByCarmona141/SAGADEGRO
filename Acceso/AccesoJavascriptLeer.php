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