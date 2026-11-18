<?php
	include_once "../seguranca.php";
	
	//if($_GET['act']=="EXPORTA"){
		$query = "select nome from cfg_exportacoes_tipos where id = " . $_GET['tipoExp'];
		$resTipo = $conexao->pesquisa($query);
		$rowTipo = mysqli_fetch_assoc($resTipo);
		$tipoExp = strtoupper($rowTipo['nome']);
		
		
		$query = "insert into log_alteracoes (tabela, id_tabela, campo, id_campo, acao, usuario, data_hora, novo, usuario_nome)
			values (
				'EXPORTACAO',
				'0',
				'GERACAO DE EXPORTACAO',
				'0',
				'DOWNLOAD',
				'".$_SESSION['user_id']."',
				'".date('Y-m-d H:i:s')."',
				'Download da exportação Tipo: [".$tipoExp."]',
				'".$_SESSION['nome_usuario']."'
			);
			";
		echo $query;
		$conexao->pesquisa($query);
		
	//}