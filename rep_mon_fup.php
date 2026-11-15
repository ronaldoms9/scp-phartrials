<?
	/*Acentuação teste chão*/
	session_start();
	if($_SESSION['valido']<1){echo "<script>window.location.replace('expirado.php');</script>";};
	ini_set("pcre.backtrack_limit", "5000000");

	include_once __DIR__ . "/class/conexao.class.php";
	require_once __DIR__ . '/vendor/autoload.php';
	include_once __DIR__ . "/class/agenda.class.php";
	include_once __DIR__."/class/usuario.class.php";
	include_once __DIR__."/definicoes.php";
	include_once __DIR__ . "/class/projeto.class.php";
	include_once __DIR__ . "/funcoes.php";


	$agendamento    = $_GET['id'];
	$varAgendamento = new Agenda($agendamento);

	$varConexao     = new Conexao();

	$mes_corrido[1] = "Janeiro";
	$mes_corrido[2] = "Fevereiro";
	$mes_corrido[3] = "Março";
	$mes_corrido[4] = "Abril";
	$mes_corrido[5] = "Maio";
	$mes_corrido[6] = "Junho";
	$mes_corrido[7] = "Julho";
	$mes_corrido[8] = "Agosto";
	$mes_corrido[9] = "Setembro";
	$mes_corrido[10] = "Outubro";
	$mes_corrido[11] = "Novembro";
	$mes_corrido[12] = "Dezembro";


	function deixa_bonito($entrada){
		$temp = strtoupper(substr($entrada, 0,1));
		$temp .= strtolower(substr($entrada, 1,strlen($entrada)));
		return $temp;
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
		return preg_replace(array("/(á|à|ã|â|ä)/","/(Ý|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Ý|Ì|Î|Ý)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a A e E i I o O u U n N"),$string);
	}


	function formatar_texto_bd($entrada){
		return utf8_encode($entrada);
	}
	$stylesheet = "
		.referencia{
			font-size:11pt;
			font-weight: bold;
			text-align: center;
		}
		.paragrafo{
			font-size:10pt;
		}
		.paragrafo1{
			font-size:10pt;
			font-weight: bold;
		}
		.versao_cabecalho{
			font-size:8pt;
			font-weight: bold;
			font-family: Times New Roman;
		}
		.footer_versao{
			font-size:8pt;
			font-weight: bold;
			font-family: Times New Roman;
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
		.dados_centro{
			font-family: 'Times new Roman';
			font-size: 10pt;
			font-weight: bold;
			line-height: 18px;
		}
		.tabela_recebimento{
			font-family: 'Times new Roman';
			font-size: 10pt;
			line-height: 29px;
			border:1;
			cellspacing:0;
			cellpadding:0;
			border-collapse: collapse;
			border: medium none;
			margin-left: 267.65pt
		}
		.recebimento_interno{
			font-family: 'Times new Roman';
			font-size: 9pt;
		}
		.conteudo_bold{
			font-family: 'Times new Roman';
			font-size: 9pt;
			font-weight:bold;
		}
		.conteudo_normal{
			font-family: 'Times new Roman';
			font-size: 9.5pt;
		}
		.paginacao{
			font-size:8.0pt;
		}
		.rodape{
			font-size:8.0pt;
			text-align:center;
		}
		.justificado{
			text-align: justify;
			letter-spacing: 0;
		}
			.texto_en{
			font-size:6.5pt;
		}
	";

	$query = "select
					agenda.*,
					cfg_centro.numero as centro_numero,
					cfg_centro.nome as centro_nome,
					cfg_centro.investigador  as centro_investigador,
					cfg_centro.coordenador as centro_coordenador,
					cfg_centro.endereco	as centro_endereco,
					usu_usuario.nome as monitora_nome
				 from agenda
					inner join cfg_centro on agenda.centro = cfg_centro.id
					left join usu_usuario on agenda.monitora = usu_usuario.id
				 where agenda.id = " . $agendamento;

	$res_agenda = $varConexao->pesquisa($query);


	$row_agenda = mysqli_fetch_assoc($res_agenda);

	$query 			= "select * from proj_projeto where id = " . $_SESSION['projeto'];
	$res_projeto	= $varConexao->pesquisa($query);
	$row_projeto    = mysqli_fetch_assoc($res_projeto);

	$varProjeto = new Projeto($_SESSION['projeto']);




	$mpdf = new \Mpdf\Mpdf(['tempDir' => __DIR__ . '/temp', 'debug' => true, 'mode' => 'utf-8', 'margin-top' => 1158, 'margin-header' => 1500 ]);

	$mpdf->setAutoTopMargin = 'stretch';
	$mpdf->setAutoBottomMargin = 'stretch';
	$mpdf->allow_charset_conversion=true;
	$mpdf->WriteHTML($stylesheet,1);


	$cabecalho = '
		<table style="width:100%;">
		  <tr>
			<td align="center">
			 <img src="logo.png" width="60">
			</td>
		   </tr>
		</table>
	';
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

	//Referência
	$html = '
			<div align="center"><span class="ptHeaderDestaque">
				'.traduzir("Carta de Follow Up").'
			</span></div>
			';
	$mpdf->WriteHTML($html);

	$tabela_ini = '
			<p class="ptTexto" style="text-align: justify; background: white">
			'.$_GET['data'].'
			</p>
			<p class="ptTexto" align="left" style="text-align: left;">
					<b>A/C '.formata_utf8_encode($row_agenda['centro_investigador']).'</b>
			<br>
				'.traduzir("Centro").': '.formata_utf8_encode($row_agenda['centro_nome']).'
			<br>
				'.traduzir("Endereço").': '.formata_utf8_encode(nl2br($row_agenda['centro_endereco'])).'
			</p>
			';
	$mpdf->WriteHTML($tabela_ini);

	$html = '
			<p class="ptTexto" style="text-align: justify; ; background: white">
			<b>
			Ref.: '.formata_utf8_encode($row_projeto['descricao']).'
			</b>
			</p>
			<p class="ptTexto" style="text-align: justify; ">
			<span class="ptTexto">
			'.traduzir("Prezado(a)").' '.formata_utf8_encode($row_agenda['centro_investigador']).',</span></p>
    ';
	$html .= '
			<p class="ptTexto" style="text-align: justify;  ">
				'.traduzir("O objetivo desta carta é destacar quais pontos do estudo supracitado foram verificados na Visita de Monitoria N&ordm;").'
				<b>'.str_pad(formata_utf8_encode($row_agenda['nro_visita']),2, "00", STR_PAD_LEFT).'</b>, '.traduzir("realizada em").'
				<b>'.formata_utf8_encode($varAgendamento->getDataInicial_e_Final()).'</b>, '.traduzir("e apontar as pendências que deverão ser resolvidas ao longo dos").'
				'.traduzir("próximos dias").'
			';
			if(strtoupper($row_agenda['tipo_monitoria'])!="VISITA FINAL"){$html .= ", ".traduzir("antes da monitoria final.");}else{$html .= " ".traduzir(", encaminhadas através da carta resposta de monitoria juntamente com as evidências.");}
		$html .=	'
			</p>
			<p></p>
			';

	$mpdf->WriteHTML($html);

	$html = '
			<p class="ptTexto" style="text-align: justify; ; background: white">
				<b>Pontos monitorados</b>
			</p>';
	$mpdf->WriteHTML($html);

	$html = '
	<table border="1" height="100%" width="100%"  cellspacing="0" cellpadding="0" bordercolor="#000000" style="border-collapse: collapse; padding: 5px;">
        <tr>
            <td class="ptTabelaAzul" style="padding: 5px;"><center><span class="ptTexto"><b>DATA E HORÁRIO DA<br>MONITORIA</b></span></center></td>
			<td class="ptTabelaAzul" style="padding: 5px;"><center><span class="ptTexto"><b>DOCUMENTOS/PROCEDIMENTOS A SEREM MONITORADOS</b></span></center></td>
        </tr>

	';

	//
	$query = "select * from agenda_atividade where agenda = ".$row_agenda['id'];
	$res_atividade = $varConexao->pesquisa($query);
	while($row_atividade = mysqli_fetch_assoc($res_atividade)){  //Linha de datas
		//Pegar as atividades
		$query = "select
						agenda_atividade.data,
						tab_agenda_atividades.id as id_atv,
						tab_agenda_atividades.atividade,
						agenda_atividade_atv.marcado
						from tab_agenda_atividades
							left join agenda_atividade_atv on tab_agenda_atividades.id = agenda_atividade_atv.id_tab_atividade
								and agenda_atividade_atv.id_atividade = ".$row_atividade['id']."
							left join agenda_atividade on agenda_atividade_atv.id_atividade = agenda_atividade.id
							where tab_agenda_atividades.ativo = 1 and agenda_atividade_atv.marcado = 1";
		$res_atividade_atv = $varConexao->pesquisa($query);
		$qtdAtv = mysqli_num_rows($res_atividade_atv);
		$iAtv = 1;
		while($row_atividade_atv = mysqli_fetch_assoc($res_atividade_atv)){
			$html .= '
				<tr>
			';
			if($iAtv == 1){
				$html .= '<td class="" style="vertical-align: top;padding: 5px;" rowspan="'.$qtdAtv.'" width="210px">
								<center>
									<span class="ptTexto">'.formata_utf8_encode($row_atividade['data']).' ('.formata_utf8_encode($row_atividade['periodo']).')</span>
								</center>
						  </td>';
			}
			$html.=		'<td class="" style="padding: 5px;"><span class="ptTexto">'.formata_utf8_encode($row_atividade_atv['atividade']).'</span></td>
				</tr>
			';
			$iAtv++;
		}
	}


	$html .= '
	</table>
	';
	$mpdf->WriteHTML($html);



	$html = '<br>
			<p class="ptHeaderDestaque" style="text-align: justify; ; background: white">
				<b>'.traduzir("Lista de Achados e Pendências").'</b>
			</p>

			<div align="center">
				<table width="100%" class="MsoNormalTable" border="1" cellspacing="0" cellpadding="0" style="border-collapse: collapse; border: medium none">
					<tr>
						<td colspan="5" valign="top" style="border: 1.0pt solid black; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm">
						<p class="paragrafo" align="center" style="text-align:center"><b>
						<span lang="EN-GB" style="font-size:12.0pt;
			  font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">'.traduzir("Lista de Pendências da Visita").' '.str_pad($row_agenda['nro_visita'],2, "00", STR_PAD_LEFT).'</span></b></td>
					</tr>

					<tr>
						<td width="110" valign="top" style="border-left: 1.0pt solid black; border-right: 1.0pt solid black; border-top: 1.0pt solid black; border-bottom: 1.0pt solid black; padding-left: 5.4pt; 	padding-right: 5.4pt; padding-top: 4px; padding-bottom: 4px">
						<center><p class="MsoNormal" align="center" style="text-align:center"><b>
						<span lang="EN-GB" style="font-size:11.0pt;  font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">'.traduzir("Participante").'</span></b></center></td>

						<td width="120" valign="top" style="border: 1.0pt solid black; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm">
						<center><p class="MsoNormal" align="left" style="text-align:center"><b>
						<span lang="EN-GB" style="font-size:11.0pt;  font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">'.traduzir("Tipo").'</span></b></center></td>



						<td width="40%" valign="top" style="border-left: medium none; border-right: 1.0pt solid black; border-top: 1.0pt solid black; border-bottom: 1.0pt solid black; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 4px; padding-bottom: 4px">
						<center><p class="MsoNormal" align="center" style="text-align:center"><b>
						<span lang="EN-GB" style="font-size:11.0pt;
			  font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">'.traduzir("Descrição").'</span></b></center></td>
			  <td width="40%" valign="top" style="border-left: medium none; border-right: 1.0pt solid black; border-top: 1.0pt solid black; border-bottom: 1.0pt solid black; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 4px; padding-bottom: 4px">
						<center><p class="MsoNormal" align="center" style="text-align:center"><b>
						<span lang="EN-GB" style="font-size:11.0pt;
			  font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">'.traduzir("Ação").'</span></b></center></td>
						<td width="100" valign="top" style="border-left: medium none; border-right: 1.0pt solid black; border-top: 1.0pt solid black; border-bottom: 1.0pt solid black; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 4px; padding-bottom: 4px">
						<center><p class="MsoNormal" align="center" style="text-align:center"><b>
						<span lang="EN-GB" style="font-size:11.0pt;
			  font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">'.traduzir("Status").'</span></b></center></td>
					</tr>
	';
			if($_SESSION['projeto']=="7"){
				$query_t = "select * from agenda_lista_pendencia where visita = ".$row_agenda['id']." and centro = ".$row_agenda['centro']." and status = 'PENDENTE' order by id";
			}else{
				if(strtoupper($row_agenda['tipo_monitoria'])!="VISITA FINAL"){
					$dataVis = $row_agenda['data'];
					$dataVis = substr($dataVis, 6,2) . "/" . substr($dataVis, 4,2) . "/" .substr($dataVis, 0,4);
					$query_t = "select * from agenda_lista_pendencia where visita = '".$row_agenda['nro_visita']."' and centro = '".$varAgendamento->centro->nome."' and projeto = ".$_SESSION['projeto']." order by paciente";
				}else{
					$dataVis = $row_agenda['data'];
					$dataVis = substr($dataVis, 6,2) . "/" . substr($dataVis, 4,2) . "/" .substr($dataVis, 0,4);
					$query_t = "select * from agenda_lista_pendencia where projeto = ".$_SESSION['projeto']." and centro = '".$varAgendamento->centro->nome."' order by paciente";
				}
			}
			//echo $query_t;


			$res_p = $varConexao->pesquisa($query_t);
			if(mysqli_num_rows($res_p)>0){
				while($row_p = mysqli_fetch_assoc($res_p)){

					$html .= '<tr>
						<td style="border-left: medium none; border-right: 1.0pt solid black;  border-left: 1.0pt solid black;border-top: medium none; border-bottom: 1.0pt solid black; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm; background: white">
							<p class="MsoNormal" align="center" style="text-align:center">
								<span class="ptTexto">
								<center>';
					$paciente = formatar_texto_bd($row_p['paciente']);
					if($paciente==""){$paciente = "N/A";}
					$html .= $paciente.'</center>
								</span>
							</p>
						</td>

						<td style="border-left: 1.0pt solid black; border-right: 1.0pt solid black; border-top: medium none; border-bottom: 1.0pt solid black; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 5px; padding-bottom: 5px; background: white">
						<center>
							<p class="MsoNormal" align="center" style="text-align:center">
								<span class="ptTexto">
								'.traduzir(deixa_bonito(formatar_texto_bd($row_p['tipo']))).'
								</span>
							</p>
							</center>
						</td>
						<td style="border-left: 1.0pt solid black; border-right: 1.0pt solid black; border-top: medium none; border-bottom: 1.0pt solid black; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 5px; padding-bottom: 5px; background: white">
							<p class="MsoNormal" align="center" style="text-align:center">
								<span class="ptTexto">
								'.traduzir(formatar_texto_bd($row_p['achado'])).'
								</span>
							</p>
						</td>
						<td style="border-left: medium none; border-right: 1.0pt solid black; border-top: medium none; border-bottom: 1.0pt solid black; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 5px; padding-bottom: 5px; background: white">
							<p class="MsoNormal" align="center" style="text-align:center">
									<span class="ptTexto">
								'.nl2br(traduzir(formatar_texto_bd($row_p['acao']))).'
								</span>
							</p>
						</td>
						<td valign="middle" style="border-left: medium none; border-right: 1.0pt solid black; border-top: medium none; border-bottom: 1.0pt solid black; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 5px; padding-bottom: 5px; background: white">
							<center><p class="MsoNormal" style="text-align:justify;">
								<span class="ptTexto">
								'.traduzir(deixa_bonito(formatar_texto_bd($row_p['status']))).'
								</span></center>
							</p>
						</td>
					</tr>';
				}
			}else{
				$html .= '<tr><td colspan=5> Nenhuma pendência encontrada.</td></tr>';
			}
			$html .= '
		</table>
	</div>
	';
	$mpdf->WriteHTML($html);


	$mpdf->AddPage();
	$query = "
		select
			agenda.id, agenda.monitora, usu_usuario.nome, usu_usuario.assinatura
			from
				agenda
			   left join usu_usuario on agenda.monitora = usu_usuario.id
			   where agenda.id = " . $agendamento;
	$res_monitora = $varConexao->pesquisa($query);
	$row_monitora = mysqli_fetch_assoc($res_monitora);

	$html =  '

		<p class="conteudo_normal">
			'.traduzir("Caso haja alguma dúvida, estou à disposição no celular ".$varAgendamento->monitor->celular." ou pelo e-mail ".$varAgendamento->monitor->email.".").'
		</p>

		<p class="conteudo_normal"><br>
		'.traduzir("Atenciosamente,").'<br><br>

		<br>
		________________________________________<br>
		'.formata_utf8_encode($row_monitora['nome']).'<br>
		'.traduzir("Monitor(a) de Estudos Clínicos").'
	';
	$mpdf->WriteHTML($html);

	$html =  '
		</ul></p><p></p><br><br>
		<p class="conteudo_normal"><b><br><br>
			<b>'.traduzir('Confirmação de recebimento').':</b>
		</p>

		<br>
		________________________________________<br>
		'. formata_utf8_encode($varAgenda->centro->quem_assina == "PI" ? $varAgenda->centro->investigador : $varAgenda->centro->coordenador). '<br>
	';
	$mpdf->WriteHTML($html);

	$mpdf->Output();
	$mpdf->charset_in='UTF-8';
?>