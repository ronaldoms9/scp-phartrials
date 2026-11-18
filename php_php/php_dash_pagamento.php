<?php
	header('Content-Type:text/html; charset=ISO-8859-1');

	include_once "../class/conexao.class.php";
	include_once "../class/projeto.class.php";
	include_once "../class/agenda.class.php";
	$varConexao = new Conexao();

	error_reporting(~E_ALL);
	ini_set('display_errors', 0);



if($_GET['act']=="EXIBE"){
        $varStatus	 = $_POST['varstatus'];
		?>
			<table class="table table-striped table-hover" id="tblProjetos">
				<thead class="header">
					<tr>
						<th><center># PHARTRIALS</center></th>
						<th>Nome</th>
						<th>Tipo</th>
						<th>Centro</th>
                        <th>Data Recebimento</th>
                        <th>Data Finalização</th>

						<th><center>Valor Total</center></th>
						<th><center>Valor Pago</center></th>
						<th><center>Valor aberto</center></th>
						<th><center>Pago?</center></th>

					</tr>
				</thead>
				<tbody class="corpo">
					<?php
						$query 		= "select id from proj_projeto
											where id > 0 ";
                        if($varStatus==1){$query .= " and qsb_pago = 1";}
                        if($varStatus==2){$query .= " and qsb_pago = 0";}
                        $query.= "            order by id
                                                ";
                        echo $query;
						$resProj 	= $varConexao->pesquisa($query);
						$intSeq         = 0;
                        $total          = 0;
                        $totalPago      = 0;
                        $totalAberto    = 0;
						while($rowProj = mysqli_fetch_assoc($resProj)){
							$varProjeto = new Projeto($rowProj['id']);
							?>
								<tr>
									<td class="dados"><span class="dados"><center><?=$varProjeto->numero;?></center></span></td>
									<td><span class="dados"><?=$varProjeto->nome;?></td>
									<td><span class="dados"><center><?=$varProjeto->tipo;?></center></td>
									<td><span class="dados"><?=$varProjeto->centro;?></td>
                                    <td><span class="dados"><center><?=$varProjeto->data_recebimento;?></center></td>
                                    <td><span class="dados"><center><?=$varProjeto->data_finalizacao;?></center></td>
                                    <td><span class="dados"><center><?php
                                        echo $varProjeto->qsb_total;
                                        $total = $total + $varProjeto->qsb_total;
                                        ?></center></td>
                                    <td><span class="dados"><center><?=$varProjeto->qsb_valor_pago;?></center></td>
                                    <td><span class="dados"><center>
                                        </center></td>
                                    <td><span class="dados"><center><?=$varProjeto->qsb_pago;?></center></td>
								</tr>
							<?php
							$intSeq++;
						}
					?>
						<tr>
							<td style="text-align: right"><b>Total de registros:</b></td>
							<td><b><?=$intSeq;?></b></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td><center>Total<br><b>R$ <?=$total;?></b></center></td>
                            <td><center>Pago<br><b>R$ <?=$totalPago;?></b></center></td>
                            <td><center>Aberto<br><b>R$ <?=$totalAberto;?></b></center></td>
                            <td></td>
						</tr>
				</tbody>
			</table>
		<?php
	}