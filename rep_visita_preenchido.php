<?php
	error_reporting(E_ERROR);
    ini_set('display_errors', 1);
	include "definicoes.php";
	function deixa_bonito($entrada){
		$temp = strtoupper(substr($entrada, 0,1));
		$temp .= strtolower(substr($entrada, 1,strlen($entrada)));
		return $temp;
	}
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
	require_once __DIR__ . '/class/env.php';
	$servidor = env('DB_HOST', 'localhost');
	$conexao = new mysqli($servidor, env('DB_ROOT_USER', 'root'), env('DB_ROOT_PASS'), "pt_monitoria");


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

	$stylesheet = "
		.direita {
		  text-align: right;
		  margin-right: 1em;
		}

		.class_item{
			height: 22.85pt;
			border-left: 1.0pt solid black;
			border-right: 1.0pt solid black;
			border-top: 1.0pt solid black;
			border-bottom: 1.0pt solid black;
			padding-left: 3.5pt;
			padding-right: 3.5pt;
			padding-top: 1.5pt;
			padding-bottom: 3.5pt;
			text-align:left;
			//position:relative;
		}
		.class_item_coluna{
			height: 22.85pt;
			border-left: medium none;
			border-right: 1.0pt solid black;
			border-top: 1.0pt solid black;
			border-bottom: 1.0pt solid black;
			padding-left: 3.5pt;
			padding-right: 3.5pt;
			padding-top: 1.5pt;
			padding-bottom: 3.5pt;
			text-align:center;
			//position:relative;
		}


		.texto{
			font-size:8.0pt;
			font-weight: bold;
		}
		.texto_ordem{
			font-size:8.0pt;
			font-weight: bold;
		}
		.texto_en{
			font-size:6.0pt;
		}

		.rodape{
			font-size:7.0pt;
			font-weight: bold;
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
	";

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
								'margin-bottom' => 200
							]);

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
				<td width="151" rowspan="3" style="width: 113.3pt; border-left: 1.0pt solid windowtext; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.0pt solid windowtext; padding-left: 3.5pt; padding-right: 3.5pt; padding-top: 3.5pt; padding-bottom: 3.5pt; background: #D9D9D9">
					<span class="texto_caption">
						Visita de Rotina
					</span>
				</td>

				<td width="57" valign="top" style="width: 42.6pt; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.0pt solid windowtext; padding-left: 3.5pt; padding-right: 3.5pt; padding-top: 3.5pt; padding-bottom: 3.5pt; background: #D9D9D9">
					<span class="texto_caption">
						Data da monitoria
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
					<span class="texto_caption">
						Data do relatório
					</span>
				</td>
				<td width="548" colspan="4" valign="top"
					style="border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.0pt solid windowtext; padding-left: 3.5pt; padding-right: 3.5pt; padding-top: 3.5pt; padding-bottom: 3.5pt" class="justificado">
					<span class="texto_conteudo">
						<center>' . $_GET['data'].'</center>
					</span>
				</td>
			</tr>
			<tr>
				<td width="151" valign="top" style="width: 113.3pt; border-left: 1.0pt solid windowtext; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.0pt solid windowtext; padding-left: 3.5pt; padding-right: 3.5pt; padding-top: 3.5pt; padding-bottom: 3.5pt; background: #D9D9D9">
					<span class="texto_caption">
						Versão do relatório
					</span>
				</td>
				<td width="548" colspan="4" valign="top"
					style="border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.0pt solid windowtext; padding-left: 3.5pt; padding-right: 3.5pt; padding-top: 3.5pt; padding-bottom: 3.5pt" class="justificado">
					<span class="texto_conteudo">
						<center>Versão 1</center>
					</span>
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
				<td width="548" colspan="4" valign="top" style="width: 411.25pt; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.0pt solid windowtext;
				padding-left: 3.5pt; padding-right: 3.5pt; padding-top: 3.5pt; padding-bottom: 3.5pt">
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
						Coordenador do estudo no site
					</span>
				</td>
				<td width="548" colspan="4" style="width: 411.25pt; border-left: medium none; border-right: 1.0pt solid windowtext; border-top: medium none; border-bottom: 1.0pt solid windowtext; padding-left: 3.5pt; padding-right: 3.5pt; padding-top: 3.5pt; padding-bottom: 3.5pt">
					<span class="texto_conteudo">
					'.strtoupper(formatar_texto_bd($row_agenda['centro_coordenador'])).'
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










	//Começo loop nos formulários
	$query = "
		SELECT crf_agenda_forms.id, cfg_visitas.acao, crf_agenda_forms.agendamento, crf_agenda_forms.form_id,
			cfg_visitas.nome, cfg_visitas.texto_crf, cfg_visitas.texto_crf_en, cfg_visitas.ordem
			FROM crf_agenda_forms
				inner join cfg_visitas on crf_agenda_forms.form_id = cfg_visitas.id
				WHERE
					cfg_visitas.ordem <> '' and cfg_visitas.registro_valido = 1 and crf_agenda_forms.agendamento = " . $_GET['id'] . "
			order by cast(cfg_visitas.ordem as int)
	";

	$res_form = pesquisa($query);
	while($row_form = mysqli_fetch_assoc($res_form)){
		if($row_form['acao']=="PAGINA_BRANCO"){
			$html = "
				<table class='MsoNormalTable' border='0' cellspacing='0' cellpadding='0' width='100%' style='width: 100.0%; border-collapse: collapse; border: medium none' >
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
			$html = "
				<br><div>
					<table class='MsoNormalTable' border='0' cellspacing='0' cellpadding='0' width='100%' style='width: 100.0%; border-collapse: collapse; border: medium none' >
						<thead>
			";
			if($row_form['ordem']!="99999"){ //Pendencias dessa visita
				if(($row_form['acao']!="CABECALHO")&&($row_form['ordem']!="")){
					if($row_form['ordem']!="9999"){
						$html .= "
										<tr>
											<td width='23%' class='header_formulario' >
											<b>
											<span class='texto_formulario_nro'>";
													$html .= $row_form['ordem'].". ";
						$html .=			"</span>
											<span class='texto_formulario'>
												";
												$temmp = trim(mb_strtoupper(utf8_encode($row_form['texto_crf']." ")));
												if(substr(strtolower($temmp),0,6)=="farmac"){
													$temmp = "FARMACÊUTICOSX ";
												}
						$html .= 			$temmp . "
											</span>
											</b>
											<span class='texto_formulario_en'></span></td>
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

					$html .= 	$temmp . "</b>
										</span>
										<br><span class='cabecalho_2'>".str_primeira_letra_maiuscula(formatar_texto_bd($row_form['texto_crf_en']))."</span>
										</td>
									</tr>
					";
				}
			}
		}
		$html .= "
					</thead>
				</table>
		";
		$mpdf->WriteHTML($html);
		$html = "";


		//Pegar campo a campo
		$query = "SELECT cfg_visitas_campos.tipo, crf_agenda_campos.id, crf_agenda_campos.valor, cfg_visitas_campos.legenda,
				cfg_visitas_campos.numeracao, cfg_visitas_campos.nome, cfg_visitas_campos.texto, cfg_visitas_campos.texto_en, cfg_visitas_campos.ordem, cfg_visitas_campos.selecao_valores
				from crf_agenda_campos
					inner join cfg_visitas_campos on crf_agenda_campos.campo_id = cfg_visitas_campos.id
					where crf_agenda_campos.form_id = ".$row_form['id']."
							ORDER BY cfg_visitas_campos.ordem
		";
		$res_campo = pesquisa($query);
		while($row_campo = mysqli_fetch_assoc($res_campo)){
			if($row_campo['tipo']=="divisao"){
				$html .= "
					<table class='MsoNormalTable' border='1' cellspacing='0' cellpadding='0' width='100%' style='width: 100.0%; border-collapse: collapse; border: medium none'>
						<tr>
							<td width='23%' class='divisao'>
								<span class='texto_formulario_item'>";
				$html .=	$row_campo['ordem'].".&nbsp;&nbsp;";
				$temmp = trim(mb_strtoupper(utf8_encode($row_campo['texto'])));
				$html .= 	$temmp . "</span></b><i><span style='font-size: 8.0pt; line-height: 110%' class='texto_en'>
								</span></i></font><i><font face='Tahoma'>
								<span style='font-size: 5.0pt; line-height: 110%'>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									(".formatar_texto_bd(str_primeira_letra_maiuscula($row_campo['texto_en'])).")
								</span></font></i></p>
							</td>
						</tr>
					</table>
				";
			}else{
				if($row_campo['tipo']!="json"){
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
								$largura_1 = '80% !important';
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
									$largura2 = intval(20 / $qtd_col);
								}else{
									$largura2 = intval(20);
								}
							}else{
								$largura_1 = '20% !important';
								$largura_2 = '80% !important';
							}
						}
						$html .= "
									<table class='MsoNormalTable' border='1' cellspacing='0' cellpadding='0' width='100%' style='width: 100.0%; border-collapse: collapse; border: medium none'>
										<tr>
											<td width='".$largura_1."' class='class_item'  valign='top'>
												<span class='texto_ordem'>";
						if($row_campo['ordem']!=0){$html .=	$row_campo['ordem'].".";}
						//Linha comentada aparece o campo em ingles tb
						//$html .=	"						&nbsp;&nbsp;</span><span class='texto'>".formatar_texto_bd($row_campo['texto'])."</span><br><i><span class='texto_en'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(".formatar_texto_bd($row_campo['texto_en']).")</i></span>
						$html .=	"						&nbsp;&nbsp;</span><span class='texto'>".formatar_texto_bd($row_campo['texto'])."</span></span>

											</td>

											";

											if(($row_campo['tipo']=="texto")||($row_campo['tipo']=="numero")||($row_campo['tipo']=="data")||($row_campo['tipo']=="calculo")){

												$html .= "<td width='".$largura_2."' class='texto_conteudo'><span class='texto_conteudo' style='font-size: 8pt'><center>".formatar_texto_bd($row_campo['valor'])."</span>";
												if($row_campo['legenda']!=""){
													$html .= "<br><span style='font-size: 8px'>".$row_campo['legenda']."</span>";
												}
												$html .= "</center>";
											}
											if(($row_campo['tipo']=="caixa")){
												$html .= "<td width='".$largura_2."' style='padding-left: 3.5pt; padding-right: 3.5pt; padding-top: 3.5pt; padding-bottom: 3.5pt'><span class='texto_conteudo' style='font-size: 8pt'>".formatar_texto_bd($row_campo['valor'])."</span><br>";
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

												if($row_campo['legenda']!=""){
													$html .= $tamanho."<td width='".$largura_2."' class='class_item_coluna'><span valign='middle' class='texto'><center>".formatar_texto_bd($row_campo['legenda'])."</center>";
													$html .= "</span></td>";
													$temp="";
												}

												for($z=0;$z<strlen($opcoes);$z++){
												   if(substr($opcoes,$z,1)!="%"){$temp.=substr($opcoes,$z,1);}
												   else{
														$temp=trim($temp);
														$temp_selected = "";
														if(trim($rowItems['valor'])==$temp){ $temp_selected = " selected "; }
														$html .= $tamanho."<td width='".$largura_2."' class='class_item_coluna'><p><img src='imagens/check.png' width='13'><br><span valign='middle' class='texto'><b>".formatar_texto_bd($temp)."</b>";
														$temp_trad = traduzir(formatar_texto_bd($temp));
														if($temp_trad!=$temp){
															//$html .= "<br><span style='font-size:6pt;'>(".trim($temp_trad).")";
															//$html .= "<br><span style='font-size:6pt;'>(".trim($temp_trad).")";
														}
														$html .= "</span></p></td>";
														$temp="";
												   }
												}
												if(strlen($temp)>0){
														$temp=trim($temp);
														$temp_selected = "";
														if(trim($rowItems['valor'])==$temp){ $temp_selected = " selected "; }
														$html .= $tamanho."<td width='".$largura_2."' class='class_item_coluna' valign='middle'><p valign='middle'><img src='imagens/check.png' width='13'><br><span valign='middle' class='texto'><b>".formatar_texto_bd($temp)."</b>";
														$temp_trad = traduzir(formatar_texto_bd($temp));
														if($temp_trad!=$temp){
															$html .= "<br><span style='font-size:6pt;'>(".trim($temp_trad).")</span>";
														}
														$html .= "</span></p></td>";
														$temp="";
												}


											}




						$html .="			</td>
										</tr>
									</table>


						";
					}
				}else{
					$json = formatar_texto_bd($row_campo['selecao_valores']);
					$json = str_replace("<br />","",$json);
					$obj = json_decode($json);

					$linhas = $obj->linhas;
					$colunas = $obj->colunas;

					$html .= "
							<table border='1' cellspacing='0' cellpadding='0' width='649' style='width: 100%; border-collapse: collapse; border: medium none'>
								<thead>
									<tr>
					";
					$largura_col = intval(100/count($colunas));
					for($col=0;$col<count($colunas);$col++){
						$html .= "
										<th style='".$largura_col."% !important' class='header_json'>
											<span class='header_json_pt'>
												".$colunas[$col]->titulo_br."
											</span><br>
											<span class='header_json_en'>
												(".$colunas[$col]->tituto_en.")
											</span><br>
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
								for($row = 1;$row<=$linhas;$row++){
									if($row>100){ break; }
									$html .= "<tr>
									";
									for($col=0;$col<count($colunas);$col++){
										$html .= "<td style='".$largura_col."% !important' class='linha_json'>";
										if($colunas[$col]->tipo=="select"){
											$html .= "<table border='0' width='100%' cellspacing='0' cellpadding='0'><tr>";

											$largura_tabela_valores = intval(100/count($colunas[$col]->valores));
											for($col_valores=0;$col_valores<count($colunas[$col]->valores);$col_valores++){
												$html .= "<td class='coluna_valor'><img src='imagens/check.png' width='10'><br><b>".$colunas[$col]->valores[$col_valores]->titulo_br."</b><br><span style='font-size: 6pt'>(".$colunas[$col]->valores[$col_valores]->tituto_en.")</span></td>";
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
					$html .="	</tbody>
							</table>
					";

				}

			}
		}



		$html .= "
			</div>

		";
		$mpdf->WriteHTML($html);

	}

		$dataDaVisita = substr($row_agenda['data'],6,2) . '/' . substr($row_agenda['data'],4,2) . '/' .substr($row_agenda['data'],0,4);
		$html = '<br>
				<p class="conteudo_normal" style="text-align: justify; ; background: white">
					<b>Lista de Pendências</b>
				</p>

				<div align="center">
					<table width="100%" class="MsoNormalTable" border="1" cellspacing="0" cellpadding="0" style="border-collapse: collapse; border: medium none">
						<tr>
							<td colspan="7" valign="top" style="border: 1.0pt solid black; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm">
							<p class="paragrafo" align="center" style="text-align:center"><b>
							<span lang="EN-GB" style="font-size:12.0pt;
				  font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Lista de Pendências da Visita '.str_pad($row_agenda['nro_visita'],2, "00", STR_PAD_LEFT).' - [ '.str_pad(substr($row_agenda['data'],6,2) . '/' . substr($row_agenda['data'],4,2) . '/' .substr($row_agenda['data'],0,4), 50, " ", STR_PAD_RIGHT).']</span></b></td>
						</tr>

						<tr>
							<td width="110" valign="top" style="border-left: 1.0pt solid black; border-right: 1.0pt solid black;
								border-top: 1.0pt solid black; border-bottom: 1.0pt solid black; padding-left: 5.4pt;
								padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm">
							<p class="MsoNormal" align="center" style="text-align:center"><b>
							<span lang="EN-GB" style="font-size:11.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;"><center>Data<br>de abertura</center></span></b></td>

							<td width="110" valign="top" style="border-left: 1.0pt solid black; border-right: 1.0pt solid black;
								border-top: 1.0pt solid black; border-bottom: 1.0pt solid black; padding-left: 5.4pt;
								padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm">
							<p class="MsoNormal" align="center" style="text-align:center"><b>
							<span lang="EN-GB" style="font-size:11.0pt;
				  font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Participante</span></b></td>
							<td width="140" valign="top" style="border-left: 1.0pt solid black; border-right: 1.0pt solid black;
								border-top: 1.0pt solid black; border-bottom: 1.0pt solid black; padding-left: 5.4pt;
								padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm">
							<p class="MsoNormal" align="center" style="text-align:center"><b>
							<span lang="EN-GB" style="font-size:11.0pt;
				  font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">CPF</span></b></td>

							<td width="120" valign="top" style="border: 1.0pt solid black; padding-left: 5.4pt;
							padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm">
							<p class="MsoNormal" align="left" style="text-align:center"><b>
							<span lang="EN-GB" style="font-size:11.0pt;
				  font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Tipo</span></b></td>



							<td width="40%" valign="top" style="border-left: medium none; border-right: 1.0pt solid black; border-top: 1.0pt solid black; border-bottom: 1.0pt solid black; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm">
							<p class="MsoNormal" align="center" style="text-align:center"><b>
							<span lang="EN-GB" style="font-size:11.0pt;
				  font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Descrição</span></b></td>

							<td width="200" valign="top" style="border-left: medium none; border-right: 1.0pt solid black; border-top: 1.0pt solid black; border-bottom: 1.0pt solid black; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm">
							<p class="MsoNormal" align="center" style="text-align:center"><b>
							<span lang="EN-GB" style="font-size:11.0pt;
				  font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Responsável</span></b></td>

							<td width="100" valign="top" style="border-left: medium none; border-right: 1.0pt solid black; border-top: 1.0pt solid black; border-bottom: 1.0pt solid black; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm">
							<p class="MsoNormal" align="center" style="text-align:center"><b>
							<span lang="EN-GB" style="font-size:11.0pt;
				  font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Status</span></b></td>
						</tr>
		';
				$query_t = "select * from agenda_lista_pendencia where ( (visita = '".$dataDaVisita."') or (data_fechamento = '".$dataDaVisita."') ) and centro = '".$row_agenda['centro_nome']."' order by cast(agenda_lista_pendencia.paciente as int), id";
				//echo $query_t;
				$res_p = pesquisa($query_t);
				if(mysqli_num_rows($res_p)>0){
					while($row_p = mysqli_fetch_assoc($res_p)){

						$html .= '<tr>
							<td style="border-left: medium none; border-right: 1.0pt solid black;  border-left: 1.0pt solid black;border-top: medium none; border-bottom: 1.0pt solid black; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm; background: white">
								<p class="MsoNormal" align="center" style="text-align:center">
									<span lang="EN-GB" style="font-size: 8.5pt; font-family: Verdana,sans-serif; ">
									<center>';
										$paciente = $row_p['paciente'];
										if($paciente==""){$paciente = "N/A";}
										$html .= $paciente.'</center>
									</span>
								</p>
							</td>
							<td style="border-left: 1.0pt solid black; border-right: 1.0pt solid black; border-top: medium none; border-bottom: 1.0pt solid black; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm; background: white">
								<p class="MsoNormal" align="center" style="text-align:center">
									<span lang="EN-GB" style="font-size: 8.5pt; font-family: Verdana,sans-serif; ">
									'.deixa_bonito(formatar_texto_bd($row_p['data_abertura'])).'
									</span>
								</p>
							</td>
							<td style="border-left: 1.0pt solid black; border-right: 1.0pt solid black; border-top: medium none; border-bottom: 1.0pt solid black; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm; background: white">
								<p class="MsoNormal" align="center" style="text-align:center">
									<span lang="EN-GB" style="font-size: 8.5pt; font-family: Verdana,sans-serif; ">
									'.deixa_bonito(formatar_texto_bd($row_p['cpf'])).'
									</span>
								</p>
							</td>
							<td style="border-left: 1.0pt solid black; border-right: 1.0pt solid black; border-top: medium none; border-bottom: 1.0pt solid black; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm; background: white">
								<p class="MsoNormal" align="center" style="text-align:center">
									<span lang="EN-GB" style="font-size: 8.5pt; font-family: Verdana,sans-serif; ">
									';
						if(formatar_texto_bd($row_p['tipo'])=="ACHADO"){$html .= " Achado ";}
						if(formatar_texto_bd($row_p['tipo'])=="DESVIO DE GCP"){$html .= " Desvio de GCP ";}
						if(formatar_texto_bd($row_p['tipo'])=="DESVIO"){$html .= " Desvio de protocolo ";}

						$html .= '
									</span>
								</p>
							</td>
							<td style="border-left: 1.0pt solid black; border-right: 1.0pt solid black; border-top: medium none; border-bottom: 1.0pt solid black; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm; background: white">
								<p class="MsoNormal" align="center" style="text-align:center">
									<span lang="EN-GB" style="font-size: 8.5pt; font-family: Verdana,sans-serif; ">
									'.formatar_texto_bd(nl2br($row_p['achado'])).'
									</span>
								</p>
							</td>
							<td valign="center" style="border-left: medium none; border-right: 1.0pt solid black; border-top: medium none; border-bottom: 1.0pt solid black; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm; background: white">
								<p class="MsoNormal" style="text-align:justify;">
									<span style="font-size: 8.5pt;  font-family: Verdana,sans-serif;">
									'.formatar_texto_bd($row_p['responsavel']).'
									</span>
								</p>
							</td>
							<td valign="center" style="border-left: medium none; border-right: 1.0pt solid black; border-top: medium none; border-bottom: 1.0pt solid black; padding-left: 5.4pt; padding-right: 5.4pt; padding-top: 0cm; padding-bottom: 0cm; background: white">
								<p class="MsoNormal" style="text-align:justify;">
									<span style="font-size: 8.5pt;  font-family: Verdana,sans-serif;">
									<center>'.deixa_bonito($row_p['status']);
							/*if(strtoupper($row_p['status'])=="RESOLVIDO"){
								$html .= "<br>[".$row_p['data_fechamento']."]";
							}*/
							$html .= '		</center>
									</span>
								</p>
							</td>
						</tr>';
					}
				}else{
					$html .= '<tr><td colspan="7"> Nenhuma pendência encontrada.</td></tr>';
				}
				$html .= '
			</table>
		</div>
		';
		$mpdf->WriteHTML($html);




		$query = "SELECT agenda.id, agenda.data, agenda.monitora, agenda.revisor,
			monitor.nome as nome_monitor, monitor.assinatura as ass_monitor, monitor.tipo_usuario as tipo_monitor,
			revisor.nome as nome_revisor, revisor.assinatura as ass_revisor
			FROM agenda
				left join usu_usuario as monitor on agenda.monitora = monitor.id
				left join usu_usuario as revisor on agenda.revisor = revisor.id

				where agenda.id = " . $_GET['id'];
		$resAss = pesquisa($query);
		$rowAss = mysqli_fetch_assoc($resAss);
		$html = '<br>
				<br><br>
				<table border="0" width="100%" cellspacing="0" cellpadding="0">
					<tr>
						<td width="95"><b><font size="3">Elaborado por</font></b><br>
						<i><font size="1"></font></i></td>
						<td width="369">&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td width="272">&nbsp;</td>
					</tr>
					<tr>
						<td width="95">&nbsp;</td>
						<td width="369">&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td width="272">&nbsp;</td>
					</tr>
					<tr>
						<td width="95" align="left"><b><font size="2">Nome</font></b><br>
						<i><font size="1"></font></i></td>
						<td width="369"><u>'.strtoupper(formatar_texto_bd($rowAss['nome_monitor'])).'</u></td>
						<td>&nbsp;</td>
						<td width="124" align="left"><font size="2"><b>Função</b></font><br>
						<i><font size="1">(Study role)</font></i></td>
						<td width="272"><u>';
						//if($rowAss['tipo_monitor']=="2"){
							$html.= "MONITOR(a) DE PESQUISA</u>";
						//}
		$html .= '</td>
					</tr>
					<tr>
						<td width="95" align="left"><b><font size="2">Assinatura</font></b><br>
						<i><font size="1"></font></i></td>
						<td width="369">';
					if($rowAss['ass_monitor']!=""){
						$html .= '<img src="assinaturas/'.$rowAss['ass_monitor'].'" width="120">';
					}
		$html .= '</td>
						<td>&nbsp;</td>
						<td width="124" align="left"><font size="2"><b>Data</b></font><br>
						<i><font size="1"></font></i></td>
						<td width="272"><u>
						<p align="center">'.$_GET['data'].'</u><br>
						<i><font size="1">(dd/mm/aaaa)</font></i></td>
					</tr>


					<tr>
						<td width="95">&nbsp;</td>
						<td width="369">&nbsp;</td>
						<td>&nbsp;</td>
						<td align="left">&nbsp;</td>
						<td width="272">&nbsp;</td>
					</tr>
					<tr>
						<td width="95">&nbsp;</td>
						<td width="369">&nbsp;</td>
						<td>&nbsp;</td>
						<td align="left">&nbsp;</td>
						<td width="272">&nbsp;</td>
					</tr>
					<tr>
						<td width="95"><font size="2"><b>Revisado</b></font><b><font size="2">
						por</font></b><br>
						<i><font size="1"></font></i></td>
						<td width="369">&nbsp;</td>
						<td>&nbsp;</td>
						<td align="left">&nbsp;</td>
						<td width="272">&nbsp;</td>
					</tr>
					<tr>
						<td width="95">&nbsp;</td>
						<td width="369">&nbsp;</td>
						<td>&nbsp;</td>
						<td align="left">&nbsp;</td>
						<td width="272">&nbsp;</td>
					</tr>
					<tr>
						<td width="95" align="left"><b><font size="2">Nome</font></b><br>
						<i><font size="1"></font></i></td>
						<td width="369"><u>'.strtoupper($rowAss['nome_revisor']).'</u></td>
						<td>&nbsp;</td>
						<td width="124" align="left"><font size="2"><b>Função</b></font><br>
						<i><font size="1"></font></i></td>
						<td width="272">';
						//if($rowAss['tipo_monitor']=="2"){
							$html.= "MONITOR(a) DE PESQUISA</u>";
						//}
					$html .= '	</td>
					</tr>
					<tr>
						<td width="95" align="left"><b><font size="2">Assinatura</font></b><br>
						<i><font size="1"></font></i></td>
						<td width="369">';
						if($rowAss['ass_revisor']!=""){
						$html .= '<img src="assinaturas/'.$rowAss['ass_revisor'].'" width="120">';
					}
					$html .= '</td>
						<td>&nbsp;</td>
						<td width="124" align="left"><font size="2"><b>Data</b></font><br>
						<i><font size="1"></font></i></td>
						<td width="272"><u>
						<p align="center">'.$_GET['data'].'</u><br>
						<i><font size="1">(dd/mm/aaaa)</font></i></center></td>
					</tr>
					<tr>
						<td width="95">&nbsp;</td>
						<td width="369">&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td width="272">&nbsp;</td>
					</tr>
				</table>


		';
		$mpdf->WriteHTML($html);









	$mpdf->Output();
	$mpdf->charset_in='UTF-8';
?>