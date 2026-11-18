<?php
    /*TESTE DE ACENTUAÇÃO PARA RELATÓRIO */
    if(!isset($_SESSION)){  session_start();    }
	/*
	ATENÃ‡ÃƒO:
	Para nÃ£o deixar o problema na tabela (redimensionamento), eu reduzi a margem para cima e tirei o table_auto_fit
	assim nao da o problema
	*/

	include_once __DIR__."/seguranca.php";
	include_once __DIR__."/class/agenda.class.php";
	include_once __DIR__."/class/projeto.class.php";
    include_once __DIR__."/class/centro.class.php";
    require_once __DIR__ . '/vendor/autoload.php';


	$conexao = new Conexao();
    $varConexao = new Conexao();
	$varProjeto = new Projeto($_SESSION['projeto']);
    $varAgenda  = new Agenda($_GET['id']);

    //Pegar o centro 1 do projeto
    $query = "select id from cfg_centro where projeto = " . $varProjeto->id;
    $resCen= $varConexao->pesquisa($query);
    $rowCen= mysqli_fetch_assoc($resCen);
    $varCentro = new CentroPesquisa($rowCen['id']);


    $meses = array("Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro");
    $dataAgenda = date("d") . " de " . $meses[date("m")-1] . " de " . date("Y");


    function busca_valor_monitoria($agendamento,$campo){
        $varConexao = new Conexao();
        $query      = "select
                        crf_agenda_campos.id,
                        crf_agenda_campos.valor,
                        crf_agenda_campos.comentario
                            from crf_agenda_campos
                                inner join crf_agenda_forms on crf_agenda_campos.form_id = crf_agenda_forms.id
                                where
                                    crf_agenda_forms.agendamento = " .$agendamento . "
                                    and campo_id = " .$campo . " ";
    $resTemp    = $varConexao->pesquisa($query);
    $rowTemp    = mysqli_fetch_assoc($resTemp);
    return $rowTemp['valor'];

    }
	function formatar_texto_bd($entrada){		return utf8_encode($entrada);	}
	function formatar_texto($entrada){		return utf8_encode($entrada);	}
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
		return $temp_entrada;
	}
	function tirarAcentos($string){
		return preg_replace(array("/(Ã¡|Ã |Ã£|Ã¢|Ã¤)/","/(Ã�|Ã€|Ãƒ|Ã‚|Ã„)/","/(Ã©|Ã¨|Ãª|Ã«)/","/(Ã‰|Ãˆ|ÃŠ|Ã‹)/","/(Ã­|Ã¬|Ã®|Ã¯)/","/(Ã�|ÃŒ|ÃŽ|Ã�)/","/(Ã³|Ã²|Ãµ|Ã´|Ã¶)/","/(Ã“|Ã’|Ã•|Ã”|Ã–)/","/(Ãº|Ã¹|Ã»|Ã¼)/","/(Ãš|Ã™|Ã›|Ãœ)/","/(Ã±)/","/(Ã‘)/"),explode(" ","a A e E i I o O u U n N"),$string);
	}
	function traduzirRep($texto_entrada){
		$conexao = new Conexao();
		$texto = "";
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

	$stylesheet = "
        .td_conteudo{
            border:1px solid #000000;
            padding: 7pt;
            text-align:justify;
            line-height: 1.5 !important;
            font-size:10.0pt;
            font-family: Arial;
        }
        .td_conteudo_sem_borda{
            border:none;
            padding: 7pt;
            text-align:justify;
            line-height: 1.5 !important;
            font-size:10.0pt;
            font-family: Arial;
        }

		.texto_conteudo{
			font-size:10.0pt;
            text-align: justify;
            font-family: Arial;
            line-height: 5.5px !important;

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
		.texto_check{
			font-size:7.0pt;
		}
		.texto_ordem{
			font-size:8.0pt;
			font-weight: bold;
		}
		.texto_en{
			font-size:6.0pt;
		}
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
		.texto_formulario{
			font-size: 8pt;
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
			background: #D9D9D9
		}
		.header_json{
			border: 1.0pt solid windowtext;
			padding-left: 5.4pt;
			padding-right: 5.4pt;
			padding-top: 4pt;
			padding-bottom: 3pt;
			background: #F2F2F2
		}
		.header_json_pt{
			font-size: 6.0pt

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
		.justificado{
			text-align: justify;
			letter-spacing: 0;
		}
		.centralizado{
			text-align: center;
			letter-spacing: 0;
		}
        .ptTabelaAzul{
			font-size       : 10.0pt;
			font-weight     : bold;
			background-color: #DBE5F1;
			color           : black;
			text-align      : center !important;
			padding-left    : 4.5pt;
			padding-right   : 4.5pt;
			padding-top     : 2.5pt;
			padding-bottom  : 2.5pt !important;
		}
        .ptTabelaTexto{
			font-size       : 10.0pt;
			/*font-weight     : bold;*/
			background-color: white;
			color           : black;
			text-align      : center !important;
			padding-left    : 4.5pt;
			padding-right   : 4.5pt;
			padding-top     : 4.5pt;
			padding-bottom  : 4.5pt
            border          : 1px solid #000000;
		}
        .ptTabelaLegenda{
			font-size       : 10.0pt;
			/*font-weight     : bold;*/
			background-color: white;
			color           : black;
			text-align      : center !important;
			padding-left    : 4.5pt;
			padding-right   : 4.5pt;
			padding-top     : 1.5pt;
		}
        .ptTexto{
			font-size: 100px !important;
			font-weight: normal;
		}
	";

	$mpdf = new \Mpdf\Mpdf([
								'tempDir' => __DIR__ . '/temp',
								'debug' => true,
								'mode' => 'utf-8',
								'margin-top' => 100,
								'margin-header' => 200,
								'margin_bottom' => 30
							]);


    $mpdf->keep_table_proportions = true;
    $mpdf->shrink_tables_to_fit=1;
	//$mpdf->shrink_tables_to_fit=0;
	$mpdf->setAutoTopMargin = 'stretch';
	$mpdf->allow_charset_conversion=true;

    $mpdf->WriteHTML($stylesheet,1);

	$cabecalho = '<table
					border="1"
					width="100%"
					cellspacing="0"
					cellpadding="0"
					bordercolor="#000000"
					style="border-collapse: collapse;">
                        <tr style="border:1px solid #000000;">
                            <td width="142" style="border:1px solid #000000;"><center><br><img src="logo.png" width="45"></center><br><br><br></td>
                            <td style="border:1px solid #000000;">
                            <p class="MsoNormal" align="center" style="text-align:center;line-height:150%"><b><span style="font-size: 12.0pt; line-height: 150%; font-family: Verdana,sans-serif"><center>Relatório Consolidado de Monitoria</center></span></b></td>
                            <td style="border:1px solid #000000;">
                                <p class="MsoNormal" align="center" style="text-align:center;line-height:150%"><b><span style="font-size: 8.0pt; line-height: 150%; font-family: Arial"><center>Versão: 01<br>Data da Versão: 14/06/2024</center></span></b></td>
                        </tr>
                </table>
                <br>
                <table
					border="1"
					height="80%"
					width="100%"
					cellspacing="0"
					cellpadding="0"
					bordercolor="#000000"
					style="border-collapse: collapse;">
                        <tr style="border:1px solid #000000;">
                            <td style="border:1px solid #000000; padding: 7pt">
                                <p class="MsoNormal" align="center" style="text-align:center;line-height:150%">
                                <span style="font-size: 9.0pt; line-height: 150%; font-family: Arial"><b>Formulação:</b></span><span style="font-size: 10.0pt; line-height: 150%; font-family: Arial"> '.formatar_texto_bd($varProjeto->nome).'</span>
                            </td>
                            <td style="border:1px solid #000000;">
                            <table width="100%" border="0" cellspacing="0">
                                <tr >
                                    <td style="padding: 5pt; border-bottom: 1.0pt solid black"><span style="font-size: 9.0pt; line-height: 150%; font-family: Arial"><b>Código do Projeto:</b></span></t>
                                    <td style="padding: 5pt; border-bottom: 1.0pt solid black"><span style="font-size: 9.0pt; line-height: 150%; font-family: Arial"><center>'.$varProjeto->numero.'</center></span></t>
                                </tr>
                                <tr>
                                    <td style="padding: 5pt; border-bottom: 1.0pt solid black"><span style="font-size: 9.0pt; line-height: 150%; font-family: Arial"><b>Versão do relatório:</b></span></t>
                                    <td style="padding: 5pt; border-bottom: 1.0pt solid black"><span style="font-size: 9.0pt; line-height: 150%; font-family: Arial">'.$varAgenda->versao.'</span></t>
                                </tr>
                                <tr>
                                    <td style="padding: 5pt"><span style="font-size: 9.0pt; line-height: 150%; font-family: Arial"><b>Data de emissão:</b></span></t>
                                    <td style="padding: 5pt"><span style="font-size: 9.0pt; line-height: 150%; font-family: Arial">'.date("d/m/Y").'</span></t>
                                </tr>
                            </table>
                        </tr>
                </table>

	';
	$rodape = '
		<table width="100%"
				style="text-align:center"
				valign="bottom">
		  <tr style="text-align:left">
			<td width="33%" align="left" valign="middle" style="text-align:left; valign:middle" >
				<span class="rodape">Template vr 1.0 - 14/06/2024<br>
				</span>
			</td>
			<td width="33%" valign="bottom" align="center" style="text-align:center">
				<span class="rodape"><b>PHARTRILS </b><br>
				Telefone   +0055 (19) telefone<br>
				www.site.com.br</span>
			</td>
			<td width="33%" valign="bottom" style="text-align:right">
				<span class="paginacao">{PAGENO}/{nbpg}</font>
			</td>
		 </tr>
		</table>
	';

	$mpdf->SetHTMLFooter($rodape);
	$mpdf->SetHTMLHeader($cabecalho);


    $html = '<br>
                <table
                border="1"
                height="80%"
                width="100%"
                cellspacing="0"
                cellpadding="0"
                bordercolor="#000000"
                style="border-collapse: collapse;">

                    <tr style="border:1px solid #000000;">
                        <td style="border:1px solid #000000;padding: 7pt;text-align:justify" width="160">
                            <span style="font-size: 9.0pt; line-height: 150%; font-family: Arial"><b>Título</b></span>
                        </td>
                        <td class="td_conteudo">
                            <span class="texto_conteudo"> '.formatar_texto_bd($varProjeto->descricao).'</span>
                        </td>
                    </tr>
                    <tr style="border:1px solid #000000;">
                        <td style="border:1px solid #000000; padding: 7pt" width="160">
                            <span style="font-size: 9.0pt; line-height: 150%; font-family: Arial"><b>Código do estudo</b></span>
                        </td>
                        <td class="td_conteudo">'.formatar_texto_bd($varProjeto->codigo).'</td>
                    </tr>
                    <tr style="border:1px solid #000000;">
                        <td style="border:1px solid #000000; padding: 7pt" width="160">
                            <span style="font-size: 9.0pt; line-height: 150%; font-family: Arial"><b>Versão / Data de emissão do protocolo</b></span>
                        </td>
                        <td class="td_conteudo">'.formatar_texto_bd($varProjeto->versao).'</td>
                    </tr>
                    <tr style="border:1px solid #000000;">
                        <td style="border:1px solid #000000; padding: 7pt" width="160">
                            <span style="font-size: 9.0pt; line-height: 150%; font-family: Arial"><b>Nome do centro</b></span>
                        </td>
                        <td style="border:1px solid #000000; padding: 7pt; text-align:justify">
                            <span class="texto_conteudo">'.formatar_texto_bd($varCentro->nome).'</span>
                        </td>
                    </tr>
                    <tr style="border:1px solid #000000;">
                        <td style="border:1px solid #000000; padding: 7pt" width="160">
                            <span style="font-size: 9.0pt; line-height: 150%; font-family: Arial"><b>Endereço</b></span>
                        </td>
                        <td style="border:1px solid #000000; padding: 7pt; text-align:justify">
                            <span class="texto_conteudo">'.formatar_texto_bd(nl2br($varCentro->endereco)).'</span>
                        </td>
                    </tr>
                    <tr style="border:1px solid #000000;">
                        <td style="border:1px solid #000000; padding: 7pt" width="160">
                            <span style="font-size: 9.0pt; line-height: 150%; font-family: Arial"><b>Investigador principal</b></span>
                        </td>
                        <td style="border:1px solid #000000; padding: 7pt; text-align:justify">
                            <span class="texto_conteudo">'.formatar_texto_bd(nl2br($varCentro->investigador)).'</span>
                        </td>
                    </tr>
                    <tr style="border:1px solid #000000;">
                        <td style="border:1px solid #000000; padding: 7pt" width="160">
                            <span style="font-size: 9.0pt; line-height: 150%; font-family: Arial"><b>Patrocinador</b></span>
                        </td>
                        <td style="border:1px solid #000000; padding: 7pt; text-align:justify">
                            <span class="texto_conteudo">'.formatar_texto_bd($varProjeto->patrocinador->nome).'</span>
                        </td>
                    </tr>
                    <tr style="border:1px solid #000000;">
                        <td style="border:1px solid #000000; padding: 7pt" width="160">
                            <span style="font-size: 9.0pt; line-height: 150%; font-family: Arial"><b>Monitoria clínica</b></span>
                        </td>
                        <td style="border:1px solid #000000; padding: 7pt; text-align:justify">
                            <span class="texto_conteudo">Phartrials Pesquisas Farmacêuticas</span>
                        </td>
                    </tr>

            </table>
    ';
    $mpdf->WriteHTML($html);
    $mpdf->AddPage();

    $query      = "select valor
                            from crf_agenda_campos
                                inner join crf_agenda_forms on crf_agenda_campos.form_id = crf_agenda_forms.id
                                where crf_agenda_forms.agendamento = " .$varAgenda->id . " and campo_id = 2869 ";
    //echo $query . '<br>';
    $resTemp    = $varConexao->pesquisa($query);
    $rowTemp    = mysqli_fetch_assoc($resTemp);

    $query = "SELECT agenda.id, agenda.data, agenda.monitora, agenda.revisor, agenda.tipo_monitoria,
			monitor.nome as nome_monitor, monitor.assinatura as ass_monitor, monitor.tipo_usuario as tipo_monitor,
			revisor.nome as nome_revisor, revisor.assinatura as ass_revisor
			FROM agenda
				left join usu_usuario as monitor on agenda.monitora = monitor.id
				left join usu_usuario as revisor on agenda.revisor = revisor.id

				where agenda.id = " . $_GET['id'];
    $resAss = $varConexao->pesquisa($query);
    $rowAss = mysqli_fetch_assoc($resAss);

    $html = '<br>
            <p>
            <span style="font-size: 9.0pt; line-height: 150%; font-family: Arial"><b>1. DECLARAÇÃO DE CONFORMIDADE</b></span>
            </p>
            <p style="text-align:justify" class="td_conteudo_sem_borda">
                <span class="texto_conteudo">'."A presente monitoria foi contratada pelo Patrocinador, ocorreu de forma independente, e foi realizada conforme procedimentos internos da CRO, atendendo aos requisitos das Diretrizes Harmonizadas do ICH para Boas Práticas Clínicas E6 (R2 e R3) e à legislação vigente da Agência Nacional de Vigilância Sanitária (ANVISA) e ao Protocolo do Estudo, bem como nos Princípios da Declaração de Helsinque vigentes, requisitos regulatórios aplicáveis e procedimentos operacionais padrão internos do centro, sempre que aplicável.".'</span>
            </p>
            <br><br><br>
    ';
    $mpdf->WriteHTML($html);

    $html = '
        <table width="100%" border="0" cellspacing="3">
            <tr>
                <td width="30%" style="text-align:center;padding: 3pt;font-size: 10.0pt; line-height: 150%; font-family: Arial">Jessica Marise Crus Rodrigues </td>
                <td width="5%"></td>
                <td width="30%" style="text-align:center;padding: 3pt;font-size: 10.0pt; line-height: 150%; font-family: Arial"></td>
                <td width="5%"></td>
                <td width="30%" style="text-align:center;padding: 3pt;font-size: 10.0pt; line-height: 150%; font-family: Arial">'.$dataAgenda.'</td>
            </tr>
            <tr>
                <td width="30%" style="border-top: 1.0pt solid black;text-align:center;padding: 3pt;font-size: 10.0pt; line-height: 150%; font-family: Arial">Monitor clínico responsável</td>
                <td width="5%"></td>
                <td width="30%" style="border-top: 1.0pt solid black;text-align:center;padding: 3pt;font-size: 10.0pt; line-height: 150%; font-family: Arial">Assinatura</td>
                <td width="5%"></td>
                <td width="30%" style="border-top: 1.0pt solid black;text-align:center;padding: 3pt;font-size: 10.0pt; line-height: 150%; font-family: Arial">Data</td>
            </tr>
        </table>
    ';
    $mpdf->WriteHTML($html);

    $html = '<br><br><br>
        <table width="100%" border="0" cellspacing="3">
            <tr>
                <td width="30%" style="text-align:center;padding: 3pt;font-size: 10.0pt; line-height: 150%; font-family: Arial">Amanda A. S. Gondim </td>
                <td width="5%"></td>
                <td width="30%" style="text-align:center;padding: 3pt;font-size: 10.0pt; line-height: 150%; font-family: Arial"></td>
                <td width="5%"></td>
                <td width="30%" style="text-align:center;padding: 3pt;font-size: 10.0pt; line-height: 150%; font-family: Arial">'.$dataAgenda.'</td>
            </tr>
            <tr>
                <td width="30%" style="border-top: 1.0pt solid black;text-align:center;padding: 3pt;font-size: 10.0pt; line-height: 150%; font-family: Arial">Coordenadora de monitoria clínica</td>
                <td width="5%"></td>
                <td width="30%" style="border-top: 1.0pt solid black;text-align:center;padding: 3pt;font-size: 10.0pt; line-height: 150%; font-family: Arial">Assinatura</td>
                <td width="5%"></td>
                <td width="30%" style="border-top: 1.0pt solid black;text-align:center;padding: 3pt;font-size: 10.0pt; line-height: 150%; font-family: Arial">Data</td>
            </tr>
        </table>
    ';
    $mpdf->WriteHTML($html);

    $mpdf->AddPage();

    //Campo 3627 - Resumo
    $html = '<br>
        <p style="text-align:justify">
        <span style="font-size: 9.0pt; line-height: 150%; font-family: Arial; text"><b>2. RESUMO</b></span>
        </p>
        <p style="text-align:justify">
            <span style="font-size: 10.0pt; line-height: 150%; font-family: Arial">'.formatar_texto_bd(nl2br(busca_valor_monitoria($varAgenda->id,3627))).'</span>
        </p>
    ';
    $mpdf->WriteHTML($html);


    //Campo 3628 - Medicamentos
    $html = '
        <p><span style="font-size: 9.0pt; line-height: 150%; font-family: Arial"><b>3. MEDICAMENTOS DO ESTUDO</b></span></p>
        <p style="text-align:justify">
            <span style="font-size: 10.0pt; line-height: 150%; font-family: Arial">'.formatar_texto_bd(nl2br(busca_valor_monitoria($varAgenda->id,3628))).'</span>
        </p>
    ';
    $mpdf->WriteHTML($html);

    //Pegar o agendamento da Visita inicial 1.
    $query = "select
                id
                    from agenda
                    where
                        projeto = ".$varProjeto->id."
                        and tipo_monitoria = 'Visita Inicial'
                        and nro_visita = 1    ";
    $resAgendamentoInicial = $varConexao->pesquisa($query);
    $rowAgendamentoInicial = mysqli_fetch_assoc($resAgendamentoInicial);
    $varAgendamentoInicial = new Agenda($rowAgendamentoInicial['id']);


    //Aqui vou montar uma tabela com os medicamentos do estudo
    //Depois da 1 leva tenho que mudar para pegar do JSON
    $html = '

            <center>
                <table width="100%" border="0" cellspacing="0">
                    <tr>
                        <td width="40%" style="border:0px"></td>
                        <td colspan="2" style="border-top:1px solid #000000;border-left:1px solid #000000;border-right:1px solid #000000;" class="ptTabelaAzul">INFORMAÇÕES DOS MEDICAMENTOS DO ESTUDO</td>
                    </tr>
                    <tr>
                        <td style="border:0px"></td>
                        <td style="border-top:1px solid #000000;border-left:1px solid #000000;border-right:1px solid #000000;" class="ptTabelaAzul">Medicamento Teste</td>
                        <td style="border-top:1px solid #000000;border-right:1px solid #000000;" class="ptTabelaAzul">Medicamento Referência</td>
                    </tr>
                    <tr>
                        <td style="border-top:1px solid #000000;border-left:1px solid #000000;" class="ptTabelaAzul">Nome Genérico Princípio ativo)</td>
                        <td style="border-top:1px solid #000000;border-left:1px solid #000000;" class="ptTabelaTexto"><center>'.formatar_texto_bd(nl2br(busca_valor_monitoria($varAgendamentoInicial->id,3330))).'</td>
                        <td style="border-top:1px solid #000000;border-left:1px solid #000000;border-right:1px solid #000000;" class="ptTabelaTexto"><center>'.formatar_texto_bd(nl2br(busca_valor_monitoria($varAgendamentoInicial->id,3323))).'</td>
                    </tr>
                    <tr>
                        <td style="border-top:1px solid #000000;border-left:1px solid #000000;" class="ptTabelaAzul">Nome fantasia</td>
                        <td style="border-top:1px solid #000000;border-left:1px solid #000000;" class="ptTabelaTexto"><center>'.formatar_texto_bd(nl2br(busca_valor_monitoria($varAgendamentoInicial->id,3331))).'</td>
                        <td style="border-top:1px solid #000000;border-left:1px solid #000000;border-right:1px solid #000000;" class="ptTabelaTexto"><center>'.formatar_texto_bd(nl2br(busca_valor_monitoria($varAgendamentoInicial->id,3324))).'</td>
                    </tr>
                    <tr>
                        <td style="border-top:1px solid #000000;border-left:1px solid #000000;" class="ptTabelaAzul">Fabricante</td>
                        <td style="border-top:1px solid #000000;border-left:1px solid #000000;" class="ptTabelaTexto"><center>'.formatar_texto_bd(nl2br(busca_valor_monitoria($varAgendamentoInicial->id,3329))).'</td>
                        <td style="border-top:1px solid #000000;border-left:1px solid #000000;border-right:1px solid #000000;" class="ptTabelaTexto"><center>'.formatar_texto_bd(nl2br(busca_valor_monitoria($varAgendamentoInicial->id,3322))).'</td>
                    </tr>
                    <tr>
                        <td style="border-top:1px solid #000000;border-left:1px solid #000000;" class="ptTabelaAzul">Forma farmacêutica</td>
                        <td style="border-top:1px solid #000000;border-left:1px solid #000000;" class="ptTabelaTexto"><center>'.formatar_texto_bd(nl2br(busca_valor_monitoria($varAgendamentoInicial->id,3333))).'</td>
                        <td style="border-top:1px solid #000000;border-left:1px solid #000000;border-right:1px solid #000000;" class="ptTabelaTexto"><center>'.formatar_texto_bd(nl2br(busca_valor_monitoria($varAgendamentoInicial->id,3326))).'</td>
                    </tr>
                    <tr>
                        <td style="border-top:1px solid #000000;border-left:1px solid #000000;" class="ptTabelaAzul">Concentração</td>
                        <td style="border-top:1px solid #000000;border-left:1px solid #000000;" class="ptTabelaTexto"><center>'.formatar_texto_bd(nl2br(busca_valor_monitoria($varAgendamentoInicial->id,3332))).'</td>
                        <td style="border-top:1px solid #000000;border-left:1px solid #000000;border-right:1px solid #000000;" class="ptTabelaTexto"><center>'.formatar_texto_bd(nl2br(busca_valor_monitoria($varAgendamentoInicial->id,3325))).'</td>
                    </tr>
                    <tr>
                        <td style="border-top:1px solid #000000;border-left:1px solid #000000;" class="ptTabelaAzul">Condições de armazenamento</td>
                        <td style="border-top:1px solid #000000;border-left:1px solid #000000;" class="ptTabelaTexto"><center>'.formatar_texto_bd(nl2br(busca_valor_monitoria($varAgendamentoInicial->id,3334))).'</td>
                        <td style="border-top:1px solid #000000;border-left:1px solid #000000;border-right:1px solid #000000;" class="ptTabelaTexto"><center>'.formatar_texto_bd(nl2br(busca_valor_monitoria($varAgendamentoInicial->id,3327))).'</td>
                    </tr>
                    <tr>
                        <td style="border-top:1px solid #000000;border-left:1px solid #000000;" class="ptTabelaAzul">Número do Lote</td>
                        <td style="border-top:1px solid #000000;border-left:1px solid #000000;" class="ptTabelaTexto"><center>'.formatar_texto_bd(nl2br(busca_valor_monitoria($varAgendamentoInicial->id,3282))).'</td>
                        <td style="border-top:1px solid #000000;border-left:1px solid #000000;border-right:1px solid #000000;" class="ptTabelaTexto"><center>'.formatar_texto_bd(nl2br(busca_valor_monitoria($varAgendamentoInicial->id,3273))).'</td>
                    </tr>
                    <tr>
                        <td style="border-top:1px solid #000000;border-left:1px solid #000000;" class="ptTabelaAzul">Data de Fabricação</td>
                        <td style="border-top:1px solid #000000;border-left:1px solid #000000;" class="ptTabelaTexto"><center>'.formatar_texto_bd(nl2br(busca_valor_monitoria($varAgendamentoInicial->id,3283))).'</td>
                        <td style="border-top:1px solid #000000;border-left:1px solid #000000;border-right:1px solid #000000;" class="ptTabelaTexto"><center>'.formatar_texto_bd(nl2br(busca_valor_monitoria($varAgendamentoInicial->id,3274))).'</td>
                    </tr>
                    <tr>
                        <td style="border-top:1px solid #000000;border-left:1px solid #000000;" class="ptTabelaAzul">Prazo de Validade</td>
                        <td style="border-top:1px solid #000000;border-left:1px solid #000000;" class="ptTabelaTexto"><center>'.formatar_texto_bd(nl2br(busca_valor_monitoria($varAgendamentoInicial->id,3284))).'</td>
                        <td style="border-top:1px solid #000000;border-left:1px solid #000000;border-right:1px solid #000000;" class="ptTabelaTexto"><center>'.formatar_texto_bd(nl2br(busca_valor_monitoria($varAgendamentoInicial->id,3275))).'</td>
                    </tr>
                    <!--<tr>
                        <td style="border-top:1px solid #000000;border-left:1px solid #000000;" class="ptTabelaAzul">Data de Recebimento</td>
                        <td style="border-top:1px solid #000000;border-left:1px solid #000000;" class="ptTabelaTexto"><center>XXXXXXXXXX</td>
                        <td  style="border-top:1px solid #000000;border-left:1px solid #000000;border-right:1px solid #000000;" class="ptTabelaTexto"><center>XXXXXXXXXX</td>
                    </tr>-->
                    <tr>
                        <td style="border-top:1px solid #000000;border-left:1px solid #000000;" class="ptTabelaAzul">Quantidade total<br>Adiquirida/Recebida</td>
                        <td  style="border-top:1px solid #000000;border-left:1px solid #000000;" class="ptTabelaTexto"><center>'.formatar_texto_bd(nl2br(busca_valor_monitoria($varAgendamentoInicial->id,3286))).'</td>
                        <td  style="border-top:1px solid #000000;border-left:1px solid #000000;border-right:1px solid #000000;" class="ptTabelaTexto"><center>'.formatar_texto_bd(nl2br(busca_valor_monitoria($varAgendamentoInicial->id,3277))).'</td>
                    </tr>
                    <tr>
                        <td style="border-bottom:1px solid #000000;border-top:1px solid #000000;border-left:1px solid #000000;" class="ptTabelaAzul">Estoque Final</td>
                        <td colspan="2" style="border-bottom:1px solid #000000;border-top:1px solid #000000;border-left:1px solid #000000;border-right:1px solid #000000; padding-bottom: 4.5pt;" class="ptTabelaTexto"><center>Conforme relatório clínico</td>
                    </tr>
                    <tr>
                        <td colspan="3" style="border-bottom:0px solid #000000;" class="ptTabelaLegenda"><center><b>Tabela 1</b>. Informações dos medicamentos utilizados do estudo</td>
                    </tr>
                </table>
            </center>
    ';
    $mpdf->WriteHTML($html);

    //Confinamento

    $query  = "SELECT min(periodo) as minimo, max(periodo) as maximo FROM `agenda` where projeto = ".$_SESSION['projeto']." and tipo_monitoria = 'VISITA REGULAR'";
    $resPer = $varConexao->pesquisa($query);
    $rowPer = mysqli_fetch_assoc($resPer);
    $varPerIni = $rowPer['minimo'];
    $varPerFim = $rowPer['maximo'];



    $html = '
        <p>
            <span style="font-size: 10.0pt; line-height: 150%; font-family: Arial">O Estudo Clínico ocorreu com confinamentos nas seguintes datas:</span>
        </p>
        <p style="text-align: center !important!">
            <div align="center">
                <table width="100%" border="0" cellspacing="0" >
                    <tr>
                            <td align="center" style="border-top: 1pt solid black; border-bottom: 1pt solid black"><span style="font-size: 9.0pt; line-height: 150%; font-family: Arial"><b>Confinamento</span></td>
                            <td align="center" style="border-top: 1pt solid black; border-bottom: 1pt solid black"><span style="font-size: 9.0pt; line-height: 150%; font-family: Arial"><b>Grupo</span></td>
    ';

    for($iPer=$varPerIni;$iPer<=$varPerFim;$iPer++){
        $html .= '          <td align="center" style="border-top: 1pt solid black; border-bottom: 1pt solid black"><span style="font-size: 9.0pt; line-height: 150%; font-family: Arial"><b>Período '.$iPer.'</span></td>';
    }
    $html .= '

                    </tr>
    ';

    $mpdf->WriteHTML($html);
    $html = "";

    $query   = "SELECT min(grupo_periodo) as minimo, max(grupo_periodo) as maximo FROM `agenda` where projeto = ".$_SESSION['projeto']." and tipo_monitoria = 'VISITA REGULAR'";
    $resGrup = $varConexao->pesquisa($query);
    $rowGrup = mysqli_fetch_assoc($resGrup);
    $varGrpIni = $rowGrup['minimo'];
    $varGrpFim = $rowGrup['maximo'];

    for($iGrup=$varGrpIni;$iGrup<=$varGrpFim;$iGrup++){
        $borda ="";
        //if($iGrup==$varGrpFim){$borda = "border-top: 1pt solid black; border-bottom: 1pt solid black";}
        $html .= '
            <tr>
                <td align="center"  style="'.$borda.'"><span style="font-size: 8.0pt; line-height: 150%; font-family: Arial">Início</td>
                <td align="center"  style="'.$borda.'"><span style="font-size: 8.0pt; line-height: 150%; font-family: Arial">Grupo '.formatar_texto_bd($iGrup).'</td>
        ';


        for($iPer=$varPerIni;$iPer<=$varPerFim;$iPer++){
            $query = "SELECT
                        id,
                        grupo_periodo,
                        periodo,
                        conso_data_inicial,
                        conso_data_final
                            FROM `agenda`
                                where grupo_periodo = '$iGrup'
                                    and periodo = '$iPer'
                                    and tipo_monitoria = 'VISITA REGULAR'
                                    and  projeto =  " . $_SESSION['projeto'];

            $resVal= $varConexao->pesquisa($query);

            if(mysqli_num_rows($resVal)>0){
                $rowVal= mysqli_fetch_assoc($resVal);
                $html .= '          <td align="center" style="'.$borda.'"><span style="font-size: 8.0pt; line-height: 150%; font-family: Arial">'.formatar_texto_bd($rowVal['conso_data_inicial']).'</span></td>';
            }else{
                $html .= '          <td align="center" style="'.$borda.'"><span style="font-size: 8.0pt; line-height: 150%; font-family: Arial">-</span></td>';
            }
        }
        $html .= '
                </tr>
                <tr>
                    <td align="center"  style="'.$borda.'"><span style="font-size: 8.0pt; line-height: 150%; font-family: Arial">Término</td>
                    <td align="center"  style="'.$borda.'"><span style="font-size: 8.0pt; line-height: 150%; font-family: Arial">Grupo '.formatar_texto_bd($iGrup).'</td>
                    ';
        for($iPer=$varPerIni;$iPer<=$varPerFim;$iPer++){
            $query = "SELECT id, grupo_periodo, periodo, conso_data_inicial, conso_data_final FROM `agenda` where grupo_periodo = '$iGrup' and periodo = '$iPer' and tipo_monitoria = 'VISITA REGULAR' and  projeto =  " . $_SESSION['projeto'];
            $resVal= $varConexao->pesquisa($query);

            if(mysqli_num_rows($resVal)>0){
                $rowVal= mysqli_fetch_assoc($resVal);
                $html .= '          <td align="center" style="'.$borda.'" ><span style="font-size: 8.0pt; line-height: 150%; font-family: Arial">'.formatar_texto_bd($rowVal['conso_data_final']).'</span></td>';
            }else{
                $html .= '          <td align="center" style="'.$borda.'" ><span style="font-size: 8.0pt; line-height: 150%; font-family: Arial">-</span></td>';
            }
        }
        $html .= '
                </tr>
            ';
    }

    $html .= '
                <tr>
                    <td colspan="4" style="border-top: 1pt solid black; "><font color="white">xx</font></td>
                </tr>
                </table>
            </div>
        </p>
    ';
    $mpdf->WriteHTML($html);

    //Campo 3242 - Resumo das internações
    $html = '
        <p style="font-size: 10.0pt; line-height: 150%; font-family: Arial; text-align:justify">'.formatar_texto_bd(nl2br(busca_valor_monitoria($varAgenda->id,3242))).'</p>
    ';
    $mpdf->WriteHTML($html);


    //Campo 3243 - DESVIOS E NÃO-CONFORMIDADE
    $html = '
        <p style="font-size: 10.0pt; line-height: 150%; font-family: Arial"><b>4. DESVIOS E NÃO-CONFORMIDADE</b></p>
        <p style="text-align:justify">
            <span style="font-size: 10.0pt; line-height: 150%; font-family: Arial">'.formatar_texto_bd(nl2br(busca_valor_monitoria($varAgenda->id,3243))).'</span>
        </p>
    ';
    $mpdf->WriteHTML($html);

    //Campo 3244 - NOTIFICAÇÕES
    $html = '
        <p style="font-size: 10.0pt; line-height: 150%; font-family: Arial"><b>5. NOTIFICAÇÕES</b></p>
        <p style="text-align:justify">
            <span style="font-size: 10.0pt; line-height: 150%; font-family: Arial">'.formatar_texto_bd(nl2br(busca_valor_monitoria($varAgenda->id,3244))).'</span>
        </p>
    ';
    $mpdf->WriteHTML($html);

    //Campo 3245 - CONCLUSÃO
    $html = '
        <p style="font-size: 10.0pt; line-height: 150%; font-family: Arial"><b>6. CONCLUSÃO</b></p>
        <p style="text-align:justify">
            <span style="font-size: 10.0pt; line-height: 150%; font-family: Arial">'.formatar_texto_bd(nl2br(busca_valor_monitoria($varAgenda->id,3245))).'</span>
        </p>
    ';
    $mpdf->WriteHTML($html);







	$mpdf->Output();
	$mpdf->charset_in='UTF-8';



?>