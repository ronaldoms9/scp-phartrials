<?php
	error_reporting(E_ERROR);
    ini_set('display_errors', 1);
	include "definicoes.php";
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
	$conexao = new mysqli($servidor, "root", "senha123", "pt_monitoria");
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
	function formatar_texto($entrada){
		return utf8_encode($entrada);
	}
	function busca_valor_agenda($agendamento, $campo){
		global $conexao;
		$query = "select valor from vw_valor_campo_agendamento where agendamento = '".$agendamento."' and campo_id = '".$campo."'";
		$res_valor = pesquisa($query);
		$row_valor = mysqli_fetch_assoc($res_valor);
		return $row_valor['valor'];
	}
	$varData = $_GET['data'];
	$varAgendamento = $_GET['id'];


	$anexo 		= $_GET['anexo'];
	$query 		= "select id, nome from crf_forms where id = " . $anexo;
	$resAnexo 	= pesquisa($query);
	$rowAnexo	= mysqli_fetch_assoc($resAnexo);


	$stylesheet = "
		.direita {
		  text-align: right;
		  margin-right: 1em;
		}



		.titulo{
			font-size:12.0pt;
			font-weight: bold;
			font-align: center
		}



		.texto_caption{
			font-size: 10pt;
			font-weight: bold;
		}
		.texto_conteudo{
			font-size: 10pt;
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
			/*background: #D9D9D9*/
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
			/*background: #D9D9D9;*/
			background: white;
			font-size: 7.0pt;
		}
		.cabecalho_2{
			padding-top: -2.0pt;
			padding-bottom: 0pt;
			padding-left: 3.5pt;
			padding-right: 3.5pt;
			/*background: #D9D9D9*/
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
		.paginacao{
			font-size:8.0pt;

		}
		.justificado{
			text-align: justify;
			letter-spacing: 0;
		}
		.centralizado{
			text-align: center;
			letter-spacing: 0;
		}
		.ass_header{
			font-size:10.0pt;
			font-weight: bold;
			padding-top: 4px;
			padding-bottom: 4px;
		}
		.ass_caption{
			font-size:8.0pt;
			font-weight: bold;
			padding-top: 4px;
			padding-bottom: 4px;
		}
		.ass_item{
			font-size:8.0pt;
			font-weight: normal;
			padding-top: 6px;
			padding-bottom: 4px;
		}
		.ass_item_caption{
			font-size:6.0pt;
			font-weight:normal;
			padding-top: 4px;
			padding-bottom: 4px;
		}
		.tabela_th{
			font-size: 7.0pt;
			font-weight: bold;
			padding-top: 4px;
			padding-bottom: 4px;
			padding-left: 4px;
			padding-right: 4px;
			border-bottom: 2px solid #000000;

		}
		.tabela_tr_th{
			/*border-bottom: 3px solid #000000;
			border-top: 3px solid #000000; */
			border-top-style: solid;
			border-top-width: 4px;
			border-top-color: #000000;
			border-bottom-style: solid;
			border-bottom-width: 4px
			border-bottom-color: #000000;
		}
		.tabela_tr_td{
			border-bottom: 1px solid #000000;

		}
		.tabela_td{
			font-size: 6.0pt;
			font-weight: normal;
			text-align: center;
			padding-top: 4px;
			padding-bottom: 4px;
			padding-left: 4px;
			padding-right: 4px;
			border-bottom: 1px solid #000000;
			border-left: 0.5px solid #000000;
		}
	";


	$mpdf = new \Mpdf\Mpdf([
								'tempDir' => __DIR__ . '/temp',
								'debug' => true,
								'mode' => 'utf-8',
								'margin-top' => 100,
								'margin-header' => 200,
								'margin-bottom' => 200,
								'orientation' => 'L'
							]);

	$mpdf->setAutoTopMargin = 'stretch';
	$mpdf->setAutoBottomMargin = 'stretch';
	$mpdf->allow_charset_conversion=true;
	$mpdf->WriteHTML($stylesheet,1);

	$cabecalho = '<table border="1" height="100%" width="100%"  cellspacing="0" cellpadding="0" bordercolor="#000000" style="border-collapse: collapse;">
		<tr style="border:1px solid #000000;">
			<td width="142" style="border:1px solid #000000;"><center><br><img src="logo.png" width="50"></center><br><br><br></td>
			<td style="border:1px solid #000000;">
			<p class="MsoNormal" align="center" style="text-align:center;line-height:150%"><b><span style="font-size: 12.0pt; line-height: 150%; font-family: Verdana,sans-serif">
				<center>ANEXO - '.formatar_texto_bd($rowAnexo['nome']).'</center></span></b></td>
		</tr>
		</table>
	';

	$mpdf->SetHTMLFooter($rodape);
	$mpdf->SetHTMLHeader($cabecalho);





	$html = "<html>
				<body>
				<br>
				<div class='titulo' style='text-align:center'>
					<table id='tabela' width='100%'  cellspacing='0' cellpadding='0'>
						<tr class='tabela_tr_th' style='border-bottom: 3px solid #000000;border-top: 3px solid #000000; '>
							<td class='tabela_th' width='50px'>Participante</td>
							";
							$query 		= "select id, nome, texto from crf_forms_campos where formulario = " . $anexo . " order by ordem";
							$resCampos	= pesquisa($query);
							while($rowCampos = mysqli_fetch_assoc($resCampos)){
								$html .= "<td class='tabela_th'>".formatar_texto_bd($rowCampos['texto'])."</td>";
							}
	$html .= "			</tr> ";
	$query = "select
				crf_paciente_pre_rand.id AS id,
				crf_paciente_pre_rand.nro_triagem AS nro_triagem,
				crf_paciente_pre_rand.nro_participante AS nro_participante,
				crf_paciente_pre_rand.nro_paciente AS nro_paciente,
				crf_paciente_pre_rand.centro AS centro,
				crf_paciente_pre_rand.nro_paciente_formatado AS nro_paciente_formatado,
				V1.valor as participante
				from crf_paciente_pre_rand
					inner join crf_entrada_forms on crf_paciente_pre_rand.centro = crf_entrada_forms.centro
							and crf_paciente_pre_rand.nro_paciente = crf_entrada_forms.paciente
							and crf_paciente_pre_rand.projeto = crf_entrada_forms.projeto

					 inner join crf_entrada_campos V1 on crf_entrada_forms.id = V1.form_id
							and V1.campo_id = 107

					where crf_paciente_pre_rand.projeto = ".$_SESSION['projeto']." and crf_paciente_pre_rand.centro = 26
					order by crf_paciente_pre_rand.nro_paciente";

	$resPart = pesquisa($query);
	while($rowPart = mysqli_fetch_assoc($resPart)){
			$html .= "<tr class='tabela_tr_td'>
				<td class='tabela_td'><center>".formatar_texto_bd($rowPart['nro_paciente'])."</center></td>";
					$query = " SELECT
									crf_entrada_forms.id,
									crf_entrada_forms.paciente,
									crf_entrada_campos.campo_id,
									crf_entrada_campos.valor
										FROM crf_entrada_forms
											inner join crf_entrada_campos on crf_entrada_forms.id = crf_entrada_campos.form_id
											inner join crf_forms_campos on crf_entrada_campos.campo_id = crf_forms_campos.id
											WHERE
												crf_entrada_forms.form_id = ".$anexo."
												and crf_entrada_forms.paciente = ".$rowPart['nro_paciente']."
												and crf_entrada_forms.projeto = ".$_SESSION['projeto']."
											order by crf_forms_campos.ordem
					";
					$resCampos = pesquisa($query);
					while($rowCampos = mysqli_fetch_assoc($resCampos)){
						$html .="<td class='tabela_td'>".formatar_texto_bd($rowCampos['valor'])."</td>";
					}
			$html .= "</tr>";
	}
	$html .= "		</table>
				</div>";
	$mpdf->WriteHTML($html);


	$html = "</body></html>";
	$mpdf->WriteHTML($html);



	$mpdf->Output();
	$mpdf->charset_in='UTF-8';
?>