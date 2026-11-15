<?php
	error_reporting(E_ERROR);
    ini_set('display_errors', 1);
	session_start();
	if($_SESSION['valido']<1){echo "<script>window.location.replace('expirado.php');</script>";};
	ini_set("pcre.backtrack_limit", "5000000");

	include_once __DIR__ . "/class/conexao.class.php";
	require_once __DIR__ . '/vendor/autoload.php';
	include_once __DIR__ . "/class/agenda.class.php";
    include_once __DIR__ . "/class/projeto.class.php";
	include_once __DIR__ . "/funcoes.php";

	$agendamento = $_GET['id'];
	$varAgendamento = new Agenda($agendamento);
    $varProjeto = new Projeto($_SESSION['projeto']);
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




	$mpdf = new \Mpdf\Mpdf(['tempDir' => __DIR__ . '/temp', 'debug' => true, 'mode' => 'utf-8', 'margin-top' => 1158, 'margin-header' => 1500 ]);

	$mpdf->setAutoTopMargin = 'stretch';
	$mpdf->setAutoBottomMargin = 'stretch';
	$mpdf->allow_charset_conversion=true;
	$mpdf->WriteHTML($stylesheet,1);


	$cabecalho = '
		<table style="width:100%;">
		  <tr>
            <td width="20%" style="border:1px solid #000000;"><center><br><img src="logo.png" width="50"></center><br><br><br></td>
			<td width="60%" style="border:1px solid #000000;">
				<p class="MsoNormal" align="center" style="text-align:center;line-height:150%"><b><span style="font-size: 12.0pt; line-height: 150%; font-family: Verdana,sans-serif"><center>
				</center></span></b>
			</td>
		   </tr>
		</table>
	';
    $cabecalho = '<table border="1" height="100%" width="100%"  cellspacing="0" cellpadding="0" bordercolor="#000000" style="border-collapse: collapse;">
		<tr style="border:1px solid #000000;">
			<td width="20%" style="border:1px solid #000000;"><center><br><img src="logo.png" width="50"></center><br><br><br></td>
			<td width="60%" style="border:1px solid #000000;">
				<p class="MsoNormal" align="center" style="text-align:center;line-height:150%"><b><span style="font-size: 12.0pt; line-height: 150%; font-family: Verdana,sans-serif"><center>'.traduzir("Carta de Follow Up").'
				</center></span></b>
			</td>
			<td width="20%" style="border:1px solid #000000;">
				<center>
					<span style="font-size: 9.0pt; line-height: 150%; font-family: Verdana,sans-serif"><b>'.$varProjeto->numero.'</b><br> '.$varProjeto->nome.'</span>
				</center>
			</td>
		</tr>
		</table>
	';

	$rodape = '
		<table width="100%" style="text-align:center">
		  <tr style="text-align:center">
			<td width="90%" align="center" style="text-align:center">
				<span class="rodape"><b>PHARTRIALS</b><br>
				Telefone   +0055 (19) telefone<br>
				www.phartrials.com.br</span>
			</td>
			<td width="10%" style="text-align:right">
				<br><br><br><span class="paginacao">{PAGENO}/{nbpg}</font>
			</td>
		 </tr>
		</table>
	';

	$mpdf->SetHTMLFooter($rodape);
	$mpdf->SetHTMLHeader($cabecalho);

	//Referência
	$html = '
			<br><br>
			';
	$mpdf->WriteHTML($html);

	$tabela_ini = '
			<p class="paragrafo" style="text-align: justify; background: white">
			'.$_GET['data'].'
			</p>
			<p class="paragrafo" align="left" style="text-align: left;">
					<b>A/C '.formatar_texto_bd($row_agenda['centro_investigador']).'</b>
			<br>
				'.traduzir("Centro").': '.formatar_texto_bd($row_agenda['centro_nome']).'
			<br>
				'.traduzir("Endereço").': '.formatar_texto_bd(nl2br($row_agenda['centro_endereco'])).'
			</p>
			';
	$mpdf->WriteHTML($tabela_ini);
	$html = '
			<p class="conteudo_normal" style="text-align: justify; ; background: white">
			<b>
			Ref.: '.formatar_texto_bd($row_projeto['descricao']).'
			</b>
			</p>
			<p class="conteudo_normal" style="text-align: justify; ">
			<span style="font-size: 9.0pt; font-family: Verdana,sans-serif; color: black">
			'.traduzir("Prezado(a)").' '.formatar_texto_bd($row_agenda['centro_investigador']).',</span></p>

			<p class="conteudo_normal" style="text-align: justify;  ">
				'.traduzir("O objetivo desta carta é destacar quais pontos do estudo supracitado foram verificados na Visita de Monitoria N&ordm;").'
				<b>'.str_pad(formatar_texto_bd($row_agenda['nro_visita']),2, "00", STR_PAD_LEFT).'</b>, '.traduzir("realizada em").'
				<b>'.formatar_texto_bd($row_agenda['data_caption']).'</b>, '.traduzir("e apontar as pendências que deverão ser resolvidas ao longo dos").'
				'.traduzir("próximos dias").'
			';
			if($row_agenda['tipo_monitoria']!="FINAL"){$html .= ", ".traduzir("antes da monitoria final.");}else{$html .= " ".traduzir("e encaminhadas por email.");}
		$html .=	'
			</p>
			<p></p>
			';

	$mpdf->WriteHTML($html);

	$html = '
			<p class="conteudo_normal" style="text-align: justify; ; background: white">
				<b>Pontos monitorados</b>
			</p>
			<p class="conteudo_normal" style="text-align: justify; ; background: white">'.formatar_texto_bd(nl2br($row_agenda['atividades'])).'</p>	';
	$mpdf->WriteHTML($html);

	$html = '<br>
			<p class="conteudo_normal" style="text-align: justify; ; background: white">
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
						<td width="110" valign="top" style="border-left: 1.0pt solid black; border-right: 1.0pt solid black; border-top: 1.0pt solid black; border-bottom: 1.0pt solid black; padding-left: 5.4pt; 	padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm">
						<p class="MsoNormal" align="center" style="text-align:center"><b>
						<span lang="EN-GB" style="font-size:11.0pt;  font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">'.traduzir("Paciente").'</span></b></td>

						<td width="120" valign="top" style="border: 1.0pt solid black; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm">
						<p class="MsoNormal" align="left" style="text-align:center"><b>
						<span lang="EN-GB" style="font-size:11.0pt;  font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">'.traduzir("Tipo").'</span></b></td>



						<td width="40%" valign="top" style="border-left: medium none; border-right: 1.0pt solid black; border-top: 1.0pt solid black; border-bottom: 1.0pt solid black; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm">
						<p class="MsoNormal" align="center" style="text-align:center"><b>
						<span lang="EN-GB" style="font-size:11.0pt;
			  font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">'.traduzir("Descrição").'</span></b></td>
			  <td width="40%" valign="top" style="border-left: medium none; border-right: 1.0pt solid black; border-top: 1.0pt solid black; border-bottom: 1.0pt solid black; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm">
						<p class="MsoNormal" align="center" style="text-align:center"><b>
						<span lang="EN-GB" style="font-size:11.0pt;
			  font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">'.traduzir("Ação").'</span></b></td>
						<td width="100" valign="top" style="border-left: medium none; border-right: 1.0pt solid black; border-top: 1.0pt solid black; border-bottom: 1.0pt solid black; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm">
						<p class="MsoNormal" align="center" style="text-align:center"><b>
						<span lang="EN-GB" style="font-size:11.0pt;
			  font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">'.traduzir("Status").'</span></b></td>
					</tr>
	';
			if($_SESSION['projeto']=="7"){
				$query_t = "select * from agenda_lista_pendencia where visita = ".$row_agenda['id']." and centro = ".$row_agenda['centro']." and status = 'PENDENTE' order by id";
			}else{
				if($row_agenda['tipo_monitoria']!="FINAL"){
					$dataVis = $row_agenda['data'];
					$dataVis = substr($dataVis, 6,2) . "/" . substr($dataVis, 4,2) . "/" .substr($dataVis, 0,4);
					$query_t = "select * from agenda_lista_pendencia where visita = '".$row_agenda['nro_visita']."' and centro = '".$varAgendamento->centro->nome."' and projeto = ".$_SESSION['projeto']." order by id";
				}else{
					$dataVis = $row_agenda['data'];
					$dataVis = substr($dataVis, 6,2) . "/" . substr($dataVis, 4,2) . "/" .substr($dataVis, 0,4);
					$query_t = "select * from agenda_lista_pendencia where projeto = ".$_SESSION['projeto']." and centro = '".$varAgendamento->centro->nome."' order by id";
				}
			}
			//echo $query_t;


			$res_p = $varConexao->pesquisa($query_t);
			if(mysqli_num_rows($res_p)>0){
				while($row_p = mysqli_fetch_assoc($res_p)){

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
								'.traduzir(deixa_bonito(formatar_texto_bd($row_p['tipo']))).'
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
								'.nl2br(traduzir(formatar_texto_bd($row_p['acao']))).'
								</span>
							</p>
						</td>
						<td valign="center" style="border-left: medium none; border-right: 1.0pt solid black; border-top: medium none; border-bottom: 1.0pt solid black; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm; background: white">
							<p class="MsoNormal" style="text-align:justify;">
								<span style="font-size: 9.0pt;  font-family: Verdana,sans-serif;">
								'.traduzir(deixa_bonito(formatar_texto_bd($row_p['status']))).'
								</span>
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
			'.traduzir("Caso haja alguma dúvida, estou à disposição no celular (19)99242-4174 ou pelo e-mail monitoria@phartrials.com.br.").'
		</p>

		<p class="conteudo_normal">
		'.traduzir("Atenciosamente,").'<br>
		<img src="assinaturas/'.$row_monitora['assinatura'].'" width="160">
		<br>
		________________________________________<br>
		'.$row_monitora['nome'].'<br>
		'.traduzir("Monitor(a) de Estudos Clínicos").'
	';
	$mpdf->WriteHTML($html);

	$mpdf->Output();
	$mpdf->charset_in='UTF-8';
?>