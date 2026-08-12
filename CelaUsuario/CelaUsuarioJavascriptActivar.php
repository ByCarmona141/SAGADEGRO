<link href="assets/plugins/passchecker/css/passtrength.css" rel="stylesheet"/>
<script src="assets/plugins/passchecker/js/jquery.passtrength.js" type="f8293aa45fd314b76f6f06dd-text/javascript"></script>
<script type="f8293aa45fd314b76f6f06dd-text/javascript">
	$(document).ready(function(){
		$(function(){
			$('#Contrase_na').rules('add', {
				remote: {
					url: 'AjaxsFunctions.php',
					type: 'post',
					data: {
						Function: 'ValidPassword',
						Password: function(){
							return  CryptoJS.MD5($('#Contrase_na').val()).toString();
						}
					}
				},
				messages:{
					remote: function(){return 'No puede utilizar una contraseña anterior'}
				}
			});
			$('#Contrase_na').passtrength({
                minChars: 8,
                passwordToggle: true,
                eyeImg: 'assets/plugins/passchecker/css/eye.svg',
                tooltip: true
            });
		})
	});
</script>