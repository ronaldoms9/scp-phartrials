<?
	/*error_reporting(E_ALL);
	ini_set("display_errors", 1);*/
	
	include "../class/conexao.class.php";
	include "../class/survey.class.php";
		
	if($_GET['act']=="LISTA_PESQUISA"){		 
		$varPesquisa = new Pesquisa(null);
		$varPesquisa->exibeLista();
	}
	if($_GET['act']=="LISTA_FORMULARIOS"){		
		$varPesquisa = new Pesquisa($_GET['id']);
		$varPesquisa->exibeFormularios();		
	}
	if($_GET['act']=="CAD_PESQUISA"){
		$varPesquisa = new Pesquisa(null);
		$varPesquisa->cadastra($_POST['nome']);
	}
	if($_GET['act']=="DELETA_PESQUISA"){
		$varPesquisa = new Pesquisa($_GET['id']);
		$varPesquisa->deletaPesquisa();
	}
	if($_GET['act']=="CADASTRA_FORMULARIO"){
		$varPesquisa = new Pesquisa(null);
		$varPesquisa->cadastraFormulario($_POST['nome']);
	}
	if($_GET['act']=="DELETA_FORMULARIO"){
		$varPesquisa = new Pesquisa(null);
		$varPesquisa->deletaFormulario($_GET['id']);
	}
	if($_GET['act']=="LISTA_CAMPOS"){
		$varCampo = new Campo(null);
		$varCampo->listaCampos($_GET['id']);
	}
	if($_GET['act']=="SALVA_CAMPO"){
		$varCampo = new Campo(null);
		$varCampo->formId = $_POST['formId'];
		$varCampo->ordem = $_POST['form_ordem'];
		$varCampo->tipo = $_POST['form_tipo'];
		$varCampo->nome = $_POST['form_nome'];
		$varCampo->texto = $_POST['form_texto'];
		$varCampo->texto_en = $_POST['form_texto_en'];
		$varCampo->formato = $_POST['form_formato'];
		$varCampo->legenda = $_POST['form_legenda'];
		$varCampo->obrigatorio = $_POST['form_obrigatorio'];		
		$varCampo->selecao_valores = $_POST['form_selecao_valores'];
		
		$varCampo->insereCampo();
		
	}
	