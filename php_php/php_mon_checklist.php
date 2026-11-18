<?php
	if(!isset($_SESSION)){
		session_start();
	}
	include_once __DIR__."/../class/conexao.class.php";
	include_once __DIR__."/../class/checklist.class.php";
	include_once __DIR__."/../class/projeto.class.php";

	$varConexao = new Conexao();
	$varCheckList = new CheckList($_SESSION['projeto']);
	$varProjeto = new Projeto($_SESSION['projeto']);

	set_time_limit(0);
	ini_set('memory_limit', '4095M'); // 4 GBs minus 1 MB


	function montaFase($fase, $periodo, $linha, $participante){
		$amarelo    = "#e9ff00; ";
		$html       = "";
		$varConexao = new Conexao();
		$varProjeto = new Projeto($_SESSION['projeto']);
		//Pega as colunas de acordo com as fases
		$query = "select agenda_check.* from agenda_check
			inner join agenda_check_fase on agenda_check_fase.id = agenda_check.periodo
			where agenda_check.projeto = ".$_SESSION['projeto']."
					and agenda_check_fase.item = '".$fase."'
					order by agenda_check_fase.id, periodo, ordem";

		$resPer = $varConexao->pesquisa($query);
		$indiceFase = 0;
		while($rowPer = mysqli_fetch_assoc($resPer)){		//LOOP em todos os campos de cada participante do loop acima

			if($indiceFase == 0){ $indiceFase = $rowPer['periodo'];}else{
				if($indiceFase != $rowPer['periodo']){
					$html .= '<td class="fase_diferente">';
					$indiceFase = $rowPer['periodo'];
				}
			}

			$varCampo = new CheckListCampoParticipante();
			$varCampo->inicializaParticipante($participante, $rowPer['id']);

			if($varCampo->observacoes!=""){
				$corBotaoIndo = "green-jungle";
			}else{
				$corBotaoIndo = "blue";

			}
			//comeco
			$classFormControl = "form-control linha_" . $linha . " linha_" . $linha . "_campo_" . $varCampo->campoClass->id;
			if($varCampo->campoClass->calculo==1){
				$classFormControl .= " calculo ";
				$somenteLeitura = " disabled ";
			}else{
				$somenteLeitura = "";
			}

			if($varCampo->campoClass->mascara!=""){$mascara = "mascara(this,'".$varCampo->campoClass->mascara."');";}else{$mascara="";}

			//

			if($varCampo->campoClass->opcoes==""){ // Não é combobox e não é calculo

				$corFundo = "";

				$varValorMinimo = $varCampo->campoClass->valor_minimo;
				$varValorMaximo = $varCampo->campoClass->valor_maximo;

				if($varCampo->valor!=""){	//Tem algo preenchido.
					if($varValorMinimo!=""){
						if($varValorMinimo>$varCampo->valor){
							$corFundo = 'red; ';
						}
					}
					if($varValorMaximo!=""){
						if($varValorMaximo<$varCampo->valor){
							$corFundo = 'red; ';
						}
					}
				}

				if($varCampo->campoClass->id==2){
					$eIdade = "1";
					$idadeMinima = $varProjeto->idade_minima;
					$idadeMaxima = $varProjeto->idade_maxima;
					if($varCampo->valor!=""){
						if($varCampo->valor<$idadeMinima){
							$corFundo = 'red; ';
						}
						if($varCampo->valor>$idadeMaxima){
							$corFundo = 'red; ';
						}
					}
				}else{
					$eIdade = "0";
				}



				$html .= '<td style="font-size: 12px !important;">
							<div class="input-group">
								<span class="input-group-btn">
									<button class="btn '.$corBotaoIndo.' btn" type="button" id="btnInfo_'.$varCampo->id.'" onclick="javascript: exibeInfo('.$varCampo->id.');"><i class="fa fa-info-circle"></i></button>
								</span>
								<input type="text" placeholder=""
									class="'.$classFormControl.' linha_'.$linha.'"
									style="text-align: center; min-width:70px; ';
									if($varCampo->valor==""){
										$html .= ' background-color: '.$amarelo.' ';
									}else{
										$html .= ' background-color: '.$corFundo.' ';
									}
									$html .= '"
									name="linha_'.$linha.'_'.$varCampo->id.'"
									id="linha_'.$linha.'_'.$varCampo->id.'"
									antigo="'.$varCampo->valor.'";
									onKeyUp="'.$mascara.'"
									varCampoId="'.$varCampo->campoClass->id.'"
									varTemCalculo="'.$varCampo->campoClass->calculo.'"
									varTipoCalculo="'.$varCampo->campoClass->calculo_tipo.'"
									varCamposCalculo="'.$varCampo->campoClass->calculo_campos.'"
									varEIdade="'.$eIdade.'"
									varIdadeMinima="'.$varProjeto->idade_minima.'"
									varIdadeMaxima="'.$varProjeto->idade_maxima.'"
									varMinimo="'.$varCampo->campoClass->valor_minimo.'"
									varMaximo="'.$varCampo->campoClass->valor_maximo.'"
									value="'.$varCampo->valor.'"
									title="'.$varCampo->id.'"
									observacoes="'.htmlspecialchars($varCampo->observacoes, ENT_QUOTES, 'ISO-8859-1').'"
									'.$somenteLeitura.'
									onBlur="javascript: calculaCampos(this); return false;"
									onchange="javascript: salvaPartDire('.$linha.'); return false;"
									>
							</div>
						</td>';
			}else{
				$opcoes = $varCampo->campoClass->opcoes;
				$opcoes = explode(";", $opcoes);
				$html .= '<td style="font-size: 12px !important;">
							<div class="input-group">
							<span class="input-group-btn">
									<button class="btn '.$corBotaoIndo.' btn" type="button" id="btnInfo_'.$varCampo->id.'" onclick="javascript: exibeInfo('.$varCampo->id.');"><i class="fa fa-info-circle"></i></button>
							</span>

							<select class="'.$classFormControl.' linha_'.$linha.'"
								data-toggle="tooltip"
								'.$somenteLeitura.'
								title="'.$varCampo->id.'"
								observacoes="'.htmlspecialchars($varCampo->observacoes, ENT_QUOTES, 'ISO-8859-1').'"
								style="text-align: center; min-width:70px; ';
								if($varCampo->valor==""){
									$html .= ' background-color: '.$amarelo.' ';
								}else{
									if( ($varCampo->pago==0)&&($varCampo->campoClass->cobranca==1)&&($varCampo->valor=="Sim")){
										$html .= ' background-color: #ffebc7 ';
									}
								}
								$html .= '"
								name="linha_'.$linha.'_'.$varCampo->id.'"
								id="linha_'.$linha.'_'.$varCampo->id.'"
								antigo="'.$varCampo->valor.'";
								onKeyUp="'.$mascara.'"
								varCampoId="'.$varCampo->campoClass->id.'"
								varTemCalculo="'.$varCampo->campoClass->calculo.'"
								varTipoCalculo="'.$varCampo->campoClass->calculo_tipo.'"
								varCamposCalculo="'.$varCampo->campoClass->calculo_campos.'"
								onchange="javascript: salvaPartDire('.trim($linha).'); calculaCampos(this); return false;"
								>
								<option value=""></option>';
				for($iOpc=0;$iOpc<count($opcoes);$iOpc++){
					$html .= '<option value="'.$opcoes[$iOpc].'" ';
					if($opcoes[$iOpc]==$varCampo->valor){
						$html .= ' selected ';
					}
					$html .= '>'.$opcoes[$iOpc].'</option>';
				}
				$html .= '
							</select>
							</div>
						</td>';
			}//Fim
		}
		return $html;
	}


	switch(strtoupper($_GET['act'])){
		case "SALVA_INFO":
			$id          = $_POST['id'];
			$observacoes = $_POST['observacoes'];
			$query       = "update agenda_check_participante_campo set observacoes = '".formata_utf8_decode(addslashes($observacoes))."', log_usuario = '".$_SESSION['user_id']."' where id = $id";
			$varConexao->pesquisa($query);
			if(trim($observacoes)!=""){
				echo "1";
			}else{
				echo "0";
			}
		break;
		case "MUDA_VALOR":
			$campo = $_POST['varCampo'];
			$campo = explode('|', $campo);
			$id 	= $campo[0];
			$valor 	= $campo[1];
			$valorV6= $campo[2];
			$varCentro = $_GET['centro'];
			$idCampo = $_POST['varIdCampo'];

			//TEM QUE VIR O CENTRO
			//Verificar se existe o campo. Se nao, criar.
			//Se sim, atualizo
			$query 		= "select * from agenda_check_centro_valor where centro = '$varCentro' and campo = '$idCampo'";
			$resValor 	= $varConexao->pesquisa($query);
			echo $query;
			if(mysqli_num_rows($resValor)==0){
				$query = "insert into agenda_check_centro_valor (centro, campo) values ($varCentro, $idCampo);";
				echo $query;
				$varConexao->pesquisa($query);
			}

			$linha  = $_POST['varLinha'];
			$fase  = $_POST['varFase'];

			$query 		= "select * from agenda_check_centro_valor where centro = '$varCentro' and campo = '$idCampo'";
			$resValor 	= $varConexao->pesquisa($query);
			$rowValor   = mysqli_fetch_assoc($resValor);
			$query = "update agenda_check_centro_valor set valor = '$valor', valor6 = '$valorV6' where id = " . $rowValor['id'];
			$varConexao->pesquisa($query);
			$query = "update agenda_cobranca_itens set campo_id = '$id' where id = '$idCampo'";
			echo $query;
			$varConexao->pesquisa($query);
		break;

		case "SALVA_FOLLOW":
			$query = "update proj_projeto set txtfollowup = '".utf8_decode($_POST['texto'])."' where id = " . $_SESSION['projeto'];
			$varConexao->pesquisa($query);
		break;
		case "INSERE_GRUPO":
			$varPeriodo = $_POST['varPeriodo'];
			$varGrupo = $_POST['varGrupo'];
			$varIni = $_POST['varIni'];
			$varFim = $_POST['varFim'];

			if($varPeriodo==1){
				for($i=$varIni;$i<=$varFim;$i++){
					$query = "insert into agenda_check_participante (projeto, numero, grupo, periodo, log_usuario) values (
								'".$_SESSION['projeto']."',
								'$i',
								'$varGrupo',
								'$varPeriodo',
								'".$_SESSION['user_id']."'
							);";
					$varConexao->pesquisa($query);
					echo $query;
				}
			}else{
				for($i=$varIni;$i<=$varFim;$i++){
					//Tentar pegar o código da fase1
					$codigo = "";
					$query = "select codigo from agenda_check_participante where status <> 'D' and projeto = '".$_SESSION['projeto']."' and numero = " . $i;
					$resCod= $varConexao->pesquisa($query);
					if(mysqli_num_rows($resCod)>0){
						$rowCod = mysqli_fetch_assoc($resCod);
						$codigo = $rowCod['codigo'];
					}else{
						$codigo = "";
					}
					$query = "insert into agenda_check_participante (projeto, numero, codigo, grupo, periodo, log_usuario) values (
								'".$_SESSION['projeto']."',
								'$i',
								'$codigo',
								'$varGrupo',
								'$varPeriodo',
								'".$_SESSION['user_id']."'
							);";
					$varConexao->pesquisa($query);
					echo $query;
				}
			}



		break;
	}

	if($_GET['act']=="PESQUISAXXXXXX"){
		$projeto 	= $_SESSION['projeto'];
		$query 		= "select id from agenda_check_fase where projeto = " . $projeto;
		$resQtd		= $varConexao->pesquisa($query);
		if(mysqli_num_rows($resQtd)==0){$projetoEspecifico = false;}else{$projetoEspecifico=true;}


		$varFase         = $_POST['fase'];
		$varGrupo        = $_POST['grupo'];
		$varPeriodo      = $_POST['periodo'];
		$varParticipante = $_POST['participante'];
		$varCodigo       = $_POST['codigo'];
		$varSexo         = $_POST['sexo'];

		switch($_SESSION['projeto']){
			default:
				$head = '<thead>
					<tr>
						<th style="min-width: 150px !important" width="150">
							<!--<button type="button" class="btn btn-default" onclick="javascript:exportar(1);"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Excel com AT</button><br>
							<button type="button" class="btn btn-default" onclick="javascript:exportar(0);"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Excel (com filtro)</button>-->
							<center><button type="button" class="btn btn-default" onclick="javascript:exportar(999);"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Excel</button></center>

						</th>
						<th style="font-size: 12px !important;"><center>#<br>Participante</center></th>
						<th style="font-size: 12px !important;"><center>Código&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</center></th>
						<th style="font-size: 12px !important;"><center>Status&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</center></th>
						<th style="font-size: 12px !important;"><center>Grupo</center></th>
						<th style="font-size: 12px !important;"><center>Período</center></th>
				';
			break;
		}

		if($varCheckList->temEspecifico()==1){
			if($varFase=="TODOS"){
				$query = "select agenda_check.*
							from agenda_check
								inner join agenda_check_fase on agenda_check_fase.id = agenda_check.periodo
									where agenda_check.projeto = $projeto
											order by agenda_check_fase.id, periodo, ordem";
			}else{
				$query = "select agenda_check.*
							from agenda_check
								inner join agenda_check_fase on agenda_check_fase.id = agenda_check.periodo
									where agenda_check.projeto = $projeto
										and periodo = '".$varFase."'
											order by agenda_check_fase.id, periodo, ordem";
			}
		}else{
			if($varFase=="TODOS"){
				$query = "select * from agenda_check
								inner join agenda_check_fase on agenda_check_fase.id = agenda_check.periodo
								where agenda_check.projeto = 0
									order by agenda_check_fase.id, periodo, ordem";
			}else{
				$query = "select * from agenda_check
							inner join agenda_check_fase on agenda_check_fase.id = agenda_check.periodo
							where agenda_check.projeto = 0 and periodo = '".$varFase."'
								order by agenda_check_fase.id, periodo, ordem";
			}
		}
		$resPer = $varConexao->pesquisa($query);

		$indiceFase = 0;
		while($rowPer = mysqli_fetch_assoc($resPer)){
			if($indiceFase == 0){ $indiceFase = $rowPer['periodo'];}else{
				if($indiceFase != $rowPer['periodo']){
					$head .= '<td class="fase_diferente">';
					$indiceFase = $rowPer['periodo'];
				}
			}
			$head .= '
			<th style="font-size: 12px !important;"><center>'.$rowPer['nome'];
			if($rowPer['label']!=""){
				$head .= '<br><i><span style="font-size: 8pt;color: black">'.$rowPer['label'].'</span><i>';
			}
			if($rowPer['cobranca']==1){
				$head .= '<br><b><i><span style="font-size: 8pt;color: red">Existe cobrança para esse campo</span></b>';
			}
			$head .= '</center></th>';
		}
		$head .= '
					<th style="min-width: 100px !important" width="100"></th>
					</tr>
				</thead>
				<tbody>';
		$html = "";

		$query = "select * from agenda_check_participante
						where projeto = '".$_SESSION['projeto']."' ";
		if($varParticipante!=""){$query .= " and numero = '$varParticipante' ";}
		if($varGrupo!="TODOS"){
			if($varGrupo!=""){
				$query .= " and ( (grupo = '$varGrupo') ) ";
			}else{
				$query .= " and ( (grupo = '') ) ";
			}
		}
		$varStatus = $_POST['status'];
		if($varStatus!="TODOS"){
			if($varStatus!=""){
				$query .= " and ( (status = '$varStatus') ) ";
			}else{
				$query .= " and ( (status = '') ) ";
			}
		}
		if($varCodigo!=""){
			$query .= " and ( (codigo = '$varCodigo') ) ";
		}
		if(isset($_POST['centro'])){
			$varCentro = $_POST['centro'];
			if( ($varCentro!="TODOS")&&($varCentro!="")){
				$query .= " and centro = $varCentro";
			}
		}

		if($varPeriodo!="TODOS"){$query .= " and ( (periodo = '') or (periodo = '$varPeriodo') ) ";}

		$query .= " order by cast(numero as INTEGER) ";
		//********AQUI É UM LIMITE PARA NÃO FICAR DEMORADO */
		//$query .= " limit 2";


		$resPart = $varConexao->pesquisa($query);
		$linha = 1;
		while($rowPart = mysqli_fetch_assoc($resPart)){		//LOOP em todos os participantes
			$amarelo = "#e9ff00; ";

			//Verificando se tem filtro de sexo
			if($varSexo!=""){
				//Pear o sexo do participante
				$idParticipante = $rowPart['id'];
				if($varCheckList->temEspecifico()==1){
					$query = "SELECT id FROM `agenda_check` where nome = 'Sexo' and projeto = ".$varProjeto->id;
					$resSexo = $varConexao->pesquisa($query);
					$rowSexo = mysqli_fetch_assoc($resSexo);
					$varIdexo = $rowSexo['id'];

				}else{
					$query = "SELECT id FROM `agenda_check` where nome = 'Sexo' and projeto = 0";
					$resSexo = $varConexao->pesquisa($query);
					$rowSexo = mysqli_fetch_assoc($resSexo);
					$varIdexo = $rowSexo['id'];
				}
				$query = "SELECT valor FROM `agenda_check_participante_campo` where participante = ".$idParticipante." and campo = ".$varIdexo;
				$resValor = $varConexao->pesquisa($query);
				$rowValor = mysqli_fetch_assoc($resValor);
				$valorSexo = $rowValor['valor'];
				if($valorSexo!=$varSexo){
					continue;
				}
			}
			$html .= '	<tr>
							<td style="font-size: 12px !important;">
								<center>
									<button type="button" class="btn btn-default" onclick="javascript: deletaPart('.$rowPart['id'].')"><i class="fa fa-trash"></i></button>
									<button type="button" class="btn btn-default" onclick="javascript: salvaPart('.$linha.')"><i class="fa fa-floppy-o"></i></button>
									<button type="button" class="btn btn-default blue-madison" alt="Não aplicável" title="Não aplicável" onclick="javascript: naoAplicavel('.$linha.');">n/a</button>
								</center>
							</td>
							<td style="font-size: 12px !important;">
								<input type="hidden"
									id="linha_'.$linha.'_id"
									name="linha_'.$linha.'_id"
									value="'.$rowPart['id'].'"
									>
								<input type="text" placeholder=""
									class="form-control linha_'.$linha.'"
									style="text-align: center; min-width:70px;';if($rowPart['numero']==""){$html .= ' background-color: '.$amarelo.' ';}$html .= '"
									id="linha_'.$linha.'_numero"
									name="linha_'.$linha.'_numero"
									antigo="'.$rowPart['numero'].'";
									value="'.$rowPart['numero'].'"
									onchange="javascript: salvaPartDire('.$linha.'); return false;">
							</td>
							<td style="font-size: 12px !important;">
								<input type="text" placeholder=""
									class="form-control linha_'.$linha.'"
									style="text-align: center; min-width:70px;';if($rowPart['codigo']==""){$html .= ' background-color: '.$amarelo.' ';}$html .= '"
									id="linha_'.$linha.'_codigo"
									name="linha_'.$linha.'_codigo"
									antigo="'.$rowPart['codigo'].'";
									value="'.$rowPart['codigo'].'"
									onchange="javascript: salvaPartDire('.$linha.'); return false;">
							</td>
							<td style="font-size: 10px !important;">
								<select class="form-control linha_'.$linha.'" style="text-align: center !important; font-size:10px; ';if($rowPart['status']==""){$html .= ' background-color: '.$amarelo.' ';}$html .= '"
									id="linha_'.$linha.'_status"
									antigo="'.$rowPart['status'].'";
									name="linha_'.$linha.'_status"
									onchange="javascript: salvaPartDire('.$linha.'); return false;">
								>
								<optgroup style="font-size:10px; ">
									<option value=""></option>
									<option
										style="font-size:10px;"
										value="T" ';
									if($rowPart['status']=="T"){ $html .= " selected ";}
									$html .= '>Titular</option>
									<option
										style="font-size:10px;"
										value="R" ';
									if($rowPart['status']=="R"){ $html .= " selected ";}
									$html .= '>Reserva</option>
									<option
										style="font-size:10px;"
										value="E" ';
									if($rowPart['status']=="E"){ $html .= " selected ";}
									$html .= '>Excluído</option>
									<option
										style="font-size:10px;"
										value="D" ';
									if($rowPart['status']=="D"){ $html .= " selected ";}
									$html .= '>Desistente</option>
									<option
										style="font-size:10px;"
										value="F" ';
									if($rowPart['status']=="F"){ $html .= " selected ";}
									$html .= '>Falha de seleção</option>

								</optgroup>
								</select>

							</td>
							<td style="font-size: 12px !important;">
								<input type="text" placeholder=""
									class="form-control linha_'.$linha.'"
									style="text-align: center; min-width:70px; ';if($rowPart['grupo']==""){$html .= ' background-color: '.$amarelo.' ';}$html .= '"
									id="linha_'.$linha.'_grupo"
									name="linha_'.$linha.'_grupo"
									antigo="'.$rowPart['grupo'].'";
									value="'.$rowPart['grupo'].'"
									onchange="javascript: salvaPartDire('.$linha.'); return false;">
							</td>
							<td style="font-size: 12px !important;">
								<input type="text" placeholder=""
									class="form-control linha_'.$linha.'"
									style="text-align: center; min-width:70px; ';if($rowPart['periodo']==""){$html .= ' background-color: '.$amarelo.' ';}$html .= '"
									id="linha_'.$linha.'_periodo"
									name="linha_'.$linha.'_periodo"
									antigo="'.$rowPart['periodo'].'";
									value="'.$rowPart['periodo'].'"
									onchange="javascript: salvaPartDire('.$linha.'); return false;">
							</td>';
							if($_SESSION['projeto']==256){
								$html .= '<td style="font-size: 10px !important;">
												<select class="form-control linha_'.$linha.'" style="text-align: center; font-size:10px; ';if($rowPart['centro']==""){$html .= ' background-color: '.$amarelo.' ';}$html .= '"
													id="linha_'.$linha.'_centro"
													antigo="'.$rowPart['centro'].'";
													name="linha_'.$linha.'_centro"
													onchange="javascript: salvaPartDire('.$linha.'); return false;">
												>
												<optgroup style="font-size:10px; ">
													<option value=""></option>
								';
								$queryCentro = "SELECT * FROM cfg_centro where projeto = " . $_SESSION['projeto'];
								$resCentro   = $varConexao->pesquisa($queryCentro);
								while($rowCentro = mysqli_fetch_assoc($resCentro)){
									$html .= '<option
													style="font-size:10px;"
													value="'.$rowCentro['id'].'" ';
												if($rowPart['centro']==$rowCentro['id']){ $html .= " selected ";}
												$html .= '>'.$rowCentro['apelido'].'</option>';
								}
								$html .= '
												</select>

											</td>
								';
							}
			if($varCheckList->temEspecifico()==0){
				//Pega as colunas de acordo com as fases
				if($varFase=="TODOS"){
					$query = "select agenda_check.* from agenda_check
								inner join agenda_check_fase on agenda_check_fase.id = agenda_check.periodo
								where agenda_check.projeto = 0
									order by agenda_check_fase.id, periodo, ordem";
				}else{
					$query = "select agenda_check.* from agenda_check
								inner join agenda_check_fase on agenda_check_fase.id = agenda_check.periodo
								where agenda_check.projeto = 0
									and periodo = '".$varFase."'
									order by agenda_check_fase.id, periodo, ordem";
				}
			}else{
				//Pega as colunas de acordo com as fases
				if($varFase=="TODOS"){
					$query = "select agenda_check.* from agenda_check
									inner join agenda_check_fase on agenda_check_fase.id = agenda_check.periodo
									where agenda_check.projeto = $projeto
									order by agenda_check_fase.id, periodo, ordem";
				}else{
					$query = "select agenda_check.* from agenda_check
								inner join agenda_check_fase on agenda_check_fase.id = agenda_check.periodo
								where agenda_check.projeto = $projeto
										and periodo = '".$varFase."'
										order by agenda_check_fase.id, periodo, ordem";
				}
			}
			$resPer = $varConexao->pesquisa($query);
			$indiceFase = 0;
			while($rowPer = mysqli_fetch_assoc($resPer)){		//LOOP em todos os campos de cada participante do loop acima

				if($indiceFase == 0){ $indiceFase = $rowPer['periodo'];}else{
					if($indiceFase != $rowPer['periodo']){
						$html .= '<td class="fase_diferente">';
						$indiceFase = $rowPer['periodo'];
					}
				}

				$varCampo = new CheckListCampoParticipante();
				$varCampo->inicializaParticipante($rowPart['id'], $rowPer['id']);

				if($varCampo->observacoes!=""){
					$corBotaoIndo = "green-jungle";
				}else{
					$corBotaoIndo = "blue";

				}
				//comeco
				$classFormControl = "form-control linha_" . $linha . " linha_" . $linha . "_campo_" . $varCampo->campoClass->id;
				if($varCampo->campoClass->calculo==1){
					$classFormControl .= " calculo ";
					$somenteLeitura = " disabled ";
				}else{
					$somenteLeitura = "";
				}

				if($varCampo->campoClass->mascara!=""){$mascara = "mascara(this,'".$varCampo->campoClass->mascara."');";}else{$mascara="";}

				//

				if($varCampo->campoClass->opcoes==""){ // Não é combobox e não é calculo

					$corFundo = "";

					$varValorMinimo = $varCampo->campoClass->valor_minimo;
					$varValorMaximo = $varCampo->campoClass->valor_maximo;

					if($varCampo->valor!=""){	//Tem algo preenchido.
						if($varValorMinimo!=""){
							if($varValorMinimo>$varCampo->valor){
								$corFundo = 'red; ';
							}
						}
						if($varValorMaximo!=""){
							if($varValorMaximo<$varCampo->valor){
								$corFundo = 'red; ';
							}
						}
					}

					if($varCampo->campoClass->id==2){
						$eIdade = "1";
						$idadeMinima = $varProjeto->idade_minima;
						$idadeMaxima = $varProjeto->idade_maxima;
						if($varCampo->valor!=""){
							if($varCampo->valor<$idadeMinima){
								$corFundo = 'red; ';
							}
							if($varCampo->valor>$idadeMaxima){
								$corFundo = 'red; ';
							}
						}
					}else{
						$eIdade = "0";
					}



					$html .= '<td style="font-size: 12px !important;">
								<div class="input-group">
									<span class="input-group-btn">
										<button class="btn '.$corBotaoIndo.' btn" type="button" onclick="javascript: exibeInfo('.$varCampo->id.');"><i class="fa fa-info-circle"></i></button>
									</span>
									<input type="text" placeholder=""
										class="'.$classFormControl.' linha_'.$linha.'"
										style="text-align: center; min-width:70px; ';
										if($varCampo->valor==""){
											$html .= ' background-color: '.$amarelo.' ';
										}else{
											$html .= ' background-color: '.$corFundo.' ';
										}
										$html .= '"
										name="linha_'.$linha.'_'.$varCampo->id.'"
										id="linha_'.$linha.'_'.$varCampo->id.'"
										antigo="'.$varCampo->valor.'";
										onKeyUp="'.$mascara.'"
										varCampoId="'.$varCampo->campoClass->id.'"
										varTemCalculo="'.$varCampo->campoClass->calculo.'"
										varTipoCalculo="'.$varCampo->campoClass->calculo_tipo.'"
										varCamposCalculo="'.$varCampo->campoClass->calculo_campos.'"
										varEIdade="'.$eIdade.'"
										varIdadeMinima="'.$varProjeto->idade_minima.'"
										varIdadeMaxima="'.$varProjeto->idade_maxima.'"
										varMinimo="'.$varCampo->campoClass->valor_minimo.'"
										varMaximo="'.$varCampo->campoClass->valor_maximo.'"
										value="'.$varCampo->valor.'"
										'.$somenteLeitura.'
										onBlur="javascript: calculaCampos(this); return false;"
										onchange="javascript: salvaPartDire('.$linha.'); return false;"
										>
								</div>
							</td>';
				}else{
					$opcoes = $varCampo->campoClass->opcoes;
					$opcoes = explode(";", $opcoes);
					$html .= '<td style="font-size: 12px !important;">
								<div class="input-group">
								<span class="input-group-btn">
										<button class="btn '.$corBotaoIndo.' btn" type="button" onclick="javascript: exibeInfo('.$varCampo->id.');"><i class="fa fa-info-circle"></i></button>
								</span>

								<select class="'.$classFormControl.' linha_'.$linha.'"
									data-toggle="tooltip"
									'.$somenteLeitura.'
									title="Pago em: '.$varCampo->data_pagamento.'"
									style="text-align: center; min-width:70px; ';
									if($varCampo->valor==""){
										$html .= ' background-color: '.$amarelo.' ';
									}else{
										if( ($varCampo->pago==0)&&($varCampo->campoClass->cobranca==1)&&($varCampo->valor==Sim)){
											$html .= ' background-color: #ffebc7 ';
										}
									}
									$html .= '"
									name="linha_'.$linha.'_'.$varCampo->id.'"
									id="linha_'.$linha.'_'.$varCampo->id.'"
									antigo="'.$varCampo->valor.'";
									onKeyUp="'.$mascara.'"
									varCampoId="'.$varCampo->campoClass->id.'"
									varTemCalculo="'.$varCampo->campoClass->calculo.'"
									varTipoCalculo="'.$varCampo->campoClass->calculo_tipo.'"
									varCamposCalculo="'.$varCampo->campoClass->calculo_campos.'"
									onchange="javascript: salvaPartDire('.trim($linha).'); calculaCampos(this); return false;"
									>
									<option value=""></option>';
					for($iOpc=0;$iOpc<count($opcoes);$iOpc++){
						$html .= '<option value="'.$opcoes[$iOpc].'" ';
						if($opcoes[$iOpc]==$varCampo->valor){
							$html .= ' selected ';
						}
						$html .= '>'.$opcoes[$iOpc].'</option>';
					}
					$html .= '
								</select>
								</div>
							</td>';
				}
				//Fim

			}
			$html .= '
						<td style="font-size: 12px !important;">
							<center>
								<div class="btn-group btn-group">
								<button type="button" class="btn btn-default" onclick="javascript: salvaPart('.$linha.')"><i class="fa fa-floppy-o"></i></button>
								</div>
							</center>
						</td>
			</tr>';
			$linha++;
		}
		$footer ='
			</tbody>
		</html>
		';
		echo $head . $html . $footer;
	}
	if($_GET['act']=="PESQUISA_V2"){
		include_once __DIR__."/../class/projeto.class.php";
		$varProjeto = new Projeto($_SESSION['projeto']);
		$varConexao = new Conexao();

		$projeto 	= $_SESSION['projeto'];
		$query 		= "select id from agenda_check_fase where projeto = " . $projeto;
		$resQtd		= $varConexao->pesquisa($query);
		if(mysqli_num_rows($resQtd)==0){$projetoEspecifico = false;}else{$projetoEspecifico=true;}


		$varGrupo        = $_POST['grupo'];
		$varParticipante = $_POST['participante'];
		$varSexo         = $_POST['sexo'];
		$varFase         = $_POST['fase'];
		$varPeriodo      = $_POST['periodo'];
		$varCodigo       = $_POST['codigo'];

		$head = '<thead>
					<tr>
						<th style="min-width: 150px !important;vertical-align: middle;" width="150">
							<center><button type="button" class="btn btn-default blue-madison" onclick="javascript:exportaV2(999);"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar para <b>Excel</b></button></center>

						</th>
						<th style="font-size: 12px !important;"><center>#<br>Participante</center></th>
						<th style="font-size: 12px !important;"><center>Código&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</center></th>
						<th style="font-size: 12px !important;"><center>Status&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</center></th>
						<th style="font-size: 12px !important;"><center>Grupo</center></th>
						<th class="divisao_fase"><span>PRE</span></th>
						<!--<th class="divisao_fase"><span>PRE</span></th>-->
		';

		$fases = array("Inicial", "Follow-up", "Dieta", "Pós");

		/*Fase inicial*/
		$query 		= "select
							id
								from agenda_check_fase
							where
								item = 'Inicial'
								and projeto = " . $projeto;
		$resFase    = $varConexao->pesquisa($query);
		$rowFase    = mysqli_fetch_assoc($resFase);
		$varFase    = $rowFase['id'];

		$query = "select agenda_check.*
						from agenda_check
							inner join agenda_check_fase on agenda_check_fase.id = agenda_check.periodo
								where agenda_check.projeto = $projeto
									and periodo = '".$varFase."'
										order by agenda_check_fase.id, periodo, ordem";
		$resPer = $varConexao->pesquisa($query);
		while($rowPer = mysqli_fetch_assoc($resPer)){
			$head .= '
			<th style="font-size: 12px !important;"><center>'.$rowPer['nome'];
			if($rowPer['label']!=""){
				$head .= '<br><i><span style="font-size: 8pt;color: black">'.$rowPer['label'].'</span><i>';
			}
			$head .= '</center></th>';
		}
		/*Periodos*/
		for($i=1;$i<=$varProjeto->periodos;$i++){
			$query 		= "select
								id
									from agenda_check_fase
								where
									item = 'Follow-up'
									and projeto = " . $projeto;
			$resFase    = $varConexao->pesquisa($query);
			$rowFase    = mysqli_fetch_assoc($resFase);
			$varFase    = $rowFase['id'];

			$query = "select agenda_check.*
							from agenda_check
								inner join agenda_check_fase on agenda_check_fase.id = agenda_check.periodo
									where agenda_check.projeto = $projeto
										and periodo = '".$varFase."'
											order by agenda_check_fase.id, periodo, ordem";
			$resPer = $varConexao->pesquisa($query);
			$head .= '
				<th class="divisao_fase"><span>P'.$i.'-FU</span></th>
			';
			while($rowPer = mysqli_fetch_assoc($resPer)){
				$head .= '
				<th style="font-size: 12px !important;"><center>'.$rowPer['nome'];
				if($rowPer['label']!=""){
					$head .= '<br><i><span style="font-size: 8pt;color: black">'.$rowPer['label'].'</span><i>';
				}
				$head .= '</center></th>';
			}
			$query 		= "select
								id
									from agenda_check_fase
								where
									item = 'Dieta'
									and projeto = " . $projeto;
			$resFase    = $varConexao->pesquisa($query);
			$rowFase    = mysqli_fetch_assoc($resFase);
			$varFase    = $rowFase['id'];

			$query = "select agenda_check.*
							from agenda_check
								inner join agenda_check_fase on agenda_check_fase.id = agenda_check.periodo
									where agenda_check.projeto = $projeto
										and periodo = '".$varFase."'
											order by agenda_check_fase.id, periodo, ordem";
			$resPer = $varConexao->pesquisa($query);
			$head .= '
				<th class="divisao_fase"><span>P'.$i.'-DIETA</span></th>
			';
			while($rowPer = mysqli_fetch_assoc($resPer)){
				$head .= '
				<th style="font-size: 12px !important;"><center>'.$rowPer['nome'];
				if($rowPer['label']!=""){
					$head .= '<br><i><span style="font-size: 8pt;color: black">'.$rowPer['label'].'</span><i>';
				}
				$head .= '</center></th>';
			}
		}

		/*Fase pós*/
		$query 		= "select
							id
								from agenda_check_fase
							where
								item = 'Pós'
								and projeto = " . $projeto;
		$resFase    = $varConexao->pesquisa($query);
		$rowFase    = mysqli_fetch_assoc($resFase);
		$varFase    = $rowFase['id'];

		$query = "select agenda_check.*
						from agenda_check
							inner join agenda_check_fase on agenda_check_fase.id = agenda_check.periodo
								where agenda_check.projeto = $projeto
									and periodo = '".$varFase."'
										order by agenda_check_fase.id, periodo, ordem";
		$resPer = $varConexao->pesquisa($query);
		$head .= '
				<th class="divisao_fase"><span>PÓS</span></th>
			';
		while($rowPer = mysqli_fetch_assoc($resPer)){
			$head .= '
			<th style="font-size: 12px !important;"><center>'.$rowPer['nome'];
			if($rowPer['label']!=""){
				$head .= '<br><i><span style="font-size: 8pt;color: black">'.$rowPer['label'].'</span><i>';
			}
			$head .= '</center></th>';
		}
		$head .= '
					<th style="min-width: 100px !important" width="100"></th>
					</tr>
				</thead>
				<tbody>';


		//echo $head;



		$html = "";



		/* PEGAR DADOS DO PARTICIPANTE */
		$query = "select
						*
							from agenda_check_participante
								where projeto = " . $projeto . "
								and periodo = 1 ";
		if($varParticipante!=""){$query .= " and numero = '$varParticipante' ";}
		if($varGrupo!="TODOS"){
			if($varGrupo!=""){
				$query .= " and ( (grupo = '$varGrupo') ) ";
			}else{
				$query .= " and ( (grupo = '') ) ";
			}
		}
		$varStatus = $_POST['status'];
		if($varStatus!="TODOS"){
			if($varStatus!=""){
				$query .= " and ( (status = '$varStatus') ) ";
			}else{
				$query .= " and ( (status = '') ) ";
			}
		}
		if($varCodigo!=""){
			$query .= " and ( (codigo = '$varCodigo') ) ";
		}
		if(isset($_POST['centro'])){
			$varCentro = $_POST['centro'];
			if( ($varCentro!="TODOS")&&($varCentro!="")){
				$query .= " and centro = $varCentro";
			}
		}
		$query .= " order by cast(numero as INTEGER) ";

		//********AQUI É UM LIMITE PARA NÃO FICAR DEMORADO */
		//$query .= " limit 2";

		$resPart = $varConexao->pesquisa($query);
		$linha = 1;
		while($rowPart = mysqli_fetch_assoc($resPart)){		//LOOP em todos os participantes
			$varIdParticipante = $rowPart['id'];
			$varHash           = $rowPart['hash'];
			$varCodigo         = $rowPart['codigo'];
			$varCodigoPart     = $rowPart['codigo'];
			$varNumeroPart     = $rowPart['numero'];
			$varStatusPart     = $rowPart['status'];
			$varGrupoPart      = $rowPart['grupo'];
			$amarelo           = "#e9ff00; ";

			//Verificando se tem filtro de sexo
			if($varSexo!=""){
				//Pear o sexo do participante
				$idParticipante = $rowPart['id'];
				if($varCheckList->temEspecifico()==1){
					$query = "SELECT id FROM `agenda_check` where nome = 'Sexo' and projeto = ".$varProjeto->id;
					$resSexo = $varConexao->pesquisa($query);
					$rowSexo = mysqli_fetch_assoc($resSexo);
					$varIdexo = $rowSexo['id'];

				}else{
					$query = "SELECT id FROM `agenda_check` where nome = 'Sexo' and projeto = 0";
					$resSexo = $varConexao->pesquisa($query);
					$rowSexo = mysqli_fetch_assoc($resSexo);
					$varIdexo = $rowSexo['id'];
				}
				$query = "SELECT valor FROM `agenda_check_participante_campo` where participante = ".$idParticipante." and campo = ".$varIdexo;
				$resValor = $varConexao->pesquisa($query);
				$rowValor = mysqli_fetch_assoc($resValor);
				$valorSexo = $rowValor['valor'];
				if($valorSexo!=$varSexo){
					continue;
				}
			}


			$html .= '	<tr>
							<td style="font-size: 12px !important;">
								<center>
									<button type="button" class="btn btn-default" onclick="javascript: deletaPart_v2(\''.$rowPart['hash'].'\')"><i class="fa fa-trash"></i></button>
									<button type="button" class="btn btn-default" onclick="javascript: salvaPart('.$linha.')"><i class="fa fa-floppy-o"></i></button>
									<button type="button" class="btn btn-default blue-madison" alt="Não aplicável" title="Não aplicável" onclick="javascript: naoAplicavel('.$linha.');">n/a</button>
								</center>
							</td>
							<td style="font-size: 12px !important;">
								<input type="hidden"
									id="linha_'.$linha.'_id"
									name="linha_'.$linha.'_id"
									value="'.$rowPart['id'].'"
									>
								<input type="text" placeholder=""
									class="form-control linha_'.$linha.'"
									style="text-align: center; min-width:70px;';if($rowPart['numero']==""){$html .= ' background-color: '.$amarelo.' ';}$html .= '"
									id="linha_'.$linha.'_numero"
									name="linha_'.$linha.'_numero"
									antigo="'.$rowPart['numero'].'";
									value="'.$rowPart['numero'].'"
									onchange="javascript: salvaPartDire('.$linha.'); return false;">
							</td>
							<td style="font-size: 12px !important;">
								<input type="text" placeholder=""
									class="form-control linha_'.$linha.'"
									style="text-align: center; min-width:70px;';if($rowPart['codigo']==""){$html .= ' background-color: '.$amarelo.' ';}$html .= '"
									id="linha_'.$linha.'_codigo"
									name="linha_'.$linha.'_codigo"
									antigo="'.$rowPart['codigo'].'";
									value="'.$rowPart['codigo'].'"
									onchange="javascript: salvaPartDire('.$linha.'); return false;">
							</td>
							<td style="font-size: 10px !important;">
								<div class="input-group">

									<select class="form-control linha_'.$linha.'" style="text-align: center !important; font-size:10px; ';if($rowPart['status']==""){$html .= ' background-color: '.$amarelo.' ';}$html .= '"
										id="linha_'.$linha.'_status"
										antigo="'.$rowPart['status'].'";
										name="linha_'.$linha.'_status"
										onchange="javascript: salvaPartDire('.$linha.'); return false;">
									>
										<optgroup style="font-size:10px; ">
											<option value=""></option>
											<option
												style="font-size:10px;"
												value="T" ';
											if($rowPart['status']=="T"){ $html .= " selected ";}
											$html .= '>Titular</option>
											<option
												style="font-size:10px;"
												value="R" ';
											if($rowPart['status']=="R"){ $html .= " selected ";}
											$html .= '>Reserva</option>
											<option
												style="font-size:10px;"
												value="E" ';
											if($rowPart['status']=="E"){ $html .= " selected ";}
											$html .= '>Excluído</option>
											<option
												style="font-size:10px;"
												value="D" ';
											if($rowPart['status']=="D"){ $html .= " selected ";}
											$html .= '>Desistente</option>
											<option
												style="font-size:10px;"
												value="F" ';
											if($rowPart['status']=="F"){ $html .= " selected ";}
											$html .= '>Falha de seleção</option>

										</optgroup>
									</select>
								</div>
							</td>
							<td style="font-size: 12px !important;">
								<input type="text" placeholder=""
									class="form-control linha_'.$linha.'"
									style="text-align: center; min-width:70px; ';if($rowPart['grupo']==""){$html .= ' background-color: '.$amarelo.' ';}$html .= '"
									id="linha_'.$linha.'_grupo"
									name="linha_'.$linha.'_grupo"
									antigo="'.$rowPart['grupo'].'";
									value="'.$rowPart['grupo'].'"
									onchange="javascript: salvaPartDire('.$linha.'); return false;">
							</td>
							<!--<td style="font-size: 12px !important;">
								<input type="text" placeholder=""
									class="form-control linha_'.$linha.'"
									style="text-align: center; min-width:70px; ';if($rowPart['periodo']==""){$html .= ' background-color: '.$amarelo.' ';}$html .= '"
									id="linha_'.$linha.'_periodo"
									name="linha_'.$linha.'_periodo"
									antigo="'.$rowPart['periodo'].'";
									value="'.$rowPart['periodo'].'"
									onchange="javascript: salvaPartDire('.$linha.'); return false;">
							</td>
							-->
							';



							// FASE INICIAL
							$html .= '<th class="divisao_fase_td"><span>PRE</span></th>';
							$html .= montaFase("Inicial", 1, $linha, $varIdParticipante);



							for($i=1;$i<=$varProjeto->periodos;$i++){
								//Pegar ID participante
								$query = "select
											*
												from agenda_check_participante
											where projeto = " . $projeto . "
												and periodo = " . $i . "
												and hash = '$varHash'";
								if($_SESSION['projeto']<488){
									if($varParticipante!=""){$query .= " and numero = '$varParticipante' ";}
									if($varGrupo!="TODOS"){
										if($varGrupo!=""){
											$query .= " and ( (grupo = '$varGrupo') ) ";
										}else{
											$query .= " and ( (grupo = '') ) ";
										}
									}
									$varStatus = $_POST['status'];
									if($varStatus!="TODOS"){
										if($varStatus!=""){
											$query .= " and ( (status = '$varStatus') ) ";
										}else{
											$query .= " and ( (status = '') ) ";
										}
									}
									if($varCodigo!=""){
										$query .= " and ( (codigo = '$varCodigo') ) ";
									}
									if(isset($_POST['centro'])){
										$varCentro = $_POST['centro'];
										if( ($varCentro!="TODOS")&&($varCentro!="")){
											$query .= " and centro = $varCentro";
										}
									}
								}
								$query .= " order by cast(numero as INTEGER) ";
								$resParticipante = $varConexao->pesquisa($query);
								if(mysqli_num_rows($resParticipante)==0){
									//Não achou o participante/periodo. Vou ter que incluir
									$queryInsere = "insert into agenda_check_participante (projeto, hash, numero, codigo, status, grupo, periodo) values (
										'".$_SESSION['projeto']."',
										'".$varHash."',
										'".$varNumeroPart."',
										'".$varCodigoPart."',
										'".$varStatusPart."',
										'".$varGrupoPart."',
										'".$i."');";
										//// - 20261013 $varConexao->pesquisa($queryInsere);
								}
								$resParticipante = $varConexao->pesquisa($query);
								$rowParticipante = mysqli_fetch_assoc($resParticipante);
								$idParticipante = "";
								$idParticipante = $rowParticipante['id'];



							// FASE FOLLOW-UP
								$html .= '<th class="divisao_fase_td"><span>P'.$i.'-FU</span></th>';
								$html .= montaFase("Follow-up", $i, $linha, $idParticipante);
								// FASE DIETA
								$html .= '<th class="divisao_fase_td"><span>P'.$i.'-DIETA</span></th>';
								$html .= montaFase("Dieta", $i, $linha, $idParticipante);
							}
							// FASE PÓS
							//Pegar ID participante
							$query = "select * from agenda_check_participante where projeto = " . $projeto . " and periodo = ".$varProjeto->periodos." and hash = '$varHash'";
							if($_SESSION['projeto']<488){
								if($varParticipante!=""){$query .= " and numero = '$varParticipante' ";}
								if($varGrupo!="TODOS"){
									if($varGrupo!=""){
										$query .= " and ( (grupo = '$varGrupo') ) ";
									}else{
										$query .= " and ( (grupo = '') ) ";
									}
								}
								$varStatus = $_POST['status'];
								if($varStatus!="TODOS"){
									if($varStatus!=""){
										$query .= " and ( (status = '$varStatus') ) ";
									}else{
										$query .= " and ( (status = '') ) ";
									}
								}
								if($varCodigo!=""){
									$query .= " and ( (codigo = '$varCodigo') ) ";
								}
								if(isset($_POST['centro'])){
									$varCentro = $_POST['centro'];
									if( ($varCentro!="TODOS")&&($varCentro!="")){
										$query .= " and centro = $varCentro";
									}
								}
							}
							$query .= " order by cast(numero as INTEGER) ";
							$resParticipante = $varConexao->pesquisa($query);
							$rowParticipante = mysqli_fetch_assoc($resParticipante);
							$idParticipante = $rowParticipante['id'];

							$html .= '<th class="divisao_fase_td"><span>PÓS</span></th>';
							$html .= montaFase("Pós", 1, $linha, $idParticipante);

			$html .= '
						<td style="font-size: 12px !important;">
							<center>
								<div class="btn-group btn-group">
								<button type="button" class="btn btn-default" onclick="javascript: salvaPart('.$linha.')"><i class="fa fa-floppy-o"></i></button>
								</div>
							</center>
						</td>
			</tr>';
			$linha++;
		}
		$footer ='
			</tbody>
		</html>
		';
		echo $head . $html . $footer;
	}
	if($_GET['act']=="INCLUI"){
		$varConexao = new Conexao();
		$query = "insert into agenda_check_participante (projeto, numero, codigo, status, grupo) values (
			'".$_SESSION['projeto']."', '', '', '', '');";
		$varConexao->pesquisa($query);
	}
	if($_GET['act']=="INCLUI_V2"){
		include_once __DIR__."/../class/projeto.class.php";
		$varConexao = new Conexao();

		$varLetras = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
		$letra      = $varLetras[rand(0, count($varLetras) - 1)];
		$codigo     = $letra . rand(1, 9999554);
		$varProjeto = new Projeto($_SESSION['projeto']);

		//Pegar a quantidade de periodos do estudo.
		for($i=1;$i<=$varProjeto->periodos;$i++){
			$query = "insert into agenda_check_participante (projeto, hash, numero, codigo, status, grupo, periodo) values (
				'".$_SESSION['projeto']."', '$codigo', '', '', '', '', '$i');";
			$varConexao->pesquisa($query);
		}
	}
	if($_GET['act']=="DELETA"){
		$id = $_GET['id'];
		$varConexao = new Conexao();
		$query = "delete from agenda_check_participante where id = " . $id;
		$varConexao->pesquisa($query);
		echo $query;
	}
	if($_GET['act']=="DELETA_V2"){
		$hash = $_GET['hash'];
		if($hash!=""){
		$varConexao = new Conexao();
			$query = "delete from agenda_check_participante where hash = '$hash'";
			$varConexao->pesquisa($query);
		}
	}
	if($_GET['act']=="SALVA"){
		$varConexao = new Conexao();

		$id    = $_POST['id'];
		$texto = $_POST['texto'];

		$texto = substr($texto,1,strlen($texto));
		$variaveis = explode("&", $texto);
		for($i=0;$i<count($variaveis);$i++){
			$item = explode("=", $variaveis[$i]);

			$variavel = explode("_", $item[0]);
			$variavel = $variavel[2];

			$valor    = explode("|", $item[1]);
			$valorAtual 	= $valor[0];
			$valorAntigo 	= $valor[1];

			if((!is_numeric($variavel))&&($variavel!="id")){
				if($valorAtual!=$valorAntigo){
					$valorAtual = trim($valorAtual);
					$query = "update agenda_check_participante
									set $variavel = '$valorAtual',
									log_usuario = '".$_SESSION['user_id']."'
										where id = " . $id;
					$varConexao->pesquisa($query);
				}
			}else{
				if(($valorAtual!=$valorAntigo)&&(is_numeric($variavel))){
					$valorAtual = trim($valorAtual);
					$query = "update agenda_check_participante_campo
									set valor = '".utf8_decode($valorAtual)."',
									log_usuario = '".$_SESSION['user_id']."'
										where id = " . $variavel;
					$varConexao->pesquisa($query);
					echo $query . "*";
				}
			}
		}
	}
	/*if($_GET['act']=="SALVA_DIRE"){
		$varConexao = new Conexao();

		$id    = $_POST['id'];
		$texto = $_POST['texto'];

		$texto = substr($texto,1,strlen($texto));
		$variaveis = explode("&", $texto);
		echo "TEXTO->"+$texto;
		for($i=0;$i<count($variaveis);$i++){
			echo "Variável: " . $variaveis[$i];
			$item = explode("=", $variaveis[$i]);

			$variavel = explode("_", $item[0]);
			$variavel = $variavel[2];

			$valor    = explode("|", $item[1]);
			$valorAtual 	= $valor[0];
			$valorAntigo 	= $valor[1];

			if((!is_numeric($variavel))&&($variavel!="id")){
				if($valorAtual!=$valorAntigo){
					$valorAtual = trim($valorAtual);
					$query = "update agenda_check_participante
									set $variavel = '$valorAtual',
									log_usuario = '".$_SESSION['user_id']."'
										where id = " . $id;
					$varConexao->pesquisa($query);
				}
			}else{
				if(($valorAtual!=$valorAntigo)&&(is_numeric($variavel))){
					$valorAtual = trim($valorAtual);
					$query = "update agenda_check_participante_campo
									set valor = '".utf8_decode($valorAtual)."',
									log_usuario = '".$_SESSION['user_id']."'
										where id = " . $variavel;
					$varConexao->pesquisa($query);
				}
			}
		}
	}*/
