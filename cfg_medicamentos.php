<?php
	include_once "seguranca.php";
	
	$tabela = "med_medicamentos";
	$pagina = "cfg_medicamentos.php";
	$titulo_pagina = "MEDICAMENTOS";
	$campos = array("nome", "fabricante", "farmaco", "concentracao", "data_de_fabricacao", "lote", "forma_farmaceutica", "data_de_validade");
	$tipos  = array("S", "S", "S", "S", "S", "S", "S", "S");
	$id = $_GET['id'];  
	$act = strtoupper($_GET['act']);
   if($act=='A'){
      $query = "update $tabela set log_usuario = " . $_SESSION['user_id'];
      $sql_campos = "";
      $pega = 1;
      for($i=0;$i<=count($campos)-1;$i++){
          if($pega>0){$sql_campos .= ", ";}else{$pega++;}
          if($tipos[$i]!="COMBO"){
               $sql_campos .= $campos[$i] . " = '" . $_POST['form_'.$campos[$i]] . "'";
          }else{
               $sql_campos .= $campos[$i] . " = '" . nl2br($_POST['form_'.$campos[$i]]) . "'";
          }
      }
      $query .= $sql_campos . " where id = ".$id_campo;
      $conexao->pesquisa($query);
      $msg=1;
  }
  if($act=='I'){
      $sql_campos = "";
      $sql_valores = "";
      $pega = 0;
      for($i=0;$i<=count($campos)-1;$i++){
          if($pega>0){$sql_campos .= ", ";$sql_valores .= ", ";}else{$pega++;}
          $sql_campos  .= $campos[$i];
          if($tipos[$i]!="COMBO"){
              $sql_valores .= "'".$_POST['form_'.$campos[$i]]."'";
          }else{
              $sql_valores .= "'".nl2br($_POST['form_'.$campos[$i]])."'";
          }
      }
	  $query = "insert into $tabela ($sql_campos) values ($sql_valores);";
	  $conexao->pesquisa($query);
      $query = "select max(id) as ult from $tabela";
      $resU = $conexao->pesquisa($query);
      $rowU = mysqli_fetch_assoc($resU);
      $ultimo = $rowU['ult']+1;
	  $id = $ultimo;
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
    <script>
        function deletar(form){
                 var resp = confirm("Tem certeza que deseja DELETAR o formulário?");
                 if(resp == true){
                         window.location = "<?=$pagina;?>?deleta=<?=$id_campo."&".gera_parametro("codigo", $id)."&".gera_parametro("act","D");?>";
                 }
        }        
    </script>
<style>
    .window{
        display:none;
        width:600px;
        height:600px;
        position:absolute;
        left:0;
        top:0;
        background:#FFF;
        z-index:9900;
        padding:10px;
        border-radius:10px;
    }
    #mascara{
        display:none;
        position:absolute;
        left:0;
        top:0;
        z-index:9000;
        background-color:#000;
    }
     .fechar{display:block; text-align:right;}
</style>
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
                                        <h1><?=traduzir("MEDICAMENTOS", $_SESSION['lang']);?> </h1>
                                    </div>
                                </div>
                            </div>
                            <div class="page-content">                                
                                <div class="row">
									<div class="col-md-4">
										<div class="portlet light">
											<div class="portlet-title">
												<div class="caption font-red-sunglo">
													<i class="icon-share font-red-sunglo"></i>
													<span class="caption-subject bold uppercase"> Medicamentos </span>
												</div>													
											</div>
											<div class="portlet-body">
												<div class="scroller" style="height:400px" data-always-visible="1" data-rail-visible="1" data-rail-color="red" data-handle-color="green">
													<ul id="sidebar-nav" class="sidebar-nav">
														<?php
															 $query = "select * from $tabela order by nome";
															 $resUsua = $conexao->pesquisa($query);
															 while($rowUsua = mysqli_fetch_assoc($resUsua)){
																 echo '<li class="list-group-item "><a href="'.$pagina.'?id='.$rowUsua['id'].'">';
																if($id == $rowUsua['id']){ echo "<b>";}
																 echo $rowUsua['nome'].'</a></li>';
																 if($id == $rowUsua['id']){ echo "</b>";}
															 }
														?>
													</ul>
												</div>
											</div>
										</div>
									</div>
									<?php
										if($id>0){
											$query = "select * from $tabela where id = " . $id;
											$res   = $conexao->pesquisa($query);
											$row   = mysqli_fetch_assoc($res);
											$acao = $pagina."?id=".$id."&act=A";
										}else{
											$acao = $pagina."?act=I";
										}
									?>
									<div class="col-md-7" style="background-color:#FFF">
										<div class="portlet light">
											<div class="portlet-title">
												<div class="caption font-red-sunglo">
													<i class="icon-share font-red-sunglo"></i>
													<span class="caption-subject bold uppercase"> Dados cadastrais </span>
												</div>													
											</div>
											<div class="portlet-body form">			
												<form method="post" action="<?=$acao;?>" class="form-horizontal form-bordered form-row-stripped">
													<div class="form-body">
														<div class="form-group">
															<label class="control-label col-md-3">Nome</label>
															<div class="col-md-9">
																<input type="text" placeholder="" class="form-control" name="form_nome" id="form_nome" value="<?=$row['nome'];?>">
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-md-3">Fabricante</label>
															<div class="col-md-9">
																<input type="text" placeholder="" class="form-control" name="form_fabricante" id="form_fabricante" value="<?=$row['fabricante'];?>">
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-md-3">Fármaco</label>
															<div class="col-md-9">
																<input type="text" placeholder="" class="form-control" name="form_farmaco" id="form_farmaco" value="<?=$row['farmaco'];?>">
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-md-3">Concentração</label>
															<div class="col-md-9">
																<input type="text" placeholder="" class="form-control" name="form_concentracao" id="form_concentracao" value="<?=$row['concentracao'];?>">
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-md-3">Data de Fabricação</label>
															<div class="col-md-9">
																<input type="text" placeholder="" class="form-control" name="form_data_de_fabricacao" id="form_data_de_fabricacao" value="<?=$row['data_de_fabricacao'];?>">
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-md-3">Data de Validade</label>
															<div class="col-md-9">
																<input type="text" placeholder="" class="form-control" name="form_data_de_validade" id="form_data_de_validade" value="<?=$row['data_de_validade'];?>">
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-md-3">Lote</label>
															<div class="col-md-9">
																<input type="text" placeholder="" class="form-control" name="form_lote" id="form_lote" value="<?=$row['lote'];?>">
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-md-3">Forma Farmacêutica</label>
															<div class="col-md-9">
																<input type="text" placeholder="" class="form-control" name="form_forma_farmaceutica" id="form_forma_farmaceutica" value="<?=$row['forma_farmaceutica'];?>">
															</div>
														</div>
													</div>
													<div class="form-actions">
														<div class="row">
															<div class="col-md-offset-3 col-md-9" style="text-align:center">
																<button type="submit" class="btn green"><i class="fa fa-check"></i> Salvar</button>
																<button type="button" class="btn green" onclick="document.location = '<?=$pagina;?>';"><i class="fa fa-check"></i> Novo medicamento</button>
															</div>
														</div>
													</div>
												</form>
											</div>
									</div>
                				</div>
                			</div>
                		</div>
					</div>
				</div>
			</div>
		</div>
	</body>	
	<?php
		include "rodape.php";
		include "global_js.php";
	?>
</body>
</html>