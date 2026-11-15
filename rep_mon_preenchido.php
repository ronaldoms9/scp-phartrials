<?
	/*		acentuação teste chão	*/

	$encoding = 'UTF-8';

	include_once __DIR__."/seguranca.php";
	include_once __DIR__."/class/agenda.class.php";
	include_once __DIR__."/class/projeto.class.php";
	require_once __DIR__ . '/vendor/autoload.php';
	require_once __DIR__."/funcoes.php";

	$separadorRegistro     = "|";
	$separadorCampo        = "§";
	$separadorRegistroJSON = "Ø";
	$separadorCampoJSON    = "»";
	$varProjeto            = new Projeto($_SESSION['projeto']);

	$varData        = $_GET['data'];
	$varAgendamento = $_GET['id'];
	$varAgenda      = new Agenda($_GET['id']);

	if($varAgenda->ativo==1){
		$varAgenda->setaDataImpressao($varData);
	}

	function getValorCampoNovo($formulario, $idCampo){
		include_once __DIR__."/class/conexao.class.php";
		$varConexao = new Conexao();
		$query = "select
					valor
						from crf_agenda_campos
							where form_id = " . $formulario . "
							and campo_id = " . $idCampo;
		$res = $varConexao->pesquisa($query);
		$row = mysqli_fetch_assoc($res);
		return formata_utf8_encode($row['valor']);
	}

	function getValorCampo($fase, $campo){
		include_once __DIR__."/class/conexao.class.php";
		$conexao = new Conexao();
		if($fase == "inicial"){
			$query = "select id, tipo_monitoria from agenda where projeto = " . $_SESSION['projeto'] . " and tipo_monitoria in ('Visita Inicial', 'Inicial') order by id descc";
			$res = $conexao->pesquisa($query);
			if(mysqli_num_rows($res)>0){
				$row = mysqli_fetch_assoc($res);
				if($row['tipo_monitoria'] == "Visita Inicial"){ //Modelo antigo
					$id_agenda = $row['id'];
					//Pegar o formulário do produto investigacional para depois pegar o campo
					$query = "select id from crf_agenda_forms where agendamento = " . $id_agenda . " and form_id = (select id from cfg_visitas where nome = 'PRODUTO INVESTIGACIONAL' and tipo = 'Visita Inicial')";
					$res_form = $conexao->pesquisa($query);
					$row_form = mysqli_fetch_assoc($res_form);
					$id_form = $row_form['id'];
					//Pegar o campo do produto investigacional
					$query = "select id, valor from crf_agenda_campos where form_id = " . $id_form . " and campo_id = " . $campo;
					$res_campo = $conexao->pesquisa($query);
					$row_campo = mysqli_fetch_assoc($res_campo);
					return formata_utf8_encode($row_campo['valor']);
				}
				if($row['tipo_monitoria'] == "Inicial"){ //Modelo novo (JSON)
					$id_agenda = $row['id'];
				}
			}
		}
	}
	function getValorCampoFinal($tipo, $campo){
		include_once __DIR__."/class/conexao.class.php";
		global $separadorCampo;
		$conexao = new Conexao();
		//if($fase == "final"){
			$query = "select id, tipo_monitoria from agenda where projeto = " . $_SESSION['projeto'] . " and tipo_monitoria in ('Visita Final')";

			$resVisita = $conexao->pesquisa($query);
			$rowVisita = mysqli_fetch_assoc($resVisita);
			$id_agenda = $rowVisita['id'];
			$query = "select id from crf_agenda_forms where agendamento = " . $id_agenda . " and form_id = (select id from cfg_visitas where nome = 'PRODUTO INVESTIGACIONAL' and tipo = 'Visita Final')";
			$res_form = $conexao->pesquisa($query);
			$row_form = mysqli_fetch_assoc($res_form);
			$id_form = $row_form['id'];
			$query = "select id, valor from crf_agenda_campos where form_id = " . $id_form . " and campo_id = 1475";
			$res_campo = $conexao->pesquisa($query);
			$row_campo = mysqli_fetch_assoc($res_campo);

			$varArr = explode("|", formata_utf8_encode($row_campo['valor']));

			$comparador = $varArr[0];
			$teste      = $varArr[1];
			$comparador = explode($separadorCampo, $comparador);
			$teste      = explode($separadorCampo, $teste);

			if($tipo==0){
				$temp = $comparador[$campo];
			}else{
				$temp = $teste[$campo];
			}

			return formata_utf8_encode($temp);
	}
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
		return preg_replace(array("/(ÃƒÂ¡|ÃƒÂ |ÃƒÂ£|ÃƒÂ¢|ÃƒÂ¤)/","/(Ãƒï¿½|Ãƒâ‚¬|ÃƒÆ’|Ãƒâ€š|Ãƒâ€ž)/","/(ÃƒÂ©|ÃƒÂ¨|ÃƒÂª|ÃƒÂ«)/","/(Ãƒâ€°|ÃƒË†|ÃƒÅ |Ãƒâ€¹)/","/(ÃƒÂ­|ÃƒÂ¬|ÃƒÂ®|ÃƒÂ¯)/","/(Ãƒï¿½|ÃƒÅ’|ÃƒÅ½|Ãƒï¿½)/","/(ÃƒÂ³|ÃƒÂ²|ÃƒÂµ|ÃƒÂ´|ÃƒÂ¶)/","/(Ãƒâ€œ|Ãƒâ€™|Ãƒâ€¢|Ãƒâ€�|Ãƒâ€“)/","/(ÃƒÂº|ÃƒÂ¹|ÃƒÂ»|ÃƒÂ¼)/","/(ÃƒÅ¡|Ãƒâ„¢|Ãƒâ€º|ÃƒÅ“)/","/(ÃƒÂ±)/","/(Ãƒâ€˜)/"),explode(" ","a A e E i I o O u U n N"),$string);
	}
	function traduzirREL($texto_entrada){
		global $conexao;
		$texto = "";
		//if(($texto_entrada!="NÃƒÂ£o")||($texto_entrada!="NÃƒÆ’O")){
		if(strtoupper(tirarAcentos($texto_entrada))!="NAO"){
			$query_tr = "select * from traducao where upper(portugues) = '".strtoupper($texto_entrada)."';";
			$res_tr = $conexao->pesquisa($query_tr);
			$lang = $_SESSION['lang'];
			if(mysqli_num_rows($res_tr)==0){$texto = $texto;}else{
				$row_tr = mysqli_fetch_assoc($res_tr);
				if($lang == "p"){$texto = $texto;}
				if($lang == "i"){$texto = $row_tr['ingles'];}
				if($lang == "e"){$texto = $row_tr['espanhol'];}
				if($lang == ""){$texto = $row_tr['ingles'];}
			}
		}else{
			$texto = "No";
		}

		if($texto == ""){$texto = $texto_entrada;}
		return $texto;
	}
	function formatar_texto_bd($entrada){
		return utf8_encode($entrada);
	}
	function formatar_texto($entrada){
		return utf8_encode($entrada);
	}







	$query = "select  ";
	$query .= " agenda.*,
			cfg_centro.numero as centro_numero,
			cfg_centro.nome as centro_nome,
			cfg_centro.investigador  as centro_investigador,
			cfg_centro.coordenador as centro_coordenador
				from agenda
				inner join cfg_centro on agenda.centro = cfg_centro.id
			where agenda.id = " . $_GET['id'];

	$res_agenda = $conexao->pesquisa($query);
	$row_agenda = mysqli_fetch_assoc($res_agenda);

	$query 			= "select * from proj_projeto where id = " . $_SESSION['projeto'];
	$res_projeto	= $conexao->pesquisa($query);
	$row_projeto    = mysqli_fetch_assoc($res_projeto);

	$query = "select * from proj_projeto where id = " . $_SESSION['projeto'];
	$res_proj = $conexao->pesquisa($query);
	$row_proj = mysqli_fetch_assoc($res_proj);

	$stylesheet = "
		table {
			page-break-inside: auto;
		}

		tr {
			page-break-inside: auto;
			page-break-after: auto;
		}

		td {
			page-break-inside: auto;
			white-space: normal;
			word-wrap: break-word;

		}
		.texto-longo {
			page-break-inside: auto;
		}


		.direita {
		  text-align: right;
		  margin-right: 1em;
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
			//position:relative;
		}
		.class_item_quebra{
			height: 22.85pt;
			padding-left: 3.5pt;
			padding-right: 3.5pt;
			padding-top: 0pt;
			padding-bottom: 0pt;
			border-left: 1.0pt solid black;
			border-right: 1.0pt solid black;

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
		.texto_check{
			font-size:7.0pt;
		}
		.texto_ordem{
			font-size:12.9px !important;
			font-weight: bold;
		}
		/*.texto_en{
			font-size:6.0pt;
		}*/
		.rodape{
			font-size:5.0pt;
			font-weight: normal;
		}
		.paginacao{
			font-size:6.0pt;
		}
		.fonte_caption{
			font-size: 10pt;
			font-weight: bold;
		}
		.texto_conteudo{
			font-size: 10pt;
		}
		.texto_comentario{
			font-size: 10.0pt;
			font-weight: italic;
			text-align: justify;
		}
		.texto_header_assinatura{
			font-size: 8pt;
		}
		.texto_assinatura{
			font-size: 9pt;
			font-weight: bold;
		}
		.assinatura_imagem{
			width:100%;
			max-width:150px;
		}
		.texto_formulario_en{
			font-size: 6.5pt;
			font-style: italic;
		}
		.texto_formulario_nro{
			font-size: 9.0pt;
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
		.cabecalho{
			background: white;
			border-left: 0.0pt solid black;
			border-right: 0.0pt solid black;
			border-top: 0.0pt solid black;
			border-bottom: 0.0pt solid black;
		}
		.cabecalho_1{
			padding-top: 4pt;
			padding-bottom: 3pt;
			padding-left: 3.5pt;
			padding-right: 3.5pt;
			background: white;
			font-size: 7.0pt;
		}
		.cabecalho_2{
			padding-top: -2.0pt;
			padding-bottom: 0pt;
			padding-left: 3.5pt;
			padding-right: 3.5pt;
			background: white;
			margin-top: -5pt;
			vertical-align: top;
			font-family: Tahoma, Verdana, Segoe, sans-serif;
			font-size: 7.0pt;
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
			/*background: #D9D9D9*/
			background: #DBE5F1;
		}
		.header_json{
			border: 1.0pt solid windowtext;
			padding-left: 5.4pt;
			padding-right: 5.4pt;
			padding-top: 4pt;
			padding-bottom: 3pt;
			/*background: #F2F2F2*/
			background: #DBE5F1;
		}
		.header_json_pt{
			height: 22.85pt;
			padding-left: 3.5pt;
			padding-right: 3.5pt;
			padding-top: 1.5pt;
			padding-bottom: 3.5pt;
			text-align:left;
			font-size: 9.0pt;
			font-weight: bold;
		}
		.header_json_en{
			font-size:4.5pt;
			font-style: italic;
			font-weight: normal;
		}
		.header_json_lg{
			font-size:4.0pt;
			font-style: italic;
		}
		.linha_json{
			text-align:center !important;
			border: 1px solid #000000;
			padding-left: 4px;
			padding-right: 4px;
			padding-top: 6px;
			padding-bottom: 3px;
			/*white-space:nowrap*/
		}
		.linha_json_legenda{
			font-style: italic;
			text-align : center !important;
			font-size  : 6.0pt;
			font-weight: normal;
		}
		.linha_json_preenchido{
			text-align:center !important;
			padding-left: 4px;
			padding-right: 4px;
			padding-top: 6px;
			padding-bottom: 3px;
			/*white-space:nowrap*/
		}
		.coluna_valor{
			text-align:center;
			font-size:6.0pt;
		}
		.justificado{
			text-align: justify;
			padding: 7px;
			/*letter-spacing: 0;*/
		}
		.centralizado{
			text-align: center;
			letter-spacing: 0;
		}
		.ptHeaderTitulo{
			font-size: 20.0pt;
			font-weight: bold;
		}
		.ptHeaderDestaque{
			font-size: 16.0pt;
			font-weight: bold;
		}
		.ptHeaderNormal{
			font-size: 19.0pt;
			font-weight: normal;
		}
		.ptFooterDestaque{
			font-size: 10.0pt;
			font-weight: bold;
		}
		.ptFooterNormal{
			font-size: 10.0pt;
			font-weight: normal;
		}
		.ptTexto{
			font-size: 11.0pt;
			font-weight: normal;
		}
		.texto{
			font-size: 10.0pt;
			font-weight: normal;
		}
		.texto_formulario_divisao{
			font-size: 9.0pt;
			font-weight: bold;
		}
		.ptTextoFormulario{
			font-size: 6.5pt;
			font-weight: normal;
		}
		.ptTabelaBranco{
			font-size       : 10.0pt;
			font-weight     : normal;
			background-color: white;
			color           : black;
			padding-left    : 4.5pt;
			padding-right   : 4.5pt;
			padding-top     : 4.5pt;
			padding-bottom  : 4.5pt
			border          : 0 solid white;
		}
		.ptTabelaBrancoBorda{
			font-size       : 10.0pt;
			font-weight     : normal;
			background-color: white;
			color           : black;
			border          : 1px solid black;
			padding-left    : 4.5pt;
			padding-right   : 4.5pt;
			padding-top     : 4.5pt;
			padding-bottom  : 4.5pt
		}
		.ptTabelaAzul{
			font-size       : 10.0pt;
			font-weight     : bold;
			background-color: #DBE5F1;
			color           : black;
			padding-left    : 4.5pt;
			padding-right   : 4.5pt;
			padding-top     : 4.5pt;
			padding-bottom  : 4.5pt
		}
		.ptTabelaAzulBorda{
			font-size       : 10.0pt;
			font-weight     : bold;
			background-color: #DBE5F1;
			color           : black;
			border          : 1px solid black;
			padding-left    : 4.5pt;
			padding-right   : 4.5pt;
			padding-top     : 4.5pt;
			padding-bottom  : 4.5pt
		}
		.ptTabelaBranca{
			font-size       : 10.0pt;
			font-weight     : normal;
			background-color: white;
			color           : black;
			padding-left    : 4.5pt;
			padding-right   : 4.5pt;
			padding-top     : 4.5pt;
			padding-bottom  : 4.5pt
			/*text-align      : center !important;*/
		}
		.ptTabelaAzulEscuro{
			font-size: 11.0pt;
			font-weight: bold;
			background-color: #8DB3E2;
			color           : black;
			padding-left    : 4.5pt;
			padding-right   : 4.5pt;
			padding-top     : 4.5pt;
			padding-bottom  : 4.5pt
		}
		.vertical-lr {
			writing-mode: vertical-lr;
			transform: rotate(180deg);
		}
	";

	$defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
    $fontDirs = $defaultConfig['fontDir'];
    $defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
    $fontData = $defaultFontConfig['fontdata'];
    $customFontDir = __DIR__ . '/fonts';
	$mpdf = new \Mpdf\Mpdf([
        'fontDir' => array_merge($fontDirs, [
        $customFontDir,
        ]),
        'fontdata' => $fontData + [
            'calibri' => [
                'R' => 'calibri-regular.ttf',
                'I' => 'calibri-italic.ttf',
                'B' => 'calibri-bold.ttf',
                'BI' => 'calibri-bold-italic.ttf',
            ],
        ],
        'default_font' => 'calibri',
        'tempDir' => __DIR__ . '/temp',
        'debug' => true,
        'mode' => 'utf-8',
        'margin-top' => 100,
        'margin-header' => 200,
        'margin_bottom' => 30,
		'autoLangToFont' => true,
    	'autoScriptToLang' => true
    ]);

	$mpdf->SetDefaultBodyCSS('text-align', 'justify');

	$mpdf->use_kwt = true;

	$textoLuis = "Segue retorno do jurídico da Instituição. “De fato, verifico que o contrato contempla a possibilidade de atuação de representantes designados pelo Patrocinador para atividades de monitoria e auditoria. Contudo, sob a ótica de governança contratual, proteção de dados pessoais sensíveis e compliance regulatório, entendemos recomendável a formalização expressa da empresa ACTGen como representante autorizada.";

	////$mpdf->shrink_tables_to_fit = 0;
	$mpdf->shrink_tables_to_fit = false;
	$mpdf->setAutoTopMargin = 'stretch';
	$mpdf->allow_charset_conversion=true;
	$mpdf->WriteHTML($stylesheet,1);

	$cabecalho = '<table border="0" height="100%" width="100%"  cellspacing="0" cellpadding="0" bordercolor="#000000" style="border-collapse: collapse;">
		<tr>
			<td style="padding: 13px;" >
                <center><br><img src="imagens/header_documento.png"></center>
            </td>
		</tr>
		</table>
	';
	$rodape = '<table border="0" height="100%" width="100%"  cellspacing="0" cellpadding="0" bordercolor="#000000" style="border-collapse: collapse;">
		<tr>
			<td style="padding: 3px; text-align: right;"><span class="ptFooterNormal">Página <b>{PAGENO}</b> de <b>{nbpg}</b></span></td>
		</tr>
		</table>
	';

	$mpdf->SetHTMLFooter($rodape);
	$mpdf->SetHTMLHeader($cabecalho);


	//Caçar variáveis
	$varIniNomeGenericoRef        = getValorCampo("inicial", 3323);
	$varIniNomeGenericoTeste      = getValorCampo("inicial", 3330);
	$varIniNomeComercialRef       = getValorCampo("inicial", 3324);
	$varIniNomeComercialTeste     = getValorCampo("inicial", 3331);
	$varIniFabricanteRef          = getValorCampo("inicial", 3322);
	$varIniFabricanteTeste        = getValorCampo("inicial", 3329);
	$varIniFormaFarmaceuticaRef   = getValorCampo("inicial", 3326);
	$varIniFormaFarmaceuticaTeste = getValorCampo("inicial", 3333);
	$varIniConcentracaoRef        = getValorCampo("inicial", 3325);
	$varIniConcentracaoTeste      = getValorCampo("inicial", 3332);
	$varIniCondArmazenamentoRef   = getValorCampo("inicial", 3327);
	$varIniCondArmazenamentoTeste = getValorCampo("inicial", 3334);
	$varIniLoteRef                = getValorCampo("inicial", 3273);
	$varIniLoteTeste              = getValorCampo("inicial", 3282);
	$varIniDataFabRef             = getValorCampo("inicial", 3274);
	$varIniDataFabTeste           = getValorCampo("inicial", 3283);
	$varIniPrazoValidadeRef       = getValorCampo("inicial", 3275);
	$varIniPrazoValidadeTeste     = getValorCampo("inicial", 3284);



	$varIniNomeGenericoRef        = getValorCampo("inicial", 3330);
	$varIniNomeGenericoTeste      = getValorCampo("inicial", 3323);
	$varIniNomeComercialRef       = getValorCampo("inicial", 3331);
	$varIniNomeComercialTeste     = getValorCampo("inicial", 3324);
	$varIniFabricanteRef          = getValorCampo("inicial", 3329);
	$varIniFabricanteTeste        = getValorCampo("inicial", 3322);
	$varIniFormaFarmaceuticaRef   = getValorCampo("inicial", 3333);
	$varIniFormaFarmaceuticaTeste = getValorCampo("inicial", 3326);
	$varIniConcentracaoRef        = getValorCampo("inicial", 3332);
	$varIniConcentracaoTeste      = getValorCampo("inicial", 3325);
	$varIniCondArmazenamentoRef   = getValorCampo("inicial", 3334);
	$varIniCondArmazenamentoTeste = getValorCampo("inicial", 3327);
	$varIniLoteRef                = getValorCampo("inicial", 3282);
	$varIniLoteTeste              = getValorCampo("inicial", 3273);
	$varIniDataFabRef             = getValorCampo("inicial", 3283);
	$varIniDataFabTeste           = getValorCampo("inicial", 3274);
	$varIniPrazoValidadeRef       = getValorCampo("inicial", 3284);
	$varIniPrazoValidadeTeste     = getValorCampo("inicial", 3275);


	$varIniCaixaRef               = getValorCampoFinal(0, 6);
	$varIniCaixaTeste             = getValorCampoFinal(1, 6);
	$varIniQuantidadeRef           = getValorCampoFinal(0, 1);
	$varIniQuantidadeTeste         = getValorCampoFinal(1, 1);

	$varIniQuantidadeUtilizadaRef                               = getValorCampoFinal(0, 2);
	$varIniQuantidadeUtilizadaTeste                             = getValorCampoFinal(1, 2);
	$varIniQuantidadeTotalEnviadaParaEtapaClinicaRef            = getValorCampoFinal(0, 7);
	$varIniQuantidadeTotalEnviadaParaEtapaClinicaTeste          = getValorCampoFinal(1, 7);
	$varIniQuantidadeConsumidaPelosParticipantesDePesquisaRef   = getValorCampoFinal(0, 3);
	$varIniQuantidadeConsumidaPelosParticipantesDePesquisaTeste = getValorCampoFinal(1, 3);
	$varIniPerdasRef                                            = getValorCampoFinal(0, 4);
	$varIniPerdasTeste                                          = getValorCampoFinal(1, 4);

	$varIniQuantidadeDevolvidaParaAFarmaciaCentralRef           = getValorCampoFinal(0, 8);
	$varIniQuantidadeDevolvidaParaAFarmaciaCentralTeste         = getValorCampoFinal(1, 8);

	$varIniEstoqueFinalRef                                      = getValorCampoFinal(0, 5);
	$varIniEstoqueFinalTeste                                    = getValorCampoFinal(1, 5);



	$varIniCaixaRef               = getValorCampoFinal(1, 6);
	$varIniCaixaTeste             = getValorCampoFinal(0, 6);
	$varIniQuantidadeRef           = getValorCampoFinal(1, 1);
	$varIniQuantidadeTeste         = getValorCampoFinal(0, 1);

	$varIniQuantidadeUtilizadaRef                               = getValorCampoFinal(1, 2);
	$varIniQuantidadeUtilizadaTeste                             = getValorCampoFinal(0, 2);
	$varIniQuantidadeTotalEnviadaParaEtapaClinicaRef            = getValorCampoFinal(1, 7);
	$varIniQuantidadeTotalEnviadaParaEtapaClinicaTeste          = getValorCampoFinal(0, 7);
	$varIniQuantidadeConsumidaPelosParticipantesDePesquisaRef   = getValorCampoFinal(1, 3);
	$varIniQuantidadeConsumidaPelosParticipantesDePesquisaTeste = getValorCampoFinal(0, 3);
	$varIniPerdasRef                                            = getValorCampoFinal(1, 4);
	$varIniPerdasTeste                                          = getValorCampoFinal(0, 4);

	$varIniQuantidadeDevolvidaParaAFarmaciaCentralRef           = getValorCampoFinal(1, 8);
	$varIniQuantidadeDevolvidaParaAFarmaciaCentralTeste         = getValorCampoFinal(0, 8);

	$varIniEstoqueFinalRef                                      = getValorCampoFinal(1, 5);
	$varIniEstoqueFinalTeste                                    = getValorCampoFinal(0, 5);


	$tabela_ini = '
			<table border="1" cellspacing="0" cellpadding="0" width="100%">
			<tr >
				<td width="100%" colspan="5" class="ptTabelaAzul">'.traduzir('Relatório de Visita de Monitoria').'</td>
			</tr>
			<tr style="page-break-inside: avoid; height: 42.95pt">
				<td width="206" class="ptTabelaAzul">'.traduzir('Código da Monitoria').'</td>
				<td class="ptTabelaBranca" colspan="4">'.$varProjeto->numero.'</td>
			</tr>
			<tr style="page-break-inside: avoid; height: 42.95pt">
				<td width="206" class="ptTabelaAzul">'.traduzir('Visita Número').'</td>
				<td class="ptTabelaBranca"><center>'.$varAgenda->nro_visita.'</center></td>
				<td width="70" class="ptTabelaAzul">'.traduzir('Data').'</td>
				<td class="ptTabelaBranca" colspan="2"><center>'.$varAgenda->getDataInicialDATA().'</center></td>
			</tr>
			<tr style="page-break-inside: avoid; height: 42.95pt">
				<td width="206" class="ptTabelaAzul">'.traduzir('Código do Protocolo').'</td>
				<td class="ptTabelaBranca"><center>'.$varProjeto->codigo.'</center></td>
				<td width="70" class="ptTabelaAzul">'.traduzir('Versão e data do protocolo').'</td>
				<td class="ptTabelaBranca" colspan="2"><center>Versão '.$varProjeto->versao.' - '.$varProjeto->data_protocolo.'</center></td>
			</tr>
			<tr style="page-break-inside: avoid; height: 42.95pt">
				<td width="206" class="ptTabelaAzul">'.traduzir('Grupo').'</td>
				<td class="ptTabelaBranca"><center>'.$varAgenda->grupo_periodo.'</center></td>
				<td width="70" class="ptTabelaAzul">'.traduzir('Período').'</td>
				<td class="ptTabelaBranca" colspan="2"><center>'.$varAgenda->periodo.'</center></td>
			</tr>
			<tr>
				<td class="ptTabelaAzul">'.traduzir('Protocolo').'</td>
				<td class="justificado" colspan="4">'.formatar_texto_bd($varProjeto->descricao).'</td>
			</tr>
			<tr>
				<td class="ptTabelaAzul">'.traduzir('Tipo de Visita').'</td>
				<td class="ptTabelaBranca" colspan="4">'. formatar_texto_bd($varAgenda->getTipoVisita()).'</td>
			</tr>
			<tr>
				<td class="ptTabelaAzul">'.traduzir('Nome Produto Investigacional').'</td>
				<td class="ptTabelaBranca" colspan="4">'. formatar_texto_bd($varProjeto->nome).'</td>
			</tr>
			<tr>
				<td class="ptTabelaAzul">'.traduzir('Patrocinador').'</td>
				<td class="ptTabelaBranca" colspan="4">'.formatar_texto($varProjeto->patrocinador->nome).'</td>
			</tr>
			<tr>
				<td class="ptTabelaAzul">'.traduzir('Centro de Pesquisa').'</td>
				<td class="ptTabelaBranca" colspan="4">'.formatar_texto($varAgenda->centro->nome).'</td>
			</tr>
			<tr>
				<td class="ptTabelaAzul">'.traduzir('Investigador Principal').'</td>
				<td class="ptTabelaBranca" colspan="4">'.formatar_texto_bd($varAgenda->centro->investigador).'</td>
			</tr>
			<tr>
				<td class="ptTabelaAzul">'.traduzir('Monitores participantes da pesquisa').'</td>
				<td class="ptTabelaBranca" colspan="4">'.formatar_texto(nl2br($varAgenda->presentes)).'</td>
			</tr>
		</table>
	';
	$mpdf->WriteHTML($tabela_ini);

	//Começo loop nos formulários
	$query = "
		SELECT crf_agenda_forms.id,
				cfg_visitas.acao,
				crf_agenda_forms.agendamento,
				crf_agenda_forms.form_id,
				cfg_visitas.nome,
				cfg_visitas.texto_crf,
				cfg_visitas.texto_crf_en,
				cfg_visitas.ordem
					FROM crf_agenda_forms
						inner join cfg_visitas on crf_agenda_forms.form_id = cfg_visitas.id
						WHERE
							cfg_visitas.ordem <> ''
							and cfg_visitas.registro_valido = 1
							and crf_agenda_forms.agendamento = " . $_GET['id'] . "
								order by cast(cfg_visitas.ordem as DECIMAL)
	";

	$res_form = $conexao->pesquisa($query);

	$countForm = 0;

	while($row_form = mysqli_fetch_assoc($res_form)){
		$countField = 0;
		if($row_form['acao']=="PAGINA_BRANCO"){
			$html = "
				<table autosize='1'
						class='MsoNormalTable'
						border='0'
						cellspacing='0'
						cellpadding='0'
						width='100%'
						style='width: 100.0%; border-collapse: collapse; border: medium none' >
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
			$countForm++;
			$html = "
				<br><div>
					<table
						autosize='1'
						class='MsoNormalTable'
						border='0'
						cellspacing='0'
						cellpadding='0'
						width='100%' style='width: 100.0%; border-collapse: collapse; border: medium none' >
						<thead>
			";
			if($row_form['acao']!="CABECALHO"){
				if($row_form['ordem']!="9999"){
					$html .= "
									<tr>
										<td width='23%' class='ptTabelaAzul' style='border-left: 1.0pt solid black; border-right: 1.0pt solid black; border-top: 1.0pt solid black; border-bottom: 1.0pt solid black;'>
										<b>
										<span class='texto_formulario_nro'>";
												$html .= $countForm.". ";
					$html .=			"</span>
										<span class='texto_formulario'>
											";
											$temmp = trim(mb_strtoupper(utf8_encode($row_form['texto_crf']." ")));
											if(substr(strtolower($temmp),0,6)=="farmac"){
												$temmp = "FARMACÅUTICOSX ";
											}
					$html .= 			$temmp;
					$html .= 			"
										</span>
										</b>
										<span class='texto_formulario_en'>
										".
										$temp .= "</span></td>
									</tr>
					";
				}
			}else{
				$html .= "
								<tr>
									<td class='ptTabelaAzul'>
									<span class='ptTabelaAzul'>
										<b>";
										$temmp = trim(mb_strtoupper(utf8_encode($row_form['texto_crf'])));
				$html .= 	$temmp .	"</b>
									</span>
									";
							$temp .="		</td>
								</tr>
				";
			}
		}
		$html .= "
					</thead>
				</table>
		";
		$mpdf->WriteHTML($html);
		$html = "";



		if($row_form['ordem']=="99999"){
			if($_SESSION['projeto']=="7"){
				$query_t = "select * from agenda_lista_pendencia where visita = ".$row_agenda['id']." and centro = ".$row_agenda['centro']." and status = 'PENDENTE' order by id";
			}else{
				$dataVis = $row_agenda['data'];
				$dataVis = substr($dataVis, 6,2) . "/" . substr($dataVis, 4,2) . "/" .substr($dataVis, 0,4);
				$query_t = "select
								*
									from agenda_lista_pendencia
										where visita = '".$row_agenda['nro_visita']."'
											and projeto = ".$_SESSION['projeto']."
												order by id";
				$resPend = $conexao->pesquisa($query_t);

				if(mysqli_num_rows($resPend)==0){
					$html = "<table
								autosize='1'
								class='MsoNormalTable'
								border='1'
								cellspacing='0'
								cellpadding='0'
								width='100%'
								style='width: 100.0%; border-collapse: collapse; border: medium none'>
								<tr>
									<td width='100%' class='class_item'  valign='top'>Nenhuma pendência cadastrada para essa visita</td>
								</tr>
							</table>
					";
					$html = "";
				}else{
					$html = '
						<table autosize="1" width="100%" class="MsoNormalTable" border="1" cellspacing="0" cellpadding="0" style="border-collapse: collapse; border: medium none">
							<tr>
								<td width="110" valign="top" style="border-left: 1.0pt solid black; border-right: 1.0pt solid black; border-top: 1.0pt solid black; border-bottom: 1.0pt solid black; padding-left: 5.4pt; 	padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm">
									<p class="MsoNormal" align="center" style="text-align:center"><b>
									<span lang="EN-GB" style="font-size:11.0pt;  font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Participante</span></b>
								</td>
								<td width="120" valign="top" style="border: 1.0pt solid black; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm">
									<p class="MsoNormal" align="left" style="text-align:center"><b>
									<span lang="EN-GB" style="font-size:11.0pt;  font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Tipo</span></b>
								</td>
								<td width="40%" valign="top" style="border-left: medium none; border-right: 1.0pt solid black; border-top: 1.0pt solid black; border-bottom: 1.0pt solid black; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm">
									<p class="MsoNormal" align="center" style="text-align:center"><b>
									<span lang="EN-GB" style="font-size:11.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Descrição</span></b>
								</td>
								<td width="40%" valign="top" style="border-left: medium none; border-right: 1.0pt solid black; border-top: 1.0pt solid black; border-bottom: 1.0pt solid black; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm">
									<p class="MsoNormal" align="center" style="text-align:center"><b>
									<span lang="EN-GB" style="font-size:11.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Ação</span></b>
								</td>
								<td width="100" valign="top" style="border-left: medium none; border-right: 1.0pt solid black; border-top: 1.0pt solid black; border-bottom: 1.0pt solid black; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm">
									<p class="MsoNormal" align="center" style="text-align:center"><b>
									<span lang="EN-GB" style="font-size:11.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Status</span></b>
								</td>
							</tr>

					';
					while($row_p = mysqli_fetch_assoc($resPend)){
						$html .= '<tr>
									<td style="border-left: medium none; border-right: 1.0pt solid black;  border-left: 1.0pt solid black;border-top: medium none; border-bottom: 1.0pt solid black; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm; background: white">
										<p class="MsoNormal" align="center" style="text-align:center">
											<span lang="EN-GB" style="font-size: 9.0pt; font-family: Verdana,sans-serif; ">
											<center>';
											$paciente = formatar_texto_bd($row_p['paciente']);
											if($paciente==""){$paciente = "N/A";}
											$html .= $paciente.'</center>
											</span>
										</p>
									</td>
									<td style="border-left: 1.0pt solid black; border-right: 1.0pt solid black; border-top: medium none; border-bottom: 1.0pt solid black; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm; background: white">
										<p class="MsoNormal" align="center" style="text-align:center">
											<span lang="EN-GB" style="font-size: 9.0pt; font-family: Verdana,sans-serif; ">
											'.formatar_texto_bd($row_p['tipo']).'
											</span>
										</p>
									</td>
									<td style="border-left: 1.0pt solid black; border-right: 1.0pt solid black; border-top: medium none; border-bottom: 1.0pt solid black; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm; background: white">
										<p class="MsoNormal" align="center" style="text-align:center">
											<span lang="EN-GB" style="font-size: 9.0pt; font-family: Verdana,sans-serif; ">
											'.nl2br(formatar_texto_bd($row_p['achado'])).'
											</span>
										</p>
									</td>
									<td style="border-left: medium none; border-right: 1.0pt solid black; border-top: medium none; border-bottom: 1.0pt solid black; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm; background: white">
										<p class="MsoNormal" align="center" style="text-align:center">
											<span lang="EN-GB" style="font-size: 9.0pt; font-family: Verdana,sans-serif; ">
											'.nl2br(formatar_texto_bd($row_p['acao'])).'
											</span>
										</p>
									</td>
									<td valign="center" style="border-left: medium none; border-right: 1.0pt solid black; border-top: medium none; border-bottom: 1.0pt solid black; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm; background: white">
										<p class="MsoNormal" style="text-align:justify;">
											<span style="font-size: 9.0pt;  font-family: Verdana,sans-serif;">
											'.formatar_texto_bd($row_p['status']).'
											</span>
										</p>
									</td>
								</tr>';

					}
					$html .= "</table>";
					$mpdf->WriteHTML($html);
					$html = "";
				}
			}
		}else{
			//Pegar campo a campo
			$query = "SELECT cfg_visitas_campos.tipo,
							crf_agenda_campos.id,
							crf_agenda_campos.valor,
							cfg_visitas_campos.legenda,
							crf_agenda_campos.comentario,
							cfg_visitas_campos.numeracao,
							cfg_visitas_campos.nome,
							cfg_visitas_campos.texto,
							cfg_visitas_campos.texto_en,
							cfg_visitas_campos.texto_es,
							cfg_visitas_campos.ordem,
							cfg_visitas_campos.selecao_valores
					from crf_agenda_campos
						inner join cfg_visitas_campos on crf_agenda_campos.campo_id = cfg_visitas_campos.id
						where crf_agenda_campos.form_id = ".$row_form['id']."
							and cfg_visitas_campos.imprime in ('ON', 'on')
								ORDER BY cfg_visitas_campos.ordem
			";
			//echo $query;
			$res_campo = $conexao->pesquisa($query);


			$mpdf->WriteHTML($html);
			$html = "";
			while($row_campo = mysqli_fetch_assoc($res_campo)){
				if($row_campo['tipo']=="divisao"){
					$html = "
						<table autosize='1'
							class='MsoNormalTable'
							border='1'
							cellspacing='0'
							cellpadding='0'
							width='100%'
							style='width: 100.0%; border-collapse: collapse; border: medium none'>
							<tr>
								<td width='23%' class='ptTabelaAzul'>
									<span class='texto_formulario_divisao'>&nbsp;".trim(mb_strtoupper(formatar_texto_bd($row_campo['texto'])))."</span>";
					$html .= "
								</td>
							</tr>
						</table>
					";
					$mpdf->WriteHTML($html);
				}else{
					$countField++;
					if(($row_campo['tipo']!="json")&&($row_campo['tipo']!="json_vertical")){
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
										$largura_1 = '70% !important';
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
											$largura2 = intval(30 / $qtd_col);
										}else{
											$largura2 = intval(30);
										}
									}else{
										$largura_1 = '30% !important';
										$largura_2 = '70% !important';
									}
								}

								switch($row_campo['tipo']){
									case "esp_tratamento":

										//Descobrir o tipo de visita inicial do paciente
										$query = "select id, tipo_monitoria from agenda where projeto = " . $_SESSION['projeto'] . " and tipo_monitoria in ('Visita Inicial', 'Inicial') order by id desc limit 1";
										$res = $conexao->pesquisa($query);
										if(mysqli_num_rows($res)>0){
											$row = mysqli_fetch_assoc($res);
											$tipo_visita = $row['tipo_monitoria'];
											$id_agenda   = $row['id'];
										}else{
											$tipo_visita = "N/A";
										}
										if(strtoupper($tipo_visita) == "VISITA INICIAL"){	//Aqui é a visita ANTIGA, com UM COMPARADOR e UM TESTE, campo fixo
											if($varAgenda->projeto!=441){
												$query       = "select id from crf_agenda_forms where agendamento = " . $id_agenda . " and form_id = 111";
												$res_agendaESP = $conexao->pesquisa($query);
												if(mysqli_num_rows($res_agenda)>0){
													$row_agendaESP = mysqli_fetch_assoc($res_agendaESP);
													$id_visita = $row_agendaESP['id'];
												}
												$varIniNomeGenericoRef        = getValorCampoNovo($id_visita, 3330);
												$varIniNomeGenericoTeste      = getValorCampoNovo($id_visita, 3323);
												$varIniNomeComercialRef       = getValorCampoNovo($id_visita, 3331);
												$varIniNomeComercialTeste     = getValorCampoNovo($id_visita, 3324);
												$varIniFabricanteRef          = getValorCampoNovo($id_visita, 3329);
												$varIniFabricanteTeste        = getValorCampoNovo($id_visita, 3322);
												$varIniFormaFarmaceuticaRef   = getValorCampoNovo($id_visita, 3333);
												$varIniFormaFarmaceuticaTeste = getValorCampoNovo($id_visita, 3326);
												$varIniConcentracaoRef        = getValorCampoNovo($id_visita, 3332);
												$varIniConcentracaoTeste      = getValorCampoNovo($id_visita, 3325);
												$varIniCondArmazenamentoRef   = getValorCampoNovo($id_visita, 3334);
												$varIniCondArmazenamentoTeste = getValorCampoNovo($id_visita, 3327);
												$varIniLoteRef                = getValorCampoNovo($id_visita, 3282);
												$varIniLoteTeste              = getValorCampoNovo($id_visita, 3273);
												$varIniDataFabRef             = getValorCampoNovo($id_visita, 3283);
												$varIniDataFabTeste           = getValorCampoNovo($id_visita, 3274);
												$varIniPrazoValidadeRef       = getValorCampoNovo($id_visita, 3284);
												$varIniPrazoValidadeTeste     = getValorCampoNovo($id_visita, 3275);




												$html = "<br>
													<table autosize='1'
														class='MsoNormalTable'
														border='0'
														cellspacing='0'
														cellpadding='0'
														width='100%'
														style='width: 100.0%; border-collapse: collapse; border: medium none'>
														<tr>
															<td width='30%' class='ptTabelaBranco'><td>
															<td width='70%' class='ptTabelaAzulBorda'>
																<span class='texto_formulario_divisao' style='text-align: center !important;'><center>&nbsp;".trim(mb_strtoupper(formatar_texto_bd($row_campo['texto'])))."</center></span>
															</td>
														</tr>
													</table>
												";
												$mpdf->WriteHTML($html);
												$html = "
													<table autosize='1'
														class='MsoNormalTable'
														border='0'
														cellspacing='0'
														cellpadding='0'
														width='100%'
														style='width: 100.0%; border-collapse: collapse; border: medium none'>
														<tr>
															<td width='30%' class='ptTabelaBranco'><td>
															<td width='35%' class='ptTabelaAzulBorda'>
																<span class='texto_formulario_divisao' style='text-align: center !important;'><center>&nbsp;MEDICAMENTO TESTE</center></span>
															</td>
															<td width='35%' class='ptTabelaAzulBorda'>
																<span class='texto_formulario_divisao' style='text-align: center !important;'><center>&nbsp;MEDICAMENTO REFERÊNCIA</center></span>
															</td>
														</tr>
													</table>
												";
												$mpdf->WriteHTML($html);
												/*Nome Genérico (Princípio Ativo)*/
												$html = "
													<table autosize='1'
														class='MsoNormalTable'
														border='0'
														cellspacing='0'
														cellpadding='0'
														width='100%'
														style='width: 100.0%; border-collapse: collapse; border: medium none'>
														<tr>
															<td width='30%' class='ptTabelaAzulBorda'><center>Nome Genérico<br>(Princípio Ativo)</center><td>
															<td width='35%' class='ptTabelaBrancoBorda'>
																<span class='justificado' style='text-align: center !important;'><center>".$varIniNomeGenericoRef."</center></span>
															</td>
															<td width='35%' class='ptTabelaBrancoBorda'>
																<span class='justificado' style='text-align: center !important;'><center>".$varIniNomeGenericoTeste."</center></span>
															</td>
														</tr>
													</table>
												";
												$mpdf->WriteHTML($html);
												/*Nome Comercial*/
												$html = "
													<table autosize='1'
														class='MsoNormalTable'
														border='0'
														cellspacing='0'
														cellpadding='0'
														width='100%'
														style='width: 100.0%; border-collapse: collapse; border: medium none'>
														<tr>
															<td width='30%' class='ptTabelaAzulBorda'><center>Nome Comercial</center><td>
															<td width='35%' class='ptTabelaBrancoBorda'>
																<span class='justificado' style='text-align: center !important;'><center>".$varIniNomeComercialRef."</center></span>
															</td>
															<td width='35%' class='ptTabelaBrancoBorda'>
																<span class='justificado' style='text-align: center !important;'><center>".$varIniNomeComercialTeste."</center></span>
															</td>
														</tr>
													</table>
												";
												$mpdf->WriteHTML($html);
												/*Fabricante*/
												$html = "
													<table autosize='1'
														class='MsoNormalTable'
														border='0'
														cellspacing='0'
														cellpadding='0'
														width='100%'
														style='width: 100.0%; border-collapse: collapse; border: medium none'>
														<tr>
															<td width='30%' class='ptTabelaAzulBorda'><center>Fabricante</center><td>
															<td width='35%' class='ptTabelaBrancoBorda'>
																<span class='justificado' style='text-align: center !important;'><center>".$varIniFabricanteRef."</center></span>
															</td>
															<td width='35%' class='ptTabelaBrancoBorda'>
																<span class='justificado' style='text-align: center !important;'><center>".$varIniFabricanteTeste."</center></span>
															</td>
														</tr>
													</table>
												";
												$mpdf->WriteHTML($html);
												/*Forma Farmacêutica*/
												$html = "
													<table autosize='1'
														class='MsoNormalTable'
														border='0'
														cellspacing='0'
														cellpadding='0'
														width='100%'
														style='width: 100.0%; border-collapse: collapse; border: medium none'>
														<tr>
															<td width='30%' class='ptTabelaAzulBorda'><center>Forma Farmacêutica</center><td>
															<td width='35%' class='ptTabelaBrancoBorda'>
																<span class='justificado' style='text-align: center !important;'><center>".$varIniFormaFarmaceuticaRef."</center></span>
															</td>
															<td width='35%' class='ptTabelaBrancoBorda'>
																<span class='justificado' style='text-align: center !important;'><center>".$varIniFormaFarmaceuticaTeste."</center></span>
															</td>
														</tr>
													</table>
												";
												$mpdf->WriteHTML($html);
												/*Concentração*/
												$html = "
													<table autosize='1'
														class='MsoNormalTable'
														border='0'
														cellspacing='0'
														cellpadding='0'
														width='100%'
														style='width: 100.0%; border-collapse: collapse; border: medium none'>
														<tr>
															<td width='30%' class='ptTabelaAzulBorda'><center>Concentração</center><td>
															<td width='35%' class='ptTabelaBrancoBorda'>
																<span class='justificado' style='text-align: center !important;'><center>".$varIniConcentracaoRef."</center></span>
															</td>
															<td width='35%' class='ptTabelaBrancoBorda'>
																<span class='justificado' style='text-align: center !important;'><center>".$varIniConcentracaoTeste."</center></span>
															</td>
														</tr>
													</table>
												";
												$mpdf->WriteHTML($html);
												/*Condições de Armazenamento*/
												$html = "
													<table autosize='1'
														class='MsoNormalTable'
														border='0'
														cellspacing='0'
														cellpadding='0'
														width='100%'
														style='width: 100.0%; border-collapse: collapse; border: medium none'>
														<tr>
															<td width='30%' class='ptTabelaAzulBorda'><center>Condições de Armazenamento</center><td>
															<td width='35%' class='ptTabelaBrancoBorda'>
																<span class='justificado' style='text-align: center !important;'><center>".$varIniCondArmazenamentoRef."</center></span>
															</td>
															<td width='35%' class='ptTabelaBrancoBorda'>
																<span class='justificado' style='text-align: center !important;'><center>".$varIniCondArmazenamentoTeste."</center></span>
															</td>
														</tr>
													</table>
												";
												$mpdf->WriteHTML($html);
												/*Número do Lote*/
												$html = "
													<table autosize='1'
														class='MsoNormalTable'
														border='0'
														cellspacing='0'
														cellpadding='0'
														width='100%'
														style='width: 100.0%; border-collapse: collapse; border: medium none'>
														<tr>
															<td width='30%' class='ptTabelaAzulBorda'><center>Número do Lote</center><td>
															<td width='35%' class='ptTabelaBrancoBorda'>
																<span class='justificado' style='text-align: center !important;'><center>".	$varIniLoteRef."</center></span>
															</td>
															<td width='35%' class='ptTabelaBrancoBorda'>
																<span class='justificado' style='text-align: center !important;'><center>".$varIniLoteTeste."</center></span>
															</td>
														</tr>
													</table>
												";
												$mpdf->WriteHTML($html);
												/*Data de fabricação*/
												$html = "
													<table autosize='1'
														class='MsoNormalTable'
														border='0'
														cellspacing='0'
														cellpadding='0'
														width='100%'
														style='width: 100.0%; border-collapse: collapse; border: medium none'>
														<tr>
															<td width='30%' class='ptTabelaAzulBorda'><center>Data de fabricação</center><td>
															<td width='35%' class='ptTabelaBrancoBorda'>
																<span class='justificado' style='text-align: center !important;'><center>".formata_utf8_decode($varIniDataFabRef)."</center></span>
															</td>
															<td width='35%' class='ptTabelaBrancoBorda'>
																<span class='justificado' style='text-align: center !important;'><center>".formata_utf8_decode($varIniDataFabTeste)."</center></span>
															</td>
														</tr>
													</table>
												";
												$mpdf->WriteHTML($html);
												/*Prazo de validade*/
												$html = "
													<table autosize='1'
														class='MsoNormalTable'
														border='0'
														cellspacing='0'
														cellpadding='0'
														width='100%'
														style='width: 100.0%; border-collapse: collapse; border: medium none'>
														<tr>
															<td width='30%' class='ptTabelaAzulBorda'><center>Prazo de validade</center><td>
															<td width='35%' class='ptTabelaBrancoBorda'>
																<span class='justificado' style='text-align: center !important;'><center>".formata_utf8_decode($varIniPrazoValidadeRef)."</center></span>
															</td>
															<td width='35%' class='ptTabelaBrancoBorda'>
																<span class='justificado' style='text-align: center !important;'><center>".formata_utf8_decode($varIniPrazoValidadeTeste)."</center></span>
															</td>
														</tr>
													</table>
												";
												$mpdf->WriteHTML($html);
												/*Caixa de Armazenamento*/
												$html = "
													<table autosize='1'
														class='MsoNormalTable'
														border='0'
														cellspacing='0'
														cellpadding='0'
														width='100%'
														style='width: 100.0%; border-collapse: collapse; border: medium none'>
														<tr>
															<td width='30%' class='ptTabelaAzulBorda'><center>Caixa de Armazenamento</center><td>
															<td width='35%' class='ptTabelaBrancoBorda'>
																<span class='justificado' style='text-align: center !important;'><center>".formata_utf8_decode($varIniCaixaRef)."</center></span>
															</td>
															<td width='35%' class='ptTabelaBrancoBorda'>
																<span class='justificado' style='text-align: center !important;'><center>".formata_utf8_decode($varIniCaixaTeste)."</center></span>
															</td>
														</tr>
													</table>
												";
												$mpdf->WriteHTML($html);
												/*Quantidade recebida pelo centro de pesquisa*/
												$html = "
													<table autosize='1'
														class='MsoNormalTable'
														border='0'
														cellspacing='0'
														cellpadding='0'
														width='100%'
														style='width: 100.0%; border-collapse: collapse; border: medium none'>
														<tr>
															<td width='30%' class='ptTabelaAzulBorda'><center>Quantidade recebida pelo centro de pesquisa</center><td>
															<td width='35%' class='ptTabelaBrancoBorda'>
																<span class='justificado' style='text-align: center !important;'><center>".formata_utf8_decode($varIniQuantidadeRef)."</center></span>
															</td>
															<td width='35%' class='ptTabelaBrancoBorda'>
																<span class='justificado' style='text-align: center !important;'><center>".formata_utf8_decode($varIniQuantidadeTeste)."</center></span>
															</td>
														</tr>
													</table>
												";
												$mpdf->WriteHTML($html);
												/*Quantidade utilizada pela farmácia matriz*/
												$html = "
													<table autosize='1'
														class='MsoNormalTable'
														border='0'
														cellspacing='0'
														cellpadding='0'
														width='100%'
														style='width: 100.0%; border-collapse: collapse; border: medium none'>
														<tr>
															<td width='30%' class='ptTabelaAzulBorda'><center>Quantidade utilizada pela farmácia matriz</center><td>
															<td width='35%' class='ptTabelaBrancoBorda'>
																<span class='justificado' style='text-align: center !important;'><center>".formata_utf8_decode($varIniQuantidadeUtilizadaRef)."</center></span>
															</td>
															<td width='35%' class='ptTabelaBrancoBorda'>
																<span class='justificado' style='text-align: center !important;'><center>".formata_utf8_decode($varIniQuantidadeUtilizadaTeste)."</center></span>
															</td>
														</tr>
													</table>
												";
												$mpdf->WriteHTML($html);
												/*Quantidade Total Enviada para a Etapa Clínica*/
												$html = "
													<table autosize='1'
														class='MsoNormalTable'
														border='0'
														cellspacing='0'
														cellpadding='0'
														width='100%'
														style='width: 100.0%; border-collapse: collapse; border: medium none'>
														<tr>
															<td width='30%' class='ptTabelaAzulBorda'><center>Quantidade Total Enviada para a Etapa Clínica</center><td>
															<td width='35%' class='ptTabelaBrancoBorda'>
																<span class='justificado' style='text-align: center !important;'><center>".formata_utf8_decode($varIniQuantidadeTotalEnviadaParaEtapaClinicaRef)."</center></span>
															</td>
															<td width='35%' class='ptTabelaBrancoBorda'>
																<span class='justificado' style='text-align: center !important;'><center>".formata_utf8_decode($varIniQuantidadeTotalEnviadaParaEtapaClinicaTeste)."</center></span>
															</td>
														</tr>
													</table>
												";
												$mpdf->WriteHTML($html);
												/*Quantidade consumida pelos Participantes de Pesquisa*/
												$html = "
													<table autosize='1'
														class='MsoNormalTable'
														border='0'
														cellspacing='0'
														cellpadding='0'
														width='100%'
														style='width: 100.0%; border-collapse: collapse; border: medium none'>
														<tr>
															<td width='30%' class='ptTabelaAzulBorda'><center>Quantidade consumida pelos Participantes de Pesquisa</center><td>
															<td width='35%' class='ptTabelaBrancoBorda'>
																<span class='justificado' style='text-align: center !important;'><center>".formata_utf8_decode($varIniQuantidadeConsumidaPelosParticipantesDePesquisaRef)."</center></span>
															</td>
															<td width='35%' class='ptTabelaBrancoBorda'>
																<span class='justificado' style='text-align: center !important;'><center>".formata_utf8_decode($varIniQuantidadeConsumidaPelosParticipantesDePesquisaTeste)."</center></span>
															</td>
														</tr>
													</table>
												";
												$mpdf->WriteHTML($html);
												/*Perdas*/
												$html = "
													<table autosize='1'
														class='MsoNormalTable'
														border='0'
														cellspacing='0'
														cellpadding='0'
														width='100%'
														style='width: 100.0%; border-collapse: collapse; border: medium none'>
														<tr>
															<td width='30%' class='ptTabelaAzulBorda'><center>Perdas</center><td>
															<td width='35%' class='ptTabelaBrancoBorda'>
																<span class='justificado' style='text-align: center !important;'><center>".formata_utf8_decode($varIniPerdasRef)."</center></span>
															</td>
															<td width='35%' class='ptTabelaBrancoBorda'>
																<span class='justificado' style='text-align: center !important;'><center>".formata_utf8_decode($varIniPerdasTeste)."</center></span>
															</td>
														</tr>
													</table>
												";
												$mpdf->WriteHTML($html);
												/*Quantidade devolvida para a Farmácia Central*/
												$html = "
													<table autosize='1'
														class='MsoNormalTable'
														border='0'
														cellspacing='0'
														cellpadding='0'
														width='100%'
														style='width: 100.0%; border-collapse: collapse; border: medium none'>
														<tr>
															<td width='30%' class='ptTabelaAzulBorda'><center>Quantidade devolvida para a Farmácia Central</center><td>
															<td width='35%' class='ptTabelaBrancoBorda'>
																<span class='justificado' style='text-align: center !important;'><center>".$varIniQuantidadeDevolvidaParaAFarmaciaCentralRef."</center></span>
															</td>
															<td width='35%' class='ptTabelaBrancoBorda'>
																<span class='justificado' style='text-align: center !important;'><center>".$varIniQuantidadeDevolvidaParaAFarmaciaCentralTeste."</center></span>
															</td>
														</tr>
													</table>
												";
												$mpdf->WriteHTML($html);
												/*Estoque final*/
												$html = "
													<table autosize='1'
														class='MsoNormalTable'
														border='0'
														cellspacing='0'
														cellpadding='0'
														width='100%'
														style='width: 100.0%; border-collapse: collapse; border: medium none'>
														<tr>
															<td width='30%' class='ptTabelaAzulBorda'><center>Estoque final</center><td>
															<td width='35%' class='ptTabelaBrancoBorda'>
																<span class='justificado' style='text-align: center !important;'><center>".$varIniEstoqueFinalRef."</center></span>
															</td>
															<td width='35%' class='ptTabelaBrancoBorda'>
																<span class='justificado' style='text-align: center !important;'><center>".$varIniEstoqueFinalTeste."</center></span>
															</td>
														</tr>
													</table>
												";
												$mpdf->WriteHTML($html);
												$mpdf->WriteHTML("<br>");
											}else{
												$query       = "select id from crf_agenda_forms where agendamento = " . $id_agenda . " and form_id = 111";
												$res_agendaESP = $conexao->pesquisa($query);
												if(mysqli_num_rows($res_agenda)>0){
													$row_agendaESP = mysqli_fetch_assoc($res_agendaESP);
													$id_visita = $row_agendaESP['id'];
												}
												$varIniNomeGenericoRef        = getValorCampoNovo($id_visita, 3330);
												$varIniNomeGenericoTeste      = getValorCampoNovo($id_visita, 3323);
												$varIniNomeComercialRef       = getValorCampoNovo($id_visita, 3331);
												$varIniNomeComercialTeste     = getValorCampoNovo($id_visita, 3324);
												$varIniFabricanteRef          = getValorCampoNovo($id_visita, 3329);
												$varIniFabricanteTeste        = getValorCampoNovo($id_visita, 3322);
												$varIniFormaFarmaceuticaRef   = getValorCampoNovo($id_visita, 3333);
												$varIniFormaFarmaceuticaTeste = getValorCampoNovo($id_visita, 3326);
												$varIniConcentracaoRef        = getValorCampoNovo($id_visita, 3332);
												$varIniConcentracaoTeste      = getValorCampoNovo($id_visita, 3325);
												$varIniCondArmazenamentoRef   = getValorCampoNovo($id_visita, 3334);
												$varIniCondArmazenamentoTeste = getValorCampoNovo($id_visita, 3327);
												$varIniLoteRef                = getValorCampoNovo($id_visita, 3282);
												$varIniLoteTeste              = getValorCampoNovo($id_visita, 3273);
												$varIniDataFabRef             = getValorCampoNovo($id_visita, 3283);
												$varIniDataFabTeste           = getValorCampoNovo($id_visita, 3274);
												$varIniPrazoValidadeRef       = getValorCampoNovo($id_visita, 3284);
												$varIniPrazoValidadeTeste     = getValorCampoNovo($id_visita, 3275);




												$html = "<br>
													<table autosize='1'
														class='MsoNormalTable'
														border='0'
														cellspacing='0'
														cellpadding='0'
														width='100%'
														style='width: 100.0%; border-collapse: collapse; border: medium none'>
														<tr>
															<td width='30%' class='ptTabelaBranco'><td>
															<td width='70%' class='ptTabelaAzulBorda'>
																<span class='texto_formulario_divisao' style='text-align: center !important;'><center>&nbsp;".trim(mb_strtoupper(formatar_texto_bd($row_campo['texto'])))."</center></span>
															</td>
														</tr>
													</table>
												";
												$mpdf->WriteHTML($html);
												$html = "
													<table autosize='1'
														class='MsoNormalTable'
														border='0'
														cellspacing='0'
														cellpadding='0'
														width='100%'
														style='width: 100.0%; border-collapse: collapse; border: medium none'>
														<tr>
															<td width='30%' class='ptTabelaBranco'><td>
															<td width='70%' class='ptTabelaAzulBorda'>
																<span class='texto_formulario_divisao' style='text-align: center !important;'><center>&nbsp;MEDICAMENTO TESTE</center></span>
															</td>
														</tr>
													</table>
												";
												$mpdf->WriteHTML($html);
												/*Nome Genérico (Princípio Ativo)*/
												$html = "
													<table autosize='1'
														class='MsoNormalTable'
														border='0'
														cellspacing='0'
														cellpadding='0'
														width='100%'
														style='width: 100.0%; border-collapse: collapse; border: medium none'>
														<tr>
															<td width='30%' class='ptTabelaAzulBorda'><center>Nome Genérico<br>(Princípio Ativo)</center><td>
															<td width='70%' class='ptTabelaBrancoBorda'>
																<span class='justificado' style='text-align: center !important;'><center>".$varIniNomeGenericoRef."</center></span>
															</td>
														</tr>
													</table>
												";
												$mpdf->WriteHTML($html);
												/*Nome Comercial*/
												$html = "
													<table autosize='1'
														class='MsoNormalTable'
														border='0'
														cellspacing='0'
														cellpadding='0'
														width='100%'
														style='width: 100.0%; border-collapse: collapse; border: medium none'>
														<tr>
															<td width='30%' class='ptTabelaAzulBorda'><center>Nome Comercial</center><td>
															<td width='70%' class='ptTabelaBrancoBorda'>
																<span class='justificado' style='text-align: center !important;'><center>".$varIniNomeComercialRef."</center></span>
															</td>
														</tr>
													</table>
												";
												$mpdf->WriteHTML($html);
												/*Fabricante*/
												$html = "
													<table autosize='1'
														class='MsoNormalTable'
														border='0'
														cellspacing='0'
														cellpadding='0'
														width='100%'
														style='width: 100.0%; border-collapse: collapse; border: medium none'>
														<tr>
															<td width='30%' class='ptTabelaAzulBorda'><center>Fabricante</center><td>
															<td width='70%' class='ptTabelaBrancoBorda'>
																<span class='justificado' style='text-align: center !important;'><center>".$varIniFabricanteRef."</center></span>
															</td>
														</tr>
													</table>
												";
												$mpdf->WriteHTML($html);
												/*Forma Farmacêutica*/
												$html = "
													<table autosize='1'
														class='MsoNormalTable'
														border='0'
														cellspacing='0'
														cellpadding='0'
														width='100%'
														style='width: 100.0%; border-collapse: collapse; border: medium none'>
														<tr>
															<td width='30%' class='ptTabelaAzulBorda'><center>Forma Farmacêutica</center><td>
															<td width='70%' class='ptTabelaBrancoBorda'>
																<span class='justificado' style='text-align: center !important;'><center>".$varIniFormaFarmaceuticaRef."</center></span>
															</td>
														</tr>
													</table>
												";
												$mpdf->WriteHTML($html);
												/*Concentração*/
												$html = "
													<table autosize='1'
														class='MsoNormalTable'
														border='0'
														cellspacing='0'
														cellpadding='0'
														width='100%'
														style='width: 100.0%; border-collapse: collapse; border: medium none'>
														<tr>
															<td width='30%' class='ptTabelaAzulBorda'><center>Concentração</center><td>
															<td width='70%' class='ptTabelaBrancoBorda'>
																<span class='justificado' style='text-align: center !important;'><center>".$varIniConcentracaoRef."</center></span>
															</td>
														</tr>
													</table>
												";
												$mpdf->WriteHTML($html);
												/*Condições de Armazenamento*/
												$html = "
													<table autosize='1'
														class='MsoNormalTable'
														border='0'
														cellspacing='0'
														cellpadding='0'
														width='100%'
														style='width: 100.0%; border-collapse: collapse; border: medium none'>
														<tr>
															<td width='30%' class='ptTabelaAzulBorda'><center>Condições de Armazenamento</center><td>
															<td width='70%' class='ptTabelaBrancoBorda'>
																<span class='justificado' style='text-align: center !important;'><center>".$varIniCondArmazenamentoRef."</center></span>
															</td>
														</tr>
													</table>
												";
												$mpdf->WriteHTML($html);
												/*Número do Lote*/
												$html = "
													<table autosize='1'
														class='MsoNormalTable'
														border='0'
														cellspacing='0'
														cellpadding='0'
														width='100%'
														style='width: 100.0%; border-collapse: collapse; border: medium none'>
														<tr>
															<td width='30%' class='ptTabelaAzulBorda'><center>Número do Lote</center><td>
															<td width='70%' class='ptTabelaBrancoBorda'>
																<span class='justificado' style='text-align: center !important;'><center>".	$varIniLoteRef."</center></span>
															</td>
														</tr>
													</table>
												";
												$mpdf->WriteHTML($html);
												/*Data de fabricação*/
												$html = "
													<table autosize='1'
														class='MsoNormalTable'
														border='0'
														cellspacing='0'
														cellpadding='0'
														width='100%'
														style='width: 100.0%; border-collapse: collapse; border: medium none'>
														<tr>
															<td width='30%' class='ptTabelaAzulBorda'><center>Data de fabricação</center><td>
															<td width='70%' class='ptTabelaBrancoBorda'>
																<span class='justificado' style='text-align: center !important;'><center>".formata_utf8_decode($varIniDataFabRef)."</center></span>
															</td>
														</tr>
													</table>
												";
												$mpdf->WriteHTML($html);
												/*Prazo de validade*/
												$html = "
													<table autosize='1'
														class='MsoNormalTable'
														border='0'
														cellspacing='0'
														cellpadding='0'
														width='100%'
														style='width: 100.0%; border-collapse: collapse; border: medium none'>
														<tr>
															<td width='30%' class='ptTabelaAzulBorda'><center>Prazo de validade</center><td>
															<td width='70%' class='ptTabelaBrancoBorda'>
																<span class='justificado' style='text-align: center !important;'><center>".formata_utf8_decode($varIniPrazoValidadeRef)."</center></span>
															</td>
														</tr>
													</table>
												";
												$mpdf->WriteHTML($html);
												/*Caixa de Armazenamento*/
												$html = "
													<table autosize='1'
														class='MsoNormalTable'
														border='0'
														cellspacing='0'
														cellpadding='0'
														width='100%'
														style='width: 100.0%; border-collapse: collapse; border: medium none'>
														<tr>
															<td width='30%' class='ptTabelaAzulBorda'><center>Caixa de Armazenamento</center><td>
															<td width='70%' class='ptTabelaBrancoBorda'>
																<span class='justificado' style='text-align: center !important;'><center>".formata_utf8_decode($varIniCaixaTeste)."</center></span>
															</td>
														</tr>
													</table>
												";
												$mpdf->WriteHTML($html);
												/*Quantidade recebida pelo centro de pesquisa*/
												$html = "
													<table autosize='1'
														class='MsoNormalTable'
														border='0'
														cellspacing='0'
														cellpadding='0'
														width='100%'
														style='width: 100.0%; border-collapse: collapse; border: medium none'>
														<tr>
															<td width='30%' class='ptTabelaAzulBorda'><center>Quantidade recebida pelo centro de pesquisa</center><td>
															<td width='70%' class='ptTabelaBrancoBorda'>
																<span class='justificado' style='text-align: center !important;'><center>".formata_utf8_decode($varIniQuantidadeTeste)."</center></span>
															</td>
														</tr>
													</table>
												";
												$mpdf->WriteHTML($html);
												/*Quantidade utilizada pela farmácia matriz*/
												$html = "
													<table autosize='1'
														class='MsoNormalTable'
														border='0'
														cellspacing='0'
														cellpadding='0'
														width='100%'
														style='width: 100.0%; border-collapse: collapse; border: medium none'>
														<tr>
															<td width='30%' class='ptTabelaAzulBorda'><center>Quantidade utilizada pela farmácia matriz</center><td>
															<td width='70%' class='ptTabelaBrancoBorda'>
																<span class='justificado' style='text-align: center !important;'><center>".formata_utf8_decode($varIniQuantidadeUtilizadaTeste)."</center></span>
															</td>
														</tr>
													</table>
												";
												$mpdf->WriteHTML($html);
												/*Quantidade Total Enviada para a Etapa Clínica*/
												$html = "
													<table autosize='1'
														class='MsoNormalTable'
														border='0'
														cellspacing='0'
														cellpadding='0'
														width='100%'
														style='width: 100.0%; border-collapse: collapse; border: medium none'>
														<tr>
															<td width='30%' class='ptTabelaAzulBorda'><center>Quantidade Total Enviada para a Etapa Clínica</center><td>
															<td width='70%' class='ptTabelaBrancoBorda'>
																<span class='justificado' style='text-align: center !important;'><center>".formata_utf8_decode($varIniQuantidadeTotalEnviadaParaEtapaClinicaTeste)."</center></span>
															</td>
														</tr>
													</table>
												";
												$mpdf->WriteHTML($html);
												/*Quantidade consumida pelos Participantes de Pesquisa*/
												$html = "
													<table autosize='1'
														class='MsoNormalTable'
														border='0'
														cellspacing='0'
														cellpadding='0'
														width='100%'
														style='width: 100.0%; border-collapse: collapse; border: medium none'>
														<tr>
															<td width='30%' class='ptTabelaAzulBorda'><center>Quantidade consumida pelos Participantes de Pesquisa</center><td>
															<td width='70%' class='ptTabelaBrancoBorda'>
																<span class='justificado' style='text-align: center !important;'><center>".formata_utf8_decode($varIniQuantidadeConsumidaPelosParticipantesDePesquisaTeste)."</center></span>
															</td>
														</tr>
													</table>
												";
												$mpdf->WriteHTML($html);
												/*Perdas*/
												$html = "
													<table autosize='1'
														class='MsoNormalTable'
														border='0'
														cellspacing='0'
														cellpadding='0'
														width='100%'
														style='width: 100.0%; border-collapse: collapse; border: medium none'>
														<tr>
															<td width='30%' class='ptTabelaAzulBorda'><center>Perdas</center><td>
															<td width='70%' class='ptTabelaBrancoBorda'>
																<span class='justificado' style='text-align: center !important;'><center>".formata_utf8_decode($varIniPerdasTeste)."</center></span>
															</td>
														</tr>
													</table>
												";
												$mpdf->WriteHTML($html);
												/*Quantidade devolvida para a Farmácia Central*/
												$html = "
													<table autosize='1'
														class='MsoNormalTable'
														border='0'
														cellspacing='0'
														cellpadding='0'
														width='100%'
														style='width: 100.0%; border-collapse: collapse; border: medium none'>
														<tr>
															<td width='30%' class='ptTabelaAzulBorda'><center>Quantidade devolvida para a Farmácia Central</center><td>
															<td width='70%' class='ptTabelaBrancoBorda'>
																<span class='justificado' style='text-align: center !important;'><center>".$varIniQuantidadeDevolvidaParaAFarmaciaCentralTeste."</center></span>
															</td>
														</tr>
													</table>
												";
												$mpdf->WriteHTML($html);
												/*Estoque final*/
												$html = "
													<table autosize='1'
														class='MsoNormalTable'
														border='0'
														cellspacing='0'
														cellpadding='0'
														width='100%'
														style='width: 100.0%; border-collapse: collapse; border: medium none'>
														<tr>
															<td width='30%' class='ptTabelaAzulBorda'><center>Estoque final</center><td>
															<td width='70%' class='ptTabelaBrancoBorda'>
																<span class='justificado' style='text-align: center !important;'><center>".$varIniEstoqueFinalTeste."</center></span>
															</td>
														</tr>
													</table>
												";
												$mpdf->WriteHTML($html);
												$mpdf->WriteHTML("<br>");
											}
										}
										if(strtoupper($tipo_visita) == "INICIAL"){	//Aqui é a visita NOVA, campos variáveis
											if($varAgenda->projeto!=441){
												$query       = "select id from crf_agenda_forms where agendamento = " . $id_agenda . " and form_id = 180";
												$res_agenda = $conexao->pesquisa($query);
												if(mysqli_num_rows($res_agenda)>0){
													$row_agenda = mysqli_fetch_assoc($res_agenda);
													$id_visita = $row_agenda['id'];
												}
												$campoJsonINICIAL = getValorCampoNovo($id_visita, 3607);
												$query       = "select id from crf_agenda_forms where agendamento = " . $_GET['id'] . " and form_id = 54";
												$res_agenda = $conexao->pesquisa($query);
												if(mysqli_num_rows($res_agenda)>0){
													$row_agenda = mysqli_fetch_assoc($res_agenda);
													$id_visita = $row_agenda['id'];
												}
												$campoJsonFINAL = getValorCampoNovo($id_visita, 1475);


												$campoJsonINICIAL = explode($separadorRegistro, $campoJsonINICIAL);
												$campoJsonFINAL   = explode($separadorRegistro, $campoJsonFINAL);
											}else{

												$query       = "select id from crf_agenda_forms where agendamento = " . $id_agenda . " and form_id = 180";
												$res_agenda = $conexao->pesquisa($query);
												if(mysqli_num_rows($res_agenda)>0){
													$row_agenda = mysqli_fetch_assoc($res_agenda);
													$id_visita = $row_agenda['id'];
												}
												$campoJsonINICIAL = getValorCampoNovo($id_visita, 3607);
												$query       = "select id from crf_agenda_forms where agendamento = " . $_GET['id'] . " and form_id = 54";
												$res_agenda = $conexao->pesquisa($query);
												if(mysqli_num_rows($res_agenda)>0){
													$row_agenda = mysqli_fetch_assoc($res_agenda);
													$id_visita = $row_agenda['id'];
												}
												$campoJsonFINAL = getValorCampoNovo($id_visita, 1475);


												$campoJsonINICIAL = explode($separadorRegistro, $campoJsonINICIAL);
												$campoJsonFINAL   = explode($separadorRegistro, $campoJsonFINAL);

											}

											$html = "<br>
												<table autosize='1'
													class='MsoNormalTable'
													border='0'
													cellspacing='0'
													cellpadding='0'
													width='100%'
													style='width: 100.0%; border-collapse: collapse; border: medium none'>
													<tr>
														<td width='20%' class='ptTabelaBranco'><td>
														<td width='80%' class='ptTabelaAzulBorda'>
															<span class='texto_formulario_divisao' style='text-align: center !important;'><center>&nbsp;".trim(mb_strtoupper(formatar_texto_bd($row_campo['texto'])))."</center></span>
														</td>
													</tr>
												</table>
											";
											$mpdf->WriteHTML($html);
											$larguraMedicamentos = 80/(count($campoJsonINICIAL)-1);
											$html = "
												<table autosize='1'
													class='MsoNormalTable'
													border='0'
													cellspacing='0'
													cellpadding='0'
													width='100%'
													style='width: 100.0%; border-collapse: collapse; border: medium none'>
													<tr>
														<td width='20%' class='ptTabelaBranco'><td>
											";
												for($colMed=0;$colMed<count($campoJsonINICIAL)-1;$colMed++){
													$medicamentoAtual = $campoJsonINICIAL[$colMed];
													$medicamentoAtual = explode($separadorCampo, $medicamentoAtual);
													$html .= "
														<td width='".$larguraMedicamentos."%' class='ptTabelaAzulBorda'>
															<span class='texto_formulario_divisao' style='text-align: center !important;'><center>&nbsp;".$medicamentoAtual[0]."</center></span>
														</td>
													";
												}
											$html .= "		</tr>
												</table>
											";
											$mpdf->WriteHTML($html);


											/*Nome Genérico (Princípio Ativo)*/
											$html = "
												<table autosize='1'
													class='MsoNormalTable'
													border='0'
													cellspacing='0'
													cellpadding='0'
													width='100%'
													style='width: 100.0%; border-collapse: collapse; border: medium none'>
													<tr>
														<td width='20%' class='ptTabelaAzulBorda'><center>Nome Genérico<br>(Princípio Ativo)</center><td>
											";
												for($colMed=0;$colMed<count($campoJsonINICIAL)-1;$colMed++){
													$medicamentoAtual = $campoJsonINICIAL[$colMed];
													$medicamentoAtual = explode($separadorCampo, $medicamentoAtual);
													$html .= "
														<td width='".$larguraMedicamentos."%' class='ptTabelaBrancoBorda'>
															<span class='justificado' style='text-align: center !important;'><center>".$medicamentoAtual[2]."</center></span>
													";
												}
											$html .= "		</tr>
												</table>
											";
											$mpdf->WriteHTML($html);

											/*Nome Comercial*/
											$html = "
												<table autosize='1'
													class='MsoNormalTable'
													border='0'
													cellspacing='0'
													cellpadding='0'
													width='100%'
													style='width: 100.0%; border-collapse: collapse; border: medium none'>
													<tr>
														<td width='20%' class='ptTabelaAzulBorda'><center>Nome Comercial</center><td>
											";
												for($colMed=0;$colMed<count($campoJsonINICIAL)-1;$colMed++){
													$medicamentoAtual = $campoJsonINICIAL[$colMed];
													$medicamentoAtual = explode($separadorCampo, $medicamentoAtual);
													$html .= "
														<td width='".$larguraMedicamentos."%' class='ptTabelaBrancoBorda'>
															<span class='justificado' style='text-align: center !important;'><center>".$medicamentoAtual[3]."</center></span>
													";
												}
											$html .= "		</tr>
												</table>
											";
											$mpdf->WriteHTML($html);

											/*Fabricante*/
											$html = "
												<table autosize='1'
													class='MsoNormalTable'
													border='0'
													cellspacing='0'
													cellpadding='0'
													width='100%'
													style='width: 100.0%; border-collapse: collapse; border: medium none'>
													<tr>
														<td width='20%' class='ptTabelaAzulBorda'><center>Fabricante</center><td>
											";
												for($colMed=0;$colMed<count($campoJsonINICIAL)-1;$colMed++){
													$medicamentoAtual = $campoJsonINICIAL[$colMed];
													$medicamentoAtual = explode($separadorCampo, $medicamentoAtual);
													$html .= "
														<td width='".$larguraMedicamentos."%' class='ptTabelaBrancoBorda'>
															<span class='justificado' style='text-align: center !important;'><center>".$medicamentoAtual[1]."</center></span>
													";
												}
											$html .= "		</tr>
												</table>
											";
											$mpdf->WriteHTML($html);

											/*Forma Farmacêutica*/
											$html = "
												<table autosize='1'
													class='MsoNormalTable'
													border='0'
													cellspacing='0'
													cellpadding='0'
													width='100%'
													style='width: 100.0%; border-collapse: collapse; border: medium none'>
													<tr>
														<td width='20%' class='ptTabelaAzulBorda'><center>Forma Farmacêutica</center><td>
											";
												for($colMed=0;$colMed<count($campoJsonINICIAL)-1;$colMed++){
													$medicamentoAtual = $campoJsonINICIAL[$colMed];
													$medicamentoAtual = explode($separadorCampo, $medicamentoAtual);
													$html .= "
														<td width='".$larguraMedicamentos."%' class='ptTabelaBrancoBorda'>
															<span class='justificado' style='text-align: center !important;'><center>".$medicamentoAtual[5]."</center></span>
													";
												}
											$html .= "		</tr>
												</table>
											";
											$mpdf->WriteHTML($html);

											/*Concentração*/
											$html = "
												<table autosize='1'
													class='MsoNormalTable'
													border='0'
													cellspacing='0'
													cellpadding='0'
													width='100%'
													style='width: 100.0%; border-collapse: collapse; border: medium none'>
													<tr>
														<td width='20%' class='ptTabelaAzulBorda'><center>Concentração</center><td>
											";
												for($colMed=0;$colMed<count($campoJsonINICIAL)-1;$colMed++){
													$medicamentoAtual = $campoJsonINICIAL[$colMed];
													$medicamentoAtual = explode($separadorCampo, $medicamentoAtual);
													$html .= "
														<td width='".$larguraMedicamentos."%' class='ptTabelaBrancoBorda'>
															<span class='justificado' style='text-align: center !important;'><center>".$medicamentoAtual[4]."</center></span>
													";
												}
											$html .= "		</tr>
												</table>
											";
											$mpdf->WriteHTML($html);
											/*Condições de Armazenamento*/
											$html = "
												<table autosize='1'
													class='MsoNormalTable'
													border='0'
													cellspacing='0'
													cellpadding='0'
													width='100%'
													style='width: 100.0%; border-collapse: collapse; border: medium none'>
													<tr>
														<td width='20%' class='ptTabelaAzulBorda'><center>Condições de Armazenamento</center><td>
											";
												for($colMed=0;$colMed<count($campoJsonINICIAL)-1;$colMed++){
													$medicamentoAtual = $campoJsonINICIAL[$colMed];
													$medicamentoAtual = explode($separadorCampo, $medicamentoAtual);
													$html .= "
														<td width='".$larguraMedicamentos."%' class='ptTabelaBrancoBorda'>
															<span class='justificado' style='text-align: center !important;'><center>".$medicamentoAtual[6]."</center></span>
													";
												}
											$html .= "		</tr>
												</table>
											";
											$mpdf->WriteHTML($html);

											/*Número do Lote*/
											$html = "
												<table autosize='1'
													class='MsoNormalTable'
													border='0'
													cellspacing='0'
													cellpadding='0'
													width='100%'
													style='width: 100.0%; border-collapse: collapse; border: medium none'>
													<tr>
														<td width='20%' class='ptTabelaAzulBorda'><center>Número do Lote</center><td>
											";
												for($colMed=0;$colMed<count($campoJsonINICIAL)-1;$colMed++){
													$medicamentoAtual = $campoJsonINICIAL[$colMed];
													$medicamentoAtual = explode($separadorCampo, $medicamentoAtual);
													$html .= "
														<td width='".$larguraMedicamentos."%' class='ptTabelaBrancoBorda'>
															<span class='justificado' style='text-align: center !important;'><center>".$medicamentoAtual[10]."</center></span>
													";
												}
											$html .= "		</tr>
												</table>
											";
											$mpdf->WriteHTML($html);

											/*Data de Fabricação*/
											$html = "
												<table autosize='1'
													class='MsoNormalTable'
													border='0'
													cellspacing='0'
													cellpadding='0'
													width='100%'
													style='width: 100.0%; border-collapse: collapse; border: medium none'>
													<tr>
														<td width='20%' class='ptTabelaAzulBorda'><center>Data de Fabricação</center><td>
											";
												for($colMed=0;$colMed<count($campoJsonINICIAL)-1;$colMed++){
													$medicamentoAtual = $campoJsonINICIAL[$colMed];
													$medicamentoAtual = explode($separadorCampo, $medicamentoAtual);
													$html .= "
														<td width='".$larguraMedicamentos."%' class='ptTabelaBrancoBorda'>
															<span class='justificado' style='text-align: center !important;'><center>".$medicamentoAtual[11]."</center></span>
													";
												}
											$html .= "		</tr>
												</table>
											";
											$mpdf->WriteHTML($html);

											/*Prazo de Validade*/
											$html = "
												<table autosize='1'
													class='MsoNormalTable'
													border='0'
													cellspacing='0'
													cellpadding='0'
													width='100%'
													style='width: 100.0%; border-collapse: collapse; border: medium none'>
													<tr>
														<td width='20%' class='ptTabelaAzulBorda'><center>Prazo de Validade</center><td>
											";
												for($colMed=0;$colMed<count($campoJsonINICIAL)-1;$colMed++){
													$medicamentoAtual = $campoJsonINICIAL[$colMed];
													$medicamentoAtual = explode($separadorCampo, $medicamentoAtual);
													$html .= "
														<td width='".$larguraMedicamentos."%' class='ptTabelaBrancoBorda'>
															<span class='justificado' style='text-align: center !important;'><center>".$medicamentoAtual[12]."</center></span>
													";
												}
											$html .= "		</tr>
												</table>
											";
											$mpdf->WriteHTML($html);

											/*Quantidade Recebida pelo Centro*/
											$html = "
												<table autosize='1'
													class='MsoNormalTable'
													border='0'
													cellspacing='0'
													cellpadding='0'
													width='100%'
													style='width: 100.0%; border-collapse: collapse; border: medium none'>
													<tr>
														<td width='20%' class='ptTabelaAzulBorda'><center>Quantidade Recebida pelo Centro</center><td>
											";
												for($colMed=0;$colMed<count($campoJsonINICIAL)-1;$colMed++){
													$medicamentoAtual = $campoJsonINICIAL[$colMed];
													$medicamentoAtual = explode($separadorCampo, $medicamentoAtual);
													$html .= "
														<td width='".$larguraMedicamentos."%' class='ptTabelaBrancoBorda'>
															<span class='justificado' style='text-align: center !important;'><center>".$medicamentoAtual[14]."</center></span>
													";
												}
											$html .= "		</tr>
												</table>
											";
											$mpdf->WriteHTML($html);

											/*Quantidade Utilizada*/
											$html = "
												<table autosize='1'
													class='MsoNormalTable'
													border='0'
													cellspacing='0'
													cellpadding='0'
													width='100%'
													style='width: 100.0%; border-collapse: collapse; border: medium none'>
													<tr>
														<td width='20%' class='ptTabelaAzulBorda'><center>Quantidade utilizada pela farmácia matriz</center><td>
											";
												for($colMed=0;$colMed<count($campoJsonFINAL)-1;$colMed++){
													$medicamentoAtual = $campoJsonFINAL[$colMed];
													$medicamentoAtual = explode($separadorCampo, $medicamentoAtual);
													$html .= "
														<td width='".$larguraMedicamentos."%' class='ptTabelaBrancoBorda'>
															<span class='justificado' style='text-align: center !important;'><center>".$medicamentoAtual[2]."</center></span>
													";
												}
											$html .= "		</tr>
												</table>
											";
											$mpdf->WriteHTML($html);

											/*Quantidade consumuda pelos participantes de pesquisa*/
											$html = "
												<table autosize='1'
													class='MsoNormalTable'
													border='0'
													cellspacing='0'
													cellpadding='0'
													width='100%'
													style='width: 100.0%; border-collapse: collapse; border: medium none'>
													<tr>
														<td width='20%' class='ptTabelaAzulBorda'><center>Quantidade consumida pelos Participantes de Pesquisa</center><td>
											";
												for($colMed=0;$colMed<count($campoJsonFINAL)-1;$colMed++){
													$medicamentoAtual = $campoJsonFINAL[$colMed];
													$medicamentoAtual = explode($separadorCampo, $medicamentoAtual);
													$html .= "
														<td width='".$larguraMedicamentos."%' class='ptTabelaBrancoBorda'>
															<span class='justificado' style='text-align: center !important;'><center>".$medicamentoAtual[3]."</center></span>
													";
												}
											$html .= "		</tr>
												</table>
											";
											$mpdf->WriteHTML($html);

											/*Perdas*/
											$html = "
												<table autosize='1'
													class='MsoNormalTable'
													border='0'
													cellspacing='0'
													cellpadding='0'
													width='100%'
													style='width: 100.0%; border-collapse: collapse; border: medium none'>
													<tr>
														<td width='20%' class='ptTabelaAzulBorda'><center>Perdas</center><td>
											";
												for($colMed=0;$colMed<count($campoJsonFINAL)-1;$colMed++){
													$medicamentoAtual = $campoJsonFINAL[$colMed];
													$medicamentoAtual = explode($separadorCampo, $medicamentoAtual);
													$html .= "
														<td width='".$larguraMedicamentos."%' class='ptTabelaBrancoBorda'>
															<span class='justificado' style='text-align: center !important;'><center>".$medicamentoAtual[4]."</center></span>
													";
												}
											$html .= "		</tr>
												</table>
											";
											$mpdf->WriteHTML($html);

											/*Quantidade total enviada para a etapa Clínica*/
											$html = "
												<table autosize='1'
													class='MsoNormalTable'
													border='0'
													cellspacing='0'
													cellpadding='0'
													width='100%'
													style='width: 100.0%; border-collapse: collapse; border: medium none'>
													<tr>
														<td width='20%' class='ptTabelaAzulBorda'><center>Quantidade total enviada para a etapa Clínica</center><td>
											";
												for($colMed=0;$colMed<count($campoJsonFINAL)-1;$colMed++){
													$medicamentoAtual = $campoJsonFINAL[$colMed];
													$medicamentoAtual = explode($separadorCampo, $medicamentoAtual);
													$html .= "
														<td width='".$larguraMedicamentos."%' class='ptTabelaBrancoBorda'>
															<span class='justificado' style='text-align: center !important;'><center>".$medicamentoAtual[7]."</center></span>
													";
												}
											$html .= "		</tr>
												</table>
											";
											$mpdf->WriteHTML($html);

											/*Quantidade devolvida para a farmácia central*/
											$html = "
												<table autosize='1'
													class='MsoNormalTable'
													border='0'
													cellspacing='0'
													cellpadding='0'
													width='100%'
													style='width: 100.0%; border-collapse: collapse; border: medium none'>
													<tr>
														<td width='20%' class='ptTabelaAzulBorda'><center>Quantidade devolvida para a Farmácia Central</center><td>
											";
												for($colMed=0;$colMed<count($campoJsonFINAL)-1;$colMed++){
													$medicamentoAtual = $campoJsonFINAL[$colMed];
													$medicamentoAtual = explode($separadorCampo, $medicamentoAtual);
													$html .= "
														<td width='".$larguraMedicamentos."%' class='ptTabelaBrancoBorda'>
															<span class='justificado' style='text-align: center !important;'><center>".$medicamentoAtual[8]."</center></span>
													";
												}
											$html .= "		</tr>
												</table>
											";
											$mpdf->WriteHTML($html);

											/*Estoque final*/
											$html = "
												<table autosize='1'
													class='MsoNormalTable'
													border='0'
													cellspacing='0'
													cellpadding='0'
													width='100%'
													style='width: 100.0%; border-collapse: collapse; border: medium none'>
													<tr>
														<td width='20%' class='ptTabelaAzulBorda'><center>Estoque final</center><td>
											";
												for($colMed=0;$colMed<count($campoJsonFINAL)-1;$colMed++){
													$medicamentoAtual = $campoJsonFINAL[$colMed];
													$medicamentoAtual = explode($separadorCampo, $medicamentoAtual);
													$html .= "
														<td width='".$larguraMedicamentos."%' class='ptTabelaBrancoBorda'>
															<span class='justificado' style='text-align: center !important;'><center>".$medicamentoAtual[5]."</center></span>
													";
												}
											$html .= "		</tr>
												</table>
											";
											$mpdf->WriteHTML($html);









											$mpdf->WriteHTML("<br><br>");
										}

										//Pegar a última visita INICIAL do paciente

										continue 2;
									break;
								}

								if(($row_campo['tipo']!="caixa")&&($row_campo['tipo']!="esp_tratamento")	){
									$html = "
											<table autosize='1'
												class='MsoNormalTable'
												border='1'
												cellspacing='0'
												cellpadding='0'
												width='100%'
												style='
												important; width: 100.0%;
												border-collapse: collapse;
												border: medium none'>
												<tr>
													<td width='".$largura_1."' class='class_item'  valign='top'>
											";
											if($row_campo['ordem']!=999){
												$html .=	"<span class='texto_ordem'>".$countForm . "." . $countField.".&nbsp;&nbsp;</span>";
											}
											$html .="	<span class='texto'>".formatar_texto_bd($row_campo['texto'])."</span>";
											$html .= "
													</td>";
									if(($row_campo['tipo']=="texto")||($row_campo['tipo']=="numero")||($row_campo['tipo']=="data")||($row_campo['tipo']=="calculo")){
										$html .= "<td width='".$largura_2."' class='class_item justificado'><span class='texto_conteudo'>".formatar_texto_bd($row_campo['valor'])."</span>";
										$html .= "</center>";
									}
									if(($row_campo['tipo']=="esp_tratamento")){
										$html .= "<td width='".$largura_2."' class='class_item justificado'><span class='texto_conteudo'>".formatar_texto_bd($row_campo['valor'])."</span>";
										$html .= "</center>";
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
												if(trim($rowItems['valor'])==$temp){ $temp_selected = " selected "; }
												$html .= $tamanho."<p><img src='imagens/check.png' width='10'><span valign='middle' class='texto'>&nbsp;&nbsp;<b>".formatar_texto_bd($temp)."</b></span></p>";
												$temp="";
										   }
										}
										if(strlen($temp)>0){
												$temp=trim($temp);
												$temp_selected = "";
												if(trim($rowItems['valor'])==$temp){ $temp_selected = " selected "; }
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
										for($z=0;$z<strlen($opcoes);$z++){
										   if(substr($opcoes,$z,1)!="%"){$temp.=substr($opcoes,$z,1);}
										   else{
												$temp=trim($temp);
												$temp_selected = "";
												if(trim($rowItems['valor'])==$temp){ $temp_selected = " selected "; }
												$html .= $tamanho."<td width='".$largura_2."' class='class_item_coluna'>
														<p>
												";
														if($temp==$row_campo['valor']){
															$html .= "<img src='imagens/checked.png' width='13'>";
														}else{
															$html .= "<img src='imagens/check.png' width='13'>";
														}

												$html .="<br><span valign='middle' class='texto'>
														".formatar_texto_bd(str_replace(" - ", "<br>", $temp))."</p>";
														$html .= "</td>";
												$temp="";
										   }
										}
										if(strlen($temp)>0){
												$temp=trim($temp);
												$temp_selected = "";
												if(trim($rowItems['valor'])==$temp){ $temp_selected = " selected "; }
												$html .= $tamanho."<td width='".$largura_2."' class='class_item_coluna' valign='middle'><p valign='middle'>
															";
														if($temp==$row_campo['valor']){
															$html .= "<img src='imagens/checked.png' width='13'>";
														}else{
															$html .= "<img src='imagens/check.png' width='13'>";
														}
												$html .= "			<span valign='middle' class='texto'><br>".formatar_texto_bd(str_replace(" - ", "<br>", $temp))."</p>";
														$html .= "</td>";
												$temp="";
										}

										}
									$html .="			</td>
													</tr>
												</table>
									";
									$mpdf->WriteHTML($html);
								}else{
									$valorAserImpresso = formatar_texto_bd($row_campo['valor']);
									$paragrafos = explode("\n", $valorAserImpresso);
									if(count($paragrafos)<3){ // Aqui o texto é curto, mando normalmente
										$html = "<table autosize='1'
													class='MsoNormalTable'
													border='1'
													cellspacing='0'
													cellpadding='0'
													width='100%'
													style='
													important; width: 100.0%;
													border-collapse: collapse;
													border: medium none'>
												<tr>
													<td width='".$largura_1."' class='class_item'  valign='top'>
											";
											if($row_campo['ordem']!=999){
												$html .=	"<span class='texto_ordem'>".$countForm . "." . $countField.".&nbsp;&nbsp;</span>";
											}
											$html .="	<span class='texto'>".formatar_texto_bd($row_campo['texto'])."</span>";
											$html .= "
													</td>";
											$html .= "<td width='".$largura_2."' class='class_item justificado'>";
													//<span class='texto_conteudo justificado texto-longo'>".$valorAserImpresso."</span>
														$html .= "<p class='texto'>".$valorAserImpresso."</p>";
													$html .= "</td>";
										$html .= "</tr>
										</table>";
										$mpdf->WriteHTML($html);
									}else{	//Aqui tem mais de 3 paragrafos. Então vou quebrar por paragrafo
										$html = "<table autosize='1'
													class='MsoNormalTable'
													cellspacing='0'
													cellpadding='0'
													width='100%'
													style='
													important; width: 100.0%;
													border-collapse: collapse;
													'>";
												$indPar = 0;
												foreach($paragrafos as $paragrafo){
													if($indPar>0){ $html .= "<tr>";  $style_td_top_quebra = " border-top: none;";}else{  $style_td_top_quebra = " border-top: 1.0pt solid black;";}
													if($indPar==count($paragrafos)-1){
														$style_td_quebra = " border-bottom: 1.0pt solid black;"; }else{ $style_td_quebra = " border-bottom: none;"; }


													if($indPar==0){ //Primeira linha.
														$quebraTexto = $countForm . "." . $countField . "&nbsp;&nbsp;";
														$quebraCssTopo = " border-top: 1.0pt solid black;";
														$quebraCssBottomo = " border-bottom: 1.0pt solid black;";
														$quebraTitulo = '<span class="texto">'.formatar_texto_bd($row_campo['texto']).'</span>';
													}else{
														$quebraTexto = "";
														$quebraCssTopo = " border-top: none;";
														$quebraCssBottomo = " border-bottom: none;";
														$quebraTitulo = '';
													}



													$html .= " <tr>
																	<td width='".$largura_1."' class='class_item_quebra'  style='".$style_td_quebra." ".$quebraCssTopo."' valign='top'>
																		<span class='texto_ordem'>".$quebraTexto."</span>
																		".$quebraTitulo."
																	</td>
																	<td width='".$largura_2."' class='class_item_quebra justificado' style='".$style_td_top_quebra." ".$style_td_quebra."'>
																		<p class='texto'>".$paragrafo."</p>
																	</td>
																</tr>";
												$indPar++;
											}
										$html .= "</tr>
										</table>";
										$mpdf->WriteHTML($html);

									}
								}
						}
					}else{
						$largura_1 = '45% !important';
						$largura_2 = '55%';

						if($row_campo['tipo']=='json'){	//Aqui é JSON
							$html = "
								<table autosize='1'
									class='MsoNormalTable'
									border='1'
									cellspacing='0'
									cellpadding='0'
									width='100%'
									style='width: 100.0%; border-collapse: collapse; border: medium none'>
									<tr>
										<td width='23%' class='ptTabelaAzul'>
											<span class='texto_formulario_divisao'>&nbsp;". $countForm.".".$countField.".&nbsp;&nbsp;".trim(mb_strtoupper(formatar_texto_bd($row_campo['texto'])))."</span>
										</td>
									</tr>
								</table>
							";
							$mpdf->WriteHTML($html);
							if($row_campo['valor']==""){
								$html = "
								<table autosize='1' border='1' cellspacing='0' cellpadding='0' width='649' style='width: 100%; border-collapse: collapse; border: medium none'>
									<tr><td width='100%'><span class='texto'>Nenhum ítem cadastrado</span></td></tr>
								</table>
								";
								$mpdf->WriteHTML($html);

							}else{
								$json = formatar_texto_bd($row_campo['selecao_valores']);
								$json = str_replace("<br />","",$json);
								$obj = json_decode($json);	//Puxando do objeto JSON do tipo do campo

								$tempCol = explode($separadorRegistro, formatar_texto_bd($row_campo['valor']));
								$qtd  = count($tempCol);	//Quantidade de registros que vai ter
								//echo "<br>CAMPO->".$row_campo['valor']."<br>***QUANTIDADE->".$qtd;
								for($i=0;$i<$qtd;$i++){
									if(trim($tempCol[$i]!="")){
										$colunas = $obj->colunas;
										$html = "
											<table autosize='1' class='MsoNormalTable' border='1' cellspacing='0' cellpadding='0' width='100%' style='width: 100.0%; border-collapse: collapse; border: medium none'>
												<tr>
													<td width='23%' class='divisao'>
														<span class='texto'>&nbsp;<b>Registro ".($i + 1) ."</b></span>
														";
										$html .= "
													</td>
												</tr>
											</table>
										";
										$html .= "
											<table autosize='1'
												autosize='0'
												border='1'
												cellspacing='0'
												cellpadding='0
												' style='width: 100%; border-collapse: collapse; border: medium none'>
										";
										for($col=0;$col<count($colunas);$col++){
											$itemCol = explode("§", $tempCol[$i]);
											$itemCol = $itemCol[$col];

											//$itemCol = "";
											$html .= "
												<tr>
													<td
														class='class_item'
														valign='middle'
														width='$largura_1'><span class='texto_conteudo'>".$colunas[$col]->titulo_br."</span>";
													$html .= "

													</td>
													<td class='linha_json' width='$largura_2' style='white-space: pre-wrap; !important'><center>";
													$html .= "<span class='texto_conteudo'>".$itemCol."</span>";
													$html .= "</center>
													</td>
												</tr>";
										}
										$html .="
											</table>
										";
										//echo $html . "<br>";
										$mpdf->WriteHTML($html);
									}
								}

							}
						}else{  //JSON VERTICAL
							//Colocar o label do campo
							$html = "
								<table autosize='1'
									class='MsoNormalTable'
									border='1'
									cellspacing='0'
									cellpadding='0'
									width='100%'
									style='width: 100.0%; border-collapse: collapse; border: medium none'>
									<tr>
										<td width='23%' class='ptTabelaAzul'>
											<span class='texto_formulario_divisao'>&nbsp;". $countForm.".".$countField.".&nbsp;&nbsp;".trim(mb_strtoupper(formatar_texto_bd($row_campo['texto'])))."</span>
										</td>
									</tr>
								</table>
							";
							$json = formatar_texto_bd($row_campo['selecao_valores']);
							$json = str_replace("<br />","",$json);
							$obj = json_decode($json);

							$linhas = $obj->linhas;
							$colunas = $obj->colunas;

							$html .= "
									<table autosize='1' border='1' cellspacing='0' cellpadding='0' width='649' style='width: 100%; border-collapse: collapse; border: medium none'>
										<thead>
											<tr>
							";
							$largura_col = intval(100/count($colunas));
							for($col=0;$col<count($colunas);$col++){
								$html .= "
									<th style='".$largura_col."% !important' class='header_json'>
										<span class='header_json_pt'>".$colunas[$col]->titulo_br."</span><br>
										<span class='header_json_lg'></span>
									</th>
								";
							}
							$html .= "
											</tr>
										</thead>
										<tbody>
							";

										$valor = trim(formatar_texto_bd($row_campo['valor']));
										if($valor==""){
											//for($row = 1;$row<=$linhas;$row++){
												for($row = 1;$row<=1;$row++){
												if($row>100){ break; }
												$html .= "<tr>
												";
												for($col=0;$col<count($colunas);$col++){
													$html .= "<td style='".$largura_col."% !important' class='linha_json'>";

													$html .= "<span class='texto_conteudo'>N/A</span>";

													$html .= "</td>";
												}
												$html .="		  </tr>
														";
											}
										}else{

											$jsonLinhas = explode($separadorRegistro, $valor);	//Aqui tenho as linhas do JSON
											$arrDados = array();
											for($row=0;$row<count($jsonLinhas)-1;$row++){
												$jsonLinha = $jsonLinhas[$row];
												$jsonColunas = explode($separadorCampo, $jsonLinha);
												$arrDados[] = $jsonColunas;
											}
											$arrDados = ordenaDados($arrDados);
											for($row=0;$row<count($arrDados);$row++){
												$html .= "<tr>";
												for($col=0;$col<count($colunas);$col++){
													$html .= "<td style='word-wrap: break-word; white-space: normal;' class='linha_json_preenchido'>";
													$varDado = $arrDados[$row][$col];
													$varDado = explode($separadorCampoJSON, $varDado);
													$iDado = 0;
													foreach($varDado as $dado){
														if($iDado==0){
															$html .= "<span class='texto_conteudo'>".$dado."</span>";
														}else{
															$html .= "<hr><span class='texto_conteudo'>".$dado."</span>";
														}
														$iDado++;
													}
													$html .= "</td>";
												}
												$html .= "</tr>";
											}
										}



							$html .="	</tbody>
									</table>
							";

							$mpdf->WriteHTML($html);


						}
					}

				}
				//$mpdf->WriteHTML($html);
				//urldecode(formatar_texto_bd($row_campo['valor']))
				//formatar_texto_bd($row_campo['comentario'])
				if(trim($row_campo['comentario']!="")){	//Tem comentÃƒÂ¡rio
					$html = "
					<table autosize='1' class='MsoNormalTable'
							border='1' cellspacing='0'
							cellpadding='0'
							width='100%'
							style='width: 100.0%; border-collapse: collapse; border: medium none'>
						<tr>
							<td width='100%' style='padding: 7pt; text-align: justify;'>
								<span style='text-align: justify;' class='texto_comentario'>
									<b>Comentário:</b> ".nl2br(formatar_texto_bd($row_campo['comentario']))."
								</span>
							</td>
						</tr>
					</table>
					";
					$mpdf->WriteHTML($html);
				}

				$html = '';
			}
		}



		$html .= "
			</div>

		";
		$mpdf->WriteHTML($html);

	}



			$html = '
			<table autosize="1" class="MsoNormalTable" border="0" cellspacing="0" cellpadding="0" width="100%" style="width: 100.0%; border-collapse: collapse; border: medium none" >
					<tr height="15"><td colspan="5" height="15"><br></td></tr>
					<tr>
						<td class="ptTabelaAzul" style="border-left: 1.0pt solid black; border-right: 1.0pt solid black; border-top: 1.0pt solid black; border-bottom: 1.0pt solid black;">
							<b>
							<span class="texto_formulario_nro">';
								$html .= $countForm+1 .'.
							</span>
							<span class="texto_formulario">
								 LISTA DE ACHADOS/PENDÊNCIAS/DESVIOS DE PROTOCOLO/NÃO CONFORMIDADES';
								$html .= '
							</span>
							</b>
						</td>
					</tr>
			</table>
			<table autosize="1" width="100%" class="MsoNormalTable" border="1" cellspacing="0" cellpadding="0" style="border-collapse: collapse; border: medium none">
				<tr>
					<td width="130" valign="top" style="border-left: 1.0pt solid black; border-right: 1.0pt solid black; border-top: 1.0pt solid black; border-bottom: 1.0pt solid black; padding-left: 5.4pt; 	padding-right: 5.4pt; padding-top: 3.5pt; padding-bottom: 3.5pt" class="ptTabelaAzul">
						<p class="MsoNormal" align="center" style="text-align:center"><b>
						<span lang="EN-GB" style="font-size:11.0pt;  font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Participante';
						$html .= '
						</span></b>
					</td>
					<td width="120" valign="top" style="border: 1.0pt solid black; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 3.5pt; padding-bottom: 3.5pt" class="ptTabelaAzul">
						<p class="MsoNormal" align="left" style="text-align:center"><b>
						<span lang="EN-GB" style="font-size:11.0pt;  font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Tipo';
						$html .= '
						</span></b>
					</td>
					<td width="40%" valign="top" style="border-left: medium none; border-right: 1.0pt solid black; border-top: 1.0pt solid black; border-bottom: 1.0pt solid black; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 3.5pt; padding-bottom: 3.5pt" class="ptTabelaAzul">
						<p class="MsoNormal" align="center" style="text-align:center"><b>
						<span lang="EN-GB" style="font-size:11.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Descrição';
						$html .= '
						</span></b>
					</td>
					<td width="40%" valign="top" style="border-left: medium none; border-right: 1.0pt solid black; border-top: 1.0pt solid black; border-bottom: 1.0pt solid black; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 3.5pt; padding-bottom: 3.5pt" class="ptTabelaAzul">
						<p class="MsoNormal" align="center" style="text-align:center"><b>
						<span lang="EN-GB" style="font-size:11.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Ação';
						$html .= '
						</span></b>
					</td>
					<td width="100" valign="top" style="border-left: medium none; border-right: 1.0pt solid black; border-top: 1.0pt solid black; border-bottom: 1.0pt solid black; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 3.5pt; padding-bottom: 3.5pt" class="ptTabelaAzul">
						<p class="MsoNormal" align="center" style="text-align:center"><b>
						<span lang="EN-GB" style="font-size:11.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Status';
						$html .= '
						</span></b>
					</td>
				</tr>
				';
		$mpdf->WriteHTML($html);

		if(strtoupper($varAgenda->tipo)!="VISITA FINAL"){
			$dataVis = $row_agenda['data'];
			$dataVis = substr($dataVis, 6,2) . "/" . substr($dataVis, 4,2) . "/" .substr($dataVis, 0,4);
			$query_t = "select * from agenda_lista_pendencia
							where   visita = '".$row_agenda['nro_visita']."'
									and projeto = ".$_SESSION['projeto']."
										order by paciente";
		}else{
			$dataVis = $row_agenda['data'];
			$dataVis = substr($dataVis, 6,2) . "/" . substr($dataVis, 4,2) . "/" .substr($dataVis, 0,4);
			$query_t = "select * from agenda_lista_pendencia
							where  projeto = ".$_SESSION['projeto']."
								order by paciente";
		}
			$html = "";
							$res_p = $conexao->pesquisa($query_t);
							if(mysqli_num_rows($res_p)!=0){
								while($row_p = mysqli_fetch_assoc($res_p)){

									$html = '<tr>
										<td style="border-left: medium none; border-right: 1.0pt solid black;  border-left: 1.0pt solid black;border-top: medium none; border-bottom: 1.0pt solid black; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm; background: white">
											<p class="MsoNormal" align="center" style="text-align:center">
												<span lang="EN-GB" class="texto_conteudo">
												<center>';
									$paciente = formatar_texto_bd($row_p['paciente']);
									if($paciente==""){$paciente = "N/A";}
									$html .= $paciente.'</center>
												</span>
											</p>
										</td>

										<td class="texto_conteudo justificado" style="border-left: medium none; border-right: 1.0pt solid black; border-top: medium none; border-bottom: 1.0pt solid black; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm; background: white"><span class="texto_conteudo"><center>'.formatar_texto_bd($row_p['tipo']).'</center></span></td>

										<td class="justificado texto_conteudo" style="border-left: medium none; border-right: 1.0pt solid black; border-top: medium none; border-bottom: 1.0pt solid black; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm; background: white"><span class="texto_conteudo justificado">'.formatar_texto_bd(nl2br($row_p['achado'])).'</span></td>

										<td class="justificado texto_conteudo" style="border-left: medium none; border-right: 1.0pt solid black; border-top: medium none; border-bottom: 1.0pt solid black; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm; background: white"><span class="texto_conteudo justificado">'.formatar_texto_bd(nl2br($row_p['acao'])).'</span></td>

										<td class="centralizado texto_conteudo" style="border-left: medium none; border-right: 1.0pt solid black; border-top: medium none; border-bottom: 1.0pt solid black; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm; background: white"><span class="texto_conteudo">'.formatar_texto_bd($row_p['status']).'</span></td>
									</tr>';
								$mpdf->WriteHTML($html);
								}
							}else{
								$html = '<tr><td colspan=5> Nenhuma pendência encontrada';
								$html .= '.</td></tr>';
								$mpdf->WriteHTML($html);
							}
			$html 	= '
						</table>
				';
			$mpdf->WriteHTML($html);
			$countForm++;

		$countForm++;
		//Assinatura!
		$query = "SELECT agenda.id, agenda.data, agenda.monitora, agenda.revisor, agenda.tipo_monitoria,
			monitor.nome as nome_monitor, monitor.assinatura as ass_monitor, monitor.tipo_usuario as tipo_monitor,
			revisor.nome as nome_revisor, revisor.assinatura as ass_revisor
			FROM agenda
				left join usu_usuario as monitor on agenda.monitora = monitor.id
				left join usu_usuario as revisor on agenda.revisor = revisor.id

				where agenda.id = " . $_GET['id'];
		$resAss = $conexao->pesquisa($query);
		$rowAss = mysqli_fetch_assoc($resAss);
		$html = "<br><br><br>
		<div align='center'>
			<table autosize='1' border='0' width='100%' cellspacing='0'>
				<tr>
					<td class='ptTabelaAzul' colspan='4' style='padding-left: 4pt; padding-top: 4pt' style='border-left: 1.0pt solid black; border-right: 1.0pt solid black; border-top: 1.0pt solid black; border-bottom: 1.0pt solid black;'>
						<span class='texto_formulario_pt'><b>".$countForm.". ASSINATURAS</b>";
						$html .= "
					</td>
				</tr>
				<tr>
					<td class='ptTabelaAzul' colspan='4' style='padding-left: 4pt; padding-top: 4pt' style='border-left: 1.0pt solid black; border-right: 1.0pt solid black; border-top: 1.0pt solid black; border-bottom: 1.0pt solid black;'>
						<span class='texto_header_assinatura'><b>ELABORADO POR</b>";
						$html .= "
					</td>
				</tr>
				<tr>
					<td style='border-left:1px solid' valign='bottom'><span class='texto_assinatura'>".formatar_texto_bd($varAgenda->monitor->nome)."</span></td>
					<td valign='bottom'><span class='texto_assinatura'><center><br><br><br><br><br></span></td>
					<td valign='bottom'><span class='texto_assinatura'>".formatar_texto_bd($varAgenda->monitor->funcao_nome)."</span></td>
					<td valign='bottom' style='border-right:1px solid;'><span class='texto_assinatura'><center></center></span></td>
				</tr>
				<tr>
					<td style='padding-bottom: 15.5pt; border-left:1px solid; border-top:1px solid'><span class='texto_header_assinatura'>Nome</span>";
						$html .= "
					</td>
					<td style='padding-bottom: 15.5pt; border-top:1px solid'><center><span class='texto_header_assinatura'>Assinatura</span>";
						$html .= "
					</td>
						<td style='padding-bottom: 15.5pt; border-top:1px solid'><span class='texto_header_assinatura'>Função</span>";
						$html .= "
					</td>
					<td style='padding-bottom: 15.5pt; border-right:1px solid; border-top:1px solid' width='20%'><span class='texto_header_assinatura'><center>Data</center></span>";
						$html .= "
					</td>
				</tr>

				<tr>
					<td class='ptTabelaAzul' colspan='4' style='padding-left: 4pt; padding-top: 4pt' style='border-left: 1.0pt solid black; border-right: 1.0pt solid black; border-top: 1.0pt solid black; border-bottom: 1.0pt solid black;'>
						<span class='texto_header_assinatura'><b>REVISADO POR</b>";
						$html .= "
					</td>
				</tr>
				<tr>
					<td style='border-left:1px solid' valign='bottom'><span class='texto_assinatura'>".formatar_texto_bd($varAgenda->revisor->nome)."</span></td>
					<td valign='bottom'><span class='texto_assinatura'><center><br><br><br><br><br></span></td>
					<td valign='bottom'><span class='texto_assinatura'>".formatar_texto_bd($varAgenda->revisor->funcao_nome)."</span></td>
					<td valign='bottom' style='border-right:1px solid;'><span class='texto_assinatura'><center></center></span></td>
				</tr>
				<tr>
					<td style='padding-bottom: 15.5pt; border-left:1px solid; border-top:1px solid; border-bottom:1px solid'><span class='texto_header_assinatura'>Nome</span>";
						$html .= "
					</td>
					<td style='padding-bottom: 15.5pt; border-top:1px solid; border-bottom:1px solid'><center><span class='texto_header_assinatura'>Assinatura</span>";
						$html .= "
					</td>
					<td style='padding-bottom: 15.5pt; border-top:1px solid; border-bottom:1px solid'><span class='texto_header_assinatura'>Função</span>";
						$html .= "
					</td>
					<td style='padding-bottom: 15.5pt; border-right:1px solid; border-top:1px solid; border-bottom:1px solid' width='20%'><span class='texto_header_assinatura'><center>Data</center></span>";
						$html .= "
					</td>
				</tr>
				<tr>
					<td class='ptTabelaAzul' colspan='4' style='padding-left: 4pt; padding-top: 4pt' style='border-left: 1.0pt solid black; border-right: 1.0pt solid black; border-top: 1.0pt solid black; border-bottom: 1.0pt solid black;'>
						<span class='texto_header_assinatura'><b>APROVADO POR</b>";
						$html .= "
					</td>
				</tr>
				<tr>
					<td style='border-left:1px solid' valign='bottom'><span class='texto_assinatura'>Amanda Azevedo da Silva Gondim</span></td>
					<td valign='bottom'><span class='texto_assinatura'><center><br><br><br><br><br></span></td>
					<td valign='bottom'><span class='texto_assinatura'>Coordenadora da Monitoria</span></td>
					<td valign='bottom' style='border-right:1px solid;'><span class='texto_assinatura'><center></center></span></td>
				</tr>
				<tr>
					<td style='padding-bottom: 15.5pt; border-left:1px solid; border-top:1px solid; border-bottom:1px solid'><span class='texto_header_assinatura'>Nome</span>";
						$html .= "
					</td>
					<td style='padding-bottom: 15.5pt; border-top:1px solid; border-bottom:1px solid'><center><span class='texto_header_assinatura'>Assinatura</span>";
						$html .= "
					</td>
					<td style='padding-bottom: 15.5pt; border-top:1px solid; border-bottom:1px solid'><span class='texto_header_assinatura'>Função</span>";
						$html .= "
					</td>
					<td style='padding-bottom: 15.5pt; border-right:1px solid; border-top:1px solid; border-bottom:1px solid' width='20%'><span class='texto_header_assinatura'><center>Data</center></span>";
						$html .= "
					</td>
				</tr>
			</table>
		</div>
		";
		$mpdf->WriteHTML($html);
		$countForm++;

		//HISTÓRICO DE VERSÕES
		$html = "<br><br><br>
			<div align='center'>
				<table autosize='1' border='0' width='100%' cellspacing='0'>
					<tr>
						<td class='ptTabelaAzul' colspan='3' style='padding-left: 4pt; padding-top: 4pt' style='border-left: 1.0pt solid black; border-right: 1.0pt solid black; border-top: 1.0pt solid black; border-bottom: 1.0pt solid black;'>
							<span class='texto_formulario_pt'><b>".$countForm.". HISTÓRICO DE VERSÕES</b>";
							$html .= "
						</td>
					</tr>
					<tr>
						<td class='ptTabelaAzul' style='padding-left: 4pt; padding-top: 4pt' style='border-left: 1.0pt solid black; border-right: 1.0pt solid black; border-bottom: 1.0pt solid black;'>
							<center><span class='texto_formulario_pt'><b>VERSÃO</b></span></center>
						</td>
						<td class='ptTabelaAzul' style='padding-left: 4pt; padding-top: 4pt' style='border-right: 1.0pt solid black; border-bottom: 1.0pt solid black;'>
							<center><span class='texto_formulario_pt'><b>MOTIVO DO VERSIONAMENTO</b></span></center>
						</td>
						<td class='ptTabelaAzul' style='padding-left: 4pt; padding-top: 4pt' style='border-right: 1.0pt solid black;border-bottom: 1.0pt solid black;'>
							<center><span class='texto_formulario_pt'><b>DATA</b></span></center>
						</td>
					</tr>";
		$query = "select
						id,
						versao,
						versao_motivo,
						data_impressao as versao_data,
						data
							from agenda
								where projeto = " . $_SESSION['projeto'] . "
										and nro_visita = ".$varAgenda->nro_visita."
										order by nro_visita, versao ";
		$resVersao = $conexao->pesquisa($query);
		while($rowVersao = mysqli_fetch_assoc($resVersao)){
			$html .= '<tr>
						<td class="texto_conteudo justificado" style="border-left: 1.0pt solid black; border-right: 1.0pt solid black; border-bottom: 1.0pt solid black;"><center>'.formata_utf8_encode($rowVersao['versao']).'</center></td>
						<td class="texto_conteudo justificado" style="border-right: 1.0pt solid black; border-bottom: 1.0pt solid black;"><center>'.formata_utf8_encode($rowVersao['versao_motivo']).'</center></td>
						<td class="texto_conteudo justificado" style="border-right: 1.0pt solid black; border-bottom: 1.0pt solid black;"><center>'.formata_utf8_encode(listoDATA($rowVersao['versao_data'])).'</center></td>
					</tr>';
		}
		$html .= "
				</table>
			</div>
			";
			$mpdf->WriteHTML($html);

	$mpdf->Output();
	$mpdf->charset_in='UTF-8';
?>