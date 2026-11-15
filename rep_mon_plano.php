<?php
	/*Acentuação teste chão*/
	$encoding = 'UTF-8';

	include_once __DIR__."/seguranca.php";
	include_once __DIR__."/class/agenda.class.php";
    include_once __DIR__."/class/centro.class.php";
	include_once __DIR__."/class/projeto.class.php";
    include_once __DIR__."/class/patrocinador.class.php";
	include_once __DIR__."/definicoes.php";
	require_once __DIR__ . '/vendor/autoload.php';


    if($_GET['localidade']=="cps"){
        $localidade = "Campinas";
    }else{
        $localidade = "Goiânia";
    }

	$varProjeto             = new Projeto($_SESSION['projeto']);
	$varAgenda              = new Agenda($_GET['id']);
	$varPatrocinador        = new Patrocinador($varProjeto->patrocinadorId);
	$varPatrocinadorProjeto = new PatrocinadorProjeto($varProjeto->id);
	$varPessoaResponsavel   = new Pessoa($varPatrocinadorProjeto->pessoa_responsavel);
	$varPessoa1             = new Pessoa($varPatrocinadorProjeto->pessoa_1);
	$varPessoa2             = new Pessoa($varPatrocinadorProjeto->pessoa_2);
    //$varCentro = new CentroPesquisa($varAgenda->centro->id);


    $nomeMes = array(
        "01" => "Janeiro",
        "02" => "Fevereiro",
        "03" => "Março",
        "04" => "Abril",
        "05" => "Maio",
        "06" => "Junho",
        "07" => "Julho",
        "08" => "Agosto",
        "09" => "Setembro",
        "10" => "Outubro",
        "11" => "Novembro",
        "12" => "Dezembro",
    );
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
	function traduzirREL($texto_entrada){
		global $conexao;
		$texto = "";
		//if(($texto_entrada!="Não")||($texto_entrada!="NÃO")){
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

    $varData = $_GET['data'];
	$varAgendamento = $_GET['id'];
	$varAgenda		= new Agenda($_GET['id']);

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
        'margin_bottom' => 30
    ]);





    $mpdf->shrink_tables_to_fit = 0;
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

    $varData = $_GET['data'];
    $varData = explode("/", $varData);
    $varDia  = $varData[0];
    $varMes  = $nomeMes[$varData[1]];
    $varAno  = $varData[2];

    $html = '<br>';

    $html = '<p class="ptHeaderDestaque" style="text-align:center;">'.traduzir('PLANO DE MONITORIA').'</p>';
    $mpdf->WriteHTML($html);

    $html = '<p class="ptTexto" style="text-align:right;">'.$localidade.', '.$varDia.' de '.$varMes.' de '.$varAno.'.</p>';
    $html .= '<p class="ptTexto" style="text-align:left;">Ilmo(a) '.formata_utf8_encode($varPessoaResponsavel->nome).'<br>';
    $html .= formata_utf8_encode($varPatrocinador->nome).'</p>';
    $mpdf->WriteHTML($html);



    $html = '<p class="ptTexto" style="text-align:justify;">Em conformidade ao contrato de monitoria da etapa clínica do estudo "' . formata_utf8_encode($varProjeto->descricao) . '", a Phartrials apresenta o Plano de Monitoria para apreciação e validação.</p>';
    $mpdf->WriteHTML($html);

    $html = '<p class="ptTexto" style="text-align:justify;">Ressaltamos que este documento foi elaborado em conformidade com nosso POP MON 002 - PLANO DE MONITORIA e contempla a monitoria de todos os processos que constituem a Etapa Clínica do referido estudo.</p>';
    $mpdf->WriteHTML($html);

    $html = '
    <table border="1" height="100%" width="100%"  cellspacing="0" cellpadding="0" bordercolor="#000000" style="border-collapse: collapse; padding: 5px;">
        <tr>
            <td width="30%" class="ptTabelaAzul" style="padding: 5px;">
                <span class="ptTexto" style="text-align:justify;"><b>CÓDIGO DA MONITORIA</b></span>
            </td>
            <td width="70%" style="border:1px solid #000000; padding: 5px;">
                <span class="ptTexto" style="text-align:justify;">'.$varProjeto->numero.'</span>
            </td>
        </tr>
        <tr>
            <td width="30%" class="ptTabelaAzul" style="padding: 5px;">
                <span class="ptTexto" style="text-align:justify;"><b>VERSÃO DO PLANO DE MONITORIA</b></span>
            </td>
            <td width="70%" style="border:1px solid #000000; padding: 5px;">
                <span class="ptTexto" style="text-align:justify;">'.$varProjeto->versao_plano.'</span>
            </td>
        </tr>
    </table><p></p>
    ';
    $mpdf->WriteHTML($html);

    $html = '
    <table border="1" height="100%" width="100%"  cellspacing="0" cellpadding="0" bordercolor="#000000" style="border-collapse: collapse; padding: 5px;">
        <tr>
            <td width="30%" class="ptTabelaAzul" style="padding: 5px;">
                <span class="ptTexto" style="text-align:justify;"><b>PATROCINADOR</b></span>
            </td>
            <td width="70%" style="border:1px solid #000000; padding: 5px;">
                <span class="ptTexto" style="text-align:justify;">'.formata_utf8_encode($varProjeto->patrocinador->nome).'</span>
            </td>
        </tr>
    </table>
    ';
    $html .= '
    <table border="1" height="100%" width="100%"  cellspacing="0" cellpadding="0" bordercolor="#000000" style="border-collapse: collapse; padding: 5px;">
        <tr>
            <td width="30%" class="ptTabelaAzul" style="padding: 5px;">
                <span class="ptTexto" style="text-align:justify;"><b>CENTRO DE PESQUISA</b></span>
            </td>
            <td width="70%" style="border:1px solid #000000; padding: 5px;">
                <span class="ptTexto" style="text-align:justify;">'.formata_utf8_encode($varAgenda->centro->nome).'</span>
            </td>
        </tr>
    </table>
    ';
    $html .= '
    <table border="1" height="100%" width="100%"  cellspacing="0" cellpadding="0" bordercolor="#000000" style="border-collapse: collapse; padding: 5px;">
        <tr>
            <td width="30%" class="ptTabelaAzul" style="padding: 5px;">
                <span class="ptTexto" style="text-align:justify;"><b>CÓDIGO DO PROTOCOLO/VERSÃO E DATA</b></span>
            </td>
            <td width="70%" style="border:1px solid #000000; padding: 5px;">
                <span class="ptTexto" style="text-align:justify;">'.$varProjeto->codigo.', Versão '.$varProjeto->versao.', Data: '.$varProjeto->data_protocolo.'</span>
            </td>
        </tr>
    </table>
    ';
    $html .= '
    <table border="1" height="100%" width="100%"  cellspacing="0" cellpadding="0" bordercolor="#000000" style="border-collapse: collapse; padding: 5px;">
        <tr>
            <td width="30%" class="ptTabelaAzul" style="padding: 5px;">
                <span class="ptTexto" style="text-align:justify;"><b>TÍTULO DO ESTUDO</b></span>
            </td>
            <td width="70%" style="border:1px solid #000000; padding: 5px;">
                <span class="ptTexto" style="text-align:justify;">'.formata_utf8_encode($varProjeto->descricao).'</span>
            </td>
        </tr>
    </table>
    ';
    $html .= '
    <table border="1" height="100%" width="100%"  cellspacing="0" cellpadding="0" bordercolor="#000000" style="border-collapse: collapse; padding: 5px;">
        <tr>
            <td width="30%" class="ptTabelaAzul" style="padding: 5px;">
                <span class="ptTexto" style="text-align:justify;"><b>INVESTIGADOR PRINCIPAL</b></span>
            </td>
            <td width="70%" style="border:1px solid #000000; padding: 5px;">
                <span class="ptTexto" style="text-align:justify;">'.formata_utf8_encode($varAgenda->centro->investigador).'</span>
            </td>
        </tr>
    </table>
    ';
    $html .= '
    <table border="1" height="100%" width="100%"  cellspacing="0" cellpadding="0" bordercolor="#000000" style="border-collapse: collapse; padding: 5px;">
        <tr>
            <td width="30%" class="ptTabelaAzul" style="padding: 5px;">
                <span class="ptTexto" style="text-align:justify;"><b>Nº DE PARTICIPANTES DE PESQUISA</b></span>
            </td>
            <td width="70%" style="border:1px solid #000000; padding: 5px;">
                <span class="ptTexto" style="text-align:justify;">'.formata_utf8_encode($varProjeto->n).'</span>
            </td>
        </tr>
    </table>
    ';
    $html .= '
    <table border="1" height="100%" width="100%"  cellspacing="0" cellpadding="0" bordercolor="#000000" style="border-collapse: collapse; padding: 5px;">
        <tr>
            <td width="30%" class="ptTabelaAzul" style="padding: 5px;">
                <span class="ptTexto" style="text-align:justify;"><b>Nº DE PERÍODOS</b></span>
            </td>
            <td width="70%" style="border:1px solid #000000; padding: 5px;">
                <span class="ptTexto" style="text-align:justify;">'.formata_utf8_encode($varProjeto->periodos).'</span>
            </td>
        </tr>
    </table>
    ';
    //Aqui vou tratar para o caso do campo coleta tiver muitos paragrafos. Vou quebrar o texto em paragrafos e adicionar um <br> entre cada paragrafo.
    $mpdf->WriteHTML($html);


    $valorAserImpresso = formatar_texto_bd($varProjeto->coletas);
    $paragrafos = explode("\n", $valorAserImpresso);
    if(count($paragrafos)<3){ // Aqui o texto é curto, mando normalmente
        $html = '
        <table border="1" height="100%" width="100%"  cellspacing="0" cellpadding="0" bordercolor="#000000" style="border-collapse: collapse; padding: 5px;">
            <tr>
                <td width="30%" class="ptTabelaAzul" style="padding: 5px;">
                    <span class="ptTexto" style="text-align:justify;"><b>Nº DE COLETAS POR PERÍODO</b></span>
                </td>
                <td width="70%" style="border:1px solid #000000; padding: 5px;">
                    <span class="ptTexto" style="text-align:justify;">'.formata_utf8_encode(nl2br($varProjeto->coletas)).'</span>
                </td>
            </tr>
        </table>';

    }else{	//Aqui tem mais de 3 paragrafos. Então vou quebrar por paragrafo
        $html = '<table border="1" height="100%" width="100%"  cellspacing="0" cellpadding="0" bordercolor="#000000" style="border-collapse: collapse; padding: 5px;">';
                $indPar = 0;
                foreach($paragrafos as $paragrafo){
                    if($indPar>0){ $html .= "<tr>";  $style_td_top_quebra = " border-top: none;padding: 5px;";}else{  $style_td_top_quebra = " border-top: 1.0pt solid black;padding: 5px;";}
                    if($indPar==count($paragrafos)-1){
                        $style_td_quebra = " border-bottom: 1.0pt solid black;"; }else{ $style_td_quebra = " border-bottom: none;"; }
                    if($indPar==0){ //Primeira linha.
                        $quebraTexto = "Nº DE COLETAS POR PERÍODO";
                        $quebraCssTopo = " border-top: 1.0pt solid black;padding: 5px;";
                        $quebraCssBottomo = " border-bottom: 1.0pt solid black;";
                    }else{
                        $quebraTexto = "";
                        $quebraCssTopo = " border-top: none; padding: 5px;";
                        $quebraCssBottomo = " border-bottom: none;";
                    }


                    $html .= " <tr>
                                    <td width='30%' class='ptTabelaAzul'  style='".$style_td_quebra." ".$quebraCssTopo.";padding: 5px;' valign='top'>
                                        <span class='ptTexto' style='text-align:justify;'><b>$quebraTexto</b></span>
                                    </td>
                                    <td width='70%' class='class_item_quebra justificado' style='".$style_td_top_quebra." ".$style_td_quebra."'>
                                        <p class='ptTexto' style='text-align:justify;'>".$paragrafo."</p>
                                    </td>
                                </tr>";
                $indPar++;
            }
        $html .= "</tr>
        </table>";
    }

    $html .= '
    <table border="1" height="100%" width="100%"  cellspacing="0" cellpadding="0" bordercolor="#000000" style="border-collapse: collapse; padding: 5px;">
        <tr>
            <td width="30%" class="ptTabelaAzul" style="padding: 5px;">
                <span class="ptTexto" style="text-align:justify;"><b>MONITORES RESPONSÁVEIS</b></span>
            </td>
            <td width="70%" style="border:1px solid #000000; padding: 5px;">
                <span class="ptTexto" style="text-align:justify;">'.nl2br(formata_utf8_encode($varAgenda->presentes)).'</span>
            </td>
        </tr>
    </table>
    ';
    $mpdf->WriteHTML($html);

    $mpdf->AddPage();

    $html = '<p></p>
    <table border="1" height="100%" width="100%"  cellspacing="0" cellpadding="0" bordercolor="#000000" style="border-collapse: collapse; padding: 5px;">
        <tr>
            <td colspan="2" class="ptTabelaAzulEscuro" style="padding: 5px;">
                <center>RECRUTAMENTO E PRÉ-INTERNAÇÃO</center>
            </td>
        </tr>';


    if($varProjeto->plano_chkOpc1 == 1){
        $html .= '

        <tr>
            <td width="15%" align="center" valign="middle" class="ptTabelaAzul" style="text-rotate: 90deg; padding: 5px; position:absolute; " rowspan="20">
                <div style="writing-mode: vertical-lr; transform: rotate(180deg); text-align: center; padding: 10px;">ARQUIVO DO INVESTIGADOR</div>
            </td>
            <td width="85%" style="border:1px solid #000000; padding: 5px;" >
                <p class="ptTexto" style="text-align:justify;">Protocolo e suas emendas (se aplicável)</p>
            </td>
        </tr>
        <tr><td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">Documentos do comitê de ética(se aplicável)</p></td></tr>
        <tr><td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">Folha de rosto</p></td></tr>
        <tr><td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">Notificação ao CEP (se aplicável)</p></td></tr>
        <tr><td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">Parecer Comitê de Ética</p></td></tr>
        <tr><td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">Termo de recrutamento aprovado pelo CEP</p></td></tr>
        <tr><td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">TCLE aprovado pelo CEP</p></td></tr>
        <tr><td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">Aprovação ANVISA ao protocolo (se aplicável)</p></td></tr>
        <tr><td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">Aprovação CEP pela CONEP</p></td></tr>
        <tr><td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">Lista de membros CEP</p></td></tr>
        <tr><td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">Habilitação do centro de pesquisa pela ANVISA</p></td></tr>

        <tr><td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">Seguro de vida</p></td></tr>
        <tr><td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">Certificado GCP equipe</p></td></tr>
        <tr><td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">Currículo equipe</p></td></tr>
        <tr><td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">Treinamento da equipe no protocolo clínico</p></td></tr>
        <tr><td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">Lista da equipe do centro</p></td></tr>
        <tr><td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">Valores de referência laboratório</p></td></tr>
        <tr><td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">Certificação do laboratório de análises clínicas</p></td></tr>
        <tr><td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">Lista do código dos participantes de pesquisa e inclusão no SINEB e Seguro</p></td></tr>
        <tr><td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">Cronograma da UTI Leito</td></tr>

        ';
    }


    if($varProjeto->plano_chkOpc2 == 1){
        $html .= '

        <tr>
            <td width="15%" align="center" valign="middle" class="ptTabelaAzul" style="text-rotate: 90deg; padding: 5px; position:absolute; " rowspan="8">
                <div style="writing-mode: vertical-lr; transform: rotate(180deg); text-align: center; padding: 10px;">RECRUTAMENTO E SELEÇÃO</div>
            </td>
            <tr><td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">Processo de consentimento</p></td></tr>
            <tr><td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">Documentos fornecidos aos participantes de pesquisa (se aplicável)</p></td></tr>
            <tr><td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">Anúncios/mídias utilizadas no recrutamento </p></td></tr>
            <tr><td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">Critérios de elegibilidade</p></td></tr>
            <tr><td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">Avaliação clínica e exames pré-estudo </p></td></tr>
            <tr><td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">Eletrocardiograma pré-estudo</p></td></tr>
            <tr><td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">Verificação de desvio ou violação de protocolo</p></td></tr>
        </tr>
    ';
    }
    if($varProjeto->plano_chkOpc3 == 1){
        $html .= '
        <tr>
            <td width="15%" align="center" valign="middle" class="ptTabelaAzul" style="text-rotate: 90deg; padding: 5px; position:absolute; " rowspan="7">
                <div style="writing-mode: vertical-lr; transform: rotate(180deg); text-align: center; padding: 10px;">MEDICAMENTOS</div>
            </td>
            <tr><td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">Certificado de equivalência farmacêutica/Laudo de controle de qualidade</p></td></tr>
            <tr><td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">Inventário dos medicamentos</p></td></tr>
            <tr><td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">Local de armazenamento e registro de temperatura</p></td></tr>
            <tr><td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">Nota fiscal/carta de encaminhamento dos medicamentos comparador e teste</p></td></tr>
            <tr><td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">Processo de preparação de dispensação dos medicamentos </p></td></tr>
            <tr><td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">Verificação de desvio ou violação de protocolo</p></td></tr>
        </tr>
    ';
    }
    if($varProjeto->plano_chkOpc4 == 1){
        $html .= '
        <tr>
            <td width="15%" align="center" valign="middle" class="ptTabelaAzul" style="text-rotate: 90deg; padding: 5px; position:absolute; " rowspan="11">
                <div style="writing-mode: vertical-lr; transform: rotate(180deg); text-align: center; padding: 10px;">PRÉ-INTERNAÇÃO</div>
            </td>
            <tr><td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">Entrada dos participantes de pesquisa</p></td></tr>
            <tr><td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">Acompanhamento do teste de álcool</p></td></tr>
            <tr><td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">Acompanhamento do teste de drogas</p></td></tr>
            <tr><td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">Acompanhamento do teste de gravidez</p></td></tr>
            <tr><td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">Exames clínico e físico</p></td></tr>
            <tr><td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">Identificação do participante de pesquisa</p></td></tr>
            <tr><td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">Organização e logística do centro de pesquisa</p></td></tr>
            <tr><td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">Verificação das exclusões e/ou desistências de participantes de pesquisa</p></td></tr>
            <tr><td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">Verificação das substituições dos participantes de pesquisa (se ocorrerem)</p></td></tr>
            <tr><td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">Verificação de desvio ou violação de protocolo</p></td></tr>
        </tr>
    ';
    }
    if($varProjeto->plano_chkOpc5 == 1){
        $html .= '
        <tr>
            <td width="15%" align="center" valign="middle" class="ptTabelaAzul" style="text-rotate: 90deg; padding: 5px; position:absolute; " rowspan="14">
                <div style="writing-mode: vertical-lr; transform: rotate(180deg); text-align: center; padding: 10px;">ADMINISTRAÇÃO E COLETAS</div>
            </td>
            <tr><td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">Ingestão do café da manhã padronizado (quando aplicável)</p></td></tr>
            <tr><td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">Administração do medicamento</p></td></tr>
            <tr><td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">Coleta das amostras biológicas</p></td></tr>
            <tr><td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">Manipulação, processamento e armazenamento das amostras biológicas</p></td></tr>
            <tr><td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">Identificação dos tubos das coletas e do armazenamento</p></td></tr>
            <tr><td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">Temperatura do local de armazenamento das amostras biológicas</p></td></tr>
            <tr><td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">Critérios de descontinuação</p></td></tr>
            <tr><td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">Dieta e ingestão de água</p></td></tr>
            <tr><td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">Sinais vitais e avaliação médica</p></td></tr>
            <tr><td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">Ocorrência de evento adverso não grave e grave</p></td></tr>
            <tr><td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">Controle de eliminações fisiológicas</p></td></tr>
            <tr><td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">Rastreabilidade das amostras biológicas</p></td></tr>
            <tr><td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">Verificação de desvio ou violação de protocolo</p></td></tr>
        </tr>
    ';
    }
    if($varProjeto->plano_chkOpc6 == 1){
        $html .= '
        <tr>
            <td width="15%" align="center" valign="middle" class="ptTabelaAzul" style="text-rotate: 90deg; padding: 5px; position:absolute; " rowspan="9">
                <div style="writing-mode: vertical-lr; transform: rotate(180deg); text-align: center; padding: 10px;">PÓS ESTUDO</div>
            </td>
            <tr><td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">Exames laboratoriais</p></td></tr>
            <tr><td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">Alta do participante de pesquisa</p></td></tr>
            <tr><td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">Resoluções de pendências</p></td></tr>
            <tr><td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">Critérios de descontinuação</p></td></tr>
            <tr><td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">Armazenamento das amostras biológicas</p></td></tr>
            <tr><td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">Cadeia de custódia (recebimento, contabilidade e movimentação) e armazenamento dos medicamentos</p></td></tr>
            <tr><td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">Ocorrência de evento adverso não grave e grave</p></td></tr>
            <tr><td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">Verificação de desvio ou violação de protocolo</p></td></tr>
        </tr>
    ';
    }
    if($varProjeto->plano_chkOpc7 == 1){
        $html .= '
        <tr>
            <td width="15%" align="center" valign="middle" class="ptTabelaAzul" style="text-rotate: 90deg; padding: 5px; position:absolute; " rowspan="2">
                <div style="writing-mode: vertical-lr; transform: rotate(180deg); text-align: center; padding: 10px;">ENCERRAMENTO</div>
            </td>
            <td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">Resoluções de pendências</p></td>
            <tr>
            </tr>
            <tr>
                <td width="85%" style="border:1px solid #000000; padding: 5px;" ><p class="ptTexto" style="text-align:justify;">Verificação de desvio ou violação de protocolo</p></td>
                </tr>
        </tr>
    ';
    }
    $html .= '
    </table>
    ';
    $mpdf->WriteHTML($html);
    //Comentários Adicionais
    $html = '
    <table border="1" height="100%" width="100%"  cellspacing="0" cellpadding="0" bordercolor="#000000" style="border-collapse: collapse; padding: 5px;">
        <tr>
            <td class="ptTabelaAzulEscuro" style="padding: 5px;">
                <center>OBSERVAÇOES/COMENTÁRIOS ADICIONAIS</center>
            </td>
        </tr>
    ';
    $varComentarios = formata_utf8_encode($varProjeto->comentarios);
    $varComentarios = str_replace("<br />", "|", $varComentarios);
    $paragrafos = explode("|", $varComentarios);
    if(count($paragrafos)<8){
      $html .= '
                <tr>
                    <td width="100%" style="border:1px solid #000000; padding: 5px; height: 80px; vertical-align: top; padding-top: 10px;" >
                        <p class="ptTexto" style="text-align:justify;">'.nl2br(formata_utf8_encode(str_replace("<br />", "", $varProjeto->comentarios))).'</p>
                    </td>
                </tr>
        ';
    }else{
        $indPar = 0;
        foreach($paragrafos as $paragrafo){
            if($indPar>0){ $html .= "<tr>";  $style_td_top_quebra = " border-top: none;";}else{  $style_td_top_quebra = " border-top: 1.0pt solid black;";}
            if($indPar==count($paragrafos)-1){
                $style_td_quebra = " border-bottom: 1.0pt solid black;"; }else{ $style_td_quebra = " border-bottom: none;"; }


            $html .= " <tr>
                            <td width='100%' class='' style='".$style_td_top_quebra." ".$style_td_quebra."padding: 5px; font-weight: normal !important;'>
                                <p class='font-size: 11.0pt;font-weight: normal;text-align: justify;'>".$paragrafo."</p>
                            </td>
                        </tr>";
        $indPar++;
        }
    }
    $html .= '
    </table>
    ';
    $mpdf->WriteHTML($html);



    //Comunicação
    $html = '<p></p>
    <table border="1" height="100%" width="100%"  cellspacing="0" cellpadding="0" bordercolor="#000000" style="border-collapse: collapse; padding: 5px;">
        <tr>
            <td colspan="2" class="ptTabelaAzulEscuro" style="padding: 5px;">
                <center>COMUNICAÇÃO PHARTRIALS/PATROCINADOR</center>
            </td>
        </tr>
        <tr>
            <td width="40%" align="center" valign="middle" class="ptTabelaAzul" style="padding: 5px; position:absolute;">
                EM CASO DE DESVIO GRAVE O MONITOR DEVE COMUNICAR IMEDIATAMENTE
            </td>
            <td width="60%" style="border:1px solid #000000; padding: 10px;" >
                <p class="ptTexto" style="text-align:justify;">
                    <b>Patrocinador: </b><b>'.formata_utf8_encode($varPessoa1->nome).'</b><br>
                    e-mail: '.$varPessoa1->email.'<br>
                    Telefone: '.$varPessoa1->telefone.'<br>';
                    if($varPessoa2->email!=""){
                        $html .= '
                            e-email: '.$varPessoa2->email.'<br>
                            Telefone: '.$varPessoa2->telefone.'<br>
                        ';
                    }
                    $html .= '

                    <b>Phartrials: </b><br>
                    e-mail: cleverson@phartrials.com.br<br>
                    Telefone: +55 (62) 98111-8666<br>
                    e-email: coordenacaomonitoria@phartrials.com.br<br>
                    Telefone: +55 (62) 99153-3100<br>
                    </p>
            </td>
        </tr>
        <tr>
            <td width="40%" align="center" valign="middle" class="ptTabelaAzul" style="padding: 5px; position:absolute;">
                EM CASO DE EVENTO ADVERSO GRAVE O MONITOR DEVE COMUNICAR IMEDIATAMENTE
            </td>
            <td width="60%" style="border:1px solid #000000; padding: 5px;" >
                <p class="ptTexto" style="text-align:justify;">
                    <b>Patrocinador: </b><b>'.formata_utf8_encode($varPessoa1->nome).'</b><br>
                    e-mail: '.$varPessoa1->email.'<br>
                    Telefone: '.$varPessoa1->telefone.'<br>';
                    if($varPessoa2->email!=""){
                        $html .= '
                            e-email: '.$varPessoa2->email.'<br>
                            Telefone: '.$varPessoa2->telefone.'<br>
                        ';
                    }
                    $html .= '

                    <b>Phartrials: </b><br>
                    e-mail: cleverson@phartrials.com.br<br>
                    Telefone: +55 (62) 98111-8666<br>
                    e-email: coordenacaomonitoria@phartrials.com.br<br>
                    Telefone: +55 (62) 99153-3100<br>
                    </p>
            </td>
        </tr>
    </table>
    ';
    $mpdf->WriteHTML($html);


    $html = '<p></p>
    <table border="1" height="100%" width="100%"  cellspacing="0" cellpadding="0" bordercolor="#000000" style="border-collapse: collapse; padding: 5px;">
        <tr>
            <td colspan="3" class="ptTabelaAzul" style="padding: 5px;text-align: center;">
                <center>HISTÓRICO DE VERSIONAMENTO DO PLANO</center>
            </td>
        </tr>
        <tr>
            <td style="border:1px solid #000000; padding: 5px" >
                <p class="ptTexto" style="text-align:center;">
                    <center>1</center>
                </p>
            </td>
            <td width="60%" style="border:1px solid #000000; padding: 5px; text-align: center;" >
                <p class="ptTexto" style="text-align:justify;">
                    Emissão do documento
                </p>
            </td>
            <td style="border:1px solid #000000; padding: 5px;text-align: center;">
                <p class="ptTexto" style="text-align:center;">
                    <center>'.listoDATA($varAgenda->data_impressao).'</center>
                </p>
            </td>
        </tr>
    ';

    $query = "select * from agenda_plano_versoes where agenda = ".$_GET['id']." order by versao";
    $res = $conexao->pesquisa($query);
    while($row = mysqli_fetch_assoc($res)){
        $html .= '
        <tr>
            <td style="border:1px solid #000000; padding: 5px;" >
                <p class="ptTexto" style="text-align:center;">
                    <center>'.$row['versao'].'</center>
                </p>
            </td>
            <td width="60%" style="border:1px solid #000000; padding: 5px; text-align: center;" >
                <p class="ptTexto" style="text-align:justify;">
                    '.formata_utf8_encode($row['motivo']).'
                </p>
            </td>
            <td style="border:1px solid #000000; padding: 5px; text-align: center;">
                <p class="ptTexto" style="text-align:justify;">
                    '.$row['data'].'
                </p>
            </td>
        </tr>
        ';
    }

    $html .= '
    </table>
    ';
    $mpdf->WriteHTML($html);


    //Fluxo de aprovação
    $html = '<p></p>
    <table border="1" height="100%" width="100%"  cellspacing="0" cellpadding="0" bordercolor="#000000" style="border-collapse: collapse; padding: 5px;">
        <tr>
            <td width="25%" class="ptTabelaAzulEscuro" style="padding: 5px; text-align: center;">FLUXO DE APROVAÇÃO<BR>DO PLANO DE MONITORIA</td>
            <td width="50%" class="ptTabelaAzulEscuro" style="padding: 5px; text-align: center;">ASSINATURA DO MONITOR</td>
            <td class="ptTabelaAzulEscuro" style="padding: 5px; text-align: center;">DATA</td>
        </tr>
        <tr>
            <td align="center" valign="middle" class="ptTabelaAzul" style="padding: 5px; position:absolute; padding-top: 10px;padding-bottom: 10px;">
                <b>Phartrials</b><br>
                <p class="ptTexto" style="font-weight: normal;">(Elaborador)</p>
            </td>
            <td style="border:1px solid #000000; padding: 5px;" ></td>
            <td style="border:1px solid #000000; padding: 5px;" ></td>
        </tr>
        <tr>
            <td align="center" valign="middle" class="ptTabelaAzul" style="padding: 5px; position:absolute;padding-top: 10px;padding-bottom: 10px;">
                <b>Phartrials</b><br>
                <p class="ptTexto" style="font-weight: normal;">(Revisor)</p>
            </td>
            <td style="border:1px solid #000000; padding: 5px;" ></td>
            <td style="border:1px solid #000000; padding: 5px;" ></td>
        </tr>
        <tr>
            <td align="center" valign="middle" class="ptTabelaAzul" style="padding: 5px; position:absolute;padding-top: 10px;padding-bottom: 10px;">
                <b>Phartrials</b><br>
                <p class="ptTexto" style="font-weight: normal;">(Aprovador)</p>
            </td>
            <td style="border:1px solid #000000; padding: 5px;" ></td>
            <td style="border:1px solid #000000; padding: 5px;" ></td>
        </tr>
        <tr>
            <td align="center" valign="middle" class="ptTabelaAzul" style="padding: 5px; position:absolute;padding-top: 10px;padding-bottom: 10px;">
                <b>Patrocinador</b><br>
                <p class="ptTexto" style="font-weight: normal;">(Aprovador)</p>
            </td>
            <td style="border:1px solid #000000; padding: 5px;" ></td>
            <td style="border:1px solid #000000; padding: 5px;" ></td>
        </tr>
    </table>
    ';
    $mpdf->WriteHTML($html);



    $mpdf->Output();
	$mpdf->charset_in='UTF-8';