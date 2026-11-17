<?php
	session_start();
    include_once __DIR__."/../class/conexao.class.php";
    include_once __DIR__."/../class/checklist.class.php";
	include_once __DIR__."/../class/projeto.class.php";
	include_once __DIR__."/../funcoes.php";

	$varConexao = new Conexao();
	$varProjeto = new Projeto($_SESSION['projeto']);
	$varCheckList = new CheckList($_SESSION['projeto']);


	function retornaStatus($status){
		switch($status){
			case "T":
				return "Titular";
			case "R":
				return "Reserva";
			case "E":
				return "Excluído";
			case "D":
				return "Desistente";
			case "F":
				return "Falha de seleção";
		}
		return $status;
	}


	function montaFase($fase, $periodo, $linha, $participante, $colInicial, $objPHPExcel){
		global $varConexao;
		global $varProjeto;

		//Pega as colunas de acordo com as fases
		$query = "select agenda_check.* from agenda_check
					inner join agenda_check_fase on agenda_check_fase.id = agenda_check.periodo
					where agenda_check.projeto = ".$varProjeto->id."
							and agenda_check_fase.item = '".$fase."'
							order by agenda_check_fase.id, periodo, ordem";
		echo "Query->".$query."<br>";
		$resPer = $varConexao->pesquisa($query);
		while($rowPer = fetch_assoc_seguro($resPer)){		//LOOP em todos os campos de cada participante do loop acima
			/*$varCampo = new CheckListCampoParticipante();
			$varCampo->inicializaParticipante($participante, $rowPer['id']);
			if($varCampo->valor!=""){
				echo "Valor->".$varCampo->valor."<br>";
			}*/
			$query = "
			select agenda_check.id as campo_id,
					agenda_check_participante_campo.valor
							from agenda_check
										inner join agenda_check_fase on agenda_check_fase.id = agenda_check.periodo
										inner join agenda_check_participante_campo on agenda_check.id = agenda_check_participante_campo.campo
										inner join agenda_check_participante on agenda_check_participante_campo.participante = agenda_check_participante.id and agenda_check_participante.id = ".$participante."
										where agenda_check.projeto = ".$varProjeto->id."
												and agenda_check_fase.item = '".$fase."'
												and agenda_check.id = ".$rowPer['id']."
												order by agenda_check_fase.id, agenda_check.periodo, ordem
			";
			echo "Query->**** ".$query."<br>";
			$resCampo = $varConexao->pesquisa($query);
			$rowCampo = fetch_assoc_seguro($resCampo);
			$varCampo = $rowCampo['valor'];
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colInicial, $linha, utf8_encode($varCampo));
			$colInicial++;
		}
		return true;
	}



	if(isset($_GET['act'])){ $opcAct        = strtoupper($_GET['act']);}else{$opcAct = 'MONITORIA_CHECK_LIST_V2_2';}
	if(isset($_POST['varTipo'])){ $tipoRelatorio = $_POST['varTipo'];}else{$tipoRelatorio = '999';}

	if($opcAct=='EXCEL_NOVO'){
		$arquivoNomeXLS = "check_list_export.xlsx";
		require_once __DIR__."/phpexcel_compat.php";

		$dadosJson = isset($_POST['dados']) ? $_POST['dados'] : '';
		$dados = json_decode($dadosJson, true);

		if(!is_array($dados) || !isset($dados['sheets']) || !is_array($dados['sheets']) || count($dados['sheets']) == 0){
			header('HTTP/1.1 400 Bad Request');
			echo "Dados invalidos para exportacao.";
			exit;
		}

		$objPHPExcel = new PHPExcel();
		$titulosUsados = array();
		$indiceAba = 0;

		foreach($dados['sheets'] as $sheetData){
			$titulo = isset($sheetData['name']) ? trim($sheetData['name']) : ('Aba'.($indiceAba+1));
			$titulo = str_replace(array('\\', '/', '?', '*', '[', ']'), '', $titulo);
			if($titulo === ''){
				$titulo = 'Aba'.($indiceAba+1);
			}
			if(function_exists('mb_substr')){
				if(mb_strlen($titulo, 'UTF-8') > 31){
					$titulo = mb_substr($titulo, 0, 31, 'UTF-8');
				}
			}else if(strlen($titulo) > 31){
				$titulo = substr($titulo, 0, 31);
			}

			$tituloBase = $titulo;
			$sufixo = 1;
			while(isset($titulosUsados[strtolower($titulo)])){
				$extra = '_'.$sufixo;
				if(function_exists('mb_substr')){
					$titulo = mb_substr($tituloBase, 0, max(1, 31 - strlen($extra)), 'UTF-8').$extra;
				}else{
					$titulo = substr($tituloBase, 0, max(1, 31 - strlen($extra))).$extra;
				}
				$sufixo++;
			}
			$titulosUsados[strtolower($titulo)] = true;

			if($indiceAba > 0){
				$objPHPExcel->createSheet();
			}
			$objPHPExcel->setActiveSheetIndex($indiceAba);
			$objPHPExcel->getActiveSheet()->setTitle($titulo);

			$headers = isset($sheetData['headers']) && is_array($sheetData['headers']) ? $sheetData['headers'] : array();
			$rows = isset($sheetData['rows']) && is_array($sheetData['rows']) ? $sheetData['rows'] : array();

			$col = 0;
			foreach($headers as $header){
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $header);
				$col++;
			}

			$colunasComObservacao = array();
			$linha = 2;
			foreach($rows as $row){
				if(!is_array($row)){
					continue;
				}
				$col = 0;
				foreach($row as $valor){
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $linha, $valor);
					if(is_string($valor) && strpos($valor, "\n[ ") !== false){
						$colunasComObservacao[$col] = true;
						$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col, $linha)->getAlignment()->setWrapText(true);
					}
					$col++;
				}
				$linha++;
			}

			foreach($colunasComObservacao as $colObs => $temObs){
				$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colObs)->setWidth(50);
				$ultimaLinha = max(1, $linha - 1);
				$objPHPExcel->getActiveSheet()
					->getStyleByColumnAndRow($colObs, 1, $colObs, $ultimaLinha)
					->getAlignment()
					->setWrapText(true);
			}

			$indiceAba++;
		}

		$objPHPExcel->setActiveSheetIndex(0);
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		$objWriter->save($arquivoNomeXLS);
		echo $arquivoNomeXLS;
		exit;
	}
	if($opcAct=='EA_RESUMO'){
		$conexao = new Conexao();
		$arquivoNome    = "tempCSV.csv";
        $arquivoNomeXLS = date("Ymd")."_resumo_ea.xlsx";
        // Converte para XLS
        require_once __DIR__."/phpexcel_compat.php";
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $rowCount = 1;

		$varParticipante = $_POST['participante'];
		$varGrupo        = $_POST['grupo'];
		$varPeriodo      = $_POST['periodo'];
		$varGravidade    = $_POST['gravidade'];
		$varStatus       = $_POST['status'];


		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,1,"Participante");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,1,formata_utf8_encode("Período"));
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,1,"Evendo Adverso");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,1,formata_utf8_encode("Número Evento"));
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,1,formata_utf8_encode("Início"));
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5,1,formata_utf8_encode("Término"));
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6,1,formata_utf8_encode("Previsibilidade"));
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7,1,formata_utf8_encode("Intensidade"));
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8,1,formata_utf8_encode("Gravidade"));
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9,1,formata_utf8_encode("Causalidade"));
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10,1,formata_utf8_encode("Ação tomada"));
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11,1,formata_utf8_encode("Desfecho"));
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12,1,formata_utf8_encode("Desfecho - Outros"));

		$query = "select
				adv_evento.*
					from adv_evento
						where projeto = ".$_SESSION['projeto'];

		if($varParticipante!=""){
			$query .= " and nro_paciente = ".$varParticipante;
		}
		if($varGrupo!=""){
			$query .= " and grupo = ".$varGrupo;
		}
		if($varPeriodo!=""){
			$query .= " and periodo = ".$varPeriodo;
		}
		if($varGravidade!=""){
			$query .= " and classificado_grave = '".formata_utf8_decode($varGravidade)."'";
		}
		if($varStatus!=""){
			$query .= " and desfecho = '".$varStatus."'";
		}

		$res   = $conexao->pesquisa($query);
		$ind = 1;
		$linha = 2;
		//echo $query;
		while($rowPart = fetch_assoc_seguro($res)){

			$amarelo = "#e9ff00; ";

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,$linha,formata_utf8_encode($rowPart['nro_paciente']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,$linha,formata_utf8_encode($rowPart['periodo']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$linha,formata_utf8_encode($rowPart['nome']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$linha,formata_utf8_encode($rowPart['nro_evento_participante']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,$linha,formata_utf8_encode($rowPart['data_inicio'])." ".$rowPart['hora_inicio']);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5,$linha,formata_utf8_encode($rowPart['data_termino'])." ".$rowPart['hora_fim']);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6,$linha,formata_utf8_encode($rowPart['previsibilidade']));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7,$linha,formata_utf8_encode($rowPart['grau']));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8,$linha,formata_utf8_encode($rowPart['classificado_grave']));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9,$linha,formata_utf8_encode($rowPart['relacao']));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10,$linha,formata_utf8_encode($rowPart['acao_tomada']));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11,$linha,formata_utf8_encode($rowPart['desfecho']));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12,$linha,formata_utf8_encode($rowPart['desfecho_outros']));
			$linha++;
			$objPHPExcel->getActiveSheet()->mergeCells('A'.$linha.':L'.$linha);
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$linha, formata_utf8_encode($rowPart['descricao']));

			$linha++;
		}

        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save($arquivoNomeXLS);
		echo $arquivoNomeXLS;
	}
	if($opcAct=='PENDENCIAS'){
		$conexao = new Conexao();
		$arquivoNome    = "tempCSV.csv";
        $arquivoNomeXLS = date("Ymd")."_pendencias.xlsx";
        // Converte para XLS
        require_once __DIR__."/phpexcel_compat.php";
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $rowCount = 1;

        /*    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('A'.$rowCount, "NOME\nEDUARDO");
            $objPHPExcel->getActiveSheet()->getStyle('a1')->getAlignment()->setWrapText(true);
            $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, "IDADE");
        */


		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,1,"Nro Visita");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,1,utf8_encode("Data da Visita"));
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,1,"Centro");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,1,"Participante");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,1,utf8_encode("Tipo"));
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5,1,utf8_encode("Achado"));
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6,1,utf8_encode("Responsável"));
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7,1,utf8_encode("Data de abertura"));
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8,1,utf8_encode("STATUS"));
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9,1,utf8_encode("Data de Fechamento"));
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10,1,utf8_encode("Criticidade"));
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11,1,utf8_encode("Categoria"));
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12,1,utf8_encode("Ação Tomada"));

		$query = "select
				`agenda_lista_pendencia`.`id` AS `id`,
				`agenda_lista_pendencia`.`visita_data` AS `visita_data`,
				`agenda_lista_pendencia`.`centro` AS `centro`,
				`agenda_lista_pendencia`.`visita` AS `visita`,
				`agenda_lista_pendencia`.`paciente` AS `paciente`,
				`agenda_lista_pendencia`.`cpf` AS `cpf`,
				`agenda_lista_pendencia`.`monitora` AS `monitora`,
				`agenda_lista_pendencia`.`tipo` AS `tipo`,
				`agenda_lista_pendencia`.`achado` AS `achado`,
				`agenda_lista_pendencia`.`acao` AS `acao`,
				`agenda_lista_pendencia`.`data_abertura` AS `data_abertura`,
				`agenda_lista_pendencia`.`status` AS `status`,
				`agenda_lista_pendencia`.`data_fechamento` AS `data_fechamento`,
				`agenda_lista_pendencia`.`resolvido_por` AS `resolvido_por`,
				`agenda_lista_pendencia`.`log_usuario` AS `log_usuario`,
				`agenda_lista_pendencia`.`responsavel` AS `responsavel`,
				`agenda_lista_pendencia`.`projeto` AS `projeto`,
				concat(substr(`agenda_lista_pendencia`.`data_abertura`,7,4),substr(`agenda_lista_pendencia`.`data_abertura`,4,2),substr(`agenda_lista_pendencia`.`data_abertura`,1,2)) AS `data_abertura_lis`,
				`usu_usuario`.`nome` AS `monitora_nome`, agenda_lista_pendencia.*
					from (`agenda_lista_pendencia` left join `usu_usuario` on(`agenda_lista_pendencia`.`monitora` = `usu_usuario`.`id`)) ";
		$query .= " where projeto = ".$_SESSION['projeto']." and agenda_lista_pendencia.id > 0 " . $_GET['filtro'];
		$res   = $conexao->pesquisa($query);
		$ind = 1;
		$linha = 2;
		//echo $query;
		while($rowPart = fetch_assoc_seguro($res)){

			$amarelo = "#e9ff00; ";

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,$linha,utf8_encode($rowPart['visita']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,$linha,utf8_encode($rowPart['visita_data']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$linha,utf8_encode($rowPart['centro']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$linha,utf8_encode($rowPart['paciente']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,$linha,utf8_encode($rowPart['tipo']));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5,$linha,utf8_encode($rowPart['achado']));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6,$linha,utf8_encode($rowPart['responsavel']));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7,$linha,utf8_encode($rowPart['data_abertura']));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8,$linha,utf8_encode($rowPart['status']));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9,$linha,utf8_encode($rowPart['data_fechamento']));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10,$linha,utf8_encode($rowPart['criticidade']));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11,$linha,utf8_encode($rowPart['categoria']));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12,$linha,utf8_encode($rowPart['acao']));

			$linha++;
		}

        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save($arquivoNomeXLS);
		echo $arquivoNomeXLS;
	}
    if($opcAct=='MONITORIA_CHECK_LIST'){

		$varSexo         = $_POST['sexo'];
		$varStatus       = $_POST['status'];
		$varPeriodo      = $_POST['periodo'];



        $arquivoNomeXLS = "export.xlsx";


		//$tipoRelatorio = '999';


        // Converte para XLS
        require_once __DIR__."/phpexcel_compat.php";

		if($tipoRelatorio==999){
			//Aqui é a exportação completa, de todos os dados, separados por abas
			$objPHPExcel = new PHPExcel();

			//Removo todas as abas existentes
			$sheetCount = $objPHPExcel->getSheetCount();
			for($i=0; $i<$sheetCount; $i++){
				$objPHPExcel->removeSheetByIndex(0);
			}

			if($varCheckList->temEspecifico()==1){
				$query = "select agenda_check_fase.* from agenda_check_fase where projeto = " . $_SESSION['projeto'];
			}else{
				$query = "select agenda_check_fase.* from agenda_check_fase where projeto = 0";
			}
			echo "QUERY->".$query."<br>";
			$resQtd = $varConexao->pesquisa($query);
			while($rowQtd = fetch_assoc_seguro($resQtd)){
				//Crio a aba nova e nomeio
				$sheetCount = $objPHPExcel->getSheetCount();
				$objPHPExcel->createSheet();
				$objPHPExcel->setActiveSheetIndex($sheetCount);

				$objPHPExcel->getActiveSheet()
					->getStyle('B:B')
					->getNumberFormat()
					->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);


				$objPHPExcel->getActiveSheet()->setTitle(formata_utf8_encode($rowQtd['item']));

				$varFase = $rowQtd['id'];

				//Fazer o loop em todos os itens dessa fase
				$queryPer = "select agenda_check.* from agenda_check
								inner join agenda_check_fase on agenda_check_fase.id = agenda_check.periodo ";
								if($varCheckList->temEspecifico()==1){
									$queryPer .= " where agenda_check.projeto = " . $_SESSION['projeto'];
								}else{
									$queryPer .= " where agenda_check.projeto = 0";
								}
								$queryPer .= "
									and periodo = ".$varFase;

				$queryPer .= "
									and agenda_check.exporta = 1
									order by agenda_check_fase.id,
												periodo,
												ordem";

				$resPer = $varConexao->pesquisa($queryPer);
				$colNumber = 1;


				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,1,"Participante");
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,1,utf8_encode("Código"));
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,1,"Status");
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,1,"Grupo");
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,1,utf8_encode("Período"));

				$colNumber = 5;

				while($rowPer = fetch_assoc_seguro($resPer)){
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNumber,1,formata_utf8_encode($rowPer['nome']));
					$colNumber++;
				}

				if(isset($_POST['grupo'])){$varGrupo = $_POST['grupo'];}else{$varGrupo = "TODOS";}


				$query = "select
								*
									from agenda_check_participante
								where projeto = '".$_SESSION['projeto']."'";
				if($varGrupo!="TODOS"){
					$query .= " and grupo = '".$varGrupo."'";
				}
				if($varPeriodo!="TODOS"){
					$query .= " and periodo = '".$varPeriodo."'";
				}
				$query .= "
									order by cast(numero as INTEGER) ";
				$resPart = $varConexao->pesquisa($query);
				$linha = 2;


				while($rowPart = fetch_assoc_seguro($resPart)){
					$amarelo = "#e9ff00; ";


					//Verificando se tem filtro de sexo
					if($varSexo!=""){
						//Pear o sexo do participante
						$idParticipante = $rowPart['id'];
						if($varCheckList->temEspecifico()==1){
							$query = "SELECT id FROM `agenda_check` where nome = 'Sexo' and projeto = ".$varProjeto->id;
							$resSexo = $varConexao->pesquisa($query);
							$rowSexo = fetch_assoc_seguro($resSexo);
							$varIdexo = $rowSexo['id'];

						}else{
							$query = "SELECT id FROM `agenda_check` where nome = 'Sexo' and projeto = 0";
							$resSexo = $varConexao->pesquisa($query);
							$rowSexo = fetch_assoc_seguro($resSexo);
							$varIdexo = $rowSexo['id'];
						}
						$query = "SELECT valor FROM `agenda_check_participante_campo` where participante = ".$idParticipante." and campo = ".$varIdexo;
						$resValor = $varConexao->pesquisa($query);
						$rowValor = fetch_assoc_seguro($resValor);
						$valorSexo = $rowValor['valor'];
						if($valorSexo!=$varSexo){
							continue;
						}
					}




					$varCelula = "B".$linha;

					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,$linha,formata_utf8_encode($rowPart['numero']));
					//$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,$linha,formata_utf8_encode($rowPart['codigo']));
					$objPHPExcel->getActiveSheet()->setCellValueExplicit($varCelula,formata_utf8_encode($rowPart['codigo']),PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$linha,formata_utf8_encode(retornaStatus($rowPart['status'])));
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$linha,formata_utf8_encode($rowPart['grupo']));
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,$linha,formata_utf8_encode($rowPart['periodo']));





					$resPer = $varConexao->pesquisa($queryPer);
					$colNumber = 5;
					while($rowPer = fetch_assoc_seguro($resPer)){
						$query = "SELECT agenda_check_participante_campo.id as campo_id,
									agenda_check_participante_campo.participante,
									agenda_check_participante_campo.campo,
									agenda_check_participante_campo.valor,
									agenda_check_participante_campo.observacoes,
									agenda_check.nome,
									agenda_check.mascara,
									agenda_check.opcoes
										FROM agenda_check_participante_campo
											inner join agenda_check on agenda_check_participante_campo.campo = agenda_check.id
											where agenda_check_participante_campo.participante = '".$rowPart['id']."'
												and agenda_check.exporta = 1
											and agenda_check_participante_campo.campo = '".$rowPer['id']."';";
						$resCampo = $varConexao->pesquisa($query);
						$rowCampo = fetch_assoc_seguro($resCampo);
						$valor      = $rowCampo['valor'];
						if($rowCampo['observacoes']!=""){
							$valor .= "\n[" . $rowCampo['observacoes'] . "]";
						}
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNumber,$linha,utf8_encode($valor));
						$objPHPExcel->getActiveSheet()->getStyle('a1')->getAlignment()->setWrapText(true);
						$colNumber++;
					}

					$linha++;
				}

			}



		}else{

			$objPHPExcel = new PHPExcel();
			$objPHPExcel->setActiveSheetIndex(0);
			$rowCount = 1;

			/*    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('A'.$rowCount, "NOME\nEDUARDO");
				$objPHPExcel->getActiveSheet()->getStyle('a1')->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, "IDADE");
			*/



			$projeto 	= $_SESSION['projeto'];
			$query 		= "select
							id
								from agenda_check_fase
									where projeto = " . $projeto . "
										order by agenda_check_fase.id, periodo, ordem";
			$resQtd		= $varConexao->pesquisa($query);
			if(num_rows_seguro($resQtd)==0){$projetoEspecifico = false;}else{$projetoEspecifico=true;}


			$varFase         = $_POST['fase'];
			$varGrupo        = $_POST['grupo'];
			$varPeriodo      = $_POST['periodo'];
			$varParticipante = $_POST['participante'];
			$varTipo         = $_POST['varTipo'];



			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,1,"Participante");
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,1,utf8_encode("Código"));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,1,"Status");
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,1,"Grupo");
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,1,utf8_encode("Período"));

			if($varCheckList->temEspecifico()==1){
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
			//echo $query;

			$resPer = $varConexao->pesquisa($query);
			$colNumber = 5;
			while($rowPer = fetch_assoc_seguro($resPer)){
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNumber,1,utf8_encode($rowPer['nome']));
				$colNumber++;
			}


			$query = "select * from agenda_check_participante where projeto = '".$_SESSION['projeto']."' ";
			if($varParticipante!=""){$query .= " and numero = '$varParticipante' ";}
			if($varGrupo!="TODOS"){$query .= " and ( (grupo = '') or (grupo = '$varGrupo') ) ";}
			if($varPeriodo!="TODOS"){$query .= " and ( (periodo = '') or (periodo = '$varPeriodo') ) ";}

			$query .= " order by cast(numero as INTEGER) ";
			$resPart = $varConexao->pesquisa($query);
			$linha = 2;


			$linhaCSV = "";
			while($rowPart = fetch_assoc_seguro($resPart)){
				$amarelo = "#e9ff00; ";

				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,$linha,utf8_encode($rowPart['numero']));
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,$linha,utf8_encode($rowPart['codigo']));
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$linha,utf8_encode($rowPart['status']));
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$linha,utf8_encode($rowPart['grupo']));
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,$linha,utf8_encode($rowPart['periodo']));


				if($varCheckList->temEspecifico()==1){
					if($varFase=="TODOS"){
						$query = "select agenda_check.* from agenda_check
									inner join agenda_check_fase on agenda_check_fase.id = agenda_check.periodo
										where agenda_check.projeto = $projeto
												";
					}else{
						$query = "select agenda_check.* from agenda_check
									inner join agenda_check_fase on agenda_check_fase.id = agenda_check.periodo
										where agenda_check.projeto = $projeto
											and periodo = '".$varFase."'
												";
					}
				}else{
					if($varFase=="TODOS"){
						$query = "select * from agenda_check
										inner join agenda_check_fase on agenda_check_fase.id = agenda_check.periodo
										where agenda_check.projeto = 0
											";
					}else{
						$query = "select * from agenda_check
									inner join agenda_check_fase on agenda_check_fase.id = agenda_check.periodo
									where agenda_check.projeto = 0 and periodo = '".$varFase."'
										";
					}
				}

				if($varTipo==0){
					$query .= " and agenda_check.exporta = 1 ";
				}
				$query .= " order by agenda_check_fase.id, periodo, ordem";

				$resPer = $varConexao->pesquisa($query);
				$colNumber = 5;
				while($rowPer = fetch_assoc_seguro($resPer)){
					$query = "SELECT agenda_check_participante_campo.id as campo_id,
								agenda_check_participante_campo.participante,
								agenda_check_participante_campo.campo,
								agenda_check_participante_campo.valor,
								agenda_check.nome,
								agenda_check.mascara,
								agenda_check.opcoes
									FROM agenda_check_participante_campo
										inner join agenda_check on agenda_check_participante_campo.campo = agenda_check.id
										where agenda_check_participante_campo.participante = '".$rowPart['id']."'
										and agenda_check_participante_campo.campo = '".$rowPer['id']."';";

					$resCampo = $varConexao->pesquisa($query);
					$rowCampo = fetch_assoc_seguro($resCampo);
					$valor      = $rowCampo['valor'];
					if($varTipo==1){
						//Pegar ultima atualizacao do campo
						$query  = "SELECT * FROM `log_alteracoes` where tabela = 'agenda_check_participante_campo' and id_campo = ".$rowCampo['campo_id']." order by id desc limit 1; ";
						$resLog = $varConexao->pesquisa($query);
						if(num_rows_seguro($resLog)>0){
							$rowLog = fetch_assoc_seguro($resLog);
							$valor .= "\n" . $rowLog['usuario_nome']. "\n" . $rowLog['data_hora'];
						}
					}
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNumber,$linha,utf8_encode($valor));
					$objPHPExcel->getActiveSheet()->getStyle('a1')->getAlignment()->setWrapText(true);
					$colNumber++;
				}
				$linha++;
			}

		}
		$objPHPExcel->setActiveSheetIndex(0);

        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save($arquivoNomeXLS);

    }
	if($opcAct=='MONITORIA_CHECK_LIST_V2_2'){
		include_once __DIR__."/../class/projeto.class.php";
		$varProjeto = new Projeto($_SESSION['projeto']);
		$varConexao = new Conexao();

		$projeto 	= $_SESSION['projeto'];
		$query 		= "select id from agenda_check_fase where projeto = " . $projeto;
		$resQtd		= $varConexao->pesquisa($query);
		if(num_rows_seguro($resQtd)==0){$projetoEspecifico = false;}else{$projetoEspecifico=true;}

		$varGrupo        = $_POST['grupo'];
		$varParticipante = $_POST['participante'];
		$varSexo         = $_POST['sexo'];
		$varFase         = $_POST['fase'];
		$varPeriodo      = $_POST['periodo'];
		$varCodigo       = $_POST['codigo'];
		$varStatus       = $_POST['status'];


		$arquivoNomeXLS = date("YmdHis")."_check_list_".str_replace(array("/", "\\"), "-", $varProjeto->numero).".xlsx"; // "/" no numero (ex.: MON058/2026) virava subpasta inexistente

		$fases = array("Inicial", "Follow-up", "Dieta", "Pós");

        require_once __DIR__."/phpexcel_compat.php";
		$objPHPExcel = new PHPExcel();
		//Removo todas as abas existentes
		$sheetCount = $objPHPExcel->getSheetCount();
		for($i=0; $i<$sheetCount; $i++){
			$objPHPExcel->removeSheetByIndex(0);
		}
		$sheetCount = $objPHPExcel->getSheetCount();

		$objPHPExcel->createSheet();
		$objPHPExcel->setActiveSheetIndex($sheetCount);
		$objPHPExcel->getActiveSheet()
			->getStyle('B:B')
			->getNumberFormat()
			->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		$objPHPExcel->getActiveSheet()->setTitle(formata_utf8_encode("PRE"));
		$sheetCount++;

		$query 		= "select
							id
								from agenda_check_fase
							where
								item = 'Inicial'
								and projeto = " . $projeto;
		$resFase    = $varConexao->pesquisa($query);
		$rowFase    = fetch_assoc_seguro($resFase);
		$varFase    = $rowFase['id'];

		$linha     = 1;
		$query = "select agenda_check.*
						from agenda_check
							inner join agenda_check_fase on agenda_check_fase.id = agenda_check.periodo
								where agenda_check.projeto = $projeto
									and periodo = '".$varFase."'
										order by agenda_check_fase.id, periodo, ordem";
		$resPer = $varConexao->pesquisa($query);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,$linha,utf8_encode('Participante'));
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,$linha,utf8_encode('Código'));
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$linha,utf8_encode('Status'));
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$linha,utf8_encode('Grupo'));
		$colNumber = 4;
		while($rowPer = fetch_assoc_seguro($resPer)){
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNumber,$linha,utf8_encode($rowPer['nome']));
			$colNumber++;
		}

		for($i=1;$i<=$varProjeto->periodos;$i++){
			$query 		= "select
								id
									from agenda_check_fase
								where
									item = 'Follow-up'
									and projeto = " . $projeto;
			$resFase    = $varConexao->pesquisa($query);
			$rowFase    = fetch_assoc_seguro($resFase);
			$varFase    = $rowFase['id'];

			$query = "select agenda_check.*
							from agenda_check
								inner join agenda_check_fase on agenda_check_fase.id = agenda_check.periodo
									where agenda_check.projeto = $projeto
										and periodo = '".$varFase."'
											order by agenda_check_fase.id, periodo, ordem";
			$resPer = $varConexao->pesquisa($query);
			$objPHPExcel->createSheet();
			$objPHPExcel->setActiveSheetIndex($sheetCount);
			$objPHPExcel->getActiveSheet()
				->getStyle('B:B')
				->getNumberFormat()
				->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
			$objPHPExcel->getActiveSheet()->setTitle(formata_utf8_encode("P".$i."-FU"));
			$sheetCount++;
			$linha     = 1;
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,$linha,utf8_encode('Participante'));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,$linha,utf8_encode('Código'));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$linha,utf8_encode('Status'));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$linha,utf8_encode('Grupo'));
			$colNumber = 4;
			while($rowPer = fetch_assoc_seguro($resPer)){
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNumber,$linha,utf8_encode($rowPer['nome']));
				$objPHPExcel->getActiveSheet()->getStyle('a1')->getAlignment()->setWrapText(true);
				$colNumber++;
			}
			$query 		= "select
								id
									from agenda_check_fase
								where
									item = 'Dieta'
									and projeto = " . $projeto;
			$resFase    = $varConexao->pesquisa($query);
			$rowFase    = fetch_assoc_seguro($resFase);
			$varFase    = $rowFase['id'];

			$query = "select agenda_check.*
							from agenda_check
								inner join agenda_check_fase on agenda_check_fase.id = agenda_check.periodo
									where agenda_check.projeto = $projeto
										and periodo = '".$varFase."'
											order by agenda_check_fase.id, periodo, ordem";
			$resPer = $varConexao->pesquisa($query);
			$objPHPExcel->createSheet();
			$objPHPExcel->setActiveSheetIndex($sheetCount);
			$objPHPExcel->getActiveSheet()
				->getStyle('B:B')
				->getNumberFormat()
				->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
			$objPHPExcel->getActiveSheet()->setTitle(formata_utf8_encode("P".$i."-DIETA"));
			$sheetCount++;
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,$linha,utf8_encode('Participante'));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,$linha,utf8_encode('Código'));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$linha,utf8_encode('Status'));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$linha,utf8_encode('Grupo'));
			$colNumber = 4;
			$linha     = 1;
			while($rowPer = fetch_assoc_seguro($resPer)){
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNumber,$linha,utf8_encode($rowPer['nome']));
				$objPHPExcel->getActiveSheet()->getStyle('a1')->getAlignment()->setWrapText(true);
				$colNumber++;
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
		$rowFase    = fetch_assoc_seguro($resFase);
		$varFase    = $rowFase['id'];

		$query = "select agenda_check.*
						from agenda_check
							inner join agenda_check_fase on agenda_check_fase.id = agenda_check.periodo
								where agenda_check.projeto = $projeto
									and periodo = '".$varFase."'
										order by agenda_check_fase.id, periodo, ordem";
		$resPer = $varConexao->pesquisa($query);
		$objPHPExcel->createSheet();
		$objPHPExcel->setActiveSheetIndex($sheetCount);
		$objPHPExcel->getActiveSheet()
			->getStyle('B:B')
			->getNumberFormat()
			->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		$objPHPExcel->getActiveSheet()->setTitle(formata_utf8_encode("PÓS"));
		$sheetCount++;
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,$linha,utf8_encode('Participante'));
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,$linha,utf8_encode('Código'));
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$linha,utf8_encode('Status'));
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$linha,utf8_encode('Grupo'));
		$colNumber = 4;
		$linha     = 1;
		while($rowPer = fetch_assoc_seguro($resPer)){
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNumber,$linha,utf8_encode($rowPer['nome']));
			$objPHPExcel->getActiveSheet()->getStyle('a1')->getAlignment()->setWrapText(true);
			$colNumber++;
		}



		$query = "select * from agenda_check_participante where projeto = " . $projeto . " and periodo = 1 ";
		/*if($varParticipante!=""){$query .= " and numero = '$varParticipante' ";}
		if($varGrupo!="TODOS"){
			if($varGrupo!=""){
				$query .= " and ( (grupo = '$varGrupo') ) ";
			}else{
				$query .= " and ( (grupo = '') ) ";
			}
		}
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
		}*/
		$query .= " order by cast(numero as INTEGER) ";


		$resPart = $varConexao->pesquisa($query);
		$linha = 2;




		while($rowPart = fetch_assoc_seguro($resPart)){		//LOOP em todos os participantes
			$indiceSheet = 0;

			$varIdParticipante = $rowPart['id'];
			$varHash           = $rowPart['hash'];
			$varCodigo         = $rowPart['codigo'];
			$varCodigoPart     = $rowPart['codigo'];
			$varNumeroPart     = $rowPart['numero'];
			$varStatusPart     = $rowPart['status'];
			$varGrupoPart      = $rowPart['grupo'];
			$amarelo           = "#e9ff00; ";


			//PRE
			$objPHPExcel->setActiveSheetIndex($indiceSheet);
			$objPHPExcel->getActiveSheet();

			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,$linha,utf8_encode($rowPart['numero']));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,$linha,utf8_encode($rowPart['codigo']));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$linha,utf8_encode($rowPart['status']));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$linha,utf8_encode($rowPart['grupo']));
			$colNumber = 4;

			$retorno = montaFase("Inicial", 1, $linha, $varIdParticipante, 4, $objPHPExcel);
			$indiceSheet++;

			for($i=1;$i<=$varProjeto->periodos;$i++){
				//Pegar ID participante
				$query = "select * from agenda_check_participante where projeto = " . $projeto . " and periodo = " . $i . " and hash = '$varHash' and codigo <> ''order by cast(numero as INTEGER) ";
				$resParticipante = $varConexao->pesquisa($query);
				mysqli_data_seek($resParticipante, $i);
				$rowParticipante = fetch_assoc_seguro($resParticipante);
				$idParticipante = "";
				$idParticipante = $rowParticipante['id'];

				$objPHPExcel->setActiveSheetIndex($indiceSheet);
				$objPHPExcel->getActiveSheet();
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,$linha,utf8_encode($rowPart['numero']));
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,$linha,utf8_encode($rowPart['codigo']));
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$linha,utf8_encode($rowPart['status']));
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$linha,utf8_encode($rowPart['grupo']));
				// FASE FOLLOW-UP
				$retorno = montaFase("Follow-up", $i, $linha, $idParticipante, 4, $objPHPExcel);
				$indiceSheet++;

				// FASE DIETA
				$objPHPExcel->setActiveSheetIndex($indiceSheet);
				$objPHPExcel->getActiveSheet();
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,$linha,utf8_encode($rowPart['numero']));
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,$linha,utf8_encode($rowPart['codigo']));
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$linha,utf8_encode($rowPart['status']));
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$linha,utf8_encode($rowPart['grupo']));
				$retorno = montaFase("Dieta", $i, $linha, $idParticipante, 4, $objPHPExcel);
				$indiceSheet++;

			}

			//POS ESTUDO

			$query = "select * from agenda_check_participante where projeto = " . $projeto . " and periodo = ".$varProjeto->periodos." and hash = '$varHash' and codigo = '$varCodigo' order by cast(numero as INTEGER) ";

			$resParticipante = $varConexao->pesquisa($query);
			$rowParticipante = fetch_assoc_seguro($resParticipante);
			$idParticipante = $rowParticipante['id'];
			$objPHPExcel->setActiveSheetIndex(5);
			$objPHPExcel->getActiveSheet();
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,$linha,utf8_encode($rowPart['numero']));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,$linha,utf8_encode($rowPart['codigo']));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$linha,utf8_encode($rowPart['status']));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$linha,utf8_encode($rowPart['grupo']));

			$retorno = montaFase("Pós", 1, $linha, $idParticipante, 4, $objPHPExcel);



		/*	$html .= '	<tr>

							// FASE INICIAL
							$html .= '<th class="divisao_fase_td"><span>PRE</span></th>';
							$html .=




							// FASE PÓS


			$html .= '
						<td style="font-size: 12px !important;">
							<center>
								<div class="btn-group btn-group">
								<button type="button" class="btn btn-default" onclick="javascript: salvaPart('.$linha.')"><i class="fa fa-floppy-o"></i></button>
								</div>
							</center>
						</td>
			</tr>';

			*/

			$linha++;
		}






		$objPHPExcel->setActiveSheetIndex(0);
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		$objWriter->save($arquivoNomeXLS);

		echo "FIM";

	}
	if($opcAct=='MONITORIA_CHECK_LIST_V2'){
		$varSexo         = $_POST['sexo'];
		$varStatus       = $_POST['status'];
		if(isset($_POST['grupo'])){$varGrupo = $_POST['grupo'];}else{$varGrupo = "TODOS";}
		$varParticipante = $_POST['participante'];

        $arquivoNomeXLS = "export.xlsx";

        // Converte para XLS
        require_once __DIR__."/phpexcel_compat.php";

		if($tipoRelatorio==999){
			//Aqui é a exportação completa, de todos os dados, separados por abas
			$objPHPExcel = new PHPExcel();

			//Removo todas as abas existentes
			$sheetCount = $objPHPExcel->getSheetCount();
			for($i=0; $i<$sheetCount; $i++){
				$objPHPExcel->removeSheetByIndex(0);
			}
			$sheetCount = $objPHPExcel->getSheetCount();

			//Participantes
			$queryParticipantes = "";
			$queryParticipantes_INI = "select
										*
											from agenda_check_participante
												where projeto = '".$_SESSION['projeto']."'";
			if($varParticipante!=""){$queryParticipantes .= " and numero = '$varParticipante' ";}
			if($varGrupo!="TODOS"){$queryParticipantes .= " and ( (grupo = '') or (grupo = '$varGrupo') ) ";}
			$queryParticipantesFinal .= " and periodo = 1 order by cast(numero as INTEGER) ";




			$objPHPExcel->createSheet();
			$objPHPExcel->setActiveSheetIndex($sheetCount);
			$objPHPExcel->getActiveSheet()
				->getStyle('B:B')
				->getNumberFormat()
				->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
			$objPHPExcel->getActiveSheet()->setTitle(formata_utf8_encode("PRE"));
			$sheetCount++;
			$linha = 2;

			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,1,utf8_encode("Participante"));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,1,utf8_encode("Código"));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,1,utf8_encode("Status"));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,1,utf8_encode("Grupo"));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,1,utf8_encode("Período"));

			//Colunas do PRE
			//Pegar ID da fase Inicial para pegar os campos
			$queryFase = "select id from agenda_check_fase where projeto = " . $_SESSION['projeto'] . " and item = 'Inicial'";
			$resFase = $varConexao->pesquisa($queryFase);
			$rowFase = fetch_assoc_seguro($resFase);
			$varFase = $rowFase['id'];

			$queryCampos = "select agenda_check.*
							from agenda_check
						inner join agenda_check_fase on agenda_check_fase.id = agenda_check.periodo
							 where agenda_check.projeto = " . $_SESSION['projeto'] . "
							and periodo = '".$varFase."'
								and agenda_check.exporta = 1
								order by agenda_check_fase.id,
											periodo,
											ordem";
			$resCampos = $varConexao->pesquisa($queryCampos);
			$colNumber = 5;
			$linha     = 2;

			while($rowCampo = fetch_assoc_seguro($resCampos)){
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNumber,1,utf8_encode($rowCampo['nome']));
				$colNumber++;
			}

			$resPart = $varConexao->pesquisa($queryParticipantes_INI." " . $queryParticipantes . " " . $queryParticipantesFinal);
			while($rowPart = fetch_assoc_seguro($resPart)){
				$amarelo = "#e9ff00; ";
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,$linha,utf8_encode($rowPart['numero']));
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,$linha,utf8_encode($rowPart['codigo']));
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$linha,utf8_encode($rowPart['status']));
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$linha,utf8_encode($rowPart['grupo']));
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,$linha,utf8_encode($rowPart['periodo']));

				//campos
				$colNumber = 5;
				$resCampos = $varConexao->pesquisa($queryCampos);
				while($rowCampo = fetch_assoc_seguro($resCampos)){
					$query = "SELECT
								agenda_check_participante_campo.valor,
								agenda_check_participante_campo.observacoes
									FROM agenda_check_participante_campo
										inner join agenda_check on agenda_check_participante_campo.campo = agenda_check.id
										where agenda_check_participante_campo.participante = '".$rowPart['id']."'
											and agenda_check.exporta = 1
										and agenda_check_participante_campo.campo = '".$rowCampo['id']."';";
					$resCampo = $varConexao->pesquisa($query);
					$rowCampo = fetch_assoc_seguro($resCampo);
					$valor      = $rowCampo['valor'];
					if($rowCampo['observacoes']!=""){
						$valor .= "\n[" . $rowCampo['observacoes'] . "]";
					}
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNumber,$linha,utf8_encode($valor));
					$objPHPExcel->getActiveSheet()->getStyle('a1')->getAlignment()->setWrapText(true);
					$colNumber++;
				}
				$linha++;
			}


			for($iPeriodo=1;$iPeriodo<=$varProjeto->periodos;$iPeriodo++){
				$objPHPExcel->createSheet();
				$objPHPExcel->setActiveSheetIndex($sheetCount);
				$objPHPExcel->getActiveSheet()
					->getStyle('B:B')
					->getNumberFormat()
					->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
				$objPHPExcel->getActiveSheet()->setTitle(formata_utf8_encode("P".$iPeriodo."-FU"));
				$sheetCount++;
				$linha = 2;
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,1,utf8_encode("Participante"));
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,1,utf8_encode("Código"));
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,1,utf8_encode("Status"));
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,1,utf8_encode("Grupo"));
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,1,utf8_encode("Período"));


				//Colunas do PRE
				//Pegar ID da fase Inicial para pegar os campos
				$queryFase = "select id from agenda_check_fase where projeto = " . $_SESSION['projeto'] . " and item = 'Follow-up'";
				$resFase = $varConexao->pesquisa($queryFase);
				$rowFase = fetch_assoc_seguro($resFase);
				$varFase = $rowFase['id'];

				$queryCampos = "select agenda_check.*
								from agenda_check
							inner join agenda_check_fase on agenda_check_fase.id = agenda_check.periodo
								where agenda_check.projeto = " . $_SESSION['projeto'] . "
								and periodo = '".$varFase."'
									and agenda_check.exporta = 1
									order by agenda_check_fase.id,
												periodo,
												ordem";
				$resCampos = $varConexao->pesquisa($queryCampos);
				$colNumber = 5;
				$linha     = 2;

				while($rowCampo = fetch_assoc_seguro($resCampos)){
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNumber,1,utf8_encode($rowCampo['nome']));
					$colNumber++;
				}

				$queryParticipantesFinal = " and periodo = ".$iPeriodo;
				$queryParticipantesFinal .= " order by cast(numero as INTEGER) ";
				$resPart = $varConexao->pesquisa($queryParticipantes_INI." " . $queryParticipantes . " " . $queryParticipantesFinal);
				while($rowPart = fetch_assoc_seguro($resPart)){
					$amarelo = "#e9ff00; ";
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,$linha,utf8_encode($rowPart['numero']));
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,$linha,utf8_encode($rowPart['codigo']));
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$linha,utf8_encode($rowPart['status']));
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$linha,utf8_encode($rowPart['grupo']));
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,$linha,utf8_encode($rowPart['periodo']));

					//campos
					$colNumber = 5;
					$resCampos = $varConexao->pesquisa($queryCampos);
					while($rowCampo = fetch_assoc_seguro($resCampos)){
						$query = "SELECT
									agenda_check_participante_campo.valor,
									agenda_check_participante_campo.observacoes
										FROM agenda_check_participante_campo
											inner join agenda_check on agenda_check_participante_campo.campo = agenda_check.id
											where agenda_check_participante_campo.participante = '".$rowPart['id']."'
												and agenda_check.exporta = 1
											and agenda_check_participante_campo.campo = '".$rowCampo['id']."';";
						$resCampo = $varConexao->pesquisa($query);
						$rowCampo = fetch_assoc_seguro($resCampo);
						$valor      = $rowCampo['valor'];
						if($rowCampo['observacoes']!=""){
							$valor .= "\n[" . $rowCampo['observacoes'] . "]";
						}
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNumber,$linha,utf8_encode($valor));
						$objPHPExcel->getActiveSheet()->getStyle('a1')->getAlignment()->setWrapText(true);
						$colNumber++;
					}
					$linha++;
				}



				$objPHPExcel->createSheet();
				$objPHPExcel->setActiveSheetIndex($sheetCount);
				$objPHPExcel->getActiveSheet()
					->getStyle('B:B')
					->getNumberFormat()
					->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
				$objPHPExcel->getActiveSheet()->setTitle(formata_utf8_encode("P".$iPeriodo."-DIETA"));
				$sheetCount++;
				$linha = 2;
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,1,utf8_encode("Participante"));
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,1,utf8_encode("Código"));
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,1,utf8_encode("Status"));
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,1,utf8_encode("Grupo"));
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,1,utf8_encode("Período"));


				//Colunas do PRE
				//Pegar ID da fase Inicial para pegar os campos
				$queryFase = "select id from agenda_check_fase where projeto = " . $_SESSION['projeto'] . " and item = 'Dieta'";
				$resFase = $varConexao->pesquisa($queryFase);
				$rowFase = fetch_assoc_seguro($resFase);
				$varFase = $rowFase['id'];

				$queryCampos = "select agenda_check.*
								from agenda_check
							inner join agenda_check_fase on agenda_check_fase.id = agenda_check.periodo
								where agenda_check.projeto = " . $_SESSION['projeto'] . "
								and periodo = '".$varFase."'
									and agenda_check.exporta = 1
									order by agenda_check_fase.id,
												periodo,
												ordem";
				$resCampos = $varConexao->pesquisa($queryCampos);
				$colNumber = 5;
				$linha     = 2;

				while($rowCampo = fetch_assoc_seguro($resCampos)){
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNumber,1,utf8_encode($rowCampo['nome']));
					$colNumber++;
				}

				$queryParticipantesFinal = " and periodo = ".$iPeriodo;
				$queryParticipantesFinal .= " order by cast(numero as INTEGER) ";
				$resPart = $varConexao->pesquisa($queryParticipantes_INI." " . $queryParticipantes . " " . $queryParticipantesFinal);
				while($rowPart = fetch_assoc_seguro($resPart)){
					$amarelo = "#e9ff00; ";
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,$linha,utf8_encode($rowPart['numero']));
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,$linha,utf8_encode($rowPart['codigo']));
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$linha,utf8_encode($rowPart['status']));
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$linha,utf8_encode($rowPart['grupo']));
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,$linha,utf8_encode($rowPart['periodo']));

					//campos
					$colNumber = 5;
					$resCampos = $varConexao->pesquisa($queryCampos);
					while($rowCampo = fetch_assoc_seguro($resCampos)){
						$query = "SELECT
									agenda_check_participante_campo.valor,
									agenda_check_participante_campo.observacoes
										FROM agenda_check_participante_campo
											inner join agenda_check on agenda_check_participante_campo.campo = agenda_check.id
											where agenda_check_participante_campo.participante = '".$rowPart['id']."'
												and agenda_check.exporta = 1
											and agenda_check_participante_campo.campo = '".$rowCampo['id']."';";
						$resCampo = $varConexao->pesquisa($query);
						$rowCampo = fetch_assoc_seguro($resCampo);
						$valor      = $rowCampo['valor'];
						if($rowCampo['observacoes']!=""){
							$valor .= "\n[" . $rowCampo['observacoes'] . "]";
						}
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNumber,$linha,utf8_encode($valor));
						$objPHPExcel->getActiveSheet()->getStyle('a1')->getAlignment()->setWrapText(true);
						$colNumber++;
					}
					$linha++;
				}
			}


			$objPHPExcel->createSheet();
			$objPHPExcel->setActiveSheetIndex($sheetCount);
			$objPHPExcel->getActiveSheet()
				->getStyle('B:B')
				->getNumberFormat()
				->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
			$objPHPExcel->getActiveSheet()->setTitle(formata_utf8_encode("PÓS"));
			$sheetCount++;
			$linha = 2;

			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,1,utf8_encode("Participante"));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,1,utf8_encode("Código"));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,1,utf8_encode("Status"));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,1,utf8_encode("Grupo"));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,1,utf8_encode("Período"));

			//Colunas do PRE
			//Pegar ID da fase Inicial para pegar os campos
			$queryFase = "select id from agenda_check_fase where projeto = " . $_SESSION['projeto'] . " and item = 'Pós'";
			$resFase = $varConexao->pesquisa($queryFase);
			$rowFase = fetch_assoc_seguro($resFase);
			$varFase = $rowFase['id'];

			$queryCampos = "select agenda_check.*
							from agenda_check
						inner join agenda_check_fase on agenda_check_fase.id = agenda_check.periodo
							 where agenda_check.projeto = " . $_SESSION['projeto'] . "
							and periodo = '".$varFase."'
								and agenda_check.exporta = 1
								order by agenda_check_fase.id,
											periodo,
											ordem";
			$resCampos = $varConexao->pesquisa($queryCampos);
			$colNumber = 5;
			$linha     = 2;

			while($rowCampo = fetch_assoc_seguro($resCampos)){
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNumber,1,utf8_encode($rowCampo['nome']));
				$colNumber++;
			}
			$queryParticipantesFinal = " and periodo = ".$varProjeto->periodos." order by cast(numero as INTEGER) ";
			$resPart = $varConexao->pesquisa($queryParticipantes_INI." " . $queryParticipantes . " " . $queryParticipantesFinal);
			while($rowPart = fetch_assoc_seguro($resPart)){
				$amarelo = "#e9ff00; ";
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,$linha,utf8_encode($rowPart['numero']));
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,$linha,utf8_encode($rowPart['codigo']));
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$linha,utf8_encode($rowPart['status']));
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$linha,utf8_encode($rowPart['grupo']));
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,$linha,utf8_encode($rowPart['periodo']));

				//campos
				$colNumber = 5;
				$resCampos = $varConexao->pesquisa($queryCampos);
				while($rowCampo = fetch_assoc_seguro($resCampos)){
					$query = "SELECT
								agenda_check_participante_campo.valor,
								agenda_check_participante_campo.observacoes
									FROM agenda_check_participante_campo
										inner join agenda_check on agenda_check_participante_campo.campo = agenda_check.id
										where agenda_check_participante_campo.participante = '".$rowPart['id']."'
											and agenda_check.exporta = 1
										and agenda_check_participante_campo.campo = '".$rowCampo['id']."';";
					$resCampo = $varConexao->pesquisa($query);
					$rowCampo = fetch_assoc_seguro($resCampo);
					$valor      = $rowCampo['valor'];
					if($rowCampo['observacoes']!=""){
						$valor .= "\n[" . $rowCampo['observacoes'] . "]";
					}
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNumber,$linha,utf8_encode($valor));
					$objPHPExcel->getActiveSheet()->getStyle('a1')->getAlignment()->setWrapText(true);
					$colNumber++;
				}
				$linha++;
			}




			$objPHPExcel->setActiveSheetIndex(0);
			$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
			$objWriter->save($arquivoNomeXLS);
			return;















			if($varCheckList->temEspecifico()==1){
				$query = "select agenda_check_fase.* from agenda_check_fase where projeto = " . $_SESSION['projeto'];
			}else{
				$query = "select agenda_check_fase.* from agenda_check_fase where projeto = 0";
			}
			echo "QUERY->".$query."<br>";
			$resQtd = $varConexao->pesquisa($query);
			while($rowQtd = fetch_assoc_seguro($resQtd)){
				//Crio a aba nova e nomeio
				$sheetCount = $objPHPExcel->getSheetCount();
				$objPHPExcel->createSheet();
				$objPHPExcel->setActiveSheetIndex($sheetCount);

				$objPHPExcel->getActiveSheet()
					->getStyle('B:B')
					->getNumberFormat()
					->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);


				$objPHPExcel->getActiveSheet()->setTitle(formata_utf8_encode($rowQtd['item']));

				$varFase = $rowQtd['id'];

				//Fazer o loop em todos os itens dessa fase
				$queryPer = "select agenda_check.*
								from agenda_check
							inner join agenda_check_fase on agenda_check_fase.id = agenda_check.periodo ";
							if($varCheckList->temEspecifico()==1){
								$queryPer .= " where agenda_check.projeto = " . $_SESSION['projeto'];
							}else{
								$queryPer .= " where agenda_check.projeto = 0";
							}
							$queryPer .= "
								and periodo = ".$varFase;

				$queryPer .= "
									and agenda_check.exporta = 1
									order by agenda_check_fase.id,
												periodo,
												ordem";

				$resPer = $varConexao->pesquisa($queryPer);
				$colNumber = 1;


				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,1,"Participante");
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,1,utf8_encode("Código"));
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,1,"Status");
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,1,"Grupo");
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,1,utf8_encode("Período"));

				$colNumber = 5;

				while($rowPer = fetch_assoc_seguro($resPer)){
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNumber,1,formata_utf8_encode($rowPer['nome']));
					$colNumber++;
				}

				if(isset($_POST['grupo'])){$varGrupo = $_POST['grupo'];}else{$varGrupo = "TODOS";}


				$query = "select
								*
									from agenda_check_participante
								where projeto = '".$_SESSION['projeto']."'";
				if($varGrupo!="TODOS"){
					$query .= " and grupo = '".$varGrupo."'";
				}
				$query .= "
									order by cast(numero as INTEGER) ";
				$resPart = $varConexao->pesquisa($query);
				$linha = 2;


				while($rowPart = fetch_assoc_seguro($resPart)){
					$amarelo = "#e9ff00; ";


					//Verificando se tem filtro de sexo
					if($varSexo!=""){
						//Pear o sexo do participante
						$idParticipante = $rowPart['id'];
						if($varCheckList->temEspecifico()==1){
							$query = "SELECT id FROM `agenda_check` where nome = 'Sexo' and projeto = ".$varProjeto->id;
							$resSexo = $varConexao->pesquisa($query);
							$rowSexo = fetch_assoc_seguro($resSexo);
							$varIdexo = $rowSexo['id'];

						}else{
							$query = "SELECT id FROM `agenda_check` where nome = 'Sexo' and projeto = 0";
							$resSexo = $varConexao->pesquisa($query);
							$rowSexo = fetch_assoc_seguro($resSexo);
							$varIdexo = $rowSexo['id'];
						}
						$query = "SELECT valor FROM `agenda_check_participante_campo` where participante = ".$idParticipante." and campo = ".$varIdexo;
						$resValor = $varConexao->pesquisa($query);
						$rowValor = fetch_assoc_seguro($resValor);
						$valorSexo = $rowValor['valor'];
						if($valorSexo!=$varSexo){
							continue;
						}
					}




					$varCelula = "B".$linha;

					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,$linha,formata_utf8_encode($rowPart['numero']));
					//$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,$linha,formata_utf8_encode($rowPart['codigo']));
					$objPHPExcel->getActiveSheet()->setCellValueExplicit($varCelula,formata_utf8_encode($rowPart['codigo']),PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$linha,formata_utf8_encode(retornaStatus($rowPart['status'])));
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$linha,formata_utf8_encode($rowPart['grupo']));
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,$linha,formata_utf8_encode($rowPart['periodo']));





					$resPer = $varConexao->pesquisa($queryPer);
					$colNumber = 5;
					while($rowPer = fetch_assoc_seguro($resPer)){
						$query = "SELECT agenda_check_participante_campo.id as campo_id,
									agenda_check_participante_campo.participante,
									agenda_check_participante_campo.campo,
									agenda_check_participante_campo.valor,
									agenda_check_participante_campo.observacoes,
									agenda_check.nome,
									agenda_check.mascara,
									agenda_check.opcoes
										FROM agenda_check_participante_campo
											inner join agenda_check on agenda_check_participante_campo.campo = agenda_check.id
											where agenda_check_participante_campo.participante = '".$rowPart['id']."'
												and agenda_check.exporta = 1
											and agenda_check_participante_campo.campo = '".$rowPer['id']."';";
						$resCampo = $varConexao->pesquisa($query);
						$rowCampo = fetch_assoc_seguro($resCampo);
						$valor      = $rowCampo['valor'];
						if($rowCampo['observacoes']!=""){
							$valor .= "\n[" . $rowCampo['observacoes'] . "]";
						}
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNumber,$linha,utf8_encode($valor));
						$objPHPExcel->getActiveSheet()->getStyle('a1')->getAlignment()->setWrapText(true);
						$colNumber++;
					}

					$linha++;
				}

			}



		}else{

			$objPHPExcel = new PHPExcel();
			$objPHPExcel->setActiveSheetIndex(0);
			$rowCount = 1;

			/*    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow('A'.$rowCount, "NOME\nEDUARDO");
				$objPHPExcel->getActiveSheet()->getStyle('a1')->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, "IDADE");
			*/



			$projeto 	= $_SESSION['projeto'];
			$query 		= "select
							id
								from agenda_check_fase
									where projeto = " . $projeto . "
										order by agenda_check_fase.id, periodo, ordem";
			$resQtd		= $varConexao->pesquisa($query);
			if(num_rows_seguro($resQtd)==0){$projetoEspecifico = false;}else{$projetoEspecifico=true;}


			$varFase         = $_POST['fase'];
			$varGrupo        = $_POST['grupo'];
			$varPeriodo      = $_POST['periodo'];
			$varParticipante = $_POST['participante'];
			$varTipo         = $_POST['varTipo'];



			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,1,"Participante");
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,1,utf8_encode("Código"));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,1,"Status");
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,1,"Grupo");
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,1,utf8_encode("Período"));

			if($varCheckList->temEspecifico()==1){
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
			//echo $query;

			$resPer = $varConexao->pesquisa($query);
			$colNumber = 5;
			while($rowPer = fetch_assoc_seguro($resPer)){
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNumber,1,utf8_encode($rowPer['nome']));
				$colNumber++;
			}


			$query = "select * from agenda_check_participante where projeto = '".$_SESSION['projeto']."' ";
			if($varParticipante!=""){$query .= " and numero = '$varParticipante' ";}
			if($varGrupo!="TODOS"){$query .= " and ( (grupo = '') or (grupo = '$varGrupo') ) ";}
			if($varPeriodo!="TODOS"){$query .= " and ( (periodo = '') or (periodo = '$varPeriodo') ) ";}

			$query .= " order by cast(numero as INTEGER) ";
			$resPart = $varConexao->pesquisa($query);
			$linha = 2;


			$linhaCSV = "";
			while($rowPart = fetch_assoc_seguro($resPart)){
				$amarelo = "#e9ff00; ";

				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,$linha,utf8_encode($rowPart['numero']));
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,$linha,utf8_encode($rowPart['codigo']));
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$linha,utf8_encode($rowPart['status']));
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$linha,utf8_encode($rowPart['grupo']));
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,$linha,utf8_encode($rowPart['periodo']));


				if($varCheckList->temEspecifico()==1){
					if($varFase=="TODOS"){
						$query = "select agenda_check.* from agenda_check
									inner join agenda_check_fase on agenda_check_fase.id = agenda_check.periodo
										where agenda_check.projeto = $projeto
												";
					}else{
						$query = "select agenda_check.* from agenda_check
									inner join agenda_check_fase on agenda_check_fase.id = agenda_check.periodo
										where agenda_check.projeto = $projeto
											and periodo = '".$varFase."'
												";
					}
				}else{
					if($varFase=="TODOS"){
						$query = "select * from agenda_check
										inner join agenda_check_fase on agenda_check_fase.id = agenda_check.periodo
										where agenda_check.projeto = 0
											";
					}else{
						$query = "select * from agenda_check
									inner join agenda_check_fase on agenda_check_fase.id = agenda_check.periodo
									where agenda_check.projeto = 0 and periodo = '".$varFase."'
										";
					}
				}

				if($varTipo==0){
					$query .= " and agenda_check.exporta = 1 ";
				}
				$query .= " order by agenda_check_fase.id, periodo, ordem";

				$resPer = $varConexao->pesquisa($query);
				$colNumber = 5;
				while($rowPer = fetch_assoc_seguro($resPer)){
					$query = "SELECT agenda_check_participante_campo.id as campo_id,
								agenda_check_participante_campo.participante,
								agenda_check_participante_campo.campo,
								agenda_check_participante_campo.valor,
								agenda_check.nome,
								agenda_check.mascara,
								agenda_check.opcoes
									FROM agenda_check_participante_campo
										inner join agenda_check on agenda_check_participante_campo.campo = agenda_check.id
										where agenda_check_participante_campo.participante = '".$rowPart['id']."'
										and agenda_check_participante_campo.campo = '".$rowPer['id']."';";

					$resCampo = $varConexao->pesquisa($query);
					$rowCampo = fetch_assoc_seguro($resCampo);
					$valor      = $rowCampo['valor'];
					if($varTipo==1){
						//Pegar ultima atualizacao do campo
						$query  = "SELECT * FROM `log_alteracoes` where tabela = 'agenda_check_participante_campo' and id_campo = ".$rowCampo['campo_id']." order by id desc limit 1; ";
						$resLog = $varConexao->pesquisa($query);
						if(num_rows_seguro($resLog)>0){
							$rowLog = fetch_assoc_seguro($resLog);
							$valor .= "\n" . $rowLog['usuario_nome']. "\n" . $rowLog['data_hora'];
						}
					}
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNumber,$linha,utf8_encode($valor));
					$objPHPExcel->getActiveSheet()->getStyle('a1')->getAlignment()->setWrapText(true);
					$colNumber++;
				}
				$linha++;
			}

		}
		$objPHPExcel->setActiveSheetIndex(0);

        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save($arquivoNomeXLS);

    }