<?
	include_once "seguranca.php";	
	$campo = $_GET['id'];	
	$query = "select
				*
				from crf_agenda_campos					
					where id  = " . $campo;
	$resCom = $conexao->pesquisa($query);
	$rowCom = mysqli_fetch_assoc($resCom);
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
							<span><i class="fa fa-commenting-o fa-sm font-blue-dark" title="Formulários"></i></span>
							Comentário						
						</div>						
					</div>
					<div class="portlet-body">											
						<div class="scroller" style="height:300px" data-rail-visible="1" data-rail-color="yellow" data-handle-color="#a1b2bd">
							<textarea name="form_modal_comentarios_comentario" id="form_modal_comentarios_comentario" rows="14" width="100%" style="width:100%"><?=$rowCom['comentario'];?></textarea>
						</div>
						<div class="row" style="text-align: center">							
							<div class="clearfix">
								<button 
									type="button" 
									id="btnCarregando" 
									onclick="salvarComentario(<?=$campo;?>);" 
									data-dismiss="modal"
									class="demo-loading-btn btn btn-primary"> Salvar comentário </button>								
							</div>
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
	<script>
		
		
	</script>
</html>