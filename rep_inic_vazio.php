<?
	error_reporting(E_ERROR);
    ini_set('display_errors', 1);

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
	$conexao = new mysqli($servidor, "root", "senha123", "synova_spc_teste_pena");


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
			font-size:6.0pt;
			font-weight: bold;
		}
		.texto_ordem{
			font-size:6pt;
			font-weight: bold;
		}

		.rodape{
			font-size:7.0pt;
			font-weight: bold;
		}

		.texto_en{
			font-size:5.0pt;
		}
		.texto_formulario{
			font-size: 6.5pt
		}
		.texto_formulario_nro{
			font-size: 8.0pt
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

	";

	$query = "select  ";
	$query .= " agenda.*,
			cfg_centro.numero as centro_numero,
			cfg_centro.nome as centro_nome,
			cfg_centro.investigador  as centro_investigador,
			cfg_centro.coordenador as centro_coordenador
	";
	$query .= "     from agenda  ";
	$query .= "     	inner join cfg_centro on agenda.centro = cfg_centro.id ";
	$query .= "     where agenda.id = " . $_GET['id'];
	$res_agenda = pesquisa($query);

	$row_agenda = mysqli_fetch_assoc($res_agenda);

	$query 			= "select * from proj_projeto";
	$res_projeto	= pesquisa($query);
	$row_projeto    = mysqli_fetch_assoc($res_projeto);




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
	$cabecalho = '
		<table>
		  <tr>
			<td>
				<img src="modelos/monitoria/header_mon_relatorio.png" style="margin-left: -70px;  margin-right: -70px;">
			</td>
		 </tr>
		</table>
	';

	$rodape = '
		<table width="100%">
		  <tr>
			<td width="20%">
				<br><br><br><span class="paginacao">{PAGENO}/{nbpg}</font>
			</td>
			<td width="60%">
				<br><br><br><span class="paginacao">Vigência (Effective): 06-Mai-2019 | Origem (Source): SN-SOP-OP-007</font>
			</td>
			<td width="20%" align="right">
				<img src="modelos/qualificacao/carta_footer.png" style="margin-bottom: -60px; margin-right: -65px">
			</td>

		 </tr>
		</table>
	';

	$mpdf->SetHTMLFooter($rodape);
	$mpdf->SetHTMLHeader($cabecalho);

	$html = '
			<div align="center">
			   <table class="MsoNormalTable" border="1" cellspacing="0" cellpadding="0" width="100%" style="width: 100.0%; border-collapse: collapse; border: medium none">
				  <thead>
					 <tr>
						<td width="18%" style="width: 18.98%; border: 1.0pt solid black; padding-left: 3.5pt; padding-right: 3.5pt; padding-top: 0cm; padding-bottom: 0cm; background: #D9D9D9">
						   <p class="MsoNormal" align="center" style="margin-bottom:0cm;margin-bottom:.0001pt;
							  text-align:center;line-height:normal">
						    <center>
							  <font face="Arial">&nbsp;<b><span style="font-size: 8.0pt">Identificação
							  do estudo</span></b><i><span style="font-size: 8.0pt; line-height: 110%"><br>
							  (Study Identification)</span></i></font></p>
							</center>
						</td>

						<td width="24%" style="width: 24.38%; border-left: medium none; border-right: 1.0pt solid black; border-top: 1.0pt solid black; border-bottom: 1.0pt solid black; padding-left: 3.5pt; padding-right: 3.5pt; padding-top: 0cm; padding-bottom: 0cm; background: #D9D9D9">
						   <p class="MsoNormal" align="center" style="margin-bottom:0cm;margin-bottom:.0001pt;
							  text-align:center;line-height:normal">
							  <center>
							  <font face="Arial"><b><span style="font-size: 8.0pt">
							  Centro de Pesquisa <br>
							  (Nome)</span></b><i><span style="font-size: 8.0pt; line-height: 110%"><br>
							  (Site - Name and number)</span></i></font>
							  </centeR>
						   </p>
						</td>
						<td width="23%" style="width: 23.06%; border-left: medium none; border-right: 1.0pt solid black; border-top: 1.0pt solid black; border-bottom: 1.0pt solid black; padding-left: 3.5pt; padding-right: 3.5pt; padding-top: 0cm; padding-bottom: 0cm; background: #D9D9D9">
						   <p class="MsoNormal" align="center" style="margin-bottom:0cm;margin-bottom:.0001pt;
							  text-align:center;line-height:normal">
							  <center><font face="Arial"><b><span style="font-size: 8.0pt">
							  Investigador(a) Principal</span></b><i><span style="font-size: 8.0pt; line-height: 110%"><br>
							  (Principal Investigator)</span></i></font>
							  </center>
						   </p>
						</td>
						<td width="16%" style="width: 16.78%; border-left: medium none; border-right: 1.0pt solid black; border-top: 1.0pt solid black; border-bottom: 1.0pt solid black; padding-left: 3.5pt; padding-right: 3.5pt; padding-top: 0cm; padding-bottom: 0cm; background: #D9D9D9">
						   <p class="MsoNormal" align="center" style="text-align:center">
							  <font face="Arial">
							  <span style="font-size: 8.0pt; line-height: 110%; font-weight:bold">
							  <center>
							  Patrocinador</span></b><i><span style="font-size: 8.0pt; line-height: 110%"><br>
							  (Sponsor)</span></i></font>
							  </center>
						   </p>
						</td>
						<td width="16%" style="width: 16.78%; border-left: medium none; border-right: 1.0pt solid black; border-top: 1.0pt solid black; border-bottom: 1.0pt solid black; padding-left: 3.5pt; padding-right: 3.5pt; padding-top: 0cm; padding-bottom: 0cm; background: #D9D9D9">

						    <p class="MsoNormal" align="center" style="text-align:center">
							<center>
							  <font face="Arial">
							  <b><span style="font-size: 8.0pt; line-height: 110%">Data da
							  visita</span></b><i><span style="font-size: 8.0pt; line-height: 110%"><br>
							  (Date)</span></i></font>
							</center>
						   </p>
						</td>
					 </tr>
				  </thead>
				  <tr style="height: 22.85pt">
					 <td width="18%" valign="top" style="width: 18.98%; height: 22.85pt; border-left: 1.0pt solid black; border-right: 1.0pt solid black; border-top: medium none; border-bottom: 1.0pt solid black; padding-left: 3.5pt; padding-right: 3.5pt; padding-top: 0cm; padding-bottom: 0cm">
						<p class="Tabelacontedo">
						   <span style="font-size:8.0pt;line-height:110%"><center><br>
				';
				//$html .= formatar_texto_bd($row_projeto['numero'] . "<br>" . $row_projeto['nome']);
				$html .= '
						   </center></span>
					 </td>
					 <td width="24%" valign="middle" style="width: 24.38%; height: 22.85pt; border-left: medium none; border-right: 1.0pt solid black; border-top: medium none; border-bottom: 1.0pt solid black; padding-left: 3.5pt; padding-right: 3.5pt; padding-top: 0cm; padding-bottom: 0cm">
						<p class="Tabelacontedo">
						   <span style="font-size:8.0pt;line-height:110%">
				';

				//$html .= formatar_texto_bd($row_agenda['centro_numero'] . " - " . $row_agenda['centro_nome']);

				$html .= '
						   </span>
					 </td>
					 <td width="23%" valign="middle" style="width: 23.06%; height: 22.85pt; border-left: medium none; border-right: 1.0pt solid black; border-top: medium none; border-bottom: 1.0pt solid black; padding-left: 3.5pt; padding-right: 3.5pt; padding-top: 0cm; padding-bottom: 0cm">
						<p class="Tabelacontedo">
						   <span style="font-size:8.0pt;line-height:110%">
				';
				//$html .= formatar_texto_bd($row_agenda['centro_investigador']);
				$html .= '</span>
					 </td>
					 <td width="16%" valign="middle" style="width: 16.78%; height: 22.85pt; border-left: medium none; border-right: 1.0pt solid black; border-top: medium none; border-bottom: 1.0pt solid black; padding-left: 3.5pt; padding-right: 3.5pt; padding-top: 0cm; padding-bottom: 0cm">
						<p class="Tabelacontedo">
						   <span style="font-size:8.0pt;line-height:110%">
				';
				//$html .= formatar_texto_bd($row_projeto['patrocinador']);
				$html .= '		   </span>
					 </td>
					 <td width="16%" valign="middle" style="width: 16.78%; height: 22.85pt; border-left: medium none; border-right: 1.0pt solid black; border-top: medium none; border-bottom: 1.0pt solid black; padding-left: 3.5pt; padding-right: 3.5pt; padding-top: 0cm; padding-bottom: 0cm">
						<p class="Tabelacontedo">
						   <span style="font-size:8.0pt;line-height:110%"><center>
				';
				//$html .= formatar_texto_bd($row_agenda['data_caption']);
				$html .= '</center>
						   </span>
					 </td>
				  </tr>
			   </table>
			</div>
	';
	$mpdf->WriteHTML($html);
	//Começo loop nos formulários
	$query = "
		SELECT crf_agenda_forms.id, cfg_visitas.acao, crf_agenda_forms.agendamento, crf_agenda_forms.form_id,
			cfg_visitas.nome, cfg_visitas.texto_crf, cfg_visitas.texto_crf_en, cfg_visitas.ordem
			FROM crf_agenda_forms
				inner join cfg_visitas on crf_agenda_forms.form_id = cfg_visitas.id
				WHERE
					cfg_visitas.registro_valido = 1 and crf_agenda_forms.agendamento = " . $_GET['id'] . "
			order by cfg_visitas.ordem
	";
	$res_form = pesquisa($query);
	while($row_form = mysqli_fetch_assoc($res_form)){
		if($row_form['acao']=="PAGINA_BRANCO"){
			$html = "";
			/*$html = "
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
			";*/
			/*$html .= "
				PAGINA_BRNACO
			";
			$mpdf->WriteHTML($html);	*/

		}else{
			$html = "
				<br><div>
					<table class='MsoNormalTable' border='0' cellspacing='0' cellpadding='0' width='100%' style='width: 100.0%; border-collapse: collapse; border: medium none' >
						<thead>
			";
			if($row_form['acao']!="CABECALHO"){
				if($row_form['ordem']!="9999"){
					$html .= "
									<tr>
										<td width='23%' class='header_formulario' >
										<p class='MsoNormal' align='center' style='margin-bottom:0cm;margin-bottom:.0001pt; text-align:left;line-height:normal'><font face='Tahoma'><b>
										<span class='texto_formulario_nro'>";
											if($row_form['ordem']>=1){
												$html .= $row_form['ordem'].". ";
											}
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
										</font>
										<font face='Arial'><i><span style='font-size: 6.0pt; line-height: 110%'>
										(".str_primeira_letra_maiuscula(formatar_texto_bd($row_form['texto_crf_en'])).")
										</span></i></font></p></td>
									</tr>
					";
				}
			}else{
				$html .= "
								<tr>
									<td class='cabecalho'>
									<span class='cabecalho_1'>
										<b>";
										//formatar_texto_bd($row_form['texto_crf']).
										$temmp = trim(mb_strtoupper(utf8_encode($row_form['texto_crf'])));

				$html .= 	$temmp .	"</b>
									</span>
									<br><span class='cabecalho_2'>".str_primeira_letra_maiuscula(formatar_texto_bd($row_form['texto_crf_en']))."</span>
									</td>
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
								<p class='MsoNormal' align='center' style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:left;line-height:normal'><font face='Arial'><b>
								<span style='font-size: 6pt'>";
				if($row_form['ordem']>=1){$html .=	$row_form['ordem'].".".$row_campo['numeracao'].".&nbsp;&nbsp;";}
				$temmp = trim(mb_strtoupper(utf8_encode($row_campo['texto'])));
				$html .= 	$temmp . "</span></b><i><span style='font-size: 8.0pt; line-height: 110%' class='texto_en'>
								</span></i></font><i><font face='Tahoma'>
								<span style='font-size: 5.0pt; line-height: 110%'>
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
						//$mpdf->AddPage();
					}else{
						if($row_campo['tipo']!="select_coluna"){
							$largura_1 = '45% !important';
							$largura_2 = '55%';
						}else{
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
						}
						$html .= "
									<table class='MsoNormalTable' border='1' cellspacing='0' cellpadding='0' width='100%' style='width: 100.0%; border-collapse: collapse; border: medium none'>
										<tr>
											<td width='".$largura_1."' class='class_item'  valign='top'>
												<span class='texto_ordem'>";
						if(($row_form['ordem']>=1)&&($row_campo['numeracao']>=1)){$html .=	$row_form['ordem'].".".$row_campo['numeracao'].".";}
						$html .=	"						&nbsp;&nbsp;</span><span class='texto'>".formatar_texto_bd($row_campo['texto'])."</span><br><i><span class='texto_en'>&nbsp;&nbsp;(".formatar_texto_bd($row_campo['texto_en']).")</i></span>

											</td>

											";

											if(($row_campo['tipo']=="texto")||($row_campo['tipo']=="numero")||($row_campo['tipo']=="data")||($row_campo['tipo']=="calculo")){

												$html .= "<td width='".$largura_2."' class='class_item'><center>_____________________________________________________";
												if($row_campo['legenda']!=""){
													$html .= "<br><span style='font-size: 8px'>".$row_campo['legenda']."</span>";
												}
												$html .= "</center>";
											}
											if(($row_campo['tipo']=="caixa")){
												$html .= "<td width='".$largura_2."' class='class_item'><center>_____________________________________________________<br>";
												$html .= "_____________________________________________________<br></center>";
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
															$html .= "<br><span style='font-size:6pt;'>(".trim($temp_trad).")";
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
											//$html .= $colunas[$col]->formato;
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

		$html = '<br>
				<div align="center">
				   <table class="MsoNormalTable" border="1" cellspacing="0" cellpadding="0" width="100%" style="width: 100.0%; border-collapse: collapse; border: medium none">
					  <thead>
						 <tr>
							<td width="18%" style="width: 18.98%; border: 1.0pt solid black; padding-left: 3.5pt; padding-right: 3.5pt; padding-top: 0cm; padding-bottom: 0cm; background: #D9D9D9">
							   <p class="MsoNormal" align="left" style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:left;line-height:normal">
								  <font face="Arial">&nbsp;<b><span style="font-size: 8.0pt">COMENTÝRIOS GERAIS</span></b><i><span style="font-size: 8.0pt; line-height: 110%"><br>
								  (General Comments)</span></i></font></p>
							</td>
						 </tr>
					  </thead>
					  <tr style="height: 22.85pt">
						 <td width="18%" valign="top" style="width: 18.98%; height: 22.85pt; border-left: 1.0pt solid black; border-right: 1.0pt solid black; border-top: medium none; border-bottom: 1.0pt solid black; padding-left: 3.5pt; padding-right: 3.5pt; padding-top: 0cm; padding-bottom: 0cm">
							<br><br><br><br><br><br>
						 </td>
					  </tr>
					</table>
				</div>
				<br><br>



				<table border="0" width="100%" cellspacing="0" cellpadding="0">
					<tr>
						<td width="95"><b><font size="2">Elaborado por</font></b><br>
						<i><font size="1">(performed by)</font></i></td>
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
						<i><font size="1">(Name)</font></i></td>
						<td width="369">___________________________________</td>
						<td>&nbsp;</td>
						<td width="124" align="left"><font size="2"><b>Função</b></font><br>
						<i><font size="1">(Study role)</font></i></td>
						<td width="272">______________________________</td>
					</tr>
					<tr>
						<td width="95" align="left"><b><font size="2">Assinatura</font></b><br>
						<i><font size="1">(Signature)</font></i></td>
						<td width="369">___________________________________</td>
						<td>&nbsp;</td>
						<td width="124" align="left"><font size="2"><b>Data</b></font><br>
						<i><font size="1">(Date)</font></i></td>
						<td width="272"><center>
						<p align="center">___/____/_____<br>
						<i><font size="1">(dd/mm/aa)</font></i></center></td>
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
						<i><font size="1">(Reviwed by)</font></i></td>
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
						<i><font size="1">(Name)</font></i></td>
						<td width="369">___________________________________</td>
						<td>&nbsp;</td>
						<td width="124" align="left"><font size="2"><b>Função</b></font><br>
						<i><font size="1">(Study role)</font></i></td>
						<td width="272">______________________________</td>
					</tr>
					<tr>
						<td width="95" align="left"><b><font size="2">Assinatura</font></b><br>
						<i><font size="1">(Signature)</font></i></td>
						<td width="369">___________________________________</td>
						<td>&nbsp;</td>
						<td width="124" align="left"><font size="2"><b>Data</b></font><br>
						<i><font size="1">(Date)</font></i></td>
						<td width="272">
						<p align="center"><center>___/____/_____<br>
						<i><font size="1">(dd/mm/aa)</font></i></center></td>
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