<?php
	
	include_once "seguranca.php";
	include_once "class/conexao.class.php";
	
	
	$conexao = new Conexao();
	
	if(isset($_GET[base64_encode('codigo')])){$id = $_GET[base64_encode('codigo')];}else{$id = "";}
	if(isset($_GET[base64_encode('codiga')])){$id_campo = $_GET[base64_encode('codiga')];}else{$id_campo = "";}
	if(isset($_GET['act'])){$act = $_GET['act'];}else{$act="";}

	$tabela = "cfg_exportacoes_tipos_campos";
	$pagina = "cfg_exp_tipos_campos.php";
	$campos = array("campo", "ordem");
	$tipos  = array("S", "S");
	$titulo_pagina = "CAMPOS DOS Exports";


	if(($id_campo<1)||($act=="I")){
		if($id>0){
			if($act!="I"){  		
				$query = "select max(ordem)+1 as maximo from crf_forms_campos where formulario = " . $id;
			}else{
				$query = "select max(ordem)+2 as maximo from crf_forms_campos where formulario = " . $id;
			}
			$resM   = $conexao->pesquisa($query);
			$rowM   = mysqli_fetch_assoc($resM);
			$PROXIMA_ORDEM = $rowM['maximo'];
		}
	}


	if($act=="ADD_CAMPO"){
		$query = "insert into cfg_exportacoes_tipos_campos (formulario, campo) values ($id,'".$_GET['VARIAVEL']."');";
		$conexao->pesquisa($query);
	}
	if($act=="DEL_CAMPO"){
		$query = "delete from cfg_exportacoes_tipos_campos where formulario = " . $id . " and campo = '".$_GET['VARIAVEL']."';";
		$conexao->pesquisa($query);
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
      $query = "select * from $tabela where id = ".$id_campo;
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
    <script>

	function deletar(form){
		var resp = confirm("Tem certeza que deseja DELETAR o formulário?");
		if(resp == true){
		window.location = "<?=$pagina;?>?deleta=<?=$id_campo."&".gera_parametro("codigo", $id)."&".gera_parametro("act","D");?>";
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
		<?php
			if($id>0){
				echo 'window.formulario = '.$id.';';
			}
		?>
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
                                        <h1><?=traduzir("Campos dos tipos de exportações", $_SESSION['lang']);?> </h1>
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
                                            <span><?=traduzir("Campos dos tipos de exportações", $_SESSION['lang']);?></span>
                                        </li>
                                    </ul>

                                </div>
								<div class="row">
									<a href="javascript:tudo();" class="btn">Adicionar todos os campos</a>
								</div>
                                <div class="row">
                                        <div class="col-md-3">
                                            <h4><center>Campos</center></h4>
                                            <fieldset style="height: 600px;overflow-y: scroll;">
                                                <ul id="sidebar-nav" class="sidebar-nav margin-bottom-30px">
                                                    <?php
                                                         $query = "select * from cfg_exportacoes_tipos where projeto = '".$_SESSION['projeto']."' order by ordem, nome";
                                                         $resUsua = $conexao->pesquisa($query);
                                                         while($rowUsua = mysqli_fetch_assoc($resUsua)){
                                                             echo '<li class="list-group-item "><a href="'.$pagina.'?'.gera_parametro("codigo",$rowUsua['id']).'">';
                    													  if($id == $rowUsua['id']){ echo "<b>";}
                                                             echo $rowUsua['ordem'].'- '.$rowUsua['nome'].'</a></li>';
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
												<form class="form-horizontal" role="form" action="<?=$acao;?>" method="post" id="form_info" name="form_info">
                                                <fieldset style="height: 600px;overflow-y: scroll;">
                                                    <ul id="sidebar-nav" class="sidebar-nav margin-bottom-30px">
														<div id="retorno_campos" name="retorno_campos">
															<?php
																 $query = "select * from cfg_exportacoes_tipos_campos where formulario = $id order by id";
																 $resUsua = $conexao->pesquisa($query);
																 while($rowUsua = mysqli_fetch_assoc($resUsua)){
																	 echo '<li class=list-group-item><a href=javascript:DEL_CAMPO("'.$rowUsua['campo'].'")>';                                                                 
																	 echo $rowUsua['campo'].'</a></li>';                        													  
																 }
															?>
														</div>
                            						</ul>
                                               </fieldset>
											   </form>
                        					</div><br><br>
                                            <div class="col-md-6" style="background-color:#FFF">												                        						
												<div class="row">
													<div class="col-md-6" style="background-color:#FFF">	
														
														<fieldset style="height: 500px;overflow-y: scroll;">
															<ul id="sidebar-nav" class="sidebar-nav margin-bottom-30px">
																<?php
																	 $query = "select * from crf_forms where projeto = '".$_SESSION['projeto']."' order by ordem, nome";
																	 $res = $conexao->pesquisa($query);
																	 while($row = mysqli_fetch_assoc($res)){
																		 echo '<li class="list-group-item "><a href="javascript:monta_campos('.$row['id'].')">';
																		 echo $row['ordem'].'- '.$row['nome'].'</a></li>';
																	 }
																?>
															</ul>
													   </fieldset>														
													</div>
													<div class="col-md-6" style="background-color:#FFF">												                        						
														<fieldset style="height: 500px;overflow-y: scroll;">
															<ul id="sidebar-nav" class="sidebar-nav margin-bottom-30px">
																<div id="retorno" name="retorno"></div>
															</ul
														</fieldset>
													</div>
												</div>
												<div class="row"><br><br>												
													<center>sdfdfed</center>
												</div>
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
				</div>
				</div>
            	<?php
					include "rodape.php";
					include "global_js.php";
				?>
</body>
<script>
	function tudo(){
		alert("OI");
	}
	function monta_campos(id){
		if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		document.getElementById("retorno").innerHTML = "<center><img src='imagens/carregando.gif' width='180'></centeR>";
		xmlhttp = new XMLHttpRequest();
		} else {
			// code for IE6, IE5
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				document.getElementById("retorno").innerHTML = this.responseText;
			}
		};
        xmlhttp.open("POST","ajax.php?act=MOSTRA_VARIAVEIS&formulario="+id);
        xmlhttp.send();
	}
	function add_campo(campo){
		if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		//document.getElementById("retorno").innerHTML = "<center><img src='imagens/carregando.gif' width='180'></centeR>";
		xmlhttp = new XMLHttpRequest();
		} else {
			// code for IE6, IE5
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				//monta_campos(window.formulario);
				monta_campos_campos(window.formulario);
			}
		};
		xmlhttp.open("POST","ajax.php?act=ADD_CAMPO_EXPORT&campo="+campo+"&formulario="+window.formulario);
        xmlhttp.send();
	}
	
	function monta_campos_campos(id){
		if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		document.getElementById("retorno_campos").innerHTML = "<center><img src='imagens/carregando.gif' width='180'></centeR>";
		xmlhttp = new XMLHttpRequest();
		} else {
			// code for IE6, IE5
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				document.getElementById("retorno_campos").innerHTML = this.responseText;
				//location.href = "#fim";
				window.location.hash = "fim";
			}
		};
        xmlhttp.open("POST","ajax.php?act=LISTA_CAMPOS_CAMPOS&formulario="+id);
        xmlhttp.send();
	}
	
	
	
	function add(NOME){
		add_campo(NOME);
	}
	function DEL_CAMPO(NOME){
		url = "cfg_exp_tipos_campos.php?Y29kaWdv=<?=$id;?>&VARIAVEL=" + NOME + "&act=DEL_CAMPO";
		window.location = url;
	}
</script>
</html>
