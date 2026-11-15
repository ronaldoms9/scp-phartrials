<?
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
	$servidor = "localhost";
	$conexao = new mysqli($servidor, "root", "senha123", "synova_spc_teste_pena");

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

		.tabela_pendencia{
			font-family: 'Times new Roman';
			font-size: 10pt;
			line-height: 29px;
			border:1;
			cellspacing:0;
			cellpadding:0;
			border-collapse: collapse;
			border: 1px solid black;

		}
		.tabela_pendencia_cabecalho{
			font-family: 'Times new Roman';
			font-size: 8pt;
			line-height: 29px;
			background-color: #D3D3D3;
			border: 0.5px solid black;
		}
		.tabela_pendencia_linha{
			font-family: 'Times new Roman';
			font-size: 6pt;
			line-height: 29px;
			background-color: white;
			border: 0.5px solid black;
		}
		.paginacao{
			font-size:8.0pt;

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


	$cabecalho = '
		<table>
		  <tr>
			<td>
				<img src="modelos/iniciacao/header_siv_fup.png" style="margin-top: -30px; margin-left: -70px; margin-right: -100px;">
			</td>
		 </tr>
		</table>
	';

	$rodape = '
		<table width="100%">
		  <tr>
			<td width="20%">
				<br><br><br><span class="paginacao">{PAGENO}/{nbpg}</font>
			</td>
			<td width="60%">
				<br><br><br><span class="paginacao">Vigência (Effective): 06-Mai-2019 | Origem (Source): SN-SOP-OP-007</font>
			</td>
			<td width="20%" align="right">
				<img src="modelos/qualificacao/carta_footer.png" style="margin-bottom: -60px; margin-right: -65px">
			</td>

		 </tr>
		</table>
	';

	$mpdf->SetHTMLFooter($rodape);
	$mpdf->SetHTMLHeader($cabecalho);

	$html = '<br><br>
		<table>
			<tr><td class="dados_centro">'.$row_agenda['centro_investigador'].'</td></tr>
			<tr><td class="dados_centro">Dados do centro de pesquisa</td></tr>
			<tr><td class="dados_centro">'.$row_agenda['centro_nome'].'</td></tr>
			<tr><td class="dados_centro">'.nl2br($row_agenda['centro_endereco']).'</td></tr>
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
	$mpdf->WriteHTML(formatar_texto_bd($html));


	$html =	'	<span class="conteudo_bold">Campinas, '.$dia.' de '.$mes_corrido[intval($mes)].' de '.$ano.'</span>
		</div>
		<br>
		<p class="conteudo_bold">
			Ref: “Carta de Acompanhamento da Visita de Abertura” – “'.$row_agenda['centro_nome'].'”
		</p>
		<p class="conteudo_normal">
			Prezado(a) Investigador(a),
		</p>
		<p class="conteudo_normal">
			Primeiramente gostaria de agradecer a disponibilidade para atender a visita de Abertira realizada no dia ';
			$dia = substr($row_agenda['data_caption'],0,2);
			$mes = substr($row_agenda['data_caption'],3,2);
			$ano = substr($row_agenda['data_caption'],6,4);
			$html .= $dia . ' de '.$mes_corrido[intval($mes)].' de '.$ano.' no periodo de '.formatar_texto_bd($row_agenda['hora']).' em seu centro para o Projeto '.$row_projeto['nome'].'
		</p>
		<p class="conteudo_normal">Os seguintes integrantes estiveram presentes durante a visita:</p><ul>';

		$query_p = "SELECT * FROM vw_agenda_valor_campo where agenda_id = " . $_GET['id'] . " and crf_agenda_campos_campo_id = 121";
		$res_p = pesquisa($query_p);
		$row_p = mysqli_fetch_assoc($res_p);
		$valor_temp = explode("|", $row_p['valor']);
		for($i=0;$i<count($valor_temp)-1;$i++){
			$valor_temp2 = explode("=", $valor_temp[$i]);
			$valor_temp2 = explode("_", $valor_temp2[1]);
			//$valor_temp2 = explode("_", $valor_temp2);
			//var_dump($valor_temp2);
			$html .= '<li class="conteudo_normal">' . formatar_texto_bd($valor_temp2[0]) .'</li>';
		}
		$query_p = "SELECT * FROM vw_agenda_valor_campo where agenda_id = " . $_GET['id'] . " and crf_agenda_campos_campo_id = 123";
		$res_p = pesquisa($query_p);
		$row_p = mysqli_fetch_assoc($res_p);
		$valor_temp = explode("|", $row_p['valor']);
		for($i=0;$i<count($valor_temp)-1;$i++){
			$valor_temp2 = explode("=", $valor_temp[$i]);
			$valor_temp2 = explode("_", $valor_temp2[1]);
			$html .= '<li class="conteudo_normal">' . formatar_texto_bd($valor_temp2[0])."</li>";
		}
		//12
		//13

		$html .= '</ul>
		<p class="conteudo_normal">e os seguintes tópicos abordados:<ul>
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




	$query_p = "SELECT * FROM vw_agenda_valor_campo where agenda_id = " . $_GET['id'] . " and crf_agenda_campos_campo_id = 180";
	$res_p = pesquisa($query_p);
	$row_p = mysqli_fetch_assoc($res_p);
	if($row_p['valor']!=""){
		$html .=  '
			</ul></p><br>
			<p class="conteudo_normal">
				Como parte do nosso processo de acompanhamento, gostaria de pedir, por gentileza, que os seguintes itens que ficaram pendentes sejam discutidos internamente e as ações acordadas realizadas pelos responsáveis:
			</p>
			<br><br>
			<p>
				<center>
					<table width="100%" class="tabela_pendencia" align="center" style="align: center">
						<tr>
							<td class="tabela_pendencia_cabecalho"><center>Item pendente</center></td>
							<td class="tabela_pendencia_cabecalho" width="40%"><center>Ação</center></td>
							<td class="tabela_pendencia_cabecalho" width="20%"><center>Responsável</center></td>
						</tr>
						';

						$query_p = "SELECT * FROM vw_agenda_valor_campo where agenda_id = " . $_GET['id'] . " and crf_agenda_campos_campo_id = 180";
						$html .= "<font color='black'>".$query_p."</font>";
						$res_p = pesquisa($query_p);
						$row_p = mysqli_fetch_assoc($res_p);
						$valor_temp = explode("|", $row_p['valor']);
						for($i=0;$i<count($valor_temp)-1;$i++){
							$valor_temp2 = explode("=", $valor_temp[$i]);
							$html .= '<tr>';
							$html .= '	<td class="tabela_pendencia_linha">&nbsp;';
							$temp = formatar_texto_bd($valor_temp2[1]);
							$temp = explode("_", $temp);
							$html .= $temp[0];
							$html .= '</td>';
							$html .= '	<td class="tabela_pendencia_linha">&nbsp;';
							$temp = formatar_texto_bd($valor_temp2[2]);
							$temp = explode("_", $temp);
							$html .= $temp[0];
							$html .= '</td>';
							$html .= '	<td class="tabela_pendencia_linha">&nbsp;';
							$temp = formatar_texto_bd($valor_temp2[3]);
							$temp = explode("_", $temp);
							$html .= $temp[0];
							$html .= '</td>';
							$html .= '</tr>';
						}

						$html .= '
					</table>
				</center>
			</p>
		';
	}
	$html .= '
		<br>
		<p class="conteudo_normal">
			Mais uma vez, agradeço a colaboração e fico à disposição para qualquer esclarecimento.
		<br><br><br>
			Atenciosamente,
		</p>
		<br>
		<p class="conteudo_normal">
		______________________________________________________<br>
		Nome:<br>
		Função:
	';
	$mpdf->WriteHTML($html);


	$mpdf->Output();
	$mpdf->charset_in='UTF-8';
?>