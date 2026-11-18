<?php
	include_once "class/conexao.class.php";
	include_once "funcoes.php";
    $varConexao = new Conexao();

    if($_GET['id']==0){
        $varCentro           = "";
        $varParticipante     = "";
        $varCodigoParticipante = "";
        $varHorarioColeta    = "";
        $varHorarioPrevisto  = "";
        $varHorarioRealizado = "";
        $varMotivo           = "";
        $varData             = "";
        $varGrupo            = "";
    }else{
        $query               = "select * from agenda_desvio_dieta where id = ".$_GET['id'];
        $result              = $varConexao->pesquisa($query);
        $row                 = $result->fetch_assoc();
        $id                  = $_GET['id'];
        $varCentro           = $row['centro'];
        $varParticipante     = $row['participante'];
        $varCodigoParticipante = $row['codigo_participante'];
        $varHorarioColeta    = $row['horario_coleta'];
        $varHorarioPrevisto  = $row['horario_previsto'];
        $varHorarioRealizado = $row['horario_realizado'];
        $varMotivo           = $row['motivo'];
        $varData             = $row['data'];
        $varGrupo            = $row['grupo'];
    }
?>
<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
	<?php
		include "global_css.php";
	?>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200;400&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="pagina/meddra/dist/themes/default/style.min.css" />
</head>
<?php
	include_once "class/conexao.class.php";
?>
    <body>
        <div>
            <div class="page-wrapper-row full-height">
                <div class="page-wrapper-middle">
                    <div class="page-container">
                        <div class="page-content-wrapper">
                            <div class="page-content">
									<div class="row" >
                                        <div class="portlet light ">
                                            <div class="portlet-body form">
                                                <form class="form-horizontal" role="form">
                                                    <div class="form-body">
                                                        <div class="form-group">
                                                            <label class="col-md-3 control-label">Centro</label>
                                                            <div class="col-md-6">
                                                                <select class="form-control" id="modal_form_centro">
                                                                    <option value="">Selecione</option>
                                                                    <?php
                                                                        $sql = "SELECT * FROM cfg_centro where projeto = '".$_SESSION['projeto']."'";
                                                                        $result = $varConexao->pesquisa($sql);
                                                                        while($row = $result->fetch_assoc()){
                                                                            echo "<option value='".$row['id']."' ".($varCentro==$varCentro?"selected":"").">".$row['nome']."</option>";
                                                                        }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-md-3 control-label">Data</label>
                                                            <div class="col-md-9">
                                                                <input type="text" class="form-control input-inline input-medium" style="text-align: center;" id="modal_form_data" name="modal_form_data<?=rand(1231,12412413);?>" value="<?=$varData;?>" onkeyUp="mascara(this,'##/##/####');">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="hidden" id="modal_form_id" value="<?php echo $_GET['id']; ?>">
                                                            <label class="col-md-3 control-label">Participante</label>
                                                            <div class="col-md-9">
                                                                <input type="text" class="form-control input-inline input-medium" style="text-align: center;" id="modal_form_participante" name="modal_form_participante<?=rand(1231,12412413);?>" value="<?=$varParticipante;?>">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="hidden" id="modal_form_id" value="<?php echo $_GET['id']; ?>">
                                                            <label class="col-md-3 control-label">Código do Participante</label>
                                                            <div class="col-md-9">
                                                                <input type="text" class="form-control input-inline input-medium" style="text-align: center;" id="modal_form_codigo_participante" name="modal_form_codigo_participante<?=rand(1231,12412413);?>" value="<?=$varCodigoParticipante;?>">
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="col-md-3 control-label">Grupo</label>
                                                            <div class="col-md-9">
                                                                <input type="text" class="form-control input-inline input-medium" style="text-align: center;" id="modal_form_grupo" name="modal_form_grupo<?=rand(1231,12412413);?>" value="<?=$varGrupo;?>">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-md-3 control-label">Período</label>
                                                            <div class="col-md-9">
                                                                <input type="text" class="form-control input-inline input-medium" style="text-align: center;" id="modal_form_horario_coleta" name="modal_form_horario_coleta<?=rand(1231,12412413);?>" value="<?=$varHorarioColeta;?>">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-md-3 control-label">Horário de Início</label>
                                                            <div class="col-md-9">
                                                                <input type="text" class="form-control input-inline input-medium" style="text-align: center;" id="modal_form_horario_previsto" name="modal_form_horario_previsto<?=rand(1231,12412413);?>" value="<?=$varHorarioPrevisto;?>">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-md-3 control-label">Horário de Término</label>
                                                            <div class="col-md-9">
                                                                <input type="text" class="form-control input-inline input-medium" style="text-align: center;" id="modal_form_horario_realizado" name="modal_form_horario_realizado<?=rand(1231,12412413);?>" value="<?=$varHorarioRealizado;?>">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-md-3 control-label">Qual é a dieta?</label>
                                                            <div class="col-md-9">
                                                                <input type="text" class="form-control input-inline input-medium" style="text-align: center;" id="modal_form_dieta" name="modal_form_dieta<?=rand(1231,12412413);?>" value="<?=$varDieta;?>">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-md-3 control-label">Motivo</label>
                                                            <div class="col-md-6">
                                                                <textarea class="form-control" id="modal_form_motivo" rows="5" name="modal_form_motivo<?=rand(1231,12412413);?>"><?=$varMotivo;?></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
									</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>