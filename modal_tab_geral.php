<?php
	include_once "class/conexao.class.php";
	include_once "funcoes.php";
    $varConexao = new Conexao();

    if($_GET['id']==0){
        $varCentro           = "";
        $varParticipante     = "";
        $varCodigoParticipante = "";
        $varTipoDesvio       = "";
        $varComentarios      = "";
        $varData             = "";
    }else{
        $query               = "select * from agenda_desvio_geral where id = ".$_GET['id'];
        $result              = $varConexao->pesquisa($query);
        $row                 = $result->fetch_assoc();
        $id                  = $_GET['id'];
        $varCentro           = $row['centro'];
        $varParticipante     = $row['participante'];
        $varCodigoParticipante = $row['codigo_participante'];
        $varTipoDesvio       = $row['tipo_desvio'];
        $varComentarios      = $row['comentarios'];
        $varData             = $row['data'];
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
                                                                <input type="text" class="form-control input-inline input-medium" style="text-align: center;" id="modal_form_periodo" name="modal_form_periodo<?=rand(1231,12412413);?>" value="<?=$varPeriodo;?>">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-md-3 control-label">Tipo de Desvio</label>
                                                            <div class="col-md-9">
                                                                <input type="text" class="form-control input-inline input-medium" style="text-align: center;" id="modal_form_tipo_desvio" name="modal_form_tipo_desvio<?=rand(1231,12412413);?>" value="<?=$varTipoDesvio;?>">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-md-3 control-label">Comentários</label>
                                                            <div class="col-md-9">
                                                                <textarea class="form-control" id="modal_form_comentarios" rows="5" name="modal_form_comentarios<?=rand(1231,12412413);?>"><?=$varComentarios;?></textarea>
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