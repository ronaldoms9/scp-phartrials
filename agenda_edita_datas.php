<?php
	include_once "seguranca.php";
	include_once "class/projeto.class.php";
	$varProjeto = new Projeto($_GET['id']);
?>
<div class="row">
	<br>
	<div class="col-md-12">
		<div class="portlet light">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-paper-plane font-yellow-casablanca"></i>
					<span class="caption-subject bold font-yellow-casablanca uppercase"> Editar Datas</span>
					te
				</div>
			</div>
			<div class="portlet-body form">
				<form autocomplete="off" class="form-horizontal form-bordered" role="form" id="form_editaDatas" name="form_editaDatas">
					<input autocomplete="false" name="hidden" type="text" style="display:none;">
						<div style="display: none;">
							<div class="form-group">
								<label class="control-label col-md-3">Data de Recebimento</label>
								<div class="col-md-4">
									<input
										value="<?=$varProjeto->data_recebimento;?>"
										id="form_data_recebimento"
										type="text"
										class="form-control input-md"
										required=""
										style="text-align: center"
									>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-3">Data Revisor Alertado</label>
								<div class="col-md-4">
									<input
										value="<?=$varProjeto->data_aviso_revisor;?>"
										id="form_data_aviso_revisor"
										type="text"
										class="form-control input-md"
										required=""
										style="text-align: center"
									>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3"><?="Data Liberação do Revisor";?></label>
								<div class="col-md-4">
									<input
										value="<?=$varProjeto->data_resposta_revisor;?>"
										id="form_data_resposta_revisor"
										type="text"
										class="form-control input-md"
										required=""
										style="text-align: center"
									>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3">Data de envio ao centro</label>
								<div class="col-md-4">
									<input
										value="<?=$varProjeto->data_envio_centro;?>"
										id="form_data_envio_centro"
										type="text"
										class="form-control input-md"
										required=""
										style="text-align: center"
									>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3">Data de retorno do centro</label>
								<div class="col-md-4">
									<input
										value="<?=$varProjeto->data_retorno_centro;?>"
										id="form_data_retorno_centro"
										type="text"
										class="form-control input-md"
										required=""
										style="text-align: center"
									>
								</div>
							</div>

						</div>
						<div class="form-group">
							<label class="control-label col-md-3"><b><?="Data de finalização";?></b></label>
							<div class="col-md-4">
								<input
									value="<?=$varProjeto->data_finalizacao;?>"
									id="form_data_finalizacao"
									type="text"
									class="form-control input-md"
									required=""
									style="text-align: center"
								>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Data LIMITE</label>
							<div class="col-md-4">
								<input
									value="<?=$varProjeto->data_limite;?>"
									id="form_data_limite"
									type="text"
									class="form-control input-md"
									required=""
									style="text-align: center"
								>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Follow Up</label>
							<div class="col-md-9">
								<textarea
									id="form_followup"
									class="form-control input-md"
									rows="7"
								><?=utf8_decode($varProjeto->followup);?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3"><b><?=utf8_decode("Status");?></b></label>
							<div class="col-md-4">
								<select
									id="form_statusSelecionado"
									name="form_statusSelecionado"
									class="form-control input-md"
									style="text-align: center"
								>
									<option value="N/D">N/D</option>
									<option value="Aguardando 1 Revisor" <?php if($varProjeto->status=="Aguardando 1 Revisor"){ echo " selected ";}?>>Aguardando 1 Revisor</option>
									<option value="Aguardando 2 Revisor" <?php if($varProjeto->status=="Aguardando 2 Revisor"){ echo " selected ";}?>>Aguardando 2 Revisor</option>
									<option value="centro" <?php if($varProjeto->status=="centro"){ echo " selected ";}?>>Aguardando Revisão I - Centro</option>
									<option value="atcgen2" <?php if($varProjeto->status=="atcgen2"){ echo " selected ";}?>>Em Revisão II</option>
									<option value="centro2" <?php if($varProjeto->status=="centro2"){ echo " selected ";}?>>Aguardando Revisão II - Centro</option>
									<option value="centro3" <?php if($varProjeto->status=="centro3"){ echo " selected ";}?>>Aguardando retorno do Centro</option>
									<option value="agpatrocinador" <?php if($varProjeto->status=="agpatrocinador"){ echo " selected ";}?>>Aguardando Patrocinador</option>
									<option value="patrocinador" <?php if($varProjeto->status=="patrocinador"){ echo " selected ";}?>>Aguardando decisão Patrocinador</option>
									<option value="assinatura" <?php if($varProjeto->status=="assinatura"){ echo " selected ";}?>>Aguardando assinatura do patrocinador</option>
									<option value="cancelado" <?php if($varProjeto->status=="cancelado"){ echo " selected ";}?>>Cancelado</option>
									<option value="finalizado" <?php if($varProjeto->status=="finalizado"){ echo " selected ";}?>>Finalizado - Doc assinado - Patrocinador</option>


								</select>
							</div>
						</div>
					</div><br>
					<div class="form-actions">
						<div class="row">
							<div class="col-md-12">
								<div class="row">
									<div class="col-md-offset-5 col-md-12">
										<button type="submit" data-dismiss="modal"  class="btn green" onclick="javascript:salvaDatas(<?=$varProjeto->id;?>);">
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
	function salvaDatas(id){
		var formData = {
			data_recebimento			:	document.getElementById("form_data_recebimento").value,
			data_aviso_revisor			:  	document.getElementById("form_data_aviso_revisor").value,
			data_resposta_revisor 		: 	document.getElementById("form_data_resposta_revisor").value,
			data_envio_centro			:	document.getElementById("form_data_envio_centro").value,
			data_retorno_centro			:	document.getElementById("form_data_retorno_centro").value,
			data_finalizacao			:	document.getElementById("form_data_finalizacao").value,
			data_limite					:	document.getElementById("form_data_limite").value,
			followup					:	document.getElementById("form_followup").value,
			status						:	document.getElementById("form_statusSelecionado").value
		}
		varUrl = "php_php/php_dash_revisao.php?act=SALVA&projeto="+id;
		$.ajax({
			url: varUrl,
			type: "POST",
			data : formData,
			async : false,
			success: function(response, textStatus, jqXHR) {
				console.log(response);
				filtro();
				exibe_mensagem("Sucesso!", "Registro salvo com sucesso!", "success");
			},
			error: function (jqXHR, textStatus, errorThrown) {
				filtro();
				exibe_mensagem("ERRO!", "Erro ao salvar o registro!", "error");
			}
		});

	}
	<?=geraDate("form_data_recebimento");?>
	<?=geraDate("form_data_limite");?>
	<?=geraDate("form_data_aviso_revisor");?>
	<?=geraDate("form_data_resposta_revisor");?>
	<?=geraDate("form_data_envio_centro");?>
	<?=geraDate("form_data_retorno_centro");?>
	<?=geraDate("form_data_finalizacao");?>

</script>
</html>
