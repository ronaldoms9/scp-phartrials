<?php
	if(!isset($_SESSION)){  session_start();    }
	include "../seguranca.php";	
	include_once "../class/projeto.class.php";
	include_once "../class/agenda.class.php";
	
	if($_GET['act']=="MUDA_STATUS"){
		$id 		= $_POST['varId'];
		$status 	= $_POST['varStatus'];
		$conexao = new Conexao();
		echo "Status -> " . $status;
		if($status=="true"){
			$data 		= date("d/m/Y");
			$dataLis 	= date("Ymd");
			
			$query  = "update fin_participante_item set feito = 1, data = '".date("d/m/Y")."', data_lis = '".$dataLis."' where id = " . $id;
		}else{
			$query  = "update fin_participante_item set feito = 0, data = '' where id = " . $id;
		}
		$conexao->pesquisa($query);
		echo $query;
	}
	if($_GET['act']=="PESQUISA"){
		$conexao = new Conexao();
		$participante = $_POST['varId'];
		$varProjeto = new Projeto($_SESSION['projeto']);
		
		$head = '<thead>
					<tr>
						<!--<th style="min-width: 100px !important" width="100">
							<button type="button" class="btn btn-default" onclick="javascript:exportar();"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Excel</button>
						</th>						-->
						<th>Item</th>
		';
		for($iVisita=1;$iVisita<=$varProjeto->fin_visitas;$iVisita++){
			$head .= '
						<th style="font-size: 12px !important;"><center>Visita '.$iVisita.'</center></th>
			';
		}
		$head .= '
					<!--<th style="min-width: 100px !important" width="100"></th>-->
					</tr>
				</thead>
				<tbody>';		
		$html = "";
		
		$query  = "select * from fin_itens order by ordem";
		$resFin = $conexao->pesquisa($query);
		while($rowFin = mysqli_fetch_assoc($resFin)){
			?>			
			<tr>
				<!--<td></td>-->
				<td style="font-size: 12px !important;"><?=$rowFin['item'];?></td>
				<?
				for($iVisita=1;$iVisita<=$varProjeto->fin_visitas;$iVisita++){				
					/*$query = "SELECT fin_itens.id,
								fin_itens.item,
								fin_itens.valor,
								fin_participante_item.participante
									FROM fin_itens
										left join fin_participante_item on fin_itens.item = fin_participante_item.item
											and fin_participante_item.participante = ".$participante."
											
									order by fin_itens.ordem";*/
					$query = "select * from fin_participante_item where participante = " . $participante . " and item = " . $rowFin['id'] . " and visita = " . $iVisita;
					$resItem = $conexao->pesquisa($query);
					if(mysqli_num_rows($resItem)==0){
						//Insiro o registro na tabela, porque é para ter mesmo
						$query = "insert into fin_participante_item (projeto, participante, item, visita) values ('".$_SESSION['projeto']."', ".$participante.",".$rowFin['id'].",".$iVisita.");";
						$conexao->pesquisa($query);
						$query = "select * from fin_participante_item where participante = " . $participante . " and item = " . $rowFin['id'] . " and visita = " . $iVisita;
						$resItem = $conexao->pesquisa($query);
					}
					$rowItem = mysqli_fetch_assoc($resItem);
					?>
						<td style="font-size: 12px !important;">										
							<center>								
								<input 
									type="checkbox" 
									value="1" 
									<?
										if($rowItem['cobrado']==1){
											echo " disabled ";
										}
										if($rowItem['feito']==1){
											echo " checked ";
										}
									?>
									onclick="javascript:mudaStatus(<?=$rowItem['id'];?>, this.checked)"
								>
								&nbsp;&nbsp;
								<div class="btn-group">
									<button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
										<i class="fa fa-ellipsis-horizontal"></i>
										<i class="fa fa-angle-down"></i>
									</button>
									
									<div class="dropdown-menu">
										<div class="row">
											<div class="col-md-1"></div>
											<div class="col-md-9">
												<form class="px-4 py-3">
													<div class="form-group">
														<label for="exampleDropdownFormEmail1">Data</label>
														<input 
															type="text" 
															class="form-control" 
															disabled
															<?=$centralizado;?>
															value="<?=$rowItem['data'];?>">
													</div>
													
													<div class="form-check">
														<input type="checkbox" 
															class="form-check-input" 
															disabled>
														<label class="form-check-label" for="dropdownCheck">
														Pago?
														</label>
													</div>
													<div class="form-group">
														<label for="exampleDropdownFormPassword1">Data Pagamento</label>
														<input 
															type="text" 
															class="form-control" 
															disabled
															<?=$centralizado;?>
															value="<?=$rowItem['data_cobrado'];?>">
													</div>													
												</form>												
											</div>
										</div>
									</div>
								</div>
							</center>
						</td>
					<?					
				}
				?>
			</tr>
			<?			
		}		
		echo $head . $html . $footer;		
	}