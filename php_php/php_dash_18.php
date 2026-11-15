<?
	/*error_reporting(E_ALL);
	ini_set("display_errors", 1);*/

	session_start();
	include_once "../class/conexao.class.php";
	if($_GET['grafico']=="paciente"){
		$json = "[";
		$query = "SELECT cfg_centro.apelido, count(*) as qtd
					FROM crf_paciente_pre_rand
						inner join cfg_centro on crf_paciente_pre_rand.centro = cfg_centro.id
						WHERE 
							crf_paciente_pre_rand.projeto = ".$_SESSION['projeto'];
		if($_GET['centro']!="ALL"){
			$query .= " and crf_paciente_pre_rand.centro = " . $_GET['centro'];
		}
		$query .= "
						group by cfg_centro.apelido";
		$conexao = new Conexao();
		$resCentros = $conexao->pesquisa($query);
		$qtdCentro = 0;
		while($rowCentros = mysqli_fetch_assoc($resCentros)){
			if($qtdCentro>0){$json.=",";}
			$json .= '{"centro":"'.utf8_encode($rowCentros['apelido']).'","patients": '. $rowCentros['qtd'] .'}';
			$qtdCentro++;
		}		
		$json .= "]";
		echo $json;
	}
	if($_GET['grafico']=="pacienteBraco"){
		$json = "[";
		$query = "SELECT 
					crf_rand_tratamento.nome_en, count(crf_rand_tratamento.nome) as qtd
					FROM crf_rand_semente
						inner join cfg_centro on crf_rand_semente.centro = cfg_centro.id
						inner join crf_rand_tratamento on crf_rand_semente.tratamento = crf_rand_tratamento.id  
							WHERE crf_rand_semente.projeto = ".$_SESSION['projeto'];
		if($_GET['centro']!="ALL"){
			$query .= " and cfg_centro.id = " . $_GET['centro'];
		}
		$query .="					group by crf_rand_tratamento.nome";
		$conexao = new Conexao();
		$resCentros = $conexao->pesquisa($query);
		$qtdCentro = 0;
		while($rowCentros = mysqli_fetch_assoc($resCentros)){
			if($qtdCentro>0){$json.=",";}
			$json .= '{"treatment":"'.utf8_encode($rowCentros['nome_en']).'","total": '. $rowCentros['qtd'] .'}';
			$qtdCentro++;
		}		
		$json .= "]";
		echo $json;
	}
	
	if($_GET['grafico']=='hospitalizacao'){
		$json = "[";
		
		$query = "select 		
					crf_rand_tratamento.nome_en,	
					vw_valor_campo.valor as dia, count(vw_valor_campo.valor) as qtd
					from vw_valor_campo 
						left join crf_rand_semente on vw_valor_campo.projeto = crf_rand_semente.projeto
							and vw_valor_campo.centro = crf_rand_semente.centro
							and vw_valor_campo.nro_participante = crf_rand_semente.paciente_alocado
						left join crf_rand_tratamento on crf_rand_semente.tratamento = crf_rand_tratamento.id
						where vw_valor_campo.projeto = ".$_SESSION['projeto'] . "
							and vw_valor_campo.nome = 'CALC_LENGTH'
							and vw_valor_campo.valor <> ''";
		if($_GET['centro']!="ALL"){
			$query .= " and vw_valor_campo.centro = " . $_GET['centro'];
		}
		$query .= "		group by crf_rand_tratamento.nome_en, vw_valor_campo.valor  order by cast(dia as int)";						
		$conexao = new Conexao();
		$resCentros = $conexao->pesquisa($query);						
		$registros = array();		
		while($rowCentros = mysqli_fetch_assoc($resCentros)){
			$registros[] = array($rowCentros['nome_en'], $rowCentros['dia'], $rowCentros['qtd']);
		}
		$tem = 0;
		for($i=1;$i<=29;$i++){			
			$heparina = 0;
			$controle = 0;
			for($ind=0;$ind<count($registros);$ind++){
				if(($registros[$ind][0]=="HEPARIN")&&($registros[$ind][1]==$i)){
					$heparina = $registros[$ind][2];
				}
				if((trim($registros[$ind][0])=="SoC")&&($registros[$ind][1]==$i)){
					$controle = $registros[$ind][2];
				}
			}
			if(($heparina!=0)||($controle!=0)){
				if($tem>0){$json .= ",";}
				$json .= '
					{
						"dias": "'.$i.'",
						"Heparin": '.$heparina.',
						"Soc": '.$controle.'
					}';
				$tem++;
			}
			
		}
		$json .= "]";
		echo $json;	
	}
	if($_GET['grafico']=="mediaDiasHospitalizado"){
		$json = "[";
		$query = "
					SELECT 	
						crf_rand_tratamento.nome as tratamento, obito.valor
						FROM crf_rand_tratamento
							inner join crf_rand_semente on crf_rand_tratamento.id = crf_rand_semente.tratamento
							left join (
										SELECT projeto, centro, nro_participante, valor 
											FROM `vw_valor_campo` 
												where nome = 'CALC_LENGTH'
													and vw_valor_campo.valor IS NOT NULL
										) as obito on crf_rand_tratamento.projeto = obito.projeto 
												and crf_rand_semente.centro = obito.centro 
												and crf_rand_semente.paciente_alocado = obito.nro_participante
							where 
								 crf_rand_semente.paciente_alocado > 0
								and crf_rand_tratamento.projeto = ".$_SESSION['projeto'];
		if($_GET['centro']!="ALL"){
			$query .= " and crf_rand_semente.centro = " . $_GET['centro'];
		}						
		$query .= " and crf_rand_tratamento.nome = 'TESTE (HEPARINA)'
					and obito.valor > 0 ";		
		$conexao = new Conexao(); 
		
		$resQtd = $conexao->pesquisa($query);		
		$somatorio = 0;		
		while($rowQtd = mysqli_fetch_assoc($resQtd)){
			$somatorio = $somatorio + intval($rowQtd['valor']);
		}		
		$mediaHeparina = $somatorio / mysqli_num_rows($resQtd);
		$query = "
					SELECT 	
						crf_rand_tratamento.nome as tratamento, obito.valor
						FROM crf_rand_tratamento
							inner join crf_rand_semente on crf_rand_tratamento.id = crf_rand_semente.tratamento
							left join (
										SELECT projeto, centro, nro_participante, valor 
											FROM `vw_valor_campo` 
												where nome = 'CALC_LENGTH'
													and vw_valor_campo.valor IS NOT NULL
										) as obito on crf_rand_tratamento.projeto = obito.projeto 
												and crf_rand_semente.centro = obito.centro 
												and crf_rand_semente.paciente_alocado = obito.nro_participante
							where 
								 crf_rand_semente.paciente_alocado > 0
								and crf_rand_tratamento.projeto = ".$_SESSION['projeto'];
		if($_GET['centro']!="ALL"){
			$query .= " and crf_rand_semente.centro = " . $_GET['centro'];
		}						
		$query .= " and crf_rand_tratamento.nome = 'CONTROLE'
					and obito.valor > 0 ";		
		$conexao = new Conexao();
		$resQtd = $conexao->pesquisa($query);		
		$somatorio = 0;		
		while($rowQtd = mysqli_fetch_assoc($resQtd)){
			$somatorio = $somatorio + intval($rowQtd['valor']);
		}		
		$mediaControle = $somatorio / mysqli_num_rows($resQtd);		
		$json = '[{"category":"Hep","days": '.intval($mediaHeparina).'},{"category":"SoC","days": '.intval($mediaControle).'}]';
		echo $json;
	}
	if($_GET['grafico']=="mortalidade"){
		$query = "SELECT 	
					crf_rand_tratamento.nome as tratamento, count(*) as qtd
					FROM crf_rand_tratamento
						inner join crf_rand_semente on crf_rand_tratamento.id = crf_rand_semente.tratamento        
						where 
							crf_rand_tratamento.projeto = ".$_SESSION['projeto']."
							and crf_rand_semente.paciente_alocado > 0
							group by 1 ";		
		$conexao = new Conexao();
		$resQtd = $conexao->pesquisa($query);				
		while($rowQtd = mysqli_fetch_assoc($resQtd)){
			if($rowQtd['tratamento']=="CONTROLE"){$qtdControleTotal = $rowQtd['qtd'];}
			if($rowQtd['tratamento']=="TESTE (HEPARINA)"){$qtdHeparinaTotal = $rowQtd['qtd'];}
		}	

		$query = "SELECT 	
					crf_rand_tratamento.nome as tratamento, obito.valor, count(*) as qtd
					FROM crf_rand_tratamento
						inner join crf_rand_semente on crf_rand_tratamento.id = crf_rand_semente.tratamento
						left join (SELECT projeto, centro, nro_participante, valor FROM `vw_valor_campo` where nome = 'ALTA_OBITO' and vw_valor_campo.valor <> '' and vw_valor_campo.projeto = 18) as obito on crf_rand_tratamento.projeto = obito.projeto and crf_rand_semente.centro = obito.centro and crf_rand_semente.paciente_alocado = obito.nro_participante
						where 
							crf_rand_tratamento.projeto = ".$_SESSION['projeto']."
							and crf_rand_semente.paciente_alocado > 0
							group by 1, 2";
		$conexao = new Conexao();
		$resQtd = $conexao->pesquisa($query);				
		while($rowQtd = mysqli_fetch_assoc($resQtd)){
			$valor	=	utf8_encode($rowQtd['valor']);
			if($rowQtd['tratamento']=="CONTROLE"){
				if($valor=="1 - Sim"){$qtdControleObito = $rowQtd['qtd'];}
			}
			if($rowQtd['tratamento']=="TESTE (HEPARINA)"){
				if($valor=="1 - Sim"){$qtdHeparinaObito = $rowQtd['qtd'];}
			}
		}
		$json = '[
					{"category": "Hep","value1": '.intval($qtdHeparinaObito).',"value2": '.intval($qtdHeparinaTotal-$qtdHeparinaObito).'},
					{"category": "SoC","value1": '.intval($qtdControleObito).',"value2": '.intval($qtdControleTotal-$qtdControleObito).'}
					]';
		echo $json;
	}
	//Entubados
	if($_GET['grafico']=="entubados"){
		$json = "[";
		$query = "
					SELECT 	
						crf_rand_tratamento.nome as tratamento, count(entubado.valor) as qtd
						FROM crf_rand_tratamento
							inner join crf_rand_semente on crf_rand_tratamento.id = crf_rand_semente.tratamento
							left join (
										SELECT projeto, centro, nro_participante, valor 
											FROM `vw_valor_campo` 
												where nome = 'CALC_ENTUBADO'													
										) as entubado on crf_rand_tratamento.projeto = entubado.projeto 
												and crf_rand_semente.centro = entubado.centro 
												and crf_rand_semente.paciente_alocado = entubado.nro_participante
							where 
								 crf_rand_semente.paciente_alocado > 0
								and crf_rand_tratamento.projeto = ".$_SESSION['projeto']. "
								and entubado.valor = 'S' ";
		if($_GET['centro']!="ALL"){
			$query .= " and crf_rand_semente.centro = " . $_GET['centro'];
		}						
		$query .= " and crf_rand_tratamento.nome = 'TESTE (HEPARINA)' ";
		$conexao = new Conexao(); 		
		$resQtd = $conexao->pesquisa($query);		
		$rowQtd = mysqli_fetch_assoc($resQtd);				
		$heparina = $rowQtd['qtd'];
		if($heparina==""){$heparina=0;}
		
		$query = "
					SELECT 	
						crf_rand_tratamento.nome as tratamento, count(entubado.valor) as qtd
						FROM crf_rand_tratamento
							inner join crf_rand_semente on crf_rand_tratamento.id = crf_rand_semente.tratamento
							left join (
										SELECT projeto, centro, nro_participante, valor 
											FROM `vw_valor_campo` 
												where nome = 'CALC_ENTUBADO'													
										) as entubado on crf_rand_tratamento.projeto = entubado.projeto 
												and crf_rand_semente.centro = entubado.centro 
												and crf_rand_semente.paciente_alocado = entubado.nro_participante
							where 
								 crf_rand_semente.paciente_alocado > 0
								and crf_rand_tratamento.projeto = ".$_SESSION['projeto']. "
								and entubado.valor = 'S' ";
		if($_GET['centro']!="ALL"){
			$query .= " and crf_rand_semente.centro = " . $_GET['centro'];
		}						
		$query .= " and crf_rand_tratamento.nome = 'CONTROLE'";
		$conexao = new Conexao();
		$resQtd = $conexao->pesquisa($query);		
		$rowQtd = mysqli_fetch_assoc($resQtd);				
		$controle = $rowQtd['qtd'];
		if($controle==""){$controle=0;}
		
		$json = '[{"category":"Hep","days": '.intval($heparina).'},{"category":"SoC","days": '.intval($controle).'}]';
		echo $json;
	}
	//Fim dos entubados
	if($_GET['grafico']=="pacienteData"){
		$json = "[";
		$query = "select concat( substr(valor,7,4), substr(valor,4,2),  substr(valor,1,2)) as data, valor, count(valor) as qtd
						from vw_valor_campo 
						where nome = 'TRI_RAND_DATA_RANDOMIZACAO' and valor <> ''  
							and vw_valor_campo.projeto = ".$_SESSION['projeto'];
		
		if($_GET['centro']!="ALL"){
			$query .= " and vw_valor_campo.centro = " . $_GET['centro'];
		}
		$query .=" group by concat( substr(valor,7,4), substr(valor,4,2),  substr(valor,1,2)), valor
				order by concat( substr(valor,7,4), substr(valor,4,2),  substr(valor,1,2))";
		$conexao = new Conexao();
		$resCentros = $conexao->pesquisa($query);
		$qtdCentro = 0;		
		$total = 0;
		while($rowCentros = mysqli_fetch_assoc($resCentros)){
			if($qtdCentro>0){$json.=",";}
			$total = $total + $rowCentros['qtd'];
			$json .= '{"data":"'.utf8_encode($rowCentros['valor']).'","inclusoes": '. $total .'}';
			$qtdCentro++;
		}
		$json .= "]";
		echo $json;		
	}
	if($_GET['grafico']=="statusParticipantes"){
		$query = "SELECT 	
					crf_rand_tratamento.nome as tratamento, count(*) as qtd
					FROM crf_rand_tratamento
						inner join crf_rand_semente on crf_rand_tratamento.id = crf_rand_semente.tratamento        
						where 
							crf_rand_tratamento.projeto = ".$_SESSION['projeto']."
							and crf_rand_semente.paciente_alocado > 0
							group by 1 ";		
		$conexao = new Conexao();
		$resQtd = $conexao->pesquisa($query);				
		while($rowQtd = mysqli_fetch_assoc($resQtd)){
			if($rowQtd['tratamento']=="CONTROLE"){$qtdControleTotal = $rowQtd['qtd'];}
			if($rowQtd['tratamento']=="TESTE (HEPARINA)"){$qtdHeparinaTotal = $rowQtd['qtd'];}
		}	

		$query = "SELECT 	
					crf_rand_tratamento.nome as tratamento, obito.valor, count(*) as qtd
					FROM crf_rand_tratamento
						inner join crf_rand_semente on crf_rand_tratamento.id = crf_rand_semente.tratamento
						left join (SELECT projeto, centro, nro_participante, valor FROM `vw_valor_campo` where nome = 'ALTA_STATUS' and vw_valor_campo.valor <> '' and vw_valor_campo.projeto = 18) as obito on crf_rand_tratamento.projeto = obito.projeto and crf_rand_semente.centro = obito.centro and crf_rand_semente.paciente_alocado = obito.nro_participante
						where 
							crf_rand_tratamento.projeto = ".$_SESSION['projeto']."
							and crf_rand_semente.paciente_alocado > 0
							group by 1, 2";
		$conexao = new Conexao();
		$resQtd = $conexao->pesquisa($query);				
		while($rowQtd = mysqli_fetch_assoc($resQtd)){
			$valor	=	utf8_encode($rowQtd['valor']);
			if($rowQtd['tratamento']=="CONTROLE"){
				if($valor==""){$qtdControleExecucao = $rowQtd['qtd'];}
				if($valor=="1 - Alta após completar todas as avaliações previstas"){$qtdControleAlta = $rowQtd['qtd'];}
				if($valor=="2 - Interrupção da participação"){$qtdControleTP = $rowQtd['qtd'];}
			}
			if($rowQtd['tratamento']=="TESTE (HEPARINA)"){
				if($valor==""){$qtdHeparinaExecucao = $rowQtd['qtd'];}
				if($valor=="1 - Alta após completar todas as avaliações previstas"){$qtdHeparinaAlta = $rowQtd['qtd'];}
				if($valor=="2 - Interrupção da participação"){$qtdHeparinaTP = $rowQtd['qtd'];}
			}
		}
		$query = "SELECT 	
					crf_rand_tratamento.nome as tratamento, obito.valor, count(*) as qtd
					FROM crf_rand_tratamento
						inner join crf_rand_semente on crf_rand_tratamento.id = crf_rand_semente.tratamento
						left join (SELECT projeto, centro, nro_participante, valor FROM `vw_valor_campo` where nome = 'ALTA_OBITO' and vw_valor_campo.valor <> '' and vw_valor_campo.projeto = 18) as obito on crf_rand_tratamento.projeto = obito.projeto and crf_rand_semente.centro = obito.centro and crf_rand_semente.paciente_alocado = obito.nro_participante
						where 
							crf_rand_tratamento.projeto = ".$_SESSION['projeto']."
							and crf_rand_semente.paciente_alocado > 0
							group by 1, 2";
		$conexao = new Conexao();
		$resQtd = $conexao->pesquisa($query);				
		while($rowQtd = mysqli_fetch_assoc($resQtd)){
			$valor	=	utf8_encode($rowQtd['valor']);
			if($rowQtd['tratamento']=="CONTROLE"){
				if($valor=="1 - Sim"){$qtdControleObito = $rowQtd['qtd'];}
			}
			if($rowQtd['tratamento']=="TESTE (HEPARINA)"){
				if($valor=="1 - Sim"){$qtdHeparinaObito = $rowQtd['qtd'];}
			}
		}
		
		$qtdHeparinaFinalizado = $qtdHeparinaTotal - $qtdHeparinaExecucao;
		$qtdControleFinalizado = $qtdControleTotal - $qtdControleExecucao;
		$json = '[
					{"category": "Heparin","total": '.intval($qtdHeparinaTotal).',"active": '.intval($qtdHeparinaExecucao).', "finished": '.intval($qtdHeparinaFinalizado).'},
					{"category": "SoC","total": '.intval($qtdControleTotal).',"active": '.intval($qtdControleExecucao).', "finished": '.intval($qtdControleFinalizado).'}
					]';
		echo $json;
	}
	
	