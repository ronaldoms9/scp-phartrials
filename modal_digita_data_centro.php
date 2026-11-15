<?
	include "seguranca.php";
?>
<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
		<meta charset="utf-8" />
		<meta http-equiv="Cache-control" content="public">
        <title>SPC - Sistema de pesquisa clínica</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="Preview page of Metronic Admin Theme #3 for " name="description" />
        <meta content="" name="author" />
		<link href="plugins/font/css/font-awesome.css" rel="stylesheet">		
		<link href='https://fonts.googleapis.com/css?family=Abel' rel='stylesheet'>
        <link href="assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />        
        <link href="assets/layouts/layout3/css/layout.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/layouts/layout3/css/themes/default.min.css" rel="stylesheet" type="text/css" id="style_color" />
        <link href="assets/global/css/geral.css" rel="stylesheet" type="text/css" />      		
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">		
		<script src="js/sweetalert2.all.min.js"></script>
		<link rel="shortcut icon" href="favicon.ico" /> 
		<style>
			.datepicker {
				z-index: 99999 !important;
			}
		</style>
	</head>
	<body>
			<div class="modal-content" id="myModal">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h4 class="modal-title">Data</h4>
				</div>
				<div class="modal-body">
					<table width="100%">
						<tr>
							<td>Informe a data: </td>
							<td>
								<input onkeypress='validate(event)' onkeyup="javascript:mascara(this, '##/##/####');" id="form_data_carta" name="form_data_carta" style="text-align: center; " type="text" placeholder="" class="form-control input-md picker" <?=$centralizado;?>>
								<input name="form_escondido" id="form_escondido" type="hidden" value="<?=$_GET['volta'];?>">
								<input name="form_id" id="form_id" type="hidden" value="<?=$_GET['id'];?>">
							</td>
							<td width="30%"></td>
						</tr>
					</table>
					<div class="col-md-4"></div>
				</div>
				<div class="modal-footer">
					<button type="button" data-dismiss="modal" class="btn dark btn-outline">Cancelar</button>
					<button type="button" data-dismiss="modal" class="btn green" onclick="continuar();">Continuar</button>
				</div>
			</div>
	</body>
	<script src="assets/global/plugins/jquery.min.js" type="text/javascript"></script>		      
	<script type="text/javascript" src="assets/global/plugins/jquery-ui/jquery-ui.min.js"></script>
	<script src="assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>                
	<script src="js/global.js" type="text/javascript"></script>            
	<script src="assets/layouts/layout3/scripts/layout.min.js" type="text/javascript"></script>				
	<link rel="stylesheet" href="css/sweetalert2.css">		
	<script src="js/funcoes.js" type="text/javascript"></script>
	<script src="js/jquery.idletimeout.js" type="text/javascript"></script>
	<script src="js/jquery.idletimer.js" type="text/javascript"></script>
	<script src="js/paginas/idle.js" type="text/javascript"></script>
	<script src="js/ui.datepicker-pt-BR.js" type="text/javascript"></script>
	<script src="js/moment.min.js"></script>
	<script src="plugin/js/fullcalendar.min.js"></script>
	<script src="plugin/js/locale/pt-br.js"></script>
	<script src="js/funcoes.js"></script>
	<script>
		$( function() {
			$( "#form_data" ).datepicker({
				autoclose: true,
				container: '#myModal',
				format: 'yyyy-mm-dd',
				'z-index': 500
			});
			var some_id = document.getElementById('form_data_carta');
			some_id.type = 'text';
			some_id.removeAttribute('autocomplete');
		} );
		function continuar(){			
			var data = document.getElementById("form_data_carta").value;			
			var id   = document.getElementById("form_id").value;			
			var volta = document.getElementById("form_escondido").value;			
			if(CheckDate(document.getElementById("form_data_carta"))==true){
				chama_pagina(volta, id, data);
			}else{
				alert("Data inválida. Por favor, revise a data digitada");
			}
		}
		function CheckDate(pObj) {
		  var expReg = /^((0[1-9]|[12]\d)\/(0[1-9]|1[0-2])|30\/(0[13-9]|1[0-2])|31\/(0[13578]|1[02]))\/(19|20)?\d{2}$/;
		  var aRet = true;
		  if ((pObj) && (pObj.value.match(expReg)) && (pObj.value != '')) {
			var dia = pObj.value.substring(0,2);
			var mes = pObj.value.substring(3,5);
			var ano = pObj.value.substring(6,10);
			if ((mes == 4 || mes == 6 || mes == 9 || mes == 11 ) && dia > 30) 
			  aRet = false;
			else 
			  if ((ano % 4) != 0 && mes == 2 && dia > 28) 
				aRet = false;
			  else
				if ((ano%4) == 0 && mes == 2 && dia > 29)
				  aRet = false;
		  }  else 
			aRet = false;  
		  return aRet;
		}		
		function validate(evt) {
		  var theEvent = evt || window.event;
		  // Handle paste
		  if (theEvent.type === 'paste') {
			  key = event.clipboardData.getData('text/plain');
		  } else {
		  // Handle key press
			  var key = theEvent.keyCode || theEvent.which;
			  key = String.fromCharCode(key);
		  }
		  var regex = /[0-9]|\./;
		  if( !regex.test(key) ) {
			theEvent.returnValue = false;
			if(theEvent.preventDefault) theEvent.preventDefault();
		  }
		}
	</script>
</html>