<script>
	$(document ).ready(function(){
//		$('#Privilegios').multiselect({
//			checkAllText: 'Todos',
//			uncheckAllText: 'Ninguno',
//			//header: '',
//			noneSelectedText: 'Privilegios que ven este rol',
//			selectedText: 'Privilegios que ven este rol',
//			classesBtn: 'form-control SelectPrivilege',
//			selectedList: 1,
//			validate: true,
//			classvalid: 'Privilege'
//		}).multiselectfilter({
//			label: 'Buscar',
//			placeholder: 'Teclee el Privilegio a Buscar'
//		});

		$('#ClonarPrivilegios').change(function(){
			console.log($(this).is(':checked'));
			if($(this).is(':checked')){
				$('.ClonePrivileges').each(function(){
					$(this).show();
					$(this).removeAttr('hidden');
				});
			}else{
				$('.ClonePrivileges').hide();
			}
		});
	});
</script>
<!-- end: Create Script-->