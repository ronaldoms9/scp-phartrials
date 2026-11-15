<?
	include_once "seguranca.php";
	include_once "class/agenda.class.php";
    include_once "class/projeto.class.php";


	$varAgenda = new Agenda($_GET['id']);
    //$varEstudo = new Projeto($_SESSION['projeto']);

?>
<div class="row">
	<br>
	<div class="col-md-12">
		<div class="portlet light">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-paper-plane font-yellow-casablanca"></i>
					<span class="caption-subject bold font-yellow-casablanca uppercase"> Datas de confinamento desse período</span>
				</div>
			</div>
			<div class="portlet-body form">
				<form autocomplete="off" class="form-horizontal form-bordered" role="form" action="#" method="post" id="form_edita" name="form_edita">
					<input autocomplete="false" name="hidden" type="text" style="display:none;">
					<input id="form_edit_id" type="hidden" value="<?=$_GET['id']?>">
					<div class="form-body">

						<div id="modalEditaAtividades">
							<div class="form-group">
								<label class="control-label col-md-3">Data de início</label>
								<div class="col-md-9">
                                    <input value="<?=$varAgenda->conso_data_inicial;?>"
									id="form_conso_data_inicial"
									name="form_hora"
									type="text"
                                    placeholder="Data inicial do confinamento"
                                    class="form-control input-md"
                                    onkeyup="javascript:mascara(this, '##/##/####');"
                                    <?=$centralizado;?>>
								</div>
							</div>
                            <div class="form-group">
								<label class="control-label col-md-3">Data de término</label>
								<div class="col-md-9">
                                    <input value="<?=$varAgenda->conso_data_final;?>"
									id="form_conso_data_final"
									name="form_horaasdasd"
									type="text"
                                    placeholder="Data final do confinamento"
                                    class="form-control input-md"
                                    onkeyup="javascript:mascara(this, '##/##/####');"
                                    <?=$centralizado;?>>
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
        varUrl = "php_php/php_agenda.php?act=SALVA_DATA";
        var formData = {
            varId	: <?=$_GET['id'];?>,
            varInicio : document.getElementById("form_conso_data_inicial").value,
            varFinal   : document.getElementById("form_conso_data_final").value,
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