<?
	include_once "seguranca.php";

	$id = $_GET[base64_encode('codigo')];
	$act = strtoupper($_GET[base64_encode('act')]);

	$tabela = "cfg_visitas";
	$pagina = "cfg_visitas.php";
	$campos = array("botao_salvar", "nome", "descricao", "ordem", "tipo", "acao", "pagina", "visita", "texto_crf", "texto_crf_en");

  if($act=='A'){
      $query = "update $tabela set log_usuario = " . $_SESSION['user_id'];
      $sql_campos = "";
      $pega = 1;
      for($i=0;$i<=count($campos)-1;$i++){
          if($pega>0){$sql_campos .= ", ";}else{$pega++;}
          $sql_campos .= $campos[$i] . " = '" . $_POST['form_'.$campos[$i]] . "'";
      }
      $query .= $sql_campos . " where id = ".$id;
      $conexao->pesquisa($query);
      $msg=1;
  }
  if($act=='D'){
      $conexao->pesquisa("update  $tabela set registro_valido = 0, log_usuario = ".$_SESSION['user_id']." where id = ". $id);
      $msg=3;
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

      $query = "insert into $tabela ($sql_campos, log_usuario) values ($sql_valores,".$_SESSION['user_id'].");";
	  //echo $query;
      $conexao->pesquisa($query);
      $msg=2;
  }

 if($id>0){
      $query = "select * from $tabela where registro_valido = 1 and id = ".$id;
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
    <script>
        function deletar(){
			var resp = confirm("Tem certeza que deseja DELETAR o formulário?");
				if(resp == true){
				window.location = "<?=$pagina;?>?<?=gera_parametro("codigo", $id)."&".gera_parametro("act","D");?>";
			}
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
                                        <h1><?=traduzir("ÍTENS DA MONITORIA", $_SESSION['lang']);?> </h1>
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
                                            <span><?=traduzir("Formulários do projeto", $_SESSION['lang']);?></span>
                                        </li>
                                    </ul>

                                </div>
				<div class="row">
					<div class="col-md-3">
                        <fieldset style="height: 400px;overflow-y: scroll;">
                            <ul id="sidebar-nav" class="sidebar-nav margin-bottom-30px">
                                <?
                                     $query = "select
                                                $tabela.*
                                                    from $tabela
                                                        inner join cfg_visitas_tipo on $tabela.tipo = cfg_visitas_tipo.tipo
                                                    where registro_valido = 1
                                                        and cfg_visitas_tipo.ativo = 1
                                                             order by $tabela.tipo, ordem , nome";
									 $resUsua = $conexao->pesquisa($query);
                                     while($rowUsua = mysqli_fetch_assoc($resUsua)){
                                         echo '<li class="list-group-item "><a href="'.$pagina.'?'.gera_parametro("codigo",$rowUsua['id']).'">['.strtoupper($rowUsua['tipo']).'] - '.$rowUsua['ordem'].'- '.$rowUsua['nome'].'</a></li>';
                                     }
                                ?>
    						</ul>
                       </fieldset>
					</div>
                    <div class="col-md-8" style="background-color:#FFF">
						<?
                             if($id>0){                   //Atualizacao
                                 $acao = $pagina."?".gera_parametro("codigo",$id)."&".gera_parametro("act","A");
                             }else{$acao = $pagina."?".gera_parametro("act","I");}
						?>
						<br>
						<form class="form-horizontal" role="form" action="<?=$acao;?>" method="post" id="form_info" name="form_info">
                            <div class="form-group">
                                 <label class="col-md-2 control-label">Nome</label>
                                 <div class="col-sm-5">
                                      <input type="text" class="form-control" id="form_nome" name="form_nome" value="<?=$rowUsu['nome'];?>">
                                 </div>
                                 <label class="col-md-1 control-label">Ordem</label>
                                 <div class="col-sm-2">
                                      <input type="text" class="form-control" id="form_ordem" name="form_ordem" value="<?=$rowUsu['ordem'];?>">
                                 </div>
									</div>

								<div class="form-group">
									<label class="col-md-2 control-label">Texto no CRF</label>
									<div class="col-sm-8">
										<input type="text" class="form-control" id="form_texto_crf" name="form_texto_crf" value="<?=$rowUsu['texto_crf'];?>">
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-2 control-label">Texto no CRF EN</label>
									<div class="col-sm-8">
										<input type="text" class="form-control" id="form_texto_crf_en" name="form_texto_crf_en" value="<?=$rowUsu['texto_crf_en'];?>">
									</div>
								</div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Descrição</label>
								<div class="col-sm-8">
									<textarea class="form-control" id="form_descricao" name="form_descricao" rows="5" cols="30"><?=$rowUsu['descricao'];?></textarea>
								</div>
							</div>
							<div class="form-group">
                                   <label class="col-md-2 control-label">Tipo</label>
                                   <div class="col-sm-3">
                                       <select class="form-control" name="form_tipo" id="form_tipo" onchange="mudou(this);">
                                              <option value=""></option>
											  <?
											  $query = "select * from cfg_visitas_tipo where ativo = 1";
											  $resTipo   = $conexao->pesquisa($query);
											  while($rowTipo = mysqli_fetch_assoc($resTipo)){
												echo '<option value="'.$rowTipo['tipo'].'" ';
												if($rowUsu['tipo']==$rowTipo['tipo']){echo " selected ";}
												echo '>'.$rowTipo['tipo'].'</option>';
											  }
											  ?>
                                      </select>
                                   </div>

                                   <label class="col-md-2 control-label">Botão salvar?</label>
                                   <div class="col-sm-2">
                                       <input type="checkbox" id="form_botao_salvar" name="form_botao_salvar" value="1" <? if($rowUsu['botao_salvar']=="1"){echo " checked ";}?>>
                                   </div>
							</div>
                            <div class="form-group">
							       <label class="col-md-2 control-label">Ação</label>
                                   <div class="col-sm-2">
                                       <select class="form-control" name="form_acao" id="form_acao" onchange="mudou(this);">
                                              <option value=""></option>
                                              <option value="CAMPOS" <?if($rowUsu['acao']=="CAMPOS"){echo " selected ";}?>>CAMPOS</option>
                                              <option value="PAGINA" <?if($rowUsu['acao']=="PAGINA"){echo " selected ";}?>>PAGINA</option>
											  <option value="CABECALHO" <?if($rowUsu['acao']=="CABECALHO"){echo " selected ";}?>>CABECALHO</option>
											  <option value="PAGINA_BRANCO" <?if($rowUsu['acao']=="PAGINA_BRANCO"){echo " selected ";}?>>PAGINA EM BRANCO</option>
											  <option value="TABELA" <?if($rowUsu['acao']=="TABELA"){echo " selected ";}?>>TABELA</option>
                                      </select>
                                   </div>
                                   <div class="form-group">
                                         <label class="col-md-2 control-label">Página/Colunas</label>
                                         <div class="col-sm-4">
                                              <input type="text" class="form-control" id="form_pagina" name="form_pagina" value="<?=$rowUsu['pagina'];?>">
                                         </div>
        							</div>
                            </div>

                            <br><br>
							<div class="form-group">
								<div class="col-sm-12"><center>
                                      <button type="button" class="btn btn-default" onclick="javascript:document.form_info.submit();">  Salvar informações </button>
                                      <button type="button" class="btn btn-default" onclick="javascript:window.location='<?=$pagina;?>';">  Novo formulário </button>
                                      <button type="button" class="btn btn-default" onclick="javascript:deletar();">  Deletar formulário  </button>
                                      <button type="button" class="btn btn-default" onclick="javascript:window.location='<?=$pagina;?>?<?=gera_parametro("codigo",$id);?>';">  Cancelar  </button>
                                      </center>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		</div></div></div>
            <?
                include "rodape.php";
            ?>
        </div>
       <?
		  include "global_js.php";
	   ?>
</body>
</html>

