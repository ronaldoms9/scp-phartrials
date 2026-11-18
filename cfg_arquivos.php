<?php
  include_once "seguranca.php";
  
  if($_SESSION['valido']<1){echo "<script>window.location.replace('expirado.php');</script>";};
  $id = $_GET[base64_encode('codigo')];
  $act = strtoupper($_GET[base64_encode('act')]);
  $tabela = "arq_tipos_arquivos";
  $pagina = "cfg_arquivos.php";
  $campos = array("nome", "pai", "ordem", "patrocinador", "site");
  $tipos  = array("S", "S", "S", "S", "S");
  if($act=='DU'){	//Se for para duplicar
	$novo_item = $_GET['nome'];
	//Pegar o pai do item de origem
	$query = "select * from arq_tipos_arquivos where id = " . $id;
	$res   = $conexao->pesquisa($query);
	$row   = mysqli_fetch_assoc($res);
	$query =  "insert into arq_tipos_arquivos (nome, pai, ordem, patrocinador, site) values ( ";
	$query .= "'".$novo_item."',";
	$query .= "'".$row['pai']."',";
	$query .= "'".intval($row['ordem']+1)."',";
	$query .= "'".$row['patrocinador']."',";
	$query .= "'".$row['site']."')";
	$conexao->pesquisa($query);
	//Pego o novo id
	$query = "select id from arq_tipos_arquivos where nome = '".$novo_item."' and pai = '".$row['pai']."'";
	$res = $conexao->pesquisa($query);
	$row = mysqli_fetch_assoc($res);
	duplica_arvore($id, $row['id']);
  }
  if($act=='A'){
      $query = "update $tabela set ";
      $sql_campos = "";
      $pega = 0;
      for($i=0;$i<=count($campos)-1;$i++){
          if($pega>0){$sql_campos .= ", ";}else{$pega++;}
          $sql_campos .= $campos[$i] . " = '" . $_POST['form_'.$campos[$i]] . "'";
      }
      $query .= $sql_campos . " where id = ".$id;
      $conexao->pesquisa($query);
      $msg=1;
  }
  if($act=="E"){
     $query = "delete from $tabela where id = " . $_GET['id'];
     $conexao->pesquisa($query);
  }
  if($act=='I'){
      $sql_campos = "";
      $sql_valores = "";
      $pega = 0;
      for($i=0;$i<=count($campos)-1;$i++){
          if($pega>0){$sql_campos .= ", ";$sql_valores .= ", ";}else{$pega++;}
          $sql_campos  .= $campos[$i];
          $sql_valores .= "'".$_POST['form_'.$campos[$i]]."'";
      }
      $query = "select max(id) as ult from $tabela";
      $resU = $conexao->pesquisa($query);
      $rowU = mysqli_fetch_assoc($resU);
      $ultimo = $rowU['ult']+1;
      $query = "insert into $tabela ($sql_campos) values ($sql_valores);";
      $conexao->pesquisa($query);
      $msg=2;
  }
 if($id>0){
      $query = "select * from $tabela where  id = ".$id;
      $resUsu = $conexao->pesquisa($query);
      $rowUsu = mysqli_fetch_assoc($resUsu);
 }
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
        <?php
			include "global_css.php";
		?>
        <!-- BEGIN PAGE LEVEL PLUGINS -->
		<link href="assets/global/plugins/jstree/dist/themes/default/style.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/jstree/dist/themes/default/style.min.css" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL PLUGINS -->
    <script>
    <?php
        if($msg==1){ echo "alert('Ítem atualizado com sucesso');"; }
        if($msg==2){ echo "alert('Ítem cadastrado com sucesso');"; }
        if($msg==3){ echo "alert('Ítem deletado com sucesso');"; }
    ?>
    </script>
</head>
    <body class="page-container-bg-solid page-footer-fixed">
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
                                        <h1><?=traduzir("DASHBOARD", $_SESSION['lang']);?> </h1>
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
                                            <span><a href="cfg_arquivos.php"><?=traduzir("Ítens do TMF", $_SESSION['lang']);?></a></span>
                                        </li>
                                    </ul>
				<div class="row fundo_branco" >
                    <div class="col-md-1"></div>
                    <div class="col-md-4 ">
                     <div class="portlet-body">
                          <div id="tree_1" class="tree-demo">
                               <ul>
                                   <li> Arquivos
                                        <ul>
                                            <?php
                                                $query = " select * from $tabela where pai < 1 order by ordem";
                                                $res_lista = $conexao->pesquisa($query);
                                                $cont = 0;
                                                while($row_lista = mysqli_fetch_assoc($res_lista)){
                                                if($cont==0){
                                                    echo "<li data-jstree='{ ";
                                                    echo '"selected" : true }';
                                                    echo "'>";
                                                }else{
                                                    echo "<li data-jstree='{";
                                                    echo '"opened" : false }';
                                                    echo "'>";
                                                }
                                            ?>
                                                    <a href="<?=$pagina.'?'.gera_parametro("codigo",$row_lista['id']);?>"><?=$row_lista['nome'];?></a>
                                                    <?php
                                                         $query = " select * from $tabela where pai = " . $row_lista['id']. "  order by ordem ";
                                                         $res_filho = $conexao->pesquisa($query);
                                                         if(mysqli_num_rows($res_filho)>0){
                                                             echo "<ul>";
                                                             while($row_filho = mysqli_fetch_assoc($res_filho)){
                                                             ?>
                                                                    <li data-jstree='{  }'> <a href="<?=$pagina.'?'.gera_parametro("codigo",$row_filho['id']);?>"><?=$row_filho['nome'];?></a>
                                                             	     <?php
                                                             	      $query = " select * from $tabela where pai = " . $row_filho['id'] . " order by ordem";
				                                                         $res_3_nivel = $conexao->pesquisa($query);
				                                                         if(mysqli_num_rows($res_3_nivel)>0){
				                                                             echo "<ul>";
				                                                             while($row_3_nivel = mysqli_fetch_assoc($res_3_nivel)){
				                                                             ?>	
				                                                             	<li data-jstree='{  }'> <a href="<?=$pagina.'?'.gera_parametro("codigo",$row_3_nivel['id']);?>"><?=$row_3_nivel['nome'];?></a>
																					<?php
																					  $query = " select * from $tabela where pai = " . $row_3_nivel['id'];
																						 $res_4_nivel = $conexao->pesquisa($query);
																						 if(mysqli_num_rows($res_4_nivel)>0){
																							 echo "<ul>";
																							 while($row_4_nivel = mysqli_fetch_assoc($res_4_nivel)){
																							 ?>	
																								<li data-jstree='{  }'> <a href="<?=$pagina.'?'.gera_parametro("codigo",$row_4_nivel['id']);?>"><?=$row_4_nivel['nome'];?></a>
																									<?php
																										  $query = " select * from $tabela where pai = " . $row_4_nivel['id'];
																											 $res_5_nivel = $conexao->pesquisa($query);
																											 if(mysqli_num_rows($res_5_nivel)>0){
																												 echo "<ul>";
																												 while($row_5_nivel = mysqli_fetch_assoc($res_5_nivel)){
																												 ?>	
																													<li data-jstree='{  }'> <a href="<?=$pagina.'?'.gera_parametro("codigo",$row_5_nivel['id']);?>"><?=$row_5_nivel['nome'];?></a>
																													</li>
																												 <?php	
																												 }
																												 echo "</ul>";
																											 }    
																									?>
																								</li>
																							 <?php	
																							 }
																							 echo "</ul>";
																						 }    
																					?>
																				</li>
				                                                             <?php	
				                                                             }
				                                                             echo "</ul>";
				                                                         }    
				                                                             ?>
                                                             	      </li>
                                                             <?php }
                                                             echo "</ul>";
                                                            }
                                                    ?>
                                                </li>
                                            <?php
                                                $cont++;
                                                }
                                            ?>
                                        </ul>
                                   </li>
                               </ul>
                          </div>
                     </div>
                    </div>
                    <div class="col-md-6">
						<?php
                             if($id>0){                   //Atualizacao
                                 $acao = $pagina."?".gera_parametro("codigo",$id)."&".gera_parametro("act","A");
                             }else{$acao = $pagina."?".gera_parametro("act","I");}
						?>
						<br>
						<center><h4>Cadastro de ítens do TMF</h4></center>
						<br>
						<form class="form-horizontal" role="form" action="<?=$acao;?>" method="post" id="form_info" name="form_info">
                             <div class="form-group">
                                <label for="inputEmail3" class="col-sm-2 control-label right-aligned">ID</label>
								<div class="col-sm-1">
									<div class="form-group">
										<input disabled type="text" <?=$centralizado;?> class="form-control" id="form_id" name="form_id" value="<?=$rowUsu['id'];?>">
									</div>
								</div>
								<label for="inputEmail3" class="col-sm-2 control-label right-aligned">Ordem</label>
								<div class="col-sm-1">
									<div class="form-group">
										<input type="text" <?=$centralizado;?> class="form-control" id="form_ordem" name="form_ordem" value="<?=$rowUsu['ordem'];?>">
									</div>
								</div>
							</div>
                            <div class="form-group">
                                 <label class="col-md-2 control-label">Pai</label>
                                 <div class="col-sm-6">
                                 <select name="form_pai" id="form_pai" class="form-control">
                                         <option value=""></option>
                                         <?php
                                             $query = "select * from $tabela order by nome";
                                             $res_pai = $conexao->pesquisa($query);
                                             while($row_pai = mysqli_fetch_assoc($res_pai)){
                                             ?>
                                                 <option value="<?=$row_pai['id'];?>"
                                                 <?php
                                                    if($rowUsu['pai']==$row_pai['id']){ echo " selected "; }
                                                 ?>
                                                 ><?=$row_pai['nome'];?></option>
                                             <?php
                                             }
                                         ?>
                                 </select>
                                 </div>
							</div>
                            <div class="form-group">
                                 <label class="col-md-2 control-label">Nome</label>
                                 <div class="col-sm-6">
                                      <input type="text" class="form-control" id="form_nome" name="form_nome" value="<?=$rowUsu['nome'];?>">
                                 </div>
							</div>
							<div class="form-group">
                                 <label class="col-md-2 control-label">Patrocinador?</label>
                                 <div class="col-sm-2">
									 <select name="form_patrocinador" id="form_patrocinador" class="form-control">
											<option value=""></option>                                         
											<option value="S"
											 <?php
												if($rowUsu['patrocinador']=="S"){ echo " selected "; }
											 ?>
											 >Sim</option>
											 <option value="N">Não</option>
									 </select>
                                 </div>
								 <label class="col-md-2 control-label">Site?</label>
                                 <div class="col-sm-2">
									 <select name="form_site" id="form_site" class="form-control">
											<option value=""></option>                                         
											<option value="S"
											 <?php
												if($rowUsu['site']=="S"){ echo " selected "; }
											 ?>
											 >Sim</option>
											 <option value="N" <?php
												if($rowUsu['site']=="N"){ echo " selected "; }
											 ?>>Não</option>
									 </select>
                                 </div>
							</div>
							<div class="form-group">
								<div class="col-sm-12"><center>
                                      <button type="button" class="btn btn-default" onclick="javascript:document.form_info.submit();">  Salvar informações </button>
                                      <button type="button" class="btn btn-default" onclick="javascript:window.location='<?=$pagina;?>';">  Novo cadastro </button>
									  <button type="button" class="btn btn-default" onclick="javascript:duplicar('<?=$id;?>')">  Duplicar </button>
                                      <button type="button" class="btn btn-default" onclick="javascript:window.location='<?=$pagina.'?'.gera_parametro("act", "E").'&id='.$id?>';">  Excluir </button>
                                      <button type="button" class="btn btn-default" onclick="javascript:window.location='<?=$pagina;?>?<?=gera_parametro("codigo",$id);?>';">  Cancelar  </button>
                                      </center>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<!-- END PAGE CONTENT -->
  <br><br>
		<!-- END CALL-TO-ACTION -->
		<!-- FOOTER -->
		<!-- END FOOTER -->
	</div>
	</div></div></div>
	<!-- END WRAPPER -->
	<!-- JAVASCRIPTS -->
</body>
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
		<script src="assets/global/plugins/jstree/dist/jstree.min.js" type="text/javascript"></script>
        <script src="assets/pages/scripts/ui-tree.min.js" type="text/javascript"></script>
		<script>
			function duplicar(){
				var nome = prompt("Entre com o nome do novo ítem", "");
				if(nome!=null){
					window.location = "<?=$pagina.'?'.gera_parametro("codigo",$id).'&'.gera_parametro("act","DU").'&nome='?>"+nome;					
				}
			}
			var $treeview = $("#tree_1");
				$treeview
				  .on('ready.jstree', function() {
					$treeview.jstree('open_all');
				  });
		</script>
</html>