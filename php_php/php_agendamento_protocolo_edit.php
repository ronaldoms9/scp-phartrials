<?
	session_start();
	header('Content-Type:text/html; charset=ISO-8859-1');
	include_once "../class/conexao.class.php";
	
	
	$conexao =  new Conexao();
	$aspas = "'";
	$conexao->pesquisa('SET CHARACTER SET utf8');
	$conexao->pesquisa("SET NAMES 'utf8'");
	
	function gera_parametro($variavel, $valor){
		$variavel = base64_encode($variavel);
		$valor    = $valor;
		return $variavel."=".$valor;
	}	
	if($_GET['act']=='SALVA'){
		$varRegistro 	  = $_GET['registro'];
		$varAgendamento   = $_POST['form_agendamentoId'];
		$varFormulario	  = $_POST['form_formulario_id'];
		$varPagina 		  = $_POST['form_pagina'];
		$varTrecho		  = addslashes($_POST['form_trecho']);
		$varCritico		  = $_POST['form_critico'];
		$varAchado	 	  = addslashes($_POST['form_achado']);
		$varResposta 	  = addslashes($_POST['form_resposta']);
		$varRespondidoPor = $_POST['form_respondido_por'];
		$varFinalizado 	  = $_POST['form_finalizado'];
				
		//Tiro as aspas do achado e coloco aspas simples		
		//$varAchado = str_replace('"', '*', $varAchado);
		//$varAchado = utf8_decode($varAchado);
		if($varRegistro==0){	//Registro novo
			//Pego o último ordem
			$query  = "SELECT max(ordem) as ult FROM `agenda_protocolo_pendencia` where agendamento = " . $varAgendamento;
			$resUlt =  $conexao->pesquisa($query);
			$rowUlt = mysqli_fetch_assoc($resUlt);
			$varUlt = intval($rowUlt['ult'])+1;
			
			$query = "insert into agenda_protocolo_pendencia (usuario_id, agendamento, pagina, trecho, critico, achado, resposta, respondido_por, finalizado, ordem) values
			('".$_SESSION['user_id']."', '$varAgendamento','$varPagina','$varTrecho','$varCritico','".$varAchado."','$varResposta','$varRespondidoPor','$varFinalizado', '$varUlt');";
			$res = $conexao->pesquisa($query);
			$query = "select * from agenda_protocolo_pendencia where agendamento = '$varAgendamento', pagina = '$varPagina', trecho = '$varTrecho'";
			$res   = $conexao->pesquisa($query);
			$row   = mysqli_fetch_assoc($res);
			echo $row['id'];
		}else{					//Salvar registro j?? feito
			$query = "update agenda_protocolo_pendencia set 
					pagina = '$varPagina',
					trecho = '$varTrecho',
					critico = '$varCritico',
					achado = '$varAchado',
					resposta = '$varResposta',
					respondido_por = '$varRespondidoPor',
					finalizado = '$varFinalizado',
					usuario_id = '".$_SESSION['user_id']."'
				where id = " . $varRegistro;
			$conexao->pesquisa($query);
			echo $varRegistro;			
		}	
		$volta = 'Location: ../agendamento_protocolo_achados.php?agendamento='.$varAgendamento.'&'.gera_parametro("pac",$paciente).'&'.gera_parametro("cam",$varFormulario).'&xxpt='.$xxpt;
		//echo $volta;
		header($volta);
		//echo "FIM";
	}
	if($_GET['act']=='DELETA'){
		$varRegistro 	  = $_POST['varRegistro'];		
		$query = "delete from agenda_protocolo_pendencia where id = " . $varRegistro;
		$conexao->pesquisa($query);
		echo $query;			
	}