<?
/*Acentuação teste chão*/
?>

<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
		<meta charset="utf-8" />
		<meta http-equiv="Cache-control" content="public">
        <title>SPC - Sistema de pesquisa clínica</title>
		<link rel="shortcut icon" href="favicon.ico" />
		<link href="assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
		<link href="assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/layouts/layout3/css/layout.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/layouts/layout3/css/themes/default.min.css" rel="stylesheet" type="text/css" id="style_color" />
        <link href="assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />

		<link href="assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/layouts/layout3/css/layout.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/layouts/layout3/css/themes/default.min.css" rel="stylesheet" type="text/css" id="style_color" />
        <link href="assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

		<style>
		.container {
			width: 100vw;
			height: 100vh;
			background: #EFF3F8;
			display: flex;
			flex-direction: row;
			justify-content: center;
			align-items: center
		}
		.box {
			width: 600px;
			height: 400px;
			background: #fff;
		}
		body {
			margin: 0px;
		}
		</style>
	</head>
	<body>

		<div class="container">
			<div class="box">

				<div class="portlet light">
						<div class="portlet-title">
							<div class="caption">
								<i class="icon-speech"></i>
								<span class="caption-subject bold uppercase"> Informe a data do documento</span>
							</div>
						</div>
						<div class="portlet-body">
							<div class="scroller" style="height:300px" data-rail-visible="1" data-rail-color="yellow" data-handle-color="#a1b2bd">
								<h4>Data:</h4>
								<p>
									<input  onkeypress='validate(event)'
									onkeyup="javascript:mascara(this, '##/##/####');"
									id="form_data_carta"
									name="form_data_carta<?=rand(123,3425234);?>"
									style="text-align: center; "
									type="text"
									placeholder="(dd/mm/aaaa)"
									class="form-control input-md ">
								<input name="form_escondido" id="form_escondido" type="hidden" value="<?=$_GET['volta'];?>">
								<input name="form_id" id="form_id" type="hidden" value="<?=$_GET['id'];?>">
								</p>
								<h4>Localidade:</h4>
								<p>
									<select name="localidade" id="localidade" class="form-control input-md ">
										<option value="go">Goiânia</option>
										<option value="cps">Campinas</option>
									</select>
								</p>
								<h4>Idioma:</h4>
								<p>
									<select name="idioma" id="idioma" class="form-control input-md ">
										<option value="pt">Português</option>
										<option value="en">Inglês</option>
									</select>
								</p>
							</div>
						</div>
						<div class="portlet-footer" style="text-align:center">
							<hr>
							<button type="button" data-dismiss="modal" class="btn dark btn-outline" onclick="voltar()">Cancelar/Voltar</button>
							<button type="button" data-dismiss="modal" class="btn green" onclick="continuar();">Continuar</button>
						</div>
					</div>
				</div>


			</div>
		</div>

	</body>
	<script src="assets/global/plugins/jquery.min.js" type="text/javascript"></script>
	<script src="assets/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
    <script src="assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script src="js/funcoes.js"></script>
	<script>
		function popup(url){
			params  = 'width=700';
			params += ', height=500';
			params += ', top=0, left=0'
			params += ', fullscreen=yes';
			newwin=window.open(url,'windowname4', params);
			if (window.focus) {newwin.focus()}
			return false;
		}
		$(document).ready(function () {
			$('#form_data_carta').datepicker({
				dateFormat: 'dd/mm/yy',
				locale: 'pt-br',
				dayNames: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'],
				dayNamesMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S', 'D'],
				dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
				monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
				monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
				nextText: 'Proximo',
				prevText: 'Anterior'
			}).on('show.bs.modal', function(event) {
				event.stopPropagation();
			});;
		});
		$( function() {
			var some_id = document.getElementById('form_data_carta');
			some_id.type = 'text';
			some_id.removeAttribute('autocomplete');
		} );
		function continuar(){
			var data = document.getElementById("form_data_carta").value;
			var id   = document.getElementById("form_id").value;
			var volta = document.getElementById("form_escondido").value;
			var localidade = document.getElementById("localidade").value;
			var idioma = document.getElementById("idioma").value;

			if(document.getElementById("idioma").value=="en"){
				volta = volta.split(".php")[0] + "_en.php";
			}


			if(CheckDate(document.getElementById("form_data_carta"))==true){
				popup(volta+"?id="+id+"&data="+data+"&idioma="+idioma+"&localidade="+localidade);
				document.location = "agenda.php";
			}else{
				alert("Data inválida. Por favor, revise a data digitada");
			}
		}
		function voltar(){
			var id   = document.getElementById("form_id").value;
			var volta = document.getElementById("form_escondido").value;
			document.location = "agenda.php";
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