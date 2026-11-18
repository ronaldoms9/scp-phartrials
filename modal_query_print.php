<?php
	include_once "seguranca.php";	
	
	$centro 	= $_GET['centro'];
	$paciente 	= $_GET['paciente'];
	$tipo		= $_GET['tipo'];
?>
<html>	
	<body>
	<div class="modal-dialog">
		<div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                 <h4 class="modal-title">Geração dos PDFs das queries...</h4>
            </div>
            <div class="modal-body">
				<div class="te" style="overflow-y: scroll; height:150px !important">											
					<div name="ret_geracao_query" id="ret_geracao_query">
					
					</div>									
				</div>						
			</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
	</div>
	</body>
	<script>
		if (window.XMLHttpRequest) {
			document.getElementById("ret_geracao_query").innerHTML = "<center><img src='imagens/carregando.gif' width='180'></centeR>";
			xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					document.getElementById("ret_geracao_query").innerHTML = this.responseText;
					//window.location.hash = "fim";
				}
			};
			
		xmlhttp.open("POST","ajax.php?act=QUERY_GERA&tipo=<?=$tipo;?>&centro=<?=$centro;?>&paciente=<?=$paciente;?>");
		xmlhttp.send();	
	</script>
</html>