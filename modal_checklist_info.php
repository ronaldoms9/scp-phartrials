<?php
	include_once "class/conexao.class.php";
	include_once "funcoes.php";
    $varConexao = new Conexao();

    $varCampo = $_GET['id'];

    $query = "select id, observacoes from agenda_check_participante_campo where id = ".$varCampo;
    $result = $varConexao->pesquisa($query);
    $row = mysqli_fetch_assoc($result);
    $varObservacoes = $row['observacoes'];
?>
<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
	<?
		include "global_css.php";
	?>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200;400&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="pagina/meddra/dist/themes/default/style.min.css" />
</head>
<?
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
                                                            <div class="col-md-12">
                                                                <input type="hidden" id="modal_form_id" value="<?=$varCampo;?>">
                                                                <textarea class="form-control" id="modal_form_observacoes" rows="10" name="modal_form_observacoes<?=rand(1231,12412413);?>"><?=$varObservacoes;?></textarea>
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