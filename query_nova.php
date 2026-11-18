<?php
	include_once "seguranca.php";
  

  $id = $_GET[base64_encode('codigo')];
  $act = strtoupper($_GET[base64_encode('act')]);
  $paciente = $_GET[base64_encode('pac')];
  $cod = $_GET[base64_encode('cod')];
  $formularioP = $_GET[base64_encode('cam')];
  $tipo = $_GET['tipo_query'];
  if($tipo==""){$tipo="AU";} //Se não tiver explicito que é Manual, seto a query como AU - Automática
  
  $pergunta = $_GET[base64_encode('per')];

  $xxpt = $_GET['xxpt'];
 
 if($xxpt>0){
      $query = "select id, numero from cfg_centro where id = " . $xxpt;
      $res = mysql_query($query,$conexao);
      $row = mysql_fetch_assoc($res);
      $PREcentro_formatado = $row['numero'];
      $PREcentro = $row['numero'];
  } 
 if($paciente>0){
      $query = "select CR.id, CR.assinado, CR.paciente_pos_rand, CR.data_termo, CR.data_rand, CR.sexo, CR.bhcg, CR.criterio_exclusao, CR.criterio_inclusao, CR.likert from crf_paciente_pre_rand CR where CR.centro ='".$row['numero']."' and CR.nro_paciente = $paciente";
      $res = mysql_query($query, $conexao);
      $row = mysql_fetch_assoc($res);
      $PREpaciente_pos = $row['paciente_pos_rand'];
      $PREdatatermo = $row['data_termo'];
      $PREdatarand = $row['data_rand'];
      $PREsexo = $row['sexo'];
      $PREbhcg = $row['bhcg'];
      $PRElikert = $row['likert'];
      $PREcriterio_exclusao = $row['criterio_exclusao'];
      $PREcriterio_inclusao = $row['criterio_inclusao'];
  }	  
  
  if($act=="I"){
       $ult = 0;
	   //Pego o nro da query
	   if($tipo=="AU"){
	   $query = "select * from configuracoes";
	   $res_config = mysql_query($query, $conexao);
	   $row_config = mysql_fetch_assoc($res_config);
	   $nro_query = $row_config['nro_query']+1;
	   $query = "update configuracoes set nro_query = nro_query + 1";
	   mysql_query($query,$conexao);
	   }else{
		   $nro_query = $_POST['nro_query'];
	   }
	   $nro_query = $tipo . str_pad($nro_query, 4, '0', STR_PAD_LEFT);	   
	   $query = "insert into crf_entrada_campos_pergunta (responsavel_questionamento, nro_query, tipo, pergunta_id, centro, usuario_inicial, campo_id, pergunta, data_hora, log_usuario) values ('".$_SESSION['nome_usuario']."', '$nro_query', 'AU', ".$ult.",".$xxpt.", ".$_SESSION['user_id'].", $cod, '".$_POST['form_pergunta']."', now(), ".$_SESSION['user_id'].");";
       mysql_query($query,$conexao);
       //Tiro o flag de assinado. QUALQUER alteração no CRF ele 'desassina'
	   //$query = " update crf_paciente_pre_rand set assinado_por = 0, assinado = 0, assinado_data = '' where nro_paciente = $paciente and centro = '$PREcentro'";
       //mysql_query($query, $conexao);
  }
  if($act=="R"){
       $query = "insert into crf_entrada_campos_pergunta (centro, usuario_inicial, pergunta_id, campo_id, pergunta, data_hora, log_usuario) values (".$_SESSION['centro'].", ".$_SESSION['user_id'].", $pergunta, $cod, '".$_POST['form_pergunta']."', now(), ".$_SESSION['user_id'].");";
       mysql_query($query,$conexao);
       $query = "update crf_entrada_campos_pergunta set respondido = 1 where id = ".$pergunta;
       mysql_query($query,$conexao);
	   //Tiro o flag de assinado. QUALQUER alteração no CRF ele 'desassina'
	   //$query = " update crf_paciente_pre_rand set assinado_por = 0, assinado = 0, assinado_data = '' where nro_paciente = $paciente and centro = '$PREcentro'";
       //mysql_query($query, $conexao);
  }

  $voltar = "ecrf_formularios.php?".gera_parametro("pac",$paciente)."&".gera_parametro("cam",$formularioP)."&".gera_parametro("cod",$cod)."&xxpt=".$xxpt;
  $acao = "ecrf_formularios.php?".gera_parametro("pac",$paciente)."&".gera_parametro("cam",$formularioP)."&".gera_parametro("cod",$cod)."&xxpt=".$xxpt;
  $resposta = $acao;
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
        <!-- BEGIN PAGE LEVEL PLUGINS -->
		<link href="assets/global/plugins/jstree/dist/themes/default/style.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/jstree/dist/themes/default/style.min.css" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL PLUGINS -->

    <script>
    <?php
        if($msg==1){ echo "alert('Ítem atualizado com sucesso');"; }
        if($msg==2){ echo "alert('Ítem cadastrado com sucesso');"; }
        if($msg==3){ echo "alert('Ítem deletado com sucesso');"; }
    ?>
    </script>
	<script>
    function valida(){
    	//var processa = true;
    	//var pergunta = document.getElementById('form_pergunta').value;
    	//if(pergunta==""){ processa = false; alert("Pergunta em branco. Por favor, revise as informações.");}
    	
    	//if(processa==true){
           document.form_info.submit();    	
    	//}
    }
</script>
</head>
    <body class="page-container-bg-solid page-footer-fixed" onload="<?php
  if(($act=="R")||($act=="I")){
     echo "document.location = '".$acao."'";
  }
?>
">
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
                                        <h1><?=traduzir("INCLUIR QUESTIONAMENTO MANUAL", $_SESSION['lang']);?> </h1>
                                    </div>
                                </div>
                            </div>
                            <div class="page-content">
                                <div class="container-fluid">                                   
									<div class="row">
										<div class="col-md-12">										
											<?php
											   $acao = "query_nova.php?act=I";
											?>
											<form class="form-horizontal" role="form" action="<?=$acao;?>" method="post" id="form_info" name="form_info">
												<div class="form-group">
													<label class="col-md-2 control-label"># Questionamento</label>
													<div class="col-sm-2">
														<input type="text" class="form-control" id="form_nro_questionamento" name="form_nro_questionamento" <?=$centralizado;?>>
													</div>													
												</div>
												
												<div class="form-group">
													<label class="col-md-2 control-label">Centro</label>
													<div class="col-sm-2">
														<select name="form_centro" id="form_centro"class="form-control" >
															<option value=""></option>
															<?php
																$query = "select * from cfg_centro where numero <> 'CRO' order by numero";
																$res = mysql_query($query, $conexao);																		
																while($row = mysql_fetch_assoc($res)){
																	?>
																		<option value="<?=$row['numero'];?>"><?=$row['numero'] . " - " . $row['nome'];?></option>
																	<?php
																}
															?>
														</select> 
													</div>
												</div>
												
												<div class="form-group">
													<label class="col-md-2 control-label"># Participante</label>
													<div class="col-sm-2">
														<input type="text" class="form-control" id="form_nro_paciente" name="form_nro_paciente" <?=$centralizado;?>>
													</div>													
												</div>
												
												<div class="form-group">
													<label class="col-md-2 control-label">Página do CRF</label>
													<div class="col-sm-5">
														<select name="form_formulario" id="form_formulario"class="form-control" onchange="filtro(this.value);">
															<option value=""></option>
															<?php
																$query = "select * from crf_forms order by ordem";
																$res = mysql_query($query, $conexao);																		
																while($row = mysql_fetch_assoc($res)){
																	?>
																		<option value="<?=$row['id'];?>"><?=$row['ordem'] . " - " . $row['texto_crf'];?></option>
																	<?php
																}
															?>
														</select> 
													</div>													
												</div>
												
												
												<div class="form-group">
													<label class="col-md-2 control-label">Campo</label>
													<div class="col-sm-5">
														<select name="form_campo" id="form_campo"class="form-control">
															<option value="">Selecione o formulário acima...</option>															
														</select> 
													</div>													
												</div>
												
												
												<div class="form-group">
													<label class="col-md-2 control-label">Visita</label>
													<div class="col-sm-5">
														<input type="text" class="form-control" id="form_visita" name="form_visita" <?=$centralizado;?>>
													</div>													
												</div>
												
												<div class="form-group">
													<label class="col-md-2 control-label">Nome do responsável</label>
													<div class="col-sm-5">
														<input type="text" class="form-control" id="form_responsavel_nome" name="form_responsavel_nome" <?=$centralizado;?>>
													</div>													
												</div>
												<div class="form-group">
													<label class="col-md-2 control-label">Data do questionamento</label>
													<div class="col-sm-5">
														<input type="text" class="form-control" id="form_responsavel_data" name="form_responsavel_data" <?=$centralizado;?>>
													</div>													
												</div>
												
												<div class="form-group">
													<label class="col-md-2 control-label">Questionamento</label>
													<div class="col-sm-8">
														<textarea class="form-control" id="form_questionamento" name="form_questionamento" rows="5" cols="25"></textarea>
													</div>
													
												</div>
												<center><br><center><button style="height:60px" type="button" class="btn btn-default" onclick="valida();">  Incluir Questionamento</button></center></center>
											</form>

											   

									 </div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
	<!-- END WRAPPER -->
	<!-- JAVASCRIPTS -->

</body>
		<?php
			include "rodape.php";
		?>
        </div>
        <div class="quick-nav-overlay"></div>
       <?php
		 include "global_js.php";
	   ?>

        <script>
			function filtro(id){						
				$('#form_campo').empty().append('<option>Carregando campos...</option>>');
				if (window.XMLHttpRequest) {
				// code for IE7+, Firefox, Chrome, Opera, Safari
				//document.getElementById("tabela_retorno").innerHTML = "<center><img src='imagens/carregando.gif' width='180'></centeR>";
				xmlhttp = new XMLHttpRequest();
				} else {
					// code for IE6, IE5
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						//document.getElementById("tabela_retorno").innerHTML = this.responseText;
						$('#form_campo').html(this.responseText);
					}
				};
				xmlhttp.open("POST","ajax.php?act=LISTA_CAMPOS&formulario="+id);
				xmlhttp.send();						 
						 
			};

		</script>
		
</html>
