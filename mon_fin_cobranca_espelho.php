<?php
	include_once "seguranca.php";
	include_once "definicoes.php";

	$varConexao = new Conexao();
	require_once __DIR__ . '/vendor/autoload.php';

	/*
	307 = alergisa
	448 = Kosmo
	454 = CIPES
	*/

	define("LIMITE", 20000);
	//define("SQL_CENTRO", " id = 454");	//CIPES
	//define("SQL_CENTRO", " id = 448");  //KOSMO
	//define("SQL_CENTRO", " id = 307");  //ALLERGISA
	define("SQL_CENTRO", " id > 0");    //TUDO


	function formatar_texto_bd($entrada){
		return utf8_encode($entrada);
	}
	function formatar_texto($entrada){
		return utf8_encode($entrada);
	}

	$stylesheet = "
		.cssTotal{
			text-align:center;
			border: 0px solid #000000;
			padding-left: 4px;
			padding-right: 4px;
			padding-top: 6px;
			padding-bottom: 3px;
			white-space:nowrap;
			font-size: 37px;
		}
		.cssValor{
			text-align:center;
			border: 1px solid #000000;
			padding-left: 4px;
			padding-right: 4px;
			padding-top: 6px;
			padding-bottom: 3px;
			white-space:nowrap;
			font-size: 27px;
		}
		.texto_caption_tabela{
			font-size: 7px;
			font-weight: bold;
		}
		.data_pago{
			font-size: 6px;
			font-weight: normal !important;
			font-style: italic;
		}
		.ja_pago{
			font-color: red;
			color: red;
		}
	";

	/*error_reporting(E_ALL ^ E_DEPRECATED);
    ini_set('display_errors', 1);*/

	$varDataLIS = $_GET['data'];
	$varData	= substr($varDataLIS,6,2) . "/" . substr($varDataLIS,4,2) . "/" . substr($varDataLIS,0,4);
	$mpdf = new \Mpdf\Mpdf([
								'tempDir' => __DIR__ . '/temp',
								'debug' => true,
								'mode' => 'utf-8',
								'margin-top' => 100,
								'margin-header' => 200,
								'margin-bottom' => 400,
								'format' => 'A4-L'
							]);
	$mpdf->shrink_tables_to_fit = 0;
	$mpdf->setAutoTopMargin = 'stretch';
	$mpdf->setAutoBottomMargin = 'stretch';
	$mpdf->allow_charset_conversion=true;
	$mpdf->WriteHTML($stylesheet,1);

	$cabecalho = '<table border="1" height="100%" width="100%"  cellspacing="0" cellpadding="0" bordercolor="#000000" style="border-collapse: collapse;">
		<tr style="border:1px solid #000000;">
			<td width="142" style="border:1px solid #000000;"><center><br><img src="logo.png" width="50"></center><br><br><br></td>
			<td style="border:1px solid #000000;">
			<p class="MsoNormal" align="center" style="text-align:center;line-height:150%"><b><span style="font-size: 12.0pt; line-height: 150%; font-family: Verdana,sans-serif"><center>ESPELHO DE COBRANÃ‡A</center></span></b><BR>Data: '.$varData.'</td>
		</tr>
		</table>
	';

	$mpdf->SetHTMLFooter($rodape);
	$mpdf->SetHTMLHeader($cabecalho);

	//

	//Loop pelos centros
	$query 		= "select * from cfg_centro where ".SQL_CENTRO." and projeto = " . $_SESSION['projeto'];
	$resCentros = $varConexao->pesquisa($query);
	while($rowCentros = mysqli_fetch_assoc($resCentros)){


		//PEgar o total do centro
		$query = "SELECT
			agenda_check_participante_campo.data_pagamento,
			data_pagamento_lis,
			sum(agenda_check_participante_campo.preco) as total
				from agenda_check_participante_campo
					inner join agenda_check_participante on agenda_check_participante_campo.participante = agenda_check_participante.id
					inner join agenda_check on agenda_check_participante_campo.campo = agenda_check.id
						where ( (agenda_check_participante_campo.data_pagamento_lis = '$varDataLIS') and agenda_check_participante.centro =  ".$rowCentros['id'].")
					group by agenda_check_participante_campo.data_pagamento, data_pagamento_lis";
		$res = $conexao->pesquisa($query);
		$row = mysqli_fetch_assoc($res);
		$varTotal = $row['total'];



		$html = "<br><br><br><br><br><br><br><br><br><br><br>";
		$html .= "<div class='cssTotal'>CENTRO: ".$rowCentros['apelido']."<br></div>";
		$html .= "<div class='cssValor'>R$".number_format($varTotal,2)."</div>";
		$html .= "</center>";
		$mpdf->WriteHTML($html);
		$mpdf->AddPage();




		$query = "SELECT
				distinct agenda_check_participante_campo.participante,
				agenda_check_participante.numero,
				agenda_check_participante.codigo
					from agenda_check_participante_campo
						inner join agenda_check_participante on agenda_check_participante_campo.participante = agenda_check_participante.id
						where agenda_check_participante.projeto = ".$_SESSION['projeto']."
						 and agenda_check_participante.centro = ".$rowCentros['id']."
					order by cast(agenda_check_participante.numero as INT)
				limit " . LIMITE;
		$resPart = $conexao->pesquisa($query);
		$totPart = mysqli_num_rows($resPart);
		$iPart   = 1;
		while($rowPart = mysqli_fetch_assoc($resPart)){
			$total_a_pagar = 0;
			$tabela_ini = '
					<table class="MsoNormalTable" border="1" cellspacing="0" cellpadding="0" width="100%" style="border-collapse: collapse; border: medium none;">
						<tr style="page-break-inside: avoid; height: 32.95pt">
							<td width="699" colspan="15" style="width: 524.55pt; border: 1.0pt solid windowtext; padding-left: 3.5pt; padding-right: 3.5pt; padding-top: 3.5pt; padding-bottom: 3.5pt; background: #D9D9D9">
								<span class="texto_caption">
									Participante [' . $rowPart['numero'] . ' - ' . $rowPart['codigo'] . '] ' .$rowPart['participante'] . '
								</span>
							</td>
						</tr>
						<tr style="page-break-inside: avoid; height: 42.95pt">
							<td style="border-left: 1.0pt solid windowtext; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.0pt solid windowtext; padding-left: 3.5pt; padding-right: 3.5pt; padding-top: 3.5pt; padding-bottom: 3.5pt; background: #D9D9D9">
								<span class="texto_caption"><center>ESTUDO MINOX - EF171</center></span>
							</td>
							';
							$query 		= "select * from agenda_cobranca_fase order by id";
							$resFase 	= $varConexao->pesquisa($query);
							while($rowFase = mysqli_fetch_assoc($resFase)){
								$tabela_ini .= '<td style="border-left: 1.0pt solid windowtext;
															border-right: 1.0pt solid windowtext;
															border-top: medium none;
															border-bottom: 1.0pt solid windowtext;
															padding-left: 3.5pt;
															padding-right: 3.5pt;
															padding-top: 3.5pt;
															padding-bottom: 3.5pt;
															background: white">
													<span class="texto_caption_tabela"><center>'.utf8_encode($rowFase['fase']).'</center></span>
												</td>
								';
							}
						$tabela_ini .= '
						</tr>';
						//Loop em todos as linhas
						$query = "select * from agenda_cobranca_linha order by id";
						$resLinha = $varConexao->pesquisa($query);
						while($rowLinha = mysqli_fetch_assoc($resLinha)){
							$tabela_ini .='
									<tr style="page-break-inside: avoid; height: 42.95pt">
										<td style="border-left: 1.0pt solid windowtext; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.0pt solid windowtext; padding-left: 3.5pt; padding-right: 3.5pt; padding-top: 3.5pt; padding-bottom: 3.5pt; background: #D9D9D9">
											<span class="texto_caption_tabela">'.utf8_encode($rowLinha['nome']).'</span>
										</td>
										';
										$query 		= "select * from agenda_cobranca_fase order by id";
										$resFase 	= $varConexao->pesquisa($query);
										while($rowFase = mysqli_fetch_assoc($resFase)){
											$tabela_ini .= '<td style="border-left: 1.0pt solid windowtext;
																		border-right: 1.0pt solid windowtext;
																		border-top: medium none;
																		border-bottom: 1.0pt solid windowtext;
																		padding-left: 3.5pt;
																		padding-right: 3.5pt;
																		padding-top: 3.5pt;
																		padding-bottom: 3.5pt;
																		background: white">
																<span class="texto_caption_tabela"><center>';

											/*
											Aqui vou ver efetivamente se é para pagar agora ou não
											*/
											$query = "select
														agenda_check_participante_campo.participante,
														agenda_check_participante_campo.preco as valor_pagar,
														agenda_check_participante_campo.pago,
														agenda_check_participante_campo.data_pagamento,
														agenda_check_participante_campo.data_pagamento_lis,
														agenda_cobranca_itens.*,
														agenda_check.nome,
														agenda_check.cobranca,
														AV.valor,
                                                        AV.valor6
															from agenda_cobranca_itens
															inner join agenda_check on agenda_cobranca_itens.campo_id = agenda_check.id

															inner join agenda_check_participante_campo on agenda_check.id = agenda_check_participante_campo.campo
															inner join agenda_check_participante on agenda_check_participante_campo.participante = agenda_check_participante.id
															left join agenda_check_centro_valor AV on agenda_cobranca_itens.id = AV.campo and AV.centro = agenda_check_participante.centro
																where
																	agenda_cobranca_itens.linha = ".$rowLinha['id']."
																	and agenda_cobranca_itens.fase = ".$rowFase['id']."
																	and agenda_check_participante_campo.participante = " . $rowPart['participante'] . "
																	and ( (AV.valor <> '') or (AV.valor6 <> '') )";
											$resValor = $conexao->pesquisa($query);
											$rowValor = mysqli_fetch_assoc($resValor);

											//$tabela_ini .= $rowValor['data_pagamento_lis'];
											if($rowValor['cobranca']!='1'){	//Se não tiver pagamento para esse item, deixo com traço
												$tabela_ini .= "-";
											}else{
												if($rowValor['data_pagamento_lis']==""){	//Ainda não aconteceu. Mostro um -
													$tabela_ini .= "-";
												}else{
													if($rowValor['data_pagamento_lis']==$_GET['data']){
														$total_a_pagar = $total_a_pagar + $rowValor['valor_pagar'];
														$tabela_ini .= number_format($rowValor['valor_pagar'],2);
													}else{
														$tabela_ini .= "<span class='ja_pago'>".number_format($rowValor['valor_pagar'],2) . "<br><span class='data_pago'>" . $rowValor['data_pagamento'] . "</span></span>";
													}
												}
											}

											$tabela_ini .= '</center></span>
															</td>
											';
										}
										$tabela_ini .= '
									</tr>
							';
						}
						$rodape = '<tr style="page-break-inside: avoid; height: 42.95pt">
								<td colspan=15 style="text-align: right; width: 113.3pt; border-left: 1.0pt solid windowtext; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.0pt solid windowtext; padding-left: 3.5pt; padding-right: 3.5pt; padding-top: 3.5pt; padding-bottom: 3.5pt; background: #D9D9D9">
									<span class="texto_caption">R$ '.number_format($total_a_pagar,2).'</span>
								</td></tr>
						';
			$tabela_ini .= $tabela_itens . $rodape .'
					</table>';
			$mpdf->WriteHTML($tabela_ini);
			if($iPart<$totPart){
				$mpdf->AddPage();
			}
			$iPart++;
		}





	}


	$query = "SELECT
		agenda_check_participante_campo.data_pagamento,
		data_pagamento_lis,
		sum(agenda_check_participante_campo.preco) as total
			from agenda_check_participante_campo
				inner join agenda_check on agenda_check_participante_campo.campo = agenda_check.id
					where ( (agenda_check_participante_campo.data_pagamento_lis = '$varDataLIS') )
				group by agenda_check_participante_campo.data_pagamento, data_pagamento_lis";
	$res = $conexao->pesquisa($query);
	$row = mysqli_fetch_assoc($res);
	$varTotal = $row['total'];

	$html = "<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>";
	$html .= "<div class='cssTotal'>TOTAL GERAL DO ESPELHO<br></div>";
	$html .= "<div class='cssValor'>R$".number_format($varTotal,2)."</div>";
	$html .= "</center>";

	$mpdf->WriteHTML($html);
	$mpdf->AddPage();





	$mpdf->Output();
	$mpdf->charset_in='UTF-8';
?>