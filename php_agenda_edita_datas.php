<?php
	include "class/agenda.class.php";
	
	if($_GET['act']=="salvaData"){
		$varAgenda = new Agenda($_GET['id']);
		
		data_revisor		:  	document.getElementById("dataRevisor").value,
			respondeu 	 		: 	document.getElementById("relRespondeu").value,
			enviado 			: 	document.getElementById("relEnviado").value,
			data_enviado		:	document.getElementById("dataEnviado").value
		
		
		$varAgenda->rel_revisado_data 	= $_GET['data_revisor'];
		$varAgenda->rel_respondido 		= $_GET['respondeu'];
		$varAgenda->rel_enviado			= $_GET['enviado'];
		$varAgenda->rel_enviado_data	=	$_GET['data_enviado'];
		
		$varAgenda->salvaAtualizacao();
		echo "FIM";
	}