<?php
	header('Content-Type:text/html; charset=ISO-8859-1');
	header("Pragma: no-cache");
	
	include_once "../class/agenda.class.php";
	if($_GET['act']=="salvaData"){
		$varAgenda = new Agenda($_GET['agenda']);
		$varAgenda->rel_alertado_data = $_POST['data_alerta'];
		$varAgenda->rel_revisado_data = $_POST['data_revisor'];		
		$varAgenda->rel_enviado		  = $_POST['enviado'];
		$varAgenda->rel_enviado_data  = $_POST['data_enviado'];
		//echo $varAgenda->atividades;
		echo $varAgenda->salvaAtualizacao();
	} 