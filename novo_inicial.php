<?
	include_once "seguranca.php";
	include_once "class/centro.class.php";
?>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
	<script src="js/pace.js"></script>
	<link href="css/pace.css" rel="stylesheet" />
	<?
		include "global_css.php";
	?>
	<link href="assets/global/plugins/morris/morris.css" rel="stylesheet" type="text/css" />
	<link href="assets/global/plugins/jqvmap/jqvmap/jqvmap.css" rel="stylesheet" type="text/css" />
	<link rel="shortcut icon" href="favicon.ico" />
	<link href="assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet" type="text/css" />
	<link href="assets/global/plugins/morris/morris.css" rel="stylesheet" type="text/css" />
	<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
	<style>
		.conteudo{			font-size: 10px;		}
		.cabecalhoPendencia{			font-size: 11px;		}
		.tabelaTH{			font-size: 10px;		}
		.tabelaTD{			font-size: 9px;		}

		#dash_grafico1 {
			width: 100%;
			height: 285px;
		}
		#dash_grafico2 {
			width: 100%;
			height: 285px;
		}
	</style>
</head>
    <body class="page-container-bg-solid page-header-menu-fixed page-footer-fixed">
        <div class="page-wrapper">
            <?
               include "menu_principal.php";
            ?>
            <div class="page-wrapper-row full-height">
                <div class="page-wrapper-middle">
                    <div class="page-container">
                        <div class="page-content-wrapper">
							<div class="page-content">
								<?php

								// Verificar se tem alguma query aberta para o centro
								//Se tiver, mostrar um alerta
								//Verificar se o usuário é um da equipe do centro.
								if( ($_SESSION['tipo_usuario']==3)||( ($_SESSION['tipo_usuario']== 22) )){

									$varConexao = new Conexao();
									$query = "SELECT
												count(crf_entrada_campos_query.id) as qtd
												FROM crf_entrada_campos_query
													inner join crf_entrada_campos on crf_entrada_campos_query.campo_id = crf_entrada_campos.id
													inner join crf_entrada_forms on crf_entrada_campos.form_id = crf_entrada_forms.id
													where crf_entrada_campos_query.status <> 3
														and crf_entrada_forms.centro = " . $_SESSION['centro'];
									$result = $varConexao->pesquisa($query);
									$row = mysqli_fetch_array($result);
									if($row['qtd']>0){
										?>
											<div class="alert alert-danger" role="alert">
											ATENÇÃO! O seu centro possui Queries abertas. Clique <a href="inicial_queries_abertas.php"><b>*AQUI*</b></a> para visualizar a lista de queries abertas!
											</div>
										<?php
									}
								}

								?>
									<div class="page-content">
										<div class="container-fluid">
											<div class="row">
												<iframe
													title="Report Section"
													width="100%"
													height="150"
													src="dashboard/dashboard.php"
													frameborder="0"
													allowFullScreen="true"></iframe>
											</div>
											<div class="row"></div>
												<iframe
													title="Report Section"
													width="100%"
													height="600"
													src="dashboard/recrutamento.php"
													frameborder="0"
													id="iframeDashboard"
													allowFullScreen="true"></iframe>
											</div>

										</div>

									</div>


							</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <?
			include "global_js.php";
			include "rodape.php";
		?>

		<script src="assets/global/plugins/moment.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/morris/morris.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/morris/raphael-min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/counterup/jquery.waypoints.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/counterup/jquery.counterup.min.js" type="text/javascript"></script>
		<script src="assets/pages/scripts/dashboard.min.js" type="text/javascript"></script>
		<script src="assets/pages/scripts/ui-confirmations.min.js" type="text/javascript"></script>	<script src="assets/global/plugins/bootstrap-multiselect/js/bootstrap-multiselect.js" type="text/javascript"></script>
		<script src="assets/pages/scripts/components-bootstrap-multiselect.min.js" type="text/javascript"></script>


		<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
		<!--<script src="https://cdn.amcharts.com/lib/5/percent.js"></script>-->
		<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

		<script type="text/javascript">
			$(document).ready(function() {
				$('#filtroPacientes').multiselect({
					enableFiltering: true,
					includeSelectAllOption: true,
					maxHeight: 300,
					width: 200,
					maxWidth:200,
					dropUp: true,
					resizable: true
				});
			});
		</script>
		<script>
			function muda_pagina(entrada){
				$.blockUI({ timeout: 500, message: '<img src="imagens/carregando.gif" width="100">Aguarde, carregando informações...', css: {
					border: 'none',
					padding: '15px',
					backgroundColor: '#000',
					'-webkit-border-radius': '10px',
					'-moz-border-radius': '10px',
					opacity: .5,
					color: '#fff'
				} });
				document.location = entrada;
			}
		</script>
</body>
		<script>
			function ExportaEXCEL(tabela){
				var BOM = "\uFEFF";
				var tab_text="<table border='2px'><tr bgcolor='#87AFC6'>";
				var textRange; var j=0;
				tab = document.getElementById(tabela); // id of table
				for(j = 0 ; j < tab.rows.length ; j++)
				{
					tab_text=tab_text+tab.rows[j].innerHTML+"</tr>";
					//tab_text=tab_text+"</tr>";
				}
				tab_text=tab_text+"</table>";
				tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
				tab_text= tab_text.replace(/<img[^>]*>/gi,""); // remove if u want images in your table
				tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params
				var ua = window.navigator.userAgent;
				var msie = ua.indexOf("MSIE ");
				if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
				{
					txtArea1.document.open("txt/html","replace");
					txtArea1.document.write(tab_text);
					txtArea1.document.close();
					txtArea1.focus();
					sa=txtArea1.document.execCommand("SaveAs",true,"Negocios em andamento.xls");
				}
				else                 //other browser not tested on IE 11
					sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(BOM + tab_text));
				return (sa);
			}
		</script>

<script>





</script>
	<?

	if(($_SESSION['tipo_usuario']==1)||($_SESSION['tipo_usuario']==8)){
		include "dash_grafico1.php";
	}
?>

</html>
