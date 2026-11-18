<?php
	include_once "seguranca.php";

	$id_usuario = $_SESSION['user_id'];
	$id = $_GET[base64_encode('codigo')];
	$id_campo = $_GET[base64_encode('codiga')];

	$act = strtoupper($_GET[base64_encode('act')]);

	$tabela = "cfg_visitas_campos";
	$pagina = "cfg_visitas_campos.php";


	$campos = array("so_leitura", "texto_en", "botao_enabled", "pagina_altura", "select_falso", "condicional_regra_alerta", "condicional_alerta", "condicional_vermelho", "tipo_campo", "data_menor_termo", "so_letra", "data_dezoito_anos", "data_menor_hoje", "so_numero", "nome", "texto", "ordem", "tipo", "numero_min", "numero_max", "selecao_valores","formato", "calculo_tipo", "calculo_campo1", "calculo_campo2", "obrigatorio", "legenda", "condicional", "botao_acao", "pagina", "termo", "data_menor_termo_alerta", "qtd_decimal", "numeracao", "campo_preenchido", "valor_padrao", "imprime");
    $tipos  = array("S", "S", "S", "S", "S", "COMBO", "S", "S", "S", "S", "S", "S", "S", "S", "S", "S", "I", "S", "S", "S", "COMBO","S", "S", "S", "S", "S", "S", "S", "S", "S", "S", "S", "S", "S", "S", "S", "S");
	$titulo_pagina = "CAMPOS DOS FORMULÁRIOS";

	if($_GET['clo']>0){      //É para clonar
       $query = "select * from cfg_visitas_campos where formulario = " . $_GET['clo'];
       $resClo = $conexao->pesquisa($query);
       while($rowClo=mysqli_fetch_assoc($resClo)){
           $query = "insert into cfg_visitas_campos ( texto, texto_en, ordem, tipo, selecao_valores, formato, legenda, formulario )
							values (
										'".$rowClo['texto']."',
										'".$rowClo['texto_en']."',
										'".$rowClo['ordem']."',
										'".$rowClo['tipo']."',
										'".$rowClo['selecao_valores']."',
										'".$rowClo['formato']."',
										'".$rowClo['legenda']."',
										'$id'
							);";

            $conexao->pesquisa($query);
       }

	}

  if(($id_campo<1)||($act=="I")){
  		if($act!="I"){
			$query = "select max(ordem)+1 as maximo from cfg_visitas_campos where formulario = " . $id;
		}else{
			$query = "select max(ordem)+2 as maximo from cfg_visitas_campos where formulario = " . $id;
		}
		$resM   = $conexao->pesquisa($query);
		$rowM   = mysqli_fetch_assoc($resM);
		$PROXIMA_ORDEM = $rowM['maximo'];
  }



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
  if($act=='D'){
      $conexao->pesquisa("delete from $tabela where id = ". $_GET['deleta']);
      $msg=3;
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
      $query = "select max(id) as ult from $tabela";
      $resU = $conexao->pesquisa($query);
      $rowU = mysqli_fetch_assoc($resU);
      $ultimo = $rowU['ult']+1;

      $query = "insert into $tabela ($sql_campos, log_usuario, formulario) values ($sql_valores,".$_SESSION['user_id'].",$id);";
	  $conexao->pesquisa($query);
      $msg=2;
  }

 if($id_campo>0){
      $query = "select * from $tabela where registro_valido = 1 and id = ".$id_campo;
      $resUsu = $conexao->pesquisa($query);
      $rowUsu = mysqli_fetch_assoc($resUsu);
      $id = $rowUsu['formulario'];
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
	<style>
		.lista_formulario{
			font-size: 11px;
		}
	</style>
    <script>
        function deletar(form){
                 var resp = confirm("Tem certeza que deseja DELETAR o formulário?");
                 if(resp == true){
                         window.location = "<?=$pagina;?>?deleta=<?=$id_campo."&".gera_parametro("codigo", $id)."&".gera_parametro("act","D");?>";
                 }
        }
        function mudou(campo){
                 var divSelecao = document.getElementById("div_numero");
                 divSelecao.style.display = 'none';
                 var divSelecao = document.getElementById("div_selecao");
                 divSelecao.style.display = 'none';
                 var divSelecao = document.getElementById("div_calculo");
                 divSelecao.style.display = 'none';
                 var divSelecao = document.getElementById("div_data");
                 divSelecao.style.display = 'none';

                 if(campo.value=="numero"){
                        var divSelecao = document.getElementById("div_numero");
                        divSelecao.style.display = 'block';
                    }
                 if((tipo_resposta.value=="select")||(tipo_resposta.value=="checkbox")||(tipo_resposta.value=="select_coluna")||(tipo_resposta.value=="json")||(tipo_resposta.value=="json_vertical")){
                        var divSelecao = document.getElementById("div_selecao");
                        divSelecao.style.display = 'block';
                 }
                 if(campo.value=="calculo"){
                        var divSelecao = document.getElementById("div_calculo");
                        divSelecao.style.display = 'block';
                 }
                 if(campo.value=="botao"){
                        var divSelecao = document.getElementById("div_botao");
                        divSelecao.style.display = 'block';
                 }
                 if(campo.value=="pagina"){
                        var divSelecao = document.getElementById("div_pagina");
                        divSelecao.style.display = 'block';
                 }
                 if(campo.value=="data"){
                        var divSelecao = document.getElementById("div_data");
                        divSelecao.style.display = 'block';
                 }
                 if(campo.value=="select_falso"){
                 			var divSelecao = document.getElementById("div_select_falso");
                        divSelecao.style.display = 'block';
              	  }
        }


        function clonar_campo(entrada){
              pag = '<?=substr($_SERVER['REQUEST_URI'],1,200);?>';
              pag = pag + '&clone_campo=1&id='+entrada;
              var resp = prompt("Qual a 'ordem' do novo campo?", "0");
              if(resp!="0"){
                  document.location = pag+"&novo_campo="+resp;
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
<div class="window" id="janela1">
    <a href="#" class="fechar">X Fechar</a>
    <h4>Formulários</h4>
    <hr>
    <div class="panel-centro" style="overflow-y: scroll;height:500" >
    <?php
         $query = "select * from cfg_visitas where registro_valido = 1 order by tipo, ordem, nome";

         $resUsua = $conexao->pesquisa($query);
         while($rowUsua = mysqli_fetch_assoc($resUsua)){
             echo '<a href="'.$pagina.'?'.gera_parametro("codigo",$id).'&clo='.$rowUsua['id'].'">['.$rowUsua['id'].'] - ('.$rowUsua['tipo'].') '.$rowUsua['ordem'].'- '.$rowUsua['nome'].'</a><br>';
         }
    ?>
    </div>
</div>
<div id="mascara"></div>

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
                                        <h1><?=traduzir("Campos dos formulários", $_SESSION['lang']);?> </h1>
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
                                            <span><?=traduzir("Campos dos formulários", $_SESSION['lang']);?></span>
                                        </li>
                                    </ul>

                                </div>
                                <div class="row">
                                        <div class="col-md-3">
                                            <h4><center>Formulários</center></h4>
                                            <fieldset style="height: 600px;overflow-y: scroll;">
                                                <ul id="sidebar-nav" class="sidebar-nav margin-bottom-30px">
                                                    <?php
                                                         //$query = "select * from cfg_visitas where registro_valido = 1 order by tipo, cast(ordem as int), nome";
														 $query = "select cfg_visitas.*
																	from cfg_visitas
																		inner join cfg_visitas_tipo on cfg_visitas.tipo = cfg_visitas_tipo.tipo
																			where registro_valido = 1
																				and cfg_visitas_tipo.ativo = 1
																		order by cfg_visitas.tipo, ordem , nome";
														 $resUsua = $conexao->pesquisa($query);
                                                         while($rowUsua = mysqli_fetch_assoc($resUsua)){
                                                             echo '<li class="list-group-item lista_formulario"><a href="'.$pagina.'?'.gera_parametro("codigo",$rowUsua['id']).'">';
                    													  if($id == $rowUsua['id']){ echo "<b>";}
                                                             echo '['.$rowUsua['id'].'] - ('.strtoupper($rowUsua['tipo']).') ' . $rowUsua['ordem'].'- '.$rowUsua['nome'].'</a></li>';
                                                             if($id == $rowUsua['id']){ echo "</b>";}
                                                         }
                                                    ?>
                        						</ul>
                                           </fieldset>
                    					</div>
                                            <?php
                                            if($id>0){
                                            ?>
                        					<div class="col-md-3">
                                                <h4><center>Campos do formulário</center></h4>
                                                <fieldset style="height: 600px;overflow-y: scroll;">
                                                    <ul id="sidebar-nav" class="sidebar-nav margin-bottom-30px">
                                                        <?php
                                                             $query = "select * from $tabela where formulario = $id and registro_valido = 1 order by ordem";
                                                             $resUsua = $conexao->pesquisa($query);
                                                             $iCampo = 1;
                                                             while($rowUsua = mysqli_fetch_assoc($resUsua)){
                                                                echo '<li class="list-group-item "><a href="'.$pagina.'?'.gera_parametro("codigo",$rowUsua['id']).'&'.gera_parametro("codiga",$rowUsua['id']).'">';
                                                                if($id_campo==$rowUsua['id']){ echo "<b>";}
                                                                if($rowUsua['tipo']!="divisao"){
                                                                    echo '['.$iCampo.'] - ';
                                                                }
                                                                 echo $rowUsua['ordem'].'- [' . $rowUsua['tipo'] .'] ' . $rowUsua['texto'].'</a></li>';
                        													  if($id_campo==$rowUsua['id']){ echo "</b>";}
                                                                if($rowUsua['tipo']!="divisao"){
                                                                    $iCampo++;
                                                                }
                                                             }
                                                        ?>
                            						</ul>
                                               </fieldset>
                        					</div><br><br>
                                            <div class="col-md-6" style="background-color:#FFF">
                        						<?php
                                                     if($id_campo>0){                   //Atualizacao
                                                         $acao = $pagina."?".gera_parametro("codiga",$id_campo)."&".gera_parametro("act","A");
                                                     }else{$acao = $pagina."?".gera_parametro("codigo",$id)."&".gera_parametro("act","I");}
                        						?>
                        						<br>
                        						<form class="form-horizontal" role="form" action="<?=$acao;?>" method="post" id="form_info" name="form_info">
                                                    <div class="form-group">
                                                         <label class="col-md-2 control-label">ID</label>
                                                         <div class="col-sm-2">
                                                              <input disabled title="Identificador do campo" <?=$centralizado;?> type="text" class="form-control" id="form_id" name="form_id" value="<?=$rowUsu['id'];?>">
                                                         </div>
                                                         <label class="col-md-1 control-label">Ordem</label>
                                                         <div class="col-sm-2">
                                                              <input <?=$centralizado;?> title="A ordem em que o campo será exibido no formulário" type="text" class="form-control" id="form_ordem" name="form_ordem" value="<?=$rowUsu['ordem'].$PROXIMA_ORDEM;?>">
                                                         </div>

                        				   						<div class="form-group">
                        				                                 <label class="col-md-1 control-label">Tipo</label>
                        				                                 <div class="col-sm-3">
                        				                                      <select title="Tipo de campo. A formatação na tela será exibida de acordo com a opção selecionada." class="form-control" name="form_tipo" id="form_tipo" onchange="mudou(this);">
                        				                                              <option value=""></option>
                                                                                      <option value="botao" <?php if($rowUsu['tipo']=="botao"){echo " selected ";}?>>Botão</option>
                                                                                      <option value="select" <?php if($rowUsu['tipo']=="select"){echo " selected ";}?>>Caixa de seleção</option>
																					  <option value="select_coluna" <?php if($rowUsu['tipo']=="select_coluna"){echo " selected ";}?>>Caixa de seleção (Coluna)</option>
                        												              <option value="select_falso" <?php if($rowUsu['tipo']=="select_falso"){echo " selected ";}?>>Select falso</option>
                        				                                              <option value="caixa" <?php if($rowUsu['tipo']=="caixa"){echo " selected ";}?>>Caixa de texto</option>
                        				                                              <option value="calculo" <?php if($rowUsu['tipo']=="calculo"){echo " selected ";}?>>Calculo</option>
                                                                                      <option value="checkbox" <?php if($rowUsu['tipo']=="checkbox"){echo " selected ";}?>>Check-Box</option>
                                                                                      <option value="data" <?php if($rowUsu['tipo']=="data"){echo " selected ";}?>>Data</option>
                                                                                      <option value="divisao" <?php if($rowUsu['tipo']=="divisao"){echo " selected ";}?>>Divisão de campo</option>
                                                                                      <option value="escondido" <?php if($rowUsu['tipo']=="escondido"){echo " selected ";}?>>Escondido</option>
                                                                                      <option value="fim_agrupamento" <?php if($rowUsu['tipo']=="fim_agrupamento"){echo " selected ";}?>>Fim Agrupamento</option>
                                                                                      <option value="inicio_agrupamento" <?php if($rowUsu['tipo']=="inicio_agrupamento"){echo " selected ";}?>>Início Agrupamento</option>
                                                                                      <option value="numero"  <?php if($rowUsu['tipo']=="numero"){echo " selected ";}?>>Número</option>
                                                                                      <option value="pagina" <?php if($rowUsu['tipo']=="pagina"){echo " selected ";}?>>Pagina</option>
                                                                                      <option value="texto" <?php if($rowUsu['tipo']=="texto"){echo " selected ";}?>>Texto</option>
																					  <option value="textoHTML" <?php if($rowUsu['tipo']=="textoHTML"){echo " selected ";}?>>Texto HTML</option>
																					  <option value="json" <?php if($rowUsu['tipo']=="json"){echo " selected ";}?>>JSON</option>
																					  <option value="json_vertical" <?php if($rowUsu['tipo']=="json_vertical"){echo " selected ";}?>>JSON VERTICAL</option>
																					  <option value="quebra" <?php if($rowUsu['tipo']=="quebra"){echo " selected ";}?>>Quebra pagina</option>
                                                                                      <option value="esp_tratamento" <?php if($rowUsu['tipo']=="esp_tratamento"){echo " selected ";}?>>Especial - Tratamento</option>
                        				                                      </select>
                        				                                 </div>
                        											</div>
                        							       </div>
                                                    <div class="form-group">
                                                         <label class="col-md-2 control-label">Variável</label>
                                                         <div class="col-sm-4">
                                                              <input title="Nome do campo" type="text" class="form-control" id="form_nome" name="form_nome" value="<?=$rowUsu['nome'];?>">
                                                         </div>
                        											<label class="col-md-2 control-label">Numeração</label>
                                                         <div class="col-sm-3">
                                                              <input title="Nome do campo" type="text" class="form-control" id="form_numeracao" name="form_numeracao" value="<?=$rowUsu['numeracao'];?>" <?=$centralizado;?>>
                                                         </div>
                        							</div>
                                                    <div class="form-group">
                                                         <label class="col-md-2 control-label">Campo PréPreenchido</label>
                                                         <div class="col-sm-9">
                                                              <input title="Nome do campo" type="text" class="form-control" id="form_campo_preenchido" name="form_campo_preenchido" value="<?=$rowUsu['campo_preenchido'];?>">
                                                         </div>
                        							</div>
                                                    <div class="form-group">
                                                         <label class="col-md-2 control-label">Valor Padrão</label>
                                                         <div class="col-sm-9">
                                                            <textarea title="Nome do campo" class="form-control" id="form_valor_padrao" name="form_valor_padrao" rows="5" cols="30"><?=$rowUsu['valor_padrao'];?></textarea>
                                                         </div>
                        							</div>
                                                    <div class="form-group">
                                                        <label class="col-md-2 control-label">Texto no CRF</label>
													    <div class="col-sm-9">
															<textarea title="Nome que será exibido no CRF" class="form-control" id="form_texto" name="form_texto" rows="5" cols="30"><?=$rowUsu['texto'];?></textarea>
													    </div>
                        							</div>
													<div class="form-group">
                                                        <label class="col-md-2 control-label">Texto no CRF (EN)</label>
													    <div class="col-sm-9">
															<textarea title="Nome que será exibido no CRF" class="form-control" id="form_texto_en" name="form_texto_en" rows="5" cols="30"><?=$rowUsu['texto_en'];?></textarea>
													    </div>
                        							</div>
                        									 <div class="form-group">
                                                         <label class="col-md-2 control-label">Formatação</label>
                                                         <div class="col-sm-2">
                                                              <input title="Formato do campo. Será utilizado a função Mascara" type="text" class="form-control" id="form_formato" name="form_formato" value="<?=$rowUsu['formato'];?>">
                                                         </div>
                        											<label class="col-md-1 control-label">Legenda</label>
                                                         <div class="col-sm-3">
																<textarea class="form-control" id="form_legenda" name="form_legenda" rows="5" cols="30"><?=$rowUsu['legenda'];?></textarea>
                                                         </div>
                                                         <label class="col-md-2 control-label">Qtd Decimal</label>
                                                         <div class="col-sm-1">
                                                              <input title="Quantidade de casas decimais após a vírgula. Caso a quantidade digitada for menor que esse campo, o sistema preenche as casas faltantes com 0" type="text" <?=$centralizado;?> class="form-control" id="form_qtd_decimal" name="form_qtd_decimal" value="<?=$rowUsu['qtd_decimal'];?>">
                                                         </div>
                                                    </div>
                        								<hr>
														<div class="form-group">
                        									<label class="col-md-2 control-label">
																<input title="Se o preenchimento é obrigatório. Caso sim, a borda do campo ficará em vermelho" type="checkbox" id="form_obrigatorio" name="form_obrigatorio" <?php if(strtoupper($rowUsu['obrigatorio'])=="ON"){ echo " checked "; }?>> Obrigatório?
															</label>
															<label class="col-md-2 control-label">
																<input title="Somente irá permitir a entrada de valores numéricos" type="checkbox" id="form_so_numero" name="form_so_numero" <?php if(strtoupper($rowUsu['so_numero'])=="ON"){ echo " checked "; }?>> Só número?
															</label>
															<label class="col-md-2 control-label">
																<input title="Somente irá permitir a entrada de valores alphanuméricos" type="checkbox" id="form_so_letra" name="form_so_letra" <?php if(strtoupper($rowUsu['so_letra'])=="ON"){ echo " checked "; }?>> Só letras?
															</label>
															<label class="col-md-2 control-label">
																<input title="Campo somente leitura" type="checkbox" id="form_so_leitura" name="form_so_leitura" <?php if(strtoupper($rowUsu['so_leitura'])=="ON"){ echo " checked "; }?>> ReadOnly
															</label>
                                                            <label class="col-md-2 control-label">
																<input title="Imprime o campo no relatório?" type="checkbox" id="form_imprime" name="form_imprime" <?php if(strtoupper($rowUsu['imprime'])=="ON"){ echo " checked "; }?>> Imprime?
															</label>
														</div>
                        								<hr>
                        								<div class="form-group">
                                                         <label class="col-md-6 control-label">Condicional (para habilitar o campo)</label>
                                                         <div class="col-sm-5">
                                                              <input title="A condição que precisa ter para habilitar o campo. Pode ser qualquer campo, qualquer formulário" type="text" class="form-control" id="form_condicional" name="form_condicional" value="<?=$rowUsu['condicional'];?>">
                                                         </div>
                        									 </div>
                                                         <div class="form-group">
                                                         <label class="col-md-2 control-label">Condicional (ALERTAS)</label>
                                                         <div class="col-sm-10">
															  <textarea title="Alertas TIPO_DE_CAMPO;CAMPO_COMPARADOR;OPERAÇÃO;MENSAGEM_DE_ALERTA" class="form-control" id="form_condicional_regra_alerta" name="form_condicional_regra_alerta" rows="5" cols="30"><?=str_replace("<br />","",$rowUsu['condicional_regra_alerta']);?></textarea>
                                                         </div>
                        									 </div>
                        									 <hr>
                        									 <div class="form-group">
                        			                                 <label class="col-md-6 control-label">Valor para ficar em vermelho (quando estiver "errado")</label>
                        			                                 <div class="col-sm-5">
                        			                                      <input title="Caso o campo tenha esse valor, ele vai ficar em vermelho escuro." type="text" class="form-control" id="form_condicional_vermelho" name="form_condicional_vermelho" value="<?=$rowUsu['condicional_vermelho'];?>">
                        			                                 </div>
                        									 </div>
                        									 <div class="form-group">
                        			                                 <label class="col-md-6 control-label">Mensagem de alerta em caso "errado"</label>
                        			                                 <div class="col-sm-5">
                        			                                      <input title="Caso o campo esteja errado, vai mostrar essa mensagem" type="text" class="form-control" id="form_condicional_alerta" name="form_condicional_alerta" value="<?=$rowUsu['condicional_alerta'];?>">
                        			                                 </div>
                        									 </div>
                        									 <hr>



                        							<div name="div_data" id="div_data" style="display:none;-moz-border-radius:7px;-webkit-border-radius:7px; border-radius:7px;border: 1px solid #888;" class="form-group">
                                                         <br>
                                                         <label class="col-md-2 control-label"><center>Maior/iguai a Data do Termo?</center></label>
                                                         <div class="col-sm-1" style="vertical-align: middle;valign: middle">
                                                           <input type="checkbox" id="form_termo" name="form_termo" <?php
                                                                           if(strtoupper($rowUsu['termo'])=="ON"){ echo " checked "; }
                                                                         ?>>
                                                         </div>

                                                         <label class="col-md-2 control-label"><center>ALERTA para Maior/iguai a Data do Termo?</center></label>
                                                         <div class="col-sm-1">
                                                           <input type="checkbox" id="form_data_menor_termo_alerta" name="form_data_menor_termo_alerta" <?php
                                                                           if(strtoupper($rowUsu['data_menor_termo_alerta'])=="ON"){ echo " checked "; }
                                                                         ?>>
                                                         </div>

                                                         <label class="col-md-2 control-label"><center>Menor que a Data do Termo?</center></label>
                                                         <div class="col-sm-1">
                                                           <input type="checkbox" id="form_data_menor_termo" name="form_data_menor_termo" <?php
                                                                           if(strtoupper($rowUsu['data_menor_termo'])=="ON"){ echo " checked "; }
                                                                         ?>>
                                                         </div>

                                                         <label class="col-md-2 control-label"><center>Obrigatório igual/menor que hoje?</center></label>
                                                         <div class="col-sm-1">
                                                           <input type="checkbox" id="form_data_menor_hoje" name="form_data_menor_hoje" <?php
                                                                           if(strtoupper($rowUsu['data_menor_hoje'])=="ON"){ echo " checked "; }
                                                                         ?>>
                                                         </div>
                                                         <label class="col-md-2 control-label"><center>Calcula 18 anos?</center></label>
                                                         <div class="col-sm-1">
                                                           <input type="checkbox" id="form_data_dezoito_anos" name="form_data_dezoito_anos" <?php
                                                                           if(strtoupper($rowUsu['data_dezoito_anos'])=="ON"){ echo " checked "; }
                                                                         ?>>
                                                         </div>

                                                         <br><br><br>
                                                    </div>




                                                    <div name="div_calculo" id="div_calculo" style="display:none;-moz-border-radius:7px;-webkit-border-radius:7px; border-radius:7px;border: 1px solid #888;" class="form-group">
                                                         <br>
                                                         <label class="col-md-1 control-label">Tipo</label>
                                                         <div class="col-sm-3">
                                                              <select class="form-control" name="form_calculo_tipo" id="form_calculo_tipo" onchange="">
                                                                      <option value=""></option>
                                                                      <option value="+" <?php if($rowUsu['calculo_tipo']=="+"){echo " selected ";}?>>+</option>
                                                                      <option value="-" <?php if($rowUsu['calculo_tipo']=="-"){echo " selected ";}?>>-</option>
                                                                      <option value="%" <?php if($rowUsu['calculo_tipo']=="%"){echo " selected ";}?>>%</option>
                                                                      <option value="Idade" <?php if($rowUsu['calculo_tipo']=="Idade"){echo " selected ";}?>>Idade</option>
                                                                      <option value="IMC" <?php if($rowUsu['calculo_tipo']=="IMC"){echo " selected ";}?>>IMC</option>
                                                                      <option value="LIKERT" <?php if($rowUsu['calculo_tipo']=="LIKERT"){echo " selected ";}?>>LIKERT</option>
                                                              </select>
                                                         </div>
                                                         <label class="col-md-1 control-label">Campo1</label>
                                                         <div class="col-sm-3">
                                                              <input type="text" <?=$centralizado;?> class="form-control" id="form_calculo_campo1" name="form_calculo_campo1" value="<?=$rowUsu['calculo_campo1'];?>">
                                                         </div>
                                                         <label class="col-md-1 control-label">Campo2</label>
                                                         <div class="col-sm-3">
                                                              <input type="text" <?=$centralizado;?> class="form-control" id="form_calculo_campo2" name="form_calculo_campo2" value="<?=$rowUsu['calculo_campo2'];?>">
                                                         </div>

                                                         <br><br><br>
                                                    </div>

                                                    <div name="div_numero" id="div_numero" style="display:none;-moz-border-radius:7px;-webkit-border-radius:7px; border-radius:7px;border: 1px solid #888;" class="form-group">
                                                         <br>
                                                         <label class="col-md-3 control-label">Valor mínimo</label>
                                                         <div class="col-sm-2">
                                                              <input type="text"<?=$centralizado;?> class="form-control" id="form_numero_min" name="form_numero_min" value="<?=$rowUsu['numero_min'];?>">
                                                         </div>
                                                         <label class="col-md-3 control-label">Valor máximo</label>
                                                         <div class="col-sm-2">
                                                              <input type="text" <?=$centralizado;?> class="form-control" id="form_numero_max" name="form_numero_max" value="<?=$rowUsu['numero_max'];?>">
                                                         </div>


                                                         <br><br><br>
                                                    </div>


                                                    <div name="div_select_falso" id="div_select_falso" style="display:none;-moz-border-radius:7px;-webkit-border-radius:7px; border-radius:7px;border: 1px solid #888;" class="form-group">
                                                         <br>
                                                         <label class="col-md-3 control-label">Valores para select falso</label>
                                                         <div class="col-sm-10">
                                                              <input type="text" class="form-control" id="form_select_falso" name="form_select_falso" value="<?=$rowUsu['select_falso'];?>">
                                                         </div>
                                                         <br><br><br><br><br>
                                                    </div>


                                                    <div name="div_pagina" id="div_pagina" style="display:none;-moz-border-radius:7px;-webkit-border-radius:7px; border-radius:7px;border: 1px solid #888;" class="form-group">
                                                         <br>
                                                         <label class="col-md-3 control-label">Nome da pagina</label>
                                                         <div class="col-sm-3">
                                                              <input type="text"<?=$centralizado;?> class="form-control" id="form_pagina" name="form_pagina" value="<?=$rowUsu['pagina'];?>">
                                                         </div>
                                                         <label class="col-md-1 control-label">Altura</label>
                                                         <div class="col-sm-3">
                                                              <input type="text"<?=$centralizado;?> class="form-control" id="form_pagina_altura" name="form_pagina_altura" value="<?=$rowUsu['pagina_altura'];?>">
                                                         </div>
                                                         <br><br><br>
                                                    </div>


                                                    <div name="div_botao" id="div_botao" style="display:none;-moz-border-radius:7px;-webkit-border-radius:7px; border-radius:7px;border: 1px solid #888;" class="form-group">
                                                         <br>
                                                         <div class="form-group">
                                                             <label class="col-md-3 control-label">Ação</label>
                                                             <div class="col-sm-7">
                                                                  <textarea class="form-control" id="form_botao_acao" name="form_botao_acao"><?=$rowUsu['botao_acao'];?></textarea>
                                                             </div>
                                                         </div>
                                                         <div class="form-group">
                                                             <label class="col-md-3 control-label">Condicional Enabled</label>
                                                             <div class="col-sm-7">
                                                                  <input type="text"<?=$centralizado;?> class="form-control" id="form_botao_enabled" name="form_botao_enabled" value="<?=$rowUsu['botao_enabled'];?>">
                                                             </div>
                                                         </div>
                                                         <br><br><br>
                                                    </div>

                                                    <div name="div_selecao" id="div_selecao" style="display:none;-moz-border-radius:7px;-webkit-border-radius:7px; border-radius:7px;border: 1px solid #888;" class="form-group">
                                                         <br>
                                                         <label class="col-md-3 control-label">Valores aceitos</label>
                                                         <div class="col-sm-2">
                                                              <textarea rows="5" style="width:500px" class="form-control" id="form_selecao_valores" name="form_selecao_valores"><?=str_replace("<br />","",$rowUsu['selecao_valores']);?></textarea>
                                                         </div>
                                                         <br><br><br><br><br><br><br>
                                                    </div>


                        							<div class="form-group">
                        								<div class="col-sm-12"><center>
                                                              <button type="button" class="btn btn-default" onclick="javascript:document.form_info.submit();">  Salvar </button>
                                                              <button type="button" class="btn btn-default" onclick="javascript:window.location='<?=$pagina;?>?<?=gera_parametro("codigo", $id);?>&novo_campo=1';">  Novo campo </button>
                                                              <button type="button" class="btn btn-default">  <a href="#janela1" rel="modal">Clonar formulário</a></button>
                                                              <button type="button" class="btn btn-default">  <a href="javascript:clonar_campo(<?=$id_campo;?>);">Clonar campo</a></button>
                                                              <button type="button" class="btn btn-default" onclick="javascript:deletar(<?=$id;?>);">  Deletar campo  </button>
                                                              <button type="button" class="btn btn-default" onclick="javascript:window.location='<?=$pagina;?>?<?=gera_parametro("codigo",$id);?>';">  Cancelar  </button>
                                                              </center>
                        								</div>
                        							</div>
                        						</form>
                        					</div>
                        					<?php
                        					} //Fecho o if se tiver formulário selecionado
                        					?>
                				</div>
                			</div>
                		</div>
                  <br><br>
            	</div>
				</div>
				</DIV>
				</DIV>
				</DIV>
            	<?php
					include "rodape.php";
					include "global_js.php";
				?>
        	<script>

      </script>
        <script>
                var tipo_resposta = document.getElementById("form_tipo");
                if(tipo_resposta.value=="numero"){
                var divSelecao = document.getElementById("div_numero");
                divSelecao.style.display = 'block';
            }
            if((tipo_resposta.value=="select")||(tipo_resposta.value=="checkbox")||(tipo_resposta.value=="select_coluna")||(tipo_resposta.value=="json")){
                var divSelecao = document.getElementById("div_selecao");
                divSelecao.style.display = 'block';
            }
            if(tipo_resposta.value=="calculo"){
                var divSelecao = document.getElementById("div_calculo");
                divSelecao.style.display = 'block';
            }
            if(tipo_resposta.value=="botao"){
                var divSelecao = document.getElementById("div_botao");
                divSelecao.style.display = 'block';
            }
            if(tipo_resposta.value=="escondido"){
                var divSelecao = document.getElementById("div_botao");
                divSelecao.style.display = 'block';
            }
            if(tipo_resposta.value=="pagina"){
                var divSelecao = document.getElementById("div_pagina");
                divSelecao.style.display = 'block';
            }
            if(tipo_resposta.value=="data"){
                var divSelecao = document.getElementById("div_data");
                divSelecao.style.display = 'block';
            }
            if(tipo_resposta.value=="select_falso"){
                 			var divSelecao = document.getElementById("div_select_falso");
                        divSelecao.style.display = 'block';
              	  }

				    $(document).ready(function(){
						$("a[rel=modal]").click( function(ev){
							ev.preventDefault();

							var id = $(this).attr("href");

							var alturaTela = $(document).height();
							var larguraTela = $(window).width();

							//colocando o fundo preto
							$('#mascara').css({'width':larguraTela,'height':alturaTela});
							$('#mascara').fadeIn(1000);
							$('#mascara').fadeTo("slow",0.8);

							var left = ($(window).width() /2) - ( $(id).width() / 2 );
							var top = ($(window).height() / 2) - ( $(id).height() / 2 );

							$(id).css({'top':top,'left':left});
							$(id).show();
						});

						$("#mascara").click( function(){
							$(this).hide();
							$(".window").hide();
						});

						$('.fechar').click(function(ev){
							ev.preventDefault();
							$("#mascara").hide();
							$(".window").hide();
						});
					});


        </script>
</body>

</html>
