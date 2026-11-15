<?
	include_once "seguranca.php";

  $id = $_GET[base64_encode('codigo')];
  $act = strtoupper($_GET[base64_encode('act')]);
  $tabela = "menu_itens";
  $pagina = "cfg_menu.php";
  $campos = array("nome", "pai", "ordem", "link", "mostra", "nova_janela");
  $tipos  = array("S", "S", "S", "S");
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
        <?
			include "global_css.php";
		?>
        <!-- BEGIN PAGE LEVEL PLUGINS -->
		<link href="assets/global/plugins/jstree/dist/themes/default/style.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/jstree/dist/themes/default/style.min.css" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL PLUGINS -->
    <script>
    <?
        if($msg==1){ echo "alert('Ítem atualizado com sucesso');"; }
        if($msg==2){ echo "alert('Ítem cadastrado com sucesso');"; }
        if($msg==3){ echo "alert('Ítem deletado com sucesso');"; }
    ?>
    </script>
</head>
    <body class="page-container-bg-solid page-footer-fixed">
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
                                        <h1><?=traduzir("ÍTENS DO MENU", $_SESSION['lang']);?> </h1>
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
                                            <span><a href="cfg_menu.php"><?=traduzir("Ítens do Menu", $_SESSION['lang']);?></a></span>
                                        </li>
                                    </ul>
				<div class="row fundo_branco" >
                    <div class="col-md-1"></div>
                    <div class="col-md-4 ">
                     <div class="portlet-body">
                          <div id="tree_1" class="tree-demo">
                               <ul>
                                   <li> Dashboard
                                        <ul>
                                            <?
                                                $query = " select * from $tabela where pai < 1 order by ordem ";
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
                                                    <a href="<?=$pagina.'?'.gera_parametro("codigo",$row_lista['id']);?>"><?=$row_lista['ordem'];?> - <?=$row_lista['nome'];?></a>
                                                    <?
                                                         $query = " select * from $tabela where pai = " . $row_lista['id'] . " order by ordem ";
                                                         $res_filho = $conexao->pesquisa($query);
                                                         if(mysqli_num_rows($res_filho)>0){
                                                             echo "<ul>";
                                                             while($row_filho = mysqli_fetch_assoc($res_filho)){
                                                             ?>
                                                                    <li data-jstree='{  }'> <a href="<?=$pagina.'?'.gera_parametro("codigo",$row_filho['id']);?>"><?=$row_filho['ordem'];?> - <?=$row_filho['nome'];?></a>
                                                                    <?
                                                                    $query = "select * from $tabela where pai = " . $row_filho['id'] . " order by ordem ";
                                                                    $res_3_nivel = $conexao->pesquisa($query);
                                                                    if(mysqli_num_rows($res_3_nivel)) {
                                                                    	  echo "<ul>";
                                                                    	  while($row_3_nivel=mysqli_fetch_assoc($res_3_nivel)){
		                                                                 	    ?>
		                                                                 	    <li data-jstree='{  }'> <a href="<?=$pagina.'?'.gera_parametro("codigo",$row_3_nivel['id']);?>"><?=$row_3_nivel['ordem'];?> - <?=$row_3_nivel['nome'];?></a></li>
		                                                                 	    <?
                                                                    	  }
                                                                    	  echo "</ul>";
                                                                    }
                                                                    ?>
                                                                    </li>
                                                             <?}
                                                             ?>
                                                             <?
                                                             echo "</ul>";
                                                            }
                                                    ?>
                                                </li>
                                            <?
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
						<?
                             if($id>0){                   //Atualizacao
                                 $acao = $pagina."?".gera_parametro("codigo",$id)."&".gera_parametro("act","A");
                             }else{$acao = $pagina."?".gera_parametro("act","I");}
						?>
						<br>
						<center><h4>Cadastro de ítens do Menu</h4></center>
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
                                         <?
                                             $query = "select * from $tabela order by nome";
                                             $res_pai = $conexao->pesquisa($query);
                                             while($row_pai = mysqli_fetch_assoc($res_pai)){
                                             ?>
                                                 <option value="<?=$row_pai['id'];?>"
                                                 <?
                                                    if($rowUsu['pai']==$row_pai['id']){ echo " selected "; }
                                                 ?>
                                                 ><?=$row_pai['nome'];?></option>
                                             <?
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
                                 <label class="col-md-2 control-label">Link</label>
                                 <div class="col-sm-6">
                                      <input type="text" class="form-control" id="form_link" name="form_link" value="<?=$rowUsu['link'];?>">
                                 </div>
							</div>
							<div class="form-group">
                                 <label class="col-md-2 control-label">Mostra?</label>
                                 <div class="col-sm-4">
                                      <input type="checkbox" id="form_mostra" name="form_mostra" value="1"
									  <?
									  if($rowUsu['mostra']==1){ echo " checked ";}
									  ?>
									  >
                                 </div>
								 <label class="col-md-2 control-label">Nova Janela?</label>
                                 <div class="col-sm-4">
                                      <input type="checkbox" id="form_nova_janela" name="form_nova_janela" value="1"
									  <?
									  if($rowUsu['nova_janela']==1){ echo " checked ";}
									  ?>
									  >
                                 </div>
							</div>
							<div class="form-group">
								<div class="col-sm-12"><center>
                                      <button type="button" class="btn btn-default" onclick="javascript:document.form_info.submit();">  Salvar informações </button>
                                      <button type="button" class="btn btn-default" onclick="javascript:window.location='<?=$pagina;?>';">  Novo cadastro </button>
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
            <?
                include "rodape.php";
            ?>
        </div>
        <div class="quick-nav-overlay"></div>
       <?
		 include "global_js.php";
	   ?>
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script src="assets/global/plugins/jstree/dist/jstree.min.js" type="text/javascript"></script>
		<script src="assets/pages/scripts/ui-tree.min.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL SCRIPTS -->
<script>
        var $treeview = $("#tree_1");
		$treeview
		  .on('ready.jstree', function() {
			$treeview.jstree('open_all');
		  });
</script>
</html>