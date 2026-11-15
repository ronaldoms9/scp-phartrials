<?
	include_once "seguranca.php";
	
	$campo = $_GET['id'];
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
<?
	include "global_css.php";
?>
</head>
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
							   </thead>
							   <tbody>
							   <?
									$id = $_GET['id'];
									
									$query = "select LA.id, LA.campo, UU.nome, LA.data_hora, LA.antigo, LA.novo, LA.motivo_alteracao, cfg_visitas_campos.texto as campo_nome, LA.campo as campo_alterado 
												from log_alteracoes LA 
													inner join usu_usuario UU on LA.usuario = UU.id 
													left join cfg_visitas_campos on LA.id_campo = cfg_visitas_campos.id 
															and LA.tabela = 'agenda_protocolo_pendencia'
														where tabela = 'agenda_protocolo_pendencia' and id_tabela = " .$id;
									
									$resHist = $conexao->pesquisa($query);
									while($rowHist=mysqli_fetch_assoc($resHist)){
									?>
										<tr>
											<td><font size="2px"><?=$rowHist['nome'];?></font></td>
											<td><font size="2px">
											<?
											   $data = quebra_linha($rowHist['data_hora']," ", 1);
											   $hora = quebra_linha($rowHist['data_hora']," ", 2);
											   $data = explode("-",$data);
											   $data = $data[2]."/".$data[1]."/".$data[0];
											   echo $data ." ".$hora;
											?></font></td>
											<td><font size="2px"><b><?=$rowHist['campo_alterado'];?></b></font></td>
											<td><font size="2px"><?=$rowHist['antigo'];?></font></td>
											<td><font size="2px"><b><?=$rowHist['novo'];?></b></font></td>
										</tr>
									<?
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
	<?
		include "global_js.php";				
	?>
</html>