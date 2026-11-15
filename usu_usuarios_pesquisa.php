<?php
	include_once "seguranca.php";
	include_once "class/usuario.class.php";		
	include_once "class/centro.class.php";
?>
	<link href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/r/bs-3.3.5/jq-2.1.4,dt-1.10.8/datatables.min.css"/>	
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.4.2/css/buttons.dataTables.min.css"/>
	<div class="row">
		<br>
		<div class="col-md-12">
			<div class="portlet light">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-paper-plane font-yellow-casablanca"></i>
						<span class="caption-subject bold font-yellow-casablanca uppercase"> Listagem de usuários</span>
					</div>										
				</div>
				
				<div class="portlet-body">
					<div style="height:300px; overflow:auto;">
						<table name="tblUsuarios" id="tblUsuarios" class="table table-striped table-hover table-sm">
							<thead>
								<th>Id</th>
								<th>Usuário</th>
								<th>Nome</th>
								<th>Função</th>
								<th>Centro</th>
								<th></th>
							</thead>
							<tbody>
								 
								<?php
									$query = "select id from usu_usuario order by nome";
									$resUsu= $conexao->pesquisa($query);
									while($rowUsu = mysqli_fetch_assoc($resUsu)){
										$varUsuario = new Usuario($rowUsu['id']);
									?>
										<tr>
											<td><font size="2px"><?=$varUsuario->id;?></font></td>
											<td><font size="2px"><?=$varUsuario->usuario;?></font></td>
											<td><font size="2px"><?=$varUsuario->nome;?></font></td>
											<td><font size="2px"><?=$varUsuario->funcao;?></font></td>
											<td><font size="2px">
												<?php
													$varCentro = new CentroPesquisa($varUsuario->centro);
													echo $varCentro->nome;
												?></font>
											</td>
											<td><center><a href="usu_usuarios.php?id=<?=$varUsuario->id;?>"><i class="fa fa-hand-o-left" aria-hidden="true"></i></a></center></td>
										</tr>
									<?php
									}
								?>
								
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	