<?
	include_once "seguranca.php";	
?>
<html>
	<head>
		<link href="assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	</head>
	<script>
		
	</script>
	<body>

		<div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                 <h4 class="modal-title">Novo questionamento</h4>

            </div>
            <div class="modal-body">
				<!--<div class="te" style="overflow-y: scroll; height:350px !important">						-->
					<?
					   $acao = "ecrf_formularios_query.php?".gera_parametro("pac",$paciente)."&".gera_parametro("cam",$formularioP)."&".gera_parametro("act","II")."&".gera_parametro("cod",$cod)."&xxpt=".$xxpt;
					?>
					<form class="form-horizontal" role="form" action="<?=$acao;?>" method="post" id="form_info_item" name="form_info_item">
						<textarea class="form-control" id="form_pergunta_item" name="form_pergunta_item" rows="13" ></textarea>
					</form>					
				<!--</div>					-->
			</div>
            <div class="modal-footer" style="text-align:right">
            
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
				<button type="button" class="btn green" data-dismiss="modal" onclick="javascript:salvar_novo_item(<?=$_GET['id'];?>);">Salvar questionamento</button>
			</div>
        </div>
	</body>
<script>
	
	
	
</script>
</html>