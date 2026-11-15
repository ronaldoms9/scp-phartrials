<?php
	session_start();	
	include "../class/conexao.class.php";
	include "../class/pesq_centro.class.php";
		
	if($_GET['act']=="CADASTRA"){		
		$varCentro = new Centro(null);
		$varCentro->nome 		= $_POST['nome'];
		$varCentro->cidade 		= $_POST['cidade'];
		$varCentro->coordenador = $_POST['coordenador'];
		$varCentro->email		= $_POST['email'];
		
		$ret = $varCentro->cadastra();
		echo $ret;
	}	
	if($_GET['act']=="LISTA"){		
		$varCentro = new Centro(null);		
		echo $varCentro->listaCentros(null);
		echo $ret;
	}	
	
