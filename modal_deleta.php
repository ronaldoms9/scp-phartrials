<?php
	include_once "seguranca.php";
?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
</head>
<body>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                 <h4 class="modal-title">Motivo para deletar o arquivo</h4>
            </div>			<!-- /modal-header -->

            <div class="modal-body">
            	<button type="button" data-dismiss="modal" class="btn dark btn-outline" onclick="deletaArquivo('Nomenclatura incorreta',<?=$_GET['id'];?>);">Nomenclatura incorreta</button>
				<button type="button" data-dismiss="modal" class="btn dark btn-outline" onclick="deletaArquivo('Conteudo incorreto',<?=$_GET['id'];?>);">Conteúdo incorreto</button>
				<button type="button" data-dismiss="modal" class="btn dark btn-outline" onclick="deletaArquivo('Lugar incorreto',<?=$_GET['id'];?>);">Lugar incorreto</button>
				<button type="button" data-dismiss="modal" class="btn dark btn-outline" onclick="deletaArquivo('Scan ilegível',<?=$_GET['id'];?>);">Scan ilegível</button>
        <button type="button" data-dismiss="modal" class="btn dark btn-outline" onclick="deletaArquivo('Metadado errado',<?=$_GET['id'];?>);">Metadado errado</button>
        <button type="button" data-dismiss="modal" class="btn dark btn-outline" onclick="deletaArquivo('Outros',<?=$_GET['id'];?>);">Outros</button>
            </div>
			<div class="modal-footer">
            	<button type="button" data-dismiss="modal" class="btn dark btn-outline">Cancelar</button>
            </div>

</body>
</html>
