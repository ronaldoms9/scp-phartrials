<?
  include "seguranca.php";
?>
<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
	<?
		include "global_css.php";
	?>	
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<link href="https://fonts.googleapis.com/css?family=Merriweather+Sans" rel="stylesheet">
	<link rel="shortcut icon" href="favicon.png">
	<link href="https://cdn.datatables.net/buttons/1.4.0/css/buttons.dataTables.min.css" rel="stylesheet">
	<link href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css" rel="stylesheet">
</head>
<body>
       <div class="page-wrapper">
            <?
               include "menu_principal.php";
            ?>
            <div class="page-wrapper-row full-height">
                <div class="page-wrapper-middle">
                    <div class="page-container">
                        <div class="page-content-wrapper">
                            <div class="page-head">
                                <div class="container-fluid">
                                    <div class="page-title">
                                        <h1><?=traduzir("Auditoria de ações no sistema", $_SESSION['lang']);?> </h1>
                                    </div>
                                </div>
                            </div>
                            <div class="page-content">
                                <div class="container-fluid">
                                    <ul class="page-breadcrumb breadcrumb">
                                        <li>
                                            <a href="inicial.php"><?=traduzir("Início", $_SESSION['lang']);?></a>
                                            <i class="fa fa-circle"></i>
                                        </li>
                                        <li>
                                            <span><?=traduzir("Audit Trail", $_SESSION['lang']);?></span>
                                        </li>
                                    </ul>
                                </div>								
								<br>
								<div class="portlet light ">
									<div class="portlet-title">
										<div class="caption">
											<i class="icon-bubble font-green-sharp"></i>
											<span class="caption-subject font-green-sharp sbold"> Log</span>
										</div>
										
									</div>
									<div class="row">
										<div class="col-md-10 column">															
											<div class="row">
													
													<div class="col-md-3 column"> 			
														<div class="search-filter ">
															<div class="search-label uppercase"><span class="caption-subject font-green-sharp sbold">Tabela</span>
															</div>
															<div class="input-icon right">
																<select name="form_tabela" id="form_tabela" class="form-control">
																	<option value=""></option>
																	<!<option value="login">LOGIN</option>
																	<?
																		$query 	 = "SELECT distinct tabela FROM log_alteracoes order by tabela";
																		$res_tab = $conexao->pesquisa($query);
																		while($row_tab = mysqli_fetch_assoc($res_tab)){
																			?>
																			<option value="<?=$row_tab['tabela'];?>" 
																			<?php
																				if($_GET['tabela']==$row_tab['tabela']){ echo " selected "; }
																			?>
																			><?=strtoupper($row_tab['tabela']);?></option>
																			<?
																		}
																	?>
																</select>
															</div>
														</div>
													</div>
													<div class="col-md-3"> 					<!--Data -->
														<table width="100%">
															<tr>
																<td>
																	<div class="search-filter ">
																		<div class="search-label uppercase"><span class="caption-subject font-green-sharp sbold"><font size="2"><b>Data Inicial</b></font></span>
																		</div>
																		<input autocomplete="new-password" type="text" class="form-control" id="form_data_inicial" name="form_data_inicial" style='text-align: center' onkeyUp="mascara(this, '##/##/####');" value="<?=$_GET['data_inicial'];?>">
																	</div>
																</td>
															</tr>
																<td>
																	<div class="search-filter ">
																		<div class="search-label uppercase"><span class="caption-subject font-green-sharp sbold"><font size="2"><b>Data final</b></font></span>
																		</div>
																		<input autocomplete="new-password" type="text" class="form-control" id="form_data_final" name="form_data_final" style='text-align: center' onkeyUp="mascara(this, '##/##/####');" value="<?=$_GET['data_final'];?>">
																	</div>
																</td>
															<tr>
															</tr>
														</table>																				
													</div>																
													<div class="col-md-3 column"> 			<!--Usuário -->
														<div class="search-filter ">
															<div class="search-label uppercase"><span class="caption-subject font-green-sharp sbold">Usuário</span>
															</div>
															<div class="input-icon right">
																<select name="form_usuario" id="form_usuario" class="form-control">
																	<option value=""></option>
																	<?
																		$query 	 = "SELECT distinct log_alteracoes.usuario_nome FROM log_alteracoes inner join usu_usuario on log_alteracoes.usuario = usu_usuario.id order by nome";
																		$res_tab = $conexao->pesquisa($query);
																		while($row_tab = mysqli_fetch_assoc($res_tab)){
																			?>
																			<option value="<?=$row_tab['usuario_nome'];?>"
																			<?php
																				if($_GET['usuario']==$row_tab['usuario_nome']){ echo " selected "; }
																			?>
																			><?=strtoupper($row_tab['usuario_nome']);?></option>
																			<?
																		}
																	?>
																</select>
															</div>
														</div>
													</div>
													<div class="col-md-2 column">																		
													</div>
											</div>
										</div>
										<div class="col-md-1 column">
											<br><button class="btn green bold uppercase btn-block" onclick="buscar();" name="btnFiltrar" id="btnFiltrar">Filtrar</button>
										</div>
									</div>
									<hr>
									<div id="buttons"></div>
									<hr>
									<div class="portlet-body" style="overflow-y: scroll; height:500px !important" >
										<table class="table table-hover table-striped table-bordered" name="tabela" id="tabela" >											
												<thead>
													<th style="font-size: 12px !important;">Tabela</th>
													<th style="font-size: 12px !important;">Usuário<br></th>
													<th style="font-size: 12px !important;">Ação</th>
													<th style="font-size: 12px !important;">Campo</th>
													<th style="font-size: 12px !important;">Valor original</th>
													<th style="font-size: 12px !important;">Novo valor</th>
													<th style="font-size: 12px !important;">Data</th>
													<th style="font-size: 12px !important;">Hora</th>													
												</thead>											
											<tbody id="tabela_retorno" name="tabela_retorno">
											<?php
												if($_GET['consulta']==1){												
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
																		<?
																			if($tabela=="login"){echo "LOGIN";}else{echo $row['tabela'];}
																		?>
																	</font>
																</td>																						
																<td><font size="2px"><?=$row['nome_usuario'];?></font></td>	
																<td><font size="2px">
																	<center>
																		<?
																			if($tabela=="login"){echo "LOGIN";}else{
																				if($row['acao']=="A"){echo "ATUALIZAÇÃO";}
																			}
																		?>
																	</center></font>
																</td>			
																<td><font size="2px">
																	<?
																		if(($tabela=="login")||($row['campo']=="acesso_web")){echo "LOGIN";}else{echo $row['campo'];}
																	?>
																	</font></td>
																<td><font size="2px"><?
																		if(($tabela=="login")||($row['campo']=="acesso_web")){echo "";}else{
																			if($row['campo']!="senha"){
																				echo $row['antigo'];
																			}else{
																				echo "****";
																			}
																		}
																	?></font></td>
																<td><font size="2px"><?
																		if(($tabela=="login")||($row['campo']=="acesso_web")){echo "";}else{
																			if(	$row['campo']!="senha"){
																				echo $row['novo'];
																			}else{
																				echo "******";
																			}
																		}
																	?></font></td>
																<td><center><font size="2px">
																	<? 
																		$data = explode(" ", $row['data_hora']);
																		$hora = $data[1];
																		$data = $data[0];
																		$data = explode("-", $data);
																		//$data = $data[2] . "/" . $data[1] . "/" . $data[0];
																		$data = $data[0] . "/" . $data[1] . "/" . $data[2];
																		echo $data;
																	?>
																</font></center></td>
																<td><center><font size="2px">
																	<? 
																		$data = explode(" ", $row['data_hora']);
																		$hora = $data[1];
																		$data = $data[0];
																		$data = explode("-", $data);
																		$data = $data[2] . "/" . $data[1] . "/" . $data[0];
																		echo $hora;
																	?>
																</font></center></td>
															</tr>
														<?
													}
												
												}
											?>
											</tbody>
										</table>
									</div>
                                </div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
            <?
                include "rodape.php";
				include "global_js.php";				
            ?>
			<script src="js/moment.min.js"></script>
			<script src="plugin/js/fullcalendar.min.js"></script>
			<script src="plugin/js/locale/pt-br.js"></script>
			
			<script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
			<script src="https://cdn.datatables.net/buttons/1.4.0/js/dataTables.buttons.min.js"></script>
			<script src="https://cdn.datatables.net/buttons/1.4.0/js/buttons.flash.min.js"></script>
			<script src="https://cdn.datatables.net/buttons/1.4.0/js/buttons.html5.min.js"></script>
			<script src="https://cdn.datatables.net/buttons/1.4.0/js/buttons.print.min.js"></script>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
			<script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/pdfmake.min.js"></script>
			<script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/vfs_fonts.js"></script>
        </div>        
</html>

	<script>
		function exporta(){$('#tabela').button(0).trigger();}
		
		function buscar(){			
			var data_inicial = document.getElementById("form_data_inicial").value;
			var	data_final   = document.getElementById("form_data_final").value;
			var tabela		 = document.getElementById("form_tabela").value;
			var usuario		 = document.getElementById("form_usuario").value;			
			var url = "aud_auditoria.php?consulta=1&data_inicial="+data_inicial+"&data_final="+data_final+"&tabela="+tabela+"&usuario="+usuario;
			muda_pagina(url);			
		}
		
		$( function() {
			$( "#form_data_inicial" ).datepicker();
			$( "#form_data_final" ).datepicker();
		} );		
		
		var tabela = $('#tabela').DataTable();
		var buttons = new $.fn.dataTable.Buttons(tabela, {
				 buttons: [				   				   
				   {
						extend: 'print',
						orientation: 'landscape',
						customize: function ( win ) {
							$(win.document.body).css( 'font-size', '9px' );                        
							$(win.document.body).find('table').addClass('compact');
							$(win.document.body).find('td').css('font-size','9px');
						}
					}
				]
			}).container().appendTo($('#buttons'));				
</script>
</html>
