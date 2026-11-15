<?php
	
	include_once "conexao.class.php";
	class Log{	
		public $string;
		
		function escreve($pagina, $mensagem){
			$conexao = new Conexao();
			$datahora 		= date("d/m/Y h:i");
			$datahoraLis 	= date('Ymdhi');
			if(isset($_SESSION['usuario_id'])){$usuario = $_SESSION['usuario_id'];}else{$usuario = "";}			
			$query = "insert into log (
										pagina, 
										usuario, 
										datahora, 
										datahora_lis, 
										mensagem) values (
										'$pagina', 
										'$usuario',
										'$datahora',
										'$datahoraLis',
										'$mensagem');";
			$conexao->pesquisa($query);
		}
		
	}