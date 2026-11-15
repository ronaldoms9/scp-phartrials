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
                 <h4 class="modal-title">Motivo da rejeição</h4>
            </div>			<!-- /modal-header -->

            <div class="modal-body">
            	<button type="button" data-dismiss="modal" class="btn dark btn-outline" onclick="rejeitaArquivo('Nomenclatura incorreta',<?=$_GET['id'];?>);">Nomenclatura incorreta</button>
				<button type="button" data-dismiss="modal" class="btn dark btn-outline" onclick="rejeitaArquivo('Conteudo incorreto',<?=$_GET['id'];?>);">Conteúdo incorreto</button>
				<button type="button" data-dismiss="modal" class="btn dark btn-outline" onclick="rejeitaArquivo('Lugar incorreto',<?=$_GET['id'];?>);">Lugar incorreto</button>
				<button type="button" data-dismiss="modal" class="btn dark btn-outline" onclick="rejeitaArquivo('Scan ilegível',<?=$_GET['id'];?>);">Scan ilegível</button>
        <button type="button" data-dismiss="modal" class="btn dark btn-outline" onclick="rejeitaArquivo('Metadado errado',<?=$_GET['id'];?>);">Metadado errado</button>
        <button type="button" data-dismiss="modal" class="btn dark btn-outline" onclick="rejeitaArquivo('Incompleto',<?=$_GET['id'];?>);">Incompleto</button>
        <button type="button" data-dismiss="modal" class="btn dark btn-outline" onclick="rejeitaArquivo('Outros',<?=$_GET['id'];?>);">Outros</button>
            </div>
			<div class="modal-footer">
            	<button type="button" data-dismiss="modal" class="btn dark btn-outline">Cancelar</button>
            </div>

</body>
</html>
