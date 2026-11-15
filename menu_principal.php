<?php
	include_once "seguranca.php";
	include_once "class/conexao.class.php";


	$conexao = new Conexao();
?>
			<style>
				#fade {
					display: none;
					position:absolute;
					top: 0%;
					left: 0%;
					width: 200%;
					height: 200%;
					background-color: #ababab;
					z-index: 1001;
					-moz-opacity: 0.8;
					opacity: .70;
					filter: alpha(opacity=80);
				}
				#modal_carregando {
					display: none;
					position: absolute;
					top: 45%;
					left: 45%;
					width: 94px;
					height: 94px;
					padding:30px 15px 0px;
					border: 3px solid #ababab;
					box-shadow:1px 1px 10px #ababab;
					border-radius:20px;
					background-color: white;
					z-index: 1002;
					text-align:center;
					overflow: auto;
				}
			</style>
			<div class="modal fade" id="modalAlterarSenha" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			  <div class="modal-dialog modal-lg">
				<div class="modal-content">
				  <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Modal title</h4>
				  </div>
				  <div class="modal-body">
					  <a class="custom-close"> My Custom Close Link </a>
				  </div>
				  <div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary">Save changes</button>
				  </div>
				</div>
			  </div>
			</div>
			<div id="fade"></div>
			<div id="modal_carregando">
				<img id="loader" src="imagens/loading.gif" />
			</div>

            <div class="page-wrapper-row">
                <div class="page-wrapper-top">
                    <div class="page-header">
                        <div class="page-header-top">
                            <div class="container-fluid ">
                                <div class="page-logo">
                                    <a href="inicial.php">
                                        <!--<img src="logo_spc_branco.png" alt="logo" class="logo-default" height="50">-->
										<img src="logo.png" alt="logo" class="logo-default" height="80" style=" margin-top: 5px;margin-left: -30px;">
                                    </a>
                                </div>
                                <a href="javascript:;" class="menu-toggler"></a>

                                <div class="top-menu">
                                    <ul class="nav navbar-nav pull-right">
										<li>
											<div class="btn-group">
												<button type="button" class="btn btn-link dropdown-toggle btn-xs" data-toggle="dropdown">
												<?
												   if(($_SESSION['lang']=="p")||($_SESSION['lang']=="")){ echo '<img src="assets_face/img/flags/brasil.png" alt="Brail"> Português <span class="caret"></span>';};
												   if($_SESSION['lang']=="i"){ echo '<img src="assets_face/img/flags/eua.png" alt="Inglês"> Inglês <span class="caret"></span>';};
												   if($_SESSION['lang']=="e"){ echo '<img src="assets_face/img/flags/espanha.gif" alt="Espanhol"> Espanhol <span class="caret"></span>';};
												?>
												</button>
												<ul class="dropdown-menu dropdown-menu-right country-selector" role="menu">
													<li>
														<a href="javascript:muda_linguagem('p');"><img src="assets_face/img/flags/brasil.png" alt="Brasil"> Português</a>
													</li>
													<li>
														<a href="javascript:muda_linguagem('i');"><img src="assets_face/img/flags/eua.png" alt="Inglês"> English</a>
													</li>
													<li>
														<a href="javascript:muda_linguagem('e');"><img src="assets_face/img/flags/espanha.gif" alt="Espanhol"> Spanish</a>
													</li>
												</ul>
											</div>
										</li>

										<li class="dropdown dropdown-user dropdown-dark">
                                            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                                <img alt="" class="img-circle" src="imagens/ico_avatar.png">
                                                <span class="username username-hide-mobile"><?=strtoupper($_SESSION['nome_usuario']);?><br><span class="cabecalho_funcao">[<?=traduzir($_SESSION['tipo_nome'], $_SESSION['lang']);?>]
												<?
												if($_SESSION['user_id']=="1"){echo "<br>[".$conexao->retornaNomeBanco()."]";}
												?>
												</span></span>
                                            </a>
                                            <ul class="dropdown-menu dropdown-menu-default">
												<?
													$randomico = rand(2342, 34634643);
												?>
                                                <li>
													<a data-toggle="modal" data-target="#modalAlterarSenha" href="pagina/alterar_senha/alterar_senha.php?rand=<?=$randomico;?>"><i class="icon-lock"></i> <?=traduzir("Alterar Senha", $_SESSION['lang']);?> </a>
                                                </li>
                                                <li>
                                                    <a href="usu_acessos.php">
                                                        <i class="icon-calendar"></i> <?=traduzir("Histórico de acessos", $_SESSION['lang']);?> </a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li class="dropdown dropdown-extended quick-sidebar-toggler">
                                            <span class="sr-only">Toggle Quick Sidebar</span>
                                            <img src="imagens/ico_logout.png" alt="Efetuar Logout" class="img-circle" style="width: 30px; height: 30px;" onclick="javascript:document.location='usu_logoff.php';">
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="page-header-menu">
                            <div class="container-fluid">
								<form class="search-form" action="page_general_search.html" method="GET">
                                    <div class="input-group" style="width: 200px !important;">
                                        <select
											class="form-control select_pesquisa "
											data-original-title="ESCOLHA O PROJETO"
											data-container="body" data-placement="left"
											id="sistema_projeto"
											onchange="javascript:mudou_projeto(this.value);"
											>
										</select>
                                    </div>
                                </form>
                                <div class="hor-menu  ">
                                    <ul class="nav navbar-nav">
                                      <?
                                         //Montagem do MENU
                                         //Nível 1
                                         //$query = "select * from menu_itens where pai < 1 order by ordem";
                                         $query = "select menu_itens.id, menu_itens.nome, menu_itens.link, menu_itens.ordem, usu_usuario_tipo_menu.acessa, menu_itens.mostra, menu_itens.nova_janela
													from menu_itens
													left join usu_usuario_tipo_menu on menu_itens.id = usu_usuario_tipo_menu.id_menu
                                                  	and usu_usuario_tipo_menu.id_usuario = ".$_SESSION['tipo_usuario'];
                                         $query .= "    where pai < 1 and usu_usuario_tipo_menu.acessa = 'ON' ";
										if(isset($_SESSION['projeto'])){
											if($_SESSION['projeto']<1){
												$query .= " and mostra = 1 ";
											}
										}else{
											$query .= " and mostra = 1 ";
										}
										$query .= "		order by ordem  ";
                                         $res_menu = $conexao->pesquisa($query);
                                         while($row_menu = mysqli_fetch_assoc($res_menu)){
                                         ?>
                                              <li aria-haspopup="true" class="menu-dropdown classic-menu-dropdown ">
												<?
												if($row_menu['nova_janela']==0){
													if(substr($row_menu['link'],0,10)!="JAVASCRIPT"){
														if($row_menu['link']!=""){
														?>
															<a href="javascript:muda_pagina('<?=$row_menu['link'];?>');"> <?=traduzir($row_menu['nome']);?>
														<?
														}else{
														?>
															<a href="#"> <?=traduzir($row_menu['nome']);?>
														<?
														}
													}else{
													?>
													<a href="<?=$row_menu['link'];?>;void(0);"> <?=traduzir($row_menu['nome']);?>
													<?
													}
												}else{
													?>
													  <a href="javascript:window.open('<?=$row_menu['link'];?>','popup')"><?=traduzir($row_menu['nome']);?>
													<?
												}
												?>
                                                    <span class="arrow"></span>
                                                </a>
                                                <?
                                                     //Montagem do SEGUNDO nível
                                                     //$query = "select * from menu_itens where pai = " . $row_menu['id'] . " order by ordem ";
													 $query = "select menu_itens.id, menu_itens.nome, menu_itens.link, menu_itens.ordem, usu_usuario_tipo_menu.acessa, menu_itens.nova_janela    ";
													 $query .= "	from menu_itens   ";
													 $query .= "         left join usu_usuario_tipo_menu on menu_itens.id = usu_usuario_tipo_menu.id_menu  ";
													 $query .= "         	and usu_usuario_tipo_menu.id_usuario = ".$_SESSION['tipo_usuario'];
													 $query .= "    where pai = ".$row_menu['id']." and usu_usuario_tipo_menu.acessa = 'ON'  ";
													 $query .= "    order by ordem  ";
                                                     $res_2_nivel = $conexao->pesquisa($query, $conexao);
                                                     if(mysqli_num_rows($res_2_nivel)>0){
                                                         echo '<ul class="dropdown-menu">';
                                                         while($row_2_nivel = mysqli_fetch_assoc($res_2_nivel)){
																 $query = "select menu_itens.id, menu_itens.nome, menu_itens.link, menu_itens.ordem, usu_usuario_tipo_menu.acessa, menu_itens.nova_janela    ";
																 $query .= "	from menu_itens   ";
																 $query .= "         left join usu_usuario_tipo_menu on menu_itens.id = usu_usuario_tipo_menu.id_menu  ";
																 $query .= "         	and usu_usuario_tipo_menu.id_usuario = ".$_SESSION['tipo_usuario'];
																 $query .= "    where pai = ".$row_2_nivel['id']." and usu_usuario_tipo_menu.acessa = 'ON'  ";
																 $query .= "    order by ordem  ";
                                                                 $res_3_nivel = $conexao->pesquisa($query);
                                                                 if(mysqli_num_rows($res_3_nivel)>0){
                                                                      echo '<li aria-haspopup="" class="dropdown-submenu">';
                                                                 }else {
                                                                 	    echo '<li aria-haspopup="" class=" ">';
                                                                 }
																	if($row_2_nivel['nova_janela']==0){
																	if(strtoupper(substr($row_2_nivel['link'],0,10))!="JAVASCRIPT"){
																	 $link = trim($row_2_nivel['link']);
																	 if($link!=""){
																		 ?>
																		 <a href="javascript:muda_pagina('<?=$row_2_nivel['link'];?>')"> <?=traduzir($row_2_nivel['nome']);?></a>
																		 <?
																	 }else{
																		 ?>
																		 <a href="#"> <?=traduzir($row_2_nivel['nome']);?></a>
																		 <?
																	 }

																		}else{
																		?>
																		<a href="<?=$row_2_nivel['link'];?>;void(0);"> <?=traduzir($row_2_nivel['nome']);?>
																		<?
																		}
																	}else{
																		?>
																		  <a href="javascript:window.open('<?=$row_2_nivel['link'];?>','popup')"> <?=traduzir($row_2_nivel['nome']);?>
																		<?
																	}
																	?>

                                                                    </a>
                                                                    <?
                                                                        //3 NIVEL
                                                                        //Montagem do SEGUNDO nível
                                                                         $query = "select * from menu_itens where pai = " . $row_2_nivel['id'] . " order by ordem ";
                                                                         $res_3_nivel = $conexao->pesquisa($query);
                                                                         if(mysqli_num_rows($res_3_nivel)>0){
                                                                             echo '<ul class="dropdown-menu">';
                                                                             while($row_3_nivel = mysqli_fetch_assoc($res_3_nivel)){
                                                                                 ?>
                                                                                     <li aria-haspopup="" class=" ">
                                                                                        <a href="javascript:muda_pagina('<?=$row_3_nivel['link'];?>');"> <?=traduzir($row_3_nivel['nome']);?>
                                                                                            <span class="arrow"></span>
                                                                                        </a>
                                                                                     </li>
                                                                                 <?
                                                                             }
                                                                             echo '</ul>';
                                                                         }
                                                                    ?>
                                                                 </li>
                                                             <?
                                                         }
                                                         echo '</ul>';
                                                     }
                                                ?>
                                              </li>
                                         <?
                                         }
                                      ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
