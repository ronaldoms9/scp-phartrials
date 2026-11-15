<?php
	include_once "seguranca.php";
	
	$campo = $_GET['campo'];
	$query = "SELECT  ";
	$query .= "	crf_entrada_campos.id, crf_entrada_campos.campo_id, crf_forms_campos.texto ";
	$query .= "	    	FROM crf_entrada_campos ";
	$query .= "	        	inner join crf_forms_campos on crf_entrada_campos.campo_id = crf_forms_campos.id ";
	$query .= "  where crf_entrada_campos.id = " . $campo;
	$res_campo = $conexao->pesquisa($query);
	$row_campo = mysqli_fetch_assoc($res_campo);
			
	$query = "select UU.nome, CP.pergunta, CP.id, CP.data_hora from crf_entrada_campos_pergunta CP inner join usu_usuario UU on CP.log_usuario = UU.id where CP.pergunta_id = 0 and CP.campo_id = " .$campo;
	$resHist = $conexao->pesquisa($query);
	
	$xxpt = $_GET['xxpt'];
?>
<html>
	<head>
		<link href="assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	</head>
	<script>
		
	</script>
	<body>

		<div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                 <h4 class="modal-title">Questionamentos para o campo "<?=$row_campo['texto'];?>"</h4>

            </div>
            <div class="modal-body">
				<div class="te" style="overflow-y: scroll; height:350px !important">						
					<table width="100%" class="table table-striped table-condensed">
					   <thead>
							  <th>Usuário</th>
							  <th>Data/Hora</th>
							  <th>Pergunta/Resposta</th>
							  <th>
					   </thead>
					   <tbody>
					   <?php
							while($rowHist=mysql_fetch_assoc($resHist)){
							?>
								<tr>
									<td><font style="font-size:9.0pt;"><?=$rowHist['nome'];?></font></td>
									<td>
										<font style="font-size:9.0pt;">
										<?php
										   $data = quebra_linha($rowHist['data_hora']," ", 1);
										   $hora = quebra_linha($rowHist['data_hora']," ", 2);
										   $data = explode("-",$data);
										   $data = $data[2]."/".$data[1]."/".$data[0];
										   echo $data ." ".$hora;
										?>
										</font>
									</td>
									<td>
										<textarea name="query_<?=$campo;?>" id="query_<?=$campo;?>" class="form-control" rows="5"><?=$rowHist['pergunta'];?></textarea>
									</td>
									<td>
										<span><a href="javascript:salvar_query(<?=$campo;?>, <?=$rowHist['id'];?>);"><i class="fa fa-floppy-o"></i></a></span>
										<span><a href="javascript:deletar_query(<?=$campo;?>, <?=$rowHist['id'];?>);"><i class="fa fa-trash"></i></a></span>
									</td>
								</tr>
								
								<?php
									 $query = "select UU.nome, CP.pergunta, CP.data_hora from crf_entrada_campos_pergunta CP inner join usu_usuario UU on CP.log_usuario = UU.id where CP.pergunta_id = ".$rowHist['id'];
									 $resHistR = $conexao->pesquisa($query);
									 if(mysqli_num_rows($resHistR)>0){
										 $rowHistR = mysqli_fetch_assoc($resHistR);
									 ?>
									   <tr>
											<td><font style="font-size:9.0pt;"><?=$rowHistR['nome'];?></font></td>
											<td><font style="font-size:9.0pt;">
											<?php
											   $data = quebra_linha($rowHistR['data_hora']," ", 1);
											   $hora = quebra_linha($rowHistR['data_hora']," ", 2);
											   $data = explode("-",$data);
											   $data = $data[2]."/".$data[1]."/".$data[0];
											   echo $data ." ".$hora;
											?>
											</font>
											</td>
											<td><b><font style="font-size:9.0pt;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$rowHistR['pergunta'];?></font></b></td>
										</tr>
									 <?php
									 }
							}
					   ?>					   
					   </tbody>
					</table>					
				</div>		
				<div class="row">
					<div class="col-md-2">
						Questionamento
					</div>
					<div class="col-md-8">
						<textarea class="form-control" name="query_nova" id="query_nova" rows="2"></textarea>
					</div>
					<div class="col-md-2">
						<button style="padding: 3.0% 0;"  type="button" class="btn btn-block btn-default " onclick="javascript:nova_query(<?=$campo;?>, <?=$xxpt;?>);"><i class="fa fa-floppy-o"></i><span style="font-size: 15px;">&nbsp&nbsp&nbsp Salvar</span></button><br>
					</div>
				</div>
			</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
            </div>
        </div>
	</body>
<script>
	
	
	
</script>
</html>