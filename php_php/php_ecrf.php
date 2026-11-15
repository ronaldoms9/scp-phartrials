<?
	session_start();
	if($_GET['act']=="CRF_EXIBE"){
		$_SESSION['crfCentro'] = $_POST['varCentro'];
		$_SESSION['crfParticipante'] = $_POST['varPart'];
		echo 'OK';
	}
	/*if($_GET['act']=="CRF_EXIBE_CRF_FROM_QUERY"){
		$varQuery = new Query($_POST['varId']);


		$_SESSION['crfCentro'] 			= $varQuery->centro;
		$_SESSION['crfParticipante'] 	= $varQuery->getPaciente();
		$_SESSION['crfAbreForm'] 		= $varQuery->getFormulario();
		$_SESSION['crfCampo'] 			= $varQuery->campo_id;
		echo 'OK';
	}
	if($_GET['act']=="CRF_EXIBE_CRF_FROM_QUERY_FROM_CAMPO"){
		$varCampo = new Campo($_POST['varId']);
		$varForm  = new Formulario($varCampo->form_id);


		$_SESSION['crfCentro'] 			= $varForm->centro;
		$_SESSION['crfParticipante'] 	= $varForm->paciente;
		$_SESSION['crfAbreForm'] 		= $varForm->id;
		$_SESSION['crfCampo'] 			= $varCampo->id;
		echo 'OK';
	}
	if($_GET['act']=="CRF_EXIBE_CRF_FROM_FORM"){
		$varForm  = new Formulario($_POST['varId']);
		$_SESSION['crfCentro'] 			= $varForm->centro;
		$_SESSION['crfParticipante'] 	= $varForm->paciente;
		$_SESSION['crfAbreForm'] 		= $varForm->id;
		$_SESSION['crfCampo'] 			= 0;
		echo 'OK';
	}
*/

