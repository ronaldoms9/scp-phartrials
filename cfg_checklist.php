<?php
	include_once "seguranca.php";

	$varConexao = new Conexao();
	$id = $_GET['id'];
	$act = strtoupper($_GET['act']);

	$tabela = "agenda_check";
	$pagina = "cfg_checklist.php";
	$campos = array("label","periodo", "ordem", "nome", "mascara", "projeto", "opcoes", "cobranca", "valor", "exporta", "calculo", "calculo_tipo", "calculo_campos", "valor_minimo", "valor_maximo");

	if($id>0){
		$query = "select * from $tabela where  id = ".$id;
		$res = $conexao->pesquisa($query);
		$row = mysqli_fetch_assoc($resUsu);
	}
	if($act=='A'){
		$query = "update $tabela set log_usuario = " . $_SESSION['user_id'];
		$sql_campos = "";
		$pega = 1;
		for($i=0;$i<=count($campos)-1;$i++){
			if($pega>0){$sql_campos .= ", ";}else{$pega++;}
			$sql_campos .= $campos[$i] . " = '" . nl2br($_POST['form_'.$campos[$i]]) . "'";
		}
		$query .= $sql_campos . " where id = ".$id;
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

	$projeto 	= $_SESSION['projeto'];
	$query 		= "select id from agenda_check_fase where projeto = " . $projeto;
	$resQtd		= $varConexao->pesquisa($query);
	if(mysqli_num_rows($resQtd)==0){$projetoEspecifico = false;}else{$projetoEspecifico=true;}
	echo "TEM ESPECIFICO: " . $projetoEspecifico."<br>";
?>
<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
        <?
			include "global_css.php";
		?>
        <link href="assets/global/plugins/jstree/dist/themes/default/style.min.css" rel="stylesheet" type="text/css" />
		<link href="assets/global/plugins/jstree/dist/themes/default/style.min.css" rel="stylesheet" type="text/css" />
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
                                        <h1>Check list de monitoria</h1>
                                    </div>
                                </div>
                            </div>
                            <div class="page-content">
                                <div class="container-fluid">

									<div class="row">
										<div class="col-md-4 fundo_branco">
										 <div class="portlet-body">
											  <div id="tree_1" class="tree-demo">

												   <ul>
														<li> Ítens do checklist
															<ul>
																<?php

																if(!$projetoEspecifico){
																	$query 		= "select * from agenda_check_fase where projeto = 0 order by id";
																}else{
																	$query 		= "select * from agenda_check_fase where projeto = " . $projeto . " order by id";
																}
																$resFases = $varConexao->pesquisa($query);

																while($rowFases = mysqli_fetch_assoc($resFases)){
																	echo "<li data-jstree='{ \"selected\" : true }'>";
																	echo "<a href='cfg_checklist.php?Y29kaWdv=12'>".$rowFases['item']."</a>";
																	$query = "select * from agenda_check
																						where periodo = '".$rowFases['id']."'
																							and projeto = '".$projeto."' ";
																	$query .= "	 order by ordem";
																	$resItm = $conexao->pesquisa($query);
																	if(mysqli_num_rows($resItm)>0){
																	echo "<ul>";
																		while($rowItm = mysqli_fetch_assoc($resItm)){
																			echo "<li data-jstree='{  }'> <a href='cfg_checklist.php?id=".$rowItm['id']."'>".$rowItm['ordem']. ' - ' .$rowItm['nome']."</a></li>";
																		}
																		echo '</ul>';
																	}
																}
																	?>

																</li>

															</ul>
														</li>
											  </div>
										 </div>
										</div>
										<?
											if($id>0){
												$query = "select * from $tabela where id = " . $id;
												$res   = $conexao->pesquisa($query);
												$row   = mysqli_fetch_assoc($res);
												$acao = $pagina."?id=".$id."&act=A";
											}else{
												$acao = $pagina."?act=I";
											}
										?>
										<div class="col-md-6">
											<div class="portlet-body">
												<div class="portlet-body form fundo_branco">
													<form method="post" action="<?=$acao;?>" class="form-horizontal form-bordered form-row-stripped">
														<div class="form-body">
															<div class="form-group">
																<label class="control-label col-md-3">ID</label>
																<div class="col-md-2">
																	<input disabled type="text" placeholder="" class="form-control" name="form_id" id="form_id" value="<?=$row['id'];?>">
																</div>
															</div>
															<div class="form-group">
																<label class="control-label col-md-3">Ordem</label>
																<div class="col-md-2">
																	<input type="text" placeholder="" class="form-control" name="form_ordem" id="form_ordem" value="<?=$row['ordem'];?>">
																</div>
															</div>
															<div class="form-group">
																<label class="control-label col-md-3">Período</label>
																<div class="col-md-9">
																	<select name="form_periodo" id="form_periodo" class="form-control">
																		<option value=""></option>
																		<?php
																			if(!$projetoEspecifico){
																				?>
																				<option value="1" <?if($row['periodo']=="1"){echo " selected ";}?>>Inicial</option>
																				<option value="2" <?if($row['periodo']=="2"){echo " selected ";}?>>Pré confinamento</option>
																				<option value="3" <?if($row['periodo']=="3"){echo " selected ";}?>>Dieta</option>
																				<option value="4" <?if($row['periodo']=="4"){echo " selected ";}?>>Pós Estudo</option>
																				<?php
																			}else{
																				$query 		= "select * from agenda_check_fase where projeto = " . $projeto;
																				$resFases 	= $varConexao->pesquisa($query);
																				while($rowFases = mysqli_fetch_assoc($resFases)){
																					echo '<option value="'.$rowFases['id'].'" ';
																					if($row['periodo']==$rowFases['id']){echo " selected ";}
																					echo '>'.$rowFases['item'] . '</option>';
																				}
																			}
																		?>
																	</select>
																</div>
															</div>
															<div class="form-group">
																<label class="control-label col-md-3">Nome</label>
																<div class="col-md-9">
																	<input type="text" class="form-control" name="form_nome" id="form_nome" value="<?=$row['nome'];?>">
																</div>
															</div>
															<div class="form-group">
																<label class="control-label col-md-3">Label</label>
																<div class="col-md-9">
																	<input type="text" class="form-control" name="form_label" id="form_label" value="<?=$row['label'];?>">
																</div>
															</div>
															<div class="form-group">
																<label class="control-label col-md-3">Máscara</label>
																<div class="col-md-4">
																	<input type="text" class="form-control" name="form_mascara" id="form_mascara" value="<?=$row['mascara'];?>" style="text-align:center">
																</div>
															</div>
															</div>
															<div class="form-group">
																<label class="control-label col-md-3">Opções</label>
																<div class="col-md-4">
																	<input type="text" class="form-control" name="form_opcoes" id="form_opcoes" value="<?=$row['opcoes'];?>" style="text-align:center">
																</div>
															</div>
															<div class="form-group">
																<label class="control-label col-md-3">Exporta</label>
																<div class="col-md-9">
																	<select name="form_exporta" id="form_exporta" class="form-control">
																		<option value=""></option>
																		<option value="1" <?if($row['exporta']=="1"){echo " selected ";}?>>Sim</option>
																		<option value="0" <?if($row['exporta']=="0"){echo " selected ";}?>>Não</option>
																	</select>
																</div>
															</div>
															<div class="form-group">
																<label class="control-label col-md-3">Cálculo</label>
																<div class="col-md-9">
																	<select name="form_calculo" id="form_calculo" class="form-control">
																		<option value=""></option>
																		<option value="1" <?if($row['calculo']=="1"){echo " selected ";}?>>Sim</option>
																		<option value="0" <?if($row['calculo']=="0"){echo " selected ";}?>>Não</option>
																	</select>
																</div>
															</div>
															<div class="form-group">
																<label class="control-label col-md-3">Tipo</label>
																<div class="col-md-9">
																	<select name="form_calculo_tipo" id="form_calculo_tipo" class="form-control">
																		<option value=""></option>
																		<option value="idade" <?if($row['calculo_tipo']=="idade"){echo " selected ";}?>>Idade</option>
																		<option value="imc" <?if($row['calculo_tipo']=="imc"){echo " selected ";}?>>IMC</option>
																	</select>
																</div>
															</div>
															<div class="form-group">
																<label class="control-label col-md-3">Campos</label>
																<div class="col-md-9">
																	<input type="text" class="form-control" name="form_calculo_campos" id="form_calculo_campos" value="<?=$row['calculo_campos'];?>">
																</div>
															</div>
															<div class="form-group">
																<label class="control-label col-md-3">Valor mínimo</label>
																<div class="col-md-9">
																	<input type="text" class="form-control" name="form_valor_minimo" id="form_valor_minimo" value="<?=$row['valor_minimo'];?>">
																</div>
															</div>
															<div class="form-group">
																<label class="control-label col-md-3">Valor máximo</label>
																<div class="col-md-9">
																	<input type="text" class="form-control" name="form_valor_maximo" id="form_valor_maximo" value="<?=$row['valor_maximo'];?>">
																</div>
															</div>
															<div class="form-group">
																<label class="control-label col-md-3">Projeto</label>
																<div class="col-md-4">
																	<input type="text" class="form-control" name="form_projeto" id="form_projeto" value="<?=$row['projeto'];?>" style="text-align:center">
																</div>
															</div>
															<div class="form-group">
																<label class="control-label col-md-3">Cobrança?</label>
																<div class="col-md-9">
																	<select name="form_cobranca" id="form_cobranca" class="form-control">
																		<option value=""></option>
																		<option value="1" <?if($row['cobranca']=="1"){echo " selected ";}?>>Sim</option>
																	</select>
																</div>
															</div>
															<div class="form-group">
																<label class="control-label col-md-3">Valor</label>
																<div class="col-md-4">
																	<input type="text" class="form-control" name="form_valor" id="form_valor" value="<?=$row['valor'];?>" style="text-align:center">
																</div>
															</div>
														</div>
														<div class="form-actions">
															<div class="row">
																<div class="col-md-offset-3 col-md-9" style="text-align:center">
																	<button type="submit" class="btn green"><i class="fa fa-check"></i> Salvar</button>
																	<button type="button" class="btn green" onclick="document.location = '<?=$pagina;?>';"><i class="fa fa-check"></i> Novo</button>
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
			</div>
		</div>
	</div>
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