<?php
	session_start();
	if($_SESSION['valido']<1){
		echo "<script>window.location.replace('expirado.php');</script>";
		exit();
	};
	include "class/conexao.class.php";
	include "class/crf.class.php";

	$varCRF = new CRF();

	error_reporting(1);
	require_once __DIR__ . '/vendor/autoload.php';
	$conexao = new mysqli($servidor, "root", "senha123", "pt_monitoria");
	function pesquisa($query){
	  global $conexao;
	  $result = $conexao->query($query);
	  return $result;
	}

	function formatar_texto($entrada){
		return utf8_decode($entrada);
	}
	function formatar_texto_bd($entrada){
		return utf8_decode($entrada);
	}

	$centro   = $_GET['centro'];
	$paciente = $_GET['paciente'];

	//Busco informações básicas
	$query = "SELECT
				crf_paciente_pre_rand.iniciais,
				crf_paciente_pre_rand.nasc,
				crf_paciente_pre_rand.centro,
				nro_triagem,
				nro_participante,
				nro_paciente,
				nro_triagem,
				paciente_pos_rand,
				usu_usuario.nome as randomizado_por,
				cfg_centro.numero as centroNumero,
				cfg_centro.nome   as centroNome
				from crf_paciente_pre_rand
					inner join usu_usuario on crf_paciente_pre_rand.randomizado_por = usu_usuario.id
					inner join cfg_centro on crf_paciente_pre_rand.centro = cfg_centro.id
						where crf_paciente_pre_rand.centro = $centro
								and paciente_pos_rand = $paciente
	";
	$res = pesquisa($query);
	$rowRand = mysqli_fetch_assoc($res);




	$mpdf = new \Mpdf\Mpdf(['tempDir' => __DIR__ . '/temp', 'debug' => true, 'mode' => 'utf-8', 'format' => 'A4', 'margin-top' => 1158, 'margin-header' => 1500 ]);
	$mpdf->setAutoTopMargin = 'stretch';



	$html = '<html>

			<head>
			<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
			<title>COMPROVANTE DE RANDOMIZAÇÃO</title>
			<style>
			<!--
			 p.MsoNormal
				{mso-style-parent:"";
				margin-top:0cm;
				margin-right:0cm;
				margin-bottom:8.0pt;
				margin-left:0cm;
				line-height:107%;
				font-size:11.0pt;
				font-family:"Calibri","sans-serif";
				}
			-->
			</style>
			</head>


			<body>

				<div align="center">
					<table border="0" cellspacing="0" cellpadding="0" style="border: 1px solid #000000">
						<tr>
							<td colspan="5"><img src="modelos/header_recibo.png"> </td>
						</tr>
						<tr>
							<td colspan="5">
							<p class="MsoNormal" align="center" style="text-align:center;line-height:150%">
							<b><span style="font-size:16.0pt;line-height:
				150%"><br><center>COMPROVANTE DE RANDOMIZAÇÃO</center></span></b></p>
							<p>&nbsp;</td>
						</tr>
						<tr>
							<td colspan="5" style="vertical-align: middle;text-align:center;line-height:150%">
							<p class="MsoNormal" align="center" valign="middle" style="text-align:center;">
							<center>PROJETO: <b>'.$_SESSION['projeto_nome'].'</center></b></td>
						</tr>
						<tr>
							<td colspan="5" style="vertical-align: middle;text-align:center;line-height:150%">
							<p class="MsoNormal" align="center" style="text-align:center;">
							<center>PARTICIPANTE: <b>
							';
							$labPre = $rowRand['nro_paciente'];
							$labPos = $rowRand['paciente_pos_rand'];
							$labCen = $rowRand['centroNumero'];

							$label_paciente = str_pad($labCen,2,"00",STR_PAD_LEFT).'-'.str_pad($labPre,3,"000",STR_PAD_LEFT)."S";

							if($labPos>0){
								$label_paciente .= str_pad($labPos,3,"000",STR_PAD_LEFT).'R';
							}
							$html .= $label_paciente;
		$html .= '
							</center></b></td>
						</tr>
						<tr>
							<td colspan="5" style="vertical-align: middle;text-align:center;line-height:150%">
							<p class="MsoNormal" align="center" style="text-align:center;">
							<center>NASCIMENTO: <b>
							';
							$html .= $rowRand['nasc'];
		$html .= '
							</center></b></td>
						</tr>
						<tr>
							<td colspan="5"  style="vertical-align: middle;text-align:center;line-height:150%">
							<p class="MsoNormal" align="center" style="text-align:center;">
							<center>DATA DA RANDOMIZAÇÃO: <b>
							';
							$html .= utf8_encode($varCRF->retornaValorPorCodigo('TRI_RAND_DATA_RANDOMIZACAO', $rowRand['centro'], $labPre));

		$html .= '					</center></b></td>
						</tr>
						<tr>
							<td colspan="5" style="vertical-align: middle;text-align:center;line-height:150%">
							<p class="MsoNormal" align="center" style="text-align:center;">
							<center>HORÁRIO DA RANDOMIZAÇÃO: <b>';
							$html .= utf8_encode($varCRF->retornaValorPorCodigo('TRI_RAND_HORA_RANDOMIZACAO', $rowRand['centro'], $labPre));

		$html .= '					</b></center></td>
						</tr>
						<tr>
							<td height="30" colspan="5" style="vertical-align: middle">
							<p class="MsoNormal" align="center" style="text-align:center;">
							<center>TRATAMENTO ALOCADO: <b>';
							$query = "select
										crf_rand_semente.id,
										crf_rand_semente.sequencia,
										crf_rand_tratamento.nome,
										crf_rand_semente.centro,
										crf_rand_semente.paciente_alocado
											from crf_rand_semente
											inner join crf_rand_tratamento on crf_rand_semente.tratamento = crf_rand_tratamento.id
											where crf_rand_tratamento.projeto = ".$_SESSION['projeto']."
													and crf_rand_semente.centro = ".$rowRand['centro']."
													and crf_rand_semente.paciente_alocado = " . $labPre;
							$resTrat = pesquisa($query);
							$rowTrat = mysqli_fetch_assoc($resTrat);
							$html .= $rowTrat['nome'];

		$html .= '					</b></center></td>
						</tr>
						<tr>
							<td colspan="5" style="vertical-align: middle;text-align:center;line-height:150%">
							<p align="center">
							<span style="font-size: 11.0pt; line-height: 107%; font-family: Calibri,sans-serif">
							<center>RESPONSÁVEL PELA RANDOMIZAÇÃO:</span></center></td>
						</tr>
						<tr>
							<td align="center" colspan="5" style="vertical-align: middle"><b>
							<span style="font-size: 11.0pt; line-height: 107%; font-family: Calibri,sans-serif">
							';
							$html .= strtoupper($rowRand['randomizado_por']);

		$html .= '					</span></b></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
					</table>
				</div>

				</body>

				</html>

	';





	$mpdf->WriteHTML($html);
	$mpdf->WriteHTML('<br><br>');
	$mpdf->Output();
	$mpdf->charset_in='UTF-8';
?>