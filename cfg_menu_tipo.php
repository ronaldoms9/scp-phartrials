<?
	include_once "seguranca.php";
	
	$id = $_GET[base64_encode('codigo')];
	$act = strtoupper($_GET[base64_encode('act')]);
	$tabela = "menu_itens";
	$pagina = "cfg_menu_tipo.php";
	$campos = array("acessa", "edita", "deleta");
	if($id>0){
		$query = "select * from $tabela where  id = ".$id;
		$resUsu = $conexao->pesquisa($query);
		$rowUsu = mysqli_fetch_assoc($resUsu);
	}
?>
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
    function mudou(id, acao, campo){
             if(campo.checked == true){
               resp = "ON";
             }else{
               resp = "OFF";
             }
             if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
                } else {
                    // code for IE6, IE5
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        //document.getElementById("resposta").innerHTML = this.responseText;
                    }
             };
            xmlhttp.open("POST","ajax.php?act=opc1&menu=<?=$id;?>&usuario="+id+"&resp="+resp+"&campo="+acao);
            xmlhttp.send();
    }
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
                                        <h1><?=traduzir("TIPOS DE USUÁRIO -> MENU", $_SESSION['lang']);?> </h1>
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
                                            <span><a href="cfg_menu_tipo.php"><?=traduzir("Tipos de usuário -> Menu", $_SESSION['lang']);?></a></span>
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
                                                $query = " select * from $tabela where pai < 1 ";
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
                                                    <?
                                                         $query = " select * from $tabela where pai = " . $row_lista['id'];
                                                         $res_filho = $conexao->pesquisa($query);
                                                         if(mysqli_num_rows($res_filho)>0){
                                                             echo "<ul>";
                                                             while($row_filho = mysqli_fetch_assoc($res_filho)){
                                                             ?>
                                                                    <li data-jstree='{  }'> <a href="<?=$pagina.'?'.gera_parametro("codigo",$row_filho['id']);?>"><?=$row_filho['nome'];?></a>
                                                                    <?
                                                                    $query = "select * from $tabela where pai = " . $row_filho['id'];
                                                                    $res_3_nivel = $conexao->pesquisa($query);
                                                                    if(mysqli_num_rows($res_3_nivel)) {
                                                                    	  echo "<ul>";
                                                                    	  while($row_3_nivel=mysqli_fetch_assoc($res_3_nivel)){
		                                                                 	    ?>
		                                                                 	    <li data-jstree='{  }'> <a href="<?=$pagina.'?'.gera_parametro("codigo",$row_3_nivel['id']);?>"><?=$row_3_nivel['nome'];?></a></li>
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
                                 $acao = $pagina."?".gera_parametro("codigo",$id)."&".gera_parametro("act","I");
                             }else{$acao = $pagina."?".gera_parametro("act","I");}
                        if($id>0){
						?>
						<br>
                        <div class="portlet-body">
                             <div class="table-scrollable table-scrollable-borderless">
                                  <table class="table table-hover table-light">
                                         <thead>
                                                <tr >
                                                    <th> Tipo de usuário </th>
                                                    <th><center>Acessa?</center></th>
                                                    <th><center>Edita?</center></th>
                                                    <th><center>Deleta?</th>
                                                </tr>
                                                <?
                                                  $query = "select id, nome from usu_usuario_tipo order by nome";
                                                  $res   = $conexao->pesquisa($query);
                                                  while($row=mysqli_fetch_assoc($res)){
                                                  ?>
                                                      <?
                                                         $query = "select * from usu_usuario_tipo_menu where id_usuario = " . $row['id'] . " and id_menu = $id";
                                                         $res_usu = $conexao->pesquisa($query);
                                                         $row_usu = mysqli_fetch_assoc($res_usu);
                                                      ?>
                                                      <tr>
                                                          <td><?=$row['nome'];?></td>
                                                          <td><center><input onchange="mudou(<?=$row['id'];?>, 'acessa', this);" type="checkbox" id="form_<?=$row['id'];?>_acessa" name="form_<?=$row['id'];?>_acessa" <?if(strtoupper($row_usu['acessa'])=="ON"){ echo " checked "; }?>></center></td>
                                                          <td><center><input onchange="mudou(<?=$row['id'];?>, 'edita', this);" type="checkbox" id="form_<?=$row['id'];?>_edita" name="form_<?=$row['id'];?>_edita" <?if(strtoupper($row_usu['edita'])=="ON"){ echo " checked "; }?>></center></td>
                                                          <td><center><input onchange="mudou(<?=$row['id'];?>, 'deleta', this);" type="checkbox" id="form_<?=$row['id'];?>_deleta" name="form_<?=$row['id'];?>_deleta" <?if(strtoupper($row_usu['deleta'])=="ON"){ echo " checked "; }?>></center></td>
                                                      </tr>
                                                  <?
                                                  }
                                                ?>
                                         </thead>
                                  </table>
                             </div>
                        </div>
                      <?
                      }
                      ?>
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
        <script src="assets/global/plugins/jstree/dist/jstree.min.js" type="text/javascript"></script>
		<script src="assets/pages/scripts/ui-tree.min.js" type="text/javascript"></script>
<script>
        var $treeview = $("#tree_1");
$treeview
  .on('ready.jstree', function() {
    $treeview.jstree('open_all');
  });
</script>
</html>