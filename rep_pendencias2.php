<?php
	/*Acentuação no report*/

	include_once "seguranca.php";
	require_once __DIR__ . '/vendor/autoload.php';
	include_once __DIR__ . "/class/projeto.class.php";

	$varProjeto = new Projeto($_SESSION['projeto']);
	$conexao = new Conexao();


	function formatar_texto($entrada){
		return utf8_decode($entrada);
	}
	function formatar_texto_bd($entrada){
		return utf8_decode($entrada);
	}
	function retorna_valor($agenda, $campo){
		$conexao = new Conexao();
		$query = "select * from vw_valor_campo_agenda where campo_id = $campo and id = $agenda";
		$volta = $conexao->pesquisa($query);
		$row   = mysqli_fetch_assoc($volta);
		return $row['valor'];
	}


	$stylesheet = "
	table{

	}
	.tabela-head{
		font-weight: bold !important;
		font-family: 'Abel', sans-serif !important;
		font-size: 12px !important;
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
		font-size: 9px !important;
		border-bottom: 1px solid #2C3E50 !important;
		border-left: 1px solid #2C3E50 !important;
		border-right: 1px solid #2C3E50 !important;
		padding-left: 5.4pt;
		padding-right: 5.4pt;
		padding-top: 5.4pt;
		padding-bottom: 5.4pt;

		//text-align: center;
	}
	.rodape{
			font-size:8.0pt;
			text-align:center;
		}
	.footer_versao{
			font-size:8pt;
			font-weight: bold;
			font-family: Times New Roman;
		}
	.paginacao{
		font-size:7pt;
		font-weight: bold;
		font-family: Times New Roman;
	}
	.versao_cabecalho{
			font-size:8pt;
			font-weight: bold;
			font-family: Times New Roman;
		}
	";

	$mpdf = new \Mpdf\Mpdf(['tempDir' => __DIR__ . '/temp', 'debug' => true, 'mode' => 'utf-8', 'format' => 'A4-L', 'margin-top' => 1158, 'margin-header' => 1500 ]);
	$mpdf->setAutoTopMargin = 'stretch';
	$mpdf->setAutoBottomMargin = 'stretch';
	$mpdf->allow_charset_conversion=true;

	$cabecalho = '<table border="1" height="100%" width="100%"  cellspacing="0" cellpadding="0" bordercolor="#000000" style="border-collapse: collapse;">
		<tr style="border:1px solid #000000;">
			<td width="20%" style="border:1px solid #000000;"><center><br><img src="logo.png" width="50"></center><br><br><br></td>
			<td width="60%" style="border:1px solid #000000;">
				<p class="MsoNormal" align="center" style="text-align:center;line-height:150%"><b><span style="font-size: 12.0pt; line-height: 150%; font-family: Verdana,sans-serif"><center>LISTAGEM DE PENDÊNCIAS';
				if($_SESSION['lang']=="i"){traduzir('LISTAGEM DE PENDÊNCIAS') . "]</span>";
				}
				$cabecalho .= '
				</center></span></b>
			</td>
			<td width="20%" style="border:1px solid #000000;">
				<center>
					<span style="font-size: 9.0pt; line-height: 150%; font-family: Verdana,sans-serif"><b>'.formata_utf8_encode($varProjeto->numero).'</b><br> '.formata_utf8_encode($varProjeto->nome).'</span>
				</center>
			</td>
		</tr>
		</table>
	';

	$rodape = '
		<table width="100%" style="text-align:center">
		  <tr style="text-align:center">
			<td width="90%" align="center" style="text-align:center">
				<span class="rodape"><b>PHARTRIALS</b><br>
				<br>
				Telefone   +0055 (19)TELEFONE<br>
				www.phartrials.com.br</span>
			</td>
			<td width="10%" style="text-align:right">
				<br><br><br><span class="paginacao">{PAGENO}/{nbpg}</font>
			</td>
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
						<td class="tabela-head"> Data da Visita</td>
						<td class="tabela-head">Participante</td>
						<td class="tabela-head">Pendência</td>
						<td class="tabela-head">Responsável</td>
						<td class="tabela-head">Ação tomada</td>
						<td class="tabela-head">Status</td>
						<td class="tabela-head">Data de<br>desfecho</td>
					</tr>
					</thead>
					<tbody>
						';
						$mpdf->WriteHTML($tabela_ini);
						$tabela_ini = "";
						$where = "";
						//$query = "select * from agenda_lista_pendencia where visita = '" . $_GET['data'] ."' order by id ";
						$responsavel  = formata_utf8_encode($_GET['responsavel']);
						$centro       = formata_utf8_encode($_GET['centro']);
						$data_inicial = formata_utf8_encode($_GET['data_inicial']);
						$data_final   = formata_utf8_encode($_GET['data_final']);
						$data_visita  = formata_utf8_encode($_GET['visita']);
						$status       = formata_utf8_encode($_GET['status']);
						$paciente     = formata_utf8_encode($_GET['paciente']);

						$where = "";
						if($responsavel!=""){ $where .= " and responsavel = '$responsavel' ";}
						if($centro!=""){ $where .= " and centro = '$centro'";}
						if($data_inicial!=""){ $where .= " and data_abertura_lis >= '$data_inicial' ";}
						if($data_final!=""){ $where .= " and data_abertura_lis <= '$data_final' ";}
						if($data_visita!=""){ $where .= " and visita = '$data_visita' ";}
						if($status!=""){ $where .= " and status = '$status' ";}
						if($paciente!=""){ $where .= " and paciente = '$paciente'"; };

						$query = "select * from agenda_lista_pendencia where projeto =  ".$_SESSION['projeto']." and id > 0 " . $where;
						$query .= " order by cast(paciente as integer), visita";


						$mpdf->WriteHTML(utf8_encode($tabela_ini));

						$res_pendencia = $conexao->pesquisa($query);
						//$tabela_ini = "";
						while($row_p = mysqli_fetch_assoc($res_pendencia)){
							$tabela_ini = '
								<tr>
									<td class="tabela-cell" style="text-align:center">'.$row_p['visita_data'].'</td>
									<td class="tabela-cell" style="text-align:center">'.$row_p['paciente'].'</td>
									<td class="tabela-cell">'.$row_p['achado'].'</td>
									<td class="tabela-cell">'.$row_p['responsavel'].'</td>
									<td class="tabela-cell">'.$row_p['acao'].'</td>
									<td class="tabela-cell" style="text-align:center">'.$row_p['status'].'</td>
									<td class="tabela-cell" style="text-align:center">'.$row_p['data_fechamento'].'</td>
								</tr>
							';
							$mpdf->WriteHTML(utf8_encode($tabela_ini));
							$tabela_ini = "";
						}

			$tabela_ini .= '
					</tbody>

				</table>
	';


	$mpdf->WriteHTML(utf8_encode($tabela_ini));
	$mpdf->WriteHTML('<br><br>');

	$mpdf->Output();
	//$mpdf->charset_in='UTF-8';

?>