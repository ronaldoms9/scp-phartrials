<?
	/*error_reporting(E_ALL);
	ini_set("display_errors", 1);*/
	session_start();
	include "../class/conexao.class.php";
	include "../class/pesq_centro.class.php";
	include "../class/survey.class.php";
	include "../class/email.class.php";

	if($_GET['act']=="CADASTRA"){
		$varPesquisa = new Pesquisa($_POST['pesquisa']);

		$varPesquisa->vinculaPesquisa($_POST['centro']);
		echo "OK";

	}
	if($_GET['act']=="LISTA_PESQUISAS"){
		$varPesquisa = new Pesquisa(null);
		$varPesquisa->listaPesquisas($_POST['centro']);

	}
	if($_GET['act']=="LISTA_FORMULARIOS"){
		$varPesquisa = new Pesquisa(null);
		$varPesquisa->listaFormularios($_POST['id'], $_POST['centro']);
	}
	if($_GET['act']=="LISTA_CAMPOS"){
		$varPesquisa = new Pesquisa(null);
		$varPesquisa->listaCampos($_POST['id']);
	}
	if($_GET['act']=="ENVIA_EMAIL"){
		$varPesquisa = new Pesquisa($_POST['pesquisa']);
		$varCentro	 = new Centro($_POST['centro']);


		$html = "
			<html>
				<body bgcolor='#F6F6F6'>
								<p align='center'><table width='90%'>
									<tr>
										<td width='218' style='background-color:#F6F6F6' height='15'></td>
										<td style='background-color:#F6F6F6' height='15'></td>
										<td style='background-color:#F6F6F6' height='15'></td>
									</tr>
									<tr>
										<td width='218' style='background-color:#F6F6F6'></td>
										<td width='*'style='background-color:white'>
												<table width='67%' class='es-content-body' cellspacing='0' cellpadding='0' align='center' style='border-collapse: collapse; border-spacing: 0px; background-color: transparent;'>
													<tr style='border-collapse: collapse;'>
														<td align='left' style='margin: 0px; padding: 20px;' bgcolor='#FFFFFF'>
														<table class='es-left' cellspacing='0' cellpadding='0' align='left' style='border-collapse: collapse; border-spacing: 0px; float: left;'>
															<tr style='border-collapse: collapse;'>
																<td class='es-m-p0r es-m-p20b' valign='top' align='center' style='padding: 0px; margin: 0px; width: 356px;'>
																<table width='100%' cellspacing='0' cellpadding='0' role='presentation' style='border-collapse: collapse; border-spacing: 0px;'>
																	<tr style='border-collapse: collapse;'>
																		<td class='es-infoblock es-m-txt-c' align='left' style='padding: 0px; margin: 0px; line-height: 14px; font-size: 12px; color: rgb(204, 204, 204);'>
																		<p style='margin: 0px; text-size-adjust: none; font-size: 12px; font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; line-height: 14px; color: rgb(204, 204, 204);'>
																		<img src='https://www.phartrials.com.br/images/logo.png' width='83' height='101' alt='PHARTRIALS'></td>
																	</tr>
																</table>
																</td>
															</tr>
														</table>
														<table cellspacing='0' cellpadding='0' align='right' style='border-collapse: collapse; border-spacing: 0px;'>
															<tr style='border-collapse: collapse;'>
																<td align='left' style='padding: 0px; margin: 0px; width: 184px;'>
																&nbsp;</td>
															</tr>
														</table>
														</td>
													</tr>
													<tr style='border-collapse: collapse;'>
																		<td align='center' style='margin-left:11px; margin-right:5px; margin-top:4px; margin-bottom:4px; padding-left:0px; padding-right:0px; padding-top:0px; padding-bottom:15px' bgcolor='#FFFFFF'>
																		<h2 style='margin: 0px; line-height: 29px; font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 24px; font-style: normal; font-weight: normal; color: rgb(51, 51, 51);'>
																		Prezado(a) <b>".$varCentro->coordenador."</b>, você recebeu um
																		convite para participar de nossa
																		pesquisa.</h2>
																		</td>
																	</tr>
																	<tr style='border-collapse: collapse;'>
																		<td align='center' style='margin-left:11px; margin-right:5px; margin-top:4px; margin-bottom:4px; padding-left:0px; padding-right:0px; padding-top:20px; padding-bottom:0px' bgcolor='#FFFFFF'>
																		<p style='margin: 0px; text-size-adjust: none; font-size: 14px; font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; line-height: 21px; color: rgb(51, 51, 51);'>
																		Siga o link abaixo para preencher sua
																		pesquisa. Agradecemos a colaboração.</td>
																	</tr>
																	<tr style='border-collapse: collapse;'>
																		<td align='left' style='margin: 4px 5px;; padding-left:0px; padding-right:0px; padding-top:15px; padding-bottom:0px' bgcolor='#FFFFFF'>
																		<p style='margin: 0px; text-size-adjust: none; font-size: 14px; font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; line-height: 21px; color: rgb(51, 51, 51);'>
																		&nbsp;</td>
																	</tr>
																	<tr style='border-collapse: collapse;'>
																		<td align='left' style='margin: 4px 5px;; padding-left:0px; padding-right:0px; padding-top:20px; padding-bottom:0px' bgcolor='#FFFFFF'>
																		<p style='margin: 0px; text-size-adjust: none; font-size: 14px; font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; line-height: 21px; color: rgb(51, 51, 51);' align='center'>
																		<a href='http://www.sistemadepesquisaclinica.com.br/pesquisa/survey.php?id=".$varPesquisa->retornaLink($_POST['centro'], $_POST['pesquisa'])."'>https://www.sistemadepesquisaclinica.com.br/pesquisa</a><p style='margin: 0px; text-size-adjust: none; font-size: 14px; font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; line-height: 21px; color: rgb(51, 51, 51);' align='center'>
																		&nbsp;<p style='margin: 0px; text-size-adjust: none; font-size: 14px; font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; line-height: 21px; color: rgb(51, 51, 51);' align='center'>
																		&nbsp;</td>
																	</tr>
																	<tr style='border-collapse: collapse;'>
																		<td align='center' style='padding: 10px 0px; margin: 0px;'>
																		<p style='text-size-adjust: none; font-size: 11px; font-family: arial, ' align='right'>
																		<i>*As informações preenchidas na pesquisa
																		são confidenciais.</i></td>
																	</tr>
																	<tr style='border-collapse: collapse;'>
																		<td class='es-infoblock' align='center' style='padding: 0px; margin: 0px; line-height: 0px; font-size: 0px; color: rgb(204, 204, 204);'>
																		<a target='_blank' style='text-size-adjust: none; font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif; font-size: 12px; text-decoration: underline; color: rgb(44, 181, 67);' href='https://www.phartrials.com.br'>
																		<img src='https://www.phartrials.com.br/logo_spc.png' alt='SPC - Sistema de Pesquisa Clínica' width='237' style='display: block; border: 0px; outline: none; text-decoration: none;' height='49'></a></td>
																	</tr>
												</table></td>
										<td style='background-color:#F6F6F6' width='200'></td>
									</tr>
									<tr>
										<td width='218' style='background-color:#F6F6F6'></td>
										<td width='1166' style='background-color:#F6F6F6'></td>
										<td style='background-color:#F6F6F6'></td>
									</tr>
								</table></p>
				</body>
				</html>
		";

		$envioMail = new EnvioEmail("envio@sistemadepesquisaclinica.com.br", "SPC - Sistema de Pesquisa Clinica", $varCentro->email, $varCentro->coordenador, 1);
		echo $envioMail->enviar("Pesquisa", $html);

	}

