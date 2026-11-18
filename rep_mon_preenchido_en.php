<?php
	$encoding = 'UTF-8';

	include_once __DIR__."/seguranca.php";
	include_once __DIR__."/class/agenda.class.php";
	include_once __DIR__."/class/projeto.class.php";
	include_once __DIR__."/definicoes.php";
	require_once __DIR__ . '/vendor/autoload.php';

	$varProjeto = new Projeto($_SESSION['projeto']);

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
	function traduzirREL($texto_entrada){
		global $conexao;
		$texto = "";
		//if(($texto_entrada!="Não")||($texto_entrada!="NÃO")){
		if(strtoupper(tirarAcentos($texto_entrada))!="NAO"){
			$query_tr = "select * from traducao where upper(portugues) = '".strtoupper($texto_entrada)."';";
			$res_tr = $conexao->pesquisa($query_tr);
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
	function formatar_texto_bd($entrada){
		return utf8_encode($entrada);
	}
	function formatar_texto($entrada){
		return utf8_encode($entrada);
	}



	$varData = $_GET['data'];
	$varAgendamento = $_GET['id'];
	$varAgenda		= new Agenda($_GET['id']);


	$query = "select  ";
	$query .= " agenda.*,
			cfg_centro.numero as centro_numero,
			cfg_centro.nome as centro_nome,
			cfg_centro.investigador  as centro_investigador,
			cfg_centro.coordenador as centro_coordenador
				from agenda
				inner join cfg_centro on agenda.centro = cfg_centro.id
			where agenda.id = " . $_GET['id'];

	$res_agenda = $conexao->pesquisa($query);
	$row_agenda = mysqli_fetch_assoc($res_agenda);

	$query 			= "select * from proj_projeto where id = " . $_SESSION['projeto'];
	$res_projeto	= $conexao->pesquisa($query);
	$row_projeto    = mysqli_fetch_assoc($res_projeto);

	$query = "select * from proj_projeto where id = " . $_SESSION['projeto'];
	$res_proj = $conexao->pesquisa($query);
	$row_proj = mysqli_fetch_assoc($res_proj);

	$mpdf = new \Mpdf\Mpdf([
								'tempDir' => __DIR__ . '/temp',
								'debug' => true,
								'mode' => 'utf-8',
								'margin-top' => 100,
								'margin-header' => 200,
								'margin_bottom' => 30
							]);


	$mpdf->shrink_tables_to_fit = 0;
	//$mpdf->autoPageBreak = false;
	$mpdf->setAutoTopMargin = 'stretch';
	//$mpdf->setAutoBottomMargin = 'stretch';
	$mpdf->allow_charset_conversion=true;
	$mpdf->WriteHTML($stylesheet,1);

	$cabecalho = '<table border="1" height="100%" width="100%"  cellspacing="0" cellpadding="0" bordercolor="#000000" style="border-collapse: collapse;">
		<tr style="border:1px solid #000000;">
			<td width="20%" style="border:1px solid #000000;"><center><br><img src="logo.png" width="50"></center><br><br><br></td>
			<td width="60%" style="border:1px solid #000000;">
				<p class="MsoNormal" align="center" style="text-align:center;line-height:150%"><b><span style="font-size: 12.0pt; line-height: 150%; font-family: Verdana,sans-serif"><center>'.strtoupper(traduzir('Relatório de Visita de Monitoria'));
				$cabecalho .= '
				</center></span></b>
			</td>
			<td width="20%" style="border:1px solid #000000;">
				<center>
					<span style="font-size: 9.0pt; line-height: 150%; font-family: Verdana,sans-serif"><b>'.$varProjeto->numero.'</b><br> '.$varProjeto->nome.'</span>
				</center>
			</td>
		</tr>
		</table>
	';
	if($_SESSION['lang']!="i"){
		$rodape = '
			<table autosize="1" width="100%" style="text-align:center" valign="bottom">
			<tr style="text-align:left">
				<td width="33%" align="left" valign="middle" style="text-align:left; valign:middle" >
					<span class="rodape"><br>Template vr 3.0 - 30/06/2025<br>
					</b></span>
				</td>
				<td width="33%" valign="bottom" align="center" style="text-align:center">
					<span class="rodape"><b>PHARTRIALS</b><br>
					'.traduzir('Telefone').'   +0055 (19) 3236-3206<br>
					www.phartrials.com.br</span>
				</td>
				<td width="33%" valign="bottom" style="text-align:right">
					<span class="paginacao">{PAGENO}/{nbpg}</font>
				</td>
			</tr>
			</table>
		';
	}else{

	}
	$mpdf->SetHTMLFooter($rodape);
	$mpdf->SetHTMLHeader($cabecalho);


	$tabela_ini = '
			<table autosize="1" class="MsoNormalTable" border="1" cellspacing="0" cellpadding="0" width="699" style="border-collapse: collapse; border: medium none;">
			<tr style="page-break-inside: avoid; height: 32.95pt">
				<td width="699" colspan="5" style="width: 524.55pt; height: 32.95pt; border: 1.0pt solid windowtext; padding-left: 3.5pt; padding-right: 3.5pt; padding-top: 3.5pt; padding-bottom: 3.5pt; background: #D9D9D9">
					<span class="texto_caption">'.traduzir('Relatório de Visita de Monitoria').'
						';
	$tabela_ini .= '</span>
				</td>
			</tr>
			<tr style="page-break-inside: avoid; height: 42.95pt">
				<td width="151" rowspan="2" style="width: 113.3pt; border-left: 1.0pt solid windowtext; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.0pt solid windowtext; padding-left: 3.5pt; padding-right: 3.5pt; padding-top: 3.5pt; padding-bottom: 3.5pt; background: #D9D9D9">
					<span class="texto_caption">'.traduzir('Visit');
	$tabela_ini .= '
					</span>
				</td>
				<td width="76" valign="top" style="width: 56.8pt; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.0pt solid windowtext; padding-left: 3.5pt; padding-right: 3.5pt; padding-top: 3.5pt; padding-bottom: 3.5pt; background: #D9D9D9">

					<span class="texto_caption">'.traduzir('Number');
	$tabela_ini .= '
					</span>
				</td>

				<td width="198" valign="top" style="width: 148.85pt; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.0pt solid windowtext; padding-left: 3.5pt; padding-right: 3.5pt; padding-top: 3.5pt; padding-bottom: 3.5pt;">
					<center><span class="texto_conteudo centralizado">
							'.$row_agenda['nro_visita'].'
					</span></center>
				</td>

				<td width="57" valign="top" style="width: 42.6pt; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.0pt solid windowtext; padding-left: 3.5pt; padding-right: 3.5pt; padding-top: 3.5pt; padding-bottom: 3.5pt; background: #D9D9D9">
					<span class="texto_caption">'.traduzir('Data');
	$tabela_ini .= '
					</span>
				</td>
				<td width="217" valign="top" style="width: 163.0pt; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.0pt solid windowtext; padding-left: 3.5pt; padding-right: 3.5pt; padding-top: 3.5pt; padding-bottom: 3.5pt;">
					<center>
						<span class="texto_conteudo centralizado">
							' . substr($row_agenda['data'],6,2) . '/' . substr($row_agenda['data'],4,2) . '/' .substr($row_agenda['data'],0,4).'
						</span>
					</center>
				</td>
			</tr>

			<tr>
				<td width="151" valign="top" style="width: 113.3pt; border-left: 1.0pt solid windowtext; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.0pt solid windowtext; padding-left: 3.5pt; padding-right: 3.5pt; padding-top: 3.5pt; padding-bottom: 3.5pt; background: #D9D9D9">
					<span class="texto_caption">'.traduzir('Protocolo');
	$tabela_ini .= '
					</span>
				</td>
				<td width="548" colspan="4" valign="top"
					style="border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.0pt solid windowtext; padding-left: 3.5pt; padding-right: 3.5pt; padding-top: 3.5pt; padding-bottom: 3.5pt" class="justificado">
					<span class="texto_conteudo">
						'.formatar_texto_bd(nl2br($row_proj['descricao'])).'
					</span>
				</td>
			</tr>
			<tr>
				<td width="151" valign="top" style="width: 113.3pt; border-left: 1.0pt solid windowtext; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.0pt solid windowtext; padding-left: 3.5pt; padding-right: 3.5pt; padding-top: 3.5pt; padding-bottom: 3.5pt; background: #D9D9D9">
					<span class="texto_caption">'.traduzir('Tipo de Visita');
	$tabela_ini .= '
					</span>
				</td>
				<td width="548" valign="center" colspan="4" valign="center" style="width: 411.25pt; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.0pt solid windowtext; padding-left: 3.5pt; padding-right: 3.5pt; padding-top: 3.5pt; padding-bottom: 3.5pt">
					<span class="texto_conteudo">
					';
			if(strtoupper($row_agenda['tipo_monitoria'])=="BIO - MONITORIA INICIAL"){				$tabela_ini .= traduzir("VISITA INICIAL");			}
			if(strtoupper($row_agenda['tipo_monitoria'])=="NOVO - INICIAL"){				$tabela_ini .= traduzir("VISITA INICIAL");			}
			if(strtoupper($row_agenda['tipo_monitoria'])=="INICIAL"){				$tabela_ini .= traduzir("VISITA INICIAL");			}
			if(strtoupper($row_agenda['tipo_monitoria'])=="REGULAR"){				$tabela_ini .= traduzir("VISITA REGULAR");			}
			if(strtoupper($row_agenda['tipo_monitoria'])=="NOVO - REGULAR"){				$tabela_ini .= traduzir("VISITA REGULAR");			}
			if(strtoupper($row_agenda['tipo_monitoria'])=="BIO - MONITORIA REGULAR"){				$tabela_ini .= traduzir("Visita Regular");			}
			if(strtoupper($row_agenda['tipo_monitoria'])=="FINAL"){				$tabela_ini .= traduzir("Closing Visit");			}
			if(strtoupper($row_agenda['tipo_monitoria'])=="[PC] - VISITA DE ENCERRAMENTO"){				$tabela_ini .= traduzir("Closing Visit");			}
			if(strtoupper($row_agenda['tipo_monitoria'])=="PROJETOS - REGULAR"){				$tabela_ini .= "VISITA REGULAR";			}
			if(strtoupper($row_agenda['tipo_monitoria'])=="PROJETO S - ANALISE DO BANCO DE DADOS"){				$tabela_ini .= "ANALISE DO BANCO DE DADOS";			}
			$tabela_ini .= '
					</span>
				</td>
			</tr>
			<tr>
				<td width="151" style="width: 113.3pt; border-left: 1.0pt solid windowtext; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.0pt solid windowtext; padding-left: 3.5pt; padding-right: 3.5pt; padding-top: 3.5pt; padding-bottom: 3.5pt; background: #D9D9D9">
					<span class="texto_caption">'.traduzir('Versão do Relatório de Monitoria');
	$tabela_ini .= '
					</span>
				</td>
				<td width="548" colspan="4" style="width: 411.25pt; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.0pt solid windowtext; padding-left: 3.5pt; padding-right: 3.5pt; padding-top: 3.5pt; padding-bottom: 3.5pt">
					<span class="texto_conteudo">'.number_format($varAgenda->versao,1).'</span>
				</td>
			</tr>
			<tr>
				<td width="151" valign="top" style="width: 113.3pt; border-left: 1.0pt solid windowtext; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.0pt solid windowtext; padding-left: 3.5pt; padding-right: 3.5pt; padding-top: 3.5pt; padding-bottom: 3.5pt; background: #D9D9D9">
					<span class="texto_caption">'.traduzir("Produto sob Investigação");
	$tabela_ini .= '
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
					<span class="texto_caption">'.traduzir('Versão do Protocolo');
	$tabela_ini .= '
					</span>
				</td>
				<td width="548" colspan="4" style="width: 411.25pt; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.0pt solid windowtext; padding-left: 3.5pt; padding-right: 3.5pt; padding-top: 3.5pt; padding-bottom: 3.5pt">
					<span class="texto_conteudo">'.formatar_texto_bd($row_proj['versao']).'</span>
				</td>
			</tr>

			<tr>
				<td width="151" valign="top" style="width: 113.3pt; border-left: 1.0pt solid windowtext; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.0pt solid windowtext; padding-left: 3.5pt; padding-right: 3.5pt; padding-top: 3.5pt; padding-bottom: 3.5pt; background: #D9D9D9">
					<span class="texto_caption">'.traduzir('Centro de Pesquisa');
	$tabela_ini .= '
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
					<span class="texto_caption">'.traduzir('Investigador Principal');
	$tabela_ini .= '
					</span>
				</td>
				<td width="548" colspan="4" style="width: 411.25pt; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.0pt solid windowtext; padding-left: 3.5pt; padding-right: 3.5pt; padding-top: 3.5pt; padding-bottom: 3.5pt">
					<span class="texto_conteudo">
					'.mb_convert_case(strtoupper(formatar_texto_bd($row_agenda['centro_investigador'])), MB_CASE_UPPER, $encoding).'
					</span>
				</td>
			</tr>

			<tr>
				<td width="151" valign="top" style="width: 113.3pt; border-left: 1.0pt solid windowtext; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.0pt solid windowtext; padding-left: 3.5pt; padding-right: 3.5pt; padding-top: 3.5pt; padding-bottom: 3.5pt; background: #D9D9D9">
					<span class="texto_caption">'.traduzir('Participantes da Pesquisa');
	$tabela_ini .= '
					</span>
				</td>
				<td width="548" colspan="4" style="width: 411.25pt; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.0pt solid windowtext; padding-left: 3.5pt; padding-right: 3.5pt; padding-top: 3.5pt; padding-bottom: 3.5pt">
					<span class="texto_conteudo">
					'.mb_convert_case(formatar_texto_bd($row_agenda['presentes']), MB_CASE_UPPER, $encoding).'
					</span>
				</td>
			</tr>
		</table>
	';
	$mpdf->WriteHTML($tabela_ini);

	//Começo loop nos formulários
	$query = "
		SELECT crf_agenda_forms.id,
				cfg_visitas.acao,
				crf_agenda_forms.agendamento,
				crf_agenda_forms.form_id,
				cfg_visitas.nome,
				cfg_visitas.texto_crf,
				cfg_visitas.texto_crf_en,
				cfg_visitas.ordem
					FROM crf_agenda_forms
						inner join cfg_visitas on crf_agenda_forms.form_id = cfg_visitas.id
						WHERE
							cfg_visitas.ordem <> ''
							and cfg_visitas.registro_valido = 1
							and crf_agenda_forms.agendamento = " . $_GET['id'] . "
								order by cast(cfg_visitas.ordem as DECIMAL)
	";

	$res_form = $conexao->pesquisa($query);

	$countForm = 0;

	while($row_form = mysqli_fetch_assoc($res_form)){
		$countField = 0;
		if($row_form['acao']=="PAGINA_BRANCO"){
			$html = "
				<table autosize='1'
						class='MsoNormalTable'
						border='0'
						cellspacing='0'
						cellpadding='0'
						width='100%'
						style='width: 100.0%; border-collapse: collapse; border: medium none' >
					<tr>
						<td width='100%' class='header_pagina_em_branco' >
						<br><br><br><br><br>
						<br><br><br><br><br>
						<br><br><br><br><br>
						<br><br><br><br><br>
						<br><br><br><br><br>
						<br><br><br><br><br>
						<br><br><br><br><br>
						<br><br><br><br><br>
						<br><br><br><br><br>
						<br><br><br><br><br>
						<br><br><br><br><br>
						<br><br><br><br><br>
						<br>
						</td>
					</tr>
				</table>
			";
		}else{
			$countForm++;
			$html = "
				<br><div>
					<table
						autosize='1'
						class='MsoNormalTable'
						border='0'
						cellspacing='0'
						cellpadding='0'
						width='100%' style='width: 100.0%; border-collapse: collapse; border: medium none' >
						<thead>
			";
			if($row_form['acao']!="CABECALHO"){
				if($row_form['ordem']!="9999"){
					$html .= "
									<tr>
										<td width='23%' class='header_formulario' >
										<b>
										<span class='texto_formulario_nro'>";
												$html .= $countForm.". ";
					$html .=			"</span>
										<span class='texto_formulario'>
											";
											$temmp = trim(mb_strtoupper(utf8_encode($row_form['texto_crf_en']." ")));
											if(substr(strtolower($temmp),0,6)=="farmac"){
												$temmp = "FARMACÊUTICOSX ";
											}
					$html .= 			$temmp;

					$html .= 			"
										</span>
										</b>
										<span class='texto_formulario_en'>
										".
										$temp .= "</span></td>
									</tr>
					";
				}
			}else{
				$html .= "
								<tr>
									<td class='cabecalho'>
									<span class='cabecalho_1'>
										<b>";
										$temmp = trim(mb_strtoupper(utf8_encode($row_form['texto_crf'])));
				$html .= 	$temmp .	"</b>
									</span>
									";
							$temp .="		</td>
								</tr>
				";
			}
		}
		$html .= "
					</thead>
				</table>
		";
		$mpdf->WriteHTML($html);
		$html = "";



		if($row_form['ordem']=="99999"){
			if($_SESSION['projeto']=="7"){
				$query_t = "select * from agenda_lista_pendencia where visita = ".$row_agenda['id']." and centro = ".$row_agenda['centro']." and status = 'PENDENTE' order by id";
			}else{
				$dataVis = $row_agenda['data'];
				$dataVis = substr($dataVis, 6,2) . "/" . substr($dataVis, 4,2) . "/" .substr($dataVis, 0,4);
				//$query_t = "select * from agenda_lista_pendencia where visita = '".$dataVis."' and projeto = ".$_SESSION['projeto']." order by id";
				$query_t = "select * from agenda_lista_pendencia where visita = '".$row_agenda['nro_visita']."' and projeto = ".$_SESSION['projeto']." order by id";
				$resPend = $conexao->pesquisa($query_t);

				if(mysqli_num_rows($resPend)==0){
					$html = "<table
								autosize='1'
								class='MsoNormalTable'
								border='1'
								cellspacing='0'
								cellpadding='0'
								width='100%'
								style='width: 100.0%; border-collapse: collapse; border: medium none'>
								<tr>
									<td width='100%' class='class_item'  valign='top'>Nenhuma pendência cadastrada para essa visita</td>
								</tr>
							</table>
					";
					$html = "";
				}else{
					$html = '
						<table autosize="1" width="100%" class="MsoNormalTable" border="1" cellspacing="0" cellpadding="0" style="border-collapse: collapse; border: medium none">
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
					while($row_p = mysqli_fetch_assoc($resPend)){
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
											'.nl2br(formatar_texto_bd($row_p['achado'])).'
											</span>
										</p>
									</td>
									<td style="border-left: medium none; border-right: 1.0pt solid black; border-top: medium none; border-bottom: 1.0pt solid black; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm; background: white">
										<p class="MsoNormal" align="center" style="text-align:center">
											<span lang="EN-GB" style="font-size: 9.0pt; font-family: Verdana,sans-serif; ">
											'.nl2br(formatar_texto_bd($row_p['acao'])).'
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
					$html .= "</table>";
					$mpdf->WriteHTML($html);
					$html = "";
				}
			}
		}else{

			//Pegar campo a campo
			$query = "SELECT cfg_visitas_campos.tipo,
							crf_agenda_campos.id,
							crf_agenda_campos.valor,
							cfg_visitas_campos.legenda,
							crf_agenda_campos.comentario,
							cfg_visitas_campos.numeracao,
							cfg_visitas_campos.nome,
							cfg_visitas_campos.texto,
							cfg_visitas_campos.texto_en,
							cfg_visitas_campos.texto_es,
							cfg_visitas_campos.ordem,
							cfg_visitas_campos.selecao_valores
					from crf_agenda_campos
						inner join cfg_visitas_campos on crf_agenda_campos.campo_id = cfg_visitas_campos.id
						where crf_agenda_campos.form_id = ".$row_form['id']."
								ORDER BY cfg_visitas_campos.ordem
			";
			//echo $query;
			$res_campo = $conexao->pesquisa($query);


			$mpdf->WriteHTML($html);
			$html = "";
			while($row_campo = mysqli_fetch_assoc($res_campo)){

				if($row_campo['tipo']=="divisao"){
					$html = "
						<table autosize='1'
							class='MsoNormalTable'
							border='1'
							cellspacing='0'
							cellpadding='0'
							width='100%'
							style='width: 100.0%; border-collapse: collapse; border: medium none'>
							<tr>
								<td width='23%' class='divisao'>
									<span class='texto_formulario_divisao'>&nbsp;".trim(mb_strtoupper(formatar_texto_bd($row_campo['texto_en'])))."</span>";
					$html .= "
								</td>
							</tr>
						</table>
					";
					$mpdf->WriteHTML($html);
				}else{
					$countField++;
					if(($row_campo['tipo']!="json")&&($row_campo['tipo']!="json_vertical")){
						if($row_campo['tipo']=="quebra"){
							$mpdf->WriteHTML($html);
							$html = "";
							$mpdf->AddPage();
						}else{

								if($row_campo['tipo']!="select_coluna"){
									$largura_1 = '45% !important';
									$largura_2 = '55%';
								}else{
									if($row_campo['ordem']!=""){
										$largura_1 = '70% !important';
										$largura_2 = '';
										$opcoes = "";
										$opcoes = str_replace("<br />","%",$row_campo['selecao_valores']);
										$opcoes = str_replace("\n","",$opcoes);
										$combo = "";
										$temp  = "";
										$qtd_col = 0;
										for($z=0;$z<strlen($opcoes);$z++){
										   if(substr($opcoes,$z,1)=="%"){
												$qtd_col++;
										   }
										}
										if($row_campo['legenda']!=""){
											$qtd_col++;
										}
										if($qtd_col>0){
											$largura2 = intval(30 / $qtd_col);
										}else{
											$largura2 = intval(30);
										}
									}else{
										$largura_1 = '30% !important';
										$largura_2 = '70% !important';
									}
								}

								if($row_campo['tipo']!="caixa"){
									$html = "
											<table autosize='1'
												class='MsoNormalTable'
												border='1'
												cellspacing='0'
												cellpadding='0'
												width='100%'
												style='
												important; width: 100.0%;
												border-collapse: collapse;
												border: medium none'>
												<tr>
													<td width='".$largura_1."' class='class_item'  valign='top'>
											";
											if($row_campo['ordem']!=999){
												$html .=	"<span class='texto_ordem'>".$countForm . "." . $countField.".&nbsp;&nbsp;</span>";
											}
											$html .="	<span class='texto'>".trim(mb_strtoupper(formatar_texto_bd($row_campo['texto_en'])))."</span>";
											$html .= "
													</td>";
									if(($row_campo['tipo']=="texto")||($row_campo['tipo']=="numero")||($row_campo['tipo']=="data")||($row_campo['tipo']=="calculo")){
										$html .= "<td width='".$largura_2."' class='class_item'><center><span class='texto_conteudo'>".formatar_texto_bd($row_campo['valor'])."</span>";
										$html .= "</center>";
									}


									if($row_campo['tipo']=="select"){
										$html .= "<td width='".$largura_2."' class='class_item'>";
										$opcoes = "";
										$opcoes = str_replace("<br />","%",$row_campo['selecao_valores']);
										$opcoes = str_replace("\n","",$opcoes);

										$combo = "";
										$temp  = "";
										for($z=0;$z<strlen($opcoes);$z++){
										   if(substr($opcoes,$z,1)!="%"){$temp.=substr($opcoes,$z,1);}
										   else{
												$temp=trim($temp);
												$temp_selected = "";
												if(trim($rowItems['valor'])==$temp){ $temp_selected = " selected "; }
												$html .= $tamanho."<p><img src='imagens/check.png' width='10'><span valign='middle' class='texto'>&nbsp;&nbsp;<b>".formatar_texto_bd($temp)."</b></span></p>";
												$temp="";
										   }
										}
										if(strlen($temp)>0){
												$temp=trim($temp);
												$temp_selected = "";
												if(trim($rowItems['valor'])==$temp){ $temp_selected = " selected "; }
												$html .= $tamanho."<p><img src='imagens/check.png' width='10'><span valign='middle' class='texto'>&nbsp;&nbsp;<b>".formatar_texto_bd($temp)."</b></span></p>";
												$temp="";
										}
									}
									if($row_campo['tipo']=="select_coluna"){
										$opcoes = "";
										$opcoes = str_replace("<br />","%",$row_campo['selecao_valores']);
										$opcoes = str_replace("\n","",$opcoes);
										$combo = "";
										$temp  = "";
										for($z=0;$z<strlen($opcoes);$z++){
										   if(substr($opcoes,$z,1)!="%"){$temp.=substr($opcoes,$z,1);}
										   else{
												$temp=trim($temp);
												$temp_selected = "";
												if(trim($rowItems['valor'])==$temp){ $temp_selected = " selected "; }
												$html .= $tamanho."<td width='".$largura_2."' class='class_item_coluna'>
														<p>
												";
														if($temp==$row_campo['valor']){
															$html .= "<img src='imagens/checked.png' width='13'>";
														}else{
															$html .= "<img src='imagens/check.png' width='13'>";
														}

												$html .="<br><span valign='middle' class='texto'>
														<b>".formatar_texto_bd(str_replace(" - ", "<br>", traduzir($temp)))."</b></p>";
														$html .= "</td>";
												$temp="";
										   }
										}
										if(strlen($temp)>0){
												$temp=trim($temp);
												$temp_selected = "";
												if(trim($rowItems['valor'])==$temp){ $temp_selected = " selected "; }
												$html .= $tamanho."<td width='".$largura_2."' class='class_item_coluna' valign='middle'><p valign='middle'>
															";
														if($temp==$row_campo['valor']){
															$html .= "<img src='imagens/checked.png' width='13'>";
														}else{
															$html .= "<img src='imagens/check.png' width='13'>";
														}
												$html .= "			<span valign='middle' class='texto'><b>".formatar_texto_bd(str_replace(" - ", "<br>", traduzir($temp)))."</b></p>";
														$html .= "</td>";
												$temp="";
										}

									}
								$html .="			</td>
												</tr>
											</table>
								";
								$mpdf->WriteHTML($html);
								}else{
									//if($row_campo['tipo']!="select_coluna"){


										$html = "<table autosize='1'
												class='MsoNormalTable'
												border='1'
												cellspacing='0'
												cellpadding='0'
												width='100%'
												style='
												important; width: 100.0%;
												border-collapse: collapse;
												border: medium none'>
												<tr>
													<td width='".$largura_1."' class='class_item'  valign='top'>
											";
											if($row_campo['ordem']!=999){
												$html .=	"<span class='texto_ordem'>".$countForm . "." . $countField.".&nbsp;&nbsp;</span>";
											}
											$html .="	<span class='texto'>".trim(mb_strtoupper(formatar_texto_bd($row_campo['texto_en'])))."</span>";
											$html .= "
													</td>";
											$html .= "<td width='".$largura_2."' class='class_item'>
													<span class='texto_conteudo'>". formatar_texto_bd($row_campo['valor'])."</span>
												";
										$html .= "</tr></table>";
										$mpdf->WriteHTML($html);
								}

						}
					}else{
						if($row_campo['tipo']=='json'){
							//AQUI É JASON
							if($row_campo['valor']==""){
								$html = "
								<table autosize='1' border='1' cellspacing='0' cellpadding='0' width='649' style='width: 100%; border-collapse: collapse; border: medium none'>
									<tr><td width='100%'><span class='texto'>Nenhum ítem cadastrado</span></td></tr>
								</table>
								";
								$mpdf->WriteHTML($html);

							}else{
								$json = formatar_texto_bd($row_campo['selecao_valores']);
								$json = str_replace("<br />","",$json);
								$obj = json_decode($json);	//Puxando do objeto JSON do tipo do campo

								$tempCol = explode("|", formatar_texto_bd($row_campo['valor']));
								$qtd  = count($tempCol);	//Quantidade de registros que vai ter
								//echo "<br>CAMPO->".$row_campo['valor']."<br>***QUANTIDADE->".$qtd;
								for($i=0;$i<$qtd;$i++){
									if(trim($tempCol[$i]!="")){
										$colunas = $obj->colunas;
										$html = "
											<table autosize='1' class='MsoNormalTable' border='1' cellspacing='0' cellpadding='0' width='100%' style='width: 100.0%; border-collapse: collapse; border: medium none'>
												<tr>
													<td width='23%' class='divisao'>
														<span class='texto_formulario_divisao'>&nbsp;RECORD ".($i + 1) ."</span>
														";
										$html .= "
													</td>
												</tr>
											</table>
										";
										$html .= "
											<table autosize='1'
												autosize='0'
												border='1'
												cellspacing='0'
												cellpadding='0
												' style='width: 100%; border-collapse: collapse; border: medium none'>
										";
										for($col=0;$col<count($colunas);$col++){
											$itemCol = explode("§", $tempCol[$i]);
											$itemCol = $itemCol[$col];
											$itemCol = explode("=", $itemCol);
											$itemCol = $itemCol[1];
											//$itemCol = "";
											$html .= "
												<tr>
													<td
														class='class_item'
														valign='middle'
														width='330'><span style='font-size:8.0pt;font-weight: bold;'>".strtoupper($colunas[$col]->titulo_en)."</span>";
													$html .= "

													</td>
													<td class='linha_json' style='white-space: pre-wrap; !important'><center>";
													if($colunas[$col]->tipo=="select"){
														$html .= "<table autosize='1' border='0' width='60%' cellspacing='0' cellpadding='0'>
																	<tr><td width='1%'></td>";
														for($col_valores=0;$col_valores<count($colunas[$col]->valores);$col_valores++){
															$html .= "<td class='coluna_valor' style='text-align:center !important'>
																			<center>
																					";
																						if($colunas[$col]->valores[$col_valores]->titulo_en!=traduzir($itemCol)){
																							$html .= "<img src='imagens/check.png' width='10'>";
																						}else{
																							$html .= "<img src='imagens/checked.png' width='10'> ";
																						}
																				$html .= "
																				<br><b>".$colunas[$col]->valores[$col_valores]->titulo_en."</b>";
																						$html .= "
																			</center>
																	</td>";
														}
														$html .= "</tr>
																</table>";
													}else{
														$html .= "<span class='texto_conteudo'>".$itemCol."</span>";
													}
													$html .= "</center>
													</td>
												</tr>";
										}
										$html .="
											</table>
										";
										//echo $html . "<br>";
										$mpdf->WriteHTML($html);
									}
								}

							}
						}else{  //JSON VERTICAL
							$json = formatar_texto_bd($row_campo['selecao_valores']);
							$json = str_replace("<br />","",$json);
							$obj = json_decode($json);

							$linhas = $obj->linhas;
							$colunas = $obj->colunas;

							$html .= "
									<table autosize='1' border='1' cellspacing='0' cellpadding='0' width='649' style='width: 100%; border-collapse: collapse; border: medium none'>
										<thead>
											<tr>
							";
							$largura_col = intval(100/count($colunas));
							for($col=0;$col<count($colunas);$col++){
								$html .= "
												<th style='".$largura_col."% !important' class='header_json'>
													<span class='header_json_pt'>
														".strtoupper($colunas[$col]->titulo_en)."</span><br>";
								$html .= "
													<span class='header_json_lg'>
														";
												$html .= "
													</span>
												</th>
								";
							}

							$html .= "
											</tr>
										</thead>
										<tbody>
							";

										$valor = trim(formatar_texto_bd($row_campo['valor']));
										//echo $valor . "<br><br><br>";
										if($valor==""){
											for($row = 1;$row<=$linhas;$row++){
												if($row>100){ break; }
												$html .= "<tr>
												";
												for($col=0;$col<count($colunas);$col++){
													$html .= "<td style='".$largura_col."% !important' class='linha_json'>";
													if($colunas[$col]->tipo=="select"){
														$html .= "<table autosize='1' border='0' width='100%' cellspacing='0' cellpadding='0'><tr>";

														$largura_tabela_valores = intval(100/count($colunas[$col]->valores));
														for($col_valores=0;$col_valores<count($colunas[$col]->valores);$col_valores++){
															//$html .= "<td width='$largura_tabela_valores %'>";
															$html .= "<td class='coluna_valor'><img src='imagens/check.png' width='10'><br><b>".$colunas[$col]->valores[$col_valores]->titulo_en."</b><br><span style='font-size: 6pt'>(".$colunas[$col]->valores[$col_valores]->tituto_en.")</span></td>";
														}
														$html .= "</tr></table>";
													}else{
														$html .= "<span style='color: white'>______________________</span>";
													}
													if($colunas[$col]->formato!=""){
														$html .= "<span class='linha_json_legenda'><br><br>(".$colunas[$col]->formato.")</span>";
													}
													$html .= "</td>";
												}
												$html .="		  </tr>
														";
											}
										}else{
											//1;1=FERNANDA SOBRENOME_2=FARMACEUTICA|2;1=ROGERIO SOBRENOME3_2=PI|
											$jsonLinhas = explode("|", $valor);
											for($row=0;$row<count($jsonLinhas)-1;$row++){
												$html .= "<tr>";
												$jsonLinha = $jsonLinhas[$row];
												$jsonColunas = explode("§", $jsonLinha);
												for($col=0;$col<count($colunas);$col++){
													$html .= "<td style='".$largura_col."% !important' class='linha_json_preenchido'>";
													$jsonColuna = $jsonColunas[$col];
													$jsonColuna = explode("=", $jsonColuna);
													$jsonValor  = $jsonColuna[1];
													if($colunas[$col]->tipo=="select"){
														$html .= "<table autosize='1' border='0' width='100%' cellspacing='0' cellpadding='0'><tr>";

														$largura_tabela_valores = intval(100/count($colunas[$col]->valores));
														for($col_valores=0;$col_valores<count($colunas[$col]->valores);$col_valores++){
															$html .= "<td class='coluna_valor'>";
															if($colunas[$col]->valores[$col_valores]->titulo_en!=$jsonValor){
																$html .= "<img src='imagens/opc_unchecked.png' width='9'>";
															}else{
																$html .= "<img src='imagens/opc_checked.png' width='9'>";
															}
															$html .= "<br><b>".$colunas[$col]->valores[$col_valores]->titulo_en."</b></td>";
														}
														$html .= "</tr></table>";
													}else{
														$html .= "<span style='font-size: 8.0pt;'>".$jsonValor."</span>";
													}
													if($colunas[$col]->formato!=""){
														$html .= "<span class='linha_json_legenda'><br><br>(".$colunas[$col]->formato.")</span>";
													}


													$html .= "</td>";
												}
												$html .= "</tr>";
											}
										}



							$html .="	</tbody>
									</table>
							";

							$mpdf->WriteHTML($html);


						}
					}

				}
				//$mpdf->WriteHTML($html);
				//urldecode(formatar_texto_bd($row_campo['valor']))
				//formatar_texto_bd($row_campo['comentario'])
				if(trim($row_campo['comentario']!="")){	//Tem comentário
					$html = "
					<table autosize='1' class='MsoNormalTable'
							border='1' cellspacing='0'
							cellpadding='0'
							width='100%'
							style='width: 100.0%; border-collapse: collapse; border: medium none'>
						<tr>
							<td width='100%'>
								<span style='font-size:7.0pt;font-weight: italic;'>
									".traduzir('Comentário').": ".nl2br(formatar_texto_bd($row_campo['comentario']))."
								</span>
							</td>
						</tr>
					</table>
					";
					$mpdf->WriteHTML($html);
				}

				$html = '';
			}
		}



		$html .= "
			</div>

		";
		$mpdf->WriteHTML($html);

	}

		$query = "SELECT agenda.id, agenda.data, agenda.monitora, agenda.revisor, agenda.tipo_monitoria,
			monitor.nome as nome_monitor, monitor.assinatura as ass_monitor, monitor.tipo_usuario as tipo_monitor,
			revisor.nome as nome_revisor, revisor.assinatura as ass_revisor
			FROM agenda
				left join usu_usuario as monitor on agenda.monitora = monitor.id
				left join usu_usuario as revisor on agenda.revisor = revisor.id

				where agenda.id = " . $_GET['id'];
		$resAss = $conexao->pesquisa($query);
		$rowAss = mysqli_fetch_assoc($resAss);

		//$mpdf->AddPage();
		if(strtoupper($row_agenda['tipo_monitoria'])!="PROJETO S - ANALISE DO BANCO DE DADOS"){
		$html = '		<br>
						<table autosize="1" class="MsoNormalTable" border="0" cellspacing="0" cellpadding="0" width="100%" style="width: 100.0%; border-collapse: collapse; border: medium none" >
								<tr height="15"><td colspan="5" height="15"><br></td></tr>
								<tr>
									<td class="header_formulario" >
										<b>
										<span class="texto_formulario_nro">';
											$html .= $countForm+1 .'.
										</span>
										<span class="texto_formulario">
											'.traduzir('LISTA DE ACHADOS E PENDÊNCIAS');
											$html .= '
										</span>
										</b>
									</td>
								</tr>
						</table>
						<table autosize="1" width="100%" class="MsoNormalTable" border="1" cellspacing="0" cellpadding="0" style="border-collapse: collapse; border: medium none">
							<tr>
								<td width="110" valign="top" style="border-left: 1.0pt solid black; border-right: 1.0pt solid black; border-top: 1.0pt solid black; border-bottom: 1.0pt solid black; padding-left: 5.4pt; 	padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm">
									<p class="MsoNormal" align="center" style="text-align:center"><b>
									<span lang="EN-GB" style="font-size:11.0pt;  font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">'.traduzir('Paciente');
									$html .= '
									</span></b>
								</td>
								<td width="120" valign="top" style="border: 1.0pt solid black; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm">
									<p class="MsoNormal" align="left" style="text-align:center"><b>
									<span lang="EN-GB" style="font-size:11.0pt;  font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">'.traduzir('Tipo');
									$html .= '
									</span></b>
								</td>
								<td width="40%" valign="top" style="border-left: medium none; border-right: 1.0pt solid black; border-top: 1.0pt solid black; border-bottom: 1.0pt solid black; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm">
									<p class="MsoNormal" align="center" style="text-align:center"><b>
									<span lang="EN-GB" style="font-size:11.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">'.traduzir('Descrição');
									$html .= '
									</span></b>
								</td>
								<td width="40%" valign="top" style="border-left: medium none; border-right: 1.0pt solid black; border-top: 1.0pt solid black; border-bottom: 1.0pt solid black; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm">
									<p class="MsoNormal" align="center" style="text-align:center"><b>
									<span lang="EN-GB" style="font-size:11.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">'.traduzir('Ação');
									$html .= '
									</span></b>
								</td>
								<td width="100" valign="top" style="border-left: medium none; border-right: 1.0pt solid black; border-top: 1.0pt solid black; border-bottom: 1.0pt solid black; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm">
									<p class="MsoNormal" align="center" style="text-align:center"><b>
									<span lang="EN-GB" style="font-size:11.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">'.traduzir('Status');
									$html .= '
									</span></b>
								</td>
							</tr>
							';

		if($_SESSION['projeto']=="7"){
			$query_t = "select * from agenda_lista_pendencia where visita = ".$row_agenda['id']." and centro = ".$row_agenda['centro']." and status = 'PENDENTE' order by id";
		}else{
			if($row_agenda['tipo_monitoria']!="FINAL"){
				$dataVis = $row_agenda['data'];
				$dataVis = substr($dataVis, 6,2) . "/" . substr($dataVis, 4,2) . "/" .substr($dataVis, 0,4);
				$query_t = "select * from agenda_lista_pendencia
								where   visita = '".$row_agenda['nro_visita']."'
										and projeto = ".$_SESSION['projeto']."
											order by paciente";
			}else{
				$dataVis = $row_agenda['data'];
				$dataVis = substr($dataVis, 6,2) . "/" . substr($dataVis, 4,2) . "/" .substr($dataVis, 0,4);
				$query_t = "select * from agenda_lista_pendencia
								where  projeto = ".$_SESSION['projeto']."
									order by paciente";
			}
		}
		$mpdf->WriteHTML($html);

			$html = "";
							$res_p = $conexao->pesquisa($query_t);
							if(mysqli_num_rows($res_p)!=0){
								while($row_p = mysqli_fetch_assoc($res_p)){

									$html = '<tr>
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
												'.formatar_texto_bd(traduzir($row_p['tipo']));
												$html .= '
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
												'.formatar_texto_bd(traduzir($row_p['status']));
												$html .= '
												</span>
											</p>
										</td>
									</tr>';
								$mpdf->WriteHTML($html);
								}
							}else{
								$html = '<tr><td colspan=5> '.traduzir('Nenhuma pend�ncia encontrada');
								$html .= '.</td></tr>';
								$mpdf->WriteHTML($html);
							}
			$html 	= '
						</table>
				';
			$mpdf->WriteHTML($html);
			$countForm++;
		}
		$countForm++;
		//Assinatura!
		$html = "<br><br><br>
		<div align='center'>
			<table autosize='1' border='0' width='100%' cellspacing='0'>
				<tr>
					<td class='header_formulario' colspan='4' style='padding-left: 4pt; padding-top: 4pt'>
						<span class='texto_formulario'><b>".$countForm.". ".traduzir('ASSINATURAS')."</b>";
						$html .= "
					</td>
				</tr>
				<tr>
					<td class='header_formulario' colspan='4' style='padding-left: 4pt; padding-top: 4pt'>
						<span class='texto_header_assinatura'><b>".traduzir('ELABORADO POR')."</b>";
						$html .= "
					</td>
				</tr>
				<tr>
					<td style='border-left:1px solid' valign='bottom'><span class='texto_assinatura'>".formatar_texto_bd($varAgenda->monitor->nome)."</span></td>
					<td valign='bottom'><span class='texto_assinatura'><center><img class='assinatura_imagem' src='assinaturas/".$varAgenda->monitor->assinatura."'></center></span></td>
					<td valign='bottom'><span class='texto_assinatura'>".formatar_texto_bd(traduzir($varAgenda->monitor->funcao_nome))."</span></td>
					<td valign='bottom' style='border-right:1px solid;'><span class='texto_assinatura'><center>".$varAgenda->getDataInicialDATA()."</center></span></td>
				</tr>
				<tr>
					<td style='padding-bottom: 15.5pt; border-left:1px solid; border-top:1px solid'><span class='texto_header_assinatura'>Nome</span>";
						if($_SESSION['lang']=="i"){
							$html .=  "<br><span class='texto_en'>[" . traduzir('Nome') . "]</span>";
						}
						$html .= "
					</td>
					<td style='padding-bottom: 15.5pt; border-top:1px solid'><center><span class='texto_header_assinatura'>Assinatura</span>";
						if($_SESSION['lang']=="i"){
							$html .=  "<br><span class='texto_en'>[" . traduzir('Assinatura') . "]</span>";
						}
						$html .= "
					</td>
					<td style='padding-bottom: 15.5pt; border-top:1px solid'><span class='texto_header_assinatura'>Fun��o</span>";
						if($_SESSION['lang']=="i"){
							$html .=  "<br><span class='texto_en'>[" . traduzir('Fun��o') . "]</span>";
						}
						$html .= "
					</td>
					<td style='padding-bottom: 15.5pt; border-right:1px solid; border-top:1px solid' width='20%'><span class='texto_header_assinatura'><center>Data</center></span>";
						if($_SESSION['lang']=="i"){
							$html .=  "<span class='texto_en'>[" . traduzir('Data') . "]</span>";
						}
						$html .= "
					</td>
				</tr>

				<tr>
					<td class='header_formulario' colspan='4'>
						<span class='texto_header_assinatura'><b>REVISADO POR</b>";
						if($_SESSION['lang']=="i"){
							$html .=  "<br><span class='texto_en'>[" . traduzir('REVISADO POR') . "]</span>";
						}
						$html .= "
					</td>
				</tr>
				<tr>
					<td style='border-left:1px solid' valign='bottom'><span class='texto_assinatura'>".formatar_texto_bd($varAgenda->revisor->nome)."</span></td>
					<td valign='bottom'><span class='texto_assinatura'><center><img class='assinatura_imagem' src='assinaturas/".$varAgenda->revisor->assinatura."'></center></span></td>
					<td valign='bottom'><span class='texto_assinatura'>".formatar_texto_bd($varAgenda->revisor->funcao_nome)."</span></td>
					<td valign='bottom' style='border-right:1px solid;'><span class='texto_assinatura'><center>".$varAgenda->getDataInicialDATA()."</center></span></td>
				</tr>
				<tr>
					<td style='padding-bottom: 15.5pt; border-left:1px solid; border-top:1px solid'><span class='texto_header_assinatura'>Nome</span>";
						if($_SESSION['lang']=="i"){
							$html .=  "<br><span class='texto_en'>[" . traduzir('Nome') . "]</span>";
						}
						$html .= "
					</td>
					<td style='padding-bottom: 15.5pt; border-top:1px solid'><center><span class='texto_header_assinatura'>Assinatura</span>";
						if($_SESSION['lang']=="i"){
							$html .=  "<br><span class='texto_en'>[" . traduzir('Assinatura') . "]</span>";
						}
						$html .= "
					</td>
					<td style='padding-bottom: 15.5pt; border-top:1px solid'><span class='texto_header_assinatura'>Fun��o</span>";
						if($_SESSION['lang']=="i"){
							$html .=  "<br><span class='texto_en'>[" . traduzir('Fun��o') . "]</span>";
						}
						$html .= "
					</td>
					<td style='padding-bottom: 15.5pt; border-right:1px solid; border-top:1px solid' width='20%'><span class='texto_header_assinatura'><center>Data</center></span>";
						if($_SESSION['lang']=="i"){
							$html .=  "<span class='texto_en'>[" . traduzir('Data') . "]</span>";
						}
						$html .= "
					</td>
				</tr>

				<tr>
					<td class='header_formulario' colspan='4'>
						<span class='texto_header_assinatura'><b>APROVA��O PATROCINADOR</b>";
						if($_SESSION['lang']=="i"){
							$html .=  "<br><span class='texto_en'>[" . traduzir('APROVA��O PATROCINADOR') . "]</span>";
						}
						$html .= "
					</td>
				</tr>
				<tr>
					<td style='border-left:1px solid' valign='bottom'><span class='texto_assinatura'><br><br><br><br><br></span></td>
					<td valign='bottom'><span class='texto_assinatura'><center></center></span></td>
					<td valign='bottom'><span class='texto_assinatura'></span></td>
					<td valign='bottom' style='border-right:1px solid;'><span class='texto_assinatura'><center></center></span></td>
				</tr>
				<tr>
					<td style='padding-bottom: 15.5pt; border-left:1px solid; border-bottom:1px solid;border-top:1px solid'><span class='texto_header_assinatura'>Nome</span>";
						if($_SESSION['lang']=="i"){
							$html .=  "<br><span class='texto_en'>[" . traduzir('Nome') . "]</span>";
						}
						$html .= "
					</td>
					<td style='padding-bottom: 15.5pt; border-bottom:1px solid;border-top:1px solid'><center><span class='texto_header_assinatura'>Assinatura</span>";
						if($_SESSION['lang']=="i"){
							$html .=  "<br><span class='texto_en'>[" . traduzir('Assinatura') . "]</span>";
						}
						$html .= "
					</td>
					<td style='padding-bottom: 15.5pt; border-bottom:1px solid;border-top:1px solid'><span class='texto_header_assinatura'>Fun��o</span>";
						if($_SESSION['lang']=="i"){
							$html .=  "<br><span class='texto_en'>[" . traduzir('Fun��o') . "]</span>";
						}
						$html .= "
					</td>
						<td style='padding-bottom: 15.5pt; border-right:1px solid; border-bottom:1px solid;border-top:1px solid' width='20%'><span class='texto_header_assinatura'><center>Data</center></span>";
						if($_SESSION['lang']=="i"){
							$html .=  "<span class='texto_en'>[" . traduzir('Data') . "]</span>";
						}
						$html .= "
					</td>
				</tr>
			</table>
		</div>
		";
		$mpdf->WriteHTML($html);

	$mpdf->Output();
	$mpdf->charset_in='UTF-8';
?>