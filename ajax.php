<?php
	include_once __DIR__."/seguranca.php";
	include_once __DIR__."/funcoes.php";



	$id_usuario = $_SESSION['user_id'];
	$act = strtoupper($_GET['act']);
	function formatar_texto_bd($entrada){
		return utf8_encode($entrada);
	}
	function escreve($entrada){
		if($entrada=="<center>OK</center>"){
			echo $entrada;
		}else{
			echo "<font color='red'>".$entrada."</font>";
		}
	}
	if($act == "MUDA_CHECKLIST"){
		$campo  = $_GET['campo'];
		$valor  = $_GET['valor'];
		$agenda = $_GET['agenda'];

		if($campo>0){
			$query = "select * from agenda_checklist_agenda
						where agenda_id =  ".$agenda."
							and  agenda_checklist_id = " . $campo;
			$res   =  $conexao->pesquisa($query);
			$label = "";
			if(mysqli_num_rows($res)==0){
				$checked = " checked ";
				$query = "insert
						into agenda_checklist_agenda (agenda_checklist_id, agenda_id, checked, usuario, datahora, log_usuario) values (
					'$campo',
					'".$agenda."',
					'1',
					'".$_SESSION['user_nome']."',
					'".date('d/m/Y H:m')."',
					'".$_SESSION['user_id']."'
				);";
				$conexao->pesquisa($query);
				$nome = $_SESSION['user_nome'];
				$nome = explode(" ", $nome);
				$label = " [" . $nome[0] . " - " . date('d/m/Y h:m') . "]";
			}else{
				$row = mysqli_fetch_assoc($res);
				$checked = "";
				if($row['checked']==0){
					$query = "update agenda_checklist_agenda
								set log_usuario = '".$_SESSION['user_id']."',
								checked = 1,
								datahora = '".date("d/m/Y H:m")."'
									where  agenda_id =  ".$agenda."
										and  agenda_checklist_id = " . $campo;
					$conexao->pesquisa($query);
					$checked = " checked ";
					$nome = $_SESSION['user_nome'];
					$nome = explode(" ", $nome);
					$label = " [" . $nome[0] . " - " . date('d/m/Y h:m') . "]";
				}else{
					$query = "update agenda_checklist_agenda
								set log_usuario = '".$_SESSION['user_id']."',
								checked = 0,
								datahora = '".date("d/m/Y H:m")."'
									where  agenda_id =  ".$agenda."
											and  agenda_checklist_id = " . $campo;
					$conexao->pesquisa($query);
					$checked = "  ";
					$label = "";
				}
			}
			?>
				<input type="checkbox" <?=$checked;?> value="1" name="check_<?=$campo;?>" onclick="muda_check(<?=$campo;?>, this.checked);"><br><font size="1px"><?=$label;?></font>
			<?php

		}
	}

	if($act=="AGENDA_DELETAR"){
		$id = $_GET['id'];

		$query = "select * from crf_agenda_forms  where agendamento = " . $id;
		echo $query;
		$resAge = $conexao->pesquisa($query);
		while($rowAge = mysqli_fetch_assoc($resAge)){
			$idForm = $rowAge['id'];
			$conexao->pesquisa("delete from crf_agenda_campos where form_id = " . $idForm);
			echo "delete from crf_agenda_campos where form_id = " . $idForm;
		}
		$conexao->pesquisa("delete from crf_agenda_forms  where agendamento = " . $id);
		$conexao->pesquisa("delete from agenda where id = " . $id);

	}
	if($act=="EXP_MUDA_USU"){
		$exportacao 	= $_POST['exportacao'];
		$usuario		= $_POST['usuario'];
		$resposta		= $_POST['resposta'];

		$query  = "select * from cfg_exportacoes_usuarios where exportacao = '$exportacao' and usuario = '$usuario'";
		$resUsu = $conexao->pesquisa($query);
		if(mysqli_num_rows($resUsu)==0){
			$query = "insert into cfg_exportacoes_usuarios (exportacao, usuario, acessa) values ('$exportacao', '$usuario', '$resposta');";
			$conexao->pesquisa($query);
		}else{
			$row = mysqli_fetch_assoc($resUsu);
			$query = "update cfg_exportacoes_usuarios set acessa = '$resposta' where id = " . $row['id'];
			$conexao->pesquisa($query);
		}
		echo $query;

	}
	if($act=="ECRF_LISTA"){
		$centro = $_GET['centro'];
		if($centro==""){
			$data[] = array(
				"numero"=>"",
				"nascimento"=>"",
				"sexo"=>"",
				"data"=>"",
				"dataRandomizacao"=>"",
				"botao"=>""
			);
			//unset($data);
			$draw = $_POST['draw'];
			$response = array(
			  "draw" => intval($draw),
			  "iTotalRecords" => 0,
			  "iTotalDisplayRecords" => 0,
			  "data" => $data
			);
			echo json_encode($response);
		}else{
			if($centro!="TODOS"){
				$query = "SELECT crf_paciente_pre_rand.id,
							crf_paciente_pre_rand.nro_triagem,
							crf_paciente_pre_rand.nro_participante,
							crf_paciente_pre_rand.centro,
							crf_paciente_pre_rand.nasc,
							crf_paciente_pre_rand.sexo,
							crf_paciente_pre_rand.iniciais,
							crf_paciente_pre_rand.nro_paciente,
							crf_paciente_pre_rand.data_hora,
							crf_paciente_pre_rand.data_rand,
							crf_paciente_pre_rand.paciente_pos_rand,
							cfg_centro.numero
						FROM crf_paciente_pre_rand
							inner join cfg_centro on crf_paciente_pre_rand.centro = cfg_centro.id
						where crf_paciente_pre_rand.projeto = " . $_SESSION['projeto'] ."
									and  crf_paciente_pre_rand.centro = " . $centro;
			}else{
				$query = "SELECT crf_paciente_pre_rand.id,
							crf_paciente_pre_rand.nro_triagem,
							crf_paciente_pre_rand.nro_participante,
							crf_paciente_pre_rand.centro,
							crf_paciente_pre_rand.nro_paciente,
							crf_paciente_pre_rand.nasc,
							crf_paciente_pre_rand.sexo,
							crf_paciente_pre_rand.iniciais,
							crf_paciente_pre_rand.data_hora,
							crf_paciente_pre_rand.data_rand,
							crf_paciente_pre_rand.paciente_pos_rand,
							cfg_centro.numero
						FROM crf_paciente_pre_rand
							inner join cfg_centro on crf_paciente_pre_rand.centro = cfg_centro.id
						where crf_paciente_pre_rand.projeto = " . $_SESSION['projeto'];
				if( ($_SESSION['centro']!=40) and ($_SESSION['centro']!=0) ){
						$query .= "	and  crf_paciente_pre_rand.centro = " . $_SESSION['centro'];
				}
			}
			$query .= " order by CAST(crf_paciente_pre_rand.nro_participante as INT) ";
			$resCentro = $conexao->pesquisa($query);
			if(mysqli_num_rows($resCentro)==0){
				$data[] = array(
					"numero"=>"",
					"nascimento"=>"",
					"sexo"=>"",
					"data"=>"",
					"dataRandomizacao"=>"",
					"botao"=>"",
					"botaoAbre"=>""
				);
				$draw = $_POST['draw'];
				$response = array(
				  "draw" => intval($draw),
				  "iTotalRecords" => 0,
				  "iTotalDisplayRecords" => 0,
				  "data" => $data
				);
			}else{
				while($row = mysqli_fetch_assoc($resCentro)){
					$labCen = $row['numero'];
					$labPre = $row['nro_triagem'];
					$labPos = $row['paciente_pos_rand'];
					$varSexo = $row['sexo'];
					if($varSexo=="F"){$varSexo = "Feminino";}else{if($varSexo=="M")$varSexo = "Masculino";}
					$label_paciente = str_pad($labCen,2,"00",STR_PAD_LEFT).'-'.str_pad($labPre,4,"0000",STR_PAD_LEFT)."S";
					if($labPos>0){
						$label_paciente .= str_pad($labPos,3,"000",STR_PAD_LEFT)."R";
					}
					$iniciais = $row['iniciais'];
					$vardata = $row['data_hora'];
					$vardata = explode(" ", $vardata);
					$hora = $vardata[1];
					$vardata = explode("-", $vardata[0]);
					$vardata = $vardata[2] . "/" . $vardata[1] . "/" . $vardata[0] . " " . $hora;

						$link = 'javascript:exibeCRF('.$row['nro_paciente'].', '.$row['centro'].');';
						$botao = '

								<div class="btn-group btn-group-circle dropdown-menu-left">
									<button type="button" class="btn btn-default"><i class="fa fa-print" alt="Visualizar CRF" ></i></button>
									<button type="button" class="btn btn-circle-right btn-default dropdown-toggle" data-toggle="dropdown">
										<i class="fa fa-angle-down"></i>
									</button>
									<ul class="dropdown-menu dropdown-menu-left" role="menu">

										<li>
											<a href="javascript:recibo('.$row['centro'].', '.$row['nro_triagem'].');"> '.traduzir(utf8_encode("Comprovante de Inclusão")).' </a>
										</li>
										';
										if($row['paciente_pos_rand']>0){
											$botao .= '
												<li>
													<a href="javascript:reciboRand('.$row['centro'].','.$row['nro_participante'].');"> '.traduzir(utf8_encode("Comprovante de Randomização")).' </a>
												</li>
											';
										}
						$botao .= '
									</ul>
								</div>';
						$botaoAbre = '
								<a alt="Visualizar CRF" href="'.$link.'" class="btn btn-circle btn-sm blue">
									<i class="fa fa-search" alt="Visualizar CRF" ></i>
								</a>
						';

					$data[] = array(
						"numero"=>$label_paciente,
						"nascimento"=>$row['nasc'],
						"sexo"=>$varSexo,
						"data"=>$vardata,
						"dataRandomizacao"=>$row['data_rand'],
						"botao"=>$botao,
						"botaoAbre"=>$botaoAbre
					);
				}
			}
			$response = array(
			  "draw" => intval($draw),
			  "iTotalRecords" => 0,
			  "iTotalDisplayRecords" => 0,
			  "data" => $data
			);
			echo json_encode($response);
		}

	}
	if($act=="AGENDA_MUDA_ENVIADO"){
		$id 	= $_GET['id'];
		$valor 	= $_GET['valor'];
		$data	= date("d/m/Y H:i");
		$conexao->pesquisa("update agenda set rel_enviado = '$valor', rel_enviado_quem = '".$_SESSION['user_nome']."', rel_enviado_data = '$data' where id = " . $id);
		if($valor=="1"){
			echo '<input type="checkbox" name="check" id="check" onclick="javascript:enviado('.$id.', this.checked);" checked "></input><font style="font-size:10px"><br>'.$_SESSION['user_nome'].'<br>'.$data."</font>";
		}else{
			echo '<input type="checkbox" name="check" id="check" onclick="javascript:enviado('.$id.', this.checked);"></input><font style="font-size:10px"><br>'.$_SESSION['user_nome'].'<br>'.$data."</font>";
		}
	}
	if($act=="AGENDA_MUDA_RESPONDIDO"){
		$id 	= $_GET['id'];
		$valor 	= $_GET['valor'];

		$conexao->pesquisa("update agenda set rel_respondido = '$valor' where id = " . $id);

	}


	if($act=="COMPARATIVO_MIT"){
		$campos = array(
			array("label" => "Código de Barras", "mct"=>"codigo_barras", "spc"=>"DC_BARCODE"),
			array("label" => "Data de entrada", "mct"=>"data_entrada", "spc"=>"DC_DTA_INCLUSAO"),
			array("label" => "Primeiro nome", "mct"=>"primeiro_nome", "spc"=>"DC_PRIME_NOME"),
			array("label" => "Sobrenome", "mct"=>"sobrenome", "spc"=>"DC_SOBRE_NOME"),
			array("label" => "CPF", "mct"=>"cpf", "spc"=>"DC_CPF"),
			array("label" => "Data de nascimento", "mct"=>"data_nascimento", "spc"=>"DC_DOB")
		);

		$query = "select * from vw_sarita_listagem_curta
			where randomizado <> '' " . $_GET['filtro'] . "
			   order by cast(randomizado  as int) ";

		$resPac = $conexao->pesquisa($query);
		while($rowPac = mysqli_fetch_assoc($resPac)){
			$nro_triagem = $rowPac['nro_triagem'];
			$centro		 = $rowPac['centro'];
			$randomizado = $rowPac['randomizado'];

			//PEsquisa na tabela do sarita
			$query = "select * from sarita_planilha where nro_paciente = '".$rowPac['randomizado']."'";
			$resMCT = $conexao->pesquisa($query);
			$rowMCT = mysqli_fetch_assoc($resMCT);

			?>
				<tr style="font-size: 6px !important;">
					<td style="font-size: 10px !important;"><?=$rowPac['centro'];?></td>
					<td style="font-size: 10px !important;"><?=$rowPac['randomizado'];?></td>
					<td style="font-size: 10px !important;"><center><?php
						$mct = strtoupper($rowMCT['altura']);
						$spc = str_replace(",","",strtoupper(busca_valor($nro_triagem, $centro, 'DC_ALTURA')));
						if($mct==$spc){escreve("<center>OK</center>");}else{escreve("MCT-> " . $mct . "<br>SPC-> ". $spc . "<br>");}
					?></center></td>
					<td style="font-size: 10px !important;"><center><?php
						$mct = strtoupper($rowMCT['peso']);
						$spc = str_replace(",","",strtoupper(busca_valor($nro_triagem, $centro, 'DC_PESO')));
						if($mct==$spc){escreve("<center>OK</center>");}else{escreve("MCT-> " . $mct . "<br>SPC-> ". $spc . "<br>");}
					?></center></td>
					<td style="font-size: 10px !important;"><center><?php
						$mct = strtoupper($rowMCT['grupo']);
						$spc = str_replace(",","",strtoupper(busca_valor($nro_triagem, $centro, 'DC_GRUPO')));
						if($spc=="N/A"){$spc = "";}
						if($mct==$spc){escreve("<center>OK</center>");}else{escreve("MCT-> " . $mct . "<br>SPC-> ". $spc . "<br>");}
					?></center></td>

					<td style="font-size: 10px !important;"><center><?php
						$mct = strtoupper($rowMCT['d1_fr']);
						$spc = str_replace(",","",strtoupper(busca_valor($nro_triagem, $centro, 'D1_FR')));
						if($mct==$spc){escreve("<center>OK</center>");}else{escreve("MCT-> " . $mct . "<br>SPC-> ". $spc . "<br>");}
					?></center></td>
					<td style="font-size: 10px !important;"><center><?php
						$mct = strtoupper($rowMCT['d1_fc']);
						$spc = str_replace(",","",strtoupper(busca_valor($nro_triagem, $centro, 'D1_FC')));
						if($mct==$spc){escreve("<center>OK</center>");}else{escreve("MCT-> " . $mct . "<br>SPC-> ". $spc . "<br>");}
					?></center></td>
					<td style="font-size: 10px !important;"><center><?php
						$mct = str_replace("x","",strtoupper($rowMCT['d1_pa']));
						$mct = str_replace("X","",strtoupper($mct));
						$mct = str_replace("/","",strtoupper($mct));
						$spc1 = str_replace(",","",strtoupper(busca_valor($nro_triagem, $centro, 'D1_PAS')));
						$spc2 = str_replace(",","",strtoupper(busca_valor($nro_triagem, $centro, 'D1_PAD')));
						$spc = $spc1.$spc2;
						if($mct==$spc){escreve("<center>OK</center>");}else{escreve("MCT-> " . $mct . "<br>SPC-> ". $spc . "<br>");}
					?></center></td>
					<td style="font-size: 10px !important;"><center><?php
						$mct = strtoupper($rowMCT['d1_temperatura']);
						$spc = strtoupper(busca_valor($nro_triagem, $centro, 'D1_TEMP'));
						$spc = explode(",", $spc);

						if(intval($spc[1])!="0"){$spc = $spc[0].".".$spc[1];}else{$spc = $spc[0];}
						if(intval($mct)==intval($spc)){escreve("<center>OK</center>");}else{escreve("MCT-> " . $mct . "<br>SPC-> ". $spc . "<br>");}
					?></center></td>
					<td style="font-size: 10px !important;"><center><?php
						$mct = strtoupper($rowMCT['d1_swab_data']);
						$spc = strtoupper(busca_valor($nro_triagem, $centro, 'D1_SWAB_DATA'));
						if(intval($mct)==intval($spc)){escreve("<center>OK</center>");}else{escreve("MCT-> " . $mct . "<br>SPC-> ". $spc . "<br>");}
					?></center></td>
					<td style="font-size: 10px !important;"><center><?php
						$mct = strtoupper($rowMCT['d1_swab_resultado']);
						$spc = strtoupper(busca_valor($nro_triagem, $centro, 'D1_RESULTADO_PCR'));
						$spc = explode(" ", $spc);
						$spc = $spc[0];
						if(intval($mct)==intval($spc)){escreve("<center>OK</center>");}else{escreve("MCT-> " . $mct . "<br>SPC-> ". $spc . "<br>");}
					?></center></td>
					<td style="font-size: 10px !important;"><center><?php
						$mct = strtoupper($rowMCT['d1_swab_data_laudo']);
						$spc = strtoupper(busca_valor($nro_triagem, $centro, 'D1_SWAB_DATA_LAUDO'));
						if(intval($mct)==intval($spc)){escreve("<center>OK</center>");}else{escreve("MCT-> " . $mct . "<br>SPC-> ". $spc . "<br>");}
					?></center></td>
					<td style="font-size: 10px !important;"><center><?php
						$mct = strtoupper($rowMCT['d1_saturacao']);
						$spc = strtoupper(busca_valor($nro_triagem, $centro, 'D1_SATURACAO'));
						if(intval($mct)==intval($spc)){escreve("<center>OK</center>");}else{escreve("MCT-> " . $mct . "<br>SPC-> ". $spc . "<br>");}
					?></center></td>
					<td style="font-size: 10px !important;"><center><?php
						$mct = strtoupper($rowMCT['d1_id_ct_vacinas']);
						$spc = strtoupper(busca_valor($nro_triagem, $centro, 'D1_CT_VACINAS'));
						if(intval($mct)==intval($spc)){escreve("<center>OK</center>");}else{escreve("MCT-> " . $mct . "<br>SPC-> ". $spc . "<br>");}
					?></center></td>
					<td style="font-size: 10px !important;"><center><?php
						$mct = strtoupper($rowMCT['d3_retornou']);
						$spc = strtoupper(busca_valor($nro_triagem, $centro, 'D3_COMPARECEU'));
						$spc = explode(" ", $spc);
						$spc = $spc[0];
						if(intval($mct)==intval($spc)){escreve("<center>OK</center>");}else{escreve("MCT-> " . $mct . "<br>SPC-> ". $spc . "<br>");}
					?></center></td>
					<td style="font-size: 10px !important;"><center><?php
						$mct = strtoupper($rowMCT['d3_data_coleta']);
						$spc = strtoupper(busca_valor($nro_triagem, $centro, 'D3_DATA_COLETA_SANGUE'));
						if(intval($mct)==intval($spc)){escreve("<center>OK</center>");}else{escreve("MCT-> " . $mct . "<br>SPC-> ". $spc . "<br>");}
					?></center></td>
					<td style="font-size: 10px !important;"><center><?php
						$mct = strtoupper($rowMCT['d3_hemoglobina']);
						$spc = strtoupper(busca_valor($nro_triagem, $centro, 'D3_HEMOGLOBINA'));
						if(intval($mct)==intval($spc)){escreve("<center>OK</center>");}else{escreve("MCT-> " . $mct . "<br>SPC-> ". $spc . "<br>");}
					?></center></td>
					<td style="font-size: 10px !important;"><center><?php
						$mct = strtoupper($rowMCT['d3_hematocrito']);
						$spc = strtoupper(busca_valor($nro_triagem, $centro, 'D3_HEMATOCRITO'));
						if(intval($mct)==intval($spc)){escreve("<center>OK</center>");}else{escreve("MCT-> " . $mct . "<br>SPC-> ". $spc . "<br>");}
					?></center></td>
					<td style="font-size: 10px !important;"><center><?php
						$mct = strtoupper($rowMCT['d3_leucocito']);
						$spc = strtoupper(busca_valor($nro_triagem, $centro, 'D3_LEUCOCITOS'));
						if(intval($mct)==intval($spc)){escreve("<center>OK</center>");}else{escreve("MCT-> " . $mct . "<br>SPC-> ". $spc . "<br>");}
					?></center></td>
					<td style="font-size: 10px !important;"><center><?php
						$mct = strtoupper($rowMCT['d3_linfocito']);
						$spc = strtoupper(busca_valor($nro_triagem, $centro, 'D3_LINFOCITOS'));
						if(intval($mct)==intval($spc)){escreve("<center>OK</center>");}else{escreve("MCT-> " . $mct . "<br>SPC-> ". $spc . "<br>");}
					?></center></td>
					<td style="font-size: 10px !important;"><center><?php
						$mct = strtoupper($rowMCT['d3_neutrofilo']);
						$spc = strtoupper(busca_valor($nro_triagem, $centro, 'D3_NEUTROFILO'));
						if(intval($mct)==intval($spc)){escreve("<center>OK</center>");}else{escreve("MCT-> " . $mct . "<br>SPC-> ". $spc . "<br>");}
					?></center></td>
					<td style="font-size: 10px !important;"><center><?php
						$mct = strtoupper($rowMCT['d3_plaqueta']);
						$spc = strtoupper(busca_valor($nro_triagem, $centro, 'D3_PLAQUETAS'));
						if(intval($mct)==intval($spc)){escreve("<center>OK</center>");}else{escreve("MCT-> " . $mct . "<br>SPC-> ". $spc . "<br>");}
					?></center></td>
					<td style="font-size: 10px !important;"><center><?php
						$mct = strtoupper($rowMCT['d3_proteina']);
						$spc = strtoupper(busca_valor($nro_triagem, $centro, 'D3_PROTEINAS'));
						if(intval($mct)==intval($spc)){escreve("<center>OK</center>");}else{escreve("MCT-> " . $mct . "<br>SPC-> ". $spc . "<br>");}
					?></center></td>
					<td style="font-size: 10px !important;"><center><?php
						$mct = strtoupper($rowMCT['d8_vivo']);
						$spc = strtoupper(busca_valor($nro_triagem, $centro, 'D8_VIVO'));
						if(intval($mct)==intval($spc)){escreve("<center>OK</center>");}else{escreve("MCT-> " . $mct . "<br>SPC-> ". $spc . "<br>");}
					?></center></td>


					<td style="font-size: 10px !important;"><center><?php
						$mct = strtoupper($rowMCT['d8_fr']);
						$spc = str_replace(",","",strtoupper(busca_valor($nro_triagem, $centro, 'D8_FR')));
						if($mct==$spc){escreve("<center>OK</center>");}else{escreve("MCT-> " . $mct . "<br>SPC-> ". $spc . "<br>");}
					?></center></td>
					<td style="font-size: 10px !important;"><center><?php
						$mct = strtoupper($rowMCT['d8_fc']);
						$spc1 = str_replace(",","",strtoupper(busca_valor($nro_triagem, $centro, 'D8_FC')));
						if($mct==$spc){escreve("<center>OK</center>");}else{escreve("MCT-> " . $mct . "<br>SPC-> ". $spc . "<br>");}
					?></center></td>
					<td style="font-size: 10px !important;"><center><?php
						$mct = str_replace("x","",strtoupper($rowMCT['d8_pa']));
						$mct = str_replace("X","",strtoupper($mct));
						$mct = str_replace("/","",strtoupper($mct));
						$spc1 = str_replace(",","",strtoupper(busca_valor($nro_triagem, $centro, 'D8_PAS')));
						$spc2 = str_replace(",","",strtoupper(busca_valor($nro_triagem, $centro, 'D8_PAD')));
						$spc = $spc1.$spc2;
						if($mct==$spc){escreve("<center>OK</center>");}else{escreve("MCT-> " . $mct . "<br>SPC-> ". $spc . "<br>");}
					?></center></td>
					<td style="font-size: 10px !important;"><center><?php
						$mct = strtoupper($rowMCT['d8_temperatura']);
						$spc = strtoupper(busca_valor($nro_triagem, $centro, 'D8_TEMP'));
						$spc = explode(",", $spc);
						if(intval($spc[1])!="0"){$spc = $spc[0].$spc[1];}else{$spc = $spc[0];}
						if(intval($mct)==intval($spc)){escreve("<center>OK</center>");}else{escreve("MCT-> " . $mct . "<br>SPC-> ". $spc . "<br>");}
					?></center></td>
					<td style="font-size: 10px !important;"><center><?php
						$mct = strtoupper($rowMCT['d8_swab_data']);
						$spc = strtoupper(busca_valor($nro_triagem, $centro, 'D8_SWAB_DATA'));
						if(intval($mct)==intval($spc)){escreve("<center>OK</center>");}else{escreve("MCT-> " . $mct . "<br>SPC-> ". $spc . "<br>");}
					?></center></td>
					<td style="font-size: 10px !important;"><center><?php
						$mct = strtoupper($rowMCT['d8_swab_resultado']);
						$spc = strtoupper(busca_valor($nro_triagem, $centro, 'D8_SWAB_RESULTADO'));
						$spc = explode(" ", $spc);
						$spc = $spc[0];
						if(intval($mct)==intval($spc)){escreve("<center>OK</center>");}else{escreve("MCT-> " . $mct . "<br>SPC-> ". $spc . "<br>");}
					?></center></td>


					<td style="font-size: 10px !important;"><center><?php
						$mct = strtoupper($rowMCT['d8_hemoglobina']);
						$spc = strtoupper(busca_valor($nro_triagem, $centro, 'D8_HEMOGLOBINA'));
						if(intval($mct)==intval($spc)){escreve("<center>OK</center>");}else{escreve("MCT-> " . $mct . "<br>SPC-> ". $spc . "<br>");}
					?></center></td>
					<td style="font-size: 10px !important;"><center><?php
						$mct = strtoupper($rowMCT['d8_hematocrito']);
						$spc = strtoupper(busca_valor($nro_triagem, $centro, 'D8_HEMATOCRITO'));
						if(intval($mct)==intval($spc)){escreve("<center>OK</center>");}else{escreve("MCT-> " . $mct . "<br>SPC-> ". $spc . "<br>");}
					?></center></td>
					<td style="font-size: 10px !important;"><center><?php
						$mct = strtoupper($rowMCT['d8_leucocito']);
						$spc = strtoupper(busca_valor($nro_triagem, $centro, 'D8_LEUCOCITO'));
						if(intval($mct)==intval($spc)){escreve("<center>OK</center>");}else{escreve("MCT-> " . $mct . "<br>SPC-> ". $spc . "<br>");}
					?></center></td>
					<td style="font-size: 10px !important;"><center><?php
						$mct = strtoupper($rowMCT['d8_linfocito']);
						$spc = strtoupper(busca_valor($nro_triagem, $centro, 'D8_LINFOCITO'));
						if(intval($mct)==intval($spc)){escreve("<center>OK</center>");}else{escreve("MCT-> " . $mct . "<br>SPC-> ". $spc . "<br>");}
					?></center></td>
					<td style="font-size: 10px !important;"><center><?php
						$mct = strtoupper($rowMCT['d8_neutrofilo']);
						$spc = strtoupper(busca_valor($nro_triagem, $centro, 'D8_NEUTROFILO'));
						if(intval($mct)==intval($spc)){escreve("<center>OK</center>");}else{escreve("MCT-> " . $mct . "<br>SPC-> ". $spc . "<br>");}
					?></center></td>
					<td style="font-size: 10px !important;"><center><?php
						$mct = strtoupper($rowMCT['d8_plaqueta']);
						$spc = strtoupper(busca_valor($nro_triagem, $centro, 'D8_PLAQUETAS'));
						if(intval($mct)==intval($spc)){escreve("<center>OK</center>");}else{escreve("MCT-> " . $mct . "<br>SPC-> ". $spc . "<br>");}
					?></center></td>
					<td style="font-size: 10px !important;"><center><?php
						$mct = strtoupper($rowMCT['d8_proteina']);
						$spc = strtoupper(busca_valor($nro_triagem, $centro, 'D8_PROTEINA'));
						if(intval($mct)==intval($spc)){escreve("<center>OK</center>");}else{escreve("MCT-> " . $mct . "<br>SPC-> ". $spc . "<br>");}
					?></center></td>


				</tr>
			<?php
		}
	}

	if($act=="LISTA_PENDENCIA_LISTA"){
		//$query = "select * from vw_sarita_lista_pendencia where projeto = ".$_SESSION['projeto']." and id > 0 " . $_GET['filtro'];
		//echo $query;
		$query = "select
				`agenda_lista_pendencia`.`id` AS `id`,
				`agenda_lista_pendencia`.visita_data,
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
				`agenda_lista_pendencia`.`criticidade` AS `criticidade`,
				`agenda_lista_pendencia`.`categoria` AS `categoria`,
				concat(substr(`agenda_lista_pendencia`.`data_abertura`,7,4),substr(`agenda_lista_pendencia`.`data_abertura`,4,2),substr(`agenda_lista_pendencia`.`data_abertura`,1,2)) AS `data_abertura_lis`,
				`usu_usuario`.`nome` AS `monitora_nome`
					from (`agenda_lista_pendencia` left join `usu_usuario` on(`agenda_lista_pendencia`.`monitora` = `usu_usuario`.`id`)) ";
		$query .= " where
						projeto = ".$_SESSION['projeto']."
							and agenda_lista_pendencia.id > 0 " . $_GET['filtro'];
		//$query .= " order by cast(agenda_lista_pendencia.paciente as integer)";
		$res   = $conexao->pesquisa($query);
		$ind = 1;
		//echo $query;
		while($row = mysqli_fetch_assoc($res)){
			$status = $row['status'];
			if($status == 'PENDENTE'){
				$status = 'color: black !important;';
			}elseif($status == 'RESOLVIDO'){
				$status = 'color: green !important;';
			}
			?>

				<tr style="font-size: 6px !important;">
					<td style="font-size: 10px !important; <?=$status;?>"><center><?=$ind;?></center></td>
					<td style="font-size: 10px !important; <?=$status;?>"><center><?=$row['visita'];?></center></td>
					<td style="font-size: 10px !important; <?=$status;?>"><center><?=$row['visita_data'];?></center></td>
					<td style="font-size: 10px !important; <?=$status;?>"><?=$row['centro'];?></td>
					<td style="font-size: 10px !important; <?=$status;?>"><center><?=$row['paciente'];?></center></td>
					<td style="font-size: 10px !important; <?=$status;?>"><center><?=$row['tipo'];?></center></td>
					<td style="font-size: 10px !important; <?=$status;?>"><?=$row['achado'];?></td>
					<td style="font-size: 10px !important; <?=$status;?>"><center><?=$row['responsavel'];?></center></td>
					<td style="font-size: 10px !important; <?=$status;?>"><center><?=$row['data_abertura'];?></center></td>
					<td style="font-size: 10px !important; <?=$status;?>"><center><?=$row['status'];?></center></td>
					<td style="font-size: 10px !important; <?=$status;?>"><center><?=$row['data_fechamento'];?></center></td>
					<td style="font-size: 10px !important; <?=$status;?>"><center><?=$row['criticidade'];?></center></td>
					<td style="font-size: 10px !important; <?=$status;?>"><center><?=$row['categoria'];?></center></td>
					<td>
						<center>
							<a href="novo_pendencia.php?opc=E&id=<?=$row['id'];?>&registro=<?=$row['id'];?>"><img src="imagens/ico_editar.jpg" width="15" alt="Editar" title="Editar informações"></a>
						</center>
					</td>
					<td style="font-size: 10px !important;"><center><a href="javascript:excluir(<?=$row['id'];?>)";><img src="imagens/ico_excluir.png" width="15"></a></center></td>
				</tr>
			<?php
			$ind++;
		}
	}
	if($act=="MODAL_PENDENCIA"){
		$centro        = $_GET['centro'];
		$visita        = $_GET['visita'];
		$dataVisita    = $_GET['visita_data'];
		$paciente      = $_GET['paciente'];
		$monitor       = $_GET['monitor'];
		$tipo          = $_GET['tipo'];
		$categoria     = $_GET['categoria'];
		$criticidade   = $_GET['criticidade'];
		$formulario    = $_GET['formulario'];
		$dataInicio    = $_GET['dataInicio'];
		$achado        = $_GET['achado'];
		$responsavel   = $_GET['responsavel'];
		$classificacao = $_GET['classificacao'];
		$data_abertura = $_GET['data_abertura'];



		$query = "insert into agenda_lista_pendencia (centro,
														visita,
														paciente,
														monitora,
														achado,
														tipo,
														responsavel,
														visita_data,
														projeto,
														status,
														categoria,
														criticidade,
														class_pendencia,
														formulario,
														data_abertura
														)
														values ('$centro',
																'$visita',
																'$paciente',
																'$monitor',
																'$achado',
																'$tipo',
																'$responsavel',
																'$dataVisita',
																'".$_SESSION['projeto']."',
																'PENDENTE',
																'$categoria',
																'$criticidade',
																'$classificacao',
																'$formulario',
																'$data_abertura'
					);";
		$conexao->pesquisa($query);
		echo $query;
	}

	if($act=="MUDOU_PROJETO"){
		$id = $_GET['id'];
		$_SESSION['projeto']=$id;
		$query 	 = "select * from proj_projeto where id = " . $id;
		$resProj = $conexao->pesquisa($query);
		$rowProj = mysqli_fetch_assoc($resProj);
		$_SESSION['projeto_nome']=$rowProj['nome'];
	}
	if($act=="MUDOU_LINGUAGEM"){
		$_SESSION['lang']=$_GET['lingua'];
	}

	if($act == "QUERY_FECHAR"){
		$id = $_GET['id'];
		$query = "update crf_entrada_campos_query set status = 3, log_usuario = ".$_SESSION['user_id'].", datahora_fechamento = '".DataHoraAtualToLIS()."', usuario_fechamento = ".$_SESSION['user_id']." where id = " . $id;
		$conexao->pesquisa($query);
		echo $query;
	}
	if($act == "QUERY_NOVO_ITEM"){
		$id = $_GET['id'];
		$msg = $_GET['msg'];
		$query = "insert into crf_entrada_campos_query_questionamentos (usuario_id, query_id, datahora, tipo, mensagem, log_usuario) values ( ";
		$query .= $_SESSION['user_id'] . ", " . $id . ", '" .DataHoraAtualToLIS(). "', 1, '".$msg."', ".$_SESSION['user_id'].");";
		$conexao->pesquisa($query);
		echo "1";
	}

	if($act == "MUDA_VALOR"){
		$campo = $_GET['campo'];
		$valor = $_GET['valor'];
		$valor = strtoupper($valor);
		if($campo>0){
			$query = "update crf_entrada_campos set log_usuario = " . $_SESSION['user_id'] . ", valor = '$valor' where id = " .$campo;
			$conexao->pesquisa($query);
		}
	}
	if($act == "BUSCA_VALOR"){
		$original = $_GET['original'];
		$busca = $_GET['busca'];
		$original = explode("_", $original);
		$original = $original[1];
		if($busca=="!HOJE"){
			echo date("d/m/Y");
		}else{
			$query = "SELECT crf_entrada_campos.id as campo_id, crf_entrada_forms.paciente, crf_entrada_forms.centro
				from crf_entrada_campos
					inner join crf_entrada_forms on crf_entrada_campos.form_id = crf_entrada_forms.id
					where crf_entrada_campos.id = " . $original;
			$res = $conexao->pesquisa($query);
			$row = mysqli_fetch_assoc($res);
			$centro = $row['centro'];
			$paciente = $row['paciente'];
			$query = "select valor
							from vw_valor_campo
							where nome = '".$busca."' and entrada_forms_centro = " . $centro . " and entrada_forms_paciente = " . $paciente;
			$res = $conexao->pesquisa($query);
			$row = mysqli_fetch_assoc($res);
			echo trim($row['valor']);
		}
	}
	if($act=="AJAX_ADD"){
		$tabela = $_GET['tabela'];
		$campos = $_GET['campos'];
		$valores = $_GET['valores'];
		$campos = explode(";", $campos);
		$valores = explode(";", $valores);
		//Pegando os valores vindos via GET
		$sql_campos = "";
		$sql_valores = "";
		$pega = 0;
		for($i=0;$i<=count($campos)-1;$i++){
			if($pega>0){$sql_campos .= ", ";$sql_valores .= ", ";}else{$pega++;}
			$sql_campos  .= $campos[$i];
			$sql_valores .= "'".$valores[$i]."'";
		}
		$query = "insert into $tabela (log_usuario, $sql_campos) values ($id_usuario, $sql_valores);";
		$conexao->pesquisa($query);
		$pesquisa = "";
		for($i=0;$i<count($campos);$i++){
			if($i==0){
				$pesquisa = $campos[$i] . " = '".$valores[$i]."' ";
			}else{
				$pesquisa .= " and " . $campos[$i] . " = '".$valores[$i]."' ";
			}
		}
		$query = "select count(id) as qtd from $tabela where " . $pesquisa;
		$res = $conexao->pesquisa($query);
		$row = mysqli_fetch_assoc($res);
		echo $row['qtd'];
	}
	if($act=="AJAX_SAVE"){
		$tabela = $_GET['tabela'];
		$id     = $_GET['id'];
		$campos  = array();
		$valores = array();
		foreach($_POST as $key => $value) {
		  array_push($campos, $key);
		  array_push($valores, $value);
		}
		//Configurações
		$query = "update $tabela set log_usuario = '$id_usuario', ";
		$sql_campos = "";
		$pega = 0;
		for($i=0;$i<=count($campos)-1;$i++){
			if($pega>0){$sql_campos .= ", ";}else{$pega++;}
			$sql_campos .= $campos[$i] . " = '" . $valores[$i] . "'";
		}
		$query .= $sql_campos . " where id = ".$id;
		$query = utf8_decode($query);
		$conexao->pesquisa($query);
		echo $query;
	}
	if($act=="AJAX_DEL"){
		$tabela = $_GET['tabela'];
		$id     = $_GET['id'];
		$query = "delete from $tabela where id = $id";
		//$conexao->pesquisa($query);
		echo $query;
	}
	if($act=="ECRF_EV_ADVERSOS"){
		$centro = $_GET['centro'];
		$paciente = $_GET['paciente'];
		$tabela   = $_GET['tabela'];
		$nro = $_GET['nro'];
		if($nro==0){$nro=1;}
		$query_med = "select * from $tabela where paciente = ".$paciente." and centro = ".$centro." order by id";
		$res_med   = $conexao->pesquisa($query_med);
		if(mysqli_num_rows($res_med)==0){
			echo "<h5>Nenhum evento adverso cadastrado...</h5>";
		}else{
			echo '<div id="paginacao" style="text-align:center">
					<ul class="pagination">
				';
				$indMed = 1;
				while($row_med = mysqli_fetch_assoc($res_med)){
					echo '  <li ';
					if(($indMed==$nro)){echo ' class="active"';}
					echo '	>
								<a href="javascript:carrega('.$indMed.');"> '.$indMed.' </a>
							</li>
						';
					$indMed++;
				}
		echo	'
				</ul>
			</div>
			';
		?>
		<div id="medicacao" name="medicacao">
			<?php
				$query = "select * from $tabela where centro = $centro and paciente = $paciente order by id";
				$res   = $conexao->pesquisa($query);
				$i=1;
				for($i=1;$i<=$nro;$i++){
					$row = mysqli_fetch_assoc($res);
				}
			?>
			<div class="portlet-body" >
				<form class="form-horizontal" role="form" action="" method="post" id="form_info" name="form_info">
					<table width="100%" class="table table-striped" style="font-family: 'Abel';font-size: 22px;">
						<thead>
							<th class="tabela-head" width="30%">Evento adverso #<?=$nro;?></th>
							<th class="tabela-head" ><center></center></th>
						</thead>
					   <tbody>
							<tr>
								<td>
								  <table width="100%">
									<tr>
										<td class="tabela-body">Data de início</td>
									</tr>
								  </table>
								</td>
								<td style="vertical-align:middle" width="22%">
									<input type="text" class="form-control" id="form_data_inicio" name="form_data_inicio" value="<?=$row['data_inicio'];?>" <?=$centralizado;?> onkeyUp="mascara(this, '##/##/####');">
								</td>
							</tr>
							<tr>
								<td>
								  <table width="100%">
									<tr>
										<td class="tabela-body">Hora de início</td>
									</tr>
								  </table>
								</td>
								<td style="vertical-align:middle" width="22%">
									<input type="text" class="form-control" id="form_hora_inicio" onkeyUp="mascara(this, '##:##');" name="form_hora_inicio" value="<?=$row['hora_inicio'];?>" <?=$centralizado;?>>
								</td>
							</tr>
							<tr>
								<td>
								  <table width="100%">
									<tr>
										<td class="tabela-body">Queixa</td>
									</tr>
								  </table>
								</td>
								<td style="vertical-align:middle" width="22%">
									<textarea class="form-control" id="form_queixa" rows="5" name="form_queixa"><?=$row['queixa'];?></textarea>
								</td>
							</tr>
							<tr>
								<td colspan="2" class="tabela-head">Classificação</td>
							</tr>
							<tr>
								<td>
								  <table width="100%">
									<tr>
										<td class="tabela-body">Gravidade</td>
									</tr>
								  </table>
								</td>
								<td style="vertical-align:middle" width="22%">
									<select class="form-control select2_category" id="form_class_gravidade" name="form_class_gravidade">
										<option value=""></option>
										<option value="1 - Não Grave" <?php if($row['class_gravidade']=='1 - Não Grave'){echo ' selected ';}?>>Não Grave</option>
										<option value="2 - EA Grave" <?php if($row['class_gravidade']=='2 - EA Grave'){echo ' selected ';}?>>EA Grave</option>
									</select>
								</td>
							</tr>
							<tr>
								<td>
								  <table width="100%">
									<tr>
										<td class="tabela-body">Intensidade</td>
									</tr>
								  </table>
								</td>
								<td style="vertical-align:middle" width="22%">
									<select class="form-control select2_category" id="form_class_intensidade" name="form_class_intensidade">
										<option value=""></option>
										<option value="1 - Leve" <?php if($row['class_intensidade']=='1 - Leve'){echo ' selected ';}?>>Leve</option>
										<option value="2 - Moderada" <?php if($row['class_intensidade']=='2 - Moderada'){echo ' selected ';}?>>Moderada</option>
										<option value="3 - Grave/Severa" <?php if($row['class_intensidade']=='3 - Grave/Severa'){echo ' selected ';}?>>Grave/Severa</option>
										<option value="4 - Comprometimento completo" <?php if($row['class_intensidade']=='4 - Comprometimento completo'){echo ' selected ';}?>>Comprometimento completo</option>
										<option value="5 - Nao especificado" <?php if($row['class_intensidade']=='5 - Nao especificado'){echo ' selected ';}?>>Nao especificado</option>
										<option value="6 - Nao aplicável" <?php if($row['class_intensidade']=='6 - Nao aplicável'){echo ' selected ';}?>>Nao aplicável</option>
									</select>
								</td>
							</tr>
							<tr>
								<td>
								  <table width="100%">
									<tr>
										<td class="tabela-body">Previsibilidade</td>
									</tr>
								  </table>
								</td>
								<td style="vertical-align:middle" width="22%">
									<select class="form-control select2_category" id="form_class_previsibilidade" name="form_class_previsibilidade">
										<option value=""></option>
										<option value="1 - Esperado" <?php if($row['class_previsibilidade']=='1 - Esperado'){echo ' selected ';}?>>Esperao</option>
										<option value="2 - Inesperado" <?php if($row['class_previsibilidade']=='2 - Inesperado'){echo ' selected ';}?>>Inesperado</option>
										<option value="3 - Não avaliável" <?php if($row['class_previsibilidade']=='3 - Não avaliável'){echo ' selected ';}?>>Não avaliável</option>
									</select>
								</td>
							</tr>
							<tr>
								<td>
								  <table width="100%">
									<tr>
										<td class="tabela-body">Causalidade</td>
									</tr>
								  </table>
								</td>
								<td style="vertical-align:middle" width="22%">
									<select class="form-control select2_category" id="form_class_causalidade" name="form_class_causalidade">
										<option value=""></option>
										<option value="1 - Definida" <?php if($row['class_causalidade']=='1 - Definida'){echo ' selected ';}?>>Definida</option>
										<option value="2 - Provável" <?php if($row['class_causalidade']=='2 - Provável'){echo ' selected ';}?>>Provável</option>
										<option value="3 - Possível" <?php if($row['class_causalidade']=='3 - Possível'){echo ' selected ';}?>>Possível</option>
										<option value="4 - Improvável" <?php if($row['class_causalidade']=='4 - Improvável'){echo ' selected ';}?>>Improvável</option>
										<option value="5 - Condicional / Não Classificada" <?php if($row['class_causalidade']=='5 - Condicional / Não Classificada'){echo ' selected ';}?>>Condicional / Não Classificada</option>
										<option value="6 - Não Acessível / Não Classificável" <?php if($row['class_causalidade']=='6 - Não Acessível / Não Classificável'){echo ' selected ';}?>>Não Acessível / Não Classificável</option>
									</select>
								</td>
							</tr>
							<tr>
								<td colspan="2" class="tabela-head">Conduta Imediata</td>
							</tr>
							<tr>
								<td>
								  <table width="100%">
									<tr>
										<td class="tabela-body">Ação quanto ao Produto sob Investigação</td>
									</tr>
								  </table>
								</td>
								<td style="vertical-align:middle" width="22%">
									<select class="form-control select2_category" id="form_cond_acao_psi" name="form_cond_acao_psi">
										<option value=""></option>
										<option value="1 - Nenhuma ação" <?php if($row['cond_acao_psi']=='1 - Nenhuma ação'){echo ' selected ';}?>>Nenhuma ação</option>
										<option value="2 - Interrupção temporária" <?php if($row['cond_acao_psi']=='2 - Interrupção temporária'){echo ' selected ';}?>>Interrupção temporária</option>
										<option value="3 - Suspensão" <?php if($row['cond_acao_psi']=='3 - Suspensão'){echo ' selected ';}?>>Suspensão</option>
										<option value="9 - Não Aplicável" <?php if($row['cond_acao_psi']=='9 - Não Aplicável'){echo ' selected ';}?>>Não Aplicável</option>
									</select>
								</td>
							</tr>
							<tr>
								<td>
								  <table width="100%">
									<tr>
										<td class="tabela-body">Tratamento</td>
									</tr>
								  </table>
								</td>
								<td style="vertical-align:middle" width="22%">
									<select class="form-control select2_category" id="form_cond_tratamento" name="form_cond_tratamento">
										<option value=""></option>
										<option value="1 - Tratamento Farmacológico" <?php if($row['cond_tratamento']=='1 - Tratamento Farmacológico'){echo ' selected ';}?>>Tratamento Farmacológico</option>
										<option value="2 - Nenhum/Observação" <?php if($row['cond_tratamento']=='2 - Nenhum/Observação'){echo ' selected ';}?>>Nenhum/Observação</option>
										<option value="3 - Outros" <?php if($row['cond_tratamento']=='3 - Outros'){echo ' selected ';}?>>Outros</option>
										<option value="4 - Retirada do Estudo" <?php if($row['cond_tratamento']=='4 - Retirada do Estudo'){echo ' selected ';}?>>Retirada do Estudo</option>
										<option value="9 - Não Aplicável" <?php if($row['cond_tratamento']=='9 - Não Aplicável'){echo ' selected ';}?>>Não Aplicável</option>
									</select>
								</td>
							</tr>
							<tr>
								<td colspan="2" class="tabela-head">Término</td>
							</tr>
							<tr>
								<td>
								  <table width="100%">
									<tr>
										<td class="tabela-body">Data</td>
									</tr>
								  </table>
								</td>
								<td style="vertical-align:middle" width="22%">
									<input type="text" class="form-control" id="form_term_data" name="form_term_data" value="<?=$row['term_data'];?>" <?=$centralizado;?> onkeyUp="mascara(this, '##/##/####');">
								</td>
							</tr>
							<tr>
								<td>
								  <table width="100%">
									<tr>
										<td class="tabela-body">Hora</td>
									</tr>
								  </table>
								</td>
								<td style="vertical-align:middle" width="22%">
									<input type="text" class="form-control" id="form_term_hora" name="form_term_hora" value="<?=$row['term_hora'];?>" <?=$centralizado;?> onkeyUp="mascara(this, '##:##');">
								</td>
							</tr>
							<tr>
								<td>
								  <table width="100%">
									<tr>
										<td class="tabela-body">Desfecho</td>
									</tr>
								  </table>
								</td>
								<td style="vertical-align:middle" width="22%">
									<select class="form-control select2_category" id="form_term_desfecho" name="form_term_desfecho">
										<option value=""></option>
										<option value="1 - Resolvido" <?php if($row['term_desfecho']=='1 - Resolvido'){echo ' selected ';}?>>Resolvido</option>
										<option value="2 - Resolvido com sequelas" <?php if($row['term_desfecho']=='2 - Resolvido com sequelas'){echo ' selected ';}?>>Resolvido com Sequelas</option>
										<option value="3 - Ainda não resolvido" <?php if($row['term_desfecho']=='3 - Ainda não resolvido'){echo ' selected ';}?>>Ainda não resolvido</option>
										<option value="4 - Não Resolvido" <?php if($row['term_desfecho']=='4 - Não Resolvido'){echo ' selected ';}?>>Não Resolvido</option>
										<option value="5 - Óbito" <?php if($row['term_desfecho']=='5 - Óbito'){echo ' selected ';}?>>Óbito</option>
										<option value="9 - Desconhecido" <?php if($row['term_desfecho']=='9 - Desconhecido'){echo ' selected ';}?>>Desconhecido</option>
									</select>
								</td>
							</tr>
							<tr>
								<td>
								  <table width="100%">
									<tr>
										<td class="tabela-body">Detalhes</td>
									</tr>
								  </table>
								</td>
								<td style="vertical-align:middle" width="22%">
									<textarea class="form-control" id="form_term_detalhes" rows="5" name="form_term_detalhes"><?=$row['term_detalhes'];?></textarea>
								</td>
							</tr>
							<tr>
								<td colspan="2" class="tabela-head">
									<div class="botal" style="text-align:center">
										<button type="button" class="btn btn-default" onclick="javascript:salvar(<?=$row['id'];?>);">Salvar informações</button>
										<button type="button" class="btn btn-default" onclick="javascript:deletar(<?=$row['id'];?>);">Deletar medicamento</button>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</form>
			</div>
		</div>
		<?php
		}
	}
	if($act=="ECRF_SUB_HC"){
		$centro = $_GET['centro'];
		$paciente = $_GET['paciente'];
		$nro = $_GET['nro'];
		if($nro==0){$nro=1;}
		$sub_tabela = "crf_sub_hc";
		$query_med = "select * from $sub_tabela where paciente = ".$paciente." and centro = ".$centro." order by id";
		$res_med   = $conexao->pesquisa($query_med);
		if(mysqli_num_rows($res_med)==0){
		?>
			<div class="portlet light">
				<div class="portlet-title fixo">
					<div class="actions">
						<a href="javascript:novo();" class="btn btn-circle btn-default btn-sm">
							<i class="fa fa-plus"></i> Nova história médica </a>
					</div>
				</div>
				<div class="portlet-body">
					<h5>Nenhuma história cadastrada...</h5>
				</div>
			</div>
		<?php
		}else{
		?>
		<div class="portlet light">
			<div class="portlet-title fixo">
				<div class="caption" style="text-align:center">
					<div class="btn-toolbar margin-bottom-10" style="text-align:center">
						<div class="btn-group">
							<?php
								$indMed = 1;
								while($row_med = mysqli_fetch_assoc($res_med)){
									if(($indMed==$nro)){
										echo '<button type="button" class="btn blue" onclick="javascript:carrega('.$indMed.')">'.$indMed.'</button>';
									}else{
										echo '<button type="button" class="btn btn-default" onclick="javascript:carrega('.$indMed.')">'.$indMed.'</button>';
									}
									$indMed++;
								}
							?>
						</div>
					</div>
				</div>
				<div class="actions">
					<a href="javascript:novo();" class="btn btn-circle btn-default btn-sm">
						<i class="fa fa-plus"></i> Novo </a>
				</div>
			</div>
			<div class="portlet-body">
				<?php
					$query = "select * from $sub_tabela where centro = $centro and paciente = $paciente order by id";
					$res   = $conexao->pesquisa($query);
					$i=1;
					for($i=1;$i<=$nro;$i++){
						$row = mysqli_fetch_assoc($res);
					}
				?>
				<div class="portlet-body" >
					<form class="form-horizontal" role="form" action="" method="post" id="form_info" name="form_info">
						<table width="100%" class="table table-striped" style="font-family: 'Abel';font-size: 22px;">
							<thead>
								<th class="tabela-head" width="30%">#<?=$nro;?></th>
								<th class="tabela-head" ><center></center></th>
							</thead>
						   <tbody>
								<tr>
									<td>
									  <table width="100%">
										<tr>
											<td class="tabela-body">Descriçao</td>
										</tr>
									  </table>
									</td>
									<td style="vertical-align:middle" width="22%">
										<input type="text" class="form-control" id="form_descricao" name="form_descricao" value="<?=$row['descricao'];?>" <?=$centralizado;?>>
									</td>
								</tr>
								<tr>
									<td>
									  <table width="100%">
										<tr>
											<td class="tabela-body">Data de início</td>
										</tr>
									  </table>
									</td>
									<td style="vertical-align:middle" width="22%">
										<input type="text" class="form-control" id="form_data_inicio" name="form_data_inicio" value="<?=$row['data_inicio'];?>" <?=$centralizado;?> onkeyUp="mascara(this, '##/##/####');">
									</td>
								</tr>
								<tr>
									<td>
									  <table width="100%">
										<tr>
											<td class="tabela-body">Em tratamento?</td>
										</tr>
									  </table>
									</td>
									<td style="vertical-align:middle" width="22%">
										<select class="form-control select2_category" id="form_em_tratamento" name="form_em_tratamento">
											<option value=""></option>
											<option value="1 - Sim" <?php if($row['em_tratamento']=='1 - Sim'){echo ' selected ';}?>>Sim</option>
											<option value="2 - Não" <?php if($row['em_tratamento']=='2 - Não'){echo ' selected ';}?>>Não</option>
										</select>
									</td>
								</tr>
								<tr>
									<td>
									  <table width="100%">
										<tr>
											<td class="tabela-body">Data de término</td>
										</tr>
									  </table>
									</td>
									<td style="vertical-align:middle" width="22%">
										<input type="text" class="form-control" id="form_data_termino" name="form_data_termino" value="<?=$row['data_termino'];?>" <?=$centralizado;?>  onkeyUp="mascara(this, '##/##/####');">
									</td>
								</tr>
								<tr>
									<td colspan="2" class="tabela-head">
										<div class="botal" style="text-align:center">
											<button type="button" class="btn btn-default" onclick="javascript:salvar(<?=$row['id'];?>);">Salvar</button>
											<button type="button" class="btn btn-default" onclick="javascript:deletar(<?=$row['id'];?>);">Deletar</button>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
					</form>
				</div>
			</div>
		</div>
		<?php
		}
	}



	if($act=="ECRF_MED_CONCOMITANTE"){
		$centro = $_GET['centro'];
		$paciente = $_GET['paciente'];
		$nro = $_GET['nro'];
		if($nro==0){$nro=1;}
		$query_med = "select * from crf_med_concomitante where paciente = ".$paciente." and centro = ".$centro." order by id";
		$res_med   = $conexao->pesquisa($query_med);
		if(mysqli_num_rows($res_med)==0){
			echo "<h5>Nenhum medicamento cadastrado...</h5>";
		}else{
			echo '<div id="paginacao" style="text-align:center">
					<ul class="pagination">
				';
				$indMed = 1;
				while($row_med = mysqli_fetch_assoc($res_med)){
					echo '  <li ';
					if(($indMed==$nro)){echo ' class="active"';}
					echo '	>
								<a href="javascript:carrega_medicamentos('.$indMed.');"> '.$indMed.' </a>
							</li>
						';
					$indMed++;
				}
		echo	'
				</ul>
			</div>
			';
		?>
		<div id="medicacao" name="medicacao">
			<?php
				$query = "select * from crf_med_concomitante where centro = $centro and paciente = $paciente order by id";
				$res   = $conexao->pesquisa($query);
				$i=1;
				for($i=1;$i<=$nro;$i++){
					$row = mysqli_fetch_assoc($res);
				}
			?>
			<div class="portlet-body" >
				<form class="form-horizontal" role="form" action="" method="post" id="form_info" name="form_info">
					<table width="100%" class="table table-striped" style="font-family: 'Abel';font-size: 22px;">
						<thead>
							<th class="tabela-head" width="30%">Medicamento #<?=$nro;?></th>
							<th class="tabela-head" ><center></center></th>
						</thead>
					   <tbody>
							<tr>
								<td>
								  <table width="100%">
									<tr>
										<td class="tabela-body">Medicamento</td>
									</tr>
								  </table>
								</td>
								<td style="vertical-align:middle" width="22%">
									<input type="text" class="form-control" id="form_medicamento" name="form_medicamento" value="<?=$row['medicamento'];?>" <?=$centralizado;?>>
								</td>
							<tr>
							<tr>
								<td>
								  <table width="100%">
									<tr>
										<td class="tabela-body">Via de administração</td>
									</tr>
								  </table>
								</td>
								<td style="vertical-align:middle" width="22%">
									<input type="text" class="form-control" id="form_via_de_administracao" name="form_via_de_administracao" value="<?=$row['via_de_administracao'];?>" <?=$centralizado;?>>
								</td>
							<tr>
							<tr>
								<td>
								  <table width="100%">
									<tr>
										<td class="tabela-body">Dose</td>
									</tr>
								  </table>
								</td>
								<td style="vertical-align:middle" width="22%">
									<input type="text" class="form-control" id="form_dose_diaria" name="form_dose_diaria" value="<?=$row['dose_diaria'];?>" <?=$centralizado;?>>
								</td>
							<tr>
							<tr>
								<td>
								  <table width="100%">
									<tr>
										<td class="tabela-body">Posologia</td>
									</tr>
								  </table>
								</td>
								<td style="vertical-align:middle" width="22%">
									<input type="text" class="form-control" id="form_posologia" name="form_posologia" value="<?=$row['posologia'];?>" <?=$centralizado;?>>
								</td>
							<tr>
							<tr>
								<td>
								  <table width="100%">
									<tr>
										<td class="tabela-body">Indicação</td>
									</tr>
								  </table>
								</td>
								<td style="vertical-align:middle" width="22%">
									<select class="form-control select2_category" id="form_indicacao" name="form_indicacao">
										<option value=""></option>
										<option value="1 - Evento Adverso" <?php if($row['indicacao']=='1 - Evento Adverso'){echo ' selected ';}?>>Evento Adverso</option>
										<option value="2 - História Médica" <?php if($row['indicacao']=='2 - História Médica'){echo ' selected ';}?>>História Médica</option>
										<option value="3 - Outro" <?php if($row['indicacao']=='3 - Outro'){echo ' selected ';}?>>Outro</option>
									</select>
								</td>
							<tr>
							<tr>
								<td>
								  <table width="100%">
									<tr>
										<td class="tabela-body">Número<br><i>(Número do EA ou da História Médica)<i></td>
									</tr>
								  </table>
								</td>
								<td style="vertical-align:middle" width="22%">
									<input type="text" class="form-control" id="form_nro_indicacao" name="form_nro_indicacao" value="<?=$row['nro_indicacao'];?>" <?=$centralizado;?>>
								</td>
							<tr>
							<tr>
								<td>
								  <table width="100%">
									<tr>
										<td class="tabela-body">Outra indicaçao</td>
									</tr>
								  </table>
								</td>
								<td style="vertical-align:middle" width="22%">
									<textarea rows="3" class="form-control" id="form_outra" name="form_outra"><?=$row['outra'];?></textarea>
								</td>
							<tr>
							<tr>
								<td>
								  <table width="100%">
									<tr>
										<td class="tabela-body">Data de início</td>
									</tr>
								  </table>
								</td>
								<td style="vertical-align:middle" width="22%">
									<input type="text" class="form-control" id="form_data_inicio" name="form_data_inicio" <?=$centralizado;?> value="<?=$row['data_inicio'];?>" onkeyUp="mascara(this, '##/##/####');">
								</td>
							<tr>
							<tr>
								<td>
								  <table width="100%">
									<tr>
										<td class="tabela-body">Em andamento?</td>
									</tr>
								  </table>
								</td>
								<td style="vertical-align:middle" width="22%">
									<select class="form-control select2_category" id="form_em_andamento" name="form_em_andamento">
										<option value=""></option>
										<option value="1 - Sim" <?php if($row['em_andamento']=='1 - Sim'){echo ' selected ';}?>>Sim</option>
										<option value="2 - Não" <?php if($row['em_andamento']=='2 - Não'){echo ' selected ';}?>>Não</option>
									</select>
								</td>
							<tr>
							<tr>
								<td>
								  <table width="100%">
									<tr>
										<td class="tabela-body">Data de término</td>
									</tr>
								  </table>
								</td>
								<td style="vertical-align:middle" width="22%">
									<input type="text" class="form-control" id="form_data_fim" name="form_data_fim" <?=$centralizado;?> value="<?=$row['data_fim'];?>" onkeyUp="mascara(this, '##/##/####');">
								</td>
							<tr>
							<tr>
								<td colspan="2" class="tabela-head">
									<div class="botal" style="text-align:center">
										<button type="button" class="btn btn-default" onclick="javascript:salvar(<?=$row['id'];?>);">Salvar informações</button>
										<button type="button" class="btn btn-default" onclick="javascript:deletar(<?=$row['id'];?>);">Deletar medicamento</button>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</form>
			</div>
		</div>
		<?php
		}
	}
	if($act=="TRAZ_PROX_VISITA"){   //Mudanças do checkbox dapágina de configuraçao de tipos usuários / menu
		$centro = $_GET['centro'];
		$query = "SELECT max(nro_visita) +1 as prox_visita FROM agenda where projeto = ".$_SESSION['projeto']." and centro = '".$centro."'";;
		$res   = $conexao->pesquisa($query);
		$row 	 = mysqli_fetch_assoc($res);
		if($row['prox_visita']<1){
			$prox = 1;
		}else{
			$prox = $row['prox_visita'];
		}
		echo $prox;
	}
	if($act == "VALIDA_CRF"){
		$centro = $_GET['centro'];
		$paciente = $_GET['paciente'];
		//echo "Centro = " . $centro . "<br>";
		//echo "Paciente = " . $paciente;
		$query = "select * from crf_entrada_forms where centro = " . $centro . " and paciente = " . $paciente;
		$res_val = $conexao->pesquisa($query);
		while($row_val = mysqli_fetch_assoc($res_val)){
			$form_id = $row_val['id'];
			$query = "update crf_entrada_campos set log_usuario = " . $_SESSION['user_id'] . ", monitoria_status = '1' where crf_entrada_campos.valor = crf_entrada_campos.valor_dupla_digitacao and form_id = ".$form_id;
			$conexao->pesquisa($query);
		}
	}


	if($act == "LOG_AUDITORIA"){
		$tabela 	= $_GET['tabela'];
		$usuario	= $_GET['usuario'];
		$data_ini	= $_GET['data_inicial'];
		$data_fim	= $_GET['data_final'];

		$sql = "where id > 0 ";
		if($tabela!="login"){
			if($tabela!=""){
				$sql .= " and tabela = '$tabela' ";
			}
		}else{
			$sql .= " and tabela = 'usu_usuario' and campo = 'acesso_web' ";
		}
		if($usuario!=""){
			$sql .= " and nome_usuario = '$usuario' ";
		}
		if($data_ini!=""){
			$data_ini = explode("/", $data_ini);
			$data_ini = $data_ini[2]."-".$data_ini[1]."-".$data_ini[0];
			$sql .= " and data >= '$data_ini' ";
		}
		if($data_fim!=""){
			$data_fim = explode("/", $data_fim);
			$data_fim = $data_fim[2]."-".$data_fim[1]."-".$data_fim[0];
			$sql .= " and data <= '$data_fim' ";
		}


		$query = "select * from VW_AUDIT_TRAIL " . $sql;
		$res = $conexao->pesquisa($query);
		while($row = mysqli_fetch_assoc($res)){
			?>
				<tr>
					<td>
						<font size="2px">
							<?php
								if($tabela=="login"){echo "LOGIN";}else{echo $row['tabela'];}
							?>
						</font>
					</td>
					<td><font size="2px"><?=$row['nome_usuario'];?></font></td>
					<td><font size="2px">
						<center>
							<?php
								if($tabela=="login"){echo "LOGIN";}else{
									if($row['acao']=="A"){echo "ATUALIZAÇÃO";}
								}
							?>
						</center></font>
					</td>
					<td><font size="2px">
						<?php
							if(($tabela=="login")||($row['campo']=="acesso_web")){echo "LOGIN";}else{echo $row['campo'];}
						?>
						</font></td>
					<td><font size="2px"><?php
							if(($tabela=="login")||($row['campo']=="acesso_web")){echo "";}else{
								if($row['campo']!="senha"){
									echo $row['antigo'];
								}else{
									echo "****";
								}
							}
						?></font></td>
					<td><font size="2px"><?php
							if(($tabela=="login")||($row['campo']=="acesso_web")){echo "";}else{
								if(	$row['campo']!="senha"){
									echo $row['novo'];
								}else{
									echo "******";
								}
							}
						?></font></td>


					<td><center><font size="2px">
						<?php
							$data = explode(" ", $row['data_hora']);
							$hora = $data[1];
							$data = $data[0];
							$data = explode("-", $data);
							$data = $data[2] . "/" . $data[1] . "/" . $data[0];
							echo $data . "<br>" . $hora;
						?>
					</font></center></td>




				</tr>
			<?php
		}
	}


	if($act == "DASH_QUERY"){

	?>
		<div class="col-md-12">
			<div class="portlet light ">
				<div class="portlet-title">
					<div class="caption caption-md">
						<i class="icon-bar-chart font-dark hide"></i>
						<span class="caption-subject font-green-steel bold uppercase">Resumo das queries</span>
						<span class="caption-helper"></span>
					</div>
				</div>
				<div class="portlet-body">
					<div class="scroller" style="height:400px" data-rail-visible="1" data-rail-color="yellow" data-handle-color="#a1b2bd">
						<?php
							$query	= "select
										crf_entrada_campos_query.id,
										crf_entrada_campos_query.centro,
										crf_entrada_campos_query.datahora_abertura,
										usu_abertura.nome as abertura_nome,
										crf_entrada_campos_query.status,
										crf_entrada_campos_query.datahora_fechamento,
										usu_fechamento.nome as fechamento_nome
										from crf_entrada_campos_query
											inner join usu_usuario as usu_abertura on crf_entrada_campos_query.usuario_abertura = usu_abertura.id
											left join usu_usuario as usu_fechamento on crf_entrada_campos_query.usuario_fechamento = usu_fechamento.id

											order by centro, datahora_abertura desc
							";
							$res	= $conexao->pesquisa($query);
							if(mysqli_num_rows($res)==0){
								echo "<h5>Nenhum questionamento cadastrado....</h5>";
							}else{
								?>
								<div class="panel-group accordion" id="accordion1">
								<?php
								$indice = 1;
								while($row_q = mysqli_fetch_assoc($res)){
								?>

									<div class="panel panel-default">
										<div class="panel-heading">
											<h4 class="panel-title">
												<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#collapse_<?=$indice;?>">
													<table width="100%">
														<thead>
															<th width="5%"><font size="2px">#<?=$indice;?></font></th>
															<th width="20%"><font size="2px"><?php
																echo listoDATA($row_q['datahora_abertura']);
															?></font></th>

															<th width="20%"><font size="2px"><?php
																$query 		= "select * from usu_usuario where id = " . $row_q['usuario_abertura'];
																$res_usu 	= $conexao->pesquisa($query);
																$row_usu	= mysqli_fetch_assoc($res_usu);
																echo $row_usu['nome'];
															?></font>
															</th>
															<th width="*" style="text-align:right">
																<font size="2px">
																<?php
																	if($row_q['status']=="1"){
																		echo "PENDENTE";
																	}
																	if($row_q['status']=="2"){
																		echo "Fechado";
																	}
																	if($row_q['status']=="3"){
																		echo "CONCLUÍDO";
																	}
																?>
																</font>
															</th>
															<th style="text-align:right" width="20%">


															</th>
														</thead>
													</table>
												</a>
											</h4>
										</div>
										<div id="collapse_<?=$indice;?>" class="panel-collapse <?php
										if($indice==1){echo "in";}else{echo "collapse";}
										?>">
											<div class="panel-body">
												<div class="scroller" style="height: 238px;" data-always-visible="1" data-rail-visible1="0" data-handle-color="#D7DCE2">
													<div class="general-item-list">
														<div class="item" style="text-align:right">
															<?php
																if($row_q['status']=="1"){
															?>
																<button class="btn green-sharp btn-large" data-toggle="modal" data-target="#myModal"  href="modal_query_nova_resposta.php?id=<?=$row_q['id'];?>"><i class="fa fa-plus"></i> Novo ítem</button>&nbsp;
																<button class="btn green-sharp btn-large" onclick="javascript:query_fechar(<?=$row_q['id'];?>);">Finalizar</button>
															<?php
															}
															?>
														</div>
														<?php
														$query = "SELECT
														crf_entrada_campos_query_questionamentos.id,
														crf_entrada_campos_query_questionamentos.usuario_id,
														usu_usuario.nome as usuario_nome,
														crf_entrada_campos_query_questionamentos.query_id,
														crf_entrada_campos_query_questionamentos.datahora,
														crf_entrada_campos_query_questionamentos.tipo,
														crf_entrada_campos_query_questionamentos.mensagem

															FROM crf_entrada_campos_query_questionamentos
																inner join usu_usuario on crf_entrada_campos_query_questionamentos.usuario_id = usu_usuario.id
																where crf_entrada_campos_query_questionamentos.query_id = " . $row_q['id'];
														$res_item = $conexao->pesquisa($query);
														$indice_item = 1;
														while($row_item = mysqli_fetch_assoc($res_item)){
														?>

															<div class="item">
																<div class="item-head">
																	<div class="item-details">
																		<img class="item-pic rounded" src="imagens/ico_avatar.png">
																		<a href="#" class="item-name primary-link"><?=$row_item['usuario_nome'];?></a>
																		<span class="item-label"><?=listoDATAHORA($row_item['datahora']);?></span>
																	</div>

																</div>
																<div class="item-body">
																	<?=$row_item['mensagem'];?>
																</div>
															</div>
														<?php
															$indice_item++;
														}
														?>
													</div>
												</div>
											</div>
										</div>
									</div>
								<?php
									$indice++;
								}
							?>
								</div>
							<?php
							}

						?>
					</div>
				</div>
			</div>
		</div>
	<?php
	}
	if($act == "DASH_SUMARIO"){
	?>
		<div class="col-lg-12 col-xs-12 col-sm-12">
			<div class="row" style="align:right">
				<div style="align:right" class="pull-right">
					<button type="button" class="btn btn-default" onclick="fnExcelReport()"><img src="imagens/ico_excel.gif" width="20"></button>
				</div>
			</div>
			<div class="row">
				<table class="table" id="sumario" name="sumario">
					<thead>
						<th style="font-size:10px"><center>Centro</center></th>
						<th style="font-size:10px"><center># Participante</center></th>
						<th style="font-size:10px"><center>% 1 Digitaçao</center></th>
						<th style="font-size:10px"><center>% 2 Digitaçao</center></th>
						<th style="font-size:10px"><center>% Validado</center></th>
						<th style="font-size:10px"><center># Queries</center></th>
						<th style="font-size:10px"><center># Queries Abertas</center></th>
						<th style="font-size:10px"><center># Comentários</center></th>
						<th style="font-size:10px"><center>Data início<br>digitaçao</center></th>
						<th style="font-size:10px"><center>Data fim<br>digitaçao</center></th>
						<th style="font-size:10px"><center>Data de<br>travamento</center></th>
						<th></th>
					</thead>
					<tbody>
						<?php
							$query = "select  ";
							$query .= "	crf_paciente_pre_rand.centro, crf_paciente_pre_rand.nro_participante, crf_paciente_pre_rand.nro_paciente, ";
							$query .= "	    VW_DASH_SUM_DATA.inicio, VW_DASH_SUM_DATA.fim, VW_DASH_SUM_TRAVADO.travado, VW_DASH_SUM_TOT_DIGITADO.qtd as total, ";
							$query .= "		VW_DASH_SUM_P_DIG.qtd as qtd_P_dig, VW_DASH_SUM_S_DIG.qtd as qtd_S_dig, VW_DASH_SUM_MONITORADO.qtd as qtd_monitorado, ";
							$query .= "		VW_DASH_SUM_QUERIES.qtd as qtd_query, VW_DASH_SUM_QUERIES_AB.qtd as qtd_query_ab, VW_DASH_SUM_COM.qtd as qtd_comentarios ";
							$query .= "	    from crf_paciente_pre_rand  ";
							$query .= "	    	left join VW_DASH_SUM_DATA on crf_paciente_pre_rand.centro = VW_DASH_SUM_DATA.centro and  crf_paciente_pre_rand.nro_paciente = VW_DASH_SUM_DATA.paciente ";
							$query .= "	    	left join VW_DASH_SUM_TRAVADO on crf_paciente_pre_rand.centro = VW_DASH_SUM_TRAVADO.centro and  crf_paciente_pre_rand.nro_paciente = VW_DASH_SUM_TRAVADO.paciente ";
							$query .= "	    	left join VW_DASH_SUM_TOT_DIGITADO on crf_paciente_pre_rand.centro = VW_DASH_SUM_TOT_DIGITADO.centro and  crf_paciente_pre_rand.nro_paciente = VW_DASH_SUM_TOT_DIGITADO.paciente ";
							$query .= "	    	left join VW_DASH_SUM_P_DIG on crf_paciente_pre_rand.centro = VW_DASH_SUM_P_DIG.centro and  crf_paciente_pre_rand.nro_paciente = VW_DASH_SUM_P_DIG.paciente ";
							$query .= "	    	left join VW_DASH_SUM_S_DIG on crf_paciente_pre_rand.centro = VW_DASH_SUM_S_DIG.centro and  crf_paciente_pre_rand.nro_paciente = VW_DASH_SUM_S_DIG.paciente ";
							$query .= "	    	left join VW_DASH_SUM_MONITORADO on crf_paciente_pre_rand.centro = VW_DASH_SUM_MONITORADO.centro and  crf_paciente_pre_rand.nro_paciente = VW_DASH_SUM_MONITORADO.paciente ";
							$query .= "	    	left join VW_DASH_SUM_QUERIES on crf_paciente_pre_rand.centro = VW_DASH_SUM_QUERIES.centro and  crf_paciente_pre_rand.nro_paciente = VW_DASH_SUM_QUERIES.paciente ";
							$query .= "	    	left join VW_DASH_SUM_QUERIES_AB on crf_paciente_pre_rand.centro = VW_DASH_SUM_QUERIES_AB.centro and  crf_paciente_pre_rand.nro_paciente = VW_DASH_SUM_QUERIES_AB.paciente ";
							$query .= "	    	left join VW_DASH_SUM_COM on crf_paciente_pre_rand.centro = VW_DASH_SUM_COM.centro and  crf_paciente_pre_rand.nro_paciente = VW_DASH_SUM_COM.paciente ";
							$query .= "	    	order by centro, nro_participante";
							$res   = $conexao->pesquisa($query);
							while($row = mysqli_fetch_assoc($res)){
								//Total
								$total   = $row['total'];
							?>
								<tr>
									<td style="font-size:10px"><center><?=$row['centro'];?></center></td>
									<td style="font-size:10px"><center><a name="ANCORA_<?=$row['nro_participante'];?>"></a><?=$row['nro_participante'];?></center></td>
									<td style="font-size:10px"><center>
									<?php
										//1 Digitaçao
										$monitorado = $row['qtd_P_dig'];
										$porc = number_format(($monitorado*100)/$total,2);
										echo $porc ."%";
									?>
									</td>
									<td style="font-size:10px"><center>
									<?php
										//2 Digitaçao
										$monitorado = $row['qtd_S_dig'];
										$porc = number_format(($monitorado*100)/$total,2);
										echo $porc ."%";
									?>
									</td>
									<td style="font-size:10px"><center>
									<?php
										//Monitorado
										$monitorado = $row['qtd_monitorado'];
										$porc = number_format(($monitorado*100)/$total,2);
										echo $porc ."%";
									?>
									</center></td>
									<td style="font-size:10px"><center>
									<?php
										$total   = $row['qtd_query'];
										if($total == ""){$total = 0;}
										echo $total;
									?>
									</center></td>
									<td style="font-size:10px"><center>
									<?php
										$total   = $row['qtd_query_ab'];
										if($total == ""){$total = 0;}
										echo $total;
									?>
									</center></td>
									<td style="font-size:10px"><center>
									<?php
										$total   = $row['qtd_comentarios'];
										if($total == ""){$total = 0;}
										echo $total;
									?>
									</center></td>
									<td style="font-size:10px"><center><?=$row['inicio'];?></center></td>
									<td style="font-size:10px"><center><?=$row['fim'];?></center></td>
									<td style="font-size:10px"><center><?=$row['travado'];?></center></td>
									<td>
									<button type="button" class="btn btn-default " onclick="javascript:muda_pagina('ecrf_formularios.php?cGFj=<?=$row['nro_paciente'];?>&xxpt=<?=$row['centro'];?>#TOPO');"><i class="fa fa-file-text-o"></i></button>
									<button type="button" class="btn btn-default " onclick="javascript:valida_crf(<?=$row['nro_paciente'];?>,<?=$row['centro'];?>);"><i class="fa fa-check-circle-o" title="Validar"></i></button>
									<!--<button type="button" class="btn btn-default " alt="Gerar Queries" title="Gerar Queries" data-toggle="modal" data-target="#modal_query" href="modal_query_print.php?tipo=VOL&centro=<?=$row['centro'];?>&paciente=<?=$row['nro_participante'];?>"  ><i class="fa fa-id-card-o"></i></button>-->
									<button type="button" class="btn btn-default " alt="Gerar Queries" title="Gerar Queries" onclick="javascript:gera_query(<?=$row['nro_participante'];?>,<?=$row['centro'];?>);;"  ><i class="fa fa-id-card-o"></i></button>
									</td>
								</tr>
							<?php
							}
						?>
					</tbody>
				</table>
			</div>
		</div>
	<?php
	}
	if($act=="QUERY_GERA"){
		$tipo = $_GET['tipo'];
		if($tipo=="VOL"){
			unlinkRecursive( '/home/spc/public_html/dapa/query', false );
			$query = "select * from vw_query_completa where nro_participante = '".$_GET['paciente']."'";
			$res   = $conexao->pesquisa($query);
			unset($arquivos);
			unset($arquivo);
			while($row = mysqli_fetch_assoc($res)){
				require_once __DIR__ . '/vendor/autoload.php';
				$mpdf = new \Mpdf\Mpdf(array('tempDir' => __DIR__ . '/temp', 'debug' => true, 'mode' => 'utf-8', 'margin-top' => 1158, 'margin-header' => 1500 ));
				$mpdf->setAutoTopMargin = 'stretch';
				$mpdf->allow_charset_conversion=true;
				$mpdf->in_charset='windows-1252';
				$file = file_get_contents('modelo_query.php');
				$file = str_replace("%QUERY_NRO%",$row['query_nro'], $file);
				$file = str_replace("%QUERY_CENTRO%",$row['query_centro'], $file);
				$file = str_replace("%QUERY_PARTICIPANTE%",$row['nro_participante'], $file);
				$file = str_replace("%QUERY_FORMULARIO_NOME%",$row['FORMULARIO_NOME'], $file);
				$file = str_replace("%QUERY_TEXTO_CAMPO%",$row['TEXTO_CAMPO'], $file);
				$file = str_replace("%QUERY_PERGUNTA%",$row['query_pergunta'], $file);
				$file = str_replace("%QUERY_QUESTIONAMENTO%",$row['responsavel_questionamento'], $file);
				$query_data = $row['query_data_hora'];
				$query_data = explode(" ", $query_data);
				$query_data = $query_data[0];
				$query_data = explode("-", $query_data);
				$query_data = $query_data[2] . "/". $query_data[1] . "/" . $query_data[0];
				$file = str_replace("%QUERY_DATA%",$query_data, $file);
				$mpdf->WriteHTML(utf8_encode($file));
				$mpdf->charset_in='UTF-8';
				$mpdf->Output('query/'.$row['query_nro'].'.pdf','F');
				$arquivos[] = 'query/'.$row['query_nro'].'.pdf';
				$arquivo[] = $row['query_nro'].'.pdf';
				unset($mpdf);
			}
			//Compactar arquivos
			$diretorio = '/home/spc/public_html/dapa/query';
			$zip = new ZipArchive();
			if($zip->open($diretorio.'/download.zip', ZIPARCHIVE::CREATE) == TRUE){
				// Insere os arquivos que devem conter no arquivo zip
				for($i=0;$i<count($arquivos);$i++){
					$zip->addFile($arquivos[$i],$arquivo[$i]);
				}
			}
			$zip->close();
			echo "<center><a href='query/download.zip'><img src='imagens/download_zip.ico' width='100'></a></center>";
		}
	}
	if($act == "SALVAR_MUDANCA_QUERY"){
		$campo = $_GET['campo'];
		$valor = $_GET['valor'];
		$query = "update crf_entrada_campos_pergunta set log_usuario = '$id_usuario', pergunta = '$valor' where id = " . $campo;
		$conexao->pesquisa($query);
	}
	if($act == "DELETAR_QUERY"){
		$campo = $_GET['campo'];
		$valor = $_GET['valor'];
		$query = "delete from crf_entrada_campos_pergunta where id = " . $campo;
		$conexao->pesquisa($query);
	}
	if($act == "SALVAR_NOVA_QUERY"){
		$campo = $_GET['campo'];
		$valor = $_GET['valor'];
		$centro = $_GET['centro'];
	   $query = "select * from configuracoes";
	   $res_config = $conexao->pesquisa($query);
	   $row_config = mysqli_fetch_assoc($res_config);
	   $nro_query = $row_config['nro_query']+1;
	   $nro_query = "AU" . $nro_query;
	   $query = "update configuracoes set nro_query = nro_query + 1";
	   $conexao->pesquisa($query);
	   $nro_query = $tipo . str_pad($nro_query, 4, '0', STR_PAD_LEFT);
	   $query = "insert into crf_entrada_campos_pergunta (responsavel_questionamento, nro_query, tipo, pergunta_id, centro, usuario_inicial, campo_id, pergunta, data_hora, log_usuario) values ('".$_SESSION['nome_usuario']."', '$nro_query', 'AU', 0,".$centro.", ".$_SESSION['user_id'].", $campo, '".$valor."', now(), ".$_SESSION['user_id'].");";
       echo $query;
	   $conexao->pesquisa($query);
	}
	if($act == "MUDA_MONITORIA_AGENDA"){
		$campo = $_GET['campo'];
		if($campo>0){
			$query = "select monitoria_status from crf_agenda_campos where id = " . $campo;
			$res   = $conexao->pesquisa($query);
			if(mysqli_num_rows($res)>0){
				$row   = mysqli_fetch_assoc($res);
				if($row['monitoria_status']==0){	//Ainda nao esta monitorado
					$query = "update crf_agenda_campos set log_usuario = " . $_SESSION['user_id'] . ", monitoria_status = 1 where id = " . $campo;
					$volta = 'OK|Item marcado como monitorado com sucesso|<i id="retorno'.$campo.'" name="retorno'.$campo.'" class="fa fa-check-square-o fa-sm" title="Monitorado"></i>|1';
				}
				if($row['monitoria_status']==1){	//Já esta monitorado
					$query = "update crf_agenda_campos set log_usuario = " . $_SESSION['user_id'] . ", monitoria_status = 0 where id = " . $campo;
					$volta = 'OK|Item desmarcado como monitorado com sucesso|<i id="retorno'.$campo.'" name="retorno'.$campo.'" class="fa fa-square-o fa-sm" title="Nao monitorado"></i>|0';
				}
				$conexao->pesquisa($query);
				echo $volta;
			}
		}
	}
	if($act == "MUDA_ERRO"){
		$campo = $_GET['campo'];
		if($campo>0){
			$query = "select erro_status from crf_entrada_campos where id = " . $campo;
			$res   = $conexao->pesquisa($query);
			if(mysqli_num_rows($res)>0){
				$row   = mysqli_fetch_assoc($res);
				if($row['erro_status']==0){	//Ainda nao tem erro
					$query = "update crf_entrada_campos set log_usuario = " . $_SESSION['user_id'] . ", erro_status = 1 where id = " . $campo;
					$volta = 'OK|Item marcado como com erro com sucesso|<i id="retorno_erro'.$campo.'" name="retorno_erro'.$campo.'" class="fa fa-file fa-sm" title="Com erro"></i>|1';

				}
				if($row['erro_status']==1){	//Já esta com erro
					$query = "update crf_entrada_campos set log_usuario = " . $_SESSION['user_id'] . ", erro_status = 0 where id = " . $campo;
					$volta = 'OK|Item desmarcado como com erro com sucesso|<i id="retorno_erro'.$campo.'" name="retorno_erro'.$campo.'" class="fa fa-file-o fa-sm" title="Nao monitorado"></i>|0';
				}
				$conexao->pesquisa($query);
				echo $volta;
			}
		}
	}
	if($act == "MUDA_ERRO_AGENDA"){
		$campo = $_GET['campo'];
		if($campo>0){
			$query = "select erro_status from crf_agenda_campos where id = " . $campo;
			$res   = $conexao->pesquisa($query);
			if(mysqli_num_rows($res)>0){
				$row   = mysqli_fetch_assoc($res);
				if($row['erro_status']==0){	//Ainda nao tem erro
					$query = "update crf_agenda_campos set log_usuario = " . $_SESSION['user_id'] . ", erro_status = 1 where id = " . $campo;
					$volta = 'OK|Item marcado como com erro com sucesso|<i id="retorno_erro'.$campo.'" name="retorno_erro'.$campo.'" class="fa fa-thumbs-down fa-sm" title="Com erro"></i>|1';

				}
				if($row['erro_status']==1){	//Já esta com erro
					$query = "update crf_agenda_campos set log_usuario = " . $_SESSION['user_id'] . ", erro_status = 0 where id = " . $campo;
					$volta = 'OK|Item desmarcado como com erro com sucesso|<i id="retorno_erro'.$campo.'" name="retorno_erro'.$campo.'" class="fa fa-thumbs-o-up fa-sm" title="Nao monitorado"></i>|0';
				}
				$conexao->pesquisa($query);
				echo $volta;
			}
		}
	}
	if($act == "MUDA_COMENTARIO"){
		$campo = $_GET['campo'];
		if($campo>0){
			$query = "select comentario from crf_agenda_campos where id = " . $campo;
			$res   = $conexao->pesquisa($query);
			$row = mysqli_fetch_assoc($res);
			if(trim($row['comentario'])==""){
				$volta = '<i class="fa fa-commenting-o fa-sm" title="Sem comentários"></i>';
			}else{
				$volta = '<i class="fa fa-commenting fa-sm font-green-steel" title="Com comentários"></i>';
			}
			echo $volta;
		}
	}



	if($act=="AGENDA_MUDA_COMENTARIO"){
		$id = $_GET['id'];
		if(isset($_POST['comentario'])){
			$comentario = $_POST['comentario'];
		}else{
			$comentario = $_GET['comentario'];
		}
		$comentario = addslashes($comentario);
		$comentario = utf8_decode($comentario);
		$query = "update crf_agenda_campos set comentario = '$comentario' where id = " . $id;
		$conexao->pesquisa($query);
		echo $query;
	}
	if($act=="AGENDA_MUDA_COMENTARIO_REVISOR"){
		$id = $_GET['id'];
		if(isset($_POST['comentario'])){
			$comentario = $_POST['comentario'];
		}else{
			$comentario = $_GET['comentario'];
		}
		$comentario = addslashes($comentario);
		$comentario = utf8_decode($comentario);
		if(isset($_POST['status'])){
			$status = $_POST['status'];
		}else{
			$status = $_GET['status'];
		}
		$query = "update crf_agenda_campos set
					comentario_revisor = '$comentario',
					comentario_revisor_status = '$status'
						where id = " . $id;
		$conexao->pesquisa($query);
		if($comentario==""){
			echo '<i class="fa fa-share-square-o fa-sm " title="Comentários do Revisor"></i>';
		}else{
			echo '<i class="fa fa-share-square fa-sm font-green-steel" title="Comentários do Revisor"></i>';
		}
	}

	if($act == "LISTA_CAMPOS"){
		$formulario = $_GET['formulario'];
		$query = "SELECT * FROM crf_forms_campos where formulario = ".$formulario." order by ordem";
		$res   = $conexao->pesquisa($query);
		$retorno = "<option value=''>Selecione o campo</option>";
		while($row = mysqli_fetch_assoc($res)){
			$retorno .= "<option value='".$row['id']."'>".$row['texto']."</option>";
		}
		echo $retorno;
	}
	if($act == "LISTA_CAMPOS_CAMPOS"){
		 $formulario = $_GET['formulario'];
		 $query = "select * from cfg_exportacoes_tipos_campos where formulario = $formulario order by id";
		 $resUsua = $conexao->pesquisa($query);
		 while($rowUsua = mysqli_fetch_assoc($resUsua)){
			 echo '<li class=list-group-item><a href=javascript:DEL_CAMPO("'.$rowUsua['campo'].'")>';
			 echo $rowUsua['campo'].'</a></li>';
		 }
		 echo "<a name='fim'></a>";
	}
	if($act == "ADD_CAMPO_EXPORT"){
		$campo = $_GET['campo'];
		$formulario = $_GET['formulario'];
		$query = "insert into cfg_exportacoes_tipos_campos (formulario, campo) values ($formulario,'$campo');";
		$res   = $conexao->pesquisa($query);
		echo $query;
	}
	if($act=="MOSTRA_VARIAVEIS"){
		$form_id = $_GET['formulario'];
		$query = "select nome, texto from crf_forms_campos where tipo <> 'divisao' and formulario = " . $form_id . " order by ordem";
		$res   = $conexao->pesquisa($query);
		//REPETICAO
		echo '<li class=list-group-item><a href=javascript:add("MULTIPLASLINHAS")>MULTIPLASLINHAS</a></li>';
		while($row = mysqli_fetch_assoc($res)){
			echo '<li class=list-group-item><a href=javascript:add("'.$row["nome"].'")>'.$row["nome"].'</a></li>';
		}
	}
	if($act=="EXPORTACAO"){	//Gera tabela para exportaçao
	$filtro = $_GET['filtro'];
	$tipo   = $_GET['tipo'];
	$resposta = $_GET['resposta'];
	if($tipo==1){
		?>
		<table class="table table-hover table-striped table-bordered" name="tabela" id="tabela" >
			<thead>
		  	<tr>
				<th>Formulário</th>
				<th>Campo</th>
				<th>Nome variável</th>
				<th>Obrigatório</th>
				<th>Tipo</th>
				<th>Casas decimais</th>
				<th>Valores Aceitos</th>
				<th>Formato</th>
				<th>Legenda</th>
				<th>Regra para alerta</th>
			</tr>
		  </thead>
		  <tbody>
		<?php
		$query = "SELECT id, ordem, texto_crf FROM crf_forms order by ordem";
		$res1  = $conexao->pesquisa($query);
		while($row1 = mysqli_fetch_assoc($res1)){
			echo '		<tr>';
			echo '			<td colspan="10"> ';
								echo $row1['texto_crf'];
			echo '			</td>';
			echo '		</tr> ';
			$query2 = "select ordem, id, nome, texto, tipo, obrigatorio, qtd_decimal, selecao_valores, formato, legenda, condicional_regra_alerta from crf_forms_campos where formulario = " . $row1['id'] . " order by ordem ";
			//echo $query2;
			$res2   = $conexao->pesquisa($query2);
			while($row2 = mysqli_fetch_assoc($res2)){
				?>
					<tr>
						<td></td>
						<td><font size="1px"><?=$row2['texto'];?></font></td>
						<td><font size="1px"><?=$row2['nome'];?></font></td>
						<td><font size="1px"><?=$row2['obrigatorio'];?></font></td>
						<td><font size="1px"><?=$row2['tipo'];?></font></td>
						<td><font size="1px"><?=$row2['qtd_decimal'];?></font></td>
						<td><font size="1px"><?=$row2['selecao_valores'];?></font></td>
						<td><font size="1px"><?=$row2['formato'];?></font></td>
						<td><font size="1px"><?=$row2['legenda'];?></font></td>
						<td><font size="1px"><?=$row2['condicional_regra_alerta'];?></font></td>
					</tr>
				<?php
			}
		}
		echo ' </tbody>';
		echo ' </table>';
	}
	if($tipo==2){
		$campos = array("HOSP", "SEQ", "DATECOL", "TIME", "IDPAT", "AGE", "SEX", "ETNIC", "AFIB", "PRIORMI", "SMOKE", "ALCOHOL", "HYPERTEN", "HEIGHT", "HEIGHTUNIT", "WEIGHT", "WEIGHTUNIT", "DIAB", "CHRONLD", "RENDYS", "ECHOPERF", "EJECFRAC", "EJEFRACNUM", "DIAGCAT", "ESCOL", "PHARMACOTHERAPY", "PHARMATYPE1", "PHARMATYPE2", "PHARMATYPE3", "PHARMATYPE4", "PHARMATYPE5", "PHARMATYPE6", "PHARMATYPE7", "PHARMATYPE8", "PHARMATYPE9", "PHARMATYPE10", "PHARMATYPE11", "DEVPROCEDURE", "DEVPROCEDURETYPE", "DEVPROCEDUREDATE", "CARDSURG", "CARDSURGVTYPE", "CARDSURGDATE", "PERCPROCEDURE", "PERCPROCEDURETYPE", "PERCPROCEDUREDATE", "REHAB", "KCCQ12_Qo1a", "KCCQ12_Qo1b", "KCCQ12_Qo1c", "KCCQ12_Qo2", "KCCQ12_Qo3", "KCCQ12_Qo4", "KCCQ12_Qo5", "KCCQ12_Qo6", "KCCQ12_Qo7", "KCCQ12_Qo8a", "KCCQ12_Qo8b", "KCCQ12_Qo8c", "NYHA_Q01", "NYHA_Q02", "PROMIS_QPFA11", "PROMIS_QPFA21", "PROMIS_QPFA23", "PROMIS_QPFA53", "PHQ2_Q01", "PHQ2_Q02", "NOTFILL", "WHOANS", "ARIDATE", "DISDATE", "COMPLDEVICE", "COMPLDEVICEDATE", "COMPLHOSP", "COMPLHOSPDATE", "COMPLMED", "COMPLMEDDATE", "FINBURDEN", "HOSPADM", "HOSPAPPT", "ADTRAT", "DEATH", "DEATHDATE", "DEATHPLACE");
	}
	$query = "SELECT crf_paciente_pre_rand.id, crf_paciente_pre_rand.centro, crf_paciente_pre_rand.nro_paciente, cfg_centro.nome as hospital FROM crf_paciente_pre_rand inner join cfg_centro on crf_paciente_pre_rand.centro = cfg_centro.numero where " . $_GET['filtro'];
	$res = $conexao->pesquisa($query);
	for($colunas=0;$colunas<count($campos);$colunas++){
		$coluna = $campos[$colunas];
		echo '<th style="font-size: 12px !important;"><center>'.$coluna.'</center></th>';
	}
	echo '</tr> ';
	echo '	</thead> ';
	echo '<tbody>';
	if(mysqli_num_rows($res)==0){
	?>
		<tr>
			<td colspan="6">Nenhum dado a exibir...</td>
		</tr>
	<?php
	}else{
		  while($row = mysqli_fetch_assoc($res)){
				echo '<tr style="font-size: 6px !important;">';
				for($colunas=0;$colunas<count($campos);$colunas++){
					$coluna = $campos[$colunas];
					$query = "select * from vw_campo_valor where centro = '".$row['centro']."' and nro_paciente = '".$row['nro_paciente']."' and nome = '".$coluna."'";
					$res_valor = $conexao->pesquisa($query);
					$row_valor = mysqli_fetch_assoc($res_valor);
					$valor = $row_valor['valor'];
					if($resposta==2){	//Aqui só mando o valor numérico
						if(strpos($valor, "-")==true){	//Tem -
							$valor = explode("-",$valor);
							$valor = trim($valor[0]);
						}
					}
					echo "<td><center>".$valor."</center></td>";
				}
				echo "</tr>";
		  }
	  }
	  echo '</tbody>';
	echo '</table>';
  }
	if($act=="INCONSISTENCIAS"){
		$query = "SELECT	";
		$query .= "	cfg_centro.numero as centro_nro, cfg_centro.nome as centro_nome,	";
		$query .= "	    crf_paciente_pre_rand.nro_paciente_formatado, nro_participante, nro_triagem, crf_paciente_pre_rand.nro_paciente,	";
		$query .= "		crf_forms.nome as formulario_nome,	";
		$query .= "	    crf_forms_campos.nome, crf_forms_campos.texto  as campo_nome,    	";
		$query .= "		crf_entrada_forms.paciente, crf_entrada_forms.form_id, crf_entrada_forms.centro,	";
		$query .= "	    crf_entrada_campos.valor, crf_entrada_campos.valor_dupla_digitacao, crf_entrada_campos.monitoria_status, crf_entrada_campos.form_id as formulario_do_campo	";
		$query .= "	    from crf_entrada_forms	";
		$query .= "	    	inner join crf_forms on crf_entrada_forms.form_id = crf_forms.id	";
		$query .= "	        inner join crf_entrada_campos on crf_entrada_forms.id = crf_entrada_campos.form_id	";
		$query .= "	        inner join crf_forms_campos on crf_entrada_campos.campo_id = crf_forms_campos.id	";
		$query .= "	        inner join crf_paciente_pre_rand on crf_paciente_pre_rand.centro = crf_entrada_forms.centro and crf_paciente_pre_rand.nro_paciente = crf_entrada_forms.paciente	";
		$query .= "	        inner join cfg_centro on crf_entrada_forms.centro = cfg_centro.id	";
		$query .= $_GET['query'];
		if($_GET['relatorio']=="1"){ //Campos sem monitorias
			$query .= " and (crf_entrada_campos.monitoria_status = 0) ";
			$query .= " ) LIMIT 1000";
			//echo $query;
			$res = $conexao->pesquisa($query);
			while($row = mysqli_fetch_assoc($res)){
				?>
						<tr>
							<td style="font-size: 11px !important;"><center><?=$row['centro_nro'] . "<br>" . $row['centro_nome']?></center></th>
							<td style="font-size: 11px !important;"><center><?=$row['nro_participante'];?></center></th>
							<td style="font-size: 11px !important;"><?=$row['formulario_nome'];?></th>
							<td style="font-size: 11px !important;"><?=$row['campo_nome'];?></th>
							<td style="font-size: 11px !important;"><?=$row['valor'];?></th>
							<td style="font-size: 11px !important;"><?=$row['valor_dupla_digitacao'];?></th>
							<td style="font-size: 11px !important;" width="30">
								<center>
									<a href="ecrf_formularios.php?cGFj=<?=$row['nro_paciente'];?>&Y2Ft=<?=$row['formulario_do_campo'];?>&xxpt=<?=$row['centro'];?>#TOPO"><img src="imagens/icon_querie.png" width="25"></font></a>
								</center>
							</td>
						</tr>
				<?php
			}
		}
		if($_GET['relatorio']=="2"){ //Campos com digitaçao diferente da 2 digitaçao
			$query .= " and ( (crf_entrada_campos.valor <> crf_entrada_campos.valor_dupla_digitacao) and ((crf_entrada_campos.valor <> '') and (crf_entrada_campos.valor_dupla_digitacao <> '')) ) ";
			$query .= " ) ";
			$res = $conexao->pesquisa($query);
			while($row = mysqli_fetch_assoc($res)){
				?>
						<tr>
							<td style="font-size: 11px !important;"><center><?=$row['centro_nro'] . "<br>" . $row['centro_nome']?></center></th>
							<td style="font-size: 11px !important;"><center><?=$row['nro_participante'];?></center></th>
							<td style="font-size: 11px !important;"><?=$row['formulario_nome'];?></th>
							<td style="font-size: 11px !important;"><?=$row['campo_nome'];?></th>
							<td style="font-size: 11px !important;"><?=$row['valor'];?></th>
							<td style="font-size: 11px !important;"><?=$row['valor_dupla_digitacao'];?></th>
							<td style="font-size: 11px !important;" width="30">
								<center>
									<a href="ecrf_formularios.php?cGFj=<?=$row['nro_paciente'];?>&Y2Ft=<?=$row['formulario_do_campo'];?>&xxpt=<?=$row['centro'];?>#TOPO"><img src="imagens/icon_querie.png" width="25"></font></a>
								</center>
							</td>
						</tr>
				<?php
			}
		}
		if($_GET['relatorio']=="4"){ //Campos com digitaçao diferente da 2 digitaçao
			$query .= " and ( (crf_entrada_campos.valor = '') and ( crf_forms_campos.obrigatorio = 'on') )";
			$query .= " ) ";
			$res = $conexao->pesquisa($query);
			while($row = mysqli_fetch_assoc($res)){
				?>
						<tr>
							<td style="font-size: 11px !important;"><center><?=$row['centro_nro'] . "<br>" . $row['centro_nome']?></center></th>
							<td style="font-size: 11px !important;"><center><?=$row['nro_participante'];?></center></th>
							<td style="font-size: 11px !important;"><?=$row['formulario_nome'];?></th>
							<td style="font-size: 11px !important;"><?=$row['campo_nome'];?></th>
							<td style="font-size: 11px !important;"><?=$row['valor'];?></th>
							<td style="font-size: 11px !important;"><?=$row['valor_dupla_digitacao'];?></th>
							<td style="font-size: 11px !important;" width="30">
								<center>
									<a href="javascript:document.location = 'ecrf_formularios.php?cGFj=<?=$row['nro_paciente'];?>&Y2Ft=<?=$row['formulario_do_campo'];?>&xxpt=<?=$row['centro'];?>#TOPO';"><img src="imagens/icon_querie.png" width="25"></font></a>
								</center>
							</td>
						</tr>
				<?php
			}
		}
	}
	if($act=="PAGINA_GRUPO_USUARIO"){   //Mudanças do checkbox dapágina de configuraçao de tipos usuários / menu
		$pagina = $_GET['pagina'];
		$grupo = $_GET['grupo'];
		$resp = $_GET['resp'];
		$acao = $_GET['acao'];
		$query = "select * from usu_usuario_tipo_pagina where pagina = $pagina and grupo_id = $grupo";
		$res = $conexao->pesquisa($query);
		if(mysqli_num_rows($res)>0){  //Já tem registro
			$query = "update usu_usuario_tipo_pagina set log_usuario = '".$_SESSION['user_id']."', ". $acao . " = '" .$resp ."' where pagina = $pagina and grupo_id = $grupo";
			$conexao->pesquisa($query);
		}else{
			$query = "insert into usu_usuario_tipo_pagina (pagina, grupo_id, $acao) values ($pagina, $grupo, '$resp');";
			$conexao->pesquisa($query);
		}
		echo $query;
	}
	if($act=="OPC1"){   //Mudanças do checkbox dapágina de configuraçao de tipos usuários / menu
		$menu = $_GET['menu'];
		$usuario = $_GET['usuario'];
		$resp = $_GET['resp'];
		$campo = $_GET['campo'];
		$query = "select * from usu_usuario_tipo_menu where id_menu = " . $menu . " and id_usuario = " . $usuario;
		$res_aj = $conexao->pesquisa($query);
		if(mysqli_num_rows($res_aj)>0){  //Já tem registro
			$query = "update usu_usuario_tipo_menu set ". $campo . " = '" .$resp ."' where id_menu = $menu and id_usuario = $usuario";
			$conexao->pesquisa($query);
		}else{
			$query = "insert into usu_usuario_tipo_menu (id_menu, id_usuario, $campo) values ($menu, $usuario, '$resp');";
			$conexao->pesquisa($query);
		}
	}

  //Dashboard TMF
  if($act=="OPC2"){
			                                                                   //totais
			                                                                   if(($_GET['tempo']=="DIA")||($_GET['tempo']=="")) {
			                                                                       $data = " data >= '".date("Y-m-d")." 00:00' and data <= '". date("Y-m-d")." 23:59'";
			                                                                   }
			                                                                   if($_GET['tempo']=="SEM") {
			                                                                   	 $data = " data >= '".date('Y-m-d H:i:s', strtotime(date("Y-m-d") . ' -7 day'))." 00:00' and data <= '". date("Y-m-d")." 23:59'";
			                                                                   }
			                                                                   if($_GET['tempo']=="MES") {
			                                                                   	 $data = " data >= '".date('Y-m-d H:i:s', strtotime(date("Y-m-d") . ' -30 day'))." 00:00' and data <= '". date("Y-m-d")." 23:59'";
			                                                                   }
																			   if($_GET['tempo']!="TUDO"){
																					$query = "select count(id) as QTD from arq_arquivos where ".$data;
																			   }else{
																					$query = "select count(id) as QTD from arq_arquivos ";
																			   }
			                                                                   $res_tot = $conexao->pesquisa($query);
			                                                                   $row_tot = mysqli_fetch_assoc($res_tot);
			                                                                   $tot_upload = $row_tot['QTD'];
																			   if($_GET['tempo']!="TUDO"){
																					$query = "select count(id) as QTD from arq_acoes where acao = 'DOWNLOAD' and " . $data;
																			   }else{
																					$query = "select count(id) as QTD from arq_acoes where acao = 'DOWNLOAD'";
																			   }
			                                                                   $res_tot = $conexao->pesquisa($query);
			                                                                   $row_tot = mysqli_fetch_assoc($res_tot);
			                                                                   $tot_download = $row_tot['QTD'];
																			   $query = "select count(id) as QTD from arq_arquivos where status_id = 1";
			                                                                   $res_tot = $conexao->pesquisa($query);
			                                                                   $row_tot = mysqli_fetch_assoc($res_tot);
			                                                                   $tot_validado = $row_tot['QTD'];
			                                                                ?>
        					                                                    <div class="portlet-body">
        					                                                        <div class="row number-stats margin-bottom-30">
        					                                                            <div class="col-md-3 col-sm-3 col-xs-3">
        					                                                                <div class="stat-left">
        					                                                                    <div class="stat-chart">
        					                                                                        <!-- do not line break "sparkline_bar" div. sparkline chart has an issue when the container div has line break -->
        					                                                                        <div id="sparkline_bar"></div>
        					                                                                    </div>
        					                                                                    <div class="stat-number">
        					                                                                        <div class="title"> Total</div>
        					                                                                        <div class="number"> <?=$tot_upload;?> </div>
        					                                                                    </div>
        					                                                                </div>
        					                                                            </div>
        					                                                            <div class="col-md-3 col-sm-3 col-xs-3">
        					                                                                <div class="stat-right">
        					                                                                    <div class="stat-chart">
        					                                                                        <!-- do not line break "sparkline_bar" div. sparkline chart has an issue when the container div has line break -->
        					                                                                        <div id="sparkline_bar2"></div>
        					                                                                    </div>
        					                                                                    <div class="stat-number">
        					                                                                        <div class="title"> Total validados </div>
        					                                                                        <div class="number"> <?=$tot_validado;?> </div>
        					                                                                    </div>
        					                                                                </div>
        					                                                            </div>
																						<div class="col-md-3 col-sm-3 col-xs-3">
        					                                                                <div class="stat-left">
        					                                                                    <div class="stat-chart">
        					                                                                        <!-- do not line break "sparkline_bar" div. sparkline chart has an issue when the container div has line break -->
        					                                                                        <div id="sparkline_bar2"></div>
        					                                                                    </div>
        					                                                                    <div class="stat-number">
        					                                                                        <div class="title"> Downloads </div>
        					                                                                        <div class="number"> <?=$tot_download;?> </div>
        					                                                                    </div>
        					                                                                </div>
        					                                                            </div>
																						<div class="col-md-3 col-sm-3 col-xs-3">
        					                                                                <div class="stat-right">
        					                                                                    <div class="stat-chart">
        					                                                                        <!-- do not line break "sparkline_bar" div. sparkline chart has an issue when the container div has line break -->
        					                                                                        <div id="sparkline_bar2"></div>
        					                                                                    </div>
        					                                                                    <div class="stat-number">
        					                                                                        <div class="title"> Uploads </div>
        					                                                                        <div class="number"> <?=$tot_upload;?> </div>
        					                                                                    </div>
        					                                                                </div>
        					                                                            </div>
        					                                                        </div>
        					                                                        <div class="table-scrollable table-scrollable-borderless">
        					                                                            <table class="table table-hover table-light">
        					                                                                <thead>
        					                                                                        <th> <?=traduzir("Usuário");?> </th>
        					                                                                        <th><center><?=traduzir("Uploads");?></center></th>
        					                                                                        <th><center><?=traduzir("Downloads");?></center></th>
        					                                                                        <th><center>% Upload</center></th>
        					                                                                </thead>
        					                                                                <?php
        					                                                                    $query = "SELECT  usu_usuario.id, usu_usuario.nome ";
	      					                                                                 $query .= "FROM usu_usuario ";
        					                                                                    $query .= "  inner join usu_usuario_tipo on usu_usuario.tipo_usuario = usu_usuario_tipo.id ";
        					                                                                    $query .= "  inner join usu_usuario_tipo_menu on usu_usuario_tipo.id = usu_usuario_tipo_menu.id_usuario ";
        					                                                                    $query .= "  inner join menu_itens on usu_usuario_tipo_menu.id_menu = menu_itens.id ";
        					                                                                     $query .= "      and menu_itens.nome = 'Arquivos do TMF' ";
        					                                                                    $res_usu = $conexao->pesquisa($query);
        					                                                                    while($row_usu=mysqli_fetch_assoc($res_usu)){
        					                                                                    $query = "select count(id) as QTD from arq_arquivos where usuario = ".$row_usu['id']." and ".$data;
							                                                                    $res_tot = $conexao->pesquisa($query);
							                                                                    $row_tot = mysqli_fetch_assoc($res_tot);
							                                                                    $tot_upload_usu = $row_tot['QTD'];
							                                                                    $query = "select count(id) as QTD from arq_acoes where usuario_id = ".$row_usu['id']." and acao = 'DOWNLOAD' and " . $data;
							                                                                    $res_tot = $conexao->pesquisa($query);
							                                                                    $row_tot = mysqli_fetch_assoc($res_tot);
							                                                                    $tot_download_usu = $row_tot['QTD'];
	        					                                                                ?>
	        					                                                                <tr>
	        					                                                                    <td><?=$row_usu['nome']?></td>
	        					                                                                    <td><center><?=$tot_upload_usu;?></center></td>
	        					                                                                    <td><center><?=$tot_download_usu;?></center></td>
	        					                                                                    <td><center><span class="bold theme-font"><?php if($tot_upload_usu>0){
	        					                                                                    	//$conta = $tot_upload/($tot_upload_usu*1);
	        					                                                                    	$conta = $tot_upload_usu * 100;
	        					                                                                    	$conta = $conta / $tot_upload;
	        					                                                                    	echo round ( $conta , 2 );
	        					                                                                    	}else{echo "0";}?> %</span></center></td>
	        					                                                                </tr>
	        					                                                                <?php
	        					                                                                }
	        					                                                                ?>
        					                                                            </table>
        					                                                        </div>
        					                                                    </div>
  <?php
  }
  if($act=="OPC3"){
		$pesquisa = strtoupper($_GET['str']);
		$query = "select crf_entrada_campos.valor, crf_paciente_pre_rand.nro_paciente, crf_paciente_pre_rand.paciente_pos_rand ";
		$query .= "	from crf_entrada_campos  ";
		$query .= "	        inner join crf_entrada_forms on crf_entrada_campos.form_id = crf_entrada_forms.id ";
		$query .= "	        inner join cfg_centro on crf_entrada_forms.centro = cfg_centro.id ";
		$query .= "	        inner join crf_paciente_pre_rand on crf_entrada_forms.paciente = crf_paciente_pre_rand.nro_paciente and cfg_centro.numero = crf_paciente_pre_rand.centro ";
		$query .= '	    where crf_entrada_campos.campo_id = 2425 and valor like("%'.$pesquisa.'%")  ';
		if($_SESSION['centro']!=3){
		    $query .= "	    and cfg_centro.id = 3 ";
		}
		$res = $conexao->pesquisa($query);
		if(mysqli_num_rows($res)>0){
				?>
					<table class="table table-hover">
						<thead>
							<th style="font-size:13px !important"><center>#<br>Recrutamento</center></th>
							<th style="font-size:13px !important"><center>#<br>Randomizado</center></th>
							<th style="font-size:13px !important">Nome</th>
							<th></th>
						</thead>
						<tbody>
							<?php
								while($row = mysqli_fetch_assoc($res)){
								?>
									<tr>
										<td style="font-size:12px !important"><center><?=$row['nro_paciente'];?></center></td>
										<td style="font-size:12px !important"><center><?=$row['paciente_pos_rand'];?></center></td>
										<td style="font-size:12px !important"><?=$row['valor'];?></td>
										<td><center><i class="fa fa-check" onclick="javascript:seleciona_vol('<?=$row['valor'];?>','<?=$_SESSION['centro'];?>');"></i></center></td>
									</tr>
								<?php
								}
							?>
						</tbody>
					</table>
				<?php
		}else{
			 echo "Nenhum resultado encontrado com a pesquisa '".$str."'....";
		}
  }

