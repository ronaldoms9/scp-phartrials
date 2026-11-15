<?
	/*teste acentuação no report*/
	session_start();

	require_once __DIR__ . '/vendor/autoload.php';
	include_once __DIR__ . "/definicoes.php";
	include_once __DIR__ . "/class/conexao.class.php";
	include_once __DIR__ . "/class/projeto.class.php";
	include_once __DIR__ . "/funcoes.php";

	$varConexao = new Conexao();
	$varProjeto = new Projeto($_SESSION['projeto']);

	function formatar_texto($entrada){return utf8_decode($entrada);}
	function formatar_texto_bd($entrada){return utf8_decode($entrada);}


	$participante = $_GET['participante'];
	$grupo        = $_GET['grupo'];
	$gravidade    = $_GET['gravidade'];
	$status       = $_GET['status'];
	$periodo      = $_GET['periodo'];



	$stylesheet = "
	table{

	}
	.tabela-head{
		font-weight: bold !important;
		font-family: 'Abel', sans-serif !important;
		font-size: 9 !important;
		background-color:#f7f7f7 !important;
		border: 1px solid #2C3E50;

		padding-left: 2.4pt;
		padding-right: 2.4pt;
		padding-top: 2.4pt;
		padding-bottom: 2.4pt;
		text-align: center;

	}
	.tabela-cell{
		font-weight: bold !important;
		font-family: 'Abel', sans-serif !important;
		font-size: 7px !important;
		border-bottom: 1px solid #2C3E50 !important;
		border-left: 1px solid #2C3E50 !important;
		border-right: 1px solid #2C3E50 !important;
		padding-left: 5.4pt;
		padding-right: 5.4pt;
		padding-top: 3.4pt;
		padding-bottom: 3.4pt;
	}
		.texto_en{
			font-size:6.0pt;
		}
	";
	$mpdf = new \Mpdf\Mpdf([
		'tempDir' => __DIR__ . '/temp',
		'debug' => true,
		'mode' => 'utf-8',
		'margin-top' => 100,
		'margin-header' => 200,
		'margin-bottom' => 400,
		'orientation' => 'L'
	]);
	$mpdf->shrink_tables_to_fit = 0;
	//$mpdf->autoPageBreak = false;
	$mpdf->setAutoTopMargin = 'stretch';
	$mpdf->setAutoBottomMargin = 'stretch';
	$mpdf->allow_charset_conversion=true;
	$mpdf->WriteHTML($stylesheet,1);
	$cabecalho = '<table border="1" height="100%" width="100%"  cellspacing="0" cellpadding="0" bordercolor="#000000" style="border-collapse: collapse;">
		<tr style="border:1px solid #000000;">
			<td width="20%" style="border:1px solid #000000;"><center><br><img src="logo.png" width="130"></center><br><br><br></td>
			<td width="60%" style="border:1px solid #000000;">
				<p class="MsoNormal" align="center" style="text-align:center;line-height:150%"><b><span style="font-size: 12.0pt; line-height: 150%; font-family: Verdana,sans-serif"><center>TABELA DE EVENTOS ADVERSOS';
				if($_SESSION['lang']=="i"){
					$cabecalho .=  "<br><span class='texto_en'>[" . traduzir('TABELA DE EVENTOS ADVERSOS') . "]</span>";
				}
				$cabecalho .= '
				</center></span></b>
			</td>
			<td width="20%" style="border:1px solid #000000;">
				<center>
					<span style="font-size: 9.0pt; line-height: 150%; font-family: Verdana,sans-serif"><b>'.$varProjeto->numero.'</b><br> '.formata_utf8_encode($varProjeto->nome).'<br><b>'.$varProjeto->codigo.'</b></span>
				</center>
			</td>
		</tr>
		</table>
	';
	$rodape = '
		<table width="100%">
			<tr>
				<td width="*"><span style="font-size: 8.0pt; font-family: Verdana,sans-serif">
		</span></td>
				<td width="15%" align="right"><center><span style="font-size: 8.0pt; font-family: Verdana,sans-serif; align:right">'.traduzir('Página').' {PAGENO} '.traduzir('de').' {nbpg}</span></center></td>
			</tr>
		</table>
	';
	$mpdf->SetHTMLFooter($rodape);
	$mpdf->SetHTMLHeader($cabecalho);
	$mpdf->WriteHTML($stylesheet,1);
	$mpdf->WriteHTML('<br>');
	$tabela_ini = '
				<table  cellspacing="0" cellpadding="0" id="listagem" name="listagem" width="100%" class="table table-striped" style="font-family: Abel;font-size: 22px;">
					<thead>
					<tr style="border-right: 1px solid #2C3E50 !important;">
						<td class="tabela-head">Número<br>do Participante</td>
						<td class="tabela-head" style="width:60px !important">Código<br>do Participante</td>
						<td class="tabela-head">Grupo</td>
						<!--<td class="tabela-head">Status do<br>Participante</td>-->
						<td class="tabela-head">Evento</td>
						<td class="tabela-head">Grau/Intensidade</td>
						<td class="tabela-head">Período</td>
						<td class="tabela-head">Data de início</td>
						<td class="tabela-head">Horário de<br>início</td>
						<td class="tabela-head">Data do<br>término</td>
						<td class="tabela-head">Horário<br>de término</td>
						<td class="tabela-head">Ação tomada/Comentários</td>
						<td class="tabela-head">Desfecho</td>
					</tr>
					</thead>
					<tbody>
						';
						$mpdf->WriteHTML($tabela_ini);
						$tabela_ini = "";
						$query = "SELECT
										* from
											adv_evento
												where projeto = " . $_SESSION['projeto'];
						if($participante != ""){
							$query .= " and nro_paciente = ".$participante;
						}
						if($gravidade != ""){
							$query .= " and classificado_grave = '".formata_utf8_decode($gravidade)."'";
						}
						if($periodo != ""){
							$query .= " and periodo = '".($periodo)."'";
						}
						if($status != ""){
							$arrCurso = array('', formata_utf8_decode('1 - Em recuperação'));;
							if($status=="1"){
								$query .= " and desfecho in ('".implode("','", $arrCurso)."')";
							}else{
								$query .= " and desfecho not in ('".implode("','", $arrCurso)."')";
							}
						}
						if($grupo != ""){
							$query .= " and grupo = '".$grupo."'";
						}
						$query .= " order by  CAST(nro_paciente as INTEGER)";
						$res_pendencia = $varConexao->pesquisa($query);
						while($row_p = mysqli_fetch_assoc($res_pendencia)){
							$status = getParticipanteStatus($row_p['nro_paciente'], $row_p['codigo_paciente']);
							$pacGrupo = getParticipanteGrupo($row_p['nro_paciente'], $row_p['codigo_paciente']);
							if($grupo!=""){
								if($pacGrupo!=$grupo){
									continue;
								}
							}
							$tempParticipante = $row_p['voluntario_estudo'];
							if($tempParticipante == "Paciente"){
								$tempParticipante = "Participante de Pesquisa";
							}else{
								$tempParticipante = $row_p['voluntario_estudo'];
							}
							$tabela_ini .= '
								<tr>

									<td class="tabela-cell" style="text-align:center">'.$row_p['nro_paciente'].'</td>
									<td class="tabela-cell" style="text-align:center">'.$row_p['codigo_paciente'].'</td>
									<td class="tabela-cell"><center>'.$row_p['grupo'].'</center></td>
									<!--<td class="tabela-cell" style="text-align:center">'.$status.'</td>-->
									<td class="tabela-cell">'.$row_p['nome'].'</td>
									<td class="tabela-cell">'.$row_p['grau'];
									if($_SESSION['lang']=="i"){
										$tabela_ini .=  "<br><span class='texto_en'>[" . traduzir(formatar_texto_bd($row_p['grau'])) . "]</span>";
									}
									$tabela_ini .=
									'</td>
									<td class="tabela-cell">'.$row_p['periodo'].'</td>
									<td class="tabela-cell" style="text-align:center">'.$row_p['data_inicio'].'</td>
									<td class="tabela-cell" style="text-align:center">'.$row_p['hora_inicio'].'</td>
									<td class="tabela-cell" style="text-align:center">'.$row_p['data_termino'].'</td>
									<td class="tabela-cell" style="text-align:center">'.$row_p['hora_fim'].'</td>
									<td class="tabela-cell">'.$row_p['acao_tomada'].'<hr>'.$row_p['descricao'].'</td>
									<td class="tabela-cell">'.$row_p['desfecho'].'<br>'.$row_p['desfecho_outros'].'</td>
								</tr>
							';
						}

			$tabela_ini .= '
					</tbody>
				</table>
			';
	$mpdf->WriteHTML(utf8_encode($tabela_ini));
	$mpdf->WriteHTML('<br><br>');
	$mpdf->Output();
	$mpdf->charset_in='UTF-8';
?>