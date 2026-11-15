<?
	error_reporting(E_ALL);
	ini_set("display_errors", 1);
	
	include "../class/conexao.class.php";
	include "../class/treinamento.class.php";
	
	if($_GET['act']=="LISTA"){		
		$varTreinamento = new Treinamento(null);
		$varTreinamento->exibeLista();
	}
	if($_GET['act']=="LISTA_TELAS"){		
		$varTreinamento = new Treinamento($_GET['id']);
		$varTreinamento->exibeTelas();		
	}