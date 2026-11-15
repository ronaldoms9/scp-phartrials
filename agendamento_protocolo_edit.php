<?php
	include_once "seguranca.php";
	
	
	$agendamentoId = $_GET['agendamento'];	
	$registroId    = $_GET['registro'];
	$varFormulario = $_GET['formulario'];
	if($registroId<1){
		$registroId = 0;
	}else{
		$query 	= "select * from agenda_protocolo_pendencia where id = " . $registroId;
		$resReg = $conexao->pesquisa($query);
		$rowReg = mysqli_fetch_assoc($resReg);
	}
?>
<html>
<head>
<meta http-equiv="Content-Language" content="pt-br">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>New Page 2</title>
<meta http-equiv="Content-Language" content="pt-br">
<?php
	include "global_css.php";
?>
</head>
	<div class="page-wrapper">
		<div class="row">
			<div class="col-md-12">
				<div class="portlet light">
					<div class="portlet-title fixo">
						<div class="caption font-blue-dark">
							<span><i class="fa fa-th font-blue-dark" title="Formulários"></i></span>
							<?php
								if($_GET['id']>0){$label = "Editar Pendência";}else{$label = "Nova Pendência";}
								$acao = "php_php/php_agendamento_protocolo_edit.php?act=SALVA&registro=".$registroId;
							?>
							<span class="caption-subject"><?=$label;?></span>
						</div>						
					</div>
					<div class="portlet-body">											
						<form class="form-horizontal" role="form" action="<?=$acao;?>" method="post" id="form_info" name="form_info"> 
							<table width="100%" class="table table-striped" style="font-family: 'Abel';font-size: 22px;">
								
							   <tbody>																		
									<tr>
										<td class="tabela-body"><?=traduzir("Página", $_SESSION['lang']);?></td>
										<td style="vertical-align:middle" >
											<input value="<?=$rowReg['pagina'];?>" type="text" class="form-control" id="form_pagina" name="form_pagina" <?=$centralizado;?>>
											<input type="hidden" class="form-control" name="form_agendamentoId" id="form_agendamentoId" <?=$centralizado;?> value="<?=$agendamentoId;?>">
											<input type="hidden" class="form-control" name="form_formulario_id" id="form_formulario_id" <?=$centralizado;?> value="<?=$varFormulario;?>">
											<input type="hidden" class="form-control" id="registroId" <?=$centralizado;?> value="<?=$registroId;?>">											
										</td>									
									</tr>
									<tr>
										<td class="tabela-body"><?=traduzir("Trecho de texto", $_SESSION['lang']);?></td>
										<td style="vertical-align:middle" >
											<textarea name="form_trecho" id="form_trecho" rows="8" width="100%" style="width:100%"><?=$rowReg['trecho'];?></textarea>
										</td>									
									</tr>
									<tr>
										<td class="tabela-body"><?=traduzir("Crítico?", $_SESSION['lang']);?></td>									
										<td>
											<select id="form_critico" name="form_critico" class="form-control">
												<option value=""></option>											
												<option value="Sim" <?php if($rowReg['critico']=='Sim'){echo " selected ";}?>><?=traduzir("Sim", $_SESSION['lang']);?></option>
												<option value="Nao" <?php if($rowReg['critico']=='Nao'){echo " selected ";}?>><?=traduzir("Não", $_SESSION['lang']);?></option>												
											</select>
										</td>									
									</tr>				
									<tr>
										<td class="tabela-body"><?=traduzir("Achado / Questionamento", $_SESSION['lang']);?></td>									
										<td style="vertical-align:middle" >
											<textarea name="form_achado" id="form_achado" rows="8" width="100%" style="width:100%"><?=$rowReg['achado'];?></textarea>
										</td>									
									</tr>
									<tr>
										<td class="tabela-body"><?=traduzir("Resposta", $_SESSION['lang']);?></td>									
										<td style="vertical-align:middle" >
											<textarea name="form_resposta" id="form_resposta" rows="5" width="100%" style="width:100%"><?=$rowReg['resposta'];?></textarea>
										</td>									
									</tr>																			
									<tr>
										<td class="tabela-body">Respondido por</td>									
										<td style="vertical-align:middle" >
											<input value="<?=$rowReg['respondido_por'];?>" type="text" class="form-control" id="form_respondido_por" name="form_respondido_por" <?=$centralizado;?>>
										</td>									
									</tr>	
									<tr>
										<td class="tabela-body"><?=traduzir("Finalizado?", $_SESSION['lang']);?></td>									
										<td>
											<select id="form_finalizado" name="form_finalizado" class="form-control">
												<option value="Nao" <?php if($rowReg['finalizado']=='Nao'){echo " selected ";}?>><?=traduzir("Não", $_SESSION['lang']);?></option>																							
												<option value="Sim" <?php if($rowReg['finalizado']=='Sim'){echo " selected ";}?>><?=traduzir("Sim", $_SESSION['lang']);?></option>
												
											</select>
										</td>									
									</tr>	
									<tr>
										<td colspan="2" class="tabela-head" style="text-align:center">										
											<button type="button" class="btn blue-chambray" onclick="document.form_info.submit();"><span id="btnSalvar">Salvar informações</span></button>
											<a href="javascript:voltar();" class="btn red"> <i class="fa fa-arrow-left"></i> Voltar</a>
										</td>
									</tr>
								</tbody>
							</table>
					</div>
				</div>		
			</div>
		</div>

	</div>	
</body>
	<?php
		include "global_js.php";				
	?>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
	<script src="php_js/js_agendamento_protocolo_edit.js"></script>
<script>	
	function voltar(){
		document.location = '<?=$paginaForm."agendamento_protocolo_achados.php?agendamento=".$agendamentoId."&".gera_parametro("pac",$paciente)."&".gera_parametro("cam",$varFormulario)."&xxpt=".$xxpt;?>';
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
</script>

</html>