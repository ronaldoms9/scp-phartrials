<?php
	include_once "seguranca.php";
	include_once "class/agenda.class.php";

	$query = "select * from agenda where id = " . $_GET['id'];
	$res   = $conexao->pesquisa($query);
	$agenda   = mysqli_fetch_assoc($res);
	$varAgenda = new Agenda($_GET['id']);
?>
<div class="row">
	<br>
	<div class="col-md-12">
		<div class="portlet light">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-paper-plane font-yellow-casablanca"></i>
					<span class="caption-subject bold font-yellow-casablanca uppercase"> Editar visita</span>
				</div>
			</div>
			<div class="portlet-body form">
				<form autocomplete="off" class="form-horizontal form-bordered" role="form" action="#" method="post" id="form_edita" name="form_edita">
					<input autocomplete="false" name="hidden" type="text" style="display:none;">
					<input id="form_edit_id" type="hidden" value="<?=$_GET['id']?>">
					<div class="form-body">
						<div class="form-group">
							<label class="control-label col-md-3">Centro</label>
							<div class="col-md-9">
								<select id="form_edita_centro" name="form_centro" class="form-control" onchange="traz_prox_visita(this.value);">
								  <option value=""></option>
								  <?php
									  $query = "select id, nome from cfg_centro where numero <> 'CRO' and projeto = ".$_SESSION['projeto']." order by nome";
									  $res = $conexao->pesquisa($query);
									  while($row = mysqli_fetch_assoc($res) ){
										  echo "<option value='".$row['id']."' ";
										  if($varAgenda->centro->id==$row['id']){ echo " selected ";}
										  echo " >".$row['nome']."</option>";
									  }
								  ?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Versão do relatório</label>
							<div class="col-md-4">
								<input
									value="<?=$varAgenda->versao;?>"
									id="form_edita_versao"
									name="form_versao"
									type="text"
									class="form-control input-md"
									required=""
									<?=$centralizado;?>>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-3">Grupo</label>
							<div class="col-md-9">
								<input
									value="<?=$varAgenda->grupo_periodo;?>"
									id="form_edita_grupo_periodo"
									name="form_grupo_periodo"
									type="text"
									class="form-control input-md"
									required="" <?=$centralizado;?>>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Período</label>
							<div class="col-md-9">
								<select id="form_edita_periodo" name="form_periodo" class="form-control input-md" required="">
									<option value=""></option>
									<option value="Inicial" <?php if($varAgenda->periodo=="Inicial"){echo " selected ";};?> >Inicial</option>
									<option value="Regular" <?php if($varAgenda->periodo=="Regular"){echo " selected ";};?> >Regular</option>
									<option value="1" <?php if($varAgenda->periodo=="1"){echo " selected ";};?> >1</option>
									<option value="2" <?php if($varAgenda->periodo=="2"){echo " selected ";};?> >2</option>
									<option value="3" <?php if($varAgenda->periodo=="3"){echo " selected ";};?> >3</option>
									<option value="4" <?php if($varAgenda->periodo=="4"){echo " selected ";};?> >4</option>
									<option value="5" <?php if($varAgenda->periodo=="5"){echo " selected ";};?> >5</option>
									<option value="6" <?php if($varAgenda->periodo=="6"){echo " selected ";};?> >6</option>
									<option value="Final"  <?php if($varAgenda->periodo=="Final"){echo " selected ";};?> >Final</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Data Inicial</label>
							<div class="col-md-6">
								<input value="<?=listoDATA($varAgenda->data);?>"
								id="form_edita_data" name="form_data" type="text" placeholder="" class="form-control input-md" required="" <?=$centralizado;?> onkeyup="mascara(this, '##/##/####');" autocomplete="off">
							</div>
						</div>
						<div style="display: none;">
							<div class="form-group">
								<label class="control-label col-md-3">Data Final</label>
								<div class="col-md-6">
									<input value="<?=listoDATA($varAgenda->datafinal);?>"
									id="form_edita_datafinal" name="form_datafinal" type="text" placeholder="" class="form-control input-md" required="" <?=$centralizado;?> onkeyup="mascara(this, '##/##/####');" autocomplete="off">
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Data de impressão</label>
							<div class="col-md-6">
								<input value="<?=listoDATA($varAgenda->data_impressao);?>"
								id="form_edita_data_impressao" name="form_data_impressao" type="text" placeholder="" class="form-control input-md" required="" <?=$centralizado;?> onkeyup="mascara(this, '##/##/####');" autocomplete="off">
							</div>
						</div>

						<div class="form-group" style="display: none;">
							<label class="control-label col-md-3">Horário previsto</label>
							<div class="col-md-4">
								<input value="<?=$varAgenda->hora;?>"
									id="form_edita_hora"
									name="form_hora"
									type="text" placeholder="" class="form-control input-md" required="" <?=$centralizado;?>>
								<span class="help-block"> Ex: 08:00 às 12:00 </span>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Monitora</label>
							<div class="col-md-8">
								<select id="form_edita_monitor" name="form_monitor" class="form-control">
									<option value=""></option>
									<?php
										//$query = "select id, nome from usu_usuario where tipo_usuario = 2 order by nome";
										$query = "select id, nome from usu_usuario order by nome";
										$res_mon = $conexao->pesquisa($query);
										while($row_mon = mysqli_fetch_assoc($res_mon)){
											echo '<option value="'.$row_mon['id'].'"';
											if($varAgenda->monitor->id==$row_mon['id']){ echo " selected ";}
											echo ' >'.$row_mon['nome'].'</option>';
										}
									?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Contato do monitor</label>
							<div class="col-md-9">
								<input value="<?=$varAgenda->contato;?>"
									id="form_edita_contato"
									name="form_contato"
									type="text" placeholder="" class="form-control input-md" required="">
								<span class="help-block"> Ex: (62) 8258-9783 e monitoria@phartrials.com.br </span>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Revisor</label>
							<div class="col-md-8">
								<select id="form_edita_revisor" name="form_revisor" class="form-control">
									<option value=""></option>
									<?php
										//$query = "select id, nome from usu_usuario where tipo_usuario = 2 order by nome";
										$query = "select id, nome from usu_usuario order by nome";
										$res_mon = $conexao->pesquisa($query);
										while($row_mon = mysqli_fetch_assoc($res_mon)){
											echo '<option value="'.$row_mon['id'].'"';
											if($varAgenda->revisor->id==$row_mon['id']){ echo " selected ";}
											echo ' >'.$row_mon['nome'].'</option>';
										}
									?>
								</select>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3">Presentes / Participantes</label>
								<div class="col-md-9">
									<?php $presentes  = str_replace("<br />", "|", str_replace("\n", '', $varAgenda->presentes));?>
									<textarea
										name="form_presentes"
										id="form_edita_presentes"
										class="form-control"
										rows="4"><?=str_replace('<br />','',$varAgenda->presentes);?></textarea>
								</div>
							</div>
						</div>
						<div id="modalEditaAtividades" style="display: none;">
							<div class="form-group">
								<label class="control-label col-md-3">Título no relatório</label>
								<div class="col-md-9">
									<?php $titulo = str_replace("<br />", "|", str_replace("\n", '', $varAgenda->titulo)); ?>
									<textarea name="form_edita_titulo" id="form_edita_titulo" class="form-control" rows="6"><?=str_replace('<br />','',$varAgenda->titulo);?></textarea>
								</div>
							</div>
							<div id="divAtvAgenda" style="display: none;">
								<div class="form-group">
									<label class="control-label col-md-3">Atividades</label>
									<div class="col-md-9">
										<?php $atividades = str_replace("<br />", "|", str_replace("\n", '', $varAgenda->atividades)); ?>
										<textarea 	name="form_edita_atividades"
													id="form_edita_atividades"
													class="form-control"
													rows="4"><?=str_replace('<br />','',$varAgenda->atividades);?></textarea>
									</div>
								</div>
							</div>

						</div>
					</div>
					<div class="form-actions">
						<div class="row">
							<div class="col-md-12">
								<div class="row">
									<div class="col-md-offset-3 col-md-9">
										<button type="BUTTON" class="btn green" onclick="javascript:agendaEdita();">
											&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-check"></i> Salvar&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
</body>
<script>
	$( document ).ready(function() {
    	tipoAgenda(<?=$varAgenda->tipo;?>);
	});

	function tipoAgenda(tipo){
		//Mostro ou oculto de acordo com o tipo
		document.getElementById("modalEditaAtividades").style.display = 'none';
		document.getElementById("divModalEditRelatorio").style.display = 'none';

		if(tipo=="Relatorio"){
			document.getElementById("divModalEditRelatorio").style.display = 'block';
		}else{
			document.getElementById("divModalEditRelatorio").style.display = 'none';
			document.getElementById("divAtividades").style.display = 'block';
		}
	}

</script>