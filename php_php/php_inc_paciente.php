<?php
	include_once "../seguranca.php";
	include_once "../class/conexao.class.php";
	include_once "../class/projeto.class.php";
	include_once "../funcoes.php";

	

	if($_GET['act']=="PESQUISA_CODIGO"){
		$varConexao = new Conexao();
		//$varCodigo = $_POST['codigo'];
		$varCentro = $_POST['centro'];
		$varSexo   = $_POST['sexo'];
		$varNasc   = $_POST['nasc'];

		$query = "SELECT * FROM vw_valor_campo where nome = 'TRI_CRI_SN_ELEG' and valor = '1 - Sim'";
		$res = $varConexao->pesquisa($query);
		$qtdInc = mysqli_num_rows($res);

		/*if($qtdInc == 152 ){
			$arrFinal = array(
				"resp" 			=> "FALHA",
				"descricao"		=> "O N do estudo ja foi atingido. Nao e possivel incluir mais pacientes.");
			$array = array_map( 'htmlentities' , $arrFinal );
			$json = html_entity_decode( json_encode( $array ) );
			echo $json;
			return;
		}

		//Verificar data de nascimento do caboclo. Não pode aceitar menor de 12 anos

		$idade = calculaIdadeFinal($varNasc, date('d/m/Y'));
		if($idade < 12){
			$arrFinal = array(
				"resp" 			=> "FALHA",
				"descricao"		=> "O participante deve ter pelo menos 12 anos de idade. Atualmente tem ".$idade." anos.");
			$array = array_map( 'htmlentities' , $arrFinal );
			$json = html_entity_decode( json_encode( $array ) );
			echo $json;
			return;
		}*/

		$arrFinal = array(
			"resp" 			=> "OK",
			"descricao"		=> "Apto para incluir"
		);
		$array = array_map( 'htmlentities' , $arrFinal );
		$json = html_entity_decode( json_encode( $array ) );
		echo $json;
		return;


	}
	if($_GET['act']=="INCLUI_PACIENTE"){
		$varCentro = $_POST['centro'];
		$varCodigo = $_POST['cod'];

		$varProjeto = new Projeto($_SESSION['projeto']);
		$retorno = $varProjeto->incluiPaciente($varCentro, "", $varCodigo, "", "");
		echo $retorno;
	}
	if($_GET['act']=="MOSTRA_RECIBO"){
			//Caçar o número do caboclo
			$conexao 	= new Conexao();
			$query 		= "select
							crf_paciente_pre_rand.id,
							crf_paciente_pre_rand.nro_triagem,
							cfg_centro.numero
							from crf_paciente_pre_rand
							inner join cfg_centro on crf_paciente_pre_rand.centro = cfg_centro.id
								where centro = '".$_POST['centro']."'
										and nasc = '".$_POST['nasc']."'
										and iniciais = '".$_POST['codigo']."'
										order by crf_paciente_pre_rand.id desc limit 1";
			$resPart	= $conexao->pesquisa($query);
			$rowPart	= mysqli_fetch_assoc($resPart);
			$centro_num = $rowPart['numero'];
			$prox		= $rowPart['nro_triagem'];

			$arrFinal = array(
				"resp" 			=> "OK",
				"descricao"		=> "Incluido com sucesso",
				"participante"	=> $prox,
				"centro"		=> $centro_num
			);
			$array = array_map( 'htmlentities' , $arrFinal );
			$json = html_entity_decode( json_encode( $array ) );
			echo $json;
	}
	if($_GET['act']=="INICIA_PAGINA"){
		?>
		<br><br>
		<?php $acao = "inc_paciente.php?".gera_parametro("act","I");?>
			<div class="row">
				<div class="col-md-12">
					<!-- BEGIN Portlet PORTLET-->
					<div class="portlet light">
						<div class="portlet-title">
							<div class="caption">
								<i class="icon-paper-plane font-yellow-casablanca"></i>
								<span class="caption-subject bold font-yellow-casablanca uppercase"> Inclusão de Participante</span>
							</div>
						</div>
						<div class="portlet-body form">
							<form class="form-horizontal form-bordered" role="form" action="<?=$acao;?>" method="post" id="form_info" name="form_info">
								<div class="form-body">
									<div class="form-group">
										 <label class="col-md-4 control-label"><?=traduzir("Centro", $_SESSION['lang']);?></label>
										 <div class="col-sm-8">
											  <select class="form-control" id="form_centro" name="form_centro">
												<?php
													if( ($_SESSION['centro']!=40) and ($_SESSION['centro']!=0) ){
														$query = "select * from cfg_centro where nome <> 'CRO'  and id = ".$_SESSION['centro']." order by numero";
													}else{
														$query = "select * from cfg_centro where nome <> 'CRO'  order by numero";
													}
													$res_centro = $conexao->pesquisa($query);
													while($row_centro = mysqli_fetch_assoc($res_centro)){
														echo '<option value="'.$row_centro['id'].'">'.$row_centro['nome'].'</option>';
													}
												?>
											  </select>
										 </div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-4 control-label"><?=traduzir("Código do Participante", $_SESSION['lang']);?></label>
									<div class="col-sm-4">
										<input
											type="text"
											class="form-control"
											id="form_codigo"
											name="form_codigo<?=rand(1213,12312412);?>"
											onKeyDown="javascript:desabilitaENTER(event);"
											style="text-align: center;">
									</div>
								</div>

								<div class="form-actions">
									<div class="row">
										<div class="col-md-12">
											<div class="row">
												<div class="col-md-offset-3 col-md-9">
													<button id="btnIncluir" type="button" class="btn blue-dark" onclick="javascript:valida();">
														<i class="fa fa-check"></i> <?=traduzir("Incluir Participante", $_SESSION['lang']);?></button>
												</div>
											</div>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<?php
	}