<?
	header('Content-Type:text/html; charset=ISO-8859-1');
	header("Pragma: no-cache");
	session_start();
	if($_SESSION['valido']<1){echo "<script>window.location.replace('expirado.php');</script>";};


	require_once __DIR__ . '/vendor/autoload.php';
	include_once "class/agenda.class.php";
	include_once "class/projeto.class.php";
	include_once "class/conexao.class.php";

	error_reporting(~E_ALL);
	ini_set('display_errors', 0);

	$varAgenda = new Agenda($_GET['id']);
	$varProjeto = new Projeto($varAgenda->projeto);

	function str_primeira_letra_maiuscula($entrada){
		$temp_entrada = $entrada;
		$entrada = strtolower($entrada);
		$entrada = explode(" ", $entrada);
		for($i=0;$i<count($entrada);$i++){
			if(substr($entrada[$i],0,1)!="("){
				$entrada[$i] = strtoupper(substr($entrada[$i],0,1)) . substr($entrada[$i],1,strlen($entrada[$i]));
			}else{
				$entrada[$i] = strtoupper(substr($entrada[$i],0,2)) . substr($entrada[$i],2,strlen($entrada[$i]));
			}
		}
		$saida = "";
		for($i=0;$i<count($entrada);$i++){
			$saida .= $entrada[$i] . " ";
		}
		//return trim($saida);

		return $temp_entrada;
	}
	function tirarAcentos($string){
		return preg_replace(array("/(á|à|ã|â|ä)/","/(Ý|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Ý|Ì|Î|Ý)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a A e E i I o O u U n N"),$string);
	}
	function traduzir($texto_entrada){
		$varConexao = new Conexao();
		$lang = $_SESSION['lang'];
		$texto = $texto_entrada;
		$texto_entrada = utf8_decode($texto_entrada);
		$query_tr = "select * from traducao where portugues = '$texto_entrada';";
		$res_tr = $varConexao->pesquisa($query_tr);
		if(mysqli_num_rows($res_tr)==0){
				$query = "insert into traducao (portugues, ingles, espanhol) values ('$texto_entrada', '', '')";
				$varConexao->pesquisa($query);
		}else{
			$row_tr = mysqli_fetch_assoc($res_tr);
			if($lang == "i"){$texto = $row_tr['ingles'];}
			if($lang == "e"){$texto = $row_tr['espanhol'];}
		}
		return $texto;
	}
	function formatar_texto_bd($entrada){
		return utf8_encode($entrada);
	}

	$conexao = new Conexao();
	$stylesheet = "
		.paragrafo1{
			font-size:10pt;
			font-weight: bold;

		}
		.linha_json{
			text-align:center;
			border: 1px solid #000000;
			padding-left: 4px;
			padding-right: 4px;
			padding-top: 6px;
			padding-bottom: 3px;
			white-space:nowrap
		}
		.linha_json_legenda{
			text-align:center;
			font-size:5.0pt;
			font-style: italic;
		}
		.coluna_valor{
			text-align:center;
			font-size:6.0pt;
		}
		.texto_paragrafo{
			font-size: 10pt;
			font-weight: normal;
			line-height: 1.5;
			text-align: justify;
		}
		.coluna_cinza{
			border: 1.0pt solid black;
			padding-left: 4.5pt;
			padding-right: 3.5pt;
			padding-top: 6px;
			padding-bottom: 6px;
			background: #D9D9D9
		}
		.coluna_cinza_label{
			font-size: 10pt;
			font-weight: bold;
		}
		.coluna_branca{
			border: 1.0pt solid black;
			padding-left: 4.5pt;
			padding-right: 3.5pt;
			padding-top: 6px;
			padding-bottom: 6px;
			background: white;
			text-align: justify;
		}
		.coluna_branca_label{
			font-size: 10pt;
			font-weight: normal;
			line-height: 1.6;
			text-align: justify;
		}
		.head_table_questionamento{
			font-size: 9pt;
			font-weight: bold;
			background: #D9D9D9;
			text-align: center;
			padding-left: 3.5pt;
			padding-right: 3.5pt;
			padding-top: 3.5pt;
			padding-bottom: 3.5pt;
		}
		.body_table_questionamento{
			font-size: 10pt;
			font-weight: normal;
			text-align: center;
			padding-left: 3.5pt;
			padding-right: 3.5pt;
			padding-top: 3.5pt;
			padding-bottom: 3.5pt;
		}
		.cabecalho{
			font-size: 11pt;
			font-weight: bold;
			text-align: justify;
			padding-left: 3.5pt;
			padding-top: 2.5pt;
			padding-bottom: 2.5pt;
		}

	";

	$mpdf = new \Mpdf\Mpdf(['tempDir' => __DIR__ . '/temp', 'debug' => true, 'mode' => 'utf-8', 'margin-top' => 1158, 'margin-header' => 1500 ]);
	$mpdf->setAutoTopMargin = 'stretch';
	$mpdf->setAutoBottomMargin = 'stretch';
	$mpdf->allow_charset_conversion=true;
	$mpdf->WriteHTML($stylesheet,1);


	$cabecalho = '
		<table>
			<tr>
				<td>
					<img src="modelos/protocolo/header.png" style="margin-left: -20px;  margin-right: -70px;">
				</td>
			</tr>
		</table>

		<table border="0" width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<td width="65%" class="cabecalho">'.traduzir("Relatório").'</td>
				<td style="border-left-style: solid; border-left-width: 1px; border-right-width: 1px; border-top-width: 1px; border-bottom-width: 1px" class="cabecalho">'.traduzir("Revisão de Protocolo").'</td>
			</tr>
			<tr>
				<td style="border-left-width: 1px; border-right-width: 1px; border-top-style: solid; border-top-width: 1px; border-bottom-width: 1px" class="cabecalho">'.traduzir("Relatório de Pendências de Revisão de Protocolo").'</td>
				<td style="border-left-style: solid; border-left-width: 1px; border-right-width: 1px; border-top-style: solid; border-top-width: 1px; border-bottom-width: 1px" class="cabecalho">'.traduzir("Versão").' '.number_format($varAgenda->versao,1).'</td>
			</tr>
		</table>


		<br><br>
	';

	$rodape = '
		<table width="100%">
		  <tr>
			<td>
				<img src="modelos/protocolo/footer.png" style="margin-left: -20px;  margin-right: -70px;">
			</td>
			<td style="text-align: right">
				<font size="8px"><span style="font-size: 12px">{PAGENO}/{nbpg}</span></font>
			</td>
		 </tr>
		</table>
	';

	$mpdf->SetHTMLFooter($rodape);
	$mpdf->SetHTMLHeader($cabecalho);





	$html = "<p class='MsoNormal' align='left' style='margin-bottom:0cm;margin-bottom:.0001pt; text-align:left;line-height:normal'>
				<font face='Tahoma'><b>
					<span class='texto_formulario_nro'>1. </span>
					<span class='texto_formulario'>
						".traduzir("OBJETIVO")."
					</span>
				</b></font>
			</p>";
	$mpdf->WriteHTML($html);

	$html = "<p class='texto_paragrafo'>
				<span class='texto_paragrafo'>
					".traduzir("Este relatório resume os achados durante o processo de revisão do Protocolo de Estudo e abre espaço para o autor do documento efetuar correções, esclarecimentos ou justificativas sobre esses achados.")."
				</span></p>
			<p class='texto_paragrafo'>
				<span class='texto_paragrafo'>
					".traduzir("A revisão se baseou em conhecimento limitado do projeto, pois, foi recebido apenas o Protocolo de Estudo. Portanto, é possível que já tenha havido discussão sobre alguns apontamentos.")."
				</span></p>
			<p class='texto_paragrafo'>
				<span class='texto_paragrafo'>
					".traduzir("A revisão se assenta primordialmente em uma visão técnico-científica.")."
				</span>
			</p>";
	$mpdf->WriteHTML($html);

	$html = '<div align="center">
				   <table class="MsoNormalTable" border="1" cellspacing="0" cellpadding="0" width="100%" style="width: 100.0%; border-collapse: collapse; border: medium none">
						<tr>
							<td width="230" class="coluna_cinza" valign="top">
							    <p class="MsoNormal" align="left" style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:left;line-height:normal">
								  <span class="coluna_cinza_label">'.traduzir("Código do documento/Estudo").'</span>
								</p>
							</td>
							<td class="coluna_branca">
								<span class="coluna_branca_label">'.$varProjeto->codigo.'</span>
							</td>
						</tr>
						<tr>
							<td width="230" class="coluna_cinza" valign="top">
							    <p class="MsoNormal" align="left" style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:left;line-height:normal">
								  <span class="coluna_cinza_label">'.traduzir("Título do Estudo").'</span>
								</p>
							</td>
							<td class="coluna_branca">
								<span class="coluna_branca_label">'.formatar_texto_bd($varProjeto->descricao).'</span>
							</td>
						</tr>
						<tr>
							<td width="230" class="coluna_cinza" valign="top">
							    <p class="MsoNormal" align="left" style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:left;line-height:normal">
								  <span class="coluna_cinza_label">'.traduzir("Formulação/Produto").'</span>
								</p>
							</td>
							<td class="coluna_branca">
								<span class="coluna_branca_label">'.formatar_texto_bd($varProjeto->formulacao).'</span>
							</td>
						</tr>
						<tr>
							<td width="230" class="coluna_cinza" valign="top">
							    <p class="MsoNormal" align="left" style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:left;line-height:normal">
								  <span class="coluna_cinza_label">'.traduzir("Patrocinador").'</span>
								</p>
							</td>
							<td class="coluna_branca">
								<span class="coluna_branca_label">'.formatar_texto_bd($varProjeto->patrocinador->nome).'</span>
							</td>
						</tr>
						<tr>
							<td width="230" class="coluna_cinza" valign="top">
							    <p class="MsoNormal" align="left" style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:left;line-height:normal">
								  <span class="coluna_cinza_label">'.traduzir("País de registro").'</span>
								</p>
							</td>
							<td class="coluna_branca">
								<span class="coluna_branca_label">'.formatar_texto_bd($varProjeto->pais ).'</span>
							</td>
						</tr>
						<tr>
							<td width="230" class="coluna_cinza" valign="top">
							    <p class="MsoNormal" align="left" style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:left;line-height:normal">
								  <span class="coluna_cinza_label">'.traduzir("Centro Clínico").'</span>
								</p>
							</td>
							<td class="coluna_branca">
								<span class="coluna_branca_label">'.formatar_texto_bd($varProjeto->centro).'</span>
							</td>
						</tr>
						<tr>
							<td width="230" class="coluna_cinza" valign="top">
							    <p class="MsoNormal" align="left" style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:left;line-height:normal">
								  <span class="coluna_cinza_label">'.traduzir("Centro Analítico").'</span>
								</p>
							</td>
							<td class="coluna_branca">
								<span class="coluna_branca_label">'.formatar_texto_bd($varProjeto->centro_analitico).'</span>
							</td>
						</tr>
						<tr>
							<td width="230" class="coluna_cinza" valign="top">
							    <p class="MsoNormal" align="left" style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:left;line-height:normal">
								  <span class="coluna_cinza_label">'.traduzir("Centro Estatístico").'</span>
								</p>
							</td>
							<td class="coluna_branca">
								<span class="coluna_branca_label">'.formatar_texto_bd($varProjeto->centro_estatistico).'</span>
							</td>
						</tr>
						<tr>
							<td width="230" class="coluna_cinza" valign="top">
							    <p class="MsoNormal" align="left" style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:left;line-height:normal">
								  <span class="coluna_cinza_label">'.traduzir("Versão desse documento").'</span>
								</p>
							</td>
							<td class="coluna_branca">
								<span class="coluna_branca_label">'.formatar_texto_bd($varAgenda->nro_visita).'</span>
							</td>
						</tr>
						<tr>
							<td width="230" class="coluna_cinza" valign="top">
							    <p class="MsoNormal" align="left" style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:left;line-height:normal">
								  <span class="coluna_cinza_label">'.traduzir("Data desse documento").'</span>
								</p>
							</td>
							<td class="coluna_branca">
								<span class="coluna_branca_label">'.$_GET['data'].'</span>
							</td>
						</tr>

					</table>
				</div>
	';
	$mpdf->WriteHTML($html);
	$html = "<p class='texto_paragrafo'>
				<span class='texto_paragrafo'>
					".traduzir("Declaramos que o Protocolo de Estudo foi revisado de acordo com os Procedimentos da PHARTRIALS, à luz da legislação vigente no Brasil a seguindo as principais diretrizes de escrita médica (Declaração SPIRIT) e Boas Práticas Clínicas.")."
				</span></p>";
	$mpdf->WriteHTML($html);
	$mpdf->AddPage();


	$html = "<p class='MsoNormal' align='left' style='margin-bottom:0cm;margin-bottom:.0001pt; text-align:left;line-height:normal'>
				<font face='Tahoma'><b>
					<span class='texto_formulario_nro'>2.</span><span class='texto_formulario'> ".traduzir("PROCESSO DE REVISÃO DE DOCUMENTOS")."</span>
				</b></font>
			</p>";
	$mpdf->WriteHTML($html);

	$html = "<p class='texto_paragrafo'>
				<span class='texto_paragrafo'>
					".traduzir("O processo de revisão dos documentos baseou-se na análise dos dados por três linhas principais:")."
				</span></p>";
	$html .= "
			<table width='100%'>
				<tr>
					<td width='60' valign='top' class='coluna_branca_label'>i)</td>
					<td  class='coluna_branca_label'><span class='coluna_branca_label'>".traduzir("Estrutura do documento: informações básicas, seções, coerência das seções, objetivos vs desfechos, etc, dirigidos por um checklist e através da leitura").".</span></td>
				</tr>
				<tr>
					<td width='60' valign='top' class='coluna_branca_label'>ii)</td>
					<td  class='coluna_branca_label'><span class='coluna_branca_label'>".traduzir("Coerência da informação e aderência aos guias: principais guias").".</span></td>
				</tr>
				<tr>
					<td width='60' valign='top' class='coluna_branca_label'>iii)</td>
					<td  class='coluna_branca_label'><span class='coluna_branca_label'>".traduzir("Coerência técnica: desenho do estudo, tamanho amostral, objetivos, desfechos, exames laboratoriais, Formulário de Relato de Caso (CRF), planejamento do estudo, critérios de aceitação de estudos analíticos, metodologia estatística, etc...")."</span></td>
				</tr>
			</table>
	";
	$mpdf->WriteHTML($html);


	$html = "<br><p class='MsoNormal' align='left' style='margin-bottom:0cm;margin-bottom:.0001pt; text-align:left;line-height:normal'>
				<font face='Tahoma'><b>
					<span class='texto_formulario_nro'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2.1. </span><span class='texto_formulario'> ".traduzir("TIPO DE DOCUMENTO/TÝTULO").":</span>
				</b></font>
			</p>";
	$mpdf->WriteHTML($html);
	$html = '<p class="texto_paragrafo">
				<span class="texto_paragrafo"><u>'.traduzir("PROTOCOLO DE ESTUDO:").'</u>&nbsp;
					'.formatar_texto_bd($varProjeto->descricao).'.
				</span></p>';
	$mpdf->WriteHTML($html);
	$mpdf->AddPage('L');
	$html = "<br><p class='MsoNormal' align='left' style='margin-bottom:0cm;margin-bottom:.0001pt; text-align:left;line-height:normal'>
				<font face='Tahoma'><b>
					<span class='texto_formulario_nro'>3.</span><span class='texto_formulario'> ".traduzir("APONTAMENTOS: QUESTIONAMENTOS A SEREM RESPONDIDOS")."</span>
				</b></font>
			</p>";
	$mpdf->WriteHTML($html);

	$html = "<p class='MsoNormal' align='left' style='margin-bottom:0cm;margin-bottom:.0001pt; text-align:left;line-height:normal'>
				<font face='Tahoma'><b>
					<span class='texto_formulario_nro'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3.1. </span><span class='texto_formulario'> ".traduzir("APONTAMENTOS DE ACHADOS GERAIS").":</span>
				</b></font>
			</p>";
	$mpdf->WriteHTML($html);
	$html = '
		<br>
			<div align="center">
			   <table class="MsoNormalTable" border="1" cellspacing="0" cellpadding="0" width="100%" style="width: 100.0%; border-collapse: collapse; border: medium none">
					<thead>
						<tr>
							<td width="30px" rowspan="2" class="head_table_questionamento">#</td>
							<td width="60px" rowspan="2" class="head_table_questionamento">'.traduzir('Página').'</td>
							<td width="30%" rowspan="2" class="head_table_questionamento">'.traduzir('Trecho do texto').'</td>
							<td width="10%" colspan="2" class="head_table_questionamento">'.traduzir('Crítico?').'</td>
							<td width="55%" rowspan="2" class="head_table_questionamento">'.traduzir('Achado').'</td>
						</tr>
						<tr>
							<td width="5%" class="head_table_questionamento">'.traduzir("Sim").'</td>
							<td width="5%" class="head_table_questionamento">'.traduzir("Não").'</td>
						</tr>
					</thead>
					<tbody>
	';

	$query = "SELECT * FROM agenda_protocolo_pendencia where agendamento = " . $varAgenda->id . " order by ordem";
	$resPend = $conexao->pesquisa($query);
	$ind = 1;
	while($rowPend = mysqli_fetch_assoc($resPend)){
		$html .= '
						<tr>
							<td class="body_table_questionamento">'.$ind.'</td>
							<td class="body_table_questionamento">'.formatar_texto_bd($rowPend['pagina']).'</td>
							<td class="body_table_questionamento">'.formatar_texto_bd($rowPend['trecho']).'</td>
							<td class="body_table_questionamento">';if($rowPend['critico']=="Sim"){$html.= "X";}$html.='</td>
							<td class="body_table_questionamento">';if($rowPend['critico']=="Nao"){$html.= "X";}$html.='</td>
							<td class="body_table_questionamento" style="text-align: justify !important">'.nl2br(formatar_texto_bd($rowPend['achado'])).'</td>
						</tr>
		';
		$ind++;
	}
	$html .= '
					</tbody>
				</table>
			</div>
		<br><br>
	';
	$mpdf->WriteHTML($html);
	$mpdf->AddPage('P');
	$html = "<br><p class='MsoNormal' align='left' style='margin-bottom:0cm;margin-bottom:.0001pt; text-align:left;line-height:normal'>
				<font face='Tahoma'><b>
					<span class='texto_formulario_nro'>4.</span><span class='texto_formulario'> ".traduzir("REVISORES")."</span>
				</b></font>
			</p>";
	$mpdf->WriteHTML($html);
	$html = '
		<br>
			<div align="center">
			   <table class="MsoNormalTable" border="1" cellspacing="0" cellpadding="0" width="80%" style="border-collapse: collapse; border: medium none">
					<thead>
						<tr>
							<td width="30px" class="head_table_questionamento" style="text-align: left !important">'.traduzir('Função').'</td>
							<td width="60px" class="head_table_questionamento" style="text-align: left !important">'.traduzir('Nome').'</td>
							<td width="20%" class="head_table_questionamento" style="text-align: left !important">'.traduzir('Departamento').'</td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="body_table_questionamento" style="text-align: left !important">'.traduzir("Revisor 1").'</td>
							<td class="body_table_questionamento" style="text-align: left !important">'.formatar_texto_bd($varAgenda->monitor->nome).'</td>
							<td class="body_table_questionamento" style="text-align: left !important">'.traduzir("Documentação técnica").'</td>
						</tr>
						<tr>
							<td class="body_table_questionamento" style="text-align: left !important">'.traduzir("Revisor").' 2</td>
							<td class="body_table_questionamento" style="text-align: left !important">'.formatar_texto_bd($varAgenda->revisor->nome).'</td>
							<td class="body_table_questionamento" style="text-align: left !important">'.traduzir("Documentação técnica").'</td>
						</tr>

					</tbody>
				</table>
	';
	$mpdf->WriteHTML($html);




	$mpdf->Output();
	$mpdf->charset_in='UTF-8';
?>