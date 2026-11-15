<?php
	error_reporting(E_ERROR);
    ini_set('display_errors', 1);


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

	session_start();
	if($_SESSION['valido']<1){echo "<script>window.location.replace('expirado.php');</script>";};

	require_once __DIR__ . '/vendor/autoload.php';
	require_once __DIR__ . '/class/env.php';
	$servidor = env('DB_HOST', 'localhost');
	$conexao = new mysqli($servidor, env('DB_ROOT_USER', 'root'), env('DB_ROOT_PASS'), "synova_spc_teste_pena");

	function traduzir($texto_entrada){
		$texto = "";
		//if(($texto_entrada!="Não")||($texto_entrada!="NÃO")){
		if(strtoupper(tirarAcentos($texto_entrada))!="NAO"){
			$query_tr = "select * from traducao where upper(portugues) = '".strtoupper($texto_entrada)."';";
			$res_tr = pesquisa($query_tr);
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
	function pesquisa($query){
	  global $conexao;
	  $result = $conexao->query($query);
	  return $result;
	}
	function formatar_texto_bd($entrada){
		return utf8_encode($entrada);
	}
	$stylesheet = "

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
			font-size: 9pt;
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
				 where agenda.id = " . $_GET['id'];
	$res_agenda = pesquisa($query);


	$row_agenda = mysqli_fetch_assoc($res_agenda);

	$query 			= "select * from proj_projeto";
	$res_projeto	= pesquisa($query);
	$row_projeto    = mysqli_fetch_assoc($res_projeto);




	$mpdf = new \Mpdf\Mpdf(['tempDir' => __DIR__ . '/temp', 'debug' => true, 'mode' => 'utf-8', 'margin-top' => 1158, 'margin-header' => 1500 ]);

	$mpdf->setAutoTopMargin = 'stretch';
	$mpdf->setAutoBottomMargin = 'stretch';
	$mpdf->allow_charset_conversion=true;
	$mpdf->WriteHTML($stylesheet,1);

	//style="margin-left: -80px;  margin-right: -70px;"

	$cabecalho = '
		<table>
		  <tr>
			<td align="left">
				<span class="versao_cabecalho">SN-LET-OP-002_v1.0</span>
			</td>
			<td align="right">
				<img src="modelos/novo/header.png" width="450" style="margin-top: -30px; margin-right: -580px;">
			</td>
		 </tr>
		</table>
	';

	$rodape = '
		<table width="100%">
			<tr>
				<td>
					<span class="footer_versao">Documento de origem: SN-SOP-OP-004</span>
				</td>
			</tr>
			<tr>
				<td>
					<img src="modelos/novo/footer.png" style="margin-left: -80px">
				</td>
			</tr>
		</table>
	';

	$mpdf->SetHTMLFooter($rodape);
	$mpdf->SetHTMLHeader($cabecalho);

	$html = '<br><br>
		<table>
			<tr><td class="dados_centro">'.formatar_texto_bd($row_agenda['centro_investigador']).'</td></tr>
			<tr><td class="dados_centro">Dados do centro de pesquisa</td></tr>
			<tr><td class="dados_centro">'.formatar_texto_bd($row_agenda['centro_nome']).'</td></tr>
			<tr><td class="dados_centro">'.nl2br(formatar_texto_bd($row_agenda['centro_endereco'])).'</td></tr>
		</table>
		<br>

		<div align="right">
			<table class="tabela_recebimento">
				<tr>
					<td width="283" colspan="2" valign="top" style="width: 211.95pt; border: 1.0pt solid windowtext; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm">
					<p class="MsoNormal" align="center" style="margin-top:6.0pt;text-align:center">
					<center><b><span class="recebimento_interno">Acuso de recebimento</span></center></td>
				</tr>
				<tr>
					<td width="181" valign="top" style="width: 136.1pt; border-left: 1.0pt solid windowtext; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.0pt solid windowtext; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm">
						<span class="recebimento_interno">Recebido por:</span></b>
					</td>
					<td width="101" valign="top" style="width: 75.85pt; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.0pt solid windowtext; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm">
					</td>
				</tr>
				<tr>
					<td width="181" valign="top" style="width: 136.1pt; border-left: 1.0pt solid windowtext; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.0pt solid windowtext; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm">
					<span class="recebimento_interno">Data do recebimento</span></b></td>
					<td width="101" valign="top" style="width: 75.85pt; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.0pt solid windowtext; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm">
					</td>
				</tr>
			</table>
		</div>

		<br>
		<div align="right">';
		$data = $_GET['data'];
		$dia  = substr($data,0,2);
		$mes  = substr($data, 3,2);
		$ano  = substr($data, 6,4);


	//formatar_texto_bd(
	$html =	'	<span class="conteudo_bold">Campinas, '.$dia.' de '.$mes_corrido[intval($mes)].' de '.$ano.'</span>
		</div>
		<br>
		<p class="conteudo_bold">
			Ref: “Carta de Confirmação para a Visita de Qualificação” – “'.$row_agenda['centro_nome'].'”
		</p>
		<p class="conteudo_normal">
			Prezado(a) Investigador(a),
		</p>
		<p class="conteudo_normal">
			Conforme combinado, a visita de Qualificação para o
			Projeto '.$row_projeto['nome'].' ficou agendada para o dia ';

	$dia = substr($row_agenda['data_caption'],0,2);
	$mes = substr($row_agenda['data_caption'],3,2);
	$ano = substr($row_agenda['data_caption'],6,4);

	$html .= $dia . ' de '.$mes_corrido[intval($mes)].' de '.$ano.' no periodo de '.formatar_texto_bd($row_agenda['hora']).' em seu centro,
			no endereço '.formatar_texto_bd($row_agenda['centro_endereco']).'. A visita será realizada por: '.$row_agenda['monitora_nome'].'.
		</p>

		<p class="conteudo_normal">Durante essa visita, os seguintes itens serão abordados:</p>

		<p class="conteudo_normal"><ul>
		';

		$opcoes = "";
		$opcoes = str_replace("<br />","%",formatar_texto_bd($row_agenda['atividades']));
		$opcoes = str_replace("\n","",$opcoes);
		for($z=0;$z<strlen($opcoes);$z++){
		   if(substr($opcoes,$z,1)!="%"){$temp.=substr($opcoes,$z,1);}
		   else{
				$temp=trim($temp);
				$temp_selected = "";
				if(trim($rowItems['valor'])==$temp){ $temp_selected = " selected "; }
				$html .= '<li class="conteudo_normal">'.$temp.'</li>';
				//echo '<option '.$temp_selected.' value="'.$temp.'">'.$temp.'</option>';
				$temp="";
		   }
		}
		if(strlen($temp)>0){
				$temp=trim($temp);
				$temp_selected = "";
				if(trim($rowItems['valor'])==$temp){ $temp_selected = " selected "; }
				$html .= '<li class="conteudo_normal">'.$temp.'</li>';
				$temp="";
		}



	$html .=  '
		</ul></p>
		<p class="conteudo_normal">
			Pedimos, por gentileza, que os profissionais responsáveis pelos departamentos relacionados acima sejam contatos e informados sobre a visita, assim como a possibilidade de uma  breve visita nas áreas correspondentes.
		</p>
		<p class="conteudo_normal">
			O acuso de recebimento deverá ser realizado no campo específico inserido no início da carta ou através de um e-mail confirmando a ciência.
		</p>
		<p class="conteudo_normal">
			Muito obrigada(o) pela atenção fornecida até o momento.<br>Atenciosamente,
		</p>
		<br>
		<p class="conteudo_normal">
		______________________________________________________<br>
		Nome<br>
		Função
	';
	$mpdf->WriteHTML($html);


	$mpdf->Output();
	$mpdf->charset_in='UTF-8';
?>