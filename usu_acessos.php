<?php
	include_once "seguranca.php";
	  

	$tabela = "usu_acessos";
	$pagina = "usu_acessos.php";
?>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
        <meta charset="utf-8" />
        <title><?=$titulo;?></title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="Preview page of Metronic Admin Theme #3 for " name="description" />
        <meta content="" name="author" />
        <?php
			include "global_css.php";
		?>

        <link rel="shortcut icon" href="favicon.ico" />
</head>
    <body class="page-container-bg-solid">
        <div class="page-wrapper">
            <?php
               include "menu_principal.php";
            ?>
            <div class="page-wrapper-row full-height">
                <div class="page-wrapper-middle">
                    <div class="page-container">
                        <div class="page-content-wrapper">
                            <div class="page-head">
                                <div class="container-fluid">
                                    <div class="page-title">
                                        <h1><?=traduzir("Histórico de acessos", $_SESSION['lang']);?> </h1>
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
                                            <span><?=traduzir("Histórico de acessos", $_SESSION['lang']);?></span>
                                        </li>
                                    </ul>

                                    
                                        <div class="row">
                                            <div class="col-md-2"></div>
                                            <div class="col-md-8">
                        						<?php
                                                      $query = "select * from $tabela where usuario = '".$_SESSION['user_nome']."' order by id desc";
                                                      $res = $conexao->pesquisa($query);

                            					?>
												<div class="portlet-body">
													<div class="scroller" style="height:350px" data-always-visible="1" data-rail-visible="1" data-rail-color="red" data-handle-color="green">
														<table class="table table-striped table-bordered table-hover table-checkable order-column" id="tabela_historico" style="background-color: white">
															   <thead>
																	  <th width="40%">Usuário</th>
																	  <th>Data / Hora</th>
																	  <th>IP de acesso</th>
																	  <th>Resultado</th>
															   </thead>
															   <tbody>
																	 
																	  <?php
																		   while($row = mysqli_fetch_assoc($res)){
																			  echo "<tr>";
																				  echo "<td>".$row['usuario']."</td>";
																				  echo "<td>".$row['data_hora']."</td>";
																				  echo "<td>".$row['ip']."</td>";
																				  echo "<td>".$row['resultado']."</td>";
																			  echo "</tr>";
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
               </div>
            </div>
            </div>
            <?php
                include "rodape.php";
            ?>
        </div>
        <div class="quick-nav-overlay"></div>
        <!--[if lt IE 9]>
<script src="assets/global/plugins/respond.min.js"></script>
<script src="assets/global/plugins/excanvas.min.js"></script>
<script src="assets/global/plugins/ie8.fix.min.js"></script>
<![endif]-->
        <?php
		    include "global_js.php";
		?>
</body>

</html>
