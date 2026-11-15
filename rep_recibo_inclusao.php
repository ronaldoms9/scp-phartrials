<?	
	include_once "seguranca.php";
	
	
	include_once "class/participante.class.php";
	
	require_once __DIR__ . '/vendor/autoload.php';	
		
	function formatar_texto($entrada){		return utf8_decode($entrada);	}
	function formatar_texto_bd($entrada){ return utf8_decode($entrada);	}
	
	$centro   = $_GET['centro'];
	$paciente = $_GET['paciente'];
	
	$participante = new Participante($centro, $paciente);
	
	$centro_num = $participante->centroNro;
	$nroPre		= $participante->nro_triagem;
	$nroPos		= $participante->paciente_pos_rand;
		
	
	$mpdf = new \Mpdf\Mpdf(['tempDir' => __DIR__ . '/temp', 'debug' => true, 'mode' => 'utf-8', 'format' => 'A4', 'margin-top' => 1158, 'margin-header' => 1500 ]);
	$mpdf->setAutoTopMargin = 'stretch';
	$mpdf->allow_charset_conversion=true;
	
	
	$html = '<html>
			<head>
			<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
			<title>COMPROVANTE DE SELEÇÃO</title>
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
				150%"><br><center>COMPROVANTE DE SELEÇÃO</center></span></b></p>
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
							$html .= $participante->retornaNroFormatado();							
		$html .= '
							</center></b></td>
						</tr>
						<tr>
							<td colspan="5" style="vertical-align: middle;text-align:center;line-height:150%">
							<p class="MsoNormal" align="center" style="text-align:center;">
							<center>NASCIMENTO: <b>
							';							
							$html .= $participante->nasc;
		$html .= '
							</center></b></td>
						</tr>						
						<tr>
							<td colspan="5"  style="vertical-align: middle;text-align:center;line-height:150%">
							<p class="MsoNormal" align="center" style="text-align:center;">
							<center>DATA DE INCLUSÃO: <b>
							';							
							$html .= $participante->dataInclusaoFormatado;
							
		$html .= '					</center></b></td>
						</tr>						
						<tr>
							<td colspan="5" style="vertical-align: middle;text-align:center;line-height:150%">
							<p align="center">
							<span style="font-size: 11.0pt; line-height: 107%; font-family: Calibri,sans-serif">
							<center>RESPONSÁVEL PELA INCLUSÃO:</span></center></td>
						</tr>
						<tr>
							<td align="center" colspan="5" style="vertical-align: middle"><b>
							<span style="font-size: 11.0pt; line-height: 107%; font-family: Calibri,sans-serif">
							';		
							$encoding = 'UTF-8';
							$html .= mb_convert_case(utf8_encode($participante->usuario->nome), MB_CASE_UPPER, $encoding);
							//$html .= strtoupper(strtoupper(utf8_encode($rowPart['nome_incluido_por'])));
							
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