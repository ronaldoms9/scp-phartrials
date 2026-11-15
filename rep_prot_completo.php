<?php
	header('Content-Type:text/html; charset=ISO-8859-1');
	header("Pragma: no-cache");

	session_start();
	if($_SESSION['valido']<1){echo "<script>window.location.replace('expirado.php');</script>";};



	require_once __DIR__ . '/vendor/autoload.php';
	include_once "class/agenda.class.php";
	include_once "class/projeto.class.php";
	include_once "class/conexao.class.php";

	error_reporting(E_ERROR);
    ini_set('display_errors', 1);

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
			font-size: 10pt;
			font-weight: bold;
			text-align: justify;
			padding-left: 3.5pt;
			padding-top: 2.5pt;
			padding-bottom: 2.5pt;
		}
		.header_formulario{
			padding-top: 4pt;
			padding-bottom: 3pt;
			border-left: 1.0pt solid black;
			border-right: 1.0pt solid black;
			border-top: 1.0pt solid black;
			border-bottom: 1.0pt solid black;
			padding-left: 3.5pt;
			padding-right: 3.5pt;
			background: #D9D9D9
		}
		.header_pagina_em_branco{
			padding-top: 4pt;
			padding-bottom: 3pt;
			border-left: 1.0pt solid black;
			border-right: 1.0pt solid black;
			border-top: 1.0pt solid black;
			border-bottom: 1.0pt solid black;
			padding-left: 3.5pt;
			padding-right: 3.5pt;
			background: white;
		}
		.header_formulario{
			padding-top: 4pt;
			padding-bottom: 3pt;
			border-left: 1.0pt solid black;
			border-right: 1.0pt solid black;
			border-top: 1.0pt solid black;
			border-bottom: 1.0pt solid black;
			padding-left: 3.5pt;
			padding-right: 3.5pt;
			background: #D9D9D9
		}
		.header_pagina_em_branco{
			padding-top: 4pt;
			padding-bottom: 3pt;
			border-left: 1.0pt solid black;
			border-right: 1.0pt solid black;
			border-top: 1.0pt solid black;
			border-bottom: 1.0pt solid black;
			padding-left: 3.5pt;
			padding-right: 3.5pt;
			background: white;
		}
		.texto_formulario{
			font-size: 9.0pt;
		}
		.texto_formulario_divisao{
			font-size: 8.0pt;
			font-weight: bold;
		}
		.texto_formulario_en{
			font-size: 6.5pt;
			font-style: italic;
		}
		.texto_formulario_nro{
			font-size: 9.0pt;
		}
		.class_item{
			height: 22.85pt;
			border-left: 1.0pt solid black;
			border-right: 1.0pt solid black;
			border-top: 1.0pt solid black;
			border-bottom: 1.0pt solid black;
			padding-left: 3.5pt;
			padding-right: 3.5pt;
			padding-top: 1.5pt;
			padding-bottom: 3.5pt;
			text-align:left;
			//position:relative;
		}
		.class_item_coluna{
			height: 22.85pt;
			border-left: medium none;
			border-right: 1.0pt solid black;
			border-top: 1.0pt solid black;
			border-bottom: 1.0pt solid black;
			padding-left: 3.5pt;
			padding-right: 3.5pt;
			padding-top: 1.5pt;
			padding-bottom: 3.5pt;
			text-align:center;
			//position:relative;
		}
		.texto{
			font-size:8.0pt;
			font-weight: bold;
		}
		.texto_ordem{
			font-size:8.0pt;
			font-weight: bold;
		}
		.texto_en{
			font-size:6.0pt;
		}
		.texto_caption{
			font-size: 10pt;
			font-weight: bold;
		}
		.texto_conteudo{
			font-size: 8pt;
		}
		.texto_formulario{
			font-size: 9.0pt;
		}
		.texto_formulario_divisao{
			font-size: 8.0pt;
			font-weight: bold;
		}
		.texto_formulario_en{
			font-size: 6.5pt;
			font-style: italic;
		}
		.texto_formulario_nro{
			font-size: 9.0pt;
		}
		.divisao{
			border-left: 1.0pt solid black;
			border-right: 1.0pt solid black;
			border-top: 1.0pt solid black;
			border-bottom: 1.0pt solid black;
			padding-left: 3.5pt;
			padding-right: 3.5pt;
			padding-top: 4pt;
			padding-bottom: 3pt;
			background: #D9D9D9
		}
		table {
			overflow: wrap;
		}


	";
	$mpdf = new \Mpdf\Mpdf(['tempDir' => __DIR__ . '/temp', 'debug' => true, 'mode' => 'utf-8', 'margin-top' => 1158, 'margin-header' => 1500 ]);
	$mpdf->shrink_tables_to_fit=0;
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
				<td style="border-left-style: solid; border-left-width: 1px; border-right-width: 1px; border-top-width: 1px; border-bottom-width: 1px" class="cabecalho">'.'Revisão de Documentos'.'</td>
			</tr>
			<tr>
				<td style="border-left-width: 1px; border-right-width: 1px; border-top-style: solid; border-top-width: 1px; border-bottom-width: 1px" class="cabecalho">'.$varAgenda->getTipoVisita(). ' - ' . $varProjeto->codigo.'</td>
				<td style="border-left-style: solid; border-left-width: 1px; border-right-width: 1px; border-top-style: solid; border-top-width: 1px; border-bottom-width: 1px" class="cabecalho">'.traduzir('Versão') . ':'. $varAgenda->versao.'</td>
			</tr>
		</table>


		<br><br>
	';
	$rodape = '
		<table width="100%">
		  <tr>
			<td>
				<img src="modelos/protocolo/footer.png" style="margin-left: -20px;  margin-right: -50px; margin-top: 40px;">
			</td>
			<td style="text-align: right">
				<font size="8px"><span style="font-size: 12px">{PAGENO}/{nbpg}</span></font>
			</td>
		 </tr>
		</table>
	';
	$mpdf->SetHTMLFooter($rodape);
	$mpdf->SetHTMLHeader($cabecalho);
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
					".$varAgenda->getRelObjetivo()."
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
					<td  class='coluna_branca_label'><span class='coluna_branca_label'>".traduzir("Coerência técnica: desenho do estudo, tamanho amostral, objetivos, desfechos, exames laboratoriais, planejamento do estudo, critérios de aceitação de estudos analíticos, metodologia estatística, etc")."</span></td>
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
				<span class="texto_paragrafo">'.$varAgenda->getRelTipoDocumento().'
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
					<span class='texto_formulario_nro'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3.1. </span><span class='texto_formulario'> ".traduzir("CHECKLIST DE REVISÃO DO DOCUMENTO").":</span>
				</b></font>
			</p>";
	$mpdf->WriteHTML($html);
	$html = "<p class='texto_paragrafo'>
				<span class='texto_paragrafo'>".$varAgenda->getRelCheckList()."</span></p>";
	$mpdf->WriteHTML($html);



	$varConexao = new Conexao();
	//Começo loop nos formulários
	$query = "
		SELECT crf_agenda_forms.id, cfg_visitas.acao, crf_agenda_forms.agendamento, crf_agenda_forms.form_id,
			cfg_visitas.nome, cfg_visitas.texto_crf, cfg_visitas.texto_crf_en, cfg_visitas.ordem
			FROM crf_agenda_forms
				inner join cfg_visitas on crf_agenda_forms.form_id = cfg_visitas.id
				WHERE
					cfg_visitas.ordem <> '9' and cfg_visitas.registro_valido = 1 and crf_agenda_forms.agendamento = " . $_GET['id'] . "
			order by cast(cfg_visitas.ordem as DECIMAL)
	";
	$res_form = $varConexao->pesquisa($query);
	while($row_form = mysqli_fetch_assoc($res_form)){
		if($row_form['acao']=="PAGINA_BRANCO"){
			$html = "
				<table class='MsoNormalTable' border='0' cellspacing='0' cellpadding='0' width='100%' style='width: 100.0%; border-collapse: collapse; border: medium none' >
					<tr>
						<td width='100%' class='header_pagina_em_branco' >
						<br><br><br><br><br>
						<br><br><br><br><br>
						<br><br><br><br><br>
						<br><br><br><br><br>
						<br><br><br><br><br>
						<br><br><br><br><br>
						<br><br><br><br><br>
						<br><br><br><br><br>
						<br><br><br><br><br>
						<br><br><br><br><br>
						<br><br><br><br><br>
						<br><br><br><br><br>
						<br>
						</td>
					</tr>
				</table>
			";
		}else{
			$html = "
				<br><div>
					<table class='MsoNormalTable' border='0' cellspacing='0' cellpadding='0' width='100%' style='width: 100.0%; border-collapse: collapse; border: medium none' >
						<thead>
			";
			if($row_form['ordem']!="99999"){ //Pendencias dessa visita
				if(($row_form['acao']!="CABECALHO")&&($row_form['ordem']!="")){
					if($row_form['ordem']!="9999"){
						$html .= "
										<tr>
											<td width='23%' class='header_formulario' >
											<b>
											<span class='texto_formulario_nro'>";
													$html .= $row_form['ordem'].". ";
						$html .=			"</span>
											<span class='texto_formulario'>
												";
												$temmp = trim(mb_strtoupper(utf8_encode($row_form['texto_crf']." ")));
												if(substr(strtolower($temmp),0,6)=="farmac"){
													$temmp = "FARMACÊUTICOSX ";
												}
						$html .= 			$temmp . "
											</span>
											</b>
											<span class='texto_formulario_en'></span></td>
										</tr>
						";
					}
				}else{
					$html .= "
									<tr>
										<td class='cabecalho'>
										<span class='cabecalho_1'>
											<b>";
											$temmp = trim(mb_strtoupper(utf8_encode($row_form['texto_crf'])));

					$html .= 	$temmp . "</b>
										</span>
										<br><span class='cabecalho_2'>".str_primeira_letra_maiuscula(formatar_texto_bd($row_form['texto_crf_en']))."</span>
										</td>
									</tr>
					";
				}
			}
		}
		$html .= "
					</thead>
				</table>
		";
		$mpdf->WriteHTML($html);
		$html = "";


		//Pegar campo a campo
		$query = "SELECT cfg_visitas_campos.tipo, crf_agenda_campos.id, crf_agenda_campos.valor, cfg_visitas_campos.legenda, crf_agenda_campos.comentario,
				cfg_visitas_campos.numeracao, cfg_visitas_campos.nome, cfg_visitas_campos.texto, cfg_visitas_campos.texto_en, cfg_visitas_campos.ordem, cfg_visitas_campos.selecao_valores
				from crf_agenda_campos
					inner join cfg_visitas_campos on crf_agenda_campos.campo_id = cfg_visitas_campos.id
					where crf_agenda_campos.form_id = ".$row_form['id']."
							ORDER BY cfg_visitas_campos.ordem
		";
		$res_campo = $varConexao->pesquisa($query);
		while($row_campo = mysqli_fetch_assoc($res_campo)){
			if($row_campo['tipo']=="divisao"){
				$html .= "
					<table class='MsoNormalTable' border='1' cellspacing='0' cellpadding='0' width='100%' style='width: 100.0%; border-collapse: collapse; border: medium none'>
						<tr>
							<td width='23%' class='divisao'>
								<span class='texto_formulario'>";
				$html .=	$row_campo['ordem'].".&nbsp;&nbsp;";
				$temmp = trim(mb_strtoupper(utf8_encode($row_campo['texto'])));
				$html .= 	$temmp . "</span></b><i><span style='font-size: 8.0pt; line-height: 110%' class='texto_en'>
								</span></i></font></p>
							</td>
						</tr>
					</table>
				";
			}else{
				if($row_campo['tipo']!="json"){
					if($row_campo['tipo']=="quebra"){
						$mpdf->WriteHTML($html);
						$html = "";
						$mpdf->AddPage();
					}else{
						if($row_campo['tipo']!="select_coluna"){
							$largura_1 = '45% !important';
							$largura_2 = '55%';
						}else{
							if($row_campo['ordem']!=""){
								$largura_1 = '80% !important';
								$largura_2 = '';
								$opcoes = "";
								$opcoes = str_replace("<br />","%",$row_campo['selecao_valores']);
								$opcoes = str_replace("\n","",$opcoes);

								$combo = "";
								$temp  = "";
								$qtd_col = 0;
								for($z=0;$z<strlen($opcoes);$z++){
								   if(substr($opcoes,$z,1)=="%"){
										$qtd_col++;
								   }
								}
								if($row_campo['legenda']!=""){
									$qtd_col++;
								}
								if($qtd_col>0){
									$largura2 = intval(20 / $qtd_col);
								}else{
									$largura2 = intval(20);
								}
							}else{
								$largura_1 = '20% !important';
								$largura_2 = '80% !important';
							}
						}
						$html .= "
									<table class='MsoNormalTable' border='1' cellspacing='0' cellpadding='0' width='100%' style='width: 100.0%; border-collapse: collapse; border: medium none'>
										<tr>
											<td width='".$largura_1."' class='class_item'  valign='top'>
												<span class='texto_ordem'>";
						if($row_campo['ordem']!=0){$html .=	$row_campo['ordem'].".";}
						//Linha comentada aparece o campo em ingles tb
						//$html .=	"						&nbsp;&nbsp;</span><span class='texto'>".formatar_texto_bd($row_campo['texto'])."</span><br><i><span class='texto_en'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(".formatar_texto_bd($row_campo['texto_en']).")</i></span>
						$html .=	"						&nbsp;&nbsp;</span><span class='texto'>".formatar_texto_bd($row_campo['texto'])."</span></span>

											</td>

											";

											if(($row_campo['tipo']=="texto")||($row_campo['tipo']=="numero")||($row_campo['tipo']=="data")||($row_campo['tipo']=="calculo")){

												$html .= "<td width='".$largura_2."' class='texto_conteudo'><span class='texto_conteudo' style='font-size: 8pt'><center>".formatar_texto_bd($row_campo['valor'])."</span>";
												if($row_campo['legenda']!=""){
													$html .= "<br><span style='font-size: 8px'>".$row_campo['legenda']."</span>";
												}
												$html .= "</center>";
											}
											if(($row_campo['tipo']=="caixa")){
												$html .= "<td width='".$largura_2."' style='padding-left: 3.5pt; padding-right: 3.5pt; padding-top: 3.5pt; padding-bottom: 3.5pt'><span class='texto_conteudo' style='font-size: 8pt'>".formatar_texto_bd($row_campo['valor'])."</span><br>";
											}

											if($row_campo['tipo']=="select"){
												$html .= "<td width='".$largura_2."' class='class_item'>";
												$opcoes = "";
												$opcoes = str_replace("<br />","%",$row_campo['selecao_valores']);
												$opcoes = str_replace("\n","",$opcoes);

												$combo = "";
												$temp  = "";
												for($z=0;$z<strlen($opcoes);$z++){
												   if(substr($opcoes,$z,1)!="%"){$temp.=substr($opcoes,$z,1);}
												   else{
														$temp=trim($temp);
														$temp_selected = "";
														if(trim($row_campo['valor'])==$temp){ $temp_selected = " selected "; }
														$html .= $tamanho."<p><img src='imagens/check.png' width='10'><span valign='middle' class='texto'>&nbsp;&nbsp;<b>".formatar_texto_bd($temp)."</b></span></p>";
														$temp="";
												   }
												}
												if(strlen($temp)>0){
														$temp=trim($temp);
														$temp_selected = "";
														if(trim($row_campo['valor'])==$temp){ $temp_selected = " selected "; }
														$html .= $tamanho."<p><img src='imagens/check.png' width='10'><span valign='middle' class='texto'>&nbsp;&nbsp;<b>".formatar_texto_bd($temp)."</b></span></p>";

														$temp="";
												}


											}
											if($row_campo['tipo']=="select_coluna"){

												$opcoes = "";
												$opcoes = str_replace("<br />","%",$row_campo['selecao_valores']);
												$opcoes = str_replace("\n","",$opcoes);

												$combo = "";
												$temp  = "";

												if($row_campo['legenda']!=""){
													$html .= $tamanho."<td width='".$largura_2."' class='class_item_coluna'><span valign='middle' class='texto'><center>".formatar_texto_bd($row_campo['legenda'])."</center>";
													$html .= "</span></td>";
													$temp="";
												}

												for($z=0;$z<strlen($opcoes);$z++){
												   if(substr($opcoes,$z,1)!="%"){$temp.=substr($opcoes,$z,1);}
												   else{
														$temp=trim($temp);
														$temp_selected = "";
														//echo "Valor1-> " . formatar_texto_bd($rowItems['valor']) . "<br>";
														//echo "Valor2-> " . $temp . "<br>";
														if(trim(formatar_texto_bd($row_campo['valor']))==$temp){ $temp_selected = "selected"; }
														if($temp_selected!="selected"){
															$html .= $tamanho."<td width='".$largura_2."' class='class_item_coluna'><p><img src='imagens/check.png' width='13'><br><span valign='middle' class='texto'><b>".formatar_texto_bd($temp)."</b>";
														}else{
															$html .= $tamanho."<td width='".$largura_2."' class='class_item_coluna'><p><img src='imagens/checked.png' width='13'><br><span valign='middle' class='texto'><b>".formatar_texto_bd($temp)."</b>";
														}
														$temp_trad = traduzir(formatar_texto_bd($temp));
														if($temp_trad!=$temp){
															//$html .= "<br><span style='font-size:6pt;'>(".trim($temp_trad).")";
															//$html .= "<br><span style='font-size:6pt;'>(".trim($temp_trad).")";
														}
														$html .= "</span></p></td>";
														$temp="";
												   }
												}
												if(strlen($temp)>0){
														$temp=trim($temp);
														$temp_selected = "";
														if(trim(formatar_texto_bd($row_campo['valor']))==$temp){ $temp_selected = "selected"; }
														if($temp_selected!="selected"){
															$html .= $tamanho."<td width='".$largura_2."' class='class_item_coluna' valign='middle'><p valign='middle'><img src='imagens/check.png' width='13'><br><span valign='middle' class='texto'><b>".formatar_texto_bd($temp)."</b>";
														}else{
															$html .= $tamanho."<td width='".$largura_2."' class='class_item_coluna'><p><img src='imagens/checked.png' width='13'><br><span valign='middle' class='texto'><b>".formatar_texto_bd($temp)."</b>";
														}
														$temp_trad = traduzir(formatar_texto_bd($temp));
														if($temp_trad!=$temp){
															$html .= "<br><span style='font-size:6pt;'>(".trim($temp_trad).")</span>";
														}
														$html .= "</span></p></td>";
														$temp="";
												}


											}




						$html .="			</td>
										</tr>
									</table>


						";
					}
					$mpdf->WriteHTML($html);
					$html = "";
				}else{
					$json = formatar_texto_bd($row_campo['selecao_valores']);
					$json = str_replace("<br />","",$json);
					$obj = json_decode($json);

					$linhas = $obj->linhas;
					$colunas = $obj->colunas;

					$html .= "
							<table border='1' cellspacing='0' cellpadding='0' width='649' style='width: 100%; border-collapse: collapse; border: medium none'>
								<thead>
									<tr>
					";
					$largura_col = intval(100/count($colunas));
					for($col=0;$col<count($colunas);$col++){
						$html .= "
										<th style='".$largura_col."% !important' class='header_json'>
											<span class='header_json_pt'>
												".$colunas[$col]->titulo_br."
											</span><br>
											<span class='header_json_en'>
												(".$colunas[$col]->tituto_en.")
											</span><br>
											<span class='header_json_lg'>
												";
										$html .= "
											</span>
										</th>
						";
					}
					$html .= "
									</tr>
								</thead>
								<tbody>
					";
								for($row = 1;$row<=$linhas;$row++){
									if($row>100){ break; }
									$html .= "<tr>
									";
									for($col=0;$col<count($colunas);$col++){
										$html .= "<td style='".$largura_col."% !important' class='linha_json'>";
										if($colunas[$col]->tipo=="select"){
											$html .= "<table border='0' width='100%' cellspacing='0' cellpadding='0'><tr>";

											$largura_tabela_valores = intval(100/count($colunas[$col]->valores));
											for($col_valores=0;$col_valores<count($colunas[$col]->valores);$col_valores++){
												$html .= "<td class='coluna_valor'><img src='imagens/check.png' width='10'><br><b>".$colunas[$col]->valores[$col_valores]->titulo_br."</b><br><span style='font-size: 6pt'>(".$colunas[$col]->valores[$col_valores]->tituto_en.")</span></td>";
											}
											$html .= "</tr></table>";
										}else{
											$html .= "<span style='color: white'>______________________</span>";
										}
										if($colunas[$col]->formato!=""){
											$html .= "<span class='linha_json_legenda'><br><br>(".$colunas[$col]->formato.")</span>";
										}
										$html .= "</td>";
									}
									$html .="		  </tr>
											";
								}
					$html .="	</tbody>
							</table>
					";
					$mpdf->WriteHTML($html);
					$html = "";
				}

			}
			//$mpdf->WriteHTML($html);
			if(trim($row_campo['comentario']!="")){	//Tem comentário
			$html = "
				<table class='MsoNormalTable' border='1' cellspacing='0' cellpadding='0' width='100%' style='width: 100.0%; border-collapse: collapse; border: medium none'>
					<tr>
						<td width='100%'>
							<span style='font-size:8.0pt;font-weight: italic;'>
								".traduzir("Comentário").": ".formatar_texto_bd($row_campo['comentario'])."
							</span>
						</td>
					</tr>
				</table>
				";
				$mpdf->WriteHTML($html);
				$html = "";
			}


		}



		$html .= "
			</div>

		";
		$mpdf->WriteHTML($html);

	}






	$html = "<p class='MsoNormal' align='left' style='margin-bottom:0cm;margin-bottom:.0001pt; text-align:left;line-height:normal'>
				<font face='Tahoma'><b>
					<span class='texto_formulario_nro'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3.2. </span><span class='texto_formulario'> ".traduzir("APONTAMENTOS DE ACHADOS GERAIS").":</span>
				</b></font>
			</p>
			<p class='texto_paragrafo'>
				<span class='texto_paragrafo'>".$varAgenda->getRelApontamento()."</span></p>";
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
							<td colspan="2" class="head_table_questionamento">'.traduzir('Crítico?').'</td>
							<td width="55%" rowspan="2" class="head_table_questionamento">'.traduzir('Achado').'</td>
						</tr>
						<tr>
							<td class="head_table_questionamento">'.traduzir("Sim").'</td>
							<td class="head_table_questionamento">'.traduzir("Não").'</td>
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
							<td class="body_table_questionamento" style="text-align:justify !important">'.nl2br(formatar_texto_bd($rowPend['achado'])).'</td>
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