<?php
	include_once "../class/conexao.class.php";
	include_once "../class/agenda.class.php";

	if(isset($_GET['act'])){
		$act = strtoupper($_GET['act']);
		$agendamento = $_POST['agendamento'];
	}else{$act="";}
	switch($act){
		case "EXIBE":
			$query = "select * from agenda_protocolo_pendencia where agendamento = " . $agendamento . " order by ordem, id";
			$conexao = new Conexao();
			$res_pend = $conexao->pesquisa($query);
			if(mysqli_num_rows($res_pend)==0){
				echo "<tr><td colspan='7'>Nenhuma pend&ecirc;ncia encontrada....</td></tr>";
			}else{
				$ind = 1;
				$totPend = mysqli_num_rows($res_pend);
				while($row_pendencias = mysqli_fetch_assoc($res_pend)){
					?>
					<tr>
						<td>
							<?
								echo $ind . " ";
								if($ind<$totPend){
									echo '<a href="javascript:mudaBaixo('.$row_pendencias['id'].', '.$agendamento.');"><i class="fa fa-level-down" aria-hidden="true"></i></a>';
								}
								if($ind>1){
									echo '<a href="javascript:mudaCima('.$row_pendencias['id'].', '.$agendamento.');"><i class="fa fa-level-up" aria-hidden="true"></i></a>';
								}
							?>
						</td>
						<td><center><?=$row_pendencias['pagina'];?></center></td>
						<td><?=$row_pendencias['trecho'];?></td>
						<td >
							<font size="2"><?=nl2br($row_pendencias['achado']);?></font>
						</td>
						<td><?=$row_pendencias['critico'];?></td>
						<td><center><?=nl2br($row_pendencias['resposta']);?></center></td>
						<td><?=$row_pendencias['respondido_por'];?></td>
						<td><center><?=$row_pendencias['finalizado'];?></center></td>
						<td><center>
							<a href="agendamento_protocolo_edit.php?agendamento=<?=$agendamento;?>&tipo=E&formulario=<?=$varFormulario;?>&registro=<?=$row_pendencias['id'];?>" class="btn btn-circle btn-sm blue">
								<i class="fa fa-search"></i>
							</a>
							<a href="javascript:deleta(<?=$ind;?>,<?=$row_pendencias['id'];;?>)" class="btn btn-circle btn-sm red">
								<i class="fa fa-trash"></i>
							</a>
							<a data-toggle="modal" data-target="#modalHistorico" href="modal_agenda_apontamento_historico.php?id=<?=$row_pendencias['id'];?>" class="btn btn-circle btn-sm green">
								<i class="fa fa-history fa-sm" title="Histórico"></i>
							</a>
							</center>
						</td>
					</tr>
				<?
					$ind++;
				}
			}
			break;
		case "MUDA_CIMA":
			$varAchado		= new Achado($_POST['achado']);
			$varAchado->trocaCima();
			break;
		case "MUDA_BAIXO":
			$varAchado		= new Achado($_POST['achado']);
			$varAchado->trocaBaixo();
			break;
	}
