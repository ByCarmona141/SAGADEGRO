<script src="assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<script src="assets/plugins/select2/select2.full.min.js"></script>
<script>
	$(function () {
		//Add text editor
		$('#message').wysihtml5();

		$('#to').select2();
	});
</script>