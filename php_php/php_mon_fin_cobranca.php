<?php
	include_once "../seguranca.php";
	include_once "../class/financeiro.class.php";
	$varConexao = new Conexao();

	if($_GET['act']=="PESQUISA_GERADOS"){
		$conexao = new Conexao();
		$varDataInicial = $_POST['dataInicial'];
		$varDataFinal	= $_POST['dataFinal'];
		if($varDataInicial==""){$varDataInicial = "20220101";}else{
			$varDataInicial = explode("/", $varDataInicial);
			$varDataInicial = $varDataInicial[2].$varDataInicial[1].$varDataInicial[0];
		}
		if($varDataFinal==""){$varDataFinal = date("Ymd");}else{
			$varDataFinal = explode("/", $varDataFinal);
			$varDataFinal = $varDataFinal[2].$varDataFinal[1].$varDataFinal[0];
		}
		$query = "SELECT
					data_cobrado,
					data_cobrado_lis,
					sum(fin_itens.valor) as total
						FROM fin_participante_item
							inner join fin_itens on fin_participante_item.item = fin_itens.id
						where data_cobrado_lis >= '$varDataInicial' and data_cobrado_lis <= '$varDataFinal'
						group by data_cobrado, data_cobrado_lis ";

		$query = "SELECT
					agenda_check_participante_campo.data_pagamento,
					data_pagamento_lis,
					sum(agenda_check_participante_campo.preco) as total
						from agenda_check_participante_campo
							inner join agenda_check on agenda_check_participante_campo.campo = agenda_check.id
								where ( (agenda_check_participante_campo.data_pagamento_lis >= '$varDataInicial') and (agenda_check_participante_campo.data_pagamento_lis <= '$varDataFinal') )
							group by agenda_check_participante_campo.data_pagamento, data_pagamento_lis";
		$res = $conexao->pesquisa($query);
		$iCount = 1;
		if(mysqli_num_rows($res)>0){
			while($row = mysqli_fetch_assoc($res)){
				?>
				<tr>
					<td style="font-size: 11px !important;"><center><?=$iCount;?></center></td>
					<td style="font-size: 11px !important;"><center><?=$row['data_pagamento'];?></center></td>
					<td style="font-size: 11px !important;"><center>R$ <?=number_format($row['total']);?></center></td>
					<td><center><a href="javascript:geraEspelho('<?=$row['data_pagamento_lis'];?>');"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a></center></td>
				</tr>
				<?php
				$iCount++;
			}
		}else{
				echo "<tr><td colspan=3>Nenhuma cobrança localiada nas datas acima</td></tr>";
		}
	}
	if($_GET['act']=="GERA_COBRANCA"){

		$query = "SELECT
					agenda_check.projeto,
					agenda_check_participante.id as id_participante,
					agenda_check_participante.numero,
					agenda_check_participante_campo.id,
					agenda_check_participante.codigo,
					agenda_check_participante.status,
					agenda_check_participante.periodo,
					agenda_check_fase.item as fase,
					agenda_check.id as idCheck,
					agenda_check.nome as nomeCheck,
					AV.valor, AV.valor6

					FROM agenda_check
						inner join agenda_check_fase on agenda_check.periodo = agenda_check_fase.id
						inner join agenda_check_participante_campo on agenda_check.id = agenda_check_participante_campo.campo
						inner join agenda_check_participante on agenda_check_participante_campo.participante = agenda_check_participante.id
						left join agenda_cobranca_itens AI on agenda_check_participante_campo.campo = AI.campo_id and AI.centro = agenda_check_participante.centro
						left join agenda_check_centro_valor AV on AI.id = AV.campo and AV.centro = agenda_check_participante.centro
						where cobranca = 1
							and agenda_check_participante.numero <> ''
							and agenda_check_participante_campo.pago = 0
							and agenda_check_participante_campo.valor = 'Sim'
							and ( (AV.valor <> '') or (AV.valor6 <> '') )
							order by agenda_check_participante.numero,
								agenda_check_fase.id,
								agenda_check.ordem";

		$resItem = $varConexao->pesquisa($query);
		while($rowItem = mysqli_fetch_assoc($resItem)){
			$id = $rowItem['id'];
			if($rowItem['periodo']==6){
				$query = "update agenda_check_participante_campo set pago = 1, preco = '".$rowItem['valor6']."', data_pagamento = '".date('d/m/Y')."', data_pagamento_lis = '".date('Ymd')."', log_usuario = '".$_SESSION['user_id']."' where id = " . $id;
			}else{
				$query = "update agenda_check_participante_campo set pago = 1, preco = '".$rowItem['valor']."', data_pagamento = '".date('d/m/Y')."', data_pagamento_lis = '".date('Ymd')."', log_usuario = '".$_SESSION['user_id']."' where id = " . $id;
			}
			$varConexao->pesquisa($query);
		}

	}
	if($_GET['act']=="PESQUISA_GERACAO"){
		$varDataInicial = $_POST['dataInicial'];
		$varDataFinal	= $_POST['dataFinal'];
		if($varDataInicial==""){$varDataInicial = "20220101";}else{
			$varDataInicial = explode("/", $varDataInicial);
			$varDataInicial = $varDataInicial[2].$varDataInicial[1].$varDataInicial[0];
		}
		if($varDataFinal==""){$varDataFinal = date("Ymd");}else{
			$varDataFinal = explode("/", $varDataFinal);
			$varDataFinal = $varDataFinal[2].$varDataFinal[1].$varDataFinal[0];
		}
		$query = "SELECT
					agenda_check.projeto,
					agenda_check_participante.id as id_participante,
					agenda_check_participante.numero,
					agenda_check_participante_campo.id,
					agenda_check_participante.codigo,
					agenda_check_participante.status,
					agenda_check_participante.periodo,
					agenda_check_fase.item as fase,
					agenda_check.id as idCheck,
					agenda_check.nome as nomeCheck,
					AV.valor, AV.valor6

					FROM agenda_check
						inner join agenda_check_fase on agenda_check.periodo = agenda_check_fase.id
						inner join agenda_check_participante_campo on agenda_check.id = agenda_check_participante_campo.campo
						inner join agenda_check_participante on agenda_check_participante_campo.participante = agenda_check_participante.id
						left join agenda_cobranca_itens AI on agenda_check_participante_campo.campo = AI.campo_id and AI.centro = agenda_check_participante.centro
        				left join agenda_check_centro_valor AV on AI.id = AV.campo and AV.centro = agenda_check_participante.centro
						where cobranca = 1
							and agenda_check_participante.numero <> ''
							and agenda_check_participante_campo.pago = 0
							and agenda_check_participante_campo.valor = 'Sim'
							and ( (AV.valor <> '') or (AV.valor6 <> '') )
							order by agenda_check_participante.numero,
								agenda_check_fase.id,
								agenda_check.ordem";
		//echo $query;
		$resItens 	= 	$conexao->pesquisa($query);
		$iCount		= 	1;
		$iTotal		=	0;
		while($rowItens = mysqli_fetch_assoc($resItens)){
			$varItem	= new FinParticipanteItem($rowItens['idCheck']);
			?>
				<tr>
					<td style="font-size: 11px !important;"><center><?=$iCount;?></center></td>
					<td style="font-size: 11px !important;"><center><?=$rowItens['numero'];?></center></td>
					<td style="font-size: 11px !important;"><center><?=$rowItens['codigo'];?></center></td>
					<td style="font-size: 11px !important;"><?=$rowItens['fase'];?></td>
					<td style="font-size: 11px !important;"><?=$rowItens['nomeCheck'];?></td>
					<td style="font-size: 11px !important;"><center>R$
					<?php
						if($rowItens['periodo']==6){
							echo number_format($rowItens['valor6'],2);
							$iTotal	= $iTotal + $rowItens['valor6'];
						}else{
							echo number_format($rowItens['valor'],2);
							$iTotal	= $iTotal + $rowItens['valor'];
						}
					?></center></td>
				</tr>
			<?php
			$iCount++;
		}
		?>
				<tr>
					<td colspan="5"></td>
					<td style="font-size: 12px !important;"><center>R$ <b><?=number_format($iTotal);?></b></td>
				</tr>
				<tfooter>
					<tr>
						<td colspan="5"></td>
						<td><button class="btn green bold uppercase btn-block" onclick="cobranca();" name="btnFiltrar" id="btnFiltrar">Gerar Cobrança</button></td>
					</tr>
				</tfooter>
		<?php
	}