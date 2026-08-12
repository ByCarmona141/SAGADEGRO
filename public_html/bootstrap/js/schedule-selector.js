(function(a){
	var 
 
})(jQuery);


$(".e_horario").click(function(){
						
						if($("#tablaDeHorarios").length){
							$("#tablaDeHorarios").css({'display':'block'});
						}else{
							var htmBody='';
							var htmlTable='<div class="row"><div class="col-md-12 text-center">SELECCIONE LOS D&Iacute;AS Y HORARIOS EN QUE LABORA</div></div>'+
	'<div class="row">'+
		'<div class="col-md-12">'+
			'<table class="table table-striped table-bordered">'+
				'<thead style="font-size: 10px;">'+
					'<tr><th>LUNES</th><th>MARTES</th><th>MIERCOLES</th><th>JUEVES</th><th>VIERNES</th><th>SABADO</th><th>DOMINGO</th><th>DE 00:00 A 00:00</th><th>Y DE 00:00 A 00:00</th></tr>'+
				'</thead>'+
				'<tbody>';
				for(i=1;i<=3;i++){
					htmBody += '<tr>'+
						'<th><div align="center"><input class="changeH" type="checkbox" name="Lunes'+i+'" id="Lunes'+i+'" value="LUNES" /></div></th>'+
						'<th><div align="center"><input class="changeH" type="checkbox" name="Martes'+i+'" id="Martes'+i+'" value="MARTES" /></div></th>'+
	
						'<th><div align="center"><input class="changeH" type="checkbox" name="Miercoles'+i+'" id="Miercoles'+i+'" value="MIERCOLES" /></div></th>'+
						
						'<th><div align="center"><input class="changeH" type="checkbox" name="Jueves'+i+'" id="Jueves'+i+'" value="JUEVES" /></div></th>'+
						'<th><div align="center"><input class="changeH" type="checkbox" name="Viernes'+i+'" id="Viernes'+i+'" value="VIERNES"/></div></th>'+
	
						'<th><div align="center"><input class="changeH" type="checkbox" name="Sabado'+i+'" id="Sabado'+i+'" value="SABADO" /></div></th>'+
						'<th><div align="center"><input class="changeH" type="checkbox" name="Domingo'+i+'" id="Domingo'+i+'" value="DOMINGO" /></div></th>'+
						'<th><div align="center"><input type="text" class="changeH input-sm form-control" name="Turno1'+i+'" id="Turno1'+i+'" value=""/></div></th>'+
						'<th><div align="center"><input type="text" class="changeH input-sm form-control" name="Turno2'+i+'" id="Turno2'+i+'" value=""/></div></th>'+
					'</tr>';
				}
	
							var contenedor=$(this).parent();
							$(contenedor).append('<div id="tablaDeHorarios" style="position: absolute; z-index: 100; background: white;" >'+htmlTable+htmBody+'</tbody></table></div></div></div>');
						}
							
					});
					
					$(document).delegate('.changeH','change',function(){
						calculaHorario();
					});
					
					$(document).delegate('#tablaDeHorarios','mouseleave', function(){
						calculaHorario();
						$("#tablaDeHorarios").css({'display':'none'});
					});
					
					function calculaHorario(){
						var horario='';
						var dias=['Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo'];
						for(i=1;i<=3;i++){
							for(j=0;j<7;j++){
								if($("#"+dias[j]+i).is(":checked")){
									horario += $("#"+dias[j]+i).val()+', ';
								}
							}
							horario=horario.substring(0, horario.length-2)+' ';
							horario += $("#Turno1"+i).val()+' '+$("#Turno2"+i).val()+'; ';
						}
						
						$("#Turno1").val(horario);
						$("#Turno1Responsable").val(horario);
					}