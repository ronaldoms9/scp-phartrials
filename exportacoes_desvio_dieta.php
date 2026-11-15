<?
	/*teste acentuação no relatório*/
	session_start();

	require_once __DIR__ . '/vendor/autoload.php';
	include_once __DIR__ . "/definicoes.php";
	include_once __DIR__ . "/class/conexao.class.php";
	include_once __DIR__ . "/class/projeto.class.php";
    include_once __DIR__ . "/funcoes.php";

	$varConexao = new Conexao();
	$varProjeto = new Projeto($_SESSION['projeto']);


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
			<td width="20%" style="border:1px solid #000000;"><center><br><img src="logo.png" width="150"></center><br><br><br></td>
			<td width="60%" style="border:1px solid #000000;">
				<p class="MsoNormal" align="center" style="text-align:center;line-height:150%"><b><span style="font-size: 12.0pt; line-height: 150%; font-family: Verdana,sans-serif"><center>TABELA DE DESVIOS DE DIETA';
				if($_SESSION['lang']=="i"){
					$cabecalho .=  "<br><span class='texto_en'>[" . traduzir('TABELA DE DESVIOS DE DIETA') . "]</span>";
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
						<td class="tabela-head" style="width: 250px;">Centro</td>
                        <td class="tabela-head">Data</td>
						<td class="tabela-head">Participante</td>
                        <td class="tabela-head">Grupo</td>
						<td class="tabela-head">Período</td>
						<td class="tabela-head">Horário<br>Previsto</td>
						<td class="tabela-head">Horário<br>Realizado</td>
						<td class="tabela-head">Qual dieta?</td>
						<td class="tabela-head" style="width: 330px;">Motivo</td>
					</tr>
					</thead>
					<tbody>
						';
						$mpdf->WriteHTML($tabela_ini);
						$tabela_ini = "";
						$participante = $_GET['participante'];
						$grupo        = $_GET['grupo'];
						$periodo      = $_GET['periodo'];
						$query = "select
                                agenda_desvio_dieta.*,
                                    agenda_desvio_dieta.motivo as motivo_descricao,
                                    cfg_centro.nome as centro_nome
                                from agenda_desvio_dieta
                                    left join cfg_centro on agenda_desvio_dieta.centro = cfg_centro.id
                                    where agenda_desvio_dieta.projeto = ".$_SESSION['projeto'];
						if($participante != ''){
							$query .= " and agenda_desvio_dieta.participante = '$participante'";
						}
						if($grupo != ''){
							$query .= " and agenda_desvio_dieta.grupo = '$grupo'";
						}
						if($periodo != ''){
							$query .= " and CAST(agenda_desvio_dieta.horario_coleta AS INT) = '$periodo'";
						}
						//$query .= " order by centro, participante, agenda_desvio_dieta.id";
						$query .= " order by centro, grupo, horario_coleta, cast(participante as INTEGER) ";
						$res_pendencia = $varConexao->pesquisa($query);
						if(mysqli_num_rows($res_pendencia)==0){
							$tabela_ini .= '
								<tr>
									<td class="tabela-cell" colspan="9">Nenhum registro encontrado</td>
								</tr>
							';
						}
						else{
						while($row_p = mysqli_fetch_assoc($res_pendencia)){
							$tempo_atraso     = retornaDiferencaHorario($row_p['horario_previsto'], $row_p['horario_realizado']);
							$tabela_ini .= '
								<tr>
									<td class="tabela-cell">'.$row_p['centro_nome'].'</td>
                                    <td class="tabela-cell" style="text-align:center">'.$row_p['data'].'</td>
									<td class="tabela-cell" style="text-align:center">'.$row_p['participante'].'</td>
                                    <td class="tabela-cell" style="text-align:center">'.$row_p['grupo'].'</td>
									<td class="tabela-cell" style="text-align:center">'.$row_p['horario_coleta'].'</td>
									<td class="tabela-cell" style="text-align:center">'.$row_p['horario_previsto'].'</td>
									<td class="tabela-cell" style="text-align:center">'.$row_p['horario_realizado'].'</td>
									<td class="tabela-cell" style="text-align:center">'.$row_p['dieta'].'</td>
									<td class="tabela-cell">'.$row_p['motivo_descricao'].'</td>
									</tr>
								';
							}
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