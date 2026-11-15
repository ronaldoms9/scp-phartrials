<?
	include "seguranca.php";

	if($_GET['opc']=="E"){
		$id = $_GET['id'];
		$query = "select * from agenda_lista_pendencia where id = " . $id;
		$res   = $conexao->pesquisa($query);
		$row   = mysqli_fetch_assoc($res);
	}
?>
<html>

<head>
<meta http-equiv="Content-Language" content="pt-br">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>New Page 2</title>
<meta http-equiv="Content-Language" content="pt-br">


<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<?
	include "global_css.php";
?>
	<style>

		.lista_formularios{
			font-size: 13px !important;
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
	</style>
</head>
<script >
		<?=$msg;?>
		function mascara(t, mask){
				 var i = t.value.length;
				 var saida = mask.substring(1,0);
				 var mascara = mask.substring(i)
				 if(event.keyCode!=8){
					 if(t.value.substr(i-1,1)!=mask.substr(i,1)){
						 if (mascara.substring(0,1) != saida){

							t.value += mascara.substring(0,1);
						 }
					 }
				 }
		 }
</script>
<body >
<br>
<div align="center">
<?
	if($id>0){
		$acao = "agendamentos_lista_pendencia.php?act=A&id=".$id;
	}else{
		$acao = "agendamentos_lista_pendencia.php?act=I";
	}
?>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet light">
				<div class="portlet-title fixo">
					<div class="caption font-blue-dark">
						<span><i class="fa fa-th font-blue-dark" title="Formulários"></i></span>
						<span class="caption-subject">Pendência</span>
					</div>
				</div>
				<div class="portlet-body">
					<form class="form-horizontal" role="form" action="<?=$acao;?>" method="post" id="form_info" name="form_info">
						<table width="100%" class="table table-striped" style="font-family: 'Abel';font-size: 22px;">
							<thead>
								<th class="tabela-head" width="30%"><?=$nro;?></th>
								<th class="tabela-head" ><center></center></th>
							</thead>
						   <tbody>
						   		<tr>
									<td class="tabela-body">Data de abertura</td>
									<td style="vertical-align:middle" >
										<input type="text" class="form-control" id="form_data_abertura" name="form_data_abertura" <?=$centralizado;?> onkeyup="mascara(this, '##/##/####');" value="<?=$row['data_abertura'];?>">
									</td>
								</tr>
								<tr>
									<td class="tabela-body">Centro</td>
									<td style="vertical-align:middle" >
										<select id="form_centro" name="form_centro" class="form-control">
											<option value=""></option>
											<?
											$query = "select * from cfg_centro where projeto = ".$_SESSION['projeto']." and numero <> 'CRO'";
											$res_c = $conexao->pesquisa($query);
											while($row_c = mysqli_fetch_assoc($res_c) ){
												echo "<option value='".$row_c['nome']."' ";
												if($row['centro']==$row_c['nome']){ echo " selected ";}
												echo ">".$row_c['nome']."</option>";
											}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td class="tabela-body"># Número da Visita</td>
									<td style="vertical-align:middle" >
										<input type="text" class="form-control" id="form_visita" name="form_visita" <?=$centralizado;?> value="<?=$row['visita'];?>">
									</td>
								</tr>
								<tr>
									<td class="tabela-body">Data da Visita</td>
									<td style="vertical-align:middle" >
										<input type="text" class="form-control" id="form_visita_data" name="form_visita_data" <?=$centralizado;?> value="<?=$row['visita_data'];?>" onkeyup="mascara(this, '##/##/####');">
									</td>
								</tr>
								<tr>
									<td class="tabela-body"># Participante<br><i>(Se aplicável)</i></td>
									<td style="vertical-align:middle" >
										<input type="text" class="form-control" id="form_paciente" name="form_paciente" <?=$centralizado;?> value="<?=$row['paciente'];?>">
									</td>
								</tr>
								<tr>
									<td class="tabela-body">Monitora</td>
									<td style="vertical-align:middle" >
										<select id="form_monitora" name="form_monitora" class="form-control">
											<option value=""></option>
											<?
												//$query = "select * from usu_usuario where tipo_usuario = 2 order by nome";
												$query = "select * from usu_usuario where ativo = 'on' order by nome";
												$resMon = $conexao->pesquisa($query);
												while($rowMon = mysqli_fetch_assoc($resMon)){
													echo '<option value="'.$rowMon['id'].'"';
													if($row['monitora']==$rowMon['id']){echo " selected ";}
													echo '>'.$rowMon['nome'].'</option>';
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td class="tabela-body">Tipo</td>
									<td style="vertical-align:middle" >
										<select id="form_tipo" name="form_tipo" class="form-control">
											<option value=""></option>
												<option value="ACHADO" <?if($row['tipo']=="ACHADO"){echo " selected ";}?>>ACHADO</option>
												<option value="DESVIO" <?if($row['tipo']=="DESVIO"){echo " selected ";}?>>DESVIO DE PROTOCOLO</option>
												<option value="DESVIO DE GCP" <?if($row['tipo']=="DESVIO DE GCP"){echo " selected ";}?>>DESVIO DE GCP</option>
												<option value="NÃO CONFORMIDADE" <?if($row['tipo']=="NÃO CONFORMIDADE"){echo " selected ";}?>>NÃO CONFORMIDADE</option>
										</select>
									</td>
								</tr>
								<tr>
									<td class="tabela-body">Categoria</td>
									<td>
										<select id="form_categoria" name="form_categoria" class="form-control">
											<option value=""></option>
											<option value="TCLE"  <?if($row['categoria']=="TCLE"){echo " selected ";}?>>TCLE</option>
											<option value="DATA"  <?if($row['categoria']=="DATA"){echo " selected ";}?> >Erros de data</option>
											<option value="EM BRANCO"  <?if($row['categoria']=="EM BRANCO"){echo " selected ";}?> >Campo em branco</option>
											<option value="ERRO DE PREENCHIMENTO"  <?if($row['categoria']=="ERRO DE PREENCHIMENTO"){echo " selected ";}?> >Erro de preenchimento</option>
											<option value="FALTA DE DOCUMENTO"  <?if($row['categoria']=="FALTA DE DOCUMENTO"){echo " selected ";}?> >Falta de Documento</option>
											<option value="FALTA DE DOCUMENTO FONTE"  <?if($row['categoria']=="FALTA DE DOCUMENTO FONTE"){echo " selected ";}?> >Falta de Documento Fonte</option>
											<option value="OUTROS"  <?if($row['categoria']=="OUTROS"){echo " selected ";}?> >OUTROS</option>
										</select>
									</td>
								</tr>

								<tr>
									<td class="tabela-body">Criticidade</td>
									<td style="vertical-align:middle" >
										<select id="form_criticidade" name="form_criticidade" class="form-control">
											<option value=""></option>
											<option value="NAO" <?if($row['criticidade']=="NAO"){echo " selected ";}?>>Não crítico</option>
											<option value="CRITICO" <?if($row['criticidade']=="CRITICO"){echo " selected ";}?>>Crítico</option>
										</select>
									</td>
								</tr>

								<tr>
									<td class="tabela-body">Achado</td>
									<td style="vertical-align:middle" >
										<textarea name="form_achado" id="form_achado" rows="5" width="100%" style="width:100%"><?=$row['achado'];?></textarea>
									</td>
								</tr>
								<tr>
									<td class="tabela-body">Responsável</td>
									<td style="vertical-align:middle" >
										<select id="form_responsavel" name="form_responsavel" class="form-control">
												<option value=""></option>
												<option value="LABORATÓRIO EXAMES" <?if($row['responsavel']=="LABORATÓRIO EXAMES"){echo " selected ";}?>>LABORATÓRIO EXAMES</option>
												<option value="CENTRO" <?if($row['responsavel']=="CENTRO"){echo " selected ";}?>>CENTRO</option>
												<option value="MONITORA" <?if($row['responsavel']=="MONITORA"){echo " selected ";}?>>MONITORA</option>
												<option value="PATROCINADOR" <?if($row['responsavel']=="PATROCINADOR"){echo " selected ";}?>>PATROCINADOR</option>
												<option value="LABORATORIO" <?if($row['responsavel']=="LABORATORIO"){echo " selected ";}?>>Laboratório</option>
												<option value="EDC" <?if($row['responsavel']=="EDC"){echo " selected ";}?>>EDC (Sistema)</option>

											</select>
									</td>
								</tr>
								<tr>
									<td class="tabela-body">Ação tomada</td>
									<td style="vertical-align:middle" >
										<textarea name="form_acao" id="form_acao" rows="4" width="100%" style="width:100%"><?=$row['acao'];?></textarea>
									</td>
								</tr>
								<tr>
									<td class="tabela-body">Status</td>
									<td style="vertical-align:middle" >
										<select id="form_status" name="form_status" class="form-control">
											<option value=""></option>
											<option value="PENDENTE" <?if($row['status']=="PENDENTE"){echo " selected ";}?>>PENDENTE</option>
											<option value="RESOLVIDO" <?if($row['status']=="RESOLVIDO"){echo " selected ";}?>>RESOLVIDO</option>
										</select>
									</td>
								</tr>
								<tr>
									<td class="tabela-body">Data de desfecho</td>
									<td style="vertical-align:middle" >
										<input type="text" class="form-control" id="form_data_fechamento" name="form_data_fechamento" <?=$centralizado;?> onkeyup="mascara(this, '##/##/####');" value="<?=$row['data_fechamento'];?>">
									</td>
								</tr>
								<tr>
									<td class="tabela-body">Data da VISITA de desfecho</td>
									<td style="vertical-align:middle" >
										<input type="text" class="form-control" id="form_data_visita_fechamento" name="form_data_visita_fechamento" <?=$centralizado;?> onkeyup="mascara(this, '##/##/####');" value="<?=$row['data_visita_fechamento'];?>">
									</td>
								</tr>
								<tr>
									<td colspan="2" class="tabela-head" style="text-align:center">
										<button type="button" class="btn green" onclick="validar();">Salvar informações</button>
										<button type="button" class="btn " onclick="javascript:document.location='agendamentos_lista_pendencia.php';">Cancelar / Voltar</button>
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
<script>
	function validar(){
		//Validar campos obrigatórios
		/*var centro = document.getElementById("form_centro").value;
		var data_inicio = document.getElementById("form_data_abertura").value;
		var achado = document.getElementById("form_achado").value;
		var status = document.getElementById("form_status").value;
		var monitora = document.getElementById("form_monitora").value;
		var tipo = document.getElementById("form_tipo").value;

		if(centro==""){alert("ATENÇÃO! Campo CENTRO não pode estar em branco");return;}
		if(data_inicio==""){alert("ATENÇÃO! Campo DATA DE INÍCIO não pode estar em branco");return;}
		if(achado==""){alert("ATENÇÃO! Campo ACHADO não pode estar em branco");return;}
		if(status==""){alert("ATENÇÃO! Campo STATUS não pode estar em branco");return;}
		if(monitora==""){alert("ATENÇÃO! Campo MONITORA não pode estar em branco");return;}
		if(tipo==""){alert("ATENÇÃO! Campo TIPO não pode estar em branco");return;}
		*/

		document.getElementById("form_info").submit();

	}
</script>

</html>