<?
	include_once "seguranca.php";
?>
<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8">
<head>
	<?
		include "global_css.php";
	?>
    <link href="//cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.3.1/css/buttons.dataTables.min.css">
	<link href="https://fonts.googleapis.com/css?family=Abel|Archivo+Narrow|PT+Sans+Narrow:700|Pragati+Narrow" rel="stylesheet">
	<link href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/r/bs-3.3.5/jq-2.1.4,dt-1.10.8/datatables.min.css"/>	
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.4.2/css/buttons.dataTables.min.css"/>
	<style>
            .icone:hover {
                width:25px;
            }
		    .lista_formularios{
				font-size: 10px !important;				
				font-family: 'Abel', sans-serif;
			}
			.header_formularios{
				font-size: 12px !important;				
				font-family: 'Abel', sans-serif;
				font-weight: bold;
			}
			.dataTables_wrapper .dataTables_processing {
				position: absolute;
				top: 30%;
				left: 50%;
				width: 50%;
				height: 150px;
				margin-left: -20%;
				margin-top: -25px;
				padding-top: 20px;
				text-align: center;
				font-size: 1.2em;
				background: white;
			}
    </style>
</head>
	
    <body class="page-container-bg-solid">
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
                                        <h1><?=traduzir("ANEXOS", $_SESSION['lang']);?> </h1>
                                    </div>
                                </div>
                            </div>
                            <div class="page-content">
                                <div class="container-fluid">									
									<div class="page-content-inner">
										<div class="row widget-row">										
											<div class="col-md-2">
												<div class="portlet light">
													<div class="portlet-title">
														<div class="caption">
															<i class="icon-speech"></i>
															<span class="caption-subject bold uppercase"> ANEXO</span>
														</div>
																												
													</div>
													<div class="portlet-body">
														<div class="scroller" style="height:450px" data-rail-visible="1" data-rail-color="yellow" data-handle-color="#a1b2bd">
															<table width="100%" class="table table-hover table-condensed" style="background-color:white !important">
																<thead class="header_formularios">
																	<th><center>#</center></th>
																	<th>ANEXO</th>																	
																</thead>
																<tbody>
																	<?
																		$query = "select id, nome from crf_forms where tipo = 2 order by ordem";	
																		$res   = $conexao->pesquisa($query);
																		while($row = mysqli_fetch_assoc($res)){
																	?>
																			<tr onclick='javascript:$conexao->pesquisa(<?=$row['id'];?>)' class='lista_formularios' style="cursor: pointer";>
																				<td width="40"><center><?=$row['ordem'];?></center></td>
																				<td>
																					<? 
																						//if($_GET['anexo']==$row['numero']){ echo "<b>";}
																						echo $row['nome'];
																						//if($_GET['centro']==$row['numero']){ echo "</b>";} 
																					?>																						
																				</td>
																			</tr>
																	<?
																		}
																	?>																	
																</tbody>
															</table>
														</div>
													</div>
												</div>
											</div>
											<?
											if($_GET['anexo']!=""){
												$anexo 		= $_GET['anexo'];
												$query 		= "select id, nome from crf_forms where id = " . $anexo;	
												$resAnexo 	= $conexao->pesquisa($query);
												$rowAnexo	= mysqli_fetch_assoc($resAnexo);
											?>
												<div class="col-md-10">
													<div class="portlet light">
														<div class="portlet-title">
															<div class="caption">
																<i class="icon-speech"></i>
																<span class="caption-subject bold uppercase"> # ANEXO - <?=$rowAnexo['nome'];?> </span>
															</div>
															<div class="actions">
																<div class="btn-group">
																	<a class="btn blue-oleo btn-circle btn-sm" href="javascript:;" data-toggle="dropdown" data-hover="dropdown" data-close-others="true"> EXPORTAR
																		<i class="fa fa-angle-down"></i>
																	</a>
																	<ul class="dropdown-menu pull-right">
																		<li>
																			<a href="javascript:exporta_excel();"> EXCEL </a>
																		</li>
																		<li class="divider"> </li>
																		<li>
																			<a href="javascript:popup('rep_anexo.php?anexo=<?=$_GET['anexo'];?>');"> PDF </a>
																		</li>																	
																	</ul>
																</div>
															</div>
														</div>
														<div class="portlet-body">
															<div class="scroller" style="height:450px" data-rail-visible="1" data-rail-color="yellow" data-handle-color="#a1b2bd">
																<table width="100%" class="table table-hover table-condensed" style="background-color:white !important" id="tabela" name="tabela">
																	<thead class="header_formularios">
																		<tr>
																			<th>Participante</th>
																	<?
																		$query 		= "select id, nome, texto from crf_forms_campos where formulario = " . $anexo . " order by ordem";
																		$resCampos	= $conexao->pesquisa($query);
																		while($rowCampos = mysqli_fetch_assoc($resCampos)){
																			echo "<th>".$rowCampos['texto']."</th>";
																		}
																	?>
																		</tr>
																	</thead>
																	<tbody class="lista_formularios">
																		<?
																			$query = "select 
																						crf_paciente_pre_rand.id AS id,
																						crf_paciente_pre_rand.nro_triagem AS nro_triagem,
																						crf_paciente_pre_rand.nro_participante AS nro_participante,
																						crf_paciente_pre_rand.nro_paciente AS nro_paciente,
																						crf_paciente_pre_rand.centro AS centro,
																						crf_paciente_pre_rand.nro_paciente_formatado AS nro_paciente_formatado,
																						V1.valor as participante						
																						from crf_paciente_pre_rand 
																							inner join crf_entrada_forms on crf_paciente_pre_rand.centro = crf_entrada_forms.centro
																									and crf_paciente_pre_rand.nro_paciente = crf_entrada_forms.paciente
																									and crf_paciente_pre_rand.projeto = crf_entrada_forms.projeto
																							 
																							 inner join crf_entrada_campos V1 on crf_entrada_forms.id = V1.form_id
																									and V1.campo_id = 107       		
																														
																							where crf_paciente_pre_rand.projeto = ".$_SESSION['projeto']." and crf_paciente_pre_rand.centro = 26
																							order by crf_paciente_pre_rand.nro_paciente";
																							
																			$resPart = $conexao->pesquisa($query);
																			while($rowPart = mysqli_fetch_assoc($resPart)){
																				?>
																					<tr>
																						<td><center><?=$rowPart['nro_paciente'];?></center></td>
																						<?
																							$query = " SELECT 
																											crf_entrada_forms.id,
																											crf_entrada_forms.paciente,
																											crf_entrada_campos.campo_id, 
																											crf_entrada_campos.valor
																												FROM crf_entrada_forms 
																													inner join crf_entrada_campos on crf_entrada_forms.id = crf_entrada_campos.form_id
																													inner join crf_forms_campos on crf_entrada_campos.campo_id = crf_forms_campos.id
																													WHERE 
																														crf_entrada_forms.form_id = ".$anexo."
																														and crf_entrada_forms.paciente = ".$rowPart['nro_paciente']."
																														and crf_entrada_forms.projeto = ".$_SESSION['projeto']."																													
																													order by crf_forms_campos.ordem																								
																							";
																							$resCampos = $conexao->pesquisa($query);
																							while($rowCampos = mysqli_fetch_assoc($resCampos)){
																								?>
																									<td><?=$rowCampos['valor'];?></td>
																								<?
																							}
																						?>
																					</tr>
																				<?
																			}
																		?>
																	</tbody>
																</table>																
															</div>
														</div>
													</div>
												</div>	
											<?
											}											
											?>
										</div>
									</div>
									
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>        	
	<?
		include "rodape.php";
		include "global_js.php";
	?>
	</body>	
	<script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js" type="text/javascript"></script>		
	<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.4.2/js/dataTables.buttons.min.js"></script>
	<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
	<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
	<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
	<script type="text/javascript" src="//cdn.datatables.net/buttons/1.4.2/js/buttons.html5.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
	<script>
		function $conexao->pesquisa(id){
			document.location = "mon_anex_pre_estudo.php?anexo="+id;
		}
		table = $('#tabela').DataTable({
					"iDisplayLength": 30,
					"ordering": false,
					"language": {
						"lengthMenu": "Mostrar  _MENU_  registros por página",
						"zeroRecords": "Nada achado",
						"info": "Mostrando página _PAGE_ de _PAGES_",
						"infoEmpty": "Sem dados para exibir",
						"infoFiltered": "(Filtrado de um total de _MAX_ registros)",
						"search":         "Pesquisar:",
						"destroy": true,
						"paginate": {
							"first":      "Primeiro",
							"last":       "Último",
							"next":       "Próximo",
							"previous":   "Anterior"
						},
						"aria": {
							"sortAscending":  ": Ordenar coluna de forma crescente",
							"sortDescending": ": Ordenar coluna de forma decrescente"
						}
					},
					dom: 'Bfrtip',
					buttons: [
						'excelHtml5',
						'csvHtml5',
						'pdfHtml5',
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
		} );
		function exporta_excel(){
			var tab_text="<table border='2px'><tr bgcolor='#87AFC6'>";
			var textRange; var j=0;
			tab = document.getElementById('tabela'); // id of table

			for(j = 0 ; j < tab.rows.length ; j++) 
			{     
				tab_text=tab_text+tab.rows[j].innerHTML+"</tr>";
				//tab_text=tab_text+"</tr>";
			}

			tab_text=tab_text+"</table>";
			tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
			tab_text= tab_text.replace(/<img[^>]*>/gi,""); // remove if u want images in your table
			tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params

			var ua = window.navigator.userAgent;
			var msie = ua.indexOf("MSIE "); 

			if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
			{
				txtArea1.document.open("txt/html","replace");
				txtArea1.document.write(tab_text);
				txtArea1.document.close();
				txtArea1.focus(); 
				sa=txtArea1.document.execCommand("SaveAs",true,"ANEXO.xls");
			}  
			else                 //other browser not tested on IE 11
				sa = window.open('data:application/vnd.ms-excel,' + escape(tab_text));  

			return (sa);
		}
	</script>
	
</html>