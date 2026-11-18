<?php
	include_once "seguranca.php";
	
	$campo = $_GET['id'];
	
	$query = "select
				crf_entrada_campos.id, 
				crf_entrada_forms.paciente, crf_entrada_forms.centro
				from crf_entrada_campos
					inner join crf_entrada_forms on crf_entrada_campos.form_id = crf_entrada_forms.id
					where crf_entrada_campos.id = " . $campo;
	//echo $query;
	$resPac = $conexao->pesquisa($query);
	$rowPac = mysqli_fetch_assoc($resPac);
	
	$iniPaciente = $rowPac['paciente'];
	$iniCentro   = $rowPac['centro'];

	
	$varPaciente = busca_valor($iniPaciente, $iniCentro, "DC_IDENT_ESTUDO");
	$varCpf 	 = busca_valor($iniPaciente, $iniCentro, "DC_CPF");

?>
<html>
<head>
<meta http-equiv="Content-Language" content="pt-br">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>New Page 2</title>
<meta http-equiv="Content-Language" content="pt-br">
	<style>
		.datepicker.dropdown-menu {z-index:10000;}
		.datepicker {
			z-index: 99999 !important;
		}
	</style>
<?php
	include "global_css.php";
?>
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
	<div class="page-wrapper">	
		<div class="row">
			<div class="col-md-12">
				<div class="portlet light">
					<div class="portlet-title fixo">
						<div class="caption font-blue-dark">
							<span><i class="fa fa-history font-blue-dark" title="Formulários"></i></span>
							Histórico de modificações							
						</div>						
					</div>
					<div class="portlet-body">											
						<div class="scroller" style="height:350px" data-rail-visible="1" data-rail-color="yellow" data-handle-color="#a1b2bd">
							<table width="100%" class="table table-striped">
							   <thead>
									  <th>Usuário</th>
									  <th>Data/Hora</th>
									  <th>Campo alterado</th>												  
									  <th>Valor antigo</th>
									  <th>Valor novo</th>
									  <th>Motivo da alteração</th>
							   </thead>
							   <tbody>
							   <?php
									$id = $_GET['id'];
									
									$query = "select LA.id, LA.campo, UU.nome, LA.data_hora, LA.antigo, LA.novo, LA.motivo_alteracao, cfg_visitas_campos.texto as campo_nome, LA.campo as campo_alterado 
												from log_alteracoes LA 
													inner join usu_usuario UU on LA.usuario = UU.id 
													left join cfg_visitas_campos on LA.id_campo = cfg_visitas_campos.id 
															and LA.tabela = 'crf_agenda_campos'
														where tabela = 'crf_agenda_campos' and id_tabela = " .$id;
									
									$resHist = $conexao->pesquisa($query);
									while($rowHist=mysqli_fetch_assoc($resHist)){
									?>
										<tr>
											<td><font size="2px"><?=$rowHist['nome'];?></font></td>
											<td><font size="2px">
											<?php
											   $data = quebra_linha($rowHist['data_hora']," ", 1);
											   $hora = quebra_linha($rowHist['data_hora']," ", 2);
											   $data = explode("-",$data);
											   $data = $data[2]."/".$data[1]."/".$data[0];
											   echo $data ." ".$hora;
											?></font></td>
											<td><font size="2px"><b><?=$rowHist['campo_alterado'];?></b></font></td>
											<td><font size="2px"><?=$rowHist['antigo'];?></font></td>
											<td><font size="2px"><b><?=$rowHist['novo'];?></b></font></td>
											<td><font size="2px"><b><?=$rowHist['motivo_alteracao'];?></b></font></td>
										</tr>
									<?php
									}
							   ?>
							   <tr><td colspan="5"></center></tr>
							   </tbody>
							</table>
						</div>
						<div class="row">
							<center>
								<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
						</div>
						
					</div>
				</div>		
			</div>
		</div>

	</div>	
</body>
	<?php
		include "global_js.php";				
	?>
</html>