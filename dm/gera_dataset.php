<?php
session_start();
include_once "../class/conexao.class.php";
include_once "funcoes.php";
include_once "../class/participante.class.php";
include_once "../class/centro.class.php";
include_once "../class/crf.class.php";
include_once "../class/sql.php";

$varConexao = new Conexao();
$conexao = new Conexao();

if(is_null($_GET['projeto'])){
	echo "Projeto n�o selecionado";
	return;
}
$projeto = $_GET['projeto'];


$varFormEA = 29;

//$varFormularios = array(1);
$varFormularios = "1";


function simplifica($entrada){
	if(substr($entrada,2,1)=="-"){
		return substr($entrada,0,1);
	}else{ return $entrada;}
}


  function retornaNomeTipo($entrada){
	$temp = $entrada;
	if($temp=="divisao_mais"){		$temp  = "Grupo de campos (+)";	}
	if($temp=="caixa"){		$temp = "Caixa de texto";	}
	return $temp;
  }

  function buscaBinaria(array $lista, int $item) {
    $baixo = 0;
    $alto = count($lista) - 1;
    while ($baixo <= $alto) {
        $meio = ceil(($baixo + $alto) / 2);
        $chute = $lista[$meio]['campo'];
        if ($chute == $item) {            return $lista[$meio]['valor'];        }
        if ($chute > $item) {            $alto = $meio - 1;        } else {            $baixo = $meio + 1;
        }
    }
		return null;
	}
	function escreve($entrada){
		echo date("d/m/Y H:i") . " -> " . $entrada . "<br>";
}



	function buscaValor($formulario, $campo_id, $qtd){
		$varConexao = new Conexao();
		$query = "select * from crf_entrada_campos where form_id = $formulario and campo_id = $campo_id order by id";
		$res   = $varConexao->pesquisa($query);
		$i = 1;
		while($row = mysqli_fetch_assoc($res)){
			if($i==$qtd){
				return $row['valor'];
			}
			$i++;
		}
	}


	error_reporting(E_ALL);
	ini_set('display_errors', 1);

	escreve("51-> Deletando arquivos .csv");
	foreach (glob("*.csv") as $filename) {
		unlink($filename);
	}

	$varValores = array();
	error_reporting(E_ALL);
	ini_set('display_errors', 1);

	escreve("Começando a rotina....");
	escreve("Gerando DataSets individuais....");



	//foreach($varFormularios as $form){
	//$query = "select * from crf_forms where projeto = $projeto and id not in ($varFormularios)";
	$query = "select * from crf_forms where projeto = $projeto ";
	echo $query;
	$resFormularios = $varConexao->pesquisa($query);
	while($rowFormularios = mysqli_fetch_assoc($resFormularios)){
		$form = $rowFormularios['id'];
		echo "FOI";
		//PEgo o nome que tem que ser o arquivo
		$query 		= "select id, arquivo from crf_forms where id = " . $form;
		$resForm 	= $varConexao->pesquisa($query);
		$rowForm	= mysqli_fetch_assoc($resForm);

		escreve("Gerando DataSet do Formulário $form....");
		//Pegar nome do formulario para gerar o nome do dataset
		$varListaCampos = array();
		//flq02ib_ae_2023_0315.xlsx
		$arquivo = fopen(" flq02ib_".$rowForm['arquivo']."_".date("Y")."_".date("md").".csv", "w");
		$horaInicial = date("H:i:s");

		$header = "Centro|Participante|#";
		$query 		= "select crf_forms_campos.id,
								crf_forms_campos.nome,
								crf_forms_campos.variavel,
								crf_forms_campos.formulario
							from crf_forms_campos
								where formulario = $form
									and tipo not in ('botao', 'divisao', 'divisao_mais', 'fim_agrupamento', 'escondido', 'inicio_agrupamento', 'pagina', 'subpagina')
										order by ordem";
		$resCampos 	= $varConexao->pesquisa($query);
		while($rowCampos = mysqli_fetch_assoc($resCampos)){
			$header .= "|".utf8_encode($rowCampos['variavel']);
			$tempArr = array("id"=>$rowCampos['id'],"formulario"=>$rowCampos['formulario']);
			array_push($varListaCampos, $tempArr);
		}
		fwrite($arquivo, $header."\n");
		$header = "Centro|Participante|#";
		$query 		= "select crf_forms_campos.id,
								crf_forms_campos.nome,
								crf_forms_campos.texto
							from crf_forms_campos
								where formulario = $form
									and tipo not in ('botao', 'divisao', 'divisao_mais', 'fim_agrupamento', 'escondido', 'inicio_agrupamento', 'pagina', 'subpagina')
										order by ordem";
		$resCampos 	= $varConexao->pesquisa($query);
		while($rowCampos = mysqli_fetch_assoc($resCampos)){
			$header .= "|".utf8_encode($rowCampos['texto']);
		}
		fwrite($arquivo, $header."\n");
		$header = "Centro|Participante|#";
		$query 		= "select crf_forms_campos.id,
								crf_forms_campos.nome,
								crf_forms_campos.tipo
							from crf_forms_campos
								where formulario = $form
									and tipo not in ('botao', 'divisao', 'divisao_mais', 'fim_agrupamento', 'escondido', 'inicio_agrupamento', 'pagina', 'subpagina')
										order by ordem";
		$resCampos 	= $varConexao->pesquisa($query);
		while($rowCampos = mysqli_fetch_assoc($resCampos)){
			$header .= "|".utf8_encode($rowCampos['tipo']);
		}
		fwrite($arquivo, $header."\n");

		//Loop nos participantes
		$query = "SELECT 	CF.id,
							CF.paciente,
							CF.centro,
							CF.ordem
							FROM crf_entrada_forms CF
						where CF.form_id = $form
							order by CF.centro, CF.paciente, CF.ordem
							";
		$indEA 	 = 1;
		$partAnt = "";
		$resPart  = $conexao->pesquisa($query);
		while($rowPart = mysqli_fetch_assoc($resPart)){
			$varValores = array();
			//Pegar tudo que foi digitado para esse participante
			$query = "select
						crf_entrada_forms.form_id,
						crf_entrada_forms.ordem,
						crf_entrada_campos.campo_id,
						crf_entrada_campos.valor
						from crf_entrada_forms
							inner join crf_entrada_campos on crf_entrada_campos.form_id = crf_entrada_forms.id and crf_entrada_campos.valor <> ''
							where crf_entrada_forms.centro = ".$rowPart['centro']."
								and crf_entrada_forms.paciente = " . $rowPart['paciente']."
								and crf_entrada_forms.ordem = " . $rowPart['ordem'] . "
								and crf_entrada_forms.form_id = ".$form."
								order by crf_entrada_forms.form_id, crf_entrada_forms.ordem, crf_entrada_campos.campo_id ";
			$resValores = $varConexao->pesquisa($query);
			while($rowValores = mysqli_fetch_assoc($resValores)){
				array_push($varValores, array("formulario"=>$rowValores['form_id'], "ordem"=>$rowValores['ordem'], "campo"=>$rowValores['campo_id'],"valor"=>$rowValores['valor']));
			}
			// *******************************************

			$varForm = new NOVO_Formulario();
			$varForm->setaId($rowPart['id']);
			$indEA = $rowPart['ordem'];
			if($indEA==0){$indEA =1;}

			$varParticipante = new Participante($rowPart['centro'], $rowPart['paciente'], $projeto);
			$varCentro = new CentroPesquisa($rowPart['centro']);

			$linha = $varCentro->nome."|".$varParticipante->retornaNroFormatado()."|".$indEA;
			for($indCampo=0;$indCampo<count($varListaCampos);$indCampo++){
				$valor = buscaBinaria($varValores,$varListaCampos[$indCampo]['id']);
				if($varListaCampos[$indCampo]['id']==254){
					$varInicioEA = $valor;
				}
				$valor = trim(preg_replace('/\s\s+/', ' ', $valor));
				$valor = str_replace("<br />", "", $valor);
				$valor = str_replace("<br />", "", $valor);
				$valor = str_replace("<br>", "", $valor);
				$valor = str_replace(PHP_EOL, '', $valor);

				$linha .= "|".utf8_encode($valor);
			}
			fwrite($arquivo, $linha."\n");
		}
	}

/*
	escreve("Gera o TCLE....");
	$arquivo = fopen("tcle_".date("Y")."_".date("md").".csv", "w");

	$header = "Centro|Participante|TRI_TCLE_TERMO_SN|TRI_TCLE_TERMO_N_MOTIVO|#|TRI_TCLE_TERMO_VERSAO|TRI_TCLE_TERMO_DATA|TRI_TCLE_TERMO_HORA|TRI_TCLE_TERMO_COMENTARIOS";
	fwrite($arquivo, $header."\n");
	$header = "Centro|Participante|O Termo de Consentimento Livre e Esclarecido (TCLE) foi corretamente assinado?|Caso não tenha sida corretamente assinado, qual foi o motivo?|#|Versão do TCLE|Data da assinatura do TCLE|Hora da assinatura do TCLE|Comentários";
	fwrite($arquivo, $header."\n");
	$header = "Centro|Participante|select|texto|#|texto|data|hora|texto";
	fwrite($arquivo, $header."\n");



	//Loop em todos os participantes
	$query = "SELECT 	CF.id,
						CF.paciente,
						CF.centro,
						CF.ordem
						FROM crf_entrada_forms CF
					where CF.form_id = 1
						order by CF.centro, CF.paciente, CF.ordem
						";
	$indEA 	 = 1;
	$partAnt = "";
	$resPart  = $conexao->pesquisa($query);
	while($rowPart = mysqli_fetch_assoc($resPart)){
		$varParticipante = new Participante($rowPart['centro'], $rowPart['paciente'], $projeto);
		$varCentro = new CentroPesquisa($rowPart['centro']);

		$query = "";

		//Chegar no formulário de TCLE do participante
		$query = "select
						*
							from crf_entrada_forms
						where form_id = 1
							and paciente = " . $varParticipante->nro_paciente . "
							and centro = " . $varParticipante->centro;
		$resTCLE = $varConexao->pesquisa($query);
		$rowTCLE = mysqli_fetch_assoc($resTCLE);

		$valor = $varParticipante->buscaValor("TRI_TCLE_TERMO_COMENTARIOS");
		$valor = trim(preg_replace('/\s\s+/', ' ', $valor));
		$valor = str_replace("<br />", "", $valor);
		$valor = str_replace("<br />", "", $valor);
		$valor = str_replace("<br>", "", $valor);
		$valor = str_replace(PHP_EOL, '', $valor);
		$motivo = $valor;


		//Pegar a qtd de TCLEs
		$query = "select
					*
						from crf_entrada_campos
							where form_id = ".$rowTCLE['id']."
							and campo_id = 4";
		$resQtd 	= $varConexao->pesquisa($query);
		$qtdTCLE 	= mysqli_num_rows($resQtd);

		for($iQtd=1;$iQtd<=$qtdTCLE;$iQtd++){
			$varTCLEVersao = buscaValor($rowTCLE['id'],4,$iQtd);
			$varTCLEData   = buscaValor($rowTCLE['id'],5,$iQtd);
			$varTCLEHora   = buscaValor($rowTCLE['id'],6,$iQtd);
			$linha = $varCentro->nome."|".$varParticipante->retornaNroFormatado()."|".$varParticipante->buscaValor("TRI_TCLE_TERMO_SN")."|".$varParticipante->buscaValor("TRI_TCLE_TERMO_N_MOTIVO")."|$iQtd|$varTCLEVersao|$varTCLEData|$varTCLEHora|".$motivo."";
			fwrite($arquivo, $linha."\n");
		}
	}

	fclose($arquivo);


	?>

</tbody>
</table>
</div>

*/
