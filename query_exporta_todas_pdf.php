<?php
	$conexao->pesquisa( "seguranca.php";
	$conexao->pesquisa( "funcoes.php";

  date_default_timezone_set('America/Sao_Paulo');
  $servidor = 'localhost';
  $conexao = mysql_connect($servidor, "root", "senha123") or print(mysql_error());
  mysql_select_db("trialcare_completo_dapaconazol", $conexao) or print(mysql_error());

  $titulo = "SPC - Sistema de pesquisa clínica";

   error_reporting(E_ERROR);
   ini_set('display_errors', 1);

  function retorna_nome($entrada){
	  $nome = explode("-", $entrada);

	  if(count($nome)==2){
		return $nome[1];
	  }else{
		 return $nome[1] . " " . $nome[2] . " " . $nome[3] . " " . $nome[4];
	  }
  }
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
        <?php
			include "global_css.php";
		?>

	<script>
		function relatorio(url){
			 params  = 'width=1024';
			 params += ', height=768';
			 params += ', top=0, left=0'
			 params += ', fullscreen=yes';

			 newwin=window.open(url,'windowname4', params);
			 if (window.focus) {newwin.focus()}
			 return false;
	   }
	</script>
	<style>

        .card {
			/* Add shadows to create the "card" effect */
			box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
			transition: 0.3s;
		}

		/* On mouse-over, add a deeper shadow */
		.card:hover {
			box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
		}

	</style>
</head>
    <body class="page-container-bg-solid page-footer-fixed">
        <div class="page-wrapper">
            <?php
               include "menu_principal.php";
            ?>
            <div class="page-wrapper-row full-height">
                <div class="page-wrapper-middle">
                    <div class="page-container">
                        <div class="page-content-wrapper">
                            <div class="page-head">
                                <div class="container-fluid">
                                    <div class="page-title">
                                        <h1><?=traduzir("EXPORTAÇÃO DE QUERIES PARA PDF", $_SESSION['lang']);?> </h1>
                                    </div>
                                </div>
                            </div>

							<div class="page-content">
                                <div class="container-fluid">
                                    <!-- BEGIN PAGE BREADCRUMBS -->
                                    <ul class="page-breadcrumb breadcrumb">
                                        <li>
                                            <a href="index.html">Home</a>
                                            <i class="fa fa-circle"></i>
                                        </li>
                                        <li>
                                            <span>EXPORTAÇÃO DE QUERIES PARA PDF</span>
                                        </li>
                                    </ul>
                                    <div class="page-content-inner">

										<div class="row">
											<div class="col-md-2 col-sm-2 col-xs-2"></div>

											<div class="col-md-8 col-sm-8 col-xs-8" style="background-color:white">

												<?php

													//Apacagar todos os arquivos da pasta QUERY
													unlinkRecursive( '/home/spc/public_html/dapa/query', false );
													//set_time_limit(120);

													//$query = "select * from vw_query_completa where id = 657";
													$query = "select * from vw_query_completa";
													$res   = mysql_query($query, $conexao);

													while($row = mysql_fetch_assoc($res)){
														require_once __DIR__ . '/vendor/autoload.php';
														$mpdf = new \Mpdf\Mpdf(['tempDir' => __DIR__ . '/temp', 'debug' => true, 'mode' => 'utf-8', 'margin-top' => 1158, 'margin-header' => 1500 ]);
														$mpdf->setAutoTopMargin = 'stretch';
														$mpdf->allow_charset_conversion=true;
														$mpdf->in_charset='windows-1252';

														$file = file_get_contents('modelo_query.php');
														$file = str_replace("%QUERY_NRO%",$row['query_nro'], $file);
														$file = str_replace("%QUERY_CENTRO%",$row['query_centro'], $file);
														$file = str_replace("%QUERY_PARTICIPANTE%",$row['nro_participante'], $file);
														$file = str_replace("%QUERY_FORMULARIO_NOME%",$row['FORMULARIO_NOME'], $file);
														$file = str_replace("%QUERY_TEXTO_CAMPO%",$row['TEXTO_CAMPO'], $file);
														$file = str_replace("%QUERY_PERGUNTA%",$row['query_pergunta'], $file);
														$file = str_replace("%QUERY_QUESTIONAMENTO%",$row['responsavel_questionamento'], $file);
														$query_data = $row['query_data_hora'];
														$query_data = explode(" ", $query_data);
														$query_data = $query_data[0];
														$query_data = explode("-", $query_data);
														$query_data = $query_data[2] . "/". $query_data[1] . "/" . $query_data[0];
														$file = str_replace("%QUERY_DATA%",$query_data, $file);


														$mpdf->WriteHTML(utf8_encode($file));
														$mpdf->charset_in='UTF-8';
														$mpdf->Output('query/'.$row['query_nro'].'.pdf','F');
														unset($mpdf);
													}
													//Listar em cards o resultado
													/*unset($files);
													if ($handle = opendir('/home/spc/public_html/dapa/query')) {
														while (false !== ($entry = readdir($handle))) {
															if ($entry != "." && $entry != "..") {
																array_push($files, $entry);
																?>
																<div class="col-md-2 col-sm-2 col-xs-2">
																	<div class="card" style="width: 18rem;">
																	  <div class="card-body">
																		<h5 class="card-title"><center><?=$entry?></center></h5>
																		<center><a href="query/<?=$entry;?>" class="btn btn-primary">Download</a></center><br>
																	  </div>
																	</div>
																</div>
																<?php
															}
														}
														closedir($handle);
														var_dump($files);
														/*unlink('queries.zip');
														$zipname = 'queries.zip';
														$zip = new ZipArchive;
														//$zip->open($zipname, ZipArchive::CREATE);
														foreach ($files as $file) {
														  //$zip->addFile($file);
														}
														//$zip->close();*/

													//}

												?>


											</div>

										</div>
									</div>
								</div>
							</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="page-wrapper-row">
                <div class="page-wrapper-bottom right" align="right">
                    <div class="page-footer">

                    </div>
                    <div class="scroll-to-top">
                        <i class="icon-arrow-up"></i>
                    </div>
                </div>
            </div>
        </div>
        <?php include "global_js.php";?>
</body>
</html>
