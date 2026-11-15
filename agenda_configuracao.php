<?
	include_once "seguranca.php";
	include_once "class/agenda.class.php";
    include_once "class/projeto.class.php";


	//$varAgenda = new Agenda($_GET['id']);
    $varEstudo = new Projeto($_SESSION['projeto']);

?>
<div class="row">
	<br>
	<div class="col-md-12">
		<div class="portlet light">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-paper-plane font-yellow-casablanca"></i>
					<span class="caption-subject bold font-yellow-casablanca uppercase"> Configurações</span>
				</div>
			</div>
			<div class="portlet-body form">
				<form autocomplete="off" class="form-horizontal form-bordered" role="form" action="#" method="post" id="form_edita" name="form_edita">
					<input autocomplete="false" name="hidden" type="text" style="display:none;">
					<input id="form_edit_id" type="hidden" value="<?=$_GET['id']?>">
					<div class="form-body">

						<div id="modalEditaAtividades">
							<div class="form-group">
								<label class="control-label col-md-3">Resumo</label>
								<div class="col-md-9">
									<textarea name="form_conso_resumo" id="form_conso_resumo" class="form-control" rows="10"><?=str_replace("<br>","&#13;&#10;",$varEstudo->conso_resumo);?></textarea>
								</div>
							</div>
                            <div class="form-group">
								<label class="control-label col-md-3">Desvios e não conformidade</label>
								<div class="col-md-9">
									<textarea name="form_conso_desvio" id="form_conso_desvio" class="form-control" rows="10"><?=str_replace("<br>","&#13;&#10;",$varEstudo->conso_desvio);?></textarea>
								</div>
							</div>
                            <div class="form-group">
								<label class="control-label col-md-3">Notificações</label>
								<div class="col-md-9">
									<textarea name="form_conso_notificacao" id="form_conso_notificacao" class="form-control" rows="10"><?=str_replace("<br>","&#13;&#10;",$varEstudo->conso_notificacao);?></textarea>
								</div>
							</div>
                            <div class="form-group">
								<label class="control-label col-md-3">Conclusão</label>
								<div class="col-md-9">
									<textarea name="form_conso_conclusao" id="form_conso_conclusao" class="form-control" rows="10"><?=str_replace("<br>","&#13;&#10;",$varEstudo->conso_conclusao);?></textarea>
								</div>
							</div>
						</div>

					</div>
					<div class="form-actions">
						<div class="row">
							<div class="col-md-12">
								<div class="row">
									<div class="col-md-offset-3 col-md-9">
										<button type="BUTTON" class="btn green" onclick="javascript:salvaCfg();">
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

   function salvaCfg(){
        mostraCarregando();
        varUrl = "php_php/php_agenda.php?act=SALVA_CFG";
        var formData = {
            varId	: <?=$_GET['id'];?>,
            varConsoResumo : document.getElementById("form_conso_resumo").value,
            varDesvio       :document.getElementById("form_conso_desvio").value,
            varNotificacao  :document.getElementById("form_conso_notificacao").value,
            varConclusao    :document.getElementById("form_conso_conclusao").value
        }
        $.ajax({
            url: varUrl,
            type: "POST",
            data : formData,
            async : false,
            success: function(response, textStatus, jqXHR) {
                console.log(response);
                removeCarregando();
                document.location = document.location;
            },
            error: function (jqXHR, textStatus, errorThrown) {
                removeCarregando();
                alert("Erro no processo");
            }
        });
   }

</script>