<?
	include_once "seguranca.php";
?>
<html>
	<head>
		<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
		<link href="https://fonts.googleapis.com/css?family=Abel|Archivo+Narrow|PT+Sans+Narrow:700|Pragati+Narrow" rel="stylesheet">
        <link href='https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' rel='stylesheet'/>
		<link href="assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
		<link href="assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
		 <link href="assets/layouts/layout3/css/layout.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/layouts/layout3/css/themes/default.min.css" rel="stylesheet" type="text/css" id="style_color" />
        <link href="assets/layouts/layout3/css/custom.min.css" rel="stylesheet" type="text/css" />
	</head>
	<style>
		.lista_formularios{
			font-size: 13px !important;
			/*font-family: 'Pragati Narrow', sans-serif;*/
			/*font-family: 'Archivo Narrow', sans-serif;*/
			font-family: 'Abel', sans-serif;
		}
		.tabela-head{
			font-weight: bold !important;
			font-family: 'Abel', sans-serif !important;
			font-size: 14px !important;
			background-color:#f7f7f7 !important;
			border-bottom: 2px solid #2C3E50 !important;
			border-top: 2px solid #2C3E50 !important;
		}
		.tabela-divisao{
			font-weight: bold !important;
			font-family: 'Abel', sans-serif !important;
			font-size: 12px !important;
			/*E9EDEF*/
			background-color:#f7f7f7 !important;
			border-bottom: 2px solid #2C3E50 !important;
			border-top: 1px solid #2C3E50 !important;
			font-weight: bold !important;
		}
		.tabela-body{
			font-family: 'Abel', sans-serif !important;
			font-size: 14px !important;
		}
		.controle{
			font-family: 'Abel', sans-serif !important;
			font-size: 13px !important;
			padding: 8px 8px !important;
			line-height: 20px !important;
			text-align: center;
			text-align-last:center;
		}
		.legenda{
			font-family: 'Abel', sans-serif !important;
			font-size: 10px !important;
			font-style: italic !important;
		}
		.fixo {
		}
		.coluna{
			color: black;
			vertical-align: center;
			background-color: white;
			color: black;
			border: 1px solid #e7e7e7;
			padding: 0.2px;
			width:25px !important;
		}
		.header_json{
			border: 1.0pt solid windowtext;
			padding-left: 5.4pt;
			padding-right: 5.4pt;
			padding-top: 4pt;
			padding-bottom: 3pt;
			background: #F2F2F2
		}
		.header_json_pt{
			font-size: 9.0pt

		}
		.header_json_en{
			font-size:7.0pt;
			font-style: italic;
		}
		.item_json{
			padding-top: 4pt;
			padding-bottom: 3pt;
		}
	</style>
	<body>
		<div class="portlet light">
			<div class="portlet-title fixo">
				<div class="caption font-blue-dark">
				</div>
				<div class="actions">
					<a href="javascript:novo();" class="btn btn-circle btn-default btn-sm">
						<i class="fa fa-plus"></i> Novo ítem </a>
				</div>
			</div>
			<div class="portlet-body">
				<div name="div_retorno" id="div_retorno">
				</div>
			</div>
			<div class="portlet-footer"></div>
		</div>
	</body>
	<script src="assets/global/plugins/jquery.min.js" type="text/javascript"></script>
	<script src="assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
	<script src="assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
	<script src="assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
	<script src="assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
	<script src="assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
	<script src="assets/global/plugins/jquery.pulsate.min.js" type="text/javascript"></script>
	<script src="assets/global/plugins/jquery-bootpag/jquery.bootpag.min.js" type="text/javascript"></script>
	<script src="assets/global/plugins/holder.js" type="text/javascript"></script>
	<script src="assets/global/scripts/app.min.js" type="text/javascript"></script>
	<script src="assets/pages/scripts/ui-general.min.js" type="text/javascript"></script>
	<script src="assets/layouts/layout3/scripts/layout.min.js" type="text/javascript"></script>
	<script src="assets/layouts/layout3/scripts/demo.min.js" type="text/javascript"></script>
	<script src="assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
	<script src="assets/layouts/global/scripts/quick-nav.min.js" type="text/javascript"></script>
	<script src="js/funcoes.js" type="text/javascript"></script>
	<script>
		function mostraCarregando(){
			$.blockUI({ timeout: 500, message: '<img src="imagens/carregando.gif" width="100">Aguarde, carregando informações...', css: {
				border: 'none',
				padding: '15px',
				backgroundColor: '#000',
				'-webkit-border-radius': '10px',
				'-moz-border-radius': '10px',
				opacity: .5,
				color: '#fff'
			} });
		}
		function removeCarregando(){
			$.unblockUI();
		}
		function novo(){
			mostraCarregando();
			varUrl = "php_php/php_ecrf_json.php?act=JSON_ADD";
			var formData = {
				varId: <?=$_GET['id'];?>,
				varTabela: "<?=$_GET['tabela'];?>",
				varTipo: "<?=$_GET['tipo'];?>"
			}
			$.ajax({
				url: varUrl,
				type: "POST",
				data : formData,
				async : true,
				success: function(response, textStatus, jqXHR) {
					console.log("Resposta NOVO: " + response);
					removeCarregando();
					carrega(response.trim());
				},
				error: function (jqXHR, textStatus, errorThrown) {
					removeCarregando();
					alert("Erro no processo");
				}
			});
		};
		function carrega(nro){
			mostraCarregando();
			varUrl = "php_php/php_ecrf_json.php?act=JSON_CARREGA";
			var formData = {
				varTipo  : "<?=$_GET['tipo'];?>",
				varId    : <?=$_GET['id'];?>,
				varNro   : nro,
				varTabela: "<?=$_GET['tabela'];?>"
			}
			$.ajax({
				url: varUrl,
				type: "POST",
				data : formData,
				async : true,
				success: function(response, textStatus, jqXHR) {
					console.log("Resposta: " + response);
					removeCarregando();
					document.getElementById("div_retorno").innerHTML = response;
				},
				error: function (jqXHR, textStatus, errorThrown) {
					removeCarregando();
					alert("Erro no processo");
				}
			});
		};
		function salvar(id, nro){
			$.blockUI({ timeout: 500, message: '<img src="imagens/carregando.gif" width="100">Aguarde, carregando informações...', css: {
				border: 'none',
				padding: '15px',
				backgroundColor: '#000',
				'-webkit-border-radius': '10px',
				'-moz-border-radius': '10px',
				opacity: .5,
				color: '#fff'
			} });
			var dataString = "";
			var elements = document.getElementById("div_retorno").elements;
			var elements = document.getElementsByClassName('campoNivel1');

			//var ancestor = document.getElementById('div_retorno'),
			//itens = ancestor.getElementsByTagName('*');
			var itens = document.getElementsByClassName('campoNivel1');
			for (i = 0; i < itens.length; ++i) {
				nome_campo = itens[i].name;
				if(nome_campo!=undefined){
					valor = itens[i].value;
					if(nome_campo!=""){
						if(dataString==""){
							dataString = nome_campo + "=" + itens[i].value;
						}else{
							dataString = dataString + "&" + nome_campo + "=" + itens[i].value;
						}
					}
				}
			}
			var dataJson = "";
			var itens = document.getElementsByClassName('jsonText');
			for (i = 0; i < itens.length; ++i) {
				valor = itens[i].value;
				//if(valor!=""){
					if(dataJson==""){
						dataJson = itens[i].value;
					}else{
						dataJson = dataJson + "»" +itens[i].value;
					}
				//}
			}
			varUrl = "php_php/php_ecrf_json.php?act=JSON_SAVE";
			var formData = {
				varTabela: "<?=$_GET['tabela'];?>",
				varId: <?=$_GET['id'];?>,
				varNro: nro,
				varValores: dataString,
				varJson: dataJson
			}
			$.ajax({
				url: varUrl,
				type: "POST",
				data : formData,
				async : true,
				success: function(response, textStatus, jqXHR) {
					console.log("Resposta SALVAR: " + response);
					removeCarregando();
					alert("Alterações realizadas com sucesso!");
				}
			});
		}
		function salvarMudando(id, nro){

			var dataString = "";
			var elements = document.getElementById("div_retorno").elements;
			var elements = document.getElementsByClassName('campoNivel1');

			//var ancestor = document.getElementById('div_retorno'),
			//itens = ancestor.getElementsByTagName('*');
			var itens = document.getElementsByClassName('campoNivel1');
			for (i = 0; i < itens.length; ++i) {
				nome_campo = itens[i].name;
				if(nome_campo!=undefined){
					valor = itens[i].value;
					if(nome_campo!=""){
						if(dataString==""){
							dataString = nome_campo + "=" + itens[i].value;
						}else{
							dataString = dataString + "&" + nome_campo + "=" + itens[i].value;
						}
					}
				}
			}
			var dataJson = "";
			var itens = document.getElementsByClassName('jsonText');
			for (i = 0; i < itens.length; ++i) {
				valor = itens[i].value;
				//if(valor!=""){
					if(dataJson==""){
						dataJson = itens[i].value;
					}else{
						dataJson = dataJson + "»" +itens[i].value;
					}
				//}
			}
			varUrl = "php_php/php_ecrf_json.php?act=JSON_SAVE";
			var formData = {
				varTabela: "<?=$_GET['tabela'];?>",
				varId: <?=$_GET['id'];?>,
				varNro: nro,
				varValores: dataString,
				varJson: dataJson
			}
			$.ajax({
				url: varUrl,
				type: "POST",
				data : formData,
				async : true,
				success: function(response, textStatus, jqXHR) {
					console.log("Resposta SALVAR: " + response);
				}
			});
		}
		function deletar(id, nro){
			mostraCarregando();
			if(confirm("Tem certeza que deseja deletar este registro?")){
				varUrl = "php_php/php_ecrf_json.php?act=JSON_DELETA";
				var formData = {
					varTabela: "<?=$_GET['tabela'];?>",
					varId: <?=$_GET['id'];?>,
					varNro: nro
				}

				$.ajax({
					url: varUrl,
					type: "POST",
					data : formData,
					async : true,
					success: function(response, textStatus, jqXHR) {
						removeCarregando();
						console.log("Resposta DELETAR: " + response);
						alert("Registro deletado com sucesso!");
						carrega(1);
					}
				});
			}
		}
		function carregaLinhhaJson(){
			var divJsonItems = document.getElementById('divJsonItems');
			if (!divJsonItems) {
				return;
			}

			var itensJson = divJsonItems.querySelectorAll('textarea.jsonText');
			var novoIndice = 0;
			for (var i = 0; i < itensJson.length; i++) {
				var match = (itensJson[i].id || '').match(/^json_(\d+)$/);
				if (match) {
					var n = parseInt(match[1], 10);
					if (n >= novoIndice) {
						novoIndice = n + 1;
					}
				}
			}

			var novoInputGroup = document.createElement('div');
			novoInputGroup.className = 'input-group';
			novoInputGroup.style.paddingBottom = '10px';

			var novoInput = document.createElement('textarea');
			novoInput.rows = 2;
			novoInput.className = 'form-control jsonText';
			novoInput.id = 'json_' + novoIndice;
			novoInput.value = '';

			var spanBtn = document.createElement('span');
			spanBtn.className = 'input-group-btn';
			spanBtn.style.paddingLeft = '10px';

			var btnRemover = document.createElement('button');
			btnRemover.type = 'button';
			btnRemover.className = 'btn';
			btnRemover.id = 'btnRemover_' + novoIndice;
			btnRemover.onclick = function() {
				removeLinhhaJson(this);
			};

			var iconRemover = document.createElement('i');
			iconRemover.className = 'fa fa-minus-square-o';
			iconRemover.setAttribute('aria-hidden', 'true');

			btnRemover.appendChild(iconRemover);
			spanBtn.appendChild(btnRemover);

			novoInputGroup.appendChild(novoInput);
			novoInputGroup.appendChild(spanBtn);

			divJsonItems.appendChild(novoInputGroup);
		}

		function removeLinhhaJson(btnElement){

			// Encontrar o input-group pai do botão clicado
			var inputGroup = btnElement.closest('.input-group');
			if (inputGroup) {
				inputGroup.remove();
			} else {
				console.error('Input-group não encontrado');
			}
		}
		carrega(1);
	</script>
</html>