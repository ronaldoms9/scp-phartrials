<?php
    include_once "class/conexao.class.php";

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
                                                            <label class="col-md-3 control-label">Atividade</label>
                                                            <div class="col-md-9">
                                                                <input type="text" class="form-control input-inline input-medium" id="modal_form_atividade" name="modal_form_atividade<?=rand(1231,12412413);?>">
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
    <script>
        document.addEventListener("keydown", function(event) {
        if (event.key === "Enter" || event.keyCode === 13) {
            event.preventDefault(); // impede a ação padrão (ex: enviar formulário)
            return false; // opcional: interrompe a execução
        }
        });
    </script>
</html>