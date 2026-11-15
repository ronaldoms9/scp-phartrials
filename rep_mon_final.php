<?php

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
	/*function traduzir($texto_entrada){
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
	}*/
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



	$query = "select  ";
	$query .= " agenda.*,
			cfg_centro.numero as centro_numero,
			cfg_centro.nome as centro_nome,
			cfg_centro.investigador  as centro_investigador,
			cfg_centro.coordenador as centro_coordenador
				from agenda
				inner join cfg_centro on agenda.centro = cfg_centro.id
			where agenda.id = " . $_GET['id'];

	$res_agenda = pesquisa($query);
	$row_agenda = mysqli_fetch_assoc($res_agenda);

	$query 			= "select * from proj_projeto where id = " . $_SESSION['projeto'];
	$res_projeto	= pesquisa($query);
	$row_projeto    = mysqli_fetch_assoc($res_projeto);

	$query = "select * from proj_projeto where id = " . $_SESSION['projeto'];
	$res_proj = pesquisa($query);
	$row_proj = mysqli_fetch_assoc($res_proj);


	$mpdf = new \Mpdf\Mpdf([
								'tempDir' => __DIR__ . '/temp',
								'debug' => true,
								'mode' => 'utf-8',
								'margin-top' => 100,
								'margin-header' => 200,
								'margin-bottom' => 400
							]);


	$mpdf->shrink_tables_to_fit = 0;
	//$mpdf->autoPageBreak = false;
	$mpdf->setAutoTopMargin = 'stretch';
	$mpdf->setAutoBottomMargin = 'stretch';
	$mpdf->allow_charset_conversion=true;
	$mpdf->WriteHTML($stylesheet,1);

	$cabecalho = '<table border="1" height="100%" width="100%"  cellspacing="0" cellpadding="0" bordercolor="#000000" style="border-collapse: collapse;">
		<tr style="border:1px solid #000000;">
			<td width="142" style="border:1px solid #000000;"><center><br><img src="logo.png" width="50"></center><br><br><br></td>
			<td style="border:1px solid #000000;">
			<p class="MsoNormal" align="center" style="text-align:center;line-height:150%"><b><span style="font-size: 12.0pt; line-height: 150%; font-family: Verdana,sans-serif"><center>Relatório de Visita de Monitoria</center></span></b></td>
		</tr>
		</table>
	';

	$mpdf->SetHTMLFooter($rodape);
	$mpdf->SetHTMLHeader($cabecalho);



	$tabela_ini = '
			<table class="MsoNormalTable" border="1" cellspacing="0" cellpadding="0" width="699" style="border-collapse: collapse; border: medium none;">
			<tr style="page-break-inside: avoid; height: 32.95pt">
				<td width="699" colspan="5" style="width: 524.55pt; height: 32.95pt; border: 1.0pt solid windowtext; padding-left: 3.5pt; padding-right: 3.5pt; padding-top: 3.5pt; padding-bottom: 3.5pt; background: #D9D9D9">
					<span class="texto_caption">
						Relatório de Visita de Monitoria
					</span>
				</td>
			</tr>
			<tr style="page-break-inside: avoid; height: 42.95pt">
				<td width="151" rowspan="2" style="width: 113.3pt; border-left: 1.0pt solid windowtext; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.0pt solid windowtext; padding-left: 3.5pt; padding-right: 3.5pt; padding-top: 3.5pt; padding-bottom: 3.5pt; background: #D9D9D9">
					<span class="texto_caption">
						Visita
					</span>
				</td>
				<td width="76" valign="top" style="width: 56.8pt; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.0pt solid windowtext; padding-left: 3.5pt; padding-right: 3.5pt; padding-top: 3.5pt; padding-bottom: 3.5pt; background: #D9D9D9">

					<span class="texto_caption">
						Número
					</span>
				</td>

				<td width="198" valign="top" style="width: 148.85pt; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.0pt solid windowtext; padding-left: 3.5pt; padding-right: 3.5pt; padding-top: 3.5pt; padding-bottom: 3.5pt;">
					<center><span class="texto_conteudo centralizado">
							'.$row_agenda['nro_visita'].'
					</span></center>
				</td>

				<td width="57" valign="top" style="width: 42.6pt; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.0pt solid windowtext; padding-left: 3.5pt; padding-right: 3.5pt; padding-top: 3.5pt; padding-bottom: 3.5pt; background: #D9D9D9">
					<span class="texto_caption">
						Data
					</span>
				</td>
				<td width="217" valign="top" style="width: 163.0pt; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.0pt solid windowtext; padding-left: 3.5pt; padding-right: 3.5pt; padding-top: 3.5pt; padding-bottom: 3.5pt;">
					<center>
						<span class="texto_conteudo centralizado">
							' . $_GET['data'] .'
						</span>
					</center>
				</td>
			</tr>

			<tr>
				<td width="151" valign="top" style="width: 113.3pt; border-left: 1.0pt solid windowtext; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.0pt solid windowtext; padding-left: 3.5pt; padding-right: 3.5pt; padding-top: 3.5pt; padding-bottom: 3.5pt; background: #D9D9D9">
					<span class="texto_caption">
						Protocolo
					</span>
				</td>
				<td width="548" colspan="4" valign="top"
					style="border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.0pt solid windowtext; padding-left: 3.5pt; padding-right: 3.5pt; padding-top: 3.5pt; padding-bottom: 3.5pt" class="justificado">
					<span class="texto_conteudo">
						'.formatar_texto_bd($row_proj['descricao']).'
					</span>
				</td>
			</tr>
			<tr>
				<td width="151" valign="top" style="width: 113.3pt; border-left: 1.0pt solid windowtext; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.0pt solid windowtext; padding-left: 3.5pt; padding-right: 3.5pt; padding-top: 3.5pt; padding-bottom: 3.5pt; background: #D9D9D9">
					<span class="texto_caption">
						Tipo de Visita
					</span>
				</td>
				<td width="548" valign="center" colspan="4" valign="center" style="width: 411.25pt; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.0pt solid windowtext; padding-left: 3.5pt; padding-right: 3.5pt; padding-top: 3.5pt; padding-bottom: 3.5pt">
					<span class="texto_conteudo">
					';
			if(strtoupper($row_agenda['tipo_monitoria'])=="BIO - MONITORIA INICIAL"){
				$tabela_ini .= "VISITA INICIAL";
			}
			if(strtoupper($row_agenda['tipo_monitoria'])=="BIO - MONITORIA REGULAR"){
				$tabela_ini .= "Visita Regular";
			}
			if(strtoupper($row_agenda['tipo_monitoria'])=="INICIAL"){
				$tabela_ini .= "VISITA INICIAL";
			}
			if(strtoupper($row_agenda['tipo_monitoria'])=="REGULAR"){
				$tabela_ini .= "VISITA REGULAR";
			}
			if(strtoupper($row_agenda['tipo_monitoria'])=="FINAL"){
				$tabela_ini .= "VISITA DE ENCERRAMENTO";
			}
			$tabela_ini .= '
					</span>
				</td>
			</tr>
			<tr>
				<td width="151" valign="top" style="width: 113.3pt; border-left: 1.0pt solid windowtext; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.0pt solid windowtext; padding-left: 3.5pt; padding-right: 3.5pt; padding-top: 3.5pt; padding-bottom: 3.5pt; background: #D9D9D9">
					<span class="texto_caption">
						Nome Produto Investigacional
					</span>
				</td>
				<td width="548" valign="center" colspan="4" valign="center" style="width: 411.25pt; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.0pt solid windowtext; padding-left: 3.5pt; padding-right: 3.5pt; padding-top: 3.5pt; padding-bottom: 3.5pt">
					<span class="texto_conteudo">
					'. formatar_texto_bd($row_proj['nome']).'
					</span>
				</td>
			</tr>
			<tr>
				<td width="151" style="width: 113.3pt; border-left: 1.0pt solid windowtext; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.0pt solid windowtext; padding-left: 3.5pt; padding-right: 3.5pt; padding-top: 3.5pt; padding-bottom: 3.5pt; background: #D9D9D9">
					<span class="texto_caption">
						Versão do Protocolo
					</span>
				</td>
				<td width="548" colspan="4" style="width: 411.25pt; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.0pt solid windowtext; padding-left: 3.5pt; padding-right: 3.5pt; padding-top: 3.5pt; padding-bottom: 3.5pt">
					<span class="texto_conteudo">'.formatar_texto_bd($row_proj['versao']).'</span>
				</td>
			</tr>

			<tr>
				<td width="151" valign="top" style="width: 113.3pt; border-left: 1.0pt solid windowtext; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.0pt solid windowtext; padding-left: 3.5pt; padding-right: 3.5pt; padding-top: 3.5pt; padding-bottom: 3.5pt; background: #D9D9D9">
					<span class="texto_caption">
						Centro
					</span>
				</td>
				<td width="548" colspan="4" valign="top" style="width: 411.25pt; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.0pt solid windowtext; padding-left: 3.5pt; padding-right: 3.5pt; padding-top: 3.5pt; padding-bottom: 3.5pt">
					<span class="texto_conteudo">
					'.formatar_texto($row_agenda['centro_nome']).'
					</span>
				</td>
			</tr>

			<tr>
				<td width="151" valign="top" style="width: 113.3pt; border-left: 1.0pt solid windowtext; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.0pt solid windowtext; padding-left: 3.5pt; padding-right: 3.5pt; padding-top: 3.5pt; padding-bottom: 3.5pt; background: #D9D9D9">
					<span class="texto_caption">
						Investigador Principal
					</span>
				</td>
				<td width="548" colspan="4" style="width: 411.25pt; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.0pt solid windowtext; padding-left: 3.5pt; padding-right: 3.5pt; padding-top: 3.5pt; padding-bottom: 3.5pt">
					<span class="texto_conteudo">
					'.strtoupper(formatar_texto_bd($row_agenda['centro_investigador'])).'
					</span>
				</td>
			</tr>

			<tr>
				<td width="151" valign="top" style="width: 113.3pt; border-left: 1.0pt solid windowtext; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.0pt solid windowtext; padding-left: 3.5pt; padding-right: 3.5pt; padding-top: 3.5pt; padding-bottom: 3.5pt; background: #D9D9D9">
					<span class="texto_caption">
						Participantes
					</span>
				</td>
				<td width="548" colspan="4" style="width: 411.25pt; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.0pt solid windowtext; padding-left: 3.5pt; padding-right: 3.5pt; padding-top: 3.5pt; padding-bottom: 3.5pt">
					<span class="texto_conteudo">
					'.formatar_texto(strtoupper($row_agenda['presentes'])).'
					</span>
				</td>
			</tr>
		</table>
	';
	$mpdf->WriteHTML($tabela_ini);



		$html = "<br>
			<p class='texto_formulario'>
				O objetivo desta carta é destacar quais pontos do estudo supracitado foram verificados nas Visitas de Monitoria do estudo supracitado, e encerrar todas as pendências que encontravam-se em aberto.
			</p>
		";
		//$mpdf->WriteHTML($html);




		$query = "SELECT agenda.id, agenda.data, agenda.monitora, agenda.revisor, agenda.tipo_monitoria,
			monitor.nome as nome_monitor, monitor.assinatura as ass_monitor, monitor.tipo_usuario as tipo_monitor,
			revisor.nome as nome_revisor, revisor.assinatura as ass_revisor
			FROM agenda
				left join usu_usuario as monitor on agenda.monitora = monitor.id
				left join usu_usuario as revisor on agenda.revisor = revisor.id

				where agenda.id = " . $_GET['id'];
		$resAss = pesquisa($query);
		$rowAss = mysqli_fetch_assoc($resAss);

		//$mpdf->AddPage();
		$html = '		<br>
						<table class="MsoNormalTable" border="0" cellspacing="0" cellpadding="0" width="100%" style="width: 100.0%; border-collapse: collapse; border: medium none" >
								<tr height="15"><td colspan="5" height="15"><br></td></tr>
								<tr>
									<td class="header_formulario" >
										<b>
										<span class="texto_formulario_nro">';
											$html .= $countForm+1 .'.
										</span>
										<span class="texto_formulario">
											LISTA DE PENDÊNCIAS
										</span>
										</b>
									</td>
								</tr>
						</table>
						<table width="100%" class="MsoNormalTable" border="1" cellspacing="0" cellpadding="0" style="border-collapse: collapse; border: medium none">
							<tr>
								<td width="110" valign="top" style="border-left: 1.0pt solid black; border-right: 1.0pt solid black; border-top: 1.0pt solid black; border-bottom: 1.0pt solid black; padding-left: 5.4pt; 	padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm">
									<p class="MsoNormal" align="center" style="text-align:center"><b>
									<span lang="EN-GB" style="font-size:11.0pt;  font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Participante</span></b>
								</td>
								<td width="120" valign="top" style="border: 1.0pt solid black; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm">
									<p class="MsoNormal" align="left" style="text-align:center"><b>
									<span lang="EN-GB" style="font-size:11.0pt;  font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Tipo</span></b>
								</td>
								<td width="40%" valign="top" style="border-left: medium none; border-right: 1.0pt solid black; border-top: 1.0pt solid black; border-bottom: 1.0pt solid black; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm">
									<p class="MsoNormal" align="center" style="text-align:center"><b>
									<span lang="EN-GB" style="font-size:11.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Descrição</span></b>
								</td>
								<td width="40%" valign="top" style="border-left: medium none; border-right: 1.0pt solid black; border-top: 1.0pt solid black; border-bottom: 1.0pt solid black; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm">
									<p class="MsoNormal" align="center" style="text-align:center"><b>
									<span lang="EN-GB" style="font-size:11.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Ação</span></b>
								</td>
								<td width="100" valign="top" style="border-left: medium none; border-right: 1.0pt solid black; border-top: 1.0pt solid black; border-bottom: 1.0pt solid black; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm">
									<p class="MsoNormal" align="center" style="text-align:center"><b>
									<span lang="EN-GB" style="font-size:11.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Status</span></b>
								</td>
							</tr>
							';
							if($_SESSION['projeto']=="7"){
								$query_t = "select * from agenda_lista_pendencia where visita = ".$row_agenda['id']." and centro = ".$row_agenda['centro']." and status = 'PENDENTE' order by id";
							}else{

								$dataVis = $row_agenda['data'];
								$dataVis = substr($dataVis, 6,2) . "/" . substr($dataVis, 4,2) . "/" .substr($dataVis, 0,4);
								$query_t = "select * from agenda_lista_pendencia where  projeto = ".$_SESSION['projeto']." order by cast(paciente as integer)";

							}
							//echo $query_t;


							$res_p = pesquisa($query_t);
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
												'.formatar_texto_bd($row_p['tipo']).'
												</span>
											</p>
										</td>
										<td style="border-left: 1.0pt solid black; border-right: 1.0pt solid black; border-top: medium none; border-bottom: 1.0pt solid black; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm; background: white">
											<p class="MsoNormal" align="center" style="text-align:center">
												<span lang="EN-GB" style="font-size: 9.0pt; font-family: Verdana,sans-serif; ">
												'.formatar_texto_bd(nl2br($row_p['achado'])).'
												</span>
											</p>
										</td>
										<td style="border-left: medium none; border-right: 1.0pt solid black; border-top: medium none; border-bottom: 1.0pt solid black; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm; background: white">
											<p class="MsoNormal" align="center" style="text-align:center">
												<span lang="EN-GB" style="font-size: 9.0pt; font-family: Verdana,sans-serif; ">
												'.formatar_texto_bd(nl2br($row_p['acao'])).'
												</span>
											</p>
										</td>
										<td valign="center" style="border-left: medium none; border-right: 1.0pt solid black; border-top: medium none; border-bottom: 1.0pt solid black; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm; background: white">
											<p class="MsoNormal" style="text-align:justify;">
												<span style="font-size: 9.0pt;  font-family: Verdana,sans-serif;">
												'.formatar_texto_bd($row_p['status']).'
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

				';
		$mpdf->WriteHTML($html);
		$countForm++;


		$query = "SELECT agenda.id, agenda.data, agenda.monitora, agenda.revisor, agenda.tipo_monitoria,
			monitor.nome as nome_monitor, monitor.assinatura as ass_monitor, monitor.tipo_usuario as tipo_monitor,
			revisor.nome as nome_revisor, revisor.assinatura as ass_revisor
			FROM agenda
				left join usu_usuario as monitor on agenda.monitora = monitor.id
				left join usu_usuario as revisor on agenda.revisor = revisor.id

				where agenda.id = " . $_GET['id'];
		$resAss = pesquisa($query);
		$rowAss = mysqli_fetch_assoc($resAss);

		$html = '		<br><br><br>
						<table class="MsoNormalTable" border="0" cellspacing="0" cellpadding="0" width="100%" style="width: 100.0%; border-collapse: collapse; border: medium none" >
								<tr height="15"><td colspan="5" height="15"><br></td></tr>
								<tr>
									<td class="header_formulario" >
										<b>
										<span class="texto_formulario_nro">';
											$html .= $countForm+1 .'.
										</span>
										<span class="texto_formulario">
											ASSINATURAS
										</span>
										</b>
									</td>
								</tr>
						</table>
						<br>
						<table border="0" width="100%" cellspacing="0" cellpadding="0">
							<tr>
								<td colspan="5" style="border-bottom-style: solid; border-bottom-width: 2px; border-color: black; padding-left: 4px; padding-right: 4px; padding-top: 4px; padding-bottom: 4px">
									<span class="ass_header">Elaborado por</span>
								</td>
							</tr>
							<tr>
								<td style="padding-top: 2px; padding-left: 2px"><span class="ass_caption">Nome:</span></td>
								<td style="padding-top: 2px;"><span class="ass_item">'.strtoupper($rowAss['nome_monitor']).'</span></td>
								<td>&nbsp;</td>
								<td style="padding-top: 4px;"><span class="ass_caption">Assinatura</span></td>
								<td valign="top" rowspan="3" width="200" style="padding-top: 4px;">
									<span style="padding-top: 4px; padding-bottom: 4px; padding-left: 4px;">
										';
								if($rowAss['ass_revisor']!=""){
									$html .= '<img src="assinaturas/'.$rowAss['ass_monitor'].'" height="60">';
								}
								$html .= '
									</span>
								</td>
							</tr>
							<tr>
								<td valign="top" style="padding-top: 4px;"><span class="ass_caption">Função:</span></td>
								<td valign="top" style="padding-top: 4px;"><span class="ass_item">';
								if($rowAss['tipo_monitor']=="2"){
									$html.= "MONITOR(a) DE PESQUISA</u>";
								}
								$html .= '</span></td>
								<td></td>
								<td></td>

							</tr>
							<tr>
								<td valign="top" style="padding-top: 4px;"><span class="ass_caption">Data</span></td>
								<td valign="top" style="padding-top: 4px;"><span class="ass_item">'.$_GET['data'].'</span><br><span class="ass_item_caption">(dd/mm/aaaa)</span></font></td>
								<td></td>
								<td></td>

							</tr>
						</table>


					<br>
						<table border="0" width="100%" cellspacing="0" cellpadding="0">
							<tr>
								<td colspan="5" style="border-bottom-style: solid; border-bottom-width: 2px; border-color: black; padding-left: 4px; padding-right: 4px; padding-top: 4px; padding-bottom: 4px">
									<span class="ass_header">Revisado por</span>
								</td>
							</tr>
							<tr>
								<td style="padding-top: 4px;"><span class="ass_caption">Nome:</span></td>
								<td valign="top" style="padding-top: 4px;"><span class="ass_item">'.strtoupper($rowAss['nome_revisor']).'</span></td>
								<td>&nbsp;</td>
								<td style="padding-top: 4px;"><span class="ass_caption">Assinatura</span></td>
								<td valign="top" rowspan="3" width="200" style="padding-top: 4px;">
									<span style="padding-top: 4px; padding-bottom: 4px; padding-left: 4px;">
										';
								if($rowAss['ass_monitor']!=""){
									$html .= '<img src="assinaturas/'.$rowAss['ass_revisor'].'"  height="60">';
								}
								$html .= '
									</span>
								</td>
							</tr>
							<tr>
								<td valign="top" style="padding-top: 4px;"><span class="ass_caption">Função:</span></td>
								<td valign="top" style="padding-top: 4px;"><span class="ass_item">';
								if($rowAss['tipo_monitor']=="2"){
									$html.= "MONITOR(a) DE PESQUISA</u>";
								}
								$html .= '
								</span></td>
								<td></td>
								<td></td>

							</tr>
							<tr>
								<td valign="top" style="padding-top: 4px;"><span class="ass_caption">Data</span></td>
								<td valign="top" style="padding-top: 4px;"><span class="ass_item"><span class="ass_item">'.$_GET['data'].'</span></span><br><span class="ass_item_caption">(dd/mm/aaaa)</span></font></td>
								<td></td>
								<td></td>

							</tr>
						</table>

				';
		$mpdf->WriteHTML($html);




	$mpdf->Output();
	$mpdf->charset_in='UTF-8';
?>